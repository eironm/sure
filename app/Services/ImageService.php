<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

/**
 * Central image processing / compression service.
 *
 * Used by every upload path in the app (facility galleries, avatars, branding)
 * so behaviour stays consistent. Backed by GD; if GD is unavailable or the file
 * is a format GD can't rasterise (e.g. SVG, ICO), it falls back to a plain move
 * so uploads never hard-fail.
 *
 * Output is written into public/<dir> and the relative path is returned, matching
 * the existing convention used across the controllers (asset() friendly).
 */
class ImageService
{
    /** Formats GD cannot process; these are moved as-is. */
    protected const PASSTHROUGH_EXT = ['svg', 'ico', 'gif'];

    /**
     * Store and compress an uploaded image.
     *
     * @param  UploadedFile $file
     * @param  string $dir        Public sub-directory, e.g. "uploads/facilities".
     * @param  array  $opts       maxWidth, maxHeight, quality (0-100), format ('jpg'|'webp'|'auto').
     * @return string             Relative public path, e.g. "uploads/facilities/ab12.webp".
     */
    public function store(UploadedFile $file, string $dir, array $opts = []): string
    {
        $opts = array_merge([
            'maxWidth'  => 1600,
            'maxHeight' => 1600,
            'quality'   => 82,
            'format'    => 'auto', // auto = webp when supported, else jpg
        ], $opts);

        $targetDir = public_path($dir);
        if (!is_dir($targetDir)) {
            @mkdir($targetDir, 0755, true);
        }

        $ext = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');

        // Vector / icon / animated formats: keep as-is.
        if (in_array($ext, self::PASSTHROUGH_EXT, true) || !$this->gdAvailable()) {
            return $this->moveOriginal($file, $dir, $ext);
        }

        $source = $this->createImageResource($file->getRealPath(), $ext);
        if (!$source) {
            return $this->moveOriginal($file, $dir, $ext);
        }

        [$dst, $dstW, $dstH] = $this->resize($source, $opts['maxWidth'], $opts['maxHeight']);

        $useWebp = $opts['format'] === 'webp'
            || ($opts['format'] === 'auto' && function_exists('imagewebp'));

        $outExt   = $useWebp ? 'webp' : 'jpg';
        $filename = $this->randomName($outExt);
        $fullPath = $targetDir . DIRECTORY_SEPARATOR . $filename;

        if ($useWebp) {
            imagewebp($dst, $fullPath, (int) $opts['quality']);
        } else {
            imagejpeg($dst, $fullPath, (int) $opts['quality']);
        }

        // Note: imagedestroy() is a no-op / deprecated on PHP 8.0+, so we simply
        // let the GdImage objects fall out of scope for garbage collection.

        return $dir . '/' . $filename;
    }

    /** True when the GD extension and JPEG support are available. */
    public function gdAvailable(): bool
    {
        return extension_loaded('gd') && function_exists('imagecreatetruecolor');
    }

    /* ── Internals ──────────────────────────────────────────────── */

    protected function createImageResource(string $path, string $ext)
    {
        try {
            return match ($ext) {
                'jpg', 'jpeg' => function_exists('imagecreatefromjpeg') ? @imagecreatefromjpeg($path) : false,
                'png'         => function_exists('imagecreatefrompng')  ? @imagecreatefrompng($path)  : false,
                'webp'        => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false,
                default       => false,
            } ?: false;
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Resize preserving aspect ratio; never upscales. Flattens transparency to
     * white when the destination is opaque (jpg).
     *
     * @return array{0:\GdImage,1:int,2:int}
     */
    protected function resize($source, int $maxW, int $maxH): array
    {
        $srcW = imagesx($source);
        $srcH = imagesy($source);

        $ratio = min($maxW / $srcW, $maxH / $srcH, 1.0);
        $dstW  = max(1, (int) round($srcW * $ratio));
        $dstH  = max(1, (int) round($srcH * $ratio));

        if ($dstW === $srcW && $dstH === $srcH) {
            return [$source, $srcW, $srcH];
        }

        $dst = imagecreatetruecolor($dstW, $dstH);

        // Preserve alpha for webp output; harmless for jpg since we fill white.
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        $transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
        imagefilledrectangle($dst, 0, 0, $dstW, $dstH, $transparent);
        imagealphablending($dst, true);

        imagecopyresampled($dst, $source, 0, 0, 0, 0, $dstW, $dstH, $srcW, $srcH);

        return [$dst, $dstW, $dstH];
    }

    protected function moveOriginal(UploadedFile $file, string $dir, string $ext): string
    {
        $filename = $this->randomName($ext);
        $file->move(public_path($dir), $filename);

        return $dir . '/' . $filename;
    }

    protected function randomName(string $ext): string
    {
        return \Illuminate\Support\Str::random(24) . '.' . $ext;
    }
}
