<?php

namespace Tests\Feature;

use App\Support\ProductImages;
use App\Support\Revamp;
use Database\Seeders\AddOnSeeder;
use Database\Seeders\PackageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The services hub, line pages and dedicated service pages (revamp phase 2).
 *
 * Three promises: a page whose service may not be published does not exist in
 * production; every published page answers the seven questions in
 * 05_NAVIGATION_AND_SEO.md §5.4 with its own substance; and nothing on a
 * published page claims a capability, figure or partnership we cannot show.
 */
class ServicePagesTest extends TestCase
{
    use RefreshDatabase;

    private const VERIFIED = [
        'custom-software-development',
        'web-application-development',
        'api-development-and-integration',
        'business-process-automation',
        'crm-systems',
        'accounting-and-invoicing-integrations',
    ];

    private const PREVIEW = ['microsoft-365', 'ai-solutions', 'managed-it-support', 'managed-hosting'];

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

    private function content(string $slug): array
    {
        return require resource_path("content/services/{$slug}.php");
    }

    public function test_the_pages_do_not_exist_unless_the_revamp_is_switched_on(): void
    {
        config(['revamp.home_v2' => false]);

        $this->get('/services')->assertNotFound();
        $this->get('/services/custom-software-development')->assertNotFound();
        $this->get('/services/software-development')->assertNotFound();
    }

    public function test_the_hub_lines_and_verified_pages_render(): void
    {
        $this->get('/services')->assertOk()->assertSee('One team that builds, hosts and supports all of it.');

        foreach (array_keys(Revamp::lines()) as $line) {
            $this->get("/services/{$line}")->assertOk();
        }
        foreach (self::VERIFIED as $slug) {
            $this->get("/services/{$slug}")->assertOk()->assertDontSee('Preview only');
        }
        $this->get('/services/no-such-service')->assertNotFound();
    }

    public function test_production_has_no_unconfirmed_page_or_line_even_with_preview_on(): void
    {
        $this->asProduction();
        config(['revamp.preview_unconfirmed' => true]);

        foreach (self::PREVIEW as $slug) {
            $this->get("/services/{$slug}")->assertNotFound();
        }
        $this->get('/services/microsoft-business-solutions')->assertNotFound();

        $this->get('/services')
            ->assertOk()
            ->assertDontSee('Microsoft 365 Consulting and Administration')
            ->assertDontSee('Managed IT Support')
            ->assertDontSee('Preview only');
    }

    public function test_staging_previews_are_marked_and_kept_out_of_search(): void
    {
        config(['revamp.preview_unconfirmed' => true]);

        foreach (self::PREVIEW as $slug) {
            $html = $this->get("/services/{$slug}")
                ->assertOk()
                ->assertSee('Preview only. This page is not published until capability is confirmed.')
                ->getContent();

            $this->assertStringContainsString('<meta name="robots" content="noindex, nofollow">', $html);
            $this->assertStringNotContainsString('application/ld+json', $html);
        }
    }

    public function test_every_page_answers_the_seven_questions(): void
    {
        foreach (Revamp::pageSlugs() as $slug) {
            $page = $this->content($slug);

            $this->assertArrayHasKey($page['gate'], Revamp::catalogue(), "[{$slug}] gate is not in the catalogue");
            foreach ($page['covers'] as $covered) {
                $this->assertArrayHasKey($covered, Revamp::catalogue(), "[{$slug}] covers an unknown service [{$covered}]");
            }
            foreach (['title', 'description', 'eyebrow', 'h1', 'lede', 'quote_type'] as $key) {
                $this->assertNotEmpty($page[$key], "[{$slug}] needs {$key}");
            }
            $this->assertLessThanOrEqual(155, mb_strlen($page['description']), "[{$slug}] meta description is too long");
            $this->assertArrayHasKey($page['quote_type'], config('revamp.quote_types'), "[{$slug}] quote type");

            $this->assertNotEmpty($page['problem']['body'], "[{$slug}] 1. the business problem");
            $this->assertGreaterThanOrEqual(4, count($page['deliverables']), "[{$slug}] 2. what we deliver");
            $this->assertCount(3, $page['use_cases'], "[{$slug}] 3. typical use cases");
            $this->assertNotEmpty($page['technologies'], "[{$slug}] 4. technologies");
            $this->assertCount(4, $page['approach'], "[{$slug}] 5. delivery approach");
            $this->assertGreaterThanOrEqual(4, count($page['faq']), "[{$slug}] FAQ");

            foreach ($page['related'] as $related) {
                $this->assertFileExists(resource_path("content/services/{$related}.php"), "[{$slug}] relates to a missing page");
            }
        }
    }

    public function test_proof_is_real_or_absent(): void
    {
        foreach (Revamp::pageSlugs() as $slug) {
            $page = $this->content($slug);

            foreach (array_filter([$page['visual']['browser'] ?? null, $page['visual']['phone'] ?? null, $page['proof']['image'] ?? null]) as $image) {
                $this->assertNotNull(ProductImages::get($image), "[{$slug}] uses an undeclared image [{$image}]");
            }
            if (! empty($page['proof']['client'])) {
                $this->assertNotNull(config('clients.'.$page['proof']['client']), "[{$slug}] names a client not in config/clients.php");
            }
            if (in_array($slug, self::PREVIEW, true)) {
                $this->assertArrayNotHasKey('proof', $page, "[{$slug}] is unconfirmed, so it has nothing to prove yet");
            }
        }
    }

    public function test_published_pages_claim_nothing_we_cannot_show(): void
    {
        // Unconfirmed capabilities (06_CAPABILITY_VERIFICATION.md), unmeasured
        // figures, and the backup promise R1 has not earned yet.
        $forbidden = ['Azure', 'AWS', 'SharePoint', 'Power Automate', 'Power BI', 'Microsoft partner', 'certified', '24/7', 'SLA', 'uptime', '99.', 'backed up', 'backups', 'guarantee', 'award', 'leading', 'Invoice Ninja'];

        foreach (self::VERIFIED as $slug) {
            $text = strip_tags($this->get("/services/{$slug}")->assertOk()->getContent());

            foreach ($forbidden as $word) {
                $this->assertDoesNotMatchRegularExpression('/(?<![a-z])'.preg_quote($word, '/').'(?![a-z])/i', $text, "[{$slug}] must not say \"{$word}\"");
            }
        }
    }

    public function test_pages_are_not_thin_copies_of_each_other(): void
    {
        $texts = [];
        foreach (Revamp::pageSlugs() as $slug) {
            $p = $this->content($slug);
            $texts[$slug] = implode(' ', array_merge(
                [$p['h1'], $p['lede']],
                $p['problem']['body'],
                array_merge(...$p['deliverables']),
                array_merge(...$p['use_cases']),
                array_merge(...$p['faq']),
            ));
        }

        $shingles = fn ($t) => collect(str_word_count(strtolower($t), 1))->sliding(5)->map(fn ($w) => $w->implode(' '))->unique()->all();

        foreach ($texts as $a => $ta) {
            // Published pages carry the most; previews are drafts until confirmed.
            $minimum = in_array($a, self::PREVIEW, true) ? 400 : 450;
            $this->assertGreaterThan($minimum, str_word_count($ta), "[{$a}] has too little substance to be a page of its own");
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

    public function test_structured_data_is_valid_on_published_pages(): void
    {
        $html = $this->get('/services/crm-systems')->assertOk()->getContent();

        preg_match('#<script type="application/ld\+json">(.+?)</script>#s', $html, $m);
        $data = json_decode($m[1] ?? '', true);

        $this->assertIsArray($data);
        $this->assertSame(['Service', 'BreadcrumbList', 'FAQPage'], array_column($data, '@type'));
        $this->assertSame('CRM systems', $data[0]['name']);
        $this->assertArrayNotHasKey('offers', $data[0], 'No price in structured data unless one is published');
        $this->assertCount(count($this->content('crm-systems')['faq']), $data[2]['mainEntity']);
    }

    public function test_each_service_links_to_its_page_or_its_place_on_the_line(): void
    {
        $this->assertSame(route('services.show', 'custom-software-development'), Revamp::href('custom-software-development'));
        $this->assertSame(route('services.show', 'custom-software-development'), Revamp::href('legacy-system-modernisation'), 'A covered service links to the page that sells it');
        $this->assertSame(route('services.show', 'business-applications').'#booking-and-scheduling-systems', Revamp::href('booking-and-scheduling-systems'));

        $this->asProduction();
        $this->assertSame(route('services.show', 'cloud-and-infrastructure').'#infrastructure-monitoring', Revamp::href('infrastructure-monitoring'), 'A blocked page never becomes a link target in production');
    }

    public function test_line_anchors_exist_for_every_service(): void
    {
        foreach (Revamp::lines() as $slug => $line) {
            $html = $this->get("/services/{$slug}")->assertOk()->getContent();
            foreach (array_keys($line['services']) as $service) {
                $this->assertStringContainsString('id="'.$service.'"', $html, "/services/{$slug}#{$service} has no target");
            }
        }
    }

    public function test_existing_urls_are_preserved(): void
    {
        foreach (['/', '/packages', '/packages/showroom', '/how-it-works', '/custom-and-it', '/demos', '/demos/auto', '/about', '/terms', '/privacy', '/contact'] as $url) {
            $this->get($url)->assertOk();
        }
    }
}
