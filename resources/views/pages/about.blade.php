@php
    $brand = config('brand');
@endphp

<x-layouts.site title="About"
                description="7Hills Digital builds and hosts websites, email and business software for South African firms, from Midrand, Gauteng.">

    <section class="bg-brand text-white">
        <div class="container-page section-tight">
            <p class="eyebrow-light">About us</p>
            <h1 class="mt-4 max-w-3xl h-display">
                The IT department for businesses that were never going to hire one.
            </h1>
        </div>
    </section>

    <section class="container-page section">
        <div class="grid gap-12 lg:grid-cols-[1.3fr_1fr] lg:gap-16">
            <div class="prose-page" data-reveal>
                <p class="lede text-brand">
                    We build websites, run email and write the software that businesses use
                    to get through the day.
                </p>

                <h2>Why we work this way</h2>
                <p>
                    We kept meeting the same business. Fifteen years of trading, a good name in
                    its town, and no website — because the last quote was thirty thousand rand
                    upfront from someone who then disappeared. The owner had been burnt once and
                    was not going to be burnt twice.
                </p>
                <p>
                    So we took the risk off the table. We build first and bill later, and we do
                    not ask anyone to sign a two-year agreement to find out whether we are any
                    good. If the work is worth the monthly fee, that is a conversation that
                    renews itself.
                </p>

                <h2>How we can afford to</h2>
                <p>
                    Because we are not building each website from scratch. Underneath the five
                    packages is one platform that we maintain for every client at once. An
                    estate agency and a dealership are, structurally, the same problem: a
                    catalogue of things with photographs, an enquiry that has to become a lead,
                    and documents that have to be collected securely.
                </p>
                <p>
                    That is what lets a single small team carry the build cost, keep the software
                    patched and still answer the phone — and it is why every client benefits when
                    we improve something for one of them.
                </p>

                <h2>Everything else your business runs on</h2>
                <p>
                    We are not only a website company. Custom software, digitising whatever is
                    still on paper, automating what somebody repeats every week, integrating the
                    systems you already pay for, sourcing hardware, and supporting the lot — it
                    all goes on one monthly bill.
                    <a href="{{ route('it-services') }}">See what else we build</a>.
                </p>

                <h2>Where we are</h2>
                <p>
                    We are based in {{ $brand['contact']['city'] }}, {{ $brand['contact']['province'] }},
                    and we work with clients across South Africa. Everything we run is built for
                    local conditions: rands, South African date formats, POPIA obligations taken
                    seriously, and WhatsApp treated as a first-class way for a customer to get
                    hold of a business.
                </p>
            </div>

            <div class="lg:sticky lg:top-28 lg:self-start" data-reveal data-reveal-delay="100">
                <div class="card p-6">
                    <h2 class="text-sm font-semibold uppercase tracking-wider text-muted">Get hold of us</h2>
                    <dl class="mt-5 space-y-4 text-sm">
                        <div>
                            <dt class="font-semibold text-brand">Email</dt>
                            <dd><a href="mailto:{{ $brand['contact']['email'] }}" class="text-secondary hover:text-accent">{{ $brand['contact']['email'] }}</a></dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-brand">Sales</dt>
                            <dd><a href="mailto:{{ $brand['contact']['sales_email'] }}" class="text-secondary hover:text-accent">{{ $brand['contact']['sales_email'] }}</a></dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-brand">Telephone</dt>
                            <dd>
                                <a href="tel:{{ preg_replace('/\D+/', '', $brand['contact']['phone']) }}" class="text-secondary hover:text-accent">
                                    {{ $brand['contact']['phone'] }}
                                </a>
                            </dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-brand">WhatsApp</dt>
                            <dd><x-whatsapp-link class="text-secondary hover:text-accent" /></dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-brand">Where</dt>
                            <dd class="text-secondary">{{ $brand['contact']['city'] }}, {{ $brand['contact']['province'] }}</dd>
                        </div>
                    </dl>
                    <a href="{{ route('contact') }}" class="btn-accent mt-6 w-full">Send us a message</a>
                </div>
            </div>
        </div>
    </section>

</x-layouts.site>
