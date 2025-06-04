<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\{Purchase, MissedPayment, FollowUp};
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class FinanceOverdueController extends Controller
{
public function index(Request $request)
{
    $statusFilter = $request->input('status');      // 'pending' or 'contacted'
    $minMissedDays = $request->input('min_days');   // Minimum missed days

    $purchases = Purchase::with(['user', 'payments', 'motorcycle', 'discounts'])
        ->where('status', 'active')
        ->where('purchase_type', 'hire')
        ->get();

    $overdueRiders = [];

    foreach ($purchases as $purchase) {
        if (!$purchase->start_date) continue;

        // Get the smart overdue summary (handles discounts + Sundays)
        $summary = $purchase->getAdjustedOverdueSummary();

        // Skip if not actually overdue
        if (!$summary['is_overdue']) continue;

        // Lookup latest follow-up
        $lastFollowUp = \App\Models\FollowUp::where('purchase_id', $purchase->id)
            ->latest()
            ->first();

        $status = $lastFollowUp->status ?? 'pending';

        // Apply filters
        if ($statusFilter && $status !== $statusFilter) continue;
        if ($minMissedDays && $summary['missed_days'] < $minMissedDays) continue;

        // Log missed dates in missed_payments table
        foreach (range(1, $summary['missed_days']) as $i) {
            // Only create missed payments if not already logged (optional)
            // You could improve this by comparing expectedDates directly
        }

        // Add to list
        $overdueRiders[] = (object) [
            'name' => $purchase->user->name,
            'phone' => $purchase->user->phone,
            'missed_days' => $summary['missed_days'],
            'due_amount' => $summary['due_amount'],
            'purchase_id' => $purchase->id,
            'latest_followup' => $lastFollowUp,
        ];
    }

    return view('finance.overdue.index', compact('overdueRiders', 'statusFilter', 'minMissedDays'));
}



    public function markAsContacted(Request $request, $purchaseId)
{
    $request->validate([
        'missed_date' => 'required|date',
    ]);

    \App\Models\MissedPayment::where('purchase_id', $purchaseId)
        ->where('missed_date', $request->missed_date)
        ->where('status', 'pending')
        ->update([
            'status' => 'contacted',
            'note' => 'Contacted by finance team',
            'contacted_at' => now(),
        ]);

    // Create a FollowUp record to reflect the contact attempt
    \App\Models\FollowUp::create([
        'purchase_id' => $purchaseId,
        'missed_date' => $request->missed_date,
        'status' => 'contacted',
        'note' => 'Contacted by finance team', // Consistent note
        'contacted_at' => now(),
    ]);

    return redirect()->back()->with('success', 'Marked as contacted');
}



    public function exportCsv()
    {
        $filename = 'overdue_riders_' . now()->format('Ymd_His') . '.csv';
        $headers = ['Content-Type' => 'text/csv'];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Name', 'Phone', 'Missed Days', 'Expected Payment']);

            $today = Carbon::today();

            $purchases = Purchase::with(['user', 'payments', 'motorcycle'])
                ->where('status', 'active')
                ->where('purchase_type', 'hire')
                ->get();

            foreach ($purchases as $purchase) {
                if (!$purchase->start_date) continue;

                $start = Carbon::parse($purchase->start_date);
                $dailyRate = $purchase->daily_rate;


                $period = CarbonPeriod::create($start, $today);
                $paidDates = $purchase->payments
                    ->pluck('payment_date')
                    ->map(fn($d) => Carbon::parse($d)->toDateString())
                    ->unique();

                $missedCount = 0;

                foreach ($period as $date) {
                    if (!$paidDates->contains($date->toDateString())) {
                        $missedCount++;
                    }
                }

                if ($missedCount > 0) {
                    fputcsv($handle, [
                        $purchase->user->name,
                        $purchase->user->phone,
                        $missedCount,
                        'UGX ' . number_format($missedCount * $dailyRate)
                    ]);
                }
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, array_merge($headers, [
            "Content-Disposition" => "attachment; filename=\"$filename\""
        ]));
    }

    public function history(Purchase $purchase)
    {
        $history = FollowUp::where('purchase_id', $purchase->id)->latest()->get();
        return view('finance.overdue.history', compact('purchase', 'history'));
    }
}