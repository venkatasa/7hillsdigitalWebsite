@props([
    'label' => null,
    'message' => null,
])

@php
    $url = \App\Support\Whatsapp::url($message);
@endphp

@if ($url)
    <a href="{{ $url }}" target="_blank" rel="noopener"
       {{ $attributes->merge(['class' => 'inline-flex items-center gap-2']) }}>
        <x-whatsapp-glyph class="h-4 w-4 shrink-0" />
        <span>{{ $label ?? \App\Support\Whatsapp::display() }}</span>
    </a>
@endif
