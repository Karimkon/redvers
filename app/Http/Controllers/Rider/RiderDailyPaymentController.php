<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MotorcyclePayment;
use App\Services\PesapalService;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RiderDailyPaymentController extends Controller
{
    protected $pesapalService;

    public function __construct(PesapalService $pesapalService)
    {
        $this->pesapalService = $pesapalService;
    }

    public function create()
    {
        $purchase = Auth::user()->purchases()->whereIn('status', ['active', 'defaulted'])
        ->latest()
        ->first();


        if (!$purchase) {
            return redirect()->route('rider.dashboard')->with('error', 'No active purchase.');
        }

        $overdueSummary = $purchase->getAdjustedOverdueSummary();

        // Dynamically set amount based on overdue amount (minimum daily rate if no overdue)
        $amount = max($overdueSummary['due_amount'], $purchase->daily_rate);

        return view('rider.daily_payment.create', compact('purchase', 'amount', 'overdueSummary'));
    }

    public function payViaPesapal(Request $request)
    {
        $purchase = Auth::user()->purchases()->whereIn('status', ['active', 'defaulted'])
        ->latest()
        ->first();


        $overdueSummary = $purchase->getAdjustedOverdueSummary();
        $dailyRate = $purchase->daily_rate;

        // Validate amount input
        $request->validate([
            'amount' => "required|numeric|min:{$dailyRate}",
        ]);

        $amount = $request->input('amount');
        $reference = 'RIDER-DAILY-' . strtoupper(Str::random(8));
        $orderId = Str::uuid()->toString();

        session([
            'daily_payment_data' => [
                'purchase_id' => $purchase->id,
                'user_id' => Auth::id(),
                'amount' => $amount,
                'payment_date' => Carbon::today()->toDateString(),
                'type' => 'daily',
                'method' => 'pesapal',
                'status' => 'pending',
                'reference' => $reference,
            ]
        ]);

        $payload = [
            "id" => $orderId,
            "currency" => "UGX",
            "amount" => $amount,
            "description" => "Motorcycle Payment (including overdue)",
            "callback_url" => route('rider.daily-payment.callback'),
            "notification_id" => "6e8802b0-bd8f-447b-a1d2-dbb201b4a089",
            "merchant_reference" => $reference,
            "billing_address" => [
                "email_address" => Auth::user()->email,
                "phone_number" => Auth::user()->phone,
                "first_name" => explode(' ', Auth::user()->name)[0],
                "last_name" => explode(' ', Auth::user()->name)[1] ?? '',
                "country_code" => "UG"
            ]
        ];

        $response = $this->pesapalService->initiatePayment($payload);

        return redirect()->away($response['redirect_url']);
    }

    public function handleCallback(Request $request)
    {
        $sessionData = session('daily_payment_data');

        if (!$sessionData) {
            return redirect()->route('rider.dashboard')->with('error', 'Session expired, please try again.');
        }

        $orderTrackingId = $request->input('OrderTrackingId');
        $status = $this->pesapalService->getPaymentStatus($orderTrackingId);

        if ($status === 'completed') {
            MotorcyclePayment::create([
                'purchase_id' => $sessionData['purchase_id'],
                'user_id' => $sessionData['user_id'],
                'payment_date' => $sessionData['payment_date'],
                'amount' => $sessionData['amount'],
                'type' => 'daily',
                'method' => 'pesapal',
                'note' => 'Daily payment via Pesapal (including overdue)',
                'status' => 'paid',
            ]);

            session()->forget('daily_payment_data');

            return redirect()->route('rider.dashboard')->with('success', 'Payment completed successfully.');
        }

        return redirect()->route('rider.dashboard')->with('error', 'Payment verification failed.');
    }
}
