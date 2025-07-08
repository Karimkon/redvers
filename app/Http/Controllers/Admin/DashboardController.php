<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Station;
use App\Models\Swap;
use App\Models\Payment;
use App\Models\Battery;
use App\Models\MotorcyclePayment;
use App\Models\SwapPromotion;
use App\Models\Purchase;

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
        $period = $request->input('period');

        // Swap Payments
        $paymentQuery = $this->applyDateFilter(Payment::where('status', 'completed'), $startDate, $endDate, $period);
        $totalPayments = $paymentQuery->sum('amount');

        // ✅ Swap Promotions: count only active or expired (both are paid)
        $swapPromos = SwapPromotion::whereIn('status', ['active', 'expired']);


        if ($period || ($startDate && $endDate)) {
            $swapPromos = $this->applyDateFilter($swapPromos, $startDate, $endDate, $period);
        }

        $promotionCount = $swapPromos->count();

        // Sum promotion revenue: motorcycle promo method + all active/paid swap promos
        $totalPromotions = $this->applyDateFilter($swapPromos, $startDate, $endDate, $period)->sum('amount');


        // Motorcycle payments (general)
        $totalMotorcyclePayments = $this->applyDateFilter(
            MotorcyclePayment::where('status', 'paid'),
            $startDate, $endDate, $period
        )->sum('amount');

        // Total Revenue
        $totalRevenue = $totalPayments + $totalPromotions + $totalMotorcyclePayments;

        // Counts
        $ridersCount = User::where('role', 'rider')->count();
        $agentsCount = User::where('role', 'agent')->count();
        $stationsCount = Station::count();
        $paymentsCount = Payment::count();

        $swapsCount = Swap::whereHas('payment', function ($q) {
            $q->where('status', 'completed');
        })->count();

        // Charts: Last 7 Days
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
        foreach (Station::all() as $station) {
            $stationPayments = Payment::where('status', 'completed')
                ->whereHas('swap', function ($query) use ($station) {
                    $query->where('station_id', $station->id);
                });

            $stationPayments = $this->applyDateFilter($stationPayments, $startDate, $endDate, $period);
            $revenueByStation[$station->name] = $stationPayments->sum('amount');
        }

        // Weekly Averages
        $weeklyAverages = [
            'swaps' => round(Swap::where('created_at', '>=', now()->subDays(7))->count() / 7, 2),
            'payments' => round(Payment::where('created_at', '>=', now()->subDays(7))->count() / 7, 2),
        ];

        // Overdue
        $allPurchases = Purchase::with(['payments', 'discounts', 'motorcycle'])->where('status', 'active')->get();
        $totalDue = $allPurchases->sum(fn($purchase) => $purchase->getAdjustedOverdueSummary()['due_amount'] ?? 0);

        $topStation = collect($revenueByStation)->sortDesc()->keys()->first();

        return view('admin.dashboard', compact(
            'ridersCount', 'agentsCount', 'stationsCount', 'swapsCount', 'paymentsCount',
            'swapStats', 'paymentStats', 'revenueByStation', 'weeklyAverages', 'topStation',
            'totalRevenue', 'totalPayments', 'totalPromotions', 'totalMotorcyclePayments',
            'startDate', 'endDate', 'period', 'batteryStatusCounts', 'totalDue','totalPromotions', 'promotionCount'
        ));
    }

    /**
     * Apply date filters to a query
     */
    private function applyDateFilter($query, $startDate, $endDate, $period)
    {
        if ($period) {
            switch ($period) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                    break;
                case 'year':
                    $query->whereYear('created_at', now()->year);
                    break;
            }
        } elseif ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query;
    }
}
