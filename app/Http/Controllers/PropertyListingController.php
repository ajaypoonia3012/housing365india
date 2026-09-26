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

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        if ($request->filled('property_type')) {
            $query->where('property_type', $request->property_type);
        }

        if ($request->filled('bedrooms')) {
            $query->where('bedrooms', '>=', (int)$request->bedrooms);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float)$request->max_price);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'Available');
        }

        $allProperties = (clone $query)->get(['id', 'title', 'slug', 'price', 'bedrooms', 'bathrooms', 'sqft', 'address', 'city', 'state', 'latitude', 'longitude', 'image', 'property_type']);
        $properties = $query->latest()->paginate(9)->withQueryString();
        $cities = CityLocation::orderBy('name')->pluck('name');
        $types = ['Apartment', 'Condo', 'House', 'Loft', 'Penthouse', 'Townhouse'];

        return view('properties.index', compact('properties', 'allProperties', 'cities', 'types'));
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
