<?php

namespace Database\Seeders;

use App\Models\AddOn;
use Illuminate\Database\Seeder;

/**
 * Optional business modules, charged on top of the monthly package fee.
 *
 * Invoicing is priced flat because the cost of carrying it does not move with
 * the client. Enterprise email is per mailbox because that is what actually
 * varies. CRM and ERP are quoted — a two-person
 * agency and a fifty-branch operation are not the same job, and pretending
 * otherwise means either overcharging the small one or losing money on the
 * large one.
 */
class AddOnSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->addOns() as $data) {
            AddOn::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }

    private function addOns(): array
    {
        return [
            [
                'slug' => 'invoicing',
                'name' => 'Invoicing',
                'tagline' => 'Quote, invoice and chase what you are owed, without leaving your website.',
                'intro' => 'Raise a quote, turn it into an invoice when the customer says yes, and see at a glance who has not paid. It runs in the same back office as everything else, so a lead becomes a quote becomes an invoice without anybody retyping a name.',
                'price_monthly' => 109,
                'price_basis' => null,
                'icon_key' => 'invoice',
                'sort' => 1,
                'what_it_does' => [
                    'Quotes and invoices on your own letterhead, numbered automatically',
                    'Convert an accepted quote straight into an invoice',
                    'Outstanding, overdue and paid, on one screen',
                    'Automatic payment reminders, so you stop being the bad guy',
                    'Recurring invoices for clients you bill every month',
                    'VAT handled properly, with a report your bookkeeper will accept',
                    'Export to CSV for your accounting package',
                ],
            ],
            [
                'slug' => 'enterprise-email',
                'name' => 'Enterprise email',
                'tagline' => 'Mailboxes on your own domain, from R20 each.',
                'intro' => 'Email is not bundled into the packages, because most businesses already have it and the ones that do not want very different amounts of it. So it is priced honestly: per mailbox, per month, at the size you actually need.',
                // The "from" figure the card leads with.
                'price_monthly' => 20,
                'price_unit' => 'per mailbox, per month',
                'price_tiers' => [
                    ['label' => '5GB mailbox', 'price' => 20],
                    ['label' => '10GB mailbox', 'price' => 30],
                ],
                'price_basis' => null,
                'icon_key' => 'mail',
                'sort' => 2,
                'what_it_does' => [
                    'Your own domain — yourname@yourbusiness.co.za',
                    'Webmail, plus IMAP and SMTP for Outlook, Gmail or your phone',
                    'Shared calendars and contacts',
                    'Spam and virus filtering',
                    'We handle the SPF, DKIM and DMARC records, so your mail reaches the inbox',
                    'Migration from your current provider, at no charge',
                ],
            ],
            [
                'slug' => 'crm',
                'name' => 'CRM',
                'tagline' => 'Every customer, every conversation, in one place your whole team can see.',
                'intro' => 'A contact database that remembers what was said, by whom and when — so the answer to "who spoke to this client last?" is on the screen instead of in somebody\'s head.',
                'price_monthly' => null,
                'price_basis' => 'Quoted on your contact volume and how many staff need access',
                'icon_key' => 'people',
                'sort' => 3,
                'what_it_does' => [
                    'One record per customer, with the full contact history attached',
                    'Pipelines and stages that match how your business actually sells',
                    'Tasks and reminders, so nothing is left to somebody remembering',
                    'Notes, calls and emails logged against the customer',
                    'Reporting on where deals come from and where they stall',
                    'Per-user access, so staff see only what they should',
                ],
            ],
            [
                'slug' => 'erp',
                'name' => 'ERP',
                'tagline' => 'Stock, purchasing, jobs and people — the operational side of the business.',
                'intro' => 'For firms that have outgrown spreadsheets. We scope what you actually need rather than selling you a module you will never open, and build it onto the platform you are already running.',
                'price_monthly' => null,
                'price_basis' => 'Scoped and quoted on your transaction volume and the modules you need',
                'icon_key' => 'boxes',
                'sort' => 4,
                'what_it_does' => [
                    'Stock and inventory with reorder levels',
                    'Purchase orders and supplier records',
                    'Job cards, work orders and scheduling',
                    'Basic HR: staff records, leave and documents',
                    'Multi-branch and multi-warehouse where you need it',
                    'Reporting built around the numbers you actually run on',
                ],
            ],
        ];
    }
}
