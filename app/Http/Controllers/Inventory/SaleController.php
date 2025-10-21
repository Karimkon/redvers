<?php


namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\{Sale, Part, LowStockAlert, User, Payment};
use App\Services\PesapalService;

class SaleController extends Controller
{
    public function index()
    {
        $shop = Auth::user()->shop;
        $sales = Sale::whereIn('part_id', $shop->parts()->pluck('id'))->latest()->paginate(10);

        return view('inventory.sales.index', compact('sales'));
    }

    public function create()
    {
        $parts = Auth::user()->shop->parts()->select('id', 'name', 'price', 'stock')->get();
        return view('inventory.sales.create', compact('parts'));
    }


public function store(Request $request)
{
    \Log::info('Sale Store Method Called', [
        'payment_method' => $request->payment_method,
        'all_data' => $request->all()
    ]);

    $request->validate([
        'part_id' => 'required|exists:parts,id',
        'quantity' => 'required|integer|min:1',
        'selling_price' => 'required|numeric|min:0',
        'customer_name' => 'nullable|string|max:255',
        'sold_at' => 'required|date',
        'payment_method' => 'required|in:cash,pesapal',
    ]);

    $part = Part::findOrFail($request->part_id);

    // ✅ Check stock
    if ($part->stock < $request->quantity) {
        return back()->with('error', 'Not enough stock available for this part.');
    }

    $totalAmount = $request->quantity * $request->selling_price;

    // 🧮 Get cost price from latest stock entry
    $latestStockEntry = $part->stockEntries()->orderByDesc('received_at')->first();
    $costPrice = $latestStockEntry?->cost_price ?? $part->cost_price;

    // 💳 If payment is Pesapal, initiate online payment
    if ($request->payment_method === 'pesapal') {
        \Log::info('Pesapal payment selected, initiating payment process');
        
        try {
            $reference = 'SALE-' . strtoupper(Str::random(8));
            \Log::info('Getting Pesapal access token...');
            
            $token = app(PesapalService::class)->getAccessToken();
            \Log::info('Pesapal token obtained', ['token_first_10' => substr($token, 0, 10) . '...']);

            // 🧠 Save sale info temporarily
            session([
                'pending_sale_data' => $request->all(),
                'pending_reference' => $reference,
                'pending_amount' => $totalAmount,
                'pending_cost_price' => $costPrice,
            ]);

            $callbackUrl = route('pesapal.inventory.callback');
            \Log::info('Callback URL', ['url' => $callbackUrl]);

            $payload = [
                "id" => Str::uuid()->toString(),
                "currency" => "UGX",
                "amount" => $totalAmount,
                "description" => "Spare Part Sale Payment - " . ($part->name ?? 'Part'),
                "callback_url" => $callbackUrl,
                "notification_id" => config('pesapal.notification_id', '34f2ce63-9c4c-430d-adb8-dbba55243d85'),
                "merchant_reference" => $reference,
                "billing_address" => [
                    "email_address" => $request->customer_email ?? "customer@example.com",
                    "phone_number" => $request->customer_phone ?? "256700000000",
                    "first_name" => $request->customer_name ?? 'Walk-in Customer',
                    "last_name" => "",
                    "line_1" => "Not provided",
                    "city" => "Kampala",
                    "state" => "Central",
                    "postal_code" => "256",
                    "zip_code" => "256",
                    "country_code" => "UG"
                ]
            ];

            \Log::info('Sending request to Pesapal', [
                'url' => config('pesapal.base_url') . '/api/Transactions/SubmitOrderRequest',
                'payload' => $payload
            ]);

            $response = Http::withToken($token)
                ->timeout(30)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post(config('pesapal.base_url') . '/api/Transactions/SubmitOrderRequest', $payload);

            $statusCode = $response->status();
            $responseBody = $response->body();
            $jsonResponse = $response->json();

            \Log::info('Pesapal API Response Details', [
                'status_code' => $statusCode,
                'response_body' => $responseBody,
                'json_response' => $jsonResponse,
                'headers' => $response->headers()
            ]);
            
            // ✅ Check if response contains required data
            if (!$response->successful()) {
                \Log::error('Pesapal API Error Response', [
                    'status' => $statusCode,
                    'body' => $responseBody,
                    'json' => $jsonResponse
                ]);
                
                if (isset($jsonResponse['error']['code'])) {
                    return back()->with('error', 'Pesapal Error: ' . $jsonResponse['error']['code'] . ' - ' . ($jsonResponse['error']['message'] ?? 'Unknown error'));
                }
                
                return back()->with('error', 'Pesapal service unavailable (HTTP ' . $statusCode . '). Please try again.');
            }

            // Check for different possible response structures
            $redirectUrl = $jsonResponse['redirect_url'] ?? $jsonResponse['url'] ?? null;
            $trackingId = $jsonResponse['order_tracking_id'] ?? $jsonResponse['tracking_id'] ?? $jsonResponse['id'] ?? null;

            \Log::info('Parsed Pesapal Response', [
                'redirect_url_found' => !empty($redirectUrl),
                'tracking_id_found' => !empty($trackingId),
                'redirect_url' => $redirectUrl,
                'tracking_id' => $trackingId
            ]);

            if (!$redirectUrl || !$trackingId) {
                \Log::error('Pesapal Missing Required Data', [
                    'available_keys' => array_keys($jsonResponse),
                    'full_response' => $jsonResponse
                ]);
                return back()->with('error', 'Unable to start payment: Payment gateway returned invalid data.');
            }

            // ✅ CRITICAL FIX: Store tracking ID in session
            session(['pending_tracking_id' => $trackingId]);
            session()->save(); // ✅ Ensure session is persisted

            \Log::info('Pesapal Redirect Initiated', [
                'reference' => $reference,
                'tracking_id' => $trackingId,
                'redirect_url' => $redirectUrl,
                'session_data' => session()->all()
            ]);

            // 🚀 Redirect to Pesapal payment page
            return redirect()->away($redirectUrl);

        } catch (\Exception $e) {
            \Log::error('Pesapal Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return back()->with('error', 'Payment gateway error: ' . $e->getMessage());
        }
    }

    // 🧾 If cash, record the sale instantly
    \Log::info('Cash payment selected, finalizing sale immediately');
    return $this->finalizeSale($part, $request, $costPrice, $totalAmount, 'cash');
}


private function finalizeSale($part, Request $request, $costPrice, $totalAmount, $paymentMethod)
{
    // 1️⃣ Record sale
    $sale = \App\Models\Sale::create([
        'part_id' => $part->id,
        'quantity' => $request->quantity,
        'selling_price' => $request->selling_price,
        'total_amount' => $totalAmount,
        'cost_price' => $costPrice,
        'customer_name' => $request->customer_name,
        'sold_at' => $request->sold_at,
        'payment_method' => $paymentMethod,
    ]);

    // 2️⃣ Reduce stock
    $part->decrement('stock', $request->quantity);

    // 3️⃣ Optionally record in stock history (if you use it)
    if (class_exists(\App\Models\StockHistory::class)) {
        \App\Models\StockHistory::create([
            'ingredient_id' => $part->id,
            'quantity' => -$request->quantity,
            'type' => 'sale',
            'added_by' => auth()->id(),
            'description' => 'Sold spare part ('.$request->customer_name.')',
        ]);
    }

    // 4️⃣ Return success redirect
    return redirect()
        ->route('inventory.sales.index')
        ->with('success', 'Sale recorded successfully (' . ucfirst($paymentMethod) . ' payment).');
}


public function pesapalCallback(Request $request)
{
    \Log::info('Pesapal Callback Received', ['request' => $request->all(), 'session' => session()->all()]);

    $trackingId = session('pending_tracking_id');
    $amount = session('pending_amount');
    $data = session('pending_sale_data');
    $costPrice = session('pending_cost_price');

    // ✅ Validate session data exists
    if (!$trackingId || !$amount || !$data) {
        \Log::error('Missing session data in Pesapal callback', [
            'trackingId' => $trackingId,
            'amount' => $amount,
            'data' => $data
        ]);
        return redirect()->route('inventory.sales.create')->with('error', 'Payment session expired. Please try again.');
    }

    try {
        $part = Part::findOrFail($data['part_id']);
        
        // ✅ Use stored cost price instead of recalculating
        $this->finalizeSale($part, new Request($data), $costPrice, $amount, 'pesapal');

        // ✅ Clear all session data
        session()->forget([
            'pending_sale_data', 
            'pending_reference', 
            'pending_amount', 
            'pending_tracking_id',
            'pending_cost_price'
        ]);

        return redirect()
            ->route('inventory.sales.index')
            ->with('success', 'Sale confirmed and recorded after online payment.');

    } catch (\Exception $e) {
        \Log::error('Pesapal Callback Error: ' . $e->getMessage());
        return redirect()->route('inventory.sales.create')->with('error', 'Failed to finalize sale: ' . $e->getMessage());
    }
}



    public function destroy(Sale $sale)
    {
        $sale->part->increment('stock', $sale->quantity); // return stock
        $sale->delete();

        return redirect()->back()->with('success', 'Sale deleted and stock restored.');
    }
}
