<?php

namespace App\Services;

use App\Models\CityLocation;
use App\Models\Locality;
use App\Models\Property;
use App\Models\SeoMetaOverride;
use App\Models\State;

class SeoService
{
    public function __construct(
        protected SeoIndexabilityService $indexabilityService
    ) {}

    /**
     * Resolve Title, Description, Canonical, Robots, Breadcrumbs, and Structured Data for a request.
     */
    public function resolveMeta(string $path, array $context = []): array
    {
        $override = SeoMetaOverride::findByPath($path);
        $canonical = $override?->canonical_url ?: $this->indexabilityService->buildCanonicalUrl(url($path));
        $shouldIndex = $this->indexabilityService->shouldIndex($path, $context);

        $title = $override?->meta_title;
        $description = $override?->meta_description;
        $h1 = $override?->h1;
        $intro = $override?->intro_text;
        $bodyHtml = $override?->body_html;
        $faqs = $override?->faqs ?? [];

        // Fallbacks if not overridden in CMS
        if (! $title) {
            $title = $this->generateTitle($context);
        }
        if (! $description) {
            $description = $this->generateDescription($context);
        }
        if (! $h1) {
            $h1 = $this->generateH1($context);
        }

        $breadcrumbs = $this->generateBreadcrumbs($context);
        $structuredData = $this->generateStructuredData($context, $breadcrumbs, $faqs);

        return [
            'title' => $title,
            'description' => $description,
            'canonical' => $canonical,
            'robots' => $shouldIndex ? 'index, follow' : 'noindex, follow',
            'h1' => $h1,
            'intro' => $intro,
            'bodyHtml' => $bodyHtml,
            'faqs' => $faqs,
            'breadcrumbs' => $breadcrumbs,
            'structuredData' => $structuredData,
        ];
    }

    protected function generateTitle(array $context): string
    {
        if (isset($context['property']) && $context['property'] instanceof Property) {
            $p = $context['property'];

            return "{$p->title} in {$p->locality}, {$p->city} | Housing365 India";
        }

        $purpose = ucfirst($context['purpose'] ?? 'Property');
        $locName = $context['locality']?->name ?? null;
        $cityName = $context['city']?->name ?? null;
        $stateName = $context['state']?->name ?? null;

        if ($locName && $cityName) {
            return "{$purpose} in {$locName}, {$cityName} | 100% Verified | Housing365 India";
        }
        if ($cityName) {
            return "{$purpose} in {$cityName} | Verified Flats, Houses & Spaces | Housing365 India";
        }
        if ($stateName) {
            return "{$purpose} in {$stateName} | Verified Listings | Housing365 India";
        }

        return 'Everything Property. One Platform. Verified Owners & Zero Spam | Housing365 India';
    }

    protected function generateDescription(array $context): string
    {
        if (isset($context['property']) && $context['property'] instanceof Property) {
            $p = $context['property'];
            $rentPrice = $p->price_display ?: ('₹'.number_format($p->price));

            return "Verified {$p->property_type} for {$p->purpose} in {$p->locality}, {$p->city}. Rent/Price: {$rentPrice}. Zero broker spam, 100% verified owners, 11-month e-stamp agreement on Housing365.";
        }

        $purpose = strtolower($context['purpose'] ?? 'property');
        $locName = $context['locality']?->name ?? null;
        $cityName = $context['city']?->name ?? null;
        $stateName = $context['state']?->name ?? 'India';

        if ($locName && $cityName) {
            return "Find verified {$purpose} in {$locName}, {$cityName}. 100% direct owner listings, legal agreements, zero fake broker calls on Housing365 India.";
        }
        if ($cityName) {
            return "Explore verified {$purpose} across prime localities of {$cityName}, {$stateName}. Direct owner listings, online police verification, and transparent leasing on Housing365.";
        }

        return "India's unified property marketplace for residential rentals, PG, co-living, coworking, commercial properties and Care services. 100% verified listings.";
    }

    protected function generateH1(array $context): string
    {
        if (isset($context['property']) && $context['property'] instanceof Property) {
            return $context['property']->title;
        }

        $purpose = ucfirst($context['purpose'] ?? 'Properties');
        $locName = $context['locality']?->name ?? null;
        $cityName = $context['city']?->name ?? null;
        $stateName = $context['state']?->name ?? null;

        if ($locName && $cityName) {
            return "{$purpose} in {$locName}, {$cityName}";
        }
        if ($cityName) {
            return "{$purpose} in {$cityName}";
        }
        if ($stateName) {
            return "{$purpose} in {$stateName}";
        }

        return 'National Property Marketplace';
    }

    public function generateBreadcrumbs(array $context): array
    {
        $crumbs = [
            ['title' => 'Home', 'url' => url('/')],
        ];

        $purpose = $context['purpose'] ?? null;
        if ($purpose) {
            $crumbs[] = [
                'title' => ucfirst($purpose),
                'url' => url('/'.strtolower($purpose)),
            ];
        }

        if (isset($context['state']) && $context['state'] instanceof State) {
            $crumbs[] = [
                'title' => $context['state']->name,
                'url' => url('/'.($purpose ? strtolower($purpose).'/' : '').$context['state']->slug),
            ];
        }

        if (isset($context['city']) && $context['city'] instanceof CityLocation) {
            $stateSlug = $context['state']?->slug ?? $context['city']->state_slug;
            $crumbs[] = [
                'title' => $context['city']->name,
                'url' => url('/'.($purpose ? strtolower($purpose).'/' : '').($stateSlug ? $stateSlug.'/' : '').$context['city']->slug),
            ];
        }

        if (isset($context['locality']) && $context['locality'] instanceof Locality) {
            $stateSlug = $context['state']?->slug ?? $context['locality']->state?->slug;
            $citySlug = $context['city']?->slug ?? $context['locality']->city?->slug;
            $crumbs[] = [
                'title' => $context['locality']->name,
                'url' => url('/'.($purpose ? strtolower($purpose).'/' : '').($stateSlug ? $stateSlug.'/' : '').($citySlug ? $citySlug.'/' : '').$context['locality']->slug),
            ];
        }

        if (isset($context['property']) && $context['property'] instanceof Property) {
            $crumbs[] = [
                'title' => $context['property']->title,
                'url' => url('/properties/'.$context['property']->slug),
            ];
        }

        return $crumbs;
    }

    public function generateStructuredData(array $context, array $breadcrumbs = [], array $faqs = []): array
    {
        $schemas = [];

        // 1. BreadcrumbList Schema
        if (! empty($breadcrumbs)) {
            $itemList = [];
            foreach ($breadcrumbs as $index => $crumb) {
                $itemList[] = [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'name' => $crumb['title'],
                    'item' => $crumb['url'],
                ];
            }
            $schemas[] = [
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => $itemList,
            ];
        }

        // 2. RealEstateListing / Accommodation Schema for Properties
        // CRITICAL INVARIANT: NEVER expose owner phone or email in Schema!
        if (isset($context['property']) && $context['property'] instanceof Property) {
            $p = $context['property'];
            $propSchema = [
                '@context' => 'https://schema.org',
                '@type' => 'RealEstateListing',
                'name' => $p->title,
                'description' => strip_tags($p->description ?? $p->title),
                'url' => url('/properties/'.$p->slug),
                'datePosted' => $p->created_at?->toIso8601String(),
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressLocality' => $p->locality ?: $p->city,
                    'addressRegion' => $p->state,
                    'addressCountry' => 'IN',
                ],
                'offers' => [
                    '@type' => 'Offer',
                    'price' => (float) $p->price,
                    'priceCurrency' => 'INR',
                    'availability' => 'https://schema.org/InStock',
                ],
            ];
            if ($p->image) {
                $propSchema['image'] = url($p->image);
            }
            $schemas[] = $propSchema;
        }

        // 3. LocalBusiness Schema for Cities
        if (isset($context['city']) && $context['city'] instanceof CityLocation) {
            $c = $context['city'];
            $schemas[] = [
                '@context' => 'https://schema.org',
                '@type' => 'RealEstateAgent',
                'name' => "Housing365 India — {$c->name}",
                'url' => url("/rent/{$c->state_slug}/{$c->slug}"),
                'description' => "Verified property rental and leasing platform serving {$c->name}.",
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressLocality' => $c->name,
                    'addressRegion' => $c->province_or_state,
                    'addressCountry' => 'IN',
                ],
            ];
        }

        // 4. FAQPage Schema
        if (! empty($faqs)) {
            $faqItems = [];
            foreach ($faqs as $faq) {
                if (! empty($faq['question']) && ! empty($faq['answer'])) {
                    $faqItems[] = [
                        '@type' => 'Question',
                        'name' => $faq['question'],
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => strip_tags($faq['answer']),
                        ],
                    ];
                }
            }
            if (! empty($faqItems)) {
                $schemas[] = [
                    '@context' => 'https://schema.org',
                    '@type' => 'FAQPage',
                    'mainEntity' => $faqItems,
                ];
            }
        }

        return $schemas;
    }
}
