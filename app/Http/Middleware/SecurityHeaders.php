<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Baseline browser security headers on every Laravel response (finding F7, R5).
 *
 * Set here rather than in nginx so a release carries them and the production
 * Docker config stays untouched. No CSP yet: Alpine needs 'unsafe-eval' and
 * Filament has not been tested under one. No HSTS: Cloudflare owns HTTPS.
 */
class SecurityHeaders
{
    public const HEADERS = [
        'X-Content-Type-Options' => 'nosniff',
        'X-Frame-Options' => 'SAMEORIGIN',
        'Referrer-Policy' => 'strict-origin-when-cross-origin',
        'Permissions-Policy' => 'camera=(), microphone=(), geolocation=(), payment=(), usb=()',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        foreach (self::HEADERS as $name => $value) {
            $response->headers->set($name, $value, false);
        }

        return $response;
    }
}
