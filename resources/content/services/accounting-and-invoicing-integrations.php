<?php

/*
 | Accounting and Invoicing Integrations — verified (available): InvoiceShelf
 | deployed and run for Excellent Real Estate; the Invoicing add-on is priced in
 | the database. We did not write InvoiceShelf and must say so. No screenshot of
 | the client's live invoicing system (POPIA). Integrations with named accounting
 | packages are NOT yet delivered — quoted after checking, never claimed.
 */

return [
    'gate' => 'accounting-and-invoicing-integrations',
    'covers' => [],
    'motif' => 'invoice',
    'quote_type' => 'custom-development',

    'title' => 'Invoicing Software and Accounting Integration',
    'description' => 'Quotes, invoices and recurring billing that go out on time from your own email address, hosted for you and connected to the rest of your business.',

    'eyebrow' => 'Invoicing and accounting integration',
    'h1' => 'Get paid on time, without anyone remembering to send the invoice.',
    'lede' => 'Quotes, invoices and recurring billing that go out from your own email address, hosted and supported for you, and connected to the rest of your business when you need it to be.',
    'cta' => 'Get a quote for invoicing',

    'visual' => ['type' => 'constellation', 'motif' => 'invoice', 'items' => ['Quotes', 'Invoices', 'Recurring billing', 'Payments', 'Statements', 'Client portal']],

    'facts' => [
        ['Running for a client today', 'Self-hosted invoicing, live on its own subdomain'],
        ['From your own address', 'Invoices arrive from your business, not a stranger'],
        ['Honest about the software', 'InvoiceShelf: open source, run and supported by us'],
    ],

    'problem' => [
        'heading' => 'Late invoices are a cash-flow problem you create yourself.',
        'source' => 'Xero, The State of South African Small Business 2026: 427 businesses surveyed, January–February 2026.',
        'body' => [
            'In Xero\'s 2026 survey of South African small businesses, 62% reported cash-flow problems in the past year. Some of that is customers paying late. Some of it is invoices going out late, or not at all, because raising them depends on someone finding the time.',
            'An invoicing system that turns accepted quotes into invoices, sends recurring bills on schedule and shows each client what they owe removes the delay you control. Connecting it to the rest of the business removes the retyping.',
        ],
    ],

    'deliverables' => [
        ['Hosted invoicing', 'A self-hosted invoicing system on your own subdomain, branded for your business.'],
        ['Quotes to invoices', 'An accepted quote becomes an invoice without being typed again.'],
        ['Recurring billing', 'Monthly or annual invoices raised and sent on schedule.'],
        ['Mail from your domain', 'Invoices and statements sent from your own email address, with the PDF attached.'],
        ['A client view', 'Customers see their own invoices, payments and balance through a secure login.'],
        ['Connections, where needed', 'Links to your back office or accounting package, scoped and quoted separately.'],
    ],

    'use_cases' => [
        ['Agency or practice', 'Invoices are made in a word processor and emailed by hand at month end.', 'Hosted invoicing with templates, recurring billing and statements, sending from the firm\'s own address.'],
        ['Subscription business', 'Monthly fees are billed from a spreadsheet, and some months are missed.', 'Recurring invoices on a schedule, with a list of who has and has not paid.'],
        ['Business with its own back office', 'Sales are recorded in one system and invoices raised in another.', 'Invoicing connected to the back office, so a completed sale raises its invoice.'],
    ],

    'technologies_title' => 'What it runs on.',
    'technologies' => [
        ['Invoicing', ['InvoiceShelf (open source)']],
        ['Hosting', ['Docker', 'MySQL', 'Cloudflare']],
        ['Connections', ['REST APIs', 'Scheduled exports']],
    ],
    'technologies_note' => 'We did not write InvoiceShelf; we deploy, brand, host and support it. Connections to a specific accounting package depend on what that package exposes, and we confirm them before quoting.',

    'approach' => [
        ['Understand your billing', 'How you quote, when you bill, how customers pay, and who chases.'],
        ['Set it up properly', 'Your branding, templates, tax settings, numbering and your own sending address.'],
        ['Move across', 'Customers and open items brought in, and the team shown how it works.'],
        ['Run and connect', 'Hosted and supported by us, with connections added when they earn their keep.'],
    ],

    'proof' => [
        'heading' => 'Running for a real estate agency today.',
        'body' => 'Excellent Real Estate invoices through a self-hosted install we deployed, branded and run on its own subdomain. We do not show its screens: it is a live system holding their customers\' details.',
        'client' => 'excellent-invoicing',
        'flow' => [['Quote', 'Sent to the customer'], ['Invoice', 'From the accepted quote'], ['Payment', 'Recorded'], ['Statement', 'For the customer']],
    ],

    'faq' => [
        ['What does it cost?', 'The Invoicing module is a monthly add-on to our platforms; the current price is on the packages page. Set-up for a standalone install, and any connection to another system, is scoped and quoted separately.'],
        ['Can it connect to Sage, Xero or Pastel?', 'Possibly. It depends on what your package and subscription allow. We check the product\'s documentation during scoping and only quote for a connection we have confirmed is possible.'],
        ['Did you write the invoicing software?', 'No. It is InvoiceShelf, an open-source application. We deploy, brand, host and support it, and we say so, because you should know exactly what you are buying.'],
        ['Whose data is it?', 'Yours. Your customers, invoices and payment records belong to you, and can be exported if you leave.'],
        ['How long does it take to set up?', 'It depends on how many customers, templates and open items need to move across, and whether a connection to another system is part of the job. The written proposal states what will be set up and in what order before anything starts.'],
    ],

    'close' => 'Bill on time, every time.',

    'related' => ['api-development-and-integration', 'crm-systems', 'business-process-automation'],
];
