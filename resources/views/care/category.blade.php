@extends('layouts.app')

@section('title', $category->name . ' Services & Repair | Housing365 Care India')
@section('meta_description', 'Book verified ' . $category->name . ' services across India. ' . $category->description . ' Upfront pricing, vetted technicians, and 100% owner approval.')

@section('content')

<!-- ── BREADCRUMB ── -->
<div class="bg-white border-b border-slate-100 py-3.5 text-sm text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-cyan transition font-medium">Home</a>
        <span class="text-slate-300">/</span>
        <a href="{{ route('care.index') }}" class="hover:text-brand-cyan transition font-medium">Housing365 Care</a>
        <span class="text-slate-300">/</span>
        <span class="text-brand-navy font-bold">{{ $category->name }}</span>
    </div>
</div>

<!-- ── CATEGORY HERO ── -->
<section class="relative overflow-hidden bg-gradient-to-b from-[#F7FAFD] via-white to-[#F0F6FC] py-16 sm:py-20">
    <div class="relative mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 items-center gap-12">
            <div class="lg:col-span-8">
                <div class="flex items-center gap-3 mb-4">
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-brand-cyan/15 text-brand-navy border border-brand-cyan/30">
                        HOUSING365 CARE
                    </span>
                    @if($category->is_emergency_available)
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-rose-50 text-rose-700 border border-rose-200">
                            <i class="fa-solid fa-triangle-exclamation mr-1"></i> 24/7 Emergency Available
                        </span>
                    @endif
                </div>

                <h1 class="font-display text-4xl sm:text-5xl font-extrabold text-brand-navy leading-tight tracking-tight">
                    Professional {{ $category->name }} Services
                </h1>

                <p class="mt-4 text-base sm:text-lg text-slate-600 leading-relaxed max-w-2xl">
                    {{ $category->description }}
                </p>

                <div class="mt-6 flex items-center gap-6 text-sm">
                    <div>
                        <span class="text-slate-400 block text-xs uppercase font-bold">Standard Pricing</span>
                        <span class="text-2xl font-display font-extrabold text-brand-navy">{{ $category->price_display }}</span>
                    </div>
                    <div class="h-8 w-px bg-slate-200"></div>
                    <div>
                        <span class="text-slate-400 block text-xs uppercase font-bold">Service Model</span>
                        <span class="text-base font-bold text-slate-700">Verified Partner Vendor Dispatch</span>
                    </div>
                </div>

                <div class="mt-8 flex flex-wrap items-center gap-4">
                    <a href="{{ route('care.request', ['category' => $category->slug]) }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-brand-cyan px-8 py-4 text-sm font-bold text-brand-navy shadow-lg shadow-brand-cyan/25 transition hover:bg-brand-cyan-hover">
                        <i class="fa-solid fa-calendar-check text-xs"></i>
                        <span>Book {{ $category->name }} Service</span>
                    </a>
                    @if($category->is_emergency_available)
                        <a href="{{ route('care.request', ['category' => $category->slug, 'priority' => 'emergency']) }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-rose-600 px-6 py-4 text-sm font-bold text-white shadow-lg transition hover:bg-rose-700">
                            <i class="fa-solid fa-bolt text-xs"></i>
                            <span>Emergency Dispatch</span>
                        </a>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-4">
                <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-xl">
                    <div class="w-16 h-16 rounded-2xl bg-brand-cyan/15 text-brand-navy flex items-center justify-center text-3xl mb-6">
                        <i class="{{ $category->icon ?: 'fa-solid fa-wrench' }}"></i>
                    </div>
                    <h3 class="text-lg font-bold text-brand-navy mb-2">Housing365 Care Guarantee</h3>
                    <ul class="space-y-3 text-xs text-slate-600">
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-brand-cyan text-sm mt-0.5"></i>
                            <span><strong>No Hidden Charges:</strong> All prices and quotes approved by you in advance.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-brand-cyan text-sm mt-0.5"></i>
                            <span><strong>Verified Technicians:</strong> Background checks and trade certification verified.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-brand-cyan text-sm mt-0.5"></i>
                            <span><strong>Quality Assurance:</strong> Photographic before &amp; after documentation on every job.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-brand-cyan text-sm mt-0.5"></i>
                            <span><strong>Zero Mandatory Management:</strong> Available to all owners &amp; tenants on-demand.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── RELATED CATEGORIES ── -->
@if($relatedCategories->isNotEmpty())
<section class="py-16 bg-slate-50 border-t border-slate-200">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <h2 class="text-xl font-bold text-brand-navy mb-6">Other Care Services</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedCategories as $rel)
                <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:shadow transition flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-brand-cyan/10 text-brand-navy flex items-center justify-center text-base mb-3">
                            <i class="{{ $rel->icon ?: 'fa-solid fa-wrench' }}"></i>
                        </div>
                        <h3 class="text-sm font-bold text-brand-navy mb-1">
                            <a href="{{ route('care.category', $rel->slug) }}" class="hover:text-brand-cyan transition">{{ $rel->name }}</a>
                        </h3>
                        <p class="text-xs text-slate-500 line-clamp-2 mb-4">{{ $rel->description }}</p>
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-slate-100 text-xs">
                        <span class="font-bold text-brand-navy">{{ $rel->price_display }}</span>
                        <a href="{{ route('care.category', $rel->slug) }}" class="text-brand-cyan font-bold hover:underline">Details &rarr;</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
