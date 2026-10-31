# 3 · Prioritised Service Catalogue

**Prepared:** 30/10/2026 · **Status:** For approval.

The brief's catalogue has **52 services in 7 lines**. Every one appears on the site,
but not every one gets its own page. Thin, near-identical pages are what the brief
forbids and what Google demotes.

## 3.1 Two kinds of offering

| Kind | Meaning | How it is shown |
|---|---|---|
| **Product** (ready-made) | Exists today and can be bought at a published price | Price from the database, "Live demo", "Start now" |
| **Service** (commissioned) | We can be engaged to deliver it. Nothing exists until the client commissions it | "Request a quote". **No screenshot implies a finished product** |

Today's products are the 5 platforms × 2 tiers, the Invoicing add-on (R109) and
Enterprise email (R20/R30 per mailbox). CRM and ERP add-ons remain **"in
development"**, and the `/packages` page already says so.

## 3.2 Availability states (extends the prototype's model)

| State | Rendered in production? | Label |
|---|---|---|
| `available` | Yes | none, or "Ready to use" for products |
| `built_to_order` | Yes | "Built to order" |
| `partner_delivered` | Yes, once the partner is named in the matrix | "Delivered with a specialist partner" |
| `capability_unconfirmed` | **Never** (staging preview only) | dashed "Preview only" |

`partner_delivered` is new. It keeps the "never refer elsewhere" rule: we contract
and invoice; the partner delivers under us.

## 3.3 Page treatment

- **Hub** (1): `/services`, every line and every service, grouped. One screen per
  line on desktop, accordions on mobile.
- **Line pages** (7): `/services/{line}`. Every service in the line has its own
  section **with an anchor**, so a service without a dedicated page is still
  linkable (`/services/microsoft-business-solutions#power-bi-dashboards`).
- **Dedicated service pages** (wave 1: 8, wave 2: up to 8 more). Only where there is
  search demand **and** enough distinct substance for 900+ words of genuinely
  different content. Everything else stays a section on its line page.

## 3.4 The catalogue

**Tier** comes from `02_OPPORTUNITY_MATRIX.md`. **Status** is today's evidence; the
owner's answers in `06_CAPABILITY_VERIFICATION.md` change it. **Page:** ● dedicated
page · ○ section on the line page.

### A. Software Development: `/services/software-development`

| Service | Tier | Status today | Page |
|---|---|---|---|
| Custom Software Development | 1 | `built_to_order` | ● `/services/custom-software-development` |
| Web Application Development | 1 | `built_to_order` | ● `/services/web-application-development` |
| API Development and Systems Integration | 1 | `built_to_order` | ● `/services/api-development-and-integration` |
| Software Maintenance and Support | 1 | `built_to_order` | ○ (joins the hosting page as one offer) |
| Database Development and Optimisation | 2 | `built_to_order` (MySQL evidence) | ○ |
| Legacy System Modernisation | 2 | `built_to_order` | ○ (wave 2 page if enquiries arrive) |
| Enterprise Application Development | 2 | `built_to_order` | ○ (same work as custom; separate page would be thin) |
| Mobile Application Development | 3 | `capability_unconfirmed` (responsive web only today) | ○ hidden in production |

### B. Microsoft Business Solutions: `/services/microsoft-business-solutions`

**The whole line is `capability_unconfirmed` until the owner answers the matrix.** In
production it is hidden until then. It is the largest Tier 2 opportunity.

| Service | Tier | Status today | Page (once confirmed) |
|---|---|---|---|
| Microsoft 365 Consulting and Administration | 2 | unconfirmed | ● `/services/microsoft-365` |
| Microsoft 365 Migration and Governance | 2 | unconfirmed | ○ (section of `/services/microsoft-365`) |
| SharePoint Online Development | 2 | unconfirmed | ● `/services/sharepoint` (rising +19%) |
| Document Management Systems | 2 | unconfirmed | ○ (section of `/services/sharepoint`) |
| Power Automate Workflow Automation | 2 | unconfirmed | ○ (linked from the automation page) |
| Power Apps Development | 2 | unconfirmed | ○ |
| Microsoft Teams Integrations | 3 | unconfirmed | ○ |
| Power BI Dashboards | 3 | unconfirmed | ○ (linked from reporting) |

### C. AI, Data and Automation: `/services/ai-data-and-automation`

| Service | Tier | Status today | Page |
|---|---|---|---|
| Business Process Automation | 1 | `built_to_order` | ● `/services/business-process-automation` |
| Workflow and Approval Automation | 1 | `built_to_order` | ○ (section of the automation page; separate page would duplicate it) |
| AI Document Processing | 2 | unconfirmed | ● `/services/ai-document-processing` (wave 2) |
| AI Business Assistants and Chatbots | 2 | unconfirmed | ○ |
| AI Integration With Existing Systems | 2 | unconfirmed | ○ |
| Intelligent Customer Service Solutions | 3 | unconfirmed | ○ (merged with assistants) |
| Reporting and Business Intelligence | 3 | unconfirmed | ○ |
| Data Analytics and Dashboards | 3 | unconfirmed | ○ (merged with reporting) |

### D. Cloud and Infrastructure: `/services/cloud-and-infrastructure`

| Service | Tier | Status today | Page |
|---|---|---|---|
| Managed Cloud Hosting | 1* | `available` **after R1–R3** | ● `/services/managed-hosting` (with maintenance) |
| Server and Infrastructure Management | 2 | `built_to_order` (own estate) | ○ |
| Infrastructure Monitoring | 2 | `built_to_order` (Uptime Kuma, Grafana in use) | ○ |
| Backup and Disaster Recovery | 3 | **blocked by R1** | ○ hidden until R1 is done |
| Cloud Consulting and Migration | 3 | unconfirmed | ○ |
| Azure and AWS Solutions | 3 | unconfirmed | ○ |
| Cloud Cost Optimisation | 3 | unconfirmed | ○ |

**Wording rule:** our hosting runs on our own infrastructure behind Cloudflare, not on
a hyperscaler. Call it **"managed hosting"**. Use "cloud" only once there is
hyperscaler capability, or describe the infrastructure plainly.

### E. Cybersecurity and IT Support: `/services/it-support-and-security`

| Service | Tier | Status today | Page |
|---|---|---|---|
| Managed IT Support | 2 | unconfirmed (no support model, hours or staffing on file) | ● `/services/managed-it-support` |
| IT Infrastructure Support | 2 | unconfirmed | ○ |
| Ongoing Maintenance and Monitoring | 2 | `built_to_order` (own estate) | ○ |
| Microsoft 365 Security Configuration | 2 | unconfirmed, **blocked by R2/R3** | ● `/services/microsoft-365-security` (wave 2) |
| Identity and Access Management | 2 | unconfirmed | ○ |
| Endpoint Security | 3 | unconfirmed | ○ |
| Security Assessments | 3 | unconfirmed → `partner_delivered`? | ○ |
| Vulnerability Management | 3 | unconfirmed | ○ |

### F. Business Applications: `/services/business-applications`

| Service | Tier | Status today | Page |
|---|---|---|---|
| CRM Systems | 1 | `built_to_order` (enquiry pipeline in our own panel) | ● `/services/crm-systems` (highest commercial intent) |
| Accounting and Invoicing Integrations | 1 | `available` (InvoiceShelf deployed for a client) | ● `/services/accounting-and-invoicing` (+77% interest) |
| Booking and Scheduling Systems | 1 | `built_to_order` (Stay platform engine) | ● `/services/booking-systems` (wave 2) |
| Customer Self-Service Portals | 1 | `built_to_order` | ○ |
| HR and Employee Portals | 2 | `built_to_order` | ○ |
| Procurement and Approval Systems | 2 | `built_to_order` | ○ |
| Inventory and Asset Management | 2 | `built_to_order` (Showroom stock engine) | ○ |
| ERP Solutions and Integrations | 3 | unconfirmed (ERP add-on "in development") | ○ |

**Honesty note for CRM:** the CRM *add-on* is still in development. The CRM *service*
page sells commissioned CRM builds and integrations. It must link to the add-on's
true status and never show the add-on as available.

### G. Websites and Digital Platforms: `/services/websites-and-platforms`

| Service | Tier | Status today | Page |
|---|---|---|---|
| Industry-Specific Subscription Platforms | 1 | `available` (products) | Existing `/packages` and `/packages/{slug}`, **unchanged** |
| Business Websites | 1 | `available` | ○ links to `/packages/business` |
| Website Hosting and Maintenance | 1 | `available` | ○ links to `/services/managed-hosting` |
| Website Modernisation | 1 | `built_to_order` | ○ |
| E-commerce Development | 3 | **unspecified** (CLAUDE.md open decision 8) | ○ hidden until priced and scoped |

## 3.5 Totals

| | Count |
|---|---|
| Services in the catalogue | 52 (+ the existing products) |
| Rendered in production **today** | 23 (Software 7 · Automation 2 · Infrastructure 2 · Support 1 · Business apps 7 · Websites 4) |
| Hidden until the owner confirms or a blocker clears | 29 (managed hosting returns as soon as R1–R3 are done) |
| Dedicated pages, wave 1 | **8**: custom software, web applications, API and integration, process automation, CRM systems, accounting and invoicing, managed hosting (after R1–R3), plus managed IT support **if confirmed** |
| Dedicated pages, wave 2 (on confirmation) | Microsoft 365, SharePoint, M365 security, AI document processing, booking systems, managed IT support (if not in wave 1) |

## 3.6 Data model

The prototype holds services in `config/revamp.php`. Phase 2 of the earlier plan
already proposes an `Offering` model. Extend that proposal rather than adding a new one:

`offerings`: `line` (enum of 7), `slug`, `name`, `kind` (`product|service`),
`availability` (4 states), `tier`, `has_page` (bool), `summary`, `body` (rich text),
`technologies` (json), `use_cases` (json), `partner_note`, `sort`, `published_at`.

Edited in `/admin/offerings`. A test asserts that no `capability_unconfirmed` row
renders in production, the guarantee the prototype already enforces.
