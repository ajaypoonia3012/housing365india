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
            <div class="inline-flex items-center gap-2 rounded-full border border-emerald-500/20 bg-emerald-50 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-emerald-700 mb-4">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                OPTIONAL PROPERTY MANAGEMENT &middot; {{ strtoupper($cityRecord->name) }}
            </div>
            <h1 class="font-display text-3xl sm:text-5xl font-extrabold text-brand-navy leading-tight">
                {{ $seo['h1'] }}
            </h1>
            <p class="mt-4 text-base sm:text-lg text-slate-600 leading-relaxed">
                Hands-off property management for NRI and remote property owners in {{ $cityRecord->name }}. Complete tenant lifecycle, rent collection guarantee, periodic video inspections, and maintenance supervision.
            </p>

            <!-- MANDATORY INVARIANT CALLOUT -->
            <div class="mt-6 p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs sm:text-sm leading-relaxed max-w-2xl">
                <i class="fa-solid fa-circle-info text-amber-600 mr-1.5"></i>
                <strong>Strict Transparency:</strong> Property Management is strictly <strong>OPTIONAL</strong>. Property owners can list and lease their property on Housing365 for <strong>₹0 (Free)</strong> under standard self-managed leasing. Management enrollment is only activated upon explicit opt-in and executed agreement.
            </div>

            <div class="mt-8 flex flex-wrap gap-4">
                <a href="{{ route('pricing') }}" class="px-6 py-3 rounded-xl bg-brand-navy text-white font-extrabold text-sm shadow hover:bg-brand-navy-light transition">
                    Explore Management Plans
                </a>
                <a href="{{ route('properties.post') }}" class="px-6 py-3 rounded-xl bg-white border border-slate-200 text-brand-navy font-bold text-sm shadow-sm hover:border-brand-cyan transition">
                    Standard ₹0 Free Listing
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
