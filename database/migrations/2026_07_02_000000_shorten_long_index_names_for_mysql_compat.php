<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Index renames: [table => [old auto-generated name => new short name]].
     *
     * The old auto-generated names exceed MySQL's 64-character identifier
     * limit. Fresh installs already get the short names (set explicitly in
     * the original create-table migrations); this migration fixes databases
     * that were migrated before the rename.
     */
    private array $renames = [
        'facility_time_slots' => [
            'facility_time_slots_facility_id_day_of_week_start_time_end_time_unique' => 'fts_facility_day_time_unique',
        ],
        'slot_reservations' => [
            'slot_reservations_reservation_id_facility_time_slot_id_reserved_date_unique' => 'slot_res_reservation_slot_date_unique',
        ],
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->renameIndexes(false);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->renameIndexes(true);
    }

    private function renameIndexes(bool $reverse): void
    {
        foreach ($this->renames as $tableName => $indexes) {
            if (! Schema::hasTable($tableName)) {
                continue;
            }

            foreach ($indexes as $from => $to) {
                if ($reverse) {
                    [$from, $to] = [$to, $from];
                }

                // Skip if already renamed (e.g. fresh installs with short names)
                if (! Schema::hasIndex($tableName, $from) || Schema::hasIndex($tableName, $to)) {
                    continue;
                }

                Schema::table($tableName, function (Blueprint $table) use ($from, $to) {
                    $table->renameIndex($from, $to);
                });
            }
        }
    }
};
