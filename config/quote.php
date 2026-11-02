<?php

/*
|--------------------------------------------------------------------------
| /quote: the project enquiry form
|--------------------------------------------------------------------------
|
| The project types themselves, and the services that gate them, live in
| config/revamp.php under `quote_types`, so the publication rule is applied in
| one place. This file holds what each type asks. The source is
| docs/revamp/full-service/07_LEAD_GENERATION.md §7.2.
|
| Every option is [value => label]. Values are what is stored on the enquiry
| and must never be renamed once real enquiries carry them. Labels can change.
|
| The budget bands are the PROPOSED bands (decision Q6 is still open). Change
| them here when the owner sets the minimum engagement.
|
*/

$users = ['1-10' => '1 to 10', '11-25' => '11 to 25', '26-50' => '26 to 50', '51-250' => '51 to 250', '250+' => 'More than 250'];

return [

    // Always offered, whatever is published: nobody is turned away.
    'other' => ['Something else', 'Describe the problem and we will work out what it needs'],

    /*
    | Step 2. `message` labels the one free-text box and says whether the type
    | needs it. `questions` are option lists, stored as answers[question => value].
    */
    'types' => [
        'custom-development' => [
            'message' => ['What should it do?', true],
            'budget' => 'project',
            'questions' => [
                'replacing' => ['Is it replacing something?', ['spreadsheets' => 'Spreadsheets', 'paper' => 'Paper forms', 'old-system' => 'An old system', 'nothing' => 'Nothing, it is new']],
                'audience' => ['Who will use it?', ['staff' => 'Our staff only', 'customers' => 'Customers too']],
            ],
        ],
        'microsoft-365' => [
            'message' => ['Anything else we should know?', false],
            'budget' => 'service',
            'questions' => [
                'need' => ['What do you need?', ['setup' => 'Set it up', 'migrate' => 'Move to it', 'sharepoint' => 'SharePoint', 'automate' => 'Automate work in it', 'secure' => 'Secure it', 'not-sure' => 'Not sure yet']],
                'users' => ['How many people use it?', $users],
            ],
        ],
        'ai-automation' => [
            'message' => ['What should AI or automation handle for you?', true],
            'budget' => 'project',
            'questions' => [
                'whatsapp' => ['Should it work on WhatsApp?', ['yes' => 'Yes', 'no' => 'No', 'not-sure' => 'Not sure yet']],
                'frequency' => ['How often does it happen?', ['daily' => 'Every day', 'weekly' => 'Every week', 'monthly' => 'Every month']],
                'documents' => ['Are documents involved?', ['yes' => 'Yes', 'no' => 'No']],
            ],
        ],
        'mobile-app' => [
            'message' => ['What should the app do?', true],
            'budget' => 'project',
            'questions' => [
                'platforms' => ['Which phones?', ['both' => 'Android and iOS', 'android' => 'Android only', 'ios' => 'iOS only', 'not-sure' => 'Not sure yet']],
                'audience' => ['Who will use it?', ['customers' => 'Our customers', 'staff' => 'Our staff or field team', 'both' => 'Both']],
            ],
        ],
        'managed-it' => [
            'message' => ['Anything else we should know?', false],
            'budget' => 'monthly',
            'questions' => [
                'users' => ['How many staff?', $users],
                'devices' => ['Roughly how many devices?', ['1-10' => '1 to 10', '11-25' => '11 to 25', '26-50' => '26 to 50', '51-100' => '51 to 100', '100+' => 'More than 100']],
                'current' => ['Who supports you today?', ['none' => 'Nobody', 'individual' => 'One person we call', 'company' => 'Another company', 'in-house' => 'Our own staff']],
            ],
        ],
        'cloud-infrastructure' => [
            'message' => ['Anything else we should know?', false],
            'budget' => 'service',
            'questions' => [
                'where' => ['Where do your systems run today?', ['office-server' => 'A server in the office', 'hosting-company' => 'A hosting company', 'azure' => 'Microsoft Azure', 'aws' => 'Amazon Web Services', 'not-sure' => 'Not sure']],
                // No "back it up": backup is blocked until R1 is fixed, so we do not invite the work.
                'goal' => ['What is the goal?', ['move' => 'Move it somewhere better', 'cost' => 'Spend less on it', 'monitor' => 'Know when something breaks', 'not-sure' => 'Not sure yet']],
            ],
        ],
        'security-assessment' => [
            'message' => ['Anything else we should know?', false],
            'budget' => 'service',
            'questions' => [
                'prompted' => ['What prompted it?', ['incident' => 'An incident', 'insurance' => 'Our insurer', 'client' => 'A client asked', 'popia' => 'POPIA', 'peace' => 'Peace of mind']],
                'users' => ['How many staff?', $users],
            ],
        ],
        'other' => [
            'message' => ['Tell us about it', true],
            'budget' => 'project',
            'questions' => [],
        ],
    ],

    /*
    | Step 3. Bands are excl. VAT. `true` marks a band that makes the enquiry a
    | priority. "Not sure yet" is in every set and is never penalised.
    */
    'budgets' => [
        'project' => [
            'under-50k' => ['Under R50 000', false],
            '50k-150k' => ['R50 000 to R150 000', false],
            '150k-500k' => ['R150 000 to R500 000', true],
            '500k+' => ['More than R500 000', true],
            'not-sure' => ['Not sure yet', false],
        ],
        'service' => [
            'under-20k' => ['Under R20 000', false],
            '20k-75k' => ['R20 000 to R75 000', false],
            '75k-250k' => ['R75 000 to R250 000', false],
            '250k+' => ['More than R250 000', true],
            'not-sure' => ['Not sure yet', false],
        ],
        'monthly' => [
            'under-5k-pm' => ['Under R5 000 a month', false],
            '5k-15k-pm' => ['R5 000 to R15 000 a month', false],
            '15k-40k-pm' => ['R15 000 to R40 000 a month', true],
            '40k+-pm' => ['More than R40 000 a month', true],
            'not-sure' => ['Not sure yet', false],
        ],
    ],

    'timeframes' => [
        'asap' => 'As soon as possible',
        '1-3-months' => 'In 1 to 3 months',
        '3-6-months' => 'In 3 to 6 months',
        'exploring' => 'Just exploring',
    ],

    'company_sizes' => [
        '1-10' => '1 to 10 people',
        '11-50' => '11 to 50',
        '51-250' => '51 to 250',
        '250+' => 'More than 250',
    ],

    'preferred_contact' => [
        'phone' => 'Phone',
        'email' => 'Email',
        'whatsapp' => 'WhatsApp',
    ],

    // A service line's "Get a quote" opens the type that fits it best.
    'lines' => [
        'software-development' => 'custom-development',
        'business-applications' => 'custom-development',
        'microsoft-business-solutions' => 'microsoft-365',
        'ai-data-and-automation' => 'ai-automation',
        'cloud-and-infrastructure' => 'cloud-infrastructure',
        'it-support-and-security' => 'managed-it',
    ],

    /*
    | Priority, shown as a badge and used to sort the panel (§7.4). Nothing
    | here sends anything or moves an enquiry on.
    */
    'priorities' => [
        'monthly' => ['Monthly revenue', 'success'],
        'high' => ['High', 'warning'],
        'standard' => ['Standard', 'gray'],
        'nurture' => ['Nurture', 'info'],
        'triage' => ['Owner triage', 'primary'],
    ],
];
