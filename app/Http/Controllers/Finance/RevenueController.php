<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Revenue;

class RevenueController extends Controller
{
    public function index()
    {
        $revenues = Revenue::latest()->paginate(20);
        return view('finance.revenues.index', compact('revenues'));
    }

    public function create()
    {
        return view('finance.revenues.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'source' => 'required|string',
            'amount' => 'required|numeric',
            'payment_method' => 'required|in:bank,petty_cash',
            'date' => 'required|date',
            'attachment_id' => 'nullable|exists:attachments,id',
            'reference' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        Revenue::create($validated);
        return redirect()->route('finance.revenues.index')->with('success', 'Revenue recorded.');
    }

    public function show(Revenue $revenue)
    {
        return view('finance.revenues.show', compact('revenue'));
    }

    public function edit(Revenue $revenue)
    {
        return view('finance.revenues.edit', compact('revenue'));
    }

    public function update(Request $request, Revenue $revenue)
    {
        $validated = $request->validate([
            'source' => 'required|string',
            'amount' => 'required|numeric',
            'payment_method' => 'required|in:bank,petty_cash',
            'date' => 'required|date',
            'attachment_id' => 'nullable|exists:attachments,id',
            'reference' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $revenue->update($validated);
        return redirect()->route('finance.revenues.index')->with('success', 'Revenue updated.');
    }

    public function destroy(Revenue $revenue)
    {
        $revenue->delete();
        return redirect()->route('finance.revenues.index')->with('success', 'Revenue deleted.');
    }
}
