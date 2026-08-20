<x-layouts.site title="Thank you" description="We have your enquiry and will come back to you within one working day.">

    <section class="container-page py-24 sm:py-32">
        <div class="mx-auto max-w-2xl text-center">
            <span class="mx-auto inline-flex h-16 w-16 items-center justify-center rounded-full bg-accent/15 text-accent">
                <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4.5 4.5L19 8"/>
                </svg>
            </span>

            <h1 class="mt-7 text-3xl font-bold tracking-tight text-brand sm:text-4xl">
                Got it. We will be in touch.
            </h1>

            <p class="mt-4 text-base leading-relaxed text-secondary">
                Someone will come back to you within one working day — usually sooner. If it
                is urgent, phone us on {{ config('brand.contact.phone') }} and skip the queue.
            </p>

            @if ($reference)
                <p class="mt-6 inline-block rounded-lg bg-brand-light px-5 py-3 text-sm text-secondary">
                    Your reference is
                    <span class="font-bold tracking-wide text-brand">{{ $reference }}</span>
                </p>
            @endif

            <div class="mt-10 flex flex-wrap justify-center gap-3">
                <a href="{{ route('packages.index') }}" class="btn-outline">Read about the packages</a>
                <a href="{{ route('home') }}" class="btn-brand">Back to the home page</a>
            </div>
        </div>
    </section>

</x-layouts.site>
