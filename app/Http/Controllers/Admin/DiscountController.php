<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Discount;

class DiscountController extends Controller
{
    /**
     * Show the form to create a discount for a purchase.
     */
    public function create(Purchase $purchase)
    {
        return view('admin.discounts.create', compact('purchase'));
    }

    /**
     * Store the discount and apply it to the purchase.
     */
    public function store(Request $request, Purchase $purchase)
    {
        $request->validate([
            'amount' => 'nullable|numeric|min:0',
            'percentage' => 'nullable|numeric|min:0|max:100',
            'reason' => 'nullable|string|max:255',
        ]);

        if (!$request->amount && !$request->percentage) {
            return back()->withErrors(['discount' => 'Enter either an amount or percentage.']);
        }

        // Calculate discount value
        $discountValue = 0;

        if ($request->amount) {
            $discountValue = $request->amount;
        } elseif ($request->percentage) {
            $discountValue = ($request->percentage / 100) * $purchase->remaining_balance;
        }

        // Prevent over-discounting
        $discountValue = min($discountValue, $purchase->remaining_balance);

        // Save discount
        Discount::create([
            'purchase_id' => $purchase->id,
            'amount' => $request->amount,
            'percentage' => $request->percentage,
            'reason' => $request->reason,
        ]);

        // Apply discount to balance
        $purchase->remaining_balance -= $discountValue;
        
        // ✅ Auto-determine correct status based on updated schedule
        $purchase->status = $purchase->determineStatusBasedOnMissedDays();
        $purchase->save();

        return redirect()
            ->route('admin.purchases.show', $purchase)
            ->with('success', 'Discount applied successfully.');
    }
}
