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
            foreach ($line['services'] as $slug => $def) {
                [$name, $availability] = $def;
                $out[$slug] = [
                    'slug' => $slug,
                    'name' => $name,
                    'summary' => $def[2] ?? null,
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

    /*
     | Dedicated service pages. Each is a content file in resources/content/services,
     | gated by the catalogue service it sells (`gate`) and serving the services it
     | lists under `covers`. A page whose gate may not render does not exist here,
     | so on production its URL is a 404 like any other unknown page.
     */
    public static function pageSlugs(): array
    {
        return collect(glob(resource_path('content/services/*.php')))
            ->map(fn ($f) => basename($f, '.php'))
            ->sort()->values()->all();
    }

    public static function page(string $slug): ?array
    {
        $file = resource_path("content/services/{$slug}.php");

        if (! preg_match('/^[a-z0-9-]+$/', $slug) || ! is_file($file)) {
            return null;
        }

        $page = require $file;

        if (! self::visible($page['gate'])) {
            return null;
        }

        return $page + ['slug' => $slug, 'preview' => self::catalogue()[$page['gate']]['preview']];
    }

    /** Every page that may render here, keyed by slug. */
    public static function pages(): array
    {
        // Menus and catalogues ask for this once per link; build it once per
        // environment state rather than re-reading every content file each time.
        static $cache = [];
        $key = (self::previewing() ? 'preview|' : 'live|').app()->environment();

        return $cache[$key] ??= collect(self::pageSlugs())
            ->mapWithKeys(fn ($slug) => [$slug => self::page($slug)])
            ->filter()->all();
    }

    /** The page that sells a catalogue service, if one may render. */
    public static function pageFor(string $service): ?string
    {
        foreach (self::pages() as $slug => $page) {
            if ($page['gate'] === $service || in_array($service, $page['covers'] ?? [], true)) {
                return $slug;
            }
        }

        return null;
    }

    /** Where a catalogue service links: its page if it has one, else its line. */
    public static function href(string $service): string
    {
        if ($page = self::pageFor($service)) {
            return route('services.show', $page);
        }

        $line = self::catalogue()[$service]['line'];

        return route('services.show', $line).'#'.$service;
    }

    public static function industries(): array
    {
        return config('revamp.industries', []);
    }

    /*
     | Solution and industry pages (Phase 3). Each list lives in config/revamp.php
     | and each page is a content file in resources/content/{solutions|industries}.
     | A solution whose `gate` service may not render is absent, like a gated
     | service page: on production its URL is a 404.
     */
    public static function solutions(): array
    {
        $out = [];

        foreach (config('revamp.solutions', []) as $slug => $s) {
            $gate = $s[3] ?? null;

            if ($gate !== null && ! self::visible($gate)) {
                continue;
            }

            $out[$slug] = [
                'slug' => $slug,
                'problem' => $s[0],
                'outcome' => $s[1],
                'line' => $s[2],
                'gate' => $gate,
                'preview' => $gate !== null && self::catalogue()[$gate]['preview'],
            ];
        }

        return $out;
    }

    public static function solution(string $slug): ?array
    {
        $entry = self::solutions()[$slug] ?? null;

        return $entry ? self::content('solutions', $slug, $entry) : null;
    }

    public static function industry(string $slug): ?array
    {
        $entry = self::industries()[$slug] ?? null;

        return $entry ? self::content('industries', $slug, ['slug' => $slug, 'preview' => false] + $entry) : null;
    }

    private static function content(string $type, string $slug, array $entry): ?array
    {
        $file = resource_path("content/{$type}/{$slug}.php");

        if (! preg_match('/^[a-z0-9-]+$/', $slug) || ! is_file($file)) {
            return null;
        }

        return (require $file) + $entry;
    }

    /**
     * The catalogue services a page names, as [slug => service + href], keeping
     * only those that may render. A role or reason travels with each.
     */
    public static function services(array $refs): array
    {
        $catalogue = self::catalogue();
        $out = [];

        foreach ($refs as [$slug, $why]) {
            if (self::visible($slug)) {
                $out[$slug] = $catalogue[$slug] + ['why' => $why, 'href' => self::href($slug)];
            }
        }

        return $out;
    }

    /** Solutions and industries whose pages name a service page, for its "see also". */
    public static function linksTo(string $page): array
    {
        $covers = self::pages()[$page] ?? null;
        $slugs = $covers ? array_merge([$covers['gate']], $covers['covers'] ?? []) : [];
        $out = ['solutions' => [], 'industries' => []];

        foreach (['solutions' => self::solutions(), 'industries' => self::industries()] as $type => $list) {
            foreach (array_keys($list) as $slug) {
                $content = $type === 'solutions' ? self::solution($slug) : self::industry($slug);
                $named = array_column($content['services'] ?? [], 0);

                if (array_intersect($named, $slugs)) {
                    $out[$type][$slug] = $content;
                }
            }
        }

        return $out;
    }
}
