<?php

namespace App\Console\Commands;

use App\Support\DemoImages;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

/**
 * Fills the demo tenants with real photographs from Openverse.
 *
 * Openverse indexes openly-licensed media (Flickr, Wikimedia and others). This
 * command requests ONLY licences that permit commercial use, downloads each
 * file, re-encodes it to a sensible size, and serves it from our own origin —
 * we never hotlink, because the site promises it loads nothing from a third
 * party and the demos have to keep that promise too.
 *
 * The photographer, licence and source URL are stored per image in a manifest
 * so the credit that CC BY / CC BY-SA require can render in the demo footer.
 *
 * These are photographs of other people's houses, cars and machines. They are
 * fine for a demo that says in its own ribbon that everything is invented; a
 * real client's site must use photographs of the real thing.
 */
class ImportDemoImages extends Command
{
    protected $signature = 'demos:import-images
                            {--demo= : Limit to one demo, e.g. realty}
                            {--per-demo=7 : How many photographs per demo}
                            {--force : Re-download demos that already have images}
                            {--thumbs : Only (re)build the small copies of images already downloaded; no network}';

    protected $description = 'Download openly-licensed photographs from Openverse for the demo tenants';

    /** Only licences that allow commercial use. Attribution still required for by / by-sa. */
    private const LICENCES = 'cc0,pdm,by,by-sa';

    private const ENDPOINT = 'https://api.openverse.org/v1/images/';

    private const MAX_WIDTH = 1400;

    /**
     * A small copy of every photograph, for cards and phones. Served through
     * srcset, so a 370px card no longer downloads a 1400px original.
     */
    public const THUMB_WIDTH = 720;

    /**
     * Wikimedia — which serves a large share of Openverse results — rejects
     * requests that do not identify themselves. Without this most downloads
     * come back empty and the demos fall back to SVG.
     */
    private const USER_AGENT = 'SevenHillsDigital-DemoImporter/1.0 (+https://7hillsdigital.co.za; hello@7hillsdigital.co.za)';

    /**
     * Search terms per demo. Several per demo so one weak query does not leave
     * a grid half empty, and so the results vary rather than repeating.
     */
    private const QUERIES = [
        'realty' => [
            'modern house exterior', 'suburban family home', 'residential house facade',
            'modern living room interior', 'modern kitchen interior', 'townhouse exterior',
            'contemporary home garden',
        ],
        'auto' => [
            'modern car parked outdoors', 'suv parked road', 'new car side view',
            'pickup truck modern', 'hatchback car parked', 'car showroom modern interior',
            'sports car exterior',
        ],
        'careers' => [
            'modern office workspace', 'business team meeting table', 'coworking desks laptops',
            'bright office interior', 'people working laptops office', 'office collaboration meeting',
            'business handshake meeting',
        ],
        'stay' => [
            'modern hotel room interior', 'hotel bedroom bed linen', 'boutique hotel bedroom',
            'guesthouse patio garden', 'swimming pool resort', 'cottage bedroom interior',
            'hotel breakfast terrace',
        ],
        'plant' => [
            'yellow excavator construction', 'wheel loader construction site', 'bulldozer working site',
            'construction machinery modern', 'crane construction site', 'excavator digging',
            'construction equipment yard',
        ],
    ];

    /**
     * Subjects that keep surfacing in openly-licensed searches and are wrong
     * for a commercial demo: emergency and military vehicles, museum pieces,
     * archive material, wrecks, and anything carrying somebody else's livery.
     * Matched against the title and tags.
     */
    private const BLOCKED = [
        'police', 'fbi', 'sheriff', 'patrol', 'military', 'army', 'navy', 'air force',
        'ambulance', 'fire department', 'fire truck', 'emergency', 'rescue',
        'museum', 'vintage', 'antique', 'historic', 'heritage', 'classic car', 'oldtimer',
        'wreck', 'crash', 'accident', 'junkyard', 'scrap', 'abandoned', 'derelict', 'rust',
        'war', 'protest', 'funeral', 'cemetery', 'parade', 'race', 'rally',
        'taxi', 'bus', 'tram', 'train', 'tractor pull',
        '1920', '1930', '1940', '1950', '1960', '1970',
    ];

    /** Below this mean saturation an image reads as archival black-and-white. */
    private const MIN_SATURATION = 0.12;

    private const MIN_WIDTH = 900;

    public function handle(): int
    {
        $only = $this->option('demo');
        $perDemo = max(1, (int) $this->option('per-demo'));

        $demos = array_keys(config('demos', []));

        if ($only && ! in_array($only, $demos, true)) {
            $this->error("Unknown demo [{$only}]. Known: ".implode(', ', $demos));

            return self::FAILURE;
        }

        foreach ($only ? [$only] : $demos as $demo) {
            if ($this->option('thumbs')) {
                $this->thumbsFor($demo);

                continue;
            }

            $this->importDemo($demo, $perDemo);
        }

        return self::SUCCESS;
    }

    private function importDemo(string $demo, int $wanted): void
    {
        $dir = public_path("images/demos/{$demo}");
        $manifestPath = "{$dir}/credits.json";

        if (File::exists($manifestPath) && ! $this->option('force')) {
            $this->line("  <fg=gray>{$demo}: already has images, skipping (use --force)</>");

            return;
        }

        File::ensureDirectoryExists($dir);

        $this->info("Fetching {$wanted} photographs for {$demo}…");

        $credits = [];
        $index = 0;

        foreach (self::QUERIES[$demo] ?? [] as $query) {
            if (count($credits) >= $wanted) {
                break;
            }

            $fromThisQuery = 0;

            foreach ($this->search($query) as $result) {
                if (count($credits) >= $wanted || $fromThisQuery >= 2) {
                    break;
                }

                if (! $this->subjectIsSuitable($result)) {
                    continue;
                }

                $bytes = $this->download($result['url'] ?? null);

                if ($bytes === null) {
                    continue;
                }

                $index++;
                $file = "{$index}.jpg";

                if (! $this->store($bytes, "{$dir}/{$file}")) {
                    $index--;

                    continue;
                }

                $credits[] = [
                    'file' => $file,
                    'title' => $result['title'] ?? null,
                    'creator' => $result['creator'] ?? null,
                    'license' => strtoupper((string) ($result['license'] ?? '')),
                    'license_version' => $result['license_version'] ?? null,
                    'license_url' => $result['license_url'] ?? null,
                    'source_url' => $result['foreign_landing_url'] ?? null,
                ];

                $fromThisQuery++;
                $this->line("  <fg=green>✓</> {$file}  <fg=gray>{$query}</>");
            }
        }

        if ($credits === []) {
            $this->warn("  {$demo}: nothing usable came back — the SVG fallback stays in place.");

            return;
        }

        File::put($manifestPath, json_encode($credits, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $this->line("  <fg=gray>{$demo}: ".count($credits).' images + credits.json</>');
    }

    /** Reject anything whose title or tags name a blocked subject. */
    private function subjectIsSuitable(array $result): bool
    {
        $tags = array_map(
            fn ($tag) => is_array($tag) ? ($tag['name'] ?? '') : (string) $tag,
            $result['tags'] ?? [],
        );

        $haystack = mb_strtolower(implode(' ', [
            $result['title'] ?? '',
            implode(' ', $tags),
        ]));

        foreach (self::BLOCKED as $word) {
            if (str_contains($haystack, $word)) {
                return false;
            }
        }

        return true;
    }

    /** @return array<int, array<string, mixed>> */
    private function search(string $query): array
    {
        try {
            $response = Http::timeout(25)
                ->retry(2, 400)
                ->withHeaders(['User-Agent' => self::USER_AGENT])
                ->get(self::ENDPOINT, [
                    'q' => $query,
                    'license' => self::LICENCES,
                    'page_size' => 20,
                    'mature' => 'false',
                ]);
        } catch (\Throwable $e) {
            $this->warn("  search failed for [{$query}]: {$e->getMessage()}");

            return [];
        }

        return $response->successful() ? ($response->json('results') ?? []) : [];
    }

    private function download(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        try {
            $response = Http::timeout(40)
                ->withHeaders(['User-Agent' => self::USER_AGENT])
                ->get($url);
        } catch (\Throwable) {
            return null;
        }

        if (! $response->successful()) {
            return null;
        }

        $body = $response->body();

        // Ignore anything implausibly small — usually an error page, not a photo.
        return strlen($body) > 20_000 ? $body : null;
    }

    /** Re-encode to a web-sized JPEG so the demos stay fast. */
    private function store(string $bytes, string $path): bool
    {
        $image = @imagecreatefromstring($bytes);

        if ($image === false) {
            return false;
        }

        $width = imagesx($image);
        $height = imagesy($image);

        /*
         | Openverse leans heavily on archives, so a search for a car returns a
         | lot of 1950s black-and-white. Reject anything that is too small to
         | look sharp, too tall to survive a 4:3 crop, or so desaturated that it
         | is plainly a historical photograph.
         */
        if ($width < self::MIN_WIDTH) {
            imagedestroy($image);

            return false;
        }

        $ratio = $width / max(1, $height);

        if ($ratio < 1.15 || $ratio > 2.2) {
            imagedestroy($image);

            return false;
        }

        if ($this->meanSaturation($image) < self::MIN_SATURATION) {
            imagedestroy($image);

            return false;
        }

        if ($width > self::MAX_WIDTH) {
            $height = (int) round($height * (self::MAX_WIDTH / $width));
            $width = self::MAX_WIDTH;
            $resized = imagescale($image, $width, $height);

            if ($resized !== false) {
                imagedestroy($image);
                $image = $resized;
            }
        }

        $ok = imagejpeg($image, $path, 82);
        imagedestroy($image);

        return $ok && $this->thumb($path);
    }

    private function thumbsFor(string $demo): void
    {
        $made = 0;

        foreach (DemoImages::credits($demo) as $credit) {
            $path = public_path("images/demos/{$demo}/".($credit['file'] ?? ''));

            if (is_file($path) && $this->thumb($path)) {
                $made++;
            }
        }

        $this->line("  {$demo}: {$made} small copies");
    }

    /** Writes `1-720.jpg` beside `1.jpg`. */
    private function thumb(string $path): bool
    {
        $image = @imagecreatefromjpeg($path);

        if ($image === false) {
            return false;
        }

        $width = imagesx($image);
        $height = (int) round(imagesy($image) * (self::THUMB_WIDTH / max(1, $width)));
        $small = $width > self::THUMB_WIDTH ? imagescale($image, self::THUMB_WIDTH, $height) : $image;
        $ok = $small !== false && imagejpeg($small, DemoImages::thumbPath($path), 78);

        imagedestroy($image);

        return $ok;
    }

    /**
     * Mean HSV saturation over a coarse grid. Cheap, and a reliable proxy for
     * "is this a colour photograph or a scanned monochrome one?".
     *
     * @param  \GdImage  $image
     */
    private function meanSaturation($image): float
    {
        $w = imagesx($image);
        $h = imagesy($image);
        $step = max(1, (int) floor(min($w, $h) / 24));

        $total = 0.0;
        $samples = 0;

        for ($y = 0; $y < $h; $y += $step) {
            for ($x = 0; $x < $w; $x += $step) {
                $rgb = imagecolorat($image, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;

                $max = max($r, $g, $b);
                $min = min($r, $g, $b);

                $total += $max === 0 ? 0.0 : ($max - $min) / $max;
                $samples++;
            }
        }

        return $samples === 0 ? 0.0 : $total / $samples;
    }
}
