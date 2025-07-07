<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PaymentScheduleController extends Controller
{
    public function index()
    {
        $rider = Auth::user();
        $purchase = $rider->purchases()->latest()->first();

        if (!$purchase) {
            return redirect()->route('rider.dashboard')->with('error', 'No active purchase found.');
        }

        $schedule = $purchase->getPaymentScheduleSummary();

        return view('rider.schedule', compact('schedule', 'purchase'));
    }
}
