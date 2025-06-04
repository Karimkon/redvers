<?php

namespace App\Http\Controllers\Agent;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Swap, Station, Payment, User, Battery, BatterySwap};
use Illuminate\Support\Facades\Auth;

class AgentSwapController extends Controller
{
    public function index()
    {
        $swaps = Swap::where('agent_id', Auth::id())
            ->with(['riderUser', 'station'])
            ->latest()
            ->paginate(20);

        return view('agent.swaps.index', compact('swaps'));
    }

    public function create()
    {
         $riders = \App\Models\User::where('role', 'rider')
        ->with(['purchases' => function ($query) {
            $query->where('status', 'active')->latest();
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
        'battery_returned_id' => 'nullable|exists:batteries,id', // ✅ Optional now
        'percentage_difference' => 'required|numeric|min:0|max:100',
        'payment_method' => 'nullable|in:mtn,airtel,pesapal',
    ]);

    $battery = Battery::where('id', $request->battery_id)
        ->where('current_station_id', $request->station_id)
        ->whereIn('status', ['in_stock', 'charging'])
        ->firstOrFail();

    // Check if rider has previous swap
    $isFirstTime = Swap::where('rider_id', $request->rider_id)->count() === 0;

if (!$isFirstTime && $request->filled('battery_returned_id')) {
    $lastSwap = Swap::where('rider_id', $request->rider_id)->latest()->first();
    $lastBatterySwap = BatterySwap::where('swap_id', $lastSwap->id)->first();

    if (
        $lastSwap &&
        $lastSwap->percentage_difference < 100 &&
        (int) $request->battery_returned_id !== (int) $lastSwap->battery_id
    ) {
        return back()->withErrors([
            'battery_returned_id' => 'Returned battery does not match the last one assigned to this rider.',
        ])->withInput();
    }
}


    $basePrice = config('billing.base_price', 15000);
    $missingPercentage = $isFirstTime ? 0 : 100 - $request->percentage_difference;
    $payableAmount = ($missingPercentage / 100) * $basePrice;

    $swap = Swap::create([
        'rider_id' => $request->rider_id,
        'motorcycle_unit_id' => $request->motorcycle_unit_id, // ✅ Add this line
        'station_id' => $request->station_id,
        'agent_id' => auth()->id(),
        'battery_id' => $battery->id,
        'battery_returned_id' => $request->battery_returned_id, // ✅ This line tracks the returned battery
        'percentage_difference' => $request->percentage_difference,
        'payable_amount' => $payableAmount,
        'payment_method' => $request->payment_method,
        'swapped_at' => now(),
    ]);

    // Mark assigned battery as in_use
    $battery->update([
        'status' => 'in_use',
        'current_station_id' => null,
        'current_rider_id' => $request->rider_id, // ✅ To track rider's current battery
    ]);

    BatterySwap::create([
    'battery_id' => $battery->id,
    'swap_id' => $swap->id,
    'from_station_id' => $request->station_id,
    'to_station_id' => $request->station_id, // ✅ fix: station receiving the battery
    'swapped_at' => now(),
]);


    // ✅ If battery was returned, mark it as charging
    if ($request->filled('battery_returned_id')) {
        Battery::where('id', $request->battery_returned_id)->update([
            'status' => 'charging',
            'current_station_id' => $request->station_id,
            'current_rider_id' => null, // ✅ rider no longer has this battery
        ]);
    }

    if ($request->payment_method && $payableAmount > 0) {
        Payment::create([
            'swap_id' => $swap->id,
            'amount' => $payableAmount,
            'method' => $request->payment_method,
            'status' => 'pending',
            'reference' => 'SWAP-' . strtoupper($request->payment_method) . '-' . uniqid(),
            'initiated_by' => 'agent',
        ]);
    }

    return redirect()->route('agent.swaps.index')->with('success', 'Swap created successfully.');
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
