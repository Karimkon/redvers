<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loan;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::latest()->paginate(20);
        return view('finance.loans.index', compact('loans'));
    }

    public function create()
    {
        return view('finance.loans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lender' => 'required|string',
            'amount' => 'required|numeric',
            'interest_rate' => 'required|numeric',
            'interest_paid' => 'nullable|numeric',
            'issued_date' => 'required|date',
            'due_date' => 'required|date',
            'status' => 'required|in:active,completed,defaulted',
            'attachment_id' => 'nullable|exists:attachments,id',
        ]);

        Loan::create($validated);
        return redirect()->route('finance.loans.index')->with('success', 'Loan added.');
    }

    public function show(Loan $loan)
    {
        return view('finance.loans.show', compact('loan'));
    }

    public function edit(Loan $loan)
    {
        return view('finance.loans.edit', compact('loan'));
    }

    public function update(Request $request, Loan $loan)
    {
        $validated = $request->validate([
            'lender' => 'required|string',
            'amount' => 'required|numeric',
            'interest_rate' => 'required|numeric',
            'interest_paid' => 'nullable|numeric',
            'issued_date' => 'required|date',
            'due_date' => 'required|date',
            'status' => 'required|in:active,completed,defaulted',
            'attachment_id' => 'nullable|exists:attachments,id',
        ]);

        $loan->update($validated);
        return redirect()->route('finance.loans.index')->with('success', 'Loan updated.');
    }

    public function destroy(Loan $loan)
    {
        $loan->delete();
        return redirect()->route('finance.loans.index')->with('success', 'Loan deleted.');
    }
}
