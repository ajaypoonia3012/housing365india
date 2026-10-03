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

<!-- ── HERO ── -->
<section class="relative bg-gradient-to-b from-[#F7FAFD] via-white to-[#F0F6FC] py-14 sm:py-16 border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12">
        <div class="max-w-4xl">
            <div class="inline-flex items-center gap-2 rounded-full border border-brand-cyan/20 bg-brand-cyan/5 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-brand-cyan mb-4">
                <span class="h-2 w-2 rounded-full bg-brand-cyan"></span>
                HOUSING365 CARE &middot; {{ strtoupper($cityRecord->name) }}
            </div>
            <h1 class="font-display text-3xl sm:text-5xl font-extrabold text-brand-navy leading-tight">
                {{ $seo['h1'] }}
            </h1>
            <p class="mt-4 text-base sm:text-lg text-slate-600 leading-relaxed">
                {{ $seo['description'] }}
            </p>

            <div class="mt-8 flex flex-wrap gap-4">
                <a href="{{ route('services.care') }}" class="px-6 py-3 rounded-xl bg-brand-cyan text-brand-navy font-extrabold text-sm shadow hover:bg-brand-cyan-hover transition">
                    Book a Service in {{ $cityRecord->name }}
                </a>
                <a href="{{ route('locations.city', ['state' => $cityRecord->state_slug ?: 'india', 'city' => $cityRecord->slug]) }}" class="px-6 py-3 rounded-xl bg-white border border-slate-200 text-brand-navy font-bold text-sm shadow-sm hover:border-brand-cyan transition">
                    Explore {{ $cityRecord->name }} Properties
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ── CARE SERVICES GRID ── -->
<section class="py-14 bg-white border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-6 rounded-3xl bg-[#F8FAFD] border border-slate-200">
                <div class="size-12 rounded-xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-xl mb-4">
                    <i class="fa-solid fa-snowflake"></i>
                </div>
                <h3 class="font-bold text-brand-navy text-lg mb-2">AC Service &amp; Repair</h3>
                <p class="text-sm text-slate-600 leading-relaxed">Pre-tenancy AC servicing, jet pump cleaning, and diagnostic repairs with transparent itemized quotations in {{ $cityRecord->name }}.</p>
            </div>
            <div class="p-6 rounded-3xl bg-[#F8FAFD] border border-slate-200">
                <div class="size-12 rounded-xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-xl mb-4">
                    <i class="fa-solid fa-faucet-drip"></i>
                </div>
                <h3 class="font-bold text-brand-navy text-lg mb-2">Plumbing &amp; Sanitary</h3>
                <p class="text-sm text-slate-600 leading-relaxed">Pressure testing, leak fixes, bathroom fittings installation, and water motor repairs by background-verified technicians.</p>
            </div>
            <div class="p-6 rounded-3xl bg-[#F8FAFD] border border-slate-200">
                <div class="size-12 rounded-xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-xl mb-4">
                    <i class="fa-solid fa-broom"></i>
                </div>
                <h3 class="font-bold text-brand-navy text-lg mb-2">Deep Cleaning &amp; Painting</h3>
                <p class="text-sm text-slate-600 leading-relaxed">Turnkey move-in and move-out deep cleaning, wall touchups, and full interior painting with quality control signoff.</p>
            </div>
        </div>
    </div>
</section>

@endsection
