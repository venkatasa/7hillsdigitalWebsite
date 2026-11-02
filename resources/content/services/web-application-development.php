<?php

/*
 | Web Application Development — verified (built_to_order).
 | Evidence: our responsive back office and the five demo tenants. We build
 | responsive web applications; native mobile apps are NOT confirmed, and this
 | page must not imply them.
 */

return [
    'gate' => 'web-application-development',
    'covers' => [],
    'motif' => 'browser',
    'quote_type' => 'custom-development',

    'title' => 'Web Application Development in South Africa',
    'description' => 'Secure, browser-based business applications that staff and customers use from any device. Designed, built, hosted and supported by one South African team.',

    'eyebrow' => 'Web application development',
    'h1' => 'One system, on every screen your people use.',
    'lede' => 'Browser-based applications that work on a desktop at the office and a phone on site, with nothing to install, one login per person, and the same data everywhere.',
    'cta' => 'Get a quote for a web application',

    'visual' => ['type' => 'screens', 'browser' => 'demo-careers-desktop', 'url' => '7hillsdigital.co.za/demos/careers', 'phone' => 'demo-careers-mobile'],

    'facts' => [
        ['Nothing to install', 'It runs in the browser your staff already have'],
        ['Works on phones', 'Responsive layouts, tested at phone, tablet and desktop'],
        ['One login per person', 'Each role sees only what it should'],
    ],

    'problem' => [
        'heading' => 'Software that only works on one computer in the office is a bottleneck with a desk.',
        'body' => [
            'Desktop software and shared drives tie work to a place. Staff on the road phone in for information, managers wait for someone to run a report, and customers email for updates a screen could have given them.',
            'A web application moves the work to where people are. The same records, permissions and rules apply on a laptop, a tablet and a phone, and there is one copy of the truth instead of one per machine.',
        ],
    ],

    'deliverables' => [
        ['Responsive interface', 'Layouts designed for phone, tablet and desktop, and checked at each size before release.'],
        ['Roles and permissions', 'Staff, managers, customers and suppliers each see only their own part of the system.'],
        ['Secure sign-in', 'Encrypted connections, hashed passwords and sessions that expire.'],
        ['Search, filters and exports', 'Find any record in seconds, and get it out to a spreadsheet when someone needs one.'],
        ['Accessible by design', 'Keyboard-friendly, readable contrast and clear labels, so the system works for everyone on the team.'],
        ['Fast on ordinary connections', 'Light pages, no third-party trackers, and assets served from our own servers.'],
    ],

    'use_cases' => [
        ['Field team', 'Technicians phone the office to check job details and send photos by WhatsApp.', 'A phone-friendly job view: details, checklist, photo upload and sign-off, synced to the office screen.'],
        ['Customer-facing business', 'Customers email to ask where their order, application or quote stands.', 'A customer login that shows their own status, documents and next steps, around the clock.'],
        ['Multi-branch company', 'Each branch keeps its own spreadsheet, and head office merges them every month.', 'One application with a branch filter, so head office sees everything and each branch sees its own.'],
    ],

    'technologies' => [
        ['Front end', ['React', 'Next.js', 'Vue.js', 'TypeScript', 'Tailwind CSS']],
        ['Back end', ['Python', 'Java', 'Node.js', 'C# and .NET', 'PHP and Laravel']],
        ['AI', ['AI assistants', 'Smart search', 'Document AI']],
        ['Data', ['PostgreSQL', 'MySQL', 'SQL Server', 'Redis']],
        ['Running it', ['AWS', 'Azure', 'Docker', 'Cloudflare']],
    ],
    'technologies_note' => 'These are responsive web applications that run in a browser. If you need an app from the Play Store or App Store, see mobile app development.',

    'approach' => [
        ['Understand the work', 'Who uses it, on what device, and what they need to do in the first ten seconds.'],
        ['Agree the scope', 'A written proposal listing the screens, the roles and what each role can do.'],
        ['Build in small, visible steps', 'Working screens early, tested on real phones as well as desktops.'],
        ['Run and improve', 'Hosting, updates and support from the team that built it.'],
    ],

    'proof' => [
        'heading' => 'Our own back office, on a phone.',
        'body' => 'The enquiry pipeline behind this website, shown on a staging install with fictional data. The same screens work at the desk and on a phone. It is responsive web, not a native app.',
        'image' => 'admin-enquiries',
        'url' => 'yourbusiness.co.za/admin/enquiries',
    ],

    'faq' => [
        ['Is a web application the same as a mobile app?', 'No. A web application runs in the phone\'s browser and needs nothing from an app store. For most business tools it does the job well, with one codebase to maintain. If you need features only a native app can offer, tell us and we will be honest about whether we are the right team.'],
        ['Will it work offline?', 'By default a web application needs a connection. If your team works where signal is poor, raise it during scoping so it can be designed for, or ruled out, up front.'],
        ['How much does it cost?', 'It depends on the number of screens, roles and integrations. After scoping we send a written scope and price before any work starts.'],
        ['Who hosts it?', 'We do, and we keep it updated and supported. Your data stays yours and can be exported.'],
        ['Can it grow as we do?', 'Yes. It is built on mainstream tools in small, well-separated parts, so new screens, roles and integrations can be added without starting again. When the business changes direction, the application is extended to match rather than replaced.'],
    ],

    'close' => 'Tell us who needs to use it, and where.',

    'related' => ['custom-software-development', 'crm-systems', 'api-development-and-integration'],
];
