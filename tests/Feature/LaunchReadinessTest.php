<?php

namespace Tests\Feature;

use App\Models\Package;
use Database\Seeders\AddOnSeeder;
use Database\Seeders\PackageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Phase 5: the release gate. The smoke list from PRODUCTION_SAFETY_CHECKLIST.md
 * §6 plus every new route, run against the sitemap so a page the site advertises
 * to search engines can never be a 404, and a proofread of every listed page.
 */
class LaunchReadinessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([PackageSeeder::class, AddOnSeeder::class]);
        // What production will run at launch: no staging previews.
        config(['revamp.home_v2' => true, 'revamp.leads' => true, 'revamp.preview_unconfirmed' => false]);
    }

    /** Paths from the sitemap, relative to the app. */
    private function sitemap(): array
    {
        $xml = $this->get('/sitemap.xml')->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->getContent();

        $doc = simplexml_load_string($xml);
        $this->assertNotFalse($doc, 'sitemap.xml is not valid XML');

        $paths = [];
        foreach ($doc->url as $url) {
            $paths[] = parse_url((string) $url->loc, PHP_URL_PATH) ?: '/';
        }

        return $paths;
    }

    public function test_the_smoke_list_and_every_sitemap_page_return_200(): void
    {
        $paths = $this->sitemap();

        // The checklist's own list, whatever the sitemap says.
        $smoke = ['/', '/packages', '/how-it-works', '/custom-and-it', '/demos', '/about', '/terms',
            '/privacy', '/contact', '/quote', '/services', '/solutions', '/industries', '/work', '/admin/login'];
        foreach (Package::published()->pluck('slug') as $slug) {
            $smoke[] = "/packages/{$slug}";
        }
        foreach (array_keys(config('demos')) as $slug) {
            $smoke[] = "/demos/{$slug}";
        }

        foreach (array_unique(array_merge($smoke, $paths)) as $path) {
            $this->get($path)->assertOk();
        }

        $this->assertGreaterThan(30, count($paths));
    }

    public function test_the_sitemap_leaves_out_what_should_not_be_indexed(): void
    {
        $paths = $this->sitemap();

        foreach ($paths as $path) {
            $this->assertDoesNotMatchRegularExpression('#^/(admin|demos/.+|.*thank-you)#', $path, "{$path} should not be in the sitemap");
        }

        $this->assertContains('/services', $paths);
        $this->assertContains('/quote', $paths);

        // A gated service page is a 404 in production, so it is never listed.
        $this->assertNotContains('/services/microsoft-365', $paths);
    }

    public function test_the_sitemap_follows_the_switches(): void
    {
        config(['revamp.home_v2' => false, 'revamp.leads' => false]);
        $paths = $this->sitemap();

        $this->assertNotContains('/services', $paths);
        $this->assertNotContains('/quote', $paths);
        $this->assertContains('/packages', $paths);
    }

    public function test_robots_points_at_the_sitemap_and_keeps_out_of_the_back_office(): void
    {
        $robots = file_get_contents(public_path('robots.txt'));

        $this->assertStringContainsString('Disallow: /admin', $robots);
        $this->assertMatchesRegularExpression('#^Sitemap: https://\S+/sitemap\.xml$#m', $robots);
    }

    public function test_every_listed_page_is_proofread(): void
    {
        foreach ($this->sitemap() as $path) {
            $html = $this->get($path)->getContent();
            $text = strip_tags(preg_replace('#<(script|style)\b.*?</\1>#s', '', $html));

            $this->assertSame(1, preg_match_all('#<h1\b#', $html), "{$path} needs exactly one h1");
            $this->assertMatchesRegularExpression('#<title>[^<]{10,}</title>#', $html, "{$path} has no title");
            $this->assertMatchesRegularExpression('#<meta name="description" content="[^"]{50,}"#', $html, "{$path} has a thin description");
            $this->assertStringContainsString('rel="canonical"', $html, $path);

            $this->assertStringNotContainsString('Seven Hills', $text, $path);
            $this->assertDoesNotMatchRegularExpression('/lorem ipsum|\bTODO\b|\bTBC\b|\{\{|Note for 7Hills/i', $text, "{$path} has placeholder or internal text");

            // The group name only where the law wants it.
            if (! in_array($path, ['/terms', '/privacy', '/about'], true)) {
                $this->assertStringNotContainsString('Holdings', $text, "{$path} names the holding company");
            }
        }
    }

    public function test_every_page_shares_one_header_and_footer(): void
    {
        foreach (['/about', '/demos', '/how-it-works', '/custom-and-it', '/contact', '/terms', '/privacy'] as $path) {
            $this->get($path)->assertOk()->assertSee('id="mobile-sheet"', false);
        }

        // With the revamp off, the live layout is untouched.
        config(['revamp.home_v2' => false]);
        $this->get('/about')->assertOk()->assertDontSee('id="mobile-sheet"', false);
    }

    public function test_only_the_first_screen_fonts_are_preloaded(): void
    {
        $html = $this->get('/')->getContent();

        $this->assertSame(2, preg_match_all('#<link rel="preload" as="font"#', $html));
        $this->assertStringContainsString('montserrat-800', $html);
        $this->assertStringContainsString('plus-jakarta-sans-400', $html);
    }

    public function test_the_whatsapp_button_sits_in_a_landmark(): void
    {
        $this->get('/')->assertSee('<aside aria-label="WhatsApp"', false);
    }

    public function test_demo_photographs_offer_a_small_copy_from_our_own_origin(): void
    {
        $html = $this->get('/demos/realty')->assertOk()->getContent();

        $this->assertMatchesRegularExpression('#srcset="'.preg_quote(url('/images/demos/realty'), '#').'/1-720\.jpg 720w, [^"]+1\.jpg 1400w"#', $html);
    }

    public function test_the_whatsapp_button_rests_in_place_before_any_script_runs(): void
    {
        // Positioned only through Alpine, it sat at the top and then jumped: layout shift on every page.
        $this->get('/')->assertSee('wa-float fixed bottom-5 right-5', false);
    }
}
