# Phase 3: Solutions, Industries and Platforms (report)

**Prepared:** 31/10/2026 · **Branch:** `revamp` · **Environment:** staging only.
**Status:** approved by the owner on 31/10/2026. Phase 4 has not started.

Production was not touched: no source, database, Docker, environment, Gitea or
deployment change. The live site on :8091 is unchanged, and its working tree is exactly
as it was before this phase.

View it at **http://192.168.90.226:8096**, beside the live site on :8091.

---

## 1. What was built

**15 new URLs, plus 6 restyled ones**

| URL | What | Production |
|---|---|---|
| `/solutions` | Six business problems, each with the services that fix it | ✅ (five problems) |
| `/solutions/replace-spreadsheets` | Spreadsheets to one system. Proof: our own price panel, invented data | ✅ |
| `/solutions/never-lose-a-lead` | Enquiry pipeline. Proof: our own enquiry pipeline, invented data | ✅ |
| `/solutions/get-paid-on-time` | Invoicing. Proof: the live Invoicing add-on, named as InvoiceShelf | ✅ |
| `/solutions/automate-approvals` | Approval workflows. No proof, so no proof section | ✅ |
| `/solutions/connect-your-systems` | Integration. No proof, so no proof section | ✅ |
| `/solutions/it-that-just-works` | Managed IT | **Preview only.** 404 in production until managed IT is confirmed |
| `/industries` | Six industries: platform, price from the database, demo | ✅ |
| `/industries/{automotive, property, recruitment, hospitality, professional-services, corporate}` | One page per industry | ✅ |
| `/work` | Live clients, our own software with invented data, the five demos | ✅ |
| `/packages`, `/packages/{5 slugs}` | **Restyled** as "Platforms". Same URLs, prices, tiers, terms, modules and contact links | ✅ |

All new pages return 404 unless `REVAMP_HOME_V2` is on. With it off, `/packages`
serves the current view, so merging this branch early changes nothing live.

**Solution pages** start from the symptom, not the service. Their layout is different
from a service page so the site does not repeat itself:
1. A "Today / With it fixed" ledger in the hero.
2. "Sound familiar?": five signs the buyer will recognise.
3. Why it matters: the cost, with a source where one is quoted.
4. How we fix it: the two or three services, each linked to its own page, plus a flow
   of the finished work.
5. What we need from you.
6. Proof, only if real.
7. FAQ.
8. Close, with the industries where the problem is common.

**Industry pages:**
1. The demo in the hero, or a flow where there is none.
2. The situation in that trade.
3. **The same day, run two ways**: a four-moment timeline, today against with the
   system.
4. The platform, with its features and "from" price read from the database, and the
   terms quoted from `config/brand.php`.
5. A live client where `config/clients.php` names one (property: Excellent Real Estate,
   public site only).
6. Beyond the platform: three services.
7. FAQ, then a close with the other industries.

Corporate has no platform, so it says plainly that the work is custom, scoped and
priced in writing.

**The Work page** sorts what we show by how real it is:
1. Live clients.
2. Our own back office, from staging with invented data.
3. Demos, each labelled "Demo · invented".

It also says plainly what we will not show: a client's back office.

**The platform pages** were restyled in place. The cards became specification rows, and
the terms moved up to sit under the hero. Every commercial element survives, and the
existing pricing and add-on tests now run against the new views. Each platform page
links to its industry page.

**Navigation:**
- The Solutions and Industries menus open the new pages, each with an "All …" link.
- Our Work goes to `/work`.
- On the homepage, the solutions ledger, the industry tabs ("… in detail →") and the
  work section all link through to the new pages.
- The footer lists Our work, Solutions and Industries.
- Service pages now link to the solutions they solve and the industries that use them.

## 2. Honesty rules (each guarded by a test)

- **Published pages contain none of these:** Azure, AWS, SharePoint, Power Automate,
  "Microsoft partner", "certified", "24/7", "SLA", "uptime", "99.", "backed up",
  "backups", "guarantee", "award", "leading", "Invoice Ninja" or "80%".
  - The check now matches whole words, so "laws" no longer trips "AWS". I applied the
    same fix to the Phase 2 test.
- **Every price on an industry or platform page comes from the database.** A test
  changes a price and checks the page follows.
- **Every demo is labelled as invented. Live clients are linked by public site only.**
- **Stay is described as taking direct booking enquiries.** It does not offer live
  availability or payments, and its FAQ says so.
- **Professional services has no demo of its own.** It points to the Plant Hire demo as
  "the engine at work", with a note saying so.
- **Anti-thin check:** each solution and industry page has more than 400 words of its
  own, and no two of the 12 pages share more than 15% of their wording.
- **Headlines stay at 60 characters or fewer.** Longer ones wrapped to five lines at
  1440 and pushed the call to action below the fold.

### Two claims I removed on the branch (your call before merge)

1. **"Daily backups" is gone from the restyled platform pages**, and so is "We tell you
   when a mailbox passes 80%". Off-site backups have failed since 16/10 (R1), and no
   storage meter exists (open decision 5). **The live :8091 pages still say both.**
2. **"Hosted, backed up and updated by us"** in `config/clients.php` now reads "Hosted
   and updated by us" **on the revamp branch only**, with a comment to restore it once
   backups run. Production's copy is unchanged. It is a one-line revert if you would
   rather keep it.

"Sends from the client's own domain" is still there. I could not verify it (§6).

## 3. Results

### Laravel tests
| Run | Result |
|---|---|
| Revamp on (staging `.env`) | **125 passed, 1 877 assertions** |
| Revamp off (`REVAMP_HOME_V2=false`) | **125 passed, 1 877 assertions** |

There are 17 new tests in two files.

`SolutionAndIndustryPagesTest` (13):
- off-switch 404s
- every page renders
- the gated solution is a 404 in production even with preview on
- previews are marked and kept out of search
- solution and industry content is complete
- each industry links its platform, demo and services
- prices come from the database
- forbidden claims
- the anti-thin check
- headline length
- structured data
- the Work page labels
- navigation reaches every new page

`PlatformPagesTest` (4):
- the switch picks the view and the URL never changes
- every commercial element survives
- each platform links its industry
- no unkept promises

### Browser checks (`tools/p3.mjs`): 15 / 15 pass
- No horizontal overflow on all 21 pages at 1440, 768 and 390.
- No JavaScript errors.
- axe (WCAG 2.0–2.2 AA): no violations, desktop and mobile.
- All 82 same-site links answer, and no in-page anchor lacks a target.
- All 34 service links from the solution and industry pages land on a page or an anchor
  that exists.
- FAQ works by keyboard. Nothing is hidden with JavaScript off, and reduced motion
  stops the pulses.
- The menus work:
  - A Solutions link opens its page.
  - Our Work opens `/work`.
  - The mobile sheet's Industries list expands to the six pages.
  - The desktop nav fits on one line at 1024.

**Homepage regression (`tools/interact3.mjs`): 23 / 23 pass.** I updated it because the
industry menu now opens pages. A `#industry-…` link still selects the homepage tab.

**Real Chrome, over the LAN:** the Solutions menu and the platform page render
correctly, with no console errors. Screenshot: `screenshots/real-chrome-solutions-menu.jpg`.

### Lighthouse 12.6.1 (`reports/`, behind a compressing proxy; mobile = slow 4G, 4× CPU)

| Page | Device | Perf | A11y | Best practice | SEO | LCP |
|---|---|---|---|---|---|---|
| Solution (never lose a lead) | Desktop / Mobile | 100 / **91** | 100 | 79 | 100 | 0.7 s / 3.2 s |
| Industry (automotive) | Desktop / Mobile | 100 / **87** | 100 | 79 | 100 | 0.8 s / 3.6 s |
| Platforms (`/packages`) | Desktop / Mobile | 100 / **91** | 100 | 79 | 100 | 0.7 s / 3.2 s |
| Platform (showroom) | Mobile | **87** | 100 | 79 | 100 | 3.6 s |
| Work, solutions hub, industries hub | Mobile | **90–93** | 100 | 79 | 100 | 3.0 s |

CLS is 0.044 or lower everywhere. Best practice loses points only for plain HTTP on
staging.

## 4. Design critique: found and fixed

| Problem | Fix |
|---|---|
| Industry and solution H1s ran to five lines at 1440, pushing the CTA to the fold edge | Every H1 cut to 60 characters or fewer, with the detail kept in the lede, and a test added |
| The tier table on a phone hid the price behind a sideways scroll | Below tablet width the "For" column is hidden and the table fits without scrolling |
| The preview-only solution looked like a normal item in the Solutions menu | It now carries a dashed "preview" tag, like preview services do |
| Two industry pages were under 400 words once the H1s were shortened | One more paragraph of real context each, with no padding |
| Five meta descriptions were over 155 characters | Trimmed |
| A word-match bug: "laws" counted as "AWS" | Whole-word matching in both claim tests |
| The work page showed three blank image frames | A capture artifact from lazy loading: every image returns 200. The page was retaken with images forced to load |

## 5. Files changed

**New**
- `app/Http/Controllers/SolutionController.php`
- `app/Http/Controllers/IndustryController.php`
- `resources/content/solutions/*.php` (6)
- `resources/content/industries/*.php` (6)
- `resources/views/pages/solutions/{index,show}.blade.php`
- `resources/views/pages/industries/{index,show}.blade.php`
- `resources/views/pages/work.blade.php`
- `resources/views/pages/packages-v2/{index,show}.blade.php`
- `resources/views/components/page/{proof,close}.blade.php`
- `resources/views/components/platform/add-ons.blade.php`
- `tests/Feature/SolutionAndIndustryPagesTest.php`
- `tests/Feature/PlatformPagesTest.php`
- `docs/revamp/design/phase3/`

**Changed**
- `routes/web.php`: `/solutions`, `/industries` and `/work`.
- `app/Support/Revamp.php`: `solutions()`, `solution()`, `industry()`, `services()`,
  `linksTo()`.
- `app/Http/Controllers/PackageController.php`: picks the restyled views when the
  revamp is on.
- `app/Http/Controllers/PageController.php`: `work()`.
- `config/revamp.php`:
  - Solutions are keyed by page slug.
  - The corporate industry is renamed `corporate`, matching the planned URL.
  - The invoicing outcome no longer claims "from your own address".
- `config/clients.php`: the "backed up" line (see §2).
- `resources/views/components/ui/{header,footer}.blade.php` and
  `pages/home-v2.blade.php`: links to the new pages.
- `resources/views/pages/services/show.blade.php`: the proof section moved into a shared
  component, plus "Solves" and "Used in" links.
- `tests/Feature/ServicePagesTest.php`: whole-word claim matching.
- `docs/revamp/design/PREVIEW.md`: the new URLs.

## 6. Not resolved, or needs your decision

1. **The live site still says "daily backups"** (on `/packages` and every platform page)
   **and promises an 80% mailbox warning.** Neither is true today. The restyle drops
   both. Production needs your word, and fixing R1 is the better answer for backups.
2. **"Sends from the client's own domain"** (Invoicing, `config/clients.php`) is still
   unverified. Reading the client's install was blocked, and I did not work around it.
3. **Mobile LCP is 3.0–3.6 s** against a 2.5 s target. The cause is the same as Phase
   2: eight to nine preloaded font files on every page (`vite.config.js`). Changing it
   needs your approval because it affects the whole site.
4. **The capability matrix is still empty.** Until it is filled, production shows five
   solutions, not six.
5. **`/demos`, `/about`, `/how-it-works`, `/custom-and-it` and `/contact`** still use the
   old layout under the new header. They were outside this phase. `/contact` becomes
   `/quote` in Phase 4.
6. **The Cloudflare switch** still needs a production-mode copy first (see `PREVIEW.md`).

## 7. Screenshots (`screenshots/`)

- `*-1440-fold.webp`, `*-768-fold.webp` and `*-390-fold.webp` for:
  - the solutions hub and the "never lose a lead" page
  - the industries hub, automotive and corporate
  - `/work`, `/packages` and showroom
- Full pages at 1440 for each of those.
- Full pages at 390 for the solution page, automotive, `/packages` and `/work`.
- `real-chrome-solutions-menu.jpg`: the Solutions menu in your own Chrome.
