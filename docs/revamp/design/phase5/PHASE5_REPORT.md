# Phase 5: launch readiness and open issues from Phases 1–4

**Status:** approved 31/10/2026 and committed on `revamp`. Everything is on staging
(`http://192.168.90.226:8096`). **Production is untouched**, and
the release itself is a separate approval (see `RELEASE_PLAN.md`).

Phase 5 in the implementation plan is "Launch": full QA, proofreading, the release
procedure and the 30-day watch. Production work needs your separate approval, so
this phase does everything that can be done on staging, and the release plan sets
out the rest step by step.

## 1. Open issues from earlier phases: what was settled

| From | Issue | Now |
|---|---|---|
| P1–P4 | Mobile pages took 2.9–3.8 s to show their main content (target 2.5 s) | **Most pages now 2.1–2.5 s.** Two causes. First, a bug in Laravel's font plugin gave every weight two `@font-face` rules (WOFF2 and WOFF), so browsers fetched both and never used the preloaded file. The fonts now come straight from the WOFF2 files. Second, only the two faces the first screen uses are preloaded, not all eight. Montserrat 900 was unused and is gone. |
| P1–P4 | Layout shift 0.044 on every page | **Now 0.004.** The WhatsApp button was positioned only by JavaScript, so it sat at the top of the page and then jumped down. It now has a CSS resting position. |
| P1 | WhatsApp button outside a landmark, and covering the last row of the open mobile menu | It is now a labelled landmark, and it steps aside while the menu is open (checked in a browser). |
| P3 | `/about`, `/demos`, `/how-it-works`, `/custom-and-it`, `/contact`, `/terms` and `/privacy` still had the old header and footer | **They now use the new header and footer** when the revamp switch is on, so a visitor never crosses between two sites. Their content is unchanged. With the switch off, the live layout is byte-for-byte as before (tested). |
| P4 | Project pipeline stages and monthly figures not built | **Built.** A "Discovery booked" stage. A required reason when a lead is lost (price, timing, chose someone else, stopped responding, not something we do, other). Once-off and monthly values recorded on a win. A "This month" dashboard panel: new monthly revenue won (headline), once-off work won, project enquiries by type, share of high or monthly priority, median time to first contact, and the enquiry → proposal → won rate with the top source. A win counts in the month the history records it. |
| — | There was no sitemap (the plan assumed one) | `/sitemap.xml` is built from the same sources as the pages, so it cannot list a 404. Demo tenants, thank-you pages, the back office and anything shown only as a staging preview are left out. `robots.txt` now blocks `/admin` and names the sitemap. |
| — | Demo pages: contrast failures and 4–5 s phone loads | Some tenants' accent colours fail 4.5:1 for white or small text; a deeper shade of the same colour is now used there. 720 px copies of every demo photograph are served by `srcset`, made locally from the files we already had, with no new downloads. Realty: 83 → 96. |
| — | `CLAUDE.md` said Laravel 12 and had no record of the revamp | Corrected, and a "The revamp" section records the three switches and the rules they enforce. |

## 2. Automated tests

**167 passed (2,470 assertions)** in each of the three switch combinations: all on;
`REVAMP_LEADS=false`; everything off.

New this phase:
- `LaunchReadinessTest` (10 tests). The release gate:
  - the safety checklist's smoke list, plus every URL in the sitemap, returns 200 (43 pages);
  - the sitemap is valid XML, follows the switches, and leaves out gated pages, demos, thank-you pages and the back office;
  - every listed page is proofread: one `h1`, a title, a real description, a canonical link, no "Seven Hills", no placeholder text, and no "Holdings" outside `/terms`, `/privacy` and `/about`;
  - one shared header and footer;
  - only two preloaded fonts;
  - the WhatsApp button is in a landmark and resting in place;
  - demo photographs offer a small copy.
- `PipelineTest` (6 tests):
  - a lost lead needs a reason;
  - a win records both values;
  - the prospect's words still cannot be rewritten;
  - the outcome fields are hidden where `/quote` is off;
  - revenue counts in the month it was won;
  - the figures are hidden when `/quote` is off.

## 3. Production-mode rehearsal (staging, 31/10/2026)

The app was served with `APP_ENV=production`, `APP_DEBUG=false` **and the preview
switch deliberately left on**, to prove production ignores it:
- all 43 sitemap URLs plus `/admin/login` and a demo tenant: **200**;
- `/services/microsoft-365` (unconfirmed): **404**, and absent from the sitemap;
- no staging banner; a missing page shows no stack trace or file paths.

## 4. Browser checks (`tools/p5.mjs`, headless Chromium; **12 of 12 pass**)

- 47 pages × 1440, 768 and 390 px: **no horizontal overflow, no JavaScript errors.**
- axe (WCAG 2.2 AA + best practice) on every page at 1440 and 390: **no violations**,
  demos included. The first run found 4 issues and the second found 1; all are fixed.
- 112 same-site links all answer. The only off-site links are WhatsApp, the live
  client's public site and the Information Regulator.
- Keyboard: the first Tab reaches "Skip to content", which moves focus into the page.
- Reduced motion: nothing is hidden behind the reveal animation.
- Mobile menu: the WhatsApp button hides when the menu opens and returns when it closes.
- Panel: the dashboard shows the monthly figures; a won enquiry shows both value fields.
- Regression: Phase 3 and homepage checks, see §7.

## 5. Lighthouse 12.6.1 (behind a compressing proxy; mobile = slow 4G, 4× CPU)

| Page | Perf / A11y / BP / SEO | LCP | CLS |
|---|---|---|---|
| `/` desktop | 100 / 100 / 79 / 100 | 0.6 s | 0.001 |
| `/` mobile | 91–94 / 100 / 79 / 100 | **2.8–2.9 s** | 0.004 |
| `/services` | 98 / 100 / 79 / 100 | 2.1 s | 0.004 |
| `/services/custom-software-development` | 89–97 / 100 / 79 / 100 | 2.3–2.5 s | 0.005 |
| `/solutions/never-lose-a-lead` | 97 / 100 / 79 / 100 | 2.3 s | 0.004 |
| `/industries/automotive` | 96 / 100 / 79 / 100 | **2.7 s** | 0.004 |
| `/packages` | 97 / 100 / 79 / 100 | 2.3 s | 0.004 |
| `/about` | 98 / 100 / 79 / 100 | 2.1 s | 0.004 |
| `/demos` | 95 / 100 / 79 / 100 | 2.3 s | 0 |
| `/demos/realty` | 96 / 100 / 79 / 66\* | 2.6 s | 0.005 |
| `/quote` | 98 / 100 / 79 / 100 | 2.1 s | 0.004 |
| `/contact` | 96 / 100 / 79 / 100 | 2.3 s | 0.005 |

Before this phase, the same pages measured mobile LCP 2.9–3.8 s, CLS 0.044–0.137 and
/demos 77.

\*Demo tenants are deliberately `noindex`. Best Practices is 79 everywhere only
because staging is plain HTTP (`is-on-https`, `redirects-http`). Production is
served over HTTPS by Cloudflare. Scores vary by ±5 between runs, mostly in blocking
time; the ranges above are from repeated runs.

## 6. Not resolved, or needs your decision

1. **The release itself.** `RELEASE_PLAN.md` sets out the order, the switches and the
   rollback. Its blockers are yours:
   - R1, off-site backups (failing since 16/10);
   - R3, production mode (debug is on for a public site);
   - the capability matrix (M2);
   - the attorney's review of the privacy changes;
   - R7, the Gitea remote.
2. **Homepage mobile LCP is still 2.8–2.9 s** (target 2.5 s). The largest element is
   the hero heading. It paints at 1.8 s in a fallback font and is counted again
   when Montserrat arrives. `font-display: optional` for the heading face would end
   that, but slow phones would then sometimes see the fallback font for the whole
   visit. That is a brand decision, so I have not made it.
3. **`/contact` promises a reply "within one working day".** This is live copy from
   before the revamp, and it applies to subscription enquiries. `/quote` makes no
   promise (Q4). Say if you want the two to match.
4. **On real phones, full-width demo photographs still load the 1,400 px original**,
   because high-density screens ask for it. Cards and Lighthouse's phone use the
   small copy. A third size would fix it, if the demos matter enough.
5. **Owner decisions still open from Phase 4:** Q4 response promise, Q5 free first
   conversation, Q6 budget bands, D8 consultants, and clearing the staging test
   enquiries. ENQ-2026-00017 "No Script (staging)" was marked won for the dashboard
   screenshot.
6. **Still open from Phases 1–3:**
   - mobile homepage length (16 screens against a target of 12);
   - "daily backups" and the 80% mailbox warning on the **live** site (the revamp drops both);
   - committing the InvoiceShelf fix on `main`;
   - the production-mode copy before any Cloudflare switch.

## 7. Regression

Phase 3 checks and the homepage interaction checks were rerun after the changes;
**Phase 3 checks 15 of 15 and homepage checks 23 of 23 pass.** Details in `tools/p5-results.txt`.

## 8. Files changed

**New**
- `app/Http/Controllers/SitemapController.php`
- `app/Filament/Widgets/LeadKpis.php`
- `database/migrations/2026_10_31_100200_add_outcome_fields_to_enquiries.php`
  (additive; staging only)
- `tests/Feature/LaunchReadinessTest.php`, `tests/Feature/PipelineTest.php`
- `public/images/demos/*/N-720.jpg` (35 small copies, 2.7 MB, made locally)
- `docs/revamp/design/phase5/`: this report, `RELEASE_PLAN.md`, 32 screenshots,
  4 Lighthouse reports, tools

**Changed**
- `vite.config.js`: WOFF2-only fonts, two preloads, no Montserrat 900.
- `resources/views/components/layouts/site.blade.php`: uses the new layout when the
  revamp is on.
- `whatsapp-float.blade.php`, `ui/header.blade.php`, `resources/css/app.css`: the
  button's landmark, resting position, and hiding under the menu.
- `app/Models/Enquiry.php`: discovery stage, lost reasons, value casts.
- `EnquiryForm.php`, `EnquiriesTable.php`: outcome fields (hidden where `/quote` is
  off), discovery badge.
- `routes/web.php`: `/sitemap.xml`. `public/robots.txt`: `/admin` and the sitemap.
- Demo templates (`layouts/demo`, `demos/show`, `pages/demos`, `demo-art`): accent
  contrast, landmarks, `srcset`. `ImportDemoImages`, `DemoImages`: small copies.
- `pages/about.blade.php`: nested landmark removed.
- `CLAUDE.md`: Laravel 13, and the revamp's switches and rules.

## 9. Screenshots (`screenshots/`)

- `{about,how-it-works,custom-and-it,demos,contact,terms,privacy}-{1440,768,390}-fold`:
  every page that moved to the new layout, at three widths.
- Full pages for about, how-it-works, custom-and-it and demos at 1440 and 390.
- `about-390-menu-open`: the mobile menu with the WhatsApp button stepped aside.
- `admin-dashboard-1440`: the monthly figures (staging, invented data).
- `admin-enquiry-won-1440`: a won enquiry with its value fields.
