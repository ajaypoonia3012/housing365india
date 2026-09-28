<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\CityLocation;

class PropertyListingController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::query();

        // 1. Purpose filter (Rent, Sale, Lease)
        if ($request->filled('purpose') && $request->purpose !== 'all') {
            if ($request->purpose === 'rent') {
                $query->whereIn('purpose', ['rent', 'lease']);
            } else {
                $query->where('purpose', $request->purpose);
            }
        }

        // 2. Category filter (Residential, Commercial, Plot/Land)
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // 3. City filter
        if ($request->filled('city') && $request->city !== 'all') {
            $query->where('city', $request->city);
        }

        // 4. Keyword search (locality, title, description, address)
        if ($request->filled('q')) {
            $term = '%' . trim($request->q) . '%';
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                  ->orWhere('locality', 'like', $term)
                  ->orWhere('address', 'like', $term)
                  ->orWhere('city', 'like', $term)
                  ->orWhere('property_type', 'like', $term)
                  ->orWhere('description', 'like', $term);
            });
        }

        // 5. Property Type filter
        if ($request->filled('property_type') && $request->property_type !== 'all') {
            $query->where('property_type', $request->property_type);
        }

        // 6. Bedrooms filter (only for residential)
        if ($request->filled('bedrooms') && (int)$request->bedrooms > 0) {
            $query->where('bedrooms', '>=', (int)$request->bedrooms);
        }

        // 7. Budget filter
        if ($request->filled('max_price') && (float)$request->max_price > 0) {
            $query->where('price', '<=', (float)$request->max_price);
        }
        if ($request->filled('min_price') && (float)$request->min_price > 0) {
            $query->where('price', '>=', (float)$request->min_price);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'Available');
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        if ($sort === 'price_low') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_high') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        $allProperties = (clone $query)->get(['id', 'title', 'slug', 'price', 'price_display', 'bedrooms', 'bathrooms', 'sqft', 'area_unit', 'address', 'locality', 'city', 'state', 'image', 'property_type', 'category', 'purpose']);
        $properties = $query->paginate(9)->withQueryString();
        
        $cities = CityLocation::orderBy('name')->pluck('name');
        
        // Dynamic types available in DB
        $types = Property::whereNotNull('property_type')->distinct()->pluck('property_type')->sort()->values();

        // Marketplace category counts for quick pill filters
        $counts = [
            'all' => Property::where('status', 'Available')->count(),
            'rent' => Property::where('status', 'Available')->whereIn('purpose', ['rent', 'lease'])->count(),
            'sale' => Property::where('status', 'Available')->where('purpose', 'sale')->count(),
            'commercial' => Property::where('status', 'Available')->where('category', 'commercial')->count(),
            'plot_land' => Property::where('status', 'Available')->where('category', 'plot_land')->count(),
        ];

        return view('properties.index', compact('properties', 'allProperties', 'cities', 'types', 'counts'));
    }

    public function show(string $slug)
    {
        $property = Property::where('slug', $slug)->firstOrFail();
        $similarProperties = Property::where('city', $property->city)
            ->where('id', '!=', $property->id)
            ->take(3)
            ->get();

        return view('properties.show', compact('property', 'similarProperties'));
    }
}
