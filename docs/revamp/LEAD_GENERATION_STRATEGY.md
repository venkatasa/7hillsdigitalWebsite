# Lead Generation Strategy

**Prepared:** 30/10/2026  **Status:** Proposal only. **No integration is built and the
enquiry data model is not changed until approved** (decision D7).

---

## 1. Principles carried over (non-negotiable)

These are today's rules, enforced by `EnquiryTest`, and they apply to every new form:

- **The lead is saved before any notification.** A mail outage never costs a lead.
- **What the prospect submitted is evidence.** It is read-only in `/admin`
  (`disabled()->dehydrated(false)`), and enquiries cannot be hand-created.
- **POPIA at the point of collection:** `<x-popia-notice>` above an explicit consent
  tick; IP, user agent and consent recorded; soft deletes.
- **Spam:** honeypot (`website`, `prohibited`) + throttle (10/min). No third-party CAPTCHA.
- **Email validation is `rfc` only.**
- **No third-party scripts or analytics cookies.**

---

## 2. Two conversion journeys

| | **Journey S — Subscription** | **Journey P — Project / Enterprise** |
|---|---|---|
| Who | SME owner who fits a platform | SME with a specific problem, or an enterprise buyer |
| Entry points | Home pathway S, `/packages*`, industry pages, demos, add-on links | Home pathway P, `/solutions/*`, `/enterprise/*`, `/work`, `/security` |
| Primary CTA | "See platforms" → "Ask about {package}" | "Book a consultation" |
| Form | **`/contact` — existing form, unchanged in behaviour** | **`/contact/project` — new, 3 short steps** |
| Promise after submit | "We'll reply within one working day with a demo built around your trade." | "We'll reply within one working day to book a 30-minute consultation." *(owner to confirm SLA)* |
| Pipeline | Existing statuses: New → Contacted → Quoted → Signed up / Not proceeding | Project stages (§4.3) |
| Typical value | R880–R1 180 × months + modules | Quoted project or retainer |

The two journeys cross-link once: a project form chooser offers "Actually, I just
need a website →" (S), and the subscription form offers "Need something bigger? →" (P).

---

## 3. Forms

### 3.1 `/contact` (Journey S) — kept
The fields, validation and tier-to-package check stay exactly as they are. Additions
(all **optional and hidden**, filled server-side; §5): enquiry type `subscription`,
attribution fields, consultant reference.

### 3.2 `/contact/project` (Journey P) — new, progressive multi-step (Alpine), one `<form>`

Without JS the three steps render as one long form, so it still works.

**Step 1 — What do you need?**
| Field | Type | Required | Values |
|---|---|---|---|
| `interest` | radio cards | yes | The published B and C offerings + "Not sure — here's the problem" |
| `message` | textarea (≤ 4 000) | yes | "Describe the problem or the process in a few lines" |
| `current_systems` | text (≤ 255) | no | "What do you use today? (e.g. Excel, Sage, Microsoft 365)" |

**Step 2 — About your organisation**
| Field | Type | Required | Values |
|---|---|---|---|
| `company` | text | yes (P only) | — |
| `industry` | text | no | — |
| `company_size` | select | yes | 1–9 · 10–49 · 50–199 · 200–999 · 1 000+ |
| `timeline` | select | yes | Exploring · Within 3 months · 3–6 months · 6+ months |
| `budget_band` | select | no | Prefer not to say · < R50k · R50k–R250k · R250k–R1m · > R1m |
| `role` | select | no | Owner/MD · IT/Operations · Finance · Other |

**Step 3 — How do we reach you?**
`name`, `email`, `phone`, `preferred_contact` (Email / Phone / WhatsApp),
`consultant_code` (optional, "Referred by a 7Hills consultant? Enter their code"),
POPIA notice + consent, and the honeypot.

Bands, not free numbers, for size and budget: they're quicker to answer, easier to
report on, and less sensitive.

---

## 4. Proposed data model (additive only — requires D7)

### 4.1 `enquiries` — new **nullable** columns (existing rows untouched)
| Column | Type | Purpose |
|---|---|---|
| `enquiry_type` | string(20), default `subscription` | `subscription` · `project` · `enterprise` · `general` |
| `offering_id` | FK → `offerings`, null on delete | What a project enquiry is about |
| `company_size`, `timeline`, `budget_band`, `role`, `preferred_contact` | string(30) | Validated against fixed lists |
| `current_systems` | string(255) | |
| `pipeline_stage` | string(30) | Project stages (§4.3); `status` stays as-is for subscriptions |
| `estimated_value` | decimal(12,2) | **Internal**, set by staff |
| `sales_consultant_id` | FK → `sales_consultants`, null on delete | Attribution |
| `attribution_method` | string(20) | `ref_link` · `code_entered` · `assigned` |
| `utm_source`, `utm_medium`, `utm_campaign` | string(100) | First-touch campaign |
| `landing_page`, `referrer_host`, `cta_source` | string(255) / (100) / (60) | First page seen; referring **domain only**; which CTA (`?from=`) |

Prospect-submitted columns join the read-only set in `EnquiryForm`. Staff-set
columns (`pipeline_stage`, `estimated_value`, consultant assignment) are editable,
and every change is logged (§4.4).

### 4.2 `sales_consultants` — new
`id`, `name`, `email`, `phone`, `code` (unique, e.g. `TM7K2`, generated), `status`
(`active`/`inactive`), `commission_plan` (JSON or FK; D8), `agreement_signed_at`,
`notes`, timestamps, soft deletes. Filament `SalesConsultantResource` (admin only).
Consultants are independent contractors; their personal information is covered
by the privacy notice, and the agreement is with 7Hills Holdings (Pty) Ltd.

### 4.3 Project pipeline stages
`new` → `qualified` → `discovery_booked` → `proposal_sent` → `won` / `lost`
(with a `lost_reason` select: price · timing · went elsewhere · no response · not a fit).
Subscription enquiries keep today's five statuses **unchanged**, so no existing data,
test or report changes meaning.

### 4.4 `enquiry_events` — new (append-only audit trail)
`id`, `enquiry_id`, `user_id` (null for system), `type` (`stage_changed`,
`consultant_assigned`, `consultant_changed`, `value_changed`, `note`), `from`,
`to`, `reason`, `created_at`. No update or delete in the panel. This is what makes
commission disputes resolvable: who attributed which lead, when and why.

### 4.5 Later, when D8 is settled — `commissions`
`id`, `enquiry_id`, `sales_consultant_id`, `basis` (first-month fee, N months of
recurring fees, % of project value), `amount`, `status` (`pending` → `approved` →
`paid` / `void`), `approved_by`, `paid_at`, `reference`. Generated when an enquiry
becomes **won** and the client's first invoice is **paid** (manual confirmation at
first; automatic if invoicing is later integrated). Monthly CSV export for paying
consultants' invoices.

---

## 5. Lead attribution — without new cookies

**Capture:** on the **first request** of a visit, middleware stores in the
**existing Laravel session**:
- `landing_page`, `referrer_host` (domain only, never the full URL),
- `utm_source`, `utm_medium`, `utm_campaign` (if present),
- `ref` (consultant code from `?ref=`, if it matches an **active** consultant).

The `?from=` CTA marker is taken at form submit. The values are copied onto the
enquiry when it is saved. Nothing is set in the browser beyond the two cookies the
site already uses.

**Why the session and not a new cookie:** the privacy notice promises exactly two
strictly necessary cookies (`LegalPagesTest` checks this). Using the session keeps
that promise true. **The notice must still be updated** to say the session briefly
records how the visitor arrived (landing page, referring site, campaign tag,
consultant code) for attributing enquiries, on the basis of **legitimate interest**
(POPIA s11(1)(f)), discarded with the session if no enquiry is made.

**Consultant attribution rules (proposal; D8):**
1. A valid `?ref=CODE` link, or a code typed into the form, attributes the lead.
   **The typed code wins over the link** (it's an explicit statement by the prospect).
2. **First valid attribution wins.** If the same email already has an open
   enquiry with a consultant, the new enquiry inherits that consultant and an event
   is logged; staff resolve conflicts.
3. **Attribution window:** a lead counts for the consultant if the client signs within
   **90 days** of the enquiry (configurable).
4. An unknown or inactive code is ignored silently: the enquiry still saves.
5. Staff can assign or reassign, always with a reason, always logged in `enquiry_events`.
6. Consultants get **their own links** (`https://7hillsdigital.co.za/?ref=CODE`,
   or a deep link to any page) and an optional printable QR code generated in
   `/admin`.

**WhatsApp leads:** a `wa.me` click can't be attributed after it leaves the site.
Option: route the float through `/go/whatsapp?from=…`, which records the click
(no personal data) and redirects; the pre-typed message includes the consultant code
when one is in the session, so the conversation itself carries the attribution.

---

## 6. Sales pipeline tracking (in the existing Filament panel)

| Feature | Detail |
|---|---|
| Enquiry list | Filters: type, stage/status, offering, consultant, source, date; saved views "My leads", "Unanswered > 1 day" |
| Pipeline board | Filament page with a column per stage (project and subscription boards separate); moving a card writes an `enquiry_events` row |
| Lead detail | Submitted fields (read-only), attribution panel, consultant, timeline of events, internal notes |
| Dashboard widgets | New this week by type · Unanswered > 24 h · Pipeline value by stage (projects) · Win rate · Leads by source and by consultant |
| Notifications | Sales inbox (existing) + the attributed consultant (email) on a new lead; daily digest of unanswered leads |
| Consultant view (optional, later) | A **separate** Filament panel `/partners` where a consultant sees **only their own leads' stage**, not prospects' messages: least privilege under POPIA |

---

## 7. CRM integration — options

| Option | Pros | Cons | Recommendation |
|---|---|---|---|
| **A. Filament *is* the CRM** (extend what exists) | No new vendor; data stays on our server (POPIA, no cross-border transfer); zero licence cost; fits the "enquiries are evidence" design | We build pipeline features ourselves | **Recommended now.** It also becomes the first real deployment of our own CRM solution (B1), which turns its availability honestly towards `available` later |
| B. Self-hosted open-source CRM (e.g. EspoCRM, Twenty) fed by a webhook | Mature CRM features | Another system to host, back up and secure; duplicate data | Revisit if volume outgrows A |
| C. SaaS CRM (HubSpot, Zoho, Salesforce) | Rich features | **POPIA s72 cross-border transfer**, a privacy notice change, cost, a third-party processor | Not recommended at this stage |

Whichever option: build an **outbound webhook** (`enquiry.created`,
`enquiry.stage_changed`, signed with HMAC, queued, retried) so a later move
needs no change to the forms.

---

## 8. KPIs

| Metric | Target to agree | Source |
|---|---|---|
| Enquiries per month by type | baseline in the first 30 days | `enquiries` |
| Time to first response | < 1 working day | `contacted_at − created_at` |
| Lead → qualified (P) / contacted → signed (S) | baseline | stages |
| Win rate and value by source / offering / consultant | baseline | stages + attribution |
| Form completion (P) | > 50% of those who start step 1 | optional step-view counter (server-side, no PII) |

---

## 9. Implementation order (after approval)

1. D7 approved → migrations (additive), models, factories.
2. `/contact/project` + validation + thank-you page; tests.
3. Attribution middleware + privacy notice update (legal review) — **both ship together**.
4. Consultant resource, codes, links, QR; events log; tests.
5. Pipeline board, widgets, notifications.
6. D8 settled → `commissions` + approval + export.
7. Optional: `/partners` consultant panel; outbound webhook.

**Tests to add:** every item in implementation plan §6, Phase 3, plus: a typed
code beats a link; the attribution window is respected; an inactive consultant gets
nothing; a consultant panel user cannot read another consultant's lead or any
prospect message; and **every existing `EnquiryTest` passes unmodified**.

---

## 10. Decisions needed

| # | Decision |
|---|---|
| D7 | Approve the additive enquiry columns and the new tables (§4) |
| D8 | Commission plan: rate, basis (first month / N months recurring / % of project), attribution window (90 days proposed), when it's payable (on the client's first paid invoice proposed), and clawback if a client cancels within N months |
| D11 | Response-time promises for each journey |
| D12 | Is the consultation free? |
| D13 | CRM option A (recommended) vs B or C |
| D14 | Do consultants get a self-service panel (`/partners`), or reports by email only? |
