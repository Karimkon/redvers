<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $shops = Shop::latest()->paginate(10);
        return view('admin.shops.index', compact('shops'));
    }

    public function create()
    {
        $users = User::where('role', 'inventory')->get();
        return view('admin.shops.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'user_id' => 'nullable|exists:users,id',
        ]);

        Shop::create([
        'name' => $request->name,
        'location' => $request->location,
        'contact_number' => $request->contact_number,
        'user_id' => $request->user_id,
    ]);

        return redirect()->route('admin.shops.index')->with('success', 'Shop created successfully.');
    }

    public function edit(Shop $shop)
    {
        $users = \App\Models\User::where('role', 'inventory')->get();
        return view('admin.shops.edit', compact('shop', 'users'));
    }


    public function update(Request $request, Shop $shop)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
        ]);

        $shop->update($request->all());

        return redirect()->route('admin.shops.index')->with('success', 'Shop updated.');
    }

    public function destroy(Shop $shop)
    {
        $shop->delete();
        return redirect()->back()->with('success', 'Shop deleted.');
    }
}
