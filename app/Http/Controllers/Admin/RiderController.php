<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RiderController extends Controller
{
    public function index()
    {
        $riders = User::where('role', 'rider')->paginate(10);
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
            'name'       => 'required|string|max:255',
            'email'      => 'nullable|email|unique:users,email',
            'phone'      => 'required|string|unique:users,phone',
            'password'   => 'required|string|min:6|confirmed',
            'nin_number' => 'nullable|string|max:50',
            'profile_photo' => 'nullable|image|max:2048',
            'id_front'      => 'nullable|image|max:2048',
            'id_back'       => 'nullable|image|max:2048',
        ]);

        $user = new User();
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->phone    = $request->phone;
        $user->password = bcrypt($request->password);
        $user->role     = 'rider';
        $user->nin_number = $request->nin_number;

        if ($request->hasFile('profile_photo')) {
            $user->profile_photo = $request->file('profile_photo')->store('riders/photos', 'public');
        }

        if ($request->hasFile('id_front')) {
            $user->id_front = $request->file('id_front')->store('riders/ids/front', 'public');
        }

        if ($request->hasFile('id_back')) {
            $user->id_back = $request->file('id_back')->store('riders/ids/back', 'public');
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
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:20|unique:users,phone,' . $rider->id,
        'email' => 'nullable|email|unique:users,email,' . $rider->id,
        'nin_number' => 'nullable|string|max:50',
        'password' => 'nullable|string|confirmed|min:6',
        'profile_photo' => 'nullable|image|max:2048',
        'id_front' => 'nullable|image|max:2048',
        'id_back' => 'nullable|image|max:2048',
    ]);

    $rider->name = $request->name;
    $rider->phone = $request->phone;
    $rider->email = $request->email;
    $rider->nin_number = $request->nin_number;

    if ($request->hasFile('profile_photo')) {
        $rider->profile_photo = $request->file('profile_photo')->store('riders/photos', 'public');
    }

    if ($request->hasFile('id_front')) {
        $rider->id_front = $request->file('id_front')->store('riders/ids/front', 'public');
    }

    if ($request->hasFile('id_back')) {
        $rider->id_back = $request->file('id_back')->store('riders/ids/back', 'public');
    }

    if ($request->filled('password')) {
        $rider->password = bcrypt($request->password);
    }

    $rider->save();

    return redirect()->route('admin.riders.show', $rider)->with('success', 'Rider updated successfully.');
}


    public function destroy(User $rider)
    {
        $rider->delete();
        return redirect()->route('admin.riders.index')->with('success', 'Rider deleted successfully.');
    }
}
