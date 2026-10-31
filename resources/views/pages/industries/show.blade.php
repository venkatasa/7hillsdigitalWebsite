{{--
    One template for every industry page. It names the trade's day, the platform
    built for it (prices from the package's live tiers), the demo where one
    exists (labelled as invented), a live client only where config/clients.php
    names one, and the custom services that go beyond the platform.
--}}
@php
    use App\Support\Revamp;

    $brand = config('brand');
    $quote = route('contact', array_filter(['type' => $package ? null : 'project', 'package' => $package?->slug, 'interest' => $package ? null : $page['quote_type'], 'from' => 'industry-'.$page['slug']]));
    $services = Revamp::services($page['services']);
    $solutions = array_filter(array_map(fn ($s) => Revamp::solutions()[$s] ?? null, $page['solutions'] ?? []));
    $client = ! empty($page['client']) ? config('clients.'.$page['client']) : null;
    $demo = $page['demo'] ?? null;
    $engineDemo = ! $demo && $package ? $package->demoSlug() : null;
    $from = $package?->tiers->sortBy('price_monthly')->first();
    $others = array_filter(Revamp::industries(), fn ($k) => $k !== $page['slug'], ARRAY_FILTER_USE_KEY);

    $jsonLd = [
        [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Industries', 'item' => route('industries.index')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => $page['name'], 'item' => url()->current()],
            ],
        ],
        [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => array_map(fn ($qa) => ['@type' => 'Question', 'name' => $qa[0], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $qa[1]]], $page['faq']),
        ],
    ];
@endphp

<x-ui.layout overlay :title="$page['title']" :description="$page['description']">
    <x-slot:head>
        <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
    </x-slot:head>

    {{-- ══ Hero ═══════════════════════════════════════════════════════ --}}
    <section id="hero" class="band-night on-dark -mt-[77px] pt-[77px]">
        <div class="contours absolute inset-y-0 right-0 -z-10 w-full opacity-[0.13] lg:w-[75%]" aria-hidden="true"></div>
        <div class="absolute inset-y-0 left-0 -z-10 w-2/3 bg-gradient-to-r from-brand-dark via-brand-dark/85 to-transparent" aria-hidden="true"></div>

        <div class="container-wide grid items-center gap-y-14 pb-16 pt-8 sm:pt-12 lg:grid-cols-12 lg:gap-x-12 lg:pb-24 lg:pt-14">
            <div class="lg:col-span-6">
                <nav aria-label="Breadcrumb">
                    <ol class="flex flex-wrap items-center gap-x-2 text-xs text-white/60">
                        <li><a href="{{ route('industries.index') }}" class="hover:text-white">Industries</a></li>
                        <li aria-hidden="true">/</li>
                        <li aria-current="page" class="text-white/80">{{ $page['name'] }}</li>
                    </ol>
                </nav>
                <p class="mt-6 inline-flex items-center gap-2.5 text-xs font-semibold uppercase tracking-[0.16em] text-mint">
                    <span class="h-px w-6 bg-mint/70" aria-hidden="true"></span>{{ $page['name'] }}
                </p>
                <h1 class="t-display mt-5 text-white">{{ $page['h1'] }}</h1>
                <p class="t-lede mt-6 max-w-xl text-white/70">{{ $page['lede'] }}</p>

                <div class="mt-9 flex flex-col gap-3 sm:flex-row sm:items-center">
                    <x-ui.button :href="$quote" tone="dark" size="lg" arrow data-cta="industry-hero-quote">{{ $page['cta'] }}</x-ui.button>
                    @if ($package)
                        <x-ui.button href="#platform" tone="dark" variant="secondary" size="lg">The {{ $package->name }} platform</x-ui.button>
                    @else
                        <x-ui.button href="#day" tone="dark" variant="secondary" size="lg">Where it helps</x-ui.button>
                    @endif
                </div>
            </div>

            <div class="relative lg:col-span-6">
                @if ($demo)
                    <div class="relative pb-10 sm:pr-16">
                        <x-ui.frame-browser :image="'demo-'.$demo.'-desktop'" :url="'7hillsdigital.co.za/demos/'.$demo" eager />
                        <div class="absolute -bottom-2 right-0 hidden w-[26%] sm:block">
                            <x-ui.frame-phone :image="'demo-'.$demo.'-mobile'" />
                        </div>
                    </div>
                @else
                    <div class="tile-dark p-6 sm:p-9">
                        <x-ui.flow tone="dark" :steps="$page['visual'] ?? array_map(fn ($d) => [$d[0], ''], $page['day'])" />
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- ══ The trade today ════════════════════════════════════════════ --}}
    <section id="context" class="band-paper section-v2">
        <div class="container-wide grid gap-10 lg:grid-cols-12 lg:gap-16">
            <div class="lg:col-span-5">
                <p class="t-label text-accent">The situation</p>
                <h2 class="t-title mt-4 text-brand" data-reveal>{{ $page['context']['heading'] }}</h2>
            </div>
            <div class="space-y-5 text-lg leading-relaxed text-secondary lg:col-span-7 lg:pt-10" data-reveal data-reveal-delay="80">
                @foreach ($page['context']['body'] as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ══ A day in the trade: a timeline, today against with the system ══ --}}
    <section id="day" class="band-mist section-v2 scroll-mt-20">
        <div class="container-wide">
            <x-ui.heading align="split" eyebrow="Where the time goes" title="The same day, run two ways."
                lede="Four moments every {{ \Illuminate\Support\Str::lower($page['short']) }} business knows, and what changes when the system carries them." />

            <ol class="relative mt-14 grid gap-10 lg:grid-cols-4 lg:gap-8">
                <span class="absolute left-0 right-0 top-[1.375rem] hidden h-px bg-brand/15 lg:block" aria-hidden="true"></span>
                @foreach ($page['day'] as [$moment, $today, $after])
                    <li class="relative grid grid-cols-[2.75rem_1fr] gap-x-5 lg:block" data-reveal data-reveal-delay="{{ $loop->index * 70 }}">
                        <span class="relative grid h-11 w-11 place-items-center rounded-full bg-white font-display text-sm font-extrabold text-brand ring-1 ring-brand/15">{{ $loop->iteration }}</span>
                        <div class="lg:mt-6">
                            <h3 class="font-display text-lg font-bold text-brand">{{ $moment }}</h3>
                            <p class="mt-3 text-sm leading-relaxed text-muted"><span class="font-semibold text-secondary">Today:</span> {{ $today }}</p>
                            <p class="mt-3 border-l-2 border-accent pl-3 text-sm font-medium leading-relaxed text-brand">{{ $after }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- ══ The platform, priced from the database ════════════════════ --}}
    <section id="platform" class="band-night on-dark section-v2 scroll-mt-20">
        <div class="contours absolute inset-0 -z-10 opacity-[0.08]" aria-hidden="true"></div>
        <div class="container-wide grid gap-14 lg:grid-cols-12 lg:gap-16">
            @if ($package)
                <div class="lg:col-span-7">
                    <p class="t-label text-mint">The platform</p>
                    <h2 class="t-title mt-4 text-white">{{ $package->name }}: {{ \Illuminate\Support\Str::lcfirst(rtrim($package->tagline, '.')) }}.</h2>
                    <ul class="mt-8 grid gap-x-8 gap-y-3 sm:grid-cols-2">
                        @foreach (array_slice($package->what_you_get ?? [], 0, 6) as $item)
                            <li class="flex gap-3 text-sm leading-relaxed text-white/80">
                                <svg class="mt-0.5 h-4 w-4 shrink-0 text-mint" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 0 1 0 1.4l-7.5 7.5a1 1 0 0 1-1.4 0L3.3 9.7a1 1 0 1 1 1.4-1.4l3.8 3.8 6.8-6.8a1 1 0 0 1 1.4 0Z" clip-rule="evenodd"/></svg>
                                <span>{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                    @if ($engineDemo && ! empty($page['demo_note']))
                        <p class="mt-8 max-w-xl text-sm leading-relaxed text-white/60">{{ $page['demo_note'] }}</p>
                    @endif
                </div>

                <aside class="tile-dark self-start p-7 sm:p-9 lg:col-span-5" aria-label="{{ $package->name }} pricing">
                    @if ($from)
                        <p class="text-sm text-white/60">From</p>
                        <p class="mt-1 flex items-baseline gap-2">
                            <span class="t-figure text-5xl text-white">{{ $from->formatted_price }}</span>
                            <span class="text-sm text-white/60">/ month, excl. VAT</span>
                        </p>
                    @endif
                    <dl class="mt-7 divide-y divide-white/10 border-y border-white/10 text-sm">
                        @foreach ([['Setup fee', $brand['terms']['setup_fee']], ['Minimum term', $brand['terms']['minimum_term']], ['Notice to cancel', $brand['terms']['notice_period']]] as [$label, $value])
                            <div class="flex justify-between gap-4 py-3"><dt class="text-white/60">{{ $label }}</dt><dd class="text-right font-semibold text-white">{{ $value }}</dd></div>
                        @endforeach
                    </dl>
                    <div class="mt-7 grid gap-3">
                        <x-ui.button :href="route('packages.show', $package)" tone="dark" arrow>See {{ $package->name }} in full</x-ui.button>
                        @if ($demo || $engineDemo)
                            <x-ui.button :href="route('demos.show', $demo ?? $engineDemo)" tone="dark" variant="secondary">{{ $demo ? 'View the demo' : 'See the engine at work' }}</x-ui.button>
                            <p class="text-center text-xs text-white/60">Demonstration: an invented business, built on the platform.</p>
                        @endif
                    </div>
                </aside>
            @else
                <div class="lg:col-span-7">
                    <p class="t-label text-mint">How we work with you</p>
                    <h2 class="t-title mt-4 text-white">No platform to fit into. Custom work, scoped and priced in writing.</h2>
                    <p class="t-lede mt-5 text-white/70">There is no off-the-shelf package for this, deliberately. Each project starts small, on one process, with the deliverables and price agreed before any work begins.</p>
                </div>
                <div class="tile-dark self-start p-7 sm:p-9 lg:col-span-5">
                    <p class="text-sm text-white/60">Priced on</p>
                    <p class="mt-1 font-display text-2xl font-bold text-white">Scope, in writing</p>
                    <p class="mt-4 text-sm leading-relaxed text-white/70">{{ str_replace(':company', $brand['name'], $brand['ownership']['buyout']) }}</p>
                    <div class="mt-7"><x-ui.button :href="$quote" tone="dark" arrow>Describe the process</x-ui.button></div>
                </div>
            @endif
        </div>
    </section>

    {{-- ══ Live client — only where config/clients.php names one ════════ --}}
    @if ($client)
        <section id="live" class="band-paper section-v2">
            <div class="container-wide grid gap-10 lg:grid-cols-12 lg:gap-16">
                <div class="lg:col-span-5">
                    <p class="t-label text-accent">Live client</p>
                    <h2 class="t-title mt-4 text-brand">{{ $client['name'] }}</h2>
                    <p class="mt-2 text-sm font-semibold uppercase tracking-wider text-muted">{{ $client['vertical'] }}</p>
                    <p class="t-lede mt-5 text-secondary">{{ $client['summary'] }}</p>
                    <p class="mt-5 text-sm text-muted">{{ $client['software'] }}</p>
                    @if (! empty($client['url']))
                        <a href="{{ $client['url'] }}" target="_blank" rel="noopener" class="mt-6 inline-flex items-center gap-1.5 font-semibold text-accent hover:text-brand">Visit the live site <span aria-hidden="true">&nearr;</span></a>
                    @endif
                </div>
                <div class="lg:col-span-7 lg:pt-10">
                    <p class="text-xs font-semibold uppercase tracking-wider text-muted">Running today</p>
                    <ul class="mt-4 divide-y divide-hairline border-y border-hairline">
                        @foreach ($client['delivered'] as $item)
                            <li class="py-4 font-display text-lg font-semibold text-brand">{{ $item }}</li>
                        @endforeach
                    </ul>
                    <p class="mt-4 text-xs leading-relaxed text-muted">We link the public site only. Client back offices hold real personal information, so we never show them.</p>
                </div>
            </div>
        </section>
    @endif

    {{-- ══ Beyond the platform ═══════════════════════════════════════════ --}}
    <section id="beyond" class="{{ $client ? 'band-mist' : 'band-paper' }} section-v2">
        <div class="container-wide">
            <x-ui.heading align="split" eyebrow="{{ $package ? 'Beyond the platform' : 'What we build' }}"
                title="{{ $package ? 'When the business needs more than the website.' : 'Where we usually start.' }}"
                lede="Custom work, quoted separately and only when you ask for it." />

            <ol class="mt-14 border-t border-brand/15">
                @foreach ($services as $service)
                    <li data-reveal data-reveal-delay="{{ $loop->index * 60 }}">
                        <a href="{{ $service['href'] }}" class="ledger-row group grid gap-x-8 gap-y-2 border-b border-hairline py-7 sm:grid-cols-[3rem_minmax(0,18rem)_1fr_auto] sm:items-baseline">
                            <span class="t-index text-accent">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="font-display text-xl font-bold text-brand group-hover:text-accent">{{ $service['name'] }}</span>
                            <span class="leading-relaxed text-secondary">{{ $service['why'] }}</span>
                            <span class="ledger-arrow hidden text-accent sm:block" aria-hidden="true">&rarr;</span>
                        </a>
                    </li>
                @endforeach
            </ol>

            @if ($solutions)
                <div class="mt-12 flex flex-wrap items-center gap-3">
                    <p class="mr-2 text-sm font-semibold text-muted">Problems we solve here:</p>
                    @foreach ($solutions as $s)
                        <a href="{{ route('solutions.show', $s['slug']) }}" class="inline-flex rounded-full bg-white px-4 py-2 text-sm font-semibold text-brand ring-1 ring-brand/15 hover:ring-accent">{{ $s['problem'] }}</a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- ══ Questions ═════════════════════════════════════════════════════ --}}
    <section id="faq" class="band-paper section-v2">
        <div class="container-wide grid gap-10 lg:grid-cols-12 lg:gap-16">
            <div class="lg:col-span-4">
                <p class="t-label text-accent">Questions</p>
                <h2 class="t-title mt-4 text-brand">What {{ \Illuminate\Support\Str::lower($page['short']) }} businesses ask us.</h2>
                <p class="mt-5 text-secondary">Something else? <a href="{{ $quote }}" class="font-semibold text-accent hover:text-brand">Ask us directly &rarr;</a></p>
            </div>
            <x-service.faq :items="$page['faq']" class="lg:col-span-8" />
        </div>
    </section>

    <x-page.close :quote="$quote" :heading="$page['close']" :cta="$page['cta']" :context="$page['name']" cta-name="industry-close-quote">
        <x-slot:links>
            <nav aria-label="Other industries" class="mt-16 border-t border-white/10 pt-8">
                <p class="t-label text-white/60">Other industries</p>
                <ul class="mt-5 flex flex-wrap gap-2">
                    @foreach ($others as $slug => $other)
                        <li><a href="{{ route('industries.show', $slug) }}" class="inline-flex rounded-full px-4 py-2 text-sm font-semibold text-white ring-1 ring-white/20 hover:bg-white/5 hover:ring-mint">{{ $other['name'] }}</a></li>
                    @endforeach
                </ul>
            </nav>
        </x-slot:links>
    </x-page.close>
</x-ui.layout>
