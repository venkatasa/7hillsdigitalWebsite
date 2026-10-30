# 5 · Navigation and SEO Landing-Page Structure

**Prepared:** 30/10/2026 · **Status:** For approval.
**Supersedes:** the `/solutions/…` and `/enterprise/…` trees in `WEBSITE_SITEMAP.md`.
Those existed only in planning and on staging, so no SEO equity is lost.

---

## 5.1 Primary navigation

```
[logo]  Services▾  Solutions▾  Industries▾  Platforms  Our Work  About     [ Get a Quote ]
```

| Item | Target | Menu content |
|---|---|---|
| **Services ▾** | `/services` | Mega-menu: 7 lines in two columns. Each line shows its 3–4 most important services and "All {line} →". Footer row: "All 50+ services" |
| **Solutions ▾** | `/solutions` | Six problem statements (`04` §3) |
| **Industries ▾** | `/industries` | Six industries; each notes its platform where one exists |
| **Platforms** | `/packages` (**URL kept**) | Plain link. The label changes, the URL does not |
| **Our Work** | `/work` (new) | Plain link. Live work, demos and case studies when approved |
| **About** | `/about` (kept) | Plain link |
| **Get a Quote** | `/quote` (new) | Primary button, `btn-accent` on light, `btn-mint` on dark |

- Desktop switches at `lg` (CLAUDE.md). Six items plus the CTA fit at 1024px; this is
  checked at 1024, 1280 and 1440.
- Mobile: a full-height sheet as in v2. Services, Solutions and Industries are
  accordions; "Get a Quote" is pinned to the bottom of the sheet.
- Unconfirmed lines are **absent** from the mega-menu in production, not greyed out.

**Footer** (4 columns + legal row): Services (7 lines) · Platforms (5 + Demos) ·
Company (About, Our Work, How it works, Contact) · Contact (phone, email, WhatsApp).
The hardware line stays one quiet sentence.

## 5.2 URL tree

```
/                                           home (v3)
/services                                   hub: all 7 lines, all services
  /services/software-development            line page  (anchors for every service)
  /services/microsoft-business-solutions    line page  [gated]
  /services/ai-data-and-automation          line page
  /services/cloud-and-infrastructure        line page
  /services/it-support-and-security         line page
  /services/business-applications           line page
  /services/websites-and-platforms          line page  (links to /packages)
  /services/{service}                       dedicated service pages (wave 1: 8)
/solutions                                  6 problem pages
  /solutions/replace-spreadsheets
  /solutions/never-lose-a-lead
  /solutions/get-paid-on-time
  /solutions/automate-approvals
  /solutions/connect-your-systems
  /solutions/it-that-just-works               [gated: managed IT]
/industries                                 hub
  /industries/{automotive|property|recruitment|hospitality|professional-services|corporate}
/packages            (KEPT)                 label "Platforms"
/packages/{slug}     (KEPT)
/demos, /demos/{slug} (KEPT)
/work                                       live work + demos + (later) case studies
/quote                                      multi-step project enquiry (new)
/contact             (KEPT)                 subscription enquiries, ?package= deep links unchanged
/how-it-works        (KEPT)                 rewritten later to cover projects too
/custom-and-it       (KEPT, see 5.3)
/about /terms /privacy (KEPT)
```

Line pages and service pages share the `/services/` prefix with **unique slugs**, so
one route resolves both (`Offering` by slug, then `line`). It is shallow, readable and
needs no nested routes.

## 5.3 Preserving existing URLs and SEO equity

| Existing URL | Decision |
|---|---|
| `/`, `/packages`, `/packages/{slug}`, `/demos`, `/demos/{slug}`, `/about`, `/terms`, `/privacy`, `/contact`, `/contact/thank-you`, `/how-it-works` | **Unchanged URLs.** Content and nav labels may change |
| `/custom-and-it` | **Kept live through launch**, with a prominent link to `/services`. After 90 days of Search Console data, **301 → `/services`** if `/services` outranks it. The route name `it-services` and `PublicSiteTest` keep working through the redirect window |
| `?package=` deep links | Unchanged |

No URL is removed. Every new page is additive.

## 5.4 Landing-page template: one structure, distinct substance

Each dedicated page answers the brief's seven questions in this order:

| # | Section | Content rule |
|---|---|---|
| 1 | **Hero**: the business problem | H1 in outcome language; the searched noun in title, slug and first sentence |
| 2 | **What we deliver** | 4–6 concrete deliverables, specific to this service |
| 3 | **Typical use cases** | 3 scenarios by industry or role. Written as scenarios, **never as client stories** |
| 4 | **Technologies** | Only technologies on the capability matrix with evidence |
| 5 | **Delivery approach** | 4 steps; durations only once Q3 is decided |
| 6 | **Proof** | Real screenshot, live work or demo **if one exists**. Otherwise this section is **omitted, not padded** |
| 7 | **FAQ** | 4–6 questions buyers actually ask (cost drivers, ownership, POPIA, support after launch) |
| 8 | **Get a quote** | Embedded first step of `/quote` with the type preset |

**Anti-thin rules (enforced in review, and partly by test):**
- ≥ 60% of the body is unique to the page. Shared blocks (process, ownership facts) are
  partials and do not count toward the 900 words.
- No city-swapped variants ("… in Pretoria", "… in Johannesburg"). Location goes in
  `LocalBusiness` schema and the Google Business Profile.
- A service without enough distinct substance stays a **section on its line page**.

## 5.5 Wave 1 pages: target terms

Volumes are **unknown until Keyword Planner is run** (owner action). Intent comes from
the Trends related-query check in `02` §2.1.

| Page | Primary term | Secondary terms | Role |
|---|---|---|---|
| `/services/custom-software-development` | custom software development south africa | software development company, bespoke software | Conversion and acquisition (the "software development company" buyer tail) |
| `/services/web-application-development` | web application development | web app developers, web development company | Acquisition |
| `/services/api-development-and-integration` | system integration | api development, connect systems | Conversion |
| `/services/business-process-automation` | business process automation | workflow automation, approval workflow | Conversion |
| `/services/crm-systems` | crm system | crm for small business, custom crm | **Acquisition** (crm = 100, commercial) |
| `/services/accounting-and-invoicing` | invoicing software | accounting software integration, sage/xero integration | **Acquisition** (+77%) |
| `/services/managed-hosting` | website hosting and maintenance | managed hosting south africa | Conversion (after R1–R3) |
| `/services/managed-it-support` [gated] | it company / it support for small business | managed it services | **Acquisition via local search** ("it company near me" +36%) |

**Wave 2, as capability is confirmed:** `/services/microsoft-365`, `/services/sharepoint`
(+19%), `/services/microsoft-365-security`, `/services/ai-document-processing`,
`/services/booking-systems`.

**Terms we deliberately do not chase with pages:** microsoft 365, azure, aws, cyber
security, it support, backup, chatbot. Their dominant intent is login, careers,
courses or consumer use (`02` §2.1).

## 5.6 Metadata and structured data

- Title: `{Service} in South Africa | 7Hills Digital`, ≤ 60 characters. Meta
  description ≤ 155 characters, with outcome and CTA. No "7Hills Holdings" (CLAUDE.md).
- JSON-LD: `Organization` and `LocalBusiness` sitewide (one Gauteng address, no
  invented service areas). `Service` on service pages (`provider`, `areaServed: ZA`,
  **no `offers` price** unless a published price exists). `FAQPage` on FAQs.
  `BreadcrumbList` everywhere. `Product` with `offers` only on `/packages/{slug}`,
  from the database.
- `sitemap.xml` is generated from routes and published `Offering` rows. Gated rows are
  excluded, and demos stay `noindex`.
- One canonical per page. `/quote?type=` variants canonicalise to `/quote`.

## 5.7 Internal linking

- Every service page links up to its line and hub, sideways to 2 related services, and
  to 1 solution and 1 industry.
- Every solution page links to the 2–3 services that solve it.
- Every industry page links to its platform (if any), its demo (if any), and 3 services.
- Homepage → 8 featured services, 6 solutions, 6 industries, `/packages`, `/work`, `/quote`.
- No orphan pages; a test walks the sitemap and asserts every URL is linked from at
  least one other page.

## 5.8 Off-page (owner actions; no code)

1. **Google Business Profile** for "IT company near me", the biggest buyer term found.
2. Google Search Console plus a sitemap submission.
3. Keyword Planner run for §5.5 terms, before final page titles.
4. CSD registration (needed for any public-sector work later).
