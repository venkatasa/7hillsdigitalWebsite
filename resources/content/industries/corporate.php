<?php

/*
 | Industry: corporate operations. No platform and no demo: this is custom work,
 | priced on scope. Only built_to_order services are named, so nothing here
 | depends on an unconfirmed capability.
 */

return [
    'quote_type' => 'custom-development',

    'title' => 'Custom Business Software for Corporate Operations Teams',
    'description' => 'Staff portals, approval workflows and reporting across systems that do not talk: custom internal software for operations teams in larger businesses.',

    'h1' => 'Internal systems for work your software does not cover.',
    'lede' => 'For operations, HR and finance teams in larger businesses: staff portals, approval workflows and connections between the systems you already run, built around how your organisation actually works.',
    'cta' => 'Talk about your operations',
    'visual' => [['Request', 'From a staff portal'], ['Route', 'By your rules'], ['Approve', 'With a record'], ['Report', 'Across systems']],

    'context' => [
        'heading' => 'The gaps between enterprise systems are filled by people.',
        'body' => [
            'Larger businesses run on big products: payroll, accounting, a CRM, perhaps an ERP. Each does its job. The work that falls between them, requests, approvals, reconciliations, reports that combine figures from three places, is done by people with spreadsheets and email.',
            'Those gaps are where time disappears and errors start. They are also hard to fix with another off-the-shelf product, because they exist precisely because the organisation works in its own way. That is where a small, well-built internal system earns its keep.',
            'Larger organisations also carry obligations a small business can sometimes get away without: segregation of duties, evidence for auditors, access that follows a joiner, mover and leaver process. An internal system can enforce those rules every time, instead of relying on people remembering them at the end of a long week.',
        ],
    ],

    'day' => [
        ['A staff member needs something', 'An email to HR or finance, then a follow-up.', 'A request through a staff portal, with its status visible.'],
        ['Someone must approve it', 'Printed, signed and scanned, or lost.', 'Routed by rule to the right approver, with reminders.'],
        ['Finance needs the figures', 'Exports from three systems combined by hand.', 'Data passed between systems automatically.'],
        ['Management wants a report', 'Built in a spreadsheet the night before.', 'Drawn from one combined source, current every day.'],
    ],

    'services' => [
        ['hr-and-employee-portals', 'Self-service for staff: requests, documents and policies in one place.'],
        ['workflow-and-approval-automation', 'Approvals routed by amount, type or department, with a full decision log.'],
        ['enterprise-application-development', 'Larger internal systems with roles, audit trails and integration with what you run.'],
    ],

    'solutions' => ['automate-approvals', 'connect-your-systems'],

    'faq' => [
        ['Do you work alongside our internal IT team?', 'Yes, and we prefer to. Your IT team sets the standards for access, hosting and security; we build to them and hand over documentation they can hold us to.'],
        ['Can it run on our own infrastructure?', 'It can be hosted by us or deployed to infrastructure you control. That choice is made at the start, with your IT and security requirements in front of us.'],
        ['How do procurement and contracts work?', 'Work is scoped and quoted in writing before it starts, with the deliverables named. We can work through your supplier onboarding process.'],
        ['What is a sensible first project?', 'One process that is slow, frequent and well understood, often an approval or a request type. It proves the approach on something small before anything larger is committed to.'],
    ],

    'close' => 'Which internal process would you fix first?',
];
