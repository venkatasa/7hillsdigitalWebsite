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
                            <x-demo-art :kind="$demo['art']" :demo="$slug" :seed="0" :label="$demo['name']"
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

    {{-- ── Five demos are not the limit ─────────────────────────────────── --}}
    <section class="container-page pb-4">
        <div class="overflow-hidden rounded-3xl bg-brand text-white" data-reveal>
            <div class="grid gap-10 p-8 sm:p-12 lg:grid-cols-[1.1fr_0.9fr] lg:gap-14">
                <div>
                    <p class="eyebrow-light">And these are only five</p>
                    <h2 class="mt-3 h-section">If it can be listed, we can build it.</h2>
                    <p class="mt-5 leading-relaxed text-white/70">
                        Every demo above is the same engine with different labels. Swap
                        "property" for whatever your business sells and the site works the
                        same way — equipment, courses, funeral plans, stock, services,
                        practitioners, venues. If you can put it in a list, we can put it
                        online and give you the back office to run it.
                    </p>
                    <p class="mt-4 leading-relaxed text-white/70">
                        We have not built a demo for your trade yet. That is not the same as
                        not being able to.
                    </p>
                </div>

                <div class="space-y-4">
                    <div class="rounded-2xl border border-mint/40 bg-mint/[0.08] p-6">
                        <h3 class="font-display text-base font-bold text-mint">Selling online?</h3>
                        <p class="mt-2 text-sm leading-relaxed text-white/75">
                            We do e-commerce too — a proper shop with a cart, checkout, South
                            African payment gateways and stock that goes down when something
                            sells. It is priced separately from the packages above because the
                            work is genuinely different. Ask us.
                        </p>
                        <a href="{{ route('contact') }}" class="mt-4 inline-flex text-sm font-semibold text-mint underline underline-offset-4 hover:brightness-110">
                            Talk to us about an online shop &rarr;
                        </a>
                    </div>

                    <div class="rounded-2xl border border-white/15 bg-white/[0.04] p-6">
                        <h3 class="font-display text-base font-bold">Something else entirely?</h3>
                        <p class="mt-2 text-sm leading-relaxed text-white/70">
                            Custom software, digitising paper, automation, IT support,
                            hardware. It is one call away.
                        </p>
                        <div class="mt-4 flex flex-wrap gap-x-5 gap-y-2 text-sm">
                            <a href="{{ route('it-services') }}" class="font-semibold text-mint underline underline-offset-4 hover:brightness-110">
                                See what else we build &rarr;
                            </a>
                            <span class="text-white/60">{{ config('brand.contact.phone') }}</span>
                        </div>
                    </div>
                </div>
            </div>
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
