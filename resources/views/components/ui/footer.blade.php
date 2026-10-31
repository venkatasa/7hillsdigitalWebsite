@php
    $brand = config('brand');
    $packages = \App\Models\Package::published()->orderBy('sort')->get(['slug', 'name']);
    $lines = \App\Support\Revamp::lines();
@endphp

<footer class="on-dark relative isolate overflow-hidden bg-brand-dark text-white/65">
    <div class="contours absolute inset-0 -z-10 opacity-[0.06]" aria-hidden="true"></div>

    <div class="container-wide grid gap-12 py-16 sm:py-20 lg:grid-cols-12">
        <div class="lg:col-span-4">
            <x-brand-logo inverse class="h-16" />
            <p class="mt-6 max-w-sm text-sm leading-relaxed">
                Software development, automation and IT services, plus ready-to-use
                business platforms. Built, hosted and supported by one team, on one invoice.
            </p>
            <div class="mt-6 space-y-1.5 text-sm">
                <a href="tel:{{ preg_replace('/\s+/', '', $brand['contact']['phone']) }}" class="block text-white hover:text-mint">{{ $brand['contact']['phone'] }}</a>
                <a href="mailto:{{ $brand['contact']['email'] }}" class="block hover:text-white">{{ $brand['contact']['email'] }}</a>
                <x-whatsapp-link label="WhatsApp us" class="text-mint hover:brightness-110" />
            </div>
        </div>

        <div class="grid gap-10 sm:grid-cols-3 lg:col-span-8">
            <div>
                <h2 class="t-label text-mint">Services</h2>
                <ul class="mt-5 space-y-3 text-sm">
                    @foreach ($lines as $slug => $line)
                        <li><a href="{{ request()->routeIs('home') ? '' : route('home') }}#line-{{ $slug }}" class="hover:text-white">{{ $line['name'] }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h2 class="t-label text-mint">Platforms</h2>
                <ul class="mt-5 space-y-3 text-sm">
                    @foreach ($packages as $package)
                        <li><a href="{{ route('packages.show', $package) }}" class="hover:text-white">{{ $package->name }}</a></li>
                    @endforeach
                    <li><a href="{{ route('demos') }}" class="hover:text-white">Demos</a></li>
                </ul>
            </div>
            <div>
                <h2 class="t-label text-mint">Company</h2>
                <ul class="mt-5 space-y-3 text-sm">
                    <li><a href="{{ route('about') }}" class="hover:text-white">About</a></li>
                    <li><a href="{{ route('it-services') }}" class="hover:text-white">Custom &amp; IT services</a></li>
                    <li><a href="{{ route('how-it-works') }}" class="hover:text-white">How it works</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-white">Contact</a></li>
                    <li><a href="{{ route('terms') }}" class="hover:text-white">Terms</a></li>
                    <li><a href="{{ route('privacy') }}" class="hover:text-white">Privacy &amp; POPIA</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="border-t border-white/10">
        <div class="container-wide flex flex-col gap-3 py-6 text-xs text-white/60 sm:flex-row sm:items-center sm:justify-between">
            <p>&copy; {{ now()->year }} {{ $brand['trading_as'] }} · {{ $brand['contact']['city'] }}, {{ $brand['contact']['province'] }}</p>
            {{-- Hardware stays one quiet line (CLAUDE.md), from the same config value as <x-hardware-note>. --}}
            <p class="leading-relaxed">
                {{ $brand['hardware']['blurb'] }}
                <a href="{{ route('contact') }}" class="underline underline-offset-2 hover:text-white">Contact sales</a>.
            </p>
        </div>
    </div>
</footer>
