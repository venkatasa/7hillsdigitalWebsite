{{--
    /packages, revamped as "Platforms" (served where REVAMP_HOME_V2 is on).
    The commercial content is unchanged from packages/index: prices and tiers from
    the database, terms and ownership quoted from config/brand.php, modules
    charged separately. PublicSiteTest and AddOnTest run against this view too.
--}}
@php
    $brand = config('brand');
    $defaults = $packages->map(fn ($p) => $p->defaultTier())->filter();
    $entry = $defaults->sortBy('price_monthly')->first();
    $base = $entry?->formatted_price;
    $email = $addOns->firstWhere('slug', 'enterprise-email');
    $emailFrom = $email && ! $email->is_metered ? $email->formatted_price : null;
    $upper = $packages->flatMap->tiers->firstWhere('is_default', false);
@endphp

<x-ui.layout overlay title="Platforms and pricing"
    description="Monthly website platforms for estate agencies, car dealerships, recruiters, guesthouses and everyone else. The build is free, there is no minimum term, and you can cancel any time.">

    {{-- ══ Hero, with the terms up front ═════════════════════════════════ --}}
    <section id="hero" class="band-night on-dark -mt-[77px] pt-[77px]">
        <div class="contours absolute inset-y-0 right-0 -z-10 w-full opacity-[0.13] lg:w-[75%]" aria-hidden="true"></div>
        <div class="container-wide pb-14 pt-10 sm:pt-14 lg:pb-20 lg:pt-16">
            <p class="inline-flex items-center gap-2.5 text-xs font-semibold uppercase tracking-[0.16em] text-mint">
                <span class="h-px w-6 bg-mint/70" aria-hidden="true"></span>Platforms
            </p>
            <h1 class="t-display mt-5 max-w-4xl text-white">One monthly fee. Your website, built, hosted and supported.</h1>
            <p class="t-lede mt-6 max-w-2xl text-white/70">
                Every platform starts on the 5GB tier and moves up when your site outgrows it.
                @if ($emailFrom) Email is priced separately, from {{ $emailFrom }} a mailbox. @else Email is priced separately. @endif
                Nothing to pay until your site is live.
            </p>
        </div>

        <div class="relative border-t border-white/10">
            <dl class="container-wide grid grid-cols-2 lg:grid-cols-4">
                @foreach ([['Setup fee', $brand['terms']['setup_fee']], ['Minimum term', $brand['terms']['minimum_term']], ['Notice to cancel', $brand['terms']['notice_period']], ['Build time', $brand['terms']['build_time']]] as [$label, $value])
                    <div class="border-white/10 py-5 pr-4 even:border-l even:pl-5 lg:border-l lg:px-6 lg:first:border-l-0 lg:first:pl-0">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-white/60">{{ $label }}</dt>
                        <dd class="mt-1.5 font-display text-lg font-bold text-white">{{ $value }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </section>

    {{-- ══ The platforms: a specification row each, not a card grid ══════ --}}
    <section id="platforms" class="band-paper section-v2">
        <div class="container-wide">
            <x-ui.heading align="split" eyebrow="Five platforms, one engine" title="Pick the one built for your trade."
                lede="The same engine runs all five, so the price is the same for a dealership and a guesthouse. What changes is the storage tier." />

            <ol class="mt-14 border-t border-brand/15">
                @foreach ($packages as $package)
                    @php $demoSlug = $package->demoSlug(); @endphp
                    <li class="grid gap-x-10 gap-y-6 border-b border-hairline py-9 lg:grid-cols-12 lg:items-center" data-reveal>
                        <div class="flex gap-5 lg:col-span-5">
                            <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-brand-light text-accent ring-1 ring-hairline">
                                <x-package-icon :name="$package->icon_key" class="h-6 w-6" />
                            </span>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-muted">{{ $package->vertical_label }}</p>
                                <h2 class="mt-1 font-display text-2xl font-bold text-brand"><a href="{{ route('packages.show', $package) }}" class="hover:text-accent">{{ $package->name }}</a></h2>
                                <p class="mt-2 leading-relaxed text-secondary">{{ $package->tagline }}</p>
                            </div>
                        </div>
                        <dl class="grid grid-cols-2 gap-px overflow-hidden rounded-2xl bg-brand/10 ring-1 ring-brand/10 lg:col-span-4">
                            @foreach ($package->tiers as $tier)
                                <div @class(['p-4', 'bg-white' => $tier->is_default, 'bg-brand-light/60' => ! $tier->is_default])>
                                    <dt class="text-xs text-muted">{{ $tier->media_gb }}GB website storage</dt>
                                    <dd class="mt-1 font-display text-xl font-bold text-brand">{{ $tier->formatted_price }}<span class="ml-1 text-xs font-medium text-muted">/ mo</span></dd>
                                </div>
                            @endforeach
                        </dl>
                        <div class="flex flex-wrap items-center gap-x-5 gap-y-2 lg:col-span-3 lg:justify-end">
                            <a href="{{ route('packages.show', $package) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-accent hover:text-brand">See {{ $package->name }} <span aria-hidden="true">&rarr;</span></a>
                            @if ($demoSlug)
                                <a href="{{ route('demos.show', $demoSlug) }}" class="text-sm font-semibold text-secondary hover:text-accent">Demo</a>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- ══ Every tier, side by side ═══════════════════════════════════════ --}}
    <section id="compare" class="band-mist section-v2">
        <div class="container-wide">
            <div class="grid gap-6 lg:grid-cols-12 lg:gap-16">
                <h2 class="t-title text-brand lg:col-span-5">Every tier, side by side</h2>
                <p class="leading-relaxed text-secondary lg:col-span-7 lg:pt-3">
                    The tier is your website's storage allowance: pages, photographs and documents.
                    A dealership with sixty cars and twelve photographs each will want the larger one.
                </p>
            </div>

            <div class="mt-10 overflow-x-auto rounded-2xl bg-white ring-1 ring-brand/10">
                <table class="w-full text-left text-sm sm:min-w-[40rem]">
                    <thead class="border-b border-brand/10 text-xs uppercase tracking-wider text-muted">
                        <tr>
                            <th scope="col" class="px-4 py-4 font-semibold sm:px-6">Platform</th>
                            <th scope="col" class="hidden px-6 py-4 font-semibold sm:table-cell">For</th>
                            <th scope="col" class="px-4 py-4 font-semibold sm:px-6">Website storage</th>
                            <th scope="col" class="px-4 py-4 text-right font-semibold sm:px-6">Per month</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-hairline">
                        @foreach ($packages as $package)
                            @foreach ($package->tiers as $tier)
                                <tr>
                                    <th scope="row" class="px-4 py-3.5 sm:px-6 font-semibold text-brand"><a href="{{ route('packages.show', $package) }}" class="hover:text-accent">{{ $tier->name }}</a></th>
                                    <td class="hidden px-6 py-3.5 text-secondary sm:table-cell">{{ $loop->first ? $package->vertical_label : '' }}</td>
                                    <td class="px-4 py-3.5 text-secondary sm:px-6">{{ $tier->media_gb }}GB</td>
                                    <td class="px-4 py-3.5 text-right font-semibold sm:px-6 tabular-nums text-brand">{{ $tier->formatted_price }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p class="mt-4 text-xs text-muted">All prices exclude VAT and cover the website only. The build itself costs nothing. {{ $brand['storage']['email_note'] }}</p>
        </div>
    </section>

    {{-- ══ What the fee buys, and who owns what ══════════════════════════ --}}
    <section id="ownership" class="band-night on-dark section-v2">
        <div class="contours absolute inset-0 -z-10 opacity-[0.08]" aria-hidden="true"></div>
        <div class="container-wide grid gap-14 lg:grid-cols-12 lg:gap-16">
            <div class="lg:col-span-5">
                <p class="t-label text-mint">What you are actually buying</p>
                <h2 class="t-title mt-4 text-white">{{ $base ?? 'One fee' }} a month, and nothing to build it.</h2>
                <p class="t-lede mt-5 text-white/70">The website is designed and built at no charge. You start paying the month it goes live, and the fee covers the site, the hosting, the security updates and someone to phone. Email is separate.</p>
                <ul class="mt-8 space-y-3">
                    @foreach (array_filter([
                        $entry ? $entry->media_gb.'GB of storage for your pages, photographs and documents' : null,
                        'Your domain pointed at it, or one registered for you',
                        'Hosting, SSL and software maintenance',
                        'Support from the people who built it',
                    ]) as $line)
                        <li class="flex gap-3 text-sm leading-relaxed text-white/80">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-mint" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 0 1 0 1.4l-7.5 7.5a1 1 0 0 1-1.4 0L3.3 9.7a1 1 0 1 1 1.4-1.4l3.8 3.8 6.8-6.8a1 1 0 0 1 1.4 0Z" clip-rule="evenodd"/></svg>
                            <span>{{ $line }}</span>
                        </li>
                    @endforeach
                </ul>
                @if ($upper)
                    <p class="mt-6 text-sm text-white/60">The {{ $upper->media_gb }} tier doubles the storage for {{ $upper->formatted_price }}.</p>
                @endif
            </div>

            <div class="lg:col-span-7">
                <p class="t-label text-mint">Who owns what</p>
                <dl class="mt-6 divide-y divide-white/10 border-y border-white/10">
                    @foreach ([['Yours, always', 'yours'], ['Ours', 'ours'], ['If you leave', 'exit']] as [$label, $key])
                        <div class="grid gap-2 py-6 sm:grid-cols-[10rem_1fr] sm:gap-8">
                            <dt class="font-display text-base font-bold text-white">{{ $label }}</dt>
                            <dd class="leading-relaxed text-white/70">{{ str_replace(':company', $brand['name'], $brand['ownership'][$key]) }}</dd>
                        </div>
                    @endforeach
                </dl>
                <div class="mt-8 rounded-2xl bg-mint/[0.08] p-6 ring-1 ring-mint/40 sm:p-7">
                    <h3 class="font-display text-lg font-bold text-mint">Want to own it outright?</h3>
                    <p class="mt-2 leading-relaxed text-white/75">{{ str_replace(':company', $brand['name'], $brand['ownership']['buyout']) }}</p>
                    <a href="{{ route('contact', ['from' => 'packages-buyout']) }}" class="mt-4 inline-flex text-sm font-semibold text-mint underline underline-offset-4 hover:brightness-110">Ask for a build-and-own quote &rarr;</a>
                </div>
            </div>
        </div>
    </section>

    <x-platform.add-ons :add-ons="$addOns" :base="$base" />

    {{-- ══ When you outgrow it ════════════════════════════════════════════ --}}
    <section id="upgrades" class="band-mist section-v2">
        <div class="container-wide">
            <x-ui.heading eyebrow="When you outgrow it" title="Move up a tier the month you need to, not a month before." />
            <ol class="mt-12 grid gap-px overflow-hidden rounded-3xl bg-brand/10 ring-1 ring-brand/10 md:grid-cols-3">
                @foreach ([
                    ['Go from 5 to 10', 'Double your website storage to 10GB. Takes effect the same day; billing changes on your next invoice.'],
                    ['Go beyond 10', 'For a photo-heavy site or a large catalogue. Priced on what you actually need rather than off a list.'],
                    ['Add mailboxes any time', 'Email is not in the platform price. Add mailboxes on your own domain'.($emailFrom ? ' from '.$emailFrom.' a month each' : '').', as a module.'],
                ] as [$heading, $copy])
                    <li class="bg-white p-7">
                        <p class="t-index text-accent">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</p>
                        <h3 class="mt-3 font-display text-lg font-bold text-brand">{{ $heading }}</h3>
                        <p class="mt-2 leading-relaxed text-secondary">{{ $copy }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- ══ Close ══════════════════════════════════════════════════════════ --}}
    <section class="band-night on-dark">
        <div class="contours absolute inset-0 -z-10 opacity-[0.12]" aria-hidden="true"></div>
        <div class="container-wide py-20 text-center sm:py-24">
            <h2 class="t-display mx-auto max-w-3xl text-white">Not sure which platform fits?</h2>
            <p class="t-lede mx-auto mt-6 max-w-xl text-white/70">Tell us about the business. We will say plainly which platform suits it, or whether you need something built.</p>
            <div class="mt-9 flex justify-center">
                <x-ui.button :href="route('contact')" tone="dark" size="lg" arrow data-cta="packages-close">Ask us about a package</x-ui.button>
            </div>
            {{-- Hardware: one quiet line, on purpose. --}}
            <x-hardware-note class="mt-10 text-xs leading-relaxed text-white/60" />
        </div>
    </section>
</x-ui.layout>
