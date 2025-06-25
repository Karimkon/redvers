<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\Sale;
use App\Models\StockEntry;
use Illuminate\Http\Request;

class SpareShopDashboardController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to   = $request->to   ?? now()->endOfMonth()->toDateString();

        $shops = Shop::with(['parts', 'sales', 'stockEntries'])->get();

        $summary = [
            'totalShops' => $shops->count(),
            'totalStock' => StockEntry::whereBetween('received_at', [$from, $to])->sum('quantity'),
            'totalSales' => Sale::whereBetween('sold_at', [$from, $to])->sum('quantity'),
        ];

        $topShops = $shops->map(function ($shop) use ($from, $to) {
            $sales = $shop->sales()->whereBetween('sold_at', [$from, $to])->sum('quantity');
            return ['name' => $shop->name, 'sales' => $sales];
        })->sortByDesc('sales')->take(5);

        return view('admin.spares.dashboard', compact('summary', 'topShops', 'from', 'to'));
    }
}
