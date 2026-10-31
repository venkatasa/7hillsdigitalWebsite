<?php

namespace App\Http\Controllers;

use App\Models\AddOn;
use App\Models\Package;
use App\Support\Revamp;

/**
 * Industry landing pages under /industries (revamp phase 3).
 *
 * Each names its platform and its demo where one exists. Prices on them come
 * from the package's live tiers, never from the content file.
 */
class IndustryController extends Controller
{
    public function index()
    {
        abort_unless(config('revamp.home_v2'), 404);

        return view('pages.industries.index', [
            'industries' => collect(Revamp::industries())->keys()->mapWithKeys(fn ($s) => [$s => Revamp::industry($s)])->filter()->all(),
            'packages' => Package::published()->with('tiers')->get()->keyBy('slug'),
        ]);
    }

    public function show(string $slug)
    {
        abort_unless(config('revamp.home_v2'), 404);

        $page = Revamp::industry($slug) ?? abort(404);

        return view('pages.industries.show', [
            'page' => $page,
            'package' => $page['package'] ? Package::published()->with('tiers')->where('slug', $page['package'])->first() : null,
            'invoicing' => AddOn::published()->where('slug', 'invoicing')->first(),
        ]);
    }
}
