<?php

namespace App\Services;

use App\Models\Service;

class ServiceCatalog
{
    private static ?array $services = null;

    private static function load(): array
    {
        if (self::$services === null) {
            $path = __DIR__.'/services_data.json';
            if (file_exists($path)) {
                self::$services = json_decode(file_get_contents($path), true) ?: [];
            } else {
                self::$services = [];
            }

            if (! isset(self::$services['rental-agreement-registration'])) {
                self::$services['rental-agreement-registration'] = self::getRentalAgreementDefinition();
            }
        }

        return self::$services;
    }

    public static function getAll(): array
    {
        $services = self::load();
        try {
            $dbServices = Service::where('is_active', true)->orderBy('order')->get();
            foreach ($dbServices as $db) {
                $slug = $db->slug;
                $base = $services[$slug] ?? [];
                $services[$slug] = [
                    'slug' => $slug,
                    'title' => $db->title ?: ($base['title'] ?? ''),
                    'metaDescription' => $db->meta_description ?: ($base['metaDescription'] ?? $db->short_description ?? ''),
                    'heroEyebrow' => $db->hero_eyebrow ?: ($base['heroEyebrow'] ?? 'HOUSING365 SPECIALIZED LEASING'),
                    'heroHeadline' => $db->hero_headline ?: ($base['heroHeadline'] ?? $db->title),
                    'heroLede' => $db->hero_lede ?: ($base['heroLede'] ?? $db->short_description ?? ''),
                    'cta1Label' => $base['cta1Label'] ?? 'Talk to an Expert',
                    'cta2Label' => $base['cta2Label'] ?? 'Pricing & Terms',
                    'problemTitle' => $base['problemTitle'] ?? null,
                    'problemPoints' => $base['problemPoints'] ?? [],
                    'solutionTitle' => $base['solutionTitle'] ?? null,
                    'solutionLede' => $base['solutionLede'] ?? null,
                    'scope' => ! empty($db->scope) ? $db->scope : ($base['scope'] ?? []),
                    'howItWorks' => ! empty($db->how_it_works) ? $db->how_it_works : ($base['howItWorks'] ?? []),
                    'whoItsFor' => ! empty($db->who_its_for) ? $db->who_its_for : ($base['whoItsFor'] ?? []),
                    'pricingNote' => $db->pricing_note ?: ($base['pricingNote'] ?? null),
                    'faqItems' => ! empty($db->faq_items) ? $db->faq_items : ($base['faqItems'] ?? []),
                ];
            }
        } catch (\Throwable $e) {
        }

        return $services;
    }

    public static function find(string $slug): ?array
    {
        $services = self::load();
        $slug = match ($slug) {
            'rent-agreement', 'rental-agreement' => 'rental-agreement-registration',
            default => $slug,
        };
        $base = $services[$slug] ?? null;
        if (! $base && $slug === 'rental-agreement-registration') {
            $base = self::getRentalAgreementDefinition();
        }

        try {
            $db = Service::where('slug', $slug)->first();
            if ($db) {
                return [
                    'slug' => $db->slug,
                    'title' => $db->title ?: ($base['title'] ?? ''),
                    'metaDescription' => $db->meta_description ?: ($base['metaDescription'] ?? $db->short_description ?? ''),
                    'heroEyebrow' => $db->hero_eyebrow ?: ($base['heroEyebrow'] ?? 'HOUSING365 SPECIALIZED LEASING'),
                    'heroHeadline' => $db->hero_headline ?: ($base['heroHeadline'] ?? $db->title),
                    'heroLede' => $db->hero_lede ?: ($base['heroLede'] ?? $db->short_description ?? ''),
                    'cta1Label' => $base['cta1Label'] ?? 'Talk to an Expert',
                    'cta2Label' => $base['cta2Label'] ?? 'Pricing & Terms',
                    'problemTitle' => $base['problemTitle'] ?? null,
                    'problemPoints' => $base['problemPoints'] ?? [],
                    'solutionTitle' => $base['solutionTitle'] ?? null,
                    'solutionLede' => $base['solutionLede'] ?? null,
                    'scope' => ! empty($db->scope) ? $db->scope : ($base['scope'] ?? []),
                    'howItWorks' => ! empty($db->how_it_works) ? $db->how_it_works : ($base['howItWorks'] ?? []),
                    'whoItsFor' => ! empty($db->who_its_for) ? $db->who_its_for : ($base['whoItsFor'] ?? []),
                    'pricingNote' => $db->pricing_note ?: ($base['pricingNote'] ?? null),
                    'faqItems' => ! empty($db->faq_items) ? $db->faq_items : ($base['faqItems'] ?? []),
                ];
            }
        } catch (\Throwable $e) {
        }

        return $base;
    }

    public static function getRelated(array|string $slugOrList, int $limit = 3): array
    {
        $services = self::getAll();
        if (is_array($slugOrList)) {
            $out = [];
            foreach ($slugOrList as $slug) {
                if (isset($services[$slug])) {
                    $out[] = $services[$slug];
                }
            }

            return $out;
        }

        $out = [];
        foreach ($services as $key => $serv) {
            if ($key !== $slugOrList) {
                $out[] = $serv;
                if (count($out) >= $limit) {
                    break;
                }
            }
        }

        return $out;
    }

    public static function getHeroBackdrop(string $slug): array
    {
        try {
            $db = Service::where('slug', $slug)->first();
            if ($db && ! empty($db->hero_backdrop)) {
                return [
                    'url' => $db->hero_backdrop,
                    'alt' => ($db->title ?? 'Housing365 Service').' - Housing365',
                ];
            }
        } catch (\Throwable $e) {
        }
        $backdrops = [
            'tenant-placement' => [
                'url' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?auto=format&fit=crop&w=2400&q=80',
                'alt' => 'Keys handed to a new tenant on move-in day',
            ],
            'leasing-services' => [
                'url' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=2400&q=80',
                'alt' => 'Modern Indian apartment living room staged for showings',
            ],
            'rental-agreement-registration' => [
                'url' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=2400&q=80',
                'alt' => 'Legal e-stamped tenancy agreement signing and registration',
            ],
            'tenant-screening' => [
                'url' => 'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?auto=format&fit=crop&w=2400&q=80',
                'alt' => 'Housing365 screener reviewing an applicant file on a laptop',
            ],
            'rent-guarantee' => [
                'url' => 'https://images.unsplash.com/photo-1556761175-5973dc0f32e7?auto=format&fit=crop&w=2400&q=80',
                'alt' => 'Team reviewing rent guarantee agreements together',
            ],
            'tenant-insurance' => [
                'url' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=2400&q=80',
                'alt' => 'Tenant signing an insurance coverage agreement',
            ],
            'tenant-guarantor' => [
                'url' => 'https://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&w=2400&q=80',
                'alt' => 'Family members reviewing a guarantor tenancy agreement together',
            ],
            'rental-preparation' => [
                'url' => 'https://images.unsplash.com/photo-1505691938895-1758d7feb511?auto=format&fit=crop&w=2400&q=80',
                'alt' => 'Bright, prepped apartment unit ready for listing photography',
            ],
            'portal-and-technology' => [
                'url' => 'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?auto=format&fit=crop&w=2400&q=80',
                'alt' => 'Owner using the Housing365 leasing portal on a laptop',
            ],
            'institutional-lease-up' => [
                'url' => 'https://images.unsplash.com/photo-1556761175-5973dc0f32e7?auto=format&fit=crop&w=2400&q=80',
                'alt' => 'Institutional lease-up team in a strategy meeting',
            ],
            'lease-execution' => [
                'url' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=2400&q=80',
                'alt' => 'Standard tenancy lease execution and signing',
            ],
            'rental-pricing-and-market-analysis' => [
                'url' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=2400&q=80',
                'alt' => 'Comprehensive market rent comps and analytics',
            ],
            'property-marketing-and-listings' => [
                'url' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=2400&q=80',
                'alt' => 'High-definition photography and syndicated marketing campaign',
            ],
            'property-showings' => [
                'url' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=2400&q=80',
                'alt' => 'In-person and virtual guided resident tours',
            ],
            'move-in-coordination' => [
                'url' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?auto=format&fit=crop&w=2400&q=80',
                'alt' => 'Inspection sign-off and key release coordination',
            ],
        ];

        return $backdrops[$slug] ?? [
            'url' => 'https://images.unsplash.com/photo-1517090504586-fde19ea6066f?auto=format&fit=crop&w=2400&q=80',
            'alt' => 'City skyline at dusk - Housing365 Indian real estate markets',
        ];
    }

    public static function getRentalAgreementDefinition(): array
    {
        return [
            'slug' => 'rental-agreement-registration',
            'title' => 'Rent Agreement & e-Stamping Registration',
            'metaDescription' => 'Legally enforceable 11-month rental agreements with state e-stamping, biometric Aadhaar e-sign, police verification assistance, and Model Tenancy Act compliance across India.',
            'heroEyebrow' => 'LEGAL DOCUMENTATION & COMPLIANCE',
            'heroKicker' => 'Services',
            'heroHeadline' => 'Online Rent Agreement & e-Stamping Registration',
            'heroLede' => 'Draft, e-stamp, and legally register your 11-month tenancy agreement from the comfort of your home. 100% compliant with state stamp acts, Model Tenancy Act, and local police verification standards.',
            'cta1Label' => 'Draft Agreement Now',
            'cta2Label' => 'Talk to Legal Team',
            'problemTitle' => 'Risks of poorly drafted or unregistered rental agreements',
            'problemPoints' => [
                [
                    'title' => 'Unstamped agreements are inadmissible in Indian courts',
                    'body' => 'Under the Indian Stamp Act and State Stamp Acts, an agreement without proper stamp duty cannot be accepted as evidence during disputes.',
                ],
                [
                    'title' => 'Police verification non-compliance penalties',
                    'body' => 'Most metropolitan police jurisdictions (Delhi, Mumbai, Bengaluru, Gurugram) mandate tenant verification under Section 188 IPC. Failure can lead to legal summons and penalties.',
                ],
                [
                    'title' => 'Arbitrary security deposit disputes',
                    'body' => 'Agreements lacking clear wear-and-tear clauses, move-out notice stipulations, and inspection timelines often result in bitter deposit withholding disputes.',
                ],
                [
                    'title' => 'Ambiguous utility and maintenance division',
                    'body' => 'Without explicit demarcation of landlord vs tenant responsibilities for society maintenance, major repairs, and property tax, conflicts arise quickly.',
                ],
            ],
            'solutionTitle' => 'Seamless, legal, and paperless rent agreement execution',
            'solutionLede' => 'Housing365 handles the entire workflow: lawyer-vetted drafting, government e-stamp paper generation, Aadhaar e-sign / biometric coordination, and police verification filing.',
            'scope' => [
                [
                    'title' => 'Custom Lawyer-Vetted Drafting',
                    'body' => 'Comprehensive clauses tailored to your property: lock-in period, notice terms, rent escalation, security deposit refund, and society rules.',
                ],
                [
                    'title' => 'Government e-Stamping (SHCIL)',
                    'body' => 'Procurement of official government e-stamp certificate in accordance with your state\'s prescribed stamp duty rate.',
                ],
                [
                    'title' => 'Aadhaar e-Sign / Biometric Verification',
                    'body' => 'Legally valid electronic signatures with time-stamped audit trails, eliminating physical meetings.',
                ],
                [
                    'title' => 'Police Verification Assistance',
                    'body' => 'Preparation and online submission of tenant verification forms to the local jurisdiction police portal.',
                ],
            ],
            'howItWorks' => [
                [
                    'step' => '01',
                    'title' => 'Enter Tenancy Details',
                    'body' => 'Submit landlord, tenant, property address, rent amount, and security deposit details in 5 minutes.',
                ],
                [
                    'step' => '02',
                    'title' => 'Review & Customize Draft',
                    'body' => 'Our legal system generates an optimized draft. You and your tenant can review and approve terms online.',
                ],
                [
                    'step' => '03',
                    'title' => 'e-Stamping & Digital Signing',
                    'body' => 'We attach official state e-stamp certificates and execute digital signatures via UIDAI Aadhaar OTP.',
                ],
                [
                    'step' => '04',
                    'title' => 'Instant Download & Police Submission',
                    'body' => 'Download the legally binding PDF agreement and receive assistance with police tenant verification submission.',
                ],
            ],
            'whoItsFor' => [
                [
                    'audience' => 'Independent Landlords',
                    'fitNote' => 'Eliminate courthouse and notary visits while ensuring ironclad legal protection.',
                ],
                [
                    'audience' => 'NRI & Remote Property Owners',
                    'fitNote' => 'Execute enforceable Indian tenancy agreements from anywhere in the world without Power of Attorney hurdles.',
                ],
                [
                    'audience' => 'Tenants & Working Professionals',
                    'fitNote' => 'Get immediate valid proof of address and HRA-compliant rent agreement for company tax deductions.',
                ],
            ],
            'pricingNote' => 'Transparent government stamp duty plus nominal flat processing fee. Zero hidden broker markups.',
            'faqItems' => [
                [
                    'question' => 'Is an 11-month agreement required to be registered under Indian law?',
                    'answer' => 'Under the Registration Act 1908, leases exceeding 11 months must be mandatorily registered at the Sub-Registrar Office. An 11-month agreement with state e-stamp duty and notarization/e-sign is widely used for residential rentals and is legally admissible.',
                ],
                [
                    'question' => 'How does Aadhaar e-Sign work for rent agreements?',
                    'answer' => 'Both landlord and tenant receive a secure link. Using their Aadhaar-linked mobile number, they verify their identity via OTP, which generates a cryptographically signed signature under the Information Technology Act 2000.',
                ],
                [
                    'question' => 'Can I use this rent agreement to claim HRA tax exemption?',
                    'answer' => 'Yes. The generated agreement contains the landlord PAN, complete address, and rent amount required by employers and the Income Tax Department for claiming HRA under Section 10(13A).',
                ],
                [
                    'question' => 'How is police verification completed?',
                    'answer' => 'We generate the standard tenant verification dossier and assist you in filing it on the relevant state police citizen portal (e.g., Delhi Police, Mumbai Police, Karnataka Police Seva Sindhu).',
                ],
            ],
        ];
    }
}
