<?php

namespace App\Http\Controllers;

use App\Models\Package;

class PageController extends Controller
{
    public function home()
    {
        return view('pages.home', [
            'packages' => Package::published()->with('tiers')->orderBy('sort')->get(),
        ]);
    }

    public function howItWorks()
    {
        return view('pages.how-it-works');
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
