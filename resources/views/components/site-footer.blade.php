@php
    $brand = config('brand');
    $packages = \App\Models\Package::published()->orderBy('sort')->get(['slug', 'name']);
@endphp

<footer class="mt-24 bg-brand-dark text-white/70">
    <div class="container-page grid gap-10 py-14 sm:grid-cols-2 lg:grid-cols-4">
        <div class="lg:col-span-2">
            <x-brand-logo inverse class="h-20" />
            <p class="mt-4 max-w-sm text-sm leading-relaxed">
                {{ $brand['tagline'] }} We build and host the website, the email and the
                back office, and we bill it monthly — nothing upfront, cancel any time.
            </p>
            <p class="mt-5 text-xs uppercase tracking-[0.18em] text-white/40">
                A {{ $brand['parent'] }} company
            </p>
        </div>

        <div>
            <h2 class="text-xs font-semibold uppercase tracking-[0.18em] text-mint">Packages</h2>
            <ul class="mt-4 space-y-2.5 text-sm">
                @foreach ($packages as $package)
                    <li>
                        <a href="{{ route('packages.show', $package) }}" class="hover:text-white">{{ $package->name }}</a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div>
            <h2 class="text-xs font-semibold uppercase tracking-[0.18em] text-mint">Company</h2>
            <ul class="mt-4 space-y-2.5 text-sm">
                <li><a href="{{ route('how-it-works') }}" class="hover:text-white">How it works</a></li>
                <li><a href="{{ route('it-services') }}" class="hover:text-white">Custom &amp; managed IT</a></li>
                <li><a href="{{ route('demos') }}" class="hover:text-white">Demos</a></li>
                <li><a href="{{ route('about') }}" class="hover:text-white">About</a></li>
                <li><a href="{{ route('contact') }}" class="hover:text-white">Contact</a></li>
                <li><a href="{{ route('terms') }}" class="hover:text-white">Terms</a></li>
                <li><a href="{{ route('privacy') }}" class="hover:text-white">Privacy &amp; POPIA</a></li>
            </ul>
        </div>
    </div>

    <div class="border-t border-white/10">
        <div class="container-page flex flex-col gap-3 py-6 text-xs sm:flex-row sm:items-center sm:justify-between">
            <p>&copy; {{ now()->year }} {{ $brand['legal_name'] }}. All rights reserved.</p>
            <p class="max-w-md text-white/60">{{ config('brand.hardware.blurb') }}</p>
            <p>
                <a href="mailto:{{ $brand['contact']['email'] }}" class="hover:text-white">{{ $brand['contact']['email'] }}</a>
                <span class="mx-2 text-white/25">|</span>
                {{ $brand['contact']['city'] }}, {{ $brand['contact']['province'] }}
            </p>
        </div>
    </div>
</footer>
