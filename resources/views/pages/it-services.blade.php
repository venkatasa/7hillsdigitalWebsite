@php $brand = config('brand'); @endphp

<x-layouts.site title="Custom builds and managed IT"
                description="The five packages are a starting point, not a limit. We build custom software, digitise manual processes, automate the repetitive, procure hardware and run your IT — so you can get on with the business.">

    {{-- ── Hero ─────────────────────────────────────────────────────────── --}}
    <section class="relative overflow-hidden bg-brand text-white">
        <div aria-hidden="true" class="pointer-events-none absolute inset-0 opacity-[0.09]">
            <div class="glow -right-32 -top-32 h-[28rem] w-[28rem] bg-mint"></div>
            <div class="glow -bottom-40 left-1/4 h-96 w-96 bg-accent"></div>
        </div>

        <div class="container-page relative section">
            <p class="eyebrow-light" data-reveal>Custom &amp; managed IT</p>

            <h1 class="mt-4 max-w-4xl h-display" data-reveal data-reveal-delay="60">
                Not on the list?
                <span class="text-mint">We build that too.</span>
            </h1>

            <p class="mt-6 max-w-2xl lede text-white/75" data-reveal data-reveal-delay="120">
                The five packages exist because those industries kept asking for the same
                thing. They are a starting point, not a limit. If your business has a
                process, we can put it on a screen — and if it has IT, we can run it.
            </p>

            <div class="mt-9 flex flex-wrap gap-3" data-reveal data-reveal-delay="180">
                <a href="{{ route('contact') }}" class="btn-mint btn-lg">Tell us what you need</a>
                <a href="#what-we-do" class="btn-ghost btn-lg">See what that covers</a>
            </div>
        </div>
    </section>

    {{-- ── The pitch ────────────────────────────────────────────────────── --}}
    <section class="container-page section">
        <div class="mx-auto max-w-3xl text-center" data-reveal>
            <p class="eyebrow">The whole idea</p>
            <h2 class="mt-3 h-section text-brand">
                You run the business. We run the technology.
            </h2>
            <p class="mt-5 lede text-secondary">
                Most owners we meet are doing a second job they never applied for — chasing
                a broken printer, rebuilding a spreadsheet somebody corrupted, waiting on a
                developer who stopped answering. That is not what you are good at, and it is
                not where your day should go.
            </p>
        </div>
    </section>

    {{-- ── What we do ───────────────────────────────────────────────────── --}}
    <section id="what-we-do" class="bg-brand-light section">
        <div class="container-page">
            <div class="max-w-2xl" data-reveal>
                <p class="eyebrow">What that covers</p>
                <h2 class="mt-3 h-section text-brand">Anything your business runs on.</h2>
            </div>

            <div class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ([
                    ['module', 'Custom software', 'A system built around how you actually work, instead of bending your business to fit somebody else\'s product. If you can describe the process, we can build it.'],
                    ['invoice', 'Digitising paper', 'The job card book, the leave form, the stock sheet, the delivery note. Anything living on a clipboard can live on a phone instead — and then it is searchable, backed up and impossible to lose in a bakkie.'],
                    ['boxes', 'Automating the repetitive', 'The report somebody rebuilds every Monday. The invoice reminders nobody sends. The data typed from one system into another. If a person is doing it the same way every time, it should not need a person.'],
                    ['people', 'Integrations', 'Making the systems you already pay for talk to each other — accounting, payroll, payment gateways, WhatsApp, your suppliers\' portals. Less retyping, fewer mistakes.'],
                    ['globe', 'IT support', 'Email problems, printers, connectivity, backups, new-starter setups, the laptop that will not connect to the projector. One number to phone, and it is a person who knows your setup.'],
                    ['car', 'Hardware and procurement', 'Laptops, desktops, routers, switches, access points, cabling. We source it, set it up and support it, so the machine that arrives is one that already works on your network.'],
                ] as $i => [$icon, $heading, $copy])
                    <article class="card-hover p-6" data-reveal data-reveal-delay="{{ $i * 70 }}">
                        <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-accent/10 text-accent">
                            <x-package-icon :name="$icon" class="h-6 w-6" />
                        </span>
                        <h3 class="mt-4 font-display text-lg font-bold text-brand">{{ $heading }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-secondary">{{ $copy }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── How a custom job runs ────────────────────────────────────────── --}}
    <section class="container-page section">
        <div class="grid gap-12 lg:grid-cols-[0.9fr_1.1fr] lg:gap-16">
            <div data-reveal>
                <p class="eyebrow">How it works</p>
                <h2 class="mt-3 h-section text-brand">
                    Same deal as the packages, wherever we can manage it.
                </h2>
                <p class="mt-5 leading-relaxed text-secondary">
                    Custom work is quoted rather than listed, because no two of these jobs are
                    the same size. But the principle does not change: we would rather show you
                    something working than sell you a proposal.
                </p>
                <a href="{{ route('contact') }}" class="btn-accent mt-8">Start with a conversation</a>
            </div>

            <ol class="space-y-6" data-reveal data-reveal-delay="100">
                @foreach ([
                    ['We come and watch', 'An hour with the people actually doing the work. Not a requirements document — we want to see the clipboard, the spreadsheet and the WhatsApp group that is holding it together.'],
                    ['We scope the smallest useful thing', 'Not the five-year platform. The one piece that takes the most pain out of the week, built first, so you get value before you have spent much.'],
                    ['We quote it properly', 'A fixed price for that piece, or a monthly figure if it becomes something we host and support. No open-ended hourly billing where the meter runs while we learn your business.'],
                    ['We build, you use it, we adjust', 'Real users on the real thing early. Software written in a vacuum is software nobody wants to open.'],
                    ['We keep it running', 'It rolls into a monthly support arrangement, so the thing we built does not become the thing nobody maintains.'],
                ] as $i => [$heading, $copy])
                    <li class="flex gap-5">
                        <span class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand font-display text-sm font-bold text-white">
                            {{ $i + 1 }}
                        </span>
                        <div>
                            <h3 class="font-display text-base font-bold text-brand">{{ $heading }}</h3>
                            <p class="mt-1.5 text-sm leading-relaxed text-secondary">{{ $copy }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- ── Honest limits ────────────────────────────────────────────────── --}}
    <section class="bg-brand section text-white">
        <div class="container-page grid gap-12 lg:grid-cols-2 lg:gap-16">
            <div data-reveal>
                <p class="eyebrow-light">Being straight with you</p>
                <h2 class="mt-3 h-section">"Anything" has edges.</h2>
                <p class="mt-5 leading-relaxed text-white/70">
                    We would rather turn work down than take your money and learn on it. So
                    here is where the line sits.
                </p>
            </div>

            <div class="space-y-6" data-reveal data-reveal-delay="100">
                @foreach ([
                    ['What we are good at', 'Business systems: things with records, forms, documents, approvals, reporting and users. Web and mobile-friendly. Hosted by us, supported by us.'],
                    ['What we will happily take on', 'Integrations with systems that publish a sensible API, hardware procurement and setup, day-to-day IT support, and moving you off paper.'],
                    ['What we will refer elsewhere', 'Native mobile apps for app stores, embedded and industrial control systems, anything safety-critical, and enterprise ERP migrations that need a team of twenty.'],
                    ['What we will tell you not to build', 'The feature you have described because a competitor has it, when nobody in your business has asked for it. We would rather keep a client than win a change request.'],
                ] as [$heading, $copy])
                    <div class="border-l-2 border-mint pl-5">
                        <h3 class="font-display text-base font-bold">{{ $heading }}</h3>
                        <p class="mt-1.5 text-sm leading-relaxed text-white/65">{{ $copy }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── Closing CTA ──────────────────────────────────────────────────── --}}
    <section class="container-page section">
        <div class="rounded-3xl border border-hairline bg-brand-light px-8 py-14 text-center sm:px-14" data-reveal>
            <h2 class="mx-auto max-w-3xl h-section text-brand">
                Describe the thing that wastes your week.
            </h2>
            <p class="mx-auto mt-4 max-w-2xl lede text-secondary">
                If we can fix it, we will tell you roughly what that costs. If we cannot, we
                will tell you that too, and point you at someone who can. Either way the
                conversation is free.
            </p>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a href="{{ route('contact') }}" class="btn-accent btn-lg">Talk to us about custom work</a>
                <a href="{{ route('packages.index') }}" class="btn-outline btn-lg">Or start with a package</a>
            </div>
            <p class="mt-6 text-sm text-muted">
                {{ $brand['contact']['phone'] }} &middot;
                <a href="mailto:{{ $brand['contact']['sales_email'] }}" class="underline underline-offset-2 hover:text-accent">{{ $brand['contact']['sales_email'] }}</a>
            </p>
        </div>
    </section>

</x-layouts.site>
