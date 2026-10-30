# 4 · Revised Homepage Composition

**Prepared:** 30/10/2026 · **Status:** For design approval, **not yet built**.
**Builds on:** the approved v2 prototype (`72714f4`). The visual system, tokens,
contour motif, components and motion rules are all kept (`design/DESIGN_SYSTEM.md`).
This document changes **what the page says and in what order**.

---

## 4.1 What changes from the v2 prototype

| v2 prototype | v3 (this proposal) | Why |
|---|---|---|
| H1 "Technology That Works for Your Business." | **"Software Development, AI, Cloud & IT Solutions for Modern Businesses."** (conditional, `01` §1.2) | Full-service positioning in the first line |
| Pathways: Explore Business Platforms / Discuss a Custom Solution | **Get a Quote** (primary) / **Explore Services** (secondary), plus a text link "Need a website? See our ready-to-use platforms" | Projects lead; platforms stay one click away |
| "Three ways to work with us" spectrum | **Removed.** Folded into Services and Platforms | It repeated the hero |
| Showcase of 7 services (tabs) | **Featured services: 8 cards** across the lines, plus "All 50+ services" | Breadth at a glance |
| Problems bento (8) | **Solutions: 6 problems** → solution pages | Less text; each links somewhere real |
| — | **New: "Built by 7Hills"** (real software, real screenshots) | Proves development capability (brief item 4) |
| — | **New: Microsoft 365 and automation spotlight** (gated) | Brief item 5 |
| Process toggle (two tracks) | **Folded into the quote section** as 4 steps | Removes a whole section |
| Trust ledger | **Folded into the quote section** as one row of true facts | Removes a whole section |
| Industries, Live work, Platforms, CTA | **Kept**, tightened | — |

**Copy budget:** v2 carries ~2 440 words in its markup, closed tab panels included
(measured on staging). v3 targets **≤ 1 200 words in the markup** and **≤ 600
visible on first load on mobile**, with secondary lines collapsed.

## 4.2 Section order

The 10 sections map one-to-one to the brief's 10 requirements.

```
 ┌─ HEADER  Services▾ Solutions▾ Industries▾ Platforms Our Work About  [Get a Quote]
 │
 1  HERO (night band, contours)        → full-service capability, two CTAs
 2  FEATURED SERVICES (paper)          → 8 services, "All services →"
 3  SOLUTIONS (mist)                   → 6 business problems → solution pages
 4  BUILT BY 7HILLS (night)            → genuine software, labelled screenshots
 5  MICROSOFT 365 & AUTOMATION (paper) → gated spotlight (fallback: automation & integration)
 6  READY-TO-USE PLATFORMS (mist)      → 5 platforms, DB prices, pay-nothing-until-live
 7  INDUSTRIES (paper)                 → 6 industry tabs
 8  OUR WORK (night)                   → live work + demo rail
 9  GET A QUOTE (paper)                → 7 enquiry-type chooser + 4 steps + true facts
10  CONSULTATION CTA (night)           → one sentence, one button
 └─ FOOTER
```

Bands alternate **night / paper / mist** so no two adjacent sections share a
background, as in v2.

## 4.3 Section by section (desktop ≥ 1024px)

### 1 · Hero (night, contours, depth on fine pointers)
```
┌──────────────────────────────────────────────────────────────────────────────┐
│  SOFTWARE · MICROSOFT 365 · AI · CLOUD · IT SUPPORT        (t-label, mint)    │
│                                                                              │
│  Software Development, AI,               ┌───────────────┐                    │
│  Cloud & IT Solutions for                │ back office   │╲  ┌──────┐         │
│  Modern Businesses.                      │ (real, staging│ ╲ │phone │         │
│                                          │  fictional)   │   │ demo │         │
│  From custom applications and intelligent└───────────────┘   └──────┘         │
│  automation to Microsoft 365, cloud …     · Back office · staging, fictional  │
│                                                                              │
│  [ Get a Quote ]  [ Explore Services ]                                       │
│  Need a website? See our ready-to-use platforms →                            │
│ ─────────────────────────────────────────────────────────────────────────── │
│  Built and run in-house  ·  One partner, one invoice  ·  POPIA-literate       │
└──────────────────────────────────────────────────────────────────────────────┘
```
- The eyebrow lists only **confirmed** lines (gated like the H1).
- The visual stays **real software**: the v2 screens, labelled. No stock imagery and no
  fake dashboards.
- The proof row has three **true** statements, with no numbers we cannot support.
- LCP stays the H1 text.

### 2 · Featured services (paper)
```
  WHAT WE DO
  Seven service lines. One accountable team.                      All 50+ services →
  ┌────────────┐┌────────────┐┌────────────┐┌────────────┐
  │ ⌘ Custom    ││ ⇄ API &    ││ ⚙ Process  ││ ◎ CRM      │
  │ software    ││ integration││ automation ││ systems    │
  │ 1-line value││            ││            ││            │
  │ Software ·  ││ Software · ││ AI & auto ·││ Business   │
  └────────────┘└────────────┘└────────────┘└────────────┘
  ┌────────────┐┌────────────┐┌────────────┐┌────────────┐
  │ M365 &      ││ Managed IT ││ AI document││ Managed    │
  │ SharePoint* ││ support*   ││ processing*││ hosting†   │
  └────────────┘└────────────┘└────────────┘└────────────┘
```
- Eight cards: **icon, name, one line, line label, arrow**. `tile` + spotlight.
- Cards marked * render only when confirmed; † only after R1–R3. Their slots fall back
  to confirmed Tier 1 services (web applications, accounting and invoicing, booking
  systems, websites), so the grid is **always 8 and never shows a gap**.

### 3 · Solutions (mist)
```
  WHAT IS SLOWING YOU DOWN?
  ┌─────────────────────────┐ ┌─────────────────────────┐ ┌─────────────────────────┐
  │ Work lives in           │ │ Leads go cold in        │ │ Invoices go out late    │
  │ spreadsheets            │ │ someone's inbox         │ │                         │
  │ → Replace spreadsheets  │ │ → Never lose a lead     │ │ → Get paid on time      │
  ├─────────────────────────┤ ├─────────────────────────┤ ├─────────────────────────┤
  │ Approvals wait on paper │ │ Systems don't talk      │ │ IT is nobody's job      │
  │ → Automate approvals    │ │ → Connect your systems  │ │ → Managed IT*           │
  └─────────────────────────┘ └─────────────────────────┘ └─────────────────────────┘
  Your industry does not have to be one of the five.  (test-pinned line kept)
```
- Six problems, each one line plus a link to `/solutions/{slug}`.
- The 62% Xero statistic (verified) stays on "Invoices go out late", with its source.
- If managed IT is not confirmed, the sixth tile becomes "Your website is out of date →
  Modernise your website".

### 4 · Built by 7Hills (night)
```
  PROOF, NOT PROMISES
  Software we wrote, running today.
  [Back office] [Platform engine] [Client invoicing]            ← tabs
  ┌───────────────────────────────────────┐  • What it does (3 bullets)
  │  real screenshot, labelled            │  • Built with: Laravel · PHP · MySQL ·
  │                                       │    Docker · Filament   (verified only)
  └───────────────────────────────────────┘  • "Back office · staging, fictional data"
```
- Reuses the v2 showcase component, but **only with things that exist**:
  1. Our back office (staging, fictional data)
  2. The platform engine (demo tenants)
  3. Client invoicing: **InvoiceShelf**, hosted and maintained by us, labelled as
     third-party software (Live-work rule 4)
- The technology strip lists only stacks evidenced in our own code and infrastructure.

### 5 · Microsoft 365 & automation spotlight (paper), **gated**
```
  ┌──────────────────────────────┐   GET MORE FROM MICROSOFT 365
  │  diagram:hub (Illustration)  │   SharePoint, Power Automate and Teams, set up
  │  documents · approvals ·     │   around how your business works.
  │  people                      │   ✓ Document libraries people use
  └──────────────────────────────┘   ✓ Approvals that do not wait in an inbox
                                     ✓ Security and MFA configured properly
                                     [ Talk to us about Microsoft 365 ]
```
- **Renders only if the Microsoft line is confirmed.** Otherwise the same slot shows
  **"Automation & integration"** (the `diagram:flow` variant), which is built-to-order
  today. The page never loses the section, only changes its subject.

### 6 · Ready-to-use platforms (mist): **kept from v2**, compressed
- Heading: "Need a website now? Pay nothing until your website is live." (test-pinned)
- Price rail of 5 platforms with **database prices**, "Cancel any time", link to
  `/packages` and `/demos`. One row; no add-on detail (that lives on `/packages`).

### 7 · Industries (paper): **kept from v2**
- Six tabs; each shows three pains, linked platform, demo and solution links.
- Copy tightened to one line per pain.

### 8 · Our work (night): **kept from v2, with one correction**
- Live work from `config/clients.php`.
- **Correction needed:** Excellent Real Estate's invoicing is now **InvoiceShelf**, not
  Invoice Ninja, which was retired on 25/08/2026 (runbook §8b). The current
  **production** homepage makes the same out-of-date claim. That fix is a production
  change and is raised separately in `08_IMPLEMENTATION_PLAN.md` §8.2.
- Demo rail: the five tenants, labelled "Demo tenant · fictional business".
- "We do not walk prospects through a client back office." (test-pinned)

### 9 · Get a quote (paper)
```
  TELL US WHAT YOU NEED
  ┌──────────┐┌──────────┐┌──────────┐┌──────────┐
  │ Custom   ││ Microsoft││ AI &     ││ Managed  │
  │ software ││ 365*     ││ automat. ││ IT*      │
  └──────────┘└──────────┘└──────────┘└──────────┘
  ┌──────────┐┌──────────┐┌──────────┐
  │ Cloud &  ││ Security ││ Website  │
  │ infra*   ││ review*  ││ platform │
  └──────────┘└──────────┘└──────────┘
  1 Tell us → 2 We call you → 3 Written proposal → 4 Build and support
  One invoice · Your data stays yours · No lock-in on your domain and email
```
- Each chip deep-links to `/quote?type={type}`. "Website platform" goes to the
  existing `/contact` flow, unchanged.
- Unconfirmed types fold into "Something else" until confirmed.
- **Step 2 carries no time promise** until the owner sets a response SLA (decision Q4).
- The fact row is true today; `brand.ownership` is the source.

### 10 · Consultation CTA (night, contours)
- "Have a project in mind? Let's talk it through." · [Get a Quote] · phone and WhatsApp.
- Line kept: "You focus on running the business. We will take care of the IT." (test-pinned)
- Whether the consultation is **free** is decision Q5. Until decided, no "free".

## 4.4 Mobile (390px)

| Section | Mobile treatment |
|---|---|
| Hero | H1 in 4 lines (clamp as v2); supporting text in 2 lines; CTAs stacked full-width; phone frame only, no depth effect; proof row becomes three small chips |
| Featured services | **Horizontal snap rail**, 1.2 cards visible, 8 cards |
| Solutions | Six one-line rows with chevrons; the statistic moves under the row |
| Built by 7Hills | Tabs become a segmented control; screenshot then 3 bullets; tech strip wraps |
| M365 spotlight | Diagram hidden; text and CTA only |
| Platforms | Snap rail (as v2) |
| Industries | Horizontal chip scroller (as v2) |
| Our work | Live work card, then demo snap rail |
| Get a quote | **2-column chip grid** (7 chips + "Something else"); steps as a compact list |
| CTA | Button, then tap-to-call and WhatsApp |

Target: **≤ 12 screen-heights** on mobile. v2 measures ≈ 21: its full-page capture
is 17 957px tall at 390 × 844.

## 4.5 Performance and accessibility budgets (unchanged from v2)

LCP ≤ 2.5 s on throttled mobile · CLS ≤ 0.05 · zero third-party requests · axe 0
violations · keyboard-complete tabs and menus · `prefers-reduced-motion` honoured ·
nothing behind `data-reveal` without the `<noscript>` fallback.

## 4.6 Tests to update when built (staging)

- `HomeV2Test::test_it_renders_both_pathways` asserts "Technology That", "Explore
  Business Platforms" and "Discuss a Custom Solution". It changes to the new H1 rules
  and "Get a Quote" / "Explore Services".
- New: **the H1 never names a line the matrix has not confirmed** (AI, Cloud) in
  production.
- New: **the featured grid always renders 8 cards and never an unconfirmed one** in
  production.
- New: **no page claims Invoice Ninja is running.**
- Kept: every promise test, DB prices, provenance, third-party assets, "7Hills" spelling.
