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
    $skipDays = [0]; // 0 = Sunday

    $expectedDates = collect();
    $cursor = $startDate->copy();

    while ($cursor <= $today) {
        if (!in_array($cursor->dayOfWeek, $skipDays)) {
            $expectedDates->push($cursor->toDateString());
        }
        $cursor->addDay();
    }

    $actualPaidDates = $this->payments
        ->pluck('payment_date')
        ->map(fn($d) => \Carbon\Carbon::parse($d)->toDateString());

    $expectedAmount = $expectedDates->count() * $dailyRate;
    $totalPaid = $this->payments->sum('amount');
    $overpaid = $totalPaid - $expectedAmount;

    $missedDatesRaw = $expectedDates->diff($actualPaidDates);
    $isOverdue = $totalPaid < $expectedAmount;

    $missedDates = $isOverdue
        ? $missedDatesRaw
            ->take(ceil(($expectedAmount - $totalPaid) / ($dailyRate > 0 ? $dailyRate : 1)))
            ->map(fn($date) => \Carbon\Carbon::parse($date)->translatedFormat('F jS, Y'))
            ->values()
        : collect();

    return [
        'expected_days' => $expectedDates->count(),
        'actual_payments' => $actualPaidDates->count(),
        'missed_payments' => $missedDates->count(),
        'missed_dates' => $missedDates,
        'paid_ahead_days' => ($overpaid > 0 && $dailyRate > 0)
            ? floor($overpaid / $dailyRate)
            : 0,
        'overpaid_amount' => $overpaid > 0 ? $overpaid : 0,
        'next_due_date' => $today->copy()->addDay()->toDateString(), // cloned safely
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
    $paidAmount = $this->payments->sum('amount');
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
