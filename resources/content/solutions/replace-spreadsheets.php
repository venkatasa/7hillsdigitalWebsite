<?php

/*
 | Solution: work lives in spreadsheets.
 | Solved by custom software and web applications (both built_to_order).
 | Proof is this site's own back office: prices are edited in a panel we built,
 | shown from the staging install with fictional data.
 */

return [
    'quote_type' => 'custom-development',
    'motif' => 'grid',

    'title' => 'Replace Spreadsheets With a Business System',
    'description' => 'When the business runs on shared spreadsheets, we replace them with one web system: one record, clear permissions, and a history of every change.',

    'eyebrow' => 'Replace spreadsheets',
    'h1' => 'One system everyone works in, not twelve spreadsheets.',
    'lede' => 'We turn the spreadsheet your business quietly depends on into a proper web application, with a single record of the truth, the right access for each person, and a log of who changed what.',
    'cta' => 'Show us the spreadsheet',

    'symptoms' => [
        'Someone asks "which version is the latest?" at least once a week.',
        'One person understands the formulas, and everyone dreads their leave.',
        'Figures are copied by hand from one sheet into another, and into the accounting system.',
        'You cannot tell who changed a number, or when.',
        'The file is emailed around, so customer details sit in a dozen inboxes.',
    ],

    'cost' => [
        'heading' => 'A spreadsheet is a fine start and an expensive habit.',
        'body' => [
            'Most systems we build began life as a spreadsheet someone made in an afternoon. It worked, so it grew: more tabs, more colleagues, more copies. By the time it runs a department, every error costs real money and nobody can see where the error came from.',
            'The cost rarely shows up as one failure. It shows up as hours spent reconciling copies, a quote sent with last month\'s price, a stock count that does not match the shelf, and a business that cannot grow past the one person who understands the workbook.',
            'There is a privacy cost too. A sheet of customer names, numbers and ID details forwarded by email is personal information spread across inboxes you do not control, which is hard to square with POPIA\'s requirement to keep it secure.',
        ],
    ],

    'shift' => [
        ['The record', 'Several copies, each slightly different', 'One database, one current version'],
        ['Access', 'Whoever has the file sees everything', 'Each person sees what their role needs'],
        ['Changes', 'Overwritten without trace', 'Every change logged with a name and time'],
        ['Reporting', 'Rebuilt by hand at month-end', 'Totals and lists that are always up to date'],
    ],

    'services' => [
        ['custom-software-development', 'Designs the system around how your team already works, so it replaces the spreadsheet rather than adding a second place to type.'],
        ['web-application-development', 'Puts it in a browser, on any device, with sign-in and permissions, so nobody needs the file on their own laptop.'],
        ['database-development', 'Moves your existing rows across cleanly and gives the data a structure that stays reliable as it grows.'],
    ],

    'flow' => [['Your sheet', 'As it is today'], ['Map it', 'Columns become fields'], ['Build', 'Screens and rules'], ['Move the data', 'Checked row by row']],

    'inputs' => [
        ['The spreadsheet itself', 'A copy with real structure. Sensitive values can be blanked out before you send it.'],
        ['The person who knows it best', 'An hour or two of their time to explain what each tab is really for.'],
        ['Who does what', 'Which roles add, approve or only read, so permissions match the business.'],
        ['A cut-over date', 'When everyone stops using the old file, so there is never a period with two truths.'],
    ],

    'proof' => [
        'heading' => 'We run our own business this way.',
        'body' => 'The prices on this website are not kept in a spreadsheet. They live in a back office we built, where a change is made once and every page follows. The screenshot is our staging install, with invented data.',
        'image' => 'admin-packages',
        'url' => '7hillsdigital.co.za/admin/packages',
    ],

    'faq' => [
        ['Can we keep using Excel for some things?', 'Yes. Most systems we build export to a spreadsheet for anyone who wants to slice the data their own way. The difference is that the export is a copy for analysis, not the record everyone edits.'],
        ['What happens to the data we already have?', 'We import it. Part of the work is cleaning it: merging duplicates, fixing dates stored as text, and agreeing what to do with the rows that do not fit. You check the result before the old file is retired.'],
        ['Is this not what off-the-shelf software is for?', 'Sometimes it is, and if a product already fits we will say so. A spreadsheet usually exists because nothing off the shelf matched how the business works, which is exactly the case for building something.'],
        ['How do we start without a big project?', 'Start with the one sheet that hurts most. A focused first version replaces it, the team uses it for a few weeks, and the next piece is decided on what you learned rather than on a guess.'],
    ],

    'close' => 'Which spreadsheet would you retire first?',

    'industries' => ['professional-services', 'corporate', 'automotive'],
];
