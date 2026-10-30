@props([
    'eyebrow' => null,
    'title',
    'lede' => null,
    'tone' => 'light',
    'align' => 'left',   // left | center | split (title left, lede right on lg)
    'level' => 'h2',
])

@php
    $dark = $tone === 'dark';
@endphp

<div {{ $attributes->class([
        'grid gap-6 lg:grid-cols-12 lg:items-end lg:gap-10' => $align === 'split',
        'mx-auto max-w-3xl text-center' => $align === 'center',
        'max-w-3xl' => $align === 'left',
    ]) }} data-reveal>
    <div @class(['lg:col-span-7' => $align === 'split'])>
        @if ($eyebrow)
            <p @class(['t-label', 'text-mint' => $dark, 'text-accent' => ! $dark])>{{ $eyebrow }}</p>
        @endif
        <{{ $level }} @class(['t-title mt-4', 'text-white' => $dark, 'text-brand' => ! $dark])>{{ $title }}</{{ $level }}>
    </div>

    @if ($lede)
        <p @class([
            't-lede',
            'mt-5' => $align !== 'split',
            'lg:col-span-5 lg:pb-1' => $align === 'split',
            'mx-auto max-w-2xl' => $align === 'center',
            'text-white/70' => $dark,
            'text-secondary' => ! $dark,
        ])>{{ $lede }}</p>
    @endif
</div>
