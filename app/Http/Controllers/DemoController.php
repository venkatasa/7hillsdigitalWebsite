<?php

namespace App\Http\Controllers;

class DemoController extends Controller
{
    public function show(string $slug)
    {
        $demo = config("demos.{$slug}");

        abort_if($demo === null, 404);

        return view('demos.show', [
            'demo' => $demo,
            'slug' => $slug,
        ]);
    }
}
