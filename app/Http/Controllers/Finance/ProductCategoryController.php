<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategory;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::all();
        return view('finance.product_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('finance.product_categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:product_categories,name',
            'description' => 'nullable|string',
        ]);

        ProductCategory::create($validated);
        return redirect()->route('finance.product_categories.index')->with('success', 'Category created.');
    }

    public function edit(ProductCategory $productCategory)
    {
        return view('finance.product_categories.edit', compact('productCategory'));
    }

    public function update(Request $request, ProductCategory $productCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:product_categories,name,' . $productCategory->id,
            'description' => 'nullable|string',
        ]);

        $productCategory->update($validated);
        return redirect()->route('finance.product_categories.index')->with('success', 'Category updated.');
    }

    public function destroy(ProductCategory $productCategory)
    {
        $productCategory->delete();
        return redirect()->route('finance.product_categories.index')->with('success', 'Category deleted.');
    }
}
