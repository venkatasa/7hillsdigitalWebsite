<?php

/*
 | API Development and Systems Integration — verified (built_to_order), partial
 | evidence: APIs inside our own software. We have NOT yet delivered an
 | integration with a third-party accounting package, so no proof section and no
 | named accounting products as experience.
 */

return [
    'gate' => 'api-development-and-integration',
    'covers' => [],
    'motif' => 'nodes',
    'quote_type' => 'custom-development',

    'title' => 'API Development and Systems Integration',
    'description' => 'Connect your website, back office, accounting and email so data is entered once. API development and system integration for South African businesses.',

    'eyebrow' => 'API development and systems integration',
    'h1' => 'Enter it once. Let your systems pass it on.',
    'lede' => 'We connect the systems you already pay for, so a lead, an order or an invoice moves between them by itself instead of being retyped by a person.',
    'cta' => 'Get a quote for an integration',

    'visual' => ['type' => 'diagram', 'name' => 'hub'],

    'facts' => [
        ['Data entered once', 'Not copied between screens by hand'],
        ['Documented', 'Every connection written down, so nothing depends on one person'],
        ['Watched', 'A failed transfer raises an alert instead of disappearing'],
    ],

    'problem' => [
        'heading' => 'Every time a person copies data between two systems, the business pays twice and gets it wrong sometimes.',
        'body' => [
            'A web enquiry is retyped into the sales system. An accepted quote is retyped into accounting. A new customer is added to the mailing list by hand, or not at all. Each step costs time, and each is a chance for a typo that surfaces months later as a dispute.',
            'Integration removes the copying. When systems exchange data directly, the business runs on one version of each record, and people spend their time on the work only people can do.',
            'Integration also tells you more about your business. When data flows between systems by itself, you can see where an order is held up, which leads turn into sales and how long it takes to get paid, without asking three people to assemble a report.',
        ],
    ],

    'deliverables' => [
        ['An integration audit', 'What you run, what each system can share, and which connection saves the most time first.'],
        ['APIs for your own systems', 'Clean, documented endpoints so your software can talk to partners, suppliers or future tools.'],
        ['Connectors between products', 'Scheduled or real-time transfers between systems that offer an API or a reliable export.'],
        ['Webhooks and notifications', 'Events in one system trigger action in another: a paid invoice updates the customer record, for example.'],
        ['Error handling and alerts', 'Failed transfers are retried, logged and reported to a person, never silently dropped.'],
        ['Documentation', 'What connects to what, why, and how to change it, written for the next person.'],
    ],

    'use_cases' => [
        ['Sales-led business', 'Website enquiries arrive by email and are retyped into a sales tracker.', 'Enquiries go straight into the pipeline with their source recorded, and the right person is notified.'],
        ['Service business', 'Accepted quotes are re-entered as invoices in a separate accounting package.', 'An accepted quote creates the invoice, and payment status flows back to the customer record.'],
        ['Supplier network', 'Partners email spreadsheets of orders that someone imports every morning.', 'A documented API the partners send orders to, validated on arrival.'],
    ],

    'technologies' => [
        ['Interfaces', ['REST and JSON', 'GraphQL', 'Webhooks', 'WhatsApp Cloud API', 'Scheduled imports and exports']],
        ['Building it', ['Python', 'Java and Spring Boot', 'Node.js', 'C# and .NET', 'PHP and Laravel', 'Message queues']],
        ['Data', ['PostgreSQL', 'MySQL', 'SQL Server', 'MongoDB']],
    ],
    'technologies_note' => 'Whether two products can be connected depends on what each one exposes. We confirm that during the audit, before quoting, and say plainly if a connection is not possible.',

    'approach' => [
        ['Audit what you run', 'List the systems, what each can share, and where people are retyping today.'],
        ['Pick the first connection', 'Start with the one that removes the most retyping, and agree it in writing.'],
        ['Build, test, switch over', 'Run the connection alongside the manual process until the numbers match, then retire the copying.'],
        ['Watch and extend', 'Alerts on failures, then the next connection when the first has proved itself.'],
    ],

    'faq' => [
        ['Can you connect our accounting package?', 'If it offers an API or a dependable export, usually yes. We check its documentation and your subscription level during the audit and confirm before quoting.'],
        ['What happens if one system is down?', 'Transfers are queued and retried. If one keeps failing, a person is alerted. Nothing is silently lost.'],
        ['Will the integration break when a product updates?', 'Products do change their APIs from time to time. We monitor the connections we run and adapt them. That is part of supporting an integration, not an extra.'],
        ['Is our data safe in transit?', 'Connections are encrypted, credentials are stored securely and never in code, and only the fields that are needed are passed across.'],
        ['Do we need to replace any of our systems first?', 'Usually not. Integration is often the cheaper alternative to replacing a system that mostly works. If a product genuinely cannot share its data, we say so during the audit, and replacing it becomes a separate conversation with its own costs and benefits laid out.'],
    ],

    'close' => 'Which two systems should be talking?',

    'related' => ['business-process-automation', 'custom-software-development', 'accounting-and-invoicing-integrations'],
];
