<?php

namespace App\Support;

use Illuminate\Support\Facades\File;

/**
 * The photographs behind the demo tenants.
 *
 * Downloaded from Openverse by `php artisan demos:import-images` and served
 * from our own origin — never hotlinked, because the site promises it loads
 * nothing from a third party. If the images are absent (a fresh checkout, or
 * the importer could not reach the internet) everything falls back to the
 * composed SVG in <x-demo-art>, so the demos never render broken.
 */
class DemoImages
{
    /** @var array<string, array<int, array<string, mixed>>> */
    private static array $manifests = [];

    /** @return array<int, array<string, mixed>> */
    public static function credits(string $demo): array
    {
        if (array_key_exists($demo, self::$manifests)) {
            return self::$manifests[$demo];
        }

        $path = public_path("images/demos/{$demo}/credits.json");

        if (! File::exists($path)) {
            return self::$manifests[$demo] = [];
        }

        $decoded = json_decode(File::get($path), true);

        return self::$manifests[$demo] = is_array($decoded) ? $decoded : [];
    }

    public static function count(string $demo): int
    {
        return count(self::credits($demo));
    }

    /** Where the small copy of an image lives: `1.jpg` → `1-720.jpg`. */
    public static function thumbPath(string $path): string
    {
        return preg_replace('/\.jpg$/', '-'.\App\Console\Commands\ImportDemoImages::THUMB_WIDTH.'.jpg', $path);
    }

    /** `srcset` for one image: the small copy and the original, when both exist. */
    public static function srcset(string $demo, int $index): ?string
    {
        $credits = self::credits($demo);
        $file = $credits === [] ? null : ($credits[$index % count($credits)]['file'] ?? null);

        if (! $file || ! is_file(public_path(self::thumbPath("images/demos/{$demo}/{$file}")))) {
            return null;
        }

        $w = \App\Console\Commands\ImportDemoImages::THUMB_WIDTH;

        return asset(self::thumbPath("images/demos/{$demo}/{$file}"))." {$w}w, ".asset("images/demos/{$demo}/{$file}").' 1400w';
    }

    /**
     * The public path for one image, chosen by index and wrapping round so a
     * grid longer than the set still fills.
     */
    public static function url(string $demo, int $index): ?string
    {
        $credits = self::credits($demo);

        if ($credits === []) {
            return null;
        }

        $entry = $credits[$index % count($credits)];
        $file = $entry['file'] ?? null;

        return $file ? asset("images/demos/{$demo}/{$file}") : null;
    }

    /**
     * Credit lines for the licences that require them. CC0 and Public Domain
     * Mark do not, so they are left out rather than padding the footer.
     *
     * @return array<int, string>
     */
    public static function attributions(string $demo): array
    {
        $lines = [];

        foreach (self::credits($demo) as $credit) {
            $licence = strtoupper((string) ($credit['license'] ?? ''));

            if (in_array($licence, ['CC0', 'PDM', ''], true)) {
                continue;
            }

            $title = $credit['title'] ?: 'Untitled';
            $creator = $credit['creator'] ?: 'Unknown';
            $lines[] = trim("{$title} by {$creator} (CC {$licence})");
        }

        return array_values(array_unique($lines));
    }
}
