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
                'hero_badge' => 'Transparent Pricing. Zero Brokerage Leasing.',
                'hero_title' => 'Pay Nothing Until We Place a <span class="font-display font-medium text-brand-cyan">Tenant</span><span class="text-[#00AEEF]">.</span>',
                'hero_subtitle' => 'Housing365 India is a unified property platform for leasing, verification, care, and management. There are no recurring monthly retainers or hidden fees. Standard owner listings are ₹0, with transparent success fees and complete verification.',
                'hero_cta_text' => 'Talk to an Expert',
                'hero_cta_link' => '/contact?type=owner',
                'meta_title' => 'Transparent Pricing & Plans | Housing365 India',
                'meta_description' => 'Transparent property leasing and management pricing in India. Performance-based success fee with no monthly retainers. ₹0 standard owner listings.',
                'sections_data' => [
                    'schedule_items' => [
                        ['label' => 'Standard leasing success fee', 'detail' => 'Apartment, villa, builder floor, or independent house. Charged once, on signed e-stamped lease with biometric police verification.', 'value' => 'Transparent Success Fee', 'emphasis' => true],
                        ['label' => 'Standard Owner Listing', 'detail' => 'Zero listing fee, verified owner badge, and multi-channel syndication across India.', 'value' => '₹0 Free', 'emphasis' => true],
                        ['label' => 'Monthly retainer or hidden percentage', 'detail' => 'We are an end-to-end property platform. There is no recurring fee.', 'value' => 'Not charged', 'emphasis' => false],
                        ['label' => 'Tenant Replacement Guarantee', 'detail' => 'If a placed tenant breaks the lease early, we re-lease under our standard placement guarantee.', 'value' => 'Included', 'emphasis' => false],
                        ['label' => 'Rent Protection (optional add-on)', 'detail' => 'Guaranteed on-time rental disbursement protection. Owner opts in.', 'value' => 'Optional add-on', 'emphasis' => false],
                        ['label' => 'Property Care & Maintenance', 'detail' => 'Deep cleaning, painting, plumbing, electrical, and pre-occupancy inspection.', 'value' => 'Pre-approved quotes', 'emphasis' => false],
                        ['label' => 'Institutional / Builder Portfolio', 'detail' => 'Multi-unit bulk leasing programs, society onboarding, dedicated relationship manager.', 'value' => 'Custom RFP', 'emphasis' => false],
                        ['label' => 'Engagement exit', 'detail' => 'Either party. No exit fee, no penalty on a placement already executed.', 'value' => '30-day notice', 'emphasis' => false],
                    ],
                    'how_we_charge' => [
                        [
                            'tag' => 'Transparent Onboarding',
                            'highlight' => '₹0',
                            'title' => 'Zero Upfront Owner Listing.',
                            'imageSrc' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=1600&q=80&auto=format&fit=crop',
                            'body' => 'High-resolution photography, syndication across India, Aadhaar title verification, and document setup. No recurring monthly retainers or markups.',
                        ],
                        [
                            'tag' => 'One success fee',
                            'highlight' => 'Once',
                            'title' => 'Paid only when a verified lease is executed.',
                            'imageSrc' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=1600&q=80',
                            'body' => 'When a qualified tenant signs a legally vetted e-stamped agreement, you pay our nominal success fee. We are a direct property platform: zero brokerage for direct tenants and no hidden percentages.',
                        ],
                        [
                            'tag' => 'No surprises',
                            'highlight' => 'Nothing',
                            'title' => 'And nothing in between.',
                            'imageSrc' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=1600&q=80&auto=format&fit=crop',
                            'body' => 'No vendor markups. No hidden surcharges. Transparent vendor estimates for repairs and deep cleaning. Everything clearly itemized.',
                        ],
                    ],
                    'guarantees' => [
                        [
                            'num' => '01',
                            'title' => 'Tenant Replacement Guarantee.',
                            'body' => 'If a placed tenant vacates prematurely within the policy period, we assist in re-leasing at no additional platform fee.',
                        ],
                        [
                            'num' => '02',
                            'title' => 'No-Placement, No-Fee Promise.',
                            'body' => 'You owe nothing if a lease is not executed. There is no kill-fee or hidden subscription charges.',
                        ],
                        [
                            'num' => '03',
                            'title' => 'Transparent Exit.',
                            'body' => 'Engagements end on 30-day notice without exit penalties or lock-in restrictions.',
                        ],
                    ],
                ],
                'is_published' => true,
            ],
            [
                'slug' => 'owners',
                'title' => 'Landlords & Property Owners',
                'hero_badge' => 'FOR PROPERTY OWNERS & NRI LANDLORDS',
                'hero_title' => 'Stress-Free Ownership. <br class="hidden sm:inline"><span class="font-display font-medium text-brand-cyan">Maximized Rental Yield.</span>',
                'hero_subtitle' => 'Full-service leasing execution built for Indian property owners and NRI landlords. We handle strategic pricing, verified marketing, physical showings, Aadhaar & police background checks, and digital e-stamped agreements.',
                'hero_cta_text' => 'List My Property',
                'hero_cta_link' => '/contact?type=owner',
                'meta_title' => 'Full-Service Property Leasing for Owners & NRIs | Housing365 India',
                'meta_description' => 'Strategic pricing, verified marketing, Aadhaar verification, biometric police checks, and legal e-stamped agreements across Indian cities. ₹0 listing fee.',
                'sections_data' => [
                    'metrics' => [
                        ['val' => 'Zero Upfront Fees', 'icon' => 'circle-check'],
                        ['val' => '14-Day Avg Placement', 'icon' => 'circle-check'],
                        ['val' => '100% Verified Owners', 'icon' => 'circle-check'],
                    ],
                ],
                'is_published' => true,
            ],
            [
                'slug' => 'tenants',
                'title' => 'Tenants & Renters',
                'hero_badge' => 'VERIFIED HOMES ACROSS INDIA',
                'hero_title' => 'Find a Home You Love. <br class="hidden sm:inline"><span class="font-display font-medium text-brand-cyan">Rent with Confidence.</span>',
                'hero_subtitle' => 'Browse 100% verified flats, independent houses, and villas across Bengaluru, Mumbai, Delhi NCR, Pune, Hyderabad, and other top Indian cities. Zero brokerage and legal e-stamped agreements.',
                'hero_cta_text' => 'Browse Available Units',
                'hero_cta_link' => '/properties',
                'meta_title' => 'Find Verified Rental Homes in India | Zero Brokerage | Housing365 India',
                'meta_description' => 'Browse 100% verified rental flats and homes across India with zero brokerage, transparent leases, and dedicated support.',
                'sections_data' => [
                    'metrics' => [
                        ['val' => '100% Verified Listings', 'icon' => 'circle-check'],
                        ['val' => 'Zero Brokerage', 'icon' => 'circle-check'],
                        ['val' => 'Digital E-Stamped Leases', 'icon' => 'circle-check'],
                    ],
                ],
                'is_published' => true,
            ],
            [
                'slug' => 'property-managers',
                'title' => 'Property Managers Partner Program',
                'hero_badge' => 'PARTNER WITH HOUSING365 INDIA',
                'hero_title' => 'Scale Your Portfolio. <br class="hidden sm:inline"><span class="font-display font-medium text-brand-cyan">Outsource Tenant Placement.</span>',
                'hero_subtitle' => 'We partner with independent property managers, developers, and gated community societies across India to handle marketing, physical site showings, and verified tenant qualification.',
                'hero_cta_text' => 'Partner With Us',
                'hero_cta_link' => '/contact?type=pm',
                'meta_title' => 'Property Manager Partner Program | Housing365 India',
                'meta_description' => 'Partner with Housing365 India for high-volume leasing syndication, qualified applicant pipelines, and seamless property management operations.',
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
                'hero_badge' => 'CLOUD PROPERTY PLATFORM',
                'hero_title' => 'Real-Time Insights. <br class="hidden sm:inline"><span class="font-display font-medium text-brand-cyan">Modern Rental Management.</span>',
                'hero_subtitle' => 'Access your digital rent receipts, automated UPI/bank payouts, property maintenance tickets, and signed e-stamped agreements anytime from your phone or laptop.',
                'hero_cta_text' => 'Access Owner Portal',
                'hero_cta_link' => '/login',
                'meta_title' => 'Cloud Rental Portal & Technology | Housing365 India',
                'meta_description' => 'Manage your Indian rental properties 24/7 with real-time financial tracking, automated rent collection, digital lease vault, and instant maintenance tickets.',
                'sections_data' => [
                    'metrics' => [
                        ['val' => 'Real-Time Dashboard', 'icon' => 'circle-check'],
                        ['val' => 'Automated UPI Payouts', 'icon' => 'circle-check'],
                        ['val' => 'Bank-Grade Security', 'icon' => 'circle-check'],
                    ],
                ],
                'is_published' => true,
            ],
            [
                'slug' => 'home',
                'title' => 'Home',
                'hero_badge' => 'PAN-INDIA PROPERTY NETWORK',
                'hero_title' => 'Everything Property. <span class="font-display font-medium text-brand-cyan">One Platform.</span>',
                'hero_subtitle' => 'India\'s unified property platform for leasing, verification, property care, management, buy & sell. 100% verified owners, ₹0 standard owner listing fee, legal e-stamped agreements, and end-to-end property solutions across all Indian cities.',
                'hero_cta_text' => 'Explore Properties',
                'hero_cta_link' => '/properties',
                'meta_title' => 'Housing365 India | Everything Property. One Platform. Verified Owners, Zero Brokerage & Legal Leasing',
                'meta_description' => 'India\'s unified property platform for leasing, verification, property care, management, buy & sell. 100% verified owners, ₹0 standard owner listing fee, legal e-stamped agreements, and end-to-end property solutions across all Indian cities.',
                'sections_data' => [],
                'is_published' => true,
            ],
            [
                'slug' => 'about',
                'title' => 'About Us',
                'hero_badge' => 'ABOUT HOUSING365 INDIA',
                'hero_title' => 'Dedicated to Transforming <span class="font-display font-medium text-brand-cyan">Indian Real Estate.</span>',
                'hero_subtitle' => 'Founded to bring radical transparency, zero-brokerage direct leasing, and institutional property management efficiency to owners, tenants, and NRI investors across India.',
                'hero_cta_text' => 'Contact Our Team',
                'hero_cta_link' => '/contact',
                'meta_title' => 'About Housing365 India | Unified Property Platform',
                'meta_description' => 'Learn about Housing365 India\'s mission, executive leadership, and technology-driven operations across major Indian metropolitan cities.',
                'sections_data' => [],
                'is_published' => true,
            ],
            [
                'slug' => 'contact',
                'title' => 'Contact Us',
                'hero_badge' => 'WE ARE HERE FOR YOU',
                'hero_title' => 'Get in Touch with <span class="font-display font-medium text-brand-cyan">Housing365 India.</span>',
                'hero_subtitle' => 'Have questions about a property listing, lease agreement, or property care service in your city? Our team is available 7 days a week.',
                'hero_cta_text' => 'Direct Phone Support',
                'hero_cta_link' => 'tel:+918000365365',
                'meta_title' => 'Contact Housing365 India | Property Advisors & Care Specialists',
                'meta_description' => 'Connect with our advisory team for leasing consultations, tenant screening, property onboarding, and end-to-end management across Indian cities.',
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
    }
}
