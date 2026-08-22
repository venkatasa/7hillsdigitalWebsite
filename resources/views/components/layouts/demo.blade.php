@props(['demo', 'slug'])

@php
    $brand = config('brand');
    $package = \App\Models\Package::where('slug', $demo['package'])->first();
    $tier = $package?->tiers->firstWhere('is_default', true) ?? $package?->tiers->first();
@endphp

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $demo['name'] }} — demonstration site by {{ $brand['name'] }}</title>
    <meta name="description" content="A demonstration site by {{ $brand['name'] }}, showing what a {{ \Illuminate\Support\Str::lower($demo['vertical']) }} website looks like on the {{ $package?->name }} package. All content is fictional.">

    {{-- A demo must never outrank the real thing, or a client's real site. --}}
    <meta name="robots" content="noindex, nofollow">

    <link rel="icon" href="{{ asset($brand['logo']['favicon']) }}" sizes="any">

    <style>
        :root {
            --demo-primary: {{ $demo['palette']['primary'] }};
            --demo-accent: {{ $demo['palette']['accent'] }};
            --demo-wash: {{ $demo['palette']['wash'] }};
        @foreach ($brand['colors'] as $token => $value)
            --brand-{{ $token }}: {{ $value }};
        @endforeach
        }
    </style>

    {{ Vite::fonts() }}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col bg-white">

    {{-- ── Demo ribbon ──────────────────────────────────────────────────── --}}
    <div class="bg-brand text-white">
        <div class="container-page flex flex-wrap items-center justify-between gap-x-6 gap-y-2 py-2.5 text-xs">
            <p class="flex items-center gap-2">
                <span class="inline-flex h-1.5 w-1.5 shrink-0 rounded-full bg-mint"></span>
                <span>
                    <strong class="font-semibold">Demonstration site.</strong>
                    <span class="text-white/65">Every business, listing and price here is invented.</span>
                </span>
            </p>
            <p class="flex items-center gap-4">
                <a href="{{ route('demos') }}" class="text-white/75 underline underline-offset-2 hover:text-white">All demos</a>
                <a href="{{ route('home') }}" class="font-semibold text-mint hover:brightness-110">
                    Built by {{ $brand['name'] }} &rarr;
                </a>
            </p>
        </div>
    </div>

    {{-- ── Tenant header ────────────────────────────────────────────────── --}}
    <header x-data="{ open: false }"
            class="sticky top-0 z-40 border-b bg-white/95 backdrop-blur"
            style="border-color: color-mix(in srgb, var(--demo-primary) 12%, transparent)">
        <div class="container-page flex h-[72px] items-center justify-between gap-4">
            <a href="#top" class="flex items-center gap-2.5">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg font-display text-sm font-extrabold text-white"
                      style="background: var(--demo-primary)">7</span>
                <span class="leading-none">
                    <span class="block font-display text-[15px] font-extrabold tracking-tight" style="color: var(--demo-primary)">
                        {{ $demo['name'] }}
                    </span>
                    <span class="mt-0.5 block text-[10px] font-semibold uppercase tracking-[0.16em]" style="color: var(--demo-accent)">
                        {{ $demo['strap'] }}
                    </span>
                </span>
            </a>

            <nav class="hidden items-center gap-7 text-sm font-medium md:flex" aria-label="Demo site">
                @foreach ($demo['nav'] ?? [['#listings', 'What we offer'], ['#about', 'Why us'], ['#contact', 'Contact']] as [$href, $label])
                    <a href="{{ $href }}" class="text-secondary transition hover:opacity-70">{{ $label }}</a>
                @endforeach
            </nav>

            <a href="#contact" class="hidden rounded-lg px-5 py-2.5 text-sm font-semibold text-white transition hover:brightness-110 md:inline-flex"
               style="background: var(--demo-accent)">
                Get in touch
            </a>

            <button type="button" x-on:click="open = ! open" :aria-expanded="open ? 'true' : 'false'"
                    class="-mr-2 inline-flex h-10 w-10 items-center justify-center rounded-lg md:hidden"
                    style="color: var(--demo-primary)">
                <span class="sr-only">Toggle menu</span>
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path x-show="! open" stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/>
                    <path x-show="open" x-cloak stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/>
                </svg>
            </button>
        </div>

        <div x-show="open" x-collapse x-cloak class="border-t md:hidden" style="border-color: color-mix(in srgb, var(--demo-primary) 12%, transparent)">
            <nav class="container-page flex flex-col py-3" aria-label="Demo site">
                @foreach ($demo['nav'] ?? [['#listings', 'What we offer'], ['#about', 'Why us'], ['#contact', 'Contact']] as [$href, $label])
                    <a href="{{ $href }}" x-on:click="open = false" class="py-2.5 text-sm font-medium text-secondary">{{ $label }}</a>
                @endforeach
                <a href="#contact" x-on:click="open = false"
                   class="mt-3 rounded-lg px-5 py-3 text-center text-sm font-semibold text-white"
                   style="background: var(--demo-accent)">Get in touch</a>
            </nav>
        </div>
    </header>

    <main id="top" class="flex-1">
        {{ $slot }}
    </main>

    {{-- ── Back-office pitch: the actual point of the demo ──────────────── --}}
    <section class="bg-brand text-white">
        <div class="container-page section">
            <div class="grid gap-12 lg:grid-cols-[1.1fr_0.9fr] lg:gap-16">
                <div data-reveal>
                    <p class="eyebrow-light">You have seen the front. Now the useful bit.</p>
                    <h2 class="mt-3 h-section">
                        Everything on this page is edited by the owner, not by us.
                    </h2>
                    <p class="mt-5 leading-relaxed text-white/70">
                        Behind every site like this is a back office where
                        {{ \Illuminate\Support\Str::lower($demo['vertical']) }} staff add
                        {{ $slug === 'careers' ? 'a vacancy' : ($slug === 'auto' ? 'a vehicle' : 'a listing') }},
                        change a price, upload photographs and work the enquiries that come in —
                        without phoning a developer or waiting until Tuesday.
                    </p>
                    <p class="mt-4 leading-relaxed text-white/70">
                        We have not put it on the public internet, for the obvious reason. Ask us
                        and we will walk you through the real thing, with your own business in it.
                    </p>

                    <div class="mt-9 flex flex-wrap gap-3">
                        <a href="{{ route('contact', ['package' => $demo['package']]) }}" class="btn-mint btn-lg">
                            Show me the back office
                        </a>
                        <a href="{{ route('packages.show', $demo['package']) }}" class="btn-ghost btn-lg">
                            What {{ $package?->name }} includes
                        </a>
                    </div>
                </div>

                <div class="rounded-2xl border border-white/15 bg-white/[0.04] p-6 sm:p-8" data-reveal data-reveal-delay="120">
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-mint">A site like this costs</p>

                    @if ($tier)
                        <p class="mt-4 flex items-baseline gap-2">
                            <span class="font-display text-5xl font-extrabold tracking-tight">{{ $tier->formatted_price }}</span>
                            <span class="text-white/60">/ month</span>
                        </p>
                        <p class="mt-2 text-sm text-white/60">
                            {{ $tier->name }}, excl. VAT — {{ $tier->storage_summary }},
                            unlimited email addresses, and the build itself free.
                        </p>
                    @endif

                    <ul class="mt-7 space-y-3 text-sm text-white/75">
                        @foreach ([
                            'Nothing to pay until your site is live',
                            'No minimum term — one month\'s notice to cancel',
                            'Email on your own domain, included',
                            'Hosting, SSL, backups and support, included',
                        ] as $line)
                            <li class="flex gap-2.5">
                                <svg class="mt-0.5 h-4 w-4 shrink-0 text-mint" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M16.7 5.3a1 1 0 0 1 0 1.4l-7.5 7.5a1 1 0 0 1-1.4 0L3.3 9.7a1 1 0 1 1 1.4-1.4l3.8 3.8 6.8-6.8a1 1 0 0 1 1.4 0Z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $line }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- ── Tenant footer ────────────────────────────────────────────────── --}}
    <footer class="py-10 text-sm" style="background: var(--demo-primary)">
        <div class="container-page">
            <div class="flex flex-col gap-3 text-white/60 sm:flex-row sm:items-center sm:justify-between">
                <p>&copy; {{ now()->year }} {{ $demo['name'] }} — a fictional business, for demonstration.</p>
                <p>
                    <a href="{{ route('home') }}" class="underline underline-offset-2 hover:text-white">
                        Website by {{ $brand['name'] }}
                    </a>
                </p>
            </div>

            {{-- CC BY and CC BY-SA oblige us to name the photographer. CC0 and
                 Public Domain Mark do not, so they are left out. --}}
            @php $attributions = \App\Support\DemoImages::attributions($slug); @endphp

            @if ($attributions)
                <details class="mt-6 border-t border-white/10 pt-5 text-xs text-white/40">
                    <summary class="cursor-pointer hover:text-white/70">Photography credits</summary>
                    <p class="mt-2 leading-relaxed">
                        Openly-licensed photographs, used to illustrate a demonstration site:
                        {{ implode(' · ', $attributions) }}.
                    </p>
                </details>
            @endif
        </div>
    </footer>

    <x-whatsapp-float :message="'Hi 7Hills Digital, I have been looking at the '.$demo['name'].' demo and would like to know more.'" />

</body>
</html>
