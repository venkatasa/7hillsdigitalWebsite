@props([
    'title' => null,
    'description' => null,
])

@php
    $brand = config('brand');
    $pageTitle = $title ? $title.' — '.$brand['name'] : $brand['name'].' — '.$brand['tagline'];
    $pageDescription = $description ?? $brand['promise'].' Websites, email and business software for South African firms, from '.$brand['contact']['city'].'.';
@endphp

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
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
    <meta name="twitter:card" content="summary">
    <meta name="theme-color" content="{{ $brand['colors']['primary'] }}">

    <link rel="icon" href="{{ asset($brand['logo']['favicon']) }}" sizes="any">
    <link rel="apple-touch-icon" href="{{ asset($brand['logo']['badge']) }}">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Brand palette as CSS custom properties: config/brand.php is the single
         source of truth, so a colour change needs no asset rebuild. Emitted
         from the config array, so a new colour needs no edit here either. --}}
    <style>
        :root {
        @foreach ($brand['colors'] as $token => $value)
            --brand-{{ $token }}: {{ $value }};
        @endforeach
        }
    </style>

    {{-- Self-hosted Montserrat + Plus Jakarta Sans, bundled at build time.
         No third-party font request is ever made from a visitor's browser. --}}
    {{ Vite::fonts() }}

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col">
    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded focus:bg-brand focus:px-4 focus:py-2 focus:text-white">
        Skip to content
    </a>

    <x-site-header />

    <main id="main" class="flex-1">
        {{ $slot }}
    </main>

    <x-site-footer />

    <x-whatsapp-float />
</body>
</html>
