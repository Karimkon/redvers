<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; // ✅ This was missing
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\Swap;
use App\Models\Payment;
use App\Models\Station;

class AgentDashboardController extends Controller
{
    public function index(Request $request)
{
    $agent = Auth::user();

    // Fetch agent's assigned station
    $station = $agent->station; // Assumes agent has station_id and relation defined

    if (!$station) {
        abort(403, 'No station assigned to this agent.');
    }

    $query = Swap::with(['riderUser', 'station'])
        ->where('agent_id', $agent->id)
        ->where('station_id', $station->id); // Auto-filter by agent’s station

    if ($request->filled('start_date')) {
        $query->whereDate('created_at', '>=', $request->start_date);
    }

    if ($request->filled('end_date')) {
        $query->whereDate('created_at', '<=', $request->end_date);
    }

    $totalSwaps = $query->count();
    $totalRevenue = $query->sum('payable_amount');
    $recentSwaps = $query->latest()->take(5)->get();
    $swapTimeline = $query->latest()->take(10)->get();

    $chartData = collect(range(0, 6))->map(function ($i) use ($agent, $station) {
        $date = now()->subDays($i)->format('Y-m-d');
        $count = Swap::where('agent_id', $agent->id)
            ->where('station_id', $station->id)
            ->whereDate('created_at', $date)
            ->count();
        return [
            'date' => \Carbon\Carbon::parse($date)->format('M d'),
            'count' => $count,
        ];
    })->reverse();

    return view('agent.dashboard', compact(
        'totalSwaps',
        'totalRevenue',
        'recentSwaps',
        'swapTimeline',
        'chartData',
        'station'
    ));
}
}