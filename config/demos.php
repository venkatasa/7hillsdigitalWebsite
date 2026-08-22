<?php

/*
|--------------------------------------------------------------------------
| Demo tenants
|--------------------------------------------------------------------------
|
| Fictional sites that show a prospect what their own site would look like,
| one per product line. Everything in here is INVENTED — the businesses, the
| stock, the prices, the reviews. Nothing is taken from a real agency, dealer
| or portal, and no photograph is loaded from anywhere: the artwork is composed
| in SVG by <x-demo-art>.
|
| Each tenant carries its OWN palette on purpose. Five identical green sites
| would prove nothing; the point of a demo is to show that the client's site
| carries the client's brand, not ours. The 7Hills palette rule in CLAUDE.md
| governs the marketing site, not these.
|
| Demos are single landing pages with no working back office. Every one closes
| with a section inviting the prospect to ask for a walkthrough of the admin
| panel — which is the actual sales conversation.
|
*/

return [

    'realty' => [
        'name' => '7Hills Realty',
        'vertical' => 'Estate agency',
        'package' => 'listings',
        'art' => 'house',
        'palette' => ['primary' => '#12314F', 'accent' => '#3B82A0', 'wash' => '#F2F7FA'],
        'strap' => 'Property in Midrand and the north',
        'hero_title' => 'The right home, in the right suburb, at the right price.',
        'hero_copy' => 'A boutique agency working Carlswald, Waterfall, Blue Hills and Kyalami. We list fewer properties than the big franchises, and we know every one of them.',
        'hero_cta' => 'Browse listings',
        'stats' => [
            ['48', 'properties on our books'],
            ['19 days', 'average time to offer'],
            ['96%', 'of sellers achieve asking'],
        ],
        'items_eyebrow' => 'On the market',
        'items_title' => 'Featured listings',
        'items_note' => 'Updated by our agents themselves — no developer, no waiting.',
        'items' => [
            ['title' => '3 Bed Family Home, Carlswald', 'price' => 'R2 195 000', 'meta' => '3 bed · 2 bath · 2 garage · 620m²', 'badge' => 'For sale'],
            ['title' => '2 Bed Apartment, Waterfall', 'price' => 'R1 450 000', 'meta' => '2 bed · 2 bath · 1 parking · 84m²', 'badge' => 'For sale'],
            ['title' => '4 Bed Cluster, Blue Hills', 'price' => 'R3 100 000', 'meta' => '4 bed · 3 bath · 2 garage · 410m²', 'badge' => 'New'],
            ['title' => '2 Bed Townhouse, Halfway Gardens', 'price' => 'R11 500 pm', 'meta' => '2 bed · 1 bath · carport · pet friendly', 'badge' => 'To let'],
            ['title' => 'Vacant Stand, Kyalami', 'price' => 'R980 000', 'meta' => '1 100m² · level · in a security estate', 'badge' => 'For sale'],
            ['title' => '5 Bed Estate Home, Blair Atholl', 'price' => 'R7 850 000', 'meta' => '5 bed · 5 bath · 3 garage · pool', 'badge' => 'Sole mandate'],
        ],
        'features_title' => 'Why sellers list with us',
        'features' => [
            ['Honest valuations', 'We price to sell, not to win the mandate. An overpriced house sits, goes stale and eventually sells for less than it should have.'],
            ['Every enquiry answered', 'Buyer enquiries land in one place and get worked. Nothing sits in an agent\'s phone over the weekend.'],
            ['Proper vetting', 'Rental applicants submit ID, payslips and bank statements before a viewing, so landlords meet qualified tenants only.'],
        ],
        'testimonial' => ['Listed on the Tuesday, three viewings by the Saturday, offer on the following Wednesday. I had the house with another agency for four months before that.', 'Nomsa D., Carlswald'],
        'contact_title' => 'Thinking of selling?',
        'contact_copy' => 'A free valuation, no obligation, and an honest number rather than a flattering one.',
    ],

    'auto' => [
        'name' => '7Hills Auto',
        'vertical' => 'Car dealership',
        'package' => 'showroom',
        'art' => 'car',
        'palette' => ['primary' => '#191C22', 'accent' => '#C2410C', 'wash' => '#FAF6F3'],
        'strap' => 'Quality pre-owned vehicles',
        'hero_title' => 'Every car on the floor, online before lunchtime.',
        'hero_copy' => 'Independent dealer in Midrand. Full service history on everything we sell, finance arranged through all major banks, trade-ins welcome.',
        'hero_cta' => 'View our stock',
        'stats' => [
            ['62', 'vehicles in stock'],
            ['All banks', 'finance arranged'],
            ['210-point', 'check before it lists'],
        ],
        'items_eyebrow' => 'On the floor',
        'items_title' => 'Current stock',
        'items_note' => 'Reserved and sold move instantly, so the website and the floor never disagree.',
        'items' => [
            ['title' => '2021 Toyota Hilux 2.8 GD-6 Raider 4x4 AT', 'price' => 'R589 900', 'meta' => '68 400 km · Diesel · Automatic', 'badge' => 'Available'],
            ['title' => '2019 Volkswagen Polo 1.0 TSI Comfortline', 'price' => 'R239 900', 'meta' => '91 200 km · Petrol · Manual', 'badge' => 'Available'],
            ['title' => '2022 Ford Ranger 2.0 SiT XLT', 'price' => 'R529 900', 'meta' => '44 700 km · Diesel · Automatic', 'badge' => 'Reserved'],
            ['title' => '2020 Haval Jolion 1.5T Luxury', 'price' => 'R289 900', 'meta' => '57 300 km · Petrol · Automatic', 'badge' => 'Available'],
            ['title' => '2018 Mercedes-Benz C200 AMG Line', 'price' => 'R399 900', 'meta' => '108 500 km · Petrol · Automatic', 'badge' => 'Available'],
            ['title' => '2023 Suzuki Swift 1.2 GL', 'price' => 'R199 900', 'meta' => '22 100 km · Petrol · Manual', 'badge' => 'New arrival'],
        ],
        'features_title' => 'How we sell',
        'features' => [
            ['Pre-qualify online', 'Buyers submit affordability details and documents before they arrive, so the F&I desk is not starting from scratch on a Saturday morning.'],
            ['Trade-in from a photo', 'Customers photograph their current car and get an indicative number back, without driving out to us first.'],
            ['Instalment calculator', 'Deposit, balloon, rate and term on every listing — so the first question is answered before anyone phones.'],
        ],
        'testimonial' => ['Saw the bakkie on the site at seven in the morning, pre-qualified from my phone, drove out at eleven and signed. No wasted trip.', 'Pieter V., Centurion'],
        'contact_title' => 'Want to trade in?',
        'contact_copy' => 'Send us a few photographs and your mileage and we will come back with a number.',
    ],

    'careers' => [
        'name' => '7Hills Careers',
        'vertical' => 'Recruitment agency',
        'package' => 'careers',
        'art' => 'job',
        'palette' => ['primary' => '#241F47', 'accent' => '#7C6BD6', 'wash' => '#F6F5FB'],
        'strap' => 'Recruitment across Gauteng',
        'hero_title' => 'Good people, placed properly.',
        'hero_copy' => 'We recruit for finance, technical, logistics and healthcare roles across Gauteng. Every candidate is screened by a consultant who understands the job — not sorted by a keyword filter.',
        'hero_cta' => 'See open roles',
        'stats' => [
            ['34', 'live vacancies'],
            ['11 days', 'average time to shortlist'],
            ['92%', 'of placements still there at 12 months'],
        ],
        'items_eyebrow' => 'Now hiring',
        'items_title' => 'Open vacancies',
        'items_note' => 'Filled roles drop off the site automatically, so nobody applies for a job that closed a month ago.',
        'items' => [
            ['title' => 'Financial Accountant', 'price' => 'R55 000 – R70 000 pm', 'meta' => 'Sandton · Permanent · CA(SA) or SAIPA', 'badge' => 'Finance'],
            ['title' => 'Diesel Mechanic', 'price' => 'R28 000 pm', 'meta' => 'Midrand · Permanent · Red seal required', 'badge' => 'Technical'],
            ['title' => 'Warehouse Supervisor', 'price' => 'R22 000 pm', 'meta' => 'Kempton Park · Permanent · 5 years\' experience', 'badge' => 'Logistics'],
            ['title' => 'Junior Software Developer', 'price' => 'R35 000 pm', 'meta' => 'Centurion · Hybrid · 1–2 years', 'badge' => 'IT'],
            ['title' => 'Registered Nurse — Theatre', 'price' => 'Negotiable', 'meta' => 'Pretoria East · Permanent · SANC registered', 'badge' => 'Healthcare'],
            ['title' => 'Sales Representative — FMCG', 'price' => 'Basic + commission', 'meta' => 'Gauteng · Permanent · Own vehicle', 'badge' => 'Sales'],
        ],
        'features_title' => 'How we work',
        'features' => [
            ['Applications arrive complete', 'CV, ID and qualifications attached, screening questions already answered. Consultants work a shortlist instead of an inbox.'],
            ['A database that outlives the vacancy', 'The right candidate for a role we filled last year is still findable when the same role opens again.'],
            ['Employers see progress', 'New, screened, shortlisted, submitted, placed. Clients can see where their vacancy actually is.'],
        ],
        'testimonial' => ['We had the role open for two months on the job boards and got 300 unusable CVs. 7Hills sent four people and we hired the second one.', 'Operations Director, logistics group'],
        'contact_title' => 'Hiring, or looking?',
        'contact_copy' => 'Send us the brief or send us your CV. Both go to a person, not an auto-responder.',
    ],

    'stay' => [
        'name' => '7Hills Stay',
        'vertical' => 'Guesthouse',
        'package' => 'stay',
        'art' => 'room',
        'palette' => ['primary' => '#3E2B23', 'accent' => '#C08552', 'wash' => '#FBF7F2'],
        'strap' => 'Guesthouse & self-catering',
        'hero_title' => 'A quiet room, ten minutes from the airport.',
        'hero_copy' => 'Six rooms and two self-catering units on a walled property in Kempton Park. Secure parking, fast wifi, breakfast from six — built for people who arrive late and leave early.',
        'hero_cta' => 'Check availability',
        'stats' => [
            ['8', 'rooms and units'],
            ['9.4', 'average guest rating'],
            ['10 min', 'from OR Tambo'],
        ],
        'items_eyebrow' => 'Where you will sleep',
        'items_title' => 'Rooms and rates',
        'items_note' => 'Rates change by season and are updated by the owner, not by us.',
        'items' => [
            ['title' => 'Garden Suite', 'price' => 'R1 450 / night', 'meta' => 'Sleeps 2 · Queen bed · Private patio', 'badge' => 'Popular'],
            ['title' => 'Family Cottage', 'price' => 'R2 100 / night', 'meta' => 'Sleeps 4 · 2 bedrooms · Kitchenette', 'badge' => 'Self-catering'],
            ['title' => 'Loft Room', 'price' => 'R1 250 / night', 'meta' => 'Sleeps 2 · Double bed · Desk', 'badge' => 'Business'],
            ['title' => 'Executive Studio', 'price' => 'R1 650 / night', 'meta' => 'Sleeps 2 · King bed · Lounge area', 'badge' => 'Available'],
            ['title' => 'Poolside Room', 'price' => 'R1 550 / night', 'meta' => 'Sleeps 3 · Queen + single · Pool access', 'badge' => 'Available'],
            ['title' => 'Self-catering Unit', 'price' => 'R1 850 / night', 'meta' => 'Sleeps 4 · Full kitchen · Braai', 'badge' => 'Long stay'],
        ],
        'features_title' => 'Why book direct',
        'features' => [
            ['No platform commission', 'Booking here costs you the same and costs us fifteen percent less, which is why direct guests get the better rate.'],
            ['We answer ourselves', 'Enquiries come straight to the owner. No call centre, no channel manager losing your special request.'],
            ['Built for early flights', 'Breakfast from six, packed breakfast on request, and a gate that opens whatever time you land.'],
        ],
        'testimonial' => ['Landed at eleven at night and the gate was open, the room was warm and there was a sandwich in the fridge. That is the whole review, really.', 'Grant M., Cape Town'],
        'contact_title' => 'Check your dates',
        'contact_copy' => 'Tell us when you are arriving and how many of you there are, and we will confirm within the hour.',
    ],

    'plant' => [
        'name' => '7Hills Plant Hire',
        'vertical' => 'Plant and tool hire',
        'package' => 'business',
        'art' => 'abstract',
        'palette' => ['primary' => '#123536', 'accent' => '#2C8C86', 'wash' => '#F1F8F7'],
        'strap' => 'Plant, tool and equipment hire',
        'hero_title' => 'The machine you need, on site tomorrow.',
        'hero_copy' => 'Earthmoving, compaction, power and access equipment for contractors across Gauteng. Daily, weekly and monthly rates, delivery arranged, operators available.',
        'hero_cta' => 'See the fleet',
        'stats' => [
            ['140+', 'machines in the fleet'],
            ['24 hrs', 'typical delivery'],
            ['Operators', 'available on request'],
        ],
        'items_eyebrow' => 'The fleet',
        'items_title' => 'Equipment and rates',
        'items_note' => 'This is the Business package with the labels changed — the same engine runs a school\'s prospectus or an attorney\'s services.',
        'items' => [
            ['title' => 'TLB — Tractor Loader Backhoe', 'price' => 'R2 850 / day', 'meta' => 'Excavation · Loading · Trenching', 'badge' => 'Available'],
            ['title' => '5-Ton Excavator', 'price' => 'R3 400 / day', 'meta' => 'Rubber tracks · Operator optional', 'badge' => 'Available'],
            ['title' => 'Ride-on Roller', 'price' => 'R1 950 / day', 'meta' => 'Compaction · 1.5t drum', 'badge' => 'On hire'],
            ['title' => 'Concrete Mixer, 500L', 'price' => 'R650 / day', 'meta' => 'Petrol · Towable', 'badge' => 'Available'],
            ['title' => 'Generator, 60kVA', 'price' => 'R1 800 / day', 'meta' => 'Silent · Diesel · Delivered', 'badge' => 'Available'],
            ['title' => 'Scaffolding, per bay', 'price' => 'R180 / day', 'meta' => 'Erection available · Weekly rates', 'badge' => 'Available'],
        ],
        'features_title' => 'How hiring works',
        'features' => [
            ['Quote before you commit', 'Pick the machine and the dates and we come back with a written quote, delivery included, same day.'],
            ['Documents in one place', 'Company profile, B-BBEE certificate, safety files and rate cards, downloadable without phoning the office.'],
            ['Serviced, not just parked', 'Everything is serviced between hires. A machine that fails on site costs you a day we cannot give back.'],
        ],
        'testimonial' => ['Ordered a TLB at four in the afternoon and it was on site by nine the next morning. That is the whole business, that is.', 'Site agent, civils contractor'],
        'contact_title' => 'Need a quote?',
        'contact_copy' => 'Tell us the machine, the site and the dates. Written quote back the same day.',
    ],

];
