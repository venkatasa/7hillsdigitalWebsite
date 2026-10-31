<?php

/*
 | Solution: invoices go out late.
 | Solved by accounting and invoicing integrations (available: the Invoicing
 | add-on) and business process automation. Proof is the live Invoicing install,
 | named as third-party software (config/clients.php, rule 4). No claim about
 | sending domain or backups: neither is verified here.
 */

return [
    'quote_type' => 'ai-automation',
    'motif' => 'invoice',

    'title' => 'Get Paid on Time: Invoicing That Goes Out by Itself',
    'description' => 'Quotes that become invoices, recurring billing that never forgets, and a clear list of who owes what, so cash arrives on time, not when someone remembers.',

    'eyebrow' => 'Get paid on time',
    'h1' => 'Invoices out on the day. Payments you can see.',
    'lede' => 'We set up invoicing that turns an accepted quote into an invoice, sends recurring bills on schedule and shows what is outstanding at a glance, so getting paid stops depending on a spare afternoon.',
    'cta' => 'Talk about your billing',

    'symptoms' => [
        'Invoices go out in a batch whenever someone finds the time.',
        'Monthly clients are sometimes billed late, or not at all.',
        'Quotes are retyped into invoices, and the numbers do not always match.',
        'Nobody can say, without digging, who is overdue and by how much.',
        'Reminders feel awkward, so they are not sent.',
    ],

    'cost' => [
        'heading' => 'Late invoices are a loan you never agreed to give.',
        'body' => [
            'Every day an invoice is not sent is a day added to when you are paid. For a small business the gap between doing the work and seeing the money is often the difference between a comfortable month and a tight one.',
            'Cash-flow trouble is common among South African small businesses: in Xero\'s 2026 survey, 62% reported cash-flow problems in the past year. Not all of that is late invoicing, but billing on time and chasing consistently is the part fully within your control.',
            'The fix is not working harder at month-end. It is a system where an accepted quote becomes an invoice without retyping, a monthly client is billed by the calendar, and overdue amounts are in a list you can act on.',
        ],
        'source' => 'Xero, The State of South African Small Business 2026, 427 businesses surveyed, Jan–Feb 2026.',
    ],

    'shift' => [
        ['Quote to invoice', 'Retyped by hand', 'Converted in one step'],
        ['Monthly billing', 'Depends on someone remembering', 'Sent on schedule'],
        ['What is owed', 'Worked out from bank statements', 'An outstanding list, always current'],
        ['Reminders', 'Awkward, so skipped', 'Sent consistently, in your words'],
    ],

    'services' => [
        ['accounting-and-invoicing-integrations', 'The invoicing itself: quotes, invoices, recurring billing and payment tracking, available today as our Invoicing add-on.'],
        ['business-process-automation', 'Connects billing to the rest of the work, so a finished job or a signed quote triggers the invoice.'],
        ['crm-systems', 'Keeps the customer record that invoicing draws from, so contact details are typed once.'],
    ],

    'flow' => [['Quote', 'Sent and accepted'], ['Invoice', 'Created from the quote'], ['Reminder', 'If it falls overdue'], ['Paid', 'Marked against the invoice']],

    'inputs' => [
        ['How you bill today', 'Once-off, monthly, by the hour or a mix, and which clients are on which.'],
        ['Your documents', 'A current quote and invoice, so the new ones carry the same details and branding.'],
        ['Your payment terms', 'When invoices are due and how firmly you want reminders worded.'],
        ['Your accountant\'s needs', 'What they need from you at month-end, so the export suits them.'],
    ],

    'proof' => [
        'heading' => 'Running for a client today.',
        'body' => 'Our Invoicing add-on is live for an estate agency, on its own subdomain: quotes, invoices and recurring billing. It runs on InvoiceShelf, open-source software that we deploy, host and support. We say so because you would find out anyway.',
        'client' => 'excellent-invoicing',
        'flow' => [['Quote', 'Accepted'], ['Invoice', 'Generated'], ['Recurring', 'Each month'], ['Paid', 'Recorded']],
    ],

    'faq' => [
        ['Do we have to stop using our accounting package?', 'No. Invoicing sits in front of it. What your accountant needs at month-end comes out as an export, and a direct connection can be built where the accounting product supports one.'],
        ['What does the Invoicing add-on cost?', 'It is a flat monthly fee on top of a platform subscription, shown on the packages page from our live price list. Connecting it to other systems is quoted separately.'],
        ['Can clients pay online?', 'That depends on the payment provider you use or choose. It is scoped at the start, along with its fees, rather than assumed.'],
        ['Is it your software?', 'The invoicing application is InvoiceShelf, which we host and support. Anything we build around it, such as automation or integration with your other systems, is ours.'],
    ],

    'close' => 'How long does an invoice usually wait before it is sent?',

    'industries' => ['professional-services', 'property', 'hospitality'],
];
