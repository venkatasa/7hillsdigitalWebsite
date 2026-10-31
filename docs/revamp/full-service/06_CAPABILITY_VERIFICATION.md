# 6 · Capability Verification Matrix

**Prepared:** 30/10/2026 · **Status:** **Owner to complete.** Nothing in the
"unconfirmed" rows reaches production until the owner fills in the row and approves it.

## 6.1 How to read this

| Column | Meaning |
|---|---|
| **Confirmed?** | ✅ evidence on file · 🟡 partial: we have done it for ourselves, not for a client · ❌ no evidence on file · ⛔ blocked by an open production finding |
| **Delivery** | In-house · Partner (named) · Not decided |
| **Evidence on file** | Only what can be pointed to in this server's code, infrastructure or runbooks. **Owner experience from elsewhere (employment, past contracts) counts once the owner writes it down here.** It is not assumed |
| **Commercial offering** | Product (published price) · Fixed-scope offer · Quoted project · Retainer or monthly · Not defined |
| **Publication** | Pending · Approved (initials and date) · Declined |

**What I checked:** this repository; the live and staging stacks; the homelab runbooks
(`~/homelab-docs/*.md`); running containers. **What I could not check:** anyone's CV,
past employment, certifications, or work done elsewhere. Those rows say
"owner to declare".

## 6.2 Evidence found on this server

| Evidence | What it proves | What it does **not** prove |
|---|---|---|
| This Laravel 13 / Filament v4 codebase: public site, enquiry pipeline, pricing back office, 87 tests | Custom web application development; database design; testing discipline | Mobile native, Microsoft stack |
| Five platform engines and demo tenants | Reusable multi-tenant product build | Live client use beyond those listed |
| Excellent Real Estate: live website plus back office (`config/clients.php`) | Delivered client software, in production | — |
| **InvoiceShelf** deployed for Excellent RE (`invoice.excellentrealestate.co.za`) and for 7Hills, with versioned deployment standard v1.1 (`invoiceshelf.md`) | Hosting and supporting third-party business software for a client; branding; repeatable deployment | That we *wrote* invoicing software |
| Docker estate across several hosts, Proxmox VE, Proxmox Backup Server, Cloudflare tunnels, Tailscale, Nginx Proxy Manager | Server and infrastructure management; self-hosting | Azure/AWS; **working off-site backups** (failing since 16/10, finding F-backup) |
| Uptime Kuma and Grafana with Telegram alerts | Infrastructure monitoring of our own estate | A client-facing monitoring service or SLA |
| Mail DNS: SPF, DKIM, DMARC set and verified; `php artisan mail:check` | Email and DNS configuration | Microsoft 365 / Exchange administration |
| Self-hosted AI stack: Open WebUI, LiteLLM, Ollama, **n8n** (`AIHost.md`) | Hands-on AI tooling and workflow automation **internally** | Any AI or automation delivered to a client |
| POPIA-oriented privacy notice, operator/responsible-party split | Privacy awareness | Legal advice. The notice still awaits an attorney (open decision 2) |

**Counter-evidence that must be fixed before related services are sold**
(`PRODUCTION_SAFETY_CHECKLIST.md`):
- **F (debug):** production runs `APP_DEBUG=true`, `APP_ENV=local`. → blocks security services.
- **F (MySQL):** database port open on the LAN with default credentials. → blocks security and hosting.
- **F (backups):** off-site PBS backups failing since 16/10. → blocks hosting, backup and DR.
- ~~**Stale claim:** the live homepage said Invoice Ninja runs for Excellent RE; it was
  retired on 25/08/2026.~~ **Fixed in production 31/10/2026** (now names InvoiceShelf; a test forbids the old name). → violates the Live-work rule "claim only what is running".

## 6.3 The matrix

`Owner` = the owner fills this cell.

### A. Software Development
| Service | Confirmed? | Delivery | Evidence on file | Commercial offering | Publication |
|---|---|---|---|---|---|
| Custom Software Development | ✅ | In-house | This codebase; Excellent RE back office | Quoted project + hosting/support retainer | Pending |
| Web Application Development | ✅ | In-house | Same | Quoted project | Pending |
| Enterprise Application Development | 🟡 | In-house | Same stack; no enterprise client on file | Quoted project | Pending: recommend "for growing businesses", not "enterprise" |
| Legacy System Modernisation | ❌ | Owner | None on file | Quoted project | Owner |
| API Development and Systems Integration | 🟡 | In-house | Internal APIs; InvoiceShelf deployed but not integrated with the back office | Quoted project | Pending |
| Database Development and Optimisation | 🟡 | In-house | MySQL schemas in our apps | Quoted project | Pending |
| Software Maintenance and Support | ✅ | In-house | We maintain our own installs | Monthly retainer: **price not defined** | Owner |
| Mobile Application Development | ❌ | Owner | Responsive web only | Quoted project | Owner |

### B. Microsoft Business Solutions
| Service | Confirmed? | Delivery | Evidence on file | Commercial offering | Publication |
|---|---|---|---|---|---|
| Microsoft 365 Consulting and Administration | ❌ | Owner | **None on file**; owner to declare | Monthly per user, or quoted | Owner |
| SharePoint Online Development | ❌ | Owner | Owner to declare | Quoted project | Owner |
| Power Apps Development | ❌ | Owner | Owner to declare | Quoted project | Owner |
| Power Automate Workflow Automation | ❌ | Owner | Owner to declare (n8n experience is adjacent, not equivalent) | Quoted project | Owner |
| Microsoft Teams Integrations | ❌ | Owner | Owner to declare | Quoted project | Owner |
| Power BI Dashboards | ❌ | Owner | Owner to declare | Quoted project | Owner |
| Document Management Systems | ❌ | Owner | Owner to declare | Quoted project | Owner |
| Microsoft 365 Migration and Governance | ❌ | Owner | Owner to declare | Fixed-scope offer per mailbox? | Owner |
| *Licence resale (CSP)* | ❌ | Owner | No CSP relationship on file | Monthly per licence | Owner: needs a CSP or indirect-reseller agreement before any licence pricing appears |

### C. AI, Data and Automation
| Service | Confirmed? | Delivery | Evidence on file | Commercial offering | Publication |
|---|---|---|---|---|---|
| Business Process Automation | 🟡 | In-house | n8n installed internally; automation inside our own apps | Quoted project | Pending |
| Workflow and Approval Automation | 🟡 | In-house | Same | Quoted project | Pending |
| AI Business Assistants and Chatbots | 🟡 | In-house | Internal AI stack only | Quoted project | Owner |
| AI Document Processing | ❌ | Owner | None delivered | Quoted project | Owner |
| AI Integration With Existing Systems | 🟡 | In-house | LiteLLM gateway internally | Quoted project | Owner |
| Intelligent Customer Service Solutions | ❌ | Owner | None | Quoted project | Owner |
| Reporting and Business Intelligence | ❌ | Owner | Grafana (infrastructure only) | Quoted project | Owner |
| Data Analytics and Dashboards | ❌ | Owner | Same | Quoted project | Owner |

**AI-specific condition:** before any AI service is published, decide and write down
what client data may be sent to which model provider (local Ollama vs external APIs),
and add it to the privacy notice. Clients will ask under POPIA s72 (cross-border transfer).

### D. Cloud and Infrastructure
| Service | Confirmed? | Delivery | Evidence on file | Commercial offering | Publication |
|---|---|---|---|---|---|
| Managed Cloud Hosting | ⛔ | In-house | Own infrastructure works; backups failing | Monthly | **Blocked until R1–R3**; then "managed hosting" wording |
| Server and Infrastructure Management | 🟡 | In-house | Own multi-host estate | Monthly retainer | Pending |
| Infrastructure Monitoring | 🟡 | In-house | Uptime Kuma and Grafana in use | Monthly | Pending |
| Backup and Disaster Recovery | ⛔ | In-house | PBS exists; off-site failing | Monthly | **Blocked until R1 is fixed and a restore is tested** |
| Cloud Consulting and Migration | ❌ | Owner | No hyperscaler evidence | Quoted | Owner |
| Azure and AWS Solutions | ❌ | Owner | None | Quoted | Owner |
| Cloud Cost Optimisation | ❌ | Owner | None | Quoted | Owner |

### E. Cybersecurity and IT Support
| Service | Confirmed? | Delivery | Evidence on file | Commercial offering | Publication |
|---|---|---|---|---|---|
| Managed IT Support | ❌ | Owner | No support desk, hours, staffing or tooling (RMM/PSA) on file | Monthly per user: **price not defined** | Owner |
| IT Infrastructure Support | 🟡 | In-house | Own estate | Monthly | Owner |
| Ongoing Maintenance and Monitoring | 🟡 | In-house | Own estate | Monthly | Pending |
| Microsoft 365 Security Configuration | ⛔❌ | Owner | None, and blocked by R2/R3 | Fixed-scope offer | Owner, after R2/R3 |
| Identity and Access Management | ❌ | Owner | None | Quoted | Owner |
| Endpoint Security | ❌ | Owner | None; needs a licensed EDR vendor | Monthly per device | Owner |
| Security Assessments | ⛔❌ | **Partner?** | None; **recommend a named, certified partner** | Fixed-scope | Owner |
| Vulnerability Management | ⛔❌ | Partner? | None | Monthly | Owner |

### F. Business Applications
| Service | Confirmed? | Delivery | Evidence on file | Commercial offering | Publication |
|---|---|---|---|---|---|
| CRM Systems | 🟡 | In-house | Enquiry pipeline in our own panel; CRM add-on "in development" | Quoted project; add-on later | Pending: never show the add-on as available |
| ERP Solutions and Integrations | ❌ | Owner | ERP add-on unbuilt | Quoted | Owner |
| Inventory and Asset Management | 🟡 | In-house | Showroom stock engine | Quoted | Pending |
| Procurement and Approval Systems | 🟡 | In-house | Approval logic buildable; none delivered | Quoted | Pending |
| HR and Employee Portals | 🟡 | In-house | Careers engine (applicants), not HR | Quoted | Pending |
| Booking and Scheduling Systems | 🟡 | In-house | Stay platform engine | Quoted, or the Stay platform | Pending |
| Accounting and Invoicing Integrations | ✅ (hosting) / 🟡 (integration) | In-house | InvoiceShelf deployed for a client; R109 add-on | Product (R109) + quoted integration | Pending |
| Customer Self-Service Portals | 🟡 | In-house | InvoiceShelf customer portal (third-party); our own panels | Quoted | Pending |

### G. Websites and Digital Platforms
| Service | Confirmed? | Delivery | Evidence on file | Commercial offering | Publication |
|---|---|---|---|---|---|
| Business Websites | ✅ | In-house | This site; Excellent RE | Product (DB prices) | Live today |
| Industry-Specific Subscription Platforms | ✅ | In-house | Five platforms, demos | Product (DB prices) | Live today |
| Website Modernisation | ✅ | In-house | Same skills | Quoted, or platform migration | Pending |
| Website Hosting and Maintenance | ⛔ | In-house | Running; backups failing | Included in the platform fee | Already implied by platforms: **R1 is urgent** |
| E-commerce Development | ❌ | Owner | Unspecified (open decision 8) | Not defined | Owner |

## 6.4 Organisational readiness (needed before "enterprise" wording)

| Item | Status | Needed for |
|---|---|---|
| B-BBEE certificate or sworn affidavit | Owner | Corporate and public procurement |
| CSD registration | Owner | Any public-sector quote |
| Professional indemnity and cyber insurance | Owner | Security, hosting, enterprise contracts |
| Standard project contract (deposit, milestones, IP, acceptance) | **Missing** | All project work: `/terms` covers subscriptions only |
| Support terms (hours, channels, response targets) | **Missing** | Managed IT, hosting, maintenance retainers |
| Data processing agreement template (POPIA operator) | **Missing** | Every hosted client |
| Named delivery partners (security, hyperscaler, CSP) | None | Partner-delivered rows |
| Team size and roles we can state publicly | Owner | About page, proposals |

## 6.5 How confirmation flows into the site

1. Owner fills a row: evidence, delivery, offer, and initials plus date under Publication.
2. That row's `availability` changes in `/admin/offerings` (Phase 2) or
   `config/revamp.php` (prototype).
3. A test fails if any production-visible service lacks a `publication_approved_at` value.
