<?php

/*
 | Business Process Automation — verified (built_to_order), partial evidence: the
 | automation inside our own enquiry pipeline (generated references, notification
 | mail, save-before-send) and n8n used internally. No client automation project
 | yet, so the proof section describes our own system only.
 */

return [
    'gate' => 'business-process-automation',
    'covers' => ['workflow-and-approval-automation', 'procurement-and-approval-systems'],
    'motif' => 'flow',
    'quote_type' => 'ai-automation',

    'title' => 'Business Process Automation and Approval Workflows',
    'description' => 'Automate the repeated work in your business: approvals, reminders, hand-offs and document generation, with a record of every step. Built in South Africa.',

    'eyebrow' => 'Business process automation',
    'h1' => 'Take the Friday-afternoon job off someone\'s desk.',
    'lede' => 'Reminders, approvals, hand-offs and paperwork that run themselves, with a record of who did what and when, so your people spend their week on work only people can do.',
    'cta' => 'Automate a process',

    'visual' => ['type' => 'flow', 'steps' => [['Request', 'Captured once'], ['Approve', 'Routed to the right person'], ['Act', 'The system does the work'], ['Record', 'Every step logged']]],

    'facts' => [
        ['One process at a time', 'Start small, prove it, then the next'],
        ['A record of every step', 'Who approved what, and when'],
        ['People stay in charge', 'Automation does the routine; people decide'],
    ],

    'problem' => [
        'heading' => 'The work nobody notices is the work that quietly eats the week.',
        'body' => [
            'Chasing an approval. Sending the same reminder every month. Copying a form into a system, then emailing a PDF of it to three people. None of it is difficult, and all of it takes time, depends on memory and gets missed when someone is away.',
            'Automating it does not replace the people. It removes the routine, so a request reaches the right person straight away, follow-ups go out on time, and there is a record to point to when someone asks what happened.',
            'Automation also makes a business easier to hand over. When the rules live in a system instead of in someone\'s head, a new staff member can follow them on day one, and a process does not stop because the person who knew it is on leave.',
        ],
    ],

    'deliverables' => [
        ['A process map', 'The steps as they really happen today, where they stall, and what the automated version will do instead.'],
        ['Approval workflows', 'Requests routed by rule: amount, department or type. Approvers notified, decisions recorded.'],
        ['Scheduled jobs', 'Reminders, renewals, follow-ups and reports that run on a timetable without anyone remembering.'],
        ['Generated documents', 'Quotes, letters and confirmations produced from your data, in your template.'],
        ['Notifications that matter', 'Email or messages when something needs a person, not a flood for everything else.'],
        ['An audit trail', 'Every automated step and human decision logged, so the record answers the question.'],
    ],

    'use_cases' => [
        ['Finance team', 'Purchase requests arrive by email, and approvals are chased in person.', 'A request form that routes by amount, notifies the approver and files the decision with the request.'],
        ['Service business', 'Contract renewals and follow-ups depend on someone checking a calendar.', 'Scheduled reminders to the customer and the account owner, with escalation if nothing happens.'],
        ['Administrative office', 'The same letter is typed out each time with different details.', 'Documents generated from the record in your template, ready to check and send.'],
    ],

    'technologies' => [
        ['AI steps', ['OpenAI, Claude and Gemini', 'Document AI', 'AI assistants']],
        ['Messaging', ['WhatsApp Business Platform', 'Email', 'SMS']],
        ['Building it', ['Python', 'Node.js', 'Java', 'PHP and Laravel', 'Schedulers and queues']],
        ['Visual workflows', ['n8n']],
    ],
    'technologies_note' => 'Where a process lives inside software we build, we automate it in that software. A visual workflow tool suits simpler hand-offs between existing products.',

    'approach' => [
        ['Map one process', 'With the people who do it, end to end, including the exceptions.'],
        ['Agree what changes', 'A written scope: what the system will do, what people will still decide.'],
        ['Run it side by side', 'The automated version runs alongside the manual one until the results match.'],
        ['Measure, then the next', 'Confirm the time saved, then pick the next process.'],
    ],

    'proof' => [
        'heading' => 'Automation we rely on ourselves.',
        'body' => 'Every enquiry to this website is saved first, given a reference automatically, and only then sent to us by email. If the mail fails, the lead is still waiting in the back office. Small rules like these are what dependable automation looks like.',
        'flow' => [['Enquiry', 'Submitted on the site'], ['Saved', 'Before anything else'], ['Referenced', 'ENQ number generated'], ['Notified', 'Mail sent, failure logged']],
    ],

    'faq' => [
        ['Which processes are worth automating first?', 'The ones that are frequent, rule-based and annoying: the same steps every time, with a clear right answer. We usually start with the one that costs the most hours or causes the most mistakes.'],
        ['Will automation replace our staff?', 'That is not the aim. It removes the routine so people spend their time on judgement, customers and exceptions. The decisions stay with people.'],
        ['What if the rules change?', 'Rules are configured, not buried in code, wherever practical. When your process changes, the automation is updated with it as part of support.'],
        ['Can it work with Microsoft 365 or our existing tools?', 'If the tools offer a way to connect, usually yes. We confirm that during the process mapping, before quoting.'],
        ['How do we know it is working?', 'Before anything is automated we record how long the manual process takes and how often it goes wrong. The automated version runs alongside it until the results match, and we compare the two. If it does not save time or prevent mistakes, it is not worth keeping, and we will say so.'],
    ],

    'close' => 'Which job does someone repeat every week?',

    'related' => ['api-development-and-integration', 'custom-software-development', 'crm-systems'],
];
