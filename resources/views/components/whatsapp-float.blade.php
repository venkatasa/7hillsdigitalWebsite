@props([
    'message' => null,
])

@php
    /*
     | Floating click-to-chat button, bottom right.
     |
     | Vibrant Mint with a Deep Forest glyph, the same pairing as `btn-mint`.
     | The glyph is NOT white: white on mint is 2.43:1 and fails AA, while deep
     | forest on mint is 6.87:1. The WhatsApp mark itself is still unmistakable,
     | which is what does the recognising.
     |
     | The message is pre-typed so an enquiry arrives with some context instead
     | of "hi".
     */
    $url = \App\Support\Whatsapp::url(
        $message ?? 'Hi 7Hills Digital, I saw your website and would like to know more.'
    );
@endphp

@if ($url)
    {{-- Lifts clear of the cookie notice while that is showing, and steps
         aside while the mobile menu is open (it covered the last row). The
         aside makes it a landmark, so no content sits outside one. Its resting
         position is static CSS, not Alpine: bound only through :class, it sat
         at the top until the script ran and then jumped (CLS on every page). --}}
    <aside aria-label="WhatsApp" x-data
         class="wa-float fixed bottom-5 right-5 z-50 transition-all duration-300 print:hidden sm:bottom-6 sm:right-6"
         :class="$store.cookieNotice.open && 'wa-lift'">
        <a href="{{ $url }}"
           target="_blank"
           rel="noopener"
           aria-label="Chat to 7Hills Digital on WhatsApp"
           class="wa-fab group flex items-center gap-0 rounded-full bg-mint py-4 pl-4 pr-4 text-brand-dark shadow-lg shadow-brand/30 transition duration-300 hover:gap-2.5 hover:pr-5 hover:brightness-105 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent">
            <x-whatsapp-glyph class="h-7 w-7 shrink-0" />

            {{-- Widens into a label on hover, on pointer devices only. Never on
                 a phone, where it would sit over the content. --}}
            <span class="hidden max-w-0 overflow-hidden whitespace-nowrap text-sm font-semibold opacity-0 transition-all duration-300 group-hover:max-w-[12rem] group-hover:opacity-100 lg:inline">
                Chat to us
            </span>
        </a>
    </aside>
@endif
