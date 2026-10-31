<?php

/*
 | Custom Software Development — verified (built_to_order).
 | Evidence: this codebase and its back office; the Excellent Real Estate install.
 | Rules: scenarios are not clients; technologies are only those in our own code;
 | no durations or prices until the owner decides them (decisions Q3, Q6).
 */

return [
    'gate' => 'custom-software-development',
    'covers' => ['enterprise-application-development', 'legacy-system-modernisation', 'database-development', 'software-maintenance-and-support'],
    'motif' => 'code',
    'quote_type' => 'custom-development',

    'title' => 'Custom Software Development in South Africa',
    'description' => 'Custom business software built around how your company works: back offices, portals and line-of-business systems, supported by the team that wrote them.',

    'eyebrow' => 'Custom software development',
    'h1' => 'Software that fits your business, instead of the other way round.',
    'lede' => 'When off-the-shelf products force your team into workarounds, we build the system around your process: back offices, portals and line-of-business tools, hosted and supported by the people who wrote them.',
    'cta' => 'Get a quote for custom software',

    'visual' => ['type' => 'screens', 'browser' => 'admin-dashboard', 'url' => 'yourbusiness.co.za/admin', 'phone' => 'admin-enquiries-mobile'],

    'facts' => [
        ['Built to your requirements', 'Scoped with you, in writing, before work starts'],
        ['One team, start to finish', 'The people who build it host and support it'],
        ['Your data stays yours', 'Exportable, and never shared or sold'],
    ],

    'problem' => [
        'heading' => 'The spreadsheet that runs the business is one bad paste away from a bad week.',
        'body' => [
            'Most growing businesses run on a mix of a product that almost fits, a shared spreadsheet that fills the gaps, and a person who remembers how the two fit together. It works until that person is on leave, the file is overwritten, or the business doubles.',
            'Buying a bigger product rarely fixes it. You pay for modules you will never use, and still bend your process to fit someone else\'s idea of how your industry works.',
            'Custom software turns the process you already have into one system with one source of truth. It does what your business actually does, and nothing it does not.',
        ],
    ],

    'deliverables' => [
        ['Discovery and a written scope', 'We map the process with the people who do the work, then agree in writing what will be built and what will not.'],
        ['A back office for your team', 'Screens for the records your business runs on, with search, filters, roles and a history of who changed what.'],
        ['Portals for customers or suppliers', 'A secure login where outsiders see only their own records, documents and status.'],
        ['Data moved across, not retyped', 'Existing spreadsheets and exports cleaned and imported, so you start with your history.'],
        ['Automated tests', 'The rules that matter most are checked by code on every change, so a fix today does not break last month\'s work.'],
        ['Hosting, support and improvements', 'Run by the team that built it, so nothing depends on a hand-over document.'],
    ],

    'use_cases_title' => 'Where custom software earns its keep.',
    'use_cases' => [
        ['Operations-heavy business', 'Jobs are tracked across three spreadsheets, and nobody trusts which one is current.', 'One job register with statuses, owners and due dates, plus a daily view per team member.'],
        ['Professional practice', 'Client files, approvals and billing live in email threads and shared folders.', 'A matter or client record that holds documents, sign-offs and time, with a client portal on top.'],
        ['Growing distributor', 'An ageing in-house system still works, but only one person can change it.', 'A staged rebuild on a current stack, running alongside the old system until each part is proven.'],
    ],

    'technologies_title' => 'A current, mainstream stack.',
    'technologies' => [
        ['Application', ['Laravel', 'PHP 8', 'Filament']],
        ['Interface', ['Tailwind CSS', 'Alpine.js', 'Responsive web']],
        ['Data', ['MySQL']],
        ['Running it', ['Docker', 'Linux', 'Cloudflare']],
    ],
    'technologies_note' => 'This is the stack our own software runs on. We choose mainstream, well-documented tools so any competent developer could pick the code up after us.',

    'approach' => [
        ['Understand the work', 'Conversations with the people who do the job, not only the people who buy the software. We write down the process as it really is.'],
        ['Agree the scope', 'A written proposal: what will be built, what will not, the order it arrives in, and the price.'],
        ['Build in small, visible steps', 'Working screens you can try early and often, so feedback arrives while it is cheap to act on.'],
        ['Run and improve', 'We host it, support your team and keep it current. Improvements are planned with you, not sprung on you.'],
    ],

    'proof' => [
        'heading' => 'This is the kind of system we build.',
        'body' => 'Our own back office runs the business behind this website: enquiries, packages and prices, all managed without a developer. Shown on a staging install with fictional data. The same engine runs a real estate agency every day.',
        'image' => 'admin-packages',
        'url' => 'yourbusiness.co.za/admin/packages',
        'client' => 'excellent',
    ],

    'faq' => [
        ['How much does custom software cost?', 'It depends on what it has to do, how many people use it and what it connects to. We do not guess from a headline. After a scoping conversation we send a written proposal with the scope and price, before any work starts.'],
        ['Who owns the software and the data?', 'Your data is always yours: you can export it, and we never share or sell it. Who owns the code is agreed in writing in the proposal, before work starts, so there are no surprises later.'],
        ['Can it work alongside the systems we already use?', 'Usually, yes. Many systems can exchange data through an API or a regular export. We check what yours can do during scoping and say plainly if something cannot be connected.'],
        ['What happens after it goes live?', 'We host it, fix what needs fixing and keep it up to date. Improvements are planned with you. The people who wrote the software are the people who support it.'],
        ['How do you handle personal information under POPIA?', 'For systems we run for you, you are the responsible party and we are your operator: we process personal information only on your instructions, keep it secured, and never use it for anything else.'],
    ],

    'close' => 'Describe the process. We will show you the system.',

    'related' => ['web-application-development', 'api-development-and-integration', 'business-process-automation'],
];
