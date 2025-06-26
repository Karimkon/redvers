<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Part;
use App\Models\Shop;

class AdminPartController extends Controller
{
        public function index(Request $request)
    {
        $shops = \App\Models\Shop::all();
        $query = \App\Models\Part::with('shop');

        if ($request->shop_id) {
            $query->where('shop_id', $request->shop_id);
        }

        $parts = $query->latest()->paginate(20);

        return view('admin.parts.index', compact('parts', 'shops'));
    }

    public function create()
    {
        $shops = Shop::all();
        return view('admin.parts.create', compact('shops'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        Part::create($request->all());

        return redirect()->route('admin.shops.index')->with('success', 'Part added successfully.');
    }

    public function edit(Part $part)
    {
        $shops = Shop::all();
        return view('admin.parts.edit', compact('part', 'shops'));
    }

    public function update(Request $request, Part $part)
    {
        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $part->update($request->all());

        return redirect()->route('admin.shops.index')->with('success', 'Part updated successfully.');
    }

    public function destroy(Part $part)
    {
        $part->delete();

        return redirect()->back()->with('success', 'Part deleted.');
    }
}
