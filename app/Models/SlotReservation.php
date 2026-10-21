<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SlotReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'facility_time_slot_id',
        'reserved_date',
        'quantity_booked',
        'price_snapshot',
    ];

    protected $casts = [
        'reserved_date' => 'date',
        'quantity_booked' => 'integer',
        'price_snapshot' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the reservation this slot reservation belongs to.
     */
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * Get the facility time slot.
     */
    public function facilityTimeSlot(): BelongsTo
    {
        return $this->belongsTo(FacilityTimeSlot::class);
    }
}
