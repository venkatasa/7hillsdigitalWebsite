{{--
    /packages/{slug}, revamped (served where REVAMP_HOME_V2 is on). Same commercial
    content as packages/show: features, tiers and prices from the database, the
    ?package= deep link into /contact, and the demo where one exists, labelled as
    invented. Adds the link to the industry page that sells this platform.
--}}
@php
    use Illuminate\Support\Str;

    $brand = config('brand');
    $demoSlug = $package->demoSlug();
    $contact = route('contact', ['package' => $package->slug]);
    $industry = collect(\App\Support\Revamp::industries())->filter(fn ($i) => $i['package'] === $package->slug)->keys()->first();
    $email = \App\Models\AddOn::published()->where('slug', 'enterprise-email')->first();
    $emailFrom = $email && ! $email->is_metered ? $email->formatted_price : null;
@endphp

<x-ui.layout overlay :title="$package->name.' — '.$package->vertical_label"
    :description="$package->tagline.' '.Str::limit($package->intro, 130)">

    {{-- ══ Hero ═══════════════════════════════════════════════════════ --}}
    <section id="hero" class="band-night on-dark -mt-[77px] pt-[77px]">
        <div class="contours absolute inset-y-0 right-0 -z-10 w-full opacity-[0.13] lg:w-[75%]" aria-hidden="true"></div>
        <div class="absolute inset-y-0 left-0 -z-10 w-2/3 bg-gradient-to-r from-brand-dark via-brand-dark/85 to-transparent" aria-hidden="true"></div>

        <div class="container-wide grid items-center gap-y-14 pb-16 pt-8 sm:pt-12 lg:grid-cols-12 lg:gap-x-12 lg:pb-24 lg:pt-14">
            <div class="lg:col-span-6">
                <nav aria-label="Breadcrumb">
                    <ol class="flex flex-wrap items-center gap-x-2 text-xs text-white/60">
                        <li><a href="{{ route('packages.index') }}" class="hover:text-white">Platforms</a></li>
                        <li aria-hidden="true">/</li>
                        <li aria-current="page" class="text-white/80">{{ $package->name }}</li>
                    </ol>
                </nav>

                <div class="mt-7 flex items-center gap-4">
                    <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white/10 text-mint ring-1 ring-white/15">
                        <x-package-icon :name="$package->icon_key" class="h-6 w-6" />
                    </span>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-mint">{{ $package->vertical_label }}</p>
                        <h1 class="font-display text-4xl font-extrabold tracking-tight text-white sm:text-5xl">{{ $package->name }}</h1>
                    </div>
                </div>

                <p class="mt-7 max-w-xl font-display text-2xl font-bold leading-snug text-white sm:text-3xl">{{ $package->tagline }}</p>
                <p class="t-lede mt-5 max-w-xl text-white/70">{{ $package->intro }}</p>

                <div class="mt-9 flex flex-col gap-3 sm:flex-row sm:items-center">
                    <x-ui.button :href="$contact" tone="dark" size="lg" arrow data-cta="package-hero">Ask about {{ $package->name }}</x-ui.button>
                    @if ($demoSlug)
                        <x-ui.button :href="route('demos.show', $demoSlug)" tone="dark" variant="secondary" size="lg">See a live {{ $package->name }} site</x-ui.button>
                    @elseif ($package->demo_url)
                        <x-ui.button :href="$package->demo_url" tone="dark" variant="secondary" size="lg">See the demo</x-ui.button>
                    @else
                        <x-ui.button :href="route('demos')" tone="dark" variant="secondary" size="lg">See the demos</x-ui.button>
                    @endif
                </div>
            </div>

            <div class="relative lg:col-span-6">
                @if ($demoSlug)
                    <div class="relative pb-10 sm:pr-16">
                        <x-ui.frame-browser :image="'demo-'.$demoSlug.'-desktop'" :url="'7hillsdigital.co.za/demos/'.$demoSlug" eager />
                        <div class="absolute -bottom-2 right-0 hidden w-[26%] sm:block">
                            <x-ui.frame-phone :image="'demo-'.$demoSlug.'-mobile'" />
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- ══ What you get, beside the live price list ═══════════════════════ --}}
    <section id="features" class="band-paper section-v2">
        <div class="container-wide grid gap-14 lg:grid-cols-12 lg:gap-16">
            <div class="lg:col-span-7">
                <p class="t-label text-accent">What you get</p>
                <h2 class="t-title mt-4 text-brand">Built the way {{ Str::lower($package->vertical_label) }} actually work.</h2>
                <ol class="mt-10 border-t border-brand/15">
                    @foreach ($package->what_you_get ?? [] as $item)
                        <li class="grid grid-cols-[2.5rem_1fr] gap-x-3 border-b border-hairline py-5" data-reveal data-reveal-delay="{{ ($loop->index % 4) * 40 }}">
                            <span class="t-index pt-0.5 text-accent">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="text-lg leading-relaxed text-ink">{{ $item }}</span>
                        </li>
                    @endforeach
                </ol>
                <div class="mt-10 rounded-2xl bg-brand-light p-6 ring-1 ring-hairline">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-brand">Plus everything in every platform</h3>
                    <p class="mt-2 leading-relaxed text-secondary">Hosting, SSL, security updates and support are included, not extra. Email is priced separately@if ($emailFrom), from {{ $emailFrom }} a mailbox a month@endif.</p>
                </div>
            </div>

            <aside class="lg:col-span-5 lg:sticky lg:top-24 lg:self-start" aria-labelledby="tiers-heading">
                <div class="tile p-6 sm:p-7">
                    <h2 id="tiers-heading" class="text-xs font-semibold uppercase tracking-wider text-muted">Choose your tier</h2>
                    <div class="mt-5 space-y-3">
                        @foreach ($package->tiers as $tier)
                            <div @class(['rounded-2xl p-5 ring-1', 'bg-accent/[0.06] ring-accent' => $tier->is_default, 'ring-brand/10' => ! $tier->is_default])>
                                <div class="flex items-baseline justify-between gap-3">
                                    <h3 class="font-display text-lg font-bold text-brand">{{ $tier->name }}</h3>
                                    <p class="text-right">
                                        <span class="t-figure text-3xl text-brand">{{ $tier->formatted_price }}</span>
                                        <span class="block text-xs text-muted">per month excl. VAT</span>
                                    </p>
                                </div>
                                <ul class="mt-4 space-y-1.5 text-sm text-secondary">
                                    <li><strong class="font-semibold text-brand">{{ $tier->media_gb }}GB</strong> of website storage</li>
                                    <li>Hosting, SSL, maintenance and support</li>
                                    <li>The build itself costs nothing</li>
                                    <li class="text-muted">Email sold separately{{ $emailFrom ? ', from '.$emailFrom.' a mailbox' : '' }}</li>
                                </ul>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6"><x-ui.button :href="$contact" size="lg" arrow class="w-full justify-center">Get {{ $package->name }} started</x-ui.button></div>
                    <p class="mt-3 text-center text-xs text-muted">Nothing to pay today. First invoice when the site goes live.</p>
                    <dl class="mt-6 grid grid-cols-2 gap-px overflow-hidden rounded-xl bg-brand/10 text-xs ring-1 ring-brand/10">
                        @foreach ([['Minimum term', $brand['terms']['minimum_term']], ['Notice', $brand['terms']['notice_period']]] as [$label, $value])
                            <div class="bg-white p-3"><dt class="text-muted">{{ $label }}</dt><dd class="mt-0.5 font-semibold text-brand">{{ $value }}</dd></div>
                        @endforeach
                    </dl>
                </div>
            </aside>
        </div>
    </section>

    {{-- ══ Who it suits ═══════════════════════════════════════════════════ --}}
    @if ($package->built_for)
        <section id="built-for" class="band-mist section-v2">
            <div class="container-wide grid gap-10 lg:grid-cols-12 lg:gap-16">
                <div class="lg:col-span-5">
                    <p class="t-label text-accent">Who it suits</p>
                    <h2 class="t-title mt-4 text-brand">{{ $package->name }} is built for</h2>
                    @if ($industry)
                        <a href="{{ route('industries.show', $industry) }}" class="mt-6 inline-flex items-center gap-1.5 font-semibold text-accent hover:text-brand">How it works for {{ Str::lower(config('revamp.industries.'.$industry.'.short')) }} businesses <span aria-hidden="true">&rarr;</span></a>
                    @endif
                </div>
                <ul class="divide-y divide-brand/10 border-y border-brand/10 lg:col-span-7">
                    @foreach ($package->built_for as $who)
                        <li class="py-4 font-display text-lg font-semibold text-brand">{{ $who }}</li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    {{-- ══ Other platforms ════════════════════════════════════════════════ --}}
    <section id="others" class="band-paper section-v2">
        <div class="container-wide">
            <x-ui.heading align="split" eyebrow="Same engine" title="Not quite your trade?"
                lede="The other platforms run on the same engine. If none of them fit, Business is the one that bends to suit you." />
            <ul class="mt-12 grid gap-px overflow-hidden rounded-3xl bg-brand/10 ring-1 ring-brand/10 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($others as $other)
                    <li>
                        <a href="{{ route('packages.show', $other) }}" class="group flex h-full flex-col bg-white p-6 hover:bg-brand-light">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-brand-light text-accent ring-1 ring-hairline group-hover:bg-white">
                                <x-package-icon :name="$other->icon_key" class="h-5 w-5" />
                            </span>
                            <span class="mt-4 font-display text-lg font-bold text-brand group-hover:text-accent">{{ $other->name }}</span>
                            <span class="mt-1 text-xs font-semibold uppercase tracking-wider text-muted">{{ $other->vertical_label }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>
</x-ui.layout>
