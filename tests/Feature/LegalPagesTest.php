<?php

namespace Tests\Feature;

use Database\Seeders\PackageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LegalPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PackageSeeder::class);
    }

    /**
     * We bill for everything in IT ourselves. The site must never send a
     * prospect to another supplier — that is revenue walking out of the door.
     */
    public function test_the_site_never_refers_work_elsewhere(): void
    {
        $phrases = [
            'refer elsewhere',
            'point you at someone',
            'not the right fit',
            'turn work down',
            'someone who can',
        ];

        foreach (['/', '/packages', '/custom-and-it', '/how-it-works', '/demos', '/about'] as $path) {
            $html = $this->get($path)->assertOk()->getContent();

            foreach ($phrases as $phrase) {
                $this->assertStringNotContainsStringIgnoringCase(
                    $phrase,
                    $html,
                    "{$path} sends work to another supplier: \"{$phrase}\"",
                );
            }
        }
    }

    /** The custom/IT page sells one supplier and one bill, not a shortlist. */
    public function test_custom_work_is_sold_as_one_bill(): void
    {
        $this->get('/custom-and-it')
            ->assertOk()
            ->assertSee('it goes on one invoice')
            ->assertSee('One bill for all of it');
    }

    /**
     * POPIA prescribes what a notice has to tell a data subject. These are the
     * elements whose absence would be a contravention, not a style choice.
     */
    public function test_the_privacy_notice_carries_the_popia_disclosures(): void
    {
        $response = $this->get('/privacy')->assertOk();

        foreach ([
            'Protection of Personal Information Act',   // the Act, named
            'responsible party',                        // who is accountable
            'operator',                                 // our role for client systems
            'lawful basis',                             // s11 justification
            'How long we keep it',                      // retention
            'Sending information outside South Africa', // s72
            'Information Regulator',                    // the complaint route
            'complaints.IR@justice.gov.za',
        ] as $needle) {
            $response->assertSee($needle, escape: false);
        }
    }

    /** Statutory rights cannot be drafted away, however the notice is angled. */
    public function test_the_privacy_notice_still_states_the_data_subject_rights(): void
    {
        $this->get('/privacy')
            ->assertOk()
            ->assertSee('ask us to correct or delete')
            ->assertSee('withdraw a consent')
            ->assertSee('object to direct marketing')
            ->assertSee('complain to the Information Regulator');
    }

    /** ECTA s43 obliges us to publish who the customer is contracting with. */
    public function test_the_about_page_publishes_the_ecta_company_disclosures(): void
    {
        $this->get('/about')
            ->assertOk()
            ->assertSee('Electronic Communications and Transactions Act')
            ->assertSee('Registration number')
            ->assertSee('Address for service of legal documents')
            ->assertSee('Description of services')
            ->assertSee('exclude VAT');
    }

    /**
     * The terms and the privacy notice must not contradict each other on
     * retention — a client reading both should get one answer.
     */
    public function test_the_terms_defer_to_the_privacy_notice_on_retention(): void
    {
        $this->get('/terms')
            ->assertOk()
            ->assertSee('within 30 days of termination')
            ->assertSee('privacy notice');
    }
}
