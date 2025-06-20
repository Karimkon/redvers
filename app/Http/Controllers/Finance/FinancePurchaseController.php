<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\{Purchase, MissedPayment};
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class FinancePurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with(['user', 'motorcycle']);

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%")
                        ->orWhere('nin_number', 'like', "%$search%");
                })
                ->orWhere('purchase_type', 'like', "%$search%")
                ->orWhere('status', 'like', "%$search%")
                ->orWhereHas('motorcycle', function ($mq) use ($search) {
                    $mq->where('type', 'like', "%$search%");
                });
            });
        }

        $purchases = $query->latest()->get();
        return view('finance.purchases.index', compact('purchases'));
    }


    public function store(Request $request, Purchase $purchase)
    {
        $request->validate([
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'type' => 'required|in:daily,weekly,lump_sum',
            'note' => 'nullable|string|max:255',
        ]);

        // Store payment
        MotorcyclePayment::create([
            'purchase_id' => $purchase->id,
            'payment_date' => $request->payment_date,
            'amount' => $request->amount,
            'type' => $request->type,
            'note' => $request->note,
        ]);

        // Update purchase record
        $purchase->amount_paid += $request->amount;
        $purchase->remaining_balance -= $request->amount;

        if ($purchase->remaining_balance <= 0) {
            $purchase->remaining_balance = 0;
            $purchase->status = 'cleared';
        } else {
            if (method_exists($purchase, 'determineStatusBasedOnMissedDays')) {
                $purchase->status = $purchase->determineStatusBasedOnMissedDays();
            } else {
                $purchase->status = 'active';
            }
        }

        $purchase->save();

        return back()->with('success', 'Payment recorded successfully by Finance.');
    }
    
    public function show(Purchase $purchase)
{
    $purchase->load(['user', 'motorcycle', 'payments', 'discounts']);

    // ✅ Use existing model method for full schedule
    $schedule = $purchase->getPaymentScheduleSummary();

    // ✅ Calculate true amount paid manually in controller
    $trueAmountPaid = $purchase->initial_deposit + $purchase->payments->sum('amount') + $purchase->discounts->sum('amount');
    $totalDiscount = $purchase->discounts->sum('amount');

    return view('finance.purchases.show', compact('purchase', 'schedule', 'trueAmountPaid', 'totalDiscount'));
}


}
