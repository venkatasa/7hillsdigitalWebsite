# Website Revamp — Implementation Plan

**Prepared:** 30/10/2026  **Status:** Planning only. No application code is changed until approved.
**Companion documents:** `WEBSITE_SITEMAP.md` · `HOMEPAGE_DESIGN_SPEC.md` ·
`SERVICE_LANDING_PAGES.md` · `LEAD_GENERATION_STRATEGY.md` ·
`PRODUCTION_SAFETY_CHECKLIST.md` · `../../WEBSITE_REVAMP_AUDIT.md`

---

## 1. Goal

Reposition 7Hills Digital as a **business technology solutions provider** with
three clearly separated commercial categories. Everything that exists today
(packages, prices, subscriptions, demos, enquiries, back office) is preserved,
and nothing planned or unbuilt is presented as available.

| Category | What it is | Commercial model | Today |
|---|---|---|---|
| **A. Ready-to-Use Business Platforms** | The 5 existing industry packages (Listings, Showroom, Careers, Stay, Business) + add-on modules | Flat monthly subscription, R0 build, 1 month's notice. **Unchanged.** | Live |
| **B. Business Solutions** | Solutions to named business problems, packaged where existing functionality supports it, otherwise custom-built | Module subscription where packaged; quoted where custom | Mixed (see §2) |
| **C. Enterprise Software & Digital Transformation** | Project-based services for larger or complex needs | Discovery → fixed-scope phase or retainer; always quoted | Services offered; **no case studies yet** |

---

## 2. Availability — the honesty rule made concrete

Every B and C item carries one of four statuses. The status is **stored in the
database and rendered on the page**, so it cannot drift from what is real.

| Status | Meaning on the page | Allowed claims |
|---|---|---|
| `available` | "Available now" | Screenshots, price, "live at…" |
| `built_to_order` | "Built to your requirements" | The capability and the process; **no** product screenshots; illustrative mock-ups labelled *Illustrative* |
| `in_development` | "In development — register interest" | Roadmap language only; no price, no screenshots |
| `capability_unconfirmed` | **Not published** | Internal only, until the owner confirms the team can deliver it |

Initial classification (**owner to confirm**, decision D2):

| # | Item | Proposed status | Basis |
|---|---|---|---|
| B1 | CRM and Customer Management | `built_to_order`; the packaged CRM module stays `in_development` | The Listings platform already captures enquiries into a pipeline (live at Excellent RE); there is no standalone CRM product |
| B2 | Business Process Automation | `built_to_order` | Capability claim; no shipped example yet |
| B3 | Appointment and Booking Systems | `built_to_order` | Not built; the Stay demo is a landing page only |
| B4 | Document and Approval Workflows | `built_to_order` | Rental applications with documents run at Excellent RE (public description only) |
| B5 | Business Reporting and Dashboards | `built_to_order` | Filament dashboards exist in our own panel |
| B6 | Invoicing and Accounting Integrations | Invoicing **`available`** (Invoice Ninja, hosted, attributed); accounting integrations `built_to_order` | Live at Excellent RE |
| B7 | Customer and Employee Portals | `built_to_order` | Capability claim |
| C1 | Custom Software Development | `built_to_order` | This site and the Listings platform are our own software |
| C2 | AI-Powered Business Applications | `capability_unconfirmed` | Owner to confirm delivery capability |
| C3 | Microsoft 365, SharePoint and Power Platform | `capability_unconfirmed` | Owner to confirm skills; **never** imply Microsoft partner status unless held |
| C4 | Enterprise Systems Integration | `built_to_order` | Owner to confirm |
| C5 | Mobile Application Development | `capability_unconfirmed` | No shipped app yet |
| C6 | Legacy Application Modernisation | `built_to_order` | Owner to confirm |
| C7 | Data Analytics and Business Intelligence | `capability_unconfirmed` | Owner to confirm (Power BI etc.) |

---

## 3. Architecture approach

Use the existing **Laravel 13 + Blade + Tailwind v4 + Alpine + Filament v4** stack.
No new front-end framework, no SPA, no CMS, and no third-party runtime assets.

| Concern | Approach |
|---|---|
| Category A | **Untouched** models (`Package`, `PackageTier`, `AddOn`) and routes. New presentation only. |
| Categories B and C | One new model **`Offering`** (`category`: `business`/`enterprise`, `availability`, slug, copy fields, JSON blocks, SEO fields, optional `add_on_id` for price, sort, published, soft deletes) with a Filament resource. One table instead of two keeps the admin simple; the category drives the URL prefix and template. |
| Industries | New **`Industry`** model (optional `package_id`, demo slug, pain points, related offerings). |
| Proof | New **`CaseStudy`** model that absorbs `config/clients.php`. The publish gate is `permission_confirmed && software_attribution`. Demos stay in `config/demos.php`. |
| Product imagery | `public/images/product/` with a `manifest.json` declaring each image's **source** (`demo`, `staging-fictional`, `client-public`, `third-party-ui`) and alt text. A test fails if any image lacks provenance or comes from a client back office. |
| Leads | Additive changes to `enquiries` + new `sales_consultants` and `enquiry_events` tables. See `LEAD_GENERATION_STRATEGY.md`. **Not built until approved.** |
| SEO | Route-driven `sitemap.xml`, per-page meta from the DB, JSON-LD partials. See `SERVICE_LANDING_PAGES.md`. |
| Prices | Never in Blade. B and C prices come only from `add_ons` via `add_on_id`, or show "Quoted". |

---

## 4. Phases

Complexity: **S** ≤ 1 day · **M** 2–3 days · **L** 4–6 days (one developer with AI
assistance, including tests). Estimates assume decisions arrive on time.

### Phase 0 — Safe ground ✅ (done 30/10/2026)
Worktree, staging, verified backup, test baseline (75/410). Outstanding: Gitea
push (owner), off-site backups (R1).

### Phase 0.5 — Production remediation (separate approvals)
R1–R3 and R7 from the safety checklist. **S** each. Independent of the redesign,
but **R3 should land before any new traffic is driven to the site.**

### Phase 1 — Foundation (no change to site structure)
| Work | Size | Depends on |
|---|---|---|
| Design-system extension: new utilities (`h-hero`, gradient border, mock-frame, glass chip, stat) and components (see homepage spec §3) | M | — |
| Replace every hard-coded price (8 places, audit §7.2) with DB reads; widen the price-in-markup test | S | — |
| Correct the Live Work walkthrough copy (audit §7.2 #6) | S | — |
| R4 timezone, R5 proxies and headers | S | approval |
| SEO base: meta component, JSON-LD partials, `sitemap.xml`, `robots.txt` sitemap line | M | — |
| Product-imagery pipeline: manifest + provenance test; capture screenshots from staging with fictional data | M | D6 |
**Exit:** all existing pages render with the new components; 75 baseline tests + new tests green; no visual regression in the demos.

### Phase 2 — Content model and new pages
| Work | Size | Depends on |
|---|---|---|
| `Offering`, `Industry` migrations, models, factories, Filament resources | M | D2 |
| Seeders with approved copy for B1–B7, C1–C7, industries | M | D2, copy sign-off |
| `/solutions`, `/solutions/{slug}`, `/enterprise`, `/enterprise/{slug}`, `/industries`, `/industries/{slug}` controllers and templates | L | above |
| Navigation (mega-menus at `lg`, mobile accordion) and footer | M | sitemap sign-off |
| New homepage (10 sections) | L | homepage spec sign-off |
| Rename the "Packages" nav label to "Platforms"; the URL stays `/packages` | S | D1 |
**Exit:** every new page renders from the DB; an unpublished offering 404s; availability badges are correct; the sitemap lists all published URLs.

### Phase 3 — Lead generation *(needs separate approval of the data-model change)*
| Work | Size | Depends on |
|---|---|---|
| Additive `enquiries` columns + `sales_consultants` + `enquiry_events` | M | D7, D8 |
| Project enquiry form `/contact/project` (multi-step, Alpine) | M | above |
| Attribution capture (UTM, landing page, referrer, consultant `?ref=`) without new cookies | M | privacy-notice update |
| Filament: consultant resource, assignment with audit trail, pipeline board, commission export | L | D8 |
| Privacy notice and terms updates for attribution and consultants | S | legal review |
**Exit:** both journeys create correctly typed enquiries; attribution is stored; every existing `EnquiryTest` still passes unchanged.

### Phase 4 — Proof and trust
| Work | Size | Depends on |
|---|---|---|
| `CaseStudy` model; migrate `config/clients.php`; `/work`, `/work/{slug}` | M | client permission |
| `/security` (hosting, backups, POPIA operator role, access control). **Only state what R1–R6 have made true.** | S | R1–R6 |
| `/enterprise` procurement block (B-BBEE, CSD, tax status, company details) | S | D4, D5 |
**Exit:** every claim on these pages maps to something verifiable.

### Phase 5 — Launch
| Work | Size |
|---|---|
| Full QA: tests, a11y (keyboard, contrast, Lighthouse a11y ≥ 95), performance (Lighthouse perf ≥ 90 mobile, LCP < 2.5 s), cross-browser | M |
| Content proofread; "7Hills" spelling; no group name on marketing pages | S |
| Release per safety checklist §5; Search Console sitemap submission | S |
| 30-day watch: enquiries by type, 404 log, Search Console coverage | S |

**Total:** roughly **25–35 working days** of build after Phase 0.5, excluding decision
and legal-review wait time.

---

## 5. Dependency graph

```
Phase 0 ✅ ──► Phase 0.5 (R1,R2,R3,R7) ──────────────────────────────┐
     │                                                               │
     └─► Phase 1 ──► Phase 2 ──► Phase 4 ──► Phase 5 (launch) ◄──────┘
                        │            ▲
                        └─► Phase 3 ─┘   (Phase 3 can run beside Phase 4)
```

Hard rules:
- **Launch requires R3** (debug off) and **R1** (off-site backup working).
- **Phase 3** requires approval of the enquiry data-model change and the privacy-notice update.
- **Phase 4 `/security`** may state only what R1–R6 have made true.

---

## 6. Test requirements

**Baseline preserved:** all 58 methods / 75 runs stay. Assertions that pin old copy
(e.g. `test_the_home_page_leads_with_the_promise`) are updated **knowingly**, each
listed in the PR description with the reason.

New tests by phase:

| Phase | Tests |
|---|---|
| 1 | No R-figure appears in any rendered page that the DB does not contain; every product image has a manifest entry with an allowed source; `sitemap.xml` is valid XML and lists every public route; JSON-LD parses on every page; security headers present (feature test on the response); timezone is Africa/Johannesburg |
| 2 | Every published offering and industry page renders; unpublished → 404; `in_development` shows its badge and no price; `capability_unconfirmed` is never routable; an offering's price equals its `add_on`'s price; category A pages are byte-for-byte unchanged in pricing content; the no-third-party-asset test runs over every new route; nav links resolve |
| 3 | A project enquiry stores the type and fields; a subscription enquiry is unchanged; a `?ref=` code attributes a consultant; an unknown or inactive code is ignored, not an error; prospect fields stay read-only; consultant reassignment writes an `enquiry_events` row; the honeypot and throttle apply to the new form; a mail failure never loses a project lead |
| 4 | A case study cannot publish without permission and attribution; `/work` never links a back office (reuses the `LiveWorkTest` rules) |
| 5 | The smoke list (safety checklist §6) as a test; Lighthouse checks recorded in the PR |

---

## 7. Rollout

1. Each phase merges to `revamp` and is reviewed on **staging** (`127.0.0.1:8096`).
2. **Go-live is one release** at the end of Phase 5, not drip-fed. Mixing old and
   new navigation on the public site would confuse visitors. Exception: Phase 1
   price and copy fixes and R4/R5 may ship early as a hotfix release, because
   they correct live inaccuracies.
3. Release follows safety checklist §5 (backup → tag → deploy → smoke → watch).
4. Rollback per safety checklist §4. All migrations are additive, so old code
   runs against the new schema.

---

## 8. Decisions required before Phase 2

| # | Decision | Needed by |
|---|---|---|
| D1 | Category names as shown: "Platforms" / "Business Solutions" / "Enterprise" | Phase 2 |
| D2 | Confirm the availability status of every B and C item (§2), especially C2, C3, C5, C7 | Phase 2 |
| D3 | The positioning line (proposed: *"Business technology that runs your company — from ready-made platforms to enterprise software."*) | Phase 2 |
| D4 | Restore ECTA s43 disclosures (strongly recommended before courting enterprise) | Phase 4 |
| D5 | Procurement documents to publish: B-BBEE level/affidavit, CSD number, tax compliance PIN | Phase 4 |
| D6 | Screenshot sources: approve staging with fictional data + public demo pages + the Excellent RE **public** site (with its permission) | Phase 1 |
| D7 | Approve the enquiry data-model extension (lead strategy §4) | Phase 3 |
| D8 | Consultant commission rules: rate, basis, duration, attribution window, payout trigger | Phase 3 |
| D9 | The unofficial logo image `Complete Technology solutions.jpg`: discard, or commission an official lockup | Phase 1 |
| D10 | Third-party product names and logos (Microsoft, Sage, Xero): text names only (recommended) or logos under each vendor's brand rules | Phase 2 |
