@props([
    'visual',          // ['type' => screens|diagram|flow|constellation, …]
])

{{--
    The signature visual on a service page. Screens are genuine and labelled
    (frame components print the manifest label); everything else is an
    illustration and says so.
--}}
@switch($visual['type'])
    @case('screens')
        <div class="relative pb-8 {{ ! empty($visual['phone']) ? 'pl-8 sm:pl-14 lg:pl-4' : '' }}" data-depth-scene>
            <div data-depth="1" @class(['lg:w-[140%]' => $visual['bleed'] ?? true])>
                <x-ui.frame-browser :image="$visual['browser']" :url="$visual['url']" :caption="false" eager />
            </div>
            @if (! empty($visual['phone']))
                <div data-depth="2.4" class="absolute -bottom-1 left-0 w-[30%] max-w-[11rem] lg:-left-8 lg:w-[32%]">
                    <x-ui.frame-phone :image="$visual['phone']" />
                </div>
            @endif
            <p class="absolute -top-6 right-0 flex items-center gap-2 text-[11px] text-white/60 lg:left-[30%] lg:right-auto">
                {{ \App\Support\ProductImages::get($visual['browser'])['label'] ?? '' }}
                <span class="h-px w-6 bg-mint/60" aria-hidden="true"></span>
            </p>
            @if (! empty($visual['phone']))
                <p class="absolute -bottom-7 left-[33%] flex items-center gap-2 text-[11px] text-white/60 lg:left-[26%]">
                    <span class="h-px w-6 bg-mint/60" aria-hidden="true"></span>
                    {{ \App\Support\ProductImages::get($visual['phone'])['label'] ?? '' }}
                </p>
            @endif
        </div>
        @break

    @case('diagram')
        <div class="tile-dark p-6 sm:p-8">
            <div class="contours absolute inset-0 -z-10 opacity-[0.1]" aria-hidden="true"></div>
            <x-ui.diagram :name="$visual['name']" tone="dark" />
        </div>
        @break

    @case('flow')
        <div class="tile-dark p-6 sm:p-9">
            <div class="contours absolute inset-0 -z-10 opacity-[0.1]" aria-hidden="true"></div>
            <x-ui.flow tone="dark" :steps="$visual['steps']" />
        </div>
        @break

    @case('constellation')
        {{-- One capability at the centre, the pieces it joins up around it. --}}
        <figure class="tile-dark relative aspect-[5/4] p-6 sm:aspect-[4/3]" style="--motif-accent: var(--brand-mint)">
            <div class="contours absolute inset-0 -z-10 opacity-[0.12]" aria-hidden="true"></div>
            <svg class="absolute inset-0 h-full w-full" viewBox="0 0 400 300" preserveAspectRatio="none" aria-hidden="true">
                @foreach ([[80, 60], [320, 60], [60, 240], [340, 240], [200, 30], [200, 275]] as [$x, $y])
                    <line x1="200" y1="150" x2="{{ $x }}" y2="{{ $y }}" class="stroke-white/15" stroke-dasharray="3 5" vector-effect="non-scaling-stroke"/>
                @endforeach
            </svg>
            <div class="absolute left-1/2 top-1/2 grid h-24 w-24 -translate-x-1/2 -translate-y-1/2 place-items-center rounded-3xl bg-brand-dark ring-1 ring-mint/40 sm:h-28 sm:w-28">
                <x-ui.motif :name="$visual['motif']" class="h-12 w-12 text-white/85 sm:h-14 sm:w-14" />
            </div>
            @foreach (array_slice($visual['items'], 0, 6) as $i => $item)
                @php $pos = ['left-[4%] top-[12%]', 'right-[4%] top-[12%]', 'left-[2%] bottom-[14%]', 'right-[2%] bottom-[14%]', 'left-1/2 top-[3%] -translate-x-1/2', 'left-1/2 bottom-[3%] -translate-x-1/2'][$i]; @endphp
                <span class="absolute {{ $pos }} rounded-full border border-white/15 bg-brand-dark/90 px-3 py-1.5 text-xs font-semibold text-white/80 backdrop-blur sm:text-sm">{{ $item }}</span>
            @endforeach
            <figcaption class="sr-only">Illustration: {{ implode(', ', $visual['items']) }}.</figcaption>
        </figure>
        <p class="mt-3 text-[11px] text-white/60">Illustration, not a product screen.</p>
        @break
@endswitch
