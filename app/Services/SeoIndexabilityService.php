<?php

namespace App\Services;

use App\Models\CityLocation;
use App\Models\Locality;
use App\Models\Property;
use App\Models\SeoMetaOverride;
use App\Models\State;

class SeoIndexabilityService
{
    /**
     * Determine if a route or URL path should be indexed by search engines.
     * Rule: Only index if genuine inventory exists OR substantial editorial guide content exists.
     * Never generate doorway or thin spam pages.
     */
    public function shouldIndex(string $path, array $context = []): bool
    {
        // 1. Explicit admin override check
        $override = SeoMetaOverride::findByPath($path);
        if ($override && $override->is_indexable !== null) {
            return (bool) $override->is_indexable;
        }

        // 2. Private, authenticated, dashboard or referral paths are NEVER indexable
        $privatePrefixes = [
            '/dashboard', '/admin', '/login', '/register', '/referrals',
            '/rewards', '/payouts', '/api', '/storage/private', '/r/',
        ];
        foreach ($privatePrefixes as $prefix) {
            if (str_starts_with($path, $prefix)) {
                return false;
            }
        }

        // 3. Location specific checks
        if (isset($context['locality']) && $context['locality'] instanceof Locality) {
            $loc = $context['locality'];
            if (! $loc->is_active) {
                return false;
            }

            // Indexable if explicitly flagged or has genuine available inventory
            return $loc->is_indexable || $loc->active_properties_count > 0;
        }

        if (isset($context['city']) && $context['city'] instanceof CityLocation) {
            $city = $context['city'];
            if (! $city->is_active) {
                return false;
            }

            return $city->is_indexable || $city->active_properties_count > 0;
        }

        if (isset($context['state']) && $context['state'] instanceof State) {
            return (bool) $context['state']->is_active;
        }

        // 4. Property Detail Page
        if (isset($context['property']) && $context['property'] instanceof Property) {
            return strtolower($context['property']->status) === 'available';
        }

        // 5. Category listing with search queries
        // If it's a search page with arbitrary filters or 0 search results, do not index thin results
        if (isset($context['results_count']) && $context['results_count'] === 0 && empty($context['has_editorial_content'])) {
            return false;
        }

        // Standard public pages are indexable by default
        return true;
    }

    /**
     * Clean and build the preferred canonical URL.
     * Strips query parameters, tracking parameters, and trailing slashes.
     */
    public function buildCanonicalUrl(string $url): string
    {
        $parsed = parse_url($url);
        $scheme = $parsed['scheme'] ?? 'https';
        $host = $parsed['host'] ?? 'housing365.in';
        $path = $parsed['path'] ?? '/';

        // Normalize trailing slashes (except root)
        if ($path !== '/' && str_ends_with($path, '/')) {
            $path = rtrim($path, '/');
        }

        return "{$scheme}://{$host}{$path}";
    }
}
