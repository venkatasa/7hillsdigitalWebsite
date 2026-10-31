{{--
    Homepage v3 — full-service positioning (docs/revamp/full-service/04_HOMEPAGE_COMPOSITION.md).
    Served only where REVAMP_HOME_V2=true. Every price comes from the database,
    every screenshot from public/images/product/manifest.json, and every service
    from config/revamp.php through App\Support\Revamp, which applies the
    publication rule: nothing unconfirmed or blocked ever renders in production.
--}}
@php
    use App\Support\Revamp;

    $brand = config('brand');
    $headline = Revamp::headline();
    $lines = Revamp::lines();
    $featured = Revamp::featured();
    $industries = Revamp::industries();
    $quoteTypes = Revamp::quoteTypes();
    $serviceCount = collect($lines)->sum(fn ($l) => count($l['services']));

    $quote = fn (string $from, ?string $type = null) => route('contact', array_filter(['type' => 'project', 'interest' => $type, 'from' => $from]));

    // The supporting line names Microsoft 365 and managed IT only once they may be published.
    $lede = Revamp::visible('microsoft-365-consulting') && Revamp::visible('managed-it-support')
        ? 'From custom applications and intelligent automation to Microsoft 365, cloud infrastructure and managed IT services, we help businesses build, connect, secure and modernise their technology.'
        : 'From custom applications and automation to systems integration, hosting and support, we help businesses build, connect and modernise their technology.';
    $ledeShort = 'Custom software, automation and IT services, built, connected and looked after by one team.';

    $fromTier = $packages->flatMap->tiers->filter(fn ($t) => $t->price_monthly !== null)->sortBy('price_monthly')->first();
    $from = $fromTier?->formatted_price;
    $packagesBySlug = $packages->keyBy('slug');
    $tierColumns = $packages->first()?->tiers ?? collect();
    // Literal class names, so Tailwind's scanner can see them.
    $linkSpan = ['md:col-span-8', 'md:col-span-6', 'md:col-span-4', 'md:col-span-2'][min($tierColumns->count(), 3)];

    $microsoft = Revamp::visible('microsoft-365-consulting');
    $motifs = ['custom-development' => 'code', 'microsoft-365' => 'grid', 'ai-automation' => 'flow', 'managed-it' => 'pulse', 'cloud-infrastructure' => 'stack', 'security-assessment' => 'shield'];
@endphp

<x-ui.layout overlay>

    {{-- ══ 1 · HERO ═════════════════════════════════════════════════════
         The copy is not revealed on scroll: the headline is the largest paint,
         and it must not wait for JavaScript. --}}
    <section id="hero" class="band-night on-dark -mt-[77px] pt-[77px]">
        <div class="contours absolute inset-y-0 right-0 -z-10 w-full opacity-[0.15] lg:w-[80%]" aria-hidden="true"></div>
        <div class="absolute inset-0 -z-10 bg-[radial-gradient(56rem_34rem_at_80%_38%,color-mix(in_oklab,var(--brand-mint)_9%,transparent),transparent_70%)]" aria-hidden="true"></div>
        <div class="absolute inset-y-0 left-0 -z-10 w-2/3 bg-gradient-to-r from-brand-dark via-brand-dark/85 to-transparent" aria-hidden="true"></div>

        <div class="container-wide grid items-center gap-y-12 pb-12 pt-8 sm:pt-14 lg:grid-cols-12 lg:gap-x-10 lg:pb-16 lg:pt-14">
            <div class="lg:col-span-7">
                <p class="inline-flex items-center gap-2.5 text-xs font-semibold tracking-[0.04em] text-mint sm:uppercase sm:tracking-[0.16em]">
                    <span class="h-px w-6 bg-mint/70" aria-hidden="true"></span>
                    Built, hosted and supported in South Africa
                </p>

                <h1 class="t-hero-xl mt-6 text-white">
                    {{ $headline['lead'] }} <span class="text-mint">{{ $headline['accent'] }}</span> {{ $headline['tail'] }}
                </h1>

                <p class="t-lede mt-6 max-w-[38rem] text-white/70">
                    <span class="sm:hidden">{{ $ledeShort }}</span><span class="hidden sm:inline">{{ $lede }}</span>
                </p>

                <div class="mt-9 flex flex-col gap-3 sm:flex-row sm:items-center">
                    <x-ui.button :href="$quote('home-hero')" tone="dark" size="lg" arrow data-cta="hero-quote">Get a Quote</x-ui.button>
                    <x-ui.button href="#services" tone="dark" variant="secondary" size="lg" data-cta="hero-services">Explore Services</x-ui.button>
                </div>

                <p class="mt-6 text-sm text-white/60">
                    Need a website now?
                    <a href="{{ route('packages.index') }}" class="font-semibold text-white underline decoration-mint/50 underline-offset-4 hover:decoration-mint" data-cta="hero-platforms">See our ready-to-use platforms</a>
                </p>
            </div>

            {{-- Genuine screens, each labelled for what it is. --}}
            <div class="relative lg:col-span-5" data-reveal data-reveal-delay="200">
                <div class="relative pb-8 pl-8 sm:pl-14 lg:pl-4" data-depth-scene>
                    <div data-depth="1" class="lg:w-[150%]">
                        <x-ui.frame-browser image="admin-enquiries" url="yourbusiness.co.za/admin/enquiries" :caption="false" eager />
                    </div>
                    <div data-depth="2.4" class="absolute -bottom-1 left-0 w-[30%] max-w-[12rem] lg:-left-10 lg:w-[34%]">
                        <x-ui.frame-phone image="demo-realty-mobile" />
                    </div>
                    <p class="absolute -bottom-7 left-[33%] flex items-center gap-2 text-[11px] text-white/55 lg:left-[28%]">
                        <span class="h-px w-6 bg-mint/60" aria-hidden="true"></span>
                        Demo tenant · fictional business
                    </p>
                    <p class="absolute -top-6 right-0 flex items-center gap-2 text-[11px] text-white/55 lg:-top-7 lg:left-[36%] lg:right-auto">
                        Back office · staging install, fictional data
                        <span class="h-px w-6 bg-mint/60" aria-hidden="true"></span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Capability index: the breadth, at a glance, one tap from each line. --}}
        <nav aria-label="Service lines" class="relative border-t border-white/10" x-data>
            <ol class="container-wide snap-row flex overflow-x-auto lg:grid lg:overflow-visible" style="grid-template-columns: repeat({{ count($lines) }}, minmax(0, 1fr))">
                @foreach ($lines as $slug => $l)
                    <li class="w-[44%] shrink-0 border-r border-white/10 first:border-l sm:w-[30%] lg:w-auto">
                        <a href="{{ route('services.show', $slug) }}"
                           class="cap-link relative flex h-full flex-col gap-3 px-4 py-5 text-white/70 hover:text-white lg:px-5" style="--motif-accent: var(--brand-mint)">
                            <span class="flex items-center justify-between">
                                <span class="t-index text-white/40">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                <x-ui.motif :name="$l['motif']" class="h-5 w-5 text-white/45" />
                            </span>
                            <span class="text-sm font-semibold leading-snug">
                                {{ $l['short'] }}
                                @if ($l['preview'])<span class="ml-1 rounded border border-dashed border-white/30 px-1 text-[10px] font-medium text-white/50">preview</span>@endif
                            </span>
                        </a>
                    </li>
                @endforeach
            </ol>
        </nav>
    </section>

    {{-- ══ 2 · SERVICES — eight featured, then the whole catalogue ═══════ --}}
    <section id="services" class="band-paper section-v2 scroll-mt-20" x-data>
        <div class="container-wide">
            <x-ui.heading align="split" eyebrow="What we do"
                title="{{ ucfirst((new NumberFormatter('en', NumberFormatter::SPELLOUT))->format(count($lines))) }} service lines. One accountable team."
                lede="We build it, connect it, host it and support it, on one invoice, with the people who wrote it." />

            <div class="snap-row -mx-5 mt-12 flex gap-3 overflow-x-auto px-5 pb-2 sm:mx-0 sm:mt-14 sm:grid sm:grid-cols-2 sm:gap-4 sm:overflow-visible sm:px-0 sm:pb-0 lg:grid-cols-4">
                @foreach ($featured as $slug => $s)
                    @php $lead = $loop->first; @endphp
                    <a href="{{ Revamp::href($slug) }}"
                       @class([
                           'svc-card tile group flex w-[78%] shrink-0 flex-col p-6 sm:w-auto sm:p-7',
                           'sm:col-span-2 lg:row-span-2' => $lead,
                           '!border-dashed !border-brand/30' => $s['preview'],
                       ])
                       data-spotlight data-reveal data-reveal-delay="{{ $loop->index * 50 }}">
                        <div class="flex items-start justify-between gap-4">
                            <x-ui.motif :name="$s['motif']" @class(['svc-motif text-brand', 'h-14 w-14' => $lead, 'h-10 w-10' => ! $lead]) />
                            @if ($s['preview'])
                                <span class="rounded-full border border-dashed border-brand/40 px-2.5 py-1 text-[11px] font-semibold text-secondary">Preview only</span>
                            @else
                                <span class="t-label !text-[0.65rem] text-muted">{{ $s['line_name'] }}</span>
                            @endif
                        </div>
                        <h3 @class(['mt-6 font-display font-bold tracking-tight text-brand', 'text-lg leading-snug sm:text-[clamp(1.85rem,1.3rem+1.6vw,2.75rem)] sm:leading-[1.1]' => $lead, 'text-lg leading-snug' => ! $lead])>{{ $s['name'] }}</h3>
                        <p @class(['mt-2 leading-relaxed text-secondary', 'text-sm sm:text-lg max-w-md' => $lead, 'text-sm' => ! $lead])>{{ $s['summary'] }}</p>

                        @if ($lead && ($shot = \App\Support\ProductImages::get('admin-packages')))
                            <div class="relative -mb-7 -mr-7 ml-10 mt-8 hidden flex-1 sm:block">
                                <figure class="h-full overflow-hidden rounded-tl-xl border-l border-t border-hairline bg-brand-light">
                                    <img src="{{ asset($shot['file']) }}" alt="{{ $shot['alt'] }}" width="{{ $shot['width'] }}" height="{{ $shot['height'] }}"
                                         loading="lazy" decoding="async" class="h-full max-h-80 min-h-48 w-full object-cover object-left-top">
                                </figure>
                                <p class="absolute -top-5 left-0 text-[11px] text-muted">Our own software · {{ $shot['label'] }}</p>
                            </div>
                        @endif
                        {{-- The lead card shows a screenshot from sm up, and the arrow on phones. --}}
                        <span @class(['svc-arrow mt-auto inline-flex items-center gap-1.5 pt-6 text-sm font-semibold text-accent', 'sm:hidden' => $lead])>
                            {{ Revamp::pageFor($slug) ? 'Read more' : 'See the line' }} <span aria-hidden="true">&rarr;</span>
                        </span>
                    </a>
                @endforeach

                <a href="{{ route('services.index') }}" class="tile-dark on-dark group flex w-[78%] shrink-0 flex-col justify-between p-6 text-white sm:w-auto sm:p-7" data-reveal data-reveal-delay="400">
                    <div class="contours absolute inset-0 -z-10 opacity-[0.12]" aria-hidden="true"></div>
                    <p class="t-figure text-5xl text-mint">{{ $serviceCount }}</p>
                    <div>
                        <p class="mt-6 font-display text-lg font-bold">Every service, in one list</p>
                        <span class="svc-arrow mt-3 inline-flex items-center gap-1.5 text-sm font-semibold text-mint">All services <span aria-hidden="true">&rarr;</span></span>
                    </div>
                </a>
            </div>

            {{-- The full catalogue — every service we will put our name to on this environment. --}}
            <div id="catalogue" class="mt-24 scroll-mt-28">
                <div class="flex flex-wrap items-end justify-between gap-4 border-b border-hairline pb-6">
                    <div>
                        <p class="t-label text-accent">The full catalogue</p>
                        <h3 class="t-subtitle mt-3 text-brand">{{ $serviceCount }} services across {{ count($lines) }} lines</h3>
                    </div>
                    <p class="flex items-center gap-5 text-xs text-muted">
                        <span class="inline-flex items-center gap-2"><span class="h-1.5 w-1.5 rounded-full bg-accent" aria-hidden="true"></span>Available or built to order</span>
                        @if (Revamp::previewing())
                            <span class="inline-flex items-center gap-2"><span class="h-1.5 w-1.5 rounded-full border border-brand/50" aria-hidden="true"></span>Preview only, hidden in production</span>
                        @endif
                    </p>
                </div>

                <div class="grid gap-x-10 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($lines as $slug => $l)
                        <div id="line-{{ $slug }}" x-data="{ open: false, flash: false }"
                             x-on:show-line.window="if ($event.detail === '{{ $slug }}') { open = true; flash = true; setTimeout(() => flash = false, 1600); $nextTick(() => $el.scrollIntoView({ behavior: matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth', block: 'start' })) }"
                             :class="flash && 'is-target'"
                             class="cat-line -mx-3 scroll-mt-28 border-b border-hairline px-3 py-5 sm:rounded-2xl sm:border-0 sm:py-6 lg:py-9"
                             style="--motif-accent: var(--brand-accent)">
                            {{-- A heading from sm up; on phones the same row is a button that opens the list. --}}
                            <h4 class="nojs-flex hidden items-center gap-3 sm:flex">
                                <x-ui.motif :name="$l['motif']" class="h-7 w-7 text-brand" />
                                <a href="{{ route('services.show', $slug) }}" class="font-display text-lg font-bold text-brand hover:text-accent">{{ $l['name'] }}</a>
                            </h4>
                            <button type="button" @click="open = ! open" :aria-expanded="open ? 'true' : 'false'" aria-controls="line-list-{{ $slug }}"
                                    class="js-only flex w-full items-center gap-3 text-left sm:hidden">
                                <x-ui.motif :name="$l['motif']" class="h-7 w-7 text-brand" />
                                <span class="flex-1 font-display text-lg font-bold text-brand">{{ $l['name'] }}</span>
                                <span class="t-index text-muted">{{ count($l['services']) }}</span>
                                <svg class="h-4 w-4 text-accent transition-transform" :class="open && 'rotate-45'" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path d="M8 3v10M3 8h10" stroke-linecap="round"/></svg>
                            </button>
                            <div id="line-list-{{ $slug }}" :class="{ 'block': open, 'hidden sm:block': ! (open) }" class="js-collapse">
                                <p class="mt-3 text-sm leading-relaxed text-muted">{{ $l['blurb'] }}</p>
                                <ul class="mt-4 space-y-2">
                                    @foreach ($l['services'] as $s)
                                        <li class="flex items-baseline gap-2.5 text-[0.92rem] leading-snug text-secondary">
                                            <span @class(['h-1.5 w-1.5 shrink-0 translate-y-[-2px] rounded-full', 'bg-accent' => ! $s['preview'], 'border border-brand/50' => $s['preview']]) aria-hidden="true"></span>
                                            <a href="{{ Revamp::href($s['slug']) }}" @class(['hover:text-accent', 'text-muted' => $s['preview']])>{{ $s['name'] }}@if ($s['preview'])<span class="sr-only"> (preview only)</span>@endif</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endforeach

                    {{-- The promise that we are not limited to a list (or to five packages). --}}
                    <div class="flex flex-col justify-center rounded-2xl bg-brand-light p-6 sm:my-6 lg:my-9">
                        <p class="font-display text-lg font-bold text-brand">Not on the list?</p>
                        <p class="mt-2 text-sm leading-relaxed text-secondary">If your business has a process, we can put it on a screen. Tell us what it is.</p>
                        <a href="{{ $quote('home-catalogue') }}" class="mt-4 text-sm font-semibold text-accent hover:text-brand">Describe it &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══ 3 · SOLUTIONS — problems first ══════════════════════════════ --}}
    <section id="solutions" class="band-mist section-v2">
        <div class="container-wide grid gap-12 lg:grid-cols-12 lg:gap-16">
            <div class="lg:col-span-5">
                <x-ui.heading eyebrow="Business problems we solve" title="What is slowing your business down?"
                    lede="We start from the problem that costs you money, then build the smallest thing that fixes it." />

                <figure class="tile-dark on-dark mt-10 p-7 text-white sm:p-8" data-reveal>
                    <p class="t-figure text-6xl text-mint">62%</p>
                    <p class="mt-3 max-w-xs leading-snug text-white/85">of South African small businesses had cash-flow problems in the past year.</p>
                    <figcaption class="mt-6 border-t border-white/10 pt-4 text-xs text-white/50">
                        Source: Xero, <cite class="not-italic">The State of South African Small Business 2026</cite>, 427 businesses surveyed, Jan–Feb 2026.
                    </figcaption>
                </figure>
            </div>

            <ol class="divide-y divide-brand/10 border-y border-brand/10 lg:col-span-7 lg:self-center" x-data>
                @foreach (config('revamp.solutions') as $i => $s)
                    @continue(isset($s[3]) && ! Revamp::visible($s[3]))
                    <li data-reveal data-reveal-delay="{{ $i * 40 }}">
                        <a href="{{ route('services.show', $s[2]) }}"
                           class="ledger-row group grid grid-cols-[2.25rem_1fr_auto] items-baseline gap-x-4 py-5 sm:py-6">
                            <span class="t-index text-accent">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <span>
                                <span class="block font-display text-lg font-bold text-brand sm:text-xl">{{ $s[0] }}</span>
                                <span class="mt-1 block text-secondary group-hover:text-brand">{{ $s[1] }}</span>
                            </span>
                            <span class="ledger-arrow text-accent" aria-hidden="true">&rarr;</span>
                        </a>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- ══ 4 · BUILT BY 7HILLS — proof, not promises ══════════════════════ --}}
    @php
        $proof = [
            'back-office' => [
                'tab' => 'Our back office',
                'title' => 'The system this business runs on.',
                'points' => ['Enquiry pipeline with a full record of who said what', 'Packages, add-ons and prices edited without a developer', 'What a prospect submitted is read-only: evidence, not a draft'],
                'image' => 'admin-packages',
                'url' => 'yourbusiness.co.za/admin/packages',
            ],
            'platform' => [
                'tab' => 'The platform engine',
                'title' => 'One engine, configured for five industries.',
                'points' => ['Stock, listings, vacancies or rooms, each with its own back office', 'Each tenant carries its own brand, not ours', 'Prices on every page follow the database'],
                'image' => 'demo-auto-desktop',
                'url' => '7hillsdigital.co.za/demos/auto',
            ],
            'invoicing' => [
                'tab' => 'Client invoicing',
                'title' => 'Third-party software, deployed and run properly.',
                'points' => ['InvoiceShelf on the client\'s own subdomain', 'Branded, backed up and updated by us', 'We say whose software it is. We did not write it'],
                'image' => null,
                'url' => null,
            ],
        ];
        $proofKeys = array_keys($proof);
    @endphp
    <section id="proof" class="band-night on-dark section-v2"
             x-data="{
                active: '{{ $proofKeys[0] }}',
                keys: @js($proofKeys),
                move(step) {
                    const i = (this.keys.indexOf(this.active) + step + this.keys.length) % this.keys.length;
                    this.active = this.keys[i];
                    this.$nextTick(() => document.getElementById('ptab-' + this.active)?.focus());
                },
             }">
        <div class="contours absolute inset-y-0 left-0 -z-10 w-full -scale-x-100 opacity-[0.07]" aria-hidden="true"></div>
        <div class="container-wide">
            <x-ui.heading tone="dark" eyebrow="Proof, not promises"
                title="Software we wrote, running today."
                lede="Before you trust us with your system, look at ours. Every screen here is real, and labelled for what it is." />

            <div role="tablist" aria-label="Our software"
                 @keydown.arrow-right.prevent="move(1)" @keydown.arrow-left.prevent="move(-1)"
                 class="js-only snap-row -mx-5 mt-12 flex gap-2 overflow-x-auto px-5 sm:mx-0 sm:px-0">
                @foreach ($proof as $key => $p)
                    <button type="button" role="tab" id="ptab-{{ $key }}" aria-controls="proof-{{ $key }}"
                            :aria-selected="active === '{{ $key }}' ? 'true' : 'false'"
                            :tabindex="active === '{{ $key }}' ? 0 : -1"
                            @click="active = '{{ $key }}'"
                            :class="{ 'bg-white/[0.09] text-white ring-white/20': active === '{{ $key }}', 'text-white/60 ring-white/10 hover:text-white': ! (active === '{{ $key }}') }"
                            class="nav-item shrink-0 rounded-full px-4 py-2.5 text-sm font-semibold ring-1 {{ $loop->first ? 'bg-white/[0.09] text-white ring-white/20' : 'text-white/60 ring-white/10' }}">
                        {{ $p['tab'] }}
                    </button>
                @endforeach
            </div>

            <div class="mt-8">
                @foreach ($proof as $key => $p)
                    <div id="proof-{{ $key }}" role="tabpanel" aria-labelledby="ptab-{{ $key }}" tabindex="0"
                         x-show="active === '{{ $key }}'"
                         x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="opacity-0"
                         @if (! $loop->first) style="display: none" @endif
                         class="js-panel">
                        <div class="grid items-center gap-10 lg:grid-cols-12">
                            <div class="lg:col-span-7">
                                @if ($p['image'])
                                    <x-ui.frame-browser :image="$p['image']" :url="$p['url']" />
                                @else
                                    <div class="tile-dark p-6 sm:p-10">
                                        <x-ui.flow tone="dark" :steps="[['Quote', 'Sent from their own domain'], ['Invoice', 'Raised from the accepted quote'], ['Payment', 'Recorded against the invoice'], ['Statement', 'The client sees where they stand']]" />
                                        <p class="mt-6 border-t border-white/10 pt-5 text-sm leading-relaxed text-white/60">
                                            No screenshot, deliberately: it is a client&rsquo;s live system, and their customers&rsquo; data is not ours to show.
                                        </p>
                                    </div>
                                @endif
                            </div>
                            <div class="lg:col-span-5">
                                <h3 class="t-subtitle text-white">{{ $p['title'] }}</h3>
                                <ul class="mt-6 space-y-3.5">
                                    @foreach ($p['points'] as $point)
                                        <li class="flex gap-3 text-white/75">
                                            <svg class="mt-1 h-4 w-4 shrink-0 text-mint" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path d="M3 8.5l3 3 7-7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            <span class="leading-relaxed">{{ $point }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Only technology evidenced in our own code and infrastructure. --}}
            <div class="mt-16 flex flex-col gap-4 border-t border-white/10 pt-8 sm:flex-row sm:items-baseline sm:gap-10">
                <p class="t-label shrink-0 text-white/60">What we build with</p>
                <ul class="flex flex-wrap gap-x-6 gap-y-2 font-display text-sm font-semibold text-white/70">
                    @foreach (['Laravel', 'PHP', 'MySQL', 'Filament', 'Tailwind CSS', 'Alpine.js', 'Docker', 'Proxmox', 'Cloudflare'] as $tech)
                        <li>{{ $tech }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>

    {{-- ══ 5 · SPOTLIGHT — Microsoft 365 when confirmed, otherwise automation ══ --}}
    <section id="spotlight" class="band-paper section-v2">
        <div class="container-wide">
            <div @class(['grid gap-12 rounded-3xl p-7 sm:p-12 lg:grid-cols-12 lg:items-center lg:gap-16', 'border border-dashed border-brand/30' => $microsoft && Revamp::catalogue()['microsoft-365-consulting']['preview'], 'border border-hairline' => ! ($microsoft && Revamp::catalogue()['microsoft-365-consulting']['preview'])])>
                <div class="lg:col-span-5">
                    @if ($microsoft)
                        @if (Revamp::catalogue()['microsoft-365-consulting']['preview'])
                            <p class="mb-5 inline-flex rounded-full border border-dashed border-brand/40 px-3 py-1 text-xs font-semibold text-secondary">Preview only. Hidden in production until capability is confirmed.</p>
                        @endif
                        <x-ui.heading eyebrow="Microsoft 365 & SharePoint" title="Get more from the Microsoft 365 you already pay for."
                            lede="SharePoint, Teams and Power Automate, set up around how your business actually works." />
                        <ul class="mt-8 space-y-3 text-secondary">
                            @foreach (['Document libraries people use, with permissions that match the org chart', 'Approvals that do not wait in an inbox', 'Sign-in and sharing secured properly'] as $point)
                                <li class="flex gap-3"><svg class="mt-1 h-4 w-4 shrink-0 text-accent" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path d="M3 8.5l3 3 7-7" stroke-linecap="round" stroke-linejoin="round"/></svg>{{ $point }}</li>
                            @endforeach
                        </ul>
                        <div class="mt-9"><x-ui.button :href="$quote('home-spotlight', 'microsoft-365')" arrow>Talk to us about Microsoft 365</x-ui.button></div>
                    @else
                        <x-ui.heading eyebrow="Automation & integration" title="Enter it once. Let the systems do the rest."
                            lede="We connect your website, back office, accounting and email, so a lead becomes a quote and a quote becomes an invoice without anyone retyping it." />
                        <div class="mt-9"><x-ui.button :href="$quote('home-spotlight', 'ai-automation')" arrow>Automate a process</x-ui.button></div>
                    @endif
                </div>
                <div class="hidden sm:block lg:col-span-7">
                    <div class="tile-dark on-dark p-7 sm:p-10">
                        <div class="contours absolute inset-0 -z-10 opacity-[0.1]" aria-hidden="true"></div>
                        @if ($microsoft)
                            <x-ui.flow tone="dark" :steps="[['Request', 'Raised in Teams'], ['Approval', 'Routed by Power Automate'], ['Filed', 'Stored in SharePoint'], ['Done', 'Finance notified']]" />
                        @else
                            <x-ui.flow tone="dark" :steps="[['Enquiry', 'Arrives from your website'], ['Pipeline', 'Logged and assigned'], ['Quote', 'Sent from your address'], ['Invoice', 'Raised on acceptance']]" />
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══ 6 · READY-TO-USE PLATFORMS (unchanged offer) ═════════════════ --}}
    <section id="platforms" class="band-mist section-v2">
        <div class="container-wide">
            <x-ui.heading align="split" eyebrow="Ready-to-use business platforms"
                title="Need a website now? Pay nothing until your website is live."
                lede="Five industry platforms on one engine. The build is free and a flat monthly fee starts on go-live. Cancel any time." />

            @if ($packages->isEmpty())
                <div class="mt-12 rounded-2xl border border-dashed border-brand/25 bg-white p-10 text-center">
                    <p class="t-subtitle text-brand">Our platforms are being updated.</p>
                    <p class="mt-2 text-secondary">Tell us what you need and we will come back to you with options.</p>
                    <div class="mt-6"><x-ui.button :href="route('contact')" arrow>Talk to us</x-ui.button></div>
                </div>
            @else
                <div class="tile mt-12 overflow-hidden" data-reveal>
                    <div class="hidden grid-cols-12 gap-4 border-b border-hairline bg-brand-light px-7 py-3 text-xs font-semibold uppercase tracking-wider text-muted md:grid">
                        <span class="col-span-4">Platform</span>
                        @foreach ($tierColumns as $col)
                            <span class="col-span-2 text-right">{{ $col->headline_storage }} storage</span>
                        @endforeach
                        <span class="{{ str_replace('md:', '', $linkSpan) }} text-right"><span class="sr-only">Links</span></span>
                    </div>
                    <ul class="divide-y divide-hairline">
                        @foreach ($packages as $package)
                            <li class="grid items-center gap-x-4 gap-y-3 px-6 py-5 transition-colors hover:bg-brand-light/60 sm:px-7 md:grid-cols-12">
                                <div class="flex items-center gap-4 md:col-span-4">
                                    <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-brand-light text-brand ring-1 ring-hairline">
                                        <x-package-icon :name="$package->icon_key" class="h-5 w-5" />
                                    </span>
                                    <div>
                                        <p class="font-display text-lg font-bold text-brand">{{ $package->name }}</p>
                                        <p class="text-xs text-muted">{{ $package->vertical_label }}</p>
                                    </div>
                                </div>
                                <div class="flex gap-6 md:contents">
                                    @foreach ($package->tiers as $tier)
                                        <p class="md:col-span-2 md:text-right">
                                            <span class="t-figure text-2xl text-brand">{{ $tier->formatted_price }}</span>
                                            <span class="text-xs text-muted">/mo</span>
                                            <span class="block text-xs text-muted md:hidden">{{ $tier->headline_storage }} storage</span>
                                        </p>
                                    @endforeach
                                </div>
                                <div class="flex gap-5 text-sm font-semibold {{ $linkSpan }} md:justify-end">
                                    @if ($demoSlug = $package->demoSlug())
                                        <a href="{{ route('demos.show', $demoSlug) }}" class="text-secondary hover:text-accent">Demo</a>
                                    @endif
                                    <a href="{{ route('packages.show', $package) }}" class="text-accent hover:text-brand">Details &rarr;</a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="mt-6 flex flex-wrap items-center justify-between gap-4 text-sm text-secondary">
                    <p>All prices exclude VAT. Add-on modules are charged separately. {{ $brand['bulk']['note'] }}</p>
                    <a href="{{ route('packages.index') }}" class="font-semibold text-accent hover:text-brand">Compare platforms and add-ons &rarr;</a>
                </div>
            @endif
        </div>
    </section>

    {{-- ══ 7 · INDUSTRIES ══════════════════════════════════════════════ --}}
    @php $industryKeys = array_keys($industries); @endphp
    <section id="industries" class="band-paper section-v2"
             x-data="{
                active: '{{ $industryKeys[0] }}',
                keys: @js($industryKeys),
                init() { const h = location.hash.replace('#industry-', ''); if (this.keys.includes(h)) this.active = h },
                move(step) {
                    const i = (this.keys.indexOf(this.active) + step + this.keys.length) % this.keys.length;
                    this.active = this.keys[i];
                    this.$nextTick(() => document.getElementById('itab-' + this.active)?.focus());
                },
             }"
             x-on:show-industry.window="active = $event.detail; $el.scrollIntoView({ behavior: matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' })">
        <div class="container-wide">
            <x-ui.heading align="split" eyebrow="Industry solutions"
                title="Built for how your industry works."
                lede="Five run on platforms you can see working today. The rest start with a conversation about your process." />

            <div role="tablist" aria-label="Industries"
                 @keydown.arrow-right.prevent="move(1)" @keydown.arrow-left.prevent="move(-1)"
                 class="js-only snap-row -mx-5 mt-12 flex gap-2 overflow-x-auto px-5 sm:mx-0 sm:flex-wrap sm:px-0">
                @foreach ($industries as $key => $industry)
                    <button type="button" role="tab" id="itab-{{ $key }}" aria-controls="industry-{{ $key }}"
                            :aria-selected="active === '{{ $key }}' ? 'true' : 'false'"
                            :tabindex="active === '{{ $key }}' ? 0 : -1"
                            @click="active = '{{ $key }}'"
                            :class="{ 'bg-brand text-white ring-brand': active === '{{ $key }}', 'bg-white text-secondary ring-hairline hover:text-brand hover:ring-brand/30': ! (active === '{{ $key }}') }"
                            class="nav-item shrink-0 rounded-full px-4 py-2.5 text-sm font-semibold ring-1 {{ $loop->first ? 'bg-brand text-white ring-brand' : 'bg-white text-secondary ring-hairline' }}">
                        {{ $industry['short'] }}
                    </button>
                @endforeach
            </div>

            <div class="mt-10">
                @foreach ($industries as $key => $industry)
                    @php
                        $package = $industry['package'] ? $packagesBySlug->get($industry['package']) : null;
                        $tier = $package?->defaultTier();
                    @endphp
                    <div id="industry-{{ $key }}" role="tabpanel" aria-labelledby="itab-{{ $key }}" tabindex="0"
                         x-show="active === '{{ $key }}'"
                         x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="opacity-0"
                         @if (! $loop->first) style="display: none" @endif
                         class="js-panel scroll-mt-28">
                        <div class="grid items-center gap-10 lg:grid-cols-12 lg:gap-12">
                            <div class="lg:col-span-5">
                                <h3 class="t-subtitle text-brand">{{ $industry['name'] }}</h3>
                                <ol class="mt-6 space-y-4">
                                    @foreach ($industry['pains'] as $pain)
                                        <li class="flex gap-4">
                                            <span class="t-index mt-0.5 text-accent">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                            <span class="text-lg leading-snug text-brand">{{ $pain }}</span>
                                        </li>
                                    @endforeach
                                </ol>
                                <div class="mt-8 flex flex-wrap items-center gap-x-5 gap-y-3">
                                    @if ($package)
                                        @if ($industry['demo'])
                                            <x-ui.button :href="route('demos.show', $industry['demo'])" arrow>View the demo</x-ui.button>
                                        @endif
                                        <a href="{{ route('packages.show', $package) }}" class="text-sm font-semibold text-brand hover:text-accent">
                                            {{ $package->name }} platform @if ($tier)· from {{ $tier->formatted_price }}/mo @endif &rarr;
                                        </a>
                                    @else
                                        <x-ui.button :href="$quote('home-industry-'.$key)" arrow>Discuss your process</x-ui.button>
                                    @endif
                                </div>
                            </div>

                            <div class="relative lg:col-span-7">
                                @if ($industry['demo'])
                                    <div class="relative pb-8 pr-4 sm:pr-10">
                                        <x-ui.frame-browser :image="'demo-'.$industry['demo'].'-desktop'" tone="light" :url="'7hillsdigital.co.za/demos/'.$industry['demo']" />
                                        <div class="absolute -right-1 bottom-0 hidden w-[27%] sm:block">
                                            <x-ui.frame-phone :image="'demo-'.$industry['demo'].'-mobile'" />
                                        </div>
                                    </div>
                                @else
                                    <div class="rounded-3xl border border-hairline bg-brand-light p-6 sm:p-10">
                                        <x-ui.flow :steps="$key === 'corporate-operations'
                                            ? [['Request', 'A staff member asks'], ['Approve', 'The right manager signs off'], ['Act', 'The system does the work'], ['Report', 'Everyone sees the status']]
                                            : [['Intake', 'A client brief, not an email thread'], ['Documents', 'Collected in one place'], ['Sign-off', 'With an audit trail'], ['Billing', 'Out on time']]" />
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <p class="mt-14 border-t border-hairline pt-8 text-secondary" data-reveal>
                <span class="font-display font-bold text-brand">Your industry does not have to be one of the five.</span>
                Those platforms are a starting point, not a limit.
                <a href="{{ $quote('home-industries') }}" class="font-semibold text-accent hover:text-brand">Ask about yours &rarr;</a>
            </p>
        </div>
    </section>

    {{-- ══ 8 · OUR WORK — live installs and labelled demos ═══════════════
         Real installs from config/clients.php, public sites only; the
         LiveWorkTest rules apply exactly as on the current homepage. --}}
    @php $clients = config('clients', []); @endphp
    <section id="work" class="band-mist section-v2 scroll-mt-20">
        <div class="container-wide">
            <x-ui.heading align="split" eyebrow="Our work"
                title="Real clients. And demos that say they're demos."
                lede="The demos are openly invented. The client work is not: a business running on our software every day." />

            @if (filled($clients))
                <p class="t-label mt-14 text-muted">Live work</p>
                <div class="mt-5 grid gap-4 lg:grid-cols-12">
                    @foreach ($clients as $client)
                        <article @class(['tile flex flex-col p-7 sm:p-10', 'lg:col-span-7' => $loop->first, 'lg:col-span-5' => ! $loop->first]) data-spotlight data-reveal data-reveal-delay="{{ $loop->index * 80 }}">
                            <p class="t-label text-accent">{{ $client['vertical'] }}</p>
                            <h3 class="mt-3 font-display text-2xl font-bold text-brand">{{ $client['name'] }}</h3>
                            <p class="mt-4 leading-relaxed text-secondary">{{ $client['summary'] }}</p>
                            <ul @class(['mt-6 hidden flex-1 content-start gap-x-8 gap-y-2.5 text-sm text-secondary sm:grid', 'sm:grid-cols-2' => $loop->first])>
                                @foreach ($client['delivered'] as $item)
                                    <li class="flex gap-2.5">
                                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-accent" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path d="M3 8.5l3 3 7-7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        {{ $item }}
                                    </li>
                                @endforeach
                            </ul>
                            <div class="mt-8 flex flex-wrap items-end justify-between gap-4 border-t border-hairline pt-6">
                                <p class="text-xs text-muted">{{ $client['software'] }}</p>
                                @if (! empty($client['url']))
                                    <a href="{{ $client['url'] }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-sm font-semibold text-brand hover:text-accent">
                                        Visit the live site <span aria-hidden="true">&nearr;</span>
                                    </a>
                                @else
                                    <p class="text-sm text-muted">Behind the client&rsquo;s login, so there is nothing public to show.</p>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>

                <p class="mt-6 max-w-3xl text-sm leading-relaxed text-muted">
                    We do not walk prospects through a client&rsquo;s back office. It holds their customers&rsquo; personal
                    information, and that is not ours to show. Ask instead for a walkthrough of the same panel on a staging
                    install with fictional data.
                </p>
            @endif

            <div class="mt-16 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h3 class="font-display text-lg font-bold text-brand">Demonstrations</h3>
                    <p class="mt-1 text-sm text-muted">Every business, listing and price on these is invented, and each one says so.</p>
                </div>
                <a href="{{ route('demos') }}" class="text-sm font-semibold text-accent hover:text-brand">All demos &rarr;</a>
            </div>

            <ul class="snap-row -mx-5 mt-6 flex gap-4 overflow-x-auto px-5 pb-2 lg:mx-0 lg:grid lg:grid-cols-5 lg:overflow-visible lg:px-0">
                @foreach (config('demos', []) as $slug => $demo)
                    @php $thumb = \App\Support\ProductImages::get('demo-'.$slug.'-desktop'); @endphp
                    <li class="w-[72%] shrink-0 sm:w-[44%] lg:w-auto">
                        <a href="{{ route('demos.show', $slug) }}" class="group block">
                            <span class="relative block overflow-hidden rounded-xl border border-hairline bg-white transition group-hover:border-accent/40 group-hover:shadow-[0_18px_40px_-24px_var(--brand-dark)]">
                                @if ($thumb)
                                    <img src="{{ asset($thumb['file']) }}" alt="{{ $thumb['alt'] }}" width="{{ $thumb['width'] }}" height="{{ $thumb['height'] }}"
                                         loading="lazy" decoding="async" class="aspect-[16/10] w-full object-cover object-top transition duration-500 group-hover:scale-[1.02]">
                                @endif
                                <span class="absolute left-2.5 top-2.5 rounded-md bg-brand-dark/85 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-mint backdrop-blur">Demo</span>
                            </span>
                            <span class="mt-3 block text-sm font-semibold text-brand group-hover:text-accent">{{ $demo['name'] }}</span>
                            <span class="block text-xs text-muted">{{ $demo['vertical'] }} · fictional</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- ══ 9 · GET A QUOTE — pick the closest fit ════════════════════════ --}}
    <section id="quote" class="band-paper section-v2 scroll-mt-20">
        <div class="container-wide">
            <x-ui.heading align="split" eyebrow="Get a quote"
                title="Tell us what you need."
                lede="Pick the closest fit. We call you back to understand it properly, then send a written proposal." />

            <ul class="mt-10 grid grid-cols-2 gap-2.5 sm:mt-12 sm:gap-3 lg:grid-cols-4">
                @foreach ($quoteTypes as $type => [$title, $line, $proves])
                    @php $preview = Revamp::catalogue()[$proves]['preview']; @endphp
                    <li data-reveal data-reveal-delay="{{ $loop->index * 40 }}">
                        <a href="{{ $quote('home-quote', $type) }}" data-cta="quote-{{ $type }}"
                           @class(['svc-card tile group flex h-full items-start gap-4 p-4 sm:p-5', '!border-dashed !border-brand/30' => $preview]) data-spotlight>
                            <x-ui.motif :name="$motifs[$type] ?? 'code'" class="svc-motif hidden h-9 w-9 shrink-0 text-brand sm:block" />
                            <span class="flex-1">
                                <span class="block font-display font-bold text-brand">{{ $title }}</span>
                                <span class="mt-0.5 block text-xs leading-snug text-muted sm:text-sm">{{ $line }}</span>
                                @if ($preview)<span class="mt-2 inline-block rounded border border-dashed border-brand/40 px-1.5 text-[10px] font-semibold text-secondary">Preview only</span>@endif
                            </span>
                        </a>
                    </li>
                @endforeach
                <li>
                    <a href="{{ route('contact', ['from' => 'home-quote']) }}" data-cta="quote-platform" class="svc-card tile group flex h-full items-start gap-4 p-4 sm:p-5" data-spotlight>
                        <x-ui.motif name="browser" class="svc-motif hidden h-9 w-9 shrink-0 text-brand sm:block" />
                        <span class="flex-1">
                            <span class="block font-display font-bold text-brand">A website platform</span>
                            <span class="mt-0.5 block text-xs leading-snug text-muted sm:text-sm">@if ($from) From {{ $from }}/mo, @endif nothing until it is live</span>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ $quote('home-quote', 'other') }}" data-cta="quote-other" class="svc-card tile group flex h-full items-start gap-4 p-4 sm:p-5" data-spotlight>
                        <x-ui.motif name="nodes" class="svc-motif hidden h-9 w-9 shrink-0 text-brand sm:block" />
                        <span class="flex-1">
                            <span class="block font-display font-bold text-brand">Something else</span>
                            <span class="mt-0.5 block text-xs leading-snug text-muted sm:text-sm">Describe it, and we will tell you honestly what fits</span>
                        </span>
                    </a>
                </li>
            </ul>

            <div class="mt-14 grid gap-10 border-t border-hairline pt-10 lg:grid-cols-12">
                <ol class="grid grid-cols-2 gap-6 sm:grid-cols-4 lg:col-span-8">
                    @foreach ([['Tell us', 'What you need, in your words'], ['We call you', 'To understand it properly'], ['Proposal', 'Scope and price, in writing'], ['Build & support', 'By the people who wrote it']] as $i => [$title, $text])
                        <li>
                            <p class="t-index text-accent">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</p>
                            <p class="mt-2 font-display font-bold text-brand">{{ $title }}</p>
                            <p class="mt-1 text-sm text-muted">{{ $text }}</p>
                        </li>
                    @endforeach
                </ol>
                <ul class="space-y-2.5 text-sm text-secondary lg:col-span-4 lg:border-l lg:border-hairline lg:pl-10">
                    @foreach (['One partner, one invoice', 'Your data, email and domain stay yours', 'POPIA: we act on your instructions, as your operator'] as $fact)
                        <li class="flex gap-2.5"><svg class="mt-0.5 h-4 w-4 shrink-0 text-accent" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path d="M3 8.5l3 3 7-7" stroke-linecap="round" stroke-linejoin="round"/></svg>{{ $fact }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>

    {{-- ══ 10 · CONSULTATION CTA ════════════════════════════════════════ --}}
    <section class="band-night on-dark">
        <div class="contours absolute inset-0 -z-10 opacity-[0.12]" aria-hidden="true"></div>
        <div class="container-wide grid gap-10 py-20 sm:py-24 lg:grid-cols-12 lg:items-end">
            <div class="lg:col-span-8">
                <h2 class="t-display text-white">Have a project in mind? <span class="text-mint">Let&rsquo;s talk it through.</span></h2>
                <p class="t-lede mt-6 max-w-2xl text-white/70">You focus on running the business. We will take care of the IT.</p>
            </div>
            <div class="flex flex-col gap-4 lg:col-span-4 lg:items-end">
                <x-ui.button :href="$quote('home-close')" tone="dark" size="lg" arrow data-cta="close-quote">Get a Quote</x-ui.button>
                <p class="flex flex-wrap gap-x-5 gap-y-2 text-sm">
                    <a href="tel:{{ preg_replace('/\s+/', '', $brand['contact']['phone']) }}" class="font-semibold text-white hover:text-mint">{{ $brand['contact']['phone'] }}</a>
                    <x-whatsapp-link label="WhatsApp us" message="Hi 7Hills Digital, I'm on your home page and would like to discuss a project." class="font-semibold text-mint hover:brightness-110" />
                </p>
            </div>
        </div>
    </section>

</x-ui.layout>
