<?php

namespace App\Http\Controllers;

use App\Models\CityLocation;
use App\Models\ContactAccessPackage;
use App\Models\Inquiry;
use App\Models\Property;
use App\Models\SavedProperty;
use App\Services\LeadManagementService;
use Illuminate\Http\Request;

class PropertyListingController extends Controller
{
    /**
     * Unified Marketplace Search & Browsing
     */
    public function index(Request $request, ?string $routePurpose = null, ?string $state = null, ?string $city = null, ?string $locality = null)
    {
        $query = Property::query();

        // Harmonize route-based parameters with request query parameters
        $effectivePurpose = $request->get('purpose', $routePurpose);
        $effectiveState = $request->get('state', $state);
        $effectiveCity = $request->get('city', $city);
        $effectiveLocality = $request->get('locality', $locality);

        // Map URL friendly purposes to canonical purposes
        if ($effectivePurpose === 'co-living') {
            $effectivePurpose = 'coliving';
        } elseif ($effectivePurpose === 'projects') {
            $effectivePurpose = 'project';
        } elseif ($effectivePurpose === 'land') {
            $effectivePurpose = 'land_sale';
        }

        // 1. Purpose filter (9 supported purposes)
        if (! empty($effectivePurpose) && $effectivePurpose !== 'all') {
            if ($effectivePurpose === 'rent') {
                $query->whereIn('purpose', ['rent', 'lease']);
            } elseif ($effectivePurpose === 'commercial') {
                $query->where(function ($q) {
                    $q->whereIn('purpose', ['commercial_rent', 'commercial_sale'])
                        ->orWhere('category', 'commercial');
                });
            } elseif ($effectivePurpose === 'land_sale' || $effectivePurpose === 'land') {
                $query->where(function ($q) {
                    $q->where('purpose', 'land_sale')
                        ->orWhere('category', 'plot_land');
                });
            } else {
                $query->where('purpose', $effectivePurpose);
            }
        }

        // 2. Category filter
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // 3. State filter
        if (! empty($effectiveState) && $effectiveState !== 'all') {
            $stateDecoded = str_replace('-', ' ', $effectiveState);
            $query->where(function ($q) use ($stateDecoded) {
                $q->where('state', 'like', '%'.$stateDecoded.'%');
            });
        }

        // 4. City filter
        if (! empty($effectiveCity) && $effectiveCity !== 'all') {
            $cityDecoded = str_replace('-', ' ', $effectiveCity);
            $query->where(function ($q) use ($cityDecoded) {
                $q->where('city', 'like', '%'.$cityDecoded.'%');
            });
        }

        // 5. Locality filter
        if (! empty($effectiveLocality) && $effectiveLocality !== 'all') {
            $localityDecoded = str_replace('-', ' ', $effectiveLocality);
            $query->where('locality', 'like', '%'.$localityDecoded.'%');
        }

        // 6. Keyword search (locality, title, description, address, city)
        if ($request->filled('q')) {
            $term = '%'.trim($request->q).'%';
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                    ->orWhere('locality', 'like', $term)
                    ->orWhere('address', 'like', $term)
                    ->orWhere('city', 'like', $term)
                    ->orWhere('property_type', 'like', $term)
                    ->orWhere('project_name', 'like', $term)
                    ->orWhere('description', 'like', $term);
            });
        }

        // 7. Property Type filter
        if ($request->filled('property_type') && $request->property_type !== 'all') {
            $query->where('property_type', $request->property_type);
        }

        // 8. Bedrooms / BHK filter (Exact or >= for 4+)
        if ($request->filled('bhk') && $request->bhk !== 'all') {
            if ((int) $request->bhk >= 4) {
                $query->where('bedrooms', '>=', 4);
            } else {
                $query->where('bedrooms', (int) $request->bhk);
            }
        } elseif ($request->filled('bedrooms') && (int) $request->bedrooms > 0) {
            $query->where('bedrooms', '>=', (int) $request->bedrooms);
        }

        // 9. Bathrooms filter
        if ($request->filled('bathrooms') && (int) $request->bathrooms > 0) {
            $query->where('bathrooms', '>=', (float) $request->bathrooms);
        }

        // 10. Furnishing filter
        if ($request->filled('furnishing') && $request->furnishing !== 'all') {
            $query->where('furnishing', $request->furnishing);
        }

        // 11. Ownership / Listing Source filter
        if ($request->filled('posted_by') && $request->posted_by !== 'all') {
            $query->where(function ($q) use ($request) {
                $q->where('posted_by', $request->posted_by)
                    ->orWhere('listing_source', $request->posted_by);
            });
        }

        // 12. Verification status filter
        if ($request->filled('verified') && $request->verified == '1') {
            $query->where(function ($q) {
                $q->where('is_verified', true)
                    ->orWhereIn('verification_level', ['basic_verified', 'document_verified', 'housing365_verified']);
            });
        }
        if ($request->filled('verification_level') && $request->verification_level !== 'all') {
            $query->where('verification_level', $request->verification_level);
        }

        // 13. Budget filter
        if ($request->filled('max_price') && (float) $request->max_price > 0) {
            $query->where('price', '<=', (float) $request->max_price);
        }
        if ($request->filled('min_price') && (float) $request->min_price > 0) {
            $query->where('price', '>=', (float) $request->min_price);
        }

        // 14. Area filter
        if ($request->filled('min_sqft') && (float) $request->min_sqft > 0) {
            $query->where('sqft', '>=', (float) $request->min_sqft);
        }
        if ($request->filled('max_sqft') && (float) $request->max_sqft > 0) {
            $query->where('sqft', '<=', (float) $request->max_sqft);
        }

        // 15. PG / Co-living specific filters
        if ($request->filled('pg_gender') && $request->pg_gender !== 'all') {
            $query->where('pg_gender', $request->pg_gender);
        }
        if ($request->filled('occupancy') && $request->occupancy !== 'all') {
            $query->where('occupancy_type', $request->occupancy);
        }
        if ($request->filled('food_included') && $request->food_included == '1') {
            $query->where('food_included', true);
        }

        // Listing status
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

        // Select ONLY public fields (never leak owner phone or email)
        $publicFields = [
            'id', 'title', 'slug', 'price', 'price_display', 'price_frequency',
            'bedrooms', 'bathrooms', 'sqft', 'area_unit', 'address', 'locality',
            'city', 'state', 'image', 'gallery', 'property_type', 'category',
            'purpose', 'posted_by', 'listing_source', 'is_verified', 'verification_level',
            'pg_gender', 'food_included', 'occupancy_type', 'featured', 'created_at', 'updated_at',
        ];

        $allProperties = (clone $query)->get($publicFields);
        $properties = $query->select($publicFields)->paginate(9)->withQueryString();

        $cities = CityLocation::orderBy('name')->pluck('name');

        // Dynamic types available in DB
        $types = Property::whereNotNull('property_type')->distinct()->pluck('property_type')->sort()->values();

        // Marketplace category counts for pills
        $counts = [
            'all' => Property::where('status', 'Available')->count(),
            'rent' => Property::where('status', 'Available')->whereIn('purpose', ['rent', 'lease'])->count(),
            'sale' => Property::where('status', 'Available')->where('purpose', 'sale')->count(),
            'commercial' => Property::where('status', 'Available')->where(function ($q) {
                $q->whereIn('purpose', ['commercial_rent', 'commercial_sale'])->orWhere('category', 'commercial');
            })->count(),
            'land_sale' => Property::where('status', 'Available')->where(function ($q) {
                $q->where('purpose', 'land_sale')->orWhere('category', 'plot_land');
            })->count(),
            'pg' => Property::where('status', 'Available')->where('purpose', 'pg')->count(),
            'coliving' => Property::where('status', 'Available')->where('purpose', 'coliving')->count(),
            'coworking' => Property::where('status', 'Available')->where('purpose', 'coworking')->count(),
            'project' => Property::where('status', 'Available')->where('purpose', 'project')->count(),
        ];

        $selectedCity = $effectiveCity ?: $request->get('city', '');
        $cityTopLocalitiesMap = [
            'Jaipur' => ['Vaishali Nagar', 'Jagatpura', 'Mansarovar', 'Malviya Nagar', 'Tonk Road', 'Ajmer Road', 'C-Scheme', 'Raja Park', 'Kalwar Road', 'Sirsi Road', 'Chitrakoot'],
            'Mumbai' => ['Bandra West', 'Andheri West', 'Juhu', 'Worli', 'Powai', 'BKC', 'Lower Parel', 'Malad West', 'Thane West'],
            'Delhi' => ['Connaught Place', 'Vasant Kunj', 'Greater Kailash', 'Hauz Khas', 'Dwarka', 'Rohini', 'Saket'],
            'Bengaluru' => ['Indiranagar', 'Koramangala', 'HSR Layout', 'Whitefield', 'Electronic City', 'Bellandur', 'Hebbal'],
            'Hyderabad' => ['Hitec City', 'Gachibowli', 'Banjara Hills', 'Jubilee Hills', 'Kondapur', 'Madhapur'],
            'Pune' => ['Koregaon Park', 'Kalyani Nagar', 'Baner', 'Hinjewadi', 'Viman Nagar', 'Wakad'],
            'Gurugram' => ['DLF Phase 5', 'Golf Course Road', 'Sohna Road', 'Cyber City', 'Sector 57'],
        ];

        if (! empty($selectedCity) && isset($cityTopLocalitiesMap[$selectedCity])) {
            $popularLocalities = $cityTopLocalitiesMap[$selectedCity];
        } else {
            $dbLocalities = Property::whereNotNull('locality')
                ->when(! empty($selectedCity), fn ($q) => $q->where('city', $selectedCity))
                ->distinct()
                ->pluck('locality')
                ->filter()
                ->values()
                ->toArray();
            $popularLocalities = ! empty($dbLocalities) ? $dbLocalities : $cityTopLocalitiesMap['Jaipur'];
        }

        // User saved property IDs for heart icons
        $savedPropertyIds = auth()->check()
            ? auth()->user()->savedProperties()->pluck('properties.id')->toArray()
            : [];

        // Build canonical URL for SEO
        $canonicalUrl = $this->buildCanonicalUrl($effectivePurpose, $effectiveState, $effectiveCity, $effectiveLocality);

        return view('properties.index', compact(
            'properties',
            'allProperties',
            'cities',
            'types',
            'counts',
            'popularLocalities',
            'savedPropertyIds',
            'effectivePurpose',
            'effectiveState',
            'effectiveCity',
            'effectiveLocality',
            'canonicalUrl'
        ));
    }

    /**
     * Show single property details (Strict Contact Access Gate).
     */
    public function show(string $slug)
    {
        $property = Property::where('slug', $slug)->firstOrFail();

        $similarProperties = Property::where('city', $property->city)
            ->where('id', '!=', $property->id)
            ->take(3)
            ->get([
                'id', 'title', 'slug', 'price', 'price_display', 'bedrooms', 'bathrooms',
                'sqft', 'address', 'locality', 'city', 'state', 'image', 'property_type',
                'category', 'purpose', 'posted_by', 'listing_source', 'is_verified', 'verification_level',
            ]);

        $user = auth()->user();
        $isUnlocked = $user ? $property->isUnlockedBy($user) : false;
        $isSaved = $user ? $property->isSavedBy($user) : false;
        $userCredits = $user ? $user->availableContactCredits() : 0;
        $packages = ContactAccessPackage::query()->active()->get();

        // Defense-in-depth: Mask seller contact info unless authorized & unlocked
        $unmaskedPhone = $isUnlocked ? $property->seller_phone : null;
        $unmaskedEmail = $isUnlocked ? $property->seller_email : null;

        if (! $isUnlocked) {
            $property->seller_phone = null;
            $property->seller_email = null;
        }

        return view('properties.show', compact(
            'property',
            'similarProperties',
            'isUnlocked',
            'isSaved',
            'userCredits',
            'packages',
            'unmaskedPhone',
            'unmaskedEmail'
        ));
    }

    /**
     * Save / Bookmark a property (Toggle).
     */
    public function toggleSave(Request $request, int $id)
    {
        $user = auth()->user();
        if (! $user) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['error' => 'Unauthenticated', 'message' => 'Please log in to save properties.'], 401);
            }

            return redirect()->route('login')->with('info', 'Please log in to save properties.');
        }

        $property = Property::findOrFail($id);

        $existing = SavedProperty::where('user_id', $user->id)
            ->where('property_id', $property->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $saved = false;
            $message = 'Property removed from saved listings.';
        } else {
            SavedProperty::create([
                'user_id' => $user->id,
                'property_id' => $property->id,
            ]);
            $saved = true;
            $message = 'Property added to your saved listings.';
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'saved' => $saved,
                'message' => $message,
                'property_id' => $property->id,
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * View user's saved properties list.
     */
    public function savedProperties(Request $request)
    {
        $user = auth()->user();
        $properties = $user->savedProperties()
            ->select([
                'properties.id', 'title', 'slug', 'price', 'price_display', 'price_frequency',
                'bedrooms', 'bathrooms', 'sqft', 'address', 'locality', 'city', 'state',
                'image', 'property_type', 'category', 'purpose', 'posted_by', 'is_verified', 'verification_level',
            ])
            ->latest('saved_properties.created_at')
            ->paginate(9);

        return view('properties.saved', compact('properties'));
    }

    /**
     * Submit free viewing / site-visit request.
     */
    public function requestViewing(Request $request, int $id)
    {
        $property = Property::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:25',
            'email' => 'nullable|email|max:150',
            'preferred_date' => 'nullable|string|max:50',
            'preferred_time' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:500',
        ]);

        $inquiry = Inquiry::create([
            'property_id' => $property->id,
            'name' => strip_tags(trim($validated['name'])),
            'phone' => strip_tags(trim($validated['phone'])),
            'email' => ! empty($validated['email']) ? filter_var(trim($validated['email']), FILTER_SANITIZE_EMAIL) : 'inquiry@housing365.in',
            'service_type' => 'Property Viewing Request',
            'subject' => 'Viewing Request for '.$property->title,
            'message' => 'Viewing Date: '.($validated['preferred_date'] ?? 'Flexible').' | Slot: '.($validated['preferred_time'] ?? 'Anytime').' | Notes: '.($validated['notes'] ?? 'None'),
            'status' => 'new',
        ]);

        try {
            app(LeadManagementService::class)->ingestFromInquiry($inquiry, [
                'source' => 'viewing_request',
                'priority' => 'high',
                'utm_data' => $request->only(['utm_source', 'utm_medium', 'utm_campaign']),
            ]);
        } catch (\Throwable $e) {
            // Safe fallback so inquiry is never blocked
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Your viewing request has been scheduled with Housing 365! Our field representative will coordinate your visit.',
            ]);
        }

        return back()->with('success', 'Your viewing request has been scheduled with Housing 365! Our field representative will coordinate your visit.');
    }

    /**
     * Construct canonical URL according to directory hierarchy.
     */
    private function buildCanonicalUrl(?string $purpose, ?string $state, ?string $city, ?string $locality): string
    {
        $url = url('/properties');
        if (empty($purpose) || $purpose === 'all') {
            return $url;
        }

        $purposePath = match ($purpose) {
            'rent' => 'rent',
            'sale' => 'sale',
            'commercial', 'commercial_rent', 'commercial_sale' => 'commercial',
            'land_sale', 'land' => 'land',
            'pg' => 'pg',
            'coliving' => 'co-living',
            'coworking' => 'coworking',
            'project' => 'projects',
            default => 'properties',
        };

        if ($purposePath === 'properties') {
            return $url;
        }

        $parts = [$purposePath];
        if (! empty($state)) {
            $parts[] = strtolower(str_replace(' ', '-', $state));
        }
        if (! empty($city)) {
            $parts[] = strtolower(str_replace(' ', '-', $city));
        }
        if (! empty($locality)) {
            $parts[] = strtolower(str_replace(' ', '-', $locality));
        }

        return url('/'.implode('/', $parts));
    }
}
