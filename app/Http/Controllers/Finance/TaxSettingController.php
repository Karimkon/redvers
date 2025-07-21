<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TaxSetting;
use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;

class TaxSettingController extends Controller
{
    public function index()
    {
        $taxes = TaxSetting::latest()->paginate(15); // or whatever number you want
        return view('finance.taxes.index', compact('taxes'));
    }

    public function create()
    {
        return view('finance.taxes.create');
    }


    public function edit(TaxSetting $tax)
    {
        return view('finance.taxes.edit', compact('tax'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'rate' => 'required|numeric',
        'active' => 'required|boolean',
        'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:2048',
    ]);

    // Handle attachment if present
    if ($request->hasFile('attachment')) {
        $file = $request->file('attachment');
        $path = $file->store('attachments', 'public');

        $attachment = Attachment::create([
            'file_path' => $path,
            'file_type' => $file->getClientMimeType(),
            'description' => 'Tax Setting Document',
        ]);

        $validated['attachment_id'] = $attachment->id;
    }

    TaxSetting::create($validated);

    return redirect()->route('finance.taxes.index')->with('success', 'Tax setting saved successfully.');
}

    public function update(Request $request, TaxSetting $tax)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'rate' => 'required|numeric',
            'active' => 'required|boolean',
        ]);

        $tax->update($validated);
        return redirect()->route('finance.taxes.index')->with('success', 'Tax updated.');
    }

    public function destroy(TaxSetting $tax)
{
    // Delete attachment file if exists
    if ($tax->attachment) {
        \Storage::disk('public')->delete($tax->attachment->file_path);
        $tax->attachment->delete();
    }

    $tax->delete();

    return redirect()->route('finance.taxes.index')->with('success', 'Tax setting deleted successfully.');
}

}
