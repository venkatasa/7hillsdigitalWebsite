@php $brand = config('brand'); @endphp

<x-layouts.site :description="$brand['promise'].' Websites and back-office software for estate agencies, dealerships, recruiters, guesthouses — and any other business that runs on something we can build.'">

    {{-- ── Hero ─────────────────────────────────────────────────────────── --}}
    <section class="relative overflow-hidden bg-brand text-white">
        <div aria-hidden="true" class="pointer-events-none absolute inset-0 opacity-[0.10]">
            <div class="glow -right-32 -top-40 h-[34rem] w-[34rem] bg-mint"></div>
            <div class="glow -bottom-48 left-1/4 h-[26rem] w-[26rem] bg-accent"></div>
        </div>

        <div class="container-page relative py-20 sm:py-28 lg:py-32">
            <h1 class="max-w-4xl h-display" data-reveal>
                Pay nothing until your website is live.
                <span class="text-mint">Cancel any time.</span>
            </h1>

            <p class="mt-6 max-w-2xl lede text-white/75" data-reveal data-reveal-delay="120">
                We build the website, host it and keep it running — for one monthly fee. No
                deposit, no setup cost, no two-year contract to sign before anyone has shown
                you anything.
            </p>

            <div class="mt-9 flex flex-wrap gap-3" data-reveal data-reveal-delay="180">
                <a href="{{ route('packages.index') }}" class="btn-mint btn-lg">See the packages</a>
                <a href="{{ route('demos') }}" class="btn-ghost btn-lg">Look at a live demo</a>
            </div>

            <dl class="mt-14 grid max-w-3xl gap-x-8 gap-y-8 border-t border-white/15 pt-9 sm:grid-cols-3"
                data-reveal data-reveal-delay="240">
                @foreach ([
                    ['R0', 'to start. You pay your first month when the site goes live, not before.'],
                    ['2–3 weeks', 'from the day you send us your photos and copy.'],
                    ['1 month', 'notice to leave. No penalty, no exit fee, no argument.'],
                ] as [$figure, $detail])
                    <div>
                        <dt class="font-display text-3xl font-extrabold text-mint">{{ $figure }}</dt>
                        <dd class="mt-2 text-sm leading-relaxed text-white/65">{{ $detail }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </section>

    {{-- ── The problem ──────────────────────────────────────────────────── --}}
    <section class="container-page section">
        <div class="grid gap-10 lg:grid-cols-2 lg:gap-16">
            <div data-reveal>
                <p class="eyebrow">Why we exist</p>
                <h2 class="mt-3 h-section text-brand">
                    Good businesses running on a Facebook page and a Gmail address.
                </h2>
            </div>
            <div class="space-y-4 leading-relaxed text-secondary" data-reveal data-reveal-delay="90">
                <p>
                    Most small firms in this country do not have an IT department. They have an
                    owner who is also the sales manager, a laptop, and whoever in the family is
                    good with computers.
                </p>
                <p>
                    So the stock lives in a WhatsApp group. The enquiries live in one person's
                    phone. The quotes go out from a Gmail address that makes the company look
                    smaller than it is. And the website, if there is one, was built in 2019 by
                    someone who no longer answers.
                </p>
                <p class="font-medium text-brand">
                    We fix that for a monthly fee, and we carry the risk of getting it right.
                </p>
            </div>
        </div>
    </section>

    {{-- ── Everything included ──────────────────────────────────────────── --}}
    <section class="bg-brand-light section">
        <div class="container-page">
            <div class="max-w-2xl" data-reveal>
                <p class="eyebrow">In every package</p>
                <h2 class="mt-3 h-section text-brand">
                    One fee. The website, the hosting and the person who fixes it.
                </h2>
            </div>

            <div class="mt-12 grid gap-x-10 gap-y-9 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ([
                    ['A website built for your trade', 'Not a template with your logo dropped in. The pages, forms and fields are the ones your industry actually needs.'],
                    ['A back office you run yourself', 'Add stock, publish a listing, close a vacancy. No developer, no ticket, no waiting until Tuesday.'],
                    ['Email when you want it', 'Mailboxes at yourname@yourbusiness.co.za are not bundled into the fee — they are R20 a month each, so you pay for the ones you actually use.'],
                    ['Hosting, SSL and backups', 'On our servers, with the certificate renewed and the database backed up without you thinking about it.'],
                    ['Updates and security patches', 'The platform is maintained for every client at once, so you are never running last year\'s software.'],
                    ['A human being to phone', 'Support is included. Not a ticket queue in another timezone — the people who built the thing.'],
                ] as $i => [$heading, $copy])
                    <div data-reveal data-reveal-delay="{{ ($i % 3) * 70 }}">
                        <h3 class="flex items-start gap-2.5 font-display text-base font-bold text-brand">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-accent" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.7 5.3a1 1 0 0 1 0 1.4l-7.5 7.5a1 1 0 0 1-1.4 0L3.3 9.7a1 1 0 1 1 1.4-1.4l3.8 3.8 6.8-6.8a1 1 0 0 1 1.4 0Z" clip-rule="evenodd"/>
                            </svg>
                            {{ $heading }}
                        </h3>
                        <p class="mt-2 pl-[30px] text-sm leading-relaxed text-secondary">{{ $copy }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── Packages ─────────────────────────────────────────────────────── --}}
    <section class="container-page section">
        <div class="max-w-2xl" data-reveal>
            <p class="eyebrow">The packages</p>
            <h2 class="mt-3 h-section text-brand">
                Four trades we know inside out, and one that covers the rest.
            </h2>
            <p class="mt-4 leading-relaxed text-secondary">
                Each package is the same platform underneath, set up for how your industry
                works. Prices below are the entry tier — the fuller comparison is on the
                packages page.
            </p>
        </div>

        <div class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($packages as $package)
                <div data-reveal data-reveal-delay="{{ ($loop->index % 3) * 70 }}">
                    <x-package-card :package="$package" />
                </div>
            @endforeach
        </div>

        <p class="mt-8 text-sm text-muted" data-reveal>
            All prices exclude VAT.
            <a href="{{ route('packages.index') }}" class="font-medium text-brand underline underline-offset-2 hover:text-accent">Compare every package and tier &rarr;</a>
        </p>

        {{-- Optional modules: flagged here, priced in full on /packages. --}}
        @if ($addOns->isNotEmpty())
            <div class="mt-10 rounded-2xl border border-hairline bg-brand-light px-6 py-6 sm:px-8" data-reveal>
                <div class="flex flex-wrap items-center justify-between gap-x-8 gap-y-4">
                    <div>
                        <h3 class="font-display text-base font-bold text-brand">Need more than a website?</h3>
                        <p class="mt-1 text-sm leading-relaxed text-secondary">
                            Invoicing, CRM and ERP bolt onto any package — charged separately, never bundled in without asking.
                        </p>
                    </div>
                    <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-sm">
                        @foreach ($addOns as $addOn)
                            <span class="text-secondary">
                                <span class="font-semibold text-brand">{{ $addOn->name }}</span>
                                {{ $addOn->is_metered ? 'on request' : '+'.$addOn->formatted_price.'/mo' }}
                            </span>
                        @endforeach
                        <a href="{{ route('packages.index') }}#add-ons" class="font-semibold text-accent underline underline-offset-2 hover:text-brand">See what they do &rarr;</a>
                    </div>
                </div>
            </div>
        @endif
    </section>

    {{-- ── Beyond the packages ──────────────────────────────────────────── --}}
    <section class="relative overflow-hidden bg-brand-light section">
        <div class="container-page">
            <div class="grid items-center gap-12 lg:grid-cols-[1.05fr_0.95fr] lg:gap-16">
                <div data-reveal>
                    <p class="eyebrow">Not on the list?</p>
                    <h2 class="mt-3 h-section text-brand">
                        Your industry does not have to be one of the five.
                    </h2>
                    <div class="mt-6 space-y-4 leading-relaxed text-secondary">
                        <p>
                            Those five packages exist because those industries kept asking for the
                            same thing. They are a starting point, not a limit. If your business
                            has a process, we can put it on a screen.
                        </p>
                        <p>
                            We build custom software, digitise whatever is still on paper, automate
                            the jobs somebody repeats every week, make the systems you already pay
                            for talk to each other, source your hardware and support the lot.
                        </p>
                        <p class="font-medium text-brand">
                            You focus on running the business. We will take care of the IT.
                        </p>
                    </div>

                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('it-services') }}" class="btn-accent">See what else we build</a>
                        <a href="{{ route('contact') }}" class="btn-outline">Ask about your industry</a>
                    </div>
                </div>

                <ul class="grid gap-3 sm:grid-cols-2" data-reveal data-reveal-delay="120">
                    @foreach ([
                        ['module', 'Custom software'],
                        ['invoice', 'Digitising paper'],
                        ['boxes', 'Automating repetition'],
                        ['people', 'System integrations'],
                        ['globe', 'Day-to-day IT support'],
                        ['car', 'Hardware and procurement'],
                    ] as $item)
                        <li class="flex items-center gap-3 rounded-xl border border-hairline bg-white px-4 py-4">
                            <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-accent/10 text-accent">
                                <x-package-icon :name="$item[0]" class="h-5 w-5" />
                            </span>
                            <span class="text-sm font-semibold text-brand">{{ $item[1] }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>

    {{-- ── The catch ────────────────────────────────────────────────────── --}}
    <section class="bg-brand section text-white">
        <div class="container-page grid gap-12 lg:grid-cols-2 lg:gap-16">
            <div data-reveal>
                <p class="eyebrow-light">The honest part</p>
                <h2 class="mt-3 h-section">"So what's the catch?"</h2>
                <p class="mt-5 leading-relaxed text-white/70">
                    Fair question, and the answer is simple enough to put on the home page
                    rather than in clause 14 of an agreement you were never going to read.
                </p>
            </div>

            <div class="space-y-7" data-reveal data-reveal-delay="100">
                @foreach ([
                    ['You are renting, not buying.', 'The monthly fee covers the build, the hosting and the support. Stop paying and the site comes down. You are not buying the software, and the code stays ours — that is exactly why there is nothing to pay upfront.'],
                    ['Your domain stays yours. Always.', 'It is registered in your name, and if you leave you take it with you. We will never hold a client\'s domain to win an argument.'],
                    ['Your data leaves with you.', 'Ask on the way out and we export your listings, your contacts and your enquiry history. You gave us that information; it does not become ours.'],
                    ['We do ask you to show up.', 'We cannot write your business into existence. Photos, prices and the answers to our questions have to come from you, or the build stalls and neither of us is happy.'],
                ] as [$heading, $copy])
                    <div class="border-l-2 border-mint pl-5">
                        <h3 class="font-display text-base font-bold">{{ $heading }}</h3>
                        <p class="mt-1.5 text-sm leading-relaxed text-white/65">{{ $copy }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── Live work ─────────────────────────────────────────────────────
         Real installs, from config/clients.php. The demos elsewhere on this
         site are openly invented, which makes them useless as proof — this is
         the section that answers "yes, but has anyone actually bought it".

         Deliberately links public sites only. CLAUDE.md forbids demoing
         Excellent Real Estate: its panel holds real leads and applicant ID
         documents, so a walkthrough is a POPIA breach. Nothing here invites
         one. ──────────────────────────────────────────────────────────── --}}
    @php $clients = config('clients', []); @endphp

    @if (filled($clients))
        <section class="container-page section">
            <div class="max-w-2xl" data-reveal>
                <p class="eyebrow">Live work</p>
                <h2 class="mt-3 h-section text-brand">
                    The demos are invented. This one is a real business, open for
                    trade this morning.
                </h2>
                <p class="mt-4 leading-relaxed text-secondary">
                    Everything on the demo pages is fiction, and says so. So here is the
                    other thing — a client running on the platform every day, with their
                    own stock, their own enquiries and their own invoices going out.
                </p>
            </div>

            <div class="mt-12 grid gap-6 md:grid-cols-2">
                @foreach ($clients as $client)
                    <article class="card flex h-full flex-col p-7"
                             data-reveal data-reveal-delay="{{ $loop->index * 80 }}">
                        <p class="eyebrow">{{ $client['vertical'] }}</p>
                        <h3 class="mt-2 text-xl font-semibold text-brand">{{ $client['name'] }}</h3>

                        <p class="mt-3 leading-relaxed text-secondary">{{ $client['summary'] }}</p>

                        <ul class="mt-5 space-y-2 text-sm text-secondary">
                            @foreach ($client['delivered'] as $item)
                                <li class="flex items-start gap-2.5">
                                    <svg class="mt-1 h-4 w-4 shrink-0 text-accent" fill="none"
                                         stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24"
                                         aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="m4.5 12.75 6 6 9-13.5" />
                                    </svg>
                                    {{ $item }}
                                </li>
                            @endforeach
                        </ul>

                        <div class="mt-auto pt-6">
                            {{-- Whose software it is, stated plainly. A prospect who assumes
                                 we wrote Invoice Ninja finds out the moment they Google it. --}}
                            <p class="text-xs text-muted">{{ $client['software'] }}</p>

                            @if (! empty($client['url']))
                                <a href="{{ $client['url'] }}" target="_blank" rel="noopener"
                                   class="mt-3 inline-flex items-center gap-1.5 text-sm font-semibold text-brand hover:text-accent">
                                    Visit the live site
                                    <span aria-hidden="true">&rarr;</span>
                                </a>
                            @else
                                <p class="mt-3 text-sm text-muted">
                                    Behind the client&rsquo;s login, so there is nothing public to show.
                                </p>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>

            <p class="mt-8 max-w-2xl text-sm text-muted" data-reveal>
                We do not walk prospects through a client&rsquo;s back office. It holds their
                customers&rsquo; personal information, and that is not ours to show. Ask instead
                for a walkthrough of the same panel on a demo install.
            </p>
        </section>
    @endif

    {{-- ── Closing CTA ──────────────────────────────────────────────────── --}}
    <section class="container-page section">
        <div class="rounded-3xl border border-hairline bg-brand-light px-8 py-14 text-center sm:px-14" data-reveal>
            <h2 class="mx-auto max-w-3xl h-section text-brand">
                Tell us what your business does. We will show you what it would look like.
            </h2>
            <p class="mx-auto mt-4 max-w-2xl lede text-secondary">
                A twenty-minute conversation, then a demo built around your trade. Nothing to
                pay, and nothing to sign until you have seen it working.
            </p>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a href="{{ route('contact') }}" class="btn-accent btn-lg">Start the conversation</a>
                <a href="{{ route('how-it-works') }}" class="btn-outline btn-lg">How it works, step by step</a>
            </div>
        </div>
    </section>

</x-layouts.site>
