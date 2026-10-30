@props([
    'name',            // flow | hub | layers | chart | doc | bars
    'tone' => 'dark',
    'label' => true,
])

@php
    /*
     | Diagrams explain HOW something works; they never pose as a product
     | screen. Every one carries an "Illustration" label, and none shows
     | figures that could be mistaken for real results.
     */
    $dark = $tone === 'dark';
    $node = $dark ? 'fill-white/[0.04] stroke-white/15' : 'fill-white stroke-brand/15';
    $nodeHi = $dark ? 'fill-mint/10 stroke-mint/60' : 'fill-accent/[0.07] stroke-accent/60';
    $text = $dark ? 'fill-white/75' : 'fill-brand';
    $muted = $dark ? 'fill-white/40' : 'fill-muted';
    $line = $dark ? 'stroke-white/20' : 'stroke-brand/20';
    $hi = $dark ? 'stroke-mint' : 'stroke-accent';
    $hiFill = $dark ? 'fill-mint' : 'fill-accent';
@endphp

<figure {{ $attributes->class(['relative']) }}>
    @switch($name)
        @case('flow')
            <svg viewBox="0 0 520 300" class="block h-full w-full" role="img" aria-label="Illustration: a request moves from submission to approval, notification and filing, each step recorded.">
                <g class="font-sans" font-size="13">
                    @foreach ([[20, 'Request', 'submitted'], [145, 'Approve', 'manager'], [270, 'Notify', 'finance'], [395, 'File', 'logged']] as $i => [$x, $t, $s])
                        <rect x="{{ $x }}" y="104" width="105" height="92" rx="14" class="{{ $i === 1 ? $nodeHi : $node }}" stroke-width="1.25"/>
                        <text x="{{ $x + 16 }}" y="142" class="{{ $text }}" font-weight="700">{{ $t }}</text>
                        <text x="{{ $x + 16 }}" y="164" class="{{ $muted }}" font-size="12">{{ $s }}</text>
                        @if ($i < 3)
                            <path d="M{{ $x + 109 }} 150h12" class="{{ $hi }}" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M{{ $x + 117 }} 145l5 5-5 5" class="{{ $hi }}" fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        @endif
                    @endforeach
                    <path d="M72 230v24h376v-24" class="{{ $line }}" fill="none" stroke-width="1.25" stroke-dasharray="3 5"/>
                    <text x="260" y="276" text-anchor="middle" class="{{ $muted }}" font-size="12">every step recorded: who, what, when</text>
                    <circle cx="197" cy="88" r="11" class="{{ $hiFill }}"/>
                    <path d="M192 88l3.5 3.5 6-7" stroke-width="1.75" fill="none" stroke-linecap="round" stroke-linejoin="round" class="stroke-brand-dark"/>
                </g>
            </svg>
            @break

        @case('hub')
            <svg viewBox="0 0 520 300" class="block h-full w-full" role="img" aria-label="Illustration: website, back office, accounting, email and documents all connected through one integration layer.">
                <g class="font-sans" font-size="13">
                    @php $spokes = [[60, 50, 'Website'], [400, 50, 'Back office'], [20, 205, 'Accounting'], [430, 205, 'Email'], [220, 250, 'Documents']]; @endphp
                    @foreach ($spokes as [$x, $y, $t])
                        <path d="M{{ $x + 45 }} {{ $y + 18 }}L260 150" class="{{ $line }}" stroke-width="1.25"/>
                    @endforeach
                    @foreach ($spokes as [$x, $y, $t])
                        <rect x="{{ $x }}" y="{{ $y }}" width="90" height="36" rx="10" class="{{ $node }}" stroke-width="1.25"/>
                        <text x="{{ $x + 45 }}" y="{{ $y + 23 }}" text-anchor="middle" class="{{ $text }}" font-weight="600" font-size="12">{{ $t }}</text>
                    @endforeach
                    <rect x="175" y="118" width="170" height="64" rx="16" class="{{ $nodeHi }}" stroke-width="1.5"/>
                    <text x="260" y="146" text-anchor="middle" class="{{ $text }}" font-weight="700">Integration layer</text>
                    <text x="260" y="166" text-anchor="middle" class="{{ $muted }}" font-size="12">enter once, use everywhere</text>
                </g>
            </svg>
            @break

        @case('layers')
            <svg viewBox="0 0 520 300" class="block h-full w-full" role="img" aria-label="Illustration: people review what an AI step drafts, and the AI step works only from the business's own data.">
                <g class="font-sans" font-size="13">
                    @foreach ([[30, 'Your people', 'review, approve, decide', false], [120, 'AI step', 'drafts · summarises · classifies', true], [210, 'Your data', 'records you already keep', false]] as [$y, $t, $s, $h])
                        <rect x="70" y="{{ $y }}" width="380" height="62" rx="14" class="{{ $h ? $nodeHi : $node }}" stroke-width="1.25"/>
                        <text x="94" y="{{ $y + 28 }}" class="{{ $text }}" font-weight="700">{{ $t }}</text>
                        <text x="94" y="{{ $y + 47 }}" class="{{ $muted }}" font-size="12">{{ $s }}</text>
                    @endforeach
                    <path d="M260 96v20M260 186v20" class="{{ $hi }}" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M255 101l5-5 5 5M255 191l5-5 5 5" class="{{ $hi }}" fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </g>
            </svg>
            @break

        @case('chart')
            <svg viewBox="0 0 520 300" class="block h-full w-full" role="img" aria-label="Illustration: a reporting dashboard drawing bars and a trend line from operational data. No real figures.">
                <g class="font-sans">
                    <rect x="20" y="20" width="480" height="260" rx="16" class="{{ $node }}" stroke-width="1.25"/>
                    @foreach ([[44, 'Sales'], [184, 'Cash'], [324, 'Operations']] as [$x, $t])
                        <rect x="{{ $x }}" y="42" width="128" height="44" rx="10" class="{{ $node }}" stroke-width="1"/>
                        <text x="{{ $x + 14 }}" y="69" class="{{ $muted }}" font-size="12">{{ $t }}</text>
                    @endforeach
                    @foreach ([118, 96, 132, 84, 150, 122, 160, 140] as $i => $h)
                        <rect x="{{ 60 + $i * 52 }}" y="{{ 262 - $h }}" width="26" height="{{ $h }}" rx="5" class="{{ $i === 6 ? $hiFill : ($dark ? 'fill-white/[0.12]' : 'fill-brand/10') }}"/>
                    @endforeach
                    <path d="M73 172C125 160 160 182 210 158s100-20 150-40 80-10 110-30" class="{{ $hi }}" fill="none" stroke-width="2" stroke-linecap="round"/>
                </g>
            </svg>
            @break

        @case('doc')
            <svg viewBox="0 0 220 150" class="block h-full w-full" role="img" aria-label="Illustration: a document with a signature line and an approval mark.">
                <rect x="40" y="14" width="104" height="124" rx="10" class="{{ $node }}" stroke-width="1.25"/>
                @foreach ([38, 52, 66, 80] as $i => $y)
                    <rect x="56" y="{{ $y }}" width="{{ [72, 60, 68, 44][$i] }}" height="5" rx="2.5" class="{{ $dark ? 'fill-white/15' : 'fill-brand/12' }}"/>
                @endforeach
                <path d="M56 112c10-8 16 6 24-2s12-6 18 0" class="{{ $line }}" fill="none" stroke-width="1.5" stroke-linecap="round"/>
                <circle cx="150" cy="104" r="26" class="{{ $nodeHi }}" stroke-width="1.5"/>
                <path d="M139 104l7 7 14-15" class="{{ $hi }}" fill="none" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            @break

        @case('steps')
            <svg viewBox="0 0 220 150" class="block h-full w-full" role="img" aria-label="Illustration: three steps that run one after another without anyone pushing them.">
                @foreach ([[14, false], [82, false], [150, true]] as $i => [$x, $done])
                    <rect x="{{ $x }}" y="52" width="56" height="46" rx="10" class="{{ $done ? $nodeHi : $node }}" stroke-width="1.25"/>
                    <rect x="{{ $x + 12 }}" y="68" width="{{ $done ? 20 : 32 }}" height="5" rx="2.5" class="{{ $dark ? 'fill-white/20' : 'fill-brand/15' }}"/>
                    <rect x="{{ $x + 12 }}" y="79" width="22" height="5" rx="2.5" class="{{ $dark ? 'fill-white/12' : 'fill-brand/10' }}"/>
                    @if ($i < 2)
                        <path d="M{{ $x + 59 }} 75h8m-3-4l4 4-4 4" class="{{ $hi }}" fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    @endif
                @endforeach
                <circle cx="198" cy="52" r="10" class="{{ $hiFill }}"/>
                <path d="M193.5 52l3 3 5-6" class="stroke-brand-dark" fill="none" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M42 112c0 16 136 16 136 0" class="{{ $line }}" fill="none" stroke-width="1.25" stroke-dasharray="3 4"/>
            </svg>
            @break

        @case('bars')
            <svg viewBox="0 0 220 150" class="block h-full w-full" role="img" aria-label="Illustration: a small bar chart with a highlighted bar. No real figures.">
                @foreach ([52, 74, 60, 96, 82, 112] as $i => $h)
                    <rect x="{{ 34 + $i * 28 }}" y="{{ 132 - $h }}" width="16" height="{{ $h }}" rx="4" class="{{ $i === 5 ? $hiFill : ($dark ? 'fill-white/[0.12]' : 'fill-brand/10') }}"/>
                @endforeach
                <path d="M30 133h170" class="{{ $line }}" stroke-width="1.25"/>
            </svg>
            @break
    @endswitch

    @if ($label)
        <figcaption @class(['mt-2 t-label !text-[0.62rem]', 'text-white/55' => $dark, 'text-muted' => ! $dark])>Illustration</figcaption>
    @endif
</figure>
