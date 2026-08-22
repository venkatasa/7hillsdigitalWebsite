@php $brand = config('brand'); @endphp

<x-layouts.site :description="$brand['promise'].' Websites, email and back-office software for estate agencies, dealerships, recruiters and guesthouses across South Africa.'">

    {{-- ── Hero ─────────────────────────────────────────────────────────── --}}
    <section class="relative overflow-hidden bg-brand text-white">
        <div aria-hidden="true" class="pointer-events-none absolute inset-0 opacity-[0.07]">
            <div class="absolute -right-24 -top-24 h-96 w-96 rounded-full bg-accent blur-3xl"></div>
            <div class="absolute -bottom-32 left-1/4 h-80 w-80 rounded-full bg-accent blur-3xl"></div>
        </div>

        <div class="container-page relative py-20 sm:py-28">
            <p class="eyebrow-light">A {{ $brand['parent'] }} company</p>

            <h1 class="mt-4 max-w-3xl text-4xl font-bold leading-[1.1] tracking-tight sm:text-5xl lg:text-6xl">
                Pay nothing until your website is live.
                <span class="text-mint">Cancel any time.</span>
            </h1>

            <p class="mt-6 max-w-2xl text-lg leading-relaxed text-white/75">
                We build the website, set up the email on your own domain, host the lot
                and keep it running — for one monthly fee. No deposit, no setup cost, no
                two-year contract to sign before anyone has shown you anything.
            </p>

            <div class="mt-9 flex flex-wrap gap-3">
                <a href="{{ route('packages.index') }}" class="btn-mint">See the packages</a>
                <a href="{{ route('demos') }}" class="btn-ghost">Look at a live demo</a>
            </div>

            <dl class="mt-14 grid max-w-3xl gap-x-8 gap-y-6 border-t border-white/15 pt-8 sm:grid-cols-3">
                @foreach ([
                    ['R0', 'to start. You pay your first month when the site goes live, not before.'],
                    ['2–3 weeks', 'from the day you send us your photos and copy.'],
                    ['1 month', 'notice to leave. No penalty, no exit fee, no argument.'],
                ] as [$figure, $detail])
                    <div>
                        <dt class="text-2xl font-bold text-mint">{{ $figure }}</dt>
                        <dd class="mt-1.5 text-sm leading-relaxed text-white/65">{{ $detail }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </section>

    {{-- ── The problem ──────────────────────────────────────────────────── --}}
    <section class="container-page py-20 sm:py-24">
        <div class="grid gap-12 lg:grid-cols-2 lg:gap-16">
            <div>
                <p class="eyebrow">Why we exist</p>
                <h2 class="mt-3 text-3xl font-bold tracking-tight text-brand sm:text-4xl">
                    Good businesses running on a Facebook page and a Gmail address.
                </h2>
            </div>
            <div class="space-y-4 text-base leading-relaxed text-secondary">
                <p>
                    Most small firms in this country do not have an IT department. They have
                    an owner who is also the sales manager, a laptop, and whoever in the
                    family is good with computers.
                </p>
                <p>
                    So the stock lives in a WhatsApp group. The enquiries live in one
                    person's phone. The quotes go out from a Gmail address that makes the
                    company look smaller than it is. And the website, if there is one, was
                    built in 2019 by someone who no longer answers.
                </p>
                <p class="font-medium text-brand">
                    We fix that for a monthly fee, and we carry the risk of getting it right.
                </p>
            </div>
        </div>
    </section>

    {{-- ── Everything included ──────────────────────────────────────────── --}}
    <section class="bg-brand-light py-20 sm:py-24">
        <div class="container-page">
            <p class="eyebrow">In every package</p>
            <h2 class="mt-3 max-w-2xl text-3xl font-bold tracking-tight text-brand sm:text-4xl">
                One fee. The website, the email and the person who fixes it.
            </h2>

            <div class="mt-12 grid gap-x-10 gap-y-9 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ([
                    ['A website built for your trade', 'Not a template with your logo dropped in. The pages, forms and fields are the ones your industry actually needs.'],
                    ['A back office you run yourself', 'Add stock, publish a listing, close a vacancy. No developer, no ticket, no waiting until Tuesday.'],
                    ['Email on your own domain', 'Mailboxes at yourname@yourbusiness.co.za, plus unlimited aliases and forwarders at no extra cost.'],
                    ['Hosting, SSL and backups', 'On our servers, with the certificate renewed and the database backed up without you thinking about it.'],
                    ['Updates and security patches', 'The platform is maintained for every client at once, so you are never running last year\'s software.'],
                    ['A human being to phone', 'Support is included. Not a ticket queue in another timezone — the people who built the thing.'],
                ] as [$heading, $copy])
                    <div>
                        <h3 class="flex items-start gap-2.5 text-base font-semibold text-brand">
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
    <section class="container-page py-20 sm:py-24">
        <div class="max-w-2xl">
            <p class="eyebrow">The packages</p>
            <h2 class="mt-3 text-3xl font-bold tracking-tight text-brand sm:text-4xl">
                Four trades we know inside out, and one that covers the rest.
            </h2>
            <p class="mt-4 text-base leading-relaxed text-secondary">
                Each package is the same platform underneath, set up for how your industry
                works. Prices below are the entry tier — the fuller comparison is on the
                packages page.
            </p>
        </div>

        <div class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($packages as $package)
                <x-package-card :package="$package" />
            @endforeach
        </div>

        <p class="mt-8 text-sm text-muted">
            All prices exclude VAT. <a href="{{ route('packages.index') }}" class="font-medium text-brand underline underline-offset-2">Compare every package and tier &rarr;</a>
        </p>

        {{-- Optional modules: flagged here, priced in full on /packages. --}}
        @if ($addOns->isNotEmpty())
            <div class="mt-10 rounded-2xl border border-hairline bg-brand-light px-6 py-6 sm:px-8">
                <div class="flex flex-wrap items-center justify-between gap-x-8 gap-y-4">
                    <div>
                        <h3 class="text-base font-bold text-brand">Need more than a website?</h3>
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

    {{-- ── The catch ────────────────────────────────────────────────────── --}}
    <section class="bg-brand py-20 text-white sm:py-24">
        <div class="container-page grid gap-12 lg:grid-cols-2 lg:gap-16">
            <div>
                <p class="eyebrow-light">The honest part</p>
                <h2 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">
                    "So what's the catch?"
                </h2>
                <p class="mt-5 text-base leading-relaxed text-white/70">
                    Fair question, and the answer is simple enough to put on the home page
                    rather than in clause 14 of an agreement you were never going to read.
                </p>
            </div>

            <div class="space-y-7">
                @foreach ([
                    ['You are renting, not buying.', 'The monthly fee covers the build, the hosting, the email and the support. Stop paying and the site comes down. You are not buying the software, and the code stays ours — that is exactly why there is nothing to pay upfront.'],
                    ['Your domain stays yours. Always.', 'It is registered in your name, and if you leave you take it with you. We will never hold a client\'s domain to win an argument.'],
                    ['Your data leaves with you.', 'Ask on the way out and we export your listings, your contacts and your enquiry history. You gave us that information; it does not become ours.'],
                    ['We do ask you to show up.', 'We cannot write your business into existence. Photos, prices and the answers to our questions have to come from you, or the build stalls and neither of us is happy.'],
                ] as [$heading, $copy])
                    <div class="border-l-2 border-mint pl-5">
                        <h3 class="text-base font-semibold">{{ $heading }}</h3>
                        <p class="mt-1.5 text-sm leading-relaxed text-white/65">{{ $copy }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── Closing CTA ──────────────────────────────────────────────────── --}}
    <section class="container-page py-20 sm:py-24">
        <div class="rounded-3xl border border-brand/10 bg-brand-light px-8 py-14 text-center sm:px-14">
            <h2 class="mx-auto max-w-2xl text-3xl font-bold tracking-tight text-brand sm:text-4xl">
                Tell us what your business does. We will show you what it would look like.
            </h2>
            <p class="mx-auto mt-4 max-w-xl text-base leading-relaxed text-secondary">
                A twenty-minute conversation, then a demo built around your trade. Nothing
                to pay, and nothing to sign until you have seen it working.
            </p>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a href="{{ route('contact') }}" class="btn-accent">Start the conversation</a>
                <a href="{{ route('how-it-works') }}" class="btn-outline">How it works, step by step</a>
            </div>
        </div>
    </section>

</x-layouts.site>
