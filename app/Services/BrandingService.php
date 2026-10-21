<?php

namespace App\Services;

use App\Models\SystemSetting;

/**
 * A globally accessible helper for system branding values.
 * Usage in Blade: {{ branding('system_name') }} or {{ branding('brand_primary') }}
 */
class BrandingService
{
    protected static array $cache = [];

    /**
     * Get a branding/system setting value.
     */
    public static function get(string $key, ?string $default = null): ?string
    {
        if (!isset(static::$cache[$key])) {
            static::$cache[$key] = SystemSetting::getValue($key, $default);
        }
        return static::$cache[$key] ?? $default;
    }

    /**
     * Helper to resolve asset path to full URL.
     */
    protected static function resolveAssetUrl(string $key, string $default): string
    {
        return static::resolveAssetPath(static::get($key, $default), $default);
    }

    /**
     * Resolve an already-selected asset path to a usable public URL.
     */
    protected static function resolveAssetPath(?string $path, string $default): string
    {
        $path = trim((string) $path);
        if ($path === '') {
            $path = $default;
        }

        $cleanPath = ltrim($path, '/');
        if (filter_var($cleanPath, FILTER_VALIDATE_URL)) {
            return $cleanPath;
        }

        // If the path does not exist on disk or is empty (0 bytes), fall back to the default asset.
        if (!app()->runningUnitTests()) {
            $publicPath = public_path($cleanPath);
            $exists = file_exists($publicPath) && @filesize($publicPath) > 0;

            if (!$exists) {
                // Check if stored under storage/app/public/
                $storageRelative = preg_replace('#^storage/#', '', $cleanPath);
                $storagePath = storage_path('app/public/' . $storageRelative);
                if (file_exists($storagePath) && @filesize($storagePath) > 0) {
                    $exists = true;
                    // Mirror into public/uploads so direct asset() URLs resolve
                    if (str_starts_with($cleanPath, 'uploads/')) {
                        @mkdir(dirname($publicPath), 0755, true);
                        @copy($storagePath, $publicPath);
                    }
                }
            }

            if (!$exists) {
                $cleanPath = ltrim($default, '/');
            }
        }

        return asset($cleanPath);
    }

    /**
     * Bundled logo paths are placeholders, not administrator-selected variants.
     * Treating them as unset lets a later CMS upload to `system_logo` flow through
     * every surface until a dedicated dark/light variant is uploaded.
     */
    public static function isBundledLogoPath(?string $path): bool
    {
        return in_array(ltrim(trim((string) $path), '/'), [
            'assets/logo-dark.png',
            'assets/logo-light.png',
        ], true);
    }

    /**
     * Determine whether an asset exists on disk and is non-empty.
     */
    public static function assetExistsOnDisk(?string $path): bool
    {
        if (app()->runningUnitTests()) {
            return true;
        }

        $path = trim((string) $path);
        if ($path === '') {
            return false;
        }

        $cleanPath = ltrim($path, '/');
        if (filter_var($cleanPath, FILTER_VALIDATE_URL)) {
            return true;
        }

        $publicPath = public_path($cleanPath);
        if (file_exists($publicPath) && @filesize($publicPath) > 0) {
            return true;
        }

        $storageRelative = preg_replace('#^storage/#', '', $cleanPath);
        $storagePath = storage_path('app/public/' . $storageRelative);
        if (file_exists($storagePath) && @filesize($storagePath) > 0) {
            return true;
        }

        return false;
    }

    /**
     * Get the full asset URL for an image setting.
     * Checks CMS uploaded variants first so custom branding applies everywhere.
     */
    public static function logo(): string
    {
        foreach (['system_logo', 'logo_light', 'logo_white'] as $key) {
            $path = static::get($key, null);
            if ($path !== null && trim($path) !== '' && !static::isBundledLogoPath($path)) {
                if (static::assetExistsOnDisk($path)) {
                    return static::resolveAssetPath($path, 'assets/logo-dark.png');
                }
            }
        }

        return static::resolveAssetUrl('system_logo', 'assets/logo-dark.png');
    }

    /**
     * Get a logo variant URL.
     *
     * @param  string  $variant  'dark' (primary), 'light', 'white', or any custom key (logo_{variant})
     * @return string
     */
    public static function logoVariant(string $variant = 'light'): string
    {
        // Light surfaces require high contrast against light backgrounds (dark logo fallback).
        // Dark surfaces (like the admin sidebar or hero navbar) require a white/light logo.
        $fallback = match ($variant) {
            'white' => 'assets/logo-light.png',
            'light', 'dark' => 'assets/logo-dark.png',
            default => 'assets/logo-dark.png',
        };

        // Resolve CMS variants in priority order with reciprocal fallbacks so uploading
        // any custom logo immediately propagates across surfaces.
        $keys = match ($variant) {
            'white' => ['logo_white', 'logo_light', 'system_logo', 'logo_dark'],
            'light' => ['logo_light', 'logo_white', 'system_logo', 'logo_dark'],
            'dark'  => ['logo_dark', 'logo_light', 'logo_white', 'system_logo'],
            default => ['logo_' . $variant, 'logo_light', 'logo_white', 'system_logo'],
        };

        foreach ($keys as $key) {
            $path = static::get($key, null);
            if ($path === null || trim($path) === '' || static::isBundledLogoPath($path)) {
                continue;
            }

            if (!static::assetExistsOnDisk($path)) {
                continue;
            }

            return static::resolveAssetPath($path, $fallback);
        }

        return static::resolveAssetPath(null, $fallback);
    }

    /**
     * Choose the best logo for a given background luminance.
     *
     * @param  string  $mode  'dark' | 'light'
     * @return string
     */
    public static function logoForBg(string $mode): string
    {
        // Light surface: use the CMS variant for light backgrounds (logo_light).
        if ($mode === 'light') {
            return static::logoVariant('light');
        }

        // Dark surface: use the CMS white/mono variant for dark backgrounds (logo_white).
        return static::logoVariant('white');
    }

    /**
     * Wordmark-only mode: the logo IS the identity, so the text name is
     * hidden everywhere (navs, footers, sidebars).
     */
    public static function wordmarkOnly(): bool
    {
        return static::get('use_wordmark_only', '0') === '1';
    }

    /**
     * Automatic variant detection: a tiny client-side script measures the
     * background brightness behind each brand logo and swaps to the correct
     * variant (white/light for dark backgrounds, dark for light backgrounds).
     */
    public static function logoAutoVariant(): bool
    {
        return static::get('logo_auto_variant', '0') === '1';
    }

    /**
     * Inline script for automatic logo-variant detection. Returns '' when off.
     */
    public static function logoAutoVariantScript(): string
    {
        return static::logoAutoSwitchScript();
    }

    /**
     * Unconditional scrolled-navbar logo swap.
     *
     * The homepage navbar turns white when scrolled, so the logo must switch
     * from the white variant to the dark variant on the fly. This script is
     * emitted on every homepage render regardless of the auto-variant toggle
     * (the filename-based CSS rules it replaced could not match CMS upload
     * filenames). It coexists with the auto-variant script: when that script
     * is also active it supersedes this one for .brand-logo-auto images,
     * because it re-runs on scroll as well and writes the final src.
     */
    public static function logoNavBarScript(): string
    {
        $light = json_encode(static::logoForBg('light'));    // for the white scrolled bar
        $darkBg = json_encode(static::logoForBg('dark'));    // for the transparent dark hero

        $html = <<<'HTML'
<script>
(function () {
    var nav = document.getElementById('hnNav');
    if (!nav) { return; }
    var imgs = nav.querySelectorAll('img.hn-logo:not(.brand-logo-auto)');
    if (!imgs.length) { return; }
    var forScrolled = __LS__; // dark variant for the white bar
    var forDefault  = __DB__; // white/light variant for the dark hero
    function sync() {
        var scrolled = nav.classList.contains('scrolled');
        imgs.forEach(function (img) {
            img.src = scrolled ? forScrolled : forDefault;
        });
    }
    var t = null;
    window.addEventListener('scroll', function () {
        if (t) { clearTimeout(t); }
        t = setTimeout(sync, 80);
    }, { passive: true });
    window.addEventListener('branding-changed', sync);
    if (document.readyState === 'complete') { sync(); }
    else { window.addEventListener('load', sync); sync(); }
})();
</script>
HTML;

        return str_replace(
            ['__LS__', '__DB__'],
            [$light, $darkBg],
            $html
        );
    }

    public static function logoAutoSwitchScript(): string
    {
        if (!static::logoAutoVariant()) {
            return '';
        }

        $white  = json_encode(static::logoVariant('white'));
        $light  = json_encode(static::logoVariant('light'));
        $dark   = json_encode(static::logoVariant('dark'));
        $system = json_encode(static::logo());

        $html = <<<'HTML'
<script>
(function () {
    function pickForBg(lum) {
        return lum < 110
            ? (window.__brandWhite || window.__brandSystem)
            : (window.__brandLight || window.__brandDark || window.__brandSystem);
    }
    function luminanceOf(el) {
        var node = el;
        while (node && node !== document.body) {
            var bg = getComputedStyle(node).backgroundColor;
            var m = bg && bg.match(/rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*([\d.]+))?\)/);
            if (m) {
                // Fully transparent layers are ignored — keep walking up.
                if (m[4] !== undefined && parseFloat(m[4]) > 0.5) {
                    return 0.299 * +m[1] + 0.587 * +m[2] + 0.114 * +m[3];
                }
                if (m[4] === undefined) {
                    return 0.299 * +m[1] + 0.587 * +m[2] + 0.114 * +m[3];
                }
            }
            node = node.parentElement;
        }
        return 255; // transparent all the way up = light page
    }
    function run() {
        document.querySelectorAll('img.brand-logo-auto').forEach(function (img) {
            var nav = img.closest('.hn-nav');
            if (nav) {
                // The Homepage navbar is an intentional overlay. Its own
                // scrolled state is the stable contrast surface; sampling the
                // hero video/poster behind it is unreliable and can flip the
                // CMS logo variant while media frames change.
                var lightOnDark = img.getAttribute('data-logo-light') || window.__brandWhite || window.__brandSystem;
                var darkOnLight = img.getAttribute('data-logo-dark') || window.__brandLight || window.__brandDark || window.__brandSystem;
                img.src = nav.classList.contains('scrolled') ? darkOnLight : lightOnDark;
                return;
            }
            var guestNav = img.closest('.navbar-sure');
            if (guestNav) {
                // Guest Panel navigation is a persistent dark gradient. Its
                // explicit data attributes keep the variant independent of
                // CSS gradient parsing and ancestor luminance heuristics.
                img.src = img.getAttribute('data-logo-light') || window.__brandWhite || window.__brandSystem;
                return;
            }
            var panelSidebar = img.closest('.sidebar');
            if (panelSidebar) {
                // Staff, Admin, SuperAdmin, and Cashier use the CMS dark
                // sidebar surface. Keep the resolver explicit so these panel
                // logos do not depend on transparent wrapper elements or on
                // whether the sidebar theme is expressed through a CSS var.
                var panelLight = img.getAttribute('data-logo-light') || window.__brandWhite || window.__brandSystem;
                var panelDark = img.getAttribute('data-logo-dark') || window.__brandLight || window.__brandDark || window.__brandSystem;
                var panelLuminance = luminanceOf(panelSidebar);
                img.src = panelLuminance < 150 ? panelLight : panelDark;
                return;
            }
            img.src = pickForBg(luminanceOf(img));
        });
    }
    window.__brandWhite  = __BW__;
    window.__brandLight  = __BL__;
    window.__brandDark   = __BD__;
    window.__brandSystem = __BS__;
    if (document.readyState === 'complete') { run(); }
    else { window.addEventListener('load', run); run(); }
    // React when the CMS toggles the mode without a full reload.
    window.addEventListener('branding-changed', run);
    // Re-measure when navbars switch to a scrolled (white) background.
    var scrollTimer = null;
    window.addEventListener('scroll', function () {
        if (scrollTimer) { clearTimeout(scrollTimer); }
        scrollTimer = setTimeout(run, 80);
    }, { passive: true });
})();
</script>
HTML;

        return str_replace(
            ['__BW__', '__BL__', '__BD__', '__BS__'],
            [$white, $light, $dark, $system],
            $html
        );
    }

    /**
     * Emit both scripts: the unconditional navbar scroll-swap plus the
     * optional auto-variant script.
     */
    public static function logoScripts(): string
    {
        return static::logoNavBarScript() . static::logoAutoVariantScript();
    }

    /**
     * Suggest a system name derived from an uploaded logo filename.
     *
     * Strips the directory and extension, splits on hyphens/underscores,
     * drops pure noise tokens like "logo", and title-cases the rest.
     *
     * Examples: "um-tacloban-logo_final.webp" → "Um Tacloban"
     *           "University Of The Visayas.png" → "University Of The Visayas"
     *           "logo-dark.png" → "Dark"
     */
    public static function deriveNameFromLogo(string $filenameOrPath): string
    {
        $name = basename($filenameOrPath);
        $name = pathinfo($name, PATHINFO_FILENAME);

        // Split on hyphens and underscores
        $parts = preg_split('/[_\-]+/', $name);
        $noise = ['logo', 'logos', 'icon', 'icono', 'brand', 'mark'];

        $meaningful = array_values(array_filter($parts, function ($p) use ($noise) {
            return $p !== '' && !in_array(strtolower($p), $noise, true);
        }));

        // Fall back to all parts with noise removed only if meaningful remains
        $tokens = $meaningful ?: $parts;

        $suggested = trim(ucwords(strtolower(implode(' ', $tokens))));

        // If nothing sensible could be derived, return an empty string (caller decides)
        return $suggested !== '' ? $suggested : '';
    }

    /**
     * Get the asset URL for the login page background image.
     */
    public static function loginBackground(): string
    {
        return static::resolveAssetUrl('login_background', 'assets/auth-bg.jpg');
    }

    /**
     * Get the asset URL for the browser tab favicon.
     */
    public static function favicon(): string
    {
        return static::resolveAssetUrl('favicon', 'favicon.ico');
    }

    /**
     * Get CSS custom properties string for injecting brand colors into layouts.
     */
    public static function cssVariables(): string
    {
        $primary   = static::get('brand_primary', '#2563eb');
        $secondary = static::get('brand_secondary', '#7c3aed');
        $accent    = static::get('brand_accent', '#06b6d4');
        $dark      = static::get('brand_dark', '#0f172a');

        return "
            --brand-primary: {$primary};
            --brand-secondary: {$secondary};
            --brand-accent: {$accent};
            --brand-dark: {$dark};
            --brand-primary-rgb: " . static::hexToRgb($primary) . ";
            --brand-secondary-rgb: " . static::hexToRgb($secondary) . ";
            --brand-accent-rgb: " . static::hexToRgb($accent) . ";
            --brand-dark-rgb: " . static::hexToRgb($dark) . ";
        ";
    }

    /**
     * Convert hex color to comma-separated RGB for use with rgba().
     */
    protected static function hexToRgb(string $hex): string
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        return "{$r},{$g},{$b}";
    }

        /**
     * Convert a hex color to its HSL components.
     *
     * @return array{0:int,1:float,2:float} [h, s, l]
     */
    protected static function hexToHsl(string $hex): array
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }
        $r = hexdec(substr($hex, 0, 2)) / 255;
        $g = hexdec(substr($hex, 2, 2)) / 255;
        $b = hexdec(substr($hex, 4, 2)) / 255;
        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $l = ($max + $min) / 2;
        if ($max === $min) {
            return [0, 0.0, $l];
        }
        $d = $max - $min;
        $s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);
        switch ($max) {
            case $r: $h = ($g - $b) / $d + ($g < $b ? 6 : 0); break;
            case $g: $h = ($b - $r) / $d + 2; break;
            default: $h = ($r - $g) / $d + 4; break;
        }
        $h *= 60;
        return [(int) round($h), (float) round($s, 3), (float) round($l, 3)];
    }

    /**
     * Convert HSL components back to a hex color.
     */
    protected static function hslToHex(int $h, float $s, float $l): string
    {
        if ($s === 0.0) {
            $v = (int) round($l * 255);
            return sprintf('#%02x%02x%02x', $v, $v, $v);
        }
        $q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
        $p = 2 * $l - $q;
        $hue = function (float $t) use ($p, $q, $h): float {
            if ($t < 0) $t += 1;
            if ($t > 1) $t -= 1;
            if ($t < 1/6) return $p + ($q - $p) * 6 * $t;
            if ($t < 1/2) return $q;
            if ($t < 2/3) return $p + ($q - $p) * (2/3 - $t) * 6;
            return $p;
        };
        return sprintf(
            '#%02x%02x%02x',
            (int) round($hue($h / 360 + 1/3) * 255),
            (int) round($hue($h / 360) * 255),
            (int) round($hue($h / 360 - 1/3) * 255)
        );
    }

    /**
     * Lighten a hex color toward white by a lightness amount (0..0.95).
     */
    public static function lightenHex(string $hex, float $amount = 0.94): string
    {
        [$h, $s, $l] = static::hexToHsl($hex);
        return static::hslToHex($h, $s, min(0.995, $l + (1 - $l) * $amount));
    }

    /**
     * Darken a hex color toward black by a lightness amount (0..0.95).
     */
    public static function darkenHex(string $hex, float $amount = 0.35): string
    {
        [$h, $s, $l] = static::hexToHsl($hex);
        return static::hslToHex($h, $s, max(0.0, $l * (1 - $amount)));
    }

    /**
     * Very light tint of the brand primary — used for email headers, banners,
     * and info cards so every email reads as one continuous brand surface.
     */
    public static function emailBrandTint(): string
    {
        return static::lightenHex(static::get('brand_primary', '#2563eb'), 0.945);
    }

    /**
     * Get the logo URL for email headers.
     *
     * Resolves the CMS-uploaded system_logo (or variant/fallback) to a full URL
     * for email clients.
     */
    public static function emailLogoUrl(string $variant = 'dark'): string
    {
        $fallback = match ($variant) {
            'dark'  => 'assets/logo-dark.png',
            'light' => 'assets/logo-light.png',
            'white' => 'assets/logo-light.png',
            default => 'assets/logo-dark.png',
        };

        // Prefer the CMS-uploaded system_logo first
        $path = static::get('system_logo', null);
        if (empty($path) || static::isBundledLogoPath($path)) {
            $path = static::get('logo_' . $variant, null);
        }
        if (empty($path)) {
            $path = $fallback;
        }

        return static::resolveAssetPath($path, $fallback);
    }

    /**
     * Get the local file path to the logo for CID embedding.
     */
    public static function emailLogoPath(string $variant = 'dark'): string
    {
        $fallback = match ($variant) {
            'dark'  => 'assets/logo-dark.png',
            'light' => 'assets/logo-light.png',
            'white' => 'assets/logo-light.png',
            default => 'assets/logo-dark.png',
        };

        $path = static::get('system_logo', null);
        if (empty($path)) {
            $path = static::get('logo_' . $variant, $fallback);
        }
        if (empty($path)) {
            $path = $fallback;
        }
        $cleanPath = ltrim($path, '/');

        $fullPath = public_path($cleanPath);
        if (file_exists($fullPath) && @filesize($fullPath) > 0) {
            return $fullPath;
        }

        $defaultFull = public_path(ltrim($fallback, '/'));
        if (file_exists($defaultFull) && @filesize($defaultFull) > 0) {
            return $defaultFull;
        }

        return '';
    }

    /**
     * Build a branded email header block (logo + system name + tagline).
     *
     * Uses the brand-primary tint as a unified header surface and the dark
     * logo variant so the identity always renders crisply on the light tint.
     * The logo uses a cid: reference that is resolved to an actual embedded
     * image attachment by the Mailable sender (via BrandedEmail trait).
     */
    public static function emailHeaderHtml(): string
    {
        $name    = static::get('system_name', 'Platform') ?: 'Platform';
        $tagline = trim(static::get('system_tagline', '') ?? '');
        $logo    = static::emailLogoUrl('dark');
        $brandPrimary = static::get('brand_primary', '#2563eb');
        $tint    = static::emailBrandTint();
        $accent  = static::darkenHex($brandPrimary, 0.15);

        $brandHtml = $logo
            ? '<img src="' . e($logo) . '" alt="' . e($name) . '" style="height: 52px; width: auto; object-fit: contain;">'
            : '';

        $identity = $brandHtml
            . '<p style="margin: 12px 0 0 0; font-size: 19px; font-weight: 800; letter-spacing: 0.5px; color: ' . e($accent) . ';">' . e($name) . '</p>'
            . ($tagline
                ? '<p style="margin: 3px 0 0 0; font-size: 11.5px; letter-spacing: 1.5px; text-transform: uppercase; color: ' . e(static::darkenHex($brandPrimary, 0.45)) . ';">' . e($tagline) . '</p>'
                : '');

        return '<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">'
            . '<tr><td style="height: 5px; background-color: ' . e($brandPrimary) . '; border-radius: 16px 16px 0 0;"></td></tr>'
            . '<tr><td style="background-color: ' . e($tint) . '; padding: 26px 40px 26px 40px; text-align: center;">'
            . $identity
            . '</td></tr></table>';
    }

    /**
     * Flush in-memory cache (for after CMS saves).
     */
    public static function flush(): void
    {
        static::$cache = [];
        SystemSetting::flushCache();
    }
}
