<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Normalize raw / legacy reservation status values to the canonical set:
 * pending, approved, denied, cancelled, completed
 *
 * Idempotent and DB-agnostic (works on SQLite locally and MySQL on production).
 * Safe to run multiple times: each UPDATE only touches rows that still carry
 * the old value.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('reservations') || !Schema::hasColumn('reservations', 'status')) {
            return;
        }

        $mappings = [
            // legacy compound / typo-ish values observed in production
            'expired_unpaid_cancelled' => 'cancelled',
            'expired_unpaid'           => 'cancelled',
            'unpaid_expired'           => 'cancelled',
            'unpaid'                   => 'cancelled',
            'expired'                  => 'cancelled',
            'canceled'                 => 'cancelled', // american spelling variant
            'cancellation'             => 'cancelled',
            'reject'                   => 'denied',
            'rejected'                 => 'denied',
            'declined'                 => 'denied',
            'confirm'                  => 'approved',
            'confirmed'                => 'approved',
            'accept'                   => 'approved',
            'accepted'                 => 'approved',
            'done'                     => 'completed',
            'finished'                 => 'completed',
        ];

        foreach ($mappings as $old => $new) {
            $affected = DB::table('reservations')
                ->where('status', $old)
                ->update(['status' => $new]);

            if ($affected > 0) {
                $this->output("Migrated $affected reservation(s): '$old' -> '$new'");
            }
        }
    }

    public function down(): void
    {
        // No reverse: legacy values cannot be reconstructed from the normalized ones.
    }

    protected function output(string $message): void
    {
        if (app()->runningInConsole()) {
            echo $message . PHP_EOL;
        }
    }
};
