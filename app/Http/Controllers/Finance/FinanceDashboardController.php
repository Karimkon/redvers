<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Station;
use App\Models\Swap;
use App\Models\Payment;
use App\Models\Battery;
use App\Models\Purchase;

class FinanceDashboardController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $period = $request->input('period');

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
        $paymentsCount = $query->count();

        // Charts Data
        $swapStats = ['labels' => [], 'counts' => []];
        $paymentStats = ['labels' => [], 'amounts' => []];

        foreach (range(6, 0) as $daysAgo) {
            $date = now()->subDays($daysAgo)->toDateString();
            $label = now()->subDays($daysAgo)->format('D');

            $swapStats['labels'][] = $label;
            $swapStats['counts'][] = Swap::whereDate('created_at', $date)->count();

            $paymentStats['labels'][] = $label;
            $paymentStats['amounts'][] = Payment::whereDate('created_at', $date)
                ->where('status', 'completed')
                ->sum('amount');
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
                        $stationPayments->whereMonth('created_at', now()->month);
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

        $allPurchases = Purchase::with(['payments', 'discounts', 'motorcycle'])->where('status', 'active')->get();

        $totalDue = $allPurchases->sum(function ($purchase) {
            return $purchase->getAdjustedOverdueSummary()['due_amount'] ?? 0;
        });


        return view('finance.dashboard', compact(
            'swapStats', 'paymentStats', 'revenueByStation', 'weeklyAverages',
            'topStation', 'totalRevenue', 'paymentsCount', 'startDate', 'endDate', 'period', 'totalDue'
        ));
    }
}
