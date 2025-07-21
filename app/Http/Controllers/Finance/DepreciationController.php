<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Depreciation;
use App\Models\Product;

class DepreciationController extends Controller
{
    public function index()
    {
        $depreciations = Depreciation::with('product')->latest()->paginate(20);
        return view('finance.depreciations.index', compact('depreciations'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('finance.depreciations.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'initial_value' => 'required|numeric',
            'depreciation_rate' => 'required|numeric',
            'lifespan_months' => 'nullable|integer',
            'start_date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        Depreciation::create($validated);
        return redirect()->route('finance.depreciations.index')->with('success', 'Depreciation recorded.');
    }

    public function show(Depreciation $depreciation)
    {
        $depreciation->load('product');
        return view('finance.depreciations.show', compact('depreciation'));
    }

    public function edit(Depreciation $depreciation)
    {
        $products = Product::orderBy('name')->get();
        return view('finance.depreciations.edit', compact('depreciation', 'products'));
    }

    public function update(Request $request, Depreciation $depreciation)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'initial_value' => 'required|numeric',
            'depreciation_rate' => 'required|numeric',
            'lifespan_months' => 'nullable|integer',
            'start_date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        $depreciation->update($validated);
        return redirect()->route('finance.depreciations.index')->with('success', 'Updated successfully.');
    }

    public function destroy(Depreciation $depreciation)
    {
        $depreciation->delete();
        return redirect()->route('finance.depreciations.index')->with('success', 'Deleted.');
    }
}
