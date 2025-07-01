<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LowStockAlert;
use Illuminate\Http\Request;

class LowStockAlertController extends Controller
{
    public function index()
    {
        $this->generateMissingLowStockAlerts();
        $alerts = LowStockAlert::with('part', 'shop')
            ->where('resolved', false)
            ->latest()
            ->paginate(20);

        return view('admin.low_stock_alerts.index', compact('alerts'));
    }

    protected function generateMissingLowStockAlerts()
    {
        $threshold = 5;

        $lowStockParts = \App\Models\Part::where('stock', '<=', $threshold)->get();

        foreach ($lowStockParts as $part) {
            LowStockAlert::firstOrCreate(
                [
                    'part_id' => $part->id,
                    'resolved' => false,
                ],
                [
                    'shop_id' => $part->shop_id,
                    'remaining_quantity' => $part->stock,
                ]
            );
        }
    }

    public function resolve(LowStockAlert $alert)
    {
        $alert->update(['resolved' => true]);

        return redirect()->back()->with('success', 'Alert marked as resolved.');
    }
}

