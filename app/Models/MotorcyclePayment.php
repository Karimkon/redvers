<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class MotorcyclePayment extends Model
{
    protected $fillable = [
        'purchase_id',
        'user_id',          // ✅ Make sure this is included
        'payment_date',     // ✅ Changed from 'date' to 'payment_date' for consistency
        'amount',
        'type',             // ✅ daily, weekly, lump_sum
        'method',           // ✅ pesapal, cash, promo, etc.
        'reference',        // ✅ payment reference
        'status',           // ✅ paid, pending, failed
        'note',             // ✅ optional notes
    ];

    protected $casts = [
        'payment_date' => 'date', // ✅ Ensure this is cast as a date
        'amount' => 'decimal:2',
    ];

    /**
     * Relationship to Purchase
     */
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    /**
     * Relationship to User (Rider)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for today's payments
     */
    public function scopeToday($query)
    {
        return $query->whereDate('payment_date', Carbon::now('Africa/Kampala')->toDateString());
    }

    /**
     * Scope for a specific rider
     */
    public function scopeForRider($query, $riderId)
    {
        return $query->where('user_id', $riderId);
    }

    /**
     * Scope for a specific date
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('payment_date', $date);
    }

    /**
     * Check if a rider has paid today
     */
    public static function riderPaidToday($riderId)
    {
        $today = Carbon::now('Africa/Kampala')->toDateString();
        
        return self::where('user_id', $riderId)
            ->whereDate('payment_date', $today)
            ->where('status', 'paid')
            ->exists();
    }

    /**
     * Get payment with proper timezone formatting
     */
    public function getFormattedDateAttribute()
    {
        return $this->payment_date ? 
            Carbon::parse($this->payment_date)->setTimezone('Africa/Kampala')->format('M d, Y') : 
            'N/A';
    }
}