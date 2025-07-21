<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expenditure;

class ExpenditureController extends Controller
{
    public function index()
    {
        $expenditures = Expenditure::latest()->paginate(20);
        return view('finance.expenditures.index', compact('expenditures'));
    }

    public function create()
    {
        return view('finance.expenditures.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string',
            'amount' => 'required|numeric',
            'payment_method' => 'required|in:bank,petty_cash',
            'date' => 'required|date',
            'attachment_id' => 'nullable|exists:attachments,id',
            'description' => 'nullable|string',
        ]);

        Expenditure::create($validated);
        return redirect()->route('finance.expenditures.index')->with('success', 'Expenditure recorded.');
    }

    public function show(Expenditure $expenditure)
    {
        return view('finance.expenditures.show', compact('expenditure'));
    }

    public function edit(Expenditure $expenditure)
    {
        return view('finance.expenditures.edit', compact('expenditure'));
    }

    public function update(Request $request, Expenditure $expenditure)
    {
        $validated = $request->validate([
            'category' => 'required|string',
            'amount' => 'required|numeric',
            'payment_method' => 'required|in:bank,petty_cash',
            'date' => 'required|date',
            'attachment_id' => 'nullable|exists:attachments,id',
            'description' => 'nullable|string',
        ]);

        $expenditure->update($validated);
        return redirect()->route('finance.expenditures.index')->with('success', 'Expenditure updated.');
    }

    public function destroy(Expenditure $expenditure)
    {
        $expenditure->delete();
        return redirect()->route('finance.expenditures.index')->with('success', 'Expenditure deleted.');
    }
}
