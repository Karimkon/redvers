<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Payment;

class FinancePaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['swap.rider'])->latest()->paginate(10);
        return view('finance.payments.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        return view('finance.payments.show', compact('payment'));
    }
}
