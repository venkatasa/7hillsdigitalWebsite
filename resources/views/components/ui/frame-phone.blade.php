@props([
    'image',
    'eager' => false,
    'caption' => false,
    'tone' => 'dark',
])

@php
    $img = \App\Support\ProductImages::get($image);
@endphp

<figure {{ $attributes->class(['relative']) }}>
    <div class="rounded-[34px] bg-brand-dark p-[7px] elevate-3 ring-1 ring-white/15">
        <div class="frame-shot relative overflow-hidden rounded-[28px]" style="aspect-ratio: 390 / 844">
            {{-- Speaker slot, drawn. --}}
            <span class="absolute left-1/2 top-2 z-10 h-[18px] w-[72px] -translate-x-1/2 rounded-full bg-brand-dark" aria-hidden="true"></span>
            @if ($img)
                <img src="{{ asset($img['file']) }}"
                     alt="{{ $img['alt'] }}"
                     width="{{ $img['width'] }}" height="{{ $img['height'] }}"
                     loading="{{ $eager ? 'eager' : 'lazy' }}" decoding="async"
                     class="block h-full w-full object-cover object-top">
            @endif
        </div>
    </div>

    @if ($caption && $img)
        <figcaption @class(['mt-3 text-center text-xs', 'text-white/55' => $tone === 'dark', 'text-muted' => $tone !== 'dark'])>
            {{ $img['label'] }}
        </figcaption>
    @endif
</figure>
