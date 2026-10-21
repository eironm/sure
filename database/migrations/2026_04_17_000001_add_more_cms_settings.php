<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $newSettings = [
            // Branding — Login page background image
            [
                'key'   => 'login_background',
                'value' => 'assets/auth-bg.jpg',
                'type'  => 'image',
                'group' => 'branding',
                'label' => 'Login Page Background Image',
            ],
            // Branding — Favicon
            [
                'key'   => 'favicon',
                'value' => 'assets/62-3974.webp',
                'type'  => 'image',
                'group' => 'branding',
                'label' => 'Favicon (Browser Tab Icon)',
            ],
            // Contact — Mailing address
            [
                'key'   => 'contact_address',
                'value' => '',
                'type'  => 'textarea',
                'group' => 'contact',
                'label' => 'Office / Mailing Address',
            ],
        ];

        foreach ($newSettings as $setting) {
            // Only insert if the key does not already exist
            if (DB::table('system_settings')->where('key', $setting['key'])->doesntExist()) {
                DB::table('system_settings')->insert(array_merge($setting, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }
    }

    public function down(): void
    {
        DB::table('system_settings')->whereIn('key', [
            'login_background',
            'favicon',
            'contact_address',
        ])->delete();
    }
};
