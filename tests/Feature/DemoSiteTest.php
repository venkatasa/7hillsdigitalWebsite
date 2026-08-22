<?php

namespace Tests\Feature;

use App\Models\Package;
use Database\Seeders\PackageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class DemoSiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PackageSeeder::class);
    }

    /**
     * Data providers run before the application boots, so the config
     * repository is not available yet — read the file itself.
     */
    public static function demoSlugs(): array
    {
        $demos = require __DIR__.'/../../config/demos.php';

        return array_map(fn ($slug) => [$slug], array_keys($demos));
    }

    #[DataProvider('demoSlugs')]
    public function test_each_demo_tenant_renders(string $slug): void
    {
        $demo = config("demos.{$slug}");

        $this->get("/demos/{$slug}")
            ->assertOk()
            ->assertSee($demo['name'])
            ->assertSee($demo['hero_title'])
            ->assertSee($demo['items'][0]['title']);
    }

    public function test_an_unknown_demo_is_not_found(): void
    {
        $this->get('/demos/there-is-no-such-business')->assertNotFound();
    }

    /**
     * A demo must never be mistaken for a real business, or outrank a real
     * client's site in search.
     */
    #[DataProvider('demoSlugs')]
    public function test_every_demo_declares_itself_a_demo_and_stays_out_of_search(string $slug): void
    {
        $this->get("/demos/{$slug}")
            ->assertOk()
            ->assertSee('Demonstration site')
            ->assertSee('noindex, nofollow', escape: false);
    }

    /** The point of a demo is the conversation about the back office. */
    #[DataProvider('demoSlugs')]
    public function test_every_demo_invites_a_back_office_walkthrough(string $slug): void
    {
        $this->get("/demos/{$slug}")
            ->assertOk()
            ->assertSee('Show me the back office');
    }

    /** A demo must never collect a real person's details. */
    #[DataProvider('demoSlugs')]
    public function test_the_demo_enquiry_form_is_inert(string $slug): void
    {
        $html = $this->get("/demos/{$slug}")->assertOk()->getContent();

        $this->assertStringNotContainsString('<form method="POST"', $html);
        $this->assertStringContainsString('disabled', $html);
    }

    public function test_demo_prices_track_the_real_package_price(): void
    {
        Package::where('slug', 'showroom')->first()->defaultTier()->update(['price_monthly' => 1234]);

        $this->get('/demos/auto')->assertOk()->assertSee('R1 234');
    }

    public function test_the_demos_index_links_to_every_tenant(): void
    {
        $response = $this->get('/demos')->assertOk();

        foreach (array_keys(config('demos')) as $slug) {
            $response->assertSee("/demos/{$slug}", escape: false);
        }
    }

    public function test_a_package_page_links_to_its_own_demo(): void
    {
        $this->get('/packages/showroom')->assertOk()->assertSee('/demos/auto', escape: false);
    }
}
