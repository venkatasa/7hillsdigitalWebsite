@props([
    'status',
    'tone' => 'light',
])

@php
    /*
     | Availability is text, never colour alone. A preview badge only ever
     | renders on staging — production never receives an unconfirmed offering.
     */
    $label = \App\Support\Revamp::AVAILABILITY[$status] ?? $status;
    $dark = $tone === 'dark';

    $classes = match ($status) {
        'available' => $dark ? 'bg-mint/15 text-mint ring-mint/30' : 'bg-accent/10 text-accent ring-accent/25',
        'built_to_order' => $dark ? 'bg-white/5 text-white/80 ring-white/15' : 'bg-white text-brand ring-brand/15',
        'in_development' => $dark ? 'bg-white/5 text-white/60 ring-white/10' : 'bg-brand-light text-muted ring-hairline',
        default => $dark ? 'border border-dashed border-white/40 text-white/75' : 'border border-dashed border-brand/40 text-secondary',
    };
@endphp

<span {{ $attributes->class(['inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold', 'ring-1 ring-inset' => $status !== 'capability_unconfirmed', $classes]) }}>
    <span @class([
        'h-1.5 w-1.5 rounded-full',
        'bg-current' => $status !== 'capability_unconfirmed',
        'border border-current' => $status === 'capability_unconfirmed',
    ]) aria-hidden="true"></span>
    {{ $label }}
</span>
