<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds admin-configurable reservation policy settings to the
     * system_settings table for security deposit, payment deadlines,
     * fee waivers, endorsement, and cancellation policies.
     */
    public function up(): void
    {
        $now = now();

        $settings = [
            [
                'key'   => 'security_deposit_amount',
                'value' => '10000',
                'type'  => 'text',
                'group' => 'reservation',
                'label' => 'Security Deposit Amount (₱)',
            ],
            [
                'key'   => 'payment_deadline_days',
                'value' => '3',
                'type'  => 'text',
                'group' => 'reservation',
                'label' => 'Payment Deadline (Days)',
            ],
            [
                'key'   => 'internal_fee_waived',
                'value' => '1',
                'type'  => 'boolean',
                'group' => 'reservation',
                'label' => 'Waive Fees for Internal Users',
            ],
            [
                'key'   => 'require_endorsement_internal',
                'value' => '1',
                'type'  => 'boolean',
                'group' => 'reservation',
                'label' => 'Require Endorsement for Internal Reservations',
            ],
            [
                'key'   => 'cancellation_policy_text',
                'value' => 'Cancellations made less than 3 days before the event date will result in forfeiture of the security deposit. For cancellations made more than 3 days before the event, the security deposit will be refunded minus a 10% administrative fee.',
                'type'  => 'textarea',
                'group' => 'reservation',
                'label' => 'Cancellation Policy Text',
            ],
            [
                'key'   => 'reserve_hero_title',
                'value' => 'Reserve a Venue',
                'type'  => 'text',
                'group' => 'login',
                'label' => 'Reserve Page Hero Heading',
            ],
            [
                'key'   => 'reserve_hero_subheading',
                'value' => 'Find the perfect campus space for your event — availability checked in real time.',
                'type'  => 'textarea',
                'group' => 'login',
                'label' => 'Reserve Page Hero Subtitle',
            ],
        ];

        foreach ($settings as $setting) {
            // Only insert if the key doesn't already exist
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
            'security_deposit_amount',
            'payment_deadline_days',
            'internal_fee_waived',
            'require_endorsement_internal',
            'cancellation_policy_text',
            'reserve_hero_title',
            'reserve_hero_subheading',
        ])->delete();
    }
};
