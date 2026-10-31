{{--
    After /quote. No response-time promise until the owner sets one (Q4); the
    reply is promised by the channel the prospect chose, which we can keep.
--}}
@php
    $brand = config('brand');
    $channel = [
        'phone' => 'by phone',
        'email' => 'by email',
        'whatsapp' => 'on WhatsApp',
    ][$preferred] ?? 'the way you asked us to';
@endphp

<x-ui.layout title="Thank you" description="We have your project enquiry.">
    <x-slot:head><meta name="robots" content="noindex"></x-slot:head>

    <section class="band-paper section-v2">
        <div class="container-wide max-w-3xl">
            <span class="inline-flex h-14 w-14 items-center justify-center rounded-full bg-accent/15 text-accent">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4.5 4.5L19 8"/>
                </svg>
            </span>

            <h1 class="t-display mt-7 text-brand">Thank you. We have it.</h1>
            <p class="t-lede mt-5 text-secondary">
                Someone who would do the work will read it and come back to you {{ $channel }}.
                If it cannot wait, phone us on
                <a href="tel:{{ preg_replace('/\D+/', '', $brand['contact']['phone']) }}" class="font-semibold text-brand underline underline-offset-4 hover:text-accent">{{ $brand['contact']['phone'] }}</a>.
            </p>

            @if ($reference)
                <p class="mt-8 inline-flex items-center gap-3 rounded-xl border border-hairline bg-white px-5 py-3 text-sm text-secondary">
                    Your reference
                    <span class="t-index font-bold tracking-wide text-brand">{{ $reference }}</span>
                </p>
            @endif

            <div class="mt-12 border-t border-hairline pt-8">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-muted">While you wait</h2>
                <ul class="mt-4 flex flex-col gap-3 sm:flex-row sm:gap-8">
                    <li><a href="{{ route('work') }}" class="font-semibold text-accent hover:text-brand">See work that is running today &rarr;</a></li>
                    <li><a href="{{ route('solutions.index') }}" class="font-semibold text-accent hover:text-brand">Problems we solve &rarr;</a></li>
                    <li><a href="{{ route('home') }}" class="font-semibold text-accent hover:text-brand">Back to the home page</a></li>
                </ul>
            </div>
        </div>
    </section>
</x-ui.layout>
