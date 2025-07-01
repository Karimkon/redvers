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
        'payment_method' => 'required|in:pesapal',
        'amount' => 'required|in:12000,13000,16000'
    ]);

    $rider = User::findOrFail($request->rider_id);
    $purchase = Purchase::where('user_id', $rider->id)
    ->where('status', 'active')
    ->latest()
    ->firstOrFail();

    $amount = $request->input('amount', 12000); // fallback if not set
    $reference = 'DAILY-PESAPAL-' . uniqid();

    // Save to session for use in callback
    session([
        'pending_daily_payment' => [
            'rider_id' => $rider->id,
            'purchase_id' => $purchase->id,
            'amount' => $amount,
            'reference' => $reference,
        ]
    ]);

    try {
        $token = app(PesapalService::class)->getAccessToken();

        $response = Http::withToken($token)->post(config('pesapal.base_url') . '/api/Transactions/SubmitOrderRequest', [
            "id" => Str::uuid()->toString(),
            "currency" => "UGX",
            "amount" => $amount,
            "description" => "Daily Motorcycle Payment",
            "callback_url" => route('pesapal.callback.daily'), // Use route in web.php
            "notification_id" => "34f2ce63-9c4c-430d-adb8-dbba55243d85", // Inline notification_id
            "billing_address" => [
                "email_address" => $rider->email,
                "phone_number" => $rider->phone, // No modification
                "first_name" => explode(' ', $rider->name)[0],
                "last_name" => explode(' ', $rider->name)[1] ?? '',
                "line_1" => "Redvers Station",
                "city" => "Kampala",
                "state" => "Central",
                "postal_code" => "256",
                "zip_code" => "256",
                "country_code" => "UG"
            ]
        ]);

        return redirect()->away($response['redirect_url']);
    } catch (\Exception $e) {
        \Log::error('Pesapal Error (Daily Payment): ' . $e->getMessage());
        return back()->withErrors(['pesapal' => 'Payment initiation failed.'])->withInput();
    }
}


}
