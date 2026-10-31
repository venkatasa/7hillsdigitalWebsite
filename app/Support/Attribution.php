<?php

namespace App\Support;

use Illuminate\Http\Request;

/**
 * How a visitor arrived, held in the EXISTING session and copied onto an
 * enquiry when one is made.
 *
 * No new cookie: the privacy notice promises exactly two, and LegalPagesTest
 * holds it to that. The referrer is reduced to its domain, never the full URL,
 * and nothing is kept if no enquiry is made: it goes with the session.
 */
class Attribution
{
    private const KEY = 'attribution';

    /** First touch wins: called on every page view, it records only the first. */
    public static function capture(Request $request): void
    {
        if ($request->session()->has(self::KEY)) {
            return;
        }

        $request->session()->put(self::KEY, array_filter([
            'landing_page' => substr('/'.ltrim($request->path(), '/'), 0, 255),
            'referrer_host' => self::referrerHost($request),
            'utm_source' => self::tag($request->query('utm_source')),
            'utm_medium' => self::tag($request->query('utm_medium')),
            'utm_campaign' => self::tag($request->query('utm_campaign')),
        ]));
    }

    /** The columns to store on an enquiry, plus which button sent them (`from`). */
    public static function forEnquiry(Request $request): array
    {
        if (! Quote::enabled()) {
            return [];
        }

        $from = (string) $request->input('from');

        return array_filter([
            ...$request->session()->get(self::KEY, []),
            'cta_source' => preg_match('/^[a-z0-9-]{1,60}$/', $from) ? $from : null,
        ]);
    }

    /** Another site's domain, or null for our own pages and direct visits. */
    private static function referrerHost(Request $request): ?string
    {
        $host = strtolower((string) parse_url((string) $request->headers->get('referer'), PHP_URL_HOST));

        if ($host === '' || $host === strtolower($request->getHost())) {
            return null;
        }

        return substr(preg_replace('/^www\./', '', $host), 0, 100);
    }

    /** Campaign tags are short labels: anything else is dropped, not stored. */
    private static function tag(mixed $value): ?string
    {
        return is_string($value) && preg_match('/^[A-Za-z0-9._ +-]{1,100}$/', $value)
            ? strtolower($value)
            : null;
    }
}
