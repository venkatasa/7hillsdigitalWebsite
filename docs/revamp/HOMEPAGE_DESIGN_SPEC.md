# Homepage Design Specification

**Prepared:** 30/10/2026  **Status:** For approval. Copy is draft; final wording needs sign-off.
**Built with:** Blade + Tailwind v4 + Alpine.js, existing `config/brand.php` tokens.
No new colours, fonts, frameworks or third-party assets.

---

## 1. Design principles

1. **Two audiences, one page.** An SME owner must find the price in about 5 seconds;
   an enterprise decision-maker must find process, governance and proof in about 30.
   Every section names which journey it serves.
2. **Show the real thing.** Real screenshots (provenance-tracked) beat
   illustration. Where a mock-up is used, it is labelled *Illustrative*.
3. **Truth is a design element.** Availability badges, "demo" labels and
   third-party attribution are styled as first-class UI, not footnotes.
4. **Premium through restraint.** Generous space, strong type, one accent per
   view, subtle depth. No stock photos, no confetti, no auto-playing carousels.

---

## 2. Visual system (extends the existing tokens)

### 2.1 Colour use (official palette only)
| Surface | Background | Text | Accent / CTA |
|---|---|---|---|
| Dark band | `brand` #1A2E22 or `brand-dark` #122118 | white, `white/70` for body | `mint` (`btn-mint`, `eyebrow-light`) |
| Light band | white / `brand-light` #F8FAF9 | `ink`, `secondary`, `muted` | `accent` (`btn-accent`, `eyebrow`) |
| Gradient edge (new) | 1px border `linear-gradient(accent → mint)` at 40% | — | Featured cards only |
| Glow (existing) | `mint` / `accent` blurs at 8–12% opacity | — | Dark bands only |

Body text stays ≥ 4.5:1. Mint is never used on white; emerald is never used on Deep Forest.

### 2.2 Typography scale
| Token | Use | Size (mobile → desktop) | Weight |
|---|---|---|---|
| `h-hero` ★ | Hero headline only | 40 → 72 px, leading 1.04, tracking −0.02em | Montserrat 800 |
| `h-display` ● | Page H1s | 36 → 60 px | 800 |
| `h-section` ● | Section H2s | 30 → 36 px | 700 |
| `h-card` ★ | Card titles | 18 → 20 px | Montserrat 700 |
| `lede` ● | Intro paragraphs | 18 → 20 px | Jakarta 400 |
| body | Copy | 16 px / 1.65 | Jakarta 400 |
| `eyebrow` ● | Kickers | 12 px, 0.18em caps | Montserrat 700 |

### 2.3 Layout
- `container-page` (max-w-6xl) for text; a new `container-wide` ★ (max-w-7xl) for bento grids and mock-ups.
- Section rhythm: the existing `section` utility (64 / 80 / 96 px). Dark and light bands alternate.
- Grid: 12 columns from `lg`, 6 from `md`, 1 on mobile. Gutters 24 px (32 px from `lg`).

### 2.4 Motion
- The existing `data-reveal` (fade + 14 px rise, staggered) on section headings and grid items only.
- New ★: `data-count` count-up on stat figures (runs once, final value in the HTML); a hover lift on cards (`card-hover`); a tab cross-fade 150 ms.
- **All motion is disabled under `prefers-reduced-motion`.** No content depends on motion. No parallax, no autoplay, no scroll-jacking.

### 2.5 New components (`resources/views/components/`)
| Component | Purpose |
|---|---|
| `section-heading` | eyebrow + H2 + lede, light or dark variant (picks `eyebrow` or `eyebrow-light` automatically) |
| `pathway-card` | Hero pathway chooser (icon, title, three facts, CTA) |
| `bento-grid`, `bento-tile` | Asymmetric problem/solution grid (sizes `sm`, `wide`, `tall`) |
| `offering-card` | B/C item: icon, name, one-line outcome, **availability badge**, link |
| `availability-badge` | `Available now` (accent) · `Built to your requirements` (outline) · `In development` (muted) |
| `mock-browser`, `mock-phone` | CSS device frames wrapping a screenshot or an HTML-drawn UI, with an optional *Illustrative* or *Demo data* label |
| `stat-band` | 3–4 figures with `data-count` |
| `process-steps` | Numbered horizontal (desktop) / vertical (mobile) steps |
| `tabs` | Accessible Alpine tabs (`role=tablist`, arrow-key navigation) |
| `industry-card` | Industry with linked platform and demo |
| `proof-card` | Client work or demo, with a mandatory `kind` label: **Client** or **Demo** |
| `trust-item` | Icon + claim + "how we know" link |
| `cta-band` | Closing two-path CTA |

---

## 3. Sections

Order and journeys (S = subscription, P = project / enterprise):

| # | Section | Band | Journey |
|---|---|---|---|
| 1 | Hero with two pathways | dark | S + P |
| 2 | Business problems we solve | light | S + P |
| 3 | Ready-to-use platforms vs custom solutions | light-wash | S + P |
| 4 | Featured technology services | dark | P |
| 5 | Industry-specific solutions | light | S |
| 6 | Client work and labelled demonstrations | light-wash | S + P |
| 7 | Development and delivery process | light | P |
| 8 | Subscription packages | light-wash | S |
| 9 | Trust, security and support | dark | P |
| 10 | Consultation and enquiry CTAs | light | S + P |

---

### Section 1 — Hero with two customer pathways

**Purpose:** say what we are in one line, and route each visitor to their journey at once.

**Layout:**
- **Desktop:** two columns, 7/5. Left: eyebrow, `h-hero`, lede, two pathway cards side by side. Right: `mock-browser` showing a **real** screenshot of the Listings platform's public site (demo `realty`, labelled *Demo data*), overlapped by a smaller `mock-phone` of the same demo's mobile view.
- **Mobile:** headline, lede, pathway cards stacked, mock-up below and scaled down.

**Draft copy:**
- Eyebrow: `Business technology · Midrand, South Africa`
- H1: **"Technology that runs your business — ready-made or built for you."**
- Lede: "Industry platforms you can be using in three weeks, business solutions for the problems that cost you money, and enterprise software built to your specification. One team builds, hosts and supports it."
- Pathway card **S**, "Ready-to-use platforms": • From **R{min tier price}**/month excl. VAT *(from DB)* • R0 to build • Cancel with a month's notice → **See platforms** (`btn-mint`) → `/packages`
- Pathway card **P**, "Solutions & enterprise software": • Scoped to your process • Fixed-scope phases or a retainer • POPIA-aware from day one → **Book a consultation** (`btn-ghost`) → `/contact/project`

**Data:** the lowest `PackageTier.price_monthly` among published packages. **No hard-coded R-figure.**
**Motion:** headline and cards reveal; the mock-up fades in at 200 ms. No looping animation.
**Accessibility:** one `<h1>` on the page; pathway cards are `<a>` blocks with a visible focus ring; the mock-up `alt` describes the screen, and the *Demo data* label is in the alt text too.
**Acceptance:** the price matches `/admin`; both CTAs reach their journeys with `?from=home-hero`; LCP element is the H1 text, not the image (the image is lazy-loaded with fixed dimensions to avoid layout shift).

---

### Section 2 — Business problems we solve

**Purpose:** meet SMEs at their pain, using the outcome language people search for.

**Layout:** `bento-grid`, 6 tiles. Desktop: 2 wide + 4 standard in a 3×2 pattern; mobile: single column.

| Tile | Problem headline | One line | Links to |
|---|---|---|---|
| wide | **Getting paid on time** | "Quotes, invoices and reminders that go out without someone remembering to send them." | `/solutions/invoicing-accounting-integration` |
| std | **Leads lost in WhatsApp** | "Every enquiry in one pipeline your whole team can see." | `/solutions/crm` |
| std | **Admin that eats the week** | "Automate the job someone repeats every Friday." | `/solutions/business-process-automation` |
| wide | **Paper, signatures and approvals** | "Forms, documents and sign-off on a screen, with a record of who approved what." | `/solutions/document-approval-workflows` |
| std | **Bookings by phone only** | "Customers book themselves, day or night." | `/solutions/booking-system` |
| std | **Flying blind** | "The numbers that matter, on one dashboard." | `/solutions/business-reporting-dashboards` |

Each tile carries its offering's `availability-badge` (from the DB).
**Evidence note:** cash flow is the headline problem because **62%** of SA small
businesses report cash-flow issues (Xero 2026, n=427, **verified in the primary
source**). The figure may appear in body copy with its citation. The unverified
late-payment percentages are **not** used.
**Acceptance:** every tile links to a published offering; the badge equals the DB status.

---

### Section 3 — Ready-to-use platforms vs custom solutions

**Purpose:** make the three categories unmistakable and help visitors self-select.

**Layout:** three columns (desktop), stacked on mobile. The middle column has the gradient edge.

| | **Platforms** (A) | **Business Solutions** (B) | **Enterprise** (C) |
|---|---|---|---|
| Best for | A business in one of our five industries that wants to start now | A specific problem: leads, bookings, invoicing, approvals | Complex, integrated or large-scale systems |
| Time to live | 2–3 weeks (`brand.terms.build_time`) | Scoped per solution | Phased, from discovery |
| Pricing | From R{tier}/mo, R0 build | Module price or quoted | Quoted: fixed scope or retainer |
| You get | Website + back office, hosted and supported | The solution, integrated with what you use | Team, process, governance |
| CTA | See platforms | Explore solutions | Talk to us about a project |

Footnote line: "Not sure which? Tell us the problem — we will say honestly which fits, including when it's the cheapest one."
**Acceptance:** build time and prices come from config and the DB.

---

### Section 4 — Featured technology services (dark band)

**Purpose:** signal enterprise capability without overclaiming.

**Layout:** `tabs` with up to 7 tabs (only **published** C items appear). Each panel: left, the outcome headline, three capability bullets and "Typical engagement" (e.g. "Discovery 1–2 weeks → build in 2-week sprints"); right, a `mock-browser` showing either a **real** screenshot (if one with provenance exists) or an HTML-drawn UI labelled *Illustrative*. Mobile: tabs become a horizontal scroll-snap chip row above the panel.

**Draft headline:** "When the off-the-shelf answer doesn't fit."
**Rules:**
- No Microsoft, Sage or other vendor logos unless D10 approves; product names in text are fine.
- No "certified" or "partner" wording unless held.
- `capability_unconfirmed` items never render.
**Acceptance:** keyboard arrow keys move between tabs; `aria-selected` and focus are correct; with JS off, all panels render stacked (progressive enhancement).

---

### Section 5 — Industry-specific solutions

**Purpose:** let SMEs find "people like me" fast and reuse the five platforms and demos.

**Layout:** six `industry-card`s in a 3×2 grid (desktop), 2 columns (tablet), and a horizontal scroll-snap row (mobile). Each card: industry name, two pain points, "Platform: **Listings** from R{tier}/mo", and links "See the demo →" and "Industry page →".
Final card: "Your industry isn't here?", which links to `/contact/project` and quotes the existing "five packages are a starting point, not a limit" message (guarded by `test_the_site_says_we_are_not_limited_to_the_five_packages`).
**Data:** `Industry` model joined to `Package` and the demo slug.

---

### Section 6 — Genuine client work and clearly labelled demonstrations

**Purpose:** proof. The honesty rules are kept intact (`LiveWorkTest`).

**Layout:** two rows.
1. **Client work:** `proof-card kind=Client` for each published `CaseStudy`. Today: Excellent Real Estate (public site, with permission) and the Invoicing add-on (attributed: "Invoice Ninja, self-hosted and maintained by 7Hills"). Each shows a real **public** screenshot, delivered capabilities, and "Visit the live site".
2. **Demonstrations:** `proof-card kind=Demo` for the 5 demo tenants, each with the ribbon **"Demo — fictional business"** and a screenshot from the demo itself.

Heading: "Real clients, and demos that say they're demos."
**Rules:** never a back-office screenshot of a client; never invite a walkthrough of a client install. The corrected walkthrough line points to a **staging install with fictional data**, per audit §7.2.

---

### Section 7 — Development and delivery process

**Purpose:** reassure enterprise buyers that delivery is governed, and SMEs that it's simple.

**Layout:** `process-steps`, 4 steps. A toggle (Alpine, two buttons) switches between **"Platform subscription"** (Choose → Send content → We build (2–3 weeks) → Live and supported) and **"Custom project"** (Discover → Design → Build in sprints → Run and support).

Under the project track, a strip of 4 governance facts:
- "Fixed-scope phases, priced before they start"
- "A demo at the end of every sprint"
- "Your data and IP terms in writing"
- "Hosted in South Africa"

**Each must be true before launch; owner to confirm.** "Hosted in South Africa" depends on the hosting location.

---

### Section 8 — Existing subscription packages

**Purpose:** keep the current offer exactly as it is, presented better.

**Layout:** the existing `package-card` restyled (same data, same links), 5 cards; the add-on strip below (existing logic: flat vs metered, "charged separately").
**Must keep, because tests guard it:**
- Prices from `PackageTier`.
- "excl. VAT".
- Link to `/packages`.
- The add-ons "charged separately" wording.
- Email "not included" wording (`test_packages_do_not_include_email`).
- Flat pricing across lines.

Heading: "Ready-to-use platforms. One monthly fee, nothing to build."

---

### Section 9 — Trust, security and support (dark band)

**Purpose:** answer the risk questions an enterprise buyer and a careful SME both ask.

**Layout:** 2×3 `trust-item` grid + a link "Read how we handle security →" `/security`.

| Item | Claim (only if true at launch) | Depends on |
|---|---|---|
| POPIA | "We act as your operator under POPIA s20 — your customers' data stays yours." | Existing privacy clause 2 |
| Backups | "Nightly backups, copied off-site, restore-tested." | **R1 fixed**, plus a restore drill |
| Access | "Staff sign-in protected by multi-factor authentication." | **R6** |
| Support | "A person who built it answers the phone." | Existing |
| Ownership | "Your data, email and domain are yours. Leave with an export." | `brand.ownership` |
| Terms | "No minimum term. One month's notice." | `brand.terms` |

**Rule:** a trust item whose dependency is unmet is **hidden** (config flag), not softened.

---

### Section 10 — Consultation and project enquiry calls to action

**Layout:** `cta-band` split in two halves on desktop, stacked on mobile.
- Left (S): "Start with a platform" · "From R{tier}/month, R0 to build." · `btn-accent` **See platforms** · secondary text link "Ask about a package" → `/contact`
- Right (P): "Talk to us about a project" · "A 30-minute consultation, no charge, no obligation." *(owner to confirm "no charge")* · `btn-brand` **Book a consultation** → `/contact/project`
- Below: WhatsApp link with the pre-typed message "Hi 7Hills, I'm on your home page and…" (reuses `x-whatsapp-link`).

---

## 4. Global page elements

- **Header:** per `WEBSITE_SITEMAP.md` §2, sticky, `bg-white/90 backdrop-blur`, hairline border appearing on scroll.
- **WhatsApp float:** unchanged (mint with a brand-dark glyph).
- **Cookie notice:** unchanged.
- **Meta:** title "7Hills Digital — Business software, platforms and enterprise development in South Africa"; description ≤ 155 characters; `Organization` + `WebSite` JSON-LD; OG image generated by `brand:banner`.

## 5. Performance and accessibility budget

| Metric | Budget |
|---|---|
| Lighthouse Performance (mobile) | ≥ 90 |
| LCP / CLS / INP | < 2.5 s / < 0.1 / < 200 ms |
| CSS (gz) / JS (gz) | ≤ 20 KB / ≤ 25 KB (today 9.9 / 19.5) |
| Images | AVIF/WebP with JPEG fallback, `width`/`height` set, `loading="lazy"` below the fold |
| Lighthouse Accessibility | ≥ 95; full keyboard path; visible focus; one H1; landmarks |

## 6. Analytics hooks (no third-party analytics)

Every CTA carries `data-cta="{section}-{journey}"` and appends `?from={section}` to
its link. Conversion is measured **server-side** when the enquiry is stored
(`LEAD_GENERATION_STRATEGY.md` §5), so no tracking script or cookie is needed.

## 7. Homepage acceptance checklist

- [ ] No hard-coded price; every R-figure traces to the DB
- [ ] Every offering shown has the correct availability badge; no `capability_unconfirmed` item renders
- [ ] Every image has a provenance manifest entry; demos are labelled Demo; illustrations are labelled Illustrative
- [ ] No client back office is shown anywhere
- [ ] The group name does not appear; "7Hills" spelling holds
- [ ] All existing PublicSiteTest / AddOnTest / LiveWorkTest assertions pass (copy-pinned ones updated knowingly)
- [ ] Reduced motion: no animation; JS off: all content visible
- [ ] Budgets in §5 met on staging
