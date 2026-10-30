{{--
    Homepage v2 — PROTOTYPE for visual approval (docs/revamp/design/).
    Served only where REVAMP_HOME_V2=true. Every price comes from the database,
    every screenshot from public/images/product/manifest.json, and every
    offering's availability from config/revamp.php (moving to the DB in Phase 2).
--}}
@php
    $brand = config('brand');
    $services = \App\Support\Revamp::services();
    $industries = \App\Support\Revamp::industries();
    $fromTier = $packages->flatMap->tiers->filter(fn ($t) => $t->price_monthly !== null)->sortBy('price_monthly')->first();
    $from = $fromTier?->formatted_price;
    $invoicing = $addOns->firstWhere('slug', 'invoicing');
    $packagesBySlug = $packages->keyBy('slug');
    $tierColumns = $packages->first()?->tiers ?? collect();
    // Literal class names, so Tailwind's scanner can see them.
    $linkSpan = ['md:col-span-8', 'md:col-span-6', 'md:col-span-4', 'md:col-span-2'][min($tierColumns->count(), 3)];
@endphp

<x-ui.layout overlay>

    {{-- ══ 1 · HERO ═════════════════════════════════════════════════════ --}}
    <section id="hero" class="band-night on-dark -mt-[77px] pt-[77px]">
        <div class="contours absolute inset-y-0 right-0 -z-10 w-full opacity-[0.16] lg:w-[78%]" aria-hidden="true"></div>
        <div class="absolute inset-0 -z-10 bg-[radial-gradient(60rem_36rem_at_78%_42%,color-mix(in_oklab,var(--brand-mint)_9%,transparent),transparent_70%)]" aria-hidden="true"></div>
        <div class="absolute inset-y-0 left-0 -z-10 w-2/3 bg-gradient-to-r from-brand-dark via-brand-dark/85 to-transparent" aria-hidden="true"></div>

        <div class="container-wide grid items-center gap-y-16 pb-20 pt-14 sm:pt-20 lg:grid-cols-12 lg:gap-x-10 lg:pb-32 lg:pt-24">
            <div class="lg:col-span-7">
                <p class="inline-flex items-center gap-2.5 rounded-full border border-white/12 bg-white/[0.04] px-3.5 py-1.5 text-xs font-medium text-white/75" data-reveal>
                    <span class="h-1.5 w-1.5 rounded-full bg-mint" aria-hidden="true"></span>
                    Platforms · Business solutions · Enterprise software
                </p>

                <h1 class="t-hero mt-7 text-white" data-reveal data-reveal-delay="60">
                    Technology That <span class="text-mint">Works</span> for Your Business.
                </h1>

                <p class="t-lede mt-7 max-w-[34rem] text-white/70" data-reveal data-reveal-delay="120">
                    From ready-to-use business platforms to intelligent custom software, we build,
                    automate and connect the technology that drives your business forward.
                </p>

                <div class="mt-10 flex flex-col gap-3 sm:flex-row" data-reveal data-reveal-delay="180">
                    <x-ui.button :href="route('packages.index')" tone="dark" size="lg" arrow data-cta="hero-subscription">
                        Explore Business Platforms
                    </x-ui.button>
                    <x-ui.button :href="route('contact', ['type' => 'project', 'from' => 'home-hero'])" tone="dark" variant="secondary" size="lg" data-cta="hero-project">
                        Discuss a Custom Solution
                    </x-ui.button>
                </div>

                <dl class="mt-14 grid max-w-xl grid-cols-3 gap-6 border-t border-white/10 pt-7" data-reveal data-reveal-delay="240">
                    @if ($from)
                        <div>
                            <dt class="sr-only">Platforms from</dt>
                            <dd class="t-figure text-2xl text-white sm:text-3xl">{{ $from }}</dd>
                            <dd class="mt-2 text-xs leading-snug text-white/50">a month for a platform, excl. VAT</dd>
                        </div>
                    @endif
                    <div>
                        <dt class="sr-only">Build cost</dt>
                        <dd class="t-figure text-2xl text-white sm:text-3xl">R0</dd>
                        <dd class="mt-2 text-xs leading-snug text-white/50">to build a platform. No minimum term.</dd>
                    </div>
                    <div>
                        <dt class="sr-only">In production</dt>
                        <dd class="t-figure text-2xl text-white sm:text-3xl">Live</dd>
                        <dd class="mt-2 text-xs leading-snug text-white/50">in production at Excellent Real&nbsp;Estate</dd>
                    </div>
                </dl>
            </div>

            {{-- Product composition: genuine screens, each labelled for what it is. --}}
            <div class="relative lg:col-span-5" data-reveal data-reveal-delay="200">
                <div class="relative pb-10 pl-6 sm:pl-12 lg:pl-4" data-depth-scene>
                    <div data-depth="1" class="lg:w-[152%]">
                        <x-ui.frame-browser image="admin-enquiries" url="yourbusiness.co.za/admin/enquiries" :caption="false" eager />
                    </div>

                    <div data-depth="2.4" class="absolute -bottom-2 left-0 w-[34%] max-w-[13rem] sm:-left-2 lg:-left-14 lg:w-[40%]">
                        <x-ui.frame-phone image="demo-realty-mobile" />
                    </div>

                    {{-- Annotations, engineering-drawing style. --}}
                    <p class="absolute -bottom-8 left-[38%] flex items-center gap-2 text-[11px] text-white/55 sm:left-[33%] lg:left-[30%]">
                        <span class="h-px w-6 bg-mint/60" aria-hidden="true"></span>
                        Demo tenant · fictional business
                    </p>
                    <p class="absolute -top-7 right-0 hidden items-center gap-2 text-[11px] text-white/55 sm:flex lg:left-[40%] lg:right-auto">
                        Back office · staging install, fictional data
                        <span class="h-px w-6 bg-mint/60" aria-hidden="true"></span>
                    </p>
                </div>
            </div>
        </div>

        <div class="absolute inset-x-0 bottom-0 h-px bg-gradient-to-r from-transparent via-mint/35 to-transparent" aria-hidden="true"></div>
    </section>

    {{-- ══ 2 · BUSINESS PROBLEMS ═══════════════════════════════════════ --}}
    <section id="problems" class="band-paper section-v2">
        <div class="container-wide">
            <x-ui.heading
                align="split"
                eyebrow="Business problems we solve"
                title="Most businesses don't need more software. They need the right pieces working together."
                lede="We start from the problem that costs you money, then choose the smallest thing that fixes it — a ready-made platform or something built for you." />

            <div class="mt-14 grid gap-4 lg:grid-cols-12">
                {{-- A · Leads — the big tile, with our own back office in it --}}
                <article class="tile flex flex-col p-7 sm:p-9 lg:col-span-7 lg:row-span-2" data-spotlight data-reveal>
                    <div class="flex flex-wrap items-center gap-3">
                        <p class="t-label text-accent">CRM &amp; customer management</p>
                        <x-ui.badge status="built_to_order" />
                    </div>
                    <h3 class="t-subtitle mt-5 max-w-md text-brand">Leads lost in WhatsApp groups and inboxes.</h3>
                    <p class="mt-3 max-w-md leading-relaxed text-secondary">
                        Every enquiry lands in one pipeline your whole team can see — who asked,
                        what for, who answered and when. Nothing waits in one person's phone over the weekend.
                    </p>

                    <div class="relative -mb-9 -mr-9 ml-2 mt-10 flex-1 sm:ml-10">
                        @php $shot = \App\Support\ProductImages::get('admin-enquiries'); @endphp
                        <figure class="h-full overflow-hidden rounded-tl-2xl border-l border-t border-hairline bg-brand-light shadow-[0_-12px_40px_-24px_var(--brand-dark)]">
                            <img src="{{ asset($shot['file']) }}" alt="{{ $shot['alt'] }}" width="{{ $shot['width'] }}" height="{{ $shot['height'] }}"
                                 loading="lazy" decoding="async" class="h-full min-h-56 w-full object-cover object-left-top">
                        </figure>
                        <p class="absolute -top-6 left-0 flex items-center gap-2 text-[11px] text-muted">
                            <span class="h-px w-5 bg-accent/60" aria-hidden="true"></span>{{ $shot['label'] }}
                        </p>
                    </div>
                </article>

                {{-- B · The verified statistic --}}
                <article class="tile-dark on-dark flex flex-col justify-between p-7 text-white sm:p-9 lg:col-span-5" data-spotlight data-reveal data-reveal-delay="70">
                    <p class="t-label text-mint">Cash flow</p>
                    <div class="mt-8">
                        <p class="t-figure text-[4.5rem] text-mint sm:text-[5.5rem]">62%</p>
                        <p class="mt-4 max-w-sm text-lg leading-snug text-white/85">
                            of South African small businesses had cash-flow problems in the past year.
                        </p>
                    </div>
                    <p class="mt-8 border-t border-white/10 pt-4 text-xs text-white/45">
                        Source: Xero, <cite class="not-italic">The State of South African Small Business 2026</cite> — 427 businesses surveyed, Jan–Feb 2026.
                    </p>
                </article>

                {{-- C · Getting paid — the one solution that is available today --}}
                <article class="tile flex flex-col bg-brand-light p-7 sm:p-9 lg:col-span-5" data-spotlight data-reveal data-reveal-delay="120">
                    <div class="flex flex-wrap items-center gap-3">
                        <p class="t-label text-accent">Invoicing</p>
                        <x-ui.badge status="available" />
                    </div>
                    <h3 class="t-subtitle mt-5 text-brand">Getting paid on time.</h3>
                    <p class="mt-3 leading-relaxed text-secondary">
                        Quotes, invoices and recurring billing that go out without someone remembering to send them —
                        from your own email address, not a stranger's.
                    </p>
                    <div class="mt-auto flex flex-wrap items-center justify-between gap-4 pt-7">
                        @if ($invoicing && ! $invoicing->is_metered)
                            <p class="text-sm text-secondary"><span class="t-figure text-2xl text-brand">+{{ $invoicing->formatted_price }}</span> / month, charged separately</p>
                        @endif
                        <a href="{{ route('packages.index') }}#add-ons" class="text-sm font-semibold text-accent hover:text-brand">See the module &rarr;</a>
                    </div>
                </article>

                {{-- D–G · four smaller problems, each with its own visual language --}}
                <div class="grid gap-4 sm:grid-cols-2 lg:col-span-12 lg:grid-cols-4">
                    <article class="tile flex flex-col p-7" data-spotlight data-reveal>
                        <x-ui.diagram name="steps" tone="light" :label="false" class="h-24 w-40" />
                        <h3 class="mt-6 font-display text-lg font-bold text-brand">Admin that eats the week</h3>
                        <p class="mt-2 text-sm leading-relaxed text-secondary">Automate the job someone repeats every Friday — reminders, hand-offs and follow-ups.</p>
                        <div class="mt-auto pt-6"><x-ui.badge status="built_to_order" /></div>
                    </article>

                    <article class="tile flex flex-col p-7" data-spotlight data-reveal data-reveal-delay="60">
                        <p class="t-figure flex h-24 items-end text-5xl text-brand">24<span class="text-accent">/</span>7</p>
                        <h3 class="mt-6 font-display text-lg font-bold text-brand">Bookings by phone only</h3>
                        <p class="mt-2 text-sm leading-relaxed text-secondary">Customers book themselves, day or night, into a calendar you control.</p>
                        <div class="mt-auto pt-6"><x-ui.badge status="built_to_order" /></div>
                    </article>

                    <article class="tile flex flex-col p-7" data-spotlight data-reveal data-reveal-delay="120">
                        <x-ui.diagram name="doc" tone="light" :label="false" class="h-24 w-40" />
                        <h3 class="mt-6 font-display text-lg font-bold text-brand">Paper, signatures, approvals</h3>
                        <p class="mt-2 text-sm leading-relaxed text-secondary">Forms and sign-off on a screen, with a record of who approved what, and when.</p>
                        <div class="mt-auto pt-6"><x-ui.badge status="built_to_order" /></div>
                    </article>

                    <article class="tile flex flex-col p-7" data-spotlight data-reveal data-reveal-delay="180">
                        <x-ui.diagram name="bars" tone="light" :label="false" class="h-24 w-40" />
                        <h3 class="mt-6 font-display text-lg font-bold text-brand">Flying blind</h3>
                        <p class="mt-2 text-sm leading-relaxed text-secondary">The numbers that run the business, on one screen instead of five spreadsheets.</p>
                        <div class="mt-auto pt-6"><x-ui.badge status="built_to_order" /></div>
                    </article>
                </div>
            </div>
            <p class="mt-6 text-xs text-muted">Small diagrams are illustrations, not product screens.</p>
        </div>
    </section>

    {{-- ══ 3 · PLATFORMS vs CUSTOM — the spectrum ═══════════════════════ --}}
    <section id="ways" class="band-mist section-v2">
        <div class="container-wide">
            <x-ui.heading align="center" eyebrow="Three ways to work with us"
                title="Ready-made, built for you — and everything in between." />

            <div class="relative mt-16">
                <div class="mb-6 hidden items-center justify-between text-xs font-medium text-muted lg:flex" aria-hidden="true">
                    <span>&larr; More ready-made · faster · fixed price</span>
                    <span>More bespoke · scoped to you &rarr;</span>
                </div>

                <div class="relative">
                <div class="absolute left-[22px] top-0 h-full w-px bg-gradient-to-b from-accent/50 via-accent/30 to-accent/10 lg:left-[16.6%] lg:right-[16.6%] lg:top-[22px] lg:h-px lg:w-auto lg:bg-gradient-to-r" aria-hidden="true"></div>

                <ol class="relative grid gap-12 lg:grid-cols-3 lg:gap-8">
                    @php
                        $stations = [
                            ['A', 'Business Platforms', 'A business in one of our industries that wants to start now.', [
                                ['Time to live', $brand['terms']['build_time']],
                                ['Pricing', $from ? 'From '.$from.'/month excl. VAT · R0 build' : 'Monthly subscription · R0 build'],
                                ['You get', 'Website and back office, hosted and supported'],
                            ], 'Explore platforms', route('packages.index'), false],
                            ['B', 'Business Solutions', 'One specific problem: leads, bookings, invoicing, approvals.', [
                                ['Time to live', 'Scoped to the problem'],
                                ['Pricing', 'Module price, or quoted'],
                                ['You get', 'The solution, connected to what you already use'],
                            ], 'Explore solutions', '#problems', true],
                            ['C', 'Enterprise Software', 'Complex, integrated or larger-scale systems.', [
                                ['Time to live', 'Phased, starting with discovery'],
                                ['Pricing', 'Quoted — fixed-scope phases or a retainer'],
                                ['You get', 'A team, a process and governance'],
                            ], 'Discuss a project', route('contact', ['type' => 'project', 'from' => 'home-spectrum']), false],
                        ];
                    @endphp

                    @foreach ($stations as $i => [$letter, $name, $fit, $rows, $cta, $href, $featured])
                        <li class="relative pl-16 lg:pl-0" data-reveal data-reveal-delay="{{ $i * 80 }}">
                            <span @class([
                                'absolute left-0 top-0 grid h-11 w-11 place-items-center rounded-full font-display text-sm font-extrabold lg:relative lg:mx-auto lg:mb-6',
                                'bg-accent text-white shadow-[0_0_0_6px_var(--brand-light),0_0_0_7px_color-mix(in_oklab,var(--brand-accent)_40%,transparent)]' => $featured,
                                'bg-white text-brand ring-1 ring-brand/15 shadow-[0_0_0_6px_var(--brand-light)]' => ! $featured,
                            ])>{{ $letter }}</span>

                            <div @class(['rounded-2xl lg:p-8', 'relative lg:bg-white lg:edge-glint lg:elevate-3' => $featured])>
                                <h3 class="t-subtitle text-brand lg:text-center">{{ $name }}</h3>
                                <p class="mt-2 text-secondary lg:text-center">{{ $fit }}</p>
                                <dl class="mt-6 divide-y divide-hairline border-y border-hairline text-sm">
                                    @foreach ($rows as [$k, $v])
                                        <div class="flex justify-between gap-4 py-3">
                                            <dt class="shrink-0 text-muted">{{ $k }}</dt>
                                            <dd class="text-right font-medium text-brand">{{ $v }}</dd>
                                        </div>
                                    @endforeach
                                </dl>
                                <div class="mt-6 lg:text-center">
                                    <x-ui.button :href="$href" :variant="$featured ? 'primary' : 'secondary'" arrow>{{ $cta }}</x-ui.button>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ol>
                </div>
            </div>

            <p class="mt-14 text-center text-secondary">
                Not sure which? Tell us the problem — we'll say honestly which fits, including when it's the cheapest one.
            </p>
        </div>
    </section>

    {{-- ══ 4 · SOFTWARE SHOWCASE (tabs) ═════════════════════════════════ --}}
    @if (count($services))
        @php $slugs = array_keys($services); @endphp
        <section id="showcase" class="band-night on-dark section-v2"
                 x-data="{
                    active: '{{ $slugs[0] }}',
                    slugs: @js($slugs),
                    init() {
                        const h = location.hash.replace('#service-', '');
                        if (this.slugs.includes(h)) this.active = h;
                    },
                    move(step) {
                        const i = (this.slugs.indexOf(this.active) + step + this.slugs.length) % this.slugs.length;
                        this.active = this.slugs[i];
                        this.$nextTick(() => document.getElementById('tab-' + this.active)?.focus());
                    },
                    edge(last) {
                        this.active = this.slugs[last ? this.slugs.length - 1 : 0];
                        this.$nextTick(() => document.getElementById('tab-' + this.active)?.focus());
                    },
                 }"
                 @show-service.window="active = $event.detail; $el.scrollIntoView({ behavior: matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' })">
            <div class="contours absolute inset-y-0 left-0 -z-10 w-full -scale-x-100 opacity-[0.07]" aria-hidden="true"></div>

            <div class="container-wide">
                <x-ui.heading tone="dark" align="split"
                    eyebrow="Software engineering"
                    title="When the off-the-shelf answer doesn't fit."
                    lede="Custom software, automation and integration — scoped in phases, demonstrated every sprint, and supported by the people who wrote it." />

                <div class="mt-14 grid gap-8 lg:grid-cols-12 lg:gap-10">
                    {{-- Tab rail: vertical on desktop, a scrolling chip row on mobile. --}}
                    <div role="tablist" aria-label="Services" aria-orientation="vertical"
                         @keydown.arrow-down.prevent="move(1)" @keydown.arrow-right.prevent="move(1)"
                         @keydown.arrow-up.prevent="move(-1)" @keydown.arrow-left.prevent="move(-1)"
                         @keydown.home.prevent="edge(false)" @keydown.end.prevent="edge(true)"
                         class="js-only snap-row -mx-5 flex gap-2 overflow-x-auto px-5 lg:col-span-4 lg:mx-0 lg:flex-col lg:gap-1 lg:overflow-visible lg:px-0">
                        @foreach ($services as $slug => $service)
                            <button type="button" role="tab"
                                    id="tab-{{ $slug }}"
                                    aria-controls="service-{{ $slug }}"
                                    :aria-selected="active === '{{ $slug }}' ? 'true' : 'false'"
                                    :tabindex="active === '{{ $slug }}' ? 0 : -1"
                                    @click="active = '{{ $slug }}'"
                                    :class="active === '{{ $slug }}' ? 'bg-white/[0.07] text-white lg:before:bg-mint' : 'text-white/55 hover:bg-white/[0.03] hover:text-white/85 lg:before:bg-transparent'"
                                    class="relative flex shrink-0 items-center gap-4 rounded-xl px-4 py-3.5 text-left transition lg:before:absolute lg:before:inset-y-3 lg:before:left-0 lg:before:w-0.5 lg:before:rounded-full {{ $loop->first ? 'bg-white/[0.07] text-white' : 'text-white/55' }}">
                                <span class="t-index text-white/55">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                <span class="whitespace-nowrap font-medium lg:whitespace-normal">{{ $service['name'] }}</span>
                                @if ($service['preview'])
                                    <span class="ml-auto rounded border border-dashed border-white/30 px-1.5 text-[10px] text-white/50">preview</span>
                                @endif
                            </button>
                        @endforeach
                    </div>

                    <div class="lg:col-span-8">
                        @foreach ($services as $slug => $service)
                            <div id="service-{{ $slug }}" role="tabpanel" aria-labelledby="tab-{{ $slug }}" tabindex="0"
                                 x-show="active === '{{ $slug }}'"
                                 x-transition:enter="transition duration-200 ease-out"
                                 x-transition:enter-start="opacity-0"
                                 @if (! $loop->first) style="display: none" @endif
                                 class="js-panel scroll-mt-28">
                                <article @class([
                                    'tile-dark relative grid gap-10 p-7 sm:p-10 lg:grid-cols-2',
                                    'edge-glint' => ! $service['preview'],
                                    '!border-dashed !border-white/25' => $service['preview'],
                                ])>
                                    <div class="flex flex-col">
                                        @if ($service['preview'])
                                            <p class="mb-5 rounded-lg border border-dashed border-white/25 px-3 py-2 text-xs leading-relaxed text-white/60">
                                                <strong class="text-white/80">Preview only.</strong> Capability not yet confirmed by the owner —
                                                this panel is hidden in production.
                                            </p>
                                        @endif
                                        <x-ui.badge :status="$service['availability']" tone="dark" class="self-start" />
                                        <h3 class="t-subtitle mt-5 text-white">{{ $service['headline'] }}</h3>
                                        <ul class="mt-6 space-y-3.5">
                                            @foreach ($service['benefits'] as $benefit)
                                                <li class="flex gap-3 text-white/75">
                                                    <svg class="mt-1 h-4 w-4 shrink-0 text-mint" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path d="M3 8.5l3 3 7-7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                                    <span class="leading-relaxed">{{ $benefit }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <div class="mt-8 rounded-xl border border-white/10 bg-white/[0.03] p-4">
                                            <p class="t-label !text-[0.65rem] text-white/45">Typical engagement</p>
                                            <p class="mt-2 text-sm leading-relaxed text-white/80">{{ $service['engagement'] }}</p>
                                        </div>
                                        @unless ($service['preview'])
                                            <div class="mt-8">
                                                <x-ui.button :href="route('contact', ['type' => 'project', 'interest' => $slug, 'from' => 'home-showcase'])" tone="dark" arrow>
                                                    Discuss {{ strtolower($service['name']) }}
                                                </x-ui.button>
                                            </div>
                                        @endunless
                                    </div>

                                    <div class="flex flex-col justify-center">
                                        @if (str_starts_with($service['visual'], 'diagram:'))
                                            <div class="rounded-xl border border-white/10 bg-brand-dark/50 p-5">
                                                <x-ui.diagram :name="substr($service['visual'], 8)" tone="dark" />
                                            </div>
                                        @elseif (str_ends_with($service['visual'], '-mobile'))
                                            <x-ui.frame-phone :image="$service['visual']" class="mx-auto w-48" />
                                        @else
                                            <x-ui.frame-browser :image="$service['visual']" url="yourbusiness.co.za/admin" :caption="false" />
                                        @endif
                                        <p class="mt-4 text-xs leading-relaxed text-white/50">{{ $service['visual_caption'] }}</p>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ══ 5 · INDUSTRIES ══════════════════════════════════════════════ --}}
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
             @show-industry.window="active = $event.detail; $el.scrollIntoView({ behavior: matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' })">
        <div class="container-wide">
            <x-ui.heading align="split" eyebrow="Industry solutions"
                title="Built for how your industry actually works."
                lede="Five run on ready-to-use platforms you can see working today. The rest start with a conversation about your process." />

            <div role="tablist" aria-label="Industries"
                 @keydown.arrow-right.prevent="move(1)" @keydown.arrow-left.prevent="move(-1)"
                 class="js-only snap-row -mx-5 mt-12 flex gap-2 overflow-x-auto px-5 sm:mx-0 sm:flex-wrap sm:px-0">
                @foreach ($industries as $key => $industry)
                    <button type="button" role="tab" id="itab-{{ $key }}" aria-controls="industry-{{ $key }}"
                            :aria-selected="active === '{{ $key }}' ? 'true' : 'false'"
                            :tabindex="active === '{{ $key }}' ? 0 : -1"
                            @click="active = '{{ $key }}'"
                            :class="active === '{{ $key }}' ? 'bg-brand text-white ring-brand' : 'bg-white text-secondary ring-hairline hover:text-brand hover:ring-brand/30'"
                            class="shrink-0 rounded-full px-4 py-2.5 text-sm font-semibold ring-1 transition {{ $loop->first ? 'bg-brand text-white ring-brand' : 'bg-white text-secondary ring-hairline' }}">
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
                         x-transition:enter="transition duration-200 ease-out"
                         x-transition:enter-start="opacity-0"
                         @if (! $loop->first) style="display: none" @endif
                         class="js-panel scroll-mt-28">
                        <div class="grid items-center gap-12 lg:grid-cols-12">
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

                                <div class="mt-9 rounded-2xl border border-hairline bg-brand-light p-5">
                                    @if ($package)
                                        <p class="text-sm text-muted">Ready-to-use platform</p>
                                        <p class="mt-1 flex flex-wrap items-baseline gap-x-3">
                                            <span class="font-display text-xl font-bold text-brand">{{ $package->name }}</span>
                                            @if ($tier)
                                                <span class="text-sm text-secondary">from <strong class="text-brand">{{ $tier->formatted_price }}</strong>/month excl. VAT</span>
                                            @endif
                                        </p>
                                        @unless ($industry['demo'])
                                            <p class="mt-2 text-xs text-muted">No demo for this industry yet — the Business platform is configured to your trade.</p>
                                        @endunless
                                        <div class="mt-5 flex flex-wrap gap-2">
                                            @if ($industry['demo'])
                                                <x-ui.button :href="route('demos.show', $industry['demo'])" arrow>View the demo</x-ui.button>
                                            @endif
                                            <x-ui.button :href="route('packages.show', $package)" variant="secondary">Platform details</x-ui.button>
                                        </div>
                                    @else
                                        <p class="text-sm text-muted">Delivered as a business solution</p>
                                        <p class="mt-1 font-display text-xl font-bold text-brand">Scoped to your organisation</p>
                                        <div class="mt-5">
                                            <x-ui.button :href="route('contact', ['type' => 'project', 'from' => 'home-industry-'.$key])" arrow>Discuss your process</x-ui.button>
                                        </div>
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
                                    <div class="grid items-center gap-6 rounded-3xl border border-hairline bg-brand-light p-6 sm:grid-cols-[1fr_auto] sm:p-10">
                                        <div class="rounded-xl border border-hairline bg-white p-5">
                                            <x-ui.diagram :name="$key === 'corporate-operations' ? 'hub' : 'flow'" tone="light" />
                                        </div>
                                        <x-ui.frame-phone image="admin-enquiries-mobile" class="mx-auto w-40" caption tone="light" />
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- The promise that we are not limited to five (PublicSiteTest). --}}
            <div class="mt-20 grid gap-6 rounded-3xl border border-hairline p-7 sm:p-10 lg:grid-cols-12 lg:items-center" data-reveal>
                <div class="lg:col-span-8">
                    <h3 class="t-subtitle text-brand">Your industry does not have to be one of the five.</h3>
                    <p class="mt-3 max-w-2xl leading-relaxed text-secondary">
                        Those platforms exist because those industries kept asking for the same thing. They are a starting point,
                        not a limit — if your business has a process, we can put it on a screen.
                        <span class="font-medium text-brand">You focus on running the business. We will take care of the IT.</span>
                    </p>
                </div>
                <div class="lg:col-span-4 lg:text-right">
                    <x-ui.button :href="route('contact', ['type' => 'project', 'from' => 'home-industries'])" variant="secondary" arrow>Ask about your industry</x-ui.button>
                </div>
            </div>
        </div>
    </section>

    {{-- ══ 6 · LIVE WORK & DEMOS ═══════════════════════════════════════
         Real installs from config/clients.php. Public sites only — the
         LiveWorkTest rules apply exactly as on the current homepage. --}}
    @php $clients = config('clients', []); @endphp
    <section id="work" class="band-mist section-v2">
        <div class="container-wide">
            <x-ui.heading align="split" eyebrow="Live work"
                title="Real clients. And demos that say they're demos."
                lede="The demos are openly invented. These are not — a business running on our platform every day, with its own stock, enquiries and invoices." />

            @if (filled($clients))
                <div class="mt-14 grid gap-4 lg:grid-cols-12">
                    @foreach ($clients as $client)
                        <article @class(['tile flex flex-col p-7 sm:p-10', 'lg:col-span-7' => $loop->first, 'lg:col-span-5' => ! $loop->first]) data-spotlight data-reveal data-reveal-delay="{{ $loop->index * 80 }}">
                            <p class="t-label text-accent">{{ $client['vertical'] }}</p>
                            <h3 class="mt-3 font-display text-2xl font-bold text-brand">{{ $client['name'] }}</h3>
                            <p class="mt-4 leading-relaxed text-secondary">{{ $client['summary'] }}</p>
                            <ul @class(['mt-6 grid flex-1 content-start gap-x-8 gap-y-2.5 text-sm text-secondary', 'sm:grid-cols-2' => $loop->first])>
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
                    <p class="mt-1 text-sm text-muted">Every business, listing and price on these is invented — and each one says so.</p>
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

    {{-- ══ 7 · PROCESS ═════════════════════════════════════════════════ --}}
    <section id="process" class="band-paper section-v2" x-data="{ track: 'platform' }">
        <div class="container-wide">
            <div class="flex flex-wrap items-end justify-between gap-8">
                <x-ui.heading eyebrow="How we deliver" title="A process you can see, from the first call to support." />

                <div role="group" aria-label="Choose a delivery track" class="js-only inline-flex rounded-full bg-brand-light p-1 ring-1 ring-hairline" data-reveal>
                    @foreach (['platform' => 'Platform subscription', 'project' => 'Custom project'] as $k => $label)
                        <button type="button" @click="track = '{{ $k }}'" :aria-pressed="track === '{{ $k }}' ? 'true' : 'false'"
                                :class="track === '{{ $k }}' ? 'bg-white text-brand shadow-sm ring-1 ring-hairline' : 'text-muted hover:text-brand'"
                                class="rounded-full px-4 py-2 text-sm font-semibold transition {{ $k === 'platform' ? 'bg-white text-brand shadow-sm ring-1 ring-hairline' : 'text-muted' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>

            @php
                $tracks = [
                    'platform' => [
                        ['Choose your platform', 'Pick the industry platform and tier. We confirm what you need — nothing to pay yet.', 'Day 1'],
                        ['Send your content', 'Photos, prices and copy. We tell you exactly what is needed, so nothing stalls.', 'Week 1'],
                        ['We build and configure', 'Your website and back office, set up for how your trade works.', $brand['terms']['build_time']],
                        ['Live and supported', $brand['terms']['billing'].'. Support and updates included.', 'Monthly'],
                    ],
                    'project' => [
                        ['Discover', 'Workshops to map the process and agree what success looks like, in writing.', '1–2 weeks'],
                        ['Design', 'A clickable prototype you can test before production code is written.', '1–3 weeks'],
                        ['Build', 'Two-week sprints, each ending with working software you can try.', 'Sprints'],
                        ['Run', 'Hosting, support and improvements from the team that built it.', 'Ongoing'],
                    ],
                ];
            @endphp

            @foreach ($tracks as $k => $steps)
                <div x-show="track === '{{ $k }}'" @if ($k !== 'platform') style="display: none" @endif
                     x-transition:enter="transition duration-200 ease-out" x-transition:enter-start="opacity-0"
                     class="js-panel mt-14">
                    <p class="nojs-show mb-6 hidden t-label text-muted">{{ $k === 'platform' ? 'Platform subscription' : 'Custom project' }}</p>
                    <div class="relative">
                    <div class="absolute left-0 right-0 top-[1.375rem] hidden h-px bg-hairline lg:block" aria-hidden="true"></div>
                    <ol class="relative grid gap-10 sm:grid-cols-2 lg:grid-cols-4 lg:gap-8">
                        @foreach ($steps as $i => [$title, $text, $when])
                            <li class="relative">
                                <span class="relative grid h-11 w-11 place-items-center rounded-full bg-white font-display text-sm font-extrabold text-brand ring-1 ring-brand/15">{{ $i + 1 }}</span>
                                <p class="mt-6 inline-flex rounded-full bg-brand-light px-2.5 py-0.5 text-xs font-semibold text-accent ring-1 ring-hairline">{{ $when }}</p>
                                <h3 class="mt-3 font-display text-lg font-bold text-brand">{{ $title }}</h3>
                                <p class="mt-2 text-sm leading-relaxed text-secondary">{{ $text }}</p>
                            </li>
                        @endforeach
                    </ol>
                    </div>

                    @if ($k === 'project')
                        <ul class="mt-12 grid gap-px overflow-hidden rounded-2xl border border-hairline bg-hairline sm:grid-cols-2 lg:grid-cols-4">
                            @foreach (['Fixed-scope phases, priced before they start', 'Working software to try at the end of every sprint', 'Your data and IP terms agreed in writing', 'We act as your POPIA operator, on your instructions'] as $g)
                                <li class="bg-brand-light p-5 text-sm font-medium leading-snug text-brand">{{ $g }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach
        </div>
    </section>

    {{-- ══ 8 · PLATFORMS & PRICING (unchanged offer, new presentation) ═══ --}}
    <section id="platforms" class="band-mist section-v2">
        <div class="container-wide">
            <x-ui.heading align="split" eyebrow="Ready-to-use business platforms"
                :title="$brand['promise']"
                lede="Five platforms, one engine underneath — each set up for how its industry works. The build is free; a flat monthly fee starts the day you go live." />

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
                            <li class="grid items-center gap-x-4 gap-y-3 px-7 py-5 transition hover:bg-brand-light/60 md:grid-cols-12">
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

                <div class="mt-6 grid gap-3 text-sm text-secondary lg:grid-cols-12">
                    <p class="lg:col-span-8">
                        <span class="font-semibold text-brand">Add-on modules, charged separately:</span>
                        @foreach ($addOns as $addOn)
                            {{ $addOn->name }} {{ $addOn->is_metered ? '(on request)' : '(+'.$addOn->formatted_price.'/mo)' }}@if (! $loop->last) · @endif
                        @endforeach
                        — <a href="{{ route('packages.index') }}#add-ons" class="font-semibold text-accent hover:text-brand">see what they do</a>.
                    </p>
                    <p class="lg:col-span-4 lg:text-right">All prices exclude VAT.</p>
                    <p class="text-muted lg:col-span-12">{{ $brand['storage']['email_note'] }} {{ $brand['bulk']['note'] }}</p>
                </div>
            @endif
        </div>
    </section>

    {{-- ══ 9 · TRUST ═══════════════════════════════════════════════════
         Each line is something true today. Claims that depend on unfinished
         operational work (off-site backups, staff MFA) stay hidden until the
         work is done — see config('revamp.trust'). --}}
    <section id="trust" class="band-night on-dark section-v2">
        <div class="container-wide">
            <x-ui.heading tone="dark" align="split" eyebrow="Trust, security & support"
                title="The questions a careful buyer asks — answered up front." />

            @php
                $ledger = [
                    ['We are the operator. Your customers\' data stays yours.', 'Under POPIA we process your customers\' information only on your instructions, as your operator — clause 2 of our privacy notice says so plainly.', route('privacy')],
                    ['You own your data, email and domain.', str_replace(':company', $brand['trading_as'], $brand['ownership']['yours']).' '.$brand['ownership']['exit'], null],
                    ['No minimum term. '.$brand['terms']['notice_period'].'\'s notice.', 'No exit fee, no penalty, no argument. '.$brand['terms']['billing'].'.', route('terms')],
                    ['A person who built it answers.', 'Support is included in every platform — not a ticket queue in another timezone.', null],
                    ['Nothing loaded from anyone else\'s servers.', 'No third-party fonts, scripts or analytics on this site. Your visit is not shared with an ad network.', route('privacy')],
                ];
                if (config('revamp.trust.offsite_backups_verified')) {
                    $ledger[] = ['Backed up nightly, off-site, restore-tested.', 'Your data is copied off the server every night, and restores are tested, not assumed.', null];
                }
                if (config('revamp.trust.staff_mfa')) {
                    $ledger[] = ['Staff sign-in uses multi-factor authentication.', 'A stolen password alone does not open a back office.', null];
                }
            @endphp

            <dl class="mt-14 divide-y divide-white/10 border-y border-white/10">
                @foreach ($ledger as [$claim, $detail, $link])
                    <div class="grid gap-3 py-7 lg:grid-cols-12 lg:gap-10" data-reveal data-reveal-delay="{{ $loop->index * 50 }}">
                        <dt class="t-subtitle text-white lg:col-span-5">{{ $claim }}</dt>
                        <dd class="leading-relaxed text-white/60 lg:col-span-7">
                            {{ $detail }}
                            @if ($link)
                                <a href="{{ $link }}" class="ml-1 whitespace-nowrap font-semibold text-mint hover:brightness-110">Read it &rarr;</a>
                            @endif
                        </dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </section>

    {{-- ══ 10 · TWO JOURNEYS, ONE CLOSE ════════════════════════════════ --}}
    <section class="band-paper section-v2">
        <div class="container-wide grid gap-4 lg:grid-cols-2">
            <article class="tile flex flex-col bg-brand-light p-8 sm:p-12" data-reveal>
                <p class="t-label text-accent">Subscription</p>
                <h2 class="t-title mt-4 text-brand">Start with a platform.</h2>
                <p class="t-lede mt-4 text-secondary">
                    Pay nothing until your website is live. @if ($from) From {{ $from }} a month, excl. VAT. @endif
                    Nothing to build, and {{ strtolower($brand['terms']['notice_period']) }}'s notice to leave.
                </p>
                <div class="mt-auto flex flex-wrap items-center gap-5 pt-10">
                    <x-ui.button :href="route('packages.index')" size="lg" arrow data-cta="close-subscription">Explore Business Platforms</x-ui.button>
                    <a href="{{ route('contact', ['from' => 'home-close']) }}" class="text-sm font-semibold text-brand hover:text-accent">Ask about a package</a>
                </div>
            </article>

            <article class="tile-dark on-dark edge-glint flex flex-col p-8 text-white sm:p-12" data-reveal data-reveal-delay="80">
                <div class="contours absolute inset-0 -z-10 opacity-[0.08]" aria-hidden="true"></div>
                <p class="t-label text-mint">Custom &amp; enterprise</p>
                <h2 class="t-title mt-4">Discuss a custom solution.</h2>
                <p class="t-lede mt-4 text-white/70">
                    Tell us the problem or the process. We will come back to you to book a consultation —
                    and tell you honestly what it would take.
                </p>
                <div class="mt-auto flex flex-wrap items-center gap-5 pt-10">
                    <x-ui.button :href="route('contact', ['type' => 'project', 'from' => 'home-close'])" tone="dark" size="lg" arrow data-cta="close-project">Book a consultation</x-ui.button>
                    <x-whatsapp-link label="Or WhatsApp us" message="Hi 7Hills Digital, I'm on your home page and would like to discuss a custom solution." class="text-sm font-semibold text-mint hover:brightness-110" />
                </div>
            </article>
        </div>
    </section>

</x-ui.layout>
