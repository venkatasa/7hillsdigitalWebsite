<?php

namespace App\Http\Controllers;

use App\Models\AddOn;
use App\Models\Package;
use App\Support\Revamp;

/**
 * The services hub, the seven line pages and the dedicated service pages.
 *
 * Part of the revamp, so served only where REVAMP_HOME_V2 is on (staging). A line
 * with nothing publishable, or a page whose service may not be published, is a
 * 404 — the same answer as a URL that never existed.
 */
class ServiceController extends Controller
{
    public function index()
    {
        abort_unless(config('revamp.home_v2'), 404);

        return view('pages.services.index', [
            'lines' => Revamp::lines(),
            'pages' => Revamp::pages(),
        ]);
    }

    public function show(string $slug)
    {
        abort_unless(config('revamp.home_v2'), 404);

        $lines = Revamp::lines();

        if (isset($lines[$slug])) {
            return view('pages.services.line', [
                'line' => $lines[$slug],
                'lines' => $lines,
            ]);
        }

        $page = Revamp::page($slug) ?? abort(404);

        return view('pages.services.show', [
            'page' => $page,
            'line' => $lines[Revamp::catalogue()[$page['gate']]['line']] ?? null,
            'packages' => Package::published()->with('tiers')->orderBy('sort')->get(),
            'addOns' => AddOn::published()->orderBy('sort')->get(),
        ]);
    }
}
