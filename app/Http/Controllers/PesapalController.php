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

    /**
     * Step 5: Handle the callback after payment
     */

   public function handleCallback(Request $request)
    {
        try {
            $data = session('pending_swap_data');
            $reference = session('pending_reference');
            $amount = session('pending_amount');

            if (!$data || !$reference || !$amount) {
                return redirect()->route('agent.swaps.index')->with('error', 'Missing payment session data.');
            }

            $battery = \App\Models\Battery::findOrFail($data['battery_id']);
            $requestData = new \Illuminate\Http\Request($data);

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
        try {
            $promoId = session('pending_promo_id');
            $reference = session('pending_promo_reference');

            if (!$promoId || !$reference) {
                return redirect()->route('agent.promotions.index')->with('error', 'Missing promo session.');
            }

            $promotion = \App\Models\SwapPromotion::with('rider')->findOrFail($promoId);

            // Update promo status
            $promotion->update([
                'status' => 'active',
                'starts_at' => now(),
                'ends_at' => now()->addDay(), // exactly 24 hours from now
            ]);


            $purchase = \App\Models\Purchase::where('user_id', $promotion->rider_id)
                ->where('status', 'active')
                ->latest()
                ->first();

            if ($purchase) {
                $alreadyPaid = \App\Models\MotorcyclePayment::where('user_id', $promotion->rider_id)
                    ->whereDate('date', now()->toDateString())
                    ->exists();

                if (!$alreadyPaid) {
                    \App\Models\MotorcyclePayment::create([
                        'purchase_id' => $purchase->id,
                        'user_id' => $promotion->rider_id,
                        'amount' => 12000,
                        'status' => 'paid',
                        'date' => now()->toDateString(),
                        'method' => 'promo',
                        'reference' => $reference,
                    ]);
                } else {
                    Log::info("🟡 Rider {$promotion->rider_id} already paid UGX 12,000 today via promotion. Skipping duplicate payment.");
                }
            }



            return redirect()->route('agent.promotions.index')->with('success', 'Promotion activated and daily fee paid.');
        } catch (\Exception $e) {
            \Log::error('Promo Callback Error: ' . $e->getMessage());
            return redirect()->route('agent.promotions.index')->with('error', 'Something went wrong in promotion callback.');
        }
    }

    public function handleDailyPaymentCallback(Request $request)
    {
        $data = session('pending_daily_payment');

        if (!$data) {
            return abort(400, 'Missing session data');
        }

        $status = app(PesapalService::class)->getPaymentStatus($data['reference']);

        if (strtolower($status) === 'completed') {
            MotorcyclePayment::create([
                'purchase_id' => $data['purchase_id'],
                'user_id' => $data['rider_id'],
                'payment_date' => now()->toDateString(),
                'amount' => $data['amount'], // ✅ Save exact amount chosen
                'method' => 'pesapal',
                'type' => 'daily',
                'note' => 'Agent Initiated',
                'reference' => $data['reference'],
                'status' => 'paid',
            ]);

            session()->forget('pending_daily_payment');

            return redirect()->route('agent.dashboard')->with('success', 'Daily payment of UGX ' . number_format($data['amount']) . ' recorded successfully.');
        }

        return redirect()->route('agent.dashboard')->with('error', 'Payment not completed.');
    }



}
