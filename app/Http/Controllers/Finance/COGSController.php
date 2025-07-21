<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use App\Models\COGS;
use App\Models\Product;

class COGSController extends Controller
{
    public function index()
    {
        $cogs = COGS::latest()->paginate(20);
        return view('finance.cogs.index', compact('cogs'));
    }

    public function create()
    {
        $categories = ProductCategory::all();
        $products = Product::all();
        return view('finance.cogs.create', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'unit_cost' => 'required|numeric',
            'quantity' => 'required|integer',
            'date' => 'required|date',
            'description' => 'nullable|string',
            'attachment_id' => 'nullable|exists:attachments,id',
        ]);

        COGS::create($validated);
        return redirect()->route('finance.cogs.index')->with('success', 'COGS entry created.');
    }

    public function show(COGS $cogs)
    {
        return view('finance.cogs.show', compact('cogs'));
    }

    public function edit(COGS $cogs)
    {
        $products = Product::all();
        return view('finance.cogs.edit', compact('cogs', 'products'));
    }

    public function update(Request $request, COGS $cogs)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'unit_cost' => 'required|numeric',
            'quantity' => 'required|integer',
            'date' => 'required|date',
            'description' => 'nullable|string',
            'attachment_id' => 'nullable|exists:attachments,id',
        ]);

        $cogs->update($validated);
        return redirect()->route('finance.cogs.index')->with('success', 'COGS entry updated.');
    }

    public function destroy(COGS $cogs)
    {
        $cogs->delete();
        return redirect()->route('finance.cogs.index')->with('success', 'COGS entry deleted.');
    }
}
