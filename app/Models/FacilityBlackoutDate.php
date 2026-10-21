<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacilityBlackoutDate extends Model
{
    protected $table = 'facility_blackout_dates';

    protected $fillable = [
        'facility_id',
        'start_date',
        'end_date',
        'reason',
        'is_recurring',
        'recurring_pattern',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_recurring' => 'boolean',
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}
