<?php

namespace App\Http\Controllers;

use App\Models\Package;

class PackageController extends Controller
{
    public function index()
    {
        return view('packages.index', [
            'packages' => Package::published()->with('tiers')->orderBy('sort')->get(),
        ]);
    }

    public function show(Package $package)
    {
        abort_unless($package->is_published, 404);

        $package->load('tiers');

        return view('packages.show', [
            'package' => $package,
            'others' => Package::published()
                ->whereKeyNot($package->getKey())
                ->orderBy('sort')
                ->get(),
        ]);
    }
}
