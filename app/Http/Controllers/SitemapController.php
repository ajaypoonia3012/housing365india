<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\CityLocation;
use App\Models\Locality;
use App\Models\Property;
use App\Models\Service;
use App\Models\State;
use Illuminate\Http\Response;

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
     */
    public function properties(): Response
    {
        $properties = Property::where('status', 'Available')
            ->latest('updated_at')
            ->get(['slug', 'updated_at']);

        $content = view('sitemaps.properties', compact('properties'))->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Locations Sitemap: /sitemaps/locations.xml
     * INVARIANT: Only active indexable States, Cities, and Localities with inventory.
     */
    public function locations(): Response
    {
        $states = State::where('is_active', true)->where('is_indexable', true)->get();
        $cities = CityLocation::where('is_active', true)->where('is_indexable', true)->get();
        $localities = Locality::where('is_active', true)
            ->where('is_indexable', true)
            ->with(['city', 'state'])
            ->get();

        $content = view('sitemaps.locations', compact('states', 'cities', 'localities'))->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Categories Sitemap: /sitemaps/categories.xml
     */
    public function categories(): Response
    {
        $purposes = ['rent', 'sale', 'commercial', 'land', 'pg', 'co-living', 'coworking', 'projects'];
        $content = view('sitemaps.categories', compact('purposes'))->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Services Sitemap: /sitemaps/services.xml
     */
    public function services(): Response
    {
        $services = Service::where('is_active', true)->get(['slug', 'updated_at']);
        $cities = CityLocation::where('is_active', true)->get(['slug', 'state_slug']);

        $content = view('sitemaps.services', compact('services', 'cities'))->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Static & CMS Sitemap: /sitemaps/cms.xml
     */
    public function cms(): Response
    {
        $posts = BlogPost::where('is_published', true)->get(['slug', 'updated_at']);
        $content = view('sitemaps.cms', compact('posts'))->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }
}
