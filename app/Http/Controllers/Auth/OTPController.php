<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Rider;
use Illuminate\Support\Facades\Validator;

class OTPController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|regex:/^07\d{8}$/'
        ]);

        $otp = rand(100000, 999999);
        Cache::put('otp_' . $request->phone, $otp, now()->addMinutes(5));

        // Simulate sending OTP
        \Log::info("OTP for {$request->phone}: $otp");

        return response()->json(['message' => 'OTP sent']);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'otp' => 'required|digits:6',
        ]);

        $cachedOtp = Cache::get('otp_' . $request->phone);
        if ($cachedOtp != $request->otp) {
            return response()->json(['message' => 'Invalid OTP'], 401);
        }

        $rider = Rider::firstOrCreate(['phone' => $request->phone]);
        $token = $rider->createToken('rider-token')->plainTextToken;

        Cache::forget('otp_' . $request->phone);

        return response()->json(['token' => $token, 'rider' => $rider]);
    }
}

