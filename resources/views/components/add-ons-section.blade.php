@props(['addOns'])

@if ($addOns->isNotEmpty())
    <section id="add-ons" class="container-page py-16 sm:py-20">
        <div class="max-w-2xl">
            <p class="eyebrow">Optional modules</p>
            <h2 class="mt-3 text-2xl font-bold tracking-tight text-brand sm:text-3xl">
                Business software that bolts onto your site.
            </h2>
            <p class="mt-4 text-base leading-relaxed text-secondary">
                These are <strong class="font-semibold text-brand">charged separately</strong>
                from your monthly package — none of them is bundled in, and none of them
                appears on your invoice unless you have asked for it. Add one whenever you
                are ready, and drop it the same way.
            </p>
        </div>

        <div class="mt-12 grid gap-6 md:grid-cols-3">
            @foreach ($addOns as $addOn)
                <article class="flex flex-col rounded-2xl border border-hairline bg-white p-6">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-brand-light text-accent">
                            <x-package-icon :name="$addOn->icon_key" />
                        </span>
                        <h3 class="text-lg font-bold text-brand">{{ $addOn->name }}</h3>
                    </div>

                    <p class="mt-4 text-sm font-medium text-brand">{{ $addOn->tagline }}</p>

                    <div class="mt-5 border-t border-hairline pt-5">
                        @if ($addOn->is_metered)
                            <p class="text-lg font-bold tracking-tight text-brand">Quoted on request</p>
                            <p class="mt-1 text-xs leading-relaxed text-muted">{{ $addOn->price_basis }}</p>
                        @else
                            <p class="flex items-baseline gap-1.5">
                                <span class="text-3xl font-bold tracking-tight text-brand">+{{ $addOn->formatted_price }}</span>
                                <span class="text-sm text-muted">/ month</span>
                            </p>
                            <p class="mt-1 text-xs text-muted">Added to your package fee, excl. VAT</p>
                        @endif
                    </div>

                    <ul class="mt-5 flex-1 space-y-2 text-sm text-secondary">
                        @foreach (array_slice($addOn->what_it_does ?? [], 0, 4) as $item)
                            <li class="flex gap-2.5">
                                <svg class="mt-0.5 h-4 w-4 shrink-0 text-accent" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M16.7 5.3a1 1 0 0 1 0 1.4l-7.5 7.5a1 1 0 0 1-1.4 0L3.3 9.7a1 1 0 1 1 1.4-1.4l3.8 3.8 6.8-6.8a1 1 0 0 1 1.4 0Z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                </article>
            @endforeach
        </div>

        <p class="mt-8 text-sm text-secondary">
            Not sure which you need?
            <a href="{{ route('contact') }}" class="font-semibold text-accent underline underline-offset-2 hover:text-brand">Tell us how you work</a>
            and we will say plainly whether a module earns its keep for you.
        </p>
    </section>
@endif
