<?php

namespace Tests\Feature;

use App\Models\Package;
use Database\Seeders\PackageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PackageSeeder::class);
    }

    public function test_every_public_page_renders(): void
    {
        foreach (['/', '/packages', '/how-it-works', '/custom-and-it', '/demos', '/about', '/contact', '/terms', '/privacy'] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_each_package_has_its_own_page(): void
    {
        foreach (Package::all() as $package) {
            $this->get("/packages/{$package->slug}")
                ->assertOk()
                ->assertSee($package->name)
                ->assertSee($package->tagline);
        }
    }

    public function test_the_home_page_leads_with_the_promise(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Pay nothing until your website is live')
            ->assertSee('Cancel any time');
    }

    public function test_an_unpublished_package_is_not_reachable(): void
    {
        $package = Package::first();
        $package->update(['is_published' => false]);

        $this->get("/packages/{$package->slug}")->assertNotFound();
        $this->get('/packages')->assertOk()->assertDontSee($package->tagline);
    }

    /**
     * Pricing is flat across every product line: the platform underneath is the
     * same, so charging a dealership more than a guesthouse for it is arbitrary
     * and indefensible on a sales call. R880 for any 5, R1 180 for any 10.
     */
    public function test_pricing_is_flat_across_every_line(): void
    {
        foreach (Package::with('tiers')->get() as $package) {
            foreach ($package->tiers as $tier) {
                $expected = $tier->media_gb === 5 ? '880.00' : '1180.00';

                $this->assertSame(
                    $expected,
                    $tier->price_monthly,
                    "{$tier->name} should be R{$expected}",
                );
            }
        }
    }

    /**
     * A package buys the website and nothing else. Email was taken out
     * deliberately, so the packages page must not imply it is bundled.
     */
    public function test_packages_do_not_include_email(): void
    {
        $html = $this->get('/packages')->assertOk()->getContent();

        $this->assertStringContainsString('Website storage', $html);
        $this->assertStringNotContainsString('Email storage', $html);
        $this->assertStringNotContainsString('Email addresses', $html);
        $this->assertStringNotContainsString('unlimited email', strtolower($html));
    }

    /**
     * The brand is "7Hills", matching the logo artwork and the guidelines —
     * never spelt out as "Seven Hills".
     */
    public function test_the_brand_is_written_as_7hills_everywhere(): void
    {
        foreach (['/', '/packages', '/about', '/privacy', '/terms', '/demos', '/demos/realty'] as $path) {
            $html = $this->get($path)->assertOk()->getContent();

            $this->assertStringNotContainsString('Seven Hills', $html, "{$path} still spells the brand out");
            $this->assertStringContainsString('7Hills', $html);
        }
    }

    /** The contact number is real, not the placeholder it replaced. */
    public function test_the_real_contact_number_is_published(): void
    {
        $this->get('/contact')
            ->assertOk()
            ->assertSee('068 859 6695')
            ->assertSee('wa.me/27688596695', escape: false)
            ->assertDontSee('+27 00 000 0000');
    }

    /**
     * The floating WhatsApp button is the main way a prospect starts a
     * conversation from a phone, so it belongs on every page, not just contact.
     */
    public function test_the_whatsapp_button_is_on_every_public_page(): void
    {
        foreach (['/', '/packages', '/packages/showroom', '/custom-and-it', '/demos', '/about', '/contact', '/terms'] as $path) {
            $this->get($path)
                ->assertOk()
                ->assertSee('wa.me/27688596695', escape: false)
                ->assertSee('Chat to 7Hills Digital on WhatsApp');
        }
    }

    /** A demo enquiry should say which demo it came from. */
    public function test_the_demo_whatsapp_message_names_the_demo(): void
    {
        $this->get('/demos/auto')
            ->assertOk()
            ->assertSee(rawurlencode('7Hills Auto demo'), escape: false);
    }

    /** The official 7Hills palette, not the placeholder it replaced. */
    public function test_the_official_brand_palette_is_applied(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('--brand-primary: #1A2E22', escape: false)
            ->assertSee('--brand-accent: #417956', escape: false)
            ->assertSee('--brand-mint: #5EB87E', escape: false);
    }

    /**
     * The privacy notice promises we load nothing from a third party. Fonts are
     * self-hosted and bundled at build time, so no page may reference an
     * external host — if this fails, the privacy notice has become a lie.
     */
    public function test_no_page_loads_a_third_party_asset(): void
    {
        $forbidden = ['fonts.googleapis.com', 'fonts.gstatic.com', 'cdn.jsdelivr.net', 'unpkg.com', 'googletagmanager.com', 'google-analytics.com'];

        foreach (['/', '/packages', '/custom-and-it', '/demos', '/demos/realty', '/contact', '/privacy'] as $path) {
            $html = $this->get($path)->assertOk()->getContent();

            foreach ($forbidden as $host) {
                $this->assertStringNotContainsString($host, $html, "{$path} must not reference {$host}");
            }
        }
    }

    /**
     * The five packages are a starting point, not a limit — the site has to say
     * so, or a prospect whose trade is not listed assumes we cannot help them.
     */
    public function test_the_site_says_we_are_not_limited_to_the_five_packages(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Your industry does not have to be one of the five')
            ->assertSee('You focus on running the business. We will take care of the IT.');

        $this->get('/custom-and-it')
            ->assertOk()
            ->assertSee('Custom software')
            ->assertSee('Automating the repetitive')
            ->assertSee('IT support')
            ->assertSee('Hardware and procurement');
    }

    public function test_prices_come_from_the_database_not_the_markup(): void
    {
        $package = Package::where('slug', 'showroom')->first();
        $package->defaultTier()->update(['price_monthly' => 2222]);

        $this->get('/packages')->assertOk()->assertSee('R2 222');
    }
}
