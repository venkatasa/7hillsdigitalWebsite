<?php

/*
 | Industry: hospitality. Platform: Stay. Demo: 7Hills Stay (invented).
 | Stay takes direct booking ENQUIRIES; it is not a live availability engine or a
 | payment gateway, and nothing here says it is.
 */

return [
    'quote_type' => 'custom-development',

    'title' => 'Guesthouse and Lodge Websites for Direct Booking Enquiries',
    'description' => 'Your own booking page for guesthouses, lodges and B&Bs: rooms, seasonal rates and direct enquiries in one pipeline, with no commission on repeat guests.',

    'h1' => 'Direct booking enquiries, without the commission.',
    'lede' => 'Stay is our platform for guesthouses, lodges and B&Bs: your rooms and seasonal rates on your own website, direct booking enquiries in one pipeline, and the pages that answer guests\' questions before they ask.',
    'cta' => 'Talk about your property',

    'context' => [
        'heading' => 'Paying commission on a guest who already knows you.',
        'body' => [
            'Booking platforms are useful for being found by people who have never heard of you. They are expensive for the guest who stayed last year and searches your name to come back, because that booking still arrives through the platform and still pays its commission.',
            'A website of your own gives returning guests, and anyone who finds you by name, a way to reach you directly. It only works if it is easy to keep current: rooms, rates for each season, and replies to enquiries that do not get buried under everything else a small property has to do.',
        ],
    ],

    'day' => [
        ['The season changes', 'Rates are updated by whoever built the site, if they are available.', 'You update the rate table yourself, per season, in minutes.'],
        ['A guest enquires', 'An email or WhatsApp that waits until evening.', 'An enquiry with dates, guests and requests, in a pipeline with a contact log.'],
        ['A guest has questions', 'The same questions by phone: check-in, pets, directions.', 'Policy, facilities and directions pages that answer them first.'],
        ['The guest returns next year', 'Through the platform, paying commission again.', 'Straight to your site, because it is the one they remember.'],
    ],

    'services' => [
        ['booking-and-scheduling-systems', 'For properties that want more than enquiries: availability and bookings in a calendar you control.'],
        ['accounting-and-invoicing-integrations', 'Invoices and deposits for direct bookings, available today as our Invoicing add-on.'],
        ['business-process-automation', 'Confirmation, pre-arrival and thank-you messages, sent when a booking changes stage.'],
    ],

    'solutions' => ['get-paid-on-time', 'automate-approvals'],

    'faq' => [
        ['Does Stay take payments or show live availability?', 'Stay takes direct booking enquiries, which you confirm. Live availability and online payment are a bigger build, scoped separately, because they depend on how you manage rooms and which payment provider you choose.'],
        ['Should we leave the booking platforms?', 'Usually not. They are good at finding new guests. The point of your own site is that guests who already know you, and anyone searching your name, can book you directly.'],
        ['Can we update photos and rates ourselves?', 'Yes. Rooms, galleries and seasonal rates are edited from the back office without a developer.'],
        ['What does it cost?', 'Stay is a flat monthly fee with the build included and no minimum term. The current price is on this page, straight from our price list.'],
    ],

    'close' => 'How many of last year\'s guests came back through a platform?',
];
