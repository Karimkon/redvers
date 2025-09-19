<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $admins = User::where('role', 'admin')
            ->when($request->filled('q'), function ($q) use ($request) {
                $search = $request->q;
                $q->where(function ($qry) use ($search) {
                    $qry->where('name',  'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->paginate(10)
            ->withQueryString();

        return view('admin.admins.index', compact('admins'));
    }

    public function show(User $admin)
    {
        if ($admin->role !== 'admin') abort(404);
        return view('admin.admins.show', compact('admin'));
    }

    public function create()
    {
        return view('admin.admins.create');
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
        $user->role     = 'admin';

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('admins/photos', 'public');
            $user->profile_photo = 'storage/' . $path;
        }

        $user->save();

        return redirect()->route('admin.admins.index')->with('success', 'Admin user created successfully.');
    }

    public function edit(User $admin)
    {
        if ($admin->role !== 'admin') abort(404);
        return view('admin.admins.edit', compact('admin'));
    }

    public function update(Request $request, User $admin)
    {
        if ($admin->role !== 'admin') abort(404);

        $request->validate([
            'name'          => 'required|string|max:255',
            'phone'         => 'required|string|max:20|unique:users,phone,' . $admin->id,
            'email'         => 'nullable|email|unique:users,email,' . $admin->id,
            'password'      => 'nullable|string|confirmed|min:6',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $admin->name  = $request->name;
        $admin->phone = $request->phone;
        $admin->email = $request->email;

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('admins/photos', 'public');
            $admin->profile_photo = 'storage/' . $path;
        }

        if ($request->filled('password')) {
            $admin->password = bcrypt($request->password);
        }

        $admin->save();

        return redirect()->route('admin.admins.show', $admin)->with('success', 'Admin user updated successfully.');
    }

    public function destroy(User $admin)
    {
        if ($admin->role !== 'admin') abort(404);

        if ($admin->profile_photo && Storage::disk('public')->exists(str_replace('storage/', '', $admin->profile_photo))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $admin->profile_photo));
        }

        $admin->delete();

        return redirect()->route('admin.admins.index')->with('success', 'Admin user deleted successfully.');
    }
}
