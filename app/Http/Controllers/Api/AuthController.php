<?php

namespace App\Http\Controllers\Api;
// interract with the mobile app which will be built by flutter 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Log;


class AuthController extends Controller
{
    public function login(Request $request)
{
    Log::info("Login Attempt", $request->all());
    $request->validate([
        'email'       => 'required|email',
        'password'    => 'required|string',
        'device_name' => 'required|string', // ✅ expected from Flutter
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    // ✅ Use the actual device_name from Flutter request
    $token = $user->createToken($request->device_name)->plainTextToken;

    return response()->json([
        'user'  => $user,
        'token' => $token,
        'role'  => $user->role,  // Optional: help routing based on role
    ]);
}


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role'     => 'rider', // or 'admin' if registering an admin
        ]);

        return response()->json([
            'user'  => $user,
            'token' => $user->createToken('mobile')->plainTextToken,
        ]);
    }
}
