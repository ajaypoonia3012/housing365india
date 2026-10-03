@extends('layouts.app')

@section('title', 'Resources & Guides | Housing365 India — Everything Property. One Platform.')
@section('meta_description', 'Comprehensive property resources, tenant & landlord guides, legal leasing checklists, rental market insights, and Indian city directories from Housing365 India.')

@section('content')

<!-- ── BREADCRUMB ── -->
<div class="bg-white border-b border-slate-100 py-3.5 text-sm text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-cyan transition font-medium">Home</a>
        <span class="text-slate-300">/</span>
        <span class="text-brand-navy font-bold">Resources &amp; Guides</span>
    </div>
</div>

<!-- ── HERO SECTION ── -->
<section class="relative overflow-hidden bg-gradient-to-b from-[#F7FAFD] via-white to-[#F0F6FC] py-16 sm:py-20 lg:py-24">
    <div class="pointer-events-none absolute -left-40 top-0 h-[500px] w-[500px] rounded-full opacity-40 blur-3xl" style="background: radial-gradient(circle, rgba(0,174,239,0.18) 0%, transparent 70%)" aria-hidden="true"></div>

    <div class="relative mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 text-center max-w-4xl">
        <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-brand-cyan/20 bg-brand-cyan/5 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-brand-cyan">
            <span class="h-2 w-2 rounded-full bg-brand-cyan"></span>
            HOUSING365 KNOWLEDGE HUB
        </div>
        <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-extrabold text-brand-navy leading-tight tracking-tight">
            Property Guides, Legal Templates &amp; <span class="text-brand-cyan">Market Insights</span>
        </h1>
        <p class="mt-5 text-base sm:text-lg text-slate-600 leading-relaxed max-w-2xl mx-auto">
            Practical, verified guides for owners, tenants, brokers, and builders across India. From Model Tenancy rules to e-stamping and property maintenance.
        </p>
    </div>
</section>

<!-- ── RESOURCE CATEGORIES ── -->
<section class="py-16 sm:py-20 bg-white border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <!-- Category 1: Owner Knowledge -->
            <div class="p-8 rounded-3xl border border-slate-200 bg-white hover:border-brand-cyan/40 hover:shadow-xl transition flex flex-col justify-between">
                <div>
                    <div class="size-12 rounded-2xl bg-sky-50 text-brand-cyan flex items-center justify-center text-xl mb-6">
                        <i class="fa-solid fa-house-chimney-user"></i>
                    </div>
                    <h3 class="text-xl font-bold text-brand-navy mb-2">Owner &amp; Landlord Guides</h3>
                    <p class="text-slate-600 text-sm leading-relaxed mb-4">
                        How to price rental properties, screen tenants effectively, draft registered 11-month agreements, and choose optional property management.
                    </p>
                    <ul class="space-y-2 text-xs font-semibold text-slate-700">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-brand-cyan"></i> Free residential listing process</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-brand-cyan"></i> Security deposit regulations by state</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-brand-cyan"></i> NRI property management guide</li>
                    </ul>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100">
                    <a href="{{ route('owners') }}" class="text-xs font-bold text-brand-cyan hover:underline flex items-center gap-1.5">
                        <span>Explore Owner Hub</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- Category 2: Tenant Knowledge -->
            <div class="p-8 rounded-3xl border border-slate-200 bg-white hover:border-brand-cyan/40 hover:shadow-xl transition flex flex-col justify-between">
                <div>
                    <div class="size-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl mb-6">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <h3 class="text-xl font-bold text-brand-navy mb-2">Tenant &amp; Renter Guides</h3>
                    <p class="text-slate-600 text-sm leading-relaxed mb-4">
                        Renting in Indian metros, understanding Leave &amp; License terms, avoiding hidden brokerage, and moving into gated societies smoothly.
                    </p>
                    <ul class="space-y-2 text-xs font-semibold text-slate-700">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-brand-cyan"></i> City-by-city rental benchmarks</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-brand-cyan"></i> Moving checklist &amp; utility setup</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-brand-cyan"></i> Society NOC &amp; move-in gate passes</li>
                    </ul>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100">
                    <a href="{{ route('tenants') }}" class="text-xs font-bold text-brand-cyan hover:underline flex items-center gap-1.5">
                        <span>Explore Tenant Hub</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- Category 3: Legal & Documentation -->
            <div class="p-8 rounded-3xl border border-slate-200 bg-white hover:border-brand-cyan/40 hover:shadow-xl transition flex flex-col justify-between">
                <div>
                    <div class="size-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl mb-6">
                        <i class="fa-solid fa-scale-balanced"></i>
                    </div>
                    <h3 class="text-xl font-bold text-brand-navy mb-2">Legal &amp; Compliance</h3>
                    <p class="text-slate-600 text-sm leading-relaxed mb-4">
                        Model Tenancy Act provisions, state-wise stamp duty rates, official e-stamping processes, and police tenant intimation guidelines.
                    </p>
                    <ul class="space-y-2 text-xs font-semibold text-slate-700">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-brand-cyan"></i> 11-Month agreement clauses</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-brand-cyan"></i> E-stamp duty calculation</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-brand-cyan"></i> Police verification requirements</li>
                    </ul>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100">
                    <a href="{{ url('/services/rental-agreement-registration') }}" class="text-xs font-bold text-brand-cyan hover:underline flex items-center gap-1.5">
                        <span>Agreement Assistance</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- Category 4: Housing365 Care -->
            <div class="p-8 rounded-3xl border border-slate-200 bg-white hover:border-brand-cyan/40 hover:shadow-xl transition flex flex-col justify-between">
                <div>
                    <div class="size-12 rounded-2xl bg-cyan-50 text-cyan-700 flex items-center justify-center text-xl mb-6">
                        <i class="fa-solid fa-screwdriver-wrench"></i>
                    </div>
                    <h3 class="text-xl font-bold text-brand-navy mb-2">Maintenance &amp; Care</h3>
                    <p class="text-slate-600 text-sm leading-relaxed mb-4">
                        Preventive property maintenance schedules, appliance care, monsoon waterproofing tips, and home painting guidance.
                    </p>
                    <ul class="space-y-2 text-xs font-semibold text-slate-700">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-brand-cyan"></i> Plumbing &amp; electrical safety</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-brand-cyan"></i> AC &amp; RO servicing schedules</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-brand-cyan"></i> Rental handover inspections</li>
                    </ul>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100">
                    <a href="{{ route('care') }}" class="text-xs font-bold text-brand-cyan hover:underline flex items-center gap-1.5">
                        <span>Housing365 Care</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- Category 5: Commercial, PG & Coworking -->
            <div class="p-8 rounded-3xl border border-slate-200 bg-white hover:border-brand-cyan/40 hover:shadow-xl transition flex flex-col justify-between">
                <div>
                    <div class="size-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl mb-6">
                        <i class="fa-solid fa-building"></i>
                    </div>
                    <h3 class="text-xl font-bold text-brand-navy mb-2">Commercial &amp; Shared Spaces</h3>
                    <p class="text-slate-600 text-sm leading-relaxed mb-4">
                        Commercial lease structuring, selecting coworking spaces for hybrid teams, and PG/co-living operator operational guidelines.
                    </p>
                    <ul class="space-y-2 text-xs font-semibold text-slate-700">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-brand-cyan"></i> Bare-shell vs warm-shell offices</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-brand-cyan"></i> Co-living occupancy agreements</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-brand-cyan"></i> Commercial fit-out checklists</li>
                    </ul>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100">
                    <a href="{{ route('properties.index', ['category' => 'commercial']) }}" class="text-xs font-bold text-brand-cyan hover:underline flex items-center gap-1.5">
                        <span>Commercial Properties</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- Category 6: City & State Hubs -->
            <div class="p-8 rounded-3xl border border-slate-200 bg-white hover:border-brand-cyan/40 hover:shadow-xl transition flex flex-col justify-between">
                <div>
                    <div class="size-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl mb-6">
                        <i class="fa-solid fa-city"></i>
                    </div>
                    <h3 class="text-xl font-bold text-brand-navy mb-2">Indian City &amp; State Hubs</h3>
                    <p class="text-slate-600 text-sm leading-relaxed mb-4">
                        Neighborhood guides and rental market intelligence across all 28 Indian States and Union Territories.
                    </p>
                    <ul class="space-y-2 text-xs font-semibold text-slate-700">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-brand-cyan"></i> Bengaluru, Mumbai &amp; Delhi NCR</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-brand-cyan"></i> Hyderabad, Pune &amp; Jaipur</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-brand-cyan"></i> Micro-market locality ratings</li>
                    </ul>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100">
                    <a href="{{ route('india.index') }}" class="text-xs font-bold text-brand-cyan hover:underline flex items-center gap-1.5">
                        <span>Explore All 28 States &rarr;</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ── LATEST EDITORIAL ARTICLES ── -->
<section class="py-16 sm:py-20 bg-[#F8FAFD]">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="flex items-center justify-between mb-10">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-brand-cyan">HOUSING365 EDITORIAL</span>
                <h2 class="font-display text-2xl sm:text-3xl font-bold text-brand-navy mt-1">Latest Articles &amp; Insights</h2>
            </div>
            <a href="{{ route('blog.index') }}" class="text-xs font-bold text-brand-cyan hover:underline">View All Articles &rarr;</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse(\App\Models\BlogPost::where('is_published', true)->latest('published_at')->take(3)->get() as $post)
                <article class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-lg transition flex flex-col justify-between">
                    <div class="p-6">
                        <span class="text-[10px] font-bold uppercase tracking-wider bg-brand-cyan/10 text-brand-cyan px-2.5 py-1 rounded-full mb-3 inline-block">
                            {{ $post->category ?? 'Rental Guide' }}
                        </span>
                        <h3 class="font-bold text-base text-brand-navy mb-2 line-clamp-2">
                            <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-brand-cyan transition">{{ $post->title }}</a>
                        </h3>
                        <p class="text-xs text-slate-500 line-clamp-3 leading-relaxed">{{ $post->excerpt }}</p>
                    </div>
                    <div class="p-6 pt-0 border-t border-slate-100 text-xs text-slate-400 flex items-center justify-between">
                        <span>{{ $post->read_time ?? '4 min read' }}</span>
                        <a href="{{ route('blog.show', $post->slug) }}" class="font-bold text-brand-cyan">Read Guide &rarr;</a>
                    </div>
                </article>
            @empty
                <div class="col-span-3 text-center py-8 text-slate-400 text-sm">Articles loading...</div>
            @endforelse
        </div>
    </div>
</section>

@endsection
