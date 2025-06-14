<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Motorcycle;
use App\Models\MotorcycleUnit;
use App\Models\Purchase;


class MotorcycleUnitController extends Controller
{
    public function index()
    {
        $units = MotorcycleUnit::with('motorcycle')->latest()->get();
        // Load assigned riders (if any)
        foreach ($units as $unit) {
            $unit->assigned_purchase = Purchase::with('user')
                ->where('motorcycle_unit_id', $unit->id)
                ->where('status', 'active')
                ->latest()
                ->first();
        }
        return view('admin.motorcycle-units.index', compact('units'));
    }

    public function create()
    {
        $motorcycles = Motorcycle::all();
        return view('admin.motorcycle-units.create', compact('motorcycles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'motorcycle_id' => 'required|exists:motorcycles,id',
            'number_plate' => 'required|unique:motorcycle_units,number_plate',
        ]);

        MotorcycleUnit::create([
            'motorcycle_id' => $request->motorcycle_id,
            'number_plate' => strtoupper($request->number_plate),
            'status' => 'available',
        ]);

        return redirect()->route('admin.motorcycle-units.index')->with('success', 'Motorcycle unit added successfully.');
    }

    public function edit(MotorcycleUnit $motorcycleUnit)
    {
        $motorcycles = Motorcycle::all();
        return view('admin.motorcycle-units.edit', compact('motorcycleUnit', 'motorcycles'));
    }

    public function update(Request $request, MotorcycleUnit $motorcycleUnit)
    {
        $request->validate([
            'motorcycle_id' => 'required|exists:motorcycles,id',
            'number_plate' => 'required|unique:motorcycle_units,number_plate,' . $motorcycleUnit->id,
            'status' => 'required|in:available,assigned,damaged',
        ]);

        $motorcycleUnit->update([
            'motorcycle_id' => $request->motorcycle_id,
            'number_plate' => strtoupper($request->number_plate),
            'status' => $request->status,
        ]);

        return redirect()->route('admin.motorcycle-units.index')->with('success', 'Motorcycle unit updated.');
    }

    public function destroy(MotorcycleUnit $motorcycleUnit)
    {
        $motorcycleUnit->delete();
        return redirect()->route('admin.motorcycle-units.index')->with('success', 'Motorcycle unit deleted.');
    }
}
