<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Part;
use Illuminate\Support\Facades\Auth;
use App\Models\LowStockAlert;


class SaleController extends Controller
{
    public function index()
    {
        $shop = Auth::user()->shop;
        $sales = Sale::whereIn('part_id', $shop->parts()->pluck('id'))->latest()->paginate(10);

        return view('inventory.sales.index', compact('sales'));
    }

    public function create()
    {
        $parts = Auth::user()->shop->parts;
        return view('inventory.sales.create', compact('parts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'part_id' => 'required|exists:parts,id',
            'quantity' => 'required|integer|min:1',
            'selling_price' => 'required|numeric|min:0',
            'customer_name' => 'nullable|string|max:255',
            'sold_at' => 'required|date',
        ]);

        $part = Part::findOrFail($request->part_id);

        if ($part->stock < $request->quantity) {
            return back()->with('error', 'Not enough stock available for this part.');
        }

        $sale = Sale::create([
            'part_id' => $request->part_id,
            'quantity' => $request->quantity,
            'selling_price' => $request->selling_price,
            'cost_price' => $part->cost_price,
            'total_price' => $request->quantity * $request->selling_price,
            'customer_name' => $request->customer_name,
            'sold_at' => $request->sold_at,
        ]);


        // Reduce stock after sale
        $part->decrement('stock', $sale->quantity);

        // Re-fetch updated stock
        $part->refresh();

        // Check for low stock
        if ($part->stock <= $part->minimum_threshold) {
            $exists = LowStockAlert::where('part_id', $part->id)
                ->where('shop_id', Auth::user()->shop_id)
                ->where('resolved', false)
                ->first();

            if (!$exists) {
                LowStockAlert::create([
                    'part_id' => $part->id,
                    'shop_id' => $part->shop_id,
                    'remaining_quantity' => $part->stock,
                ]);
            }
        }

        return redirect()->route('inventory.sales.index')->with('success', 'Sale recorded successfully.');
    }

    public function destroy(Sale $sale)
    {
        $sale->part->increment('stock', $sale->quantity); // return stock
        $sale->delete();

        return redirect()->back()->with('success', 'Sale deleted and stock restored.');
    }
}
