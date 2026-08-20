@php $brand = config('brand'); @endphp

<x-layouts.site title="Privacy notice"
                description="How Seven Hills Digital collects, uses and protects personal information under POPIA.">

    <section class="bg-brand text-white">
        <div class="container-page py-14 sm:py-16">
            <p class="eyebrow">Privacy</p>
            <h1 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">Privacy notice</h1>
            <p class="mt-4 max-w-2xl text-base text-white/70">
                What we collect, why we collect it, and what you can make us do about it.
            </p>
        </div>
    </section>

    <section class="container-page py-14 sm:py-16">
        <div class="prose-page max-w-3xl">
            <p class="text-sm text-muted">Last updated {{ now()->format('d/m/Y') }}</p>

            <p>
                This notice is issued under the Protection of Personal Information Act, 2013
                ("POPIA"). The responsible party is {{ $brand['legal_name'] }},
                {{ $brand['contact']['city'] }}, {{ $brand['contact']['province'] }}.
            </p>

            <h2>What we collect from visitors to this site</h2>
            <p>When you send us an enquiry we collect:</p>
            <ul>
                <li>your name and, if you give it, your business name;</li>
                <li>your email address and telephone number;</li>
                <li>what you tell us about your business and what you need;</li>
                <li>the package you expressed interest in;</li>
                <li>your IP address and browser user agent, recorded automatically as proof of when and how the enquiry was submitted.</li>
            </ul>

            <h2>Why we collect it</h2>
            <p>
                To answer your enquiry, to quote, and — if you go ahead — to set up and support
                your website and email. That is the whole purpose. We do not sell personal
                information to anyone, and we do not add you to a marketing list without asking
                you separately.
            </p>

            <h2>On what basis</h2>
            <p>
                Your consent, given by ticking the box on the form, and thereafter our legitimate
                interest in servicing and administering an agreement you have entered into with us.
                You may withdraw consent at any time, though we may be unable to continue providing
                a service if you do.
            </p>

            <h2>How long we keep it</h2>
            <ul>
                <li><strong>Enquiries that go nowhere:</strong> three years from the date of the enquiry, then deleted.</li>
                <li><strong>Client records:</strong> for the life of the agreement and five years afterwards, which is what tax and company law require of us.</li>
                <li><strong>Server logs:</strong> 90 days.</li>
            </ul>

            <h2>Who we share it with</h2>
            <p>
                Our hosting and email infrastructure providers, and our accountants, each of whom
                is bound to protect it and to use it only on our instruction. We do not otherwise
                share personal information unless the law requires it.
            </p>

            <h2>Where it is stored</h2>
            <p>
                On servers we control. Where any processing takes place outside South Africa, we
                satisfy ourselves that the recipient is subject to laws or binding agreements
                providing an adequate level of protection, as section 72 of POPIA requires.
            </p>

            <h2>Your rights</h2>
            <p>Under POPIA you may:</p>
            <ul>
                <li>ask what personal information we hold about you;</li>
                <li>ask us to correct or delete anything inaccurate or no longer needed;</li>
                <li>object to processing;</li>
                <li>withdraw consent;</li>
                <li>complain to the Information Regulator.</li>
            </ul>
            <p>
                Write to <a href="mailto:{{ $brand['contact']['email'] }}">{{ $brand['contact']['email'] }}</a>
                and we will respond within 30 days.
            </p>

            <h2>The Information Regulator</h2>
            <p>
                Information Regulator (South Africa), JD House, 27 Stiemens Street, Braamfontein,
                Johannesburg 2001 — <a href="https://inforegulator.org.za">inforegulator.org.za</a>.
            </p>

            <h2>Cookies</h2>
            <p>
                This site sets one cookie, which keeps your session secure while you use a form. It
                is strictly necessary and carries no advertising or tracking. We do not use
                third-party analytics or advertising cookies on this site, and we do not load fonts
                or scripts from third-party servers — so browsing here does not disclose your visit
                to anyone else.
            </p>

            <h2>When we are the operator, not the responsible party</h2>
            <p>
                Where we host a website or system for a client, that client decides what personal
                information is collected from their customers and why. In POPIA terms they are the
                responsible party and we are their operator: we process only on their instruction,
                we keep it secure, and we notify them of any compromise. If you are a customer of
                one of our clients and want your information removed, contact that business directly
                — though you are welcome to contact us and we will pass it on.
            </p>

            <hr class="my-10 border-brand/10">

            <p class="text-sm text-muted">
                <strong>Note for {{ $brand['name'] }}:</strong> POPIA requires a registered Information
                Officer. Register yours with the Information Regulator and put their contact details
                here before this site handles live enquiries.
            </p>
        </div>
    </section>

</x-layouts.site>
