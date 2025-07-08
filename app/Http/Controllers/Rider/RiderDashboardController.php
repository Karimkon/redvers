<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Swap;
use App\Models\Payment;
use App\Models\Battery;
use App\Models\Purchase;


class RiderDashboardController extends Controller
{
  public function index()
{
    $rider = Auth::user();

    $swaps = Swap::with(['station', 'battery', 'payment'])
        ->where('rider_id', $rider->id)
        ->orderByDesc('created_at')
        ->get();

    $recentSwaps = $swaps->take(7);
    $totalSwaps = $swaps->count();
    $totalRevenue = Payment::whereIn('swap_id', $rider->swaps->pluck('id'))->sum('amount');
    $swapStats = ['labels' => [], 'counts' => []];

    foreach (range(6, 0) as $daysAgo) {
        $date = now()->subDays($daysAgo)->toDateString();
        $swapStats['labels'][] = now()->subDays($daysAgo)->format('D');
        $swapStats['counts'][] = $swaps->whereBetween('created_at', [
            $date . ' 00:00:00', $date . ' 23:59:59'
        ])->count();
    }

    // 🔋 Current battery info
    $currentBattery = Battery::where('current_rider_id', $rider->id)
        ->where('status', 'in_use')
        ->latest()
        ->first();

    // 🏍️ Get latest purchase
    $purchase = $rider->purchases()->latest()->first();
    $remainingBalance = $purchase ? $purchase->remaining_balance : 0;

    // 📊 Only show summaries if purchase is active
    $scheduleSummary = $purchase && $purchase->status !== 'completed'
        ? $purchase->getPaymentScheduleSummary()
        : null;

    $overdueSummary = $purchase && $purchase->status !== 'completed'
        ? $purchase->getAdjustedOverdueSummary()
        : null;

    return view('rider.dashboard', compact(
        'rider', 'swaps', 'recentSwaps', 'totalSwaps', 'totalRevenue',
        'currentBattery', 'swapStats',
        'remainingBalance', 'scheduleSummary', 'overdueSummary', 'purchase'
    ));
}

}
