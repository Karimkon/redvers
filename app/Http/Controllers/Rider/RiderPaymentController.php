<?php

// app/Http/Controllers/Rider/RiderPaymentController.php
namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Purchase;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;


class RiderPaymentController extends Controller
{
    public function index()
    {
        $rider = Auth::user();
        $purchase = $rider->purchases()->with(['payments', 'discounts', 'motorcycle'])->first();

        if (!$purchase) {
            return view('rider.payments.index', ['message' => 'No motorcycle purchase found.']);
        }

        // Expected total payments (days since start)
        $start = $purchase->created_at->copy();
        $now = Carbon::now();
        $expectedDays = $now->diffInDays($start);
        $actualPayments = $purchase->payments->count();
        $missedPayments = max($expectedDays - $actualPayments, 0);

        $nextDueDate = $purchase->payments->last()?->payment_date 
                       ? Carbon::parse($purchase->payments->last()->payment_date)->addDays(1)->toDateString() 
                       : $start->toDateString();

        $completion = $purchase->total_price > 0
            ? ($purchase->amount_paid / $purchase->total_price) * 100
            : 0;

        return view('rider.payments.index', compact('purchase', 'expectedDays', 'actualPayments', 'missedPayments', 'nextDueDate', 'completion'));
    }


    public function download()
    {
        $rider = Auth::user();
        $purchase = $rider->purchases()->with(['payments', 'motorcycle'])->first();

        $pdf = Pdf::loadView('rider.payments.pdf', compact('purchase'));
        return $pdf->download('payment_summary.pdf');
    }

}
   