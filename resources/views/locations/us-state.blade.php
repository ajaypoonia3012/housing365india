@extends('layouts.app')

@php
    $stateName = $state['name'] ?? 'State';
    $stateAbbr = $state['abbreviation'] ?? 'US';
    $stateDesc = $state['description'] ?? "Full-service residential leasing and tenant screening operations across {$stateName}.";
    $bgImage = $state['heroImageUrl'] ?? 'https://images.unsplash.com/photo-1531218150217-54595bc2b934?auto=format&fit=crop&w=2000&q=80';
@endphp

@section('title', "Leasing Services & Property Management in {$stateName} ({$stateAbbr}) | Housing 365")
@section('meta_description', "Full-service leasing and tenant placement platform across {$stateName}: compliant leases, 75-point tenant screening, guaranteed rent, and fast placement.")

@section('content')

<!-- ── BREADCRUMB ── -->
<div class="bg-white border-b border-slate-100 py-3.5 text-xs text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-cyan transition font-medium">Home</a>
        <span class="text-slate-300">/</span>
        <a href="{{ url('/us') }}" class="hover:text-brand-cyan transition font-medium">United States</a>
        <span class="text-slate-300">/</span>
        <span class="text-brand-navy font-bold truncate">{{ $stateName }}</span>
    </div>
</div>

<!-- ── HERO BLOCK (LIGHT MODERN DESIGN) ── -->
<section class="relative bg-gradient-to-b from-[#F7FAFD] via-white to-[#F0F6FC] overflow-hidden py-16 sm:py-24">
    <div class="pointer-events-none absolute -left-40 top-0 h-[500px] w-[500px] rounded-full opacity-40 blur-3xl" style="background: radial-gradient(circle, rgba(0,174,239,0.18) 0%, transparent 70%)" aria-hidden="true"></div>
    <div class="pointer-events-none absolute -right-20 bottom-0 h-96 w-96 rounded-full opacity-30 blur-3xl" style="background: radial-gradient(circle, rgba(0,174,239,0.12) 0%, transparent 70%)" aria-hidden="true"></div>

    <div class="relative z-10 mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-7">
                <div class="inline-flex items-center gap-2 rounded-full border border-brand-cyan/20 bg-brand-cyan/5 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-brand-cyan mb-5">
                    <span class="size-2 rounded-full bg-brand-cyan animate-pulse"></span>
                    STATEWIDE LEASING OPERATIONS &bull; {{ $stateAbbr }}
                </div>

                <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-brand-navy leading-[1.1]">
                    Leasing Services Across <br>
                    <span class="text-brand-cyan font-extrabold">{{ $stateName }}</span>
                </h1>

                <p class="mt-6 text-base sm:text-lg text-slate-600 leading-relaxed max-w-2xl">
                    {{ $stateDesc }} From individual single-family rental homes to multi-unit apartment buildings, Housing 365 delivers zero-friction leasing, verified tenant screening, and full statutory compliance in {{ $stateName }}.
                </p>

                <div class="mt-8 flex flex-wrap gap-4 items-center">
                    <a href="#cities-directory" class="px-8 py-4 rounded-xl bg-brand-cyan hover:bg-brand-cyan-hover text-white text-xs sm:text-sm font-bold uppercase tracking-wider shadow-lg shadow-brand-cyan/25 transition-all duration-200 hover:-translate-y-0.5">
                        Explore {{ count($cities) }} Active Metros
                    </a>
                    <a href="{{ route('contact') }}" class="px-7 py-4 rounded-xl bg-white hover:bg-slate-50 text-brand-navy text-xs sm:text-sm font-bold uppercase tracking-wider border border-slate-300 shadow-sm transition-all duration-200">
                        Speak With A {{ $stateAbbr }} Specialist
                    </a>
                </div>
            </div>

            <div class="lg:col-span-5">
                <div class="relative rounded-3xl overflow-hidden shadow-2xl border border-slate-200/80 bg-white">
                    <div class="h-80 sm:h-96 relative overflow-hidden">
                        <img src="{{ $bgImage }}" 
                             alt="{{ $state['heroImageAlt'] ?? ($stateName . ' landscape') }}" 
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-brand-navy/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-6 left-6 right-6 text-white">
                            <span class="text-xs font-bold uppercase tracking-wider text-brand-cyan">Regional Coverage</span>
                            <h3 class="font-display text-2xl font-bold text-white">{{ $stateName }} State Desk</h3>
                            <p class="text-xs text-slate-200 mt-1">Licensed leasing brokerage partners across {{ $stateAbbr }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── STATEWIDE BENCHMARKS ── -->
<section class="bg-white border-b border-slate-200/80 py-8 shadow-sm">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div class="p-4 border-r border-slate-100 last:border-0">
                <div class="text-xs uppercase font-bold tracking-wider text-slate-400 mb-1">State Coverage</div>
                <div class="font-display text-2xl sm:text-3xl font-bold text-brand-navy">{{ count($cities) }} Metros</div>
                <div class="text-[11px] text-slate-500 mt-1">Direct local field agents</div>
            </div>
            <div class="p-4 border-r border-slate-100 last:border-0">
                <div class="text-xs uppercase font-bold tracking-wider text-slate-400 mb-1">Average Lease-Up</div>
                <div class="font-display text-2xl sm:text-3xl font-bold text-brand-cyan">14 Days</div>
                <div class="text-[11px] text-slate-500 mt-1">From syndication to signed lease</div>
            </div>
            <div class="p-4 border-r border-slate-100 last:border-0">
                <div class="text-xs uppercase font-bold tracking-wider text-slate-400 mb-1">Screening Protocol</div>
                <div class="font-display text-2xl sm:text-3xl font-bold text-brand-navy">75-Point</div>
                <div class="text-[11px] text-emerald-600 font-bold mt-1">Tri-bureau + criminal validation</div>
            </div>
            <div class="p-4">
                <div class="text-xs uppercase font-bold tracking-wider text-slate-400 mb-1">Placement Fee</div>
                <div class="font-display text-2xl sm:text-3xl font-bold text-brand-navy">$0 Upfront</div>
                <div class="text-[11px] text-emerald-600 font-bold mt-1">Success fee only upon placement</div>
            </div>
        </div>
    </div>
</section>

<!-- ── CITIES DIRECTORY GRID ── -->
<section id="cities-directory" class="py-16 sm:py-20 lg:py-24 bg-[#F8FAFD]">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-brand-cyan">LOCAL MARKETS</span>
                <h2 class="font-display text-3xl sm:text-4xl text-brand-navy font-extrabold mt-2">
                    Metropolitan Areas in {{ $stateName }}
                </h2>
                <p class="text-slate-600 text-sm mt-2">Every metro features localized listing syndication, in-person showings, and licensed tenancy enforcement.</p>
            </div>
            <div class="mt-4 md:mt-0 text-xs font-bold text-slate-500">
                Showing {{ count($cities) }} Supported Cities
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($cities as $city)
            @php
                $cSlug = is_array($city['slug']) ? ($city['slug']['current'] ?? '') : $city['slug'];
                $cRent = isset($city['medianRent']) ? '$' . number_format($city['medianRent']) : '$2,100';
                $cPop = isset($city['population']) ? number_format($city['population']) : '250K+';
            @endphp
            <div class="bg-white rounded-3xl overflow-hidden border border-slate-200/80 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col group">
                <div class="h-56 bg-slate-100 relative overflow-hidden">
                    <img src="{{ $city['heroImageUrl'] ?? 'https://images.unsplash.com/photo-1444723121867-7a241cacace9?auto=format&fit=crop&w=1200&q=80' }}" 
                         alt="{{ $city['heroImageAlt'] ?? ($city['title'] . ' cityscape') }}" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-brand-navy/80 via-transparent to-transparent"></div>
                    <div class="absolute bottom-4 left-4 right-4 flex justify-between items-end text-white">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-brand-cyan">{{ $stateAbbr }} Metro</span>
                            <h3 class="font-display text-2xl font-bold leading-tight">{{ $city['title'] }}</h3>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] text-slate-300 block uppercase font-bold">Median</span>
                            <span class="font-display text-lg font-bold text-brand-cyan">{{ $cRent }}</span>
                        </div>
                    </div>
                </div>

                <div class="p-6 flex flex-col flex-1">
                    <p class="text-sm text-slate-600 line-clamp-3 leading-relaxed mb-6">
                        {{ $city['marketNote'] ?? "Premier leasing services, tenant screening, and rent guarantee across {$city['title']} and surrounding neighbourhoods." }}
                    </p>
                    <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs text-slate-400 font-semibold"><i class="fa-solid fa-users text-slate-400 mr-1"></i> {{ $cPop }} pop</span>
                        <a href="{{ url('/us/' . $state['slug'] . '/' . $cSlug) }}" class="text-xs font-bold text-brand-navy group-hover:text-brand-cyan transition flex items-center gap-1.5">
                            City Hub <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-12 bg-white rounded-3xl border border-slate-200">
                <p class="text-slate-500 text-sm">No cities cataloged for this state yet.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- ── STATE ADVANTAGE (LIGHT BENTO) ── -->
<section class="py-16 sm:py-20 bg-white">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="text-center max-w-3xl mx-auto mb-14">
            <span class="text-xs font-bold uppercase tracking-widest text-brand-cyan">STATEWIDE INFRASTRUCTURE</span>
            <h2 class="font-display text-3xl sm:text-4xl text-brand-navy font-extrabold mt-2">
                Why Property Owners Choose Housing 365 in {{ $stateName }}
            </h2>
            <p class="text-slate-600 text-base mt-3">We combine deep local knowledge of {{ $stateAbbr }} residential landlord-tenant statutes with high-powered digital syndication.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="p-8 rounded-3xl bg-[#F8FAFD] border border-slate-200/80 hover:border-brand-cyan/40 transition duration-300">
                <div class="size-14 rounded-2xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-2xl mb-6">
                    <i class="fa-solid fa-gavel"></i>
                </div>
                <h3 class="font-display text-xl text-brand-navy font-bold mb-2">{{ $stateAbbr }} Statutory Compliance</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Custom lease agreements strictly drafted according to {{ $stateName }} state statutes, security deposit escrow regulations, statutory disclosure forms, and mandatory notice periods.
                </p>
            </div>
            <div class="p-8 rounded-3xl bg-[#F8FAFD] border border-slate-200/80 hover:border-brand-cyan/40 transition duration-300">
                <div class="size-14 rounded-2xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-2xl mb-6">
                    <i class="fa-solid fa-network-wired"></i>
                </div>
                <h3 class="font-display text-xl text-brand-navy font-bold mb-2">High-Velocity Syndication</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Direct automated push distribution to 40+ leading rental channels (Zillow, Realtor.com, Trulia, HotPads, MLS), capturing incoming corporate relocations and qualified residents.
                </p>
            </div>
            <div class="p-8 rounded-3xl bg-[#F8FAFD] border border-slate-200/80 hover:border-brand-cyan/40 transition duration-300">
                <div class="size-14 rounded-2xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-2xl mb-6">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                </div>
                <h3 class="font-display text-xl text-brand-navy font-bold mb-2">Pure Performance Pricing</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Transparent pricing with no monthly retainers. You only pay our placement fee after a thoroughly vetted tenant signs the lease and deposits first month's funds.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ── STATEWIDE REGULATORY & LEASING FAQS (FULL WIDTH 2-COLUMN) ── -->
<section class="py-16 sm:py-20 lg:py-24 bg-[#F8FAFD] border-t border-slate-200/80">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12 pb-6 border-b border-slate-200">
            <div>
                <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                    <span class="h-2 w-2 rounded-full bg-brand-cyan"></span>
                    REGULATORY COMPLIANCE &middot; {{ strtoupper($stateName) }} FAQ
                </div>
                <h2 class="font-display text-3xl font-extrabold tracking-tight text-brand-navy sm:text-4xl">
                    {{ $stateName }} Leasing Frequently Asked Questions
                </h2>
                <p class="mt-3 text-base text-slate-600 max-w-2xl">
                    Key answers regarding local tenancy rules, security deposit limits, and tenant screening in {{ $stateName }}.
                </p>
            </div>
            <div class="shrink-0">
                <a href="{{ url('/contact?type=owner') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-bold text-brand-navy shadow-sm transition-all hover:bg-slate-50 hover:border-slate-400">
                    <span>Ask a Regional Advisor</span>
                    <i class="fa-solid fa-arrow-right text-xs text-brand-cyan"></i>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start w-full">
            <div class="p-6 rounded-2xl bg-white border border-slate-200/90 shadow-sm transition hover:border-brand-cyan/40 hover:shadow-md">
                <h3 class="font-bold text-brand-navy text-base sm:text-lg mb-2 flex items-start gap-3">
                    <span class="inline-flex items-center justify-center rounded-lg bg-sky-50 px-2.5 py-1 text-xs font-bold text-brand-cyan border border-sky-100">01</span>
                    <span>How fast do properties rent in {{ $stateName }}?</span>
                </h3>
                <p class="text-sm sm:text-base text-slate-600 leading-relaxed pl-9">
                    Across our active {{ $stateName }} metros, well-priced residential units typically receive multiple verified applications within 10 to 14 days of multi-channel syndication and 3D architectural virtual staging.
                </p>
            </div>

            <div class="p-6 rounded-2xl bg-white border border-slate-200/90 shadow-sm transition hover:border-brand-cyan/40 hover:shadow-md">
                <h3 class="font-bold text-brand-navy text-base sm:text-lg mb-2 flex items-start gap-3">
                    <span class="inline-flex items-center justify-center rounded-lg bg-sky-50 px-2.5 py-1 text-xs font-bold text-brand-cyan border border-sky-100">02</span>
                    <span>What background checks are performed on applicants?</span>
                </h3>
                <p class="text-sm sm:text-base text-slate-600 leading-relaxed pl-9">
                    Every adult applicant passes through our 75-point verification suite: tri-bureau Equifax and TransUnion credit history, nationwide criminal background checks, eviction registry screening, direct payroll validation, and landlord reference interviews.
                </p>
            </div>

            <div class="p-6 rounded-2xl bg-white border border-slate-200/90 shadow-sm transition hover:border-brand-cyan/40 hover:shadow-md">
                <h3 class="font-bold text-brand-navy text-base sm:text-lg mb-2 flex items-start gap-3">
                    <span class="inline-flex items-center justify-center rounded-lg bg-sky-50 px-2.5 py-1 text-xs font-bold text-brand-cyan border border-sky-100">03</span>
                    <span>How are security deposits managed in {{ $stateName }}?</span>
                </h3>
                <p class="text-sm sm:text-base text-slate-600 leading-relaxed pl-9">
                    All security deposits are collected electronically into segregated escrow accounts compliant with {{ $stateName }} banking statutes, complete with statutory receipt disclosures and condition inspection documentation.
                </p>
            </div>

            <div class="p-6 rounded-2xl bg-white border border-slate-200/90 shadow-sm transition hover:border-brand-cyan/40 hover:shadow-md">
                <h3 class="font-bold text-brand-navy text-base sm:text-lg mb-2 flex items-start gap-3">
                    <span class="inline-flex items-center justify-center rounded-lg bg-sky-50 px-2.5 py-1 text-xs font-bold text-brand-cyan border border-sky-100">04</span>
                    <span>What happens if a placed tenant defaults on rent?</span>
                </h3>
                <p class="text-sm sm:text-base text-slate-600 leading-relaxed pl-9">
                    Under our standard 12-month placement guarantee, if a placed tenant vacates or breaks their lease during the first 12 months, Housing 365 procures a qualified replacement tenant at zero additional placement fee. Owners enrolled in Rent Guarantee also receive 100% on-time monthly disbursements.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ── CTA BANNER (LIGHT MODERN) ── -->
<section class="py-16 sm:py-20 lg:py-24 bg-gradient-to-r from-sky-50 via-white to-cyan-50 border-t border-slate-200">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 text-center">
        <div class="max-w-3xl mx-auto">
            <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold text-brand-navy mb-4">
                Partner with Housing 365 in <span class="text-brand-cyan">{{ $stateName }}</span>
            </h2>
            <p class="text-slate-600 text-base sm:text-lg mb-8 leading-relaxed">
                Connect with our local {{ $stateAbbr }} leasing team for free market rental evaluations, owner portal demonstrations, or portfolio lease-up strategies.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('contact') }}" class="px-8 py-4 rounded-xl bg-brand-cyan hover:bg-brand-cyan-hover text-white font-bold text-xs sm:text-sm uppercase tracking-wider shadow-lg shadow-brand-cyan/25 transition hover:-translate-y-0.5">
                    Consult With A {{ $stateAbbr }} Leasing Agent
                </a>
                <a href="{{ route('pricing') }}" class="px-8 py-4 rounded-xl bg-white hover:bg-slate-50 text-brand-navy font-bold text-xs sm:text-sm uppercase tracking-wider border border-slate-300 shadow-sm transition">
                    View Transparent Pricing
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
