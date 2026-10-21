<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Baseline accounts (idempotent, safe to re-run after any DB wipe).
        $this->call([
            UserSeeder::class,
            // Sample facilities, categories, tags, time slots & blackout dates.
            // The app is unusable without facilities — this makes every fresh
            // deployment work out of the box (QA found the live DB empty).
            SampleDataSeeder::class,
            // Default English legal documents so the footer policy pages are
            // never empty for new deployments.
            LegalDocsSeeder::class,
        ]);

        // Ensure guest reservation page copy exists idempotently
        if (\Illuminate\Support\Facades\Schema::hasTable('system_settings')) {
            $defaultSettings = [
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

            foreach ($defaultSettings as $setting) {
                if (!\Illuminate\Support\Facades\DB::table('system_settings')->where('key', $setting['key'])->exists()) {
                    \Illuminate\Support\Facades\DB::table('system_settings')->insert(array_merge($setting, [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]));
                }
            }
        }
    }
}
