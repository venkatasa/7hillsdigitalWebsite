# Phase 1: Homepage and Navigation (report)

**Prepared:** 31/10/2026 · **Branch:** `revamp` (uncommitted) · **Environment:** staging only
(`127.0.0.1:8096`) · **Status:** awaiting visual approval. Phase 2 has not started.

Production was not touched: no source, database, Docker, environment, Gitea or
deployment change. Every screenshot shows staging with fictional data.

---

## 1. Design skills

| Asked for | Installed? | Where it exists | What it would take |
|---|---|---|---|
| "Impeccable" frontend design | **No** | Not found in the plugin directory under that name | Name or link needed. Not installed |
| Emil Kowalski's animation / design-engineering skills | **No** | Bundled as `emil-design-engineering` and `web-animation-design` inside **audit-suite** (community publisher "Orbit", Anthropic plugin directory) | Install from the directory card. **Community, not Anthropic-verified**, so I did not install it |
| Frontend design | **No** | **frontend-design**, Anthropic (`anthropics/claude-plugins-official`, `plugins/frontend-design`) | One click in the plugin card shown in chat |
| Design critique and accessibility review | **No** | **Design**, Anthropic (`anthropics/knowledge-work-plugins`, `design`) | One click in the plugin card |
| Accessibility | **No** | **Axe Accessibility** (Deque, partner tier); A11y Enforcer (community) | Plugin install |
| Browser testing | Partly | Claude in Chrome and computer use are available. Neither can reach staging, which listens on the server's loopback only | Used a throwaway headless Chromium container instead (below) |

Nothing was installed and no remote script was run. In place of the missing skills I
applied the project's own design system (`docs/revamp/design/DESIGN_SYSTEM.md`) and
checked it with the same tooling as v2: axe-core 4.10.3, Lighthouse 12.6.1, and
Puppeteer driving Alpine Chromium in a container on the staging network. No skill
instruction overrode a safety rule.

## 2. What was built

| # | Deliverable | Where |
|---|---|---|
| 1 | **Homepage v3**: 10 sections per `04_HOMEPAGE_COMPOSITION.md` | `resources/views/pages/home-v2.blade.php` (same switch, `REVAMP_HOME_V2`) |
| 2 | **Services mega-menu**: full-width, two panes. Hover or focus a line to see its services | `components/ui/header.blade.php` |
| 3 | Navigation: Services▾ Solutions▾ Industries▾ Platforms · Our Work · About · **Get a Quote** | same |
| 4 | **52-service catalogue** with the publication rule (available / built to order / unconfirmed / blocked) | `config/revamp.php`, `app/Support/Revamp.php` |
| 5 | Conditional headline: "AI" and "Cloud" appear only when their services may be published | `Revamp::headline()` |
| 6 | 8-card service grid that always fills: an unpublishable service hands its slot to a fallback | `Revamp::featured()` |
| 7 | Quote chooser: 6 project types, plus website platform and "something else" | homepage §9 |
| 8 | New components: `<x-ui.motif>` (13 line drawings), `<x-ui.flow>` (process diagram) | `components/ui/` |

**Same page, two outputs:**

| | Staging (preview on) | Production (as rendered with previews off) |
|---|---|---|
| Headline | Software Development, **AI, Cloud** & IT Solutions… | Software Development, **Automation** & IT Solutions… |
| Service lines | 7 | 6 (Microsoft has nothing publishable yet) |
| Services in catalogue | 52, unconfirmed ones dashed | 23 |
| Featured grid | 8, four marked "Preview only" | 8, all deliverable |
| Spotlight | Microsoft 365 (dashed) | Automation & integration |

See `screenshots/prodview-*` for the production rendering.

**Links today:** service, solution and industry pages are Phases 2–3, so every menu link
opens the matching place on the homepage: the catalogue line, the industry tab or the
section. "Get a Quote" goes to the existing `/contact` until `/quote` is built in
Phase 4. No link 404s; a test and a crawl both check this.

## 3. Results

### Automated tests (Laravel, staging container, SQLite in memory)

| Run | Result |
|---|---|
| New homepage on (staging `.env`) | **96 passed, 687 assertions** |
| New homepage off (`REVAMP_HOME_V2=false`) | **96 passed, 687 assertions** |

New and rewritten tests: `HomeV2Test` (16) and `RevampCatalogueTest` (6). They cover:
- production never renders an unconfirmed or blocked service (headline, menu, grid,
  catalogue or quote chooser), even with the preview switch on
- the grid is always 8 cards
- every in-page link has a target
- custom menu events survive Blade (see §4)
- every existing homepage promise, database prices, and image provenance

### Interaction checks (`tools/interact3.mjs`): **22 / 22 pass**

- Services menu opens on hover and on Enter, sets `aria-expanded`, and highlights only
  the hovered line.
- Tab moves through lines; Esc closes the menu and returns focus.
- A menu link scrolls to and outlines its catalogue line.
- An industry link selects its tab.
- Proof tabs follow the arrow keys.
- The header turns paper after the hero.
- No in-page link points at a missing id, and all 41 same-site links answer below 400.
- 768px uses the mobile menu.
- The mobile sheet locks scroll, takes focus and covers the screen.
- A link in the sheet closes it and opens the right catalogue line.
- Reduced motion: every block is visible and the flow pulses are off.
- No JavaScript: nothing hidden and no dead tab buttons.
- Zero JavaScript errors across all runs.

### Layout (`tools/v3.mjs`)

| Width | Horizontal overflow | Page length | v2 for comparison |
|---|---|---|---|
| 1440 | **0px** | 12.8 screens | 13.1 |
| 768 | **0px** | 15.0 screens | — |
| 390 | **0px** | **16.4 screens** | 20.7 |

(The hero's browser frame deliberately bleeds off the right edge and is clipped by the
section, so nothing scrolls sideways.)

### Accessibility: axe-core 4.10.3, WCAG 2.0/2.1/2.2 AA plus best practice

| State | Violations |
|---|---|
| 1440, 1440 with Services menu open, 768, 390, 390 with sheet open | **0 WCAG violations.** One best-practice note: the existing WhatsApp float sits outside a landmark |

Three contrast failures found during the review were fixed: the URL bar in dark frames,
the "What we build with" label, and the diagram caption.

### Lighthouse 12.6.1 (`reports/`)

| Run | Perf | A11y | Best practice | SEO | FCP | LCP | TBT | CLS |
|---|---|---|---|---|---|---|---|---|
| Desktop | **97** | **100** | 79 | **100** | 0.8 s | 1.1 s | 0 ms | 0.009 |
| Mobile, staging as is | 67 | **100** | 79 | **100** | 4.2 s | 6.1 s | 50 ms | 0.044 |
| Mobile, behind a gzip proxy | **85** | **100** | 76 | **100** | 2.6 s | 3.8 s | 0 ms | 0.002 |

Why mobile differs between the two runs:
- **Staging nginx compresses nothing.** Lighthouse estimates 410 KiB of savings.
  Production sits behind Cloudflare, which serves Brotli over HTTP/3 (checked live:
  `content-encoding: br`). The third row puts a temporary gzip proxy in front of staging
  to approximate that. Even there the CSS file bypassed the proxy uncompressed, so
  production should do somewhat better.
- **Best practice 76–79** is staging served over plain HTTP. It is not a page defect.

## 4. Found and fixed during the critique pass

| Problem | Fix |
|---|---|
| Desktop H1 wrapped to six lines | Wider text column (7/12) and a calmer type scale. Now three lines at 1440 |
| Mobile hero was a full screen of text with no product | Shorter mobile lede; screen composition and its label shown on phones |
| The staging notice covered the primary CTA | Moved to a slim bar above the header |
| Mega-menu let the hero show through | Solid panel |
| The first menu line and tab stayed highlighted beside the active one (also in v2) | Alpine class bindings switched to object syntax, which removes the initial classes |
| **Menu links to sections did nothing (also in v2).** Blade treats `@show` as its own directive, so `@show-industry.window` reached the browser as `-industry.window` | `x-on:show-…`. A test now guards it |
| **The mobile menu opened with no visible content (also in v2).** The header's `backdrop-filter` traps fixed-position children inside the 76px bar | Blur is off while the sheet is open, and the sheet is positioned under the header. A check now measures it |
| Hero headline (the LCP element) faded in only after JavaScript ran | Hero copy is no longer scroll-revealed |
| Logo images had no intrinsic size | `width` and `height` declared on `<x-brand-logo>` |
| Gap in the bento grid; "7 service lines" read oddly | A catalogue tile fills the gap; the number is spelled out |
| Repeated arrow icons on the quote cards | Each type has its own line drawing |
| Mobile length 20.7 screens | Service rail, compact quote grid, tighter section rhythm: **16.4** |

## 5. Not resolved, or needs your decision

1. **Mobile length is 16.4 screens against a target of 12** (`04` §4.4). The remaining
   bulk is Our Work (2.1 screens), platforms (1.7), solutions (1.7) and the existing
   footer (1.6). Shortening further means cutting content you may want. Your call on
   the visual review.
2. **Mobile LCP is 3.8 s with compression** (target 2.5 s). The largest remaining cost
   is nine self-hosted font files, every weight preloaded on every page (`vite.config.js`).
   Preloading only the two weights the hero uses would help. It changes how **every**
   page loads fonts, so I have not done it without your approval.
3. **The WhatsApp float** sits outside a landmark (axe best practice) and overlaps the
   last row of the open mobile menu. It is an existing shared component, left as is.
4. **Capability matrix still empty.** Production would show the "Automation" headline,
   six lines and 23 services until you confirm Microsoft, managed IT, AI and cloud.
5. **Three v2 bugs fixed here also exist in the approved v2 commit (`72714f4`):** menu
   events, the trapped mobile sheet and the stuck highlights. They never reached
   production, because v2 was never deployed.
6. **The live site** uses the old layouts, so none of this affects it.

## 6. Files changed

**Application (staging branch only)**
- `config/revamp.php`: rewritten as the 52-service catalogue, featured and fallback
  cards, headline rule, solutions, quote types.
- `app/Support/Revamp.php`: `catalogue()`, `visible()`, `lines()`, `featured()`,
  `headline()`, `quoteTypes()`, `previewing()`, and a `blocked` state.
- `resources/views/pages/home-v2.blade.php`: homepage v3.
- `resources/views/components/ui/header.blade.php`: new navigation, Services mega-menu,
  mobile sheet.
- `resources/views/components/ui/footer.blade.php`: Services column by line.
- `resources/views/components/ui/layout.blade.php`: staging notice and no-JS rules.
- `resources/views/components/ui/motif.blade.php` (new) and `flow.blade.php` (new).
- `resources/views/components/ui/frame-browser.blade.php`: URL bar contrast.
- `resources/views/components/brand-logo.blade.php`: intrinsic width and height (shared
  with the live layouts once merged; display unchanged).
- `resources/css/app.css`: v3 section (type scale, menu and sheet motion, cards,
  catalogue, flow pulse, reduced-motion overrides). Mobile section padding `py-16`.
- `tests/Feature/HomeV2Test.php` (rewritten) and `tests/Feature/RevampCatalogueTest.php` (new).

**Documents**
- `docs/revamp/full-service/03_SERVICE_CATALOGUE.md` and `README.md`: the brief lists
  **52** services, not 54 as I wrote earlier; counts corrected.
- `docs/revamp/design/phase1/`: this report, `screenshots/`, `reports/` (Lighthouse
  HTML), `tools/`.

**Also in the working tree, from the earlier Invoice Ninja fix (not yet committed, at
your instruction):** `config/clients.php`, `tests/Feature/LiveWorkTest.php`,
`resources/views/pages/home.blade.php`, `CLAUDE.md`, and the matching notes in
`docs/revamp/full-service/04`, `06`, `08`.

## 7. Screenshots (`screenshots/`)

| File | What |
|---|---|
| `v3-1440-fold.webp`, `v3-1440-full.webp` | Desktop, staging preview |
| `v3-1440-menu-services.webp` | Services mega-menu, AI & Automation line hovered |
| `v3-768-fold.webp`, `v3-768-full.webp`, `v3-768-menu-sheet.webp` | Tablet |
| `v3-390-fold.webp`, `v3-390-full.jpg`, `v3-390-menu-sheet.webp` | Mobile (2×) |
| `prodview-1440-fold.webp`, `prodview-1440-full.webp`, `prodview-390-fold.webp` | **What production would render**, with previews off |

## 8. Preview it yourself

```bash
ssh -N -L 8096:127.0.0.1:8096 buntu@192.168.90.226
```

Then open http://localhost:8096/. Things to try:
- Hover **Services** and move down the lines.
- Tab through the menu with the keyboard.
- Narrow the window to phone width and open the menu.
- Turn on "reduce motion" in your OS.
