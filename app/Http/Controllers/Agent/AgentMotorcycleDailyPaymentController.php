<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Purchase;
use App\Models\MotorcyclePayment;
use App\Services\PesapalService;

class AgentMotorcycleDailyPaymentController extends Controller
{
    public function create()
    {
        $riders = User::where('role', 'rider')
            ->with(['purchases' => fn($q) => $q->where('status', 'active')->latest()])
            ->get();

        return view('agent.daily_payments.create', compact('riders'));
    }

public function store(Request $request) 
{
    $request->validate([
        'rider_id' => 'required|exists:users,id',
        'purchase_id' => 'required|exists:purchases,id',
        'amount' => 'required|in:12000,13000,16000',
        'phone_number' => 'required|string|regex:/^(0|256)[0-9]{9}$/',
    ]);

    try {
        DB::beginTransaction();

        $rider = User::findOrFail($request->rider_id);
        $purchase = Purchase::findOrFail($request->purchase_id);

        // Verify the purchase belongs to the rider
        if ($purchase->user_id != $rider->id) {
            throw new \Exception("Purchase does not belong to selected rider");
        }

        $amount = $request->input('amount', 12000);
        $reference = 'DAILY-' . Str::upper(Str::random(8));

        // Save to session for callback
        session([
            'pending_daily_payment' => [
                'rider_id' => $rider->id,
                'purchase_id' => $purchase->id,
                'amount' => $amount,
                'reference' => $reference,
                'method' => 'pesapal',
                'phone_number' => $request->phone_number,
            ]
        ]);

        \Log::info('Initiating daily payment', [
            'rider' => $rider->id,
            'amount' => $amount,
            'reference' => $reference
        ]);

        $token = app(PesapalService::class)->getAccessToken();
        
        $paymentData = [
            "id" => Str::uuid()->toString(),
            "currency" => "UGX",
            "amount" => $amount,
            "description" => "Daily Motorcycle Payment for " . $rider->name,
            "callback_url" => route('pesapal.callback.daily'),
            "notification_id" => config('pesapal.notification_id'),
            "billing_address" => [
                "email_address" => $rider->email,
                "phone_number" => $request->phone_number,
                "first_name" => explode(' ', $rider->name)[0],
                "last_name" => explode(' ', $rider->name)[1] ?? '',
                "country_code" => "UG"
            ]
        ];

        $response = Http::withToken($token)
            ->timeout(30)
            ->retry(3, 1000)
            ->post(config('pesapal.base_url') . '/api/Transactions/SubmitOrderRequest', $paymentData);

        if (!$response->successful() || !isset($response['redirect_url'])) {
            \Log::error('Pesapal payment failed', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            throw new \Exception("Failed to initiate payment with Pesapal");
        }

        DB::commit();

        return redirect()->away($response['redirect_url']);
        
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Payment error: ' . $e->getMessage());
        
        return back()
            ->withInput()
            ->withErrors(['error' => 'Payment initiation failed. Please try again.']);
    }
}

}
