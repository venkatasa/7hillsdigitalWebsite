<?php

/*
|--------------------------------------------------------------------------
| Revamp prototype — switches and interim content
|--------------------------------------------------------------------------
|
| The v2 homepage is a PROTOTYPE for visual approval. It renders only where
| REVAMP_HOME_V2=true (staging). Everywhere else — production, the test suite
| by default — the current homepage is served unchanged.
|
| The offerings and industries below are interim content. The implementation
| plan moves them into the database (Offering, Industry) in Phase 2, with the
| availability status edited in /admin. Until then they live here, and the
| same rules apply:
|
|  - `availability` is shown on the page, so it cannot drift from what is real.
|  - `capability_unconfirmed` items are NEVER rendered in production. On
|    staging, REVAMP_PREVIEW_UNCONFIRMED=true shows them with a dashed
|    "Preview only" marker so the owner can judge the design — nothing more.
|
*/

return [

    'home_v2' => (bool) env('REVAMP_HOME_V2', false),

    'preview_unconfirmed' => (bool) env('REVAMP_PREVIEW_UNCONFIRMED', false),

    /*
    | Trust claims that depend on operational work. A claim whose precondition
    | is not yet true is hidden, not softened. See PRODUCTION_SAFETY_CHECKLIST.md.
    */
    'trust' => [
        'offsite_backups_verified' => false,   // R1
        'staff_mfa' => false,                  // R6
    ],

    /*
    | Enterprise and technology services (category C, plus the custom end of B).
    | `visual` is either a screenshot key from public/images/product/manifest.json
    | or a diagram name rendered by <x-ui.diagram>.
    */
    'services' => [
        'custom-software-development' => [
            'name' => 'Custom software development',
            'availability' => 'built_to_order',
            'headline' => 'Software built around how your business actually works.',
            'benefits' => [
                'Line-of-business applications, back offices and admin panels',
                'Your process on a screen, instead of a workaround around a product',
                'Hosted, maintained and supported by the team that built it',
            ],
            'engagement' => 'Discovery sprint, then fixed-scope phases with a demo every two weeks.',
            'visual' => 'admin-packages',
            'visual_caption' => 'Our own software — the back office where these platforms and their prices are managed, on staging.',
        ],
        'business-process-automation' => [
            'name' => 'Business process automation',
            'availability' => 'built_to_order',
            'headline' => 'Automate the job someone repeats every Friday.',
            'benefits' => [
                'Reminders, follow-ups and hand-offs that run on their own',
                'Approvals routed to the right person, with a record of who said yes',
                'Spreadsheets retired, not copied into a new tool',
            ],
            'engagement' => 'Map one process end to end, automate it, measure the hours saved, then the next.',
            'visual' => 'diagram:flow',
            'visual_caption' => 'Illustration — how an automated approval moves through a business.',
        ],
        'systems-integration' => [
            'name' => 'Systems integration',
            'availability' => 'built_to_order',
            'headline' => 'Make the systems you already pay for talk to each other.',
            'benefits' => [
                'Your website, back office, accounting and email in one flow',
                'Data entered once, not retyped into three systems',
                'APIs and webhooks documented, so nothing depends on one person',
            ],
            'engagement' => 'Audit what you run today, then connect the two systems that cost you the most retyping.',
            'visual' => 'diagram:hub',
            'visual_caption' => 'Illustration — one integration layer between the tools a business already uses.',
        ],
        'ai-business-applications' => [
            'name' => 'AI-powered applications',
            'availability' => 'capability_unconfirmed',
            'headline' => 'AI that does a job in your business, not a demo.',
            'benefits' => [
                'Drafting, summarising and classifying the documents you already handle',
                'Answers grounded in your own records, with a person in the loop',
                'POPIA-aware: what goes to a model, and what never does, decided up front',
            ],
            'engagement' => 'One narrow use case, measured against the manual way, before anything grows.',
            'visual' => 'diagram:layers',
            'visual_caption' => 'Illustration — where an AI step sits between your data and your people.',
        ],
        'microsoft-365-sharepoint' => [
            'name' => 'Microsoft 365 & SharePoint',
            'availability' => 'capability_unconfirmed',
            'headline' => 'Get more from the Microsoft 365 you already pay for.',
            'benefits' => [
                'SharePoint sites and document libraries people actually use',
                'Power Automate flows for approvals and notifications',
                'Permissions that match how the business is organised',
            ],
            'engagement' => 'Tidy what exists, then automate the first two workflows.',
            'visual' => 'diagram:hub',
            'visual_caption' => 'Illustration — documents, approvals and people around one workspace.',
        ],
        'mobile-app-development' => [
            'name' => 'Mobile applications',
            'availability' => 'capability_unconfirmed',
            'headline' => 'A mobile app your staff or customers will actually use.',
            'benefits' => [
                'Field teams: job cards, photos and sign-off on site',
                'Customers: bookings, statements and updates in their pocket',
                'Connected to the same back office as your website',
            ],
            'engagement' => 'Start with the one screen people need most, ship it, then grow.',
            'visual' => 'admin-enquiries-mobile',
            'visual_caption' => 'Our back office on a phone (responsive web, staging, fictional data) — not a native app.',
        ],
        'business-intelligence' => [
            'name' => 'Business intelligence & reporting',
            'availability' => 'capability_unconfirmed',
            'headline' => 'The numbers that run your business, on one screen.',
            'benefits' => [
                'Dashboards built from the systems you already run',
                'One version of the truth for sales, cash and operations',
                'Reports that arrive on time, instead of being assembled on Friday',
            ],
            'engagement' => 'Agree five numbers that matter, then build the dashboard around them.',
            'visual' => 'diagram:chart',
            'visual_caption' => 'Illustration — a reporting layer over operational data.',
        ],
    ],

    /*
    | Industry switcher. `package` and `demo` link the existing platforms and
    | demo tenants; industries without either are served by Business Solutions.
    */
    'industries' => [
        'automotive' => [
            'name' => 'Automotive dealerships',
            'short' => 'Automotive',
            'package' => 'showroom',
            'demo' => 'auto',
            'pains' => ['Stock online the hour it arrives', 'Every finance enquiry in one pipeline', 'Trade-in requests with photos attached'],
        ],
        'property' => [
            'name' => 'Property & real estate',
            'short' => 'Property',
            'package' => 'listings',
            'demo' => 'realty',
            'pains' => ['Listings your agents publish themselves', 'Buyer enquiries that never sit in a phone', 'Rental applications with documents, in one place'],
        ],
        'recruitment' => [
            'name' => 'Recruitment & staffing',
            'short' => 'Recruitment',
            'package' => 'careers',
            'demo' => 'careers',
            'pains' => ['Vacancies live in minutes', 'CVs captured, not lost in an inbox', 'Applicants tracked from first contact to placement'],
        ],
        'hospitality' => [
            'name' => 'Hospitality',
            'short' => 'Hospitality',
            'package' => 'stay',
            'demo' => 'stay',
            'pains' => ['Direct bookings without the commission', 'Rooms, rates and seasons you control', 'Guest enquiries answered from one inbox'],
        ],
        'professional-services' => [
            'name' => 'Professional services',
            'short' => 'Professional',
            'package' => 'business',
            'demo' => null,
            'pains' => ['Client intake that is not an email thread', 'Documents and approvals with an audit trail', 'Billing that goes out on time'],
        ],
        'corporate-operations' => [
            'name' => 'Corporate operations',
            'short' => 'Corporate',
            'package' => null,
            'demo' => null,
            'pains' => ['Staff portals and self-service', 'Approval workflows that replace paper', 'Reporting across systems that do not talk'],
        ],
    ],

];
