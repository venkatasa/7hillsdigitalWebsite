{{--
    /services — every line and every service we will put our name to on this
    environment. Lines are rows, not cards: a name and a short promise on the
    left, the services on the right, each linking to its page or its line.
--}}
@php
    use App\Support\Revamp;

    $serviceCount = collect($lines)->sum(fn ($l) => count($l['services']));
    $words = new NumberFormatter('en', NumberFormatter::SPELLOUT);
@endphp

<x-ui.layout overlay title="IT Services and Software Development"
    description="Software development, automation, integration, business applications, hosting and IT services for South African businesses, from one team on one invoice.">

    <section id="hero" class="band-night on-dark -mt-[77px] pt-[77px]">
        <div class="contours absolute inset-y-0 right-0 -z-10 w-full opacity-[0.13] lg:w-[75%]" aria-hidden="true"></div>
        <div class="container-wide pb-14 pt-10 sm:pt-14 lg:pb-20 lg:pt-16">
            <p class="inline-flex items-center gap-2.5 text-xs font-semibold uppercase tracking-[0.16em] text-mint">
                <span class="h-px w-6 bg-mint/70" aria-hidden="true"></span>Services
            </p>
            <h1 class="t-display mt-5 max-w-4xl text-white">{{ ucfirst($words->format(count($lines))) }} service lines. One team that builds, hosts and supports all of it.</h1>
            <p class="t-lede mt-6 max-w-2xl text-white/70">{{ $serviceCount }} services, from a single integration to a whole system. Pick a line, or tell us the problem and we will tell you which fits.</p>
            <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                <x-ui.button :href="route('contact', ['type' => 'project', 'from' => 'services-hub'])" tone="dark" size="lg" arrow>Get a Quote</x-ui.button>
                <x-ui.button :href="route('packages.index')" tone="dark" variant="secondary" size="lg">Ready-to-use platforms</x-ui.button>
            </div>
        </div>

        <nav aria-label="Service lines" class="relative border-t border-white/10">
            <ol class="container-wide snap-row flex overflow-x-auto lg:grid lg:overflow-visible" style="grid-template-columns: repeat({{ count($lines) }}, minmax(0, 1fr))">
                @foreach ($lines as $slug => $l)
                    <li class="w-[44%] shrink-0 border-r border-white/10 first:border-l sm:w-[30%] lg:w-auto">
                        <a href="#{{ $slug }}" class="cap-link relative flex h-full flex-col gap-3 px-4 py-5 text-white/70 hover:text-white lg:px-5" style="--motif-accent: var(--brand-mint)">
                            <span class="flex items-center justify-between">
                                <span class="t-index text-white/40">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                <x-ui.motif :name="$l['motif']" class="h-5 w-5 text-white/45" />
                            </span>
                            <span class="text-sm font-semibold leading-snug">{{ $l['short'] }}</span>
                        </a>
                    </li>
                @endforeach
            </ol>
        </nav>
    </section>

    <section class="band-paper section-v2">
        <div class="container-wide">
            @foreach ($lines as $slug => $l)
                <section id="{{ $slug }}" aria-labelledby="h-{{ $slug }}" @class(['grid scroll-mt-24 gap-8 border-t border-hairline py-12 lg:grid-cols-12 lg:gap-12 lg:py-16', 'border-t-0 pt-0 lg:pt-0' => $loop->first]) style="--motif-accent: var(--brand-accent)">
                    <div class="lg:col-span-5" data-reveal>
                        <div class="flex items-center gap-4">
                            <span class="t-index text-accent">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <x-ui.motif :name="$l['motif']" class="h-9 w-9 text-brand" />
                        </div>
                        <h2 id="h-{{ $slug }}" class="t-title mt-5 text-brand">
                            <a href="{{ route('services.show', $slug) }}" class="hover:text-accent">{{ $l['name'] }}</a>
                        </h2>
                        @if ($l['preview'])
                            <p class="mt-3 inline-flex rounded-full border border-dashed border-brand/40 px-3 py-1 text-xs font-semibold text-secondary">Preview only, hidden in production</p>
                        @endif
                        <p class="mt-4 max-w-md leading-relaxed text-secondary">{{ $l['blurb'] }}</p>
                        <a href="{{ route('services.show', $slug) }}" class="mt-6 inline-flex items-center gap-1.5 text-sm font-semibold text-accent hover:text-brand">
                            All {{ strtolower($l['short']) }} services <span aria-hidden="true">&rarr;</span>
                        </a>
                    </div>

                    <ul class="grid content-start gap-x-10 sm:grid-cols-2 lg:col-span-7" data-reveal data-reveal-delay="80">
                        @foreach ($l['services'] as $s)
                            @php $page = Revamp::pageFor($s['slug']); @endphp
                            <li class="border-b border-hairline">
                                <a href="{{ Revamp::href($s['slug']) }}" class="ledger-row group flex items-start gap-3 py-4">
                                    <span @class(['mt-2 h-1.5 w-1.5 shrink-0 rounded-full', 'bg-accent' => ! $s['preview'], 'border border-brand/50' => $s['preview']]) aria-hidden="true"></span>
                                    <span class="flex-1">
                                        <span @class(['block font-semibold leading-snug group-hover:text-accent', 'text-brand' => ! $s['preview'], 'text-secondary' => $s['preview']])>{{ $s['name'] }}</span>
                                        @if ($s['preview'])<span class="sr-only">(preview only)</span>@endif
                                    </span>
                                    @if ($page)
                                        <span class="ledger-arrow mt-0.5 text-accent" aria-hidden="true">&rarr;</span>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endforeach

            <div class="mt-6 grid gap-6 rounded-3xl bg-brand-light p-8 sm:p-10 lg:grid-cols-12 lg:items-center">
                <div class="lg:col-span-8">
                    <h2 class="t-subtitle text-brand">Not on the list?</h2>
                    <p class="mt-2 max-w-2xl text-secondary">If your business has a process, we can put it on a screen. You focus on running the business. We will take care of the IT.</p>
                </div>
                <div class="lg:col-span-4 lg:text-right">
                    <x-ui.button :href="route('contact', ['type' => 'project', 'interest' => 'other', 'from' => 'services-hub'])" arrow>Describe it</x-ui.button>
                </div>
            </div>
        </div>
    </section>
</x-ui.layout>
