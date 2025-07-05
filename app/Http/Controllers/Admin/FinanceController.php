<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FinanceController extends Controller
{
    /* ─────────────────────────────────────
     | List + search
     ───────────────────────────────────── */
    public function index(Request $request)
    {
        $finances = User::where('role', 'finance')
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

        return view('admin.finance.index', compact('finances'));
    }

    /* ─────────────────────────────────────
     | Show one staff member (optional view)
     ───────────────────────────────────── */
    public function show(User $finance)
    {
        abort_unless($finance->role === 'finance', 404);
        return view('admin.finance.show', compact('finance'));
    }

    /* ─────────────────────────────────────
     | Create form
     ───────────────────────────────────── */
    public function create()
    {
        return view('admin.finance.create');
    }

    /* ─────────────────────────────────────
     | Store new staff
     ───────────────────────────────────── */
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
        $user->role     = 'finance';

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')
                    ->store('finance/photos', 'public');
            $user->profile_photo = 'storage/' . $path;
        }

        $user->save();

        return redirect()
            ->route('admin.finance.index')
            ->with('success', 'Finance staff created successfully.');
    }

    /* ─────────────────────────────────────
     | Edit form
     ───────────────────────────────────── */
    public function edit(User $finance)
    {
        abort_unless($finance->role === 'finance', 404);
        return view('admin.finance.edit', compact('finance'));
    }

    /* ─────────────────────────────────────
     | Update staff
     ───────────────────────────────────── */
    public function update(Request $request, User $finance)
    {
        abort_unless($finance->role === 'finance', 404);

        $request->validate([
            'name'          => 'required|string|max:255',
            'phone'         => 'required|string|max:20|unique:users,phone,' . $finance->id,
            'email'         => 'nullable|email|unique:users,email,' . $finance->id,
            'password'      => 'nullable|string|confirmed|min:6',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $finance->name  = $request->name;
        $finance->phone = $request->phone;
        $finance->email = $request->email;

        if ($request->hasFile('profile_photo')) {
            // delete old
            if ($finance->profile_photo) {
                Storage::disk('public')
                    ->delete(str_replace('storage/', '', $finance->profile_photo));
            }
            $path = $request->file('profile_photo')
                    ->store('finance/photos', 'public');
            $finance->profile_photo = 'storage/' . $path;
        }

        if ($request->filled('password')) {
            $finance->password = bcrypt($request->password);
        }

        $finance->save();

        return redirect()
            ->route('admin.finance.show', $finance)
            ->with('success', 'Finance staff updated successfully.');
    }

    /* ─────────────────────────────────────
     | Delete staff
     ───────────────────────────────────── */
    public function destroy(User $finance)
    {
        abort_unless($finance->role === 'finance', 404);

        // remove photo
        if ($finance->profile_photo) {
            Storage::disk('public')
                ->delete(str_replace('storage/', '', $finance->profile_photo));
        }

        $finance->delete();

        return redirect()
            ->route('admin.finance.index')
            ->with('success', 'Finance staff deleted successfully.');
    }
}
