@props([
    'quote',
    'heading',
    'cta' => 'Get a quote',
    'context',          // named in the pre-typed WhatsApp message
    'ctaName' => 'close-quote',
    'links' => null,
])

{{-- The closing call to action shared by solution, industry and work pages. --}}
<section class="band-night on-dark">
    <div class="contours absolute inset-0 -z-10 opacity-[0.12]" aria-hidden="true"></div>
    <div class="container-wide py-20 sm:py-24">
        <div class="grid gap-10 lg:grid-cols-12 lg:items-end">
            <div class="lg:col-span-8">
                <h2 class="t-display text-white">{{ $heading }}</h2>
                <p class="t-lede mt-6 max-w-2xl text-white/70">We call you back to understand it properly, then send a scope and price in writing.</p>
            </div>
            <div class="flex flex-col gap-4 lg:col-span-4 lg:items-end">
                <x-ui.button :href="$quote" tone="dark" size="lg" arrow :data-cta="$ctaName">{{ $cta }}</x-ui.button>
                <x-whatsapp-link label="Or WhatsApp us" :message="'Hi 7Hills Digital, I\'m on your '.$context.' page and would like to talk.'" class="text-sm font-semibold text-mint hover:brightness-110" />
            </div>
        </div>

        {{ $links }}
    </div>
</section>
