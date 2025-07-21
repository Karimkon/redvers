<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Investor;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use App\Models\Attachment;

class InvestorController extends Controller
{
    public function index()
    {
        $investors = Investor::latest()->paginate(20);
        return view('finance.investors.index', compact('investors'));
    }

    public function create()
    {
        return view('finance.investors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'contribution' => 'required|numeric',
            'ownership_percentage' => 'nullable|numeric',
            'payment_method' => 'required|in:bank,petty_cash',
            'date' => 'required|date',
        ]);

            // Handle attachment upload
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('attachments', 'public');

            $attachment = \App\Models\Attachment::create([
                'file_path' => $path,
                'file_type' => $file->getClientMimeType(),
                'description' => 'Investor document'
            ]);

            $validated['attachment_id'] = $attachment->id;
        }


        Investor::create($validated);
        return redirect()->route('finance.investors.index')->with('success', 'Investor recorded.');
    }

    public function show(Investor $investor)
    {
        return view('finance.investors.show', compact('investor'));
    }

    public function edit(Investor $investor)
    {
        return view('finance.investors.edit', compact('investor'));
    }

    public function update(Request $request, Investor $investor)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'contribution' => 'required|numeric',
            'ownership_percentage' => 'nullable|numeric',
            'payment_method' => 'required|in:bank,petty_cash',
            'date' => 'required|date',
        ]);

        
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('attachments', 'public');

            $attachment = \App\Models\Attachment::create([
                'file_path' => $path,
                'file_type' => $file->getClientMimeType(),
                'description' => 'Investor updated document'
            ]);

            $validated['attachment_id'] = $attachment->id;
        }

        $investor->update($validated);
        return redirect()->route('finance.investors.index')->with('success', 'Investor updated.');
    }

    public function viewAttachment($id)
    {
        $attachment = \App\Models\Attachment::findOrFail($id);

        // Use full path or storage
        $path = storage_path('app/' . $attachment->file_path);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path); // Or ->download($path) if you want to force download
    }


    public function destroy(Investor $investor)
    {
        $investor->delete();
        return redirect()->route('finance.investors.index')->with('success', 'Investor removed.');
    }
}
