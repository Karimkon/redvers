<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MechanicController extends Controller
{
    public function index(Request $request)
    {
        $mechanics = User::where('role', 'mechanic')
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = $request->q;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->paginate(10)
            ->withQueryString();

        return view('admin.mechanics.index', compact('mechanics'));
    }

    public function create()
    {
        return view('admin.mechanics.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'nullable|email|unique:users,email',
            'phone'         => 'required|string|unique:users,phone',
            'password'      => 'required|string|min:6|confirmed',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $user = new User();
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->phone    = $request->phone;
        $user->password = bcrypt($request->password);
        $user->role     = 'mechanic';

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('mechanics/photos', 'public');
            $user->profile_photo = 'storage/' . $path;
        }

        $user->save();

        return redirect()->route('admin.mechanics.index')->with('success', 'Mechanic created successfully.');
    }

    public function show(User $mechanic)
    {
        return view('admin.mechanics.show', compact('mechanic'));
    }

    public function edit(User $mechanic)
    {
        return view('admin.mechanics.edit', compact('mechanic'));
    }

    public function update(Request $request, User $mechanic)
    {
        if ($mechanic->role !== 'mechanic') abort(404);

        $request->validate([
            'name'          => 'required|string|max:255',
            'phone'         => 'required|string|unique:users,phone,' . $mechanic->id,
            'email'         => 'nullable|email|unique:users,email,' . $mechanic->id,
            'password'      => 'nullable|string|confirmed|min:6',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $mechanic->name  = $request->name;
        $mechanic->phone = $request->phone;
        $mechanic->email = $request->email;

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('mechanics/photos', 'public');
            $mechanic->profile_photo = 'storage/' . $path;
        }

        if ($request->filled('password')) {
            $mechanic->password = bcrypt($request->password);
        }

        $mechanic->save();

        return redirect()->route('admin.mechanics.show', $mechanic)->with('success', 'Mechanic updated successfully.');
    }

    public function destroy(User $mechanic)
    {
        if ($mechanic->role !== 'mechanic') abort(404);

        // Delete photo if exists
        if ($mechanic->profile_photo && Storage::disk('public')->exists(str_replace('storage/', '', $mechanic->profile_photo))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $mechanic->profile_photo));
        }

        $mechanic->delete();

        return redirect()->route('admin.mechanics.index')->with('success', 'Mechanic deleted successfully.');
    }
}
