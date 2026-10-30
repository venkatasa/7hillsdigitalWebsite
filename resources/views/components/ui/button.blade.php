@props([
    'href',
    'variant' => 'primary',   // primary | secondary | ghost
    'tone' => 'light',        // the surface the button sits on
    'size' => 'md',
    'arrow' => false,
])

@php
    /*
     | The accent follows the surface, so the CLAUDE.md rule (emerald on light,
     | mint on dark) cannot be broken by picking the wrong class by hand.
     */
    $styles = [
        'light' => [
            'primary' => 'bg-accent text-white shadow-[0_8px_20px_-10px_var(--brand-accent)] hover:brightness-110',
            'secondary' => 'border border-brand/15 bg-white text-brand hover:border-accent/50 hover:text-accent',
            'ghost' => 'text-brand hover:text-accent',
        ],
        'dark' => [
            'primary' => 'bg-mint text-brand-dark shadow-[0_10px_28px_-12px_var(--brand-mint)] hover:brightness-105',
            'secondary' => 'border border-white/20 bg-white/[0.04] text-white backdrop-blur hover:border-white/40 hover:bg-white/[0.08]',
            'ghost' => 'text-white/80 hover:text-white',
        ],
    ];
@endphp

<a href="{{ $href }}"
   {{ $attributes->class([
        'btn-v2',
        'btn-v2-lg' => $size === 'lg',
        $styles[$tone][$variant],
   ]) }}>
    <span>{{ $slot }}</span>
    @if ($arrow)
        <svg class="btn-arrow h-4 w-4" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M3 8h10M9 4l4 4-4 4"/>
        </svg>
    @endif
</a>
