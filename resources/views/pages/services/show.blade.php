{{--
    One template for every dedicated service page. Content comes from
    resources/content/services/{slug}.php; the order answers the brief's seven
    questions: the problem, what we deliver, use cases, technologies, delivery,
    proof (only if real), and how to get a quote.
--}}
@php
    use App\Support\Revamp;

    $quote = route('contact', ['type' => 'project', 'interest' => $page['quote_type'], 'from' => 'service-'.$page['slug']]);
    $brand = config('brand');

    $jsonLd = [
        [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $page['eyebrow'],
            'description' => $page['description'],
            'serviceType' => $page['eyebrow'],
            'areaServed' => ['@type' => 'Country', 'name' => 'South Africa'],
            'provider' => ['@type' => 'Organization', 'name' => $brand['trading_as'], 'url' => url('/')],
            'url' => url()->current(),
        ],
        [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => array_values(array_filter([
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Services', 'item' => route('services.index')],
                $line ? ['@type' => 'ListItem', 'position' => 2, 'name' => $line['name'], 'item' => route('services.show', $line['slug'])] : null,
                ['@type' => 'ListItem', 'position' => $line ? 3 : 2, 'name' => $page['eyebrow'], 'item' => url()->current()],
            ])),
        ],
        [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => array_map(fn ($qa) => ['@type' => 'Question', 'name' => $qa[0], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $qa[1]]], $page['faq']),
        ],
    ];

    $related = array_values(array_filter(array_map(fn ($s) => Revamp::page($s), $page['related'] ?? [])));
@endphp

<x-ui.layout overlay :title="$page['title']" :description="$page['description']">
    <x-slot:head>
        @if (! $page['preview'])
            <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
        @else
            <meta name="robots" content="noindex, nofollow">
        @endif
    </x-slot:head>

    <x-service.hero :page="$page" :line="$line" :quote="$quote" />

    {{-- ══ The problem, then what we deliver ══════════════════════════════ --}}
    <section id="deliver" class="band-paper section-v2">
        <div class="container-wide">
            <div class="grid gap-10 lg:grid-cols-12 lg:gap-16">
                <div class="lg:col-span-5">
                    <p class="t-label text-accent">The problem</p>
                    <h2 class="t-title mt-4 text-brand" data-reveal>{{ $page['problem']['heading'] }}</h2>
                </div>
                <div class="space-y-5 text-lg leading-relaxed text-secondary lg:col-span-7 lg:pt-10" data-reveal data-reveal-delay="80">
                    @foreach ($page['problem']['body'] as $paragraph)
                        <p>{{ $paragraph }}</p>
                    @endforeach
                    @if (! empty($page['problem']['source']))
                        <p class="border-t border-hairline pt-4 text-xs text-muted">Source: {{ $page['problem']['source'] }}</p>
                    @endif
                </div>
            </div>

            {{-- A specification, not a wall of cards: numbered rows on a hairline grid. --}}
            <div class="mt-20 border-t border-brand/15 pt-10">
                <div class="flex flex-wrap items-baseline justify-between gap-4">
                    <h2 class="t-subtitle text-brand">What we deliver</h2>
                    <p class="text-sm text-muted">{{ count($page['deliverables']) }} parts, scoped to what you need</p>
                </div>
                <ol class="mt-8 grid gap-x-12 sm:grid-cols-2">
                    @foreach ($page['deliverables'] as [$title, $detail])
                        <li class="grid grid-cols-[2.5rem_1fr] gap-x-3 border-b border-hairline py-6" data-reveal data-reveal-delay="{{ ($loop->index % 2) * 60 }}">
                            <span class="t-index pt-1 text-accent">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <span>
                                <span class="block font-display text-lg font-bold text-brand">{{ $title }}</span>
                                <span class="mt-1.5 block leading-relaxed text-secondary">{{ $detail }}</span>
                            </span>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </section>

    {{-- ══ Typical use cases — scenarios, never client stories ════════════ --}}
    <section id="use-cases" class="band-mist section-v2">
        <div class="container-wide">
            <x-ui.heading align="split" eyebrow="Typical use cases" title="{{ $page['use_cases_title'] ?? 'Where this earns its keep.' }}"
                lede="Typical situations, written as scenarios. They describe the kind of work, not a named client." />

            <div class="mt-14 grid gap-px overflow-hidden rounded-3xl bg-brand/10 ring-1 ring-brand/10 lg:grid-cols-3">
                @foreach ($page['use_cases'] as [$who, $situation, $build])
                    <article class="flex flex-col bg-white p-7 sm:p-9" data-reveal data-reveal-delay="{{ $loop->index * 70 }}">
                        <p class="t-label text-accent">{{ $who }}</p>
                        <p class="mt-5 font-display text-lg font-bold leading-snug text-brand">{{ $situation }}</p>
                        <div class="mt-auto pt-8">
                            <p class="text-xs font-semibold uppercase tracking-wider text-muted">What we build</p>
                            <p class="mt-2 leading-relaxed text-secondary">{{ $build }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ══ Technologies and delivery ═════════════════════════════════════ --}}
    <section id="approach" class="band-paper section-v2 scroll-mt-20">
        <div class="container-wide grid gap-16 lg:grid-cols-12">
            <div class="lg:col-span-5">
                <p class="t-label text-accent">Technologies</p>
                <h2 class="t-subtitle mt-4 text-brand">{{ $page['technologies_title'] ?? 'What we build it with.' }}</h2>
                <dl class="mt-8 space-y-6">
                    @foreach ($page['technologies'] as [$group, $items])
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wider text-muted">{{ $group }}</dt>
                            <dd class="mt-2.5 flex flex-wrap gap-2">
                                @foreach ($items as $item)
                                    <span class="rounded-lg bg-brand-light px-3 py-1.5 text-sm font-semibold text-brand ring-1 ring-hairline">{{ $item }}</span>
                                @endforeach
                            </dd>
                        </div>
                    @endforeach
                </dl>
                @if (! empty($page['technologies_note']))
                    <p class="mt-6 text-sm leading-relaxed text-muted">{{ $page['technologies_note'] }}</p>
                @endif
            </div>

            <div class="lg:col-span-7">
                <p class="t-label text-accent">How we deliver</p>
                <h2 class="t-subtitle mt-4 text-brand">From the first conversation to support.</h2>
                <ol class="relative mt-8">
                    <span class="absolute bottom-6 left-[1.375rem] top-6 w-px bg-brand/15" aria-hidden="true"></span>
                    @foreach ($page['approach'] as [$step, $detail])
                        <li class="relative grid grid-cols-[2.75rem_1fr] gap-x-5 pb-8 last:pb-0" data-reveal data-reveal-delay="{{ $loop->index * 60 }}">
                            <span @class(['relative grid h-11 w-11 place-items-center rounded-full font-display text-sm font-extrabold', 'bg-accent text-white' => $loop->last, 'bg-white text-brand ring-1 ring-brand/15' => ! $loop->last])>{{ $loop->iteration }}</span>
                            <span class="pt-2">
                                <span class="block font-display text-lg font-bold text-brand">{{ $step }}</span>
                                <span class="mt-1 block leading-relaxed text-secondary">{{ $detail }}</span>
                            </span>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </section>

    {{-- ══ Proof — only if real; otherwise the section is omitted, not padded ══ --}}
    @if (! empty($page['proof']))
        @php $proof = $page['proof']; @endphp
        <section id="proof" class="band-night on-dark section-v2">
            <div class="contours absolute inset-0 -z-10 opacity-[0.08]" aria-hidden="true"></div>
            <div class="container-wide grid items-center gap-12 lg:grid-cols-12">
                <div class="lg:col-span-5">
                    <p class="t-label text-mint">Proof, not promises</p>
                    <h2 class="t-title mt-4 text-white">{{ $proof['heading'] }}</h2>
                    <p class="t-lede mt-5 text-white/70">{{ $proof['body'] }}</p>
                    @if (! empty($proof['client']) && ($client = config('clients.'.$proof['client'])))
                        <div class="mt-8 border-t border-white/10 pt-6">
                            <p class="font-display text-lg font-bold text-white">{{ $client['name'] }}</p>
                            <p class="mt-1 text-sm text-white/60">{{ $client['software'] }}</p>
                            @if (! empty($client['url']))
                                <a href="{{ $client['url'] }}" target="_blank" rel="noopener" class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-mint hover:brightness-110">Visit the live site <span aria-hidden="true">&nearr;</span></a>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="lg:col-span-7">
                    @if (! empty($proof['image']))
                        <x-ui.frame-browser :image="$proof['image']" :url="$proof['url'] ?? 'yourbusiness.co.za/admin'" />
                    @elseif (! empty($proof['flow']))
                        <div class="tile-dark p-6 sm:p-9"><x-ui.flow tone="dark" :steps="$proof['flow']" /></div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    {{-- ══ Questions ═════════════════════════════════════════════════════ --}}
    <section id="faq" class="band-paper section-v2">
        <div class="container-wide grid gap-10 lg:grid-cols-12 lg:gap-16">
            <div class="lg:col-span-4">
                <p class="t-label text-accent">Questions</p>
                <h2 class="t-title mt-4 text-brand">What buyers ask us first.</h2>
                <p class="mt-5 text-secondary">Something else? <a href="{{ $quote }}" class="font-semibold text-accent hover:text-brand">Ask us directly &rarr;</a></p>
            </div>
            <x-service.faq :items="$page['faq']" class="lg:col-span-8" />
        </div>
    </section>

    {{-- ══ Get a quote, then where to go next ════════════════════════════ --}}
    <section class="band-night on-dark">
        <div class="contours absolute inset-0 -z-10 opacity-[0.12]" aria-hidden="true"></div>
        <div class="container-wide py-20 sm:py-24">
            <div class="grid gap-10 lg:grid-cols-12 lg:items-end">
                <div class="lg:col-span-8">
                    <h2 class="t-display text-white">{{ $page['close'] ?? 'Tell us what you need.' }}</h2>
                    <p class="t-lede mt-6 max-w-2xl text-white/70">We call you back to understand it properly, then send a scope and price in writing.</p>
                </div>
                <div class="flex flex-col gap-4 lg:col-span-4 lg:items-end">
                    <x-ui.button :href="$quote" tone="dark" size="lg" arrow data-cta="service-close-quote">{{ $page['cta'] ?? 'Get a quote' }}</x-ui.button>
                    <x-whatsapp-link label="Or WhatsApp us" :message="'Hi 7Hills Digital, I\'m on your '.$page['eyebrow'].' page and would like to talk about a project.'" class="text-sm font-semibold text-mint hover:brightness-110" />
                </div>
            </div>

            @if ($related)
                <nav aria-label="Related services" class="mt-16 border-t border-white/10 pt-8">
                    <p class="t-label text-white/60">Often paired with</p>
                    <ul class="mt-5 grid gap-px overflow-hidden rounded-2xl bg-white/10 {{ [1 => 'sm:grid-cols-1', 2 => 'sm:grid-cols-2', 3 => 'sm:grid-cols-3'][min(count($related), 3)] }}">
                        @foreach ($related as $r)
                            <li>
                                <a href="{{ route('services.show', $r['slug']) }}" class="svc-card group flex h-full items-center gap-4 bg-brand-dark p-5 hover:bg-white/[0.04]" style="--motif-accent: var(--brand-mint)">
                                    <x-ui.motif :name="$r['motif']" class="svc-motif h-8 w-8 text-white/60" />
                                    <span class="flex-1 font-semibold text-white">{{ $r['eyebrow'] }}</span>
                                    <span class="svc-arrow text-mint" aria-hidden="true">&rarr;</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            @endif
        </div>
    </section>
</x-ui.layout>
