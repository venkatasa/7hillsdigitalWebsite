@php
    $demos = config('demos', []);
@endphp

<x-layouts.site title="Demos"
                description="Working demonstration sites for each package — see exactly what your business would be getting before you commit to anything.">

    {{-- ── Hero ─────────────────────────────────────────────────────────── --}}
    <section class="relative overflow-hidden bg-brand text-white">
        <div aria-hidden="true" class="pointer-events-none absolute inset-0 opacity-[0.09]">
            <div class="glow -right-28 -top-28 h-[26rem] w-[26rem] bg-mint"></div>
        </div>

        <div class="container-page relative section">
            <p class="eyebrow-light" data-reveal>Demos</p>
            <h1 class="mt-4 max-w-3xl h-display" data-reveal data-reveal-delay="60">
                Look at the real thing before you commit to anything.
            </h1>
            <p class="mt-6 max-w-2xl lede text-white/75" data-reveal data-reveal-delay="120">
                Five complete sites, one per package, each built for a fictional business so
                you can click around freely. Every listing, price and review in them is
                invented — but the site is exactly what you would get.
            </p>
        </div>
    </section>

    {{-- ── Demo grid ────────────────────────────────────────────────────── --}}
    <section class="container-page section">
        <div class="grid gap-8 lg:grid-cols-2">
            @foreach ($demos as $slug => $demo)
                @php $package = $packages->firstWhere('slug', $demo['package']); @endphp

                <article class="group overflow-hidden rounded-3xl border border-hairline bg-white transition duration-300 hover:-translate-y-1 hover:shadow-2xl hover:shadow-brand/[0.08]"
                         data-reveal data-reveal-delay="{{ $loop->index % 2 * 90 }}"
                         style="--demo-primary: {{ $demo['palette']['primary'] }}; --demo-accent: {{ $demo['palette']['accent'] }}">
                    <a href="{{ route('demos.show', $slug) }}" class="block">
                        <div class="relative overflow-hidden">
                            <x-demo-art :kind="$demo['art']" :seed="$loop->index + 1" :label="$demo['name']"
                                        class="aspect-[16/9] transition duration-500 group-hover:scale-105" />
                            <div class="absolute inset-x-0 bottom-0 p-6">
                                <p class="font-display text-2xl font-extrabold text-white drop-shadow">{{ $demo['name'] }}</p>
                                <p class="mt-1 text-sm font-medium text-white/80">{{ $demo['strap'] }}</p>
                            </div>
                        </div>
                    </a>

                    <div class="p-6 sm:p-7">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="rounded-full bg-brand-light px-3 py-1 text-xs font-semibold text-brand">
                                {{ $demo['vertical'] }}
                            </span>
                            @if ($package)
                                <span class="rounded-full px-3 py-1 text-xs font-semibold text-white" style="background: var(--demo-accent)">
                                    {{ $package->name }} package
                                </span>
                            @endif
                        </div>

                        <p class="mt-4 leading-relaxed text-secondary">{{ $demo['hero_copy'] }}</p>

                        <div class="mt-6 flex flex-wrap items-center gap-3">
                            <a href="{{ route('demos.show', $slug) }}" class="btn-accent">
                                Open the {{ $demo['name'] }} demo
                            </a>
                            @if ($package)
                                <a href="{{ route('packages.show', $package) }}" class="text-sm font-semibold text-brand underline underline-offset-4 hover:text-accent">
                                    What it costs
                                </a>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    {{-- ── What the demos deliberately leave out ────────────────────────── --}}
    <section class="bg-brand-light section">
        <div class="container-page grid gap-12 lg:grid-cols-2 lg:gap-16">
            <div data-reveal>
                <p class="eyebrow">What you are not seeing</p>
                <h2 class="mt-3 h-section text-brand">The back office is the half that earns its keep.</h2>
                <p class="mt-5 leading-relaxed text-secondary">
                    The demos are public, so they show the public side only. The part that
                    actually changes how you work — where staff load stock, publish a listing,
                    change a price and work the enquiries — is behind a login, and we would
                    rather show you that live than leave it on the internet.
                </p>
                <a href="{{ route('contact') }}" class="btn-accent mt-8">Book a walkthrough</a>
            </div>

            <ul class="space-y-4" data-reveal data-reveal-delay="100">
                @foreach ([
                    'Adding, editing and publishing your own stock — no developer involved',
                    'Photo galleries uploaded from a phone on the floor',
                    'Enquiries arriving as tracked leads, with a contact log',
                    'Documents collected securely — IDs, payslips, CVs',
                    'Changing your own prices, whenever you like',
                    'Staff accounts, so people see only what they should',
                ] as $item)
                    <li class="flex gap-3 rounded-xl border border-hairline bg-white px-5 py-4">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-accent" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M16.7 5.3a1 1 0 0 1 0 1.4l-7.5 7.5a1 1 0 0 1-1.4 0L3.3 9.7a1 1 0 1 1 1.4-1.4l3.8 3.8 6.8-6.8a1 1 0 0 1 1.4 0Z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm font-medium text-brand">{{ $item }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- ── Your own content ─────────────────────────────────────────────── --}}
    <section class="container-page section">
        <div class="rounded-3xl border border-hairline bg-brand-light px-8 py-14 text-center sm:px-14" data-reveal>
            <h2 class="mx-auto max-w-3xl h-section text-brand">
                Would you rather see it with your own stock in it?
            </h2>
            <p class="mx-auto mt-4 max-w-2xl lede text-secondary">
                Send us a handful of photographs and prices and we will put them into a demo
                for you. It costs you nothing, and it is a far better test than somebody
                else's sample data.
            </p>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a href="{{ route('contact') }}" class="btn-accent btn-lg">Ask for your own demo</a>
                <a href="{{ route('it-services') }}" class="btn-outline btn-lg">My industry is not listed</a>
            </div>
        </div>
    </section>

</x-layouts.site>
