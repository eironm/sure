<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    /**
     * CMS Branding Expansion — Social, SEO, Email Signature, Announcement,
     * Login/Error pages and Site Appearance settings for full white-label control.
     */
    public function up(): void
    {
        $now = now();
        $settings = [
            // ── Social Media ──────────────────────────────────
            ['key' => 'social_facebook',  'value' => '', 'type' => 'text', 'group' => 'social', 'label' => 'Facebook Page URL'],
            ['key' => 'social_instagram', 'value' => '', 'type' => 'text', 'group' => 'social', 'label' => 'Instagram URL'],
            ['key' => 'social_tiktok',    'value' => '', 'type' => 'text', 'group' => 'social', 'label' => 'TikTok URL'],
            ['key' => 'social_x',         'value' => '', 'type' => 'text', 'group' => 'social', 'label' => 'X (Twitter) URL'],
            ['key' => 'social_linkedin',  'value' => '', 'type' => 'text', 'group' => 'social', 'label' => 'LinkedIn URL'],
            // ── SEO & Social Sharing ──────────────────────────
            ['key' => 'meta_description', 'value' => '', 'type' => 'textarea', 'group' => 'seo', 'label' => 'Site Meta Description'],
            ['key' => 'og_image',         'value' => '', 'type' => 'image',  'group' => 'seo', 'label' => 'Social Share Image (Open Graph)'],
            ['key' => 'site_keywords',    'value' => '', 'type' => 'text',   'group' => 'seo', 'label' => 'Site Keywords (comma-separated)'],
            // ── Email Signature & Sender ──────────────────────
            ['key' => 'email_signature',  'value' => '', 'type' => 'textarea', 'group' => 'email', 'label' => 'Email Signature (HTML)'],
            ['key' => 'mail_from_name',   'value' => '', 'type' => 'text',     'group' => 'email', 'label' => 'Email Sender Name'],
            // ── Announcement Banner ───────────────────────────
            ['key' => 'announcement_enabled', 'value' => '0', 'type' => 'boolean', 'group' => 'announcements', 'label' => 'Show Announcement Banner'],
            ['key' => 'announcement_text',    'value' => '', 'type' => 'textarea', 'group' => 'announcements', 'label' => 'Announcement Message'],
            // ── Login & Error Pages ───────────────────────────
            ['key' => 'login_welcome_text',  'value' => '', 'type' => 'textarea', 'group' => 'login', 'label' => 'Login Page Welcome Message'],
            ['key' => 'error_page_message',  'value' => '', 'type' => 'textarea', 'group' => 'login', 'label' => 'Custom Error Page Message'],
            // ── Site Appearance ───────────────────────────────
            ['key' => 'panel_font',          'value' => 'Figtree', 'type' => 'text', 'group' => 'appearance', 'label' => 'Panel Font Family'],
            ['key' => 'public_font',         'value' => '',      'type' => 'text', 'group' => 'appearance', 'label' => 'Public Site Font Family'],
            ['key' => 'hero_gradient_start', 'value' => '',      'type' => 'color', 'group' => 'appearance', 'label' => 'Hero Gradient Start Color'],
            ['key' => 'hero_gradient_end',   'value' => '',      'type' => 'color', 'group' => 'appearance', 'label' => 'Hero Gradient End Color'],
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

    public function down(): void
    {
        DB::table('system_settings')->whereIn('key', [
            'social_facebook', 'social_instagram', 'social_tiktok',
            'social_x', 'social_linkedin',
            'meta_description', 'og_image', 'site_keywords',
            'email_signature', 'mail_from_name',
            'announcement_enabled', 'announcement_text',
            'login_welcome_text', 'error_page_message',
            'panel_font', 'public_font',
            'hero_gradient_start', 'hero_gradient_end',
        ])->delete();
    }
};
