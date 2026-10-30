# 7Hills Digital — Website Revamp Audit & Redesign Plan

**Date:** 30/10/2026  **Status:** For approval. Nothing has been implemented.
**Scope:** Read-only audit of `/opt/docker/7hillsdigital`, plus a plan to reposition the
site as a B2B software development and digital solutions company serving SMEs and
enterprise customers.

---

## 0. How this audit was done

- **Read-only.** No production file, database, container or deployment
  configuration was changed. This file is the only thing written, and it is
  untracked and uncommitted.
- Every PHP class, route, config file, migration, seeder, Blade view, the CSS and
  JS entry points, the Vite config, Docker Compose and all 8 feature test files
  were read.
- **The test suite was not run.** The only PHP runtime on the host is the live
  `7hills-app` container, and the brief said not to touch production. Tests use
  SQLite `:memory:` (`phpunit.xml`), so running them is safe *in a separate
  checkout*. That is step 1 of the implementation plan.
- The market findings in §9 come from research done earlier in this session:
  12 months of Google Trends data for South Africa (`geo=ZA`), the Xero *State of
  South African Small Business 2026* report, and 2026 SA press coverage.

---

## 1. Executive summary

The codebase is **small, clean and unusually well-guarded**: about 3 500 lines of
Blade, 4 models, 4 controllers, 3 Filament resources and **58 feature test methods (75 test runs)** that
enforce commercial and legal rules (flat pricing, POPIA, no third-party assets,
the honest-demo rules). That is a strong base. The revamp is a
**content-and-information-architecture change on top of the existing engine,
not a rebuild.**

What blocks the B2B and enterprise positioning today:

1. **The site sells one thing: a rented website in five verticals.** Custom
   software, business systems and IT are pushed onto one secondary page
   (`/custom-and-it`). An enterprise buyer arriving on the home page reads
   "R880 website for estate agents".
2. **There is no data model for what we would actually sell next** —
   solutions, industries and case studies. Everything outside a package is
   hard-coded copy.
3. **The enquiry form is package-centric.** A request for "a CRM for 40 users"
   or "integrate Sage with our ERP" has nowhere structured to go.
4. **Proof is thin.** One live client and one hosted third-party module. Enterprise
   buyers expect case studies, process, security posture and procurement documents
   (B-BBEE, tax clearance, CSD number).
5. **Several operational risks** need fixing regardless of the redesign (§7).

**Recommendation:** reposition around **"Business software and digital solutions
for South African companies"**. Lead with the problems SMEs are known to pay to
solve (getting paid, admin, compliance, customer management). Keep the five
website packages intact as the *entry product*, and add a separate, quote-based
**Enterprise / Custom development** track. Do it in four phases on a branch in a
separate checkout, never in place (§13).

---

## 2. Architecture as it stands

### 2.1 Stack and infrastructure

| Layer | Actual | Note |
|---|---|---|
| Framework | **Laravel `^13.17`** (`composer.json`) | ⚠ CLAUDE.md says Laravel 12. The documentation has drifted. |
| PHP | 8.4 image, `composer.json` allows `^8.3` | |
| Admin | Filament v4 at `/admin` | `Filament\Schemas` / `Filament\Actions` namespaces |
| Front end | Blade + Tailwind CSS v4 + Alpine.js 3 (+ collapse), Vite 8 | No React/Vue/Livewire on the public site |
| Fonts | Montserrat + Plus Jakarta Sans, self-hosted via `laravel-vite-plugin/fonts` | Guarded by `test_no_page_loads_a_third_party_asset` |
| DB | MySQL 8 (Docker volume `7hills-mysql`) | Tests run on SQLite in memory |
| Runtime | Docker Compose: `app` (php-fpm), `nginx` (host **8091**), `mysql` (host **33063**) | All up for about 2 months |
| Code mount | `./:/var/www/html` bind-mounted into `app` and `nginx` | ⚠ **Editing this directory edits the running site** (§7.1) |

### 2.2 Routes (`routes/web.php`)

| Method | URI | Handler | Data source |
|---|---|---|---|
| GET | `/` | `PageController@home` | `Package` + tiers, `AddOn`, `config/clients.php` |
| GET | `/packages` | `PackageController@index` | `Package` + tiers, `AddOn` |
| GET | `/packages/{package:slug}` | `PackageController@show` | `Package`, `config/demos.php` |
| GET | `/how-it-works` | `PageController@howItWorks` | static Blade |
| GET | `/custom-and-it` (`it-services`) | `PageController@itServices` | static Blade |
| GET | `/demos` | `PageController@demos` | `config/demos.php`, `Package` |
| GET | `/demos/{slug}` | `DemoController@show` | `config/demos.php`, separate `x-layouts.demo` |
| GET | `/about`, `/terms`, `/privacy` | `PageController` | static Blade + `config/brand.php` |
| GET/POST | `/contact` | `EnquiryController@create/store` | `Enquiry`; POST throttled 10/min |
| GET | `/contact/thank-you` | `EnquiryController@thanks` | session flash |
| — | `/admin/*` | Filament | session auth, no public registration |

**Every one of these URLs must keep working after the revamp** (SEO, links already
shared, WhatsApp messages, tests).

### 2.3 Data model

| Table / model | Purpose | Key rules |
|---|---|---|
| `packages` / `Package` | 5 product lines: listings, showroom, careers, stay, business | soft deletes, `is_published`, `is_featured`, JSON `what_you_get`, `built_for`; route key `slug` |
| `package_tiers` / `PackageTier` | 5 and 10 tiers, `price_monthly`, `media_gb`, `is_default` | **Prices live here.** `mail_gb` and `mailboxes` were dropped deliberately and must not return |
| `add_ons` / `AddOn` | invoicing (R109), enterprise-email (R20/R30 via `price_tiers`), crm, erp (metered) | flat **or** metered, never both; `price_tiers` JSON table |
| `enquiries` / `Enquiry` | leads; `ENQ-YYYY-00001` reference; status pipeline new → contacted → quoted → won/lost | prospect fields read-only in the panel; cannot be created by hand; IP, user agent and consent captured |
| `users` / `User` | staff only, `canAccessPanel() = true` | no public registration |

Not in the database, deliberately (config-as-content): `config/brand.php` (palette,
contact details, commercial terms, ownership wording, legal values),
`config/demos.php` (5 fictional tenants), `config/clients.php` (live work).

### 2.4 Back office (Filament)

- **Resources:** Packages (with a Tiers relation manager), Add-ons, Enquiries
  (no create).
- **Widgets:** `EnquiryOverview` and the account widget.
- **Styling:** plain CSS injected via the `HEAD_END` render hook
  (`components/filament-styles.blade.php`), with the primary colour taken from
  `brand.colors.accent`.

### 2.5 Other functionality

- **Mail:** `EnquiryReceived` goes to `brand.notify_email`. It is sent *after* the
  lead is saved, inside a try/catch.
- **Artisan commands:** `mail:check` (SPF/DKIM/credentials),
  `demos:import-images` (Openverse, commercial-use licences, quality gates),
  `brand:banner` (generated social artwork).
- **Support classes:** `App\Support\Whatsapp` (local number to `wa.me` form) and
  `App\Support\DemoImages` (attribution list).
- **Scheduler:** none (`routes/console.php` holds only `inspire`).

### 2.6 Test suite — 58 feature tests

| File | Tests | Guards |
|---|---|---|
| `PublicSiteTest` | 14 | every page renders, flat pricing, no email in packages, "7Hills" spelling, WhatsApp on every page, palette, **no third-party assets**, "not limited to five packages", prices come from the DB |
| `AddOnTest` | 11 | modules priced in the DB, "charged separately" wording, metered vs flat, bulk note, ownership statement |
| `LegalPagesTest` | 9 | never refers work elsewhere, POPIA disclosures, data-subject rights, cookie truthfulness, no internal notes, terms defer to privacy on retention |
| `DemoSiteTest` | 8 | every demo renders, `noindex`, inert form, back-office pitch, prices track the DB |
| `EnquiryTest` | 7 | storage + notify, provenance, consent, honeypot, tier/package match, references, mail failure keeps the lead |
| `LiveWorkTest` | 5 | public links only, no back-office walkthrough, third-party software attributed |
| `AdminPanelTest` | 3 | panel closed to the public, staff reach every page, enquiries cannot be created by hand |
| `EmptyStateTest` | 1 | site stands up with no packages |

These tests are the safety net for the revamp. **Copy-level assertions will need
deliberate updates**, for example `test_the_home_page_leads_with_the_promise`. Every
such change should be a conscious decision, listed in the PR, never a silent edit
to make a test pass.

---

## 3. Design system inventory

**Tokens** (from `config/brand.php`, emitted as CSS variables in `<head>`, mapped
in Tailwind `@theme`):

| Token | Value | Use |
|---|---|---|
| `brand` | `#1A2E22` Deep Forest | dark sections, headings |
| `brand-dark` | `#122118` | deepest surface, text on mint |
| `brand-light` | `#F8FAF9` | light wash sections |
| `accent` | `#417956` Emerald | CTAs and accents **on light** |
| `mint` | `#5EB87E` Vibrant Mint | CTAs and accents **on dark** |
| `ink` | `#111111` | body copy |
| `secondary` `#5C6861`, `muted` `#64726A` (5.05:1), `hairline` `#E2E8E5` | | text and borders |

**Utilities** (`@utility` in `resources/css/app.css`): `container-page`, `btn`,
`btn-accent`, `btn-mint`, `btn-brand`, `btn-ghost`, `btn-outline`, `btn-lg`,
`card`, `card-hover`, `section`, `section-tight`, `h-display`, `h-section`,
`lede`, `glow`, `eyebrow`, `eyebrow-light`; plus `.prose-page` for legal pages.

**Motion:** `[data-reveal]` IntersectionObserver fade-up with stagger. It is
progressive enhancement and respects `prefers-reduced-motion`.

**Components** (`resources/views/components`): `layouts.site`, `layouts.demo`,
`site-header` (desktop nav at `lg`), `site-footer`, `brand-logo`,
`package-card`, `package-icon` (`<x-icon>` is taken by Filament),
`add-ons-section`, `form-field`, `popia-notice`, `cookie-notice`,
`hardware-note`, `whatsapp-float`, `whatsapp-link`, `whatsapp-glyph`,
`demo-art`, `filament-brand`, `filament-styles`.

**Assessment:** the system is consistent and accessible, but visually
conservative: flat cards, one glow treatment, text-led sections, no imagery or
product UI on the marketing pages. It will carry an "ultra-modern" look without
new colours or fonts. What it lacks is **richer surface components** (bento grids,
product mock frames, gradient borders, stat bands, logo strips, tabs), not tokens.

---

## 4. Content and information architecture as it stands

| Page | Message today | Problem for the B2B/enterprise goal |
|---|---|---|
| Home | "Pay nothing until your website is live." Then the problem, what's included, packages, "not on the list?", the catch, live work, CTA | Website-rental first. Solutions appear only in section 5, as six chips |
| `/packages` and `/packages/{slug}` | Tier table, the R880 ownership bargain, add-ons | Fine as the **SME entry product**, keep as is |
| `/custom-and-it` | Custom software, digitising paper, automation, integrations, IT support, hardware; "one invoice" | The only place the bigger offer lives, and it is labelled like an afterthought |
| `/demos` and `/demos/{slug}` | Five invented tenants | Good proof of *websites*; nothing shows *business software* |
| `/how-it-works` | Process and FAQ for packages | Package-only process; no discovery → build → support process for projects |
| `/about` | Why we work this way, contact | No team, capability or credentials |
| `/contact` | Package/tier picker + free text | No structured "what do you need / size / timeline" |

Navigation: Packages · How it works · Custom & IT · Demos · About · **Get started**.

---

## 5. Invariants the revamp must preserve

Taken from CLAUDE.md and enforced by tests. **None of these change without a separate
decision:**

- Flat package pricing (R880 / R1 180 excl. VAT), stored in the **database** and
  edited in `/admin`.
- Packages are website-only; email is sold per mailbox; add-ons are charged
  separately; a module is flat **or** metered.
- No upfront cost, no minimum term, one month's notice, all quoted from
  `brand.terms`. The ownership bargain is stated on `/packages`.
- **No third-party assets at runtime**: no Google Fonts, CDNs, analytics or
  embedded video. This constrains "modern" choices (§11.4).
- The official palette and type only, `accent` on light and `mint` on dark, body
  text ≥ 4.5:1.
- The group name stays off marketing pages; "7Hills", never "Seven Hills".
- Never refer work to another supplier; one invoice.
- Enquiries are evidence: saved before mail, read-only in the panel, honeypot,
  throttle, POPIA notice above consent.
- Demos are fiction, `noindex`, inert forms. Live work claims only what runs and
  attributes third-party software.
- Hardware stays quiet.
- Every existing URL keeps resolving.

---

## 6. Reusable assets for the revamp

| Asset | Reuse |
|---|---|
| `AddOn` model with flat, metered and table pricing | Already the right shape for **solutions** (CRM, invoicing, ERP); extend rather than duplicate |
| `Package` with `is_featured`, JSON feature lists, Filament CRUD | Pattern for new content types (Solution, Industry, CaseStudy) |
| `Enquiry` pipeline + Filament resource + mail | Extend with nullable fields for project enquiries; keep every guarantee |
| `config/clients.php` + `LiveWorkTest` | The seed of a **case studies** section, with honesty rules already in place |
| `brand:banner` | Social and OG cards per solution page |
| Design utilities and `data-reveal` | Base for the new components |
| WhatsApp float with context message | Extend so a solution page pre-types its own name |

---

## 7. Findings and risks (independent of the redesign)

### 7.1 Operational (high priority)
1. **The working copy is the live site.** `docker-compose.yml` bind-mounts `./`
   into both `app` and `nginx`. Any edit or `npm run build` here is served
   immediately. **All revamp work must happen in a separate checkout or git
   worktree on a branch**, with a staging container on a different port.
2. **`APP_ENV=local` and `APP_DEBUG=true`** in `.env`. If this instance is
   publicly reachable (via a reverse proxy to `:8091`), error pages can expose
   stack traces and configuration. Production should run `APP_ENV=production` and
   `APP_DEBUG=false`. *Not changed. Needs your go-ahead.*
3. **MySQL is published on `0.0.0.0:33063`.** The compose file's own comment says
   "remove in production". Bind it to `127.0.0.1` or drop it.
4. **Not in version-controlled hosting.** CLAUDE.md records the repo has never been
   pushed to Gitea. A redesign this size should start with a remote backup of `main`.

### 7.2 Code and rule drift
5. **Prices hard-coded in Blade**, against the "prices live in the database" rule:
   - "R20 a mailbox": `pages/home.blade.php:87`, `packages/index.blade.php:14,157`,
     `packages/show.blade.php:63,91`, `components/layouts/demo.blade.php:163`,
     `pages/how-it-works.blade.php:78`, and `config/brand.php` (`storage.email_note`).
   - "R880 a month": `packages/index.blade.php:76`.
   - "R880 + R109": `components/add-ons-section.blade.php:14`.

   Changing a price in `/admin` today leaves these stale. The redesign should read
   them from `AddOn`/`PackageTier` and widen `test_prices_come_from_the_database_not_the_markup`.
6. **Live Work promises something that doesn't exist.** `home.blade.php:308–311`
   says "ask … for a walkthrough of the same panel on a demo install", but open
   decision 7 says the demo tenants have no back office.
7. **CLAUDE.md says Laravel 12; `composer.json` requires `^13.17`.** Update the doc.
8. **Stale comment:** `config/brand.php` says `legal_name` "appears on /about"; the
   s43 disclosures were removed from `/about`.

### 7.3 Brand asset needing a decision
9. **The untracked `public/images/brand/Complete Technology solutions.jpg`** is a
   1024×1024 JPEG on white: a different logo lockup (bar-chart mark, "7HILLS
   DIGITAL | COMPLETE TECHNOLOGY SOLUTIONS"). It does not match the supplied
   `logo.png`, and "Complete Technology Solutions" does not appear in
   `Brand_Guidelines_And_Color_Codes.html`. CLAUDE.md says logo artwork is used
   as supplied and no logo treatment outside the guidelines is introduced. **Is
   this a new official lockup or tagline?** If yes, it needs a transparent
   high-resolution original and an update to the guidelines. If no, keep it off
   the site. The *phrase* could still be used as a text tagline.

### 7.4 Commercial and legal exposure that grows with enterprise
10. **ECTA s43 disclosures are not published** (removed by instruction, open
    decision 4). Enterprise procurement and legal teams look for registration
    number, directors and physical address, so their absence will cost credibility
    as well as compliance. Strongly recommend restoring them in the footer or an
    `/about` table before pitching enterprise. It is a template change; the values
    already sit in `brand.legal`.
11. **No Information Officer named** (open decision 3). A site selling POPIA
    compliance with an empty clause 1 is a liability in front of enterprise buyers.
12. **Unbuilt offers.** CRM and ERP are sold but not built; e-commerce is
    advertised without a product. The revamp must not widen this gap (§12).

### 7.5 SEO and discoverability
13. No `sitemap.xml`, no structured data (`Organization`, `LocalBusiness`,
    `Service`, `FAQPage`), `twitter:card` is `summary`, the OG image is the logo
    badge on every page, and no page targets a search term people actually use (§9).

---

## 8. Gap analysis: current state vs target

| Enterprise and SME buyers expect | Today | Gap |
|---|---|---|
| A clear statement of what you build | "Websites for 5 trades" | Repositioning, new home page |
| Solutions organised by business problem | 6 chips on one page | `/solutions` hub and pages |
| Industries served | 5 package verticals | `/industries` reusing packages and demos |
| Proof: case studies, results, clients | 1 live client card | Case study model and pages |
| How you deliver projects | Package-only process | Discovery → design → build → run, with SLAs |
| Security, hosting, POPIA posture | Privacy notice only | `/security` (trust centre) page |
| Procurement documents | None | B-BBEE level/affidavit, tax clearance, CSD number, company registration |
| Engagement models and pricing transparency | Packages + add-ons | SME subscriptions **and** enterprise options: fixed-scope project, monthly retainer, dedicated team |
| A structured enquiry | Package picker | Solution, company size, timeline, budget band |
| Technology credibility | None | Stack and integrations strip (Sage, Xero, Microsoft 365, WhatsApp Business, SARS e-filing) |

---

## 9. Market evidence behind the new positioning

Summary of the research done in this session. The figures are relative Google
Trends indices for South Africa over 12 months, and survey figures come from Xero
2026, unless noted.

**SMEs' biggest problems:**
- **Cash flow**: 62% had cash-flow problems in the past year (up from 24% in
  2024). **42%** struggle with late payments. **91%** had invoices paid late,
  on average 18 days over terms.
- **Admin**: about 34% still run finances on spreadsheets; 32% struggle with tax
  compliance.
- **Red tape**: SARS, CIPC, UIF, B-BBEE and POPIA; compliance costs estimated at
  R270–450bn a year nationally.
- **Digital intent without a plan**: 85% call digital adoption a priority, while 56%
  want help seeing where it fits. 77% trust their accountant most.

**What people search for (South Africa):**
- Rising business-software searches: *best CRM software for small business*
  (+2 250%), *online appointment scheduling software* (+3 600%), *ERP software*
  (+3 600%), *payroll software for small companies* (+2 600%), *customer support
  software comparison* (Breakout), *cybersecurity solutions for small business*
  (Breakout), *SARS digital VAT system changes* (Breakout).
- Large or growing terms: CRM (large, +7%), accounting software (+75%), POPIA
  (+54%), SEO (+76%), "IT company" (+36%).
- **Falling:** website design (−21%), "custom software" (near zero), "web/app
  developer" (low). People search for **the outcome or product**, not the supplier.
- **Upcoming regulation:** SARS VAT modernisation, with mandatory e-invoicing
  phased in from about 2030 (large taxpayers first) and voluntary/pilot use before.

**Implication for the copy:** headline the business outcome (*get paid faster,
stop the admin, stay compliant, know every customer*), and use searched nouns in
headings and URLs (`crm`, `invoicing`, `booking-system`, `payroll`, `popia`,
`helpdesk`).

---

## 10. Recommended positioning

**Primary line:** *Business software and digital solutions for South African companies.*
**Supporting line:** *Websites, business systems and integrations, built, hosted
and supported by one team, on one invoice.*

Two tracks, one brand:

| | **SME track** (existing, kept) | **Enterprise / custom track** (new) |
|---|---|---|
| Offer | 5 website packages + add-on modules + solution modules | Custom systems, integrations, workflow automation, modernising legacy systems |
| Price | Flat monthly from the DB, R0 build, cancel with 1 month's notice | Quoted: discovery workshop → fixed-scope phase or monthly retainer |
| Proof | Demos + live work | Case studies, process, security page, procurement documents |
| CTA | "Start for R0" / "See the packages" | "Book a discovery call" |

The `brand.tagline` / `brand.promise` copy changes accordingly. The commercial
promise stays and becomes the SME track's headline instead of the whole company's.

---

## 11. Proposed information architecture

### 11.1 Sitemap (new pages marked ★; every existing URL kept)

```
/                         Home (rebuilt)
/solutions ★              Hub: business problems → solutions
  /solutions/{slug} ★     e.g. invoicing-and-payments, crm, booking-system,
                          payroll-and-hr, helpdesk, compliance-and-popia,
                          business-websites, integrations, custom-software
/industries ★             Hub reusing the 5 package verticals + "everyone else"
  /industries/{slug} ★    estate-agencies, car-dealerships, recruitment,
                          hospitality, professional-services…
/packages                 KEPT as is (SME website subscriptions)
/packages/{slug}          KEPT
/enterprise ★             Engagement models, process, governance, procurement docs
/work ★                   Case studies (grows from config/clients.php)
  /work/{slug} ★
/security ★               Hosting, backups, POPIA operator role, access control
/how-it-works             KEPT, reframed as "How we deliver" with two paths
/custom-and-it            KEPT; becomes the IT services page (still quiet on hardware)
/demos, /demos/{slug}     KEPT
/about                    KEPT, extended (team, capability, credentials)
/contact                  KEPT, extended form (§12.3)
/terms, /privacy          KEPT
/sitemap.xml ★
```

### 11.2 Navigation

`Solutions ▾ · Industries ▾ · Packages · Enterprise · Work · About` +
**Book a call** (primary) + **See packages** (secondary). The desktop switch stays
at `lg`. The two dropdowns become mega-menus, built with Alpine and no new
dependency.

### 11.3 Home page, section by section

1. **Hero (dark, `bg-brand`)**: outcome headline, e.g. *"Software that runs your
   business, so you can run the business."* Two CTAs: **Book a discovery call**
   (`btn-mint`) and **Websites from R{tier price}/month** (`btn-ghost`, price read from the DB).
   Right side: a stylised **product UI mock** (invoice/CRM dashboard drawn in
   HTML/SVG with brand tokens; no screenshots of client data).
2. **Trust strip**: live client(s), "Built in Midrand", POPIA operator, "One invoice".
3. **Problems we solve (bento grid)**: Get paid faster · Stop the admin ·
   Stay compliant (POPIA, SARS e-invoicing) · Know every customer · Take bookings
   24/7 · Connect your systems. Each tile links to a solution page.
4. **Two ways to work with us**: SME subscription card | Enterprise/custom card.
5. **Solutions showcase**: tabbed panels with a mock UI per solution.
6. **Industries**: the five verticals (reusing packages and demos) + "your industry".
7. **How we deliver**: Discover → Design → Build → Run (support and hosting).
8. **Proof**: case study cards (live work, honesty rules kept).
9. **Integrations and stack strip**: Sage, Xero, Pastel, Microsoft 365, Google
   Workspace, WhatsApp Business, PayFast/Ozow/Yoco, SARS eFiling. Text or
   self-hosted monochrome marks only, nothing hotlinked. Check trademark guidelines
   before using any third-party logo.
10. **The honest part**: kept, shortened.
11. **Closing CTA**: discovery call + WhatsApp.

### 11.4 The "ultra-modern" visual language, inside the brand rules

All of the following use only the existing tokens and fonts, and need **no
third-party runtime assets**:

- **Dark-first hero and section bands** in Deep Forest with layered `glow` meshes
  (mint/emerald at low opacity) and a subtle CSS grid or noise texture.
- **Bento grids** of mixed-size cards with 1px gradient borders
  (`accent → mint`), soft inner shadows, and a hover lift (`card-hover`).
- **Product mock frames**: browser and phone chrome drawn in CSS, with the UI
  drawn in HTML. They load fast, stay sharp and never leak client data.
- **Large display type**: extend `h-display` with an `xl` step (`lg:text-7xl`) and
  tighter tracking for the hero only.
- **Motion**: keep `data-reveal`; add count-up stats and a gentle marquee for the
  integrations strip. All decorative and disabled under `prefers-reduced-motion`.
  No content behind motion.
- **Glass surfaces** (`backdrop-blur` on `bg-white/5`) for floating chips on dark
  sections, checked for ≥ 4.5:1 text contrast.
- **Photography:** none of stock people. Use product mocks and the generated
  `brand:banner` style. If imagery is wanted, extend the Openverse importer
  pattern (download, re-encode, credit), never hotlink.

New Blade components (all under `resources/views/components/`):
`section-heading`, `bento-grid` / `bento-tile`, `solution-card`, `industry-card`,
`case-study-card`, `stat-band`, `logo-strip`, `process-steps`, `tabs`,
`mock-browser`, `mock-phone`, `cta-band`, `mega-menu`, `faq` (with
`FAQPage` JSON-LD).

---

## 12. Technical plan

### 12.1 Data model (additive only; no existing column changes)

| New table | Fields (summary) | Admin |
|---|---|---|
| `solutions` | slug, name, tagline, problem, outcome, `features` JSON, `faqs` JSON, `industries` JSON, optional `add_on_id` FK (reuses the AddOn price), `status` enum **`live` / `in_development` / `on_request`**, seo_title, seo_description, sort, is_published, soft deletes | Filament `SolutionResource` |
| `industries` | slug, name, summary, pain_points JSON, optional `package_id` FK, optional demo slug, sort, is_published | Filament `IndustryResource` |
| `case_studies` | slug, client_name, `permission_confirmed` bool (required to publish), industry, challenge, solution, results JSON, `software_attribution` (required), public_url, is_published | Filament `CaseStudyResource` |

Rules carried over:
- **Solution prices come from `add_ons`** (or say "Quoted"). No price column on
  `solutions`, so there is never a second copy of a price.
- **`status` is shown on the page.** A solution that is `in_development` says so
  ("Coming soon — register interest"). This keeps the "claim only what runs" rule
  without hiding the roadmap. CRM and ERP start as `in_development`; Invoicing is
  `live` (Invoice Ninja, attributed).
- `case_studies` absorbs `config/clients.php` once the honesty rules are mirrored
  in validation and tests.

### 12.2 Routes and controllers (all new, nothing removed)

`SolutionController@index/show`, `IndustryController@index/show`,
`CaseStudyController@index/show`, and `PageController@enterprise/security`.
`SitemapController` generates `/sitemap.xml` from packages, solutions, industries,
case studies and static pages.

### 12.3 Enquiry form (backwards-compatible)

New **nullable** columns on `enquiries`: `enquiry_type`
(`package|solution|enterprise|it|general`), `solution_id`, `company_size` band,
`timeline` band, `budget` band. The existing package/tier path and
`StoreEnquiryRequest::after()` stay unchanged. New fields are validated against
fixed option lists and remain read-only in the panel. Deep links follow the
existing pattern: `/contact?solution=crm`, `/contact?type=enterprise`. The Filament
table gains filters by type and solution, and `EnquiryOverview` gains a
"by type" stat.

### 12.4 SEO

`sitemap.xml`; per-page `title` and description from the DB; JSON-LD
(`Organization`, `LocalBusiness`, `Service`, `FAQPage`, `BreadcrumbList`);
`twitter:card=summary_large_image` with per-solution OG images from `brand:banner`;
keyword-led headings and slugs from §9.

### 12.5 Tests

- **Keep all 58.** Update the copy-level assertions knowingly, each listed in the PR.
- Widen the price-in-markup test to cover every hard-coded R-figure in §7.2.
- New: every solution, industry and case-study page renders; an unpublished one
  returns 404; `in_development` solutions show the badge; a case study cannot
  publish without permission and attribution; the sitemap lists every published
  URL; new enquiry types validate and stay read-only; `/enterprise` and
  `/security` render; and the existing no-third-party-asset test is applied to
  every new route.

---

## 13. Phased delivery (nothing starts until this plan is approved)

| Phase | Content | Safety |
|---|---|---|
| **0. Safe ground** | Push `main` to Gitea (private). Create a branch in a **separate worktree**. Run a staging container on another port with its own DB copy. Run the 58 tests and record the baseline. Decide the §7.1 fixes. | Production untouched |
| **1. Foundation** | New components and visual language; fix hard-coded prices; correct the Live Work copy; sitemap and JSON-LD. Pages look new; IA unchanged. | Staging review, all tests green |
| **2. Solutions and industries** | Migrations, models and Filament resources; `/solutions`, `/industries`; new home page; navigation; extended enquiry form | Additive migrations only, rehearsed on a DB copy |
| **3. Enterprise and proof** | `/enterprise`, `/security`, `/work`; procurement documents; s43 and Information Officer decisions | Legal review before publishing |
| **4. Go-live** | Production DB backup → deploy → migrate → build → smoke test → rollback plan | Your explicit go-ahead |

---

## 14. Decisions needed from you

1. **Positioning line.** Approve *"Business software and digital solutions for
   South African companies"*, or supply your own.
2. **Solutions at launch** (recommended order): Invoicing & payments (live) ·
   Business websites (live) · CRM · Booking system · Compliance & POPIA ·
   Integrations · Custom software. Payroll/HR and helpdesk to follow.
3. **How unbuilt solutions are shown**: "in development / register interest"
   badge (recommended), or hidden until built.
4. **Enterprise engagement models**: discovery workshop (paid or free?),
   fixed-scope project, monthly retainer. Are any prices published, or all quoted?
5. **The new logo image**: official lockup or not? (§7.3)
6. **ECTA s43 and Information Officer**: restore the disclosures and name the
   officer before the enterprise pitch? (§7.4)
7. **Operational fixes** in §7.1 (`APP_DEBUG`, MySQL port, Gitea push): may I
   prepare them as a separate change?
8. **Procurement documents**: B-BBEE level/affidavit, CSD number and tax
   compliance status to publish on `/enterprise`.
9. **Third-party logos** in the integrations strip, or text only?

---

*Sections 0–14 were prepared from a read-only review: no other file was created
or modified, no container was touched, and nothing was committed.*

---

## 15. Phase 0 log — 30/10/2026 (approved)

| Item | Result |
|---|---|
| Branch | `revamp`, checked out as a git worktree at `/opt/docker/7hillsdigital-revamp`. The live checkout `/opt/docker/7hillsdigital` stays on `main`, untouched. |
| Staging stack | `docker/staging/docker-compose.yml`, project `7hills-staging`: containers `7hills-staging-{app,nginx,mysql}`, own network and volume. Reuses the production image `7hillsdigital/app`. **http://127.0.0.1:8096** (bound to localhost only); MySQL not published. |
| Staging `.env` | Copied from production, then: `MAIL_MAILER=log` (staging never sends mail), new `APP_KEY`, new DB passwords, `APP_URL=http://localhost:8096`, `DB_HOST=mysql`. Git-ignored, mode 600. |
| Staging data | `mysqldump --single-transaction` of production (non-locking, read-only on that side). Packages (5), tiers (10), add-ons (4), staff users (1) and migrations (9) copied. **Enquiries, sessions, cache, jobs and reset tokens copied as structure only, with no rows**, so no prospect's personal data leaves production. |
| Smoke test | `/`, `/packages`, `/packages/showroom`, `/custom-and-it`, `/demos`, `/demos/auto`, `/contact`, `/admin/login` all return 200; live prices R880 / R1 180 / R109 render. |
| **Test baseline** | **75 passed, 410 assertions, 0 failed** (58 test methods; the demo tests run once per tenant). Run with `docker exec 7hills-staging-app php artisan test`. |
| Gitea backup | ⚠ **Not done.** `main` is 1 commit ahead of `origin/main` (`94235bb`). `origin` points at `192.168.90.164:3000`, which no longer answers; Gitea 1.27.1 is live at `192.168.90.215:3000`. Repointing the remote was blocked by the session's permission rules, so it is left for the owner (commands below). CLAUDE.md's "not yet pushed" note is also stale: the repo was pushed on 24/08/2026. |

Commands for the owner:

```bash
git -C /opt/docker/7hillsdigital remote set-url origin http://gitadmin@192.168.90.215:3000/gitadmin/7hillsdigital.git
git -C /opt/docker/7hillsdigital push origin main revamp
```

Tear-down if ever needed (removes staging only):

```bash
docker compose -p 7hills-staging -f /opt/docker/7hillsdigital-revamp/docker/staging/docker-compose.yml down -v
```

Still open from §7.1 (not touched, needs a decision): production `APP_DEBUG=true` /
`APP_ENV=local`, and MySQL published on `0.0.0.0:33063`.
