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
| Palette, logos and typography all come from the official 7Hills brand kit in
| public/images/brand/ — see Brand_Guidelines_And_Color_Codes.html. Do not
| introduce a colour that is not in that document.
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
    | OFFICIAL 7Hills palette. Consumed in two places:
    |  - resources/css/app.css   (as CSS custom properties, for the public site)
    |  - AdminPanelProvider      (as the Filament primary colour)
    |
    | 'accent' (Emerald Forest) is legible on LIGHT surfaces. 'mint' (Vibrant
    | Mint) is the accent to use on DARK surfaces, where emerald on deep forest
    | would be unreadable. Do not swap them.
    */
    'colors' => [
        // Deep Forest (Corporate Dark) — dark sections, headings.
        'primary' => env('BRAND_PRIMARY', '#1A2E22'),
        // Emerald Forest (Primary) — calls to action and accents on light.
        'accent' => env('BRAND_ACCENT', '#417956'),
        // Vibrant Mint (Accent / Dark Mode) — accents on dark surfaces.
        'mint' => env('BRAND_MINT', '#5EB87E'),
        // Charcoal Black (Ink) — body copy.
        'ink' => env('BRAND_INK', '#111111'),
        'secondary' => env('BRAND_SECONDARY', '#5C6861'),
        'dark' => env('BRAND_DARK', '#122118'),
        'light' => env('BRAND_LIGHT', '#F8FAF9'),
        'muted' => env('BRAND_MUTED', '#7C8A83'),
        'hairline' => env('BRAND_HAIRLINE', '#E2E8E5'),
    ],

    /*
    | Typography, per the brand guidelines: Montserrat for the logotype and
    | headings, Plus Jakarta Sans for body copy and interfaces. Both are
    | self-hosted, bundled at build time by the Vite font plugin — never
    | linked from Google Fonts at runtime. See vite.config.js.
    */
    'fonts' => [
        'display' => 'Montserrat',
        'body' => 'Plus Jakarta Sans',
    ],

    /*
    | Logo & imagery from the official kit. Paths are relative to public/.
    | logo.png and logo-inverse.png are the supplied 2400px transparent
    | artwork with the dead space trimmed off, so the lockup fills its box.
    */
    'logo' => [
        // Primary Boxed — website header and documents. On light backgrounds.
        'primary' => 'images/brand/logo.png',
        // Transparent cutout, all white. On dark backgrounds.
        'inverse' => 'images/brand/logo-inverse.png',
        // Reversed badge on its own deep-forest field, for social/OG cards.
        'badge' => 'images/brand/7Hills_Digital_DarkBG_2400px.png',
        // Fallback vector mark, used only if the artwork above is missing.
        'mark' => 'images/brand/logo-mark.svg',
        'favicon' => 'images/brand/favicon.ico',
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

    /*
    | Hardware procurement. Deliberately a QUIET offering — a single small line
    | on the packages page and one in the footer. It is a service we will
    | happily provide but it is not what we are selling, and giving it real
    | estate makes us look like a reseller rather than a software company.
    */
    'hardware' => [
        'blurb' => 'Need laptops, routers or networking kit? We can procure hardware for you too — just ask.',
    ],

];
