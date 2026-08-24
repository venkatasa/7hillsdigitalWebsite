<x-layouts.site :title="$package->name.' — '.$package->vertical_label"
                :description="$package->tagline.' '.\Illuminate\Support\Str::limit($package->intro, 130)">

    <section class="bg-brand text-white">
        <div class="container-page section">
            <nav aria-label="Breadcrumb" class="text-sm text-white/50">
                <a href="{{ route('packages.index') }}" class="hover:text-white">Packages</a>
                <span class="mx-2" aria-hidden="true">/</span>
                <span class="text-white/80">{{ $package->name }}</span>
            </nav>

            <div class="mt-6 flex items-start gap-4">
                <span class="inline-flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/10 text-mint">
                    <x-package-icon :name="$package->icon_key" class="h-7 w-7" />
                </span>
                <div>
                    <p class="eyebrow-light">{{ $package->vertical_label }}</p>
                    <h1 class="mt-1.5 font-display text-4xl font-extrabold tracking-tight sm:text-5xl">{{ $package->name }}</h1>
                </div>
            </div>

            <p class="mt-6 max-w-2xl text-xl font-medium text-mint">{{ $package->tagline }}</p>
            <p class="mt-4 max-w-2xl text-base leading-relaxed text-white/70">{{ $package->intro }}</p>

            <div class="mt-9 flex flex-wrap gap-3">
                <a href="{{ route('contact', ['package' => $package->slug]) }}" class="btn-mint">Ask about {{ $package->name }}</a>
                @php $demoSlug = $package->demoSlug(); @endphp
                @if ($demoSlug)
                    <a href="{{ route('demos.show', $demoSlug) }}" class="btn-ghost">See a live {{ $package->name }} site</a>
                @elseif ($package->demo_url)
                    <a href="{{ $package->demo_url }}" class="btn-ghost">See the demo</a>
                @else
                    <a href="{{ route('demos') }}" class="btn-ghost">See the demos</a>
                @endif
            </div>
        </div>
    </section>

    {{-- ── What you get ─────────────────────────────────────────────────── --}}
    <section class="container-page section">
        <div class="grid gap-12 lg:grid-cols-[1.4fr_1fr] lg:gap-16">
            <div>
                <p class="eyebrow">What you get</p>
                <h2 class="mt-3 h-section text-brand">
                    Built the way {{ \Illuminate\Support\Str::lower($package->vertical_label) }} actually work.
                </h2>

                <ul class="mt-8 space-y-4">
                    @foreach ($package->what_you_get ?? [] as $item)
                        <li class="flex gap-3.5">
                            <svg class="mt-1 h-5 w-5 shrink-0 text-accent" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.7 5.3a1 1 0 0 1 0 1.4l-7.5 7.5a1 1 0 0 1-1.4 0L3.3 9.7a1 1 0 1 1 1.4-1.4l3.8 3.8 6.8-6.8a1 1 0 0 1 1.4 0Z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-base leading-relaxed text-secondary">{{ $item }}</span>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-10 rounded-xl bg-brand-light p-6">
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-brand">Plus everything in every package</h3>
                    <p class="mt-2 text-sm leading-relaxed text-secondary">
                        Hosting, SSL, daily backups, security updates and support — included,
                        not extra. Email is priced separately, from R20 a mailbox a month.
                    </p>
                </div>
            </div>

            {{-- ── Pricing rail ─────────────────────────────────────────── --}}
            <aside class="lg:sticky lg:top-24 lg:self-start">
                <div class="rounded-2xl border border-brand/10 p-6">
                    <h2 class="text-sm font-semibold uppercase tracking-wider text-muted">Choose your tier</h2>

                    <div class="mt-5 space-y-4">
                        @foreach ($package->tiers as $tier)
                            <div @class([
                                'rounded-xl border p-5',
                                'border-accent bg-accent/5' => $tier->is_default,
                                'border-brand/10' => ! $tier->is_default,
                            ])>
                                <div class="flex items-baseline justify-between gap-3">
                                    <h3 class="text-base font-bold text-brand">{{ $tier->name }}</h3>
                                    <p class="text-right">
                                        <span class="text-2xl font-bold tracking-tight text-brand">{{ $tier->formatted_price }}</span>
                                        <span class="block text-xs text-muted">per month excl. VAT</span>
                                    </p>
                                </div>
                                <ul class="mt-4 space-y-1.5 text-sm text-secondary">
                                    <li><strong class="font-semibold text-brand">{{ $tier->media_gb }}GB</strong> of website storage</li>
                                    <li>Hosting, SSL, daily backups and support</li>
                                    <li>The build itself costs nothing</li>
                                    <li class="text-muted">Email sold separately, from R20 a mailbox</li>
                                </ul>
                            </div>
                        @endforeach
                    </div>

                    <a href="{{ route('contact', ['package' => $package->slug]) }}" class="btn-accent mt-6 w-full">
                        Get {{ $package->name }} started
                    </a>
                    <p class="mt-3 text-center text-xs text-muted">
                        Nothing to pay today. First invoice when the site goes live.
                    </p>
                </div>
            </aside>
        </div>
    </section>

    {{-- ── Built for ────────────────────────────────────────────────────── --}}
    @if ($package->built_for)
        <section class="bg-brand-light section">
            <div class="container-page">
                <p class="eyebrow">Who it suits</p>
                <h2 class="mt-3 h-section text-brand">{{ $package->name }} is built for</h2>
                <ul class="mt-8 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($package->built_for as $who)
                        <li class="rounded-lg border border-brand/10 bg-white px-5 py-4 text-sm font-medium text-brand">{{ $who }}</li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    {{-- ── Other packages ───────────────────────────────────────────────── --}}
    <section class="container-page section">
        <h2 class="h-section text-brand">Not quite your trade?</h2>
        <p class="mt-3 max-w-2xl text-sm leading-relaxed text-secondary">
            The other packages run on the same platform. If none of them fit, Business is
            the one that bends to suit you.
        </p>
        <div class="mt-8 grid gap-6 md:grid-cols-2 lg:grid-cols-4">
            @foreach ($others as $other)
                <a href="{{ route('packages.show', $other) }}"
                   class="group rounded-xl border border-brand/10 p-5 transition hover:border-accent/60">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-brand-light text-brand">
                        <x-package-icon :name="$other->icon_key" class="h-5 w-5" />
                    </span>
                    <h3 class="mt-3.5 text-base font-bold text-brand group-hover:text-accent">{{ $other->name }}</h3>
                    <p class="mt-1 text-xs font-medium uppercase tracking-wider text-muted">{{ $other->vertical_label }}</p>
                </a>
            @endforeach
        </div>
    </section>

</x-layouts.site>
