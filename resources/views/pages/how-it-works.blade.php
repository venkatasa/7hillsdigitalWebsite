@php $brand = config('brand'); @endphp

<x-layouts.site title="How it works"
                description="From first conversation to a live website, usually two to three weeks. Nothing to pay until it is live.">

    <section class="bg-brand text-white">
        <div class="container-page section">
            <p class="eyebrow-light">How it works</p>
            <h1 class="mt-4 max-w-3xl h-display">
                Five steps, and you only reach for your wallet at step five.
            </h1>
        </div>
    </section>

    <section class="container-page section">
        <ol class="space-y-12">
            @foreach ([
                ['A twenty-minute conversation', 'You tell us what the business does, how enquiries reach you today and what drives you mad about it. We tell you which package fits, what else we can take off your hands, and roughly what it costs.'],
                ['You send us your content', 'Photos, prices, and your logo if you have one. This is the part only you can do, and it is usually what decides whether the build takes two weeks or two months.'],
                ['We build it', 'Two to three weeks. You get a link to the real site on a temporary address, with your real content in it — not a mock-up, not a slide deck. Your staff get logins to the back office at the same time.'],
                ['You go through it with us', 'We sit down and change what needs changing. Wrong colours, missing field, a form that asks the wrong question — that is what this step is for, and it is included.'],
                ['We go live, and you pay your first month', 'We point your domain at the site and it goes live. If you have asked for mailboxes we set those up and move your email across at the same time. From that day it is one monthly invoice. If it is still not right, you have not lost anything.'],
            ] as $i => [$heading, $copy])
                <li class="flex gap-6 sm:gap-8">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-brand text-base font-bold text-white">
                        {{ $i + 1 }}
                    </span>
                    <div class="pt-1.5">
                        <h2 class="text-xl font-bold text-brand">{{ $heading }}</h2>
                        <p class="mt-2.5 max-w-2xl text-base leading-relaxed text-secondary">{{ $copy }}</p>
                    </div>
                </li>
            @endforeach
        </ol>
    </section>

    <section class="bg-brand-light section">
        <div class="container-page grid gap-12 lg:grid-cols-2 lg:gap-16">
            <div>
                <p class="eyebrow">What we need from you</p>
                <h2 class="mt-3 h-section text-brand">
                    The build moves at the speed of your photographs.
                </h2>
                <p class="mt-4 text-base leading-relaxed text-secondary">
                    We are carrying the cost until you go live, so the one thing we ask is
                    that you answer us. A build that stalls for three months waiting on a
                    logo helps nobody.
                </p>
            </div>
            <ul class="space-y-3.5">
                @foreach ([
                    'Photographs of your stock, rooms, premises or team',
                    'Your prices, rates or salary bands',
                    'Your logo in the best quality you have — even a business card helps',
                    'The names and job titles of everyone who needs a mailbox',
                    'Access to your domain, or permission for us to register one',
                    'One person who can make decisions and answer questions',
                ] as $item)
                    <li class="flex gap-3 rounded-lg bg-white px-5 py-4 text-sm font-medium text-brand">
                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-accent" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M16.7 5.3a1 1 0 0 1 0 1.4l-7.5 7.5a1 1 0 0 1-1.4 0L3.3 9.7a1 1 0 1 1 1.4-1.4l3.8 3.8 6.8-6.8a1 1 0 0 1 1.4 0Z" clip-rule="evenodd"/>
                        </svg>
                        {{ $item }}
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- ── FAQ ──────────────────────────────────────────────────────────── --}}
    <section class="container-page section">
        <h2 class="h-section text-brand">Questions we get asked</h2>

        <div class="mt-10 divide-y divide-brand/10 border-y border-brand/10">
            @foreach ([
                ['If there is nothing to pay upfront, what stops me cancelling in month two?', 'Nothing does, and that is the point. We are betting that a site that brings you enquiries is one you will keep paying for. If it does not, we would rather you left than felt trapped — a client serving out a contract they resent is worse for us than an empty slot.'],
                ['Do I own the website?', 'You own your domain, your content and your data. You do not own the software — the monthly fee rents it. That is what lets us build with nothing upfront. If you want a site you own outright, we can quote that too, but it will not be R0 to start.'],
                ['Is email included?', 'No — and that is deliberate. Most businesses already have email, and the ones that do not want very different amounts of it, so bundling it would either overcharge you or lose us money. Mailboxes on your own domain are R20 a month for 5GB and R30 for 10GB. Buying a lot of them? Ask us for volume pricing.'],
                ['What happens to my email if I leave?', 'Your domain is yours, so you point it wherever you like and your address keeps working. We will hand over your mailbox data in a standard format. We do not hold email hostage.'],
                ['Can I move my existing website across?', 'Usually. We can carry over your content and your existing addresses so links do not break. If your current site is on a platform we cannot export from, we will tell you before you commit.'],
                ['Who do I phone when something breaks?', 'Us. Support is in the monthly fee. There is no per-hour charge for fixing something that should have worked.'],
                ['Is my clients\' information safe?', 'Personal information is stored on our servers in encrypted form, documents like IDs and payslips are never publicly reachable, and every form carries a POPIA collection notice and consent. Our privacy notice sets out the detail.'],
            ] as [$q, $a])
                <details class="group py-5" name="faq">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-6 text-base font-semibold text-brand marker:hidden">
                        {{ $q }}
                        <svg class="h-5 w-5 shrink-0 text-accent transition group-open:rotate-45" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" d="M10 4.5v11M4.5 10h11"/>
                        </svg>
                    </summary>
                    <p class="mt-3 max-w-3xl text-sm leading-relaxed text-secondary">{{ $a }}</p>
                </details>
            @endforeach
        </div>

        <div class="mt-12 text-center">
            <a href="{{ route('contact') }}" class="btn-accent">Ask us something else</a>
        </div>
    </section>

</x-layouts.site>
