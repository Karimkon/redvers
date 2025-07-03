<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\Sale;
use App\Models\Part;
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

         $investmentType = $request->input('investment_type', 'lifetime'); // default to lifetime

    if ($investmentType === 'inventory') {
        // current unsold inventory value
        $totalInvested = Part::where('shop_id', $shop->id)
            ->selectRaw('SUM(stock * cost_price) AS invested')
            ->value('invested') ?? 0;
    } else {
        // lifetime = cost of sold items + cost of remaining stock
        $soldCost = Sale::join('parts', 'parts.id', '=', 'sales.part_id')
            ->where('parts.shop_id', $shop->id)
            ->selectRaw('SUM(sales.quantity * parts.cost_price) AS sold_cost')
            ->value('sold_cost') ?? 0;

        $unsoldValue = Part::where('shop_id', $shop->id)
            ->selectRaw('SUM(stock * cost_price) AS stock_value')
            ->value('stock_value') ?? 0;

        $totalInvested = $soldCost + $unsoldValue;
    }


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
        $lowStockAlerts = LowStockAlert::with(['part:id,name,stock', 'shop:id,name'])
        ->where('shop_id', $shop->id)
        ->where('resolved', false)
        ->get();

        $lowStockCount = $lowStockAlerts->count();

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
            'from', 'to', 'labels', 'salesData', 'stockData', 'lowStockAlerts',
            'topPartLabels', 'topPartCounts', 'totalInvested', 'investmentType'
        ));
    }

    public function profitDetails(Request $request, Shop $shop)
    {
        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to   = $request->input('to', now()->endOfMonth()->toDateString());

        // Paginated list of individual sales
        $sales = \App\Models\Sale::with('part')
            ->whereHas('part', fn ($q) => $q->where('shop_id', $shop->id))
            ->whereBetween('sold_at', [$from, $to])
            ->latest()
            ->paginate(20);

        $totalCost = Sale::whereHas('part', fn ($q) => $q->where('shop_id', $shop->id))
            ->whereBetween('sold_at', [$from, $to])
            ->selectRaw('SUM(cost_price * quantity) AS total_cost')
            ->value('total_cost') ?? 0;

        // Accurate total profit for the whole period
        $totalProfit = Sale::whereHas('part', fn ($q) => $q->where('shop_id', $shop->id))
            ->whereBetween('sold_at', [$from, $to])
            ->selectRaw('SUM((selling_price - cost_price) * quantity) AS total_profit')
            ->value('total_profit') ?? 0;   // guard against null

        return view('admin.shops.profit-details', compact('sales', 'shop', 'from', 'to', 'totalProfit', 'totalCost'));
    }

}
