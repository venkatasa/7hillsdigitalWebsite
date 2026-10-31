<?php

namespace Tests\Feature;

use App\Filament\Resources\Enquiries\Pages\EditEnquiry;
use App\Filament\Widgets\LeadKpis;
use App\Models\Enquiry;
use App\Models\User;
use Database\Seeders\PackageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * How an enquiry ends (07_LEAD_GENERATION.md §7.7): a discovery stage, a reason
 * for every loss, a win split once-off against monthly, and the monthly figures
 * read from the append-only history.
 */
class PipelineTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PackageSeeder::class);
        Mail::fake();
        config(['revamp.leads' => true]);
        $this->actingAs(User::factory()->create());
    }

    private function enquiry(array $attributes = []): Enquiry
    {
        return Enquiry::create($attributes + [
            'journey' => 'project', 'project_type' => 'custom-development', 'priority' => 'standard',
            'name' => 'Ayanda Zulu', 'email' => 'ayanda@example.co.za', 'consent' => true,
        ]);
    }

    public function test_a_lost_lead_needs_a_reason(): void
    {
        $e = $this->enquiry();

        Livewire::test(EditEnquiry::class, ['record' => $e->getRouteKey()])
            ->fillForm(['status' => 'lost'])
            ->call('save')
            ->assertHasFormErrors(['lost_reason' => 'required']);

        Livewire::test(EditEnquiry::class, ['record' => $e->getRouteKey()])
            ->fillForm(['status' => 'lost', 'lost_reason' => 'timing'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('timing', $e->fresh()->lost_reason);
    }

    public function test_a_win_records_once_off_and_monthly_value(): void
    {
        $e = $this->enquiry();

        Livewire::test(EditEnquiry::class, ['record' => $e->getRouteKey()])
            ->fillForm(['status' => 'won', 'won_once_off' => 45000, 'won_monthly' => 3500])
            ->call('save')
            ->assertHasNoFormErrors();

        $e->refresh();
        $this->assertSame('45000.00', $e->won_once_off);
        $this->assertSame('3500.00', $e->won_monthly);
    }

    public function test_the_prospects_words_still_cannot_be_rewritten(): void
    {
        $e = $this->enquiry(['message' => 'What they actually said.']);

        Livewire::test(EditEnquiry::class, ['record' => $e->getRouteKey()])
            ->fillForm(['status' => 'discovery', 'message' => 'Something else entirely.'])
            ->call('save');

        $e->refresh();
        $this->assertSame('discovery', $e->status);
        $this->assertSame('What they actually said.', $e->message);
    }

    public function test_the_outcome_fields_are_hidden_when_quote_is_off(): void
    {
        config(['revamp.leads' => false]);
        $e = $this->enquiry();

        // Production before the migration: the columns do not exist, so the form must not offer them.
        Livewire::test(EditEnquiry::class, ['record' => $e->getRouteKey()])
            ->fillForm(['status' => 'lost'])
            ->assertFormFieldIsHidden('lost_reason')
            ->call('save')
            ->assertHasNoFormErrors();
    }

    public function test_monthly_revenue_counts_in_the_month_it_was_won(): void
    {
        $this->travelTo(now()->subMonthNoOverflow()->startOfMonth()->addDays(3));
        $old = $this->enquiry();
        $old->update(['status' => 'won', 'won_monthly' => 9999]);
        $this->travelBack();

        $e = $this->enquiry(['priority' => 'monthly']);
        $e->update(['contacted_at' => now()->addHours(2), 'status' => 'quoted']);
        $e->update(['status' => 'won', 'won_monthly' => 4200, 'won_once_off' => 12000]);

        Livewire::test(LeadKpis::class)
            ->assertSee('New monthly revenue won')
            ->assertSee('R4 200')
            ->assertDontSee('R9 999')
            ->assertSee('R12 000')
            ->assertSee('2 h');
    }

    public function test_the_figures_are_hidden_when_quote_is_off(): void
    {
        config(['revamp.leads' => false]);
        $this->assertFalse(LeadKpis::canView());
    }
}
