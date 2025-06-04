<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Station;
use App\Models\Swap;
use App\Models\Payment;
use App\Models\Battery;
use Illuminate\Http\Request;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $batteryStatusCounts = [
        'in_stock' => Battery::where('status', 'in_stock')->count(),
        'in_use' => Battery::where('status', 'in_use')->count(),
        'charging' => Battery::where('status', 'charging')->count(),
        'damaged' => Battery::where('status', 'damaged')->count(),
    ];

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $period = $request->input('period'); // e.g., 'today', 'week', 'month', 'year'

        $query = Payment::query()->where('status', 'completed');

        if ($period) {
            switch ($period) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month);
                    break;
                case 'year':
                    $query->whereYear('created_at', now()->year);
                    break;
            }
        } elseif ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $totalRevenue = $query->sum('amount');

        // ✅ Use unified users table with role filtering
        $ridersCount = User::where('role', 'rider')->count();
        $agentsCount = User::where('role', 'agent')->count();
        $stationsCount = Station::count();
        $swapsCount = Swap::count();
        $paymentsCount = Payment::count();

        // Charts data
        $swapStats = ['labels' => [], 'counts' => []];
        $paymentStats = ['labels' => [], 'amounts' => []];

        foreach (range(6, 0) as $daysAgo) {
            $date = now()->subDays($daysAgo)->toDateString();
            $label = now()->subDays($daysAgo)->format('D');

            $swapStats['labels'][] = $label;
            $swapStats['counts'][] = Swap::whereDate('created_at', $date)->count();

            $paymentStats['labels'][] = $label;
            $paymentStats['amounts'][] = Payment::whereDate('created_at', $date)->sum('amount');
        }

        // Revenue by Station
        $revenueByStation = [];
        $stations = Station::all();

        foreach ($stations as $station) {
            $stationPayments = Payment::where('status', 'completed')
                ->whereHas('swap', function ($query) use ($station) {
                    $query->where('station_id', $station->id);
                });

            if ($period) {
                switch ($period) {
                    case 'today':
                        $stationPayments->whereDate('created_at', today());
                        break;
                    case 'week':
                        $stationPayments->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                        break;
                    case 'month':
                        $stationPayments->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                        break;
                    case 'year':
                        $stationPayments->whereYear('created_at', now()->year);
                        break;
                }
            } elseif ($startDate && $endDate) {
                $stationPayments->whereBetween('created_at', [$startDate, $endDate]);
            }

            $revenueByStation[$station->name] = $stationPayments->sum('amount');
        }

        $weeklyAverages = [
            'swaps' => round(Swap::where('created_at', '>=', now()->subDays(7))->count() / 7, 2),
            'payments' => round(Payment::where('created_at', '>=', now()->subDays(7))->count() / 7, 2),
        ];

        $topStation = collect($revenueByStation)->sortDesc()->keys()->first();

        return view('admin.dashboard', compact(
            'ridersCount', 'stationsCount', 'swapsCount', 'agentsCount', 'paymentsCount',
            'swapStats', 'paymentStats', 'revenueByStation', 'weeklyAverages', 'topStation',
            'totalRevenue', 'startDate', 'endDate', 'period', 'batteryStatusCounts'
        ));
    }
}
