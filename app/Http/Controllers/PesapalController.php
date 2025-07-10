<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Payment;
use App\Services\PesapalService;    
use Illuminate\Support\Facades\DB;
use App\Models\Battery;
use App\Models\Swap;
use App\Models\BatterySwap;
use App\Http\Controllers\Agent\AgentSwapController;

class PesapalController extends Controller
{
    /**
     * Step 1: Test token authentication (optional but useful for debugging)
     */
    public function authenticate()
    {
        try {
            $token = app(PesapalService::class)->getAccessToken();

            return response()->json([
                'token' => $token,
                'status' => 'success',
                'message' => 'Token retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**handlePromotionCallback
     * Step 5: Handle the callback after payment
     */

   public function handleCallback(Request $request)
    {
        \Log::info('✅ Pesapal Callback Triggered.', [
            'data' => session('pending_swap_data'),
            'reference' => session('pending_reference'),
            'amount' => session('pending_amount')
        ]);

        try {
            $data = session('pending_swap_data');
            $reference = session('pending_reference');
            $amount = session('pending_amount');

            if (!$data || !$reference || !$amount) {
                \Log::warning('❌ Missing session data during Pesapal callback.');
                return redirect()->route('agent.swaps.index')->with('error', 'Missing payment session data.');
            }

            $battery = Battery::findOrFail($data['battery_id']);

            // Wrap array into a Request object for compatibility
            $requestData = new Request($data);

            $agentSwapController = new AgentSwapController;
            return $agentSwapController->finalizeSwap($requestData, $battery, $amount, 'pesapal', 'completed');
        } catch (\Exception $e) {
            \Log::error('Pesapal Callback Error: ' . $e->getMessage());
            return redirect()->route('agent.swaps.index')->with('error', 'Payment confirmed but swap could not be finalized.');
        }
    }



        public function handleIPN(Request $request)
    {
        $orderTrackingId = $request->input('OrderTrackingId');
        $merchantRef = $request->input('OrderMerchantReference');
        $notificationType = $request->input('OrderNotificationType');

        \Log::info('📥 IPN received from Pesapal', [
            'tracking_id' => $orderTrackingId,
            'merchant_reference' => $merchantRef,
            'type' => $notificationType,
        ]);

        try {
            $token = app(PesapalService::class)->getAccessToken();

            $response = Http::withToken($token)->get(
                config('pesapal.base_url') . '/api/Transactions/GetTransactionStatus',
                ['orderTrackingId' => $orderTrackingId]
            );

            if ($response->successful()) {
                $status = strtolower($response['payment_status_description']);

                $payment = Payment::where('external_reference', $orderTrackingId)->first();

                if ($payment) {
                    $payment->update(['status' => $status]);

                    \Log::info("✅ Payment updated via IPN: {$status}", [
                        'payment_id' => $payment->id,
                        'tracking_id' => $orderTrackingId,
                    ]);
                }
            }

            return response()->json([
                'orderNotificationType' => $notificationType,
                'orderTrackingId' => $orderTrackingId,
                'orderMerchantReference' => $merchantRef,
                'status' => 200
            ]);
        } catch (\Exception $e) {
            \Log::error('❌ Error in IPN handler: ' . $e->getMessage());

            return response()->json([
                'orderNotificationType' => $notificationType,
                'orderTrackingId' => $orderTrackingId,
                'orderMerchantReference' => $merchantRef,
                'status' => 500
            ]);
        }
    }

    public function handlePromotionCallback(Request $request)
{
    $promoId = session('pending_promo_id');
    $reference = session('pending_promo_reference');

    if (!$promoId || !$reference) {
        return redirect()->route('agent.promotions.index')->with('error', 'Missing promo session.');
    }

    try {
        $promotion = \App\Models\SwapPromotion::with('rider')->findOrFail($promoId);

        // Activate promotion for 24 hours
        $now = now('Africa/Kampala');
        $promotion->update([
            'status' => 'active',
            'starts_at' => $now,
            'ends_at' => $now->copy()->addDay(),
            'payment_reference' => $reference,
        ]);

        // Record 12k motorcycle payment if not already paid today
        $purchase = \App\Models\Purchase::where('user_id', $promotion->rider_id)
            ->where('status', 'active')->first();

        if ($purchase) {
            $today = $now->toDateString();
            $alreadyPaid = \App\Models\MotorcyclePayment::where('purchase_id', $purchase->id)
                ->where('payment_date', $today)->exists();

            if (!$alreadyPaid) {
                \App\Models\MotorcyclePayment::create([
                    'purchase_id' => $purchase->id,
                    'user_id' => $purchase->user_id,
                    'payment_date' => $today,
                    'amount' => 12000,
                    'type' => 'daily',
                    'method' => 'promo',
                    'reference' => $reference,
                    'note' => 'Auto-paid via promotion activation',
                    'status' => 'paid'
                ]);

                $purchase->increment('amount_paid', 12000);
                $purchase->decrement('remaining_balance', 12000);
            }
        }

        session()->forget(['pending_promo_id', 'pending_promo_reference']);

        return redirect()->route('agent.promotions.index')->with('success', 'Promotion activated and daily fee paid.');
    } catch (\Exception $e) {
        \Log::error('Promotion Callback Error: ' . $e->getMessage());
        return redirect()->route('agent.promotions.index')->with('error', 'Promotion activation failed.');
    }
}

    
   public function handleDailyPaymentCallback(Request $request)
{
    $data = session('pending_daily_payment');

    if (!$data) {
        \Log::warning('🚫 No pending_daily_payment found in session during callback.');
        return redirect()->route('agent.daily-payments.create')->with('error', 'Session expired or invalid payment attempt.');
    }

    try {
        // ✅ Verify payment status from Pesapal API
        $token = app(PesapalService::class)->getAccessToken();

        $trackingId = $request->input('OrderTrackingId'); // From Pesapal redirect
        $merchantReference = $request->input('OrderMerchantReference'); // From session

        $response = Http::withToken($token)->get(config('pesapal.base_url') . "/api/Transactions/GetTransactionStatus", [
            'orderTrackingId' => $trackingId,
        ]);

        $paymentStatus = $response['payment_status'] ?? 'FAILED';

        if ($paymentStatus !== 'COMPLETED') {
            \Log::warning('❌ Pesapal payment not completed.', ['status' => $paymentStatus]);
            return redirect()->route('agent.daily-payments.create')->with('error', 'Payment was not successful.');
        }

        // ✅ Proceed to save the motorcycle payment
        DB::beginTransaction();

        MotorcyclePayment::create([
            'purchase_id' => $data['purchase_id'],
            'user_id' => $data['rider_id'],
            'amount' => $data['amount'],
            'method' => 'pesapal',
            'status' => 'completed',
            'reference' => $data['reference'],
            'paid_at' => now('Africa/Kampala'),
            'recorded_by' => 'agent',
        ]);

        DB::commit();

        session()->forget('pending_daily_payment');

        return redirect()->route('agent.daily-payments.create')->with('success', '✅ Daily payment recorded successfully.');

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('🔥 Error in handleDailyPaymentCallback: ' . $e->getMessage());
        return redirect()->route('agent.daily-payments.create')->with('error', 'Unexpected error occurred.');
    }
}

}
