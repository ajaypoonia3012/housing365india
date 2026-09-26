@extends('layouts.app')

@php
    $stateName = $state['name'] ?? ucfirst($city['stateSlug'] ?? 'State');
    $stateAbbr = $state['abbreviation'] ?? strtoupper(substr($city['stateSlug'] ?? 'US', 0, 2));
    $cityTitle = $city['title'] ?? 'City';
    $medianRent = isset($city['medianRent']) ? '$' . number_format($city['medianRent']) : '$2,200';
    $population = isset($city['population']) ? number_format($city['population']) : '300,000+';
    $bgImage = $city['heroImageUrl'] ?? 'https://images.unsplash.com/photo-1444723121867-7a241cacace9?auto=format&fit=crop&w=2000&q=80';
@endphp

@section('title', "Leasing Services & Property Management in {$cityTitle}, {$stateAbbr} | Housing 365")
@section('meta_description', "Full-service leasing and tenant placement in {$cityTitle}, {$stateName}: 75-point tenant screening, guaranteed rent, compliant lease execution, and digital showings.")

@section('content')

<!-- ── BREADCRUMB ── -->
<div class="bg-white border-b border-slate-100 py-3.5 text-xs text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-cyan transition font-medium">Home</a>
        <span class="text-slate-300">/</span>
        <a href="{{ url('/us') }}" class="hover:text-brand-cyan transition font-medium">United States</a>
        <span class="text-slate-300">/</span>
        <a href="{{ url('/us/' . ($city['stateSlug'] ?? '')) }}" class="hover:text-brand-cyan transition font-medium">{{ $stateName }}</a>
        <span class="text-slate-300">/</span>
        <span class="text-brand-navy font-bold truncate">{{ $cityTitle }}</span>
    </div>
</div>

<!-- ── HERO BLOCK (LIGHT MODERN DESIGN) ── -->
<section class="relative bg-gradient-to-b from-[#F7FAFD] via-white to-[#F0F6FC] overflow-hidden py-16 sm:py-24">
    <div class="pointer-events-none absolute -left-40 top-0 h-[500px] w-[500px] rounded-full opacity-40 blur-3xl" style="background: radial-gradient(circle, rgba(0,174,239,0.18) 0%, transparent 70%)" aria-hidden="true"></div>
    <div class="pointer-events-none absolute -right-20 bottom-0 h-96 w-96 rounded-full opacity-30 blur-3xl" style="background: radial-gradient(circle, rgba(0,174,239,0.12) 0%, transparent 70%)" aria-hidden="true"></div>

    <div class="relative z-10 mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-7">
                <div class="inline-flex items-center gap-2 rounded-full border border-brand-cyan/20 bg-brand-cyan/5 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-brand-cyan mb-4">
                    <span class="size-2 rounded-full bg-brand-cyan animate-pulse"></span>
                    {{ $cityTitle }}, {{ $stateAbbr }} LEASING METRO
                </div>

                <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-brand-navy leading-[1.1]">
                    End-to-End Leasing in <br>
                    <span class="text-brand-cyan font-extrabold">{{ $cityTitle }}, {{ $stateAbbr }}</span>
                </h1>

                <p class="mt-6 text-base sm:text-lg text-slate-600 leading-relaxed max-w-2xl">
                    {{ $city['marketNote'] ?? "Full-service leasing and licensed property placement in {$cityTitle}. Zero upfront fees, 75-point tenant vetting, guaranteed on-time rent, and legal compliance under {$stateName} tenancy statutes." }}
                </p>

                <div class="mt-8 flex flex-wrap gap-4 items-center">
                    <a href="{{ route('contact') }}" class="px-8 py-4 rounded-xl bg-brand-cyan hover:bg-brand-cyan-hover text-white text-xs sm:text-sm font-bold uppercase tracking-wider shadow-lg shadow-brand-cyan/25 transition-all duration-200 hover:-translate-y-0.5">
                        Request Rental Analysis
                    </a>
                    <a href="{{ route('properties.index', ['city' => $cityTitle]) }}" class="px-7 py-4 rounded-xl bg-white hover:bg-slate-50 text-brand-navy text-xs sm:text-sm font-bold uppercase tracking-wider border border-slate-300 shadow-sm transition-all duration-200">
                        Browse {{ $cityTitle }} Rentals
                    </a>
                </div>
            </div>

            <div class="lg:col-span-5">
                <div class="relative rounded-3xl overflow-hidden shadow-2xl border border-slate-200/80 bg-white">
                    <div class="h-80 sm:h-96 relative overflow-hidden">
                        <img src="{{ $bgImage }}" 
                             alt="{{ $city['heroImageAlt'] ?? ($cityTitle . ' skyline') }}" 
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-brand-navy/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-6 left-6 right-6 text-white">
                            <span class="text-xs font-bold uppercase tracking-wider text-brand-cyan">{{ $stateAbbr }} Real Estate</span>
                            <h3 class="font-display text-2xl font-bold text-white">{{ $cityTitle }} Metropolitan Area</h3>
                            <p class="text-xs text-slate-200 mt-1">Live showing coverage and multi-family placement</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── MARKET STATS STRIP ── -->
<section class="bg-white border-b border-slate-200/80 py-8 shadow-sm">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div class="p-4 border-r border-slate-100 last:border-0">
                <div class="text-xs uppercase font-bold tracking-wider text-slate-400 mb-1">Median Rent</div>
                <div class="font-display text-2xl sm:text-3xl font-bold text-brand-navy">{{ $medianRent }}<span class="text-xs font-semibold text-slate-400">/mo</span></div>
                <div class="text-[11px] text-slate-500 mt-1">Market Benchmark</div>
            </div>
            <div class="p-4 border-r border-slate-100 last:border-0">
                <div class="text-xs uppercase font-bold tracking-wider text-slate-400 mb-1">Metro Population</div>
                <div class="font-display text-2xl sm:text-3xl font-bold text-brand-navy">{{ $population }}</div>
                <div class="text-[11px] text-slate-500 mt-1">Active Renter Pool</div>
            </div>
            <div class="p-4 border-r border-slate-100 last:border-0">
                <div class="text-xs uppercase font-bold tracking-wider text-slate-400 mb-1">Average Lease-Up</div>
                <div class="font-display text-2xl sm:text-3xl font-bold text-brand-cyan">14 Days</div>
                <div class="text-[11px] text-slate-500 mt-1">From syndication to signed lease</div>
            </div>
            <div class="p-4">
                <div class="text-xs uppercase font-bold tracking-wider text-slate-400 mb-1">Screening Standard</div>
                <div class="font-display text-2xl sm:text-3xl font-bold text-brand-navy">75-Point</div>
                <div class="text-[11px] text-emerald-600 font-bold mt-1"><i class="fa-solid fa-shield-check mr-1"></i>0.1% Eviction Rate</div>
            </div>
        </div>
    </div>
</section>

<!-- ── MARKET NARRATIVE & RENTAL ANALYSIS FORM (LIGHT DESIGN) ── -->
<section class="py-16 sm:py-20 bg-[#F8FAFD]">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-7 space-y-6">
                <div class="inline-flex items-center gap-2 rounded-full border border-brand-cyan/20 bg-brand-cyan/5 px-3 py-1 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                    <i class="fa-solid fa-chart-line"></i> Local Real Estate Intelligence
                </div>
                <h2 class="font-display text-3xl sm:text-4xl text-brand-navy font-extrabold tracking-tight">
                    Navigating the Rental Landscape in <span class="text-brand-cyan">{{ $cityTitle }}</span>
                </h2>
                <p class="text-slate-600 text-base sm:text-lg leading-relaxed">
                    {{ $city['marketNote'] ?? "As one of {$stateName}'s key residential hubs, {$cityTitle} requires careful pricing, robust marketing, and comprehensive applicant vetting." }}
                </p>
                <div class="space-y-4 pt-2">
                    <div class="flex items-start gap-4 p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
                        <div class="size-10 rounded-xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-lg shrink-0 mt-0.5">
                            <i class="fa-solid fa-bullhorn"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-brand-navy text-sm sm:text-base">Omnichannel Syndication</h4>
                            <p class="text-sm text-slate-600 mt-1">Direct API distribution to Zillow, Trulia, HotPads, Realtor.com, MLS, and local housing networks across {{ $cityTitle }}.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
                        <div class="size-10 rounded-xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-lg shrink-0 mt-0.5">
                            <i class="fa-solid fa-scale-balanced"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-brand-navy text-sm sm:text-base">{{ $stateName }} Tenancy Compliance</h4>
                            <p class="text-sm text-slate-600 mt-1">Lease agreements rigorously reviewed against {{ $stateName }} state statutes, security deposit rules, and local fair-housing guidelines.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
                        <div class="size-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-lg shrink-0 mt-0.5">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-brand-navy text-sm sm:text-base">Guaranteed Rent Protection</h4>
                            <p class="text-sm text-slate-600 mt-1">Optional continuous cash flow backing: 100% on-time rent disbursement even in unforeseen resident default events.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5">
                <div class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200/80 shadow-xl relative overflow-hidden">
                    <div class="absolute -right-10 -bottom-10 size-48 rounded-full bg-brand-cyan/10 blur-2xl"></div>
                    <h3 class="font-display text-2xl text-brand-navy font-extrabold mb-2">Get {{ $cityTitle }} Rental Estimate</h3>
                    <p class="text-sm text-slate-600 mb-6">Discover the optimal monthly rent, vacancy risk, and projected yield for your property.</p>

                    <form action="{{ route('inquiry.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="hidden" style="display:none !important;" aria-hidden="true">
                            <label for="website_url_hp">Security Check - Leave Empty</label>
                            <input type="text" name="website_url_hp" id="website_url_hp" tabindex="-1" autocomplete="off">
                        </div>
                        <input type="hidden" name="service_type" value="Rental Market Analysis">
                        <input type="hidden" name="subject" value="Rental Analysis Request for {{ $cityTitle }}, {{ $stateAbbr }}">

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Your Name</label>
                            <input type="text" name="name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan" placeholder="First & Last Name">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Email Address</label>
                            <input type="email" name="email" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan" placeholder="name@domain.com">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Property Address or Postal Code</label>
                            <input type="text" name="message" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan" placeholder="e.g. 123 Main St, {{ $cityTitle }}">
                        </div>
                        <button type="submit" class="w-full py-4 rounded-xl bg-brand-cyan hover:bg-brand-cyan-hover text-white text-xs font-bold uppercase tracking-wider shadow-lg shadow-brand-cyan/25 transition">
                            Generate Market Report
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── LOCALIZED SERVICES GRID ── -->
<section class="py-16 sm:py-20 bg-white">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="text-xs font-bold uppercase tracking-widest text-brand-cyan">OUR SPECIALTIES</span>
            <h2 class="font-display text-3xl sm:text-4xl text-brand-navy font-extrabold mt-2">
                Leasing Solutions in {{ $cityTitle }}
            </h2>
            <p class="text-slate-600 text-sm mt-3">From individual condo units to multi-family portfolios, every service is tailored to {{ $cityTitle }}'s municipal codes.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="p-8 rounded-3xl bg-[#F8FAFD] border border-slate-200/80 hover:border-brand-cyan transition duration-300 group">
                <div class="size-12 rounded-2xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-xl mb-6 group-hover:scale-110 transition">
                    <i class="fa-solid fa-users-viewfinder"></i>
                </div>
                <h3 class="font-display text-xl text-brand-navy font-bold mb-2">Tenant Placement</h3>
                <p class="text-sm text-slate-600 leading-relaxed mb-4">Complete candidate sourcing, high-res photos, 3D tours, in-person showings, and lease signing.</p>
                <a href="{{ url('/services/tenant-placement') }}" class="text-xs font-bold text-brand-cyan hover:underline flex items-center gap-1.5">
                    Explore Placement <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="p-8 rounded-3xl bg-[#F8FAFD] border border-slate-200/80 hover:border-brand-cyan transition duration-300 group">
                <div class="size-12 rounded-2xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-xl mb-6 group-hover:scale-110 transition">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
                <h3 class="font-display text-xl text-brand-navy font-bold mb-2">75-Point Tenant Screening</h3>
                <p class="text-sm text-slate-600 leading-relaxed mb-4">Equifax/TransUnion tri-bureau credit reports, criminal background checks, income validation, and prior landlord interviews.</p>
                <a href="{{ url('/services/tenant-screening') }}" class="text-xs font-bold text-brand-cyan hover:underline flex items-center gap-1.5">
                    Screening Process <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="p-8 rounded-3xl bg-[#F8FAFD] border border-slate-200/80 hover:border-brand-cyan transition duration-300 group">
                <div class="size-12 rounded-2xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-xl mb-6 group-hover:scale-110 transition">
                    <i class="fa-solid fa-sack-dollar"></i>
                </div>
                <h3 class="font-display text-xl text-brand-navy font-bold mb-2">Rent Guarantee</h3>
                <p class="text-sm text-slate-600 leading-relaxed mb-4">Direct rent disbursements guaranteed on the 1st of every month, backed by our institutional protection fund.</p>
                <a href="{{ url('/services/rent-guarantee') }}" class="text-xs font-bold text-brand-cyan hover:underline flex items-center gap-1.5">
                    Guarantee Terms <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="p-8 rounded-3xl bg-[#F8FAFD] border border-slate-200/80 hover:border-brand-cyan transition duration-300 group">
                <div class="size-12 rounded-2xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-xl mb-6 group-hover:scale-110 transition">
                    <i class="fa-solid fa-file-signature"></i>
                </div>
                <h3 class="font-display text-xl text-brand-navy font-bold mb-2">Compliant Lease Execution</h3>
                <p class="text-sm text-slate-600 leading-relaxed mb-4">State-specific residential lease contracts, digital signatures, deposit escrow collection, and tenant onboarding.</p>
                <a href="{{ url('/services/lease-execution') }}" class="text-xs font-bold text-brand-cyan hover:underline flex items-center gap-1.5">
                    Leasing Standards <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="p-8 rounded-3xl bg-[#F8FAFD] border border-slate-200/80 hover:border-brand-cyan transition duration-300 group">
                <div class="size-12 rounded-2xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-xl mb-6 group-hover:scale-110 transition">
                    <i class="fa-solid fa-laptop-code"></i>
                </div>
                <h3 class="font-display text-xl text-brand-navy font-bold mb-2">Portal & Technology</h3>
                <p class="text-sm text-slate-600 leading-relaxed mb-4">Real-time owner portal with live showing logs, applicant profiles, financial statements, and automated banking.</p>
                <a href="{{ url('/portal-and-technology') }}" class="text-xs font-bold text-brand-cyan hover:underline flex items-center gap-1.5">
                    View Platform <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="p-8 rounded-3xl bg-[#F8FAFD] border border-slate-200/80 hover:border-brand-cyan transition duration-300 group">
                <div class="size-12 rounded-2xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-xl mb-6 group-hover:scale-110 transition">
                    <i class="fa-solid fa-building-circle-check"></i>
                </div>
                <h3 class="font-display text-xl text-brand-navy font-bold mb-2">Institutional Lease-Up</h3>
                <p class="text-sm text-slate-600 leading-relaxed mb-4">High-velocity multi-family lease up campaigns for REITs, developers, and institutional property managers.</p>
                <a href="{{ url('/services/institutional-lease-up') }}" class="text-xs font-bold text-brand-cyan hover:underline flex items-center gap-1.5">
                    Developer Solutions <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ── VERIFIED RENTALS IN THIS REGION ── -->
@if(isset($properties) && count($properties) > 0)
<section class="py-16 sm:py-20 bg-[#F8FAFD] border-t border-slate-200/80">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-brand-cyan">NOW AVAILABLE</span>
                <h2 class="font-display text-3xl sm:text-4xl text-brand-navy font-extrabold mt-1">
                    Featured Properties in {{ $cityTitle }}
                </h2>
            </div>
            <a href="{{ route('properties.index', ['city' => $cityTitle]) }}" class="mt-4 sm:mt-0 text-xs font-bold uppercase tracking-wider text-brand-navy hover:text-brand-cyan flex items-center gap-1">
                View All Listings <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($properties as $property)
            <div class="bg-white rounded-3xl overflow-hidden border border-slate-200/80 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col group">
                <div class="h-60 bg-slate-100 relative overflow-hidden">
                    <img src="{{ $property->image ?? '/city-placeholder-c.svg' }}" 
                         alt="{{ $property->title }}" 
                         onerror="this.src='/city-placeholder-c.svg'"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-4 left-4 flex gap-2">
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-brand-navy text-white">
                            {{ $property->property_type }}
                        </span>
                    </div>
                    <div class="absolute bottom-4 left-4 right-4 flex justify-between items-end text-white">
                        <div>
                            <span class="font-display text-2xl font-bold">${{ number_format($property->price, 0) }}</span>
                            <span class="text-xs">/{{ $property->price_frequency }}</span>
                        </div>
                    </div>
                </div>
                <div class="p-6 flex flex-col flex-1">
                    <h3 class="font-display text-lg text-brand-navy font-bold truncate">{{ $property->title }}</h3>
                    <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                        <i class="fa-solid fa-location-dot text-brand-cyan"></i>
                        {{ $property->city }}, {{ $property->state }}
                    </p>
                    <div class="flex items-center gap-4 text-xs font-semibold text-slate-600 my-4 py-3 border-y border-slate-100">
                        <span><i class="fa-solid fa-bed text-brand-cyan mr-1"></i> {{ $property->bedrooms }} Bed</span>
                        <span><i class="fa-solid fa-bath text-brand-cyan mr-1"></i> {{ $property->bathrooms }} Bath</span>
                        <span><i class="fa-solid fa-ruler-combined text-brand-cyan mr-1"></i> {{ $property->sqft ? $property->sqft . ' Sq Ft' : 'Spacious' }}</span>
                    </div>
                    <div class="mt-auto pt-2">
                        <a href="{{ route('properties.show', $property->slug) }}" class="block w-full py-3 rounded-xl bg-brand-cyan hover:bg-brand-cyan-hover text-white text-center text-xs font-bold uppercase tracking-wider shadow-md shadow-brand-cyan/25 transition">
                            View Listing & Schedule Tour
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ── CTA BANNER (LIGHT MODERN) ── -->
<section class="py-16 sm:py-20 lg:py-24 bg-gradient-to-r from-sky-50 via-white to-cyan-50 border-t border-slate-200">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 text-center">
        <div class="max-w-3xl mx-auto">
            <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold text-brand-navy mb-4">
                Ready to lease your property in <span class="text-brand-cyan">{{ $cityTitle }}</span>?
            </h2>
            <p class="text-slate-600 text-base sm:text-lg leading-relaxed mb-8">
                Get zero-risk tenant placement with our success-fee model. You pay nothing until a fully screened, qualified tenant moves in.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('contact') }}" class="px-8 py-4 rounded-xl bg-brand-cyan hover:bg-brand-cyan-hover text-white font-bold text-xs sm:text-sm uppercase tracking-wider shadow-lg shadow-brand-cyan/25 transition hover:-translate-y-0.5">
                    Start Free Consultation
                </a>
                <a href="{{ route('pricing') }}" class="px-8 py-4 rounded-xl bg-white hover:bg-slate-50 text-brand-navy font-bold text-xs sm:text-sm uppercase tracking-wider border border-slate-300 shadow-sm transition">
                    View Transparent Pricing
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
