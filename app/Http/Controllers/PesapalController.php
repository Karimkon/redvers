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
                $purchase->save();
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
    \Log::info('📥 Daily Payment Callback Triggered', [
        'request_data' => $request->all(),
        'session_exists' => session()->has('pending_daily_payment'),
        'ip' => $request->ip(),
        'user_agent' => $request->userAgent(),
    ]);

    // Check session expiry
    $expiresAt = session('pending_payment_expires');
    if ($expiresAt && now()->timestamp > $expiresAt) {
        \Log::warning('⏰ Payment session expired', [
            'expires_at' => $expiresAt,
            'current_time' => now()->timestamp,
        ]);
        session()->forget(['pending_daily_payment', 'pending_payment_expires']);
        return redirect()->route('agent.dashboard')->with('warning', 'Payment session has expired. Please initiate a new payment.');
    }

    $data = session('pending_daily_payment');

    if (!$data) {
        \Log::error('❌ Missing daily payment session data');
        return redirect()->route('agent.dashboard')->with('error', 'Payment session not found. Please try again.');
    }

    // Validate session data integrity
    if (!$this->validateSessionData($data)) {
        \Log::error('❌ Invalid session data structure', ['data' => $data]);
        session()->forget(['pending_daily_payment', 'pending_payment_expires']);
        return redirect()->route('agent.dashboard')->with('error', 'Invalid payment session. Please try again.');
    }

    try {
        DB::beginTransaction();

        $orderTrackingId = $request->input('OrderTrackingId');
        $merchantReference = $request->input('OrderMerchantReference');
        
        if (!$orderTrackingId) {
            throw new \Exception('Missing OrderTrackingId in callback');
        }

        // Verify payment with Pesapal
        $paymentStatus = $this->verifyPaymentWithPesapal($orderTrackingId);
        
        if ($paymentStatus !== 'completed') {
            \Log::warning('⚠️ Payment not completed', [
                'status' => $paymentStatus,
                'tracking_id' => $orderTrackingId,
                'data' => $data,
            ]);
            
            $message = $this->getPaymentStatusMessage($paymentStatus);
            return redirect()->route('agent.dashboard')->with('warning', $message);
        }

        // Get models
        $purchase = Purchase::with('user')->findOrFail($data['purchase_id']);
        $rider = $purchase->user;
        
        // Verify data consistency
        if ($purchase->user_id != $data['rider_id']) {
            throw new \Exception('Data inconsistency: Purchase does not belong to rider');
        }

        // Check for duplicate payment
        $today = now('Africa/Kampala')->toDateString();
        $existingPayment = MotorcyclePayment::where('purchase_id', $data['purchase_id'])
            ->where('payment_date', $today)
            ->where('status', 'paid')
            ->first();

        if ($existingPayment) {
            \Log::warning('⚠️ Duplicate payment attempt', [
                'existing_payment_id' => $existingPayment->id,
                'tracking_id' => $orderTrackingId,
                'purchase_id' => $data['purchase_id'],
            ]);
            
            session()->forget(['pending_daily_payment', 'pending_payment_expires']);
            return redirect()->route('agent.dashboard')
                ->with('info', 'Payment has already been processed for today.');
        }

        // Verify purchase is still active
        if ($purchase->status !== 'active') {
            throw new \Exception('Purchase is no longer active');
        }

        // Create payment record
        $payment = $this->createPaymentRecord($data, $purchase, $orderTrackingId, $today);

        // Update purchase balances
        $this->updatePurchaseBalances($purchase, $data['amount']);

        // Log success
        \Log::info('✅ Daily payment processed successfully', [
            'payment_id' => $payment->id,
            'rider_name' => $rider->name,
            'amount' => $data['amount'],
            'tracking_id' => $orderTrackingId,
            'agent_id' => $data['agent_id'] ?? null,
            'new_balance' => $purchase->fresh()->remaining_balance,
        ]);

        // Clear session
        session()->forget(['pending_daily_payment', 'pending_payment_expires']);

        DB::commit();

        // Redirect with appropriate message
        $message = "Daily payment of UGX " . number_format($data['amount']) . " processed successfully for {$rider->name}.";
        
        if ($purchase->fresh()->remaining_balance <= 0) {
            $message .= " 🎉 Motorcycle purchase is now fully paid!";
        }

        return redirect()->route('agent.dashboard')->with('success', $message);
        
    } catch (\Exception $e) {
        DB::rollBack();
        
        \Log::error('❌ Daily payment processing error', [
            'error' => $e->getMessage(),
            'session_data' => $data,
            'tracking_id' => $orderTrackingId ?? 'N/A',
            'trace' => $e->getTraceAsString(),
        ]);

        // Keep session data for retry unless it's a fatal error
        if ($this->isFatalError($e->getMessage())) {
            session()->forget(['pending_daily_payment', 'pending_payment_expires']);
        }

        return redirect()->route('agent.dashboard')
            ->with('error', 'Payment processing failed: ' . $this->getUserFriendlyError($e->getMessage()));
    }
}

private function validateSessionData(array $data): bool
{
    $required = ['rider_id', 'purchase_id', 'amount', 'reference', 'phone_number', 'created_at'];
    
    foreach ($required as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            return false;
        }
    }
    
    // Validate data types
    if (!is_numeric($data['rider_id']) || !is_numeric($data['purchase_id']) || !is_numeric($data['amount'])) {
        return false;
    }
    
    // Check if session is not too old (max 1 hour)
    $createdAt = Carbon::parse($data['created_at']);
    if ($createdAt->diffInHours(now()) > 1) {
        return false;
    }
    
    return true;
}

private function verifyPaymentWithPesapal(string $orderTrackingId): string
{
    $token = app(PesapalService::class)->getAccessToken();
    
    $response = Http::withToken($token)
        ->timeout(30)
        ->retry(3, 2000)
        ->get(config('pesapal.base_url') . '/api/Transactions/GetTransactionStatus', [
            'orderTrackingId' => $orderTrackingId
        ]);

    if (!$response->successful()) {
        \Log::error('❌ Failed to verify payment status', [
            'tracking_id' => $orderTrackingId,
            'status' => $response->status(),
            'response' => $response->body()
        ]);
        throw new \Exception('Unable to verify payment status with gateway');
    }

    $responseData = $response->json();
    
    if (!isset($responseData['payment_status_description'])) {
        \Log::error('❌ Invalid payment verification response', [
            'tracking_id' => $orderTrackingId,
            'response' => $responseData
        ]);
        throw new \Exception('Invalid payment verification response');
    }

    return strtolower($responseData['payment_status_description']);
}

private function createPaymentRecord(array $data, Purchase $purchase, string $orderTrackingId, string $today): MotorcyclePayment
{
    return MotorcyclePayment::create([
        'purchase_id' => $data['purchase_id'],
        'user_id' => $purchase->user_id,
        'payment_date' => $today,
        'amount' => $data['amount'],
        'type' => 'daily',
        'method' => 'pesapal',
        'reference' => $orderTrackingId,
        'note' => sprintf(
            'Daily payment via Pesapal. Agent: %s, Original Ref: %s',
            $data['agent_id'] ?? 'Unknown',
            $data['reference']
        ),
        'status' => 'paid'
    ]);
}

private function updatePurchaseBalances(Purchase $purchase, int $amount): void
{
    $purchase->increment('amount_paid', $amount);
    $purchase->decrement('remaining_balance', $amount);
    
    // Check if purchase is fully paid
    if ($purchase->fresh()->remaining_balance <= 0) {
        $purchase->update([
            'status' => 'cleared',
            'cleared_at' => now('Africa/Kampala')
        ]);
    }
}

private function getPaymentStatusMessage(string $status): string
{
    $messages = [
        'pending' => 'Payment is still pending. Please complete the payment process.',
        'failed' => 'Payment failed. Please try again.',
        'cancelled' => 'Payment was cancelled.',
        'invalid' => 'Payment verification failed.',
    ];

    return $messages[$status] ?? "Payment status: {$status}. Please contact support if this continues.";
}

private function isFatalError(string $error): bool
{
    $fatalErrors = [
        'Data inconsistency',
        'Purchase is no longer active',
        'Invalid payment verification response',
    ];

    foreach ($fatalErrors as $fatal) {
        if (str_contains($error, $fatal)) {
            return true;
        }
    }

    return false;
}

private function getUserFriendlyError(string $error): string
{
    $friendlyMessages = [
        'Missing OrderTrackingId' => 'Payment reference is missing. Please try again.',
        'Data inconsistency' => 'Payment data is inconsistent. Please initiate a new payment.',
        'Purchase is no longer active' => 'The motorcycle purchase is no longer active.',
        'Unable to verify payment status' => 'Unable to verify payment. Please contact support.',
        'Invalid payment verification response' => 'Payment verification failed. Please contact support.',
    ];

    foreach ($friendlyMessages as $key => $message) {
        if (str_contains($error, $key)) {
            return $message;
        }
    }

    return 'An unexpected error occurred. Please try again or contact support.';
}


}
