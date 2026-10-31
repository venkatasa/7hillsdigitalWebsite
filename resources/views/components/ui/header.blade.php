@props([
    'overlay' => false,
])

@php
    $packages = \App\Models\Package::published()->with('tiers')->orderBy('sort')->get();
    $lines = \App\Support\Revamp::lines();
    $industries = \App\Support\Revamp::industries();
    $serviceCount = collect($lines)->sum(fn ($l) => count($l['services']));
    $firstLine = array_key_first($lines);
    // On the homepage, menu links are plain anchors; elsewhere they lead back to it.
    $home = request()->routeIs('home') ? '' : route('home');

    /*
     | Service, solution and industry pages arrive in Phases 2–3. Until then
     | every menu link opens the matching place on the homepage, never a URL
     | that would 404. Phase 4 replaces /contact with /quote.
     */
    $quote = route('contact', ['type' => 'project', 'from' => 'header']);
    $solutions = config('revamp.solutions', []);
    $menus = ['services' => 'Services', 'solutions' => 'Solutions', 'industries' => 'Industries'];
@endphp

<header
    x-data="{
        open: false,
        menu: null,
        line: '{{ $firstLine }}',
        solid: {{ $overlay ? 'false' : 'true' }},
        timer: null,
        enter(m) { clearTimeout(this.timer); this.timer = setTimeout(() => this.menu = m, 80) },
        leave() { clearTimeout(this.timer); this.timer = setTimeout(() => this.menu = null, 180) },
        toggle(m) { this.menu = this.menu === m ? null : m },
        close() { this.menu = null; this.open = false },
        go(event, name, detail) { this.close(); window.dispatchEvent(new CustomEvent(name, { detail })) },
    }"
    @if ($overlay)
        x-init="
            const hero = document.getElementById('hero');
            const sync = () => solid = window.scrollY > ((hero?.offsetHeight ?? 480) - 88);
            sync(); window.addEventListener('scroll', sync, { passive: true });
        "
    @endif
    {{-- No blur while the sheet is open: a backdrop-filter would trap the fixed sheet inside the header bar. --}}
    x-effect="document.documentElement.classList.toggle('overflow-hidden', open)"
    @keydown.escape.window="if (menu) { const m = menu; menu = null; $refs['btn-' + m]?.focus() } else if (open) { open = false; $refs.burger.focus() }"
    :class="{ 'border-hairline bg-white/85': solid && ! open && menu !== 'services', 'border-white/10 bg-brand-dark/80': ! (solid && ! open && menu !== 'services'), 'backdrop-blur-xl': ! open, 'bg-brand-dark': open }"
    class="hdr sticky top-0 z-50 border-b backdrop-blur-xl {{ $overlay ? 'border-white/10 bg-brand-dark/80' : 'border-hairline bg-white/85' }}">

    <div class="container-wide flex h-[76px] items-center justify-between gap-6">
        <a href="{{ route('home') }}" class="shrink-0" aria-label="{{ config('brand.name') }} — home" @click="close()">
            <x-brand-logo inverse class="h-14" x-show="! solid || open || menu === 'services'" />
            <x-brand-logo class="h-14" x-show="solid && ! open && menu !== 'services'" x-cloak />
        </a>

        {{-- Desktop navigation --}}
        <nav class="hidden items-center gap-0.5 lg:flex" aria-label="Main">
            @foreach ($menus as $key => $label)
                <div @mouseenter="enter('{{ $key }}')" @mouseleave="leave()" @class(['relative' => $key !== 'services'])>
                    <button type="button"
                            x-ref="btn-{{ $key }}"
                            @click="toggle('{{ $key }}')"
                            :aria-expanded="menu === '{{ $key }}' ? 'true' : 'false'"
                            aria-controls="menu-{{ $key }}"
                            :class="{ 'text-secondary hover:text-brand': solid && menu !== 'services', 'text-white': (! solid || menu === 'services') && menu === '{{ $key }}', 'text-white/75 hover:text-white': (! solid || menu === 'services') && menu !== '{{ $key }}' }"
                            class="nav-item inline-flex h-10 items-center gap-1.5 rounded-lg px-3 text-sm font-medium {{ $overlay ? 'text-white/75' : 'text-secondary' }}">
                        {{ $label }}
                        <svg class="nav-chevron h-3.5 w-3.5" :class="menu === '{{ $key }}' && 'rotate-180'" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path d="M4 6l4 4 4-4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>

                    @if ($key === 'services')
                        {{-- Services: full-width, two panes. Hover or focus a line on the left to see its services. --}}
                        <div id="menu-services"
                             x-show="menu === 'services'" x-cloak
                             x-transition:enter="menu-enter" x-transition:enter-start="menu-from"
                             x-transition:leave="menu-leave" x-transition:leave-end="menu-from"
                             @click.outside="menu === 'services' && ! $refs['btn-services'].contains($event.target) && (menu = null)"
                             class="on-dark absolute inset-x-0 top-full border-b border-white/10 bg-brand-dark shadow-[0_30px_60px_-30px_rgb(0_0_0/0.6)]">
                            <div class="contours pointer-events-none absolute inset-0 opacity-[0.05]" aria-hidden="true"></div>
                            <div class="container-wide relative grid grid-cols-12 gap-10 py-10">
                                <div class="col-span-4">
                                    <p class="t-label text-white/45">{{ count($lines) }} service lines</p>
                                    <ul class="mt-5 space-y-0.5">
                                        @foreach ($lines as $slug => $l)
                                            <li>
                                                <a href="{{ $home }}#line-{{ $slug }}"
                                                   @mouseenter="line = '{{ $slug }}'" @focus="line = '{{ $slug }}'"
                                                   @click="go($event, 'show-line', '{{ $slug }}')"
                                                   :class="{ 'bg-white/[0.07] text-white': line === '{{ $slug }}', 'text-white/60 hover:text-white': ! (line === '{{ $slug }}') }"
                                                   class="menu-line group flex items-center gap-3.5 rounded-xl px-3.5 py-2.5 {{ $loop->first ? 'bg-white/[0.07] text-white' : 'text-white/60' }}"
                                                   style="--motif-accent: var(--brand-mint)">
                                                    <x-ui.motif :name="$l['motif']" class="h-6 w-6 text-white/55" />
                                                    <span class="flex-1 text-[0.95rem] font-semibold">{{ $l['name'] }}</span>
                                                    @if ($l['preview'])
                                                        <span class="rounded border border-dashed border-white/30 px-1.5 text-[10px] font-medium text-white/55">preview</span>
                                                    @endif
                                                    <span class="t-index text-white/40">{{ count($l['services']) }}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <div class="col-span-8 border-l border-white/10 pl-10">
                                    @foreach ($lines as $slug => $l)
                                        <div x-show="line === '{{ $slug }}'" @if (! $loop->first) style="display: none" @endif class="menu-pane">
                                            <div class="flex items-start justify-between gap-8">
                                                <div>
                                                    <p class="font-display text-2xl font-bold tracking-tight text-white">{{ $l['name'] }}</p>
                                                    <p class="mt-2 max-w-lg text-sm leading-relaxed text-white/60">{{ $l['blurb'] }}</p>
                                                </div>
                                                <a href="{{ route('contact', ['type' => 'project', 'interest' => $slug, 'from' => 'menu']) }}"
                                                   class="btn-v2 min-h-10 shrink-0 border border-white/20 px-4 text-sm text-white hover:border-mint/60">
                                                    Get a quote
                                                </a>
                                            </div>
                                            <ul class="mt-7 grid grid-cols-2 gap-x-8 gap-y-1">
                                                @foreach ($l['services'] as $s)
                                                    <li>
                                                        <a href="{{ $home }}#line-{{ $slug }}" @click="go($event, 'show-line', '{{ $slug }}')"
                                                           class="flex items-center gap-2.5 rounded-lg py-2 text-sm text-white/80 hover:text-mint">
                                                            <span @class(['h-1.5 w-1.5 shrink-0 rounded-full', 'bg-mint' => ! $s['preview'], 'border border-white/40' => $s['preview']]) aria-hidden="true"></span>
                                                            {{ $s['name'] }}
                                                            @if ($s['preview'])<span class="sr-only">(preview only)</span>@endif
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="relative border-t border-white/10">
                                <div class="container-wide flex items-center justify-between gap-6 py-4 text-sm">
                                    <p class="text-white/50">{{ $serviceCount }} services, one team, one invoice.</p>
                                    <div class="flex items-center gap-6">
                                        <a href="{{ route('packages.index') }}" class="font-semibold text-white/80 hover:text-white">Ready-to-use platforms</a>
                                        <a href="{{ $home }}#catalogue" @click="close()" class="font-semibold text-mint hover:brightness-110">The full catalogue &rarr;</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div id="menu-{{ $key }}"
                             x-show="menu === '{{ $key }}'" x-cloak
                             x-transition:enter="menu-enter" x-transition:enter-start="menu-from"
                             x-transition:leave="menu-leave" x-transition:leave-end="menu-from"
                             @click.outside="menu === '{{ $key }}' && ! $refs['btn-{{ $key }}'].contains($event.target) && (menu = null)"
                             class="absolute left-1/2 top-full z-50 mt-3 w-[min(40rem,90vw)] -translate-x-1/2 overflow-hidden rounded-2xl border border-hairline bg-white text-ink elevate-3">
                            <div class="grid grid-cols-2 gap-1 p-3">
                                @if ($key === 'solutions')
                                    @foreach ($solutions as $s)
                                        @continue(isset($s[3]) && ! \App\Support\Revamp::visible($s[3]))
                                        <a href="{{ $home }}#line-{{ $s[2] }}" @click="go($event, 'show-line', '{{ $s[2] }}')" class="rounded-xl p-3 hover:bg-brand-light">
                                            <span class="block text-sm font-semibold text-brand">{{ $s[0] }}</span>
                                            <span class="block text-xs text-muted">{{ $s[1] }}</span>
                                        </a>
                                    @endforeach
                                @else
                                    @foreach ($industries as $slug => $industry)
                                        <a href="{{ $home }}#industry-{{ $slug }}" @click="go($event, 'show-industry', '{{ $slug }}')" class="rounded-xl p-3 hover:bg-brand-light">
                                            <span class="block text-sm font-semibold text-brand">{{ $industry['name'] }}</span>
                                            <span class="block text-xs text-muted">{{ $industry['pains'][0] }}</span>
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                            <div class="border-t border-hairline bg-brand-light px-6 py-3.5 text-sm">
                                <a href="{{ $quote }}" class="font-semibold text-accent hover:text-brand">Not sure where you fit? Describe the problem &rarr;</a>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach

            @foreach ([['Platforms', route('packages.index')], ['Our Work', $home.'#work'], ['About', route('about')]] as [$label, $href])
                <a href="{{ $href }}" @click="close()"
                   :class="{ 'text-secondary hover:text-brand': solid && menu !== 'services', 'text-white/75 hover:text-white': ! (solid && menu !== 'services') }"
                   class="nav-item inline-flex h-10 items-center rounded-lg px-3 text-sm font-medium {{ $overlay ? 'text-white/75' : 'text-secondary' }}">{{ $label }}</a>
            @endforeach
        </nav>

        <div class="hidden lg:block">
            <a href="{{ $quote }}" data-cta="header-quote"
               :class="{ 'bg-accent text-white hover:brightness-110': solid && menu !== 'services', 'bg-mint text-brand-dark hover:brightness-105': ! (solid && menu !== 'services') }"
               class="btn-v2 min-h-10 px-4 text-sm {{ $overlay ? 'bg-mint text-brand-dark' : 'bg-accent text-white' }}">Get a Quote</a>
        </div>

        {{-- Mobile toggle --}}
        <button type="button" x-ref="burger"
                @click="open = ! open; menu = null"
                :aria-expanded="open ? 'true' : 'false'"
                aria-controls="mobile-sheet"
                :class="{ 'text-brand': solid && ! open, 'text-white': ! (solid && ! open) }"
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
         x-transition:enter="sheet-enter" x-transition:enter-start="sheet-from"
         x-transition:leave="sheet-leave" x-transition:leave-end="sheet-from"
         x-effect="if (open) { $el.style.top = $root.getBoundingClientRect().bottom + 'px'; $nextTick(() => $el.querySelector('button')?.focus()) }"
         class="on-dark fixed inset-x-0 bottom-0 top-[76px] z-40 overflow-y-auto bg-brand-dark lg:hidden">
        <nav class="container-wide flex min-h-full flex-col py-3" aria-label="Main">
            @foreach ($menus as $key => $label)
                <div x-data="{ sub: false }" class="border-b border-white/10">
                    <button type="button" @click="sub = ! sub" :aria-expanded="sub ? 'true' : 'false'"
                            class="flex w-full items-center justify-between py-4 text-left font-display text-xl font-bold text-white">
                        {{ $label }}
                        <svg class="h-4 w-4 text-mint transition-transform" :class="sub && 'rotate-45'" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path d="M8 3v10M3 8h10" stroke-linecap="round"/></svg>
                    </button>
                    <div x-show="sub" x-collapse x-cloak class="pb-4">
                        <ul class="grid gap-0.5">
                            @if ($key === 'services')
                                @foreach ($lines as $slug => $l)
                                    <li>
                                        <a href="{{ $home }}#line-{{ $slug }}" @click="go($event, 'show-line', '{{ $slug }}')"
                                           class="flex items-center gap-3 rounded-lg px-2 py-2.5 text-white/80 hover:bg-white/5 hover:text-white" style="--motif-accent: var(--brand-mint)">
                                            <x-ui.motif :name="$l['motif']" class="h-5 w-5 text-white/50" />
                                            <span class="flex-1">{{ $l['name'] }}</span>
                                            @if ($l['preview'])<span class="text-xs text-white/50">preview</span>@endif
                                            <span class="t-index text-white/40">{{ count($l['services']) }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            @elseif ($key === 'solutions')
                                @foreach ($solutions as $s)
                                    @continue(isset($s[3]) && ! \App\Support\Revamp::visible($s[3]))
                                    <li><a href="{{ $home }}#line-{{ $s[2] }}" @click="go($event, 'show-line', '{{ $s[2] }}')" class="block rounded-lg px-2 py-2.5 text-white/80 hover:bg-white/5 hover:text-white">{{ $s[0] }}</a></li>
                                @endforeach
                            @else
                                @foreach ($industries as $slug => $industry)
                                    <li><a href="{{ $home }}#industry-{{ $slug }}" @click="go($event, 'show-industry', '{{ $slug }}')" class="block rounded-lg px-2 py-2.5 text-white/80 hover:bg-white/5 hover:text-white">{{ $industry['name'] }}</a></li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            @endforeach
            @foreach ([['Platforms', route('packages.index')], ['Our Work', $home.'#work'], ['About', route('about')]] as [$label, $href])
                <a href="{{ $href }}" @click="close()" class="border-b border-white/10 py-4 font-display text-xl font-bold text-white">{{ $label }}</a>
            @endforeach

            <div class="mt-auto grid gap-3 pt-8">
                <x-ui.button :href="$quote" tone="dark" size="lg" arrow data-cta="sheet-quote">Get a Quote</x-ui.button>
                <x-whatsapp-link label="Or WhatsApp us" class="justify-center py-2 text-sm font-semibold text-mint" />
            </div>
        </nav>
    </div>
</header>
