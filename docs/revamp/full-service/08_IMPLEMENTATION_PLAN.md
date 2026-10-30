# 8 · Phased Implementation Plan (full-service)

**Prepared:** 30/10/2026 · **Status:** For approval. **Nothing in this plan has started.**
**Replaces:** phases 2–5 of `WEBSITE_REVAMP_IMPLEMENTATION_PLAN.md`. Phase 0 (backup,
worktree, staging) is done; Phase 0.5 (production remediation) is unchanged and still
needs its own approvals.

## 8.1 Ground rules (unchanged)

- All work is on the `revamp` branch, in the isolated staging stack (`127.0.0.1:8096`).
- Production source, database, Docker configuration and deployment: **untouched**
  without an explicit, separate approval.
- Existing packages, prices, routes, demos, enquiry data and integrations: preserved.
- Security remediation is **never bundled** into a visual release.
- Unconfirmed capability is never rendered in production (enforced by test).

## 8.2 Phases

### Phase A: Strategy approval *(you, now)*
- Approve or amend documents 01–07.
- **Owner completes the capability matrix (`06`)**, the single biggest unlock.
- Answer the decisions listed in §8.4.

**Exit:** signed-off positioning, a filled matrix, decisions answered.

### Phase B: Production prerequisites *(production; separate approval for each)*

| Item | Why it blocks the revamp |
|---|---|
| **R1** off-site backups fixed and restore-tested | Hosting, backup and DR services cannot be sold before this |
| **R2** MySQL port closed, credentials rotated | Security and hosting services |
| **R3** `APP_ENV=production`, `APP_DEBUG=false`, HTTPS URL, secure cookies | Security services; basic credibility |
| **Live-work correction**: Invoice Ninja → InvoiceShelf in `config/clients.php` (one string, one test) | The live homepage currently makes a claim that is no longer true |
| `data-reveal` `<noscript>` fix in `layouts/site` and `layouts/demo` | The live site shows 36 blank blocks without JS |

The last two are content and CSS-only. They can ship as one small release, independent
of the redesign, **with your approval**.

**Exit:** each item approved, applied, smoke-tested and recorded in
`PRODUCTION_SAFETY_CHECKLIST.md`.

### Phase C: Homepage v3 and the services hub *(staging)*
1. Extend `config/revamp.php` into the 7-line catalogue with the 4 availability states
   (interim, as the prototype does).
2. Homepage v3 per `04_HOMEPAGE_COMPOSITION.md`: conditional H1, 8-card grid with
   fallbacks, the "Built by 7Hills" section, gated M365 spotlight, quote chooser.
3. New header and footer navigation (`05` §5.1).
4. `/services` hub and the 7 line pages (sections with anchors, no dedicated pages yet).
5. Tests: §4.6 of `04` plus a hub test that no gated service renders in production.
6. Fresh screenshots, LCP, CLS, axe and interaction checks, as for v2.

**Exit: your visual approval of v3 on staging.** ← *the next stop after this document set*

### Phase D: Content model and wave 1 pages *(staging)*
1. `Offering` model, migration and `/admin/offerings` (`03` §3.6). This is an
   **additive** migration, never run against production until launch approval.
2. Seed from the interim config; tests move to the model.
3. Landing-page template (`05` §5.4) with JSON-LD.
4. **Wave 1 pages** (6–8, depending on Phase A answers), written one at a time and
   reviewed by you for substance and claims.
5. Solutions pages (6) and industry pages (6).
6. `/work` page.

**Exit:** content review per page; anti-thin check; link-graph test passes.

### Phase E: `/quote` and the pipeline *(staging; needs D7)*
1. Additive `enquiries` columns (`07` §7.3), `enquiry_events`, attribution.
2. `/quote` four-step form, no-JS fallback, POPIA notice, honeypot, rate limit,
   save-before-mail.
3. Filament: type, priority badge, filters, read-only answers.
4. Tests mirroring `EnquiryTest`: evidence is read-only, mail failure keeps the lead,
   a gated type is rejected server-side in production.

**Exit:** end-to-end test on staging with fictional data; your sign-off.

### Phase F: Launch *(production; separate approval)*
1. Release plan from `PRODUCTION_SAFETY_CHECKLIST.md`: fresh backup → image build →
   migrate (additive) → flags on → smoke list → rollback ready.
2. `REVAMP_HOME_V2` → v3 flag; production shows only approved rows.
3. Sitemap, Search Console, Business Profile (owner).
4. Update `CLAUDE.md` (product description, new invariants) and the runbook.

### Phase G: Wave 2 and review *(rolling)*
- Publish Microsoft 365, SharePoint, M365 security, AI document processing and booking
  pages **as each matrix row is approved**.
- At 90 days: Search Console review; decide the `/custom-and-it` 301; re-rank the page
  backlog on real impressions.

## 8.3 Order and dependencies

```
A (approve, fill matrix) ──┬──> C (homepage v3 + hub, staging) ──> D (model + pages) ──> E (/quote) ──> F (launch)
                           │                                                                       ▲
B (production fixes, each approved separately) ─────────────────────────────────────────────────────┘
                                                                         G (wave 2, ongoing) after F
```

B can run in parallel with C–E but must be **complete before F**.

## 8.4 Decisions needed

| # | Decision | Blocks |
|---|---|---|
| **M1** | Approve the positioning (`01`), including the conditional H1 | C |
| **M2** | Complete the capability matrix (`06`), especially Microsoft, managed IT, AI and cloud | C (what renders), D |
| **M3** | Approve Phase B items one by one | F; hosting, security and DR pages |
| M4 | Wave 1 page list (`05` §5.5) | D |
| D7 | Additive enquiry columns | E |
| Q3 | Process durations and governance claims | D |
| Q4 | Response promise for project enquiries | E |
| Q5 | Free consultation, yes or no | C (CTA copy) |
| Q6 | Budget bands and minimum engagement | E |
| Q7 | Lead routing | E |
| D6 | Excellent RE public-site screenshot permission | C ("Our work" visual) |
| O1 | Project contract template, support terms, DPA | Before the first project is signed |
| O2 | B-BBEE, CSD, PI insurance | Any "enterprise" or public-sector wording |

## 8.5 What I recommend doing first

1. **Fill in the capability matrix.** It decides whether the site leads with Microsoft
   365 and managed IT, the recurring-revenue lines, or with software and automation.
   Everything else follows from it.
2. **Approve the small Phase B release** (Live-work correction and `<noscript>` fix).
   The live site currently states something untrue about a client's install.
3. **Approve R1–R3 separately.** They gate a third of the catalogue and the
   credibility of all of it.
4. Then I build **homepage v3 and the services hub on staging** for your visual approval.
