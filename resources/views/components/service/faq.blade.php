@props([
    'items',   // [[question, answer], …]
])

{{-- Native <details>: opens with Enter or Space, needs no JavaScript, and is read
     correctly by screen readers. --}}
<div {{ $attributes->class(['divide-y divide-hairline border-y border-hairline']) }}>
    @foreach ($items as [$question, $answer])
        <details class="faq group py-1" @if ($loop->first) open @endif>
            <summary class="flex cursor-pointer list-none items-start justify-between gap-6 rounded-lg py-5 font-display text-lg font-bold text-brand [&::-webkit-details-marker]:hidden">
                {{ $question }}
                <svg class="faq-icon mt-1 h-4 w-4 shrink-0 text-accent" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path d="M8 3v10M3 8h10" stroke-linecap="round"/></svg>
            </summary>
            <div class="faq-body max-w-2xl pb-6 leading-relaxed text-secondary">{{ $answer }}</div>
        </details>
    @endforeach
</div>
