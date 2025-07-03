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
        $this->cleanDuplicateAlerts();  
        
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
        LowStockAlert::updateOrCreate(          // ← change here
            [
                'part_id'  => $part->id,
                'resolved' => false,
            ],
            [
                'shop_id'             => $part->shop_id,
                'remaining_quantity'  => $part->stock,   // will be updated
            ]
        );
    }
}

protected function cleanDuplicateAlerts(): void
{
    // Find duplicate unresolved alerts (same part_id, keep newest)
    $duplicates = LowStockAlert::where('resolved', false)
        ->select('id', 'part_id')
        ->orderByDesc('id')              // newest first
        ->get()
        ->groupBy('part_id');

    foreach ($duplicates as $partAlerts) {
        // Keep the newest alert → everything after the first ID is a duplicate
        $idsToRemove = $partAlerts->skip(1)->pluck('id');
        if ($idsToRemove->isNotEmpty()) {
            LowStockAlert::whereIn('id', $idsToRemove)->delete(); // or ->update(['resolved'=>true])
        }
    }
}


    public function resolve(LowStockAlert $alert)
    {
        $alert->update(['resolved' => true]);

        return redirect()->back()->with('success', 'Alert marked as resolved.');
    }
}

