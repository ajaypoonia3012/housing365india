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

        // 3b. Locality filter
        if ($request->filled('locality') && $request->locality !== 'all') {
            $query->where('locality', 'like', '%' . trim($request->locality) . '%');
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

        // 6. Bedrooms / BHK filter (Exact or >= for 4+)
        if ($request->filled('bhk') && $request->bhk !== 'all') {
            if ((int)$request->bhk >= 4) {
                $query->where('bedrooms', '>=', 4);
            } else {
                $query->where('bedrooms', (int)$request->bhk);
            }
        } elseif ($request->filled('bedrooms') && (int)$request->bedrooms > 0) {
            $query->where('bedrooms', '>=', (int)$request->bedrooms);
        }

        // 7. Furnishing filter
        if ($request->filled('furnishing') && $request->furnishing !== 'all') {
            $query->where('furnishing', $request->furnishing);
        }

        // 8. Ownership / Posted By filter (Owner, Builder)
        if ($request->filled('posted_by') && $request->posted_by !== 'all') {
            $query->where('posted_by', $request->posted_by);
        }

        // 9. Verified listings only
        if ($request->filled('verified') && $request->verified == '1') {
            $query->where('is_verified', 1);
        }

        // 10. Budget filter
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

        $allProperties = (clone $query)->get(['id', 'title', 'slug', 'price', 'price_display', 'bedrooms', 'bathrooms', 'sqft', 'area_unit', 'address', 'locality', 'city', 'state', 'image', 'property_type', 'category', 'purpose', 'posted_by', 'is_verified']);
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

        // Micro-market localities map for top Indian cities (inspired by MagicBricks)
        $cityTopLocalitiesMap = [
            'Jaipur' => ['Vaishali Nagar', 'Jagatpura', 'Mansarovar', 'Malviya Nagar', 'Tonk Road', 'Ajmer Road', 'C-Scheme', 'Raja Park', 'Kalwar Road', 'Sirsi Road', 'Chitrakoot'],
            'Mumbai' => ['Bandra West', 'Andheri West', 'Juhu', 'Worli', 'Powai', 'BKC', 'Lower Parel', 'Malad West', 'Thane West'],
            'Delhi' => ['Connaught Place', 'Vasant Kunj', 'Greater Kailash', 'Hauz Khas', 'Dwarka', 'Rohini', 'Saket'],
            'Bengaluru' => ['Indiranagar', 'Koramangala', 'HSR Layout', 'Whitefield', 'Electronic City', 'Bellandur', 'Hebbal'],
            'Hyderabad' => ['Hitec City', 'Gachibowli', 'Banjara Hills', 'Jubilee Hills', 'Kondapur', 'Madhapur'],
            'Pune' => ['Koregaon Park', 'Kalyani Nagar', 'Baner', 'Hinjewadi', 'Viman Nagar', 'Wakad'],
            'Gurugram' => ['DLF Phase 5', 'Golf Course Road', 'Sohna Road', 'Cyber City', 'Sector 57'],
        ];

        $selectedCity = $request->get('city', '');
        if (!empty($selectedCity) && isset($cityTopLocalitiesMap[$selectedCity])) {
            $popularLocalities = $cityTopLocalitiesMap[$selectedCity];
        } else {
            $dbLocalities = Property::whereNotNull('locality')
                ->when(!empty($selectedCity), fn($q) => $q->where('city', $selectedCity))
                ->distinct()
                ->pluck('locality')
                ->filter()
                ->values()
                ->toArray();
            $popularLocalities = !empty($dbLocalities) ? $dbLocalities : $cityTopLocalitiesMap['Jaipur'];
        }

        return view('properties.index', compact('properties', 'allProperties', 'cities', 'types', 'counts', 'popularLocalities'));
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
