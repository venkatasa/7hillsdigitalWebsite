{{-- The panel's brand lockup. Reuses the public site's mark so the back
     office and the front of house are visibly the same company. --}}
<span class="inline-flex items-center gap-2.5">
    <span class="inline-block h-7" style="color: {{ config('brand.colors.primary') }}">
        {!! str_replace('<svg', '<svg class="h-full w-auto"', file_get_contents(public_path(config('brand.logo.mark')))) !!}
    </span>
    <span class="leading-none">
        <span class="block text-sm font-bold tracking-tight" style="color: {{ config('brand.colors.primary') }}">Seven Hills</span>
        <span class="block text-[10px] font-semibold uppercase tracking-[0.2em]" style="color: {{ config('brand.colors.accent') }}">Digital</span>
    </span>
</span>
