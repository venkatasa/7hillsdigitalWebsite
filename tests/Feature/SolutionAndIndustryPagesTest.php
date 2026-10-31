<?php

namespace Tests\Feature;

use App\Models\Package;
use App\Support\Revamp;
use Database\Seeders\AddOnSeeder;
use Database\Seeders\PackageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Solution, industry and work pages (revamp phase 3).
 *
 * The same promises as the service pages: a solution whose service may not be
 * published does not exist in production, prices come from the database, demos
 * say they are invented, and nothing published claims what we cannot show.
 */
class SolutionAndIndustryPagesTest extends TestCase
{
    use RefreshDatabase;

    private const GATED_SOLUTION = 'it-that-just-works';

    private const FORBIDDEN = ['Azure', 'AWS', 'SharePoint', 'Power Automate', 'Microsoft partner', 'certified', '24/7', 'SLA', 'uptime', '99.', 'backed up', 'backups', 'guarantee', 'award', 'leading', 'Invoice Ninja', '80%'];

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([PackageSeeder::class, AddOnSeeder::class]);
        config(['revamp.home_v2' => true, 'revamp.preview_unconfirmed' => false]);
    }

    private function asProduction(): void
    {
        $this->app->detectEnvironment(fn () => 'production');
    }

    private function publishedUrls(): array
    {
        return array_merge(
            ['/solutions', '/industries', '/work'],
            array_map(fn ($s) => "/solutions/{$s}", array_keys(Revamp::solutions())),
            array_map(fn ($s) => "/industries/{$s}", array_keys(Revamp::industries())),
        );
    }

    public function test_the_pages_do_not_exist_unless_the_revamp_is_switched_on(): void
    {
        config(['revamp.home_v2' => false]);

        foreach (['/solutions', '/solutions/replace-spreadsheets', '/industries', '/industries/automotive', '/work'] as $url) {
            $this->get($url)->assertNotFound();
        }
    }

    public function test_every_hub_and_page_renders_and_unknown_slugs_are_404(): void
    {
        foreach ($this->publishedUrls() as $url) {
            $this->get($url)->assertOk()->assertDontSee('Preview only');
        }

        $this->get('/solutions/no-such-problem')->assertNotFound();
        $this->get('/industries/no-such-trade')->assertNotFound();
    }

    public function test_a_gated_solution_does_not_exist_in_production_even_with_preview_on(): void
    {
        $this->asProduction();
        config(['revamp.preview_unconfirmed' => true]);

        $this->get('/solutions/'.self::GATED_SOLUTION)->assertNotFound();
        $this->get('/solutions')->assertOk()->assertDontSee('IT is nobody');
        $this->get('/')->assertOk()->assertDontSee('/solutions/'.self::GATED_SOLUTION, false);
    }

    public function test_a_gated_solution_is_a_marked_noindex_preview_on_staging(): void
    {
        config(['revamp.preview_unconfirmed' => true]);

        $html = $this->get('/solutions/'.self::GATED_SOLUTION)
            ->assertOk()
            ->assertSee('Preview only. This page is not published until capability is confirmed.')
            ->getContent();

        $this->assertStringContainsString('<meta name="robots" content="noindex, nofollow">', $html);
        $this->assertStringNotContainsString('application/ld+json', $html);
    }

    public function test_every_solution_is_complete_and_names_services_that_render_in_production(): void
    {
        $this->asProduction();

        foreach (array_keys(config('revamp.solutions')) as $slug) {
            $page = require resource_path("content/solutions/{$slug}.php");

            foreach (['title', 'description', 'h1', 'lede', 'cta', 'close', 'quote_type'] as $key) {
                $this->assertNotEmpty($page[$key], "[{$slug}] needs {$key}");
            }
            $this->assertLessThanOrEqual(155, mb_strlen($page['description']), "[{$slug}] meta description is too long");
            $this->assertArrayHasKey($page['quote_type'], config('revamp.quote_types'));
            $this->assertGreaterThanOrEqual(4, count($page['symptoms']), "[{$slug}] symptoms");
            $this->assertCount(4, $page['shift'], "[{$slug}] what changes");
            $this->assertCount(4, $page['inputs'], "[{$slug}] what we need");
            $this->assertGreaterThanOrEqual(4, count($page['faq']), "[{$slug}] FAQ");

            foreach ($page['services'] as [$service]) {
                $this->assertArrayHasKey($service, Revamp::catalogue(), "[{$slug}] names unknown service [{$service}]");
            }
            foreach ($page['industries'] as $industry) {
                $this->assertArrayHasKey($industry, config('revamp.industries'), "[{$slug}] names unknown industry [{$industry}]");
            }

            if ($slug !== self::GATED_SOLUTION) {
                $this->assertGreaterThanOrEqual(2, count(Revamp::services($page['services'])), "[{$slug}] must be solved by at least two published services");
            }
        }
    }

    public function test_every_industry_links_its_platform_its_demo_and_three_services(): void
    {
        $this->asProduction();

        foreach (Revamp::industries() as $slug => $industry) {
            $page = Revamp::industry($slug);
            $html = $this->get("/industries/{$slug}")->assertOk()->getContent();

            if ($industry['package']) {
                $this->assertNotNull(Package::where('slug', $industry['package'])->first(), "[{$slug}] names a missing package");
                $this->assertStringContainsString(route('packages.show', $industry['package']), $html);
            }
            if ($industry['demo']) {
                $this->assertArrayHasKey($industry['demo'], config('demos'));
                $this->assertStringContainsString(route('demos.show', $industry['demo']), $html);
                $this->assertStringContainsString('an invented business', $html, "[{$slug}] must label its demo as invented");
            }
            if (! empty($page['client'])) {
                $this->assertNotNull(config('clients.'.$page['client']), "[{$slug}] names a client not in config/clients.php");
            }

            $this->assertCount(3, Revamp::services($page['services']), "[{$slug}] needs three published services");
            $this->assertCount(4, $page['day'], "[{$slug}] the day in four moments");
            $this->assertLessThanOrEqual(155, mb_strlen($page['description']), "[{$slug}] meta description is too long");
        }
    }

    public function test_industry_prices_come_from_the_database(): void
    {
        Package::where('slug', 'showroom')->first()->tiers()->update(['price_monthly' => 3333]);

        $this->get('/industries/automotive')->assertOk()->assertSee('R3 333');
        $this->get('/industries')->assertOk()->assertSee('From R3 333');
    }

    public function test_published_pages_claim_nothing_we_cannot_show(): void
    {
        foreach ($this->publishedUrls() as $url) {
            $text = strip_tags($this->get($url)->assertOk()->getContent());

            foreach (self::FORBIDDEN as $word) {
                $this->assertDoesNotMatchRegularExpression('/(?<![a-z])'.preg_quote($word, '/').'(?![a-z])/i', $text, "{$url} must not say \"{$word}\"");
            }
        }
    }

    public function test_pages_are_not_thin_copies_of_each_other(): void
    {
        $texts = [];
        foreach (array_keys(config('revamp.solutions')) as $slug) {
            $p = require resource_path("content/solutions/{$slug}.php");
            $texts["solution:{$slug}"] = implode(' ', array_merge([$p['h1'], $p['lede']], $p['symptoms'], $p['cost']['body'], array_merge(...$p['shift']), array_merge(...$p['inputs']), array_merge(...$p['faq'])));
        }
        foreach (array_keys(config('revamp.industries')) as $slug) {
            $p = require resource_path("content/industries/{$slug}.php");
            $texts["industry:{$slug}"] = implode(' ', array_merge([$p['h1'], $p['lede']], $p['context']['body'], array_merge(...$p['day']), array_column($p['services'], 1), array_merge(...$p['faq'])));
        }

        $shingles = fn ($t) => collect(str_word_count(strtolower($t), 1))->sliding(5)->map(fn ($w) => $w->implode(' '))->unique()->all();

        foreach ($texts as $a => $ta) {
            $this->assertGreaterThan(400, str_word_count($ta), "[{$a}] has too little substance to be a page of its own");
            foreach ($texts as $b => $tb) {
                if ($a >= $b) {
                    continue;
                }
                [$sa, $sb] = [$shingles($ta), $shingles($tb)];
                $overlap = count(array_intersect($sa, $sb)) / max(1, min(count($sa), count($sb)));
                $this->assertLessThan(0.15, $overlap, "[{$a}] and [{$b}] share too much wording ({$overlap})");
            }
        }
    }

    /** A hero headline past about 60 characters wraps to five lines at 1440 and pushes the CTA below the fold. */
    public function test_headlines_stay_short_enough_to_keep_the_cta_in_view(): void
    {
        foreach (['solutions', 'industries'] as $type) {
            foreach (glob(resource_path("content/{$type}/*.php")) as $file) {
                $this->assertLessThanOrEqual(60, mb_strlen((require $file)['h1']), basename($file).' headline is too long');
            }
        }
    }

    public function test_structured_data_is_breadcrumbs_and_faq_only(): void
    {
        foreach (['/solutions/never-lose-a-lead', '/industries/property'] as $url) {
            preg_match('#<script type="application/ld\+json">(.+?)</script>#s', $this->get($url)->getContent(), $m);
            $data = json_decode($m[1] ?? '', true);

            $this->assertSame(['BreadcrumbList', 'FAQPage'], array_column($data, '@type'), $url);
        }
    }

    public function test_the_work_page_labels_everything_for_what_it_is(): void
    {
        $html = $this->get('/work')
            ->assertOk()
            ->assertSee('Excellent Real Estate')
            ->assertSee('InvoiceShelf, self-hosted and maintained by 7Hills.')
            ->assertSee('Back office · staging install, fictional data')
            ->assertSee('Demo · invented')
            ->assertSee('A client&rsquo;s back office.', false)
            ->getContent();

        foreach (array_keys(config('demos')) as $slug) {
            $this->assertStringContainsString(route('demos.show', $slug), $html);
        }
        foreach (['/admin', 'invoice.excellentrealestate'] as $forbidden) {
            $this->assertStringNotContainsString('href="'.(str_starts_with($forbidden, '/') ? url($forbidden) : 'https://'.$forbidden), $html, "Must not link {$forbidden}");
        }
    }

    public function test_navigation_reaches_every_new_page(): void
    {
        $home = $this->get('/')->assertOk()->getContent();
        $services = $this->get('/services/crm-systems')->assertOk()->getContent();

        foreach (array_keys(Revamp::solutions()) as $slug) {
            $this->assertStringContainsString(route('solutions.show', $slug), $home, "No link to solution [{$slug}]");
        }
        foreach (array_keys(Revamp::industries()) as $slug) {
            $this->assertStringContainsString(route('industries.show', $slug), $home, "No link to industry [{$slug}]");
        }
        foreach (['solutions.index', 'industries.index', 'work'] as $route) {
            $this->assertStringContainsString(route($route), $home);
        }
        $this->assertStringContainsString(route('solutions.show', 'never-lose-a-lead'), $services, 'A service page links to the solution it serves');
        $this->assertStringContainsString(route('industries.show', 'automotive'), $services, 'A service page links to an industry that uses it');
    }
}
