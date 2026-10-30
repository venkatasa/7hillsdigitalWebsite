<?php

namespace Tests\Feature;

use App\Models\Package;
use App\Support\ProductImages;
use Database\Seeders\AddOnSeeder;
use Database\Seeders\PackageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The v2 homepage prototype must keep every promise the current homepage keeps,
 * and add two of its own: screenshots declare where they came from, and an
 * offering whose capability is unconfirmed never reaches production.
 */
class HomeV2Test extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([PackageSeeder::class, AddOnSeeder::class]);
        config(['revamp.home_v2' => true]);
    }

    public function test_the_prototype_is_off_unless_switched_on(): void
    {
        config(['revamp.home_v2' => false]);

        $this->get('/')->assertOk()->assertDontSee('Technology That');
    }

    public function test_it_renders_both_pathways(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Technology That')
            ->assertSee('Explore Business Platforms')
            ->assertSee('Discuss a Custom Solution');
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
            ->assertSee('Live work')
            ->assertSee('Excellent Real Estate')
            ->assertSee('Invoice Ninja, self-hosted and maintained by 7Hills.')
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

    public function test_unconfirmed_capabilities_are_hidden_by_default(): void
    {
        config(['revamp.preview_unconfirmed' => false]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Custom software development')
            ->assertDontSee('AI-powered applications')
            ->assertDontSee('Preview only');
    }

    public function test_production_never_shows_unconfirmed_capabilities(): void
    {
        config(['revamp.preview_unconfirmed' => true]);
        $this->app->detectEnvironment(fn () => 'production');

        $this->get('/')->assertOk()->assertDontSee('AI-powered applications');
    }

    public function test_staging_can_preview_them_clearly_marked(): void
    {
        config(['revamp.preview_unconfirmed' => true]);

        $this->get('/')
            ->assertOk()
            ->assertSee('AI-powered applications')
            ->assertSee('Preview only.');
    }

    public function test_trust_claims_wait_for_the_work_behind_them(): void
    {
        $this->get('/')->assertOk()->assertDontSee('restore-tested')->assertDontSee('multi-factor');

        config(['revamp.trust.offsite_backups_verified' => true]);

        $this->get('/')->assertOk()->assertSee('restore-tested');
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
            ->assertSee('Demo tenant · fictional business');
    }
}
