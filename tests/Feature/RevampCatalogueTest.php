<?php

namespace Tests\Feature;

use App\Support\Revamp;
use Tests\TestCase;

/**
 * The publication rule behind every service the v3 site shows
 * (docs/revamp/full-service/06_CAPABILITY_VERIFICATION.md).
 */
class RevampCatalogueTest extends TestCase
{
    private function asProduction(): void
    {
        $this->app->detectEnvironment(fn () => 'production');
    }

    public function test_every_service_has_a_known_status(): void
    {
        $catalogue = Revamp::catalogue();

        $this->assertCount(53, $catalogue, 'The 52 services in the brief, plus WhatsApp automation (02/11/2026)');
        foreach ($catalogue as $slug => $service) {
            $this->assertArrayHasKey($service['availability'], Revamp::AVAILABILITY, "[{$slug}] has an unknown status");
        }
    }

    public function test_production_ignores_the_preview_switch(): void
    {
        $this->asProduction();
        config(['revamp.preview_unconfirmed' => true]);

        $this->assertFalse(Revamp::previewing());
        foreach (Revamp::lines() as $line) {
            foreach ($line['services'] as $service) {
                $this->assertFalse($service['preview'], "{$service['name']} must not render in production");
            }
        }
    }

    public function test_the_featured_grid_is_always_eight_and_never_a_preview_in_production(): void
    {
        $this->asProduction();
        $featured = Revamp::featured();

        $this->assertCount(8, $featured);
        $this->assertSame([], array_filter($featured, fn ($s) => $s['preview']));
        $this->assertArrayNotHasKey('microsoft-365-consulting', $featured, 'An unconfirmed service never takes a slot');
        $this->assertSame('ai-business-assistants', array_key_first($featured), 'AI leads the grid');

        config(['revamp.preview_unconfirmed' => true]);
        $this->app->detectEnvironment(fn () => 'staging');
        $this->assertCount(8, Revamp::featured());
    }

    public function test_every_featured_and_fallback_slug_exists_in_the_catalogue(): void
    {
        $catalogue = Revamp::catalogue();

        foreach ([...array_keys(config('revamp.featured')), ...array_keys(config('revamp.fallbacks'))] as $slug) {
            $this->assertArrayHasKey($slug, $catalogue, "[{$slug}] is featured but not in the catalogue");
        }
        foreach (config('revamp.quote_types') as $type => $def) {
            $this->assertArrayHasKey($def[2], $catalogue, "Quote type [{$type}] names an unknown service");
        }
        foreach (config('revamp.headline') as $word => $slug) {
            $this->assertArrayHasKey($slug, $catalogue, "Headline word [{$word}] names an unknown service");
        }
    }

    public function test_blocked_services_stay_blocked_until_the_production_fixes(): void
    {
        // Selling hosting, backup or security while R1–R3 are open is the risk
        // 02_OPPORTUNITY_MATRIX.md §2.2 warns about. Unblock them deliberately.
        foreach (['managed-hosting', 'backup-and-disaster-recovery', 'microsoft-365-security', 'security-assessments'] as $slug) {
            $this->assertSame('blocked', Revamp::catalogue()[$slug]['availability'], "[{$slug}]");
        }
    }

    public function test_quote_types_follow_the_services_they_depend_on(): void
    {
        $this->asProduction();
        $types = array_keys(Revamp::quoteTypes());

        $this->assertContains('custom-development', $types);
        $this->assertContains('ai-automation', $types);
        $this->assertNotContains('microsoft-365', $types);
        $this->assertNotContains('managed-it', $types);
        $this->assertNotContains('security-assessment', $types);
    }
}
