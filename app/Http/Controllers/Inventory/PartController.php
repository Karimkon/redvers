<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Part;
use Illuminate\Support\Facades\Auth;

class PartController extends Controller
{
    public function index()
    {
        $shop = Auth::user()->shop;
        $parts = $shop->parts()->latest()->paginate(10);

        return view('inventory.parts.index', compact('parts'));
    }

    public function create()
    {
        return view('inventory.parts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'cost_price' => 'required|numeric|min:0',
        ]);

        Auth::user()->shop->parts()->create($request->all());

        return redirect()->route('inventory.parts.index')->with('success', 'Part added successfully.');
    }

    public function show(Part $part)
    {
        $this->authorizeAccess($part);
        return view('inventory.parts.show', compact('part'));
    }

    public function edit(Part $part)
    {
        $this->authorizeAccess($part);
        return view('inventory.parts.edit', compact('part'));
    }

    public function update(Request $request, Part $part)
    {
        $this->authorizeAccess($part);

        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'cost_price' => 'required|numeric|min:0',
        ]);

        $part->update($request->all());

        return redirect()->route('inventory.parts.index')->with('success', 'Part updated successfully.');
    }

    public function destroy(Part $part)
    {
        $this->authorizeAccess($part);
        $part->delete();

        return redirect()->route('inventory.parts.index')->with('success', 'Part deleted.');
    }

    protected function authorizeAccess(Part $part)
    {
        if ($part->shop_id !== Auth::user()->shop->id) {
            abort(403);
        }
    }
}
