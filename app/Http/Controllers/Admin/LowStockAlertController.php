<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LowStockAlert;
use Illuminate\Http\Request;

class LowStockAlertController extends Controller
{
    public function index()
    {
        $alerts = LowStockAlert::with('part', 'shop')
            ->where('resolved', false)
            ->latest()
            ->paginate(20);

        return view('admin.low_stock_alerts.index', compact('alerts'));
    }

    public function resolve(LowStockAlert $alert)
    {
        $alert->update(['resolved' => true]);

        return redirect()->back()->with('success', 'Alert marked as resolved.');
    }
}

