<?php

namespace Tests\Feature;

use App\Models\Enquiry;
use App\Models\EnquiryEvent;
use App\Models\User;
use Database\Seeders\PackageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use LogicException;
use Tests\TestCase;

/**
 * How a lead arrived, and what happened to it afterwards. Attribution lives in
 * the existing session (no new cookie, referrer reduced to a domain); history
 * is append-only. Both are off unless /quote is switched on.
 */
class LeadAttributionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PackageSeeder::class);
        Mail::fake();
        config(['revamp.leads' => true, 'revamp.preview_unconfirmed' => false]);
    }

    private function quote(array $overrides = []): array
    {
        return array_merge([
            'project_type' => 'other',
            'message' => 'We need a booking system for our training rooms.',
            'budget_band' => 'not-sure',
            'timeframe' => 'exploring',
            'name' => 'Sipho Nkosi',
            'email' => 'sipho@example.co.za',
            'phone' => '0830000000',
            'company_size' => '1-10',
            'preferred_contact' => 'phone',
            'consent' => '1',
        ], $overrides);
    }

    public function test_the_first_page_of_a_visit_is_attributed_to_the_enquiry(): void
    {
        $this->withHeader('Referer', 'https://www.google.com/search?q=custom+software+johannesburg')
            ->get('/packages?utm_source=Newsletter&utm_medium=email&utm_campaign=oct-2026');

        // A later page does not overwrite the first touch.
        $this->withHeader('Referer', 'https://example.org/blog')->get('/about?utm_source=other');

        $this->post('/quote', $this->quote(['from' => 'solution-never-lose-a-lead']))->assertRedirect();

        $e = Enquiry::sole();
        $this->assertSame('/packages', $e->landing_page);
        $this->assertSame('google.com', $e->referrer_host);
        $this->assertSame('newsletter', $e->utm_source);
        $this->assertSame('email', $e->utm_medium);
        $this->assertSame('oct-2026', $e->utm_campaign);
        $this->assertSame('solution-never-lose-a-lead', $e->cta_source);
    }

    public function test_the_referrer_is_a_domain_only_and_bad_tags_are_dropped(): void
    {
        $this->withHeader('Referer', 'http://localhost/contact')->get('/?utm_source=<script>&utm_campaign='.str_repeat('x', 300));
        $this->post('/quote', $this->quote(['from' => 'Not A Valid/Marker']));

        $e = Enquiry::sole();
        $this->assertNull($e->referrer_host, 'Our own pages are not a referrer.');
        $this->assertNull($e->utm_source);
        $this->assertNull($e->utm_campaign);
        $this->assertNull($e->cta_source);
        $this->assertSame('/', $e->landing_page);
    }

    public function test_attribution_adds_no_cookie(): void
    {
        $response = $this->get('/?utm_source=newsletter');

        $names = collect($response->headers->getCookies())->map->getName()->sort()->values()->all();
        $expected = collect(['XSRF-TOKEN', config('session.cookie')])->sort()->values()->all();
        $this->assertSame($expected, $names);
    }

    public function test_the_back_office_is_never_the_landing_page(): void
    {
        $this->get('/admin/login');
        $this->get('/solutions');
        $this->post('/quote', $this->quote());

        $this->assertSame('/solutions', Enquiry::sole()->landing_page);
    }

    public function test_contact_enquiries_are_attributed_too(): void
    {
        $this->get('/packages/showroom?utm_source=facebook');
        $this->post('/contact', [
            'name' => 'Thabo Molefe', 'email' => 'thabo@example.co.za', 'consent' => '1',
            'from' => 'industry-automotive',
        ])->assertRedirect(route('contact.thanks'));

        $e = Enquiry::sole();
        $this->assertSame('subscription', $e->journey);
        $this->assertSame('/packages/showroom', $e->landing_page);
        $this->assertSame('facebook', $e->utm_source);
        $this->assertSame('industry-automotive', $e->cta_source);
    }

    public function test_nothing_new_is_written_when_switched_off(): void
    {
        config(['revamp.leads' => false]);

        $this->get('/packages?utm_source=facebook');
        $this->post('/contact', ['name' => 'Thabo Molefe', 'email' => 'thabo@example.co.za', 'consent' => '1', 'from' => 'header']);

        $e = Enquiry::sole();
        $this->assertNull($e->landing_page);
        $this->assertNull($e->utm_source);
        $this->assertNull($e->cta_source);
        $this->assertSame(0, EnquiryEvent::count(), 'No history rows: production may not have the table.');

        $this->get('/privacy')->assertDontSee('how you arrived');
    }

    public function test_the_privacy_notice_describes_attribution_when_it_is_on(): void
    {
        $this->get('/privacy')->assertOk()
            ->assertSee('its domain only')
            ->assertSee('discarded with the session')
            ->assertSee('two cookies');
    }

    public function test_history_records_receipt_status_changes_and_first_contact(): void
    {
        $this->post('/quote', $this->quote());
        $e = Enquiry::sole();
        $staff = User::factory()->create();
        $this->actingAs($staff);

        $e->update(['status' => 'contacted', 'contacted_at' => now(), 'contacted_by' => $staff->id]);
        $e->update(['internal_notes' => 'Called, sending a proposal.']);
        $e->update(['status' => 'quoted']);

        $events = $e->events()->reorder('id')->get();
        $this->assertSame(['received', 'status_changed', 'contacted', 'status_changed'], $events->pluck('type')->all());
        $this->assertNull($events[0]->user_id, 'The website, not a person, received it.');
        $this->assertSame(['new', 'contacted'], [$events[1]->from, $events[1]->to]);
        $this->assertSame($staff->id, $events[3]->user_id);
    }

    public function test_history_is_append_only(): void
    {
        $this->post('/quote', $this->quote());
        $event = Enquiry::sole()->events()->first();

        $this->expectException(LogicException::class);
        $event->update(['type' => 'status_changed']);
    }

    public function test_history_cannot_be_deleted(): void
    {
        $this->post('/quote', $this->quote());

        $this->expectException(LogicException::class);
        Enquiry::sole()->events()->first()->delete();
    }

    public function test_nurture_and_triage_are_worked_out_on_arrival(): void
    {
        $this->post('/quote', $this->quote());
        $this->assertSame('triage', Enquiry::sole()->priority);
    }
}
