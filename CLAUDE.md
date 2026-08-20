# Seven Hills Digital — marketing site & sales back office

Project spec and working agreement for this codebase. Keep it current as things
change.

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

Each line has two storage tiers (5 and 10), seeded by `PackageSeeder`:

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
  contact details and the commercial terms. Colours are emitted as CSS variables
  in the page `<head>` and consumed by Tailwind's `@theme`, and are also fed to
  the Filament panel. Never hard-code a brand colour, a price or a notice period
  anywhere else — the terms strip on `/packages` and the terms page both quote
  `brand.terms`.
- **Prices live in the database, never in a Blade file.** `PackageSeeder` sets
  placeholders on a fresh install; the real numbers are edited at
  `/admin/packages` under the Tiers relation manager. `PublicSiteTest` guards
  this by changing a price in the database and asserting the page follows.
- **Mail storage and site storage are metered separately.** A dealership with
  60 cars will use gigabytes of photographs, and those photographs must never be
  able to fill the sales inbox. `mail_gb`/`mailboxes` and `media_gb` are separate
  columns on `package_tiers` and are quoted separately on the site. Do not pool
  them back together.
- **Mailboxes are counted; aliases and forwarders are not.** Aliases are routing
  rules and cost nothing to carry, which is how "unlimited email addresses" and
  "5 mailboxes at 1GB each" are both true at once.
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

1. **Prices are placeholders.** Every number in `PackageSeeder` is a guess at
   what a Gauteng SMB will carry monthly. Set the real ones at `/admin/packages`.
2. **The mail/media split is an assumption**, not a signed-off decision — 5GB
   mail (5 × 1GB) + 10GB site media on the entry tier, doubling on the 10 tier.
3. **Contact details are placeholders** — `COMPANY_PHONE` and `COMPANY_WHATSAPP`
   in `.env` are `+27 00 000 0000`.
4. **The brand palette is a placeholder** (navy + teal, chosen to sit apart from
   Excellent's charcoal + gold). Real artwork goes in `public/images/brand/` as
   `logo.png` / `logo-inverse.png` and replaces the composed lockup wholesale.
5. **`/terms` needs an attorney's eye** before it governs a paying relationship.
6. **POPIA requires a registered Information Officer.** Register one with the
   Information Regulator and put their details in the privacy notice.
7. **No storage meter yet.** The pricing page promises we warn clients at 80% of
   their mail allowance. Nothing implements that.
