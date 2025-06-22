<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Swap;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
        public function index(Request $request)
        {
            $query = Payment::with('swap.rider')->latest();

            if ($search = $request->get('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('reference', 'like', "%{$search}%")
                    ->orWhere('method', 'like', "%{$search}%")
                    ->orWhere('amount', 'like', "%{$search}%")
                    ->orWhereHas('swap.rider', function ($riderQuery) use ($search) {
                        $riderQuery->where('name', 'like', "%{$search}%");
                    });
                });
            }

            $payments = $query->paginate(10)->appends($request->only('search'));

            return view('admin.payments.index', compact('payments'));
        }

    public function show(Payment $payment)
    {
        return view('admin.payments.show', compact('payment'));
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('admin.payments.index')->with('success', 'Payment deleted successfully.');
    }
}
