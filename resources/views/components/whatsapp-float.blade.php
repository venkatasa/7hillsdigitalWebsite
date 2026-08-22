@props([
    'message' => null,
])

@php
    /*
     | Floating click-to-chat button, bottom right.
     |
     | The colour is WhatsApp's own #25D366 rather than a 7Hills green. That is
     | a deliberate exception to the palette rule: this is a third-party mark,
     | and the whole value of the button is that people recognise it without
     | reading it. Our brand governs our own expression, not somebody else's logo.
     |
     | The message is pre-typed so an enquiry arrives with some context instead
     | of "hi".
     */
    $url = \App\Support\Whatsapp::url(
        $message ?? 'Hi 7Hills Digital, I saw your website and would like to know more.'
    );
@endphp

@if ($url)
    <div class="fixed bottom-5 right-5 z-50 print:hidden sm:bottom-6 sm:right-6">
        <a href="{{ $url }}"
           target="_blank"
           rel="noopener"
           aria-label="Chat to 7Hills Digital on WhatsApp"
           class="wa-fab group flex items-center gap-0 rounded-full bg-[#25D366] py-4 pl-4 pr-4 text-white shadow-lg shadow-black/25 transition duration-300 hover:gap-2.5 hover:pr-5 hover:brightness-105 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#25D366]">
            <x-whatsapp-glyph class="h-7 w-7 shrink-0" />

            {{-- Widens into a label on hover, on pointer devices only. Never on
                 a phone, where it would sit over the content. --}}
            <span class="hidden max-w-0 overflow-hidden whitespace-nowrap text-sm font-semibold opacity-0 transition-all duration-300 group-hover:max-w-[12rem] group-hover:opacity-100 lg:inline">
                Chat to us
            </span>
        </a>
    </div>
@endif
