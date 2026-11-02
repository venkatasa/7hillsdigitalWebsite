<?php

/*
 | WhatsApp automation and AI assistants. Owner-confirmed 02/11/2026 (built to
 | order). Built on Meta's official WhatsApp Business Platform: unofficial
 | "WhatsApp Web" bots get numbers banned, and we never sell them.
 */

return [
    'gate' => 'whatsapp-automation',
    'covers' => [],
    'motif' => 'nodes',
    'quote_type' => 'ai-automation',

    'title' => 'WhatsApp Automation and AI Assistants for Business',
    'description' => 'Automatic WhatsApp replies, reminders and an AI assistant on your business number, built on the official WhatsApp Business Platform. POPIA-aware.',

    'eyebrow' => 'WhatsApp automation & AI',
    'h1' => 'Your customers are already on WhatsApp. Answer them in seconds, day and night.',
    'lede' => 'An AI assistant on your business number that answers questions, takes bookings and qualifies leads, sends reminders and updates automatically, and hands the conversation to a person whenever it should.',
    'cta' => 'Discuss WhatsApp automation',

    'visual' => ['type' => 'flow', 'steps' => [['Message', 'A customer writes on WhatsApp'], ['AI reply', 'Answered from your own information'], ['Action', 'Booking, quote or lead captured'], ['Hand-over', 'A person takes over when needed']]],

    'facts' => [
        ['Official WhatsApp Business Platform', 'No unofficial bots that get numbers banned'],
        ['AI with a person in the loop', 'Hand-over to staff at any point'],
        ['Connected to your systems', 'Bookings, leads and orders land where you work'],
    ],

    'problem' => [
        'heading' => 'Most South African customers would rather WhatsApp you than fill in a form or wait on hold.',
        'body' => [
            'So the business WhatsApp fills up: the same questions about prices, hours and availability, booking requests at 22:00, and leads that go cold because nobody saw the message until the next afternoon.',
            'A person on a phone cannot keep up, and a shared phone leaves no record of who promised what. Meanwhile reminders, confirmations and follow-ups that should go out automatically depend on someone remembering.',
            'Automating WhatsApp properly means using the official WhatsApp Business Platform, with customers opting in to messages, an AI that answers only from information you approve, and a clean hand-over to a person.',
        ],
    ],

    'deliverables' => [
        ['An AI assistant on WhatsApp', 'Answers questions from your own price lists, policies and stock, and says so when it does not know.'],
        ['Automatic messages', 'Booking confirmations, reminders, payment and order updates, sent from your systems as things happen.'],
        ['Lead capture and qualification', 'Name, need and budget collected in the chat, then passed to your CRM or back office.'],
        ['Hand-over to staff', 'A shared inbox so your team can see the conversation and take over at any point.'],
        ['Official set-up', 'WhatsApp Business Platform account, verified business profile and approved message templates.'],
        ['Records and consent', 'Opt-in recorded, every conversation kept, and data handled the way POPIA requires.'],
    ],

    'use_cases' => [
        ['Guesthouse or lodge', 'Availability questions arrive at all hours and bookings are lost to faster competitors.', 'The assistant checks dates, quotes the rate, takes the booking and sends a reminder before arrival.'],
        ['Dealership or estate agency', 'Enquiries about a listing go unanswered until the morning.', 'Instant replies with the details, a viewing or test drive booked, and the lead in the pipeline.'],
        ['Service business', 'Staff spend the day confirming appointments and chasing payments.', 'Confirmations, reminders and payment links sent automatically, with replies routed to the right person.'],
    ],

    'technologies_title' => 'Built on the official platform, with the AI you choose.',
    'technologies' => [
        ['WhatsApp', ['WhatsApp Business Platform', 'WhatsApp Cloud API', 'Message templates', 'Shared team inbox']],
        ['AI', ['OpenAI GPT', 'Anthropic Claude', 'Google Gemini', 'Self-hosted open models', 'Retrieval from your documents']],
        ['Connecting it', ['Python', 'Node.js', 'Webhooks', 'REST APIs', 'n8n']],
    ],
    'technologies_note' => 'Meta charges for some WhatsApp conversations, separately from our fee, and approves the message templates used to start them. We set that up with you and show you the costs before anything goes live.',

    'approach' => [
        ['Map the conversations', 'The questions customers ask most, and what a good answer looks like.'],
        ['Set up the number', 'Official business account, verified profile, templates submitted for approval.'],
        ['Build and test', 'The assistant answers from approved information, tested on real questions before launch.'],
        ['Go live and improve', 'Watch the conversations, tighten the answers, and add automations as they earn their place.'],
    ],

    'faq' => [
        ['Can we keep our existing WhatsApp number?', 'Usually, yes. A number moves to the WhatsApp Business Platform from the WhatsApp Business app, which means it stops working in the app on the phone. We plan the move with you so no conversations are lost.'],
        ['Will the AI make things up?', 'It is set up to answer only from information you approve, to say when it does not know, and to hand over to a person. Every conversation is recorded so you can see what it said.'],
        ['Can we message customers who have not written to us?', 'Only those who have opted in, using templates WhatsApp has approved. That is WhatsApp\'s rule and POPIA\'s, and it protects your number from being blocked.'],
        ['What does it cost to run?', 'Our build and support are quoted on the scope. WhatsApp\'s own conversation charges are paid to Meta and depend on volume; we estimate them with you up front.'],
        ['Does it work with our booking system or CRM?', 'Yes, if it has an API, and most do. If it does not, the assistant can create the booking or lead in a back office we build or already run for you.'],
    ],

    'close' => 'What do your customers ask on WhatsApp most often?',

    'related' => ['ai-solutions', 'business-process-automation', 'crm-systems'],
];
