<?php

/*
 | CRM Systems — verified (built_to_order). Evidence: the enquiry pipeline in our
 | own back office. The CRM ADD-ON is still in development and must never be
 | presented as available (CLAUDE.md, 03_SERVICE_CATALOGUE.md).
 */

return [
    'gate' => 'crm-systems',
    'covers' => ['customer-self-service-portals'],
    'motif' => 'pipeline',
    'quote_type' => 'custom-development',

    'title' => 'CRM Systems for South African Businesses',
    'description' => 'A CRM built around how you sell: every lead and customer in one pipeline, with follow-ups, ownership and history. Custom CRM development and integration.',

    'eyebrow' => 'CRM systems',
    'h1' => 'Never lose a lead in someone\'s inbox again.',
    'lede' => 'Every enquiry and customer in one pipeline the whole team can see: who asked, what for, who owns it and what happens next, built around how you actually sell.',
    'cta' => 'Get a quote for a CRM',

    'visual' => ['type' => 'flow', 'steps' => [['New', 'Captured from the website'], ['Contacted', 'Owner assigned'], ['Quoted', 'Follow-up scheduled'], ['Won', 'Customer record kept']]],

    'facts' => [
        ['Built around your sales process', 'Your stages, your fields, your language'],
        ['Connected to your website', 'Enquiries arrive in the pipeline, not an inbox'],
        ['A history you can trust', 'What the customer said is kept as they said it'],
    ],

    'problem' => [
        'heading' => 'A lead that waits in one person\'s phone over the weekend is a lead your competitor answers on Monday.',
        'body' => [
            'Enquiries arrive by web form, email, phone and WhatsApp, and end up in whoever\'s inbox they landed in. Nobody can see what is outstanding, follow-ups depend on memory, and when a salesperson leaves, their pipeline leaves with them.',
            'Generic CRM products solve part of this, but they often ask your team to adopt someone else\'s sales process and fill in fields nobody uses. A CRM built around how you actually sell gets used, and a CRM that gets used is the only kind that works.',
        ],
    ],

    'deliverables' => [
        ['A pipeline with your stages', 'New, contacted, quoted, won, lost, or whatever your process really is.'],
        ['Customer and contact records', 'Everything about a customer in one place, with the full history of every interaction.'],
        ['Ownership and follow-ups', 'Each lead has an owner and a next step, and overdue follow-ups are flagged.'],
        ['Website capture', 'Enquiries from your site land in the pipeline with their source, never in an inbox.'],
        ['Evidence kept intact', 'What a prospect submitted is stored read-only, so the record shows what was actually asked.'],
        ['Reports that answer questions', 'Where leads come from, how many convert, and where they stall.'],
    ],

    'use_cases' => [
        ['Estate agency', 'Buyer enquiries reach agents\' phones and are never logged.', 'Enquiries from every listing land in one pipeline, assigned by area, with follow-up reminders.'],
        ['B2B services firm', 'Quotes are tracked in a spreadsheet that only the MD updates.', 'A quote pipeline with values, owners and stages, and a weekly view of what is about to close.'],
        ['Dealership', 'Finance and trade-in requests get lost between sales and the F&I office.', 'One record per buyer, holding the vehicle, finance and trade-in, visible to both teams.'],
    ],

    'technologies' => [
        ['Application', ['Python', 'Java', 'PHP and Laravel', 'React']],
        ['Data', ['PostgreSQL', 'MySQL']],
        ['Capture', ['Website forms', 'WhatsApp', 'Email']],
        ['AI', ['Lead scoring', 'Suggested replies', 'Call and chat summaries']],
    ],
    'technologies_note' => 'A ready-made 7Hills CRM module is in development and is not yet available. Today we build a CRM to your requirements, or extend the back office you already have with us.',

    'approach' => [
        ['Map how you sell', 'Where leads come from, who handles them, and what "won" means in your business.'],
        ['Agree the scope', 'Stages, fields, roles and reports, in writing, with the price.'],
        ['Build and import', 'The pipeline built, your existing contacts cleaned and imported.'],
        ['Train, run, refine', 'The team trained on it, then adjustments once it is in daily use.'],
    ],

    'proof' => [
        'heading' => 'The pipeline behind this website.',
        'body' => 'Every enquiry to 7Hills Digital lands in this pipeline with a reference, a status and a full history. What a prospect submitted is read-only, so nobody can rewrite what was actually asked. Shown on staging with fictional data.',
        'image' => 'admin-enquiries',
        'url' => 'yourbusiness.co.za/admin/enquiries',
    ],

    'faq' => [
        ['Do you sell an off-the-shelf CRM?', 'Not yet. A ready-made 7Hills CRM module is in development. Today we build a CRM to your requirements, or add pipeline features to a system we already run for you.'],
        ['Why not just use a big CRM product?', 'For some businesses that is the right answer, and we will say so. A custom CRM makes sense when your process does not fit the product, or when the CRM needs to sit inside the system your team already uses every day.'],
        ['Can it capture WhatsApp enquiries?', 'Enquiries can be logged against the customer record, and WhatsApp links can carry context about which page the customer was on. Full WhatsApp integration depends on the WhatsApp Business tools you use; we confirm it during scoping.'],
        ['Is customer data protected under POPIA?', 'Yes. You are the responsible party and we are your operator: we process the data only on your instructions, keep it secured, and never use it for anything else.'],
        ['Can we bring our existing contacts across?', 'Yes. Contacts and open deals are cleaned and imported from your spreadsheets or current system as part of the build, so the team starts with its history rather than an empty screen. Duplicates and incomplete records are flagged for a person to decide on.'],
    ],

    'close' => 'Show us how you sell. We will build the pipeline around it.',

    'related' => ['web-application-development', 'business-process-automation', 'api-development-and-integration'],
];
