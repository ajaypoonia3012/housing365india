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
                {{ strtoupper($localityRecord->name) }}, {{ strtoupper($cityRecord->name) }}
            </div>
            <h1 class="font-display text-3xl sm:text-5xl font-extrabold text-brand-navy leading-tight">
                {{ $seo['h1'] }}
            </h1>
            <p class="mt-4 text-base sm:text-lg text-slate-600 leading-relaxed">
                {{ $localityRecord->seo_intro ?: $seo['description'] }}
            </p>

            <div class="mt-6 flex flex-wrap gap-4 text-xs font-bold text-slate-600">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-slate-200 shadow-sm">
                    <i class="fa-solid fa-location-dot text-brand-cyan"></i>
                    <span>{{ $localityRecord->name }}</span>
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-slate-200 shadow-sm">
                    <i class="fa-solid fa-city text-brand-cyan"></i>
                    <span>City: {{ $cityRecord->name }}</span>
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-slate-200 shadow-sm">
                    <i class="fa-solid fa-building text-brand-cyan"></i>
                    <span>Active Listings: {{ $properties->total() }}</span>
                </span>
            </div>
        </div>
    </div>
</section>

<!-- ── INVENTORY GRID ── -->
<section class="py-14 bg-[#F8FAFD] border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12">
        <div class="mb-8">
            <span class="text-xs font-bold uppercase tracking-widest text-brand-cyan">LOCALITY INVENTORY</span>
            <h2 class="font-display text-2xl sm:text-3xl text-brand-navy font-extrabold mt-1">
                Available Properties in {{ $localityRecord->name }}
            </h2>
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

        <div class="mt-8">
            {{ $properties->links() }}
        </div>
        @else
        <!-- Constructive empty state -->
        <div class="bg-white rounded-3xl p-10 text-center border border-slate-200">
            <div class="size-16 rounded-2xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-2xl mx-auto mb-4">
                <i class="fa-solid fa-map-location-dot"></i>
            </div>
            <h3 class="font-display text-xl font-bold text-brand-navy mb-2">No Properties Listed in {{ $localityRecord->name }} Yet</h3>
            <p class="text-sm text-slate-600 max-w-md mx-auto mb-6">
                Be the first to list a property in this locality with ₹0 owner fee, or check all properties across {{ $cityRecord->name }}.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('properties.post') }}" class="px-5 py-2.5 rounded-xl bg-brand-cyan text-brand-navy font-bold text-sm shadow hover:bg-brand-cyan-hover transition">
                    List My Property Free
                </a>
                <a href="{{ route('locations.city', ['state' => $cityRecord->state_slug ?: 'india', 'city' => $cityRecord->slug]) }}" 
                   class="px-5 py-2.5 rounded-xl bg-brand-navy text-white font-bold text-sm shadow hover:bg-brand-navy-light transition">
                    Explore {{ $cityRecord->name }}
                </a>
            </div>
        </div>
        @endif
    </div>
</section>

@endsection
