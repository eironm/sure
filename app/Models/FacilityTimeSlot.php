<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FacilityTimeSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'facility_id',
        'day_of_week',
        'start_time',
        'end_time',
        'capacity',
        'price_per_hour',
        'is_active',
    ];

    protected $casts = [
        'day_of_week' => 'integer',
        'capacity' => 'integer',
        'price_per_hour' => 'decimal:2',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the facility this time slot belongs to.
     */
    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    /**
     * Get the slot reservations for this time slot.
     */
    public function slotReservations(): HasMany
    {
        return $this->hasMany(SlotReservation::class);
    }

    /**
     * Get day name from day_of_week number.
     */
    public static function getDayName(int $dayOfWeek): string
    {
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        return $days[$dayOfWeek] ?? 'Unknown';
    }

    /**
     * Get the day name for this slot.
     */
    public function getDayNameAttribute(): string
    {
        return self::getDayName($this->day_of_week);
    }

    /**
     * Check if this slot is available on a specific date.
     */
    public function isAvailableOn(\DateTime|string $date, int $quantityNeeded = 1): bool
    {
        $date = is_string($date) ? \Carbon\Carbon::parse($date) : $date;

        // Check if slot is active
        if (!$this->is_active) {
            return false;
        }

        // Check if day of week matches
        if ($date->dayOfWeek !== $this->day_of_week) {
            return false;
        }

        // Check if facility has blackout date
        $hasBlackout = FacilityBlackoutDate::where('facility_id', $this->facility_id)
            ->where(function ($query) use ($date) {
                $query->whereBetween('start_date', [$date, $date])
                    ->orWhereBetween('end_date', [$date, $date])
                    ->orWhere(function ($q) use ($date) {
                        $q->where('start_date', '<=', $date)
                            ->where('end_date', '>=', $date);
                    });
            })
            ->exists();

        if ($hasBlackout) {
            return false;
        }

        // Check capacity
        $bookedCount = SlotReservation::where('facility_time_slot_id', $this->id)
            ->where('reserved_date', $date)
            ->sum('quantity_booked');

        return ($bookedCount + $quantityNeeded) <= $this->capacity;
    }

    /**
     * Get available quantity on a specific date.
     */
    public function getAvailableQuantityOn(\DateTime|string $date): int
    {
        $date = is_string($date) ? \Carbon\Carbon::parse($date) : $date;

        // Check if slot is available first
        if (!$this->isAvailableOn($date)) {
            return 0;
        }

        $bookedCount = SlotReservation::where('facility_time_slot_id', $this->id)
            ->where('reserved_date', $date)
            ->sum('quantity_booked');

        return max(0, $this->capacity - $bookedCount);
    }

    /**
     * Get availability status for a specific date.
     */
    public function getAvailabilityStatusOn(\DateTime|string $date): string
    {
        $available = $this->getAvailableQuantityOn($date);

        if ($available === 0) {
            return 'booked';
        }

        if ($available < ceil($this->capacity * 0.3)) {
            return 'almost_full';
        }

        return 'available';
    }
}
