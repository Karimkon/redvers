<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StockEntry;
use App\Models\Part;
use Illuminate\Support\Facades\Auth;

class StockEntryController extends Controller
{
    public function index()
    {
        $shop = Auth::user()->shop;
        $entries = StockEntry::whereIn('part_id', $shop->parts()->pluck('id'))->latest()->paginate(10);

        return view('inventory.stock_entries.index', compact('entries'));
    }

    public function create()
    {
        $parts = Auth::user()->shop->parts;
        return view('inventory.stock_entries.create', compact('parts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'part_id' => 'required|exists:parts,id',
            'quantity' => 'required|integer|min:1',
            'cost_price' => 'required|numeric|min:0',
            'received_at' => 'required|date',
        ]);

        $entry = StockEntry::create($request->all());

        // Update the part stock
        $entry->part->increment('stock', $entry->quantity);

        return redirect()->route('inventory.stock-entries.index')->with('success', 'Stock entry recorded.');
    }

    public function destroy(StockEntry $stockEntry)
    {
        $part = $stockEntry->part;
        $part->decrement('stock', $stockEntry->quantity);
        $stockEntry->delete();

        return redirect()->back()->with('success', 'Stock entry deleted and stock adjusted.');
    }
}
