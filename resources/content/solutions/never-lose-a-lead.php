<?php

/*
 | Solution: leads go cold in someone's inbox.
 | Solved by CRM systems and business process automation (built_to_order).
 | Proof is our own enquiry pipeline, from the staging install, fictional data.
 */

return [
    'quote_type' => 'custom-development',
    'motif' => 'pipeline',

    'title' => 'Stop Losing Leads: An Enquiry Pipeline Your Team Can See',
    'description' => 'Every enquiry from your website, phone and WhatsApp in one pipeline, with an owner and a next step, so no prospect waits for a reply that never comes.',

    'eyebrow' => 'Never lose a lead',
    'h1' => 'Every enquiry gets an owner and a next step.',
    'lede' => 'We give your enquiries one place to land, a person responsible for each, and a clear stage from first contact to won or lost, so you can see the pipeline instead of hoping someone replied.',
    'cta' => 'Talk about your enquiries',

    'symptoms' => [
        'Website enquiries arrive in a shared mailbox nobody owns.',
        'A salesperson leaves and their leads leave with their phone.',
        'You find out a prospect bought elsewhere because nobody called back.',
        'You cannot say how many enquiries came in last month, or where from.',
        'Follow-ups depend on someone remembering to do them.',
    ],

    'cost' => [
        'heading' => 'The expensive lead is the one you already paid for.',
        'body' => [
            'Advertising, a listing on a portal, a referral from a happy customer: by the time someone fills in your form, you have already spent money to reach them. Losing them to a slow or forgotten reply wastes all of it.',
            'The cause is almost never laziness. Enquiries arrive through too many doors, the website, a phone call, a WhatsApp message, a walk-in, and each door leads to a different person\'s inbox or notebook. Nothing shows the whole picture, so nothing shows what is slipping.',
            'A pipeline fixes the picture. When every lead is visible with its stage and its owner, a manager can see on a Monday morning what is waiting, and a lead that sits untouched stands out instead of disappearing.',
        ],
    ],

    'shift' => [
        ['Where leads land', 'Personal inboxes and phones', 'One pipeline for the whole team'],
        ['Ownership', 'Whoever saw it first, maybe', 'A named person on every enquiry'],
        ['Follow-up', 'Memory and sticky notes', 'A next step and a date on each lead'],
        ['Reporting', 'A guess at month-end', 'Counts by source, stage and outcome'],
    ],

    'services' => [
        ['crm-systems', 'The pipeline itself: stages that match how you sell, an owner per lead and the full history of every contact.'],
        ['business-process-automation', 'Moves enquiries into the pipeline automatically and nudges the owner when one has waited too long.'],
        ['customer-self-service-portals', 'Lets customers check the status of their request themselves, which cuts the "any news?" calls.'],
    ],

    'flow' => [['Enquiry', 'Form, call or message'], ['Captured', 'Saved before anything else'], ['Assigned', 'An owner and a next step'], ['Closed', 'Won or lost, with a reason']],

    'inputs' => [
        ['Every door a lead comes through', 'Your forms, phone numbers, WhatsApp and any portals that send you enquiries.'],
        ['Your sales stages', 'How a prospect moves from first contact to signed, in your words.'],
        ['Who owns what', 'How leads are split today, by branch, product or round robin.'],
        ['What you want to know', 'The two or three numbers you would check every Monday if you could.'],
    ],

    'proof' => [
        'heading' => 'Our own enquiries work exactly like this.',
        'body' => 'Every enquiry from this website is saved first and notified second, so a mail outage never costs us a lead. It arrives as new, gets an owner and moves through stages nobody can skip. The screenshot is our staging install with invented enquiries.',
        'image' => 'admin-enquiries',
        'url' => '7hillsdigital.co.za/admin/enquiries',
    ],

    'faq' => [
        ['We already pay for a CRM. Why build one?', 'You may not need to. If your current CRM fits and the team uses it, we can connect your website and messaging to it instead. We build when the product forces a way of selling that does not match yours.'],
        ['Can WhatsApp enquiries go into the pipeline?', 'Enquiries that start from your website\'s WhatsApp button can be logged with their context. Capturing every message from a personal WhatsApp number automatically depends on which WhatsApp product you use, which we check before promising it.'],
        ['What about POPIA?', 'A pipeline helps. Enquiries sit in one access-controlled place with a record of consent and a way to delete them on request, instead of being scattered across phones and personal mailboxes.'],
        ['Will the sales team actually use it?', 'They will if it saves them time. That is why the first version is deliberately small: capture, assign, follow up. Extra fields and reports come after the team is using it every day.'],
    ],

    'close' => 'How many enquiries did you lose last month?',

    'industries' => ['automotive', 'property', 'recruitment'],
];
