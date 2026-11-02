<?php

namespace Tests\Feature;

use App\Models\Package;
use App\Support\ProductImages;
use Database\Seeders\AddOnSeeder;
use Database\Seeders\PackageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The v3 homepage must keep every promise the current homepage keeps, and add
 * its own: screenshots declare where they came from, and a service whose
 * capability is unconfirmed, or whose sale is blocked by an open production
 * finding, never reaches production — not in the headline, the menu, the grid,
 * the catalogue or the quote chooser.
 */
class HomeV2Test extends TestCase
{
    use RefreshDatabase;

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

    public function test_the_new_homepage_is_off_unless_switched_on(): void
    {
        config(['revamp.home_v2' => false]);

        $this->get('/')->assertOk()->assertDontSee('One accountable team.');
    }

    public function test_it_offers_the_quote_services_and_platform_journeys(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Get a Quote')
            ->assertSee('Explore Services')
            ->assertSee('See our ready-to-use platforms')
            ->assertSee(route('packages.index'), false);
    }

    public function test_it_keeps_the_current_homepage_promises(): void
    {
        $this->get('/')
            ->assertOk()
            // The commercial promise.
            ->assertSee('Pay nothing until your website is live')
            ->assertSee('Cancel any time')
            // Not limited to five packages.
            ->assertSee('Your industry does not have to be one of the five')
            ->assertSee('You focus on running the business. We will take care of the IT.')
            // Live work, honestly.
            ->assertSee('Our work')
            ->assertSee('Excellent Real Estate')
            ->assertSee('InvoiceShelf, self-hosted and maintained by 7Hills.')
            ->assertDontSee('Invoice Ninja')
            ->assertSee('do not walk prospects through a client', false)
            // The official palette.
            ->assertSee('--brand-primary: #1A2E22', escape: false)
            ->assertSee('--brand-mint: #5EB87E', escape: false);
    }

    public function test_it_never_links_a_client_back_office(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('https://excellentrealestate.co.za', $html);
        foreach (['excellentrealestate.co.za/admin', 'invoice.excellentrealestate.co.za'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $html);
        }
    }

    public function test_it_loads_nothing_from_a_third_party_and_spells_the_brand(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        foreach (['fonts.googleapis.com', 'fonts.gstatic.com', 'cdn.jsdelivr.net', 'unpkg.com', 'googletagmanager.com', 'google-analytics.com'] as $host) {
            $this->assertStringNotContainsString($host, $html);
        }
        $this->assertStringNotContainsString('Seven Hills', $html);
    }

    public function test_prices_come_from_the_database(): void
    {
        Package::where('slug', 'showroom')->first()->defaultTier()->update(['price_monthly' => 2222]);

        $this->get('/')->assertOk()->assertSee('R2 222');
    }

    public function test_the_headline_names_only_capabilities_we_can_show(): void
    {
        $this->asProduction();
        config(['revamp.preview_unconfirmed' => true]);   // ignored in production

        $this->get('/')
            ->assertOk()
            // AI confirmed by the owner 02/11/2026; cloud consulting is not.
            ->assertSee('Software Development, <span class="text-mint">AI</span>', false)
            ->assertDontSee('AI, Cloud');
    }

    public function test_production_leads_with_ai_whatsapp_and_the_wider_technology_range(): void
    {
        // Owner's direction, 02/11/2026: AI everywhere, WhatsApp automation, mobile,
        // and more than one stack. A later edit must not quietly shrink it back.
        $this->asProduction();

        $this->get('/')->assertOk()
            ->assertSee('AI Business Assistants and Chatbots')
            ->assertSee('WhatsApp Automation and AI Assistants')
            ->assertSee('Mobile Application Development')
            ->assertSee('World-class technology')
            ->assertSee(['Python', 'Java', 'Android (Kotlin)', 'iOS (Swift)', 'WhatsApp Business Platform', 'OpenAI GPT']);

        $this->get('/services/whatsapp-automation')->assertOk();
        $this->get('/services/mobile-application-development')->assertOk();
        $this->get('/services/ai-solutions')->assertOk();
    }

    public function test_production_never_renders_an_unconfirmed_or_blocked_service(): void
    {
        $this->asProduction();
        config(['revamp.preview_unconfirmed' => true]);   // ignored in production

        $response = $this->get('/')->assertOk();

        foreach ([
            'Microsoft 365 Consulting and Administration',  // unconfirmed
            'Managed IT Support',                           // unconfirmed
            'Power BI Dashboards',                          // unconfirmed
            'Managed Hosting',                              // blocked by R1–R3
            'Security Assessments',                         // blocked
            'Microsoft Business Solutions',                 // a line with nothing publishable
            'Preview only',
            'Staging preview',
        ] as $text) {
            $response->assertDontSee($text);
        }
    }

    public function test_production_renders_the_services_we_can_deliver(): void
    {
        $this->asProduction();

        $this->get('/')
            ->assertOk()
            ->assertSee('Custom Software Development')
            ->assertSee('API Development and Systems Integration')
            ->assertSee('Business Process Automation')
            ->assertSee('CRM Systems')
            ->assertSee('Accounting and Invoicing Integrations');
    }

    public function test_staging_can_preview_them_clearly_marked(): void
    {
        config(['revamp.preview_unconfirmed' => true]);

        $this->get('/')
            ->assertOk()
            ->assertSee('AI, Cloud')
            ->assertSee('Microsoft 365 Consulting and Administration')
            ->assertSee('Preview only')
            ->assertSee('Staging preview.');
    }

    public function test_every_menu_and_card_link_lands_on_something_that_exists(): void
    {
        foreach ([false, true] as $preview) {
            config(['revamp.preview_unconfirmed' => $preview]);
            $html = $this->get('/')->assertOk()->getContent();

            preg_match_all('/href="(?:'.preg_quote(url('/'), '/').'\/?)?#([a-z0-9-]+)"/', $html, $m);
            $this->assertNotEmpty($m[1]);

            foreach (array_unique($m[1]) as $anchor) {
                $this->assertMatchesRegularExpression('/id="'.preg_quote($anchor, '/').'"/', $html, "#{$anchor} is linked but not on the page");
            }
        }
    }

    public function test_menu_events_reach_alpine_intact(): void
    {
        // Blade reads "@show" as its own directive, so "@show-line.window" would
        // arrive in the browser as "-line.window" and menu links would do nothing.
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('x-on:show-line.window=', $html);
        $this->assertStringContainsString('x-on:show-industry.window=', $html);
        $this->assertDoesNotMatchRegularExpression('/\s-(line|industry)\.window=/', $html);
    }

    public function test_trust_claims_wait_for_the_work_behind_them(): void
    {
        $this->get('/')->assertOk()->assertDontSee('restore-tested')->assertDontSee('multi-factor');
    }

    public function test_every_product_image_declares_its_provenance(): void
    {
        $declared = collect(ProductImages::all())->pluck('file')->map(fn ($f) => basename($f))->all();
        $onDisk = collect(glob(public_path('images/product/*')))
            ->map(fn ($f) => basename($f))
            ->filter(fn ($f) => preg_match('/\.(webp|png|jpe?g|avif)$/i', $f))
            ->values()->all();

        $this->assertNotEmpty($onDisk);
        $this->assertEqualsCanonicalizing($onDisk, $declared, 'Every product image needs a manifest entry, and every entry a file.');

        foreach (ProductImages::all() as $key => $image) {
            $this->assertContains($image['source'], ProductImages::ALLOWED_SOURCES, "[{$key}] has an unknown source");
            $this->assertNotEmpty($image['label'], "[{$key}] must say what it is");
            $this->assertNotEmpty($image['alt'], "[{$key}] needs alt text");
            $this->assertStringNotContainsString('excellentrealestate.co.za/admin', $image['page'] ?? '', "[{$key}] is a client back office");
        }
    }

    public function test_every_screenshot_is_labelled_on_the_page(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Back office · staging install, fictional data')
            ->assertSee('Demo tenant · fictional business')
            ->assertSee('Illustration of a typical flow, not a product screen.');
    }
}
