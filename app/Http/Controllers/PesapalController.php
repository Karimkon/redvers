<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Payment;
use App\Services\PesapalService;

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
        $data = $request->all();

        Log::info('📥 Pesapal Callback Received', $data);

        if (!isset($data['order_tracking_id'])) {
            return response()->json(['error' => 'Missing tracking ID'], 400);
        }

        // Find payment by pesapal_transaction_id
        $payment = Payment::where('pesapal_transaction_id', $data['order_tracking_id'])->first();

        if (!$payment) {
            Log::warning('⚠️ Payment not found for callback', ['tracking_id' => $data['order_tracking_id']]);
            return response()->json(['error' => 'Payment not found'], 404);
        }

        // You can further validate or call Pesapal API for confirmation if needed

        // Update payment status (assumes success, adjust if more fields needed)
        $payment->status = 'completed'; // or use $data['status'] if returned
        $payment->save();

        return response()->json(['message' => 'Callback processed'], 200);
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


    public function testSubmitOrder()
{
    try {
        $token = app(\App\Services\PesapalService::class)->getAccessToken();

        $response = Http::withToken($token)
            ->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->post(config('pesapal.base_url') . '/api/Transactions/SubmitOrderRequest', [
                "id" => null,
                "currency" => "UGX",
                "amount" => 12000,
                "description" => "Manual API Test - Battery Swap",
                "callback_url" => route('pesapal.callback'),
                "billing_address" => [
                    "email_address" => "test@redvers.com",
                    "phone_number" => "0700000000",
                    "first_name" => "Redvers",
                    "last_name" => "Tester",
                    "line_1" => "Naguru",
                    "city" => "Kampala",
                    "state" => "Central",
                    "postal_code" => "256",
                    "zip_code" => "256",
                    "country_code" => "UG"
                ]
            ]);

        if ($response->successful()) {
            return redirect()->away($response['redirect_url']); // opens Pesapal payment page
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Failed to submit order',
                'response' => $response->json()
            ]);
        }
    } catch (\Exception $e) {
        return response()->json([
            'error' => true,
            'message' => $e->getMessage()
        ], 500);
    }
}

}
