# Website Sitemap — proposed

**Prepared:** 30/10/2026  **Status:** For approval.
**Rule:** every URL live today keeps working at the same address with the same
meaning. **No redirects are needed.** New pages are additive.

Legend: **●** existing, kept · **★** new · *(noindex)* excluded from search and from `sitemap.xml`

---

## 1. URL tree

```
/                                         ● Home (redesigned)
│
├── A. READY-TO-USE BUSINESS PLATFORMS
│   ├── /packages                         ● Platforms overview + tier table (nav label becomes "Platforms")
│   ├── /packages/listings                ● Estate agencies
│   ├── /packages/showroom                ● Car dealerships
│   ├── /packages/careers                 ● Recruitment agencies
│   ├── /packages/stay                    ● Guesthouses and lodges
│   ├── /packages/business                ● Everyone else
│   ├── /packages#add-ons                 ● Add-on modules (Invoicing, Enterprise email, CRM, ERP)
│   └── /how-it-works                     ● How a subscription works (kept, scoped to Platforms)
│
├── B. BUSINESS SOLUTIONS
│   ├── /solutions                        ★ Hub: problems → solutions
│   ├── /solutions/crm                                  ★ CRM and Customer Management
│   ├── /solutions/business-process-automation          ★
│   ├── /solutions/booking-system                       ★ Appointment and Booking Systems
│   ├── /solutions/document-approval-workflows          ★
│   ├── /solutions/business-reporting-dashboards        ★
│   ├── /solutions/invoicing-accounting-integration     ★
│   └── /solutions/customer-employee-portals            ★
│
├── C. ENTERPRISE SOFTWARE & DIGITAL TRANSFORMATION
│   ├── /enterprise                       ★ Hub: engagement models, process, governance, procurement
│   ├── /enterprise/custom-software-development         ★
│   ├── /enterprise/ai-business-applications            ★ (only if D2 confirms capability)
│   ├── /enterprise/microsoft-365-sharepoint-power-platform ★ (only if D2 confirms)
│   ├── /enterprise/systems-integration                 ★
│   ├── /enterprise/mobile-app-development              ★ (only if D2 confirms)
│   ├── /enterprise/legacy-modernisation                ★
│   └── /enterprise/data-analytics-business-intelligence ★ (only if D2 confirms)
│
├── INDUSTRIES
│   ├── /industries                       ★ Hub
│   ├── /industries/estate-agencies       ★ → links /packages/listings + /demos/realty
│   ├── /industries/car-dealerships       ★ → /packages/showroom + /demos/auto
│   ├── /industries/recruitment           ★ → /packages/careers + /demos/careers
│   ├── /industries/hospitality           ★ → /packages/stay + /demos/stay
│   ├── /industries/plant-and-equipment-hire ★ → /packages/business + /demos/plant
│   └── /industries/professional-services ★ → Business platform + B solutions (no demo)
│
├── PROOF
│   ├── /work                             ★ Client work (from the CaseStudy model; replaces config/clients.php)
│   ├── /work/{slug}                      ★ e.g. /work/excellent-real-estate (public facts only, with permission)
│   ├── /demos                            ● Clearly labelled demonstrations
│   └── /demos/{realty|auto|careers|stay|plant}  ● (noindex — unchanged)
│
├── COMPANY & TRUST
│   ├── /about                            ● (extended: team, capability, credentials)
│   ├── /security                         ★ Hosting, backups, POPIA operator role, access control
│   ├── /custom-and-it                    ● Kept: IT support and managed IT (hardware stays one quiet line)
│   ├── /terms                            ●
│   └── /privacy                          ● (updated in Phase 3 for attribution)
│
├── CONVERSION
│   ├── /contact                          ● Subscription enquiry (package deep links kept: ?package=showroom)
│   ├── /contact/project                  ★ Project / enterprise enquiry (multi-step)
│   ├── /contact/thank-you                ● (noindex)
│   └── /contact/project/thank-you        ★ (noindex)
│
├── /sitemap.xml                          ★
├── /robots.txt                           ● (adds the Sitemap: line)
└── /admin                                ● Back office (noindex; disallowed in robots)
```

**Why `/packages` keeps its URL:** it is indexed, linked from WhatsApp shares and
pinned by tests. Only the **visible label** changes to "Platforms". A rename to
`/platforms` would buy nothing in search and cost a redirect chain.

**Why `/custom-and-it` stays:** it is the existing home of IT support and is
asserted by `LegalPagesTest::test_custom_work_is_sold_as_one_bill`. Its custom-software
copy moves to `/enterprise/custom-software-development`, and `/custom-and-it`
becomes the managed-IT page that links there.

---

## 2. Navigation

### Desktop (switches at `lg`, as today)

```
[logo]  Platforms ▾   Solutions ▾   Enterprise ▾   Industries ▾   Work   About        [See platforms]  [Book a consultation]
```

- **Platforms ▾:** the 5 packages (icon + one line + "from R{tier}") · Add-on modules · How subscriptions work · Demos
- **Solutions ▾:** B1–B7 with availability badges · "Not sure? Describe the problem →" (`/contact/project`)
- **Enterprise ▾:** C items (only those that are published) · Engagement models · Security · Procurement documents
- **Industries ▾:** the 6 industries · "Your industry isn't listed? →"
- Two CTAs: secondary `btn-outline` "See platforms" (subscription journey) and primary `btn-accent` "Book a consultation" (project journey).

### Mobile
Logo · hamburger → accordion of the same four groups (Alpine `x-collapse`, already
installed) + Work + About + the two CTAs stacked full width. The WhatsApp float stays.

### Footer (4 columns + legal row)
| Platforms | Solutions | Enterprise | Company |
|---|---|---|---|
| 5 packages, Add-ons, Demos | B1–B7 | Published C items, Security | About, Work, Custom & IT, Contact, Terms, Privacy & POPIA |

The legal row: © 7Hills Digital (trading name, per the existing rule) · the hardware
note (`<x-hardware-note>`, unchanged) · the ECTA s43 block if D4 restores it.

---

## 3. Indexing rules

| URL group | Index | In `sitemap.xml` | Canonical |
|---|---|---|---|
| Home, packages, solutions, enterprise, industries, work, about, security, how-it-works, custom-and-it, terms, privacy, contact, contact/project | index | yes | self, `https://7hillsdigital.co.za` host |
| `/demos/{slug}` | **noindex, nofollow** (unchanged rule) | no | self |
| `/demos` index | index | yes | self |
| Thank-you pages | noindex | no | — |
| `/admin*` | noindex; `Disallow: /admin` | no | — |
| Unpublished or `capability_unconfirmed` offerings | 404 | no | — |
| Query variants (`?package=`, `?ref=`, `utm_*`) | canonical to the clean URL | no | clean URL |

`sitemap.xml` is generated by the app from the routes and published DB rows, with
`lastmod` from `updated_at`, so it cannot list a page that doesn't exist.

---

## 4. Internal linking map

```
Home ──► each category hub (3) ──► each item page
Industry page ──► its platform package ──► its demo
Industry page ──► 2–3 relevant B solutions
B solution ──► related industry pages + the platform that already includes it (e.g. CRM ↔ Listings pipeline)
B solution (built_to_order) ──► /contact/project?solution={slug}
B solution with a module (Invoicing) ──► /packages#add-ons and /contact?package=…
C service ──► /enterprise (process, governance) ──► /security ──► /contact/project?type=enterprise
Work case study ──► the platform or solution it used ──► the industry
Every page ──► one primary CTA matching its journey (lead strategy §2)
```

Breadcrumbs (visible + `BreadcrumbList` JSON-LD) on every page below a hub.
