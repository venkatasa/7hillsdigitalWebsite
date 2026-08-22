# Seven Hills Digital — marketing site & sales back office

Project spec and working agreement for this codebase. Keep it current as things
change.

> Infrastructure, commercial detail and open business decisions live in the
> homelab runbook: `~/homelab-docs/7hillsdigital.md` (summarised in that repo's
> `CLAUDE.md` §5). This file is the **code-level** working agreement — the
> invariants and the reasons behind them.

## Product

The public marketing site for **Seven Hills Digital**, the technology arm of
**Seven Hills Holdings**, plus the back office that works the sales pipeline it
generates.

Seven Hills Digital sells **monthly subscriptions**: it builds, hosts and
supports a website, email on the client's own domain, and the back-office
software that runs on it. The commercial promise the whole site rests on is
**no upfront cost, no minimum term, one month's notice to cancel**.

**Visitors** can browse the home page, the packages and each package's own page,
how-it-works, demos, about, terms and the privacy notice, and submit an enquiry
from `/contact` (optionally deep-linked from a package: `/contact?package=showroom`).

**Staff** (logged in at `/admin`) can work the enquiry pipeline and edit the
packages, their copy and — the point of the whole panel — **their prices**.

## The five product lines

Each line has two storage tiers (5 and 10), seeded by `PackageSeeder`. Pricing
is flat: **R880** for any 5, **R1 080** for any 10, excl. VAT.

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
| Invoicing | `invoicing` | **R109** / month, flat |
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
  already contains the wordmark, so never set "Seven Hills Digital" in HTML
  beside it — and never render it below ~56px, where the wordmark stops being
  legible.
- **Prices live in the database, never in a Blade file.** `PackageSeeder` sets
  what a fresh install starts with; the live numbers are edited at
  `/admin/packages` under the Tiers relation manager. `PublicSiteTest` guards
  this by changing a price in the database and asserting the page follows.
- **Pricing is flat across every product line** — **R880** for any 5 tier,
  **R1 080** for any 10 tier, excl. VAT. The engine underneath is the same for
  a dealership and a guesthouse, so charging them differently for it is
  arbitrary and indefensible on a sales call. What varies is the tier: how many
  people need email. `test_pricing_is_flat_across_every_line` guards this.
- **Mail storage and site storage are metered separately.** A dealership with
  60 cars will use gigabytes of photographs, and those photographs must never be
  able to fill the sales inbox. `mail_gb`/`mailboxes` and `media_gb` are separate
  columns on `package_tiers` and are quoted separately on the site. Do not pool
  them back together.
- **Mailboxes are counted; aliases and forwarders are not.** Aliases are routing
  rules and cost nothing to carry, which is how "unlimited email addresses" and
  "5 mailboxes at 1GB each" are both true at once.
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

## Demos

`Package::$demo_url` is null for every package. The demos page renders "Demo
being built — ask us for a walkthrough" rather than a broken link, which is the
honest state until the demo tenants exist.

**Do not demo Excellent Real Estate to prospects.** It is a live client with real
leads and real applicant ID documents in its back office; walking a prospect
through it is a POPIA problem. Build fictional demo tenants instead.

## Open decisions — resolve before this site sees a real prospect

1. **Contact details are placeholders** — `COMPANY_PHONE` and `COMPANY_WHATSAPP`
   in `.env` are `+27 00 000 0000`. This is the last placeholder on the site.
2. **The mail/media split is an assumption**, not a signed-off decision — 5GB
   mail (5 × 1GB) + 10GB site media on the entry tier, doubling on the 10 tier.
3. **`/terms` needs an attorney's eye** before it governs a paying relationship,
   and it does not yet mention the add-on modules or hardware procurement.
4. **POPIA requires a registered Information Officer.** Register one with the
   Information Regulator and put their details in the privacy notice.
5. **No storage meter yet.** The pricing page promises we warn clients at 80% of
   their mail allowance. Nothing implements that.
6. **The add-on modules do not exist as software yet.** The site sells
   Invoicing, CRM and ERP; only the offer is built. Do not demo what is not
   there.

Settled since the first build: prices (flat R880 / R1 080), and the brand —
the official 7Hills kit is applied throughout.

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
