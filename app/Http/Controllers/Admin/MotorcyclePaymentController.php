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
            // Check if defaulted logic applies
            $lastPayment = $purchase->payments()->latest('payment_date')->first();
            $lastDate = $lastPayment ? Carbon::parse($lastPayment->payment_date) : null;
            $expectedGap = $request->type === 'weekly' ? 7 : 1;
            
            if ($lastDate && $lastDate->diffInDays(now()) > ($expectedGap + 2)) {
                $purchase->status = 'defaulted';
            } else {
                $purchase->status = 'active';
            }
        }

        $purchase->save();

        return back()->with('success', 'Payment recorded successfully.');
    }
}
