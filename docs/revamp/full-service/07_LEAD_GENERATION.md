# 7 · Lead-Generation Strategy (full-service)

**Prepared:** 30/10/2026 · **Status:** For approval.
**Extends:** `LEAD_GENERATION_STRATEGY.md`. Its principles, attribution without new
cookies, consultant model, `enquiry_events` audit trail and Filament pipeline all
stand. This document adds the **seven enquiry paths** and the questions each one asks.
**No data-model change is made without approval (decision D7).**

---

## 7.1 Seven paths, two forms

| # | Path | Form | Pipeline |
|---|---|---|---|
| 1 | New custom development project | `/quote?type=custom-development` | Project |
| 2 | Microsoft 365 and SharePoint | `/quote?type=microsoft-365` [gated] | Project or monthly |
| 3 | AI and automation | `/quote?type=ai-automation` | Project |
| 4 | Managed IT service contract | `/quote?type=managed-it` [gated] | **Monthly** |
| 5 | Cloud and infrastructure | `/quote?type=cloud-infrastructure` | Project or monthly |
| 6 | Cybersecurity assessment | `/quote?type=security-assessment` [gated, blocked by R2/R3] | Project |
| 7 | Existing website subscription | **`/contact?package=`, unchanged** | Subscription (existing) |

The existing `/contact` form and every CLAUDE.md rule around it (evidence, read-only
fields, honeypot, rate limit, POPIA notice above the tick, save-before-mail) stay
untouched. `/quote` follows the **same rules**.

Gated types are hidden in production until confirmed. "Something else" is always
present, so no prospect is turned away (the "not limited" promise).

## 7.2 `/quote`: one `<form>`, four short steps (Alpine, works without JS)

Without JS, all four steps render as one long form, so nothing is hidden (the
`data-reveal` lesson). With JS there is one step per screen, a progress bar, and
Back/Next. It is mobile first: one column, large tap targets, native inputs (`tel`,
`email`, `select`), and no free-text field required except the description.

### Step 1: What do you need? (one tap)
Chips: the seven types + "Something else". Preset from `?type=`.

### Step 2: About the work (2–3 questions, type-specific)

| Type | Questions |
|---|---|
| Custom development | What should it do? *(text, required)* · Replacing something? *(spreadsheets / paper / an old system / nothing)* · Who will use it? *(staff only / customers too)* |
| Microsoft 365 | What do you need? *(set up / migrate / SharePoint / automate / secure / not sure)* · Number of users *(1–10 / 11–25 / 26–50 / 51–250 / 250+)* |
| AI and automation | Which process? *(text)* · How often does it happen? *(daily / weekly / monthly)* · Documents involved? *(yes / no)* |
| Managed IT | Users *(bands)* · Devices roughly *(bands)* · Current support *(none / an individual / another company / in-house)* |
| Cloud and infrastructure | Where do systems run today? *(office server / hosting company / Azure / AWS / not sure)* · Goal *(move / back up / reduce cost / monitor)* |
| Security assessment | What prompted it? *(an incident / insurance / a client requirement / POPIA / peace of mind)* · Users *(bands)* |
| Something else | Tell us about it *(text)* |

### Step 3: Budget and timing

Budget bands are **type-specific** so they are meaningful. All are excl. VAT and
include "Not sure yet", which is never penalised.

| Type | Bands |
|---|---|
| Custom development / AI and automation | < R50k · R50k–R150k · R150k–R500k · R500k+ · Not sure yet |
| Microsoft 365 / Cloud / Security (project) | < R20k · R20k–R75k · R75k–R250k · R250k+ · Not sure yet |
| Managed IT (monthly) | < R5k · R5k–R15k · R15k–R40k · R40k+ per month · Not sure yet |

Timeframe: As soon as possible · 1–3 months · 3–6 months · Just exploring.

*The bands are proposals [A]. The owner should set them to match the minimum
engagement we will accept (decision Q6).*

### Step 4: Contact details
Name · Company · Role (optional) · Email · Phone · Company size *(1–10 / 11–50 / 51–250 /
250+)* · Preferred contact *(phone / email / WhatsApp)* · `<x-popia-notice>` above
the consent tick · honeypot.

**Total required fields: 9.** That is the same order as today's `/contact` form.

## 7.3 Data model (additive; needs D7)

Extends `LEAD_GENERATION_STRATEGY.md` §4.1 with nullable columns on `enquiries`. No
existing column changes and existing rows are untouched:

| Column | Type | Notes |
|---|---|---|
| `journey` | enum `subscription|project` | Existing rows backfilled `subscription` |
| `project_type` | string | One of the 7 types or `other` |
| `answers` | json | Step 2 answers, keyed by question id. Shown **read-only** in the panel, like every submitted field |
| `budget_band` | string | |
| `timeframe` | string | |
| `company_size` | string | |
| `preferred_contact` | string | |

Attribution columns (`landing_page`, `referrer`, `utm_*`, `consultant_id`) as in the
earlier document.

## 7.4 Routing and priority (in Filament, no third-party CRM)

| Signal | Effect |
|---|---|
| `managed-it` or `microsoft-365` with ≥ 11 users | **Priority: monthly-revenue lead** |
| Budget ≥ R150k (project) or ≥ R15k/month | Priority |
| Timeframe "As soon as possible" | Priority |
| "Just exploring" + "Not sure yet" | Nurture: send the relevant guide, follow up in 30 days |
| Type is gated (should not happen) or "Something else" | Owner triage |

Priority is a **displayed badge and a sort order**, not an automated reply. Nothing
auto-advances an enquiry (CLAUDE.md "Enquiries are evidence"). The notification mail
names the type and priority in its subject line, so the right person opens it first.

## 7.5 Conversion points across the site

| Where | CTA | Goes to |
|---|---|---|
| Header (every page) | Get a Quote | `/quote` |
| Service page hero and footer | Get a quote for {service} | `/quote?type=…` (embedded step 1 at the foot) |
| Solution page | Solve this with us | `/quote?type=…` |
| Industry page | See the platform / Discuss a project | `/packages/{slug}` / `/quote` |
| Platform pages (existing) | Start with {package} | `/contact?package=` **(unchanged)** |
| Floating WhatsApp (existing) | Pre-typed message naming the page | WhatsApp |
| Homepage section 9 | 7 chips | `/quote?type=…` |

## 7.6 Response and follow-up

- **Response promise:** none is published until the owner sets one (Q4). Once set, it
  appears on `/quote`'s thank-you page and in the confirmation email, and becomes a KPI.
- Discovery call → written proposal → deposit. The project contract template is
  **missing** (`06` §6.4) and must exist before the first project is signed.

## 7.7 KPIs (monthly, from the Filament panel)

Enquiries by type · % priority · time to first contact · enquiry → proposal →
won · won value split into **once-off vs monthly** · source (organic / direct /
referral / WhatsApp / Business Profile) · consultant attribution.

**The headline KPI is new monthly recurring revenue (MRR) won,** because Tier 2 exists
to create it.

## 7.8 Decisions

| # | Decision |
|---|---|
| D7 | Approve the additive `enquiries` columns (§7.3) |
| Q4 | Response promise for project enquiries |
| Q5 | Is the consultation free, and how long is it? |
| Q6 | Budget bands and minimum engagement size |
| Q7 | Who receives each type (one inbox, or per consultant)? |
