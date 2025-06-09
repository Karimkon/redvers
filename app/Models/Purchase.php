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

    // 🗓️ Step 1: Build expected dates (excluding Sundays)
    $expectedDates = collect();
    $cursor = $startDate->copy();
    while ($cursor <= $today) {
        if ($cursor->dayOfWeek !== 0) {
            $expectedDates->push($cursor->toDateString());
        }
        $cursor->addDay();
    }

    // 🧾 Step 2: Get actual payment dates (ignoring duplicates)
    $paidDates = $this->payments
        ->pluck('payment_date')
        ->map(fn($d) => \Carbon\Carbon::parse($d)->toDateString())
        ->unique();

    // 💰 Step 3: Include discount value in "virtual paid amount"
    $actualPaidAmount = $this->payments->sum('amount') + $this->discounts->sum('amount');
    $expectedAmount = $expectedDates->count() * $dailyRate;
    $overpaid = max(0, $actualPaidAmount - $expectedAmount);
    $remainingExpectedAmount = max(0, $expectedAmount - $actualPaidAmount);
    $paidAhead = $overpaid > 0 ? floor($overpaid / $dailyRate) : 0;

    // 🔍 Step 4: Identify missed dates and trim by how many are really unpaid
    $rawMissedDates = $expectedDates->diff($paidDates);
    $missedCount = ceil($remainingExpectedAmount / $dailyRate);
    $missedDates = $rawMissedDates
        ->take($missedCount)
        ->map(fn($d) => \Carbon\Carbon::parse($d)->translatedFormat('l, F jS, Y'))
        ->values();

    $lastPaymentDate = $this->payments->last()?->payment_date;
    $nextDueDate = $lastPaymentDate
        ? \Carbon\Carbon::parse($lastPaymentDate)->addDay()->toDateString()
        : $startDate->toDateString();

    return [
        'expected_days' => $expectedDates->count(),
        'actual_payments' => $paidDates->count(),
        'missed_payments' => $missedCount,
        'missed_dates' => $missedDates,
        'paid_ahead_days' => $paidAhead,
        'overpaid_amount' => $overpaid,
        'remaining_expected_amount' => $remainingExpectedAmount,
        'next_due_date' => $nextDueDate,
        'expected_dates' => $expectedDates
            ->map(fn($d) => \Carbon\Carbon::parse($d)->translatedFormat('l, F jS, Y'))
            ->toArray(),
    ];
}




    public function missedPayments()
    {
        return $this->hasMany(MissedPayment::class);
    }

    public function getDailyRateAttribute()
    {
        return $this->motorcycle->daily_payment ?? 0;
    }

 public function getAdjustedOverdueSummary()
{
    $startDate = $this->start_date ?? $this->created_at->copy();
    $today = now()->startOfDay();
    $dailyRate = $this->motorcycle->daily_payment ?? 0;
    $skipWeekdays = [0]; // 0 = Sunday

    $expectedDates = collect();
    $current = $startDate->copy();

    while ($current <= $today) {
        if (!in_array($current->dayOfWeek, $skipWeekdays)) {
            $expectedDates->push($current->toDateString());
        }
        $current->addDay();
    }

    $expectedAmount = $expectedDates->count() * $dailyRate;
    $paidAmount = $this->payments->sum('amount') + $this->discounts->sum('amount');
    $overdue = $expectedAmount > $paidAmount;

    return [
        'is_overdue' => $overdue,
        'missed_days' => $overdue
            ? ceil(($expectedAmount - $paidAmount) / $dailyRate)
            : 0,
        'due_amount' => max($expectedAmount - $paidAmount, 0),
        'expected_payment' => $expectedAmount,
        'total_paid' => $paidAmount,
        'skipped_days_count' => $expectedDates->count(),
        'next_due_date' => $current->toDateString(),
    ];
}



}
