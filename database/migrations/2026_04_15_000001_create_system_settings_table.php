<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, textarea, image, boolean, color
            $table->string('group')->default('general'); // general, branding, hero, contact
            $table->string('label')->nullable(); // Human-readable label for admin UI
            $table->timestamps();
        });

        // Seed default branding settings
        $defaults = [
            // Branding
            ['key' => 'system_name', 'value' => 'SURE', 'type' => 'text', 'group' => 'branding', 'label' => 'System Name'],
            ['key' => 'system_tagline', 'value' => 'Smart University Reservation Engine', 'type' => 'text', 'group' => 'branding', 'label' => 'System Tagline'],
            ['key' => 'system_logo', 'value' => 'assets/logo-dark.png', 'type' => 'image', 'group' => 'branding', 'label' => 'System Logo'],
            ['key' => 'admin_title', 'value' => 'SURE Admin', 'type' => 'text', 'group' => 'branding', 'label' => 'Admin Panel Title'],

            // Colors
            ['key' => 'brand_primary', 'value' => '#2563eb', 'type' => 'color', 'group' => 'branding', 'label' => 'Primary Brand Color'],
            ['key' => 'brand_secondary', 'value' => '#7c3aed', 'type' => 'color', 'group' => 'branding', 'label' => 'Secondary Brand Color'],
            ['key' => 'brand_accent', 'value' => '#06b6d4', 'type' => 'color', 'group' => 'branding', 'label' => 'Accent Color'],
            ['key' => 'brand_dark', 'value' => '#0f172a', 'type' => 'color', 'group' => 'branding', 'label' => 'Dark Background Color'],

            // Hero Section
            ['key' => 'hero_heading', 'value' => 'Reserve University Facilities', 'type' => 'text', 'group' => 'hero', 'label' => 'Hero Heading'],
            ['key' => 'hero_subheading', 'value' => 'Book campus spaces effortlessly with our streamlined reservation platform.', 'type' => 'textarea', 'group' => 'hero', 'label' => 'Hero Subheading'],

            // Contact
            ['key' => 'contact_email', 'value' => 'no-reply@prod.qzz.io', 'type' => 'text', 'group' => 'contact', 'label' => 'Contact Email'],
            ['key' => 'contact_phone', 'value' => '', 'type' => 'text', 'group' => 'contact', 'label' => 'Contact Phone'],

            // Footer
            ['key' => 'footer_text', 'value' => '© 2026 SURE. All rights reserved.', 'type' => 'text', 'group' => 'general', 'label' => 'Footer Text'],
        ];

        foreach ($defaults as $setting) {
            DB::table('system_settings')->insert(array_merge($setting, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
