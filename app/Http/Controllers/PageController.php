<?php

namespace App\Http\Controllers;

use App\Models\AddOn;
use App\Models\Package;

class PageController extends Controller
{
    public function home()
    {
        // The v2 homepage is a prototype for visual approval, served only where
        // REVAMP_HOME_V2 is switched on (staging). See config/revamp.php.
        return view(config('revamp.home_v2') ? 'pages.home-v2' : 'pages.home', [
            'packages' => Package::published()->with('tiers')->orderBy('sort')->get(),
            'addOns' => AddOn::published()->orderBy('sort')->get(),
        ]);
    }

    public function howItWorks()
    {
        return view('pages.how-it-works');
    }

    public function itServices()
    {
        return view('pages.it-services');
    }

    public function demos()
    {
        return view('pages.demos', [
            'packages' => Package::published()->orderBy('sort')->get(),
        ]);
    }

    public function about()
    {
        return view('pages.about');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }
}
