@props([
    'name',
])

{{--
    Small line drawings for service lines and featured services. Engineering-drawing
    style: 1.5px strokes in currentColor, one detail picked out in the surface's
    accent (--motif-accent, set by the parent). No figures, no fake data.
--}}
<svg {{ $attributes->class(['shrink-0']) }} viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    @switch($name)
        @case('code')
            <rect x="6" y="12" width="52" height="40" rx="5"/>
            <path d="M6 21h52"/>
            <path d="M24 30l-6 6 6 6M40 30l6 6-6 6"/>
            <path d="M35 28l-6 16" stroke="var(--motif-accent)"/>
            @break
        @case('grid')
            <rect x="8" y="8" width="21" height="21" rx="4"/>
            <rect x="35" y="8" width="21" height="21" rx="4"/>
            <rect x="8" y="35" width="21" height="21" rx="4"/>
            <rect x="35" y="35" width="21" height="21" rx="4" stroke="var(--motif-accent)"/>
            <path d="M14 18h9M41 45h9M41 50h5" />
            @break
        @case('flow')
            <circle cx="12" cy="32" r="5"/>
            <rect x="26" y="25" width="14" height="14" rx="3" transform="rotate(45 33 32)" stroke="var(--motif-accent)"/>
            <circle cx="54" cy="16" r="5"/>
            <circle cx="54" cy="48" r="5"/>
            <path d="M17 32h7M40 27l9-8M40 37l9 8"/>
            @break
        @case('stack')
            <rect x="10" y="10" width="44" height="12" rx="3"/>
            <rect x="10" y="26" width="44" height="12" rx="3"/>
            <rect x="10" y="42" width="44" height="12" rx="3"/>
            <path d="M17 16h2M17 32h2M17 48h2"/>
            <path d="M42 48h6" stroke="var(--motif-accent)"/>
            @break
        @case('shield')
            <path d="M32 7l20 7v15c0 13-8.5 22-20 28C20.5 51 12 42 12 29V14z"/>
            <path d="M24 32l6 6 11-12" stroke="var(--motif-accent)"/>
            @break
        @case('pipeline')
            <rect x="6" y="10" width="14" height="44" rx="3"/>
            <rect x="25" y="10" width="14" height="44" rx="3"/>
            <rect x="44" y="10" width="14" height="44" rx="3"/>
            <rect x="9" y="15" width="8" height="6" rx="1.5"/>
            <rect x="9" y="25" width="8" height="6" rx="1.5"/>
            <rect x="28" y="15" width="8" height="6" rx="1.5" stroke="var(--motif-accent)"/>
            <rect x="47" y="15" width="8" height="6" rx="1.5"/>
            @break
        @case('browser')
            <rect x="6" y="10" width="52" height="44" rx="5"/>
            <path d="M6 19h52"/>
            <circle cx="12" cy="14.5" r="1"/><circle cx="16" cy="14.5" r="1"/>
            <rect x="12" y="25" width="22" height="14" rx="2" stroke="var(--motif-accent)"/>
            <path d="M40 26h12M40 32h12M12 46h40"/>
            @break
        @case('nodes')
            <circle cx="32" cy="32" r="7" stroke="var(--motif-accent)"/>
            <circle cx="10" cy="14" r="4"/><circle cx="54" cy="14" r="4"/>
            <circle cx="10" cy="50" r="4"/><circle cx="54" cy="50" r="4"/>
            <path d="M13 17l13 10M51 17L38 27M13 47l13-10M51 47L38 37"/>
            @break
        @case('pulse')
            <rect x="6" y="12" width="52" height="40" rx="5"/>
            <path d="M12 34h10l4-10 6 18 5-12 3 4h12" stroke="var(--motif-accent)"/>
            @break
        @case('scan')
            <path d="M18 8h20l10 10v38H18z"/>
            <path d="M38 8v10h10"/>
            <path d="M24 28h18M24 34h18M24 40h12"/>
            <path d="M10 46V56h10M54 46V56H44" stroke="var(--motif-accent)"/>
            @break
        @case('invoice')
            <path d="M16 6h32v52l-5-3-5 3-6-3-6 3-5-3-5 3z"/>
            <path d="M23 18h18M23 25h18M23 32h10"/>
            <path d="M33 44h8" stroke="var(--motif-accent)"/>
            @break
        @case('calendar')
            <rect x="8" y="12" width="48" height="42" rx="5"/>
            <path d="M8 22h48M20 8v8M44 8v8"/>
            <path d="M16 30h6M29 30h6M42 30h6M16 38h6M29 38h6M16 46h6"/>
            <rect x="40" y="36" width="10" height="10" rx="2" stroke="var(--motif-accent)"/>
            @break
        @case('refresh')
            <path d="M50 26A19 19 0 0 0 15 20M14 38a19 19 0 0 0 35 6"/>
            <path d="M15 11v9h9M49 53v-9h-9" stroke="var(--motif-accent)"/>
            @break
    @endswitch
</svg>
