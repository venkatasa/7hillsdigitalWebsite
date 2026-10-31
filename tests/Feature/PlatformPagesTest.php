<?php

namespace Tests\Feature;

use App\Models\Package;
use Database\Seeders\AddOnSeeder;
use Database\Seeders\PackageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The revamped /packages pages ("Platforms"). Restyled, not re-sold: every
 * commercial element of the current pages must survive. PublicSiteTest and
 * AddOnTest cover the rest when the suite runs with the revamp on.
 */
class PlatformPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([PackageSeeder::class, AddOnSeeder::class]);
    }

    public function test_the_revamp_switch_chooses_the_view_and_the_url_never_changes(): void
    {
        config(['revamp.home_v2' => false]);
        $this->get('/packages')->assertOk()->assertViewIs('packages.index');
        $this->get('/packages/stay')->assertOk()->assertViewIs('packages.show');

        config(['revamp.home_v2' => true]);
        $this->get('/packages')->assertOk()->assertViewIs('pages.packages-v2.index')->assertSee('Five platforms, one engine');
        $this->get('/packages/stay')->assertOk()->assertViewIs('pages.packages-v2.show');
    }

    public function test_every_commercial_element_survives_the_restyle(): void
    {
        config(['revamp.home_v2' => true]);
        $brand = config('brand');

        $this->get('/packages')
            ->assertOk()
            ->assertSee($brand['terms']['setup_fee'])
            ->assertSee($brand['terms']['minimum_term'])
            ->assertSee($brand['terms']['notice_period'])
            ->assertSee($brand['terms']['build_time'])
            ->assertSee($brand['bulk']['note'])
            ->assertSee($brand['hardware']['blurb'])
            ->assertSee(str_replace(':company', $brand['name'], $brand['ownership']['buyout']))
            ->assertSee('Every tier, side by side');

        foreach (Package::with('tiers')->get() as $package) {
            $html = $this->get("/packages/{$package->slug}")
                ->assertOk()
                ->assertSee(route('contact', ['package' => $package->slug]), false)
                ->getContent();

            foreach ($package->tiers as $tier) {
                $this->assertStringContainsString($tier->formatted_price, $html, "{$tier->name} price missing");
            }
            foreach ($package->what_you_get as $item) {
                $this->assertStringContainsString(e($item), $html, "[{$package->slug}] lost a feature");
            }
        }
    }

    public function test_a_platform_page_links_its_industry_page(): void
    {
        config(['revamp.home_v2' => true]);

        $this->get('/packages/showroom')->assertOk()->assertSee(route('industries.show', 'automotive'), false);
    }

    /**
     * The restyle drops two promises nothing currently keeps: "daily backups"
     * (off-site backups have failed since 16/10, R1) and the 80% mailbox warning
     * (open decision 5, no storage meter exists).
     */
    public function test_the_restyle_makes_no_promise_we_do_not_keep(): void
    {
        config(['revamp.home_v2' => true]);

        foreach (['/packages', '/packages/listings'] as $url) {
            $text = strip_tags($this->get($url)->getContent());
            $this->assertStringNotContainsStringIgnoringCase('backups', $text, $url);
            $this->assertStringNotContainsString('80%', $text, $url);
        }
    }
}
