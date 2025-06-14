<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Motorcycle;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\MotorcycleUnit;


class MotorcyclePurchaseController extends Controller
{
    /**
     * Display all motorcycle purchases.
     */
    public function index(Request $request)
{
    $query = Purchase::with(['user', 'motorcycle', 'motorcycleUnit'])->latest();

    if ($request->has('search') && $request->search) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('email', 'like', '%' . $request->search . '%')
              ->orWhere('phone', 'like', '%' . $request->search . '%');
        });
    }

    $purchases = $query->get();

    return view('admin.purchases.index', compact('purchases'));
}



    /**
     * Show form to assign a motorcycle to a rider.
     */

public function create()
{
    $riders = User::where('role', 'rider')->get();
    $motorcycles = Motorcycle::all();
    $availableUnits = MotorcycleUnit::where('status', 'available')->with('motorcycle')->get();

    return view('admin.purchases.create', compact('riders', 'motorcycles', 'availableUnits'));
}


    /**
     * Store the assigned motorcycle purchase.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'start_date' => ['required', 'date'],
            'motorcycle_id' => 'required|exists:motorcycles,id',
            'motorcycle_unit_id' => 'required|exists:motorcycle_units,id',
            'purchase_type' => 'required|in:cash,hire',
        ]);

        $motorcycle = Motorcycle::findOrFail($request->motorcycle_id);
        $unit = MotorcycleUnit::where('id', $request->motorcycle_unit_id)
                            ->where('status', 'available')
                            ->firstOrFail();

        // Defaults
        $deposit = 0;
        $total = 0;
        $amountPaid = 0;
        $remaining = 0;
        $status = 'active';

        if ($request->purchase_type === 'cash') {
            $total = $motorcycle->cash_price;
            $deposit = $total;
            $amountPaid = $total;
            $remaining = 0;
            $status = 'completed'; // ✅ cash = fully paid
        } elseif ($request->purchase_type === 'hire') {
            $total = $motorcycle->hire_price_total;
            $deposit = $motorcycle->type === 'brand_new' ? 300000 : 200000;
            $amountPaid = $deposit;
            $remaining = $total - $deposit;
        }

        $existing = Purchase::where('motorcycle_unit_id', $request->motorcycle_unit_id)
            ->where('status', 'active')
            ->exists();

        if ($existing) {
            return back()->withErrors(['motorcycle_unit_id' => 'This motorcycle unit is already assigned to another rider.'])
                        ->withInput();
        }

        // Create the purchase
        $purchase = Purchase::create([
            'user_id' => $request->user_id,
            'motorcycle_id' => $request->motorcycle_id,
            'motorcycle_unit_id' => $request->motorcycle_unit_id,
            'purchase_type' => $request->purchase_type,
            'initial_deposit' => $deposit,
            'total_price' => $total,
            'amount_paid' => $amountPaid,
            'remaining_balance' => $remaining,
            'status' => $status,
            'start_date' => $request->start_date,
        ]);

        // Mark motorcycle unit as assigned
        $unit->status = 'assigned';
        $unit->save();

        return redirect()->route('admin.purchases.index')->with('success', 'Motorcycle assigned successfully.');
    }


public function show(Purchase $purchase)
{
    $purchase->loadMissing(['discounts', 'payments', 'motorcycle']);
    $schedule = $purchase->getPaymentScheduleSummary();
    $totalDiscount = $purchase->discounts->sum('amount');
    $trueAmountPaid = $purchase->payments->sum('amount');

    return view('admin.purchases.show', compact('purchase', 'schedule', 'trueAmountPaid', 'totalDiscount'));
}




     /**
     * Edit the status of a purchase.
     */
    public function edit(Purchase $purchase)
    {
        return view('admin.purchases.edit', compact('purchase'));
    }

    /**
     * Update the status of the purchase.
     */
    public function update(Request $request, Purchase $purchase)
    {
        $request->validate([
            'status' => 'required|in:active,completed,defaulted',
        ]);

        $purchase->update([
            'status' => $request->status,
        ]);

        return redirect()->route('admin.purchases.index')->with('success', 'Purchase status updated successfully.');
    }

    /**
     * (Optional) Delete a purchase record.
     */
    public function destroy(Purchase $purchase)
    {
        $purchase->delete();
        return redirect()->route('admin.purchases.index')->with('success', 'Purchase deleted.');
    }


}
