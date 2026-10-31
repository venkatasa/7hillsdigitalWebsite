<?php

namespace Tests\Feature;

use App\Mail\EnquiryReceived;
use App\Models\Enquiry;
use App\Models\User;
use App\Support\Quote;
use Database\Seeders\PackageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * /quote: project enquiries (07_LEAD_GENERATION.md §7.2–7.4). The same rules
 * as /contact, plus: a gated type can be neither shown nor stored, and every
 * answer must come from the chosen type's own lists.
 */
class QuoteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PackageSeeder::class);
        Mail::fake();
        config(['revamp.leads' => true, 'revamp.preview_unconfirmed' => false]);
    }

    private function payload(array $overrides = []): array
    {
        return array_replace_recursive([
            'project_type' => 'custom-development',
            'message' => 'Our job cards live in a shared spreadsheet that four people edit.',
            'answers' => ['custom-development' => ['replacing' => 'spreadsheets', 'audience' => 'staff']],
            'budget_band' => '50k-150k',
            'timeframe' => '1-3-months',
            'name' => 'Lerato Dlamini',
            'company' => 'Example Engineering',
            'email' => 'lerato@example.co.za',
            'phone' => '0820000000',
            'company_size' => '11-50',
            'preferred_contact' => 'email',
            'from' => 'header',
            'consent' => '1',
            'website' => '',
        ], $overrides);
    }

    public function test_quote_is_a_404_when_switched_off(): void
    {
        config(['revamp.leads' => false]);

        $this->get('/quote')->assertNotFound();
        $this->post('/quote', $this->payload())->assertNotFound();
        $this->get('/quote/thank-you')->assertNotFound();
        $this->assertSame(0, Enquiry::count());
    }

    public function test_a_project_enquiry_is_stored_and_the_team_is_notified(): void
    {
        $this->post('/quote', $this->payload())->assertRedirect(route('quote.thanks'));

        $e = Enquiry::sole();
        $this->assertSame('project', $e->journey);
        $this->assertSame('custom-development', $e->project_type);
        $this->assertSame(['replacing' => 'spreadsheets', 'audience' => 'staff'], $e->answers);
        $this->assertSame('new', $e->status);
        $this->assertTrue($e->consent);
        $this->assertNotNull($e->ip_address);
        $this->assertSame('standard', $e->priority);
        $this->assertSame('header', $e->cta_source);
        $this->assertMatchesRegularExpression('/^ENQ-\d{4}-\d{5}$/', $e->reference);

        Mail::assertSent(EnquiryReceived::class, function (EnquiryReceived $mail) {
            return str_contains($mail->envelope()->subject, 'New project enquiry')
                && str_contains($mail->envelope()->subject, 'Custom software · Standard');
        });

        $this->get(route('quote.thanks'))->assertOk()->assertSee($e->reference)->assertSee('by email');
    }

    public function test_a_mail_failure_does_not_cost_us_the_lead(): void
    {
        Mail::shouldReceive('to')->andThrow(new \RuntimeException('SMTP down'));

        $this->post('/quote', $this->payload())->assertRedirect(route('quote.thanks'));
        $this->assertSame(1, Enquiry::count());
    }

    public function test_only_the_chosen_types_answers_are_kept(): void
    {
        $this->post('/quote', $this->payload([
            'answers' => ['cloud-infrastructure' => ['where' => 'aws', 'goal' => 'move']],
        ]))->assertRedirect();

        $this->assertSame(['replacing' => 'spreadsheets', 'audience' => 'staff'], Enquiry::sole()->answers);
    }

    public function test_an_answer_the_form_never_offered_is_rejected(): void
    {
        $this->post('/quote', $this->payload(['answers' => ['custom-development' => ['replacing' => 'a-mainframe']]]))
            ->assertSessionHasErrors('answers.custom-development.replacing');

        $this->post('/quote', $this->payload(['answers' => ['custom-development' => ['audience' => null]]]))
            ->assertSessionHasErrors('answers.custom-development.audience');

        $this->assertSame(0, Enquiry::count());
    }

    public function test_the_budget_band_must_belong_to_the_type(): void
    {
        // A monthly band on a project type: the form never pairs these.
        $this->post('/quote', $this->payload(['budget_band' => '15k-40k-pm']))
            ->assertSessionHasErrors('budget_band');

        // "Not sure yet" is always allowed and never penalised.
        $this->post('/quote', $this->payload(['budget_band' => 'not-sure']))->assertSessionHasNoErrors();
    }

    public function test_the_description_is_required_only_where_the_type_needs_it(): void
    {
        $this->post('/quote', $this->payload(['message' => '']))->assertSessionHasErrors('message');

        $this->post('/quote', $this->payload([
            'project_type' => 'cloud-infrastructure',
            'message' => '',
            'answers' => ['cloud-infrastructure' => ['where' => 'office-server', 'goal' => 'monitor']],
            'budget_band' => 'under-20k',
        ]))->assertSessionHasNoErrors();
    }

    public function test_consent_honeypot_and_required_contact_details(): void
    {
        $this->post('/quote', $this->payload(['consent' => null]))->assertSessionHasErrors('consent');
        $this->post('/quote', $this->payload(['website' => 'http://spam.example']))->assertSessionHasErrors('website');
        $this->post('/quote', $this->payload(['phone' => '', 'company_size' => 'huge']))
            ->assertSessionHasErrors(['phone', 'company_size']);
        $this->post('/quote', $this->payload(['email' => 'not-an-email']))->assertSessionHasErrors('email');

        $this->assertSame(0, Enquiry::count());
    }

    public function test_a_gated_type_is_neither_shown_nor_stored_in_production(): void
    {
        // Even with the preview switch on: production ignores it.
        config(['revamp.preview_unconfirmed' => true]);
        $this->app['env'] = 'production';
        // Outside "testing" Laravel enforces CSRF; this test is about validation.
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\PreventRequestForgery::class);

        $this->get('/quote')->assertOk()
            ->assertDontSee('Managed IT support')
            ->assertDontSee('Security assessment')
            ->assertSee('Something else');

        $this->post('/quote', $this->payload([
            'project_type' => 'managed-it',
            'answers' => ['managed-it' => ['users' => '26-50', 'devices' => '26-50', 'current' => 'none']],
            'budget_band' => '5k-15k-pm',
        ]))->assertSessionHasErrors('project_type');

        $this->assertSame(0, Enquiry::count());
    }

    public function test_staging_previews_a_gated_type_and_marks_it(): void
    {
        config(['revamp.preview_unconfirmed' => true]);

        $this->get('/quote?type=managed-it')->assertOk()
            ->assertSee('Managed IT support')
            ->assertSee('Preview only');

        $this->post('/quote', $this->payload([
            'project_type' => 'managed-it',
            'message' => '',
            'answers' => ['managed-it' => ['users' => '26-50', 'devices' => '26-50', 'current' => 'none']],
            'budget_band' => '5k-15k-pm',
        ]))->assertSessionHasNoErrors();

        $this->assertSame('monthly', Enquiry::sole()->priority);
    }

    public function test_priority_follows_the_agreed_rules(): void
    {
        $this->assertSame('triage', Quote::priority('other', [], '500k+', 'asap'));
        $this->assertSame('monthly', Quote::priority('managed-it', ['users' => '11-25'], 'not-sure', 'exploring'));
        $this->assertSame('monthly', Quote::priority('microsoft-365', ['users' => '250+'], 'under-20k', '3-6-months'));
        $this->assertSame('standard', Quote::priority('managed-it', ['users' => '1-10'], '5k-15k-pm', '3-6-months'));
        $this->assertSame('high', Quote::priority('managed-it', ['users' => '1-10'], '15k-40k-pm', '3-6-months'));
        $this->assertSame('high', Quote::priority('custom-development', [], '150k-500k', '3-6-months'));
        $this->assertSame('high', Quote::priority('custom-development', [], 'under-50k', 'asap'));
        $this->assertSame('standard', Quote::priority('cloud-infrastructure', [], '75k-250k', '1-3-months'));
        $this->assertSame('nurture', Quote::priority('ai-automation', [], 'not-sure', 'exploring'));
    }

    public function test_the_form_works_without_javascript_and_promises_nothing_unset(): void
    {
        $html = $this->get('/quote')->assertOk()->getContent();

        foreach (['What do you need?', 'About the work', 'Budget and timing', 'Your details'] as $step) {
            $this->assertStringContainsString($step, $html);
        }

        // Every offered type's questions are in the page, so nothing depends on JS.
        foreach (Quote::types() as $slug => $type) {
            $this->assertStringContainsString('value="'.$slug.'"', $html);
            foreach (array_keys(Quote::type($slug)['questions']) as $id) {
                $this->assertStringContainsString("answers[{$slug}][{$id}]", $html);
            }
        }

        // The POPIA notice sits above the consent tick.
        $this->assertLessThan(strpos($html, 'name="consent"'), strpos($html, 'How we use these details'));

        // Q4 and Q5 are open: no response-time promise and no "free".
        $this->assertDoesNotMatchRegularExpression('/working day|within \d+ hours|free consultation|\bfree\b/i', strip_tags($html));
    }

    public function test_a_preset_type_is_selected_and_an_unknown_one_ignored(): void
    {
        $this->get('/quote?type=ai-automation')->assertOk()
            ->assertSee('value="ai-automation" required', false)
            ->assertSee('step: 2', false);

        $this->get('/quote?type=nonsense')->assertOk()->assertSee('step: 1', false);
    }

    public function test_quote_links_fall_back_to_contact_when_switched_off(): void
    {
        $this->assertStringContainsString('/quote?type=custom-development&from=menu', Quote::url('menu', 'software-development'));
        $this->assertStringContainsString('/quote?from=header', Quote::url('header', 'managed-it'));

        config(['revamp.leads' => false]);
        $this->assertStringContainsString('/contact?type=project', Quote::url('header'));
    }

    public function test_the_panel_shows_the_project_read_only(): void
    {
        $this->post('/quote', $this->payload());
        $e = Enquiry::sole();

        $this->actingAs(User::factory()->create());

        $this->get("/admin/enquiries/{$e->id}/edit")->assertOk()
            ->assertSee('The project')
            ->assertSee('Is it replacing something?')->assertSee('Spreadsheets')
            ->assertSee('How they found us')
            ->assertSee('History');

        $this->get('/admin/enquiries')->assertOk()->assertSee('Custom software');
    }
}
