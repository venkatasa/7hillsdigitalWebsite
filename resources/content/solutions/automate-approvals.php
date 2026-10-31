<?php

/*
 | Solution: approvals wait on paper.
 | Solved by workflow and approval automation, procurement and approval systems
 | and HR portals (all built_to_order). No proof yet: omitted, not padded.
 */

return [
    'quote_type' => 'ai-automation',
    'motif' => 'flow',

    'title' => 'Automate Approvals: Digital Workflows With an Audit Trail',
    'description' => 'Leave requests, purchase orders and sign-offs routed to the right person, with reminders and a record of every decision, not forms waiting on a desk.',

    'eyebrow' => 'Automate approvals',
    'h1' => 'Requests reach the right person, with a record.',
    'lede' => 'We replace paper forms and email chains with a simple workflow: a request is submitted once, routed by your rules, approved or declined with a reason, and kept on file for whoever needs to check it later.',
    'cta' => 'Map an approval with us',

    'symptoms' => [
        'Forms wait in an in-tray until the manager is back in the office.',
        '"Did you approve this?" is answered by searching through email.',
        'Purchases are made first and approved afterwards.',
        'Nobody knows where a request is stuck, only that it is late.',
        'An auditor asks for evidence of a sign-off and it takes a day to find.',
    ],

    'cost' => [
        'heading' => 'A slow approval costs more than the thing being approved.',
        'body' => [
            'A purchase order that waits a week holds up a job. A leave form that is lost becomes an argument. An expense claim nobody signs becomes a resentful employee. None of these is dramatic on its own, but together they are a steady drag on the business.',
            'Paper and email also leave a weak record. When a decision has to be justified later, to an auditor, a client or a court, you want to show who approved what, when and on what basis, without relying on someone\'s memory.',
            'Most approvals follow rules that are already known: amounts above a limit go to a director, leave goes to the line manager, anything over budget needs finance. Those rules can be written down once and applied every time.',
        ],
    ],

    'shift' => [
        ['Submitting', 'A printed form or an email', 'One online form, the same every time'],
        ['Routing', 'Walked from desk to desk', 'Sent by your rules to the right person'],
        ['Waiting', 'Invisible until it is late', 'Reminders, and a status anyone can check'],
        ['The record', 'Scattered across inboxes', 'Every decision kept with a name, date and reason'],
    ],

    'services' => [
        ['workflow-and-approval-automation', 'The workflow engine: forms, routing rules, reminders, escalation and the decision log.'],
        ['procurement-and-approval-systems', 'Purchase requests and orders with budget limits and supplier records, for spending decisions.'],
        ['hr-and-employee-portals', 'Leave, expense and onboarding requests in one place staff can use from their phone.'],
    ],

    'flow' => [['Request', 'Submitted once'], ['Route', 'By amount or type'], ['Decide', 'Approve or decline'], ['Record', 'Kept with a reason']],

    'inputs' => [
        ['The current form', 'Paper, PDF or email template, whatever people fill in today.'],
        ['Your rules', 'Who approves what, the limits that change the route, and who stands in when someone is away.'],
        ['Where results go', 'The system or person that acts on an approval, such as payroll or the buyer.'],
        ['What must be kept', 'How long records are retained and who may see them later.'],
    ],

    'faq' => [
        ['Can managers approve from their phone?', 'Yes. Approvals are designed to be done in under a minute from a phone, with the details needed to decide on one screen and a clear approve or decline.'],
        ['What if the approver is on leave?', 'You decide: a named deputy, an automatic escalation after a set time, or both. The rule is written into the workflow so nothing waits on someone\'s holiday.'],
        ['Do we need new software for every form?', 'No. Once the workflow exists, adding another type of request is usually a matter of a new form and a new set of rules, not a new system.'],
        ['Does this replace our HR or accounting system?', 'Usually not. The workflow handles the request and the decision, then hands the result to the system that already owns it.'],
    ],

    'close' => 'Which form spends the longest waiting on a desk?',

    'industries' => ['corporate', 'professional-services', 'hospitality'],
];
