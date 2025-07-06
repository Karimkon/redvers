<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\Payment;
use App\Services\PesapalService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RiderWalletController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $wallet = $user->wallet()->first();

        $transactions = $wallet
            ? $wallet->logs()->latest()->paginate(10)
            : collect();

        return view('rider.wallet.index', compact('wallet', 'transactions'));
    }

    public function topUpForm()
    {
        return view('rider.wallet.topup');
    }

    public function initiateTopUp(Request $request)
{
    $request->validate([
        'amount'         => 'required|integer|min:1000',
        'payment_method' => 'required|in:mtn,airtel,pesapal',
    ]);

    $amount   = (int) $request->amount;
    $method   = $request->payment_method;
    $user     = Auth::user();
    $ref      = 'WALLET-TOPUP-' . uniqid();

    /* ---------- P E S A P A L ------------- */
    if ($method === 'pesapal') {
        try {
            $token = app(PesapalService::class)->getAccessToken();

            $payload = [
                'id'                 => Str::uuid()->toString(),
                'currency'           => 'UGX',
                'amount'             => $amount,
                'description'        => 'Wallet Top‑Up',
                'callback_url'       => route('rider.wallet.pesapal.callback'),
                'notification_id'    => '34f2ce63-9c4c-430d-adb8-dbba55243d85', // ✅ the working ID
                'merchant_reference' => $ref,
                'billing_address'    => [
                    'email_address' => $user->email,
                    'phone_number'  => $user->phone,
                    'first_name'    => explode(' ', $user->name)[0],
                    'last_name'     => explode(' ', $user->name)[1] ?? '',
                    'line_1'        => 'Redvers Rider Wallet',
                    'city'          => 'Kampala',
                    'state'         => 'Central',
                    'postal_code'   => '256',
                    'zip_code'      => '256',
                    'country_code'  => 'UG',
                ],
            ];

            $resp  = Http::withToken($token)->post(
                config('pesapal.base_url') . '/api/Transactions/SubmitOrderRequest',
                $payload
            );

            $data = $resp->json();
            \Log::info('Pesapal wallet top‑up response', $data);

            $tracking = data_get($data, 'data.order_tracking_id')
                      ?? data_get($data, 'order_tracking_id');

            $redirect = data_get($data, 'data.redirect_url')
                      ?? data_get($data, 'redirect_url');

            if (!$tracking || !$redirect) {
                throw new \Exception(
                    'Pesapal reply missing keys. Body: ' . json_encode($data)
                );
            }

            // Save only what we need for callback
            session([
                'pending_wallet_topup_reference'   => $ref,
                'pending_wallet_topup_amount'      => $amount,
                'pending_wallet_topup_tracking_id' => $tracking,
            ]);


            return redirect()->away($redirect);

        } catch (\Throwable $e) {
            \Log::error('Pesapal error: ' . $e->getMessage());
            return back()
                ->withErrors(['pesapal' => 'Payment start failed: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /* ---------- M T N / A I R T E L  (stub) ------------- */
    DB::transaction(function () use ($user, $amount, $method, $ref) {
        $wallet = $user->wallet()->firstOrCreate([], ['balance' => 0]);
        $wallet->increment('balance', $amount);

        WalletTransaction::create([
            'user_id'     => $user->id,
            'amount'      => $amount,
            'type'        => 'credit',
            'description' => strtoupper($method) . ' manual top‑up',
            'reference'   => $ref,
        ]);
    });

    return redirect()->route('rider.wallet.index')
        ->with('success', 'Wallet credited with UGX ' . number_format($amount));
}


    public function pesapalCallback(Request $request)
{
    // use the SAME keys you stored
    $reference  = session('pending_wallet_topup_reference');
    $trackingId = session('pending_wallet_topup_tracking_id');
    $amount     = session('pending_wallet_topup_amount');
    $user       = Auth::user();

    if (!$reference || !$trackingId || !$amount) {
        return to_route('rider.wallet.index')
               ->with('error', 'Missing payment session data.');
    }

    try {
        $token = app(PesapalService::class)->getAccessToken();

        $resp = Http::withToken($token)->get(
            config('pesapal.base_url').'/api/Transactions/GetTransactionStatus',
            ['orderTrackingId' => $trackingId]
        );

        if (strtolower($resp['payment_status_description'] ?? '') !== 'completed') {
            return to_route('rider.wallet.index')
                   ->with('error', 'Payment not completed.');
        }

        DB::transaction(function () use ($user, $amount, $reference) {
            $wallet = $user->wallet()->firstOrCreate([], ['balance' => 0]);
            $wallet->increment('balance', $amount);

            WalletTransaction::create([
                'user_id'     => $user->id,
                'amount'      => $amount,
                'type'        => 'credit',
                'description' => 'Pesapal wallet top‑up',
                'reference'   => $reference,
            ]);
        });

        // clear the session keys we just used
        session()->forget([
            'pending_wallet_topup_reference',
            'pending_wallet_topup_tracking_id',
            'pending_wallet_topup_amount',
        ]);

        return to_route('rider.wallet.index')
               ->with('success', 'Wallet topped up successfully.');

    } catch (\Throwable $e) {
        \Log::error('Pesapal callback error: '.$e->getMessage());
        return to_route('rider.wallet.index')
               ->with('error', 'Payment verification failed.');
    }
}

}
