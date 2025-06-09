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

    if (!$dailyRate || $dailyRate <= 0 || $startDate->greaterThan($today)) {
        return [
            'expected_days' => 0,
            'actual_payments' => 0,
            'missed_payments' => 0,
            'missed_dates' => [],
            'paid_ahead_days' => 0,
            'overpaid_amount' => 0,
            'remaining_expected_amount' => 0,
            'next_due_date' => null,
            'expected_dates' => [],
        ];
    }

    // 🗓 Build expected payment dates excluding Sundays
    $expectedDates = collect();
    $cursor = $startDate->copy();
    while ($cursor <= $today) {
        if ($cursor->dayOfWeek !== 0) { // Exclude Sundays
            $expectedDates->push($cursor->toDateString());
        }
        $cursor->addDay();
    }

    // 🔍 Actual payment amount
    $actualPaidAmount = $this->payments->sum('amount') + $this->discounts->sum('amount');

    // 📊 Compute expected totals
    $expectedAmount = $expectedDates->count() * $dailyRate;
    $overpaidAmount = max(0, $actualPaidAmount - $expectedAmount);
    $paidAheadDays = $overpaidAmount > 0 ? floor($overpaidAmount / $dailyRate) : 0;
    $remainingExpectedAmount = max(0, $expectedAmount - $actualPaidAmount);
    $missedPayments = $actualPaidAmount < $expectedAmount
        ? ceil(($expectedAmount - $actualPaidAmount) / $dailyRate)
        : 0;

    // 🔜 Determine next due date
    $lastPaymentDate = $this->payments->last()?->payment_date;
    $nextDueDate = $lastPaymentDate
        ? Carbon::parse($lastPaymentDate)->addDay()->toDateString()
        : $startDate->copy()->toDateString();

    return [
        'expected_days' => $expectedDates->count(),
        'actual_payments' => $this->payments->count(),
        'missed_payments' => $missedPayments,
        'missed_dates' => [], // Optional: can build from expectedDates - paidDates
        'paid_ahead_days' => $paidAheadDays,
        'overpaid_amount' => $overpaidAmount,
        'remaining_expected_amount' => $remainingExpectedAmount,
        'next_due_date' => $nextDueDate,
        'expected_dates' => $expectedDates->toArray(),
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
