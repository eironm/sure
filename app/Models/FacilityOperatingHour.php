<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacilityOperatingHour extends Model
{
    protected $table = 'facility_operating_hours';

    protected $fillable = [
        'facility_id',
        'day_of_week',
        'opening_time',
        'closing_time',
        'is_closed',
    ];

    protected $casts = [
        'is_closed' => 'boolean',
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public static $dayNames = [
        0 => 'Sunday',
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
    ];

    public function getDayName()
    {
        return self::$dayNames[$this->day_of_week] ?? 'Unknown';
    }
}
