<?php

namespace App\Support;

/**
 * Interim catalogue for the revamp, with the publication rule applied.
 *
 * Moves to the Offering model in Phase 2. The rule does not move: a service whose
 * capability is unconfirmed, or whose sale is blocked by an open production
 * finding, is never published. On staging, REVAMP_PREVIEW_UNCONFIRMED shows it —
 * marked as a preview — so the owner can judge the design; production ignores the
 * switch entirely.
 */
class Revamp
{
    public const AVAILABILITY = [
        'available' => 'Available now',
        'built_to_order' => 'Built to your requirements',
        'in_development' => 'In development',
        'capability_unconfirmed' => 'Preview only — not published',
        'blocked' => 'Preview only — blocked until production fixes',
    ];

    private const PUBLISHABLE = ['available', 'built_to_order'];

    /** True when staging may show unpublishable services, marked as previews. */
    public static function previewing(): bool
    {
        return config('revamp.preview_unconfirmed') && ! app()->isProduction();
    }

    /** Every service, flat, keyed by slug: name, line, availability, preview. */
    public static function catalogue(): array
    {
        $out = [];

        foreach (config('revamp.lines', []) as $lineSlug => $line) {
            foreach ($line['services'] as $slug => [$name, $availability]) {
                $out[$slug] = [
                    'slug' => $slug,
                    'name' => $name,
                    'line' => $lineSlug,
                    'availability' => $availability,
                    'preview' => ! in_array($availability, self::PUBLISHABLE, true),
                ];
            }
        }

        return $out;
    }

    /** Whether a service may render on this environment. */
    public static function visible(?string $slug): bool
    {
        $service = self::catalogue()[$slug] ?? null;

        return $service !== null && (! $service['preview'] || self::previewing());
    }

    /** Lines with only their renderable services; a line left empty is dropped. */
    public static function lines(): array
    {
        $out = [];

        foreach (config('revamp.lines', []) as $slug => $line) {
            $services = array_filter(
                self::catalogue(),
                fn ($s) => $s['line'] === $slug && self::visible($s['slug']),
            );

            if ($services === []) {
                continue;
            }

            // A line is a preview when none of its rendered services is publishable.
            $preview = array_filter($services, fn ($s) => ! $s['preview']) === [];

            $out[$slug] = ['slug' => $slug, 'services' => $services, 'preview' => $preview] + $line;
        }

        return $out;
    }

    /** Exactly eight featured services: the first choices that may render, then fallbacks. */
    public static function featured(int $count = 8): array
    {
        $catalogue = self::catalogue();
        $picked = [];

        foreach ([config('revamp.featured', []), config('revamp.fallbacks', [])] as $pool) {
            foreach ($pool as $slug => $card) {
                if (count($picked) === $count) {
                    break 2;
                }
                if (self::visible($slug)) {
                    $line = $catalogue[$slug]['line'];
                    $picked[$slug] = $card + $catalogue[$slug] + ['line_name' => config("revamp.lines.{$line}.short")];
                }
            }
        }

        return $picked;
    }

    /** The positioning line, naming only capabilities that may be published. */
    public static function headline(): array
    {
        $ai = self::visible(config('revamp.headline.ai'));
        $cloud = self::visible(config('revamp.headline.cloud'));

        $middle = match (true) {
            $ai && $cloud => 'AI, Cloud',
            $cloud => 'Cloud',
            $ai => 'AI',
            default => 'Automation',
        };

        return ['lead' => 'Software Development,', 'accent' => $middle, 'tail' => '& IT Solutions for Modern Businesses.'];
    }

    /** Quote chooser types whose proving service may render. */
    public static function quoteTypes(): array
    {
        return array_filter(
            config('revamp.quote_types', []),
            fn ($type) => self::visible($type[2]),
        );
    }

    public static function industries(): array
    {
        return config('revamp.industries', []);
    }
}
