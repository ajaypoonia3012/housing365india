<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\CityLocation;
use App\Models\Locality;
use App\Models\Page;
use App\Models\Property;
use App\Models\SeoMetaOverride;
use App\Models\Service;
use App\Models\State;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class SitemapController extends Controller
{
    /**
     * XML Sitemap Index: /sitemap.xml
     */
    public function index(): Response
    {
        $content = view('sitemaps.index')->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Properties Sitemap: /sitemaps/properties.xml
     * INVARIANT: Only published, Available properties. Zero rented/draft properties.
     * Excludes any property explicitly marked as non-indexable.
     */
    public function properties(): Response
    {
        $nonIndexablePaths = SeoMetaOverride::where('is_indexable', false)
            ->pluck('path')
            ->map(fn ($p) => '/'.ltrim(rtrim($p, '/'), '/'))
            ->flip()
            ->all();

        $properties = Property::where('status', 'Available')
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->latest('updated_at')
            ->select(['slug', 'updated_at'])
            ->get()
            ->filter(fn ($p) => ! isset($nonIndexablePaths['/properties/'.$p->slug]));

        $content = view('sitemaps.properties', compact('properties'))->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Locations Sitemap: /sitemaps/locations.xml
     * INVARIANT: Only active, indexable States, Cities, and Localities with valid slugs.
     * Excludes any location explicitly marked as non-indexable.
     */
    public function locations(): Response
    {
        $nonIndexablePaths = SeoMetaOverride::where('is_indexable', false)
            ->pluck('path')
            ->map(fn ($p) => '/'.ltrim(rtrim($p, '/'), '/'))
            ->flip()
            ->all();

        $states = State::where('is_active', true)
            ->where('is_indexable', true)
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->select(['id', 'slug', 'updated_at'])
            ->get()
            ->filter(fn ($s) => ! isset($nonIndexablePaths['/rent/'.$s->slug]));

        $cities = CityLocation::where('is_active', true)
            ->where('is_indexable', true)
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->select(['id', 'slug', 'state_slug', 'province_or_state', 'state_id', 'updated_at'])
            ->get()
            ->filter(function ($c) use ($nonIndexablePaths) {
                $stateSlug = $c->state_slug ?: ($c->province_or_state ? Str::slug($c->province_or_state) : 'india');

                return ! isset($nonIndexablePaths['/locations/'.$stateSlug.'/'.$c->slug])
                    && ! isset($nonIndexablePaths['/rent/'.$stateSlug.'/'.$c->slug]);
            });

        $localities = Locality::where('is_active', true)
            ->where('is_indexable', true)
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->select(['id', 'slug', 'city_location_id', 'state_id', 'updated_at'])
            ->with([
                'city' => fn ($q) => $q->select(['id', 'slug', 'state_slug', 'province_or_state']),
                'state' => fn ($q) => $q->select(['id', 'slug']),
            ])
            ->get()
            ->filter(function ($loc) use ($nonIndexablePaths) {
                $cSlug = $loc->city?->slug ?: 'city';
                $sSlug = $loc->city?->state_slug ?: ($loc->state?->slug ?: ($loc->city?->province_or_state ? Str::slug($loc->city->province_or_state) : 'india'));

                return ! isset($nonIndexablePaths['/rent/'.$sSlug.'/'.$cSlug.'/'.$loc->slug]);
            });

        $content = view('sitemaps.locations', compact('states', 'cities', 'localities'))->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Categories Sitemap: /sitemaps/categories.xml
     */
    public function categories(): Response
    {
        $nonIndexablePaths = SeoMetaOverride::where('is_indexable', false)
            ->pluck('path')
            ->map(fn ($p) => '/'.ltrim(rtrim($p, '/'), '/'))
            ->flip()
            ->all();

        $purposes = array_filter(
            ['rent', 'sale', 'commercial', 'land', 'pg', 'co-living', 'coworking', 'projects'],
            fn ($purp) => ! isset($nonIndexablePaths['/'.$purp])
        );

        $content = view('sitemaps.categories', compact('purposes'))->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Services Sitemap: /sitemaps/services.xml
     */
    public function services(): Response
    {
        $nonIndexablePaths = SeoMetaOverride::where('is_indexable', false)
            ->pluck('path')
            ->map(fn ($p) => '/'.ltrim(rtrim($p, '/'), '/'))
            ->flip()
            ->all();

        $services = Service::where('is_active', true)
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->select(['slug', 'updated_at'])
            ->get()
            ->filter(fn ($s) => ! isset($nonIndexablePaths['/services/'.$s->slug]));

        $cities = CityLocation::where('is_active', true)
            ->where('is_indexable', true)
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->select(['slug', 'state_slug', 'province_or_state'])
            ->get()
            ->filter(function ($c) use ($nonIndexablePaths) {
                $stateSlug = $c->state_slug ?: ($c->province_or_state ? Str::slug($c->province_or_state) : 'india');

                return ! isset($nonIndexablePaths['/housing365-care/'.$stateSlug.'/'.$c->slug])
                    && ! isset($nonIndexablePaths['/property-management/'.$stateSlug.'/'.$c->slug]);
            });

        $content = view('sitemaps.services', compact('services', 'cities', 'nonIndexablePaths'))->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Static & CMS Sitemap: /sitemaps/cms.xml
     */
    public function cms(): Response
    {
        $nonIndexablePaths = SeoMetaOverride::where('is_indexable', false)
            ->pluck('path')
            ->map(fn ($p) => '/'.ltrim(rtrim($p, '/'), '/'))
            ->flip()
            ->all();

        $posts = BlogPost::where('is_published', true)
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->latest('updated_at')
            ->select(['slug', 'updated_at'])
            ->get()
            ->filter(fn ($post) => ! isset($nonIndexablePaths['/blog/'.$post->slug]));

        $pages = Page::where('is_published', true)
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->whereNotIn('slug', ['home'])
            ->select(['slug', 'updated_at'])
            ->get()
            ->filter(fn ($page) => ! isset($nonIndexablePaths['/'.$page->slug]));

        $content = view('sitemaps.cms', compact('posts', 'pages', 'nonIndexablePaths'))->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }
}
