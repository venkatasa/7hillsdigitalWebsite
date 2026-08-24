@php $brand = config('brand'); @endphp

<x-layouts.site title="Packages and pricing"
                description="Monthly packages for estate agencies, car dealerships, recruitment agencies, guesthouses and everyone else. No upfront cost, cancel any time.">

    <section class="bg-brand text-white">
        <div class="container-page section">
            <p class="eyebrow-light">Packages</p>
            <h1 class="mt-4 max-w-3xl h-display">
                One monthly fee. Your website, built, hosted and supported.
            </h1>
            <p class="mt-6 max-w-2xl lede text-white/75">
                Every package starts on the 5GB tier and moves up when your site outgrows
                it. Email is priced separately, from R20 a mailbox. Nothing to pay until your
                site is live.
            </p>
        </div>
    </section>

    {{-- ── Package cards ────────────────────────────────────────────────── --}}
    <section class="container-page section">
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($packages as $package)
                <x-package-card :package="$package" />
            @endforeach
        </div>
    </section>

    {{-- ── Full comparison ──────────────────────────────────────────────── --}}
    <section class="container-page pb-16 sm:pb-20 lg:pb-24">
        <h2 class="h-section text-brand">Every tier, side by side</h2>
        <p class="mt-3 max-w-2xl text-sm leading-relaxed text-secondary">
            The tier is your website's storage allowance — pages, photographs and documents.
            A dealership with sixty cars and twelve photographs each will want the larger one.
        </p>

        <div class="mt-8 overflow-x-auto rounded-xl border border-brand/10">
            <table class="w-full min-w-[46rem] text-left text-sm">
                <thead class="bg-brand-light text-xs uppercase tracking-wider text-brand">
                    <tr>
                        <th scope="col" class="px-5 py-3.5 font-semibold">Package</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold">For</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold">Website storage</th>
                        <th scope="col" class="px-5 py-3.5 text-right font-semibold">Per month</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-brand/10">
                    @foreach ($packages as $package)
                        @foreach ($package->tiers as $tier)
                            <tr class="{{ $tier->is_default ? 'bg-white' : 'bg-brand-light/40' }}">
                                <th scope="row" class="px-5 py-3.5 font-semibold text-brand">
                                    <a href="{{ route('packages.show', $package) }}" class="hover:text-accent">{{ $tier->name }}</a>
                                </th>
                                <td class="px-5 py-3.5 text-secondary">{{ $loop->first ? $package->vertical_label : '' }}</td>
                                <td class="px-5 py-3.5 text-secondary">{{ $tier->media_gb }}GB</td>
                                <td class="px-5 py-3.5 text-right font-semibold text-brand">{{ $tier->formatted_price }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>

        <p class="mt-4 text-xs text-muted">
            All prices exclude VAT and cover the website only — the build itself costs
            nothing. {{ $brand['storage']['email_note'] }}
        </p>
    </section>

    {{-- ── What R880 actually buys, and who owns what ──────────────────── --}}
    <section class="bg-brand section text-white">
        <div class="container-page">
            <div class="grid gap-12 lg:grid-cols-2 lg:gap-16">
                <div data-reveal>
                    <p class="eyebrow-light">What you are actually buying</p>
                    <h2 class="mt-3 h-section">R880 a month, and nothing to build it.</h2>
                    <p class="mt-5 leading-relaxed text-white/70">
                        The website is designed and built at no charge. You start paying the
                        month it goes live, and the fee covers the site, the hosting, the
                        security updates and someone to phone. Email is separate — see below.
                    </p>

                    <ul class="mt-8 space-y-3">
                        @foreach ([
                            '5GB of storage for your pages, photographs and documents',
                            'Your domain pointed at it, or one registered for you',
                            'Hosting, SSL, daily backups and software maintenance',
                            'Support from the people who built it',
                        ] as $line)
                            <li class="flex gap-3">
                                <svg class="mt-0.5 h-5 w-5 shrink-0 text-mint" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M16.7 5.3a1 1 0 0 1 0 1.4l-7.5 7.5a1 1 0 0 1-1.4 0L3.3 9.7a1 1 0 1 1 1.4-1.4l3.8 3.8 6.8-6.8a1 1 0 0 1 1.4 0Z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm leading-relaxed text-white/80">{{ $line }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <p class="mt-6 text-sm leading-relaxed text-white/60">
                        The 10 tier doubles the storage for R1 180.
                    </p>
                </div>

                <div class="space-y-6" data-reveal data-reveal-delay="100">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-mint">Who owns what</p>

                    <div class="rounded-2xl border border-white/15 bg-white/[0.04] p-6">
                        <h3 class="font-display text-base font-bold">Yours, always</h3>
                        <p class="mt-2 text-sm leading-relaxed text-white/70">
                            {{ str_replace(':company', $brand['name'], $brand['ownership']['yours']) }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-white/15 bg-white/[0.04] p-6">
                        <h3 class="font-display text-base font-bold">Ours</h3>
                        <p class="mt-2 text-sm leading-relaxed text-white/70">
                            {{ str_replace(':company', $brand['name'], $brand['ownership']['ours']) }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-white/15 bg-white/[0.04] p-6">
                        <h3 class="font-display text-base font-bold">If you leave</h3>
                        <p class="mt-2 text-sm leading-relaxed text-white/70">
                            {{ str_replace(':company', $brand['name'], $brand['ownership']['exit']) }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-mint/40 bg-mint/[0.08] p-6">
                        <h3 class="font-display text-base font-bold text-mint">Want to own it outright?</h3>
                        <p class="mt-2 text-sm leading-relaxed text-white/75">
                            {{ str_replace(':company', $brand['name'], $brand['ownership']['buyout']) }}
                        </p>
                        <a href="{{ route('contact') }}" class="mt-4 inline-flex text-sm font-semibold text-mint underline underline-offset-4 hover:brightness-110">
                            Ask for a build-and-own quote &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── Optional modules, charged separately ──────────────────────── --}}
    <x-add-ons-section :add-ons="$addOns" />

    {{-- ── Upgrades ─────────────────────────────────────────────────────── --}}
    <section class="bg-brand-light section">
        <div class="container-page">
            <p class="eyebrow">When you outgrow it</p>
            <h2 class="mt-3 max-w-2xl h-section text-brand">
                Move up a tier the month you need to, not a month before.
            </h2>

            <div class="mt-10 grid gap-6 sm:grid-cols-3">
                @foreach ([
                    ['Go from 5 to 10', 'Double your website storage to 10GB. Takes effect the same day; billing changes on your next invoice.'],
                    ['Go beyond 10', 'For a photo-heavy site or a large catalogue. Priced on what you actually need rather than off a list.'],
                    ['Add mailboxes any time', 'Email is not in the package price. Add mailboxes on your own domain from R20 a month each — see the modules below.'],
                ] as [$heading, $copy])
                    <div class="rounded-xl border border-brand/10 bg-white p-6">
                        <h3 class="text-base font-semibold text-brand">{{ $heading }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-secondary">{{ $copy }}</p>
                    </div>
                @endforeach
            </div>

            <p class="mt-8 text-sm text-secondary">
                We tell you when a mailbox passes 80% of its allowance, so an upgrade is a
                decision you make in advance rather than a crisis on a Friday afternoon.
            </p>
        </div>
    </section>

    {{-- ── Terms strip ──────────────────────────────────────────────────── --}}
    <section class="container-page section">
        <div class="grid gap-8 rounded-2xl border border-brand/10 p-8 sm:grid-cols-2 lg:grid-cols-4 sm:p-10">
            @foreach ([
                ['Setup fee', $brand['terms']['setup_fee']],
                ['Minimum term', $brand['terms']['minimum_term']],
                ['Notice to cancel', $brand['terms']['notice_period']],
                ['Build time', $brand['terms']['build_time']],
            ] as [$label, $value])
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-muted">{{ $label }}</dt>
                    <dd class="mt-1.5 text-lg font-bold text-brand">{{ $value }}</dd>
                </div>
            @endforeach
        </div>

        <div class="mt-10 text-center">
            <a href="{{ route('contact') }}" class="btn-accent">Ask us about a package</a>
        </div>

        {{-- Hardware: one quiet line, on purpose. --}}
        <x-hardware-note class="mt-10 text-center text-xs leading-relaxed text-muted" />
    </section>

</x-layouts.site>
