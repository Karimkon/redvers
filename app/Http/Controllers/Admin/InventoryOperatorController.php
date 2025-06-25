<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class InventoryOperatorController extends Controller
{
    public function index()
    {
        $operators = User::where('role', 'inventory')->paginate(10);
        return view('admin.inventory.index', compact('operators'));
    }

    public function create()
    {
        $shops = Shop::whereNull('user_id')->get(); // Only unassigned shops

        return view('admin.inventory.create', compact('shops'));
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|unique:users,email',
        'phone' => 'required|string|unique:users,phone',
        'password' => 'required|string|min:6|confirmed',
        'shop_id' => 'required|exists:shops,id',
    ]);

    // ✅ Step 1: Create the inventory user
    $user = new User();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->phone = $request->phone;
    $user->password = Hash::make($request->password);
    $user->role = 'inventory';
    $user->save();

    // ✅ Step 2: Assign that user to the selected shop
    $shop = Shop::findOrFail($request->shop_id);
    $shop->user_id = $user->id;
    $shop->save();

    return redirect()->route('admin.inventory.index')->with('success', 'Inventory operator registered and assigned to shop.');
}


    public function edit(User $inventory)
    {
        if ($inventory->role !== 'inventory') abort(404);
        $shops = Shop::all();
        return view('admin.inventory.edit', compact('inventory', 'shops'));
    }

    public function update(Request $request, User $inventory)
    {
        if ($inventory->role !== 'inventory') abort(404);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:users,phone,' . $inventory->id,
            'email' => 'nullable|email|unique:users,email,' . $inventory->id,
            'password' => 'nullable|string|confirmed|min:6',
            'shop_id' => 'required|exists:shops,id',
        ]);

        $inventory->name = $request->name;
        $inventory->phone = $request->phone;
        $inventory->email = $request->email;
        // Detach previous shop (if any)
        $oldShop = Shop::where('user_id', $inventory->id)->first();
        if ($oldShop) {
            $oldShop->user_id = null;
            $oldShop->save();
        }

        // Assign new shop
        $newShop = Shop::findOrFail($request->shop_id);
        $newShop->user_id = $inventory->id;
        $newShop->save();


        if ($request->filled('password')) {
            $inventory->password = Hash::make($request->password);
        }

        $inventory->save();

        return redirect()->route('admin.inventory.show', $inventory)->with('success', 'Inventory operator updated successfully.');
    }

    public function show(User $inventory)
    {
        if ($inventory->role !== 'inventory') abort(404);
        return view('admin.inventory.show', compact('inventory'));
    }

    public function destroy(User $inventory)
    {
        if ($inventory->role !== 'inventory') abort(404);
        $inventory->delete();
        return redirect()->route('admin.inventory.index')->with('success', 'Inventory operator deleted successfully.');
    }
}
