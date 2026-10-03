@extends('layouts.app')

@section('title', $seo['title'])
@section('meta_description', $seo['description'])
@section('meta_robots', $seo['robots'])
@section('canonical', $seo['canonical'])

@push('schema')
@if(!empty($seo['structuredData']))
    @foreach($seo['structuredData'] as $schema)
        <script type="application/ld+json">
            {!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
        </script>
    @endforeach
@endif
@endpush

@section('content')

<!-- ── BREADCRUMB ── -->
<div class="bg-white border-b border-slate-100 py-3 text-sm text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 flex items-center space-x-2 flex-wrap">
        @foreach($seo['breadcrumbs'] as $crumb)
            @if(!$loop->last)
                <a href="{{ $crumb['url'] }}" class="hover:text-brand-cyan transition font-medium">{{ $crumb['title'] }}</a>
                <span class="text-slate-300">/</span>
            @else
                <span class="text-brand-navy font-bold">{{ $crumb['title'] }}</span>
            @endif
        @endforeach
    </div>
</div>

<!-- ── HERO SECTION ── -->
<section class="relative bg-gradient-to-b from-[#F7FAFD] via-white to-[#F0F6FC] py-14 sm:py-16 border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12">
        <div class="max-w-4xl">
            <div class="inline-flex items-center gap-2 rounded-full border border-brand-cyan/20 bg-brand-cyan/5 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-brand-cyan mb-4">
                <span class="h-2 w-2 rounded-full bg-brand-cyan"></span>
                {{ strtoupper($cityRecord->name) }} VERIFIED PROPERTY ECOSYSTEM
            </div>
            <h1 class="font-display text-3xl sm:text-5xl font-extrabold text-brand-navy leading-tight">
                {{ $seo['h1'] }}
            </h1>
            <p class="mt-4 text-base sm:text-lg text-slate-600 leading-relaxed">
                {{ $seo['intro'] ?: $seo['description'] }}
            </p>

            <!-- Category Pills -->
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('marketplace.rent', ['state' => $cityRecord->state_slug ?: 'india', 'city' => $cityRecord->slug]) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-sm font-bold text-brand-navy shadow-sm hover:border-brand-cyan hover:text-brand-cyan transition">
                    <i class="fa-solid fa-key text-brand-cyan"></i>
                    <span>Rentals ({{ $counts['rent'] ?? 0 }})</span>
                </a>
                <a href="{{ route('marketplace.sale', ['state' => $cityRecord->state_slug ?: 'india', 'city' => $cityRecord->slug]) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-sm font-bold text-brand-navy shadow-sm hover:border-brand-cyan hover:text-brand-cyan transition">
                    <i class="fa-solid fa-building text-brand-cyan"></i>
                    <span>Buy &amp; Sell ({{ $counts['sale'] ?? 0 }})</span>
                </a>
                <a href="{{ route('marketplace.pg', ['state' => $cityRecord->state_slug ?: 'india', 'city' => $cityRecord->slug]) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-sm font-bold text-brand-navy shadow-sm hover:border-brand-cyan hover:text-brand-cyan transition">
                    <i class="fa-solid fa-bed text-brand-cyan"></i>
                    <span>PG &amp; Hostels ({{ $counts['pg'] ?? 0 }})</span>
                </a>
                <a href="{{ route('marketplace.coworking', ['state' => $cityRecord->state_slug ?: 'india', 'city' => $cityRecord->slug]) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-sm font-bold text-brand-navy shadow-sm hover:border-brand-cyan hover:text-brand-cyan transition">
                    <i class="fa-solid fa-laptop text-brand-cyan"></i>
                    <span>Coworking ({{ $counts['coworking'] ?? 0 }})</span>
                </a>
                <a href="{{ route('marketplace.commercial', ['state' => $cityRecord->state_slug ?: 'india', 'city' => $cityRecord->slug]) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-sm font-bold text-brand-navy shadow-sm hover:border-brand-cyan hover:text-brand-cyan transition">
                    <i class="fa-solid fa-briefcase text-brand-cyan"></i>
                    <span>Commercial ({{ $counts['commercial'] ?? 0 }})</span>
                </a>
                <a href="{{ route('care.location', ['state' => $cityRecord->state_slug ?: 'india', 'city' => $cityRecord->slug]) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-brand-navy text-white text-sm font-bold shadow-sm hover:bg-brand-navy-light transition">
                    <i class="fa-solid fa-wrench text-brand-cyan"></i>
                    <span>Housing365 Care</span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ── POPULAR LOCALITIES IN CITY ── -->
@if($localities->isNotEmpty())
<section class="py-12 bg-white border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12">
        <div class="flex items-center justify-between mb-8">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-brand-cyan">LOCAL NEIGHBORHOODS</span>
                <h2 class="font-display text-2xl sm:text-3xl text-brand-navy font-extrabold mt-1">
                    Explore Prime Localities in {{ $cityRecord->name }}
                </h2>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($localities as $loc)
            @php $stateSlug = $cityRecord->state_slug ?: 'india'; @endphp
            <a href="{{ route('locations.locality', ['state' => $stateSlug, 'city' => $cityRecord->slug, 'locality' => $loc->slug]) }}" 
               class="group p-5 rounded-2xl bg-[#F8FAFD] border border-slate-200/80 hover:border-brand-cyan hover:bg-white hover:shadow-md transition">
                <div class="flex items-center justify-between mb-1">
                    <h4 class="font-bold text-brand-navy group-hover:text-brand-cyan text-sm sm:text-base transition">{{ $loc->name }}</h4>
                    <i class="fa-solid fa-arrow-right text-xs text-slate-400 group-hover:text-brand-cyan group-hover:translate-x-1 transition"></i>
                </div>
                <p class="text-xs text-slate-500">Verified residential &middot; Metro connected</p>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ── AVAILABLE PROPERTIES ── -->
<section class="py-14 bg-[#F8FAFD] border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12">
        <div class="flex items-center justify-between mb-8">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-brand-cyan">LIVE INVENTORY</span>
                <h2 class="font-display text-2xl sm:text-3xl text-brand-navy font-extrabold mt-1">
                    Featured Available Properties in {{ $cityRecord->name }}
                </h2>
            </div>
            <a href="{{ route('marketplace.rent', ['state' => $cityRecord->state_slug ?: 'india', 'city' => $cityRecord->slug]) }}" 
               class="text-sm font-bold text-brand-cyan hover:underline inline-flex items-center gap-1.5">
                <span>View All Properties</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

        @if($properties->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($properties as $property)
            <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-lg transition flex flex-col">
                <div class="relative h-48 bg-slate-100 overflow-hidden">
                    <img src="{{ $property->image ?: asset('placeholder-property.jpg') }}" alt="{{ $property->title }}" class="w-full h-full object-cover">
                    <div class="absolute top-3 left-3 flex gap-2">
                        <span class="px-2.5 py-1 rounded-full text-2xs font-extrabold bg-brand-navy text-white uppercase">{{ $property->purpose }}</span>
                        @if($property->is_verified)
                        <span class="px-2.5 py-1 rounded-full text-2xs font-extrabold bg-emerald-600 text-white inline-flex items-center gap-1">
                            <i class="fa-solid fa-circle-check text-2xs"></i>
                            <span>Verified</span>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="text-xl font-black text-brand-navy mb-1">
                            {{ $property->price_display ?: ('₹' . number_format($property->price) . ($property->purpose === 'rent' ? '/mo' : '')) }}
                        </div>
                        <h3 class="font-bold text-slate-800 text-base line-clamp-1 mb-2">
                            <a href="{{ route('properties.show', $property->slug) }}" class="hover:text-brand-cyan transition">
                                {{ $property->title }}
                            </a>
                        </h3>
                        <p class="text-xs text-slate-500 mb-4 inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-location-dot text-brand-cyan"></i>
                            <span>{{ $property->locality ? $property->locality . ', ' : '' }}{{ $property->city }}</span>
                        </p>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
                        <span>{{ $property->bedrooms ? $property->bedrooms . ' BHK' : $property->property_type }}</span>
                        <a href="{{ route('properties.show', $property->slug) }}" class="font-bold text-brand-cyan hover:underline inline-flex items-center gap-1">
                            <span>Details</span>
                            <i class="fa-solid fa-chevron-right text-2xs"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <!-- Empty State with constructive discovery links -->
        <div class="bg-white rounded-3xl p-10 text-center border border-slate-200">
            <div class="size-16 rounded-2xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-2xl mx-auto mb-4">
                <i class="fa-solid fa-house-chimney-window"></i>
            </div>
            <h3 class="font-display text-xl font-bold text-brand-navy mb-2">No Active Properties Found in {{ $cityRecord->name }}</h3>
            <p class="text-sm text-slate-600 max-w-md mx-auto mb-6">
                Are you a property owner in {{ $cityRecord->name }}? List your property for free with zero brokerage, or refer an owner to earn ₹100!
            </p>
            <div class="flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('properties.post') }}" class="px-5 py-2.5 rounded-xl bg-brand-cyan text-brand-navy font-bold text-sm shadow hover:bg-brand-cyan-hover transition">
                    List My Property (₹0 Free)
                </a>
                <a href="{{ route('refer-and-earn') }}" class="px-5 py-2.5 rounded-xl bg-brand-navy text-white font-bold text-sm shadow hover:bg-brand-navy-light transition">
                    Refer &amp; Earn ₹100
                </a>
            </div>
        </div>
        @endif
    </div>
</section>

<!-- ── LEGAL & POLICY TRANSPARENCY (NO FAKE DATA) ── -->
@if($stateRecord)
<section class="py-14 bg-white border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12">
        <div class="max-w-3xl mb-8">
            <span class="text-xs font-bold uppercase tracking-widest text-brand-cyan">REGULATORY COMPLIANCE</span>
            <h2 class="font-display text-2xl sm:text-3xl text-brand-navy font-extrabold mt-1">
                Tenancy Laws &amp; Legal Framework in {{ $stateRecord->name }}
            </h2>
            <p class="text-sm text-slate-600 mt-2">Housing365 coordinates 100% legal digital tenancy agreements compliant with state statutory rules.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-6 rounded-2xl bg-[#F8FAFD] border border-slate-200">
                <div class="text-brand-cyan text-lg mb-3"><i class="fa-solid fa-scale-balanced"></i></div>
                <h4 class="font-bold text-brand-navy text-sm mb-1">State Tenancy Act</h4>
                <p class="text-xs text-slate-600 leading-relaxed">{{ $stateRecord->tenancy_act_title ?: 'Model Tenancy Act & State Rent Control Rules' }}</p>
            </div>
            <div class="p-6 rounded-2xl bg-[#F8FAFD] border border-slate-200">
                <div class="text-brand-cyan text-lg mb-3"><i class="fa-solid fa-stamp"></i></div>
                <h4 class="font-bold text-brand-navy text-sm mb-1">E-Stamp Duty &amp; Registration</h4>
                <p class="text-xs text-slate-600 leading-relaxed">{{ $stateRecord->stamp_duty_summary ?: 'Government e-stamp duty and registration fees charged at statutory actuals.' }}</p>
            </div>
            <div class="p-6 rounded-2xl bg-[#F8FAFD] border border-slate-200">
                <div class="text-brand-cyan text-lg mb-3"><i class="fa-solid fa-shield-halved"></i></div>
                <h4 class="font-bold text-brand-navy text-sm mb-1">Police Tenant Verification</h4>
                <p class="text-xs text-slate-600 leading-relaxed">{{ $stateRecord->police_verification_portal ?: 'Direct police clearance intimation filed through official state citizen portals.' }}</p>
            </div>
        </div>
    </div>
</section>
@endif

@endsection
