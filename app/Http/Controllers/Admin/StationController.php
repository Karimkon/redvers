<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Station;

class StationController extends Controller
{
    public function index()
    {
        $stations = Station::latest()->paginate(10);
        return view('admin.stations.index', compact('stations'));
    }

    public function create()
    {
        return view('admin.stations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        Station::create($validated);

        return redirect()->route('admin.stations.index')->with('success', 'Station created successfully.');
    }

    public function show(Station $station)
    {
        return view('admin.stations.show', compact('station'));
    }

    public function edit(Station $station)
    {
        return view('admin.stations.edit', compact('station'));
    }

    public function update(Request $request, Station $station)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $station->update($validated);

        return redirect()->route('admin.stations.index')->with('success', 'Station updated successfully.');
    }

    public function destroy(Station $station)
    {
        $station->delete();
        return redirect()->route('admin.stations.index')->with('success', 'Station deleted successfully.');
    }
}
