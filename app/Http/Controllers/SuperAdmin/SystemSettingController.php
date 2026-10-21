<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Services\BrandingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * SuperAdmin System Settings — one dedicated, plain HTML page per category.
 * No SPA machinery: every link is a normal full-page navigation.
 */
class SystemSettingController extends Controller
{
    private const TABS = [
        'branding', 'payments', 'announcements', 'pages', 'appearance', 'logo', 'reports',
    ];

    /**
     * Human-readable labels for each setting group (branding page grid).
     */
    private const GROUP_LABELS = [
        'branding'       => 'Branding &amp; Identity',
        'payments'       => 'Payment Methods',
        'announcements'  => 'Announcements',
        'login'          => 'Login &amp; Error Pages',
        'appearance'     => 'Site Appearance',
        'reservation'    => 'Reservation',
        'general'        => 'General',
        'social'         => 'Social',
        'seo'            => 'SEO',
        'email'          => 'Email',
        'reports'        => 'Report Letterhead &amp; Footer',
    ];

    /** Bootstrap-icons class used next to each group label. */
    private const GROUP_ICONS = [
        'branding'      => 'bi-palette-fill',
        'payments'      => 'bi-credit-card-fill',
        'announcements' => 'bi-megaphone-fill',
        'login'         => 'bi-door-open-fill',
        'appearance'    => 'bi-brush-fill',
        'reservation'   => 'bi-calendar-check-fill',
        'general'       => 'bi-gear-fill',
        'social'        => 'bi-share-fill',
        'seo'           => 'bi-search',
        'email'         => 'bi-envelope-fill',
        'reports'       => 'bi-file-earmark-richtext-fill',
    ];

    /** User-friendly human labels for file upload settings validation. */
    private const FRIENDLY_LABELS = [
        'report_header_left_logo'  => 'Left Seal (Primary)',
        'report_header_right_logo' => 'Right Seal (Auxiliary)',
        'report_header_image'      => 'Header Banner Image',
        'report_footer_image'      => 'Footer Banner Image',
        'system_logo'              => 'Primary System Logo',
        'logo_light'               => 'Light Theme Logo',
        'logo_white'               => 'White Logo',
        'favicon'                  => 'Favicon',
        'login_background'         => 'Login Background Image',
        'og_image'                 => 'Social Share Image',
    ];

    /**
     * Show a single System Settings category page.
     */
    public function category(string $tab)
    {
        if (auth()->user()?->isStaff()) {
            abort(403, 'Staff accounts do not have access to report letterhead settings.');
        }

        $tab = in_array($tab, self::TABS, true) ? $tab : 'branding';

        $this->seedOptionalRows();

        // All settings except legal (managed by the dedicated Legal editor).
        $settings = SystemSetting::where('group', '!=', 'legal')
            ->get()
            ->groupBy('group');

        return view('superadmin.settings.system.category.' . $tab, [
            'tab'        => $tab,
            'settings'   => $settings,
            'groupLabels' => self::GROUP_LABELS,
            'groupIcons'  => self::GROUP_ICONS,
        ]);
    }

    /**
     * Save all submitted settings from the category form.
     */
    public function update(Request $request)
    {
        if ($request->user()?->isStaff()) {
            abort(403, 'Staff accounts do not have access to report letterhead settings.');
        }

        $this->seedOptionalRows();

        // Legal documents are managed by LegalSettingController; never touch them here.
        $settings = SystemSetting::where('group', '!=', 'legal')->get();

        foreach ($settings as $setting) {
            $key = $setting->key;

            if ($setting->type === 'image') {
                // The CMS crop modal writes the final file into a hidden input
                // named settings.cropped_input_{key} instead of the plain one.
                // Prefer the cropped result when present; fall back to the plain
                // upload input so a direct "Choose File" (no crop) still saves.
                $hasCropped = $request->hasFile("settings.cropped_input_{$key}");
                $hasPlain   = $request->hasFile("settings.{$key}");
                $inputKey   = $hasCropped ? "cropped_input_{$key}" : $key;

                // Handle removal of report branding images
                if ($request->input("remove_{$key}") == '1') {
                    $setting->update(['value' => null]);
                    continue;
                }

                if (! $request->hasFile("settings.{$inputKey}")) {
                    continue; // Nothing submitted — leave the existing value.
                }

                $file = $request->file("settings.{$inputKey}");

                if ($file->getSize() <= 0) {
                    continue; // Never store a 0-byte placeholder.
                }

                $label = self::FRIENDLY_LABELS[$key] ?? ucwords(str_replace(['_', 'settings.'], ' ', $key));

                // Favicon uses extension-based validation (.ico bypasses fragile mime checks)
                if ($key === 'favicon') {
                    $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension());
                    if (! in_array($extension, ['ico', 'png', 'jpg', 'jpeg', 'webp', 'svg'], true)) {
                        return redirect()->back()->withErrors([
                            "settings.{$key}" => "The {$label} must be a file of type: ICO, PNG, JPG, JPEG, WebP, or SVG.",
                        ]);
                    }
                    if ($file->getSize() > 4096 * 1024) {
                        return redirect()->back()->withErrors([
                            "settings.{$key}" => "The {$label} must not be greater than 4MB.",
                        ]);
                    }
                } else {
                    $request->validate([
                        "settings.{$inputKey}" => ['image', 'mimes:png,jpg,jpeg,webp,svg', 'max:4096'],
                    ], [
                        "settings.{$inputKey}.image" => "The {$label} must be a valid image file.",
                        "settings.{$inputKey}.mimes" => "The {$label} must be an image of type: PNG, JPG, JPEG, WebP, or SVG.",
                        "settings.{$inputKey}.max"   => "The {$label} must not be larger than 4MB.",
                    ], [
                        "settings.{$inputKey}" => $label,
                    ]);
                }

                $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'png');

                // Favicons, vector/icon formats, and original PNG logos/seals must NOT
                // be rasterised or converted (PNG transparency must be preserved).
                // Everything else is compressed through the shared ImageService.
                $isPngLogo = in_array($key, [
                    'system_logo', 'logo_light', 'logo_white',
                    'report_header_left_logo', 'report_header_right_logo',
                    'report_header_image', 'report_footer_image'
                ], true) && $extension === 'png';

                $targetDir = public_path('uploads/branding');
                if (!is_dir($targetDir)) {
                    @mkdir($targetDir, 0755, true);
                }

                if ($key === 'favicon' || in_array($extension, ['ico', 'svg'], true) || $isPngLogo) {
                    $filename   = Str::random(20) . '.' . $extension;
                    $storedPath = 'uploads/branding/' . $filename;
                    $file->move($targetDir, $filename);
                } else {
                    // Logos need generous headroom; backgrounds can be larger;
                    // social share images are stored near Open Graph resolution.
                    $maxDim = match ($key) {
                        'login_background' => 1920,
                        'og_image'         => 1600,
                        default            => 800,
                    };

                    $storedPath = app(\App\Services\ImageService::class)->store($file, 'uploads/branding', [
                        'maxWidth'  => $maxDim,
                        'maxHeight' => $maxDim,
                        'quality'   => 85,
                        'format'    => 'auto',
                    ]);
                }

                $setting->update(['value' => $storedPath]);

                // When uploading custom logo variants, also keep system_logo in sync if it's default
                if (in_array($key, ['logo_light', 'logo_white'], true)) {
                    $currentSystemLogo = SystemSetting::getValue('system_logo', 'assets/logo-dark.png');
                    if (BrandingService::isBundledLogoPath($currentSystemLogo) || empty($currentSystemLogo)) {
                        SystemSetting::where('key', 'system_logo')->update(['value' => $storedPath]);
                    }
                }

                continue;
            }

            if ($setting->type === 'boolean') {
                // Category forms submit only their own settings. An absent checkbox
                // means the setting belongs to another category, not that it is off.
                if (! $request->has("settings.{$key}")) {
                    continue;
                }

                $setting->update(['value' => $request->boolean("settings.{$key}") ? '1' : '0']);

                continue;
            }

            if ($request->has("settings.{$key}")) {
                $setting->update(['value' => $request->input("settings.{$key}")]);
            }
        }

        // Handle explicit image removal flags (e.g. removing left seal or banner)
        $removalKeys = [
            'remove_report_header_left_logo'  => 'report_header_left_logo',
            'remove_report_header_right_logo' => 'report_header_right_logo',
            'remove_report_header_image'      => 'report_header_image',
            'remove_report_footer_image'      => 'report_footer_image',
        ];
        foreach ($removalKeys as $reqFlag => $settingKey) {
            if ($request->has($reqFlag) && $request->input($reqFlag) == '1') {
                SystemSetting::where('key', $settingKey)->update(['value' => '']);
            }
        }

        // Catch any newly-submitted settings that were not yet in the DB collection
        if ($request->has('settings') && is_array($request->input('settings'))) {
            foreach ($request->input('settings') as $subKey => $subVal) {
                if (!is_array($subVal)) {
                    SystemSetting::updateOrCreate(
                        ['key' => $subKey],
                        ['value' => (string)$subVal, 'group' => 'reports']
                    );
                }
            }
        }

        BrandingService::flush();
        SystemSetting::flushCache();

        // If the mail sender name changed, apply it to the running config so
        // outgoing mail immediately uses the white-label identity.
        if ($request->has('settings.mail_from_name')) {
            $sender = trim((string) $request->input('settings.mail_from_name'));
            if ($sender !== '' && $sender !== (string) config('mail.from.name')) {
                config(['mail.from.name' => $sender]);
            }
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Report Letterhead & Footer settings saved successfully.',
            ]);
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }

    /**
     * Seed optional rows that older databases may not have. Never overwrites.
     */
    protected function seedOptionalRows(): void
    {
        $primary = SystemSetting::getValue('system_logo', 'assets/logo-dark.png') ?? 'assets/logo-dark.png';

        $rows = [
            ['key' => 'logo_light', 'value' => $primary, 'type' => 'image', 'group' => 'branding', 'label' => 'Light Logo (light backgrounds)'],
            ['key' => 'logo_white', 'value' => $primary, 'type' => 'image', 'group' => 'branding', 'label' => 'White Logo (dark backgrounds)'],
            ['key' => 'use_wordmark_only', 'value' => '0', 'type' => 'boolean', 'group' => 'branding', 'label' => 'Wordmark-Only Mode (hide system name text)'],
            ['key' => 'logo_auto_variant', 'value' => '0', 'type' => 'boolean', 'group' => 'branding', 'label' => 'Automatic Logo Variant (detect background brightness)'],
            ['key' => 'hero_gradient_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'appearance', 'label' => 'Hero Gradient Overlay'],

            // Report Header & Footer Settings
            ['key' => 'report_header_mode', 'value' => 'builder', 'type' => 'text', 'group' => 'reports', 'label' => 'Report Header Mode'],
            ['key' => 'report_header_image', 'value' => '', 'type' => 'image', 'group' => 'reports', 'label' => 'Header Banner Image'],
            ['key' => 'report_header_image_height', 'value' => '90', 'type' => 'text', 'group' => 'reports', 'label' => 'Header Banner Height (px)'],
            ['key' => 'report_header_image_fit', 'value' => 'contain', 'type' => 'text', 'group' => 'reports', 'label' => 'Header Banner Fit Mode'],
            ['key' => 'report_header_edge', 'value' => 'flush', 'type' => 'text', 'group' => 'reports', 'label' => 'Header Banner Edge Placement'],
            ['key' => 'report_header_offset_y', 'value' => '0', 'type' => 'text', 'group' => 'reports', 'label' => 'Header Banner Top Offset (px)'],
            ['key' => 'report_header_image_pos_x', 'value' => '0', 'type' => 'text', 'group' => 'reports', 'label' => 'Header Banner X Position'],
            ['key' => 'report_header_image_pos_y', 'value' => '0', 'type' => 'text', 'group' => 'reports', 'label' => 'Header Banner Y Position'],
            ['key' => 'report_header_image_scale', 'value' => '1.0', 'type' => 'text', 'group' => 'reports', 'label' => 'Header Banner Scale'],
            ['key' => 'report_header_left_logo', 'value' => '', 'type' => 'image', 'group' => 'reports', 'label' => 'Left Seal / Logo'],
            ['key' => 'report_header_left_logo_show', 'value' => '1', 'type' => 'boolean', 'group' => 'reports', 'label' => 'Show Left Seal'],
            ['key' => 'report_header_left_logo_size', 'value' => '60', 'type' => 'text', 'group' => 'reports', 'label' => 'Left Seal Size'],
            ['key' => 'report_header_left_logo_shape', 'value' => 'circle', 'type' => 'text', 'group' => 'reports', 'label' => 'Left Seal Shape'],
            ['key' => 'report_header_right_logo', 'value' => '', 'type' => 'image', 'group' => 'reports', 'label' => 'Right Seal / Logo'],
            ['key' => 'report_header_right_logo_show', 'value' => '1', 'type' => 'boolean', 'group' => 'reports', 'label' => 'Show Right Seal'],
            ['key' => 'report_header_right_logo_size', 'value' => '60', 'type' => 'text', 'group' => 'reports', 'label' => 'Right Seal Size'],
            ['key' => 'report_header_right_logo_shape', 'value' => 'circle', 'type' => 'text', 'group' => 'reports', 'label' => 'Right Seal Shape'],

            ['key' => 'report_header_line1', 'value' => 'Republic of the Philippines', 'type' => 'text', 'group' => 'reports', 'label' => 'Line 1 (Country)'],
            ['key' => 'report_header_line1_font_size', 'value' => '9.5px', 'type' => 'text', 'group' => 'reports', 'label' => 'Line 1 Font Size'],
            ['key' => 'report_header_line1_font_weight', 'value' => '700', 'type' => 'text', 'group' => 'reports', 'label' => 'Line 1 Font Weight'],
            ['key' => 'report_header_line1_color', 'value' => '#334155', 'type' => 'text', 'group' => 'reports', 'label' => 'Line 1 Color'],
            ['key' => 'report_header_line1_transform', 'value' => 'uppercase', 'type' => 'text', 'group' => 'reports', 'label' => 'Line 1 Transform'],

            ['key' => 'report_header_line2', 'value' => 'STATE UNIVERSITY SYSTEM', 'type' => 'text', 'group' => 'reports', 'label' => 'Line 2 (Institution)'],
            ['key' => 'report_header_line2_font_size', 'value' => '16.5px', 'type' => 'text', 'group' => 'reports', 'label' => 'Line 2 Font Size'],
            ['key' => 'report_header_line2_font_weight', 'value' => '900', 'type' => 'text', 'group' => 'reports', 'label' => 'Line 2 Font Weight'],
            ['key' => 'report_header_line2_color', 'value' => '#0f2b48', 'type' => 'text', 'group' => 'reports', 'label' => 'Line 2 Color'],
            ['key' => 'report_header_line2_transform', 'value' => 'none', 'type' => 'text', 'group' => 'reports', 'label' => 'Line 2 Transform'],

            ['key' => 'report_header_line3', 'value' => 'OFFICE OF PHYSICAL PLANT & AUXILIARY SERVICES', 'type' => 'text', 'group' => 'reports', 'label' => 'Line 3 (Office/Dept)'],
            ['key' => 'report_header_line3_font_size', 'value' => '10.5px', 'type' => 'text', 'group' => 'reports', 'label' => 'Line 3 Font Size'],
            ['key' => 'report_header_line3_font_weight', 'value' => '600', 'type' => 'text', 'group' => 'reports', 'label' => 'Line 3 Font Weight'],
            ['key' => 'report_header_line3_color', 'value' => '#475569', 'type' => 'text', 'group' => 'reports', 'label' => 'Line 3 Color'],
            ['key' => 'report_header_line3_transform', 'value' => 'none', 'type' => 'text', 'group' => 'reports', 'label' => 'Line 3 Transform'],

            ['key' => 'report_header_line4', 'value' => 'University Boulevard, Main Campus, Metro Manila, Philippines', 'type' => 'text', 'group' => 'reports', 'label' => 'Line 4 (Address)'],
            ['key' => 'report_header_line4_font_size', 'value' => '8.5px', 'type' => 'text', 'group' => 'reports', 'label' => 'Line 4 Font Size'],
            ['key' => 'report_header_line4_font_weight', 'value' => '400', 'type' => 'text', 'group' => 'reports', 'label' => 'Line 4 Font Weight'],
            ['key' => 'report_header_line4_color', 'value' => '#475569', 'type' => 'text', 'group' => 'reports', 'label' => 'Line 4 Color'],

            ['key' => 'report_header_line5', 'value' => 'Tel: (02) 8911-2000 • Web: sure.university.edu.ph', 'type' => 'text', 'group' => 'reports', 'label' => 'Line 5 (Contact/Web)'],
            ['key' => 'report_header_line5_font_size', 'value' => '8.5px', 'type' => 'text', 'group' => 'reports', 'label' => 'Line 5 Font Size'],
            ['key' => 'report_header_line5_font_weight', 'value' => '400', 'type' => 'text', 'group' => 'reports', 'label' => 'Line 5 Font Weight'],
            ['key' => 'report_header_line5_color', 'value' => '#475569', 'type' => 'text', 'group' => 'reports', 'label' => 'Line 5 Color'],

            ['key' => 'report_header_divider_style', 'value' => 'double', 'type' => 'text', 'group' => 'reports', 'label' => 'Divider Style'],
            ['key' => 'report_header_divider_color', 'value' => '#0f2b48', 'type' => 'text', 'group' => 'reports', 'label' => 'Divider Color'],

            ['key' => 'report_footer_mode', 'value' => 'builder', 'type' => 'text', 'group' => 'reports', 'label' => 'Footer Mode'],
            ['key' => 'report_footer_image', 'value' => '', 'type' => 'image', 'group' => 'reports', 'label' => 'Footer Banner Image'],
            ['key' => 'report_footer_image_height', 'value' => '50', 'type' => 'text', 'group' => 'reports', 'label' => 'Footer Banner Height (px)'],
            ['key' => 'report_footer_image_fit', 'value' => 'contain', 'type' => 'text', 'group' => 'reports', 'label' => 'Footer Banner Fit Mode'],
            ['key' => 'report_footer_edge', 'value' => 'flush', 'type' => 'text', 'group' => 'reports', 'label' => 'Footer Banner Edge Placement'],
            ['key' => 'report_footer_offset_y', 'value' => '0', 'type' => 'text', 'group' => 'reports', 'label' => 'Footer Banner Bottom Offset (px)'],
            ['key' => 'report_footer_image_pos_x', 'value' => '0', 'type' => 'text', 'group' => 'reports', 'label' => 'Footer Banner X Position'],
            ['key' => 'report_footer_image_pos_y', 'value' => '0', 'type' => 'text', 'group' => 'reports', 'label' => 'Footer Banner Y Position'],
            ['key' => 'report_footer_image_scale', 'value' => '1.0', 'type' => 'text', 'group' => 'reports', 'label' => 'Footer Banner Scale'],
            ['key' => 'report_footer_divider', 'value' => '1', 'type' => 'boolean', 'group' => 'reports', 'label' => 'Show Footer Divider'],
            ['key' => 'report_footer_left_title', 'value' => 'State University Reservation Engine', 'type' => 'text', 'group' => 'reports', 'label' => 'Footer Title'],
            ['key' => 'report_footer_left_line1', 'value' => 'University Boulevard, Main Campus, Metro Manila', 'type' => 'text', 'group' => 'reports', 'label' => 'Footer Address'],
            ['key' => 'report_footer_left_line2', 'value' => 'Contact: (02) 8911-2000 | Email: facilities-office@university.edu.ph', 'type' => 'text', 'group' => 'reports', 'label' => 'Footer Contact'],
            ['key' => 'report_footer_center_text', 'value' => 'ISO 9001:2015 Certified Institution', 'type' => 'text', 'group' => 'reports', 'label' => 'Footer Center Text'],
            ['key' => 'report_footer_show_timestamp', 'value' => '1', 'type' => 'boolean', 'group' => 'reports', 'label' => 'Show Timestamp in Footer'],
            ['key' => 'report_footer_show_pagenum', 'value' => '1', 'type' => 'boolean', 'group' => 'reports', 'label' => 'Show Page Number in Footer'],
            ['key' => 'report_footer_system_notice', 'value' => 'Generated by: SURE Facility Management System (Official Report)', 'type' => 'text', 'group' => 'reports', 'label' => 'Footer Notice'],
            ['key' => 'reserve_hero_title', 'value' => 'Reserve a Venue', 'type' => 'text', 'group' => 'login', 'label' => 'Reserve Page Hero Heading'],
            ['key' => 'reserve_hero_subheading', 'value' => 'Find the perfect campus space for your event — availability checked in real time.', 'type' => 'textarea', 'group' => 'login', 'label' => 'Reserve Page Hero Subtitle'],
        ];

        foreach ($rows as $row) {
            if (SystemSetting::where('key', $row['key'])->exists()) {
                continue;
            }
            try {
                DB::table('system_settings')->insert(array_merge($row, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
                SystemSetting::flushCache();
            } catch (\Exception $e) {
                // Best-effort: ignore duplicate-key races from concurrent requests.
            }
        }
    }
}
