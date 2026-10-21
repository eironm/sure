<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * One-time data fix: reset all facility time slot capacities to 1.
 *
 * Before availability became capacity-aware, slot capacity was ignored for
 * booking conflicts (every overlap was rejected), and the admin forms
 * pre-filled the field with the facility's PEOPLE capacity (e.g., 100).
 * Left as-is, those values would suddenly allow up to 100 overlapping
 * bookings per slot.
 *
 * Resetting to 1 exactly preserves the system's historical exclusive
 * behavior. Admins can deliberately raise capacity per slot where
 * concurrent bookings are genuinely intended (e.g., multiple courts).
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('facility_time_slots')->update(['capacity' => 1]);
    }

    public function down(): void
    {
        // Irreversible data normalization — previous values were accidental
        // copies of facility headcounts and are not worth restoring.
    }
};
