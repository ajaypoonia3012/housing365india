<?php

namespace Database\Seeders;

use App\Models\CityLocation;
use App\Models\Faq;
use App\Models\Page;
use App\Models\Service;
use App\Services\ServiceCatalog;
use Illuminate\Database\Seeder;

class DynamicContentSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. SEED ALL 8 KEY PAGES (Home, About, Pricing, Contact, Owners, Tenants, Property Managers, Portal) ──
        $pages = [
            [
                'slug' => 'pricing',
                'title' => 'Pricing & Plans',
                'hero_badge' => 'Transparent Pricing. Success-Fee Leasing.',
                'hero_title' => 'Pay Nothing Until We Place a <span class="font-display font-medium text-brand-cyan">Tenant</span><span class="text-[#00AEEF]">.</span>',
                'hero_subtitle' => 'Housing365 is a full-service leasing and property management company. There are no recurring monthly retainers or ongoing management percentages. You pay our leasing success fee — typically equivalent to one month of rent — only when a qualified tenant signs the lease.',
                'hero_cta_text' => 'Talk to an Expert',
                'hero_cta_link' => '/contact?type=owner',
                'meta_title' => 'Leasing Platform Pricing Canada | Transparent Success-Fee Leasing | Housing365',
                'meta_description' => 'Transparent leasing platform pricing. Performance-based success fee with no monthly retainers. Pay our success fee only when a qualified tenant signs the lease. Custom pricing for institutional lease-up.',
                'sections_data' => [
                    'schedule_items' => [
                        ['label' => 'Standard leasing success fee', 'detail' => 'Single condo, townhouse, or single-family home. Charged once, on signed lease, to a qualified tenant we placed.', 'value' => 'One month rent equivalent', 'emphasis' => true],
                        ['label' => 'Setup, onboarding, photography, syndication', 'detail' => 'Account creation, document collection, professional photos, 40+ portal listing.', 'value' => '$149', 'emphasis' => true],
                        ['label' => 'Monthly retainer or management %', 'detail' => 'We are a leasing platform. There is no recurring fee.', 'value' => 'Not charged', 'emphasis' => false],
                        ['label' => 'Tenant Replacement Guarantee', 'detail' => 'If a tenant we placed breaks the lease in the first six months, we re-lease at no additional success fee.', 'value' => 'Included', 'emphasis' => false],
                        ['label' => 'Rent Protection (optional add-on)', 'detail' => 'Guaranteed rental income coverage underwritten through our insurance partner. Owner opts in.', 'value' => 'Quoted by partner', 'emphasis' => false],
                        ['label' => 'Paid advertising beyond standard syndication', 'detail' => 'Optional boosted listings, premium portal placements, or targeted social campaigns. Owner pre-approves.', 'value' => 'Pass-through at cost', 'emphasis' => false],
                        ['label' => 'Institutional lease-up (PMC, builder, developer)', 'detail' => 'Multi-unit lease-up programs, project-based engagements, custom service scope.', 'value' => 'Custom RFP', 'emphasis' => false],
                        ['label' => 'Engagement exit', 'detail' => 'Either party. No exit fee, no clawback on a placement already earned.', 'value' => '30-day notice', 'emphasis' => false],
                    ],
                    'how_we_charge' => [
                        [
                            'tag' => 'Transparent Setup',
                            'highlight' => '$149',
                            'title' => 'All-Inclusive Onboarding.',
                            'imageSrc' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=1600&q=80&auto=format&fit=crop',
                            'body' => 'Professional HDR photography, syndication across 40+ portals, screening, and documentation setup. No recurring monthly retainers or markups — our primary leasing placement fee is earned only when a qualified tenant is placed.',
                        ],
                        [
                            'tag' => 'One success fee',
                            'highlight' => 'Once',
                            'title' => 'Paid only when a lease is signed.',
                            'imageSrc' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=1600&q=80',
                            'body' => 'When a qualified tenant signs a lease we negotiated, you pay a single one-time success fee — typically equivalent to one month of contracted rent. Invoiced once, on placement. We are a leasing platform, not an ongoing manager: there is no monthly percentage, ever.',
                        ],
                        [
                            'tag' => 'No surprises',
                            'highlight' => 'Nothing',
                            'title' => 'And nothing in between.',
                            'imageSrc' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=1600&q=80&auto=format&fit=crop',
                            'body' => 'No vendor markups. No marketing surcharges. No listing-prep surcharges. No clawbacks. Optional add-ons (Rent Protection, paid advertising beyond standard syndication) are clearly named and owner-approved. Institutional lease-up engagements are quoted per RFP. Everything else is on this page.',
                        ],
                    ],
                    'guarantees' => [
                        [
                            'num' => '01',
                            'title' => 'Tenant Replacement Guarantee.',
                            'body' => 'If a tenant we placed breaks the lease in the first six months, we re-lease the unit at no additional success fee. We carry advertising, screening, and lease prep again. Our economics absorb the cost, not yours.',
                        ],
                        [
                            'num' => '02',
                            'title' => 'No-Placement, No-Fee Promise.',
                            'body' => 'You owe nothing if we fail to place a qualified tenant. There is no kill-fee, no time-and-materials clawback, no minimum spend. Our risk is real and contractual - spelled out in writing before you sign anything.',
                        ],
                        [
                            'num' => '03',
                            'title' => 'Transparent Exit.',
                            'body' => 'Engagements end on 30-day written notice from either party. There is no exit fee, no clawback on a placement already earned, and we hand off all applicant records and signed documents within seven days.',
                        ],
                    ],
                ],
                'is_published' => true,
            ],
            [
                'slug' => 'owners',
                'title' => 'Landlords & Property Owners',
                'hero_badge' => 'FOR PROPERTY OWNERS & LANDLORDS',
                'hero_title' => 'Stress-Free Ownership. <br class="hidden sm:inline"><span class="font-display font-medium text-brand-cyan">Maximized Rental Yield.</span>',
                'hero_subtitle' => 'Full-service leasing execution built for landlords who value their time. We handle pricing analysis, 4K marketing, showings, 20-point tenant qualification, and legal lease execution across Ontario and major US hubs.',
                'hero_cta_text' => 'List My Property',
                'hero_cta_link' => '/contact?type=owner',
                'meta_title' => 'Full-Service Leasing for Landlords & Property Owners | Housing 365',
                'meta_description' => 'Strategic pricing, professional 4K marketing, structured tenant screening, and legal lease execution for Canadian and US rental property owners. Zero upfront cost.',
                'sections_data' => [
                    'metrics' => [
                        ['val' => 'Zero Upfront Fees', 'icon' => 'circle-check'],
                        ['val' => '14-Day Avg Placement', 'icon' => 'circle-check'],
                        ['val' => '100% Lease Guarantee', 'icon' => 'circle-check'],
                    ],
                ],
                'is_published' => true,
            ],
            [
                'slug' => 'tenants',
                'title' => 'Tenants & Renters',
                'hero_badge' => 'CURATED RENTAL HOMES',
                'hero_title' => 'Find a Home You Love. <br class="hidden sm:inline"><span class="font-display font-medium text-brand-cyan">Rent with Confidence.</span>',
                'hero_subtitle' => 'Browse verified rental condos, townhomes, and single-family houses across Canada and premier US cities. Clear lease terms, transparent applications, and direct tenant support.',
                'hero_cta_text' => 'Browse Available Units',
                'hero_cta_link' => '/properties',
                'meta_title' => 'Find Verified Rental Homes | Tenant Placement | Housing 365',
                'meta_description' => 'Browse verified rental listings, complete transparent digital applications, and move into your next rental home with 24/7 dedicated support.',
                'sections_data' => [
                    'metrics' => [
                        ['val' => '100% Verified Listings', 'icon' => 'circle-check'],
                        ['val' => 'Fast 24-hr Processing', 'icon' => 'circle-check'],
                        ['val' => 'Standard Digital Leases', 'icon' => 'circle-check'],
                    ],
                ],
                'is_published' => true,
            ],
            [
                'slug' => 'property-managers',
                'title' => 'Property Managers Partner Program',
                'hero_badge' => 'PARTNER WITH HOUSING365',
                'hero_title' => 'Scale Your Portfolio. <br class="hidden sm:inline"><span class="font-display font-medium text-brand-cyan">Outsource Tenant Placement.</span>',
                'hero_subtitle' => 'We partner with independent property managers, condo boards, and multi-unit operators to handle marketing, showings, and tenant qualification so you can focus on core building operations.',
                'hero_cta_text' => 'Partner With Us',
                'hero_cta_link' => '/contact?type=pm',
                'meta_title' => 'Property Manager Partner Program | Housing 365',
                'meta_description' => 'Partner with Housing365 for high-volume leasing syndication, qualified applicant pipelines, and seamless tenant placement support.',
                'sections_data' => [
                    'metrics' => [
                        ['val' => 'Co-Brokered Leasing Fees', 'icon' => 'circle-check'],
                        ['val' => 'Dedicated Placement Desk', 'icon' => 'circle-check'],
                        ['val' => 'White-Label Available', 'icon' => 'circle-check'],
                    ],
                ],
                'is_published' => true,
            ],
            [
                'slug' => 'portal-and-technology',
                'title' => 'Owner & Tenant Technology Portal',
                'hero_badge' => 'CLOUD LEASING PLATFORM',
                'hero_title' => 'Real-Time Insights. <br class="hidden sm:inline"><span class="font-display font-medium text-brand-cyan">Modern Rental Management.</span>',
                'hero_subtitle' => 'Access your financial statements, rent distribution tracking, maintenance requests, and signed lease documents anytime, anywhere from any device.',
                'hero_cta_text' => 'Access Owner Portal',
                'hero_cta_link' => 'https://portal.revun.com/login',
                'meta_title' => 'Cloud Rental Portal & Technology | Housing 365',
                'meta_description' => 'Manage your rental property 24/7 with real-time financial tracking, automated rent collection, digital lease storage, and instant maintenance tickets.',
                'sections_data' => [
                    'metrics' => [
                        ['val' => 'Real-Time Dashboard', 'icon' => 'circle-check'],
                        ['val' => 'Automated Payouts', 'icon' => 'circle-check'],
                        ['val' => 'Bank-Grade Security', 'icon' => 'circle-check'],
                    ],
                ],
                'is_published' => true,
            ],
            [
                'slug' => 'home',
                'title' => 'Home',
                'hero_badge' => 'CANADA & UNITED STATES',
                'hero_title' => 'Stress-Free Property Ownership, <span class="font-display font-medium text-brand-cyan">Higher Returns.</span>',
                'hero_subtitle' => 'Full-service leasing and property management company delivering end-to-end leasing execution for landlords, property managers, and institutional rental operators across Canada and the United States.',
                'hero_cta_text' => 'Talk to an Expert',
                'hero_cta_link' => '/contact',
                'meta_title' => 'Housing365 | Stress-Free Property Ownership, Higher Returns across Canada and the US',
                'meta_description' => 'Full-service leasing and property management company delivering end-to-end leasing execution for landlords, property managers, and institutional rental operators across Canada and the United States.',
                'sections_data' => [],
                'is_published' => true,
            ],
            [
                'slug' => 'about',
                'title' => 'About Us',
                'hero_badge' => 'ABOUT HOUSING365',
                'hero_title' => 'Dedicated to Maximizing <span class="font-display font-medium text-brand-cyan">Property Potential.</span>',
                'hero_subtitle' => 'Founded to bring radical transparency and institutional leasing efficiency to residential rental property owners across North America.',
                'hero_cta_text' => 'Talk to an Expert',
                'hero_cta_link' => '/contact',
                'meta_title' => 'About Housing365 | North American Leasing Execution',
                'meta_description' => 'Learn about Housing365\'s mission, executive leadership, and technology-driven leasing operations across Canadian and US metropolitan markets.',
                'sections_data' => [],
                'is_published' => true,
            ],
            [
                'slug' => 'contact',
                'title' => 'Contact Us',
                'hero_badge' => 'WE ARE HERE FOR YOU',
                'hero_title' => 'Get in Touch with <span class="font-display font-medium text-brand-cyan">Housing365.</span>',
                'hero_subtitle' => 'Have questions about a property listing, lease agreement, or property management contract? Our dedicated specialists reply within 2 hours during business hours.',
                'hero_cta_text' => 'Direct Phone Support',
                'hero_cta_link' => 'tel:+16473356449',
                'meta_title' => 'Contact Housing365 | Leasing Specialists & Property Management',
                'meta_description' => 'Connect with our advisory team for leasing consultations, tenant screening, property onboarding, and institutional management.',
                'sections_data' => [],
                'is_published' => true,
            ],
        ];

        foreach ($pages as $p) {
            Page::updateOrCreate(['slug' => $p['slug']], $p);
        }

        // ── 2. SEED 9 PRICING FAQS INTO FAQS TABLE ──
        $pricingFaqs = [
            [
                'question' => 'What is included in the onboarding and setup fee?',
                'answer' => 'Our onboarding fee covers account creation, title verification, digital document preparation, professional photography, and multi-portal syndication across 40+ leading rental channels. Beyond this one-time setup, there are no monthly retainers or recurring management fees — our leasing placement fee is earned only when a qualified tenant signs the lease.',
                'category' => 'Pricing',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'question' => 'Do you charge a monthly management percentage?',
                'answer' => 'No. We are a leasing platform, not an ongoing property manager. There is no recurring monthly fee. Once we place a qualified tenant, the engagement is complete unless you specifically engage us for a separate scope.',
                'category' => 'Pricing',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'question' => 'When exactly do I pay the success fee?',
                'answer' => 'The success fee is invoiced after the tenant signs the lease and pays first month\'s rent plus deposit. It is typically deducted from that first rent payment so nothing comes out of your bank account.',
                'category' => 'Pricing',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'question' => 'What is included in the leasing success fee?',
                'answer' => 'All nine pillars of our service: professional photography and video, MLS plus 40+ portal syndication, applicant screening (credit, employment, income, references, prior tenancy), coordinated showings, lease drafting and e-signing, move-in coordination, key handover, condition reporting, and the Tenant Replacement Guarantee.',
                'category' => 'Pricing',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'question' => 'What costs extra?',
                'answer' => 'Three things, all optional and clearly named: Rent Protection (guaranteed rental income coverage, quoted by our insurance partner), paid advertising beyond standard syndication (boosted listings or premium portal placements, owner pre-approves), and Institutional Lease-Up engagements for property management companies, builders, or developers, which are scoped per RFP.',
                'category' => 'Pricing',
                'order' => 5,
                'is_active' => true,
            ],
            [
                'question' => 'What happens if my tenant breaks the lease early?',
                'answer' => 'If a tenant we placed breaks the lease within the first six months, we re-lease the property at no additional success fee under our Tenant Replacement Guarantee. We cover advertising, screening, and lease preparation again.',
                'category' => 'Pricing',
                'order' => 6,
                'is_active' => true,
            ],
            [
                'question' => 'Do you work with builders, developers, or property management companies?',
                'answer' => 'Yes. Institutional lease-up engagements are scoped per RFP - commonly a blend of monthly engagement allowance plus per-lease success fee, with custom service scope (dedicated agent team, onsite leasing office option, weekly absorption reporting). Contact us for an institutional quote.',
                'category' => 'Pricing',
                'order' => 7,
                'is_active' => true,
            ],
            [
                'question' => 'What are the cancellation terms?',
                'answer' => 'Either party can cancel with 30 days\' written notice. There is no exit fee, and no clawback on a placement already earned. We hand off all applicant records and signed documents within seven days.',
                'category' => 'Pricing',
                'order' => 8,
                'is_active' => true,
            ],
            [
                'question' => 'Is GST/HST included in the fees shown?',
                'answer' => 'No. All fees on this page are quoted before tax. Applicable GST/HST is added to your invoice and itemized separately so your accountant can claim input tax credits where eligible.',
                'category' => 'Pricing',
                'order' => 9,
                'is_active' => true,
            ],
        ];

        foreach ($pricingFaqs as $faq) {
            Faq::updateOrCreate([
                'question' => $faq['question'],
                'category' => 'Pricing',
            ], $faq);
        }

        // ── 3. SEED ALL 14 SUB-SERVICES FROM JSON INTO SERVICES TABLE ──
        $servicesPath = app_path('Services/services_data.json');
        if (file_exists($servicesPath)) {
            $servicesData = json_decode(file_get_contents($servicesPath), true) ?: [];
            $order = 1;
            foreach ($servicesData as $slug => $data) {
                Service::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'title' => $data['title'] ?? ucfirst(str_replace('-', ' ', $slug)),
                        'category' => in_array($slug, ['tenant-insurance', 'tenant-guarantor']) ? 'tenants' : (in_array($slug, ['institutional-lease-up']) ? 'institutional' : 'owners'),
                        'icon' => $data['icon'] ?? 'clipboard-check',
                        'short_description' => $data['metaDescription'] ?? ($data['heroLede'] ?? ''),
                        'full_description' => $data['solutionLede'] ?? ($data['heroLede'] ?? ''),
                        'hero_eyebrow' => $data['heroEyebrow'] ?? null,
                        'hero_headline' => $data['heroHeadline'] ?? null,
                        'hero_lede' => $data['heroLede'] ?? null,
                        'hero_backdrop' => ServiceCatalog::getHeroBackdrop($slug)['src'] ?? null,
                        'features' => $data['problemPoints'] ?? [],
                        'scope' => $data['scope'] ?? [],
                        'how_it_works' => $data['howItWorks'] ?? [],
                        'who_its_for' => $data['whoItsFor'] ?? [],
                        'pricing_info' => $data['pricingNote'] ?? 'Success-Fee Leasing',
                        'pricing_note' => $data['pricingNote'] ?? null,
                        'faq_items' => $data['faqItems'] ?? [],
                        'meta_description' => $data['metaDescription'] ?? null,
                        'order' => $order++,
                        'is_active' => true,
                    ]
                );
            }
        }

        // ── 4. SEED ALL 57 US CITIES INTO CITY_LOCATIONS TABLE ──
        $locationsPath = app_path('Services/us_locations_data.json');
        if (file_exists($locationsPath)) {
            $locationsData = json_decode(file_get_contents($locationsPath), true) ?: [];
            $cities = $locationsData['cities'] ?? [];
            foreach ($cities as $c) {
                CityLocation::updateOrCreate(
                    ['slug' => $c['slug'], 'country' => 'US'],
                    [
                        'name' => $c['title'] ?? ucfirst($c['slug']),
                        'province_or_state' => $c['stateSlug'] ?? 'US',
                        'country' => 'US',
                        'tier' => 1,
                        'avg_rent' => isset($c['medianRent']) ? '$'.number_format($c['medianRent']) : '$2,500',
                        'available_units_count' => rand(20, 95),
                        'description' => $c['marketNote'] ?? 'High demand residential rental market managed by Housing365.',
                        'image' => $c['heroImageUrl'] ?? 'https://images.unsplash.com/photo-1535498730771-e735b998cd64?w=800&auto=format&fit=crop&q=80',
                        'is_featured' => in_array($c['slug'], ['miami', 'new-york', 'austin', 'los-angeles', 'chicago', 'orlando']),
                    ]
                );
            }
        }
    }
}
