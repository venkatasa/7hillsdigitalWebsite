<?php

namespace Tests\Feature;

use App\Models\Enquiry;
use App\Models\Package;
use App\Models\User;
use Database\Seeders\PackageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PackageSeeder::class);
    }

    public function test_the_back_office_is_closed_to_the_public(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/enquiries')->assertRedirect('/admin/login');
        $this->get('/admin/packages')->assertRedirect('/admin/login');
    }

    public function test_staff_can_reach_every_panel_page(): void
    {
        $this->actingAs(User::factory()->create());

        $enquiry = Enquiry::create([
            'name' => 'Thabo Molefe',
            'email' => 'thabo@example.co.za',
            'consent' => true,
            'status' => 'new',
        ]);

        $package = Package::where('slug', 'showroom')->firstOrFail();

        $this->get('/admin')->assertOk();
        $this->get('/admin/enquiries')->assertOk();
        $this->get("/admin/enquiries/{$enquiry->id}/edit")->assertOk();
        $this->get('/admin/packages')->assertOk();
        // Package binds by slug (see getRouteKeyName), so the panel route does too.
        $this->get("/admin/packages/{$package->getRouteKey()}/edit")->assertOk();
    }

    /**
     * Enquiries are evidence of what a prospect told us and consented to.
     * The panel must not offer a way to hand-craft one.
     */
    public function test_enquiries_cannot_be_created_by_hand(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/admin/enquiries/create')->assertNotFound();
    }
}
