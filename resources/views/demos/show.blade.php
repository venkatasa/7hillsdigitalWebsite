<x-layouts.demo :demo="$demo" :slug="$slug">

    {{-- ── Hero ─────────────────────────────────────────────────────────── --}}
    <section class="relative overflow-hidden" style="background: var(--demo-primary)">
        <div aria-hidden="true" class="pointer-events-none absolute inset-0 opacity-25">
            <div class="glow -right-24 -top-32 h-[30rem] w-[30rem]" style="background: var(--demo-accent)"></div>
            <div class="glow -bottom-40 -left-20 h-96 w-96" style="background: var(--demo-accent); opacity: 0.6"></div>
        </div>

        <div class="container-page relative grid items-center gap-12 py-16 text-white sm:py-20 lg:grid-cols-[1.05fr_0.95fr] lg:gap-16 lg:py-24">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em]" style="color: var(--demo-accent)" data-reveal>
                    {{ $demo['strap'] }}
                </p>
                <h1 class="mt-4 h-display" data-reveal data-reveal-delay="60">{{ $demo['hero_title'] }}</h1>
                <p class="mt-6 max-w-xl lede text-white/75" data-reveal data-reveal-delay="120">{{ $demo['hero_copy'] }}</p>

                <div class="mt-9 flex flex-wrap gap-3" data-reveal data-reveal-delay="180">
                    <a href="#listings" class="btn btn-lg text-white hover:brightness-110" style="background: var(--demo-accent)">
                        {{ $demo['hero_cta'] }}
                    </a>
                    <a href="#contact" class="btn btn-lg border border-white/30 text-white hover:bg-white/10">Contact us</a>
                </div>

                <dl class="mt-12 grid max-w-lg grid-cols-3 gap-6 border-t border-white/15 pt-7" data-reveal data-reveal-delay="240">
                    @foreach ($demo['stats'] as [$figure, $label])
                        <div>
                            <dt class="font-display text-2xl font-extrabold" style="color: var(--demo-accent)">{{ $figure }}</dt>
                            <dd class="mt-1 text-xs leading-snug text-white/60">{{ $label }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>

            <div class="relative" data-reveal data-reveal-delay="140">
                <div class="overflow-hidden rounded-3xl border border-white/15 shadow-2xl">
                    <x-demo-art :kind="$demo['art']" :seed="1" :label="$demo['name']" class="aspect-[4/3]" />
                </div>
                <div class="absolute -bottom-5 -left-4 hidden rounded-2xl bg-white p-4 shadow-xl sm:block">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.14em] text-muted">Latest</p>
                    <p class="mt-1 font-display text-sm font-bold" style="color: var(--demo-primary)">
                        {{ \Illuminate\Support\Str::limit($demo['items'][0]['title'], 26) }}
                    </p>
                    <p class="text-xs font-semibold" style="color: var(--demo-accent)">{{ $demo['items'][0]['price'] }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ── The catalogue ────────────────────────────────────────────────── --}}
    <section id="listings" class="section" style="background: var(--demo-wash)">
        <div class="container-page">
            <div class="flex flex-wrap items-end justify-between gap-6" data-reveal>
                <div class="max-w-2xl">
                    <p class="text-xs font-bold uppercase tracking-[0.18em]" style="color: var(--demo-accent)">
                        {{ $demo['items_eyebrow'] }}
                    </p>
                    <h2 class="mt-3 h-section" style="color: var(--demo-primary)">{{ $demo['items_title'] }}</h2>
                </div>
                <p class="max-w-sm text-sm leading-relaxed text-secondary">{{ $demo['items_note'] }}</p>
            </div>

            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($demo['items'] as $i => $item)
                    <article class="group overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/5 transition duration-300 hover:-translate-y-1 hover:shadow-xl"
                             data-reveal data-reveal-delay="{{ ($i % 3) * 80 }}">
                        <div class="relative overflow-hidden">
                            <x-demo-art :kind="$demo['art']" :seed="$i + 2" :label="$item['title']"
                                        class="aspect-[4/3] transition duration-500 group-hover:scale-105" />
                            <span class="absolute left-4 top-4 rounded-full bg-white/95 px-3 py-1 text-[11px] font-bold uppercase tracking-wider"
                                  style="color: var(--demo-primary)">{{ $item['badge'] }}</span>
                        </div>

                        <div class="p-5">
                            <h3 class="font-display text-base font-bold leading-snug" style="color: var(--demo-primary)">
                                {{ $item['title'] }}
                            </h3>
                            <p class="mt-1.5 text-xs leading-relaxed text-muted">{{ $item['meta'] }}</p>
                            <div class="mt-4 flex items-center justify-between border-t border-hairline pt-4">
                                <p class="font-display text-lg font-extrabold" style="color: var(--demo-accent)">{{ $item['price'] }}</p>
                                <span class="text-xs font-semibold text-secondary transition group-hover:translate-x-0.5">Details &rarr;</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── Why us ───────────────────────────────────────────────────────── --}}
    <section id="about" class="container-page section">
        <div class="mx-auto max-w-2xl text-center" data-reveal>
            <h2 class="h-section" style="color: var(--demo-primary)">{{ $demo['features_title'] }}</h2>
        </div>

        <div class="mt-12 grid gap-8 md:grid-cols-3">
            @foreach ($demo['features'] as $i => [$heading, $copy])
                <div class="text-center" data-reveal data-reveal-delay="{{ $i * 80 }}">
                    <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl font-display text-base font-extrabold text-white"
                          style="background: var(--demo-accent)">{{ $i + 1 }}</span>
                    <h3 class="mt-5 font-display text-lg font-bold" style="color: var(--demo-primary)">{{ $heading }}</h3>
                    <p class="mt-2.5 text-sm leading-relaxed text-secondary">{{ $copy }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ── Testimonial ──────────────────────────────────────────────────── --}}
    <section class="section-tight" style="background: var(--demo-wash)">
        <div class="container-page">
            <blockquote class="mx-auto max-w-3xl text-center" data-reveal>
                <svg class="mx-auto h-8 w-8 opacity-30" style="color: var(--demo-accent)" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M9.6 6C6.5 7.6 4.8 10.3 4.8 13.6c0 2.7 1.6 4.4 3.8 4.4 2 0 3.5-1.5 3.5-3.4 0-1.9-1.4-3.3-3.2-3.3-.3 0-.7 0-1 .2.5-1.6 1.9-3 3.6-3.9L9.6 6Zm8.6 0c-3.1 1.6-4.8 4.3-4.8 7.6 0 2.7 1.6 4.4 3.8 4.4 2 0 3.5-1.5 3.5-3.4 0-1.9-1.4-3.3-3.2-3.3-.3 0-.7 0-1 .2.5-1.6 1.9-3 3.6-3.9L18.2 6Z"/>
                </svg>
                <p class="mt-5 font-display text-xl font-semibold leading-relaxed sm:text-2xl" style="color: var(--demo-primary)">
                    &ldquo;{{ $demo['testimonial'][0] }}&rdquo;
                </p>
                <footer class="mt-5 text-sm font-medium text-muted">— {{ $demo['testimonial'][1] }}</footer>
            </blockquote>
        </div>
    </section>

    {{-- ── Contact ──────────────────────────────────────────────────────── --}}
    <section id="contact" class="container-page section">
        <div class="grid gap-12 lg:grid-cols-2 lg:gap-16">
            <div data-reveal>
                <h2 class="h-section" style="color: var(--demo-primary)">{{ $demo['contact_title'] }}</h2>
                <p class="mt-4 lede text-secondary">{{ $demo['contact_copy'] }}</p>

                <div class="mt-8 rounded-xl border border-dashed p-5 text-sm leading-relaxed text-secondary"
                     style="border-color: color-mix(in srgb, var(--demo-accent) 40%, transparent); background: var(--demo-wash)">
                    <strong style="color: var(--demo-primary)">This form is for show.</strong>
                    On a real site it captures the enquiry, emails the right person and opens a
                    tracked lead in the back office.
                    <a href="{{ route('contact', ['package' => $demo['package']]) }}"
                       class="font-semibold underline underline-offset-2" style="color: var(--demo-accent)">Enquire about a site like this instead</a>.
                </div>
            </div>

            {{-- Deliberately inert: a demo must never collect a real person's details. --}}
            <form class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-black/5 sm:p-8" data-reveal data-reveal-delay="100"
                  onsubmit="return false" aria-label="Demonstration enquiry form (not functional)">
                <div class="grid gap-5 sm:grid-cols-2">
                    @foreach ([['Your name', 'text'], ['Email address', 'email'], ['Telephone', 'tel'], ['Best time to call', 'text']] as [$label, $type])
                        <div>
                            <label class="block text-sm font-medium" style="color: var(--demo-primary)">{{ $label }}</label>
                            <input type="{{ $type }}" disabled
                                   class="mt-1.5 w-full rounded-lg border border-hairline bg-brand-light px-3.5 py-2.5 text-sm text-muted"
                                   placeholder="—">
                        </div>
                    @endforeach
                </div>
                <div class="mt-5">
                    <label class="block text-sm font-medium" style="color: var(--demo-primary)">How can we help?</label>
                    <textarea rows="4" disabled
                              class="mt-1.5 w-full rounded-lg border border-hairline bg-brand-light px-3.5 py-2.5 text-sm text-muted"
                              placeholder="—"></textarea>
                </div>
                <button type="button" disabled
                        class="mt-6 w-full cursor-not-allowed rounded-lg px-5 py-3 text-sm font-semibold text-white opacity-50"
                        style="background: var(--demo-accent)">
                    Send enquiry (disabled in the demo)
                </button>
            </form>
        </div>
    </section>

</x-layouts.demo>
