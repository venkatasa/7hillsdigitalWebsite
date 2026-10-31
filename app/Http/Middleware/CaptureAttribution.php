<?php

namespace App\Http\Middleware;

use App\Support\Attribution;
use App\Support\Quote;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Records the first public page of a visit (Attribution::capture). Off unless
 * /quote is switched on, and never for the back office, Livewire or assets.
 */
class CaptureAttribution
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Quote::enabled()
            && $request->isMethod('GET')
            && $request->hasSession()
            && ! $request->is('admin', 'admin/*', 'livewire*', 'up', 'build/*', 'images/*')
        ) {
            Attribution::capture($request);
        }

        return $next($request);
    }
}
