<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm(Request $request)
    {
        $route = $request->route()->getName();

        if ($route === 'agent.login') {
            return view('agent.auth.login');
        } elseif ($route === 'rider.login') {
            return view('rider.auth.login');
        }

        return view('admin.auth.login');
    }


    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        } elseif ($user->role === 'agent') {
            return redirect()->intended(route('agent.dashboard'));
        } elseif ($user->role === 'rider') {
            return redirect()->intended(route('rider.dashboard'));
        } else {
            Auth::logout();
            return back()->withErrors(['email' => 'Unauthorized role.']);
        }
    }

    return back()->withErrors(['email' => 'Invalid credentials.']);
}


    public function logout(Request $request)
    {
        Auth::logout(); // Use default guard now
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
