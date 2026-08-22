<?php

namespace Tests\Feature;

use Database\Seeders\PackageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The "Live work" section on the home page is the only place the site shows a
 * real client rather than an invented demo, which makes it the one place where
 * a careless edit leaks a client's private surface to the public web.
 *
 * CLAUDE.md forbids demoing Excellent Real Estate: its back office holds real
 * leads and real applicant ID documents, so walking a prospect through it is a
 * POPIA breach. These tests pin the boundary.
 */
class LiveWorkTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PackageSeeder::class);
    }

    public function test_the_home_page_shows_the_live_client_work(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Live work')
            ->assertSee('Excellent Real Estate')
            ->assertSee('Invoicing');
    }

    public function test_it_links_only_to_the_public_client_site(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('https://excellentrealestate.co.za', $html);

        // Never the panel. Real leads and ID documents live behind it.
        foreach (['excellentrealestate.co.za/admin', 'invoice.excellentrealestate.co.za'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $html, "Must not expose {$forbidden}");
        }
    }

    public function test_it_says_a_client_back_office_is_not_for_showing(): void
    {
        // The section has to carry the reason, not just omit the link — otherwise
        // the next person to edit it simply adds one back.
        $this->get('/')
            ->assertOk()
            ->assertSee('do not walk prospects through a client', false);
    }

    public function test_third_party_software_is_attributed(): void
    {
        // Letting a prospect assume we wrote Invoice Ninja is a lie that lasts
        // exactly until they search for it.
        $this->get('/')
            ->assertOk()
            ->assertSee('Invoice Ninja, self-hosted and maintained by 7Hills.');
    }

    public function test_every_listed_client_declares_whose_software_it_is(): void
    {
        foreach (config('clients') as $key => $client) {
            $this->assertNotEmpty($client['software'] ?? null, "Client [{$key}] must state whose software it is");
            $this->assertNotEmpty($client['summary'] ?? null, "Client [{$key}] needs a summary");
        }
    }
}
