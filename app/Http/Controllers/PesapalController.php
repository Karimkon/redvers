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
        $orderTrackingId = $request->get('OrderTrackingId');

        if (!$orderTrackingId) {
            Log::warning('Pesapal callback received without OrderTrackingId.');
            return response('Missing OrderTrackingId', 400);
        }

        $payment = Payment::where('external_reference', $orderTrackingId)->first();

        if (!$payment) {
            Log::warning("Payment not found for OrderTrackingId: {$orderTrackingId}");
            return response('Payment not found', 404);
        }

        try {
            $token = app(PesapalService::class)->getAccessToken();

            $response = Http::withToken($token)->get(config('pesapal.base_url') . '/api/Transactions/GetTransactionStatus', [
                'orderTrackingId' => $orderTrackingId,
            ]);

            if ($response->successful()) {
                $status = strtolower($response['payment_status']);

                if (in_array($status, ['completed', 'failed', 'pending'])) {
                    $payment->update(['status' => $status]);
                    Log::info("Payment status updated to {$status} for OrderTrackingId: {$orderTrackingId}");
                } else {
                    Log::warning("Unexpected status '{$status}' for OrderTrackingId: {$orderTrackingId}");
                }

                return response('Callback handled', 200);
            } else {
                Log::error("Failed to query transaction status: {$response->body()}");
                return response('Failed to retrieve transaction status', 500);
            }
        } catch (\Exception $e) {
            Log::error("Pesapal callback exception: " . $e->getMessage());
            return response('Internal Server Error', 500);
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
