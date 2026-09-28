<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\CityLocation;
use Illuminate\Support\Str;

class PostPropertyController extends Controller
{
    /**
     * Show the Post Property form (like OLX, Quikr, MagicBricks).
     */
    public function create()
    {
        $cities = CityLocation::orderBy('name')->pluck('name');
        
        $categories = [
            'residential' => [
                'label' => 'Residential',
                'icon' => 'fa-house',
                'types' => ['Apartment', 'Independent House', 'Builder Floor', 'Villa', 'Penthouse', 'Studio Apartment']
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
            'category' => 'required|in:residential,commercial,plot_land',
            'property_type' => 'required|string|max:100',
            'title' => 'required|string|max:180',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'locality' => 'required|string|max:150',
            'address' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:500',
            'price_frequency' => 'nullable|string|max:20',
            'sqft' => 'required|numeric|min:10',
            'area_unit' => 'nullable|in:sqft,sq.yards,acres,bigha',
            'bedrooms' => 'nullable|integer|min:0|max:20',
            'bathrooms' => 'nullable|numeric|min:0|max:20',
            'furnishing' => 'nullable|string|max:50',
            'posted_by' => 'nullable|in:Owner,Builder,Agent',
            'seller_name' => 'required|string|max:100',
            'seller_phone' => 'required|string|max:25',
            'seller_email' => 'nullable|email|max:150',
            'description' => 'required|string|min:15|max:5000',
            'amenities' => 'nullable|array',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',
            'image_url' => 'nullable|url|max:500',
        ]);

        // 1. Handle Image
        $imageUrl = null;
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('property-uploads', 'public');
            $imageUrl = '/storage/' . $path;
        } elseif ($request->filled('image_url')) {
            $imageUrl = $request->image_url;
        } else {
            // Category-specific high-res default fallback
            if ($validated['category'] === 'plot_land') {
                $imageUrl = 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=1200&auto=format&fit=crop&q=80';
            } elseif ($validated['category'] === 'commercial') {
                $imageUrl = 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=1200&auto=format&fit=crop&q=80';
            } else {
                $imageUrl = 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=1200&auto=format&fit=crop&q=80';
            }
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

        // 3. Generate unique slug
        $baseSlug = Str::slug($validated['title'] . '-' . $validated['city']);
        $uniqueSlug = $baseSlug . '-' . Str::lower(Str::random(5));

        // 4. Create property
        $property = Property::create([
            'title' => strip_tags(trim($validated['title'])),
            'slug' => $uniqueSlug,
            'purpose' => $validated['purpose'],
            'category' => $validated['category'],
            'property_type' => strip_tags(trim($validated['property_type'])),
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
            'bathrooms' => isset($validated['bathrooms']) ? (float)$validated['bathrooms'] : 0,
            'furnishing' => $validated['furnishing'] ?? 'Unfurnished',
            'posted_by' => $validated['posted_by'] ?? 'Owner',
            'seller_name' => strip_tags(trim($validated['seller_name'])),
            'seller_phone' => strip_tags(trim($validated['seller_phone'])),
            'seller_email' => !empty($validated['seller_email']) ? filter_var(trim($validated['seller_email']), FILTER_SANITIZE_EMAIL) : 'contact@housing365.in',
            'description' => strip_tags(trim($validated['description'])),
            'amenities' => $validated['amenities'] ?? ['Verified Listing', 'Water Supply', 'Road Access'],
            'image' => $imageUrl,
            'status' => 'Available',
            'featured' => false,
            'is_verified' => true,
        ]);

        return redirect()->route('properties.show', $property->slug)
            ->with('success', '🎉 Congratulations! Your property has been listed live on Housing 365 India. Potential buyers & tenants can now view your listing and contact you directly.');
    }
}
