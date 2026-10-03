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
        $base = $services[$slug] ?? null;

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
                'alt' => 'Modern Canadian rental living room staged for showings',
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
                'alt' => 'Bright, prepped condo unit ready for listing photography',
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
            'alt' => 'Canadian skyline at dusk - Housing365 leasing markets',
        ];
    }
}
