<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Battery;
use App\Models\Station;
use App\Models\User;

class BatteryController extends Controller
{
    public function index(Request $request)
    {
        $stationId = $request->get('station_id');
        $search = $request->get('search');
        $stations = Station::all();

        $batteries = Battery::with('currentStation', 'currentRider')
        ->when($stationId, fn($query) => $query->where('current_station_id', $stationId))
        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                // Search serial number
                $q->where('serial_number', 'like', "%{$search}%")
                  ->orWhereHas('currentRider', function ($riderQuery) use ($search) {
                      $riderQuery->where('name', 'like', "%{$search}%")
                                 ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        })
        ->latest()
        ->paginate(10);

    return view('admin.batteries.index', compact('batteries', 'stations', 'stationId', 'search'));
}

    public function create()
    {
        $stations = Station::all();
        $riders = User::where('role', 'rider')->get();
        return view('admin.batteries.create', compact('stations', 'riders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'serial_number' => 'required|string|unique:batteries,serial_number',
            'status' => 'required|in:in_stock,in_use,charging,damaged',
            'current_station_id' => 'nullable|exists:stations,id',
            'current_rider_id' => 'nullable|exists:users,id',
        ]);

        Battery::create($request->all());

        return redirect()->route('admin.batteries.index')->with('success', 'Battery created successfully.');
    }

    public function edit(Battery $battery)
    {
        $stations = Station::all();
        $riders = User::where('role', 'rider')->get();
        
        // Debug: Check if the battery has the rider relationship loaded
        if (config('app.debug')) {
            \Log::info("Editing battery {$battery->serial_number}:", [
                'current_rider_id' => $battery->current_rider_id,
                'rider_name' => $battery->currentRider?->name ?? 'null',
            ]);
        }
        
        return view('admin.batteries.edit', compact('battery', 'stations', 'riders'));
    }

    public function update(Request $request, Battery $battery)
    {
        $request->validate([
            'serial_number' => 'required|string|unique:batteries,serial_number,' . $battery->id,
            'status' => 'required|in:in_stock,in_use,charging,damaged',
            'current_station_id' => 'nullable|exists:stations,id',
            'current_rider_id' => 'nullable|exists:users,id',
        ]);

        $newRiderId = $request->current_rider_id;
        $oldRiderId = $battery->current_rider_id;

        // ✅ STEP 1: Check if this new rider already has another battery
        if ($newRiderId) {
            $existingBattery = Battery::where('current_rider_id', $newRiderId)
                ->where('id', '!=', $battery->id)
                ->first();
                
            if ($existingBattery) {
                // 🧠 Automatically remove the old battery from that rider
                $existingBattery->update([
                    'current_rider_id' => null,
                    'status' => 'in_stock' // optional: set to in_stock or charging
                ]);

                // ✅ Log swap-out for previous battery
                \App\Models\BatterySwap::create([
                    'battery_id' => $existingBattery->id,
                    'swap_id' => null,
                    'from_station_id' => $existingBattery->current_station_id,
                    'to_station_id' => $existingBattery->current_station_id,
                    'swapped_at' => now(),
                ]);
            }
        }

        // ✅ STEP 2: Update the current battery
        $battery->update([
            'serial_number' => $request->serial_number,
            'status' => $request->status,
            'current_station_id' => $request->current_station_id,
            'current_rider_id' => $newRiderId,
        ]);

        // ✅ STEP 3: Log the swap history if rider changed
        if ($oldRiderId != $newRiderId) {
            \App\Models\BatterySwap::create([
                'battery_id' => $battery->id,
                'swap_id' => null,
                'from_station_id' => $battery->current_station_id,
                'to_station_id' => $request->current_station_id,
                'swapped_at' => now(),
            ]);
        }

        return redirect()->route('admin.batteries.index')->with('success', 'Battery reassigned successfully.');
    }

    public function history(Battery $battery)
    {
        $swaps = \App\Models\BatterySwap::with('swap.station', 'swap.riderUser')
            ->where('battery_id', $battery->id)
            ->orderBy('swapped_at')
            ->get();

        return view('admin.batteries.history', compact('battery', 'swaps'));
    }

    public function destroy(Battery $battery)
    {
        $battery->delete();
        return redirect()->route('admin.batteries.index')->with('success', 'Battery deleted successfully.');
    }

    /**
     * Debug method to check relationships
     */
    public function debug()
    {
        if (!config('app.debug')) {
            abort(404);
        }

        $batteries = Battery::with('currentRider', 'currentStation')->take(5)->get();
        
        foreach ($batteries as $battery) {
            dump([
                'serial' => $battery->serial_number,
                'current_rider_id' => $battery->current_rider_id,
                'rider_loaded' => $battery->relationLoaded('currentRider'),
                'rider_exists' => $battery->currentRider !== null,
                'rider_name' => $battery->currentRider?->name,
                'rider_phone' => $battery->currentRider?->phone,
            ]);
        }

        // Also check if there are any riders with role 'rider'
        $riderCount = User::where('role', 'rider')->count();
        dump("Total riders: {$riderCount}");

        return response('Debug complete - check output above');
    }
}