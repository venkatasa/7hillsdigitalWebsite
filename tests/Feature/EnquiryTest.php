<?php

namespace Tests\Feature;

use App\Mail\EnquiryReceived;
use App\Models\Enquiry;
use App\Models\Package;
use Database\Seeders\PackageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EnquiryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PackageSeeder::class);
        Mail::fake();
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Thabo Molefe',
            'company' => 'Molefe Motors',
            'email' => 'thabo@example.co.za',
            'phone' => '0821234567',
            'message' => 'Forty cars on the floor and no website.',
            'consent' => '1',
            'website' => '',
        ], $overrides);
    }

    public function test_an_enquiry_is_stored_and_the_sales_team_is_notified(): void
    {
        $this->post('/contact', $this->payload())
            ->assertRedirect(route('contact.thanks'));

        $enquiry = Enquiry::sole();

        $this->assertSame('Thabo Molefe', $enquiry->name);
        $this->assertSame('new', $enquiry->status);
        $this->assertTrue($enquiry->consent);

        Mail::assertSent(EnquiryReceived::class);
    }

    public function test_popia_provenance_is_captured_at_the_point_of_collection(): void
    {
        $this->withServerVariables(['REMOTE_ADDR' => '196.25.1.1'])
            ->post('/contact', $this->payload());

        $enquiry = Enquiry::sole();

        $this->assertTrue($enquiry->consent);
        $this->assertSame('196.25.1.1', $enquiry->ip_address);
        $this->assertNotNull($enquiry->created_at);
    }

    public function test_consent_is_required(): void
    {
        $this->post('/contact', $this->payload(['consent' => null]))
            ->assertSessionHasErrors('consent');

        $this->assertSame(0, Enquiry::count());
    }

    public function test_the_honeypot_stops_a_bot(): void
    {
        $this->post('/contact', $this->payload(['website' => 'http://spam.example']))
            ->assertSessionHasErrors('website');

        $this->assertSame(0, Enquiry::count());
    }

    public function test_a_tier_from_another_package_is_rejected(): void
    {
        $showroom = Package::where('slug', 'showroom')->first();
        $business = Package::where('slug', 'business')->first();

        $this->post('/contact', $this->payload([
            'package_id' => $showroom->id,
            'package_tier_id' => $business->tiers->first()->id,
        ]))->assertSessionHasErrors('package_tier_id');

        $this->assertSame(0, Enquiry::count());
    }

    public function test_references_are_generated_and_increment(): void
    {
        $this->post('/contact', $this->payload());
        $this->post('/contact', $this->payload(['email' => 'second@example.co.za']));

        $refs = Enquiry::orderBy('id')->pluck('reference')->all();
        $year = now()->year;

        $this->assertSame(["ENQ-{$year}-00001", "ENQ-{$year}-00002"], $refs);
    }

    public function test_a_mail_failure_does_not_cost_us_the_lead(): void
    {
        Mail::shouldReceive('to')->andThrow(new \RuntimeException('SMTP down'));

        $this->post('/contact', $this->payload())
            ->assertRedirect(route('contact.thanks'));

        $this->assertSame(1, Enquiry::count());
    }
}
