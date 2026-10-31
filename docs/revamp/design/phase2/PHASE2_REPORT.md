# Phase 2: Core Service Pages (report)

**Prepared:** 31/10/2026 · **Branch:** `revamp` · **Environment:** staging only.
**Status:** approved 31/10/2026. Phase 3 has not started.

Production was not touched: no source, database, Docker, environment, Gitea or
deployment change. The live site on :8091 still serves the old homepage.

---

## 1. Side-by-side hosting (your request during this phase)

| | URL |
|---|---|
| Live site, unchanged | http://192.168.90.226:8091 |
| Revamp, staging | **http://192.168.90.226:8096** |

Staging was bound from loopback to the LAN address only (never `0.0.0.0`), in
`docker/staging/docker-compose.yml`. **Do not point Cloudflare at :8096 yet.** Staging
shows unconfirmed services, writes mail to a log and saves enquiries to its own copy of
the database, so a lead sent through it would never reach you. Making a production-mode
copy you can switch Cloudflare to, and back, is a separate step needing your approval.

## 2. Skills used

| Skill or tool | Used for |
|---|---|
| **Claude in Chrome** (installed) | Opened the pages in your own Chrome over the LAN: the CRM page, the Services mega-menu, console errors (none). Screenshot: `screenshots/real-chrome-services-menu.jpg` |
| Built-in browser, computer use (installed) | Not needed; Chrome covered the real-browser check |
| frontend-design, Design (Anthropic) | **Still not installed.** The card is in chat; `ListPlugins` shows nothing enabled |
| audit-suite, with Emil Kowalski's design-engineering skills (community) | Not installed; unverified publisher |
| axe-core 4.10.3, Lighthouse 12.6.1, Puppeteer | Quality gates, in a throwaway container on the staging network (removed afterwards) |

## 3. What was built

**Pages: 18 new URLs, all under `/services`**

| URL | What | Production |
|---|---|---|
| `/services` | Hub: every line, every service, each linked to its page or its place on its line | ✅ |
| `/services/{line}` × 7 | One page per service line. **Every service has an anchor**, for example `/services/software-development#database-development` | 6 ✅, Microsoft line 404 until confirmed |
| `/services/custom-software-development` | Dedicated page | ✅ |
| `/services/web-application-development` | Dedicated page | ✅ |
| `/services/api-development-and-integration` | Dedicated page | ✅ |
| `/services/business-process-automation` | Dedicated page (also covers approvals and procurement) | ✅ |
| `/services/crm-systems` | Dedicated page (says plainly the CRM add-on is still in development) | ✅ |
| `/services/accounting-and-invoicing-integrations` | Dedicated page (InvoiceShelf named as third-party software) | ✅ |
| `/services/microsoft-365` | Microsoft 365, SharePoint and Power Platform | **Preview only.** 404 in production |
| `/services/ai-solutions` | AI assistants and document processing | **Preview only.** 404 in production |
| `/services/managed-it-support` | Managed IT | **Preview only.** 404 in production |
| `/services/managed-hosting` | Hosting and infrastructure | **Preview only, blocked until R1–R3** |

All of these return 404 unless `REVAMP_HOME_V2` is on, so merging the branch early
cannot publish them.

**Reusable components (`resources/views/components/service/`)**
- `hero`: breadcrumb, searched-noun eyebrow, outcome H1, a dashed preview notice when
  unconfirmed, two CTAs, and a facts strip.
- `visual`: four original compositions.
  - Real **screens** (browser and phone, labelled from the manifest).
  - **Diagrams**.
  - **Flows** with a travelling pulse.
  - **Constellation**: a capability at the centre, the products it joins around it.
- `faq`: native `<details>`, with no JavaScript, keyboard support and FAQPage data.

**The page template** (`pages/services/show.blade.php`) answers the brief's seven
questions in order:
1. The problem, in an editorial two-column layout.
2. What we deliver, as a numbered specification, not cards.
3. Use cases, three scenarios "written as scenarios, not a named client".
4. Technologies.
5. How we deliver, as a four-step timeline.
6. Proof, **omitted entirely when there is none**.
7. FAQ, then a quote close with related services.

**Content** lives in `resources/content/services/*.php`, one file per page. It moves
to the database later with the rest of the catalogue.

**Navigation updated**
- The Services mega-menu, homepage service cards, the capability bar, the catalogue
  and the footer now lead to these pages.
- Solutions lead to their service line until Phase 3 builds solution pages.
- Industries still open their homepage tab until Phase 3.

## 4. Honesty rules applied (each guarded by a test)

- **Published pages contain none of these:** Azure, AWS, SharePoint, Power Automate,
  Power BI, "Microsoft partner", "certified", "24/7", "SLA", "uptime", "99.",
  "backed up", "backups", "guarantee", "award", "leading" or "Invoice Ninja".
- Technologies listed on published pages are only those in our own code and
  infrastructure.
- Use cases are scenarios, labelled as such. No client is named except in proof that
  links to `config/clients.php`.
- No durations, prices or SLAs, except the Invoicing add-on, which points to the
  database price on `/packages`.
- **Backup claims removed.** Off-site backups have been failing since 16/10 (R1), so
  no published page says "backed up". (See §7: the *live* site still does.)
- Preview pages carry `noindex, nofollow` and no structured data.
- **Anti-thin check:** each published page has more than 450 words of its own copy,
  and no two pages share more than 15% of their wording (5-word shingles).

## 5. Results

### Laravel tests
| Run | Result |
|---|---|
| Revamp on (staging `.env`) | **108 passed, 1 182 assertions** |
| Revamp off (`REVAMP_HOME_V2=false`) | **108 passed, 1 182 assertions** |

`ServicePagesTest` is new and has 12 tests. It covers:
- off-switch 404s
- every published page renders
- production 404s preview pages even with the preview switch on
- previews are marked and `noindex`
- every page answers the seven questions
- proof is real or absent
- the forbidden-claims list
- the anti-thin check
- structured data validity (Service, BreadcrumbList, FAQPage; no `offers`)
- link targets per service
- every line anchor exists
- every pre-existing URL still answers 200

### Browser checks (`tools/p2.mjs`): 12 / 12 pass
- No horizontal overflow on all 14 pages at 1440, 768 and 390.
- No JavaScript errors.
- axe (WCAG 2.0–2.2 AA): **no violations**, desktop and mobile. One finding, invalid
  `<dl>` nesting in the hero facts, was fixed.
- All 55 same-site links answer.
- No in-page anchor without a target.
- All 17 hub links to a service on its line land on it.
- FAQ: Enter opens, Space closes, the first answer is open by default.
- With JavaScript off, nothing is hidden.
- With reduced motion, everything is visible and the pulses stop.
- A menu link opens its page.

**Homepage regression (`tools/interact3.mjs`): 22 / 22 pass.** Updated for links that
now go to real pages.

### Lighthouse 12.6.1 (`reports/`)

Measured behind a compressing proxy, approximating Cloudflare. Mobile uses slow 4G
with 4× CPU slowdown.

| Page | Device | Perf | A11y | Best practice | SEO | LCP | CLS |
|---|---|---|---|---|---|---|---|
| Home | Desktop | **100** | 100 | 79 | 100 | 0.8 s | 0.009 |
| Home | Mobile | **87** | 100 | 79 | 100 | 3.7 s | 0.044 |
| CRM service page | Desktop | **100** | 100 | 79 | 100 | 0.7 s | 0.009 |
| CRM service page | Mobile | **80** | 100 | 79 | 100 | 3.3 s | 0 |
| Services hub | Mobile | **94** | 100 | 79 | 100 | 2.9 s | 0.044 |

- Best practice loses points only for plain HTTP on staging.
- **Correction to Phase 1:** my first proxy sent the wrong Host header, so its web
  fonts failed to load. The Phase 1 "mobile 85" was measured without fonts. Re-measured
  correctly, the homepage scores **87**, so the conclusion holds, but that first figure
  should not have been quoted. The proxy config in `tools/` is fixed.

## 6. Design critique pass: found and fixed

| Problem | Fix |
|---|---|
| The same screenshot in the hero and the proof section (custom software, CRM, web apps) | Each hero has its own visual: the dashboard, a pipeline-stage illustration, and a demo tenant on desktop and phone |
| Two near-identical quote-to-invoice flows on the invoicing page | The hero became a constellation of the billing pieces |
| Nine pages too thin (below 450 words of their own copy) | One more buyer question each, and a third problem paragraph where short. No padding text |
| Three meta descriptions over 155 characters | Trimmed |
| Invalid definition-list nesting (axe, serious) | Markup fixed |
| Dynamic Tailwind class names (`sm:grid-cols-{{ n }}`) the build could not see | Literal class maps |

## 7. Not resolved, or needs your decision

1. **The live site still says "Hosted, backed up and updated by us"** on the Excellent
   invoicing card (`config/clients.php`). With off-site backups failing since 16/10
   (R1), that is not currently true. It is a production content change, so it waits
   for your word, and fixing R1 is the better answer.
2. **Mobile LCP is 3.3–3.7 s** against a 2.5 s target. The largest remaining cost is
   still nine preloaded font files (`vite.config.js`, affects every page).
3. **The capability matrix is still empty.** Until it is filled, production shows six
   lines, the "Automation" headline and six service pages.
4. **The Cloudflare switch-over** needs a production-mode copy first (§1).
5. **Phase 3** (solutions, industries, platform pages) is next. Solutions and industries
   still route to service lines and homepage tabs until then.

## 8. Files changed

**New**
- `app/Http/Controllers/ServiceController.php`
- `resources/content/services/*.php` (10 pages)
- `resources/views/pages/services/{index,line,show}.blade.php`
- `resources/views/components/service/{hero,visual,faq}.blade.php`
- `tests/Feature/ServicePagesTest.php`
- `docs/revamp/design/phase2/` (this report, screenshots, Lighthouse reports, tools)

**Changed**
- `routes/web.php`: `/services` and `/services/{slug}`.
- `app/Support/Revamp.php`: summaries, `pages()`, `page()`, `pageFor()`, `href()`, with
  caching.
- `config/revamp.php`: a one-line summary for each of the 52 services.
- `resources/views/components/ui/header.blade.php`, `footer.blade.php` and
  `pages/home-v2.blade.php`: links to the new pages.
- `resources/css/app.css`: FAQ styles.
- `docker/staging/docker-compose.yml`: LAN binding for side-by-side comparison.
- `docs/revamp/design/PREVIEW.md`: LAN URLs and the Cloudflare warning.
- `docs/revamp/design/phase1/tools/`: corrected proxy config and homepage checks.

## 9. Screenshots (`screenshots/`)

- `*-1440-fold.webp`, `*-768-fold.webp` and `*-390-fold.webp` for the hub, a line page,
  custom software, CRM, invoicing and Microsoft 365 (preview).
- Full pages: `services-1440-full.webp`, `services-custom-software-development-1440-full.webp`,
  `services-software-development-1440-full.webp`, `services-microsoft-365-1440-full.webp`,
  `services-crm-systems-390-full.jpg`.
- `real-chrome-services-menu.jpg`: the mega-menu in your own Chrome.
