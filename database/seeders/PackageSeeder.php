<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

/**
 * The five product lines.
 *
 * PRICES ARE PLACEHOLDERS. They are pitched at what a Gauteng SMB will carry
 * monthly, but they have not been signed off — every one of them is editable
 * at /admin without touching this file. Change them there before the site
 * goes anywhere near a real prospect.
 *
 * Storage: mail and site media are quoted separately on purpose. See the
 * 'storage' note in config/brand.php for why.
 */
class PackageSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->packages() as $data) {
            $tiers = $data['tiers'];
            unset($data['tiers']);

            $package = Package::updateOrCreate(['slug' => $data['slug']], $data);

            foreach ($tiers as $tier) {
                $package->tiers()->updateOrCreate(['name' => $tier['name']], $tier);
            }
        }
    }

    /** Standard 5GB and 10GB tiers for a line, at the given prices. */
    private function tiers(string $line, float $five, float $ten): array
    {
        return [
            [
                'name' => "{$line} 5",
                'mail_gb' => 5,
                'mailboxes' => 5,
                'gb_per_mailbox' => 1,
                'media_gb' => 10,
                'price_monthly' => $five,
                'is_default' => true,
                'sort' => 1,
            ],
            [
                'name' => "{$line} 10",
                'mail_gb' => 10,
                'mailboxes' => 10,
                'gb_per_mailbox' => 1,
                'media_gb' => 20,
                'price_monthly' => $ten,
                'is_default' => false,
                'sort' => 2,
            ],
        ];
    }

    private function packages(): array
    {
        return [
            [
                'slug' => 'listings',
                'name' => 'Listings',
                'vertical_label' => 'Estate agencies',
                'tagline' => 'Your stock online, your leads in one place.',
                'intro' => 'A property website you control yourself. Load a house once and it appears on your site, on your agents\' phones and in your enquiry inbox — with every buyer and tenant tracked from first enquiry to signed offer.',
                'icon_key' => 'home',
                'sort' => 1,
                'is_featured' => true,
                'what_you_get' => [
                    'Listings for sale and to rent, with photo galleries and virtual tour links',
                    'A back office where your agents publish and update stock themselves — no developer, no waiting',
                    'Enquiry forms that capture budget, financing and timeframe, so you know which leads are real',
                    'Full rental applications with ID, payslip and bank statement uploads, stored privately',
                    'A bond repayment calculator on every sale listing',
                    'WhatsApp buttons that open a chat quoting the property reference',
                    'A lead pipeline with a contact log, so nothing goes cold unnoticed',
                ],
                'built_for' => [
                    'Boutique and independent agencies',
                    'Rental portfolios and managing agents',
                    'Agencies leaving a portal-only presence',
                    'New agencies opening their doors',
                ],
                'tiers' => $this->tiers('Listings', 1495, 1795),
            ],
            [
                'slug' => 'showroom',
                'name' => 'Showroom',
                'vertical_label' => 'Car dealerships',
                'tagline' => 'Every car on the floor, online by lunchtime.',
                'intro' => 'A dealership website that does the work a salesperson shouldn\'t have to. Load your stock, take finance pre-qualifications and trade-in enquiries, and see every lead in one pipeline instead of scattered across three phones.',
                'icon_key' => 'car',
                'sort' => 2,
                'is_featured' => true,
                'what_you_get' => [
                    'Stock listings with make, model, derivative, year, mileage, fuel, transmission and full photo galleries',
                    'Reserved and sold states, so the floor and the website never disagree',
                    'A monthly instalment calculator with deposit, balloon, rate and term',
                    'Finance pre-qualification forms that collect ID, payslips and bank statements securely',
                    'Trade-in enquiries where the customer uploads photos of their current car',
                    'Test drive bookings straight into your diary',
                    'WhatsApp buttons that open a chat quoting the stock number',
                ],
                'built_for' => [
                    'Independent and pre-owned dealerships',
                    'Multi-franchise floors carrying mixed stock',
                    'Dealers selling off Facebook Marketplace today',
                    'Finance and insurance desks that need clean lead data',
                ],
                'tiers' => $this->tiers('Showroom', 1495, 1795),
            ],
            [
                'slug' => 'careers',
                'name' => 'Careers',
                'vertical_label' => 'Recruitment and staffing agencies',
                'tagline' => 'Post the vacancy. Collect the CVs. Stop drowning in email.',
                'intro' => 'A recruitment website with the applicant tracking built in. Vacancies go up in minutes, applications arrive with the CV already attached and the screening questions already answered, and your consultants work one shortlist instead of an inbox.',
                'icon_key' => 'briefcase',
                'sort' => 3,
                'what_you_get' => [
                    'Vacancy listings with sector, location, salary band and contract type',
                    'Applications with CV, ID and qualification uploads, stored privately',
                    'Screening questions per vacancy, answered before the CV reaches you',
                    'A candidate pipeline: new, screened, shortlisted, submitted, placed',
                    'Closed and filled vacancies drop off the site automatically',
                    'A searchable candidate database that outlives the vacancy',
                    'Client-facing pages for the employer side of your business',
                ],
                'built_for' => [
                    'Generalist and specialist recruitment agencies',
                    'Labour brokers and temporary employment services',
                    'In-house talent teams hiring at volume',
                    'Agencies still collecting CVs on a Gmail address',
                ],
                'tiers' => $this->tiers('Careers', 1295, 1595),
            ],
            [
                'slug' => 'stay',
                'name' => 'Stay',
                'vertical_label' => 'Guesthouses, lodges and B&Bs',
                'tagline' => 'Your own booking page, not somebody else\'s commission.',
                'intro' => 'A website that takes booking enquiries directly, so the guests who already know your name stop arriving through a platform that charges you fifteen percent for the privilege.',
                'icon_key' => 'bed',
                'sort' => 4,
                'what_you_get' => [
                    'Room and unit listings with galleries, sleeps, amenities and seasonal rates',
                    'Direct booking enquiries with dates, guest numbers and special requests',
                    'An enquiry pipeline with a contact log, so no request sits unanswered',
                    'Rate tables per season, updated by you without a developer',
                    'Facilities, things-to-do and directions pages',
                    'Guest review display and WhatsApp enquiry buttons',
                    'House rules, check-in times and policy pages that cut repeat questions',
                ],
                'built_for' => [
                    'Guesthouses and boutique lodges',
                    'B&Bs and self-catering units',
                    'Game and country lodges',
                    'Owners paying platform commission on repeat guests',
                ],
                'tiers' => $this->tiers('Stay', 1095, 1395),
            ],
            [
                'slug' => 'business',
                'name' => 'Business',
                'vertical_label' => 'Everyone else',
                'tagline' => 'The whole engine, pointed at whatever you do.',
                'intro' => 'The same platform behind our industry packages, with the labels set to suit your business. If you have things to show, enquiries to capture and documents to collect, Business fits — whether those things are legal services, school programmes, funeral plans or plant on hire.',
                'icon_key' => 'globe',
                'sort' => 5,
                'what_you_get' => [
                    'A catalogue of whatever you sell — services, programmes, equipment, plans — with photos and detail pages',
                    'Enquiry forms that route to the right person and land in a tracked pipeline',
                    'Secure document intake for anything a client must send you',
                    'A news and notices section for updates, newsletters and announcements',
                    'Team and branch pages',
                    'Downloadable documents: company profile, B-BBEE certificate, rate cards, forms',
                    'The same back office our industry packages run on',
                ],
                'built_for' => [
                    'Attorneys, accountants and consultancies',
                    'Schools, crèches and private colleges',
                    'Funeral homes and policy brokers',
                    'Plant, tool and equipment hire',
                    'Clinics and allied health practices',
                    'Contractors and professional services bidding for tenders',
                ],
                'tiers' => $this->tiers('Business', 895, 1195),
            ],
        ];
    }
}
