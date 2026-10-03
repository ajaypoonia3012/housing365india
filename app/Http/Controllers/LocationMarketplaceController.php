<?php

namespace App\Http\Controllers;

use App\Models\CityLocation;
use App\Models\Locality;
use App\Models\Property;
use App\Models\State;
use App\Services\SeoService;
use Illuminate\Http\Request;

class LocationMarketplaceController extends Controller
{
    public function __construct(
        protected SeoService $seoService
    ) {}

    /**
     * City Marketplace Landing Page
     * Example: /locations/{state}/{city} or /property-in/{city}
     */
    public function city(Request $request, string $state, string $city)
    {
        $stateRecord = State::where('slug', strtolower($state))->first();
        $cityRecord = CityLocation::where('slug', strtolower($city))
            ->when($stateRecord, fn ($q) => $q->where('state_id', $stateRecord->id))
            ->first();

        if (! $cityRecord) {
            abort(404, 'Indian City location not found');
        }

        // Fetch localities under this city
        $localities = Locality::where('city_location_id', $cityRecord->id)
            ->where('is_active', true)
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        // Fetch properties in this city
        $publicFields = [
            'id', 'title', 'slug', 'price', 'price_display', 'price_frequency',
            'bedrooms', 'bathrooms', 'sqft', 'address', 'locality', 'city', 'state',
            'image', 'property_type', 'category', 'purpose', 'posted_by', 'is_verified',
            'verification_level', 'featured', 'created_at',
        ];

        $properties = Property::where('status', 'Available')
            ->where('city', $cityRecord->name)
            ->latest()
            ->take(12)
            ->get($publicFields);

        // Inventory counts by purpose
        $counts = [
            'rent' => Property::where('status', 'Available')->where('city', $cityRecord->name)->whereIn('purpose', ['rent', 'lease'])->count(),
            'sale' => Property::where('status', 'Available')->where('city', $cityRecord->name)->where('purpose', 'sale')->count(),
            'pg' => Property::where('status', 'Available')->where('city', $cityRecord->name)->where('purpose', 'pg')->count(),
            'coworking' => Property::where('status', 'Available')->where('city', $cityRecord->name)->where('purpose', 'coworking')->count(),
            'commercial' => Property::where('status', 'Available')->where('city', $cityRecord->name)->where(function ($q) {
                $q->whereIn('purpose', ['commercial_rent', 'commercial_sale'])->orWhere('category', 'commercial');
            })->count(),
        ];

        $context = [
            'state' => $stateRecord,
            'city' => $cityRecord,
            'purpose' => 'Property',
        ];

        $seo = $this->seoService->resolveMeta($request->path(), $context);

        return view('locations.city', compact('stateRecord', 'cityRecord', 'localities', 'properties', 'counts', 'seo'));
    }

    /**
     * Locality Marketplace Landing Page
     */
    public function locality(Request $request, string $state, string $city, string $locality)
    {
        $stateRecord = State::where('slug', strtolower($state))->first();
        $cityRecord = CityLocation::where('slug', strtolower($city))->first();

        if (! $cityRecord) {
            abort(404, 'Indian City location not found');
        }

        $localityRecord = Locality::where('city_location_id', $cityRecord->id)
            ->where('slug', strtolower($locality))
            ->first();

        if (! $localityRecord) {
            abort(404, 'Indian Locality not found');
        }

        $publicFields = [
            'id', 'title', 'slug', 'price', 'price_display', 'price_frequency',
            'bedrooms', 'bathrooms', 'sqft', 'address', 'locality', 'city', 'state',
            'image', 'property_type', 'category', 'purpose', 'posted_by', 'is_verified',
            'verification_level', 'featured', 'created_at',
        ];

        $properties = Property::where('status', 'Available')
            ->where('city', $cityRecord->name)
            ->where(function ($q) use ($localityRecord) {
                $q->where('locality', $localityRecord->name)
                    ->orWhere('locality', 'LIKE', '%'.$localityRecord->name.'%');
            })
            ->latest()
            ->paginate(12);

        $context = [
            'state' => $stateRecord,
            'city' => $cityRecord,
            'locality' => $localityRecord,
            'purpose' => 'Property',
            'results_count' => $properties->total(),
        ];

        $seo = $this->seoService->resolveMeta($request->path(), $context);

        return view('locations.locality', compact('stateRecord', 'cityRecord', 'localityRecord', 'properties', 'seo'));
    }

    /**
     * Care Location Discovery Page
     * Example: /housing365-care/{state}/{city}/{service?}
     */
    public function careLocation(Request $request, string $state, string $city, ?string $service = null)
    {
        $stateRecord = State::where('slug', strtolower($state))->first();
        $cityRecord = CityLocation::where('slug', strtolower($city))->first();

        if (! $cityRecord) {
            abort(404, 'City location not found for Care services');
        }

        $context = [
            'state' => $stateRecord,
            'city' => $cityRecord,
            'purpose' => 'Housing365 Care Services',
        ];

        $seo = $this->seoService->resolveMeta($request->path(), $context);

        return view('services.care-location', compact('stateRecord', 'cityRecord', 'service', 'seo'));
    }

    /**
     * Optional Property Management Location Page
     * Example: /property-management/{state}/{city}
     * INVARIANT: Property Management is strictly optional.
     */
    public function managementLocation(Request $request, string $state, string $city)
    {
        $stateRecord = State::where('slug', strtolower($state))->first();
        $cityRecord = CityLocation::where('slug', strtolower($city))->first();

        if (! $cityRecord) {
            abort(404, 'City location not found for Property Management');
        }

        $context = [
            'state' => $stateRecord,
            'city' => $cityRecord,
            'purpose' => 'Property Management',
        ];

        $seo = $this->seoService->resolveMeta($request->path(), $context);

        return view('management.location', compact('stateRecord', 'cityRecord', 'seo'));
    }
}
