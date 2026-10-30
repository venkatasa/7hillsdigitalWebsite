# 1 · Revised Commercial Positioning

**Prepared:** 30/10/2026 · **Status:** For approval · **Supersedes:** the "three ways to work with us" framing in `HOMEPAGE_DESIGN_SPEC.md` §2.

---

## 1.1 The position

> **Software Development, AI, Cloud & IT Solutions for Modern Businesses.**
>
> From custom applications and intelligent automation to Microsoft 365, cloud
> infrastructure and managed IT services, we help businesses build, connect,
> secure and modernise their technology.

7Hills Digital is a **technology partner**, not a website subscription company. The
subscription platforms stay, with their prices, routes and demos unchanged. They
become **one of seven service lines** instead of the headline.

### Who we sell to

| Segment | Fit | How we win |
|---|---|---|
| **SMEs (5–50 staff)** | **Primary** | One accountable partner for software, Microsoft 365, support and hosting, on one invoice |
| **Mid-market (50–250 staff)** | **Primary** for projects | Custom applications, automation and integration that a generalist MSP cannot build |
| **Large enterprises and the public sector** | **Secondary**: departmental projects only | Specialist build capacity for a defined piece of work. We do **not** compete for transversal outsourcing (BCX, NTT DATA, Altron, IBM and Accenture together hold just over 45% of the market, per Mordor Intelligence 2025) |

Large enterprise buyers routinely ask for a B-BBEE certificate, CSD registration,
professional indemnity cover, a security questionnaire and named references. We
should not lead with "enterprise" until those exist (see the capability matrix,
§6.4).

### What makes us different (only claims we can support today)

1. **We write software, not only resell it.** Our own back office, the five platform
   engines and the client installs are built and run in-house. *Evidence: this
   codebase; the live installs.*
2. **One partner, one invoice.** We build, host, support and bill for everything
   ourselves (CLAUDE.md: the site never refers work elsewhere). Where a specialist
   partner delivers part of the work, we contract and invoice it.
3. **No lock-in on what is yours.** The client's data, email and domain are theirs,
   and there is a buy-out on quote (`brand.ownership`). That is unusual in this market.
4. **POPIA-literate by default.** We act as operator, not responsible party; consent
   at the point of collection; no third-party trackers on our own site.

### What we must not claim yet

- Any Microsoft partner designation, certification, "Gold/Solutions Partner" status, or
  Azure/AWS partnership.
- Any SLA, response time or uptime percentage that we have not measured.
- "Enterprise-grade security" or "24/7 monitoring" while the production findings in
  `PRODUCTION_SAFETY_CHECKLIST.md` are open. Today production has public debug mode, a
  database port open to the LAN with default passwords, and off-site backups failing
  since 16/10.
- Client counts, years in business, projects delivered, or "trusted by" logos beyond
  `config/clients.php`.

## 1.2 The headline is conditional on capability

The headline names **AI** and **Cloud**. Each word must be backed by a confirmed row in
the capability matrix (`06_CAPABILITY_VERIFICATION.md`) before it reaches production.

| Capability confirmed | Production H1 |
|---|---|
| AI **and** Cloud | **Software Development, AI, Cloud & IT Solutions for Modern Businesses.** |
| Cloud only | Software Development, Cloud & IT Solutions for Modern Businesses. |
| Neither (today's evidence) | **Software Development, Automation & IT Solutions for Modern Businesses.** |

Staging shows the full headline for design review. Production renders the row the
matrix supports, through the same `availability` gate the prototype already uses.

## 1.3 Message hierarchy

| Level | Message | Where |
|---|---|---|
| Promise | Build, connect, secure and modernise your technology | Hero |
| Proof | Real software we built and run, labelled honestly | Hero visual, "Built by 7Hills" section |
| Breadth | Seven service lines, 50+ services | Services section and hub |
| Relevance | Your problem, your industry | Solutions and Industries |
| Low-risk entry | Ready-to-use platforms: pay nothing until live, cancel any time | Platforms section |
| Action | Tell us what you need, get a quote | Quote chooser, final CTA |

## 1.4 Tone

- Plain South African business English: "we build", "we look after", "on one invoice".
- Outcomes before technology: "approvals that do not wait in an inbox" before "Power Automate".
- Name technology where buyers search for it (SharePoint, Power BI, Microsoft 365), in
  titles and body copy, never as a wall of logos.
- No superlatives we cannot defend ("leading", "best", "award-winning").

## 1.5 Effect on existing invariants (CLAUDE.md)

| Invariant | Effect |
|---|---|
| "7Hills Digital sells monthly subscriptions" (Product section) | **Must be rewritten** after approval. The subscriptions remain, but as one line of seven |
| No upfront cost / no minimum term / one month's notice | **Unchanged for platforms.** It does **not** extend to project work. Project terms must be written (deposit, milestones); see the open decision in `07_LEAD_GENERATION.md` |
| Prices in the database | Unchanged. New service lines carry **no public prices** unless a confirmed fixed offer exists |
| The site never refers work elsewhere | Compatible: partner-delivered services are contracted and billed by us |
| Hardware procurement stays quiet | Unchanged. Hardware is not added as a service line |
| The five packages are a starting point, not a limit | Strengthened: the whole site now says so |
| The group name is kept off marketing pages | Unchanged |
