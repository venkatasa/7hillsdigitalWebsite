@props(['addOns', 'base' => null])

{{-- Optional modules on the platforms page (revamp). Same commercial rules as
     <x-add-ons-section>: on top of the package fee, charged separately, priced
     from the database, flat or metered but never both. AddOnTest guards it. --}}
@php
    $invoicing = $addOns->firstWhere('slug', 'invoicing');
@endphp

@if ($addOns->isNotEmpty())
    <section id="add-ons" class="band-paper section-v2 scroll-mt-20">
        <div class="container-wide">
            <div class="grid gap-8 lg:grid-cols-12 lg:gap-16">
                <div class="lg:col-span-5">
                    <p class="t-label text-accent">Optional modules</p>
                    <h2 class="t-title mt-4 text-brand">Business software that bolts onto your site.</h2>
                </div>
                <p class="text-lg leading-relaxed text-secondary lg:col-span-7 lg:pt-10">
                    These sit <strong class="font-semibold text-brand">on top of your monthly package fee</strong>
                    and are <strong class="font-semibold text-brand">charged separately</strong>. None is bundled
                    in, and none appears on your invoice unless you have asked for it.
                    @if ($base && $invoicing && ! $invoicing->is_metered)
                        Invoicing on the 5 tier, for instance, is {{ $base }} + {{ $invoicing->formatted_price }}.
                    @endif
                    Add one whenever you are ready, and drop it the same way.
                </p>
            </div>

            <div class="mt-14 grid gap-px overflow-hidden rounded-3xl bg-brand/10 ring-1 ring-brand/10 md:grid-cols-2 xl:grid-cols-4">
                @foreach ($addOns as $addOn)
                    <article class="flex flex-col bg-white p-7" data-reveal data-reveal-delay="{{ $loop->index * 60 }}">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand-light text-accent">
                                <x-package-icon :name="$addOn->icon_key" class="h-5 w-5" />
                            </span>
                            <h3 class="font-display text-lg font-bold text-brand">{{ $addOn->name }}</h3>
                        </div>
                        <p class="mt-4 text-sm font-medium leading-relaxed text-brand">{{ $addOn->tagline }}</p>

                        <div class="mt-6 border-t border-hairline pt-5">
                            @if ($addOn->is_metered)
                                <p class="font-display text-xl font-bold text-brand">Quoted on request</p>
                                <p class="mt-1 text-xs leading-relaxed text-muted">{{ $addOn->price_basis }}</p>
                            @else
                                <p class="flex items-baseline gap-1.5">
                                    @if ($addOn->has_price_tiers)
                                        <span class="text-sm font-semibold text-muted">from</span>
                                    @endif
                                    <span class="t-figure text-3xl text-brand">+{{ $addOn->formatted_price }}</span>
                                    <span class="text-sm text-muted">/ month</span>
                                </p>
                                <p class="mt-1 text-xs text-muted">{{ $addOn->price_unit ?? 'Added to your package fee' }}, excl. VAT</p>

                                @if ($addOn->has_price_tiers)
                                    <ul class="mt-4 divide-y divide-hairline rounded-xl bg-brand-light px-4">
                                        @foreach ($addOn->price_tiers as $tier)
                                            <li class="flex items-baseline justify-between gap-3 py-2 text-sm">
                                                <span class="text-secondary">{{ $tier['label'] }}</span>
                                                <span class="font-semibold text-brand">R{{ number_format((float) $tier['price'], 0, '.', ' ') }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            @endif
                        </div>

                        <ul class="mt-5 flex-1 space-y-2 text-sm text-secondary">
                            @foreach (array_slice($addOn->what_it_does ?? [], 0, 4) as $item)
                                <li class="flex gap-2.5">
                                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-accent" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path d="M3 8.5l3 3 7-7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    <span>{{ $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </article>
                @endforeach
            </div>

            <div class="mt-8 grid gap-4 lg:grid-cols-2">
                <div class="flex flex-col gap-4 rounded-2xl bg-accent/[0.07] p-6 ring-1 ring-accent/25 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm leading-relaxed text-secondary"><strong class="font-semibold text-brand">Buying in bulk?</strong> {{ config('brand.bulk.note') }}</p>
                    <x-ui.button :href="route('contact', ['from' => 'packages-bulk'])" variant="secondary" class="shrink-0">Ask about volume pricing</x-ui.button>
                </div>
                <div class="flex flex-col gap-4 rounded-2xl bg-brand-light p-6 ring-1 ring-hairline sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm leading-relaxed text-secondary"><strong class="font-semibold text-brand">Need software that is not on this list?</strong> Whatever your business runs on, we can build it and add it to the same monthly bill.</p>
                    <x-ui.button :href="route('services.index')" class="shrink-0" arrow>See what else we build</x-ui.button>
                </div>
            </div>
        </div>
    </section>
@endif
