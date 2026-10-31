{{--
    /solutions — the business problems we solve, as a ledger: the symptom in the
    buyer's words, the outcome, and the services behind it. A gated solution is
    simply absent where its service may not render.
--}}
@php
    use App\Support\Revamp;

    $quote = \App\Support\Quote::url('solutions-hub');
@endphp

<x-ui.layout overlay title="Business Problems We Solve"
    description="Spreadsheets, lost leads, late invoices, paper approvals and systems that do not talk: the problems South African businesses bring us, and how we fix them.">

    <section id="hero" class="band-night on-dark -mt-[77px] pt-[77px]">
        <div class="contours absolute inset-y-0 right-0 -z-10 w-full opacity-[0.13] lg:w-[75%]" aria-hidden="true"></div>
        <div class="container-wide pb-16 pt-10 sm:pt-14 lg:pb-24 lg:pt-16">
            <p class="inline-flex items-center gap-2.5 text-xs font-semibold uppercase tracking-[0.16em] text-mint">
                <span class="h-px w-6 bg-mint/70" aria-hidden="true"></span>Solutions
            </p>
            <h1 class="t-display mt-5 max-w-4xl text-white">Start with what is costing you money. We will work out what to build.</h1>
            <p class="t-lede mt-6 max-w-2xl text-white/70">Nobody wakes up wanting an API. They want to stop retyping orders. These are the problems businesses bring us most often, each with the services that fix it.</p>
        </div>
    </section>

    <section class="band-paper section-v2">
        <div class="container-wide">
            <ol class="border-t border-brand/15">
                @foreach ($solutions as $slug => $s)
                    @php $services = Revamp::services($s['services']); @endphp
                    <li class="grid gap-x-10 gap-y-5 border-b border-hairline py-10 lg:grid-cols-12 lg:py-12" data-reveal>
                        <div class="flex gap-5 lg:col-span-6">
                            <span class="t-index pt-2 text-accent">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <div>
                                @if ($s['preview'])
                                    <p class="mb-3 inline-flex rounded-full border border-dashed border-brand/30 px-2.5 py-0.5 text-xs font-semibold text-muted">Preview only</p>
                                @endif
                                <h2 class="t-subtitle text-brand">
                                    <a href="{{ route('solutions.show', $slug) }}" class="hover:text-accent">{{ $s['problem'] }}</a>
                                </h2>
                                <p class="mt-2 text-lg text-secondary">{{ $s['outcome'] }}.</p>
                            </div>
                        </div>
                        <div class="pl-11 lg:col-span-4 lg:pl-0">
                            <p class="text-xs font-semibold uppercase tracking-wider text-muted">Fixed with</p>
                            <ul class="mt-3 space-y-1.5">
                                @foreach ($services as $service)
                                    <li><a href="{{ $service['href'] }}" class="text-sm font-semibold text-brand hover:text-accent">{{ $service['name'] }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="pl-11 lg:col-span-2 lg:pl-0 lg:text-right">
                            <a href="{{ route('solutions.show', $slug) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-accent hover:text-brand" aria-label="Read more: {{ $s['problem'] }}">Read more <span aria-hidden="true">&rarr;</span></a>
                        </div>
                    </li>
                @endforeach
            </ol>

            <p class="mt-12 max-w-2xl text-lg leading-relaxed text-secondary">
                <span class="font-display font-bold text-brand">Your problem is not on the list?</span>
                These are the common ones, not the only ones. <a href="{{ $quote }}" class="font-semibold text-accent hover:text-brand">Describe yours &rarr;</a>
            </p>
        </div>
    </section>

    <x-page.close :quote="$quote" heading="Tell us what is slowing you down." cta="Describe the problem" context="Solutions" cta-name="solutions-close-quote" />
</x-ui.layout>
