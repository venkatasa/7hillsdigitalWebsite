# Homepage Prototype — How to Preview

**Prepared:** 30/10/2026 · **Branch:** `revamp` · **Status:** awaiting visual approval.
Nothing here touches production: the live site, its database, its Docker
configuration and `/opt/docker/7hillsdigital` are unchanged.

---

## 1. Open it

**Since 31/10/2026 staging is on the LAN, beside production:**

| | URL |
|---|---|
| Live site (unchanged) | http://192.168.90.226:8091 |
| Revamp (staging) | **http://192.168.90.226:8096** |

Do **not** point the public Cloudflare hostname at :8096 as it stands. Staging shows
unconfirmed services, sends mail to a log and saves enquiries to its own database copy,
so leads would be lost. A production-mode release candidate is a separate step.

From outside the LAN, an SSH tunnel still works:

```bash
ssh -N -L 8096:127.0.0.1:8096 buntu@192.168.90.226
```

Leave that running, then browse to **http://localhost:8096/**.

- **Homepage prototype:** `http://localhost:8096/`
- **Services** (phase 2): `/services`, the seven line pages and ten service pages
- **Solutions, industries and work** (phase 3): `/solutions`, `/industries`, `/work`
- **Platforms** (phase 3, restyled; same prices and terms): `/packages`, `/packages/{slug}`
- **Get a quote** (phase 4): `/quote`, `/quote?type=custom-development`; submissions land in
  `/admin/enquiries` with their answers, priority, how they arrived and a history
- **Existing pages** (unchanged, for comparison): `/demos`, `/about`, `/how-it-works`, …
- **Back office on staging:** `http://localhost:8096/admin` (staging data only:
  the real catalogue, plus *fictional* enquiries, including the test submissions
  marked "(staging)"; no real prospects)

No SSH? The **static captures** in `docs/revamp/design/preview/` show desktop,
tablet and mobile, including full-page views.

## 2. What to look at

| Try | Expect |
|---|---|
| Resize from 1440px down to 390px | The hero sets in 3 lines (desktop), 2 (tablet), 4 (mobile); no horizontal scroll |
| Move the mouse over the hero screens | The screens drift a few pixels (desktop only) |
| Hover the problem tiles | A soft mint spotlight follows the pointer |
| Tab through the page with the keyboard | A visible focus ring everywhere; arrow keys move between service and industry tabs; `Esc` closes menus |
| Open *Enterprise ▾* or *Industries ▾* and pick an item | The page scrolls to that tab and opens it |
| Turn on "Reduce motion" in your OS | No animation at all; everything visible |
| Phone: open the menu | A full-height sheet with accordions; the page behind doesn't scroll |

## 3. What is real, and what is labelled

- **Screenshots** are genuine: the five demo tenants and our own back office on
  staging. Each carries its label on the page ("Demo tenant · fictional business",
  "Back office · staging install, fictional data"). Provenance is in
  `public/images/product/manifest.json`, and a test fails if an image isn't declared.
- **Diagrams** (flows, hubs, charts) are labelled *Illustration* and show no figures.
- **Prices** come from the database. Change one in `/admin` and the homepage follows.
- **The 62% statistic** is verified in Xero's primary release, and its source is printed.
- **Excellent Real Estate** is text only. Its public homepage shows an identifiable
  agent with her phone number and email; reusing that image needs the client's
  written permission (decision D6).

## 4. Preview-only switches (staging `.env`)

| Switch | Staging | Production |
|---|---|---|
| `REVAMP_HOME_V2` | `true`: serves the prototype at `/` | unset → **current homepage** |
| `REVAMP_PREVIEW_UNCONFIRMED` | `true`: shows AI, Microsoft 365, Mobile and BI with a dashed **Preview only** marker | **ignored**; those four are never rendered until you confirm capability (decision D2) |
| `REVAMP_LEADS` | `true`: `/quote`, attribution and enquiry history (needs the D7 migrations, run on staging only) | unset → `/quote` is a 404, "Get a quote" links go to `/contact` as today, nothing new is written |
| `revamp.trust.*` (config) | `false` | off-site backups and MFA claims stay hidden until R1 and R6 are done |

## 5. Verified

| Check | Result |
|---|---|
| Test suite (current homepage) | **87 passed** (75 baseline + 12 new) |
| Test suite with the prototype as the homepage | **87 passed**, so the prototype keeps every promise the current homepage's tests guard |
| LCP | 1.1 s desktop; 1.7–2.0 s mobile on slow 4G with 4× CPU slowdown (LCP element: the headline text) |
| Layout shift (CLS) | 0–0.008 |
| Third-party requests | **none** |
| Page weight | HTML 29 KB gzipped; CSS 15.5 KB / JS 19.8 KB gzipped |
| Accessibility (axe-core, WCAG 2.1 AA) | **0 violations** on the new code. One best-practice note on the existing WhatsApp float (outside a landmark) |
| Keyboard, menus, mobile sheet, reduced motion, no-JS | 9/9 scripted checks pass (`tools/interact.mjs`) |

## 6. Found while building (not fixed on the current site)

**`data-reveal` hides content when JavaScript is off.** The existing CSS sets
`opacity: 0` on every `[data-reveal]` element unconditionally, so without JS the
**current live site** shows 36 blank blocks. CLAUDE.md says it must never hide
content. The prototype layout fixes it with a `<noscript>` rule; the same one-line
fix belongs in `layouts/site` and `layouts/demo`. **Awaiting your approval.**

## 7. Decisions this prototype needs from you

1. **Visual direction:** the seven-hills contour motif, the annotation style, the colour balance.
2. **D2:** can we deliver AI, Microsoft 365/SharePoint, mobile apps and BI? Until
   confirmed, those panels stay out of production.
3. **Process and governance claims** in section 7 (durations; "fixed-scope phases";
   "IP terms in writing"): confirm they reflect how you will actually work.
4. **Response promise** for the custom-solution journey (currently no SLA stated).
5. **D6:** permission to show Excellent Real Estate's public site (without the agent's photo).
6. **D9:** the unofficial `Complete Technology solutions.jpg` lockup is **not** used anywhere.

## 8. Staging housekeeping

- The staging DB holds a `preview@staging.test` user (password in the staging
  `.env`, `PREVIEW_PASSWORD`) and five fictional enquiries used for screenshots.
- Re-capture screenshots after a UI change with `tools/shoot.mjs` (see the header
  comment). It runs in a throwaway container on the staging network only.
- Tear staging down: `docker compose -p 7hills-staging -f docker/staging/docker-compose.yml down -v`.
