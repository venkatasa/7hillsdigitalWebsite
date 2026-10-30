@props([
    'image',             // manifest key, see App\Support\ProductImages
    'url' => null,       // what the address bar shows
    'tone' => 'dark',
    'eager' => false,
    'caption' => true,
])

@php
    $img = \App\Support\ProductImages::get($image);
    $dark = $tone === 'dark';
@endphp

<figure {{ $attributes->class(['relative']) }}>
    <div @class([
        'overflow-hidden rounded-[18px] elevate-3',
        'border border-white/10 bg-[color-mix(in_oklab,var(--brand-primary)_70%,var(--brand-dark))]' => $dark,
        'border border-hairline bg-white' => ! $dark,
    ])>
        {{-- Window chrome: drawn, not an image, so it costs nothing. --}}
        <div @class([
            'flex h-9 items-center gap-3 px-4',
            'border-b border-white/10' => $dark,
            'border-b border-hairline bg-brand-light' => ! $dark,
        ]) aria-hidden="true">
            <span class="flex gap-1.5">
                <span @class(['h-2.5 w-2.5 rounded-full', 'bg-white/15' => $dark, 'bg-brand/15' => ! $dark])></span>
                <span @class(['h-2.5 w-2.5 rounded-full', 'bg-white/15' => $dark, 'bg-brand/15' => ! $dark])></span>
                <span @class(['h-2.5 w-2.5 rounded-full', 'bg-white/15' => $dark, 'bg-brand/15' => ! $dark])></span>
            </span>
            @if ($url)
                <span @class([
                    'mx-auto flex h-5 max-w-[60%] items-center truncate rounded-md px-3 text-[11px]',
                    'bg-white/[0.06] text-white/45' => $dark,
                    'bg-white text-muted ring-1 ring-hairline' => ! $dark,
                ])>{{ $url }}</span>
            @endif
        </div>

        <div class="frame-shot" style="aspect-ratio: {{ $img['width'] ?? 16 }} / {{ $img['height'] ?? 10 }}">
            @if ($img)
                <img src="{{ asset($img['file']) }}"
                     alt="{{ $img['alt'] }}"
                     width="{{ $img['width'] }}" height="{{ $img['height'] }}"
                     loading="{{ $eager ? 'eager' : 'lazy' }}" decoding="async"
                     @if ($eager) fetchpriority="high" @endif
                     class="block h-full w-full object-cover object-top">
            @endif
        </div>
    </div>

    @if ($caption && $img)
        <figcaption @class([
            'mt-3 flex items-center gap-2 text-xs',
            'text-white/55' => $dark,
            'text-muted' => ! $dark,
        ])>
            <span @class(['h-px w-5', 'bg-mint/60' => $dark, 'bg-accent/60' => ! $dark]) aria-hidden="true"></span>
            {{ $img['label'] }}
        </figcaption>
    @endif
</figure>
