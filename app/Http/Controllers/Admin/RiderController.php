<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RiderController extends Controller
{
    public function index(Request $request)           // ⬅ add Request
    {
        $riders = User::where('role', 'rider')
            ->when($request->filled('q'), function ($q) use ($request) {
                $search = $request->q;
                $q->where(function ($qry) use ($search) {
                    $qry->where('name',  'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->paginate(10)
            ->withQueryString();   // keep ?q=… during pagination links

        return view('admin.riders.index', compact('riders'));
    }

    public function show(User $rider)
    {
        return view('admin.riders.show', compact('rider'));
    }

    public function create()
    {
        return view('admin.riders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'nullable|email|unique:users,email',
            'phone'         => 'required|string|unique:users,phone',
            'password'      => 'required|string|min:6|confirmed',
            'nin_number'    => 'nullable|string|max:50',
            'profile_photo' => 'nullable|image|max:2048',
            'id_front'      => 'nullable|image|max:2048',
            'id_back'       => 'nullable|image|max:2048',
        ]);

        $user = new User();
        $user->name        = $request->name;
        $user->email       = $request->email;
        $user->phone       = $request->phone;
        $user->password    = bcrypt($request->password);
        $user->role        = 'rider';
        $user->nin_number  = $request->nin_number;

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('riders/photos', 'public');
            $user->profile_photo = 'storage/' . $path;
        }

        if ($request->hasFile('id_front')) {
            $path = $request->file('id_front')->store('riders/ids', 'public');
            $user->id_front = 'storage/' . $path;
        }

        if ($request->hasFile('id_back')) {
            $path = $request->file('id_back')->store('riders/ids', 'public');
            $user->id_back = 'storage/' . $path;
        }

        $user->save();

        return redirect()->route('admin.riders.index')->with('success', 'Rider created successfully.');
    }

    public function edit(User $rider)
    {
        return view('admin.riders.edit', compact('rider'));
    }

    public function update(Request $request, User $rider)
    {
        if ($rider->role !== 'rider') abort(404);

        $request->validate([
            'name'          => 'required|string|max:255',
            'phone'         => 'required|string|max:20|unique:users,phone,' . $rider->id,
            'email'         => 'nullable|email|unique:users,email,' . $rider->id,
            'nin_number'    => 'nullable|string|max:50',
            'password'      => 'nullable|string|confirmed|min:6',
            'profile_photo' => 'nullable|image|max:2048',
            'id_front'      => 'nullable|image|max:2048',
            'id_back'       => 'nullable|image|max:2048',
        ]);

        $rider->name = $request->name;
        $rider->phone = $request->phone;
        $rider->email = $request->email;
        $rider->nin_number = $request->nin_number;

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('riders/photos', 'public');
            $rider->profile_photo = 'storage/' . $path;
        }

        if ($request->hasFile('id_front')) {
            $path = $request->file('id_front')->store('riders/ids', 'public');
            $rider->id_front = 'storage/' . $path;
        }

        if ($request->hasFile('id_back')) {
            $path = $request->file('id_back')->store('riders/ids', 'public');
            $rider->id_back = 'storage/' . $path;
        }

        if ($request->filled('password')) {
            $rider->password = bcrypt($request->password);
        }

        $rider->save();

        return redirect()->route('admin.riders.show', $rider)->with('success', 'Rider updated successfully.');
    }

    public function destroy(User $rider)
    {
        // Step 1: Delete related swap promotions
        $rider->swapPromotions()->delete();

        // Step 2: Delete attached images from storage
        foreach (['profile_photo', 'id_front', 'id_back'] as $field) {
            if ($rider->$field && Storage::disk('public')->exists(str_replace('storage/', '', $rider->$field))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $rider->$field));
            }
        }

        // Step 3: Delete the user
        $rider->delete();

        return redirect()->route('admin.riders.index')->with('success', 'Rider deleted successfully.');
    }

}
