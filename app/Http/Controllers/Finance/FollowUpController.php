<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FollowUp;
use Carbon\Carbon;

class FollowUpController extends Controller
{
    /**
     * Mark all pending follow-ups for a purchase as contacted.
     */
    public function markAsContacted(Request $request, $purchaseId)
    {
        $request->validate([
            'missed_date' => 'required|date',
        ]);

        FollowUp::create([
            'purchase_id' => $purchaseId,
            'missed_date' => $request->missed_date,
            'status' => 'contacted',
            'note' => 'Contacted by finance team',
            'contacted_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Marked as contacted');
    }


    /**
     * Show the follow-up history for a specific purchase.
     */
    public function history($purchaseId)
    {
        $followups = FollowUp::where('purchase_id', $purchaseId)->latest()->get();

        return view('finance.overdue.history', compact('followups'));
    }
}
