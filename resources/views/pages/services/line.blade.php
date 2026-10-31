{{--
    /services/{line} — one service line. Every service in it has its own anchor,
    so a service without a dedicated page is still linkable
    (/services/software-development#database-development). Services that do have
    a page lead with it.
--}}
@php
    use App\Support\Revamp;

    $pages = collect(Revamp::pages())->filter(fn ($p) => Revamp::catalogue()[$p['gate']]['line'] === $line['slug'] || collect($p['covers'] ?? [])->contains(fn ($c) => isset($line['services'][$c])));
    $quote = route('contact', ['type' => 'project', 'interest' => $line['slug'], 'from' => 'line-'.$line['slug']]);
    $index = array_search($line['slug'], array_keys($lines), true) + 1;
@endphp

<x-ui.layout overlay :title="$line['name'].' Services'" :description="$line['blurb']">
    @if ($line['preview'])
        <x-slot:head><meta name="robots" content="noindex, nofollow"></x-slot:head>
    @endif

    <section id="hero" class="band-night on-dark -mt-[77px] pt-[77px]" style="--motif-accent: var(--brand-mint)">
        <div class="contours absolute inset-y-0 right-0 -z-10 w-full opacity-[0.13] lg:w-[75%]" aria-hidden="true"></div>
        <div class="container-wide grid items-end gap-10 pb-14 pt-8 sm:pt-12 lg:grid-cols-12 lg:pb-20 lg:pt-14">
            <div class="lg:col-span-8">
                <nav aria-label="Breadcrumb"><ol class="flex items-center gap-2 text-xs text-white/60"><li><a href="{{ route('services.index') }}" class="hover:text-white">Services</a></li><li aria-hidden="true">/</li><li aria-current="page" class="text-white/80">{{ $line['short'] }}</li></ol></nav>
                @if ($line['preview'])
                    <p class="mt-6 inline-flex rounded-full border border-dashed border-white/40 px-3 py-1 text-xs font-semibold text-white/80">Preview only. This line is not published until capability is confirmed.</p>
                @endif
                <p class="mt-6 t-index text-mint">{{ str_pad($index, 2, '0', STR_PAD_LEFT) }} / {{ str_pad(count($lines), 2, '0', STR_PAD_LEFT) }}</p>
                <h1 class="t-display mt-3 text-white">{{ $line['name'] }}</h1>
                <p class="t-lede mt-6 max-w-2xl text-white/70">{{ $line['blurb'] }}</p>
                <div class="mt-9"><x-ui.button :href="$quote" tone="dark" size="lg" arrow>Get a Quote</x-ui.button></div>
            </div>
            <div class="hidden lg:col-span-4 lg:flex lg:justify-end">
                <x-ui.motif :name="$line['motif']" class="h-40 w-40 text-white/25" />
            </div>
        </div>
    </section>

    @if ($pages->isNotEmpty())
        <section class="band-paper pt-16 sm:pt-20">
            <div class="container-wide">
                <p class="t-label text-accent">In depth</p>
                <ul class="mt-6 grid gap-4 {{ [1 => 'lg:grid-cols-1', 2 => 'lg:grid-cols-2', 3 => 'lg:grid-cols-3'][min($pages->count(), 3)] }}">
                    @foreach ($pages as $slug => $p)
                        <li>
                            <a href="{{ route('services.show', $slug) }}" @class(['svc-card tile group flex h-full flex-col p-7', '!border-dashed !border-brand/30' => $p['preview']]) data-spotlight>
                                <div class="flex items-start justify-between gap-4">
                                    <x-ui.motif :name="$p['motif']" class="svc-motif h-10 w-10 text-brand" />
                                    @if ($p['preview'])<span class="rounded-full border border-dashed border-brand/40 px-2.5 py-1 text-[11px] font-semibold text-secondary">Preview only</span>@endif
                                </div>
                                <p class="mt-6 text-sm font-semibold text-accent">{{ $p['eyebrow'] }}</p>
                                <p class="mt-2 font-display text-xl font-bold leading-snug text-brand">{{ $p['h1'] }}</p>
                                <span class="svc-arrow mt-auto inline-flex items-center gap-1.5 pt-6 text-sm font-semibold text-accent">Read more <span aria-hidden="true">&rarr;</span></span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    <section class="band-paper section-v2">
        <div class="container-wide">
            <h2 class="t-subtitle text-brand">Every {{ strtolower($line['short']) }} service</h2>
            <ol class="mt-8 border-t border-brand/15">
                @foreach ($line['services'] as $s)
                    @php $page = Revamp::pageFor($s['slug']); @endphp
                    <li id="{{ $s['slug'] }}" class="cat-line grid scroll-mt-28 gap-3 border-b border-hairline py-7 sm:grid-cols-12 sm:gap-8" data-reveal>
                        <span class="t-index text-accent sm:col-span-1 sm:pt-1">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <div class="sm:col-span-7">
                            <h3 @class(['font-display text-xl font-bold', 'text-brand' => ! $s['preview'], 'text-secondary' => $s['preview']])>{{ $s['name'] }}</h3>
                            <p class="mt-1.5 leading-relaxed text-secondary">{{ $s['summary'] }}</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-4 sm:col-span-4 sm:justify-end">
                            <x-ui.badge :status="$s['availability']" />
                            @if ($page)
                                <a href="{{ route('services.show', $page) }}" class="text-sm font-semibold text-accent hover:text-brand">Read more &rarr;</a>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ol>

            <div class="mt-12 flex flex-wrap items-center justify-between gap-6">
                <p class="max-w-xl text-secondary">Need more than one of these? Most projects do. Tell us the outcome you want and we will scope the combination.</p>
                <x-ui.button :href="$quote" arrow>Get a Quote</x-ui.button>
            </div>
        </div>
    </section>

    <nav aria-label="Other service lines" class="band-mist">
        <div class="container-wide py-12">
            <p class="t-label text-muted">Other service lines</p>
            <ul class="mt-5 flex flex-wrap gap-2">
                @foreach ($lines as $slug => $l)
                    @continue($slug === $line['slug'])
                    <li><a href="{{ route('services.show', $slug) }}" class="nav-item inline-flex rounded-full bg-white px-4 py-2 text-sm font-semibold text-secondary ring-1 ring-hairline hover:text-brand hover:ring-brand/30">{{ $l['name'] }}</a></li>
                @endforeach
            </ul>
        </div>
    </nav>
</x-ui.layout>
