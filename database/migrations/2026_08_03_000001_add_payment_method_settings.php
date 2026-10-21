<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Add whitelabel payment method toggle settings.
     *
     * Adds two boolean system settings that let a SuperAdmin enable or
     * disable each payment gateway platform-wide. When disabled, the
     * option is hidden from the guest reservation wizard AND blocked at
     * the backend validation level.
     */
    public function up(): void
    {
        $now = now();

        $settings = [
            [
                'key'   => 'payment_method_cash_enabled',
                'value' => '1',
                'type'  => 'boolean',
                'group' => 'payment_methods',
                'label' => 'Enable Cash / Pay at Cashier',
            ],
            [
                'key'   => 'payment_method_paymongo_enabled',
                'value' => '1',
                'type'  => 'boolean',
                'group' => 'payment_methods',
                'label' => 'Enable Online Payment (PayMongo)',
            ],
        ];

        foreach ($settings as $setting) {
            if (!DB::table('system_settings')->where('key', $setting['key'])->exists()) {
                DB::table('system_settings')->insert(array_merge($setting, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('system_settings')->whereIn('key', [
            'payment_method_cash_enabled',
            'payment_method_paymongo_enabled',
        ])->delete();
    }
};
