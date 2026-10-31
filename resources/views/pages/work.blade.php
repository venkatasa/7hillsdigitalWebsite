{{--
    /work — what we can show, sorted by how real it is: live client installs
    (config/clients.php, public sites only, LiveWorkTest rules), our own software
    on a staging install with invented data, and the demo tenants, each labelled
    as invented. Nothing here is a client back office.
--}}
@php
    use App\Support\ProductImages;

    $clients = config('clients', []);
    $demos = config('demos', []);
    $quote = \App\Support\Quote::url('work');
    $ours = [
        ['admin-dashboard', 'The dashboard', 'What needs attention today, on one screen.'],
        ['admin-enquiries', 'The enquiry pipeline', 'Every lead with an owner and a stage. What the prospect wrote is read-only.'],
        ['admin-packages', 'Prices, edited once', 'Change a price here and every page on the site follows.'],
    ];
@endphp

<x-ui.layout overlay title="Our Work: Live Clients and Labelled Demos"
    description="Live client installs, our own software shown with invented data, and five demo sites that say plainly they are demos. What we can show, and why we will not show more.">

    <section id="hero" class="band-night on-dark -mt-[77px] pt-[77px]">
        <div class="contours absolute inset-y-0 right-0 -z-10 w-full opacity-[0.13] lg:w-[75%]" aria-hidden="true"></div>
        <div class="container-wide pb-16 pt-10 sm:pt-14 lg:pb-20 lg:pt-16">
            <p class="inline-flex items-center gap-2.5 text-xs font-semibold uppercase tracking-[0.16em] text-mint">
                <span class="h-px w-6 bg-mint/70" aria-hidden="true"></span>Our work
            </p>
            <h1 class="t-display mt-5 max-w-4xl text-white">Real clients. And demos that say they are demos.</h1>
            <p class="t-lede mt-6 max-w-2xl text-white/70">Everything on this page is labelled for what it is: a live client, our own software shown with invented data, or a demonstration business that does not exist.</p>
        </div>

        <nav aria-label="On this page" class="relative border-t border-white/10">
            <ol class="container-wide grid grid-cols-3 text-sm">
                @foreach ([['#live', 'Live clients', count($clients)], ['#ours', 'Our software', count($ours)], ['#demos', 'Demonstrations', count($demos)]] as [$href, $label, $count])
                    <li class="border-r border-white/10 first:border-l">
                        <a href="{{ $href }}" class="flex h-full flex-col gap-1 px-3 py-5 text-white/70 hover:text-white sm:flex-row sm:items-baseline sm:justify-between sm:px-5">
                            <span class="font-semibold">{{ $label }}</span>
                            <span class="t-index text-white/60">{{ str_pad($count, 2, '0', STR_PAD_LEFT) }}</span>
                        </a>
                    </li>
                @endforeach
            </ol>
        </nav>
    </section>

    {{-- ══ Live clients ════════════════════════════════════════════════ --}}
    @if (filled($clients))
        <section id="live" class="band-paper section-v2 scroll-mt-20">
            <div class="container-wide">
                <x-ui.heading align="split" eyebrow="Live clients" title="Businesses running on it today."
                    lede="Named with their permission, linked to their public site only, and described only as far as what is actually running." />

                <div class="mt-14 border-t border-brand/15">
                    @foreach ($clients as $client)
                        <article class="grid gap-x-12 gap-y-6 border-b border-hairline py-10 lg:grid-cols-12" data-reveal>
                            <div class="lg:col-span-5">
                                <p class="t-label text-accent">{{ $client['vertical'] }}</p>
                                <h3 class="mt-3 font-display text-2xl font-bold text-brand">{{ $client['name'] }}</h3>
                                <p class="mt-4 leading-relaxed text-secondary">{{ $client['summary'] }}</p>
                                <p class="mt-5 text-sm text-muted">{{ $client['software'] }}</p>
                            </div>
                            <div class="lg:col-span-5">
                                <p class="text-xs font-semibold uppercase tracking-wider text-muted">Running today</p>
                                <ul class="mt-3 space-y-2.5 text-secondary">
                                    @foreach ($client['delivered'] as $item)
                                        <li class="flex gap-2.5">
                                            <svg class="mt-1 h-4 w-4 shrink-0 text-accent" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path d="M3 8.5l3 3 7-7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            {{ $item }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="lg:col-span-2 lg:text-right">
                                @if (! empty($client['url']))
                                    <a href="{{ $client['url'] }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-sm font-semibold text-accent hover:text-brand">Visit the live site <span aria-hidden="true">&nearr;</span></a>
                                @else
                                    <p class="text-sm text-muted">Behind the client&rsquo;s login. Nothing public to link.</p>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ══ Our own software, invented data ═════════════════════════════ --}}
    <section id="ours" class="band-mist section-v2 scroll-mt-20">
        <div class="container-wide">
            <x-ui.heading align="split" eyebrow="Our own software" title="The back office, with the names made up."
                lede="These are screenshots of software we wrote, running on our staging install with invented enquiries and data. A client's own back office is never shown." />

            <div class="mt-14 grid gap-10 lg:grid-cols-12">
                <div class="lg:col-span-8" data-reveal>
                    <x-ui.frame-browser :image="$ours[0][0]" tone="light" url="7hillsdigital.co.za/admin" />
                </div>
                <div class="flex flex-col gap-10 lg:col-span-4">
                    @foreach ($ours as [$image, $title, $detail])
                        <div class="border-t border-brand/15 pt-5" data-reveal data-reveal-delay="{{ $loop->index * 60 }}">
                            <p class="t-index text-accent">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</p>
                            <h3 class="mt-2 font-display text-lg font-bold text-brand">{{ $title }}</h3>
                            <p class="mt-1.5 leading-relaxed text-secondary">{{ $detail }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="mt-10 grid gap-6 sm:grid-cols-2">
                @foreach (array_slice($ours, 1) as [$image])
                    <div data-reveal><x-ui.frame-browser :image="$image" tone="light" url="7hillsdigital.co.za/admin" /></div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ══ Demonstrations — invented, and labelled as such ═══════════════ --}}
    <section id="demos" class="band-paper section-v2 scroll-mt-20">
        <div class="container-wide">
            <x-ui.heading align="split" eyebrow="Demonstrations" title="Five businesses that do not exist."
                lede="Each demo shows one platform carrying a different brand. The businesses, stock, prices and reviews are all invented, and every demo page says so." />

            <ul class="mt-14 grid gap-x-8 gap-y-14 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($demos as $slug => $demo)
                    @php
                        $thumb = ProductImages::get('demo-'.$slug.'-desktop');
                        $package = $packages->get($demo['package']);
                    @endphp
                    <li data-reveal data-reveal-delay="{{ ($loop->index % 3) * 60 }}">
                        <a href="{{ route('demos.show', $slug) }}" class="group block">
                            <span class="relative block overflow-hidden rounded-2xl border border-hairline bg-white transition group-hover:border-accent/40 group-hover:shadow-[0_18px_40px_-24px_var(--brand-dark)]">
                                @if ($thumb)
                                    <img src="{{ asset($thumb['file']) }}" alt="{{ $thumb['alt'] }}" width="{{ $thumb['width'] }}" height="{{ $thumb['height'] }}"
                                         loading="lazy" decoding="async" class="aspect-[16/10] w-full object-cover object-top transition duration-500 group-hover:scale-[1.02]">
                                @endif
                                <span class="absolute left-3 top-3 rounded-md bg-brand-dark/85 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-mint backdrop-blur">Demo · invented</span>
                            </span>
                            <span class="mt-4 flex items-baseline justify-between gap-4">
                                <span class="font-display text-lg font-bold text-brand group-hover:text-accent">{{ $demo['name'] }}</span>
                                <span class="text-accent" aria-hidden="true">&rarr;</span>
                            </span>
                        </a>
                        <p class="mt-1 text-sm text-muted">{{ $demo['vertical'] }}@if ($package) · runs on <a href="{{ route('packages.show', $package) }}" class="font-semibold text-secondary hover:text-accent">{{ $package->name }}</a>@endif</p>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- ══ What we will not show ═══════════════════════════════════════ --}}
    <section class="band-mist section-v2">
        <div class="container-wide grid gap-10 lg:grid-cols-12 lg:gap-16">
            <div class="lg:col-span-5">
                <p class="t-label text-accent">What we will not show</p>
                <h2 class="t-title mt-4 text-brand">A client&rsquo;s back office.</h2>
            </div>
            <div class="space-y-5 text-lg leading-relaxed text-secondary lg:col-span-7 lg:pt-10">
                <p>A client&rsquo;s back office holds their customers&rsquo; personal information: enquiries, applications, identity documents. Walking a stranger through it would breach the trust our clients place in us, and POPIA.</p>
                <p>So we show the same software on a staging install filled with invented data. If you would like a live walkthrough, that is what you will see, and you can ask it anything.</p>
                <p><a href="{{ $quote }}" class="font-semibold text-accent hover:text-brand">Book a walkthrough &rarr;</a></p>
            </div>
        </div>
    </section>

    <x-page.close :quote="$quote" heading="Want to be the next live client?" cta="Start a conversation" context="Our work" cta-name="work-close-quote" />
</x-ui.layout>
