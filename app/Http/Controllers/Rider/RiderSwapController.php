<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Swap;


class RiderSwapController extends Controller
{
   public function index()
    {
        $rider = Auth::user();

        $swaps = Swap::with(['station'])
            ->where('rider_id', $rider->id)
            ->orderByDesc('swapped_at')
            ->paginate(10);

        return view('rider.swaps.index', compact('swaps'));
    }

}
