@php
    $brand = config('brand');
    $legal = $brand['legal'];
    $officer = $legal['information_officer'] ?: 'the Information Officer';
    $officerEmail = $legal['information_officer_email'] ?: $brand['contact']['email'];
@endphp

<x-layouts.site title="Privacy notice"
                description="How 7Hills Digital collects, uses and protects personal information under the Protection of Personal Information Act, 2013.">

    <section class="bg-brand text-white">
        <div class="container-page section-tight">
            <p class="eyebrow-light">Privacy</p>
            <h1 class="mt-4 h-display">Privacy notice</h1>
            <p class="mt-6 max-w-2xl lede text-white/75">
                Issued in terms of the Protection of Personal Information Act, 2013 ("POPIA").
                It explains what we collect, why we are entitled to collect it, and what you
                may require of us.
            </p>
        </div>
    </section>

    <section class="container-page section-tight">
        <div class="prose-page max-w-3xl">
            <p class="text-sm text-muted">
                Version 1.0, effective {{ now()->format('d/m/Y') }}. This notice applies to
                {{ $brand['legal_name'] }} and supersedes any earlier version.
            </p>

            <h2>1. Who is responsible for your information</h2>
            <p>
                The responsible party is <strong>{{ $brand['legal_name'] }}</strong>,
                trading as <strong>{{ $brand['trading_as'] }}</strong>
                @if ($legal['registration_number'])
                    (registration number {{ $legal['registration_number'] }})
                @endif
                , a private company incorporated in the {{ $legal['place_of_registration'] }},
                of {{ $legal['physical_address'] ?: $brand['contact']['city'].', '.$brand['contact']['province'] }}.
            </p>
            <p>
                Enquiries about this notice, and any request under POPIA or the Promotion of
                Access to Information Act, 2000 ("PAIA"), must be directed to {{ $officer }} at
                <a href="mailto:{{ $officerEmail }}">{{ $officerEmail }}</a>. We are not obliged
                to act on a request made through any other channel.
            </p>

            <h2>2. When we are not the responsible party</h2>
            <p>
                <strong>This is the most important clause in this notice.</strong> Where we
                build, host or support a website or system for a client, that client decides
                what personal information is collected from their own customers and for what
                purpose. In POPIA terms <strong>the client is the responsible party and we act
                only as their operator</strong> under section 20.
            </p>
            <p>
                In that role we process personal information solely on the client's documented
                instruction, we treat it as confidential, and we notify the client of any
                compromise we become aware of. The obligations owed to that client's customers
                — including the issuing of a compliant privacy notice, obtaining any consent
                required, and answering data subject requests — rest with the client and not
                with us. If you are a customer of one of our clients, your request must be
                directed to that business. We may, at our discretion and without obligation,
                pass it on.
            </p>

            <h2>3. What we collect</h2>
            <p>Directly from you, when you enquire, contract with us or use our services:</p>
            <ul>
                <li>identifying and contact details — name, business name, email address, telephone number, and your role in the business;</li>
                <li>the content of your enquiry and of our subsequent correspondence;</li>
                <li>the package, module or service you expressed an interest in;</li>
                <li>billing and account information, including banking details where you pay us by debit order or transfer;</li>
                <li>information you or your staff submit when using an administration panel we host, including account credentials in hashed form.</li>
            </ul>
            <p>Automatically, when you use this website:</p>
            <ul>
                <li>your IP address, browser user agent, referring page, and the date and time of your request, recorded in server logs and against any form you submit;</li>
                <li>the session cookie described in clause 10.</li>
            </ul>

            <h2>4. Why we process it, and on what lawful basis</h2>
            <p>
                We rely on the justifications in section 11 of POPIA. Where a justification
                other than consent is available to us, we rely on it.
            </p>
            <ul>
                <li><strong>Performance of a contract</strong> (s11(1)(b)) — to quote for, build, host, support and bill the services you have asked us for.</li>
                <li><strong>Compliance with an obligation of law</strong> (s11(1)(c)) — to keep the accounting, tax and company records the Tax Administration Act, the Value-Added Tax Act and the Companies Act require of us.</li>
                <li><strong>Our legitimate interests, and those of our clients</strong> (s11(1)(f)) — to administer and secure our systems, prevent and investigate fraud and abuse, keep a record of what was said and agreed, recover amounts owing to us, establish, exercise or defend legal claims, and improve and develop our services.</li>
                <li><strong>Consent</strong> (s11(1)(a)) — where the law requires it and no other justification is available, most obviously for electronic direct marketing to a person who is not already our customer.</li>
            </ul>
            <p>
                Withdrawing consent does not affect processing already carried out, nor
                processing we are entitled to carry out on any other basis, and may mean we can
                no longer provide the service.
            </p>

            <h2>5. Direct marketing</h2>
            <p>
                Where you are already our customer, section 69(3) of POPIA permits us to market
                our own similar services to you by electronic means. You may object at any time
                and we will stop. We will not send you electronic direct marketing on behalf of
                a third party without your consent, and we do not sell your details to anybody.
            </p>

            <h2>6. Who we share it with</h2>
            <ul>
                <li><strong>Operators acting on our instruction</strong> — hosting, connectivity, email delivery, backup and payment providers, each bound to process only as instructed and to keep the information secure.</li>
                <li><strong>Our professional advisers</strong> — accountants, auditors, attorneys and insurers, where they need it to advise us.</li>
                <li><strong>Debt recovery and credit control</strong> — where an amount is owing to us and remains unpaid.</li>
                <li><strong>A purchaser or successor</strong> — if our business, or a part of it, is sold, merged or reorganised, information held in that part of the business transfers with it, subject to this notice.</li>
                <li><strong>Where the law obliges or permits us</strong> — to a court, regulator, the Information Regulator, or a law enforcement agency.</li>
            </ul>

            <h2>7. Sending information outside South Africa</h2>
            <p>
                Some of our operators process information outside the Republic. We transfer
                personal information across a border only where section 72 permits it — because
                the recipient is subject to a law, binding corporate rules or a binding agreement
                that upholds principles substantially similar to POPIA, because the transfer is
                necessary to perform our contract with you, or because you have consented.
            </p>

            <h2>8. How long we keep it</h2>
            <p>
                We keep personal information for as long as the purpose requires and thereafter
                for as long as the law permits or obliges us to. In particular:
            </p>
            <ul>
                <li><strong>Accounting, tax and company records</strong> — at least five years, as the Tax Administration Act and the Companies Act require.</li>
                <li><strong>Client records and correspondence</strong> — for the life of the agreement and for a further five years after it ends.</li>
                <li><strong>Enquiries that do not become clients</strong> — up to three years from the last contact, so that we can recognise you if you come back and so we have a record of what was said.</li>
                <li><strong>Server logs</strong> — up to twelve months.</li>
                <li><strong>Anything relevant to a dispute, investigation or legal claim</strong> — until that matter and any period of prescription or appeal has finally ended, regardless of the periods above.</li>
            </ul>
            <p>
                Section 15 of POPIA permits us to retain records for longer than these periods
                for historical, statistical or research purposes, subject to appropriate
                safeguards. We may also keep information in an <strong>anonymised or aggregated
                form indefinitely</strong>. Once information can no longer identify anybody it
                is not personal information, and this notice does not restrict our use of it.
            </p>

            <h2>9. Security</h2>
            <p>
                We take the reasonable technical and organisational measures required by section
                19 of POPIA to secure the integrity and confidentiality of personal information
                in our possession — including transport encryption, access control, hashed
                credentials, private storage for uploaded documents, and regular backups.
            </p>
            <p>
                <strong>No system is impenetrable and we do not warrant that ours is.</strong>
                We do not accept responsibility for a compromise arising from a cause outside our
                reasonable control, from your own act or omission, or from the loss or misuse of
                credentials issued to you or your staff. You must keep those credentials
                confidential and tell us immediately if you believe they have been compromised.
                Where a compromise does occur, we will notify the Information Regulator and the
                affected data subjects as section 22 requires.
            </p>

            <h2>10. Cookies</h2>
            <p>
                This site sets two cookies, both strictly necessary and neither carrying an
                advertising or tracking identifier:
            </p>
            <ul>
                <li><strong>a session cookie</strong>, which remembers your form entries between pages; and</li>
                <li><strong>an XSRF-TOKEN cookie</strong>, which proves a form was submitted from this site and not forged by another.</li>
            </ul>
            <p>
                Because both are strictly necessary for a service you have asked for,
                they do not require your consent. Dismissing the cookie notice at the foot of the
                page stores a single preference in your browser's local storage — not a cookie —
                so that we do not show it to you again. We use no analytics or advertising cookies, and we
                load no fonts, scripts, images or other assets from third-party servers, so
                visiting this site does not disclose your visit to anyone else. Administration
                panels we host for clients additionally set an authentication cookie, which is
                equally necessary.
            </p>

            <h2>11. Your rights, and how to exercise them</h2>
            <p>Subject to POPIA and PAIA, you may:</p>
            <ul>
                <li>ask whether we hold personal information about you, and ask for a description of it (s23);</li>
                <li>ask us to correct or delete information that is inaccurate, irrelevant, excessive, out of date, incomplete, misleading or unlawfully obtained (s24);</li>
                <li>object, on reasonable grounds, to processing carried out under s11(1)(d) to (f) (s11(3));</li>
                <li>withdraw a consent you have given, subject to clause 4;</li>
                <li>object to direct marketing at any time; and</li>
                <li>complain to the Information Regulator.</li>
            </ul>
            <p>
                We may require you to satisfy us of your identity before we act, and to make the
                request on the prescribed form. A request for access is dealt with under PAIA and
                may attract the prescribed fee. We will respond within 30 days, or such longer
                period as PAIA allows. <strong>We may refuse a request</strong> where the law
                permits or requires us to — including where the information is needed for a legal
                claim, where retention is required by law, where the request is manifestly
                unfounded or excessive, or where granting it would disclose the personal
                information of another person.
            </p>

            <h2>12. The Information Regulator</h2>
            <p>
                Information Regulator (South Africa), JD House, 27 Stiemens Street, Braamfontein,
                Johannesburg 2001 —
                <a href="https://inforegulator.org.za">inforegulator.org.za</a>,
                complaints: <a href="mailto:complaints.IR@justice.gov.za">complaints.IR@justice.gov.za</a>.
                We would ask you to raise the matter with us first.
            </p>

            <h2>13. Our information, and this website</h2>
            <p>
                The content, design, software and structure of this website are ours or our
                licensors', and are protected by the Copyright Act, 1978. You may view and print
                pages for your own use. You may not copy, republish, scrape, mine or reuse them
                commercially without our written permission.
            </p>
            <p>
                Everything on this site is general information about our services. It is not
                professional, legal, financial or technical advice, and it is not an offer capable
                of acceptance. Prices, specifications and availability may change, and are
                confirmed only in a written quotation from us.
            </p>

            <h2>14. Changes to this notice</h2>
            <p>
                We may amend this notice from time to time. The version published on this page is
                the one that applies, and it takes effect when published. Where a change
                materially reduces your rights we will take reasonable steps to bring it to your
                attention. Continuing to use this website or our services after a change means
                you accept the amended notice.
            </p>

            <h2>15. Governing law</h2>
            <p>
                This notice is governed by the law of the Republic of South Africa, and the
                courts of the Republic have exclusive jurisdiction over any dispute arising from
                it.
            </p>
        </div>
    </section>

</x-layouts.site>
