@props([
    'inverse' => false,
    'class' => 'h-9',
])

@php
    $brand = config('brand');
    // If real artwork has been dropped into public/images/brand/, it replaces
    // the composed lockup wholesale.
    $artwork = $inverse ? $brand['logo']['inverse'] : $brand['logo']['primary'];
    $hasArtwork = file_exists(public_path($artwork));
@endphp

@if ($hasArtwork)
    <img src="{{ asset($artwork) }}" alt="{{ $brand['name'] }}" {{ $attributes->merge(['class' => $class.' w-auto']) }}>
@else
    <span {{ $attributes->merge(['class' => 'inline-flex items-center gap-2.5']) }}>
        <span class="{{ $inverse ? 'text-white/70' : 'text-brand/70' }} {{ $class }} inline-block">
            {!! str_replace('<svg', '<svg class="h-full w-auto"', file_get_contents(public_path($brand['logo']['mark']))) !!}
        </span>
        <span class="leading-none">
            <span class="block text-[15px] font-bold tracking-tight {{ $inverse ? 'text-white' : 'text-brand' }}">
                Seven Hills
            </span>
            <span class="block text-[11px] font-semibold uppercase tracking-[0.22em] text-accent">
                Digital
            </span>
        </span>
    </span>
@endif
