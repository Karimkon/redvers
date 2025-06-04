<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\{Purchase, MissedPayment};
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class FinancePurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with(['user', 'motorcycle']);

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%")
                        ->orWhere('nin_number', 'like', "%$search%");
                })
                ->orWhere('purchase_type', 'like', "%$search%")
                ->orWhere('status', 'like', "%$search%")
                ->orWhereHas('motorcycle', function ($mq) use ($search) {
                    $mq->where('type', 'like', "%$search%");
                });
            });
        }

        $purchases = $query->latest()->get();
        return view('finance.purchases.index', compact('purchases'));
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['user', 'motorcycle', 'payments', 'discounts']);

        // ✅ Detect missed payments (daily expected)
        if ($purchase->purchase_type === 'hire') {
            $expectedDates = CarbonPeriod::create($purchase->start_date, now());

            foreach ($expectedDates as $date) {
                $alreadyPaid = $purchase->payments->contains('payment_date', $date->format('Y-m-d'));

                if (!$alreadyPaid) {
                    MissedPayment::firstOrCreate([
                        'purchase_id' => $purchase->id,
                        'missed_date' => $date->format('Y-m-d'),
                    ]);
                }
            }
        }

        $missed = MissedPayment::where('purchase_id', $purchase->id)->orderByDesc('missed_date')->get();

        $schedule = [
            'expected_days' => $purchase->purchase_type === 'hire' ? Carbon::parse($purchase->start_date)->diffInDays(now()) + 1 : 1,
            'actual_payments' => $purchase->payments->count(),
            'missed_payments' => $missed->count(),
            'missed_dates' => $missed->pluck('missed_date'),
            'next_due_date' => $purchase->payments->last()
                ? Carbon::parse($purchase->payments->last()->payment_date)->addDay()->format('Y-m-d')
                : Carbon::parse($purchase->start_date)->format('Y-m-d'),
        ];

        return view('finance.purchases.show', compact('purchase', 'schedule'));
    }

}
