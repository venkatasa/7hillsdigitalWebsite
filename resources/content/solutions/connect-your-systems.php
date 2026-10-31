<?php

/*
 | Solution: your systems don't talk.
 | Solved by API development and integration and business process automation
 | (built_to_order). No proof yet: omitted, not padded.
 */

return [
    'quote_type' => 'custom-development',
    'motif' => 'nodes',

    'title' => 'Connect Your Business Systems So Data Is Entered Once',
    'description' => 'Your website, CRM, invoicing and spreadsheets connected so information is captured once and flows where it is needed, without retyping or copy and paste.',

    'eyebrow' => 'Connect your systems',
    'h1' => 'Enter it once. Every system that needs it gets it.',
    'lede' => 'We connect the products you already pay for, so a new customer, order or booking is typed in one place and appears in the others automatically, checked and logged along the way.',
    'cta' => 'Tell us what needs connecting',

    'symptoms' => [
        'The same customer is typed into three different systems.',
        'Staff copy orders from the website into the back office by hand.',
        'Reports need exports from several products stitched together in a spreadsheet.',
        'A change in one system is not reflected in another until someone notices.',
        'You avoid a better product because moving data into it seems impossible.',
    ],

    'cost' => [
        'heading' => 'Every retyped record is a chance to get it wrong.',
        'body' => [
            'Most businesses do not have one system. They have a website, an accounting package, a CRM or a booking tool, and a spreadsheet filling the gaps between them. People become the connection, copying data across by hand.',
            'That work is slow, and it introduces errors that are hard to trace: a misspelt email address in one system, an old price in another, an order that never made it across. Each mistake is small, and fixing it eats time nobody planned for.',
            'Connecting systems moves that work to software. When the website takes an order, the back office has it. When an invoice is paid, the CRM knows. Information is checked on the way through, and every transfer is logged so a failure is noticed rather than silent.',
        ],
    ],

    'shift' => [
        ['New customers', 'Typed into each system separately', 'Created once and shared'],
        ['Orders and bookings', 'Copied across by hand', 'Passed on automatically'],
        ['Errors', 'Found weeks later, by accident', 'Flagged when a transfer fails'],
        ['Changing products', 'Feels impossible', 'A matter of moving one connection'],
    ],

    'services' => [
        ['api-development-and-integration', 'Builds the connections between your products, using their published interfaces wherever they exist.'],
        ['business-process-automation', 'Decides what happens when data arrives: who is told, what is created and what is checked.'],
        ['database-development', 'Gives the combined data one home when reporting across systems needs it.'],
    ],

    'flow' => [['Captured', 'In one system'], ['Checked', 'Required fields valid'], ['Passed on', 'To each system that needs it'], ['Logged', 'Failures flagged']],

    'inputs' => [
        ['The list of products', 'Every system involved, with the plan or edition you are on.'],
        ['Access', 'Administrator access or API keys for each, created for us and revocable by you.'],
        ['The flows that hurt', 'Where data is retyped today, and roughly how often.'],
        ['Which system is right', 'When two disagree, which one should win.'],
    ],

    'faq' => [
        ['Can you connect any two systems?', 'Most modern products publish an interface for exactly this. Some older or locked-down ones do not, and we tell you that during scoping, along with the workaround if there is one.'],
        ['What happens when a connection fails?', 'It is logged and someone is told. A connection that fails silently is worse than none, so alerting is part of every build, not an extra.'],
        ['Who owns the connections?', 'The accounts and data remain yours. The integration code is ours, maintained by us as part of support, and documented so you are never locked in by it.'],
        ['Does this make our data less secure?', 'It should make it more secure: each connection uses the minimum access it needs, credentials are stored safely rather than in spreadsheets, and personal information moves directly between systems instead of through inboxes.'],
    ],

    'close' => 'Where is your team retyping the same thing today?',

    'industries' => ['corporate', 'hospitality', 'recruitment'],
];
