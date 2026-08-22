@props([
    'kind' => 'house',
    'seed' => 0,
    'label' => null,
    'demo' => null,
])

@php
    // A real photograph if the importer has run; otherwise the composed SVG
    // below, so a fresh checkout still renders properly.
    $photo = $demo ? \App\Support\DemoImages::url($demo, (int) $seed) : null;
@endphp

@if ($photo)
    <img src="{{ $photo }}"
         alt="{{ $label ?? '' }}"
         loading="lazy"
         decoding="async"
         {{ $attributes->merge(['class' => 'block h-full w-full object-cover']) }}>
@else

@php
    /*
     | Generated artwork for the demo tenants.
     |
     | The demos must look like real sites, and real sites have photographs —
     | but we will not scrape a property portal or a dealer's stock, and we load
     | nothing from a third party. So each card gets a composed SVG: a scenic
     | gradient plus a silhouette appropriate to the trade, varied by $seed so a
     | grid of them does not look like the same image repeated.
     |
     | Colours come from the tenant palette set on the demo layout, so the art
     | belongs to whichever demo it is rendered in.
     */
    $s = (int) $seed;
    $hueShift = ($s * 37) % 60 - 30;      // -30..29, tilts each card slightly
    $skyStop = 42 + (($s * 13) % 26);      // where the horizon sits
    $id = 'art'.$kind.$s;
@endphp

<svg viewBox="0 0 400 280" preserveAspectRatio="xMidYMid slice"
     role="img" aria-label="{{ $label ?? 'Illustration' }}"
     {{ $attributes->merge(['class' => 'block h-auto w-full']) }}>
    <defs>
        <linearGradient id="sky{{ $id }}" x1="0" y1="0" x2="{{ 0.3 + ($s % 4) / 10 }}" y2="1">
            <stop offset="0%" stop-color="var(--demo-primary)"/>
            <stop offset="100%" stop-color="var(--demo-accent)"/>
        </linearGradient>
        <linearGradient id="fade{{ $id }}" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#ffffff" stop-opacity="0"/>
            <stop offset="100%" stop-color="#000000" stop-opacity="0.35"/>
        </linearGradient>
    </defs>

    <rect width="400" height="280" fill="url(#sky{{ $id }})"/>

    {{-- Soft light source, moved per card. --}}
    <circle cx="{{ 90 + ($s * 53) % 240 }}" cy="{{ 50 + ($s * 29) % 40 }}" r="{{ 46 + ($s % 3) * 14 }}"
            fill="#ffffff" opacity="0.14"/>

    @switch($kind)
        @case('house')
            {{-- Suburban roofline. --}}
            <g fill="#ffffff" opacity="0.16">
                <path d="M0 {{ $skyStop + 150 }}h400v130H0z"/>
            </g>
            <g fill="#ffffff" opacity="0.30">
                <path d="M40 200l60-46 60 46v80H40z"/>
                <path d="M170 214l52-40 52 40v66h-104z"/>
                <path d="M290 192l58-44 58 44v88h-116z"/>
            </g>
            <g fill="var(--demo-primary)" opacity="0.55">
                <rect x="78" y="228" width="22" height="52" rx="2"/>
                <rect x="208" y="238" width="20" height="42" rx="2"/>
                <rect x="332" y="226" width="22" height="54" rx="2"/>
            </g>
            @break

        @case('car')
            {{-- Car in profile. --}}
            <g fill="#ffffff" opacity="0.12"><path d="M0 210h400v70H0z"/></g>
            <g fill="#ffffff" opacity="0.92" transform="translate({{ 30 + ($s % 3) * 10 }}, 0)">
                <path d="M64 196c-6-2-9-7-9-14l2-18c1-7 6-12 13-13l32-4 30-24c5-4 11-6 17-6h58c8 0 15 4 19 11l16 26 38 7c11 2 19 11 19 22v13c0 4-3 7-7 7H64z"/>
            </g>
            <g fill="var(--demo-primary)" opacity="0.45">
                <path d="M{{ 138 + ($s % 3) * 10 }} 152h44v-24h-38z"/>
                <path d="M{{ 192 + ($s % 3) * 10 }} 152h50l-14-24h-36z"/>
            </g>
            <g fill="var(--demo-primary)" opacity="0.9">
                <circle cx="{{ 130 + ($s % 3) * 10 }}" cy="199" r="24"/>
                <circle cx="{{ 268 + ($s % 3) * 10 }}" cy="199" r="24"/>
            </g>
            <g fill="#ffffff" opacity="0.85">
                <circle cx="{{ 130 + ($s % 3) * 10 }}" cy="199" r="10"/>
                <circle cx="{{ 268 + ($s % 3) * 10 }}" cy="199" r="10"/>
            </g>
            @break

        @case('room')
            {{-- Bed, window and a lamp. --}}
            <rect x="{{ 240 + ($s % 3) * 12 }}" y="46" width="104" height="76" rx="4" fill="#ffffff" opacity="0.28"/>
            <path d="M{{ 240 + ($s % 3) * 12 }} 84h104M{{ 292 + ($s % 3) * 12 }} 46v76" stroke="#ffffff" stroke-opacity="0.5" stroke-width="3"/>
            <g fill="#ffffff" opacity="0.9">
                <rect x="44" y="176" width="240" height="16" rx="6"/>
                <rect x="52" y="192" width="224" height="42" rx="6" opacity="0.75"/>
                <rect x="36" y="150" width="34" height="84" rx="6" opacity="0.6"/>
                <rect x="74" y="150" width="76" height="30" rx="10" opacity="0.85"/>
                <rect x="158" y="150" width="76" height="30" rx="10" opacity="0.85"/>
            </g>
            <g fill="var(--demo-primary)" opacity="0.5">
                <rect x="308" y="150" width="10" height="84" rx="4"/>
                <path d="M296 150h34l-8-26h-18z"/>
            </g>
            @break

        @case('job')
            {{-- Rising columns and a briefcase. --}}
            <g fill="#ffffff" opacity="0.22">
                <rect x="44" y="176" width="46" height="104" rx="4"/>
                <rect x="104" y="142" width="46" height="138" rx="4"/>
                <rect x="164" y="106" width="46" height="174" rx="4"/>
            </g>
            <g fill="#ffffff" opacity="0.92" transform="translate({{ 236 + ($s % 3) * 8 }}, 132)">
                <rect x="0" y="24" width="128" height="86" rx="10"/>
                <path d="M44 24v-12a10 10 0 0 1 10-10h20a10 10 0 0 1 10 10v12" fill="none" stroke="#ffffff" stroke-width="9"/>
            </g>
            <rect x="{{ 288 + ($s % 3) * 8 }}" y="{{ 132 + 58 }}" width="24" height="14" rx="4" fill="var(--demo-primary)" opacity="0.6"/>
            @break

        @default
            {{-- Abstract: layered planes for the generic services demo. --}}
            <g fill="#ffffff">
                <rect x="{{ 40 + ($s % 4) * 12 }}" y="112" width="150" height="150" rx="18" opacity="0.28"/>
                <rect x="{{ 130 + ($s % 4) * 10 }}" y="74" width="150" height="150" rx="18" opacity="0.36"/>
                <rect x="{{ 216 + ($s % 4) * 8 }}" y="120" width="130" height="130" rx="18" opacity="0.22"/>
            </g>
            <g stroke="#ffffff" stroke-opacity="0.5" stroke-width="3" fill="none">
                <path d="M{{ 70 + ($s % 4) * 12 }} 200h90M{{ 70 + ($s % 4) * 12 }} 224h56"/>
            </g>
    @endswitch

    <rect width="400" height="280" fill="url(#fade{{ $id }})"/>
</svg>
@endif
