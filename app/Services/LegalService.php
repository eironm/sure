<?php

namespace App\Services;

use Illuminate\Support\Str;

/**
 * Resolves whitelabel legal documents (Terms of Service, Privacy Policy,
 * Data Collection Notice) stored as JSON in system_settings.
 *
 * Each document is an ordered list of language entries:
 *   [ { "code": "en", "label": "English", "body": "<markdown>" }, ... ]
 *
 * Bodies are authored in Markdown and rendered to HTML on read.
 */
class LegalService
{
    /**
     * Map short document keys to their underlying setting keys.
     */
    protected static array $map = [
        'terms'           => 'legal_terms',
        'privacy'         => 'legal_privacy',
        'data_collection' => 'legal_data_collection',
    ];

    /**
     * Get the decoded language entries for a document.
     *
     * @return array<int, array{code:string,label:string,body:string}>
     */
    public static function languages(string $doc): array
    {
        $settingKey = static::$map[$doc] ?? $doc;
        $raw        = BrandingService::get($settingKey, null);

        if (empty($raw)) {
            return [];
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            return [];
        }

        // Normalize and drop empties.
        $entries = [];
        foreach ($decoded as $i => $item) {
            if (!is_array($item)) {
                continue;
            }
            $body = trim((string) ($item['body'] ?? ''));
            if ($body === '') {
                continue;
            }
            $code  = trim((string) ($item['code'] ?? ('lang' . $i))) ?: ('lang' . $i);
            $label = trim((string) ($item['label'] ?? '')) ?: strtoupper($code);
            $entries[] = [
                'code'  => Str::slug($code) ?: ('lang' . $i),
                'label' => $label,
                'body'  => $body,
            ];
        }

        return $entries;
    }

    /**
     * Get language entries with the body rendered to HTML.
     *
     * @return array<int, array{code:string,label:string,html:string}>
     */
    public static function rendered(string $doc): array
    {
        return array_map(function (array $entry) {
            return [
                'code'  => $entry['code'],
                'label' => $entry['label'],
                'html'  => static::toHtml($entry['body']),
            ];
        }, static::languages($doc));
    }

    /**
     * Convert Markdown to sanitized HTML.
     */
    public static function toHtml(string $markdown): string
    {
        return Str::markdown($markdown, [
            'html_input'         => 'escape',
            'allow_unsafe_links' => false,
        ]);
    }

    /**
     * Get the timestamp recorded when the legal documents were last saved.
     *
     * Existing installations without the new setting fall back to the current
     * date once; subsequent saves persist the real timestamp.
     */
    public static function lastUpdated(): \Carbon\Carbon
    {
        $raw = BrandingService::get('legal_documents_updated_at', null);

        try {
            return $raw
                ? \Carbon\Carbon::parse($raw)->timezone(config('app.timezone'))
                : now();
        } catch (\Throwable $e) {
            return now();
        }
    }
    /**
     * All document short-keys with their human labels.
     *
     * @return array<string,string>
     */
    public static function documents(): array
    {
        return [
            'terms'           => 'Terms of Service',
            'privacy'         => 'Privacy Policy',
            'data_collection' => 'Data Collection Notice',
        ];
    }

    /**
     * Underlying setting key for a short document key.
     */
    public static function settingKey(string $doc): string
    {
        return static::$map[$doc] ?? $doc;
    }
}
