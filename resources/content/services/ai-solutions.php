<?php

/*
 | AI solutions — PREVIEW ONLY.
 | capability_unconfirmed: we run an AI stack internally but have delivered no AI
 | work to a client (06_CAPABILITY_VERIFICATION.md §C). Also requires a written
 | data policy (POPIA s72 cross-border transfer) before publication.
 */

return [
    'gate' => 'ai-business-assistants',
    'covers' => ['ai-document-processing', 'ai-integration', 'intelligent-customer-service'],
    'motif' => 'scan',
    'quote_type' => 'ai-automation',

    'title' => 'AI Solutions for Business: Assistants and Document Processing',
    'description' => 'Practical AI for South African businesses: assistants grounded in your own documents, and document processing with a person in the loop. POPIA-aware.',

    'eyebrow' => 'AI solutions',
    'h1' => 'AI that does a real job in your business, not a demo.',
    'lede' => 'Assistants that answer from your own documents and processing that reads invoices and forms for you, each with a person checking the result and clear rules about where your data goes.',
    'cta' => 'Discuss an AI use case',

    'visual' => ['type' => 'flow', 'steps' => [['Document', 'Arrives by email or upload'], ['Read', 'Fields extracted'], ['Check', 'A person confirms'], ['File', 'Into your system']]],

    'facts' => [
        ['One narrow use case first', 'Measured against the manual way'],
        ['A person in the loop', 'AI proposes; people approve'],
        ['Data rules decided up front', 'What goes to a model, and what never does'],
    ],

    'problem' => [
        'heading' => 'Most AI projects fail because they start with the technology instead of the job.',
        'body' => [
            'A chatbot bolted onto a website, or a pilot nobody measured, proves very little. Meanwhile staff still retype invoices, read through contracts to find one clause, and answer the same internal questions every week.',
            'Useful AI is narrow and measurable. It takes one repeated task, does a first pass a person can check quickly, and gets judged on the hours saved and the errors avoided, with clear rules about whose data is sent where.',
            'There is also a data question every South African business has to answer first. Sending customer information to a model hosted overseas is a cross-border transfer under POPIA, and it needs a lawful basis. Deciding that up front is part of the design, not paperwork afterwards.',
        ],
    ],

    'deliverables' => [
        ['A use-case assessment', 'Which tasks suit AI, which do not, and how success will be measured.'],
        ['Document processing', 'Invoices, forms and contracts read, key fields extracted, and results checked by a person.'],
        ['Internal assistants', 'Answers drawn from your own policies and documents, with the source shown.'],
        ['Integration with your systems', 'Results land in the system you already use, not in another tool.'],
        ['A data policy', 'Written rules on what may be sent to a model, which provider, and what is kept local.'],
        ['Measurement', 'Accuracy and time saved, compared with the manual way, before anything grows.'],
    ],

    'use_cases' => [
        ['Accounts department', 'Supplier invoices are retyped into the accounting system every day.', 'Invoices read automatically, fields proposed for a person to confirm, then posted.'],
        ['HR or compliance team', 'Staff ask the same policy questions by email every week.', 'An assistant that answers from the policy documents and shows which clause it used.'],
        ['Customer service', 'First-line questions take up the time experienced staff should spend on difficult cases.', 'Suggested answers from your knowledge base, with hand-over to a person.'],
    ],

    'technologies_title' => 'How we approach the technology.',
    'technologies' => [
        ['Models', ['Hosted language models', 'Self-hosted open models']],
        ['Connecting it', ['REST APIs', 'Laravel', 'n8n']],
        ['Safeguards', ['Human review', 'Audit log', 'Data minimisation']],
    ],
    'technologies_note' => 'Whether a hosted or a self-hosted model is right depends on the data. Personal information sent outside South Africa needs a lawful basis under POPIA, and the choice is agreed with you in writing.',

    'approach' => [
        ['Pick one task', 'Frequent, repetitive and measurable, with a clear right answer.'],
        ['Agree the data rules', 'What may be processed, where, and by which provider, in writing.'],
        ['Pilot against the manual way', 'Run it alongside your team and measure accuracy and time.'],
        ['Integrate or stop', 'Connect it to your systems if it earns its place. If it does not, stop.'],
    ],

    'faq' => [
        ['Is our data used to train someone else\'s model?', 'That depends on the provider and the plan, which is why it is settled in writing before anything is processed. Where data must not leave your control, a self-hosted model is an option.'],
        ['How accurate is it?', 'It varies by document and task, which is why every project starts with a measured pilot and a person checking results.'],
        ['Is this POPIA-compliant?', 'It can be, if it is designed to be: minimal data, a lawful basis for any cross-border transfer, and a record of processing. We treat that as part of the design, not an afterthought.'],
        ['Do we need custom software for this?', 'Not always. Some tasks fit inside the tools you already use. Others need a small application around the AI step.'],
        ['What does a pilot involve?', 'One task, a sample of real documents or questions, a person checking every result, and a written comparison with the manual way at the end. The pilot decides whether the idea goes further, and stopping is a perfectly good outcome.'],
    ],

    'close' => 'Which task would you hand to an assistant first?',

    'related' => ['business-process-automation', 'api-development-and-integration', 'custom-software-development'],
];
