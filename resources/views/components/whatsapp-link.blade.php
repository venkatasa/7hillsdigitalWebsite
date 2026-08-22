@props(['label' => null])

@php
    /*
     | Click-to-chat. wa.me needs the number in international form with no
     | punctuation, so a locally-formatted "068 859 6695" is normalised here
     | rather than being stored twice in config and drifting apart.
     */
    $display = config('brand.contact.whatsapp');
    $digits = preg_replace('/\D+/', '', (string) $display);

    if (str_starts_with($digits, '0')) {
        $digits = '27'.substr($digits, 1);
    }
@endphp

@if ($digits)
    <a href="https://wa.me/{{ $digits }}"
       target="_blank"
       rel="noopener"
       {{ $attributes->merge(['class' => 'inline-flex items-center gap-2']) }}>
        <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M12.04 2c-5.5 0-9.96 4.46-9.96 9.96 0 1.76.46 3.48 1.34 5L2 22l5.16-1.35a9.92 9.92 0 0 0 4.88 1.25h.01c5.5 0 9.96-4.46 9.96-9.96 0-2.66-1.04-5.16-2.92-7.04A9.9 9.9 0 0 0 12.04 2Zm0 18.15h-.01a8.26 8.26 0 0 1-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.24 8.24 0 0 1-1.26-4.4c0-4.56 3.71-8.27 8.27-8.27a8.2 8.2 0 0 1 5.84 2.42 8.2 8.2 0 0 1 2.42 5.85c0 4.57-3.71 8.26-8.27 8.26Zm4.53-6.19c-.25-.13-1.47-.72-1.69-.8-.23-.09-.39-.13-.56.12-.16.25-.64.8-.78.97-.15.16-.29.18-.53.06-.25-.13-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.01-.38.11-.5.11-.11.25-.29.37-.44.13-.15.17-.25.25-.42.09-.16.04-.31-.02-.43-.06-.13-.56-1.35-.77-1.84-.2-.48-.4-.42-.55-.43h-.47c-.16 0-.43.06-.65.31-.22.25-.85.83-.85 2.03s.87 2.35.99 2.51c.12.17 1.71 2.61 4.15 3.66.58.25 1.03.4 1.39.51.58.19 1.11.16 1.53.1.47-.07 1.47-.6 1.67-1.18.21-.58.21-1.07.15-1.18-.06-.11-.22-.17-.47-.29Z"/>
        </svg>
        <span>{{ $label ?? $display }}</span>
    </a>
@endif
