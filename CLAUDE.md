# 7Hills Digital — marketing site & sales back office

Project spec and working agreement for this codebase. Keep it current as things
change.

> Infrastructure, commercial detail and open business decisions live in the
> homelab runbook: `~/homelab-docs/7hillsdigital.md` (summarised in that repo's
> `CLAUDE.md` §5). This file is the **code-level** working agreement — the
> invariants and the reasons behind them.

## Product

The public marketing site for **7Hills Digital**, the technology arm of
**7Hills Holdings**, plus the back office that works the sales pipeline it
generates.

7Hills Digital sells **monthly subscriptions**: it builds, hosts and
supports a website, email on the client's own domain, and the back-office
software that runs on it. The commercial promise the whole site rests on is
**no upfront cost, no minimum term, one month's notice to cancel**.

**Visitors** can browse the home page, the packages and each package's own page,
how-it-works, `/custom-and-it`, the demos and each demo tenant, about, terms and
the privacy notice, and submit an enquiry from `/contact` (optionally deep-linked
from a package: `/contact?package=showroom`).

**The five packages are a starting point, not a limit.** `/custom-and-it` and a
section on the home page say so explicitly — custom software, digitising paper,
automation, integrations, IT support and hardware procurement. A prospect whose
trade is not on the list must never conclude we cannot help them.
`PublicSiteTest::test_the_site_says_we_are_not_limited_to_the_five_packages`
guards that message.

**Staff** (logged in at `/admin`) can work the enquiry pipeline and edit the
packages, their copy and — the point of the whole panel — **their prices**.

## The five product lines

Each line has two storage tiers (5 and 10), seeded by `PackageSeeder`. Pricing
is flat: **R880** for any 5, **R1 180** for any 10, excl. VAT.

| Tier | Email storage | Website storage | Addresses | Price |
|---|---|---|---|---|
| 5 | 5GB | 5GB | Unlimited | R880 |
| 10 | 10GB | 10GB | Unlimited | R1 180 |

**The build is free.** Nothing is charged for design and development — that is
what the monthly fee buys back over time, and it is why the software stays ours.

| Line | Vertical | Slug |
|---|---|---|
| Listings | Estate agencies | `listings` |
| Showroom | Car dealerships | `showroom` |
| Careers | Recruitment and staffing agencies | `careers` |
| Stay | Guesthouses, lodges and B&Bs | `stay` |
| Business | Everyone else (attorneys, schools, funeral homes, plant hire…) | `business` |

`Business` is not the leftovers bin — it is the **core engine with configurable
labels**, and the four vertical packages are meant to become configurations of
it rather than four separate codebases. Build shared capability into Business
first.

## Add-on modules

Optional business software, **charged separately** from the package fee and
seeded by `AddOnSeeder` into the `add_ons` table:

| Module | Slug | Price |
|---|---|---|
| Invoicing | `invoicing` | **R109** / month, flat — so the 5 tier is R880 + R109 |
| Enterprise email | `enterprise-email` | Quoted on mailbox count and storage |
| CRM | `crm` | Quoted on contact volume and seats |
| ERP | `erp` | Scoped and quoted on transaction volume and modules |

They appear as their own section on `/packages` (`#add-ons`), as a one-line
strip on the home page, and are edited at `/admin/add-ons`.

**None of this is built yet** — the site sells the offer. See open decisions.

## Stack

- **Laravel 12** on **PHP 8.4**, **MySQL 8**
- **Filament v4** admin panel at `/admin`, session-authenticated
- Public site: **Blade + Tailwind CSS v4 + Alpine.js**, built with Vite
- **Docker Compose** (`app`, `nginx`, `mysql`); nginx on host port **8091**,
  MySQL exposed on **33063** (8080 btmedical, 8090 excellent, 3700
  listing-scout are already taken on this host)
- Timezone **Africa/Johannesburg**, currency **ZAR**, dates **dd/mm/yyyy**

Deliberately mirrors the Excellent Real Estate stack so both projects stay in
step. Filament v5 exists but this targets **v4**: layout components live under
`Filament\Schemas\Components\*` and actions are unified under
`Filament\Actions\*`.

## Key cross-cutting rules

- **`config/brand.php` is the single source of truth** for colours, logo paths,
  typography, contact details and the commercial terms. Colours are emitted as
  CSS variables in the page `<head>` from the config array (add a colour there
  and it appears automatically) and consumed by Tailwind's `@theme`; they also
  feed the Filament panel. Never hard-code a brand colour, a price or a notice
  period anywhere else — the terms strip on `/packages` and the terms page both
  quote `brand.terms`.
- **The brand is the official 7Hills kit**, in `public/images/brand/`:
  `Brand_Guidelines_And_Color_Codes.html` is the authority. Do not introduce a
  colour, typeface or logo treatment that is not in that document.
  | Token | Colour | Use |
  |---|---|---|
  | `brand` | Deep Forest `#1A2E22` | dark sections, headings |
  | `accent` | Emerald Forest `#417956` | accents and CTAs **on light** |
  | `mint` | Vibrant Mint `#5EB87E` | accents and CTAs **on dark** |
  | `ink` | Charcoal Black `#111111` | body copy |
- **`accent` and `mint` are not interchangeable.** Emerald on Deep Forest is
  unreadable, so every dark section uses `mint`, `btn-mint` and `eyebrow-light`;
  every light section uses `accent`, `btn-accent` and `eyebrow`. Check the
  background before you pick.
- **Typography is Montserrat (headings) and Plus Jakarta Sans (body)**, declared
  in `vite.config.js` and **self-hosted** — fetched from the `@fontsource/*`
  packages at build time and served from our own origin. Never link a font from
  Google at runtime; `PublicSiteTest::test_no_page_loads_a_third_party_asset`
  fails the build if anything does.
- **The logo artwork is used as supplied.** `logo.png` and `logo-inverse.png`
  are the 2400px transparent originals with the dead space trimmed so the
  lockup fills its box; the originals are kept alongside them. The boxed lockup
  already contains the wordmark, so never set "7Hills Digital" in HTML
  beside it — and never render it below ~56px, where the wordmark stops being
  legible.
- **Prices live in the database, never in a Blade file.** `PackageSeeder` sets
  what a fresh install starts with; the live numbers are edited at
  `/admin/packages` under the Tiers relation manager. `PublicSiteTest` guards
  this by changing a price in the database and asserting the page follows.
- **Pricing is flat across every product line** — **R880** for any 5 tier,
  **R1 180** for any 10 tier, excl. VAT. The engine underneath is the same for
  a dealership and a guesthouse, so charging them differently for it is
  arbitrary and indefensible on a sales call. What varies is the tier: how many
  people need email. `test_pricing_is_flat_across_every_line` guards this.
- **Email storage and website storage are metered separately, and symmetrically.**
  5GB of each on the 5 tier, 10GB of each on the 10. A dealership with 60 cars
  will use gigabytes of photographs, and those photographs must never be able to
  fill the sales inbox — so `mail_gb` and `media_gb` stay separate columns and
  are quoted separately on the site. **Do not pool them.**
- **Email addresses are unlimited.** `mailboxes` is nullable and null means
  unlimited, which is what every tier sells. They all draw on the same email
  allowance, so counting them earns nothing and costs goodwill.
- **Who owns what is stated on the packages page, not just in `/terms`.** The
  build is free, the software stays ours, the client's data, email and domain
  are theirs, and a build-and-own buy-out is available on quote. `brand.ownership`
  holds the wording; both pages quote it. A client should not have to reach
  clause 3 to learn the site is rented rather than bought.
- **Add-on modules are charged separately and never bundled.** `AddOn` records
  (Invoicing, CRM, ERP) sit outside the package fee, are priced in the database
  like everything else, and the packages page says "charged separately" in so
  many words. A client who finds a module they never asked for on their first
  invoice is a client who cancels — and with no minimum term, cancelling costs
  them nothing. `AddOnTest` guards the wording.
- **A module is either flat-priced or metered, never both.** `price_monthly`
  set means a rand figure is shown (Invoicing, R109); `price_monthly` null means
  `price_basis` is shown instead (CRM, ERP — quoted on transaction volume).
  Quoting one number for a metered module means overcharging the small client
  or losing money on the large one.
- **The site never refers work to another supplier.** We bill for everything in
  IT ourselves, on one invoice, so there is no "we would refer that elsewhere"
  anywhere on the site. `LegalPagesTest::test_the_site_never_refers_work_elsewhere`
  fails the build if that language comes back.
- **Legal pages are drafted in the company's favour, within the law.** The
  privacy notice leans on contract, legal obligation and legitimate interest
  ahead of consent (consent can be withdrawn), ties retention to statutory
  minimums, reserves business-transfer sharing and anonymised reuse, and warrants
  nothing about security beyond s19's "reasonable measures". What it does **not**
  do is remove a statutory right — s23/s24 access and correction, s11(3)
  objection, s69 marketing opt-out and the Regulator complaint route are all
  stated, because a notice that overreaches invites enforcement rather than
  deterring claims.
- **We are the operator, not the responsible party, for client systems.** Clause 2
  of the privacy notice says so plainly. That is both correct under POPIA s20 and
  the single most useful risk allocation on the site: obligations to a client's
  own customers sit with the client.
- **`/terms` and `/privacy` must not contradict each other**, most easily on
  retention. Terms defer to the privacy notice for how long records are kept;
  a test asserts it.
- **`/about` carries the ECTA s43 disclosures** — registered name, registration
  number, directors, physical and service addresses, service description, price
  basis. Blank statutory fields render as an em-dash rather than being hidden,
  so an unfilled one is obvious.
- **Hardware procurement stays quiet.** One small line on `/packages` and one in
  the footer, via `<x-hardware-note>` reading `brand.hardware.blurb`. No card,
  no heading, no section. We will happily source laptops and routers, but
  giving it real estate makes us read as a reseller rather than a software
  company.
- **Enquiries are evidence.** They arrive `new`, nothing auto-advances them, and
  what the prospect submitted is **read-only in the panel** —
  `EnquiryForm` marks those fields `disabled()->dehydrated(false)` so a save
  cannot rewrite what was actually said. `EnquiryResource::canCreate()` returns
  `false`: there is no way to hand-craft one.
- **The lead is saved before the notification is sent.** `EnquiryController`
  wraps the mail in a try/catch and logs failures. A mail outage must never cost
  an enquiry at the door — better delivered late from `/admin` than lost.
  `EnquiryTest::test_a_mail_failure_does_not_cost_us_the_lead` guards this.
- **References are generated, never typed** — `ENQ-YYYY-00001`, zero-padded so
  `LENGTH() DESC, reference DESC` sorts numerically on any database.
- **Email validation is `rfc` only, not `dns`.** A DNS hiccup rejecting a real
  prospect is worse than accepting a typo we can phone about.
- **POPIA**: explicit consent checkbox on the public form, `<x-popia-notice>`
  rendered *above* it (the Act requires telling the data subject what is
  collected and why at the point of collection — a tick and a policy link are not
  enough), soft deletes throughout, IP and user agent recorded for provenance.
- **The public form carries a honeypot** (`website` field, must be empty, rule
  `prohibited`) and is rate limited to 10/minute.
- **A tier must belong to the package it is submitted with**, checked in
  `StoreEnquiryRequest::after()` — otherwise a crafted post could pair "Business"
  with the Showroom price.
- **No third-party assets.** No Google Fonts, no CDN scripts, no analytics
  cookies. A site that sells POPIA compliance should not ship every visitor's IP
  to someone else to fetch a font. The privacy notice says so; keep it true.
- **`User` implements `FilamentUser`.** Without `canAccessPanel()` Filament
  refuses panel access outside `local`, which would lock the back office in
  production. There is no public registration route — every user is staff.
- **Admin panel styling** is injected as plain CSS through a render hook
  (`resources/views/components/filament-styles.blade.php`) rather than a compiled
  Filament theme, so restyling the back office needs no Vite build.
- **`<x-icon>` is taken by Filament's blade-icons.** The package vertical icons
  are `<x-package-icon>`.
- **Custom CSS classes are declared with `@utility`, not `@layer components`.**
  Tailwind v4 can only `@apply` a custom class that was registered as a utility.
- **Layout uses the shared design utilities, not ad-hoc class soup.** `section` /
  `section-tight` for vertical rhythm, `h-display` / `h-section` / `lede` for
  type, `card` / `card-hover` for surfaces, `glow` for the blurred shapes behind
  dark sections. Change the rhythm of the whole site in one place; do not
  reintroduce `py-16 sm:py-20` by hand.
- **`data-reveal` is decoration and must never hide content.** The CSS hides
  those elements and `app.js` reveals them on intersection; anything without JS,
  or with `prefers-reduced-motion`, sees the page in full. Never put content
  behind it that the page needs in order to make sense.
- **`[x-cloak]` must stay in the base layer.** Without it the mobile menu and
  its close icon flash on every page load.
- **The desktop nav switches at `lg`, not `md`.** Five nav items plus a CTA and
  the logo do not fit at 768px.
- **Body text must clear 4.5:1.** `muted` is `#64726A` (5.05:1 on white) rather
  than something prettier and lighter — the earlier value failed AA. Emerald on
  Deep Forest is 2.81:1, which is why `mint` exists.

## Demos

Five fictional tenants, one per line, defined in **`config/demos.php`** and
rendered by a single template (`demos/show.blade.php`) inside `x-layouts.demo`:

| Demo | Slug | Package |
|---|---|---|
| 7Hills Realty | `realty` | Listings |
| 7Hills Auto | `auto` | Showroom |
| 7Hills Careers | `careers` | Careers |
| 7Hills Stay | `stay` | Stay |
| 7Hills Plant Hire | `plant` | Business |

Rules these must keep:

- **Everything in them is invented** — the businesses, the stock, the prices,
  the reviews. Nothing is taken from a real agency, dealer or portal.
- **Photographs are downloaded, never hotlinked.** `php artisan demos:import-images`
  pulls from **Openverse**, restricted to licences permitting commercial use
  (`cc0,pdm,by,by-sa`), re-encodes them and serves them from our own origin —
  the no-third-party-assets rule applies to the demos too. `<x-demo-art>` falls
  back to a composed SVG when the files are absent, so a fresh checkout never
  renders broken. Never scrape a portal or a dealer's stock.
- **The importer filters on quality, because Openverse skews archival.** It
  rejects images below 900px, outside a 1.15–2.2 aspect ratio, below 0.12 mean
  saturation (which catches scanned monochrome), and anything whose title or
  tags name a blocked subject — police, military, museum, wreck, vintage and so
  on. Without those gates a search for "car" returns 1950s dealership archives
  and liveried police vehicles.
- **CC BY / CC BY-SA oblige us to credit the photographer.** `DemoImages::attributions()`
  builds the credit list and the demo footer renders it. Do not strip it while a
  licensed photo is still in use.
- **Each tenant carries its own palette**, and that is a deliberate exception to
  the 7Hills colour rule above — five identical green sites would prove nothing.
  The demo's job is to show a prospect that the site carries *their* brand. The
  7Hills palette governs the marketing site only.
- **The enquiry form is inert** (`disabled`, `onsubmit="return false"`). A demo
  must never collect a real person's details.
- **`noindex, nofollow` on every demo**, so a fiction never outranks the real
  site or a client's.
- **Every demo closes with the back-office pitch**, because that section is the
  actual sales conversation — the public half is not what earns the fee. Prices
  in it come from the live `PackageTier`, so a price change follows everywhere.

`DemoSiteTest` guards all of the above.

**Do not demo Excellent Real Estate to prospects.** It is a live client with real
leads and real applicant ID documents in its back office; walking a prospect
through it is a POPIA problem. These fictional tenants exist precisely so nobody
is tempted.

## Open decisions — resolve before this site sees a real prospect

> The statutory items below (2, 3 and 4) have step-by-step instructions, and the
> exact `.env` keys, in `~/homelab-docs/TODO.md` §3.

1. **No warning is sent before an allowance runs out.** Email and website
   storage are separate and symmetric (5GB + 5GB, or 10GB + 10GB), which stops
   photographs eating the mail quota — but nothing tells a client they are close
   to either limit. See item 6.
2. **`/terms` and `/privacy` need an attorney's eye** before they govern a
   paying relationship. Both are written to be compliant and company-favourable,
   but neither is drafted by a lawyer.
3. **POPIA requires a registered Information Officer.** Register one with the
   Information Regulator and set `COMPANY_INFORMATION_OFFICER` in `.env` — until
   then clause 1 of the privacy notice names nobody, which is itself a
   contravention.
4. **The ECTA s43 fields in `config/brand.php` are blank.** Registration number,
   directors, VAT number, physical address and address for service of legal
   documents all render as "—" on `/about`. They are blank rather than invented
   on purpose: publishing a fabricated company number would be a
   misrepresentation. Fill them from the CIPC documents.
5. **No storage meter yet.** The pricing page promises we warn clients at 80% of
   their mail allowance. Nothing implements that.
6. **The add-on modules do not exist as software yet.** The site sells
   Invoicing, CRM and ERP; only the offer is built. Do not demo what is not
   there.
7. **The demo tenants have no back office.** They are public landing pages only.
   The walkthrough they invite has to be given live, off a real install.
8. **E-commerce is advertised but not specified.** `/demos` offers an online
   shop "priced separately"; there is no package, no price and no build behind
   that yet.
9. **Demo photographs are of other people's houses, cars and machines.** Fine
   for a page whose own ribbon says everything is invented; a real client's site
   must use photographs of the real thing.

Settled since the first build: prices (flat R880 / R1 180, build free), the
contact number (068 859 6695, phone and WhatsApp), the brand name (**7Hills**,
never spelt out as "Seven Hills" — a test enforces it), and the brand kit.

## Version control

Git repo on `main`, initialised 20/08/2026. Commit style: explain **why**, not
just what — the initial commit records the reasoning behind the invariants above.

**Not yet pushed to Gitea** (`http://192.168.90.164:3000`, hlab001). The push
token in `~/homelab-docs/secrets.md` is revoked; see §9 of
`~/homelab-docs/7hillsdigital.md` for the two commands that finish it. The repo
should be **private** — it carries pricing strategy, commercial terms and
infrastructure detail.

`/opt/docker/excellent` also has a `.git` dir with no remote configured. Neither
project has been pushed anywhere.
