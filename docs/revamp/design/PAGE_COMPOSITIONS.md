# Page Compositions

**Prepared:** 30/10/2026 · **Status:** For visual approval.
Deliverables **2** (desktop homepage), **3** (mobile homepage), **4** (enterprise
service page) and **5** (business platform page). The homepage is built as a
prototype on staging; 4 and 5 are specified here for approval before they're built.

Grid references: desktop 1440px viewport, `container-wide` 1280px, 12 columns,
32px gutters. Mobile 390px, 4 columns, 20px side padding.

---

## A. Homepage — desktop (1440px)

```
┌────────────────────────────────────────────────────────────────────────────────┐
│ HEADER (72px, transparent over night; paper + blur after the hero)             │
│ [logo-inverse 56px]  Platforms▾ Solutions▾ Enterprise▾ Industries▾ Work About  │
│                                         [See platforms]  [Book a consultation] │
├────────────────────────────────────────────────────────────────────────────────┤
│ 1 HERO — night band, seven-hills contours masked in mint at ~14% on the right  │
│ ┌ cols 1–6 ─────────────────────────┐  ┌ cols 7–12, bleeds 120px off right ──┐ │
│ │ ● Platforms · Solutions · Enterprise│  │ ┌ browser frame (e3) ─────────────┐ │ │
│ │                                    │  │ │ admin /enquiries screenshot      │ │ │
│ │ Technology That                    │  │ │ (back office, staging, fictional)│ │ │
│ │ Works for Your                     │  │ └─────────────────────────────────┘ │ │
│ │ Business.          (t-hero, 84px)  │  │   ┌ phone ┐ overlaps bottom-left, │ │
│ │                                    │  │   │ realty│ −48px; annotation      │ │
│ │ From ready-to-use business         │  │   │ demo  │ leader lines label     │ │
│ │ platforms to intelligent custom…   │  │   └───────┘ both screens           │ │
│ │ [Explore Business Platforms →]     │  └──────────────────────────────────────┘ │
│ │ [Discuss a Custom Solution]        │                                          │
│ │ ───────────────────────────────    │                                          │
│ │ From R880/mo │ R0 to build │ Live at Excellent RE                            │
│ └────────────────────────────────────┘                                          │
│ ─ 1px mint horizon line ─                                                        │
├────────────────────────────────────────────────────────────────────────────────┤
│ 2 PROBLEMS — paper. Heading left (cols 1–7), lede right (cols 8–12)            │
│ ┌ A: Leads lost in inboxes (cols 1–7, 2 rows) ─┐ ┌ B: 62% stat (cols 8–12) ──┐ │
│ │ copy + genuine enquiries screenshot bleeding │ │ "of SA small businesses…" │ │
│ │ out of the tile's bottom-right edge          │ │ source: Xero 2026, n=427  │ │
│ │                                              │ ├ C: Getting paid on time ──┤ │
│ │                                              │ │ Invoicing · Available now │ │
│ └──────────────────────────────────────────────┘ └───────────────────────────┘ │
│ ┌ D: Admin that eats the week ┐ ┌ E: Paper & approvals ┐ ┌ F: Flying blind ───┐ │
│ │ flow diagram (Illustration) │ │ doc-stamp diagram    │ │ bar diagram        │ │
│ └─────────────────────────────┘ └──────────────────────┘ └────────────────────┘ │
├────────────────────────────────────────────────────────────────────────────────┤
│ 3 SPECTRUM — mist. "Ready-made or built for you — and everything between."     │
│  more ready-made ◄──────────────────────────────────────────────► more bespoke │
│     ●────────────────────────●────────────────────────●                        │
│  PLATFORMS                 SOLUTIONS                 ENTERPRISE                │
│  Live in 2–3 weeks         Scoped to the problem     Phased, from discovery    │
│  From R880/mo, R0 build    Module price or quote     Fixed-scope or retainer   │
│  [Explore platforms]       [See solutions]           [Discuss a project]       │
├────────────────────────────────────────────────────────────────────────────────┤
│ 4 SHOWCASE — night, contours faint. Panel top edge overlaps band above by 64px │
│ ┌ tab rail cols 1–4 ───┐ ┌ panel cols 5–12 ───────────────────────────────────┐ │
│ │ 01 Custom software  ▸│ │ H3 outcome · 3 benefits · Typical engagement       │ │
│ │ 02 Process automation│ │ ┌ visual: genuine screenshot (custom software =    │ │
│ │ 03 Systems integrat. │ │ │ our own platform) or labelled diagram           │ │
│ │ 04 AI applications ◌ │ │ └──────────────────────────────────────────────── │ │
│ │ 05 Microsoft 365   ◌ │ │ badge · [Discuss this →]                          │ │
│ │ 06 Mobile apps     ◌ │ └────────────────────────────────────────────────────┘ │
│ │ 07 BI & reporting  ◌ │   ◌ = capability unconfirmed: shown ONLY on staging   │
│ └──────────────────────┘     with a dashed "Preview only" marker               │
├────────────────────────────────────────────────────────────────────────────────┤
│ 5 INDUSTRIES — paper. Segmented control (6) with a sliding indicator           │
│  [Automotive][Property][Recruitment][Hospitality][Professional][Corporate ops] │
│ ┌ cols 1–5: pains, platform + live price, [View demo] [Platform details] ────┐ │
│ └ cols 6–12: browser + phone frames with that demo's screenshots, labelled ──┘ │
│  Closing strip: "Your industry does not have to be one of the five." …         │
├────────────────────────────────────────────────────────────────────────────────┤
│ 6 LIVE WORK & DEMOS — mist                                                     │
│ ┌ Live work (cols 1–7): Excellent Real Estate card ┐ ┌ Invoicing (cols 8–12) ┐ │
│ │ delivered list · Visit the live site · Built by  │ │ Invoice Ninja,        │ │
│ │ 7Hills                                           │ │ self-hosted… (attrib.)│ │
│ └──────────────────────────────────────────────────┘ └───────────────────────┘ │
│  "We do not walk prospects through a client's back office…"                    │
│  Demo rail: 5 thumbnails, each with a DEMO ribbon → /demos/{slug}              │
├────────────────────────────────────────────────────────────────────────────────┤
│ 7 PROCESS — paper. Segmented: [Platform subscription] [Custom project]         │
│  ①──────②──────③──────④  each step: title, what happens, duration             │
│  Project track adds a governance row (4 commitments)                           │
├────────────────────────────────────────────────────────────────────────────────┤
│ 8 PLATFORMS & PRICING — mist. brand.promise as the heading                     │
│  Price rail: one row per package: icon · name · vertical · 5GB R880 ·          │
│  10GB R1 180 · Demo → · Details →   (all from DB)                              │
│  Add-ons line (flat vs metered) · email note · excl. VAT · bulk note           │
├────────────────────────────────────────────────────────────────────────────────┤
│ 9 TRUST — night. Ledger: left statement (t-subtitle), right detail, hairlines  │
│  Items whose precondition is unmet (off-site backups, MFA) are hidden          │
├────────────────────────────────────────────────────────────────────────────────┤
│ 10 CTA SPLIT — paper, contours faint in the corners                            │
│ ┌ Start with a platform ─────────┐ ┌ Talk to us about a project (night) ──────┐ │
│ └────────────────────────────────┘ └──────────────────────────────────────────┘ │
├────────────────────────────────────────────────────────────────────────────────┤
│ FOOTER — night. 4 columns · hardware note · © 7Hills Digital                   │
└────────────────────────────────────────────────────────────────────────────────┘
```

Why this order: the hero splits the audience; **2–3** speak to the problem and
the choice; **4** proves engineering depth (enterprise); **5–6** prove fit and
honesty (SME); **7** lowers risk; **8** gives SMEs the numbers; **9–10** close both
journeys.

---

## B. Homepage — mobile (390px)

```
┌──────────────────────────────┐
│ [logo 56px]           [≡]    │  sheet menu: accordions + 2 full-width CTAs
├──────────────────────────────┤
│ HERO (night)                 │
│ ● Platforms · Solutions …    │  pill wraps to 2 lines if needed
│ Technology That Works        │  t-hero ≈ 42px, balanced
│ for Your Business.           │
│ lede (17px)                  │
│ [Explore Business Platforms] │  full width, 52px tall
│ [Discuss a Custom Solution]  │
│ From R880/mo · R0 to build   │  ledger as 2 rows
│ ┌ phone frame centred ┐      │  the browser frame peeks behind at 60%
│ └────────────────────┘      │  scale; annotations become captions below
├──────────────────────────────┤
│ PROBLEMS: single column      │  order: B (62% stat) → A (leads + screenshot)
│                              │  → C → D → E → F; diagrams shrink to 72px
├──────────────────────────────┤
│ SPECTRUM: vertical track     │  stations stacked; the line runs down the left
├──────────────────────────────┤
│ SHOWCASE: chip row scrolls ⇆ │  scroll-snap chips (01–07); panel below;
│                              │  visual on top, text under
├──────────────────────────────┤
│ INDUSTRIES: chips scroll ⇆   │  phone frame only (no browser) to save height
├──────────────────────────────┤
│ LIVE WORK: stacked cards     │  demo rail: horizontal scroll-snap
├──────────────────────────────┤
│ PROCESS: vertical timeline   │
├──────────────────────────────┤
│ PRICING: one card per package│  name, two tier prices, Demo / Details links
├──────────────────────────────┤
│ TRUST ledger stacked         │
│ CTA split stacked            │
│ FOOTER accordion columns     │
│ WhatsApp float (unchanged)   │
└──────────────────────────────┘
```

Mobile rules:
- Tap targets ≥ 44px.
- No hover-only information.
- Horizontal scrollers show a partial next item (peek), so their affordance is visible.
- Sticky header is 64px.
- Total page height target: ≤ 14 viewport heights.

---

## C. Representative enterprise service page — `/enterprise/custom-software-development`

Status: `built_to_order`. Journey P.

```
HEADER
BREADCRUMB  Home › Enterprise › Custom software development
HERO (night, contours)
  cols 1–7: eyebrow "Enterprise · Custom software"
            H1 "Software built around how your business actually works."
            lede · badge "Built to your requirements"
            [Book a discovery consultation]  [See how we work ↓]
  cols 8–12: browser frame — our own back office (genuine, staging, fictional
            data) captioned "The platform this site runs on — built by 7Hills"
WHEN IT'S THE RIGHT CHOICE (paper)
  3-column typographic list: "Off-the-shelf forces a workaround" ·
  "Your process is your advantage" · "Systems that don't talk"
WHAT WE BUILD (mist, bento)
  Line-of-business apps · Back offices and admin panels · Client and staff
  portals · Integrations and APIs (each: 2 lines + a diagram, labelled Illustration)
HOW WE DELIVER (paper) — timeline
  Discovery (1–2 wks, fixed price) → Design (clickable prototype) →
  Build (2-week sprints, demo each sprint) → Run (hosting, support, SLA)
  Governance row: fixed-scope phases · weekly status · IP and data terms in
  writing · POPIA operator agreement
TECHNOLOGY (night) — honest stack: Laravel · PHP 8.4 · MySQL · Filament ·
  Tailwind · Alpine · Docker. "We choose boring, proven technology on purpose."
PROOF (mist) — "Our own platform" case: the Listings platform at Excellent
  Real Estate (public facts only). No invented clients, logos or metrics.
ENGAGEMENT MODELS (paper) — 3 columns: Discovery sprint · Fixed-scope project
  · Monthly retainer. Price: "Quoted" (or DB values if D4 publishes them)
FAQ (paper) — <details>, FAQPage JSON-LD
CTA (night) — "Describe the problem. We'll tell you honestly what it takes."
  [Book a discovery consultation] → /contact/project?offering=custom-software-development
FOOTER
```

---

## D. Representative business platform page — `/packages/listings` (redesign of an existing page)

Status: `available`. Journey S. **URL, data, prices and tests unchanged.**

```
HEADER
BREADCRUMB  Home › Platforms › Listings
HERO (night)
  cols 1–6: eyebrow "Platform · Estate agencies"  H1 "Listings"
            lede from Package.description · price block from DB:
            "From R880 /month excl. VAT · R0 to build · Cancel with a month's notice"
            [Ask about Listings] (→ /contact?package=listings)  [View the live demo]
  cols 7–12: browser frame (realty demo, desktop) + phone frame (realty, mobile),
             captioned "Demo tenant · fictional business"
IN PRODUCTION (mist) — Excellent Real Estate: "Running on Listings since 2026",
  delivered list, Visit the live site (public only)
WHAT YOU GET (paper) — two columns:
  "The website your buyers see" (what_you_get JSON) | "The back office your
  agents work in" (screenshot: back office, staging, fictional data)
CHOOSE YOUR TIER (mist) — two tier cards from PackageTier (5GB R880 · 10GB
  R1 180), storage note, email note (brand.storage.email_note), bulk note
ADD-ON MODULES (paper) — reuses add-ons data (flat vs metered, "charged separately")
WHO OWNS WHAT (night) — brand.ownership quoted (existing test)
NOT QUITE YOUR TRADE? (paper) — the other 4 platforms + "Your industry does not
  have to be one of the five"
CTA split
FOOTER
```

Acceptance for C and D: every price from the DB; every image with a manifest entry;
availability badges from data; existing `PublicSiteTest`, `AddOnTest` and
`DemoSiteTest` pass unchanged.
