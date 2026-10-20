<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\CityLocation;
use App\Models\Faq;
use App\Models\Inquiry;
use App\Models\Page;
use App\Models\Property;
use App\Models\Service;
use App\Models\Setting;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Services\IndiaLocationCatalog;
use App\Services\LeadManagementService;
use App\Services\ServiceCatalog;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function home()
    {
        $page = Page::where('slug', 'home')->first();
        $featuredProperties = Property::where('featured', true)->latest()->take(6)->get();
        if ($featuredProperties->count() < 6) {
            $extra = Property::where('featured', false)->latest()->take(6 - $featuredProperties->count())->get();
            $featuredProperties = $featuredProperties->concat($extra);
        }
        $services = Service::where('is_active', true)->orderBy('order')->take(8)->get();
        $cities = CityLocation::where('is_featured', true)->take(6)->get();
        $testimonials = Testimonial::where('is_featured', true)->orderBy('order')->take(3)->get();
        $faqs = Faq::where('is_active', true)->orderBy('order')->take(5)->get();
        $latestPosts = BlogPost::where('is_published', true)->latest('published_at')->take(3)->get();

        return view('home', compact(
            'page',
            'featuredProperties',
            'services',
            'cities',
            'testimonials',
            'faqs',
            'latestPosts'
        ));
    }

    public function about()
    {
        $page = Page::where('slug', 'about')->first();
        $team = TeamMember::orderBy('order')->get();
        $stats = [
            'properties' => Setting::get('stat_properties', '10K+'),
            'satisfaction' => Setting::get('stat_satisfaction', '98%'),
            'cities' => Setting::get('stat_cities', '50+'),
            'volume' => Setting::get('stat_volume', 'End-to-End'),
        ];

        return view('about', compact('page', 'team', 'stats'));
    }

    public function services()
    {
        $services = Service::where('is_active', true)->orderBy('order')->get();
        $faqs = Faq::where('is_active', true)->orderBy('order')->get();

        return view('services', compact('services', 'faqs'));
    }

    public function locations()
    {
        return $this->indiaIndex();
    }

    public function pricing()
    {
        $page = Page::where('slug', 'pricing')->first();
        $faqs = Faq::where('category', 'Pricing')->orderBy('order')->get();

        return view('pricing', compact('page', 'faqs'));
    }

    public function reviews()
    {
        $testimonials = Testimonial::orderBy('order')->latest()->paginate(9);

        return view('reviews', compact('testimonials'));
    }

    public function contact()
    {
        $page = Page::where('slug', 'contact')->first();

        return view('contact', compact('page'));
    }

    public function storeInquiry(Request $request)
    {
        // 1. Honeypot check: If the hidden honeypot field is filled, silently discard (bot detected)
        if ($request->filled('website_url_hp')) {
            return back()->with('success', 'Thank you! Your message has been received. Our team will contact you shortly.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email:rfc,filter|max:255',
            'phone' => 'nullable|string|max:50',
            'service_type' => 'nullable|string|max:100',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $inquiry = Inquiry::create([
            'name' => strip_tags(trim($validated['name'])),
            'email' => filter_var(trim($validated['email']), FILTER_SANITIZE_EMAIL),
            'phone' => ! empty($validated['phone']) ? strip_tags(trim($validated['phone'])) : null,
            'service_type' => strip_tags($validated['service_type'] ?? 'General'),
            'subject' => strip_tags($validated['subject'] ?? 'Website Inquiry'),
            'message' => strip_tags(trim($validated['message'])),
            'status' => 'new',
        ]);

        try {
            app(LeadManagementService::class)->ingestFromInquiry($inquiry, [
                'source' => 'website_contact',
                'priority' => 'normal',
            ]);
        } catch (\Throwable $e) {
            // Graceful fallback
        }

        return back()->with('success', 'Thank you! Your message has been received. Our team will contact you shortly.');
    }

    public function serviceDetail(string $slug)
    {
        $service = ServiceCatalog::find($slug);
        if (! $service) {
            abort(404, 'Service not found');
        }

        $relatedServices = ServiceCatalog::getRelated($slug, 3);
        $faqs = $service['faqs'] ?? [];
        $heroBackdrop = ServiceCatalog::getHeroBackdrop($slug);

        return view('services.show', compact('service', 'relatedServices', 'faqs', 'heroBackdrop'));
    }

    public function owners()
    {
        $page = Page::where('slug', 'owners')->first();
        $services = Service::where('is_active', true)->orderBy('order')->take(6)->get();
        $testimonials = Testimonial::where('is_featured', true)->take(3)->get();
        $faqs = Faq::where('is_active', true)->take(5)->get();

        return view('owners', compact('page', 'services', 'testimonials', 'faqs'));
    }

    public function tenants()
    {
        $page = Page::where('slug', 'tenants')->first();
        $featuredProperties = Property::where('featured', true)->take(6)->get();
        $faqs = Faq::where('is_active', true)->take(5)->get();

        return view('tenants', compact('page', 'featuredProperties', 'faqs'));
    }

    public function propertyManagers()
    {
        $page = Page::where('slug', 'property-managers')->first();
        $faqs = Faq::where('is_active', true)->take(5)->get();

        return view('property-managers', compact('page', 'faqs'));
    }

    public function portalTechnology()
    {
        $page = Page::where('slug', 'portal-and-technology')->first();

        return view('portal-and-technology', compact('page'));
    }

    public function resources()
    {
        return view('resources');
    }

    public function franchising()
    {
        return view('franchising');
    }

    public function privacyPolicy()
    {
        return view('privacy-policy');
    }

    public function termsOfService()
    {
        return view('terms');
    }

    public function activatePrime(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Prime membership activated successfully.',
            'whatsapp_url' => 'https://wa.me/916376163263?text=' . urlencode('Hi Housing365, I have activated Prime membership for ' . $request->input('plan', 'Prime')),
            'redirect_url' => route('pricing'),
        ]);
    }

    public function housing365Care()
    {
        return view('services.care');
    }

    public function locationDetail(string $city)
    {
        $cityRecord = CityLocation::where('slug', $city)
            ->orWhere('name', 'LIKE', $city)
            ->first();

        if ($cityRecord) {
            $properties = Property::where('city', 'LIKE', "%{$cityRecord->name}%")
                ->take(6)
                ->get();

            return view('locations.show', [
                'city' => $cityRecord,
                'properties' => $properties,
            ]);
        }

        // Check IndiaLocationCatalog
        $states = IndiaLocationCatalog::getStates();
        $cityLower = strtolower($city);
        foreach ($states as $sSlug => $sData) {
            if (isset($sData['cities'][$cityLower])) {
                return redirect()->route('india.city', ['state' => $sSlug, 'city' => $cityLower]);
            }
            foreach ($sData['cities'] as $cSlug => $cData) {
                if (strtolower($cData['name']) === $cityLower) {
                    return redirect()->route('india.city', ['state' => $sSlug, 'city' => $cSlug]);
                }
            }
        }

        abort(404, 'Indian City location not found');
    }

    public function indiaIndex()
    {
        $states = IndiaLocationCatalog::getStates();

        return view('locations.india-index', compact('states'));
    }

    public function indiaState(string $state)
    {
        $states = IndiaLocationCatalog::getStates();
        $stateKey = strtolower($state);
        if (! isset($states[$stateKey])) {
            abort(404, 'Indian State not found');
        }

        $stateData = $states[$stateKey];

        return view('locations.india-state', [
            'stateSlug' => $stateKey,
            'stateData' => $stateData,
        ]);
    }

    public function indiaCity(string $state, string $city)
    {
        $states = IndiaLocationCatalog::getStates();
        $stateKey = strtolower($state);
        $cityKey = strtolower($city);

        if (! isset($states[$stateKey]) || ! isset($states[$stateKey]['cities'][$cityKey])) {
            abort(404, 'Indian City not found');
        }

        $stateData = $states[$stateKey];
        $cityData = $stateData['cities'][$cityKey];
        $properties = Property::where('city', 'LIKE', '%'.$cityData['name'].'%')->take(3)->get();

        return view('locations.india-city', [
            'stateSlug' => $stateKey,
            'citySlug' => $cityKey,
            'stateData' => $stateData,
            'cityData' => $cityData,
            'properties' => $properties,
        ]);
    }
}
