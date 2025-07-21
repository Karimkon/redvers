<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(20);
        return view('finance.products.index', compact('products'));
    }

    public function create()
    {
        $categories = ProductCategory::orderBy('name')->get();
        return view('finance.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:products,name',
            'description' => 'nullable|string',
            'unit_cost' => 'required|numeric',
            'category_id' => 'required|exists:product_categories,id',
        ]);

        Product::create($validated);
        return redirect()->route('finance.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = ProductCategory::orderBy('name')->get();
        return view('finance.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:products,name,' . $product->id,
            'category_id' => 'required|exists:product_categories,id',
            'description' => 'nullable|string',
            'unit_cost' => 'required|numeric',
        ]);

        $product->update($validated);
        return redirect()->route('finance.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('finance.products.index')->with('success', 'Product deleted successfully.');
    }

    public function show(Product $product)
    {
        return view('finance.products.show', compact('product'));
    }
}
