<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RiderProfileController extends Controller
{
    public function show()
    {
        $rider = Auth::user();
        return view('rider.profile', compact('rider'));
    }
}
