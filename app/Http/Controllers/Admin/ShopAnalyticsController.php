<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\Sale;
use App\Models\StockEntry;
use App\Models\LowStockAlert;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ShopAnalyticsController extends Controller
{
    public function index()
    {
        $shops = Shop::all();
        return view('admin.shops.index', compact('shops'));
    }

    public function show(Request $request, Shop $shop)
    {
        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to   = $request->input('to', now()->endOfMonth()->toDateString());

        $fromDate = Carbon::parse($from);
        $toDate = Carbon::parse($to);

        // ✅ Total quantity sold
        $totalSales = Sale::whereHas('part', fn ($q) => $q->where('shop_id', $shop->id))
            ->whereBetween('sold_at', [$from, $to])
            ->sum('quantity');

        // ✅ Total quantity received
        $totalReceived = StockEntry::whereHas('part', fn ($q) => $q->where('shop_id', $shop->id))
            ->whereBetween('received_at', [$from, $to])
            ->sum('quantity');

        // ✅ Total revenue
        $totalRevenue = Sale::whereHas('part', fn ($q) => $q->where('shop_id', $shop->id))
            ->whereBetween('sold_at', [$from, $to])
            ->sum(\DB::raw('quantity * selling_price'));

        // ✅ Total Profit Calculation
        $sales = \App\Models\Sale::with('part')
            ->whereHas('part', fn ($q) => $q->where('shop_id', $shop->id))
            ->whereBetween('sold_at', [$from, $to])
            ->get();

        $totalProfit = $sales->sum(fn($sale) => $sale->profit);





        // ✅ Unresolved low stock alerts
        $lowStockCount = LowStockAlert::where('shop_id', $shop->id)
            ->where('resolved', false)
            ->count();

        // ✅ Top 10 bestselling parts
        $topParts = Sale::whereHas('part', fn ($q) => $q->where('shop_id', $shop->id))
            ->whereBetween('sold_at', [$from, $to])
            ->selectRaw('part_id, SUM(quantity) as total')
            ->groupBy('part_id')
            ->orderByDesc('total')
            ->take(10)
            ->with('part')
            ->get();

        $topPartLabels = $topParts->pluck('part.name');
        $topPartCounts = $topParts->pluck('total');

        // ✅ Daily sales (chart data)
        $salesStats = Sale::whereHas('part', fn ($q) => $q->where('shop_id', $shop->id))
            ->whereBetween('sold_at', [$fromDate, $toDate])
            ->selectRaw('DATE(sold_at) as day, SUM(quantity) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day');

        // ✅ Daily stock received (chart data)
        $stockStats = StockEntry::whereHas('part', fn ($q) => $q->where('shop_id', $shop->id))
            ->whereBetween('received_at', [$fromDate, $toDate])
            ->selectRaw('DATE(received_at) as day, SUM(quantity) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day');

        // ✅ Date range normalization for consistent chart labels
        $labels = collect();
        $pointer = $fromDate->copy();
        while ($pointer <= $toDate) {
            $labels->push($pointer->toDateString());
            $pointer->addDay();
        }

        $salesData = $labels->map(fn($day) => $salesStats[$day] ?? 0);
        $stockData = $labels->map(fn($day) => $stockStats[$day] ?? 0);

        return view('admin.shops.analytics', compact(
            'shop', 'totalSales', 'totalReceived', 'lowStockCount', 'totalRevenue', 'totalProfit',
            'from', 'to', 'labels', 'salesData', 'stockData',
            'topPartLabels', 'topPartCounts'
        ));
    }

    public function profitDetails(Request $request, Shop $shop)
    {
        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to   = $request->input('to', now()->endOfMonth()->toDateString());

        $sales = \App\Models\Sale::with('part')
            ->whereHas('part', fn ($q) => $q->where('shop_id', $shop->id))
            ->whereBetween('sold_at', [$from, $to])
            ->latest()
            ->paginate(20);

        $totalProfit = $sales->sum(fn($sale) => $sale->profit);

        return view('admin.shops.profit-details', compact('sales', 'shop', 'from', 'to', 'totalProfit'));
    }

}
