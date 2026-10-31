<?php

namespace App\Http\Controllers;

use App\Support\Revamp;

/**
 * Business-problem pages under /solutions (revamp phase 3).
 *
 * Served only where REVAMP_HOME_V2 is on. A solution gated on a service that may
 * not render here is a 404, the same answer as a URL that never existed.
 */
class SolutionController extends Controller
{
    public function index()
    {
        abort_unless(config('revamp.home_v2'), 404);

        return view('pages.solutions.index', [
            'solutions' => collect(Revamp::solutions())->map(fn ($s) => Revamp::solution($s['slug']))->filter()->all(),
        ]);
    }

    public function show(string $slug)
    {
        abort_unless(config('revamp.home_v2'), 404);

        return view('pages.solutions.show', [
            'page' => Revamp::solution($slug) ?? abort(404),
        ]);
    }
}
