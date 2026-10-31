{{--
    One template for every solution page. A service page sells a capability; a
    solution page starts from the symptom the buyer recognises, shows what
    changes, and names the services that do the work. Content comes from
    resources/content/solutions/{slug}.php.
--}}
@php
    use App\Support\Revamp;

    $quote = route('contact', ['type' => 'project', 'interest' => $page['quote_type'], 'from' => 'solution-'.$page['slug']]);
    $services = Revamp::services($page['services']);
    $industries = array_filter(array_map(fn ($s) => Revamp::industry($s), $page['industries'] ?? []));
    $others = array_filter(Revamp::solutions(), fn ($s) => $s['slug'] !== $page['slug']);

    $jsonLd = [
        [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Solutions', 'item' => route('solutions.index')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => $page['eyebrow'], 'item' => url()->current()],
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
        @if (! $page['preview'])
            <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
        @else
            <meta name="robots" content="noindex, nofollow">
        @endif
    </x-slot:head>

    {{-- ══ Hero: the outcome, beside a ledger of what changes ═══════════════ --}}
    <section id="hero" class="band-night on-dark -mt-[77px] pt-[77px]">
        <div class="contours absolute inset-y-0 right-0 -z-10 w-full opacity-[0.13] lg:w-[75%]" aria-hidden="true"></div>
        <div class="absolute inset-y-0 left-0 -z-10 w-2/3 bg-gradient-to-r from-brand-dark via-brand-dark/85 to-transparent" aria-hidden="true"></div>

        <div class="container-wide grid items-center gap-y-14 pb-16 pt-8 sm:pt-12 lg:grid-cols-12 lg:gap-x-12 lg:pb-24 lg:pt-14">
            <div class="lg:col-span-6">
                <nav aria-label="Breadcrumb">
                    <ol class="flex flex-wrap items-center gap-x-2 text-xs text-white/60">
                        <li><a href="{{ route('solutions.index') }}" class="hover:text-white">Solutions</a></li>
                        <li aria-hidden="true">/</li>
                        <li aria-current="page" class="text-white/80">{{ $page['eyebrow'] }}</li>
                    </ol>
                </nav>

                @if ($page['preview'])
                    <p class="mt-6 inline-flex rounded-full border border-dashed border-white/40 px-3 py-1 text-xs font-semibold text-white/80">
                        Preview only. This page is not published until capability is confirmed.
                    </p>
                @endif

                <p class="mt-6 inline-flex items-center gap-2.5 text-xs font-semibold uppercase tracking-[0.16em] text-mint">
                    <span class="h-px w-6 bg-mint/70" aria-hidden="true"></span>{{ $page['problem'] }}
                </p>
                <h1 class="t-display mt-5 text-white">{{ $page['h1'] }}</h1>
                <p class="t-lede mt-6 max-w-xl text-white/70">{{ $page['lede'] }}</p>

                <div class="mt-9 flex flex-col gap-3 sm:flex-row sm:items-center">
                    <x-ui.button :href="$quote" tone="dark" size="lg" arrow data-cta="solution-hero-quote">{{ $page['cta'] }}</x-ui.button>
                    <x-ui.button href="#how" tone="dark" variant="secondary" size="lg">How we fix it</x-ui.button>
                </div>
            </div>

            <div class="lg:col-span-6">
                <figure class="tile-dark overflow-hidden">
                    <figcaption class="grid grid-cols-[1fr_1fr] border-b border-white/10 sm:grid-cols-[7rem_1fr_1fr]">
                        <span class="hidden sm:block" aria-hidden="true"></span>
                        <span class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-white/60 sm:px-5">Today</span>
                        <span class="border-l border-white/10 px-4 py-3 text-xs font-semibold uppercase tracking-wider text-mint sm:px-5">With it fixed</span>
                    </figcaption>
                    <dl>
                        @foreach ($page['shift'] as [$area, $today, $after])
                            <div class="grid grid-cols-2 border-b border-white/10 last:border-b-0 sm:grid-cols-[7rem_1fr_1fr]">
                                <dt class="col-span-2 px-4 pt-4 text-xs font-semibold text-white sm:col-span-1 sm:px-5 sm:py-4">{{ $area }}</dt>
                                <dd class="px-4 pb-4 pt-1.5 text-sm leading-snug text-white/55 sm:px-5 sm:py-4">{{ $today }}</dd>
                                <dd class="border-l border-white/10 px-4 pb-4 pt-1.5 text-sm font-medium leading-snug text-white sm:px-5 sm:py-4">{{ $after }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </figure>
            </div>
        </div>
    </section>

    {{-- ══ Sound familiar? ══════════════════════════════════════════════ --}}
    <section id="symptoms" class="band-paper section-v2">
        <div class="container-wide grid gap-12 lg:grid-cols-12 lg:gap-16">
            <div class="lg:col-span-4">
                <p class="t-label text-accent">Sound familiar?</p>
                <h2 class="t-title mt-4 text-brand">The signs we hear first.</h2>
                <p class="mt-5 leading-relaxed text-secondary">If two or three of these are true in your business, this is worth a conversation.</p>
            </div>
            <ul class="lg:col-span-8">
                @foreach ($page['symptoms'] as $symptom)
                    <li class="grid grid-cols-[2rem_1fr] items-baseline gap-x-4 border-b border-hairline py-5 first:border-t" data-reveal data-reveal-delay="{{ $loop->index * 50 }}">
                        <span class="grid h-6 w-6 place-items-center rounded-md ring-1 ring-brand/20" aria-hidden="true">
                            <svg class="h-3.5 w-3.5 text-accent" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2.25"><path d="m3.5 8.5 3 3 6-7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                        <span class="font-display text-lg font-semibold leading-snug text-brand sm:text-xl">{{ $symptom }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- ══ What it costs ════════════════════════════════════════════════ --}}
    <section id="cost" class="band-mist section-v2">
        <div class="container-wide grid gap-10 lg:grid-cols-12 lg:gap-16">
            <div class="lg:col-span-5">
                <p class="t-label text-accent">Why it matters</p>
                <h2 class="t-title mt-4 text-brand" data-reveal>{{ $page['cost']['heading'] }}</h2>
            </div>
            <div class="space-y-5 text-lg leading-relaxed text-secondary lg:col-span-7 lg:pt-10" data-reveal data-reveal-delay="80">
                @foreach ($page['cost']['body'] as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
                @if (! empty($page['cost']['source']))
                    <p class="border-t border-hairline pt-4 text-xs text-muted">Source: {{ $page['cost']['source'] }}</p>
                @endif
            </div>
        </div>
    </section>

    {{-- ══ How we fix it: the services, in the order they do the work ═══════ --}}
    <section id="how" class="band-paper section-v2 scroll-mt-20">
        <div class="container-wide">
            <x-ui.heading align="split" eyebrow="How we fix it" title="The services that do the work."
                lede="Each one has its own page. Most projects need the first; the others are added only if the problem calls for them." />

            <ol class="mt-14 border-t border-brand/15">
                @foreach ($services as $slug => $service)
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

            <div class="tile mt-14 p-6 sm:p-9">
                <p class="t-label text-muted">What the finished work does</p>
                <div class="mt-6"><x-ui.flow :steps="$page['flow']" /></div>
            </div>
        </div>
    </section>

    {{-- ══ What we need from you ════════════════════════════════════════ --}}
    <section id="inputs" class="band-mist section-v2">
        <div class="container-wide">
            <x-ui.heading eyebrow="To get started" title="What we need from you." />
            <dl class="mt-12 grid gap-px overflow-hidden rounded-3xl bg-brand/10 ring-1 ring-brand/10 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($page['inputs'] as [$what, $detail])
                    <div class="bg-white p-7" data-reveal data-reveal-delay="{{ $loop->index * 60 }}">
                        <dt class="font-display text-lg font-bold text-brand">{{ $what }}</dt>
                        <dd class="mt-3 leading-relaxed text-secondary">{{ $detail }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </section>

    @if (! empty($page['proof']))
        <x-page.proof :proof="$page['proof']" />
    @endif

    {{-- ══ Questions ═════════════════════════════════════════════════════ --}}
    <section id="faq" class="band-paper section-v2">
        <div class="container-wide grid gap-10 lg:grid-cols-12 lg:gap-16">
            <div class="lg:col-span-4">
                <p class="t-label text-accent">Questions</p>
                <h2 class="t-title mt-4 text-brand">Before you ask.</h2>
                <p class="mt-5 text-secondary">Something else? <a href="{{ $quote }}" class="font-semibold text-accent hover:text-brand">Ask us directly &rarr;</a></p>
            </div>
            <x-service.faq :items="$page['faq']" class="lg:col-span-8" />
        </div>
    </section>

    <x-page.close :quote="$quote" :heading="$page['close']" :cta="$page['cta']" :context="$page['eyebrow']" cta-name="solution-close-quote">
        @if ($industries)
            <x-slot:links>
                <nav aria-label="Industries" class="mt-16 border-t border-white/10 pt-8">
                    <p class="t-label text-white/60">Common in</p>
                    <ul class="mt-5 flex flex-wrap gap-2">
                        @foreach ($industries as $industry)
                            <li><a href="{{ route('industries.show', $industry['slug']) }}" class="inline-flex rounded-full px-4 py-2 text-sm font-semibold text-white ring-1 ring-white/20 hover:bg-white/5 hover:ring-mint">{{ $industry['name'] }}</a></li>
                        @endforeach
                    </ul>
                </nav>
                <nav aria-label="Other solutions" class="mt-10">
                    <p class="t-label text-white/60">Other problems we solve</p>
                    <ul class="mt-5 grid gap-x-8 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($others as $other)
                            <li><a href="{{ route('solutions.show', $other['slug']) }}" class="flex items-baseline justify-between gap-4 border-b border-white/10 py-3 text-white/80 hover:text-white"><span>{{ $other['problem'] }}</span><span class="text-mint" aria-hidden="true">&rarr;</span></a></li>
                        @endforeach
                    </ul>
                </nav>
            </x-slot:links>
        @endif
    </x-page.close>
</x-ui.layout>
