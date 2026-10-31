@php
    $brand = config('brand');

    // Feeds the dependent tier select. Only what the browser needs.
    $tierMap = $packages->mapWithKeys(fn ($p) => [
        $p->id => $p->tiers->map(fn ($t) => [
            'id' => $t->id,
            'label' => $t->name.' — '.$t->formatted_price.'/month',
        ])->values(),
    ]);
@endphp

<x-layouts.site title="Get started"
                description="Tell us what your business does and we will show you what your website would look like. Nothing to pay, nothing to sign.">

    <section class="bg-brand text-white">
        <div class="container-page section">
            <p class="eyebrow-light">Get started</p>
            <h1 class="mt-4 max-w-3xl h-display">
                Tell us about your business.
            </h1>
            <p class="mt-6 max-w-2xl lede text-white/75">
                We will come back to you within one working day with the package that fits
                and what it would cost. No obligation, and nothing to pay to find out.
            </p>
        </div>
    </section>

    <section class="container-page section">
        <div class="grid gap-12 lg:grid-cols-[1.5fr_1fr] lg:gap-16">

            <div>
                @if ($errors->any())
                    <div role="alert" class="mb-8 rounded-xl border border-red-200 bg-red-50 p-5">
                        <p class="text-sm font-semibold text-red-800">We could not send that just yet.</p>
                        <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('contact.store') }}" class="space-y-6"
                      x-data="{
                          tiers: {{ Illuminate\Support\Js::from($tierMap) }},
                          packageId: '{{ old('package_id', $selected?->id) }}',
                          tierId: '{{ old('package_tier_id') }}',
                          get options() { return this.tiers[this.packageId] ?? [] },
                      }">
                    @csrf
                    {{-- Which button brought them here; stored only while /quote is switched on. --}}
                    <input type="hidden" name="from" value="{{ old('from', request()->query('from')) }}">

                    {{-- Honeypot. Hidden from people, irresistible to bots. --}}
                    <div class="absolute left-[-9999px]" aria-hidden="true">
                        <label for="website">Leave this field blank</label>
                        <input type="text" name="website" id="website" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <x-form-field name="name" label="Your name" required />
                        <x-form-field name="company" label="Business name" />
                        <x-form-field name="email" label="Email address" type="email" required />
                        <x-form-field name="phone" label="Telephone or WhatsApp" type="tel" />
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label for="package_id" class="block text-sm font-medium text-brand">Which package interests you?</label>
                            <select name="package_id" id="package_id" x-model="packageId" x-on:change="tierId = ''"
                                    class="mt-1.5 w-full rounded-lg border border-brand/20 bg-white px-3.5 py-2.5 text-sm text-brand focus:border-accent focus:ring-0">
                                <option value="">Not sure yet — please advise</option>
                                @foreach ($packages as $package)
                                    <option value="{{ $package->id }}">{{ $package->name }} — {{ $package->vertical_label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div x-show="options.length" x-cloak>
                            <label for="package_tier_id" class="block text-sm font-medium text-brand">Tier</label>
                            <select name="package_tier_id" id="package_tier_id" x-model="tierId"
                                    class="mt-1.5 w-full rounded-lg border border-brand/20 bg-white px-3.5 py-2.5 text-sm text-brand focus:border-accent focus:ring-0">
                                <option value="">Whichever suits us best</option>
                                <template x-for="tier in options" :key="tier.id">
                                    <option :value="tier.id" x-text="tier.label"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <x-form-field name="industry" label="What does your business do?"
                                  hint="Especially useful if none of the packages above are an obvious fit." />

                    <x-form-field name="current_website" label="Current website, if you have one"
                                  placeholder="www.yourbusiness.co.za" />

                    <div>
                        <label for="message" class="block text-sm font-medium text-brand">Anything else we should know?</label>
                        <textarea name="message" id="message" rows="5"
                                  class="mt-1.5 w-full rounded-lg border border-brand/20 px-3.5 py-2.5 text-sm text-brand focus:border-accent focus:ring-0"
                                  placeholder="How customers reach you today, what you would want the site to do, when you would want to be live.">{{ old('message') }}</textarea>
                    </div>

                    <x-popia-notice />

                    <div class="flex gap-3">
                        <input type="checkbox" name="consent" id="consent" value="1" @checked(old('consent'))
                               class="mt-0.5 h-4 w-4 shrink-0 rounded border-brand/30 text-accent focus:ring-accent">
                        <label for="consent" class="text-sm leading-relaxed text-secondary">
                            I agree that {{ $brand['name'] }} may use the details above to answer this
                            enquiry and to set up my website and email if I go ahead.
                        </label>
                    </div>

                    <button type="submit" class="btn-accent w-full sm:w-auto">Send my enquiry</button>
                </form>
            </div>

            <div class="lg:sticky lg:top-24 lg:self-start">
                <div class="rounded-2xl bg-brand-light p-6">
                    <h2 class="text-base font-bold text-brand">What happens next</h2>
                    <ol class="mt-5 space-y-4 text-sm text-secondary">
                        @foreach ([
                            'We read it and come back to you within one working day.',
                            'A short call to understand the business and confirm the package.',
                            'We build it, you review it, and only then does an invoice appear.',
                        ] as $i => $step)
                            <li class="flex gap-3">
                                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-brand text-xs font-bold text-white">{{ $i + 1 }}</span>
                                <span class="leading-relaxed">{{ $step }}</span>
                            </li>
                        @endforeach
                    </ol>

                    <div class="mt-7 border-t border-hairline pt-5 text-sm">
                        <p class="font-semibold text-brand">Would rather just phone?</p>
                        <p class="mt-2">
                            <a href="tel:{{ preg_replace('/\D+/', '', $brand['contact']['phone']) }}" class="text-secondary hover:text-accent">
                                {{ $brand['contact']['phone'] }}
                            </a>
                        </p>
                        <p class="mt-2">
                            <x-whatsapp-link class="text-secondary hover:text-accent" label="WhatsApp us" />
                        </p>
                        <p class="mt-2">
                            <a href="mailto:{{ $brand['contact']['sales_email'] }}" class="text-secondary underline underline-offset-2 hover:text-accent">
                                {{ $brand['contact']['sales_email'] }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </section>

</x-layouts.site>
