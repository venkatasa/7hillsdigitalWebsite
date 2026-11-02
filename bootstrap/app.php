<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Cloudflare reaches us through cloudflared on the prod laptop (.207), which
        // forwards plain HTTP with X-Forwarded-Proto: https. Trust that host only:
        // the origin is also reachable on the LAN, so '*' would let anyone claim https.
        $middleware->trustProxies(
            at: array_filter(explode(',', (string) env('TRUSTED_PROXIES', '192.168.90.207'))),
            headers: Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_PROTO,
        );
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);

        // After StartSession, so the existing session holds it and no cookie is added.
        $middleware->web(append: [\App\Http\Middleware\CaptureAttribution::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
