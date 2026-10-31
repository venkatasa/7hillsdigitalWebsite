<?php

/*
 | Solution: IT is nobody's job — PREVIEW ONLY.
 | Gated on managed-it-support (capability_unconfirmed, 06_CAPABILITY_VERIFICATION.md).
 | Production returns 404 until that service is confirmed. No proof, no response
 | times, no SLA language.
 */

return [
    'quote_type' => 'managed-it',
    'motif' => 'shield',

    'title' => 'Managed IT for Small Businesses: IT That Just Works',
    'description' => 'When IT is nobody\'s job, it becomes everyone\'s problem. One team for your staff, devices, email and accounts, on a monthly agreement.',

    'eyebrow' => 'IT that just works',
    'h1' => 'Hand IT to a team that answers.',
    'lede' => 'We look after your staff\'s devices, accounts and email on a monthly agreement: one number to call, the basics kept up to date, and a record of what you own and who has access to it.',
    'cta' => 'Talk about your IT',

    'symptoms' => [
        'The person who is "good with computers" spends a day a week fixing them.',
        'Nobody is sure who still has access to the company email.',
        'Laptops are replaced when they die, never before.',
        'Passwords live in a notebook or a shared document.',
        'When something breaks, you search for a supplier\'s number.',
    ],

    'cost' => [
        'heading' => 'IT nobody owns is IT nobody secures.',
        'body' => [
            'In a growing business, IT is usually done by whoever knows the most about it, between their actual work. It gets done just well enough, until a laptop is stolen, an account is compromised or a former employee is found still reading the company email.',
            'The hidden cost is in the gaps: devices without updates, accounts never closed, nothing written down. Those gaps are where most problems start, and they are dull, routine things to close once someone is responsible for them.',
            'A monthly agreement makes that someone a team, with a list of what you own, a routine for keeping it current and one place to ask for help.',
        ],
    ],

    'shift' => [
        ['Who to call', 'A colleague, or a search for a supplier', 'One team, one number'],
        ['Devices', 'Replaced when they fail', 'Listed, updated and planned for'],
        ['Accounts', 'Opened easily, rarely closed', 'Created and removed with staff changes'],
        ['Knowledge', 'In one person\'s head', 'Written down and handed over'],
    ],

    'services' => [
        ['managed-it-support', 'The monthly agreement: help for your staff, device care and a single point of contact.'],
        ['it-infrastructure-support', 'The office side: network, printers and the equipment everything else depends on.'],
        ['identity-and-access-management', 'Who has access to what, with accounts opened and closed as people join and leave.'],
    ],

    'flow' => [['Audit', 'What you own'], ['Fix', 'The urgent gaps'], ['Routine', 'Monthly care'], ['Support', 'One number']],

    'inputs' => [
        ['A list of people', 'Who works for you, and who has left in the last year.'],
        ['A list of devices', 'Even a rough one. We complete it during the first visit.'],
        ['Your accounts', 'Email, domain and the main products the business pays for.'],
        ['What bothers you most', 'The problem that made you look for help in the first place.'],
    ],

    'faq' => [
        ['Is this priced per user?', 'Pricing is settled once the service is confirmed for publication. It is quoted in writing after the first audit, based on the people and devices you actually have.'],
        ['Do you come on site?', 'Where and how often is agreed in the proposal. Much of the routine work can be done remotely, with visits for what needs hands.'],
        ['Can you take over from our current provider?', 'Yes. A handover starts with getting the administrator accounts and documentation transferred properly, so nothing depends on the previous provider afterwards.'],
        ['Can you supply the laptops too?', 'We can source hardware and add it to the same invoice, so the devices you buy fit the way they will be looked after.'],
    ],

    'close' => 'Who looks after your IT at the moment?',

    'industries' => ['professional-services', 'corporate', 'hospitality'],
];
