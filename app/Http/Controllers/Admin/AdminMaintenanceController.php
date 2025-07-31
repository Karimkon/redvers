<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use Illuminate\Http\Request;

class AdminMaintenanceController extends Controller
{
    public function index()
    {
        $records = Maintenance::with(['mechanic', 'motorcycleUnit.motorcycle'])->latest()->paginate(25);
        return view('admin.maintenances.index', compact('records'));
    }

    public function show(Maintenance $maintenance)
    {
        $maintenance->load(['mechanic', 'motorcycleUnit.motorcycle']);
        return view('admin.maintenances.show', compact('maintenance'));
    }
}
