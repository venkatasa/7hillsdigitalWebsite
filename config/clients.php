<?php

/*
|--------------------------------------------------------------------------
| Live client work
|--------------------------------------------------------------------------
|
| Real installs, running real businesses today. The opposite of config/demos.php,
| which is entirely invented — everything in here is true, and must stay true.
|
| RULES FOR THIS FILE
|
| 1. Name a client only with their permission. A public reference is the
|    client's to give, not ours to take.
|
| 2. Link to the client's PUBLIC site only. Never link a back office, and never
|    invite a prospect to walk through one. CLAUDE.md is explicit that Excellent
|    Real Estate must not be demoed: its panel holds real leads and real
|    applicant ID documents, and showing that to a stranger is a POPIA breach.
|    A screenshot of someone else's pipeline is the same breach with extra steps.
|
| 3. Claim only what is actually running. This section exists precisely because
|    the rest of the site sells things that are not built yet (open decisions 6
|    and 8). If it drifts into aspiration it is worth less than nothing.
|
| 4. Say whose software it is. Where a deliverable is a third-party application
|    we host and support rather than something we wrote, `software` says so.
|    Letting a prospect assume we built InvoiceShelf would be a lie that
|    survives exactly until they Google it.
|
*/

return [

    'excellent' => [
        'name' => 'Excellent Real Estate',
        'vertical' => 'Estate agency, Midrand',
        'package' => 'listings',
        'url' => 'https://excellentrealestate.co.za',
        'summary' => 'A boutique residential agency running its whole operation on the '
            .'Listings package — the public site buyers browse, and the back office the '
            .'agents actually work in.',

        // Public-facing capability only. Nothing here invites a walkthrough.
        'delivered' => [
            'Sale and rental listings with photo galleries',
            'Buyer enquiries captured straight into the agents’ pipeline',
            'Online rental applications, documents and all',
            'Scheduled publishing, so a listing goes live on the day it should',
        ],

        'software' => 'Built by 7Hills.',
    ],

    'excellent-invoicing' => [
        'name' => 'Invoicing',
        'vertical' => 'Add-on module · Excellent Real Estate',
        'package' => null,
        'url' => null,   // behind a login — deliberately not linked
        'summary' => 'The Invoicing add-on, live on its own subdomain and sending from the '
            .'agency’s own mailbox, so an invoice arrives from the business rather than '
            .'from a stranger.',

        'delivered' => [
            'Invoices, quotes and recurring billing',
            'Sends from the client’s own domain',
            // "backed up" was dropped on the revamp branch (31/10/2026): off-site
            // backups have failed since 16/10 (finding R1). Restore it once they run.
            'Hosted and updated by us',
        ],

        // See rule 4. We deploy, host and support it; we did not write it.
        // InvoiceShelf replaced Invoice Ninja on 25/08/2026; rule 3 means this line
        // follows whatever is actually running.
        'software' => 'InvoiceShelf, self-hosted and maintained by 7Hills.',
    ],

];
