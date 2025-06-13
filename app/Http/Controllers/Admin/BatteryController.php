<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Battery;
use App\Models\Station;

class BatteryController extends Controller
{
    public function index(Request $request)
    {
        $stationId = $request->get('station_id');
        $search = $request->get('search');
        $stations = Station::all();

        $batteries = Battery::with('currentStation', 'currentRider')
            ->when($stationId, fn($query) => $query->where('current_station_id', $stationId))
            ->when($search, fn($query) => $query->where('serial_number', 'like', "%{$search}%"))
            ->latest()
            ->paginate(10);

        return view('admin.batteries.index', compact('batteries', 'stations', 'stationId', 'search'));
    }



    public function create()
    {
        $stations = Station::all();
        return view('admin.batteries.create', compact('stations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'serial_number' => 'required|string|unique:batteries,serial_number',
            'status' => 'required|in:in_stock,in_use,charging,damaged',
            'current_station_id' => 'nullable|exists:stations,id',
        ]);

        Battery::create($request->all());

        return redirect()->route('admin.batteries.index')->with('success', 'Battery created successfully.');
    }

    public function edit(Battery $battery)
    {
        $stations = Station::all();
        return view('admin.batteries.edit', compact('battery', 'stations'));
    }

    public function update(Request $request, Battery $battery)
    {
        $request->validate([
            'serial_number' => 'required|string|unique:batteries,serial_number,' . $battery->id,
            'status' => 'required|in:in_stock,in_use,charging,damaged',
            'current_station_id' => 'nullable|exists:stations,id',
        ]);

        $battery->update($request->all());

        return redirect()->route('admin.batteries.index')->with('success', 'Battery updated successfully.');
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
}
