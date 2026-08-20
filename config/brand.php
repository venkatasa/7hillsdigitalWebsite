<?php

/*
|--------------------------------------------------------------------------
| Seven Hills Digital — brand & company details
|--------------------------------------------------------------------------
|
| SINGLE SOURCE OF TRUTH for the site's colours, logo paths and contact
| details, following the same pattern as the Excellent Real Estate build.
| Change values here (or via .env) and the whole public site and the Filament
| admin panel follow.
|
| The palette is a PLACEHOLDER: a deep harbour navy with a teal accent, chosen
| to sit apart from Excellent's charcoal-and-gold so the two sites never look
| like the same agency. Once the real Seven Hills Digital artwork is dropped
| into brand-assets/, replace the six hex values under 'colors' — nothing else
| needs to change.
|
*/

return [

    'name' => env('APP_NAME', 'Seven Hills Digital'),

    'legal_name' => env('COMPANY_LEGAL_NAME', 'Seven Hills Digital (Pty) Ltd'),

    'parent' => env('COMPANY_PARENT', 'Seven Hills Holdings'),

    'tagline' => 'Websites, email and the software to run on them.',

    // The one-line promise the whole business rests on.
    'promise' => 'Pay nothing until your site is live. Cancel any time.',

    /*
    | Brand palette. Consumed in two places:
    |  - resources/css/app.css   (as CSS custom properties, for the public site)
    |  - AdminPanelProvider      (as the Filament primary colour)
    */
    'colors' => [
        // Deep harbour navy — headers, footers, body text.
        'primary' => env('BRAND_PRIMARY', '#0E2436'),
        // Teal — calls to action, prices, accents.
        'accent' => env('BRAND_ACCENT', '#12B3A0'),
        // Slate, for secondary text and surfaces.
        'secondary' => env('BRAND_SECONDARY', '#4A5A6A'),
        'dark' => env('BRAND_DARK', '#081724'),
        'light' => env('BRAND_LIGHT', '#F4F7F9'),
        'muted' => env('BRAND_MUTED', '#8494A1'),
    ],

    /*
    | Logo & imagery. Paths are relative to public/. Drop replacements in and
    | keep the filenames, or change the paths here.
    */
    'logo' => [
        // Seven ascending peaks, rebuilt as vector. Pure geometry, so it stays
        // crisp at any size. The wordmark beside it is set in HTML — see
        // resources/views/components/brand-logo.blade.php.
        'mark' => 'images/brand/logo-mark.svg',

        // Optional overrides: drop the original artwork in at either path and
        // it replaces the composed lockup wholesale.
        'primary' => 'images/brand/logo.png',         // on light backgrounds
        'inverse' => 'images/brand/logo-inverse.png', // on dark backgrounds
    ],

    'contact' => [
        'email' => env('COMPANY_EMAIL', 'hello@7hillsdigital.co.za'),
        'sales_email' => env('COMPANY_SALES_EMAIL', 'sales@7hillsdigital.co.za'),
        'phone' => env('COMPANY_PHONE', '+27 00 000 0000'),
        'whatsapp' => env('COMPANY_WHATSAPP', '+27 00 000 0000'),
        'city' => env('COMPANY_CITY', 'Midrand'),
        'province' => env('COMPANY_PROVINCE', 'Gauteng'),
        'country' => 'South Africa',
    ],

    // Where new package enquiries land.
    'notify_email' => env('SALES_NOTIFY_EMAIL', 'sales@7hillsdigital.co.za'),

    /*
    | Commercial terms. These appear verbatim on the pricing page and in the
    | terms page, so they live in one place and are quoted, never retyped.
    */
    'terms' => [
        'setup_fee' => 'R0',
        'notice_period' => 'One calendar month',
        'minimum_term' => 'None',
        'billing' => 'Monthly in advance, first month billed on go-live',
        'build_time' => '2–3 weeks from receiving your content',
    ],

    /*
    | Storage model. Mail and site media are quoted SEPARATELY on purpose: a
    | dealership with 60 cars and twelve photographs each will use ~2GB of
    | media before a single email is sent. Pooling the two means the photos
    | eat the mail quota and the client phones to say their email is full.
    |
    | Mailbox COUNT is what is metered. Aliases and forwarders are unlimited
    | because they are only routing rules and cost nothing to carry.
    */
    'storage' => [
        'aliases' => 'Unlimited email aliases and forwarders',
        'note' => 'Mail storage and website storage are separate allowances, so your photographs can never fill up your inbox.',
    ],

];
