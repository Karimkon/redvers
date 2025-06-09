<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\{Swap, Station, Payment, User};
use App\Services\MtnMomoService;
use App\Models\BatterySwap; // ensure this is at the top if not already

class SwapController extends Controller
{
    public function index()
    {
        $swaps = Swap::with(['riderUser', 'station', 'agentUser'])->latest()->paginate(20);
        return view('admin.swaps.index', compact('swaps'));
    }

    public function create()
    {
        $riders = \App\Models\User::where('role', 'rider')
        ->with(['purchases' => function ($query) {
            $query->where('status', 'active')->latest();
        }, 'purchases.motorcycleUnit']) // eager-load
        ->get();    

        $agents = User::where('role', 'agent')->get();
        $stations = Station::all();

        $availableBatteries = \App\Models\Battery::whereIn('status', ['in_stock', 'charging'])->get();

        return view('admin.swaps.create', compact('riders', 'agents', 'stations', 'availableBatteries'));
    }


public function store(Request $request)
{
    $request->validate([
        'rider_id' => 'required|exists:users,id',
        'motorcycle_unit_id' => 'required|exists:motorcycle_units,id',
        'station_id' => 'required|exists:stations,id',
        'battery_id' => 'required|exists:batteries,id',
        'agent_id' => 'nullable|exists:users,id',
        'percentage_difference' => 'required|numeric|min:0|max:100',
        'payment_method' => 'nullable|in:mtn,airtel,pesapal',
        'battery_returned_id' => 'nullable|exists:batteries,id',
    ]);

    \DB::beginTransaction();

    try {
        $isNewRider = !\App\Models\Swap::where('rider_id', $request->rider_id)->exists();

        $basePrice = config('billing.base_price', 15000);
        $missing = 100 - $request->percentage_difference;
        $amount = ($missing / 100) * $basePrice;

        $swap = \App\Models\Swap::create([
            'rider_id' => $request->rider_id,
            'motorcycle_unit_id' => $request->motorcycle_unit_id,
            'station_id' => $request->station_id,
            'agent_id' => $request->agent_id,
            'battery_id' => $request->battery_id,
            'battery_returned_id' => $request->battery_returned_id,
            'percentage_difference' => $request->percentage_difference,
            'payable_amount' => $isNewRider ? 0 : $amount,
            'payment_method' => $request->payment_method,
            'swapped_at' => now(),
        ]);

        // Assign new battery
        \App\Models\Battery::find($request->battery_id)->update([
            'status' => 'in_use',
            'current_station_id' => null,
            'current_rider_id' => $request->rider_id,
        ]);

        // ✅ Handle returned battery if present
        if ($request->filled('battery_returned_id')) {
    $returnedBattery = \App\Models\Battery::find($request->battery_returned_id);

    if ($returnedBattery) {
        $fromStationId = $returnedBattery->current_station_id ?? $request->station_id;

        $returnedBattery->update([
            'status' => 'charging',
            'current_station_id' => $request->station_id,
            'current_rider_id' => null, // ✅ Unassign rider from returned battery
        ]);

        \App\Models\BatterySwap::create([
            'battery_id' => $returnedBattery->id,
            'swap_id' => $swap->id,
            'from_station_id' => $fromStationId,
            'to_station_id' => $request->station_id,
            'swapped_at' => now(),
        ]);

        \Log::info('Battery swap recorded', [
            'swap_id' => $swap->id,
            'battery_id' => $returnedBattery->id,
        ]);
    }
}


        // ✅ Create payment
        if ($request->payment_method && !$isNewRider) {
            $payment = \App\Models\Payment::create([
                'swap_id' => $swap->id,
                'amount' => $amount,
                'method' => $request->payment_method,
                'status' => 'pending',
                'reference' => 'SWAP-' . strtoupper($request->payment_method) . '-' . uniqid(),
                'initiated_by' => 'admin',
            ]);

            if ($request->payment_method === 'pesapal') {
        $rider = \App\Models\User::find($request->rider_id);

        $order = [
            'id' => Str::uuid()->toString(),
            'currency' => 'UGX',
            'amount' => $request->payable_amount,
            'description' => 'Battery Swap',
            'callback_url' => route('pesapal.callback'),
            'notification_id' => '34f2ce63-9c4c-430d-adb8-dbba55243d85', 
            'billing_address' => [
                'email_address' => $rider->email,
                'phone_number' => $rider->phone,
                'first_name' => $rider->name,
                'last_name' => '',
                'line_1' => '',
                'city' => 'Kampala',
                'state' => 'Central',
                'postal_code' => '256',
                'country_code' => 'UG',
            ],
        ];
        $pesapal = app(\App\Services\PesapalService::class);


        $response = $pesapal->initiatePayment($order);

        if (isset($response['redirect_url'])) {
            return redirect($response['redirect_url']);
        }

        return back()->with('error', 'Pesapal payment initiation failed.');
    }
    
        }


        \DB::commit();
        return redirect()->route('admin.swaps.index')->with('success', 'Swap created successfully.');
    } catch (\Exception $e) {
        \DB::rollBack();
        \Log::error('Swap failed', ['error' => $e->getMessage()]);
        return redirect()->back()->withErrors(['error' => 'Swap failed: ' . $e->getMessage()]);
    }
}




    public function show($id)
    {
        $swap = Swap::with(['riderUser', 'station', 'agentUser'])->findOrFail($id);
        return view('admin.swaps.show', compact('swap'));
    }

public function edit($id)
{
    $swap = Swap::findOrFail($id);

    $riders = User::where('role', 'rider')
        ->with(['purchases' => function ($query) {
            $query->where('status', 'active')->latest();
        }, 'purchases.motorcycleUnit'])
        ->get();

    $agents = User::where('role', 'agent')->get();
    $stations = Station::all();

    // Batteries list for dropdowns
    $batteries = \App\Models\Battery::whereIn('status', ['in_stock', 'charging', 'in_use'])->get();

    return view('admin.swaps.edit', compact('swap', 'riders', 'stations', 'agents', 'batteries'));
}


    public function update(Request $request, $id)
{
    $swap = Swap::findOrFail($id);

    $request->validate([
        'rider_id' => 'required|exists:users,id',
        'motorcycle_unit_id' => 'required|exists:motorcycle_units,id',
        'station_id' => 'required|exists:stations,id',
        'battery_id' => 'required|exists:batteries,id',
        'battery_returned_id' => 'nullable|exists:batteries,id',
        'agent_id' => 'nullable|exists:users,id',
        'percentage_difference' => 'required|numeric|min:0|max:100',
        'payment_method' => 'nullable|in:mtn,airtel,pesapal',
    ]);

    $basePrice = config('billing.base_price', 15000);
    $missing = 100 - $request->percentage_difference;
    $payableAmount = ($missing / 100) * $basePrice;

    $swap->update([
        'rider_id' => $request->rider_id,
        'motorcycle_unit_id' => $request->motorcycle_unit_id,
        'station_id' => $request->station_id,
        'battery_id' => $request->battery_id,
        'battery_returned_id' => $request->battery_returned_id,
        'agent_id' => $request->agent_id,
        'percentage_difference' => $request->percentage_difference,
        'payable_amount' => $payableAmount,
        'payment_method' => $request->payment_method,
        'swapped_at' => now(),
    ]);

    return redirect()->route('admin.swaps.index')->with('success', 'Swap updated successfully.');
}

    public function destroy($id)
    {
        Swap::findOrFail($id)->delete();
        return redirect()->route('admin.swaps.index')->with('success', 'Swap deleted.');
    }
}
