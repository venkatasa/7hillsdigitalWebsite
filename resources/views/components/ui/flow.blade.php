@props([
    'steps',            // [[title, detail], …]
    'tone' => 'light',
])

{{--
    A process drawn as nodes and connectors. A soft pulse travels each connector
    (CSS only, off under reduced motion). It is an illustration, labelled as one,
    and carries no figures.
--}}
@php $dark = $tone === 'dark'; @endphp

<figure {{ $attributes->class(['relative']) }}>
    <ol class="grid gap-3 sm:grid-cols-[repeat(var(--n),minmax(0,1fr))] sm:gap-0" style="--n: {{ count($steps) }}">
        @foreach ($steps as $i => [$title, $detail])
            <li class="relative flex items-center gap-4 sm:flex-col sm:items-stretch sm:gap-0">
                <div class="flex items-center sm:mb-4">
                    <span @class([
                        'relative z-10 grid h-11 w-11 shrink-0 place-items-center rounded-full font-display text-sm font-extrabold',
                        'bg-mint text-brand-dark' => $dark && $loop->last,
                        'bg-accent text-white' => ! $dark && $loop->last,
                        'bg-white/[0.06] text-white ring-1 ring-white/15' => $dark && ! $loop->last,
                        'bg-white text-brand ring-1 ring-brand/15' => ! $dark && ! $loop->last,
                    ])>{{ $i + 1 }}</span>
                    @unless ($loop->last)
                        <span @class(['flow-link hidden h-px flex-1 sm:block', 'bg-white/15' => $dark, 'bg-brand/15' => ! $dark])
                              style="--flow-delay: {{ $i * 0.45 }}s" aria-hidden="true"></span>
                    @endunless
                </div>
                <div class="sm:pr-6">
                    <p @class(['font-display font-bold', 'text-white' => $dark, 'text-brand' => ! $dark])>{{ $title }}</p>
                    <p @class(['mt-1 text-sm leading-snug', 'text-white/55' => $dark, 'text-muted' => ! $dark])>{{ $detail }}</p>
                </div>
            </li>
        @endforeach
    </ol>
    <figcaption @class(['mt-6 text-[11px]', 'text-white/60' => $dark, 'text-muted' => ! $dark])>Illustration of a typical flow, not a product screen.</figcaption>
</figure>
