@props(['package'])

@php
    $tier = $package->defaultTier();
@endphp

<article class="group relative flex flex-col rounded-2xl border border-brand/10 bg-white p-6 transition hover:border-accent/60 hover:shadow-lg hover:shadow-brand/5">
    @if ($package->is_featured)
        <span class="absolute -top-2.5 right-5 rounded-full bg-accent px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-brand-dark">
            Most asked for
        </span>
    @endif

    <div class="flex items-center gap-3">
        <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-brand-light text-brand">
            <x-package-icon :name="$package->icon_key" />
        </span>
        <div class="min-w-0">
            <h3 class="text-lg font-bold text-brand">{{ $package->name }}</h3>
            <p class="truncate text-xs font-medium uppercase tracking-wider text-muted">{{ $package->vertical_label }}</p>
        </div>
    </div>

    <p class="mt-4 text-sm font-medium text-brand">{{ $package->tagline }}</p>

    @if ($tier)
        <div class="mt-5 flex items-baseline gap-1.5 border-t border-brand/10 pt-5">
            <span class="text-3xl font-bold tracking-tight text-brand">{{ $tier->formatted_price }}</span>
            <span class="text-sm text-muted">/ month</span>
        </div>
        <p class="mt-1 text-xs text-muted">
            {{ $tier->name }} &middot; {{ $tier->mailbox_summary }} &middot; {{ $tier->media_gb }}GB site storage
        </p>
    @endif

    <ul class="mt-5 flex-1 space-y-2 text-sm text-secondary">
        @foreach (array_slice($package->what_you_get ?? [], 0, 3) as $item)
            <li class="flex gap-2.5">
                <svg class="mt-0.5 h-4 w-4 shrink-0 text-accent" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M16.7 5.3a1 1 0 0 1 0 1.4l-7.5 7.5a1 1 0 0 1-1.4 0L3.3 9.7a1 1 0 1 1 1.4-1.4l3.8 3.8 6.8-6.8a1 1 0 0 1 1.4 0Z" clip-rule="evenodd"/>
                </svg>
                <span>{{ $item }}</span>
            </li>
        @endforeach
    </ul>

    <a href="{{ route('packages.show', $package) }}"
       class="mt-6 inline-flex items-center gap-1.5 text-sm font-semibold text-brand transition group-hover:text-accent">
        See what {{ $package->name }} includes
        <span aria-hidden="true">&rarr;</span>
    </a>
</article>
