<?php

/*
 | Industry: professional services. Platform: Business (the core engine with
 | configurable labels). Demo: 7Hills Plant Hire runs on the same engine but is a
 | different trade, so it is offered as "the engine at work", not as this industry.
 */

return [
    'quote_type' => 'custom-development',

    'title' => 'Websites and Client Software for Professional Services Firms',
    'description' => 'Client intake that is not an email thread, secure document collection, and billing that goes out on time. For attorneys, accountants and consultancies.',

    'h1' => 'Client intake that is not an email thread.',
    'lede' => 'For attorneys, accountants, consultancies and practices: a website that takes a new matter properly, collects the documents you need securely, and hands both to a back office your team works in.',
    'cta' => 'Talk about your practice',
    'demo_note' => 'There is no professional-services demo. The Plant Hire demo runs on the same Business engine, configured for a different trade.',

    'context' => [
        'heading' => 'A new client should not begin with six emails.',
        'body' => [
            'In most practices, a new client starts as an email, then a phone call, then a request for documents, then a reminder about the documents, then a PDF that cannot be opened. Each step is small. Together they make a poor first impression and cost fee-earners time they cannot bill.',
            'Smaller firms feel this most. Without a practice manager or an IT department, the partners end up chasing documents and forwarding emails themselves, which is the most expensive time in the building spent on the least valuable work.',
            'Professional firms also hold some of the most sensitive information there is: identity documents, financial statements, details of disputes. Collecting it by email scatters it across inboxes. A secure intake keeps it in one access-controlled place from the first day.',
        ],
    ],

    'day' => [
        ['A prospective client makes contact', 'A general email, forwarded until it reaches the right person.', 'An enquiry form that routes by matter type to the right person.'],
        ['Documents are needed', 'Requested by email, chased by phone, received in pieces.', 'Uploaded through secure document intake, against the right client.'],
        ['Work is under way', 'Status updates by phone, on request.', 'A tracked pipeline the team can see at a glance.'],
        ['The work is billed', 'Invoices prepared in a batch at month-end.', 'Invoices from accepted quotes, with the Invoicing add-on.'],
    ],

    'services' => [
        ['customer-self-service-portals', 'A client portal: documents, status and messages in one place instead of email.'],
        ['workflow-and-approval-automation', 'Internal sign-offs, conflict checks and reviews, routed by rule with a record.'],
        ['accounting-and-invoicing-integrations', 'Quotes, invoices and recurring fees, available today as our Invoicing add-on.'],
    ],

    'solutions' => ['replace-spreadsheets', 'get-paid-on-time'],

    'faq' => [
        ['Is this a practice management system?', 'Not out of the box. Business handles your public site, intake, documents and enquiry pipeline. If you need matter management or time recording beyond that, we either connect to the product you use or build it, and quote it separately.'],
        ['Is document upload secure enough for client records?', 'Files are uploaded over an encrypted connection and stored privately, visible only to signed-in staff. Retention follows the policy you set, in line with POPIA and your professional rules.'],
        ['Can we list our team and practice areas?', 'Yes. Team, branch and service pages are part of the platform, and you edit them yourselves.'],
        ['What does it cost?', 'Business is a flat monthly fee with the build included and no minimum term. The current price is on this page, straight from our price list. A client portal or workflow is quoted separately.'],
    ],

    'close' => 'How does a new client reach you today?',
];
