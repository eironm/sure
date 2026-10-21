<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SystemSetting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group', 'label'];

    /**
     * Get a system setting value by key, with caching.
     */
    public static function getValue(string $key, $default = null): ?string
    {
        return Cache::remember("system_setting.{$key}", 3600, function () use ($key, $default) {
            try {
                $setting = static::where('key', $key)->first();
                return $setting ? $setting->value : $default;
            } catch (\Exception $e) {
                // Return default if table doesn't exist yet (e.g. during migrations)
                return $default;
            }
        });
    }

    /**
     * Set a system setting value and bust cache.
     */
    public static function setValue(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("system_setting.{$key}");
        if (class_exists(\App\Services\BrandingService::class)) {
            \App\Services\BrandingService::flush();
        }
    }

    /**
     * Get all settings as key-value pairs, grouped.
     */
    public static function getAllGrouped(): array
    {
        return Cache::remember('system_settings.all_grouped', 3600, function () {
            return static::all()->groupBy('group')->map(function ($group) {
                return $group->pluck('value', 'key');
            })->toArray();
        });
    }

    /**
     * Flush all setting caches.
     */
    public static function flushCache(): void
    {
        $keys = static::pluck('key');
        foreach ($keys as $key) {
            Cache::forget("system_setting.{$key}");
        }

        // Also flush known core settings that may be accessed with defaults
        $coreKeys = [
            'system_name', 'system_tagline', 'system_logo', 'logo_light', 'logo_white',
            'logo_dark', 'use_wordmark_only', 'logo_auto_variant', 'brand_primary',
            'brand_secondary', 'brand_accent', 'brand_dark', 'admin_title',
        ];
        foreach ($coreKeys as $ck) {
            Cache::forget("system_setting.{$ck}");
        }

        Cache::forget('system_settings.all_grouped');

        // On database cache store, purge matching entries directly
        try {
            if (config('cache.default') === 'database') {
                $prefix = config('cache.prefix', '');
                \Illuminate\Support\Facades\DB::table('cache')
                    ->where('key', 'like', ($prefix ? $prefix . '-' : '') . '%system_setting%')
                    ->delete();
            }
        } catch (\Throwable $e) {}

        if (class_exists(\App\Services\BrandingService::class)) {
            \App\Services\BrandingService::flush();
        }
    }
}
