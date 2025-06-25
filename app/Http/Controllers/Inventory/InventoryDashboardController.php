<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Part;
use App\Models\Sale;
use App\Models\StockEntry;
use Illuminate\Support\Facades\Auth;

class InventoryDashboardController extends Controller
{
    public function index()
    {
        $shop = Auth::user()->shop;
        if (!$shop) {
            abort(403, 'No shop assigned to this user.');
        }
        
        $parts = $shop->parts;
        $partIds = $parts->pluck('id');

        $totalParts = $parts->count();
        $totalReceived = StockEntry::whereIn('part_id', $partIds)->sum('quantity');
        $totalSold = Sale::whereIn('part_id', $partIds)->sum('quantity');

        $topSelling = Sale::whereIn('part_id', $partIds)
            ->selectRaw('part_id, SUM(quantity) as total')
            ->groupBy('part_id')
            ->orderByDesc('total')
            ->take(5)
            ->with('part')
            ->get();

        $lowStock = $parts->where('stock', '<=', 5);

        return view('inventory.dashboard', compact(
            'totalParts',
            'totalReceived',
            'totalSold',
            'topSelling',
            'lowStock'
        ));
    }
}
