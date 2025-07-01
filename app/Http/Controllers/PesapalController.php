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
use Carbon\Carbon;

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
        \Log::info('🎯 Promotion callback started', [
            'promo_id' => session('pending_promo_id'),
            'reference' => session('pending_promo_reference'),
            'timezone' => config('app.timezone'),
            'current_time' => now()->toDateTimeString(),
        ]);

        try {
            $promoId = session('pending_promo_id');
            $reference = session('pending_promo_reference');

            if (!$promoId || !$reference) {
                \Log::warning('❌ Missing promo session data');
                return redirect()->route('agent.promotions.index')->with('error', 'Missing promo session.');
            }

            $promotion = \App\Models\SwapPromotion::with('rider')->findOrFail($promoId);

            // ✅ FIX 1: Use Carbon with proper timezone for promotion times
            $kampalaTime = Carbon::now('Africa/Kampala');
            $startsAt = $kampalaTime->copy();
            $endsAt = $kampalaTime->copy()->addDay(); // exactly 24 hours from now

            \Log::info('🕐 Promotion timing', [
                'kampala_time' => $kampalaTime->toDateTimeString(),
                'starts_at' => $startsAt->toDateTimeString(),
                'ends_at' => $endsAt->toDateTimeString(),
            ]);

            // Update promo status with correct timezone
            $promotion->update([
                'status' => 'active',
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
            ]);

            \Log::info('✅ Promotion updated successfully', [
                'promotion_id' => $promotion->id,
                'rider_id' => $promotion->rider_id,
                'new_status' => $promotion->status,
            ]);

            // ✅ FIX 2: Create motorcycle payment for the promotion
            $purchase = \App\Models\Purchase::where('user_id', $promotion->rider_id)
                ->where('status', 'active')
                ->latest()
                ->first();

            if ($purchase) {
                \Log::info('🏍️ Found active purchase for rider', [
                    'purchase_id' => $purchase->id,
                    'rider_id' => $promotion->rider_id,
                ]);

                // Check if payment already exists for today
                $todayDate = $kampalaTime->toDateString();
                $alreadyPaid = MotorcyclePayment::where('user_id', $promotion->rider_id)
                    ->where('payment_date', $todayDate)
                    ->exists();

                if (!$alreadyPaid) {
                    // Create the motorcycle payment record
                    $motorcyclePayment = MotorcyclePayment::create([
                        'purchase_id' => $purchase->id,
                        'user_id' => $promotion->rider_id, // ✅ Make sure this field exists
                        'payment_date' => $todayDate, // ✅ Use payment_date not date
                        'amount' => 12000,
                        'type' => 'daily', // ✅ Add type field
                        'method' => 'promo',
                        'reference' => $reference,
                        'status' => 'paid',
                        'note' => 'Auto-paid via promotion activation',
                    ]);

                    \Log::info('✅ Motorcycle payment created', [
                        'payment_id' => $motorcyclePayment->id,
                        'amount' => 12000,
                        'date' => $todayDate,
                        'rider_id' => $promotion->rider_id,
                    ]);
                } else {
                    \Log::info("🟡 Rider {$promotion->rider_id} already paid UGX 12,000 today. Skipping duplicate payment.");
                }
            } else {
                \Log::warning('❌ No active purchase found for rider', [
                    'rider_id' => $promotion->rider_id,
                ]);
            }

            // Clear session data
            session()->forget(['pending_promo_id', 'pending_promo_reference']);

            return redirect()->route('agent.promotions.index')->with('success', 'Promotion activated successfully! Daily fee of UGX 12,000 has been recorded.');

        } catch (\Exception $e) {
            \Log::error('❌ Promo Callback Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('agent.promotions.index')->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function handleDailyPaymentCallback(Request $request)
    {
        $data = session('pending_daily_payment');

        if (!$data) {
            return abort(400, 'Missing session data');
        }

        $status = app(PesapalService::class)->getPaymentStatus($data['tracking_id'], $data['reference']);

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
