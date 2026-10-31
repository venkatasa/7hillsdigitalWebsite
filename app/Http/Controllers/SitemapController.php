<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Support\Quote;
use App\Support\Revamp;
use Illuminate\Http\Response;

/**
 * /sitemap.xml: every public page a search engine should know about, built from
 * the same sources the pages render from, so a page that 404s is never listed.
 *
 * Left out on purpose: the demo tenants (noindex — a fiction must never outrank
 * a real site), thank-you pages (noindex), the back office, and anything shown
 * only as a staging preview, so the file reads the same as it will in production.
 */
class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = [route('home')];
        $urls[] = route('packages.index');

        foreach (Package::published()->orderBy('sort')->pluck('slug') as $slug) {
            $urls[] = route('packages.show', $slug);
        }

        if (config('revamp.home_v2')) {
            $urls[] = route('services.index');

            foreach (array_keys(Revamp::lines()) as $line) {
                $urls[] = route('services.show', $line);
            }

            foreach (Revamp::pages() as $slug => $page) {
                if (! $page['preview']) {
                    $urls[] = route('services.show', $slug);
                }
            }

            $urls[] = route('solutions.index');

            foreach (Revamp::solutions() as $slug => $s) {
                if (! $s['preview'] && Revamp::solution($slug)) {
                    $urls[] = route('solutions.show', $slug);
                }
            }

            $urls[] = route('industries.index');

            foreach (array_keys(Revamp::industries()) as $slug) {
                if (Revamp::industry($slug)) {
                    $urls[] = route('industries.show', $slug);
                }
            }

            $urls[] = route('work');
        }

        foreach (['how-it-works', 'it-services', 'demos', 'about', 'contact', 'terms', 'privacy'] as $name) {
            $urls[] = route($name);
        }

        if (Quote::enabled()) {
            $urls[] = route('quote');
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n"
            .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        foreach (array_unique($urls) as $url) {
            $xml .= '  <url><loc>'.e($url).'</loc></url>'."\n";
        }

        return response($xml.'</urlset>'."\n", 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
