<?php

namespace Tests\Feature;

use App\Models\AddOn;
use App\Models\User;
use Database\Seeders\AddOnSeeder;
use Database\Seeders\PackageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddOnTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PackageSeeder::class);
        $this->seed(AddOnSeeder::class);
    }

    public function test_the_modules_are_offered_on_the_packages_page(): void
    {
        $this->get('/packages')
            ->assertOk()
            ->assertSee('Invoicing')
            ->assertSee('Enterprise email')
            ->assertSee('CRM')
            ->assertSee('ERP');
    }

    /** Modules are a top-up on the package fee, and the page must say so. */
    public function test_modules_are_shown_as_a_top_up_on_the_package_fee(): void
    {
        $this->get('/packages')
            ->assertOk()
            ->assertSee('on top of your monthly')
            ->assertSee('R880 + R109');
    }

    /**
     * A client who finds a module they never asked for on their first invoice
     * is a client who cancels — and cancelling costs them nothing.
     */
    public function test_the_page_says_plainly_that_modules_cost_extra(): void
    {
        $this->get('/packages')
            ->assertOk()
            ->assertSee('charged separately');
    }

    public function test_a_flat_priced_module_shows_its_price(): void
    {
        $this->get('/packages')->assertOk()->assertSee('+R109');
    }

    public function test_a_metered_module_shows_its_basis_not_a_number(): void
    {
        $erp = AddOn::where('slug', 'erp')->firstOrFail();

        $this->assertNull($erp->price_monthly);
        $this->assertTrue($erp->is_metered);

        $this->get('/packages')
            ->assertOk()
            ->assertSee('Quoted on request')
            ->assertSee($erp->price_basis);
    }

    public function test_add_on_prices_come_from_the_database(): void
    {
        AddOn::where('slug', 'invoicing')->update(['price_monthly' => 149]);

        $this->get('/packages')->assertOk()->assertSee('+R149');
    }

    public function test_an_unpublished_module_disappears(): void
    {
        AddOn::where('slug', 'crm')->update(['is_published' => false]);

        $this->get('/packages')->assertOk()->assertDontSee('Quoted on your contact volume');
    }

    public function test_staff_can_edit_the_modules(): void
    {
        $this->actingAs(User::factory()->create());

        $addOn = AddOn::where('slug', 'invoicing')->firstOrFail();

        $this->get('/admin/add-ons')->assertOk();
        $this->get("/admin/add-ons/{$addOn->getRouteKey()}/edit")->assertOk();
    }
}
