@props([
    'page',
    'line' => null,
    'quote',
])

{{-- A service page opens on the business problem, in outcome language, with the
     searched noun as the eyebrow (05_NAVIGATION_AND_SEO.md §5.4). --}}
<section id="hero" class="band-night on-dark -mt-[77px] pt-[77px]">
    <div class="contours absolute inset-y-0 right-0 -z-10 w-full opacity-[0.13] lg:w-[75%]" aria-hidden="true"></div>
    <div class="absolute inset-y-0 left-0 -z-10 w-2/3 bg-gradient-to-r from-brand-dark via-brand-dark/85 to-transparent" aria-hidden="true"></div>

    <div class="container-wide grid items-center gap-y-14 pb-16 pt-8 sm:pt-12 lg:grid-cols-12 lg:gap-x-12 lg:pb-24 lg:pt-14">
        <div class="lg:col-span-6">
            <nav aria-label="Breadcrumb">
                <ol class="flex flex-wrap items-center gap-x-2 gap-y-1 text-xs text-white/60">
                    <li><a href="{{ route('services.index') }}" class="hover:text-white">Services</a></li>
                    @if ($line)
                        <li aria-hidden="true">/</li>
                        <li><a href="{{ route('services.show', $line['slug']) }}" class="hover:text-white">{{ $line['name'] }}</a></li>
                    @endif
                </ol>
            </nav>

            @if ($page['preview'])
                <p class="mt-6 inline-flex rounded-full border border-dashed border-white/40 px-3 py-1 text-xs font-semibold text-white/80">
                    Preview only. This page is not published until capability is confirmed.
                </p>
            @endif

            <p class="mt-6 inline-flex items-center gap-2.5 text-xs font-semibold uppercase tracking-[0.16em] text-mint">
                <span class="h-px w-6 bg-mint/70" aria-hidden="true"></span>{{ $page['eyebrow'] }}
            </p>
            <h1 class="t-display mt-5 text-white">{{ $page['h1'] }}</h1>
            <p class="t-lede mt-6 max-w-xl text-white/70">{{ $page['lede'] }}</p>

            <div class="mt-9 flex flex-col gap-3 sm:flex-row sm:items-center">
                <x-ui.button :href="$quote" tone="dark" size="lg" arrow data-cta="service-hero-quote">{{ $page['cta'] ?? 'Get a quote' }}</x-ui.button>
                <x-ui.button href="#approach" tone="dark" variant="secondary" size="lg">How we deliver</x-ui.button>
            </div>
        </div>

        <div class="relative lg:col-span-6">
            <x-service.visual :visual="$page['visual']" />
        </div>
    </div>

    @if (! empty($page['facts']))
        <div class="relative border-t border-white/10">
            <dl class="container-wide grid sm:grid-cols-3">
                @foreach ($page['facts'] as [$term, $detail])
                    <div class="border-white/10 py-5 sm:border-l sm:px-6 sm:first:border-l-0 sm:first:pl-0">
                        <dt class="text-sm font-semibold text-white">{{ $term }}</dt>
                        <dd class="mt-1 text-sm text-white/60">{{ $detail }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>
    @endif
</section>
