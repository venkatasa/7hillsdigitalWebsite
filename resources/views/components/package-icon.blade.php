@props(['name' => 'globe'])

@php
    // Stroke paths only — the wrapper supplies size and colour.
    $paths = [
        'home' => '<path d="M3 10.5 12 3l9 7.5"/><path d="M5.5 9.5V20h13V9.5"/><path d="M10 20v-5h4v5"/>',
        'car' => '<path d="M4.5 15.5h15"/><path d="M6 15.5v2.2a.8.8 0 0 1-.8.8H4.3a.8.8 0 0 1-.8-.8v-2.2"/><path d="M20.5 15.5v2.2a.8.8 0 0 1-.8.8h-.9a.8.8 0 0 1-.8-.8v-2.2"/><path d="M3.5 15.5v-3l2-4.6A2 2 0 0 1 7.3 6.6h9.4a2 2 0 0 1 1.8 1.3l2 4.6v3"/><path d="M5.5 11.5h13"/><circle cx="7.5" cy="13.5" r=".6"/><circle cx="16.5" cy="13.5" r=".6"/>',
        'briefcase' => '<rect x="3" y="7.5" width="18" height="12" rx="2"/><path d="M9 7.5V6a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v1.5"/><path d="M3 12.5h18"/>',
        'bed' => '<path d="M3 18V7"/><path d="M3 12h18a0 0 0 0 1 0 0v6"/><path d="M3 18h18"/><path d="M7.5 12V9.5h4a2 2 0 0 1 2 2V12"/>',
        'globe' => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18"/><path d="M12 3a14 14 0 0 1 0 18a14 14 0 0 1 0-18Z"/>',
        // Add-on modules.
        'invoice' => '<path d="M6 3h12a1 1 0 0 1 1 1v16l-2.5-1.5L14 20l-2-1.5L10 20l-2.5-1.5L5 20V4a1 1 0 0 1 1-1Z"/><path d="M9 8h6"/><path d="M9 12h6"/>',
        'people' => '<circle cx="9" cy="8" r="3"/><path d="M3.5 19a5.5 5.5 0 0 1 11 0"/><path d="M16 5.5a3 3 0 0 1 0 5"/><path d="M17.5 14.5a5.5 5.5 0 0 1 3 4.5"/>',
        'boxes' => '<path d="M3.5 7.5 12 3l8.5 4.5v9L12 21l-8.5-4.5v-9Z"/><path d="m3.5 7.5 8.5 4.5 8.5-4.5"/><path d="M12 12v9"/>',
        'module' => '<rect x="3" y="3" width="7.5" height="7.5" rx="1.5"/><rect x="13.5" y="3" width="7.5" height="7.5" rx="1.5"/><rect x="3" y="13.5" width="7.5" height="7.5" rx="1.5"/><rect x="13.5" y="13.5" width="7.5" height="7.5" rx="1.5"/>',
    ];
@endphp

<svg {{ $attributes->merge(['class' => 'h-6 w-6']) }} viewBox="0 0 24 24" fill="none"
     stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"
     aria-hidden="true">
    {!! $paths[$name] ?? $paths['globe'] !!}
</svg>
