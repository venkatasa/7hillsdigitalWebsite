@props([
    'title' => null,
    'description' => null,
    'overlay' => false,   // true when the page opens on a night (dark) hero
])

@php
    $brand = config('brand');
    $pageTitle = $title ? $title.' — '.$brand['name'] : $brand['name'].' — Business software, platforms and enterprise development';
    $pageDescription = $description ?? 'Ready-to-use business platforms, business solutions and custom software — built, hosted and supported by one team in '.$brand['contact']['city'].', South Africa.';
@endphp

<!DOCTYPE html>
<html lang="en-ZA" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">

    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="{{ $brand['name'] }}">
    <meta property="og:image" content="{{ asset($brand['logo']['badge']) }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="theme-color" content="{{ $brand['colors']['dark'] }}">

    <link rel="icon" href="{{ asset($brand['logo']['favicon']) }}" sizes="any">
    <link rel="apple-touch-icon" href="{{ asset($brand['logo']['badge']) }}">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Brand palette from config/brand.php — the single source of truth. --}}
    <style>
        :root {
        @foreach ($brand['colors'] as $token => $value)
            --brand-{{ $token }}: {{ $value }};
        @endforeach
        }
    </style>

    {{ Vite::fonts() }}

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Without JavaScript, tabbed content renders in sequence rather than hiding. --}}
    <noscript><style>[data-reveal] { opacity: 1 !important; transform: none !important; } .js-panel { display: block !important; } .js-panel + .js-panel { margin-top: 3rem; } .js-only { display: none !important; } .nojs-show { display: block !important; }</style></noscript>

    {{ $head ?? '' }}
</head>
<body class="flex min-h-screen flex-col bg-white">
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[60] focus:rounded-lg focus:bg-brand focus:px-4 focus:py-2 focus:text-white">
        Skip to content
    </a>

    <x-ui.header :overlay="$overlay" />

    <main id="main" class="flex-1">
        {{ $slot }}
    </main>

    <x-ui.footer />

    <x-whatsapp-float />

    <x-cookie-notice />
</body>
</html>
