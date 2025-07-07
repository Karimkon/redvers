<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\MotorcyclePayment;
use Carbon\Carbon;

class MotorcyclePaymentController extends Controller
{
    public function store(Request $request, Purchase $purchase)
    {
        $request->validate([
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'type' => 'required|in:daily,weekly,lump_sum',
            'note' => 'nullable|string|max:255',
        ]);

        // ✅ SAFETY CHECK: ensure purchase has a user linked
        if (!$purchase->user_id) {
            return back()->with('error', 'Cannot record payment: the selected purchase has no rider assigned. Please check the purchase details.');
        }

        // Store payment
        MotorcyclePayment::create([
            'purchase_id' => $purchase->id,
            'user_id' => $purchase->user_id, // ✅ direct link to the rider
            'payment_date' => $request->payment_date,
            'amount' => $request->amount,
            'type' => $request->type,
            'note' => $request->note,
            'status' => 'paid', // ✅ set status
        ]);

        // Update purchase record
        $purchase->amount_paid += $request->amount;
        $purchase->remaining_balance -= $request->amount;

        if ($purchase->remaining_balance <= 0) {
            $purchase->remaining_balance = 0;
            $purchase->status = 'cleared';
        } else {
            $purchase->status = $purchase->determineStatusBasedOnMissedDays();
        }

        $purchase->save();

        return back()->with('success', 'Payment recorded successfully.');
    }


    public function destroy(Purchase $purchase, MotorcyclePayment $payment)
    {
        $purchase->amount_paid -= $payment->amount;
        $purchase->remaining_balance += $payment->amount;

        if ($purchase->remaining_balance <= 0) {
            $purchase->remaining_balance = 0;
            $purchase->status = 'cleared';
        } else {
            $purchase->status = $purchase->determineStatusBasedOnMissedDays();
        }

        $purchase->save();
        $payment->delete();

        return back()->with('success', 'Payment deleted successfully.');
    }

}
