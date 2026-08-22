<x-layouts.site title="Demos"
                description="Working demonstration sites for each package — see exactly what you would be getting before you commit to anything.">

    <section class="bg-brand text-white">
        <div class="container-page py-16 sm:py-20">
            <p class="eyebrow-light">Demos</p>
            <h1 class="mt-3 max-w-3xl text-4xl font-bold leading-tight tracking-tight sm:text-5xl">
                Look at the real thing before you commit to anything.
            </h1>
            <p class="mt-5 max-w-2xl text-lg leading-relaxed text-white/70">
                Each demo is a complete working site — public pages and the back office
                behind them — filled with realistic content for that trade. Click around.
                Break something. That is what they are for.
            </p>
        </div>
    </section>

    <section class="container-page py-16 sm:py-20">
        <div class="grid gap-6 md:grid-cols-2">
            @foreach ($packages as $package)
                <article class="flex flex-col rounded-2xl border border-brand/10 p-6">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-brand-light text-brand">
                            <x-package-icon :name="$package->icon_key" />
                        </span>
                        <div>
                            <h2 class="text-lg font-bold text-brand">{{ $package->name }}</h2>
                            <p class="text-xs font-medium uppercase tracking-wider text-muted">{{ $package->vertical_label }}</p>
                        </div>
                    </div>

                    <p class="mt-4 flex-1 text-sm leading-relaxed text-secondary">{{ $package->tagline }}</p>

                    @if ($package->demo_url)
                        <a href="{{ $package->demo_url }}" class="btn-outline mt-6 self-start">
                            Open the {{ $package->name }} demo
                        </a>
                    @else
                        <p class="mt-6 inline-flex items-center gap-2 self-start rounded-lg bg-brand-light px-4 py-2.5 text-sm font-medium text-secondary">
                            <span class="h-1.5 w-1.5 rounded-full bg-accent"></span>
                            Demo being built — ask us for a walkthrough
                        </p>
                    @endif
                </article>
            @endforeach
        </div>

        <div class="mt-14 rounded-2xl bg-brand-light px-8 py-10 sm:px-12">
            <h2 class="max-w-2xl text-2xl font-bold tracking-tight text-brand">
                Would you rather see it with your own stock in it?
            </h2>
            <p class="mt-3 max-w-2xl text-base leading-relaxed text-secondary">
                Send us a handful of photographs and prices and we will put them into a
                demo for you. It costs you nothing and it is a far better test than
                somebody else's sample data.
            </p>
            <a href="{{ route('contact') }}" class="btn-accent mt-7">Ask for a demo</a>
        </div>
    </section>

</x-layouts.site>
