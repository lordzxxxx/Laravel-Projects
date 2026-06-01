<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class TenantLogoProcessor
{
    private const MAX_EDGE = 800;

    /** RGB channels at or above this value are treated as background (made transparent). */
    private const LIGHT_BACKGROUND_THRESHOLD = 248;

    /**
     * Store an uploaded logo as PNG with alpha (preserves PNG/WebP transparency; lifts white JPEG backgrounds).
     */
    public static function store(UploadedFile $file, string $directory = 'tenant-logos'): string
    {
        if (! extension_loaded('gd')) {
            return $file->store($directory, 'public');
        }

        try {
            $pngBytes = self::toTransparentPngBytes($file);
        } catch (\Throwable) {
            return $file->store($directory, 'public');
        }

        $path = trim($directory, '/').'/'.Str::uuid().'.png';
        Storage::disk('public')->put($path, $pngBytes);

        return $path;
    }

    /**
     * @throws RuntimeException
     */
    public static function toTransparentPngBytes(UploadedFile $file): string
    {
        $source = self::loadImageResource($file);
        $width = imagesx($source);
        $height = imagesy($source);

        if ($width < 1 || $height < 1) {
            imagedestroy($source);
            throw new RuntimeException('Invalid logo dimensions.');
        }

        [$width, $height, $source] = self::downscaleIfNeeded($source, $width, $height);

        $canvas = imagecreatetruecolor($width, $height);
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);

        $fill = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
        imagefill($canvas, 0, 0, $fill);

        imagealphablending($canvas, true);
        imagecopy($canvas, $source, 0, 0, 0, 0, $width, $height);
        imagedestroy($source);

        self::applyLightBackgroundTransparency($canvas, $width, $height);

        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);

        ob_start();
        imagepng($canvas, null, 6);
        $bytes = (string) ob_get_clean();
        imagedestroy($canvas);

        if ($bytes === '') {
            throw new RuntimeException('Failed to encode logo PNG.');
        }

        return $bytes;
    }

    /**
     * @return resource|\GdImage
     */
    private static function loadImageResource(UploadedFile $file)
    {
        $path = $file->getRealPath();
        if (! is_string($path) || $path === '') {
            throw new RuntimeException('Unreadable logo upload.');
        }

        $mime = strtolower((string) $file->getMimeType());
        $extension = strtolower((string) $file->getClientOriginalExtension());

        $image = match (true) {
            str_contains($mime, 'png') || $extension === 'png' => @imagecreatefrompng($path),
            str_contains($mime, 'webp') || $extension === 'webp' => function_exists('imagecreatefromwebp')
                ? @imagecreatefromwebp($path)
                : false,
            str_contains($mime, 'gif') || $extension === 'gif' => @imagecreatefromgif($path),
            default => @imagecreatefromjpeg($path) ?: @imagecreatefrompng($path),
        };

        if ($image === false) {
            throw new RuntimeException('Unsupported or corrupt logo image.');
        }

        if (function_exists('imagesavealpha')) {
            imagesavealpha($image, true);
        }

        return $image;
    }

    /**
     * @param  resource|\GdImage  $source
     * @return array{0: int, 1: int, 2: resource|\GdImage}
     */
    private static function downscaleIfNeeded($source, int $width, int $height): array
    {
        $maxEdge = max($width, $height);
        if ($maxEdge <= self::MAX_EDGE) {
            return [$width, $height, $source];
        }

        $scale = self::MAX_EDGE / $maxEdge;
        $targetWidth = max(1, (int) round($width * $scale));
        $targetHeight = max(1, (int) round($height * $scale));

        $resized = imagecreatetruecolor($targetWidth, $targetHeight);
        imagealphablending($resized, false);
        imagesavealpha($resized, true);
        $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
        imagefill($resized, 0, 0, $transparent);
        imagealphablending($resized, true);
        imagecopyresampled($resized, $source, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);
        imagedestroy($source);

        return [$targetWidth, $targetHeight, $resized];
    }

    /**
     * @param  resource|\GdImage  $image
     */
    private static function applyLightBackgroundTransparency($image, int $width, int $height): void
    {
        imagealphablending($image, false);
        imagesavealpha($image, true);

        $threshold = self::LIGHT_BACKGROUND_THRESHOLD;

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $rgba = imagecolorat($image, $x, $y);
                $alpha = ($rgba >> 24) & 0x7F;

                if ($alpha >= 127) {
                    continue;
                }

                $red = ($rgba >> 16) & 0xFF;
                $green = ($rgba >> 8) & 0xFF;
                $blue = $rgba & 0xFF;

                if ($red >= $threshold && $green >= $threshold && $blue >= $threshold) {
                    $transparent = imagecolorallocatealpha($image, $red, $green, $blue, 127);
                    imagesetpixel($image, $x, $y, $transparent);
                }
            }
        }
    }
}
