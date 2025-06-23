<?php

namespace App\Http\Controllers\Agent;
use Illuminate\Support\Facades\DB;
use App\Services\PesapalService;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\{Swap, Station, Payment, User, Battery, BatterySwap};
use Illuminate\Support\Facades\Auth;
use App\Models\SwapPromotion;

class AgentSwapController extends Controller
{
    public function index()
    {
        $swaps = Swap::where('agent_id', Auth::id())
            ->with(['riderUser', 'station', 'payment'])
            ->latest()
            ->paginate(20);

        return view('agent.swaps.index', compact('swaps'));
    }

    public function create()
    {
         $riders = \App\Models\User::where('role', 'rider')
        ->with(['purchases' => function ($query) {
            $query->whereIn('status', ['active', 'completed'])->latest();
        }, 'purchases.motorcycleUnit']) // eager-load the motorcycle unit
        ->get();

        $stationId = Auth::user()->station_id;

        // Only show available batteries at agent's station and charging ones
       $availableBatteries = Battery::where('current_station_id', auth()->user()->station_id)
        ->whereIn('status', ['in_stock', 'charging']) // ✅ Fetch both
        ->get();

        $returnableBatteries = Battery::where('status', 'in_use') // Rider brings used battery
        ->get(); // optionally filter by rider if you track it per rider


        return view('agent.swaps.create', compact('riders', 'availableBatteries', 'returnableBatteries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rider_id' => 'required|exists:users,id',
            'motorcycle_unit_id' => 'required|exists:motorcycle_units,id',
            'station_id' => 'required|exists:stations,id',
            'battery_id' => 'required|exists:batteries,id',
            'battery_returned_id' => 'nullable|exists:batteries,id',
            'percentage_difference' => 'required|numeric|min:0|max:100',
            'payment_method' => 'nullable|in:mtn,airtel,pesapal',
        ]);

        $battery = Battery::where('id', $request->battery_id)
            ->where('current_station_id', $request->station_id)
            ->whereIn('status', ['in_stock', 'charging'])
            ->firstOrFail();

        $isFirstTime = Swap::where('rider_id', $request->rider_id)->count() === 0;

        // Check if rider has active promotion
        $activePromo = SwapPromotion::where('rider_id', $request->rider_id)
            ->where('status', 'active')
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())
            ->latest()
            ->first();

        $currentBattery = Battery::where('current_rider_id', $request->rider_id)
            ->where('status', 'in_use')
            ->first();

            if (!$isFirstTime && $request->filled('battery_returned_id')) {
                if ($currentBattery && (int) $request->battery_returned_id !== (int) $currentBattery->id) {
                    return back()->withErrors([
                        'battery_returned_id' => 'Returned battery does not match the last one currently assigned to this rider.',
                    ])->withInput();
            }
        }

        $basePrice = config('billing.base_price', 15000);
        $missingPercentage = $isFirstTime ? 0 : 100 - $request->percentage_difference;

        $payableAmount = $activePromo
            ? 0 // Promo active — only motorcycle fee paid earlier
            : ($missingPercentage / 100) * $basePrice;


        if ($payableAmount <= 0) {
            return $this->finalizeSwap($request, $battery, 0, null, 'completed');
        }

        // Store in session for delayed save
        if ($request->payment_method === 'pesapal') {
            try {
                $reference = 'SWAP-PESAPAL-' . uniqid();
                $token = app(PesapalService::class)->getAccessToken();
                $rider = User::find($request->rider_id);

                session([
                    'pending_swap_data' => $request->all(),
                    'pending_reference' => $reference,
                    'pending_amount' => $payableAmount,
                ]);

                session()->save(); // ✅ Force session write

                $response = Http::withToken($token)->post(config('pesapal.base_url') . '/api/Transactions/SubmitOrderRequest', [
                    "id" => Str::uuid()->toString(),
                    "currency" => "UGX",
                    "amount" => $payableAmount,
                    "description" => "Battery Swap Payment",
                    "callback_url" => route('pesapal.callback'),
                    "notification_id" => "34f2ce63-9c4c-430d-adb8-dbba55243d85",
                    "merchant_reference"=> $reference, 
                    "billing_address" => [
                        "email_address" => $rider->email,
                        "phone_number" => $rider->phone,
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
                \Log::error('Pesapal Error: ' . $e->getMessage());
                return back()->withErrors(['pesapal' => 'Error: ' . $e->getMessage()])->withInput();
            }
        }

        return $this->finalizeSwap($request, $battery, $payableAmount, $request->payment_method, 'pending');
    }


     public function finalizeSwap($request, $battery, $amount, $method = null, $status = 'completed')
    {
        $reference = 'SWAP-' . uniqid();

        DB::beginTransaction();

        try {
            $swap = Swap::create([
                'rider_id' => $request->rider_id,
                'motorcycle_unit_id' => $request->motorcycle_unit_id,
                'station_id' => $request->station_id,
                'agent_id' => auth()->id(),
                'battery_id' => $battery->id,
                'battery_returned_id' => $request->battery_returned_id,
                'percentage_difference' => $request->percentage_difference,
                'payable_amount' => $amount,
                'payment_method' => $method,
                'swapped_at' => now(),
            ]);

            BatterySwap::create([
                'battery_id' => $battery->id,
                'swap_id' => $swap->id,
                'from_station_id' => $request->station_id,
                'to_station_id' => $request->station_id,
                'swapped_at' => now(),
            ]);

            $battery->update([
                'status' => 'in_use',
                'current_station_id' => null,
                'current_rider_id' => $request->rider_id,
            ]);

            if ($request->filled('battery_returned_id')) {
                Battery::where('id', $request->battery_returned_id)->update([
                    'status' => 'charging',
                    'current_station_id' => $request->station_id,
                    'current_rider_id' => null,
                ]);
            }

            if ($amount > 0) {
                Payment::create([
                    'swap_id' => $swap->id,
                    'amount' => $amount,
                    'method' => $method,
                    'status' => $status,
                    'reference' => $reference,
                    'initiated_by' => 'agent',
                ]);
            }

            DB::commit();
            return redirect()->route('agent.swaps.index')->with('success', 'Swap created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Swap Finalization Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to finalize swap.')->withInput();
        }
    }


    public function show($id)
    {
        $swap = Swap::where('agent_id', Auth::id())
            ->with(['riderUser', 'station', 'returnedBattery', 'batterySwap.battery'])
            ->findOrFail($id);

        return view('agent.swaps.show', compact('swap'));
    }


    public function destroy($id)
    {
        $swap = Swap::where('agent_id', Auth::id())->findOrFail($id);
        $swap->delete();
        return redirect()->route('agent.swaps.index')->with('success', 'Swap deleted.');
    }
}
