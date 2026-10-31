<?php

namespace App\Http\Controllers;

use App\Models\AddOn;
use App\Models\Package;

class PackageController extends Controller
{
    public function index()
    {
        // The revamp restyles these as "Platforms" without changing what they sell.
        return view(config('revamp.home_v2') ? 'pages.packages-v2.index' : 'packages.index', [
            'packages' => Package::published()->with('tiers')->orderBy('sort')->get(),
            'addOns' => AddOn::published()->orderBy('sort')->get(),
        ]);
    }

    public function show(Package $package)
    {
        abort_unless($package->is_published, 404);

        $package->load('tiers');

        return view(config('revamp.home_v2') ? 'pages.packages-v2.show' : 'packages.show', [
            'package' => $package,
            'others' => Package::published()
                ->whereKeyNot($package->getKey())
                ->orderBy('sort')
                ->get(),
        ]);
    }
}
