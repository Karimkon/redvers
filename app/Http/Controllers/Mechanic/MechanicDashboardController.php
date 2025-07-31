<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\MotorcycleUnit;
use App\Models\Maintenance;
use Illuminate\Support\Carbon;

class MechanicDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Stats
        $totalBikes = MotorcycleUnit::count();
        $repairsDone = Maintenance::where('mechanic_id', $user->id)->count();
        $recentRepairs = Maintenance::where('mechanic_id', $user->id)->latest()->take(5)->get();

        // Weekly maintenance chart data
        $chartData = collect(range(0, 6))->map(function ($i) use ($user) {
            $date = now()->subDays($i)->format('Y-m-d');
            return [
                'date' => Carbon::parse($date)->format('M d'),
                'count' => Maintenance::where('mechanic_id', $user->id)
                    ->whereDate('created_at', $date)
                    ->count(),
            ];
        })->reverse();

        return view('mechanic.dashboard', compact(
            'user', 'totalBikes', 'repairsDone', 'recentRepairs', 'chartData'
        ));
    }
}
