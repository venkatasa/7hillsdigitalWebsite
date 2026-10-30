# Service & Industry Landing Pages — and SEO Strategy

**Prepared:** 30/10/2026  **Status:** For approval.
Covers: page template, per-page briefs (7 business solutions, 7 enterprise
services, 6 industries), keyword research **with validation**, internal linking,
metadata, structured data, sitemap, URL preservation, performance and accessibility.

---

## 1. Keyword research — method and validation

### 1.1 Method
- **Google Trends, South Africa (`geo=ZA`), last 12 months**, queried 30/10/2026
  through Trends' own data endpoints. A shared anchor term in every batch
  (`crm` = 100) makes batches comparable.
- "Trend" = last-quarter average vs first-quarter average.
- **Rising related queries** for broad seeds (`software`, `it support`,
  `cybersecurity`, `website`).

### 1.2 What Trends can and cannot tell us
- ✅ **Relative** interest between terms, and direction over time.
- ❌ **No absolute volumes.** "100" might be 500 or 50 000 searches a month.
- ❌ Ambiguity: `dashboard` includes car dashboards; `client portal` includes
  bank and insurer portals; `sage` includes the herb; `it support` includes job
  seekers (its top related query is *"it support jobs"*).
- ❌ Percentage growth on a tiny base is noise (e.g. *payroll software* +818%,
  *workflow automation* +540%).

### 1.3 Validation of earlier claims

| Claim used earlier in this project | Status | Use it? |
|---|---|---|
| 62% of SA small businesses had cash-flow issues (Xero 2026, n=427, KLA, Jan–Feb 2026) | ✅ **Verified** in Xero's primary release | Yes, with citation |
| 85% prioritise digital adoption; 77% trust their accountant most; 52% use AI tools weekly | ✅ **Verified** in the primary release | Yes, with citation |
| 42% struggle with late payments | ⚠ **Not in the primary release** (secondary coverage only) | **No**, until the full report is checked |
| 91% of SMEs paid late, 18 days over terms | ⚠ From an opinion piece citing a different Xero study | **No** |
| Accounting software search interest rising (~+75%) | ✅ **Replicated** in two independent runs (+75%, +77%) | Yes |
| Website design interest falling (~−21%) | ✅ **Replicated** (−21% both runs) | Yes |
| CRM large and growing (+7%) | ✅ **Replicated** (+7% both runs) | Yes |
| Booking system modest and declining (−12%) | ✅ **Replicated** | Yes |
| "Best CRM software for small business" +2 250%, ERP software +3 600%, appointment scheduling +3 600% | ⚠ "Rising" related queries: real direction, **tiny base** | As long-tail targets only, not as demand proof |
| SARS mandatory e-invoicing | ⚠ Timeline disputed: 2028 in older sources, **~2030 phased** in SARS's latest consultation | Say "coming"; never a date |
| Market-research CAGR figures (cybersecurity etc.) | ⚠ Vendor reports, conflicting | No |

### 1.4 Keyword table (validated run, `crm` = 100)

| Term | Index | 12-mo trend | Intent | Confidence |
|---|---|---|---|---|
| microsoft 365 | 237 | −8% | Product/support (mixed consumer) | High volume, mixed intent |
| dashboard | 237 | +6% | **Ambiguous** | Low |
| crm | **100** | +7% | Commercial investigation | **High** |
| sharepoint | 81 | **+19%** | Product/how-to | Medium |
| power bi | 69 | −11% | Product/how-to, training | Medium |
| erp | 59 | +8% | Commercial | Medium |
| data analytics | 45 | −10% | Mixed (careers/courses) | Low |
| accounting software | 33 | **+77%** | Commercial | **High** (replicated) |
| website design | 33 | **−21%** | Commercial | **High** (replicated) |
| client portal | 29 | −17% | Ambiguous (navigational) | Low |
| ai for business | 29 | −44% | Informational | Low |
| booking system | 28 | −12% | Commercial | High (replicated) |
| invoicing | 21 | +24% | Mixed | Medium |
| power automate / power apps | 19 / 15 | −15% / −20% | How-to | Medium |
| business intelligence | 18 | +29% | Informational | Medium |
| payroll software | 7 | (noise) | Commercial | Low base |
| mobile app development | 5 | (noise) | Commercial | Low base |
| system integration | 5 | −32% | Commercial | Low base |
| workflow automation | 4 | (noise) | Commercial | Low base |
| employee portal | 3 | — | Navigational | Low |
| document management system, approval workflow, legacy system, custom software development | ≈0 | — | — | **Negligible SA search** |

**Conclusions for prioritisation:**
1. **Acquisition pages** (worth real SEO effort): CRM · Invoicing & accounting
   integration · Booking system · ERP (as a module, `in_development`) · Microsoft
   365 / SharePoint (only if capability is confirmed).
2. **Conversion pages** (low search; they exist for visitors arriving from the home
   page, referrals and sales conversations): document workflows · portals ·
   process automation · custom software · legacy modernisation · integration ·
   mobile. Write them for persuasion, not for keywords.
3. Lead with **outcome language** in H1s ("Get paid on time", "Never lose a
   lead") and put the **searched noun** in the title, slug and first paragraph.

### 1.5 Before launch, and the first 90 days after
- **Google Keyword Planner** (needs a free Google Ads account) for absolute SA
  volumes on the acquisition terms. *Owner action.*
- **Google Search Console** (verify the domain through the Cloudflare DNS TXT
  record): submit `sitemap.xml`, then after 60–90 days re-rank by real
  impressions and clicks. This replaces Trends as the source of truth.
- Re-run this table quarterly.

---

## 2. Landing page template

One Blade template per type (`offerings/show`, `industries/show`), driven by the DB.
Sections in order:

| # | Block | Content | Notes |
|---|---|---|---|
| 1 | Breadcrumb | Home › Solutions › CRM | + `BreadcrumbList` JSON-LD |
| 2 | Hero | H1 (outcome), lede (keyword in first 100 words), **availability badge**, primary CTA for the journey, `mock-browser` | Screenshot only with provenance; else an *Illustrative* label |
| 3 | The problem | 3 pain points in the customer's words | |
| 4 | What you get | 4–6 capabilities with icons | Only what exists (`available`) or what we will build (`built_to_order`) |
| 5 | How it works with what you have | Integrations as **text** (Sage, Xero, Microsoft 365, WhatsApp) | No vendor logos unless D10 approves |
| 6 | Proof | Related case study or demo (`proof-card` with Client/Demo label) | Omit the block if there's none; never invent |
| 7 | Pricing | Module price from `add_ons`, or "Quoted — typical engagement: …" | Never a hard-coded figure |
| 8 | Process | 4 steps (subscription or project variant) | |
| 9 | FAQ | 4–6 Q&As from the DB | + `FAQPage` JSON-LD |
| 10 | Related | 3 links: industry, related solution, platform | Internal linking |
| 11 | CTA band | Journey-specific | `?from=solution-{slug}` |

---

## 3. Page briefs

Columns: **Slug** · **Primary / secondary keywords** · **H1 (draft)** ·
**Status** (proposed; D2) · **Proof today** · **CTA**.

### 3.1 Business Solutions (`/solutions/…`)

| Slug | Keywords | H1 | Status | Proof | CTA |
|---|---|---|---|---|---|
| `crm` | crm; crm software for small business, customer management system SA | Every lead, every customer, one place your team can see | `built_to_order` (CRM module `in_development`) | Listings pipeline at Excellent RE (public description) | Project form `?solution=crm` |
| `invoicing-accounting-integration` | accounting software; invoicing, invoice software, Sage/Xero/Pastel integration, SARS e-invoicing | Get paid on time — invoicing that chases for you | Invoicing **`available`**; integrations `built_to_order` | Invoicing live (Invoice Ninja, attributed) | Subscription `/contact?addon=invoicing` *and* project form |
| `booking-system` | booking system; online booking, appointment scheduling software | Let customers book themselves, day or night | `built_to_order` | Stay demo (labelled Demo) | Project form |
| `business-process-automation` | business process automation; workflow automation, automate admin | Automate the job someone repeats every Friday | `built_to_order` | — | Project form |
| `document-approval-workflows` | document workflow; approval workflow, digital forms, e-signature | Paper, signatures and sign-off — on a screen, with an audit trail | `built_to_order` | Rental-application documents at Excellent RE (public description) | Project form |
| `business-reporting-dashboards` | business dashboard; business intelligence, reporting software, KPI dashboard | The numbers that run your business, on one screen | `built_to_order` | Our own admin dashboard (screenshot from staging, fictional data) | Project form |
| `customer-employee-portals` | customer portal; client portal, employee self-service portal | A secure login for your customers and staff | `built_to_order` | — | Project form |

### 3.2 Enterprise (`/enterprise/…`)

| Slug | Keywords | H1 | Status | Proof | CTA |
|---|---|---|---|---|---|
| `custom-software-development` | software development company South Africa; bespoke software | Software built around how your business actually works | `built_to_order` | Our own platform (this site + Listings) | Project form `?type=enterprise` |
| `microsoft-365-sharepoint-power-platform` | sharepoint; microsoft 365, power automate, power apps, power bi | Get more from the Microsoft 365 you already pay for | **`capability_unconfirmed`** | — | — |
| `ai-business-applications` | ai for business; ai automation, ai assistant for business | AI that does a job in your business, not a demo | **`capability_unconfirmed`** | — | — |
| `systems-integration` | system integration; api integration, connect business systems | Make the systems you already pay for talk to each other | `built_to_order` | Invoicing sending from the client's own mailbox (a small integration) | Project form |
| `mobile-app-development` | mobile app development South Africa; business app | A mobile app your staff or customers will actually use | **`capability_unconfirmed`** | — | — |
| `legacy-modernisation` | legacy system modernisation; replace old software | Replace the system nobody dares touch — without stopping the business | `built_to_order` | — | Project form |
| `data-analytics-business-intelligence` | business intelligence; data analytics, power bi consulting | Turn the data you already have into decisions | **`capability_unconfirmed`** | — | — |

### 3.3 Industries (`/industries/…`)

| Slug | Keywords | H1 | Platform · Demo |
|---|---|---|---|
| `estate-agencies` | estate agency software; property listing website | Listings, leads and rental applications in one system | Listings · `realty` · **Excellent RE (client)** |
| `car-dealerships` | dealership website; car dealer software | Your stock online the hour it arrives | Showroom · `auto` |
| `recruitment` | recruitment agency software; job board website | Vacancies, applicants and CVs in one place | Careers · `careers` |
| `hospitality` | guesthouse booking website; B&B website | Direct bookings, without the commission | Stay · `stay` |
| `plant-and-equipment-hire` | plant hire website; equipment rental software | Your fleet, availability and enquiries online | Business · `plant` |
| `professional-services` | software for attorneys / accountants / consultants | Client intake, documents and billing that don't live in email | Business + B solutions · *no demo* |

---

## 4. Metadata patterns

| Page | `<title>` (≤ 60 chars) | Meta description (≤ 155) |
|---|---|---|
| Solution | `{Searched noun} for SA businesses — 7Hills Digital` | `{Outcome}. {Availability phrase}. Built, hosted and supported in South Africa.` |
| Enterprise | `{Service} in South Africa — 7Hills Digital` | `{Outcome}. Discovery, fixed-scope phases and support from one team.` |
| Industry | `Software & websites for {industry} — 7Hills Digital` | `{Platform outcome}. From R{tier}/month, R0 to build.` *(price from DB)* |

Fields `seo_title` and `seo_description` on `Offering` and `Industry` override the
pattern. A test fails on empty or over-length values.

## 5. Structured data (JSON-LD partials)

| Where | Types |
|---|---|
| Every page | `Organization` (name: 7Hills Digital, url, logo, contactPoint with phone and WhatsApp; the legal name only if D4 restores disclosures) |
| Home | + `WebSite` |
| Solution / Enterprise | `Service` (`serviceType`, `provider` → Organization, `areaServed: ZA`, `offers` only when priced from the DB) + `FAQPage` + `BreadcrumbList` |
| Industry | `Service` with `audience` + `BreadcrumbList` |
| Packages | `Product`-style `Offer` per tier **only** from DB values (`priceCurrency: ZAR`, `price`, `valueAddedTaxIncluded: false`) |
| Case study | `Article` + `BreadcrumbList` |
| Demos | **none** (noindex fiction) |

Nothing in JSON-LD may claim more than the page shows (no ratings or reviews).

## 6. XML sitemap
`/sitemap.xml` is generated from named routes + published `Package`, `Offering`,
`Industry` and `CaseStudy` rows; `lastmod` = `updated_at`; it excludes demos,
thank-you pages, admin and unpublished rows. `robots.txt` gains
`Sitemap: https://7hillsdigital.co.za/sitemap.xml` and `Disallow: /admin`.

## 7. Existing URL preservation
All 14 public routes keep their path and purpose (see `WEBSITE_SITEMAP.md`). A
test asserts each returns 200. Query strings in use (`?package=`) keep working.
Canonical tags strip tracking parameters. After launch, watch the 404 log weekly
for a month.

## 8. Internal linking rules
- Every new page links **up** (breadcrumb), **across** (2–3 related pages) and **down** (CTA).
- Industries ↔ packages ↔ demos form a triangle; solutions link to the industries where they matter most.
- Anchor text uses the searched noun ("booking system"), never "click here".
- No orphan pages: a test walks the sitemap and asserts every URL is linked from at least one other page.

## 9. Performance and accessibility (all landing pages)
The same budgets as the homepage (Lighthouse perf ≥ 90 mobile, a11y ≥ 95, LCP < 2.5 s,
CLS < 0.1). Self-hosted, responsive images with dimensions; no third-party
scripts; semantic headings (one H1); FAQ as `<details>` (works without JS);
availability badges are text, not colour-only.
