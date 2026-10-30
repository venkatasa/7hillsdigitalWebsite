@props([
    'overlay' => false,
])

@php
    $packages = \App\Models\Package::published()->with('tiers')->orderBy('sort')->get();
    $services = \App\Support\Revamp::services();
    $industries = \App\Support\Revamp::industries();

    /*
     | Solution pages arrive in Phase 2 (WEBSITE_SITEMAP.md). Until then the
     | prototype's menu points at the homepage sections that introduce them,
     | never at a URL that would 404.
     */
    $solutions = [
        ['CRM & customer management', 'Every lead and customer in one place', '#problems'],
        ['Process automation', 'Retire the repeated Friday job', '#problems'],
        ['Booking systems', 'Customers book themselves', '#problems'],
        ['Document & approval workflows', 'Sign-off with an audit trail', '#problems'],
        ['Reporting & dashboards', 'The numbers on one screen', '#problems'],
        ['Invoicing & accounting', 'Get paid on time', route('packages.index').'#add-ons'],
        ['Customer & staff portals', 'A secure login for each', '#problems'],
    ];

    $menus = ['platforms' => 'Platforms', 'solutions' => 'Solutions', 'enterprise' => 'Enterprise', 'industries' => 'Industries'];
@endphp

<header
    x-data="{
        open: false,
        menu: null,
        solid: {{ $overlay ? 'false' : 'true' }},
        timer: null,
        enter(m) { clearTimeout(this.timer); this.timer = setTimeout(() => this.menu = m, 90) },
        leave() { clearTimeout(this.timer); this.timer = setTimeout(() => this.menu = null, 160) },
        toggle(m) { this.menu = this.menu === m ? null : m },
        close() { this.menu = null; this.open = false },
    }"
    @if ($overlay)
        x-init="
            const hero = document.getElementById('hero');
            const sync = () => solid = window.scrollY > ((hero?.offsetHeight ?? 480) - 88);
            sync(); window.addEventListener('scroll', sync, { passive: true });
        "
    @endif
    x-effect="document.documentElement.classList.toggle('overflow-hidden', open)"
    @keydown.escape.window="if (menu) { const m = menu; menu = null; $refs['btn-' + m]?.focus() } else if (open) { open = false; $refs.burger.focus() }"
    :class="(solid && ! open) ? 'border-hairline bg-white/85 shadow-[0_1px_0_0_rgb(0_0_0/0.02)]' : 'border-white/10 bg-brand-dark/70'"
    class="hdr sticky top-0 z-50 border-b backdrop-blur-xl {{ $overlay ? 'border-white/10 bg-brand-dark/70' : 'border-hairline bg-white/85' }}">

    <div class="container-wide flex h-[76px] items-center justify-between gap-6">
        <a href="{{ route('home') }}" class="shrink-0" aria-label="{{ config('brand.name') }} — home" @click="close()">
            @if ($overlay)
                <x-brand-logo inverse class="h-14" x-show="! solid || open" />
                <x-brand-logo class="h-14" x-show="solid && ! open" x-cloak />
            @else
                <x-brand-logo class="h-14" x-show="! open" />
                <x-brand-logo inverse class="h-14" x-show="open" x-cloak />
            @endif
        </a>

        {{-- Desktop navigation --}}
        <nav class="hidden items-center gap-1 lg:flex" aria-label="Main">
            @foreach ($menus as $key => $label)
                <div class="relative" @mouseenter="enter('{{ $key }}')" @mouseleave="leave()">
                    <button type="button"
                            x-ref="btn-{{ $key }}"
                            @click="toggle('{{ $key }}')"
                            :aria-expanded="menu === '{{ $key }}' ? 'true' : 'false'"
                            aria-controls="menu-{{ $key }}"
                            :class="solid ? 'text-secondary hover:text-brand' : 'text-white/75 hover:text-white'"
                            class="inline-flex h-10 items-center gap-1.5 rounded-lg px-3 text-sm font-medium transition {{ $overlay ? 'text-white/75' : 'text-secondary' }}">
                        {{ $label }}
                        <svg class="h-3.5 w-3.5 transition" :class="menu === '{{ $key }}' && 'rotate-180'" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path d="M4 6l4 4 4-4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>

                    <div id="menu-{{ $key }}"
                         x-show="menu === '{{ $key }}'"
                         x-cloak
                         x-transition:enter="transition duration-200 ease-out"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:leave="transition duration-150 ease-in"
                         x-transition:leave-end="opacity-0 -translate-y-1"
                         @click.outside="menu === '{{ $key }}' && (menu = null)"
                         class="absolute left-1/2 top-full z-50 mt-3 w-[min(44rem,90vw)] -translate-x-1/2 overflow-hidden rounded-2xl border border-hairline bg-white text-ink elevate-3">

                        @if ($key === 'platforms')
                            <div class="grid grid-cols-2 gap-1 p-3">
                                @foreach ($packages as $package)
                                    @php $tier = $package->defaultTier(); @endphp
                                    <a href="{{ route('packages.show', $package) }}" class="group flex gap-3 rounded-xl p-3 transition hover:bg-brand-light">
                                        <span class="mt-0.5 inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-brand-light text-brand ring-1 ring-hairline group-hover:bg-white">
                                            <x-package-icon :name="$package->icon_key" class="h-[18px] w-[18px]" />
                                        </span>
                                        <span>
                                            <span class="block text-sm font-semibold text-brand">{{ $package->name }}</span>
                                            <span class="block text-xs text-muted">{{ $package->vertical_label }}@if ($tier) · from {{ $tier->formatted_price }}/mo @endif</span>
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                            <div class="flex items-center justify-between border-t border-hairline bg-brand-light px-6 py-3.5 text-sm">
                                <span class="text-muted">{{ config('brand.promise') }}</span>
                                <a href="{{ route('packages.index') }}" class="font-semibold text-accent hover:text-brand">Compare platforms &rarr;</a>
                            </div>
                        @elseif ($key === 'solutions')
                            <div class="grid grid-cols-2 gap-1 p-3">
                                @foreach ($solutions as [$name, $line, $href])
                                    <a href="{{ $href }}" @click="close()" class="rounded-xl p-3 transition hover:bg-brand-light">
                                        <span class="block text-sm font-semibold text-brand">{{ $name }}</span>
                                        <span class="block text-xs text-muted">{{ $line }}</span>
                                    </a>
                                @endforeach
                            </div>
                            <div class="border-t border-hairline bg-brand-light px-6 py-3.5 text-sm">
                                <a href="{{ route('contact', ['type' => 'project']) }}" class="font-semibold text-accent hover:text-brand">Not sure which? Describe the problem &rarr;</a>
                            </div>
                        @elseif ($key === 'enterprise')
                            <div class="grid grid-cols-2 gap-1 p-3">
                                @foreach ($services as $slug => $service)
                                    <a href="#service-{{ $slug }}" @click="close(); window.dispatchEvent(new CustomEvent('show-service', { detail: '{{ $slug }}' }))" class="rounded-xl p-3 transition hover:bg-brand-light">
                                        <span class="flex items-center gap-2 text-sm font-semibold text-brand">
                                            {{ $service['name'] }}
                                            @if ($service['preview'])
                                                <span class="rounded border border-dashed border-brand/40 px-1 text-[10px] font-medium text-muted">preview</span>
                                            @endif
                                        </span>
                                        <span class="block text-xs text-muted">{{ \App\Support\Revamp::AVAILABILITY[$service['availability']] }}</span>
                                    </a>
                                @endforeach
                            </div>
                            <div class="border-t border-hairline bg-brand-light px-6 py-3.5 text-sm">
                                <a href="#process" @click="close()" class="font-semibold text-accent hover:text-brand">How we deliver projects &rarr;</a>
                            </div>
                        @else
                            <div class="grid grid-cols-2 gap-1 p-3">
                                @foreach ($industries as $slug => $industry)
                                    <a href="#industry-{{ $slug }}" @click="close(); window.dispatchEvent(new CustomEvent('show-industry', { detail: '{{ $slug }}' }))" class="rounded-xl p-3 transition hover:bg-brand-light">
                                        <span class="block text-sm font-semibold text-brand">{{ $industry['name'] }}</span>
                                        <span class="block text-xs text-muted">{{ $industry['pains'][0] }}</span>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach

            <a href="#work" :class="solid ? 'text-secondary hover:text-brand' : 'text-white/75 hover:text-white'" class="inline-flex h-10 items-center rounded-lg px-3 text-sm font-medium transition {{ $overlay ? 'text-white/75' : 'text-secondary' }}">Work</a>
            <a href="{{ route('about') }}" :class="solid ? 'text-secondary hover:text-brand' : 'text-white/75 hover:text-white'" class="inline-flex h-10 items-center rounded-lg px-3 text-sm font-medium transition {{ $overlay ? 'text-white/75' : 'text-secondary' }}">About</a>
        </nav>

        <div class="hidden items-center gap-2 lg:flex">
            <a href="{{ route('packages.index') }}"
               :class="solid ? 'text-brand hover:text-accent' : 'text-white/85 hover:text-white'"
               class="btn-v2 min-h-10 px-4 text-sm {{ $overlay ? 'text-white/85' : 'text-brand' }}">See platforms</a>
            <a href="{{ route('contact', ['type' => 'project']) }}"
               :class="solid ? 'bg-accent text-white hover:brightness-110' : 'bg-mint text-brand-dark hover:brightness-105'"
               class="btn-v2 min-h-10 px-4 text-sm {{ $overlay ? 'bg-mint text-brand-dark' : 'bg-accent text-white' }}">Book a consultation</a>
        </div>

        {{-- Mobile toggle --}}
        <button type="button" x-ref="burger"
                @click="open = ! open; menu = null"
                :aria-expanded="open ? 'true' : 'false'"
                aria-controls="mobile-sheet"
                :class="(solid && ! open) ? 'text-brand' : 'text-white'"
                class="-mr-2 inline-flex h-11 w-11 items-center justify-center rounded-lg lg:hidden {{ $overlay ? 'text-white' : 'text-brand' }}">
            <span class="sr-only" x-text="open ? 'Close menu' : 'Open menu'">Open menu</span>
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" aria-hidden="true">
                <path x-show="! open" d="M4 7h16M4 12h16M4 17h10"/>
                <path x-show="open" x-cloak d="M6 6l12 12M18 6L6 18"/>
            </svg>
        </button>
    </div>

    {{-- Mobile sheet: full height, its own scroll, page scroll locked behind it. --}}
    <div id="mobile-sheet"
         x-show="open" x-cloak
         x-transition:enter="transition duration-200 ease-out"
         x-transition:enter-start="opacity-0"
         x-transition:leave="transition duration-150 ease-in"
         x-transition:leave-end="opacity-0"
         x-effect="open && $nextTick(() => $el.querySelector('button')?.focus())"
         class="on-dark fixed inset-x-0 bottom-0 top-[76px] z-40 overflow-y-auto bg-brand-dark lg:hidden">
        <nav class="container-wide flex min-h-full flex-col py-4" aria-label="Main">
            @foreach ($menus as $key => $label)
                <div x-data="{ sub: false }" class="border-b border-white/10">
                    <button type="button" @click="sub = ! sub" :aria-expanded="sub ? 'true' : 'false'"
                            class="flex w-full items-center justify-between py-4 text-left font-display text-lg font-bold text-white">
                        {{ $label }}
                        <svg class="h-4 w-4 text-mint transition" :class="sub && 'rotate-45'" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path d="M8 3v10M3 8h10" stroke-linecap="round"/></svg>
                    </button>
                    <div x-show="sub" x-collapse x-cloak class="pb-4">
                        <ul class="grid gap-1">
                            @if ($key === 'platforms')
                                @foreach ($packages as $package)
                                    <li><a href="{{ route('packages.show', $package) }}" class="flex justify-between rounded-lg px-3 py-2.5 text-white/75 hover:bg-white/5 hover:text-white"><span>{{ $package->name }}</span><span class="text-sm text-white/45">{{ $package->vertical_label }}</span></a></li>
                                @endforeach
                                <li><a href="{{ route('packages.index') }}" class="block rounded-lg px-3 py-2.5 font-semibold text-mint">Compare platforms &rarr;</a></li>
                            @elseif ($key === 'solutions')
                                @foreach ($solutions as [$name, $line, $href])
                                    <li><a href="{{ $href }}" @click="close()" class="block rounded-lg px-3 py-2.5 text-white/75 hover:bg-white/5 hover:text-white">{{ $name }}</a></li>
                                @endforeach
                            @elseif ($key === 'enterprise')
                                @foreach ($services as $slug => $service)
                                    <li><a href="#service-{{ $slug }}" @click="close(); window.dispatchEvent(new CustomEvent('show-service', { detail: '{{ $slug }}' }))" class="block rounded-lg px-3 py-2.5 text-white/75 hover:bg-white/5 hover:text-white">{{ $service['name'] }}@if ($service['preview']) <span class="text-xs text-white/55">(preview)</span>@endif</a></li>
                                @endforeach
                            @else
                                @foreach ($industries as $slug => $industry)
                                    <li><a href="#industry-{{ $slug }}" @click="close(); window.dispatchEvent(new CustomEvent('show-industry', { detail: '{{ $slug }}' }))" class="block rounded-lg px-3 py-2.5 text-white/75 hover:bg-white/5 hover:text-white">{{ $industry['name'] }}</a></li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            @endforeach
            <a href="#work" @click="close()" class="border-b border-white/10 py-4 font-display text-lg font-bold text-white">Work</a>
            <a href="{{ route('about') }}" class="border-b border-white/10 py-4 font-display text-lg font-bold text-white">About</a>

            <div class="mt-auto grid gap-3 pt-8">
                <x-ui.button :href="route('packages.index')" tone="dark" variant="secondary" size="lg">See platforms</x-ui.button>
                <x-ui.button :href="route('contact', ['type' => 'project'])" tone="dark" size="lg" arrow>Book a consultation</x-ui.button>
            </div>
        </nav>
    </div>
</header>
