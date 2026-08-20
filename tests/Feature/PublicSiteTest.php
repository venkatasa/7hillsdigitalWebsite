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
        foreach (['/', '/packages', '/how-it-works', '/demos', '/about', '/contact', '/terms', '/privacy'] as $path) {
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

    public function test_prices_come_from_the_database_not_the_markup(): void
    {
        $package = Package::where('slug', 'showroom')->first();
        $package->defaultTier()->update(['price_monthly' => 2222]);

        $this->get('/packages')->assertOk()->assertSee('R2 222');
    }
}
