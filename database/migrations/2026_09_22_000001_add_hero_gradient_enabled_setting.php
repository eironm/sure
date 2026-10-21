<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!DB::table('system_settings')->where('key', 'hero_gradient_enabled')->exists()) {
            DB::table('system_settings')->insert([
                'key'        => 'hero_gradient_enabled',
                'value'      => '1',
                'type'       => 'boolean',
                'group'      => 'appearance',
                'label'      => 'Hero Gradient Overlay',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('system_settings')->where('key', 'hero_gradient_enabled')->delete();
    }
};
