<?php

/*
|--------------------------------------------------------------------------
| 7Hills Digital — brand & company details
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

    'name' => env('APP_NAME', '7Hills Digital'),

    'legal_name' => env('COMPANY_LEGAL_NAME', '7Hills Digital (Pty) Ltd'),

    'parent' => env('COMPANY_PARENT', '7Hills Holdings'),

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
        'muted' => env('BRAND_MUTED', '#64726A'),
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
        'phone' => env('COMPANY_PHONE', '068 859 6695'),
        'whatsapp' => env('COMPANY_WHATSAPP', '068 859 6695'),
        'city' => env('COMPANY_CITY', 'Midrand'),
        'province' => env('COMPANY_PROVINCE', 'Gauteng'),
        'country' => 'South Africa',
    ],

    /*
    | Statutory company details.
    |
    | Section 43 of the Electronic Communications and Transactions Act obliges a
    | supplier offering services on a website to publish its legal name and
    | status, registration number, physical address, an address for the service
    | of legal documents, and contact details. POPIA additionally requires a
    | registered Information Officer to be identified.
    |
    | EVERY VALUE BELOW IS A PLACEHOLDER. They are not invented registration
    | numbers — they are blanks, deliberately, because publishing a fabricated
    | company number would itself be a misrepresentation. Fill them in from the
    | CIPC registration documents before this site handles a real enquiry.
    */
    'legal' => [
        'registration_number' => env('COMPANY_REG_NUMBER', ''),
        'vat_number' => env('COMPANY_VAT_NUMBER', ''),
        'directors' => env('COMPANY_DIRECTORS', ''),
        'place_of_registration' => env('COMPANY_REG_PLACE', 'Republic of South Africa'),
        'physical_address' => env('COMPANY_PHYSICAL_ADDRESS', ''),
        // Where legal process may be served — ECTA s43(1)(f).
        'service_address' => env('COMPANY_SERVICE_ADDRESS', ''),
        'information_officer' => env('COMPANY_INFORMATION_OFFICER', ''),
        'information_officer_email' => env('COMPANY_INFORMATION_OFFICER_EMAIL', ''),
    ],

    // Where new package enquiries land.
    'notify_email' => env('SALES_NOTIFY_EMAIL', 'sales@7hillsdigital.co.za'),

    /*
    | Commercial terms. These appear verbatim on the pricing page and in the
    | terms page, so they live in one place and are quoted, never retyped.
    */
    'terms' => [
        'build_cost' => 'R0 — the build is free',
        'setup_fee' => 'R0',
        'notice_period' => 'One calendar month',
        'minimum_term' => 'None',
        'billing' => 'Monthly in advance, first month billed on go-live',
        'build_time' => '2–3 weeks from receiving your content',
    ],

    /*
    | Who owns what. This is the honest core of a no-upfront offer and it is
    | quoted on the packages page as well as in /terms — a client should not
    | have to reach clause 3 to learn that the software is not theirs.
    */
    'ownership' => [
        'ours' => 'The website itself — the design, the code and the platform it runs on — is built at no charge and stays the property of :company.',
        'yours' => 'Everything you put into it is yours: your listings, your customers, your enquiries, your documents, your email and your domain.',
        'exit' => 'Cancel and we export the lot and hand it over. The site comes down, because the software was never what you were buying.',
        'buyout' => 'If you would rather own the site outright, we will quote you a build-and-own package instead. It costs money upfront — that is the trade for keeping it.',
    ],

    /*
    | Storage model. Mail and site storage are metered SEPARATELY but sized
    | symmetrically: the 5 tier is 5GB of each, the 10 tier is 10GB of each.
    |
    | Keeping them apart is the point. A dealership with 60 cars and twelve
    | photographs each will use gigabytes of media — if that came out of the
    | same pool, the photographs would fill the sales inbox and the client
    | would phone to say their email is broken. Do not pool them.
    |
    | Email ADDRESSES are unlimited: they all draw on the same mail allowance,
    | so counting them earns us nothing and costs the client goodwill.
    */
    'storage' => [
        'aliases' => 'Unlimited email addresses',
        'note' => 'Your email allowance and your website allowance are separate, so the photographs on your site can never fill up your inbox.',
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
