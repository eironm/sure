<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PencilBooking extends Model
{
    /** Days before a pencil hold silently expires. */
    public const HOLD_DAYS = 3;

    protected $fillable = [
        'user_id',
        'facility_id',
        'date',
        'note',
        'expires_at',
    ];

    protected $casts = [
        'date' => 'date',
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    /** Only holds that have not expired yet. */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('expires_at', '>', now());
    }
}
