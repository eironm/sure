<?php

use App\Services\BrandingService;
use App\Services\LegalService;

// Wasmer's PHP build does not include mbstring, and Symfony's PHP 8 polyfill
// intentionally omits the deprecated mb_split() function. Laravel still uses
// mb_split() when compiling Blade component names, so provide the compatible
// Unicode-aware fallback when the native function is unavailable.
if (!function_exists('mb_split')) {
    function mb_split(string $pattern, string $string, int $limit = -1): array|false
    {
        return preg_split('~'.$pattern.'~u', $string, $limit);
    }
}

if (!function_exists('legal_doc')) {
    /**
     * Get the rendered (Markdown -> HTML) language entries for a legal document.
     *
     * @return array<int, array{code:string,label:string,html:string}>
     */
    function legal_doc(string $doc): array
    {
        return LegalService::rendered($doc);
    }
}

if (!function_exists('branding')) {
    /**
     * Get a system branding/setting value.
     *
     * Usage: branding('system_name'), branding('brand_primary', '#2563eb')
     */
    function branding(string $key, ?string $default = null): ?string
    {
        return BrandingService::get($key, $default);
    }
}

if (!function_exists('brand_logo')) {
    /**
     * Get the system logo URL.
     */
    function brand_logo(): string
    {
        return BrandingService::logo();
    }
}

if (!function_exists('brand_css_vars')) {
    /**
     * Get CSS custom properties string for brand colors.
     */
    function brand_css_vars(): string
    {
        return BrandingService::cssVariables();
    }
}

if (!function_exists('brand_login_bg')) {
    /**
     * Get the login page background image URL.
     */
    function brand_login_bg(): string
    {
        return BrandingService::loginBackground();
    }
}

if (!function_exists('brand_favicon')) {
    /**
     * Get the favicon URL.
     */
    function brand_favicon(): string
    {
        return BrandingService::favicon();
    }
}

if (!function_exists('brand_favicon_mimetype')) {
    /**
     * Get the MIME type for the current favicon (png/svg/ico/x-icon).
     * Returns the browser-safe default when the stored favicon has no
     * recognisable extension.
     */
    function brand_favicon_mimetype(): string
    {
        $path = BrandingService::favicon();
        $ext = strtolower(pathinfo(parse_url($path, PHP_URL_PATH), PATHINFO_EXTENSION));

        return match ($ext) {
            'png' => 'image/png',
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon',
            default => 'image/x-icon',
        };
    }
}

if (!function_exists('safe_mail')) {
    /**
     * Send a mailable without ever crashing the request if the mail transport
     * fails (e.g., a misconfigured / test-mode API key on the live host).
     *
     * Logs the failure to laravel.log and returns false so callers can flash
     * a friendly warning. Returns true when the mail was handed off OK.
     *
     * Usage:
     *   $ok = safe_mail($user->email, new WelcomeRegistration($user));
     *   if (!$ok) { $request->session()->flash('warning', 'We could not send the confirmation email…'); }
     */
    function safe_mail(string|array $recipients, \Illuminate\Mail\Mailable $mailable): bool
    {
        try {
            \Illuminate\Support\Facades\Mail::to($recipients)->send($mailable);
            return true;
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('safe_mail: delivery failed', [
                'recipients' => $recipients,
                'mailable'   => get_class($mailable),
                'error'      => $e->getMessage(),
            ]);
            return false;
        }
    }
}

if (!function_exists('safe_notify')) {
    /**
     * Fire a notification on a notifiable without ever crashing the request.
     *
     * Same contract as safe_mail() — returns false (and logs) when the
     * notification channel (usually mail) throws.
     */
    function safe_notify(object $notifiable, \Illuminate\Notifications\Notification $notification): bool
    {
        if (!method_exists($notifiable, 'notify')) {
            \Illuminate\Support\Facades\Log::error('safe_notify: target is not notifiable (missing Notifiable trait)', [
                'notifiable' => get_class($notifiable) . '#' . ($notifiable->id ?? '?'),
                'notification' => get_class($notification),
            ]);
            return false;
        }
        try {
            $notifiable->notify($notification);
            return true;
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('safe_notify: delivery failed', [
                'notifiable' => get_class($notifiable) . '#' . ($notifiable->id ?? '?'),
                'notification' => get_class($notification),
                'error'      => $e->getMessage(),
            ]);
            return false;
        }
    }
}



if (!function_exists('homepage_content_decode')) {
    /** Recursively decode HTML entities in legacy CMS-stored values. */
    function homepage_content_decode($v)
    {
        if (is_string($v)) {
            $d = htmlspecialchars_decode($v, ENT_QUOTES | ENT_HTML5);
            return ($d !== false && $d !== '') ? $d : $v;
        }
        if (is_array($v)) {
            $out = [];
            foreach ($v as $k => $vv) {
                $out[$k] = homepage_content_decode($vv);
            }
            return $out;
        }
        return $v;
    }
}

if (!function_exists('homepage_content')) {
    /**
     * Get homepage CMS content.
     *
     * Usage:
     *   homepage_content()               -> full array
     *   homepage_content('hero.heading') -> nested dot value
     *   homepage_content('hero.heading', 'Default Heading')
     *
     * Uses Laravel's cache (one hour) so the long-running built-in dev server
     * never serves stale CMS content from a request-scoped static.
     */
    function homepage_content(?string $path = null, $default = null)
    {
        $cache = \Illuminate\Support\Facades\Cache::remember('homepage_content', 3600, function () {
            try {
                $raw = \App\Models\SystemSetting::where('key', 'homepage_content')->first();
                if ($raw && is_string($raw->value)) {
                    $decoded = json_decode($raw->value, true);
                    if (is_array($decoded)) {
                        return $decoded;
                    }
                }
            } catch (\Exception $e) {
                // table may not exist yet
            }
            return [];
        });

        if ($path === null) {
            return $cache;
        }

        $value = data_get($cache, $path);
        if ($value !== null) {
            $value = homepage_content_decode($value);
        }
        return ($value !== null && $value !== '') ? $value : $default;
    }
}
