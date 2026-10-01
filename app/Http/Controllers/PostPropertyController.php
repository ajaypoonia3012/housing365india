<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\CityLocation;
use App\Models\Inquiry;
use Illuminate\Support\Str;

class PostPropertyController extends Controller
{
    /**
     * Show the Post Property form (matching post.magicbricks.com 2-stage UX).
     */
    public function create()
    {
        $cities = CityLocation::orderBy('name')->pluck('name');
        
        $categories = [
            'residential' => [
                'label' => 'Residential',
                'icon' => 'fa-house',
                'types' => ['Flat / Apartment', 'House / Villa', 'Builder Floor', 'Penthouse', 'Studio Apartment']
            ],
            'commercial' => [
                'label' => 'Commercial',
                'icon' => 'fa-building',
                'types' => ['Commercial Office', 'Retail Shop', 'Showroom', 'Warehouse', 'Co-Working Space', 'Industrial Shed']
            ],
            'plot_land' => [
                'label' => 'Plots & Land',
                'icon' => 'fa-map',
                'types' => ['Residential Plot', 'Commercial Land', 'Agricultural Farm Land', 'Industrial Land']
            ]
        ];

        return view('properties.post', compact('cities', 'categories'));
    }

    /**
     * Store the posted property.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'purpose' => 'required|in:rent,sale,lease',
            'category' => 'nullable|in:residential,commercial,plot_land',
            'property_type' => 'required|string|max:100',
            'posting_as' => 'nullable|string|max:50',
            'total_flats_in_society' => 'nullable|string|max:50',
            'title' => 'nullable|string|max:180',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'locality' => 'required|string|max:150',
            'address' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:100',
            'maintenance' => 'nullable|string|max:100',
            'price_frequency' => 'nullable|string|max:20',
            'sqft' => 'required|numeric|min:10',
            'area_unit' => 'nullable|in:sqft,sq.yards,acres,bigha',
            'bedrooms' => 'nullable|integer|min:0|max:20',
            'balconies' => 'nullable|integer|min:0|max:10',
            'bathrooms' => 'nullable|numeric|min:0|max:20',
            'floor_no' => 'nullable|string|max:50',
            'total_floors' => 'nullable|string|max:50',
            'furnishing' => 'nullable|string|max:50',
            'posted_by' => 'nullable|in:Owner,Builder,Agent',
            'seller_name' => 'required|string|max:100',
            'seller_phone' => 'required|string|max:25',
            'seller_email' => 'nullable|email|max:150',
            'description' => 'nullable|string|max:5000',
            'amenities' => 'nullable|array',
            'photos' => 'required|array|min:5|max:12',
            'photos.*' => 'image|mimes:jpeg,png,jpg,webp|max:15360',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:15360',
            'image_url' => 'nullable|url|max:500',
            'video_file' => 'nullable|file|mimes:mp4,webm,mov,mkv,avi|max:102400',
            'video_url' => 'nullable|string|max:500',
        ], [
            'photos.required' => 'कम से कम 5 फोटो अपलोड करना अनिवार्य है (Please upload at least 5 property photos).',
            'photos.array' => 'फोटो अपलोड सूची सही नहीं है।',
            'photos.min' => 'कम से कम 5 फोटो अपलोड करना अनिवार्य है (Minimum 5 photos are required).',
            'photos.max' => 'अधिकतम 12 फोटो ही अपलोड कर सकते हैं (Maximum 12 photos limit).',
            'photos.*.image' => 'प्रत्येक फ़ाइल एक वैध फोटो होनी चाहिए।',
            'photos.*.mimes' => 'फोटो केवल JPG, JPEG, PNG या WebP प्रारूप में होनी चाहिए।',
            'photos.*.max' => 'प्रत्येक फोटो का साइज 15MB से कम होना चाहिए।',
            'video_file.mimes' => 'वीडियो केवल MP4, WebM या MOV प्रारूप में होना चाहिए।',
            'video_file.max' => 'वीडियो का साइज 100MB से कम होना चाहिए।',
        ]);

        // Auto-detect category if not explicitly provided
        $category = $validated['category'] ?? 'residential';
        $propType = $validated['property_type'];
        if (in_array($propType, ['Commercial Office', 'Retail Shop', 'Showroom', 'Warehouse', 'Co-Working Space', 'Industrial Shed'])) {
            $category = 'commercial';
        } elseif (in_array($propType, ['Residential Plot', 'Commercial Land', 'Agricultural Farm Land', 'Industrial Land', 'Plot'])) {
            $category = 'plot_land';
        }

        // 1. Handle Multiple Photos (All 5-12 photos saved to gallery)
        $gallery = [];
        if ($request->hasFile('photos')) {
            $files = $request->file('photos');
            // Enforce max 12
            $files = array_slice($files, 0, 12);
            foreach ($files as $file) {
                $path = $file->store('property-uploads', 'public');
                $gallery[] = '/storage/' . $path;
            }
        }

        // Support fallback single image if provided
        if (empty($gallery) && $request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('property-uploads', 'public');
            $gallery[] = '/storage/' . $path;
        }

        if ($request->filled('image_url')) {
            $gallery[] = $request->image_url;
        }

        if (!empty($gallery)) {
            $imageUrl = $gallery[0];
        } else {
            // Category-specific high-res default fallback
            if ($category === 'plot_land') {
                $imageUrl = 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=1200&auto=format&fit=crop&q=80';
            } elseif ($category === 'commercial') {
                $imageUrl = 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=1200&auto=format&fit=crop&q=80';
            } else {
                $imageUrl = 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=1200&auto=format&fit=crop&q=80';
            }
            $gallery = [$imageUrl];
        }

        // 1b. Handle Video (Optional 1 Video upload or URL)
        $videoUrl = null;
        if ($request->hasFile('video_file')) {
            $vPath = $request->file('video_file')->store('property-videos', 'public');
            $videoUrl = '/storage/' . $vPath;
        } elseif ($request->filled('video_url')) {
            $videoUrl = strip_tags(trim($request->video_url));
        }

        // 2. Format Price Display
        $price = (float)$validated['price'];
        $priceDisplay = '';
        if ($validated['purpose'] === 'sale') {
            if ($price >= 10000000) {
                $priceDisplay = '₹' . rtrim(rtrim(number_format($price / 10000000, 2), '0'), '.') . ' Cr';
            } elseif ($price >= 100000) {
                $priceDisplay = '₹' . rtrim(rtrim(number_format($price / 100000, 2), '0'), '.') . ' Lakh';
            } else {
                $priceDisplay = '₹' . number_format($price);
            }
            $priceFrequency = 'total';
        } else {
            $priceDisplay = '₹' . number_format($price) . '/mo';
            $priceFrequency = 'month';
        }

        // 3. Auto-generate Title if blank
        $bhk = isset($validated['bedrooms']) && $validated['bedrooms'] > 0 ? $validated['bedrooms'] . ' BHK ' : '';
        $defaultTitle = $bhk . $propType . ' in ' . $validated['locality'] . ', ' . $validated['city'];
        $title = !empty($validated['title']) ? strip_tags(trim($validated['title'])) : $defaultTitle;

        // 4. Auto-generate Description if blank
        $defaultDesc = "Verified {$propType} available for " . ($validated['purpose'] === 'sale' ? 'purchase' : 'rent') . " in {$validated['locality']}, {$validated['city']}. Features {$validated['sqft']} {$validated['area_unit']} area with excellent road access, modern amenities, and prime connectivity. Direct contact with {$validated['posted_by']} - zero brokerage.";
        $description = !empty($validated['description']) ? strip_tags(trim($validated['description'])) : $defaultDesc;

        // 5. Generate unique slug
        $baseSlug = Str::slug($title . '-' . $validated['city']);
        $uniqueSlug = $baseSlug . '-' . Str::lower(Str::random(5));

        // 6. Create property
        $property = Property::create([
            'title' => $title,
            'slug' => $uniqueSlug,
            'purpose' => $validated['purpose'],
            'category' => $category,
            'property_type' => strip_tags(trim($propType)),
            'city' => strip_tags(trim($validated['city'])),
            'state' => !empty($validated['state']) ? strip_tags(trim($validated['state'])) : 'India',
            'country' => 'IN',
            'locality' => strip_tags(trim($validated['locality'])),
            'address' => !empty($validated['address']) ? strip_tags(trim($validated['address'])) : ($validated['locality'] . ', ' . $validated['city']),
            'price' => $price,
            'price_display' => $priceDisplay,
            'price_frequency' => $priceFrequency,
            'sqft' => (int)$validated['sqft'],
            'area_unit' => $validated['area_unit'] ?? 'sqft',
            'bedrooms' => isset($validated['bedrooms']) ? (int)$validated['bedrooms'] : 0,
            'balconies' => isset($validated['balconies']) ? (int)$validated['balconies'] : 1,
            'bathrooms' => isset($validated['bathrooms']) ? (float)$validated['bathrooms'] : 1,
            'floor_no' => $validated['floor_no'] ?? 'Ground',
            'total_floors' => $validated['total_floors'] ?? '4',
            'furnishing' => $validated['furnishing'] ?? 'Unfurnished',
            'posting_as' => $validated['posting_as'] ?? 'Full House',
            'total_flats_in_society' => $validated['total_flats_in_society'] ?? null,
            'maintenance' => $validated['maintenance'] ?? null,
            'posted_by' => $validated['posted_by'] ?? 'Owner',
            'seller_name' => strip_tags(trim($validated['seller_name'])),
            'seller_phone' => strip_tags(trim($validated['seller_phone'])),
            'seller_email' => !empty($validated['seller_email']) ? filter_var(trim($validated['seller_email']), FILTER_SANITIZE_EMAIL) : 'contact@housing365.in',
            'description' => $description,
            'amenities' => $validated['amenities'] ?? ['Verified Listing', 'Water Supply', 'Road Access', '24/7 Security'],
            'image' => $imageUrl,
            'gallery' => $gallery,
            'video_url' => $videoUrl,
            'status' => 'Available',
            'featured' => false,
            'is_verified' => true,
        ]);

        return redirect()->route('properties.show', $property->slug)
            ->with('success', '🎉 Congratulations! Your property has been listed live on Housing 365 India. Potential buyers & tenants can now view your listing and contact you directly.');
    }

    /**
     * Handle "Need help in posting your property?" expert callback modal.
     */
    public function requestAssistance(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string|max:25',
        ]);

        Inquiry::create([
            'name' => 'Free Listing Assistance Request',
            'email' => 'expert-post@housing365.in',
            'phone' => strip_tags(trim($validated['phone'])),
            'service_type' => 'Post Property Assistance',
            'subject' => 'Listing Callback Request',
            'message' => 'User requested dedicated Property Expert callback on phone: ' . $validated['phone'],
            'status' => 'new',
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Thank you! Our property expert will call you shortly at +91 ' . $validated['phone'] . ' to help you list your property for free.',
            ]);
        }

        return back()->with('success', 'Thank you! Our property expert will call you shortly at +91 ' . $validated['phone'] . ' to help you list your property for free.');
    }
}
