{{--
    /industries — six trades, each with its platform (price from the live tiers)
    and its demo where one exists. Rows, not cards: the trade on the left, what
    we bring on the right.
--}}
@php
    $quote = route('contact', ['type' => 'project', 'from' => 'industries-hub']);
@endphp

<x-ui.layout overlay title="Software and Websites by Industry"
    description="Platforms and custom software for car dealerships, estate agencies, recruiters, guesthouses, professional firms and corporate operations teams in South Africa.">

    <section id="hero" class="band-night on-dark -mt-[77px] pt-[77px]">
        <div class="contours absolute inset-y-0 right-0 -z-10 w-full opacity-[0.13] lg:w-[75%]" aria-hidden="true"></div>
        <div class="container-wide pb-16 pt-10 sm:pt-14 lg:pb-24 lg:pt-16">
            <p class="inline-flex items-center gap-2.5 text-xs font-semibold uppercase tracking-[0.16em] text-mint">
                <span class="h-px w-6 bg-mint/70" aria-hidden="true"></span>Industries
            </p>
            <h1 class="t-display mt-5 max-w-4xl text-white">Built for how your industry already works.</h1>
            <p class="t-lede mt-6 max-w-2xl text-white/70">Four trades have a ready-made platform on one shared engine. The rest get the same engine, configured for them, or software built from scratch.</p>
        </div>
    </section>

    <section class="band-paper section-v2">
        <div class="container-wide">
            <ol class="border-t border-brand/15">
                @foreach ($industries as $slug => $industry)
                    @php
                        $package = $industry['package'] ? $packages->get($industry['package']) : null;
                        $from = $package?->tiers->sortBy('price_monthly')->first();
                    @endphp
                    <li class="grid gap-x-10 gap-y-5 border-b border-hairline py-10 lg:grid-cols-12" data-reveal>
                        <div class="lg:col-span-5">
                            <h2 class="t-subtitle text-brand"><a href="{{ route('industries.show', $slug) }}" class="hover:text-accent">{{ $industry['name'] }}</a></h2>
                            <ul class="mt-4 space-y-1.5 text-secondary">
                                @foreach ($industry['pains'] as $pain)
                                    <li class="flex gap-2.5"><span class="mt-2.5 h-1 w-1 shrink-0 rounded-full bg-accent" aria-hidden="true"></span>{{ $pain }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <dl class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm lg:col-span-5 lg:pt-1.5">
                            <div>
                                <dt class="text-xs font-semibold uppercase tracking-wider text-muted">Platform</dt>
                                <dd class="mt-1.5 font-semibold text-brand">
                                    @if ($package)
                                        <a href="{{ route('packages.show', $package) }}" class="hover:text-accent">{{ $package->name }}</a>
                                    @else
                                        Custom work
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs font-semibold uppercase tracking-wider text-muted">Monthly</dt>
                                <dd class="mt-1.5 font-semibold text-brand">{{ $from ? 'From '.$from->formatted_price : 'Quoted on scope' }}</dd>
                            </div>
                            <div class="col-span-2">
                                <dt class="text-xs font-semibold uppercase tracking-wider text-muted">Demo</dt>
                                <dd class="mt-1.5">
                                    @if ($industry['demo'])
                                        <a href="{{ route('demos.show', $industry['demo']) }}" class="font-semibold text-brand hover:text-accent">{{ config('demos.'.$industry['demo'].'.name') }}</a>
                                        <span class="text-muted">· invented business</span>
                                    @else
                                        <span class="text-muted">None for this industry</span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                        <div class="lg:col-span-2 lg:pt-1.5 lg:text-right">
                            <a href="{{ route('industries.show', $slug) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-accent hover:text-brand" aria-label="{{ $industry['name'] }}: read more">Read more <span aria-hidden="true">&rarr;</span></a>
                        </div>
                    </li>
                @endforeach
            </ol>

            <p class="mt-12 max-w-2xl text-lg leading-relaxed text-secondary">
                <span class="font-display font-bold text-brand">Your industry does not have to be one of the six.</span>
                Schools, funeral homes, plant hire and clinics run on the same engine. <a href="{{ $quote }}" class="font-semibold text-accent hover:text-brand">Ask about yours &rarr;</a>
            </p>
        </div>
    </section>

    <x-page.close :quote="$quote" heading="Tell us how your business runs." cta="Ask about your industry" context="Industries" cta-name="industries-close-quote" />
</x-ui.layout>
