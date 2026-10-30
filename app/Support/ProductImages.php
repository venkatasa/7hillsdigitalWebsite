<?php

namespace App\Support;

/**
 * Screenshots of our own software, with their provenance.
 *
 * Every image under public/images/product/ is declared in manifest.json with
 * where it came from — a demo tenant, or our own back office on staging with
 * fictional data. The label travels with the image onto the page, so a
 * screenshot can never be shown without saying what it is.
 */
class ProductImages
{
    public const ALLOWED_SOURCES = ['demo', 'staging-fictional', 'client-public', 'illustration'];

    private static ?array $manifest = null;

    public static function all(): array
    {
        if (self::$manifest === null) {
            $path = public_path('images/product/manifest.json');
            self::$manifest = is_file($path)
                ? (json_decode(file_get_contents($path), true)['images'] ?? [])
                : [];
        }

        return self::$manifest;
    }

    /** The manifest entry for a key, or null if the image is not declared. */
    public static function get(string $key): ?array
    {
        $image = self::all()[$key] ?? null;

        if ($image === null || ! is_file(public_path($image['file']))) {
            return null;
        }

        return $image;
    }
}
