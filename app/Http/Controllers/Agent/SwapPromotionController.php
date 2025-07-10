<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SwapPromotion;
use App\Models\User;
use Illuminate\Support\Carbon;
use App\Services\PesapalService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class SwapPromotionController extends Controller
{
    public function index()
{
    $promotions = \App\Models\SwapPromotion::with('rider')
        ->where('agent_id', auth()->id())
        ->latest()
        ->paginate(10);
        
        // use ->getCollection()->map(...) to mutate paginated items
        $promotions->getCollection()->transform(function ($promotion) {
                $now = now('Africa/Kampala');

                if ($promotion->status !== 'expired') {
                    if ($now->gt($promotion->ends_at)) {
                        $promotion->status = 'expired';
                    } elseif ($now->between($promotion->starts_at, $promotion->ends_at)) {
                        $promotion->status = 'active';
                    } else {
                        $promotion->status = 'pending';
                    }
                }

                return $promotion;
            });

    return view('agent.promotions.index', compact('promotions'));
}


    public function create()
    {
        $riders = User::where('role', 'rider')->get();
        return view('agent.promotions.create', compact('riders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rider_id' => 'required|exists:users,id',
        ]);

        $promo = SwapPromotion::create([
            'rider_id' => $request->rider_id,
            'agent_id' => auth()->id(),
            'starts_at' => now(),
            'ends_at' => now()->addDay(),
            'amount' => 25000,
            'status' => 'pending',
        ]);

        session([
            'pending_promo_id' => $promo->id,
            'pending_promo_reference' => 'PROMO-' . uniqid()
        ]);

        return redirect()->route('agent.promotions.payment');
    }

    public function redirectToPayment()
{
    $promoId = session('pending_promo_id');
    $reference = session('pending_promo_reference');

    if (!$promoId) {
        return redirect()->back()->with('error', 'No promo session found.');
    }

    $promotion = SwapPromotion::with('rider')->findOrFail($promoId);
    $rider = $promotion->rider;
    $amount = $promotion->amount;

    return view('agent.promotions.pesapal', compact('promotion', 'rider', 'amount', 'reference'));
}

    public function payViaPesapal(Request $request)
{
    $promoId = session('pending_promo_id');
    $reference = session('pending_promo_reference');
    $promotion = SwapPromotion::with('rider')->findOrFail($promoId);
    $rider = $promotion->rider;

    try {
        $token = app(PesapalService::class)->getAccessToken();

        $response = Http::withToken($token)->post(config('pesapal.base_url') . '/api/Transactions/SubmitOrderRequest', [
            "id" => Str::uuid()->toString(),
            "currency" => "UGX",
            "amount" => $promotion->amount,
            "description" => "Unlimited Swap Promotion",
            "callback_url" => route('pesapal.promo.callback'),
            "notification_id" => "34f2ce63-9c4c-430d-adb8-dbba55243d85",
            "billing_address" => [
                "email_address" => $rider->email,
                "phone_number" => $rider->phone,
                "first_name" => explode(' ', $rider->name)[0],
                "last_name" => explode(' ', $rider->name)[1] ?? '',
                "line_1" => "Redvers Promotion",
                "city" => "Kampala",
                "state" => "Central",
                "postal_code" => "256",
                "zip_code" => "256",
                "country_code" => "UG"
            ]
        ]);

        if ($response->successful()) {
            return redirect()->away($response['redirect_url']);
        }

        return back()->withErrors(['pesapal' => 'Pesapal initiation failed.']);
    } catch (\Exception $e) {
        \Log::error('Pesapal Promo Error', ['error' => $e->getMessage()]);
        return back()->withErrors(['pesapal' => 'Error: ' . $e->getMessage()]);
    }
}


    public function edit(SwapPromotion $promotion)
    {
        $this->authorizeAgent($promotion);
        return view('agent.promotions.edit', compact('promotion'));
    }

    public function update(Request $request, SwapPromotion $promotion)
    {
        $this->authorizeAgent($promotion);

        $request->validate([
            'status' => 'required|in:pending,active,expired,cancelled',
        ]);

        $promotion->update([ 'status' => $request->status ]);

        return redirect()->route('agent.promotions.index')->with('success', 'Promotion updated.');
    }

    public function destroy(SwapPromotion $promotion)
    {
        $this->authorizeAgent($promotion);
        $promotion->delete();
        return back()->with('success', 'Promotion deleted.');
    }

    protected function authorizeAgent(SwapPromotion $promotion) 
    {
        if ($promotion->agent_id !== auth()->id()) {
            abort(403);
        }
    }
}
