@props([
    'proof',   // ['heading', 'body', 'image'|'flow', 'client'?, 'url'?]
])

{{-- Proof, only if real. A page with nothing to show leaves this out rather than
     padding it: see config/clients.php and public/images/product/manifest.json. --}}
<section id="proof" class="band-night on-dark section-v2">
    <div class="contours absolute inset-0 -z-10 opacity-[0.08]" aria-hidden="true"></div>
    <div class="container-wide grid items-center gap-12 lg:grid-cols-12">
        <div class="lg:col-span-5">
            <p class="t-label text-mint">Proof, not promises</p>
            <h2 class="t-title mt-4 text-white">{{ $proof['heading'] }}</h2>
            <p class="t-lede mt-5 text-white/70">{{ $proof['body'] }}</p>
            @if (! empty($proof['client']) && ($client = config('clients.'.$proof['client'])))
                <div class="mt-8 border-t border-white/10 pt-6">
                    <p class="font-display text-lg font-bold text-white">{{ $client['name'] }}</p>
                    <p class="mt-1 text-sm text-white/60">{{ $client['software'] }}</p>
                    @if (! empty($client['url']))
                        <a href="{{ $client['url'] }}" target="_blank" rel="noopener" class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-mint hover:brightness-110">Visit the live site <span aria-hidden="true">&nearr;</span></a>
                    @endif
                </div>
            @endif
        </div>
        <div class="lg:col-span-7">
            @if (! empty($proof['image']))
                <x-ui.frame-browser :image="$proof['image']" :url="$proof['url'] ?? 'yourbusiness.co.za/admin'" />
            @elseif (! empty($proof['flow']))
                <div class="tile-dark p-6 sm:p-9"><x-ui.flow tone="dark" :steps="$proof['flow']" /></div>
            @endif
        </div>
    </div>
</section>
