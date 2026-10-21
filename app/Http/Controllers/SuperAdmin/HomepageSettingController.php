<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Services\BrandingService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * SuperAdmin Homepage Editor — one dedicated, plain HTML page per section.
 * All content is stored as JSON in the system_settings row "homepage_content".
 * Saving any section preserves every other section exactly as-is.
 */
class HomepageSettingController extends Controller
{
    private const KEY = 'homepage_content';

    private const TABS = ['hero', 'about', 'features', 'gallery', 'faq', 'contact'];

    /**
     * Canonical field names inside the stored JSON for each section.
     * Views read AND write these exact keys — one shape, no mismatches.
     */
    private const SECTION_KEYS = [
        'hero'     => ['heading', 'sub', 'cta_text', 'eyebrow', 'badges', 'media'],
        'about'    => ['title', 'text', 'subtitle', 'quote'],
        'features' => ['title', 'subtitle', 'cards'],
        'gallery'  => ['title', 'subtitle', 'note', 'items'],
        'faq'      => ['title', 'subtitle', 'groups'],
        'contact'  => ['title', 'note'],
    ];

    /** Map of posted form field => canonical JSON key per section. */
    private const FORM_MAP = [
        'hero'     => ['hero_eyebrow' => 'eyebrow', 'hero_heading' => 'heading', 'hero_cta_text' => 'cta_text', 'hero_sub' => 'sub'],
        'about'    => ['about_title' => 'title', 'about_sub_text' => 'subtitle', 'about_text' => 'text', 'about_quote' => 'quote'],
        'features' => ['feature_title' => 'title', 'feature_sub' => 'subtitle'],
        'gallery'  => ['gallery_title' => 'title', 'gallery_sub' => 'subtitle', 'gallery_note' => 'note'],
        'faq'      => ['faq_title' => 'title', 'faq_sub' => 'subtitle'],
        'contact'  => ['contact_title' => 'title', 'contact_note' => 'note'],
    ];

    /** Map of posted JSON-repeater field => canonical JSON key per section. */
    private const REPEATER_MAP = [
        'hero'     => ['hero_badges' => 'badges'],
        'features' => ['feature_cards' => 'cards'],
        'gallery'  => ['gallery_items' => 'items'],
        'faq'      => ['faq_groups' => 'groups'],
    ];

    /** Default content used when the JSON row is missing or empty. */
    private const DEFAULTS = [
        'hero'     => ['heading' => 'Reserve University Facilities', 'sub' => 'Book campus spaces effortlessly with our streamlined reservation platform.', 'cta_text' => 'Reserve a facility', 'eyebrow' => 'Simplify University Reservations', 'badges' => [['label' => '100% online process'], ['label' => 'Real-time status updates'], ['label' => 'No double bookings']], 'media' => ['enabled' => true, 'type' => 'video', 'url' => 'assets/figma.mp4', 'poster' => 'assets/figma-not.jpg', 'alt' => 'University campus activity']],
        'about'    => ['title' => 'About Us', 'text' => '', 'subtitle' => '', 'quote' => ''],
        'features' => ['title' => 'Why choose us', 'subtitle' => '', 'cards' => []],
        'gallery'  => ['title' => 'Gallery', 'subtitle' => '', 'note' => '', 'items' => []],
        'faq'      => ['title' => 'Frequently asked questions', 'subtitle' => '', 'groups' => []],
        'contact'  => ['title' => 'Contact us', 'note' => ''],
    ];

    /**
     * Show a single Homepage Editor section page.
     */
    public function category(string $tab)
    {
        $tab = in_array($tab, self::TABS, true) ? $tab : 'hero';

        $data = self::loadData();

        // Facility photos available for the homepage gallery picker.
        $availableImages = [];
        $galleryDir = public_path('uploads/facilities');
        if (File::isDirectory($galleryDir)) {
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            foreach (File::files($galleryDir) as $file) {
                if (in_array(strtolower($file->getExtension()), $allowedExtensions, true)) {
                    $availableImages[] = [
                        'filename' => $file->getFilename(),
                        'url'      => asset('uploads/facilities/' . $file->getFilename()),
                    ];
                }
            }
        }
        usort($availableImages, fn($a, $b) => strcasecmp($a['filename'], $b['filename']));

        return view('superadmin.settings.homepage.category.' . $tab, [
            'tab'             => $tab,
            'data'            => $data,
            'availableImages' => $availableImages,
        ]);
    }

    /**
     * Save the section posted by the category form; every other section is
     * copied forward verbatim so partial saves can never lose data.
     */
    public function update(Request $request)
    {
        $editingSection = in_array($request->input('editing_section'), self::TABS, true)
            ? $request->input('editing_section')
            : null;

        $request->validate([
            'hero_heading'  => ['nullable', 'string', 'max:255'],
            'hero_sub'      => ['nullable', 'string', 'max:500'],
            'hero_cta_text' => ['nullable', 'string', 'max:100'],
            'hero_eyebrow'  => ['nullable', 'string', 'max:100'],
            'hero_media_enabled' => ['nullable', 'boolean'],
            'hero_media_reset'   => ['nullable', 'boolean'],
            'hero_media_type'    => ['nullable', 'in:image,video,gif'],
            'hero_media_alt'     => ['nullable', 'string', 'max:255'],
            'hero_media'        => ['nullable', 'file', 'max:51200', 'mimes:mp4,webm,ogg,gif,jpg,jpeg,png,webp'],
            'hero_media_poster' => ['nullable', 'file', 'max:20480', 'mimes:gif,jpg,jpeg,png,webp'],
            'about_title'   => ['nullable', 'string', 'max:255'],
            'about_sub_text'=> ['nullable', 'string', 'max:500'],
            'about_text'    => ['nullable', 'string'],
            'about_quote'   => ['nullable', 'string', 'max:500'],
            'feature_title' => ['nullable', 'string', 'max:255'],
            'feature_sub'   => ['nullable', 'string', 'max:255'],
            'gallery_title' => ['nullable', 'string', 'max:255'],
            'gallery_sub'   => ['nullable', 'string', 'max:255'],
            'gallery_note'  => ['nullable', 'string', 'max:500'],
            'faq_title'     => ['nullable', 'string', 'max:255'],
            'faq_sub'       => ['nullable', 'string', 'max:500'],
            'contact_title' => ['nullable', 'string', 'max:255'],
            'contact_note'  => ['nullable', 'string', 'max:500'],
        ], [], [
            'hero_heading'       => 'Hero Heading',
            'hero_sub'           => 'Hero Subheading',
            'hero_cta_text'      => 'Hero Call-to-Action Text',
            'hero_eyebrow'       => 'Hero Eyebrow Text',
            'hero_media'         => 'Hero Media File',
            'hero_media_poster'  => 'Hero Media Poster Image',
            'about_title'        => 'About Section Title',
            'about_sub_text'     => 'About Section Subtitle',
            'about_text'         => 'About Section Content',
            'about_quote'        => 'About Section Quote',
            'feature_title'      => 'Feature Section Title',
            'feature_sub'        => 'Feature Section Subtitle',
            'gallery_title'      => 'Gallery Section Title',
            'gallery_sub'        => 'Gallery Section Subtitle',
            'gallery_note'       => 'Gallery Section Note',
            'faq_title'          => 'FAQ Section Title',
            'faq_sub'            => 'FAQ Section Subtitle',
            'contact_title'      => 'Contact Section Title',
            'contact_note'       => 'Contact Section Note',
        ]);

        // Decode JSON repeater payloads (synced from the page JS as JSON strings).
        foreach (['hero_badges', 'feature_cards', 'gallery_items', 'faq_groups'] as $k) {
            $raw = $request->input($k);
            if (is_string($raw)) {
                $decoded = json_decode($raw, true);
                $request->merge([$k => is_array($decoded) ? $decoded : []]);
            }
        }

        $existing   = self::loadData();
        $heroMedia  = $existing['hero']['media'] ?? self::DEFAULTS['hero']['media'];

        if ($editingSection === 'hero') {
            if ($request->boolean('hero_media_reset')) {
                $heroMedia = self::DEFAULTS['hero']['media'];
            } else {
                if ($request->has('hero_media_enabled')) {
                    $heroMedia['enabled'] = $request->boolean('hero_media_enabled');
                }
                if ($request->has('hero_media_type')) {
                    $heroMedia['type'] = $request->input('hero_media_type');
                }
                if ($request->has('hero_media_alt')) {
                    $heroMedia['alt'] = trim((string) $request->input('hero_media_alt'));
                }
                if ($request->hasFile('hero_media')) {
                    $file = $request->file('hero_media');
                    $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension());
                    $heroMedia['url'] = self::storeHeroAsset($file, 'media', $extension);
                    $heroMedia['type'] = in_array($extension, ['mp4', 'webm', 'ogg'], true)
                        ? 'video'
                        : ($extension === 'gif' ? 'gif' : 'image');
                }
                if ($request->hasFile('hero_media_poster')) {
                    $file = $request->file('hero_media_poster');
                    $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension());
                    $heroMedia['poster'] = self::storeHeroAsset($file, 'poster', $extension);
                }
            }
        }
        $payload    = [];

        foreach (self::TABS as $section) {
            $isEditing = ($editingSection === null || $editingSection === $section);

            $base = $existing[$section] ?? self::DEFAULTS[$section];
            $built = $base;
            if ($section === 'hero') {
                $built['media'] = $heroMedia;
            }

            // Scalar fields posted by THIS page only replace their values when
            // this page is the one editing the section.
            foreach ((self::FORM_MAP[$section] ?? []) as $inKey => $outKey) {
                if ($isEditing && $request->has($inKey)) {
                    $built[$outKey] = trim((string) ($request->input($inKey) ?? ''));
                }
            }

            // JSON repeater fields posted by THIS page overwrite the whole array.
            foreach ((self::REPEATER_MAP[$section] ?? []) as $inKey => $outKey) {
                if ($isEditing && $request->has($inKey)) {
                    $built[$outKey] = $request->input($inKey);
                }
            }

            $payload[$section] = $built;
        }

        $payload = self::sanitize($payload);

        SystemSetting::updateOrCreate(
            ['key' => self::KEY],
            ['value' => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)]
        );

        Cache::forget(self::KEY);
        BrandingService::flush();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'  => true,
                'message'  => 'Homepage content updated successfully.',
                'redirect' => route('superadmin.settings.' . ($editingSection ?? 'hero')),
                'media'    => $heroMedia,
            ]);
        }

        return redirect()->route('superadmin.settings.' . ($editingSection ?? 'hero'))
            ->with('success', 'Homepage content updated successfully.');
    }

    /**
     * Load the homepage content JSON with defaults merged in for missing keys.
     */
    public static function loadData(): array
    {
        $data = [];
        try {
            $raw = SystemSetting::where('key', self::KEY)->first();
            if ($raw) {
                $decoded = json_decode($raw->value, true);
                if (is_array($decoded)) {
                    $data = $decoded;
                }
            }
        } catch (\Throwable $e) {
            // A fresh Wasmer database may not have system_settings yet.
            // The editor must still render with its built-in defaults.
            Log::warning('Homepage settings unavailable; using defaults.', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
            ]);
        }

        foreach (self::TABS as $section) {
            $sectionData = $data[$section] ?? [];
            foreach (self::SECTION_KEYS[$section] as $key) {
                if (! array_key_exists($key, $sectionData)) {
                    $sectionData[$key] = self::DEFAULTS[$section][$key] ?? '';
                }
            }
            $data[$section] = $sectionData;
        }

        return $data;
    }

    private static function storeHeroAsset(UploadedFile $file, string $slot, string $extension): string
    {
        @set_time_limit(300);
        @ini_set('memory_limit', '512M');

        $directory = public_path('uploads/homepage');
        if (! is_dir($directory) && ! mkdir($directory, 0755, true) && ! is_dir($directory)) {
            throw new \RuntimeException('Unable to create the homepage upload directory.');
        }

        $filename = 'hero-' . $slot . '-' . Str::random(24) . '.' . $extension;
        $file->move($directory, $filename);
        $fullPath = $directory . DIRECTORY_SEPARATOR . $filename;

        // Automatically convert MP4 videos to FastStart (moov atom at file start)
        // so browsers can begin progressive streaming instantly instead of buffering the whole file.
        if ($extension === 'mp4') {
            self::optimizeMp4FastStart($fullPath);
        }

        // Automatically downscale oversized camera images (>1920px or >1MB) using GD
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            self::optimizeImage($fullPath, $extension);
        }

        return 'uploads/homepage/' . $filename;
    }

    /**
     * Pure-PHP MP4 FastStart relocation.
     * Moves the 'moov' atom from the end of the MP4 file to before 'mdat' and
     * patches all chunk offsets (stco/co64) accordingly. Runs in ~50ms without external tools.
     */
    private static function optimizeMp4FastStart(string $filePath): void
    {
        if (! file_exists($filePath)) {
            return;
        }

        try {
            $tmpPath = $filePath . '.faststart.tmp';
            if (self::applyFastStart($filePath, $tmpPath) && file_exists($tmpPath) && filesize($tmpPath) > 0) {
                rename($tmpPath, $filePath);
            } elseif (file_exists($tmpPath)) {
                @unlink($tmpPath);
            }
        } catch (\Throwable $e) {
            Log::info('MP4 faststart optimization skipped: ' . $e->getMessage());
        }
    }

    private static function copyStreamBytes($in, $out, int $bytes): void
    {
        $chunkSize = 1048576; // 1 MB buffer for fast I/O
        while ($bytes > 0 && ! feof($in)) {
            $toRead = min($bytes, $chunkSize);
            $chunk = fread($in, $toRead);
            if ($chunk === false || $chunk === '') {
                break;
            }
            fwrite($out, $chunk);
            $bytes -= strlen($chunk);
        }
    }

    private static function applyFastStart(string $inputFile, string $outputFile): bool
    {
        $in = @fopen($inputFile, 'rb');
        if (! $in) {
            return false;
        }

        $fileSize = filesize($inputFile);
        $atoms = [];
        $moovPos = -1;
        $moovSize = 0;
        $mdatPos = -1;
        $mdatSize = 0;
        $ftypPos = -1;
        $ftypSize = 0;

        while (ftell($in) < $fileSize) {
            $pos = ftell($in);
            $hdr = fread($in, 8);
            if (strlen($hdr) < 8) {
                break;
            }
            $size = unpack('N', substr($hdr, 0, 4))[1];
            $type = substr($hdr, 4, 4);

            if ($size === 1) {
                $size = unpack('J', fread($in, 8))[1];
            } elseif ($size === 0) {
                $size = $fileSize - $pos;
            }

            if ($size <= 0) {
                break;
            }

            if ($type === 'ftyp' && $ftypPos === -1) {
                $ftypPos = $pos;
                $ftypSize = $size;
            } elseif ($type === 'moov') {
                $moovPos = $pos;
                $moovSize = $size;
            } elseif ($type === 'mdat' && $mdatPos === -1) {
                $mdatPos = $pos;
                $mdatSize = $size;
            }

            $atoms[] = ['type' => $type, 'pos' => $pos, 'size' => $size];
            fseek($in, $pos + $size);
        }

        // If moov is already before mdat or atoms are missing, file is already faststart or unhandled
        if ($moovPos === -1 || $mdatPos === -1 || $moovPos < $mdatPos) {
            fclose($in);
            return false;
        }

        // Read moov metadata atom into memory
        fseek($in, $moovPos);
        $moovData = fread($in, $moovSize);
        if (strlen($moovData) !== $moovSize) {
            fclose($in);
            return false;
        }

        // Patch chunk offsets inside moov atom (shifting by moovSize)
        self::patchMoovOffsets($moovData, 8, $moovSize, $moovSize);

        $out = @fopen($outputFile, 'wb');
        if (! $out) {
            fclose($in);
            return false;
        }

        // 1. Write ftyp
        if ($ftypPos !== -1) {
            fseek($in, $ftypPos);
            self::copyStreamBytes($in, $out, $ftypSize);
        }

        // 2. Write relocated moov
        fwrite($out, $moovData);

        // 3. Write atoms before mdat (skipping ftyp & moov)
        foreach ($atoms as $atom) {
            if ($atom['type'] !== 'ftyp' && $atom['type'] !== 'moov' && $atom['pos'] < $mdatPos) {
                fseek($in, $atom['pos']);
                self::copyStreamBytes($in, $out, $atom['size']);
            }
        }

        // 4. Stream mdat
        fseek($in, $mdatPos);
        self::copyStreamBytes($in, $out, $mdatSize);

        // 5. Write any atoms that appeared after moov
        foreach ($atoms as $atom) {
            if ($atom['pos'] > $moovPos) {
                fseek($in, $atom['pos']);
                self::copyStreamBytes($in, $out, $atom['size']);
            }
        }

        fclose($in);
        fclose($out);

        return true;
    }

    private static function patchMoovOffsets(string &$data, int $start, int $end, int $shift): void
    {
        $pos = $start;
        while ($pos <= $end - 8) {
            $atomSize = unpack('N', substr($data, $pos, 4))[1];
            $atomType = substr($data, $pos + 4, 4);
            if ($atomSize < 8 || $pos + $atomSize > $end) {
                break;
            }

            if (in_array($atomType, ['moov', 'trak', 'mdia', 'minf', 'stbl'], true)) {
                self::patchMoovOffsets($data, $pos + 8, $pos + $atomSize, $shift);
            } elseif ($atomType === 'stco' && $atomSize >= 16) {
                $entryCount = unpack('N', substr($data, $pos + 12, 4))[1];
                $tableStart = $pos + 16;
                if ($tableStart + ($entryCount * 4) <= $pos + $atomSize) {
                    for ($i = 0; $i < $entryCount; $i++) {
                        $offsetPos = $tableStart + ($i * 4);
                        $curr = unpack('N', substr($data, $offsetPos, 4))[1];
                        $p = pack('N', $curr + $shift);
                        $data[$offsetPos]     = $p[0];
                        $data[$offsetPos + 1] = $p[1];
                        $data[$offsetPos + 2] = $p[2];
                        $data[$offsetPos + 3] = $p[3];
                    }
                }
            } elseif ($atomType === 'co64' && $atomSize >= 16) {
                $entryCount = unpack('N', substr($data, $pos + 12, 4))[1];
                $tableStart = $pos + 16;
                if ($tableStart + ($entryCount * 8) <= $pos + $atomSize) {
                    for ($i = 0; $i < $entryCount; $i++) {
                        $offsetPos = $tableStart + ($i * 8);
                        $curr = unpack('J', substr($data, $offsetPos, 8))[1];
                        $p = pack('J', $curr + $shift);
                        for ($b = 0; $b < 8; $b++) {
                            $data[$offsetPos + $b] = $p[$b];
                        }
                    }
                }
            }

            $pos += $atomSize;
        }
    }

    /**
     * Pure-PHP image dimension downscaling and compression using GD.
     * Prevents multi-megabyte 50MP camera photos from blocking hero banner render times.
     */
    private static function optimizeImage(string $filePath, string $extension): void
    {
        if (! extension_loaded('gd') || ! file_exists($filePath)) {
            return;
        }

        try {
            $imageInfo = @getimagesize($filePath);
            if (! $imageInfo) {
                return;
            }

            [$width, $height, $type] = $imageInfo;
            if ($width <= 1920 && filesize($filePath) < 1200000) {
                return;
            }

            $source = match ($type) {
                IMAGETYPE_JPEG => @imagecreatefromjpeg($filePath),
                IMAGETYPE_PNG  => @imagecreatefrompng($filePath),
                IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($filePath) : null,
                default        => null,
            };

            if (! $source) {
                return;
            }

            $targetWidth = min($width, 1920);
            $targetHeight = (int) round($height * ($targetWidth / $width));

            $canvas = imagecreatetruecolor($targetWidth, $targetHeight);

            if ($type === IMAGETYPE_PNG) {
                imagealphablending($canvas, false);
                imagesavealpha($canvas, true);
                $transparent = imagecolorallocatealpha($canvas, 255, 255, 255, 127);
                imagefilledrectangle($canvas, 0, 0, $targetWidth, $targetHeight, $transparent);
            }

            imagecopyresampled($canvas, $source, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);

            $tmpPath = $filePath . '.opt.tmp';
            $saved = match ($type) {
                IMAGETYPE_PNG  => imagepng($canvas, $tmpPath, 7),
                IMAGETYPE_WEBP => function_exists('imagewebp') ? imagewebp($canvas, $tmpPath, 82) : false,
                default        => imagejpeg($canvas, $tmpPath, 82),
            };

            imagedestroy($source);
            imagedestroy($canvas);

            if ($saved && file_exists($tmpPath) && filesize($tmpPath) > 0) {
                rename($tmpPath, $filePath);
            } elseif (file_exists($tmpPath)) {
                @unlink($tmpPath);
            }
        } catch (\Throwable $e) {
            Log::info('Image optimization skipped: ' . $e->getMessage());
        }
    }
private static function sanitize(array $payload): array
    {
        $maxCards = 8;
        $maxQ     = 50;
        $media = $payload['hero']['media'] ?? self::DEFAULTS['hero']['media'];
        $media['enabled'] = filter_var($media['enabled'] ?? true, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? true;
        $media['type'] = in_array($media['type'] ?? 'video', ['image', 'video', 'gif'], true)
            ? $media['type']
            : 'video';
        $media['alt'] = trim((string) ($media['alt'] ?? ''));
        foreach (['url', 'poster'] as $mediaKey) {
            $path = trim((string) ($media[$mediaKey] ?? ''));
            $media[$mediaKey] = ($path !== '' && ! str_contains($path, '..')) ? ltrim($path, '/') : null;
        }
        $payload['hero']['media'] = $media;

        $payload['hero']['badges'] = array_slice(array_values(array_map(function ($b) {
            return ['label' => trim((string) ($b['label'] ?? ''))];
        }, $payload['hero']['badges'] ?? [])), 0, 8);

        $payload['features']['cards'] = array_slice(array_values(array_map(function ($c) {
            return [
                'icon'  => trim((string) ($c['icon'] ?? 'bi-star')),
                'title' => trim((string) ($c['title'] ?? '')),
                'body'  => trim((string) ($c['body'] ?? '')),
            ];
        }, $payload['features']['cards'] ?? [])), 0, $maxCards);

        $payload['gallery']['items'] = array_slice(array_values(array_map(function ($i) {
            $image = trim((string) ($i['image'] ?? ''));
            if ($image !== '') {
                // Normalize full asset() URLs down to a relative path.
                $image = preg_replace('#^https?://[^/]+/#', '', $image);
                $file  = public_path(str_replace('/', DIRECTORY_SEPARATOR, $image));
                if (! is_file($file) || strpos($image, '..') !== false) {
                    $image = '';
                }
            }
            return [
                'caption' => trim((string) ($i['caption'] ?? '')),
                'image'   => $image !== '' ? $image : null,
            ];
        }, $payload['gallery']['items'] ?? [])), 0, 4);

        $cleanGroups = [];
        foreach (array_values($payload['faq']['groups'] ?? []) as $g) {
            $questions = array_slice(array_values(array_map(function ($q) {
                return [
                    'q' => trim((string) ($q['q'] ?? '')),
                    'a' => trim((string) ($q['a'] ?? '')),
                ];
            }, $g['questions'] ?? [])), 0, $maxQ);

            $groupTitle = trim((string) ($g['title'] ?? ''));
            if ($groupTitle !== '' || ! empty($questions)) {
                $cleanGroups[] = [
                    'title'     => $groupTitle,
                    'questions' => $questions,
                ];
            }
        }

        $payload['faq']['groups'] = array_slice($cleanGroups, 0, 10);

        return $payload;
    }
}
