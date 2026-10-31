<?php

/*
 | Microsoft 365, SharePoint and Power Platform — PREVIEW ONLY.
 | capability_unconfirmed: no evidence on file (06_CAPABILITY_VERIFICATION.md §B).
 | Never rendered in production until the owner confirms the row. No partner
 | status, certification or licence pricing may appear here.
 */

return [
    'gate' => 'microsoft-365-consulting',
    'covers' => ['sharepoint-online-development', 'power-apps-development', 'power-automate-workflows', 'microsoft-teams-integrations', 'power-bi-dashboards', 'document-management-systems', 'microsoft-365-migration-and-governance', 'microsoft-365-security'],
    'motif' => 'grid',
    'quote_type' => 'microsoft-365',

    'title' => 'Microsoft 365, SharePoint and Power Platform Services',
    'description' => 'Microsoft 365 set-up, SharePoint intranets and document management, and Power Automate and Power Apps workflows, organised around how your business works.',

    'eyebrow' => 'Microsoft 365, SharePoint and Power Platform',
    'h1' => 'Get more from the Microsoft 365 you already pay for.',
    'lede' => 'SharePoint, Teams, Power Automate and Power Apps, set up around how your people actually work, so documents are findable, approvals move, and sharing is safe.',
    'cta' => 'Talk to us about Microsoft 365',

    'visual' => ['type' => 'constellation', 'motif' => 'grid', 'items' => ['SharePoint', 'Teams', 'Power Automate', 'Power Apps', 'Exchange', 'Power BI']],

    'facts' => [
        ['Licences you already own', 'Most of this is included in common Microsoft 365 plans'],
        ['Organised, then automated', 'Tidy structure first, workflows second'],
        ['Permissions that make sense', 'Access that matches how the business is organised'],
    ],

    'problem' => [
        'heading' => 'Most businesses pay for Microsoft 365 and use it as email and a file dump.',
        'body' => [
            'Files are scattered across personal OneDrives, email attachments and a SharePoint site nobody structured. Approvals happen in email threads. Former staff still have access because nobody owns the list. Meanwhile the tools that would fix it are already in the subscription.',
            'Getting value from Microsoft 365 is less about buying more and more about setting up what you have: a sensible structure, clear permissions, and a few well-chosen workflows that remove the most email.',
            'There is a security side too. Sharing links that never expire, mailboxes without multi-factor sign-in and accounts that outlive their owners are among the most common ways businesses expose data. Tidying the setup closes those gaps as a side effect of making it easier to use.',
        ],
    ],

    'deliverables' => [
        ['A Microsoft 365 review', 'Licences, users, sharing settings and where documents actually live today.'],
        ['SharePoint structure', 'Sites and document libraries organised by how the business works, with versioning and retention.'],
        ['Teams set up properly', 'Teams and channels that match real working groups, connected to the right document libraries.'],
        ['Power Automate workflows', 'Approvals and notifications that run inside Microsoft 365 instead of in inboxes.'],
        ['Power Apps for simple jobs', 'Small internal apps for forms and checklists, built on licences you already have.'],
        ['Migration and governance', 'Mail and files moved in, plus rules for who can create, share and see what.'],
    ],

    'use_cases' => [
        ['Professional office', 'Client documents live in personal folders and email attachments.', 'Client libraries in SharePoint with consistent structure, permissions and version history.'],
        ['Operations team', 'Leave and purchase requests are approved by replying to an email.', 'A form and a Power Automate approval flow, with every decision logged.'],
        ['Growing company', 'Leavers keep access to files for months after they go.', 'Access reviews and a leaver checklist that removes access the day someone goes.'],
    ],

    'technologies_title' => 'The Microsoft 365 tools involved.',
    'technologies' => [
        ['Collaboration', ['SharePoint Online', 'Microsoft Teams', 'OneDrive']],
        ['Automation and apps', ['Power Automate', 'Power Apps']],
        ['Reporting', ['Power BI']],
    ],
    'technologies_note' => 'Which features you can use depends on your Microsoft 365 plan. We check that before recommending anything that needs a licence change.',

    'approach' => [
        ['Review what you have', 'Licences, structure, sharing and where the time goes today.'],
        ['Agree a plan', 'Structure first, then the workflows that remove the most email, in writing.'],
        ['Tidy, then automate', 'Libraries and permissions set up, then the first workflows built and tested.'],
        ['Hand over and support', 'Staff shown how it works, and support afterwards.'],
    ],

    'faq' => [
        ['Do we need to buy more licences?', 'Often not. Much of SharePoint, Teams and Power Automate is included in common Microsoft 365 business plans. We check your plan first and only suggest a change when it is needed.'],
        ['Can you migrate our email and files?', 'Moving mail and files into Microsoft 365 is part of this service. It is scoped after we know where everything lives today.'],
        ['Will our staff need training?', 'Some. A tidy structure is only useful if people know where things go. A short walkthrough is part of the hand-over.'],
        ['Is this the same as your custom software?', 'No. Microsoft 365 work uses the tools in your subscription. When a process outgrows them, custom software is the next step.'],
        ['Can you fix permissions that have grown out of control?', 'That is often the first job. We list who can see what, agree the structure that should exist, and then move sites, libraries and sharing links to match it, removing access that no longer has a reason to exist.'],
    ],

    'close' => 'Make the subscription earn its keep.',

    'related' => ['business-process-automation', 'managed-it-support', 'custom-software-development'],
];
