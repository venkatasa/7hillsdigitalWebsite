@php $brand = config('brand'); @endphp

<x-layouts.site title="Terms of service"
                description="Our commercial terms in plain English: no upfront cost, one month's notice, your domain and data stay yours.">

    <section class="bg-brand text-white">
        <div class="container-page section-tight">
            <p class="eyebrow-light">Terms</p>
            <h1 class="mt-3 h-section">Terms of service</h1>
            <p class="mt-4 max-w-2xl text-base text-white/70">
                Written to be read. If anything here is unclear, ask us before you sign up
                rather than after.
            </p>
        </div>
    </section>

    <section class="container-page section-tight">
        <div class="prose-page max-w-3xl">
            <p class="text-sm text-muted">Last updated {{ now()->format('d/m/Y') }}</p>

            <h2>1. Who we are</h2>
            <p>
                <strong>{{ $brand['legal_name'] }}</strong> ("we", "us"), trading as
                <strong>{{ $brand['trading_as'] }}</strong>, based in
                {{ $brand['contact']['city'] }}, {{ $brand['contact']['province'] }}, South Africa.
                Contact: <a href="mailto:{{ $brand['contact']['email'] }}">{{ $brand['contact']['email'] }}</a>.
            </p>

            <h2>2. What you are paying for</h2>
            <p>
                A monthly subscription covering the design and build of your website, hosting,
                SSL, backups, software maintenance and support, together with the website storage
                allowance stated for your tier. The fee is quoted per month and excludes VAT.
                <strong>Email is not included</strong> — mailboxes on your own domain are charged
                separately, per mailbox, at the rates shown on our site.
                <strong>The build itself is not charged for.</strong>
            </p>
            <p>
                <strong>This is a subscription, not a purchase.</strong> You are licensed to use
                the software for as long as you subscribe. Ownership of the platform and its
                source code remains ours. This is precisely why there is no upfront charge.
            </p>

            <p>
                <strong>Optional modules are charged separately.</strong> Invoicing, CRM, ERP and
                anything else described on our site as an add-on are not included in the package
                fee. Nothing is added to your invoice unless you have asked for it in writing,
                and you can drop a module on the same one month's notice as the package itself.
            </p>

            <p>
                <strong>Hardware is not part of this agreement.</strong> If we source equipment
                for you — laptops, routers, networking — it is quoted and invoiced separately,
                you own it outright, and the manufacturer's warranty is the one that applies.
            </p>

            <p>
                <strong>You may buy the site outright instead.</strong> If you would rather own
                the software rather than subscribe to it, we will quote a build-and-own package:
                a once-off development fee, after which the code and the design are yours to take
                anywhere. Hosting, email and support are then optional and quoted separately.
                That option costs money upfront — which is precisely the trade for keeping it.
            </p>

            <h2>3. What you own</h2>
            <ul>
                <li><strong>Your domain name</strong> is registered in your name and remains yours. If you leave, you take it with you.</li>
                <li><strong>Your content</strong> — photographs, text, logos and anything else you supply — remains yours.</li>
                <li><strong>Your data</strong> — listings, contacts, enquiries and documents — remains yours, and we will export it in a standard format on request.</li>
                <li><strong>Your email and its contents</strong> remain yours. We will hand over your mailbox data on request.</li>
            </ul>

            <h2>4. Payment</h2>
            <p>
                Prices shown on this website are in South African rand, exclude VAT, and are an
                indication only. A price is confirmed solely in a written quotation from us.
            </p>
            <ul>
                <li>Nothing is payable until your site goes live.</li>
                <li>The first invoice is raised on the day the site goes live.</li>
                <li>Thereafter you are billed monthly in advance.</li>
                <li>There is no setup fee and no minimum term.</li>
            </ul>
            <p>
                If an invoice goes unpaid we will contact you. If it remains unpaid 30 days after
                its due date we may suspend the service, having given you at least 7 days' written
                warning first. Suspension is not deletion — see clause 6.
            </p>

            <h2>5. Cancelling</h2>
            <p>
                You may cancel at any time by giving us {{ \Illuminate\Support\Str::lower($brand['terms']['notice_period']) }}'s
                notice in writing, including by email. There is no penalty and no exit fee. You pay
                for the notice month and nothing after it.
            </p>
            <p>
                We may also end the arrangement on one calendar month's notice — for instance if the
                work is outside what we can support. We will help you move.
            </p>

            <h2>6. What happens when it ends</h2>
            <ul>
                <li>Your website is taken offline at the end of the notice period.</li>
                <li>Your domain is released to you or to whoever you nominate.</li>
                <li>We will export your data and mailbox contents on request, provided your account is paid up. Ask for that export within 30 days of termination.</li>
                <li>After that window we may remove your data from the live service. We continue to hold records of the engagement for the periods set out in our <a href="{{ route('privacy') }}">privacy notice</a>, because tax and company law require it of us.</li>
            </ul>

            <h2>7. What we need from you</h2>
            <p>
                We cannot build the site without your content and your answers. If a build stalls
                for more than 60 days waiting on you, we may close it and ask you to start again
                when you are ready. Nothing is charged for a build that never went live.
            </p>

            <h2>8. Acceptable use</h2>
            <p>
                You may not use the service to publish unlawful content, to send unsolicited bulk
                email, or to store material you have no right to publish — including photographs
                taken from another company's website. You are responsible for the accuracy of what
                you publish, including prices and property or vehicle details.
            </p>

            <h2>9. Availability</h2>
            <p>
                We target 99.5% uptime measured monthly, excluding scheduled maintenance, which we
                announce in advance and carry out outside business hours wherever we can. That is a
                target and not a warranty: we do not guarantee uninterrupted or error-free service,
                and we do not offer a financial service credit. What we offer is that we answer the
                phone. We are not responsible for downtime caused by your connectivity, your domain
                registrar, a third-party service, or any cause outside our reasonable control.
            </p>

            <h2>10. Liability</h2>
            <p>
                We are liable for the service we provide, but our total liability in any twelve-month
                period is limited to the fees you paid us in that period. We are not liable for
                indirect or consequential loss, including lost profit or lost business. Nothing here
                limits liability that cannot lawfully be limited, including under the Consumer
                Protection Act.
            </p>

            <h2>11. Personal information</h2>
            <p>
                We process personal information in accordance with the Protection of Personal
                Information Act. Our <a href="{{ route('privacy') }}">privacy notice</a> explains what
                we collect and why. Where we host a system that processes your customers' personal
                information, you are the responsible party and we are your operator.
            </p>

            <h2>12. Changes</h2>
            <p>
                We may change these terms or our prices on 30 days' written notice. If a change does
                not suit you, cancel — with no minimum term, you are never locked into terms you did
                not agree to.
            </p>

            <h2>13. Governing law</h2>
            <p>
                These terms are governed by the law of the Republic of South Africa.
            </p>
        </div>
    </section>

</x-layouts.site>
