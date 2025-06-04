<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Motorcycle;
use App\Models\MotorcycleUnit;

class MotorcycleUnitController extends Controller
{
    public function index()
    {
        $units = MotorcycleUnit::with('motorcycle')->latest()->get();
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
}
