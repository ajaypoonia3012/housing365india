@extends('layouts.app')

@php
    $cityName = $city->name ?? $city['title'] ?? 'City';
    $countryCode = $city->country ?? 'IN';
    $stateRegion = $city->state ?? $city['stateSlug'] ?? '';
    $avgRent = $city->average_rent ?? $city['medianRent'] ?? 2400;
    $bgImg = $city->image ?? $city['heroImageUrl'] ?? '/city-placeholder-c.svg';
@endphp

@section('title', "Rental Housing & Leasing Services in {$cityName} | Housing 365")
@section('meta_description', "Explore rental properties, market pricing, and tenant placement services in {$cityName}. Zero upfront cost, guaranteed rent, and 75-point tenant screening.")

@section('content')

<!-- ── BREADCRUMB ── -->
<div class="bg-white border-b border-slate-100 py-3.5 text-xs text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-cyan transition font-medium">Home</a>
        <span class="text-slate-300">/</span>
        <a href="{{ route('locations') }}" class="hover:text-brand-cyan transition font-medium">Locations</a>
        <span class="text-slate-300">/</span>
        <span class="text-brand-navy font-bold truncate">{{ $cityName }}</span>
    </div>
</div>

<!-- ── HERO SHOWCASE (LIGHT ELEGANT DESIGN) ── -->
<section class="relative bg-gradient-to-b from-[#F7FAFD] via-white to-[#F0F6FC] overflow-hidden py-16 sm:py-24">
    <!-- Ambient Glow Effects -->
    <div class="pointer-events-none absolute -left-40 top-0 h-[500px] w-[500px] rounded-full opacity-40 blur-3xl" style="background: radial-gradient(circle, rgba(0,174,239,0.18) 0%, transparent 70%)" aria-hidden="true"></div>

    <div class="relative z-10 mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 rounded-full border border-brand-cyan/20 bg-brand-cyan/5 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-brand-cyan mb-4">
                <span class="size-2 rounded-full bg-brand-cyan animate-pulse"></span>
                {{ $cityName }} LEASING METRO
            </div>

            <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-brand-navy leading-[1.1]">
                Full-Service Leasing in <br>
                <span class="text-brand-cyan font-extrabold">{{ $cityName }}</span>
            </h1>

            <p class="mt-6 text-base sm:text-lg text-slate-600 leading-relaxed max-w-2xl">
                {{ $city->description ?? "Premier tenant placement, property marketing, and lease management across {$cityName}." }}
            </p>

            <div class="mt-8 flex flex-wrap gap-4 items-center">
                <a href="{{ route('properties.index', ['city' => $cityName]) }}" class="px-8 py-4 rounded-xl bg-brand-cyan hover:bg-brand-cyan-hover text-white text-xs sm:text-sm font-bold uppercase tracking-wider shadow-lg shadow-brand-cyan/25 transition-all duration-200 hover:-translate-y-0.5">
                    Browse {{ $cityName }} Rentals
                </a>
                <a href="{{ route('contact') }}" class="px-7 py-4 rounded-xl bg-white hover:bg-slate-50 text-brand-navy text-xs sm:text-sm font-bold uppercase tracking-wider border border-slate-300 shadow-sm transition-all duration-200">
                    Get Free Rental Estimate
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ── MARKET BENCHMARK STRIP ── -->
<section class="bg-white border-b border-slate-200/80 py-8 shadow-sm">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div class="p-4 border-r border-slate-100 last:border-0">
                <div class="text-xs uppercase font-bold tracking-wider text-slate-400 mb-1">Average Rent</div>
                <div class="font-display text-2xl sm:text-3xl font-bold text-brand-navy">₹{{ number_format($avgRent) }}<span class="text-xs font-semibold text-slate-400">/mo</span></div>
                <div class="text-[11px] text-slate-500 mt-1">Metro Market Benchmark</div>
            </div>
            <div class="p-4 border-r border-slate-100 last:border-0">
                <div class="text-xs uppercase font-bold tracking-wider text-slate-400 mb-1">Average Days on Market</div>
                <div class="font-display text-2xl sm:text-3xl font-bold text-brand-cyan">14 Days</div>
                <div class="text-[11px] text-slate-500 mt-1">Fast candidate placement</div>
            </div>
            <div class="p-4 border-r border-slate-100 last:border-0">
                <div class="text-xs uppercase font-bold tracking-wider text-slate-400 mb-1">Tenant Screening</div>
                <div class="font-display text-2xl sm:text-3xl font-bold text-brand-navy">75-Point</div>
                <div class="text-[11px] text-emerald-600 font-bold mt-1">CIBIL, identity & background verification</div>
            </div>
            <div class="p-4">
                <div class="text-xs uppercase font-bold tracking-wider text-slate-400 mb-1">Upfront Fee</div>
                <div class="font-display text-2xl sm:text-3xl font-bold text-brand-navy">₹0</div>
                <div class="text-[11px] text-emerald-600 font-bold mt-1">Success fee on placement only</div>
            </div>
        </div>
    </div>
</section>

<!-- ── AVAILABLE PROPERTIES ── -->
<section class="py-16 sm:py-20 bg-[#F8FAFD]">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-brand-cyan">AVAILABLE RESIDENCES</span>
                <h2 class="font-display text-3xl sm:text-4xl text-brand-navy font-normal mt-1">
                    Featured Listings in {{ $cityName }}
                </h2>
            </div>
            <a href="{{ route('properties.index', ['city' => $cityName]) }}" class="mt-4 sm:mt-0 text-xs font-bold uppercase tracking-wider text-brand-navy hover:text-brand-cyan flex items-center gap-1">
                View All Properties <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($properties as $property)
            <div class="bg-white rounded-3xl overflow-hidden border border-slate-200/80 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col group">
                <div class="h-60 bg-slate-900 relative overflow-hidden">
                    <img src="{{ $property->image ?? '/city-placeholder-c.svg' }}" 
                         alt="{{ $property->title }}" 
                         onerror="this.src='/city-placeholder-c.svg'"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-4 left-4">
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-brand-navy text-white">
                            {{ $property->property_type }}
                        </span>
                    </div>
                    <div class="absolute bottom-4 left-4 right-4 flex justify-between items-end text-white">
                        <div>
                            <span class="font-display text-2xl font-bold">₹{{ number_format($property->price, 0) }}</span>
                            <span class="text-xs">/{{ $property->price_frequency }}</span>
                        </div>
                    </div>
                </div>
                <div class="p-6 flex flex-col flex-1">
                    <h3 class="font-display text-lg text-brand-navy font-semibold truncate">{{ $property->title }}</h3>
                    <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                        <i class="fa-solid fa-location-dot text-brand-cyan"></i>
                        {{ $property->address }}, {{ $property->city }}
                    </p>
                    <div class="flex items-center gap-4 text-xs font-semibold text-slate-600 my-4 py-3 border-y border-slate-100">
                        <span><i class="fa-solid fa-bed text-brand-cyan mr-1"></i> {{ $property->bedrooms }} Bed</span>
                        <span><i class="fa-solid fa-bath text-brand-cyan mr-1"></i> {{ $property->bathrooms }} Bath</span>
                        <span><i class="fa-solid fa-ruler-combined text-brand-cyan mr-1"></i> {{ $property->sqft ? $property->sqft . ' Sq Ft' : 'Spacious' }}</span>
                    </div>
                    <div class="mt-auto pt-2">
                        <a href="{{ route('properties.show', $property->slug) }}" class="block w-full py-2.5 rounded-xl bg-brand-navy hover:bg-brand-navy-hover text-white text-center text-xs font-bold uppercase tracking-wider transition">
                            View Listing & Schedule Tour
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-16 bg-white rounded-3xl border border-slate-200">
                <p class="text-slate-500 text-sm mb-4">No active listings currently displayed for {{ $cityName }}.</p>
                <a href="{{ route('contact') }}" class="inline-block px-6 py-3 rounded-full bg-brand-cyan text-white text-xs font-bold uppercase tracking-wider">
                    Register For Off-Market Units
                </a>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- ── CTA BANNER (LIGHT MODERN) ── -->
<section class="py-16 sm:py-20 lg:py-24 bg-gradient-to-r from-sky-50 via-white to-cyan-50 border-t border-slate-200">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 text-center">
        <div class="max-w-2xl mx-auto">
            <h2 class="font-display text-3xl sm:text-4xl text-brand-navy font-extrabold mb-4">
                Looking to rent or lease in <span class="text-brand-cyan">{{ $cityName }}</span>?
            </h2>
            <p class="text-slate-600 text-base sm:text-lg mb-8">
                Connect with our local on-the-ground leasing specialists today for expert market advice and immediate property showings.
            </p>
            <a href="{{ route('contact') }}" class="inline-block px-8 py-4 rounded-xl bg-brand-cyan hover:bg-brand-cyan-hover text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-brand-cyan/25 transition">
                Talk to a {{ $cityName }} Specialist
            </a>
        </div>
    </div>
</section>

@endsection
