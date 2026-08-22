{{-- The panel's brand lockup — the official artwork, so the back office and
     the front of house are visibly the same company. --}}
@php
    $logo = config('brand.logo.primary');
    $hasArtwork = file_exists(public_path($logo));
@endphp

@if ($hasArtwork)
    <img src="{{ asset($logo) }}" alt="{{ config('brand.name') }}" class="h-11 w-auto dark:hidden">
    <img src="{{ asset(config('brand.logo.inverse')) }}" alt="{{ config('brand.name') }}" class="hidden h-11 w-auto dark:block">
@else
    <span class="inline-flex items-center gap-2.5">
        <span class="inline-block h-7" style="color: {{ config('brand.colors.primary') }}">
            {!! str_replace('<svg', '<svg class="h-full w-auto"', file_get_contents(public_path(config('brand.logo.mark')))) !!}
        </span>
        <span class="leading-none">
            <span class="block text-sm font-bold tracking-tight" style="color: {{ config('brand.colors.primary') }}">7HILLS</span>
            <span class="block text-[10px] font-semibold uppercase tracking-[0.2em]" style="color: {{ config('brand.colors.accent') }}">Digital</span>
        </span>
    </span>
@endif
