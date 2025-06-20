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
            $reference = session('pending_reference');
            $swapData = session('pending_swap_data');
            $amount = session('pending_amount');

            if (!$reference || !$swapData) {
                \Log::error('Pesapal callback: Missing session data.');
                return redirect()->route('agent.swaps.index')->with('error', 'Session expired. Please try again.');
            }

            $token = app(PesapalService::class)->getAccessToken();

            $statusResponse = Http::withToken($token)
                ->get(config('pesapal.base_url') . '/api/Transactions/GetTransactionStatus', [
                    'orderTrackingId' => $request->get('OrderTrackingId'),
                ]);

            if (!$statusResponse->successful() || $statusResponse['payment_status'] !== 'COMPLETED') {
                return redirect()->route('agent.swaps.index')->with('error', 'Payment failed or not confirmed.');
            }

            // Wrap in DB transaction
            DB::transaction(function () use ($swapData, $amount, $reference) {
                $battery = Battery::findOrFail($swapData['battery_id']);

                $swap = Swap::create([
                    'rider_id' => $swapData['rider_id'],
                    'motorcycle_unit_id' => $swapData['motorcycle_unit_id'],
                    'station_id' => $swapData['station_id'],
                    'agent_id' => auth()->id(),
                    'battery_id' => $battery->id,
                    'battery_returned_id' => $swapData['battery_returned_id'],
                    'percentage_difference' => $swapData['percentage_difference'],
                    'payable_amount' => $amount,
                    'payment_method' => 'pesapal',
                    'swapped_at' => now(),
                ]);

                BatterySwap::create([
                    'battery_id' => $battery->id,
                    'swap_id' => $swap->id,
                    'from_station_id' => $swapData['station_id'],
                    'to_station_id' => $swapData['station_id'],
                    'swapped_at' => now(),
                ]);

                $battery->update([
                    'status' => 'in_use',
                    'current_station_id' => null,
                    'current_rider_id' => $swapData['rider_id'],
                ]);

                if (!empty($swapData['battery_returned_id'])) {
                    Battery::where('id', $swapData['battery_returned_id'])->update([
                        'status' => 'charging',
                        'current_station_id' => $swapData['station_id'],
                        'current_rider_id' => null,
                    ]);
                }

                Payment::create([
                    'swap_id' => $swap->id,
                    'amount' => $amount,
                    'method' => 'pesapal',
                    'status' => 'completed',
                    'reference' => $reference,
                    'initiated_by' => 'agent',
                ]);
            });

            session()->forget(['pending_swap_data', 'pending_reference', 'pending_amount']);

            return redirect()->route('agent.swaps.index')->with('success', 'Payment successful and swap created.');

        } catch (\Exception $e) {
            \Log::error('Pesapal Callback Error: ' . $e->getMessage());
            return redirect()->route('agent.swaps.index')->with('error', 'Unexpected error during payment confirmation.');
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



}
