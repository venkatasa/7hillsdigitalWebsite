<?php

/*
 | Industry: automotive dealerships. Platform: Showroom. Demo: 7Hills Auto
 | (invented). Platform features render from the package's own database record,
 | so this file describes the trade, not the feature list.
 */

return [
    'quote_type' => 'custom-development',

    'title' => 'Dealership Websites and Software for South African Car Dealers',
    'description' => 'Stock online the hour it arrives, finance and trade-in enquiries in one pipeline, and a back office your sales team updates itself. For car dealerships.',

    'h1' => 'Every car online by lunchtime. Every enquiry in one place.',
    'lede' => 'Showroom is our platform for dealerships: your stock on your own website, finance and trade-in enquiries landing in one pipeline, and the floor and the website always agreeing on what is sold.',
    'cta' => 'Talk about your dealership',

    'context' => [
        'heading' => 'Buyers decide online before they visit the floor.',
        'body' => [
            'By the time someone walks onto your floor, they have usually compared your stock with three other dealers on their phone. If your website shows a car you sold last week, or no price, or photos that will not load, the comparison is lost before you knew it started.',
            'Behind the website, a dealership runs on enquiries that arrive everywhere at once: portals, Facebook, phone calls, WhatsApp, walk-ins. Finance applications need documents. Trade-ins need photos. Each one is a sale in progress, and each one is easy to lose in a salesperson\'s phone.',
        ],
    ],

    'day' => [
        ['Stock arrives', 'Photographed and loaded by someone in the office, when they get to it.', 'Listed by the sales team from the back office, with every photo, the same morning.'],
        ['A buyer enquires', 'An email lands in a shared inbox, or a WhatsApp in someone\'s personal phone.', 'The enquiry, with the stock number, lands in a pipeline with an owner.'],
        ['Finance is discussed', 'Documents are sent by email and printed.', 'Pre-qualification collects ID, payslips and bank statements securely.'],
        ['The car is sold', 'The website still shows it for a week.', 'Marked sold once, and the website follows immediately.'],
    ],

    'services' => [
        ['crm-systems', 'A sales pipeline beyond the website: follow-ups, test drives and the reasons deals are lost.'],
        ['api-development-and-integration', 'Connects your stock to the systems you already use, so a car is loaded once.'],
        ['inventory-and-asset-management', 'Tracks stock from purchase to sale, with costs, reconditioning and days on the floor.'],
    ],

    'solutions' => ['never-lose-a-lead', 'connect-your-systems'],

    'faq' => [
        ['Can our stock still go on the portals?', 'Yes. Your website does not replace the portals; it becomes the place every enquiry lands and the one site where your brand, not the portal\'s, is front and centre. Feeding stock to a portal depends on the portal\'s own import options, which we check for the ones you use.'],
        ['Who loads the cars?', 'Your team does, from a back office designed for it. Adding a car, its photos and its price takes minutes, and no developer is involved.'],
        ['Are finance documents safe?', 'They are uploaded over an encrypted connection and stored privately, visible only to staff signed in to your back office, which is how POPIA expects personal information to be handled.'],
        ['What does it cost?', 'Showroom is a flat monthly fee, with the build included and no minimum term. The current price is on this page, straight from our price list. Custom work around it is quoted separately.'],
    ],

    'close' => 'How long does a new car take to reach your website today?',
];
