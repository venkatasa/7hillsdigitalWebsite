<?php

/*
 | Managed IT Support — PREVIEW ONLY.
 | capability_unconfirmed: no support desk, hours, staffing, tooling or price on
 | file (06_CAPABILITY_VERIFICATION.md §E). No response times, SLAs or "24/7"
 | until the owner sets them in writing.
 */

return [
    'gate' => 'managed-it-support',
    'covers' => ['it-infrastructure-support', 'identity-and-access-management', 'endpoint-security'],
    'motif' => 'pulse',
    'quote_type' => 'managed-it',

    'title' => 'Managed IT Support for Small and Medium Businesses',
    'description' => 'Managed IT support for South African businesses: one number for your staff, devices and accounts, from a team that knows your setup.',

    'eyebrow' => 'Managed IT support',
    'h1' => 'One number to call, and someone who already knows your setup.',
    'lede' => 'Your staff, laptops, accounts and office network looked after by one team on a monthly contract, so IT stops being the job everyone does a little of.',
    'cta' => 'Get a managed IT quote',

    'visual' => ['type' => 'constellation', 'motif' => 'pulse', 'items' => ['Staff accounts', 'Laptops', 'Email', 'Office network', 'Printers', 'Access']],

    'facts' => [
        ['A monthly contract', 'Priced on users and devices'],
        ['One point of contact', 'For staff, accounts and equipment'],
        ['Documented setup', 'Your systems written down, not in one person\'s head'],
    ],

    'problem' => [
        'heading' => 'In most small businesses, IT is nobody\'s job, so it becomes everybody\'s problem.',
        'body' => [
            'The person who is "good with computers" sets up the new laptop, resets passwords and fights the printer, on top of their real work. Nobody keeps track of who has access to what, updates are skipped, and when something serious breaks there is no one to call.',
            'Managed IT gives that work to a team whose job it is: a known number to call, a record of your equipment and accounts, and routine maintenance that happens whether or not anyone remembers.',
            'It also matters for security. Most incidents start with something ordinary: a reused password, a missed update, a former employee who still has access. Routine, recorded IT work closes those gaps before they become the subject of a notification to the Information Regulator.',
        ],
    ],

    'deliverables' => [
        ['A helpdesk for your staff', 'One way to log a problem, and a person who follows it through.'],
        ['Device management', 'Laptops and phones set up, updated and recorded.'],
        ['Accounts and access', 'New starters set up, leavers removed on the day, passwords reset safely.'],
        ['Network and office equipment', 'Wi-Fi, routers and printers looked after.'],
        ['Documentation', 'Your equipment, accounts and settings written down and kept current.'],
        ['Advice before you buy', 'Recommendations on equipment and software before money is spent.'],
    ],

    'use_cases' => [
        ['Ten-person office', 'One staff member does IT on the side, badly and resentfully.', 'A monthly contract covering every user and device, with a documented setup.'],
        ['Business with high staff turnover', 'Former staff keep access to email and files.', 'A starter and leaver process that sets up and removes access on the day.'],
        ['Multi-site company', 'Each branch has its own way of doing IT.', 'One standard setup across sites, supported by one team.'],
    ],

    'technologies' => [
        ['Areas covered', ['User accounts', 'Laptops and phones', 'Office networks', 'Email']],
    ],
    'technologies_note' => 'Response times, support hours and pricing per user will be published when the service is launched, not before.',

    'approach' => [
        ['Audit your setup', 'Users, devices, accounts and the network, written down.'],
        ['Agree the contract', 'What is covered, how to log a request, and the monthly price.'],
        ['Fix the basics', 'Updates, access clean-up and documentation brought up to date.'],
        ['Support every month', 'Requests handled, maintenance done, setup kept current.'],
    ],

    'faq' => [
        ['How is it priced?', 'Monthly, based on the number of users and devices. The price is agreed in writing after an audit of your setup.'],
        ['Do you come on site?', 'Most support can be done remotely. On-site needs are agreed in the contract.'],
        ['Is there a minimum term?', 'The terms are set in the contract and will be stated before you sign.'],
        ['Can you also support our software?', 'If we built or host it, it is already supported. Other software is covered as far as the contract states.'],
        ['What if we already have someone who does our IT?', 'Managed support can sit alongside them: taking the routine work and the documentation off their desk, and covering when they are away. The contract states exactly who does what, so nothing falls between two people.'],
    ],

    'close' => 'Hand IT to a team whose job it is.',

    'related' => ['microsoft-365', 'managed-hosting', 'custom-software-development'],
];
