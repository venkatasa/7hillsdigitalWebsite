<?php

/*
|--------------------------------------------------------------------------
| Revamp — switches and interim content
|--------------------------------------------------------------------------
|
| The v3 homepage renders only where REVAMP_HOME_V2=true (staging). Everywhere
| else — production, the test suite by default — the current homepage is served
| unchanged.
|
| The catalogue below is interim content. Phase 2 moves it into the database
| (an Offering model, edited in /admin). Until then it lives here, and the
| publication rule (docs/revamp/full-service/06_CAPABILITY_VERIFICATION.md)
| applies exactly as it will there:
|
|  - `built_to_order` / `available`  evidence on file; rendered everywhere.
|  - `capability_unconfirmed`        no evidence on file; NEVER rendered in
|                                     production.
|  - `blocked`                       evidence exists, but an open production
|                                     finding (R1–R3) makes selling it unsafe;
|                                     NEVER rendered in production.
|
| On staging, REVAMP_PREVIEW_UNCONFIRMED=true shows the last two with a dashed
| "Preview only" marker so the owner can judge the design — nothing more.
| Changing a status here is a publication decision: it needs the owner's
| approval recorded in the capability matrix first.
|
*/

return [

    'home_v2' => (bool) env('REVAMP_HOME_V2', false),

    'preview_unconfirmed' => (bool) env('REVAMP_PREVIEW_UNCONFIRMED', false),

    /*
    | Trust claims that depend on operational work. A claim whose precondition
    | is not yet true is hidden, not softened. See PRODUCTION_SAFETY_CHECKLIST.md.
    */
    'trust' => [
        'offsite_backups_verified' => false,   // R1
        'staff_mfa' => false,                  // R6
    ],

    /*
    | The headline names a capability only when the service that proves it is
    | publishable (01_COMMERCIAL_POSITIONING.md §1.2).
    */
    'headline' => [
        'ai' => 'ai-business-assistants',
        'cloud' => 'cloud-consulting-and-migration',
    ],

    /*
    | Seven service lines. `motif` names a drawing in <x-ui.motif>.
    */
    'lines' => [
        'software-development' => [
            'name' => 'Software Development',
            'short' => 'Software',
            'motif' => 'code',
            'blurb' => 'Applications built around how your business actually works, and supported by the people who wrote them.',
            'services' => [
                'custom-software-development' => ['Custom Software Development', 'built_to_order', 'Line-of-business systems built around your process, not a product you bend to fit.'],
                'web-application-development' => ['Web Application Development', 'built_to_order', 'Browser-based systems your staff and customers sign in to from any device.'],
                'api-development-and-integration' => ['API Development and Systems Integration', 'built_to_order', 'APIs and connectors so your systems pass data to each other instead of to people.'],
                'software-maintenance-and-support' => ['Software Maintenance and Support', 'built_to_order', 'Updates, fixes and small improvements to software already in use.'],
                'database-development' => ['Database Development and Optimisation', 'built_to_order', 'Schemas, queries and reports that stay fast as the data grows.'],
                'legacy-system-modernisation' => ['Legacy System Modernisation', 'built_to_order', 'An ageing system rebuilt in stages, without a big-bang switchover.'],
                'enterprise-application-development' => ['Enterprise Application Development', 'built_to_order', 'Larger multi-team systems, delivered in phases with clear ownership.'],
                'mobile-application-development' => ['Mobile Application Development', 'capability_unconfirmed', 'Apps for field teams and customers, on iOS and Android.'],
            ],
        ],
        'microsoft-business-solutions' => [
            'name' => 'Microsoft Business Solutions',
            'short' => 'Microsoft 365',
            'motif' => 'grid',
            'blurb' => 'Microsoft 365, SharePoint and the Power Platform, set up around how your people work.',
            'services' => [
                'microsoft-365-consulting' => ['Microsoft 365 Consulting and Administration', 'capability_unconfirmed', 'Set-up, administration and tidy-up of Microsoft 365 for your organisation.'],
                'sharepoint-online-development' => ['SharePoint Online Development', 'capability_unconfirmed', 'Intranets and document libraries people actually use.'],
                'power-apps-development' => ['Power Apps Development', 'capability_unconfirmed', 'Simple internal apps built on the Microsoft licences you already pay for.'],
                'power-automate-workflows' => ['Power Automate Workflow Automation', 'capability_unconfirmed', 'Approvals and notifications that run inside Microsoft 365.'],
                'microsoft-teams-integrations' => ['Microsoft Teams Integrations', 'capability_unconfirmed', 'Alerts, approvals and data from your systems, inside Teams.'],
                'power-bi-dashboards' => ['Power BI Dashboards', 'capability_unconfirmed', 'Reports built from the systems you already run.'],
                'document-management-systems' => ['Document Management Systems', 'capability_unconfirmed', 'Version control, permissions and retention for business documents.'],
                'microsoft-365-migration-and-governance' => ['Microsoft 365 Migration and Governance', 'capability_unconfirmed', 'Moving mail and files into Microsoft 365, with rules for who can see what.'],
            ],
        ],
        'ai-data-and-automation' => [
            'name' => 'AI, Data & Automation',
            'short' => 'AI & Automation',
            'motif' => 'flow',
            'blurb' => 'Take the repeated work out of the week, and put the numbers that matter on one screen.',
            'services' => [
                'business-process-automation' => ['Business Process Automation', 'built_to_order', 'Repeated work taken off people\'s desks: reminders, hand-offs and paperwork.'],
                'workflow-and-approval-automation' => ['Workflow and Approval Automation', 'built_to_order', 'Requests routed to the right person, with a record of every decision.'],
                'ai-business-assistants' => ['AI Business Assistants and Chatbots', 'capability_unconfirmed', 'Assistants that answer from your own documents, with a person in the loop.'],
                'ai-document-processing' => ['AI Document Processing', 'capability_unconfirmed', 'Invoices, forms and contracts read, checked and filed.'],
                'ai-integration' => ['AI Integration With Existing Systems', 'capability_unconfirmed', 'AI steps added to the systems you already use, with clear limits on data.'],
                'intelligent-customer-service' => ['Intelligent Customer Service Solutions', 'capability_unconfirmed', 'First-line answers for customers, handed to staff when it matters.'],
                'reporting-and-business-intelligence' => ['Reporting and Business Intelligence', 'capability_unconfirmed', 'The numbers that run the business, on one screen.'],
                'data-analytics-and-dashboards' => ['Data Analytics and Dashboards', 'capability_unconfirmed', 'Trends and exceptions surfaced from your operational data.'],
            ],
        ],
        'cloud-and-infrastructure' => [
            'name' => 'Cloud & Infrastructure',
            'short' => 'Cloud',
            'motif' => 'stack',
            'blurb' => 'Servers, hosting and monitoring run properly, so nothing depends on luck.',
            'services' => [
                'managed-hosting' => ['Managed Hosting', 'blocked', 'Your applications hosted, updated and watched by the team that built them.'],
                'server-and-infrastructure-management' => ['Server and Infrastructure Management', 'built_to_order', 'Servers, containers and networks kept patched, documented and running.'],
                'infrastructure-monitoring' => ['Infrastructure Monitoring', 'built_to_order', 'Uptime and health checks that alert someone before customers notice.'],
                'backup-and-disaster-recovery' => ['Backup and Disaster Recovery', 'blocked', 'Off-site copies, and restores that have actually been tested.'],
                'cloud-consulting-and-migration' => ['Cloud Consulting and Migration', 'capability_unconfirmed', 'Deciding what belongs in the cloud, then moving it safely.'],
                'azure-and-aws-solutions' => ['Azure and AWS Solutions', 'capability_unconfirmed', 'Workloads designed for, and run on, the major cloud platforms.'],
                'cloud-cost-optimisation' => ['Cloud Cost Optimisation', 'capability_unconfirmed', 'Finding and removing what you pay for and do not use.'],
            ],
        ],
        'it-support-and-security' => [
            'name' => 'Cybersecurity & IT Support',
            'short' => 'Security & Support',
            'motif' => 'shield',
            'blurb' => 'Support your staff can call, and the basics of security done properly.',
            'services' => [
                'managed-it-support' => ['Managed IT Support', 'capability_unconfirmed', 'One number to call for your staff, devices and accounts.'],
                'it-infrastructure-support' => ['IT Infrastructure Support', 'capability_unconfirmed', 'Networks, servers and office equipment looked after.'],
                'maintenance-and-monitoring' => ['Ongoing Maintenance and Monitoring', 'built_to_order', 'Scheduled updates and checks on the systems we run for you.'],
                'microsoft-365-security' => ['Microsoft 365 Security Configuration', 'blocked', 'Sign-in, sharing and mailbox settings hardened properly.'],
                'identity-and-access-management' => ['Identity and Access Management', 'capability_unconfirmed', 'The right people with the right access, and leavers removed on time.'],
                'endpoint-security' => ['Endpoint Security', 'capability_unconfirmed', 'Laptops and phones protected and kept up to date.'],
                'security-assessments' => ['Security Assessments', 'blocked', 'An independent look for the gaps, before someone else finds them.'],
                'vulnerability-management' => ['Vulnerability Management', 'blocked', 'Known weaknesses found, ranked and fixed on a schedule.'],
            ],
        ],
        'business-applications' => [
            'name' => 'Business Applications',
            'short' => 'Business Apps',
            'motif' => 'pipeline',
            'blurb' => 'The systems a business runs on: customers, bookings, stock, approvals and billing.',
            'services' => [
                'crm-systems' => ['CRM Systems', 'built_to_order', 'Every lead and customer in one pipeline the whole team can see.'],
                'accounting-and-invoicing-integrations' => ['Accounting and Invoicing Integrations', 'available', 'Quotes, invoices and payments, connected to the rest of the business.'],
                'booking-and-scheduling-systems' => ['Booking and Scheduling Systems', 'built_to_order', 'Customers book themselves into a calendar you control.'],
                'customer-self-service-portals' => ['Customer Self-Service Portals', 'built_to_order', 'A secure login where customers see their own documents and status.'],
                'hr-and-employee-portals' => ['HR and Employee Portals', 'built_to_order', 'Leave, documents and requests in one place for staff.'],
                'procurement-and-approval-systems' => ['Procurement and Approval Systems', 'built_to_order', 'Purchase requests approved and tracked, not chased by email.'],
                'inventory-and-asset-management' => ['Inventory and Asset Management', 'built_to_order', 'Stock and equipment tracked, with history for every item.'],
                'erp-solutions-and-integrations' => ['ERP Solutions and Integrations', 'capability_unconfirmed', 'Finance, stock and operations in one connected system.'],
            ],
        ],
        'websites-and-platforms' => [
            'name' => 'Websites & Digital Platforms',
            'short' => 'Websites',
            'motif' => 'browser',
            'blurb' => 'Business websites and ready-to-use industry platforms, hosted and supported.',
            'services' => [
                'industry-platforms' => ['Industry-Specific Subscription Platforms', 'available', 'Ready-to-use platforms for five industries, at a flat monthly fee.'],
                'business-websites' => ['Business Websites', 'available', 'A professional website with a back office you update yourself.'],
                'website-hosting-and-maintenance' => ['Website Hosting and Maintenance', 'available', 'Hosting, updates and support included in the platform fee.'],
                'website-modernisation' => ['Website Modernisation', 'built_to_order', 'An old site rebuilt to be fast, secure and easy to update.'],
                'ecommerce-development' => ['E-commerce Development', 'capability_unconfirmed', 'An online shop connected to your stock and payments.'],
            ],
        ],
    ],

    /*
    | The homepage grid always shows eight. A featured service that may not be
    | published gives its slot to the next fallback, in order, so the grid never
    | shows a gap and never shows an unpublishable service in production.
    */
    'featured' => [
        'custom-software-development' => ['summary' => 'Line-of-business systems, back offices and portals, built around your process.', 'motif' => 'code'],
        'api-development-and-integration' => ['summary' => 'Your website, back office and accounting passing data instead of people retyping it.', 'motif' => 'nodes'],
        'business-process-automation' => ['summary' => 'Reminders, hand-offs and approvals that run themselves, with a record of each.', 'motif' => 'flow'],
        'crm-systems' => ['summary' => 'Every lead and customer in one pipeline the whole team can see.', 'motif' => 'pipeline'],
        'microsoft-365-consulting' => ['summary' => 'SharePoint, Teams and Power Automate, organised around how you work.', 'motif' => 'grid'],
        'managed-it-support' => ['summary' => 'One number to call, and someone who already knows your setup.', 'motif' => 'pulse'],
        'ai-document-processing' => ['summary' => 'Invoices, forms and contracts read and filed, with a person checking.', 'motif' => 'scan'],
        'managed-hosting' => ['summary' => 'Hosted, updated and watched by the team that built it.', 'motif' => 'stack'],
    ],
    'fallbacks' => [
        'web-application-development' => ['summary' => 'Browser-based systems your staff and customers sign in to from anywhere.', 'motif' => 'browser'],
        'accounting-and-invoicing-integrations' => ['summary' => 'Quotes, invoices and recurring billing that go out without anyone remembering.', 'motif' => 'invoice'],
        'booking-and-scheduling-systems' => ['summary' => 'Customers book themselves, day or night, into a calendar you control.', 'motif' => 'calendar'],
        'website-modernisation' => ['summary' => 'An old site rebuilt to be fast, secure and easy for you to update.', 'motif' => 'refresh'],
    ],

    /*
    | Business problems, keyed by the slug of their page under /solutions:
    | [problem, outcome, line, gate]. A solution with a `gate` exists only where
    | that catalogue service may render, so "IT that just works" is a 404 in
    | production until managed IT support is confirmed.
    */
    'solutions' => [
        'replace-spreadsheets' => ['Work lives in spreadsheets', 'Replace them with one system everyone uses', 'software-development'],
        'never-lose-a-lead' => ['Leads go cold in someone\'s inbox', 'Put every enquiry in a pipeline the team can see', 'business-applications'],
        'get-paid-on-time' => ['Invoices go out late', 'Bill on time, and see at a glance who has paid', 'business-applications'],
        'automate-approvals' => ['Approvals wait on paper', 'Route them to the right person, with a record', 'ai-data-and-automation'],
        'connect-your-systems' => ['Your systems don\'t talk', 'Connect them, so data is entered once', 'software-development'],
        'it-that-just-works' => ['IT is nobody\'s job', 'Hand it to a team that answers', 'it-support-and-security', 'managed-it-support'],
    ],

    /*
    | Quote chooser. `requires` names the service that must be publishable for
    | the chip to render. Phase 4 replaces /contact with /quote.
    */
    'quote_types' => [
        'custom-development' => ['Custom software', 'A system, portal or app built for you', 'custom-software-development'],
        'microsoft-365' => ['Microsoft 365 & SharePoint', 'Set up, migrate, automate or secure', 'microsoft-365-consulting'],
        'ai-automation' => ['AI & automation', 'Take a repeated process off someone\'s desk', 'business-process-automation'],
        'managed-it' => ['Managed IT support', 'A monthly contract for your staff and devices', 'managed-it-support'],
        'cloud-infrastructure' => ['Cloud & infrastructure', 'Servers, hosting and monitoring', 'server-and-infrastructure-management'],
        'security-assessment' => ['Security assessment', 'Find the gaps before someone else does', 'security-assessments'],
    ],

    /*
    | Industries, keyed by the slug of their page under /industries. `package` and
    | `demo` link the existing platforms and demo tenants; an industry without a
    | platform is served by custom work.
    */
    'industries' => [
        'automotive' => [
            'name' => 'Automotive dealerships',
            'short' => 'Automotive',
            'package' => 'showroom',
            'demo' => 'auto',
            'pains' => ['Stock online the hour it arrives', 'Every finance enquiry in one pipeline', 'Trade-in requests with photos attached'],
        ],
        'property' => [
            'name' => 'Property & real estate',
            'short' => 'Property',
            'package' => 'listings',
            'demo' => 'realty',
            'pains' => ['Listings your agents publish themselves', 'Buyer enquiries that never sit in a phone', 'Rental applications with documents, in one place'],
        ],
        'recruitment' => [
            'name' => 'Recruitment & staffing',
            'short' => 'Recruitment',
            'package' => 'careers',
            'demo' => 'careers',
            'pains' => ['Vacancies live in minutes', 'CVs captured, not lost in an inbox', 'Applicants tracked from first contact to placement'],
        ],
        'hospitality' => [
            'name' => 'Hospitality',
            'short' => 'Hospitality',
            'package' => 'stay',
            'demo' => 'stay',
            'pains' => ['Direct bookings without the commission', 'Rooms, rates and seasons you control', 'Guest enquiries answered from one inbox'],
        ],
        'professional-services' => [
            'name' => 'Professional services',
            'short' => 'Professional',
            'package' => 'business',
            'demo' => null,
            'pains' => ['Client intake that is not an email thread', 'Documents and approvals with an audit trail', 'Billing that goes out on time'],
        ],
        'corporate' => [
            'name' => 'Corporate operations',
            'short' => 'Corporate',
            'package' => null,
            'demo' => null,
            'pains' => ['Staff portals and self-service', 'Approval workflows that replace paper', 'Reporting across systems that do not talk'],
        ],
    ],

];
