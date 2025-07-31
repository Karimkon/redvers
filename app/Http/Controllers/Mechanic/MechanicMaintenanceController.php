<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Maintenance;
use App\Models\MotorcycleUnit;
use Illuminate\Support\Facades\Auth;

class MechanicMaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Maintenance::with(['motorcycleUnit.motorcycle', 'motorcycleUnit.purchase.user'])
            ->where('mechanic_id', Auth::id());

        if ($request->filled('q')) {
            $search = $request->q;

            $query->whereHas('motorcycleUnit', function ($q) use ($search) {
                $q->where('number_plate', 'like', "%{$search}%")
                  ->orWhereHas('purchase.user', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        $repairs = $query->latest()->paginate(20)->withQueryString();

        return view('mechanic.maintenances.index', compact('repairs'));
    }

    public function create()
    {
        $units = MotorcycleUnit::with(['motorcycle', 'purchase.user'])->get();
        return view('mechanic.maintenances.create', compact('units'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'motorcycle_unit_id' => 'required|exists:motorcycle_units,id',
            'reported_issue'     => 'required|string',
            'diagnosis'          => 'nullable|string',
            'action_taken'       => 'nullable|string',
            'status'             => 'required|in:pending,in_progress,resolved',
            'repair_date'        => 'nullable|date',
        ]);

        $data['mechanic_id'] = Auth::id();

        Maintenance::create($data);

        return redirect()->route('mechanic.maintenances.index')->with('success', 'Maintenance record saved.');
    }

    public function show(Maintenance $maintenance)
    {
        // $this->authorize('view', $maintenance); ❌ Removed
        return view('mechanic.maintenances.show', compact('maintenance'));
    }

    public function edit(Maintenance $maintenance)
    {
        // $this->authorize('update', $maintenance); ❌ Removed
        $units = MotorcycleUnit::with(['motorcycle', 'purchase.user'])->get();
        return view('mechanic.maintenances.edit', compact('maintenance', 'units'));
    }

    public function update(Request $request, Maintenance $maintenance)
    {
        // $this->authorize('update', $maintenance); ❌ Removed

        $data = $request->validate([
            'reported_issue' => 'required|string',
            'diagnosis'      => 'nullable|string',
            'action_taken'   => 'nullable|string',
            'status'         => 'required|in:pending,in_progress,resolved',
            'repair_date'    => 'nullable|date',
        ]);

        $maintenance->update($data);

        return redirect()->route('mechanic.maintenances.index')->with('success', 'Maintenance updated.');
    }

    public function destroy(Maintenance $maintenance)
    {
        // $this->authorize('delete', $maintenance); ❌ Removed
        $maintenance->delete();

        return back()->with('success', 'Maintenance deleted.');
    }
}
