<?php

namespace Tests\Feature;

use App\Http\Middleware\SecurityHeaders;
use Database\Seeders\AddOnSeeder;
use Database\Seeders\PackageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * R5: Cloudflare's tunnel on .207 forwards plain HTTP with X-Forwarded-Proto. Without
 * trusting it the live sitemap and og:url said http://, which Search Console would
 * index as a second copy of the site.
 */
class ProxyAndHeadersTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([PackageSeeder::class, AddOnSeeder::class]);
    }

    private function fromTunnel(string $ip = '192.168.90.207')
    {
        return $this->withServerVariables(['REMOTE_ADDR' => $ip])
            ->withHeaders(['X-Forwarded-Proto' => 'https', 'X-Forwarded-For' => '203.0.113.9']);
    }

    public function test_the_tunnel_makes_the_sitemap_and_og_url_https(): void
    {
        $sitemap = $this->fromTunnel()->get('/sitemap.xml')->assertOk()->getContent();
        $this->assertStringContainsString('<loc>https://', $sitemap);
        $this->assertStringNotContainsString('<loc>http://', $sitemap);

        $this->fromTunnel()->get('/packages')->assertOk()
            ->assertSee('property="og:url" content="https://', false);
    }

    public function test_the_visitor_ip_comes_from_the_tunnel_header(): void
    {
        $this->fromTunnel();
        $this->get('/');
        $this->assertSame('203.0.113.9', request()->ip());
    }

    public function test_any_other_lan_host_cannot_claim_https(): void
    {
        $sitemap = $this->fromTunnel('192.168.90.50')->get('/sitemap.xml')->assertOk()->getContent();
        $this->assertStringNotContainsString('<loc>https://', $sitemap);
    }

    public function test_every_response_carries_the_security_headers(): void
    {
        foreach (['/', '/packages', '/sitemap.xml', '/admin/login', '/no-such-page'] as $url) {
            $response = $this->get($url);
            foreach (SecurityHeaders::HEADERS as $name => $value) {
                $response->assertHeader($name, $value);
            }
        }
    }
}
