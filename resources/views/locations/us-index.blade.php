@extends('layouts.app')

@section('title', 'United States Rental Markets & Leasing Coverage | Housing 365')
@section('meta_description', 'Explore Housing 365 leasing services across priority US states including Florida, Texas, California, New York, Illinois, and more.')

@section('content')

<!-- ── BREADCRUMB ── -->
<div class="bg-white border-b border-slate-100 py-3.5 text-sm text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-cyan transition font-medium">Home</a>
        <span class="text-slate-300">/</span>
        <span class="text-brand-navy font-bold">United States</span>
    </div>
</div>

<!-- ── SECTION 1: HERO SHOWCASE (LIGHT ELEGANT DESIGN) ── -->
<section class="relative bg-gradient-to-b from-[#F7FAFD] via-white to-[#F0F6FC] overflow-hidden py-16 sm:py-24">
    <!-- Ambient Glow Effects -->
    <div class="pointer-events-none absolute -left-40 top-0 h-[500px] w-[500px] rounded-full opacity-40 blur-3xl" style="background: radial-gradient(circle, rgba(0,174,239,0.18) 0%, transparent 70%)" aria-hidden="true"></div>

    <div class="relative z-10 mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 rounded-full border border-brand-cyan/20 bg-brand-cyan/5 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-brand-cyan mb-4">
                <span class="size-2 rounded-full bg-brand-cyan animate-pulse"></span>
                U.S. EXPANSION DIRECTORY
            </div>

            <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-brand-navy leading-[1.1]">
                Full-Service Leasing Across <br>
                <span class="text-brand-cyan font-extrabold">Major U.S. Rental Markets</span>
            </h1>

            <p class="mt-6 text-base sm:text-lg text-slate-600 leading-relaxed max-w-2xl">
                On-the-ground leasing operations, 75-point tenant screening, state-specific legal compliance, and guaranteed rent protection in 10 priority states and 57 metropolitan cities.
            </p>
        </div>

        <!-- 4-Stat Strip (Light Modern) -->
        <div class="mt-14 grid grid-cols-2 sm:grid-cols-4 gap-4 pt-8 border-t border-slate-200">
            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
                <span class="font-display text-3xl font-extrabold text-brand-cyan">10</span>
                <span class="text-sm font-bold text-brand-navy block mt-1">Priority States</span>
                <span class="text-xs text-slate-500">Florida, Texas, CA &amp; more</span>
            </div>
            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
                <span class="font-display text-3xl font-extrabold text-brand-navy">57</span>
                <span class="text-sm font-bold text-brand-navy block mt-1">Metropolitan Cities</span>
                <span class="text-xs text-slate-500">High-growth rental hubs</span>
            </div>
            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
                <span class="font-display text-3xl font-extrabold text-emerald-600">100%</span>
                <span class="text-sm font-bold text-brand-navy block mt-1">Statutory Compliance</span>
                <span class="text-xs text-slate-500">Local property code rules</span>
            </div>
            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
                <span class="font-display text-3xl font-extrabold text-brand-cyan">$0</span>
                <span class="text-sm font-bold text-brand-navy block mt-1">Monthly Retainer</span>
                <span class="text-xs text-slate-500">Performance leasing</span>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 2: STATES DIRECTORY GRID ── -->
<section class="py-16 sm:py-20 lg:py-24 bg-[#F8FAFD] border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="mb-12">
            <span class="text-xs font-bold uppercase tracking-widest text-brand-cyan">JURISDICTIONS</span>
            <h2 class="font-display text-3xl sm:text-4xl text-brand-navy font-extrabold mt-2">
                Active U.S. States
            </h2>
            <p class="text-slate-600 text-base mt-2">Select a state to explore metropolitan coverage, local rent rates, and regional tenant demand.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($states as $state)
            @php
                $stateCities = \App\Services\LocationCatalog::getCities($state['slug']);
            @endphp
            <div class="bg-white rounded-3xl overflow-hidden border border-slate-200/80 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col group">
                <div class="h-52 bg-slate-900 relative overflow-hidden">
                    <img src="{{ $state['heroImageUrl'] ?? 'https://images.unsplash.com/photo-1531218150217-54595bc2b934?auto=format&fit=crop&w=1200&q=80' }}" 
                         alt="{{ $state['name'] }}" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                    <div class="absolute bottom-4 left-4 right-4 flex justify-between items-end text-white">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-brand-cyan">{{ $state['abbreviation'] }}</span>
                            <h3 class="font-display text-2xl font-bold leading-tight">{{ $state['name'] }}</h3>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-white/20 backdrop-blur-sm text-xs font-bold text-white">
                            {{ count($stateCities) }} Cities
                        </span>
                    </div>
                </div>

                <div class="p-6 flex flex-col flex-1">
                    <p class="text-sm text-slate-600 line-clamp-3 leading-relaxed mb-6">
                        {{ $state['description'] }}
                    </p>
                    <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs text-brand-cyan font-bold">Compliant Leasing</span>
                        <a href="{{ url('/us/' . $state['slug']) }}" class="text-xs font-bold text-brand-navy group-hover:text-brand-cyan transition flex items-center gap-1.5 uppercase tracking-wider">
                            View State Hub <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ── SECTION 3: CROSS-BORDER INVESTOR ADVISORY ── -->
<section class="py-16 sm:py-20 lg:py-24 bg-white border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-6">
                <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                    <span class="h-2 w-2 rounded-full bg-brand-cyan"></span>
                    CROSS-BORDER LANDLORD DESK
                </div>
                <h2 class="font-display text-3xl font-extrabold tracking-tight text-brand-navy sm:text-4xl">
                    Investing in U.S. Real Estate from Canada or Abroad?
                </h2>
                <p class="mt-4 text-base text-slate-600 leading-relaxed">
                    Managing U.S. rental properties from another country can be daunting due to unfamiliar state tenancy codes, withholding taxes, and physical maintenance distances. Housing 365 bridges the border with institutional ease.
                </p>

                <div class="mt-8 space-y-4">
                    <div class="p-4 rounded-2xl bg-[#F8FAFD] border border-slate-200/80 flex items-start gap-4">
                        <i class="fa-solid fa-file-invoice-dollar text-brand-cyan text-xl mt-1"></i>
                        <div>
                            <h4 class="font-bold text-sm text-brand-navy">IRS W-8BEN &amp; 1042-S Tax Compliance</h4>
                            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Automated accounting reports for your CPA to optimize cross-border depreciation and foreign tax credits.</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-[#F8FAFD] border border-slate-200/80 flex items-start gap-4">
                        <i class="fa-solid fa-dollar-sign text-emerald-600 text-xl mt-1"></i>
                        <div>
                            <h4 class="font-bold text-sm text-brand-navy">Direct USD Banking &amp; Payouts</h4>
                            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Net rent proceeds disbursed in USD straight to your institutional or Canadian cross-border bank account.</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-[#F8FAFD] border border-slate-200/80 flex items-start gap-4">
                        <i class="fa-solid fa-wrench text-brand-cyan text-xl mt-1"></i>
                        <div>
                            <h4 class="font-bold text-sm text-brand-navy">Local Vetted Trade Contractor Network</h4>
                            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">24/7 emergency dispatch and routine turnover repairs handled with pre-negotiated volume contractor discounts.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-6 bg-gradient-to-br from-sky-50 to-[#F8FAFD] p-8 sm:p-12 rounded-3xl border border-slate-200/90 shadow-sm text-center">
                <div class="size-16 rounded-2xl bg-brand-cyan text-white flex items-center justify-center text-3xl mx-auto mb-6 shadow-lg shadow-brand-cyan/25">
                    <i class="fa-solid fa-flag-usa"></i>
                </div>
                <h3 class="font-display text-2xl font-bold text-brand-navy mb-3">Expanding into New U.S. Metros</h3>
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed max-w-md mx-auto mb-8">
                    Looking to acquire single-family rental (SFR) or multi-family properties in Florida, Texas, or the Sunbelt? Connect with our localized expansion directors.
                </p>
                <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 rounded-xl bg-brand-cyan px-8 py-4 text-xs font-bold uppercase tracking-wider text-white shadow-lg shadow-brand-cyan/25 transition hover:bg-brand-cyan-hover">
                    <span>Inquire for U.S. Portfolio Management</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 4: CALL TO ACTION BANNER (LIGHT MODERN) ── -->
<section class="py-16 sm:py-20 lg:py-24 bg-gradient-to-r from-sky-50 via-white to-cyan-50">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="relative overflow-hidden rounded-3xl border border-brand-cyan/20 bg-white p-8 sm:p-14 lg:p-16 shadow-xl flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="max-w-2xl">
                <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                    <span class="size-2 rounded-full bg-brand-cyan animate-pulse"></span>
                    ACTIVE U.S. MARKETS
                </div>
                <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold text-brand-navy">
                    Have U.S. Properties to Lease?
                </h2>
                <p class="mt-4 text-base sm:text-lg text-slate-600">
                    Connect with our cross-border leasing desk for instant rental yield estimates and local placement execution.
                </p>
            </div>

            <div class="shrink-0">
                <a href="{{ route('contact') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand-cyan px-8 py-4 text-base font-bold text-white shadow-lg shadow-brand-cyan/25 transition-all duration-200 hover:-translate-y-0.5 hover:bg-brand-cyan-hover">
                    <span>Talk to an Expert</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
