@php $brand = config('brand'); @endphp

<x-layouts.site title="Packages and pricing"
                description="Monthly packages for estate agencies, car dealerships, recruitment agencies, guesthouses and everyone else. No upfront cost, cancel any time.">

    <section class="bg-brand text-white">
        <div class="container-page section">
            <p class="eyebrow-light">Packages</p>
            <h1 class="mt-4 max-w-3xl h-display">
                One monthly fee. Website, email, hosting and support.
            </h1>
            <p class="mt-6 max-w-2xl lede text-white/75">
                Every package starts on the 5GB tier and moves up when you need more
                people on email. Nothing to pay until your site is live.
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
            Mail storage and website storage are separate allowances. That is deliberate:
            a dealership with sixty cars will use gigabytes of photographs, and those
            photographs should never be able to fill up the sales inbox.
        </p>

        <div class="mt-8 overflow-x-auto rounded-xl border border-brand/10">
            <table class="w-full min-w-[46rem] text-left text-sm">
                <thead class="bg-brand-light text-xs uppercase tracking-wider text-brand">
                    <tr>
                        <th scope="col" class="px-5 py-3.5 font-semibold">Package</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold">For</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold">Mailboxes</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold">Mail storage</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold">Site storage</th>
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
                                <td class="px-5 py-3.5 text-secondary">{{ $tier->mailboxes }}</td>
                                <td class="px-5 py-3.5 text-secondary">{{ $tier->mail_gb }}GB <span class="text-muted">({{ $tier->gb_per_mailbox }}GB each)</span></td>
                                <td class="px-5 py-3.5 text-secondary">{{ $tier->media_gb }}GB</td>
                                <td class="px-5 py-3.5 text-right font-semibold text-brand">{{ $tier->formatted_price }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>

        <p class="mt-4 text-xs text-muted">
            All prices exclude VAT. Unlimited email aliases and forwarders on every tier —
            only mailboxes are counted.
        </p>
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
                    ['Go from 5GB to 10GB', 'Twice the mailboxes and twice the mail storage. Takes effect the same day; billing changes on your next invoice.'],
                    ['Go to 20GB', 'For firms with a bigger team or heavy attachment traffic. Priced on the number of mailboxes you need.'],
                    ['Enterprise email', 'Shared calendars, larger individual mailboxes, and retention rules for regulated industries. Quoted separately.'],
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
