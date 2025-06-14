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
        $trackingId = $request->get('order_tracking_id');

        if (!$trackingId) {
            return redirect()->route('agent.swaps.index')->with('error', 'Invalid callback');
        }

        try {
            $token = app(PesapalService::class)->getAccessToken();

            $response = Http::withToken($token)->get(
                config('pesapal.base_url') . '/api/Transactions/GetTransactionStatus',
                ['orderTrackingId' => $trackingId]
            );

            if (!$response->successful()) {
                return redirect()->route('agent.swaps.index')->with('error', 'Failed to verify payment.');
            }

            $status = strtolower($response['payment_status_description']);

            if ($status !== 'completed') {
                return redirect()->route('agent.swaps.index')->with('error', 'Payment not completed.');
            }

            // ✅ Fetch session data
            $data = session('pending_swap_data');
            $reference = session('pending_reference');

            // ✅ Clean session immediately to avoid stale state
            session()->forget(['pending_swap_data', 'pending_reference']);

            if (!$data || !$reference) {
                return redirect()->route('agent.swaps.index')->with('error', 'Session expired. Please try again.');
            }

            // ✅ Try to find existing payment first
            $payment = Payment::where('reference', $reference)->first();

            // ✅ Ensure the battery exists
            $battery = \App\Models\Battery::findOrFail($data['battery_id']);

            $amount = session('pending_amount') ?? $data['payable_amount'] ?? 0;

            // ✅ Create swap
            $swap = \App\Models\Swap::create([
                'rider_id' => $data['rider_id'],
                'motorcycle_unit_id' => $data['motorcycle_unit_id'],
                'station_id' => $data['station_id'],
                'agent_id' => auth()->id(),
                'battery_id' => $battery->id,
                'battery_returned_id' => $data['battery_returned_id'] ?? null,
                'percentage_difference' => $data['percentage_difference'],
                'payable_amount' => $amount,
                'payment_method' => 'pesapal',
                'swapped_at' => now(),
            ]);
            

            // Create or update payment
            if ($payment) {
                $payment->update([
                    'swap_id' => $swap->id,
                    'status' => 'completed',
                    'amount' => $amount ?? $payment->amount, // fallback if session lost
                    'pesapal_transaction_id' => $trackingId,
                    'method' => 'pesapal',
                    'reference' => $reference,
                ]);
            } else {
                    Payment::create([
                    'swap_id' => $swap->id,
                    'amount' => $amount,
                    'method' => 'pesapal',
                    'status' => 'completed',
                    'pesapal_transaction_id' => $trackingId,
                    'reference' => $reference,
                    'initiated_by' => 'agent',
                ]);
            }

            // ✅ Create Battery Swap log
            \App\Models\BatterySwap::create([
                'battery_id' => $battery->id,
                'swap_id' => $swap->id,
                'from_station_id' => $data['station_id'],
                'to_station_id' => $data['station_id'],
                'swapped_at' => now(),
            ]);

            // ✅ Update battery status
            $battery->update([
                'status' => 'in_use',
                'current_station_id' => null,
                'current_rider_id' => $data['rider_id'],
            ]);

            if (!empty($data['battery_returned_id'])) {
                \App\Models\Battery::where('id', $data['battery_returned_id'])->update([
                    'status' => 'charging',
                    'current_station_id' => $data['station_id'],
                    'current_rider_id' => null,
                ]);
            }

            return redirect()->route('agent.swaps.index')
                ->with('success', '✅ Payment received and swap completed successfully.');

        } catch (\Exception $e) {
            \Log::error('Pesapal callback error', ['msg' => $e->getMessage()]);
            return redirect()->route('agent.swaps.index')->with('error', 'Payment processing error.');
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
