@php
    $links = [
        ['label' => 'Packages', 'route' => 'packages.index'],
        ['label' => 'How it works', 'route' => 'how-it-works'],
        ['label' => 'Custom & IT', 'route' => 'it-services'],
        ['label' => 'Demos', 'route' => 'demos'],
        ['label' => 'About', 'route' => 'about'],
    ];
@endphp

<header x-data="{ open: false }" class="sticky top-0 z-40 border-b border-hairline bg-white/95 backdrop-blur">
    <div class="container-page flex h-[80px] items-center justify-between gap-4">
        <a href="{{ route('home') }}" aria-label="{{ config('brand.name') }} home">
            <x-brand-logo class="h-14" />
        </a>

        <nav class="hidden items-center gap-7 lg:flex" aria-label="Main">
            @foreach ($links as $link)
                <a href="{{ route($link['route']) }}"
                   @class([
                       'text-sm font-medium transition hover:text-accent',
                       'text-accent' => request()->routeIs($link['route'].'*'),
                       'text-secondary' => ! request()->routeIs($link['route'].'*'),
                   ])>
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="hidden lg:block">
            <a href="{{ route('contact') }}" class="btn-accent">Get started</a>
        </div>

        <button type="button"
                x-on:click="open = ! open"
                :aria-expanded="open ? 'true' : 'false'"
                aria-controls="mobile-nav"
                class="-mr-2 inline-flex h-10 w-10 items-center justify-center rounded-lg text-brand lg:hidden">
            <span class="sr-only">Toggle menu</span>
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path x-show="! open" stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/>
                <path x-show="open" x-cloak stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/>
            </svg>
        </button>
    </div>

    <div id="mobile-nav" x-show="open" x-collapse x-cloak class="border-t border-hairline lg:hidden">
        <nav class="container-page flex flex-col py-3" aria-label="Main">
            @foreach ($links as $link)
                <a href="{{ route($link['route']) }}" x-on:click="open = false" class="py-2.5 text-sm font-medium text-secondary hover:text-accent">
                    {{ $link['label'] }}
                </a>
            @endforeach
            <a href="{{ route('contact') }}" class="btn-accent mt-3">Get started</a>
        </nav>
    </div>
</header>
