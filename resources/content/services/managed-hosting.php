<?php

/*
 | Managed Hosting — PREVIEW ONLY.
 | blocked: we do host applications today, but selling hosting while off-site
 | backups are failing and the database port is exposed (R1–R3,
 | PRODUCTION_SAFETY_CHECKLIST.md) is not defensible. No uptime figures or
 | backup promises until those are fixed and a restore has been tested.
 | Wording rule: "managed hosting", not "cloud" — it runs on our own infrastructure.
 */

return [
    'gate' => 'managed-hosting',
    'covers' => ['server-and-infrastructure-management', 'infrastructure-monitoring', 'backup-and-disaster-recovery', 'maintenance-and-monitoring'],
    'motif' => 'stack',
    'quote_type' => 'cloud-infrastructure',

    'title' => 'Managed Hosting and Infrastructure Management',
    'description' => 'Your business applications hosted, updated and monitored by the team that built them, with security updates applied and every server documented.',

    'eyebrow' => 'Managed hosting',
    'h1' => 'Hosted, updated and watched by the team that built it.',
    'lede' => 'Your applications run on infrastructure we manage: patched, monitored and documented, so a problem raises an alert before a customer notices.',
    'cta' => 'Get a hosting quote',

    'visual' => ['type' => 'diagram', 'name' => 'layers'],

    'facts' => [
        ['Monitored', 'Health checks that alert a person'],
        ['Kept current', 'Security updates applied on a schedule'],
        ['Documented', 'Every server and service written down'],
    ],

    'problem' => [
        'heading' => 'Software is only as dependable as the place it runs.',
        'body' => [
            'A well-built application on a forgotten server is a risk waiting for a date. Updates are skipped, nobody notices a disk filling up, and the first sign of trouble is a customer calling to say the site is down.',
            'Managed hosting means somebody owns that risk: the server is patched, the application is watched, problems raise alerts, and how it all fits together is written down.',
            'Hosting is also where a business\'s obligations meet its software. Customer records live on that server, and under POPIA the business must keep them secure. Knowing exactly where they are, who can reach them and how they are restored is part of meeting that obligation, not an optional extra.',
        ],
    ],

    'deliverables' => [
        ['Hosting for your applications', 'Containers on infrastructure we manage, behind Cloudflare.'],
        ['Monitoring and alerts', 'Uptime and health checks that notify a person when something fails.'],
        ['Security updates', 'Operating system and application updates applied on a schedule.'],
        ['Backups and restores', 'Copies kept off the server, and restores tested, not assumed.'],
        ['Documentation', 'Each server, service and setting recorded for the next person.'],
        ['Change management', 'Updates tested on staging before they reach your live system.'],
    ],

    'use_cases' => [
        ['Business with custom software', 'An application was built years ago and nobody looks after the server.', 'The application moved to managed hosting, updated, monitored and documented.'],
        ['Agency client', 'A website broke after an unplanned update and took a day to restore.', 'Updates tested on staging first, with a rollback ready.'],
        ['Growing team', 'Nobody knows which server runs what.', 'An inventory of every service, with owners and how to restore it.'],
    ],

    'technologies' => [
        ['Platform', ['Docker', 'Linux', 'Proxmox']],
        ['Edge and access', ['Cloudflare', 'Tailscale']],
        ['Watching it', ['Uptime Kuma', 'Grafana']],
    ],
    'technologies_note' => 'This runs on infrastructure we operate, not on a public cloud platform. Uptime figures will be published only once they have been measured over time.',

    'approach' => [
        ['Assess what runs today', 'Applications, dependencies, data and how they are restored.'],
        ['Plan the move', 'Where it will run, how it is backed up, and how it is cut over.'],
        ['Migrate and verify', 'Moved, tested and restored once on purpose before going live.'],
        ['Run it', 'Monitored, updated and documented, every month.'],
    ],

    'faq' => [
        ['Is this cloud hosting?', 'It runs on infrastructure we operate, behind Cloudflare, rather than on a public cloud platform. If you need Azure or AWS specifically, say so during scoping.'],
        ['What uptime do you guarantee?', 'We will publish uptime only once we have measured it over a meaningful period. Until then we would rather make no claim than a hollow one.'],
        ['How are backups handled?', 'Copies are kept off the server and restores are tested. The details are agreed in the hosting contract.'],
        ['Can you host software you did not build?', 'Often, yes. We assess it first: how it is installed, updated and restored.'],
        ['What happens if something goes wrong at night?', 'Monitoring raises an alert when a service fails. How and when a person responds is set out in the hosting contract, and we will not promise response times we have not committed to in writing.'],
    ],

    'close' => 'Put your software somewhere looked after.',

    'related' => ['managed-it-support', 'custom-software-development', 'api-development-and-integration'],
];
