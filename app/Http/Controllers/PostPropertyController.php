<?php

namespace App\Http\Controllers;

use App\Models\CityLocation;
use App\Models\Inquiry;
use App\Models\Property;
use App\Services\ReferralService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PostPropertyController extends Controller
{
    /**
     * Show the Post Property form with multi-purpose support and clear ₹0 free leasing disclosure.
     */
    public function create()
    {
        $cities = CityLocation::orderBy('name')->pluck('name');

        $categories = [
            'residential' => [
                'label' => 'Residential',
                'icon' => 'fa-house',
                'types' => ['Flat / Apartment', 'House / Villa', 'Builder Floor', 'Penthouse', 'Studio Apartment', 'Independent House'],
            ],
            'commercial' => [
                'label' => 'Commercial',
                'icon' => 'fa-building',
                'types' => ['Commercial Office', 'Retail Shop', 'Showroom', 'Warehouse', 'Co-Working Space', 'Industrial Shed', 'Commercial Building'],
            ],
            'plot_land' => [
                'label' => 'Plots & Land',
                'icon' => 'fa-map',
                'types' => ['Residential Plot', 'Commercial Land', 'Agricultural Farm Land', 'Industrial Land'],
            ],
            'pg_coliving' => [
                'label' => 'PG & Co-Living',
                'icon' => 'fa-users',
                'types' => ['PG for Men', 'PG for Women', 'Co-living Shared Suite', 'Private Studio PG'],
            ],
            'coworking' => [
                'label' => 'Coworking Space',
                'icon' => 'fa-briefcase',
                'types' => ['Dedicated Desk', 'Flexi Desk', 'Private Team Cabin', 'Managed Enterprise Office'],
            ],
            'project' => [
                'label' => 'Builder Project',
                'icon' => 'fa-city',
                'types' => ['New Launch Township', 'Luxury High-Rise Project', 'Gated Villa Community', 'Commercial Tech Park'],
            ],
        ];

        return view('properties.post', compact('cities', 'categories'));
    }

    /**
     * Store the posted property.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'purpose' => 'required|in:rent,sale,commercial_rent,commercial_sale,land_sale,pg,coliving,coworking,project,lease',
            'category' => 'nullable|string|max:50',
            'property_type' => 'required|string|max:100',
            'posting_as' => 'nullable|string|max:50',
            'total_flats_in_society' => 'nullable|string|max:50',
            'title' => 'nullable|string|max:180',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'locality' => 'required|string|max:150',
            'address' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:1',
            'deposit_amount' => 'nullable|numeric|min:0',
            'maintenance' => 'nullable|string|max:100',
            'price_frequency' => 'nullable|string|max:20',
            'sqft' => 'required|numeric|min:1',
            'area_unit' => 'nullable|in:sqft,sq.yards,acres,bigha',
            'bedrooms' => 'nullable|integer|min:0|max:20',
            'balconies' => 'nullable|integer|min:0|max:10',
            'bathrooms' => 'nullable|numeric|min:0|max:20',
            'floor_no' => 'nullable|string|max:50',
            'total_floors' => 'nullable|string|max:50',
            'furnishing' => 'nullable|string|max:50',
            'posted_by' => 'nullable|string|max:50',
            'listing_source' => 'nullable|in:Owner,Broker,Builder,Operator,Housing365',
            'seller_name' => 'required|string|max:100',
            'seller_phone' => 'required|string|max:25',
            'seller_email' => 'nullable|email|max:150',
            'description' => 'nullable|string|max:5000',
            'amenities' => 'nullable|array',

            // Purpose-specific fields
            'pg_gender' => 'nullable|in:boys,girls,unisex',
            'occupancy_type' => 'nullable|string|max:50',
            'food_included' => 'nullable|boolean',
            'house_rules' => 'nullable|string|max:2000',
            'rera_number' => 'nullable|string|max:100',
            'project_name' => 'nullable|string|max:150',
            'possession_date' => 'nullable|string|max:50',
            'opt_in_management' => 'nullable|boolean',

            'photos' => 'required|array|min:1|max:12',
            'photos.*' => 'image|mimes:jpeg,png,jpg,webp|max:15360',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:15360',
            'image_url' => 'nullable|url|max:500',
            'video_file' => 'nullable|file|mimes:mp4,webm,mov,mkv,avi|max:102400',
            'video_url' => 'nullable|string|max:500',
        ], [
            'photos.required' => 'कम से कम फोटो अपलोड करना अनिवार्य है (Please upload property photos).',
            'photos.array' => 'फोटो अपलोड सूची सही नहीं है।',
            'photos.max' => 'अधिकतम 12 फोटो ही अपलोड कर सकते हैं (Maximum 12 photos limit).',
            'photos.*.image' => 'प्रत्येक फ़ाइल एक वैध फोटो होनी चाहिए।',
            'photos.*.mimes' => 'फोटो केवल JPG, JPEG, PNG या WebP प्रारूप में होनी चाहिए।',
            'photos.*.max' => 'प्रत्येक फोटो का साइज 15MB से कम होना चाहिए।',
            'video_file.mimes' => 'वीडियो केवल MP4, WebM या MOV प्रारूप में होना चाहिए।',
            'video_file.max' => 'वीडियो का साइज 100MB से कम होना चाहिए।',
        ]);

        // Auto-detect and normalize purpose & category
        $purpose = $validated['purpose'];
        $propType = $validated['property_type'];
        $category = $validated['category'] ?? 'residential';

        if (in_array($purpose, ['pg', 'coliving'])) {
            $category = 'pg_coliving';
        } elseif ($purpose === 'coworking') {
            $category = 'commercial';
        } elseif ($purpose === 'project') {
            $category = 'residential';
        } elseif (in_array($propType, ['Commercial Office', 'Retail Shop', 'Showroom', 'Warehouse', 'Co-Working Space', 'Industrial Shed', 'Commercial Building'])) {
            $category = 'commercial';
            if ($purpose === 'rent') {
                $purpose = 'commercial_rent';
            }
            if ($purpose === 'sale') {
                $purpose = 'commercial_sale';
            }
        } elseif (in_array($propType, ['Residential Plot', 'Commercial Land', 'Agricultural Farm Land', 'Industrial Land', 'Plot'])) {
            $category = 'plot_land';
            if ($purpose === 'sale') {
                $purpose = 'land_sale';
            }
        }

        // Handle Photos
        $gallery = [];
        if ($request->hasFile('photos')) {
            $files = array_slice($request->file('photos'), 0, 12);
            foreach ($files as $file) {
                $path = $file->store('property-uploads', 'public');
                $gallery[] = '/storage/'.$path;
            }
        }

        if (empty($gallery) && $request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('property-uploads', 'public');
            $gallery[] = '/storage/'.$path;
        }

        if ($request->filled('image_url')) {
            $gallery[] = $request->image_url;
        }

        if (! empty($gallery)) {
            $imageUrl = $gallery[0];
        } else {
            if ($category === 'plot_land') {
                $imageUrl = 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=1200&auto=format&fit=crop&q=80';
            } elseif ($category === 'commercial') {
                $imageUrl = 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=1200&auto=format&fit=crop&q=80';
            } else {
                $imageUrl = 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=1200&auto=format&fit=crop&q=80';
            }
            $gallery = [$imageUrl];
        }

        // Handle Video
        $videoUrl = null;
        if ($request->hasFile('video_file')) {
            $vPath = $request->file('video_file')->store('property-videos', 'public');
            $videoUrl = '/storage/'.$vPath;
        } elseif ($request->filled('video_url')) {
            $videoUrl = strip_tags(trim($request->video_url));
        }

        // Format Price Display
        $price = (float) $validated['price'];
        $priceDisplay = '';
        if (in_array($purpose, ['sale', 'commercial_sale', 'land_sale', 'project'])) {
            if ($price >= 10000000) {
                $priceDisplay = '₹'.rtrim(rtrim(number_format($price / 10000000, 2), '0'), '.').' Cr';
            } elseif ($price >= 100000) {
                $priceDisplay = '₹'.rtrim(rtrim(number_format($price / 100000, 2), '0'), '.').' Lakh';
            } else {
                $priceDisplay = '₹'.number_format($price);
            }
            $priceFrequency = 'total';
        } else {
            $priceDisplay = '₹'.number_format($price).'/mo';
            $priceFrequency = 'month';
        }

        // Auto-generate Title if blank
        $bhk = isset($validated['bedrooms']) && $validated['bedrooms'] > 0 ? $validated['bedrooms'].' BHK ' : '';
        $defaultTitle = $bhk.$propType.' in '.$validated['locality'].', '.$validated['city'];
        $title = ! empty($validated['title']) ? strip_tags(trim($validated['title'])) : $defaultTitle;

        // Auto-generate Description if blank
        $areaUnit = $validated['area_unit'] ?? 'sqft';
        $defaultDesc = "Verified {$propType} available for {$purpose} in {$validated['locality']}, {$validated['city']}. Features {$validated['sqft']} {$areaUnit} area with excellent road access, modern amenities, and prime connectivity. Direct listing on Housing 365.";
        $description = ! empty($validated['description']) ? strip_tags(trim($validated['description'])) : $defaultDesc;

        // Generate unique slug
        $baseSlug = Str::slug($title.'-'.$validated['city']);
        $uniqueSlug = $baseSlug.'-'.Str::lower(Str::random(5));

        // Listing Source Attribution
        $postedBy = $validated['posted_by'] ?? 'Owner';
        $listingSource = $validated['listing_source'] ?? $postedBy;

        // Property Management is strictly opt-in and NOT automatically enrolled
        $isManaged = ! empty($validated['opt_in_management']) && $validated['opt_in_management'] == '1';

        $property = Property::create([
            'user_id' => auth()->id(), // Nullable for guest/unauthenticated postings
            'title' => $title,
            'slug' => $uniqueSlug,
            'purpose' => $purpose,
            'category' => $category,
            'property_type' => strip_tags(trim($propType)),
            'city' => strip_tags(trim($validated['city'])),
            'state' => ! empty($validated['state']) ? strip_tags(trim($validated['state'])) : 'India',
            'country' => 'IN',
            'locality' => strip_tags(trim($validated['locality'])),
            'address' => ! empty($validated['address']) ? strip_tags(trim($validated['address'])) : ($validated['locality'].', '.$validated['city']),
            'price' => $price,
            'deposit_amount' => ! empty($validated['deposit_amount']) ? (float) $validated['deposit_amount'] : null,
            'price_display' => $priceDisplay,
            'price_frequency' => $priceFrequency,
            'sqft' => (int) $validated['sqft'],
            'area_unit' => $validated['area_unit'] ?? 'sqft',
            'bedrooms' => isset($validated['bedrooms']) ? (int) $validated['bedrooms'] : 0,
            'balconies' => isset($validated['balconies']) ? (int) $validated['balconies'] : 1,
            'bathrooms' => isset($validated['bathrooms']) ? (float) $validated['bathrooms'] : 1,
            'floor_no' => $validated['floor_no'] ?? 'Ground',
            'total_floors' => $validated['total_floors'] ?? '4',
            'furnishing' => $validated['furnishing'] ?? 'Unfurnished',
            'posting_as' => $validated['posting_as'] ?? 'Full House',
            'total_flats_in_society' => $validated['total_flats_in_society'] ?? null,
            'maintenance' => $validated['maintenance'] ?? null,
            'posted_by' => $postedBy,
            'listing_source' => $listingSource,
            'seller_name' => strip_tags(trim($validated['seller_name'])),
            'seller_phone' => strip_tags(trim($validated['seller_phone'])),
            'seller_email' => ! empty($validated['seller_email']) ? filter_var(trim($validated['seller_email']), FILTER_SANITIZE_EMAIL) : 'contact@housing365.in',
            'description' => $description,
            'amenities' => $validated['amenities'] ?? ['Verified Listing', 'Water Supply', 'Road Access', '24/7 Security'],
            'image' => $imageUrl,
            'gallery' => $gallery,
            'video_url' => $videoUrl,
            'status' => 'Available',
            'featured' => false,
            'is_verified' => true,
            'verification_level' => 'submitted', // Starts as submitted; advances upon inspection

            // PG / Co-living & Commercial
            'pg_gender' => $validated['pg_gender'] ?? null,
            'occupancy_type' => $validated['occupancy_type'] ?? null,
            'food_included' => ! empty($validated['food_included']),
            'house_rules' => $validated['house_rules'] ?? null,
            'rera_number' => $validated['rera_number'] ?? null,
            'project_name' => $validated['project_name'] ?? null,
            'possession_date' => $validated['possession_date'] ?? null,
            'is_managed' => $isManaged,
        ]);

        // Phase 7: Match and qualify active referral if owner was referred
        try {
            app(ReferralService::class)->matchAndQualifyListing($property);
        } catch (\Throwable $e) {
            // Log and allow property listing to proceed cleanly without interruption
            Log::warning('Referral qualification hook error: '.$e->getMessage());
        }

        return redirect()->route('properties.show', $property->slug)
            ->with('success', '🎉 Congratulations! Your property has been listed live on Housing 365 India. Potential buyers & tenants can now view your listing.');
    }

    /**
     * Handle "Need help in posting your property?" expert callback modal.
     */
    public function requestAssistance(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string|max:25',
        ]);

        $inquiry = Inquiry::create([
            'name' => 'Free Listing Assistance Request',
            'email' => 'expert-post@housing365.in',
            'phone' => strip_tags(trim($validated['phone'])),
            'service_type' => 'Post Property Assistance',
            'subject' => 'Listing Callback Request',
            'message' => 'User requested dedicated Property Expert callback on phone: '.$validated['phone'],
            'status' => 'new',
        ]);

        try {
            app(\App\Services\LeadManagementService::class)->ingestFromInquiry($inquiry, [
                'source' => 'post_property_assistance',
                'priority' => 'high',
            ]);
        } catch (\Throwable $e) {
            // Graceful fallback
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Thank you! Our property expert will call you shortly at +91 '.$validated['phone'].' to help you list your property for free.',
            ]);
        }

        return back()->with('success', 'Thank you! Our property expert will call you shortly at +91 '.$validated['phone'].' to help you list your property for free.');
    }
}
