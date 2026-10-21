<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!DB::table('system_settings')->where('key', 'allow_multiple_advance_reservations')->exists()) {
            DB::table('system_settings')->insert([
                'key'        => 'allow_multiple_advance_reservations',
                'value'      => '0',
                'type'       => 'boolean',
                'group'      => 'reservation',
                'label'      => 'Allow Multiple Advance Reservations Per Account',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('system_settings')
            ->where('key', 'allow_multiple_advance_reservations')
            ->delete();
    }
};

