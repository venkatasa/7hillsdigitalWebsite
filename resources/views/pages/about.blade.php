@php
    $brand = config('brand');
    $legal = $brand['legal'];

    // ECTA s43(1) obliges us to publish these. A blank renders as an em-dash
    // rather than being hidden, so an unfilled statutory field is obvious.
    $companyInfo = [
        ['Registered name', $brand['legal_name']],
        ['Legal status', 'Private company (Pty) Ltd'],
        ['Registration number', $legal['registration_number']],
        ['Place of registration', $legal['place_of_registration']],
        ['VAT number', $legal['vat_number']],
        ['Directors', $legal['directors']],
        ['Physical address', $legal['physical_address']],
        ['Address for service of legal documents', $legal['service_address']],
        ['Telephone', $brand['contact']['phone']],
        ['Email', $brand['contact']['email']],
        ['Information Officer', $legal['information_officer']],
    ];
@endphp

<x-layouts.site title="About"
                description="7Hills Digital builds and hosts websites, email and business software for South African firms. Part of 7Hills Holdings.">

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
                    {{ $brand['legal_name'] }} is the technology arm of {{ $brand['parent'] }}.
                    We build websites, run email and write the software that businesses use to
                    get through the day.
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

            <aside class="lg:sticky lg:top-28 lg:self-start" data-reveal data-reveal-delay="100">
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
            </aside>
        </div>
    </section>

    {{-- ── Statutory company information (ECTA s43) ─────────────────────── --}}
    <section class="bg-brand-light section-tight">
        <div class="container-page">
            <div class="max-w-2xl" data-reveal>
                <p class="eyebrow">Company information</p>
                <h2 class="mt-3 h-section text-brand">Who you are actually dealing with.</h2>
                <p class="mt-4 leading-relaxed text-secondary">
                    Published in terms of section 43 of the
                    Electronic Communications and Transactions Act, 2002.
                </p>
            </div>

            <div class="mt-10 overflow-hidden rounded-2xl border border-hairline bg-white" data-reveal data-reveal-delay="80">
                <dl class="divide-y divide-hairline text-sm">
                    @foreach ($companyInfo as [$label, $value])
                        <div class="grid gap-1 px-6 py-4 sm:grid-cols-[16rem_1fr] sm:gap-6">
                            <dt class="font-semibold text-brand">{{ $label }}</dt>
                            <dd class="{{ $value ? 'text-secondary' : 'text-muted' }}">{{ $value ?: '—' }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>

            <div class="mt-8 max-w-3xl space-y-3 text-xs leading-relaxed text-muted" data-reveal>
                <p>
                    <strong class="text-secondary">Description of services.</strong> Design, development,
                    hosting and support of websites and business software; email services on a client's
                    own domain; optional software modules; IT support; and the procurement of hardware.
                    Services are supplied on a monthly subscription unless otherwise agreed in writing.
                </p>
                <p>
                    <strong class="text-secondary">Prices.</strong> All prices shown on this website are in
                    South African rand and exclude VAT. They are an indication only and are confirmed
                    solely in a written quotation from us. Our
                    <a href="{{ route('terms') }}" class="underline underline-offset-2 hover:text-accent">terms of service</a>
                    govern every engagement.
                </p>
                <p>
                    <strong class="text-secondary">General information only.</strong> Nothing on this website
                    is professional, legal, financial or technical advice, and nothing on it is an offer
                    capable of acceptance. Specifications, availability and prices may change without notice.
                </p>
                <p>
                    <strong class="text-secondary">Complaints.</strong> Raise any complaint with us first at
                    <a href="mailto:{{ $brand['contact']['email'] }}" class="underline underline-offset-2 hover:text-accent">{{ $brand['contact']['email'] }}</a>.
                    We are not a member of any self-regulatory body governing our sector.
                </p>
            </div>
        </div>
    </section>

</x-layouts.site>
