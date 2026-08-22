@props([
    'inverse' => false,
    'class' => 'h-11',
])

@php
    $brand = config('brand');
    // The official artwork from the brand kit. The composed SVG lockup below
    // is only a fallback for a checkout where the PNGs are missing.
    $artwork = $inverse ? $brand['logo']['inverse'] : $brand['logo']['primary'];
    $hasArtwork = file_exists(public_path($artwork));
@endphp

@if ($hasArtwork)
    <img src="{{ asset($artwork) }}"
         alt="{{ $brand['name'] }}"
         {{ $attributes->merge(['class' => $class.' w-auto']) }}>
@else
    <span {{ $attributes->merge(['class' => 'inline-flex items-center gap-2.5']) }}>
        <span class="{{ $inverse ? 'text-white/70' : 'text-brand/70' }} {{ $class }} inline-block">
            {!! str_replace('<svg', '<svg class="h-full w-auto"', file_get_contents(public_path($brand['logo']['mark']))) !!}
        </span>
        <span class="leading-none">
            <span class="block font-display text-[15px] font-extrabold tracking-tight {{ $inverse ? 'text-white' : 'text-brand' }}">
                7HILLS
            </span>
            <span class="block font-display text-[11px] font-bold uppercase tracking-[0.22em] text-accent">
                Digital
            </span>
        </span>
    </span>
@endif
