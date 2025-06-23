<?php

// app/Models/Purchase.php

namespace App\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'user_id',
        'motorcycle_id',
        'start_date',
        'motorcycle_unit_id',
        'purchase_type',
        'initial_deposit',
        'total_price',
        'amount_paid',
        'remaining_balance',
        'status',
    ];

    protected $casts = [
    'start_date' => 'date', // ✅ ensures it's a Carbon instance
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function motorcycle()
    {
        return $this->belongsTo(Motorcycle::class);
    }

    public function payments()
    {
        return $this->hasMany(MotorcyclePayment::class);
    }

    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }

    public function motorcycleUnit()
{
    return $this->belongsTo(MotorcycleUnit::class, 'motorcycle_unit_id');
}


public function getPaymentScheduleSummary()
{
    if ($this->status === 'completed') {
        return [
            'expected_days' => 0,
            'actual_payments' => 0,
            'missed_payments' => 0,
            'missed_dates' => collect(),
            'paid_ahead_days' => 0,
            'overpaid_amount' => 0,
            'remaining_expected_amount' => 0,
            'next_due_date' => null,
            'expected_dates' => [],
        ];
    }
    
    $startDate = $this->start_date ?? $this->created_at->copy()->startOfDay();
    $today = now()->startOfDay();
    $dailyRate = $this->motorcycle->daily_payment ?? 0;

    if (!$dailyRate || $dailyRate <= 0) {
        return [
            'expected_days' => 0,
            'actual_payments' => 0,
            'missed_payments' => 0,
            'missed_dates' => collect(),
            'paid_ahead_days' => 0,
            'overpaid_amount' => 0,
            'remaining_expected_amount' => 0,
            'next_due_date' => null,
            'expected_dates' => [],
        ];
    }

    // 1️⃣ Build all expected dates from start to today (excluding Sundays)
    $expectedDates = collect();
    $cursor = $startDate->copy();
    while ($cursor <= $today) {
        if (!$cursor->isSunday()) {
            $expectedDates->push($cursor->toDateString());
        }
        $cursor->addDay();
    }

    // 2️⃣ Total amount paid (cash + discounts)
    $totalPaidAmount = $this->payments->sum('amount') + $this->discounts->sum('amount');
    $daysCoveredByAmount = floor($totalPaidAmount / $dailyRate);

    // 3️⃣ Expected dates fully covered by amount
    $coveredDates = $expectedDates->take($daysCoveredByAmount);

    // 4️⃣ Get actual paid dates
    $paidDates = $this->payments
        ->pluck('payment_date')
        ->map(fn($d) => \Carbon\Carbon::parse($d)->toDateString())
        ->unique();

    // 5️⃣ Combine covered + paid = fully cleared
    $fullyPaidDates = $coveredDates->merge($paidDates)->unique();

    // 6️⃣ Remaining expected (unpaid) dates
    $unpaidDates = $expectedDates->diff($fullyPaidDates)->values();

    // 7️⃣ Missed count (if any unpaid amount remains)
    $remainingExpectedAmount = max(0, ($expectedDates->count() * $dailyRate) - $totalPaidAmount);
    $missedCount = ceil($remainingExpectedAmount / $dailyRate);

    // 8️⃣ Final missed dates (formatted nicely)
    $missedDates = $unpaidDates
        ->take($missedCount)
        ->map(fn($d) => \Carbon\Carbon::parse($d)->translatedFormat('l, F jS, Y'));

    // 9️⃣ Overpaid logic
    $overpaidAmount = max(0, $totalPaidAmount - ($expectedDates->count() * $dailyRate));
    $paidAhead = $overpaidAmount > 0 ? floor($overpaidAmount / $dailyRate) : 0;

    // 🔟 Next due date = first unpaid (non-Sunday) date
    $nextDueDate = $unpaidDates->first();

    if (!$nextDueDate) {
        // All expected dates are covered → find next available non-Sunday
        $next = $today->copy()->addDay();
        while ($next->isSunday()) {
            $next->addDay();
        }
        $nextDueDate = $next->toDateString();
    }

    return [
        'expected_days' => $expectedDates->count(),
        'actual_payments' => $paidDates->count(),
        'missed_payments' => $missedCount,
        'missed_dates' => $missedDates,
        'paid_ahead_days' => $paidAhead,
        'overpaid_amount' => $overpaidAmount,
        'remaining_expected_amount' => $remainingExpectedAmount,
        'next_due_date' => $nextDueDate,
        'expected_dates' => $expectedDates
            ->map(fn($d) => \Carbon\Carbon::parse($d)->translatedFormat('l, F jS, Y'))
            ->toArray(),
    ];
}



    /**
     * Auto-determine status based on how many days are missed.
     */
    public function determineStatusBasedOnMissedDays(): string
    {
        $missed = $this->getPaymentScheduleSummary()['missed_payments'] ?? 0;
        return $missed >= 8 ? 'defaulted' : 'active';
    }


    public function missedPayments()
    {
        return $this->hasMany(MissedPayment::class);
    }

    public function getDailyRateAttribute()
    {
        return $this->motorcycle->daily_payment ?? 0;
    }

    /**
     * Updated next due date logic that skips Sundays and respects paid days.
     */
    public function getNextExpectedPaymentDate(): ?string
{
    $rate = $this->getDailyRateAttribute();
    if (!$rate || $rate <= 0) return null;

    $today = now()->startOfDay();

    // ✅ Determine when to start generating expected dates
    $firstPaymentDate = $this->payments()->min('payment_date');
    $cursor = $firstPaymentDate
        ? Carbon::parse($firstPaymentDate)->copy()->startOfDay()
        : $this->start_date->copy()->startOfDay();

    // Generate expected non-Sunday payment dates
    $expectedDates = collect();
    while ($cursor <= $today) {
        if (!$cursor->isSunday()) {
            $expectedDates->push($cursor->toDateString());
        }
        $cursor->addDay();
    }

    // Get unique paid dates
    $paidDates = $this->payments
        ->pluck('payment_date')
        ->map(fn($d) => Carbon::parse($d)->toDateString())
        ->unique();

    // Return first expected date that is unpaid
    foreach ($expectedDates as $date) {
        if (!$paidDates->contains($date)) {
            return $date;
        }
    }

    // All up to today is paid → return next valid (non-Sunday) day
    $next = $today->copy()->addDay();
    while ($next->isSunday()) {
        $next->addDay();
    }

    return $next->toDateString();
}

    public function getAdjustedOverdueSummary()
    {
        $startDate = $this->start_date ?? $this->created_at->copy();
        $today = now()->startOfDay();
        $dailyRate = $this->getDailyRateAttribute();
        $expectedDates = collect();
        $cursor = $startDate->copy();

        while ($cursor <= $today) {
            if ($cursor->dayOfWeek !== 0) {
                $expectedDates->push($cursor->toDateString());
            }
            $cursor->addDay();
        }

        $expectedAmount = $expectedDates->count() * $dailyRate;
        $paidAmount = $this->payments->sum('amount') + $this->discounts->sum('amount');
        $overdue = $expectedAmount > $paidAmount;

        return [
            'is_overdue' => $overdue,
            'missed_days' => $overdue ? ceil(($expectedAmount - $paidAmount) / $dailyRate) : 0,
            'due_amount' => max($expectedAmount - $paidAmount, 0),
            'expected_payment' => $expectedAmount,
            'total_paid' => $paidAmount,
            'skipped_days_count' => $expectedDates->count(),
            'next_due_date' => $this->getNextExpectedPaymentDate(),
        ];
    }
}
