# Phase 4: Lead generation (report)

**Prepared:** 31/10/2026 · **Branch:** `revamp` (uncommitted) · **Environment:** staging only.
**Status:** approved 31/10/2026, committed on `revamp`. Production deployment still needs separate approval.

Production was not touched: no source, database, Docker, environment, Gitea or
deployment change. The two new migrations ran on the **staging** database only
(`7hills-staging-mysql`, checked before running). The live site on :8091 is unchanged.

View it at **http://192.168.90.226:8096/quote**, beside the live site on :8091.

## 0. What you decided before the build

| Decision | Your answer | What it means in the code |
|---|---|---|
| D7, data model | Approve, staging only | Additive, nullable columns on `enquiries`, plus an append-only `enquiry_events` table. Ran on staging only |
| Consultants (D8) | Later, once commission is set | No consultants table, no `?ref=` codes. Attribution records the page, the referring site, campaign tags and the button pressed |
| Bands and routing (Q6, Q7) | Proposed bands, one inbox | The bands in `07_LEAD_GENERATION.md`, all with "Not sure yet", in `config/quote.php`. Every enquiry goes to `brand.notify_email` |
| Q4, Q5 | Not yet decided | No response-time promise and no "free" anywhere on `/quote`. A test enforces it |

## 1. What was built

**`/quote`: one form, four short steps.**
1. **What you need.** Project types, offered only where the service behind them may be
   published, plus "Something else", which is always there. A link points platform
   buyers to `/packages`.
2. **About the work.** Two or three questions for the chosen type, and one free-text box
   whose label and requirement follow the type ("What should it do?" is required for
   custom software and optional for cloud).
3. **Budget and timing.** Budget bands that fit the type: project, service or monthly.
   Changing to a type with a different set clears the old band.
4. **Your details.** Name, company, role, email, phone, company size, preferred
   contact, the POPIA notice above the consent tick, and the honeypot.

**How the form behaves:**
- With JavaScript, each step gets its own screen, with a progress bar, Back and Next,
  and focus moved to the step heading.
- The browser checks each step before moving on, and the server checks everything again.
- When the server rejects a submission, the form reopens at the first step with an
  error, and everything the visitor entered is kept.
- Without JavaScript, every step and every type's questions are shown on one long form
  that still submits. The server reads only the chosen type's answers.
- A `?type=` link opens the form at step 2 with the type already chosen. `?from=`
  records which button sent the visitor.

**`/quote/thank-you`.** Shows the reference and promises a reply through the channel
the visitor chose (phone, email or WhatsApp) instead of a time we have not committed
to. It is `noindex`.

**The rules are the same as `/contact`:**
- The enquiry is saved before the email is sent, so a mail failure is logged and the
  lead is kept.
- It arrives as `new`, and nothing moves it on automatically.
- Email is checked with `rfc` only.
- Consent is explicit, with the POPIA notice above the tick.
- There is a honeypot and a limit of 10 submissions a minute.
- IP address and browser are recorded for provenance.
- The reference is generated (`ENQ-…`).

**Validation goes further than the form:**
- A gated type is refused in production even if someone builds the POST by hand.
- Every answer must come from the chosen type's own list.
- The budget band must belong to the type's set. A monthly band on a project is refused.

**Priority** (§7.4) is worked out once, when the enquiry is saved, and shown as a badge
and a sort order. It never replies to anyone or moves an enquiry on.

| Badge | When |
|---|---|
| Monthly revenue | Managed IT or Microsoft 365 with 11 or more users |
| High | Budget R150k+ (project), R250k+ (service), R15k+ a month, or "As soon as possible" |
| Nurture | "Just exploring" and "Not sure yet" |
| Owner triage | "Something else" |
| Standard | Everything else |

**Attribution, with no new cookie.** The first public page of a visit is held in the
**existing** session:
- the landing page;
- the referring site, as a **domain only** (our own pages don't count);
- `utm_source`, `utm_medium` and `utm_campaign`, kept only if they look like real tags;
- the button that was pressed, from `?from=`.

These are copied onto the enquiry, from `/quote` **and** `/contact`, and discarded
with the session if no enquiry is made. The back office and assets never count as a
landing page.

**The privacy notice describes all of this while the feature is on.** Clause 3 lists it,
clause 4 adds the legitimate interest, and clause 10 says what the session cookie holds.
It still says "two cookies", which a test checks.

**History (`enquiry_events`).** Each enquiry records:
- when it was received;
- every status change, with who made it;
- when it was first contacted.

The panel shows this as a read-only "History" table, and the model refuses any update
or delete.

**Back office.**
- A project enquiry gets a read-only "The project" section: type, priority, and the
  answers in plain words.
- A collapsed, read-only "How they found us" section shows how they arrived.
- Fields that only apply to subscriptions are hidden on a project enquiry.
- The list adds a Priority column, which sorts by importance and not alphabetically,
  plus Kind and Priority filters.
- "Received" now shows as dd/mm/yyyy.

**Notification email.** The subject names the type and the priority, for example "New
project enquiry ENQ-2026-00010 — Custom software · High". The body lists the answers.
Subscription emails are unchanged.

**Every "Get a quote" goes through one helper, `Quote::url()`:**
- With `/quote` on: the header, the menus, the homepage, service lines, service pages,
  solutions and the hubs all open `/quote`, carrying the right type and the page the
  visitor came from.
- With `/quote` off: they fall back to `/contact` exactly as before.
- Platform buttons still go to `/contact?package=…`, unchanged.
- Industry pages with a platform keep the platform enquiry, and now record which page
  sent the visitor.

### Why `/quote` has its own switch, `REVAMP_LEADS`
The new code writes to columns that exist only where the D7 migrations have run. If this
branch were merged before those migrations reached production:
- `/quote` would be a 404;
- every link would fall back to `/contact`;
- `/contact` would write nothing new;
- no history row would be written.

So a merge alone cannot break the live enquiry form. Tests run in all three
combinations of the switches to prove it.

## 2. Results

### Laravel tests: 151 passed, 2 012 assertions, in each of three modes
| Mode | Result |
|---|---|
| Staging: everything on | **151 passed** |
| New pages on, `/quote` off (`REVAMP_LEADS=false`) | **151 passed** |
| Everything off (what production runs today) | **151 passed** |

There are 26 new tests in two files.

`QuoteTest` (15):
- it is a 404 when off
- it saves and notifies
- a mail failure keeps the lead
- only the chosen type's answers are kept
- invented answers are refused
- the band must match the type
- the description is required only where it is needed
- consent, honeypot and contact details
- a gated type is refused in production even with the preview on
- staging marks the preview
- every priority rule
- the form works without JS, with the POPIA notice above the tick and no time promise or "free"
- the preset type
- the fallback links
- the panel shows the project read-only

`LeadAttributionTest` (11):
- first touch wins
- the referrer is kept as a domain only and bad tags are dropped
- **no new cookie**
- the back office is never the landing page
- `/contact` is attributed too
- **nothing is written when off**
- the privacy notice describes it only when it is on
- history records receipt, status and first contact
- history cannot be edited
- history cannot be deleted
- triage is set on arrival

### Browser checks (`tools/p4.mjs`, results in `tools/p4-results.txt`): 29 / 29 pass
- **Pages checked:** 11 (`/quote` three ways, the thank-you page, `/contact`, `/privacy`,
  the homepage, `/services`, a solution, an industry and `/work`) at 1440, 768 and 390.
- **Overflow and errors:** no horizontal overflow and no JavaScript errors.
- **axe (WCAG 2.0–2.2 AA plus best practice):** no violations on any page or **on any
  of the four steps**, or on the error state.
- **Full JS flow on a phone, arriving from a tagged solution link:**
  - It won't advance without a choice or with questions unanswered.
  - It shows only the chosen type's questions, and the description label follows the type.
  - Focus moves to each step heading.
  - It submits and shows a reference, and the thank-you page names the channel with
    no time promise.
- **The record it stored, checked in the staging database:**
  - landing page `/solutions/never-lose-a-lead`
  - `utm_source=p4-check` and `utm_campaign=phase4`
  - the button that was pressed
  - priority "High"
  - its "Received" history row
  - its notification, written to the mail log and **not sent**, because staging mail
    goes to the log
- **Type switching:** moving to a monthly type clears the band and turns on the monthly
  bands. The preview type is marked on staging.
- **Keyboard:** arrow keys choose a type, and Tab and Enter advance. Tab reaches the
  options, and the focused one shows a visible ring.
- **Reduced motion:** the progress bar does not animate, and step changes jump instead
  of scrolling.
- **Server-side errors:** they reopen at step 3 (the first step with an error), and
  the answers are kept.
- **No JavaScript:** all four steps and every type's questions are on screen, with no
  dead buttons. It submits and returns a reference.
- **Buttons around the site:**
  - The header, service and solution buttons carry the type and the page.
  - Platform buttons still go to `/contact?package=`.
  - Industry buttons keep the platform enquiry, with the page recorded.
- **Back office, on staging with the preview login and invented data:**
  - The list shows type and priority.
  - The enquiry shows its answers, how the visitor arrived and its history.
  - Every submitted field is disabled.
- **Links:** all 82 same-site links answer.

**Regression:** the Phase 3 checks pass 15/15 and the homepage checks pass 23/23 against
the changed header and links.

### Lighthouse 12.6.1 (`reports/`; behind a compressing proxy; mobile = slow 4G, 4× CPU)
| Page | Perf | A11y | Best practice | SEO | LCP | CLS |
|---|---|---|---|---|---|---|
| `/quote`, desktop | **100** | 100 | 79 | 100 | 0.7 s | 0.009 |
| `/quote`, mobile | **94** | 100 | 79 | 100 | 2.9 s | 0.044 |
| `/quote/thank-you`, mobile | 94 | 100 | 79 | 69* | 2.9 s | 0.044 |
| `/contact`, mobile | 93 | 100 | 79 | 100 | 3.0 s | 0.044 |

\*The thank-you page is deliberately `noindex`, so Lighthouse's SEO score is low by
design. Best practice loses points only for plain HTTP on staging.

## 3. Design critique: found and fixed

| Problem | Fix |
|---|---|
| **Mobile layout shifted by 0.306** when JavaScript loaded and revealed the progress bar and the Next buttons | Those controls are now hidden by the layout's `<noscript>` rule instead of `x-cloak`, so they are in place at first paint. The shift is now 0.044, all of it from the site-wide WhatsApp button |
| Selected options used the browser's default blue | `accent-color` set to Emerald, so radios and the consent tick are on-brand |
| A "0%" label sat beside a progress bar that was already a quarter full | The percentage was removed. "Step 1 of 4: What you need" carries it |
| On a phone the form started below the fold | The hero padding is tighter and the step headings went down a size |
| Error text read "The when you would like to start field is required." | Messages written for each field |
| The panel showed answers as "something?: Spreadsheets", labelled a high-priority lead just "Priority", and showed "Received" as a raw timestamp | Answers now read "Question — Answer", the badge reads "High", and dates use dd/mm/yyyy |
| `/quote` and `/contact` placed an `<aside>` inside `<main>` (axe best practice) | Changed to a `<div>` |
| The old site footer's "Midrand, Gauteng" was 3.77:1, failing AA. **This is on the live site today.** | Raised to 60% white, about 6:1. It affects the old pages (`/contact`, `/privacy`, …) once merged |
| The cloud question offered "back it up" | Removed: backup is blocked until R1 is fixed, so the form does not invite work we cannot sell |

## 4. Files changed

**New**
- `database/migrations/2026_10_31_100000_add_project_fields_to_enquiries.php` (additive and nullable)
- `database/migrations/2026_10_31_100100_create_enquiry_events_table.php`
- `config/quote.php`: questions, bands, timeframes, sizes, routing from service lines, priorities
- `app/Support/Quote.php` and `app/Support/Attribution.php`
- `app/Http/Middleware/CaptureAttribution.php`, registered in `bootstrap/app.php`
- `app/Http/Controllers/QuoteController.php` and `app/Http/Requests/StoreQuoteRequest.php`
- `app/Models/EnquiryEvent.php`
- `app/Filament/Resources/Enquiries/RelationManagers/EventsRelationManager.php`
- `resources/views/pages/quote/{create,thanks}.blade.php`
- `tests/Feature/QuoteTest.php` and `tests/Feature/LeadAttributionTest.php`
- `docs/revamp/design/phase4/`: this report, 15 screenshots, 4 Lighthouse reports, the
  browser script and its results

**Changed**
- `routes/web.php`: `/quote`, `/quote` (POST) and `/quote/thank-you`.
- `config/revamp.php`: the `leads` switch.
- `app/Models/Enquiry.php`: `answers` cast, history hooks, project interest.
- `EnquiryController`: attribution on `/contact`, only while the switch is on.
- `app/Mail/EnquiryReceived.php` and `mail/enquiry-received.blade.php`.
- The enquiry form, table and resource in the panel.
- `components/popia-notice.blade.php`: a `purpose` for project enquiries. The default
  wording is unchanged.
- `pages/privacy.blade.php`: the attribution wording, shown only while the switch is on.
- `pages/contact.blade.php`: carries `from`, and its sidebar is now a `div`.
- `components/site-footer.blade.php`: the contrast fix.
- The header, homepage, work, service, line, solution and industry pages, and both hubs:
  their links now go through `Quote::url()`.
- `docs/revamp/design/PREVIEW.md` and `full-service/07_LEAD_GENERATION.md`: the
  decisions recorded.
- Staging `.env` (not in git): `REVAMP_LEADS=true`.

## 5. Not resolved, or needs your decision

1. **The migrations are staging-only.** Running them on production, and turning on
   `REVAMP_LEADS` there, is a deployment step that needs its own approval. The order
   would be: back up the database, migrate, then turn on the switch.
2. **Q4, a response promise.** None is published. Once you set one, it belongs on the
   thank-you page and in a confirmation email to the prospect, which is not built because
   it would make that promise for you.
3. **Q5, is the first conversation free?** The page says "Nothing to pay unless you
   accept it" (the proposal), which matches the discovery → proposal → deposit flow. It
   does not say "free".
4. **Q6, the budget bands are proposals.** Set your minimum engagement and I will change
   `config/quote.php`. Stored values are kept stable so reporting survives a relabel.
5. **D8, consultants and commission.** Not built. Attribution already leaves room for a
   consultant reference later.
6. **The privacy notice changed (clauses 3, 4 and 10)** to describe attribution. Add it
   to the attorney's review (open decision 2 in `CLAUDE.md`).
7. **Pipeline stages.** Project enquiries use the existing five statuses. The separate
   project pipeline from the strategy (discovery booked, proposal sent, lost reason) and
   the KPI dashboard (§7.7) are not built yet. They would be the natural next lead-gen
   step.
8. **Mobile LCP is 2.9–3.0 s** against a 2.5 s target. The cause is the font preloads,
   as in Phases 2 and 3.
9. **Test enquiries on staging.** The browser runs left fictional enquiries in the
   staging back office, marked "(staging)" and using `example.com` addresses. They are
   useful for a look round the panel. Say if you want them cleared.

## 6. Screenshots (`screenshots/`)
- `quote-{1440,768,390}-fold`: the first screen at each width.
- `quote-step1/2/3-390-fold` and `quote-step4-390-full`: each step on a phone.
- `quote-step1/2-1440-fold`: desktop.
- `quote-errors-390-fold`: how the server's errors look.
- `quote-nojs-1440-full`: the whole form without JavaScript.
- `quote-thanks-{1440,390}-fold`: the thank-you page.
- `admin-enquiries-1440` and `admin-enquiry-project-1440`: the back office, staging,
  invented data.
