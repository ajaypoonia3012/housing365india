@extends('layouts.app')

@section('title', 'Housing365 India | Everything Property. One Platform. Verified Owners, Zero Brokerage & Legal Leasing')
@section('meta_description', 'Find, list, lease, verify, move, maintain, manage, buy and sell property across India — all through Housing365. ₹0 standard residential listing fee for owners.')

@section('content')

<!-- ── SECTION 1: HERO SHOWCASE WITH EVERYTHING PROPERTY. ONE PLATFORM ── -->
<section class="relative overflow-hidden bg-gradient-to-b from-[#F7FAFD] via-white to-white pt-8 pb-12 sm:pt-12 sm:pb-16 lg:pt-16 lg:pb-20">
    <!-- Glow Effects -->
    <div class="pointer-events-none absolute -left-40 top-0 h-[500px] w-[500px] rounded-full opacity-40 blur-3xl" style="background: radial-gradient(circle, rgba(0,174,239,0.18) 0%, transparent 70%)" aria-hidden="true"></div>
    <div class="pointer-events-none absolute right-0 top-20 h-[600px] w-[600px] rounded-full opacity-30 blur-3xl" style="background: radial-gradient(circle, rgba(0,174,239,0.12) 0%, transparent 70%)" aria-hidden="true"></div>

    <div class="relative mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-12 lg:gap-8">
            
            <!-- Left Column: Copy & CTAs -->
            <div class="lg:col-span-6 xl:col-span-6">
                <!-- Eyebrow Badge -->
                <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-brand-cyan/20 bg-brand-cyan/5 px-3.5 py-1 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                    <span class="h-1.5 w-1.5 rounded-full bg-brand-cyan"></span>
                    <span>EVERYTHING PROPERTY. ONE PLATFORM.</span>
                </div>

                <!-- Headline -->
                <h1 class="font-display text-4xl font-normal leading-[1.12] tracking-tight text-brand-navy sm:text-5xl md:text-6xl">
                    Everything Property.
                    <span class="block font-display font-medium text-brand-cyan mt-1">One Platform.</span>
                </h1>

                <!-- Subheadline -->
                <p class="mt-5 max-w-xl text-base leading-relaxed text-slate-600 sm:text-lg">
                    Find, list, lease, verify, move, maintain, manage, buy and sell property across India — all through Housing365. Enjoy <strong class="text-brand-navy font-bold">₹0 standard residential listing and leasing fee</strong> for owners.
                </p>

                <!-- Persona Quick Bar -->
                <div class="mt-6 flex flex-wrap items-center gap-2 text-xs font-semibold text-slate-600">
                    <span class="text-slate-400 text-[11px] uppercase tracking-wider mr-1">I am an:</span>
                    <a href="{{ route('owners') }}" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-brand-cyan/10 hover:text-brand-navy transition">Owner</a>
                    <a href="{{ route('tenants') }}" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-brand-cyan/10 hover:text-brand-navy transition">Tenant</a>
                    <a href="{{ route('franchising') }}#brokers" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-brand-cyan/10 hover:text-brand-navy transition">Broker</a>
                    <a href="{{ route('franchising') }}#builders" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-brand-cyan/10 hover:text-brand-navy transition">Builder</a>
                    <a href="{{ route('properties.index', ['property_type' => 'pg']) }}" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-brand-cyan/10 hover:text-brand-navy transition">PG &amp; Co-Living</a>
                    <a href="{{ route('properties.index', ['property_type' => 'coworking']) }}" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-brand-cyan/10 hover:text-brand-navy transition">Coworking</a>
                    <a href="{{ route('services.care') }}" class="px-2.5 py-1 rounded-lg bg-cyan-50 text-brand-cyan-dark hover:bg-cyan-100 transition font-bold">Housing365 Care</a>
                </div>

                <!-- CTA Buttons -->
                <div class="mt-8 flex flex-wrap items-center gap-4">
                    <a href="{{ route('properties.post') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand-navy px-7 py-3.5 text-sm font-bold text-white shadow-lg shadow-brand-navy/20 transition-all duration-200 hover:-translate-y-0.5 hover:bg-brand-navy-light">
                        <i class="fa-solid fa-circle-plus text-brand-cyan text-xs"></i>
                        <span>List my Property — Free</span>
                    </a>

                    <a href="{{ route('properties.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-7 py-3.5 text-sm font-bold text-brand-navy shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-slate-300 hover:bg-slate-50">
                        <i class="fa-solid fa-magnifying-glass text-xs text-brand-cyan"></i>
                        <span>Find a Property</span>
                    </a>
                </div>

                <!-- Owner Zero Fee Assurance Strip -->
                <div class="mt-8 inline-flex items-center gap-3 p-3 rounded-2xl bg-cyan-50/60 border border-cyan-100 text-xs text-slate-700">
                    <span class="flex size-7 items-center justify-center rounded-full bg-brand-cyan text-white text-xs">
                        <i class="fa-solid fa-check"></i>
                    </span>
                    <div>
                        <strong class="text-brand-navy font-bold block">₹0 Standard Residential Listing &amp; Leasing Fee</strong>
                        <span class="text-slate-500 text-[11px]">Free listing for owners &bull; Property Management is strictly opt-in and optional</span>
                    </div>
                </div>
            </div>

            <!-- Right Column: Visual with Architecture Image + Floating Property Performance Card -->
            <div class="relative lg:col-span-6 xl:col-span-6">
                <div class="relative h-[540px] sm:h-[600px] lg:h-[640px] w-full overflow-hidden rounded-3xl shadow-2xl shadow-slate-900/15 ring-1 ring-slate-200/80">
                    <img src="https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=1600&auto=format&fit=crop&q=80" 
                         alt="Luxury residential property building on Housing365 India" 
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/50 via-transparent to-transparent"></div>

                    <!-- Floating Glassmorphic/White Performance Card -->
                    <div class="absolute bottom-6 left-4 right-4 rounded-2xl border border-white/80 bg-white/95 p-4 shadow-2xl shadow-slate-900/30 backdrop-blur-md sm:bottom-8 sm:left-auto sm:right-8 sm:w-[460px] sm:p-5">
                        <!-- Card Header -->
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <h3 class="text-xs font-bold text-brand-navy sm:text-sm">
                                Verified Property Performance
                            </h3>
                            <div class="flex items-center gap-1 text-[11px] font-semibold text-slate-500">
                                <span>Pan-India Network</span>
                                <i class="fa-solid fa-shield-halved text-[9px] text-brand-cyan"></i>
                            </div>
                        </div>

                        <!-- 3 Metric Pills Row -->
                        <div class="mt-3 grid grid-cols-3 gap-2">
                            <div class="rounded-xl bg-emerald-50/80 p-2 sm:p-2.5">
                                <div class="flex items-center gap-1.5 text-emerald-600">
                                    <i class="fa-solid fa-arrow-trend-up text-xs"></i>
                                    <span class="text-xs font-bold sm:text-sm">₹0 Fee</span>
                                </div>
                                <p class="mt-0.5 text-[10px] text-slate-500">Owner Placement</p>
                            </div>

                            <div class="rounded-xl bg-sky-50/80 p-2 sm:p-2.5">
                                <div class="flex items-center gap-1.5 text-brand-cyan">
                                    <i class="fa-solid fa-circle-check text-xs"></i>
                                    <span class="text-xs font-bold sm:text-sm">100%</span>
                                </div>
                                <p class="mt-0.5 text-[10px] text-slate-500">Verified Owners</p>
                            </div>

                            <div class="rounded-xl bg-blue-50/80 p-2 sm:p-2.5">
                                <div class="flex items-center gap-1.5 text-blue-600">
                                    <i class="fa-solid fa-clock text-xs"></i>
                                    <span class="text-xs font-bold sm:text-sm">11-Month</span>
                                </div>
                                <p class="mt-0.5 text-[10px] text-slate-500">Legal Agreements</p>
                            </div>
                        </div>

                        <!-- SVG Area Chart -->
                        <div class="mt-3">
                            <div class="h-14 w-full">
                                <svg viewBox="0 0 300 60" class="h-full w-full overflow-visible" preserveAspectRatio="none">
                                    <defs>
                                        <linearGradient id="chartGrad" x1="0" y1="0" x2="0" y2="1">
                                            <stop offset="0%" stop-color="#00AEEF" stop-opacity="0.35" />
                                            <stop offset="100%" stop-color="#00AEEF" stop-opacity="0.0" />
                                        </linearGradient>
                                    </defs>
                                    <path d="M0,45 Q25,35 50,40 T100,28 T150,22 T200,30 T250,15 T300,10 L300,60 L0,60 Z" fill="url(#chartGrad)" />
                                    <path d="M0,45 Q25,35 50,40 T100,28 T150,22 T200,30 T250,15 T300,10" fill="none" stroke="#00AEEF" stroke-width="2.5" stroke-linecap="round" />
                                </svg>
                            </div>
                            <div class="mt-1 flex justify-between text-[9px] font-medium text-slate-400">
                                <span>Delhi NCR</span>
                                <span>Mumbai</span>
                                <span>Bengaluru</span>
                                <span>Hyderabad</span>
                                <span>Pune</span>
                                <span>Chennai</span>
                            </div>
                        </div>

                        <!-- Recent Activity Mini Feed -->
                        <div class="mt-3 border-t border-slate-100 pt-2.5">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                Live Platform Activity
                            </p>
                            <div class="mt-1.5 space-y-1.5 text-[11px]">
                                <div class="flex items-center justify-between text-slate-700">
                                    <div class="flex items-center gap-1.5">
                                        <span class="flex size-4 items-center justify-center rounded-full bg-brand-cyan/15 text-brand-cyan">
                                            <i class="fa-solid fa-users text-[8px]"></i>
                                        </span>
                                        <span>Direct owner inquiry verified</span>
                                    </div>
                                    <span class="text-[10px] text-slate-400">Just now</span>
                                </div>

                                <div class="flex items-center justify-between text-slate-700">
                                    <div class="flex items-center gap-1.5">
                                        <span class="flex size-4 items-center justify-center rounded-full bg-brand-cyan/15 text-brand-cyan">
                                            <i class="fa-solid fa-file-circle-check text-[8px]"></i>
                                        </span>
                                        <span>E-stamped lease agreement executed</span>
                                    </div>
                                    <span class="text-[10px] text-slate-400">1 hour ago</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <!-- ── HERO MARKETPLACE SEARCH & POST HUB ── -->
        <div class="mt-12 sm:mt-16 bg-white rounded-3xl p-6 sm:p-8 shadow-xl shadow-slate-900/5 border border-slate-200/80">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6 pb-6 border-b border-slate-100">
                <!-- Search Intent Tabs -->
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 mr-2 hidden sm:inline">Search:</span>
                    <button type="button" onclick="setSearchIntent('rent', 'residential', this)" class="intent-btn active px-4 py-2 rounded-xl text-xs font-bold bg-brand-navy text-white shadow-sm transition">
                        <i class="fa-solid fa-key mr-1.5 text-brand-cyan"></i> Rent
                    </button>
                    <button type="button" onclick="setSearchIntent('sale', 'residential', this)" class="intent-btn px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                        <i class="fa-solid fa-tag mr-1.5 text-emerald-600"></i> Buy
                    </button>
                    <button type="button" onclick="setSearchIntent('', 'commercial', this)" class="intent-btn px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                        <i class="fa-solid fa-building mr-1.5 text-indigo-600"></i> Commercial
                    </button>
                    <button type="button" onclick="setSearchIntent('sale', 'plot_land', this)" class="intent-btn px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                        <i class="fa-solid fa-map-location-dot mr-1.5 text-amber-600"></i> Plots &amp; Land
                    </button>
                </div>

                <!-- Direct Post Property Link -->
                <div class="hidden sm:flex items-center gap-2">
                    <span class="text-xs text-slate-500">Property Owner?</span>
                    <a href="{{ route('properties.post') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold transition">
                        <i class="fa-solid fa-circle-plus text-brand-cyan text-xs"></i>
                        <span>List Property Free</span>
                    </a>
                </div>
            </div>

            <!-- Search Form -->
            <form action="{{ route('properties.index') }}" method="GET" id="heroMarketplaceForm" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3.5 items-end">
                <input type="hidden" name="purpose" id="heroPurpose" value="rent">
                <input type="hidden" name="category" id="heroCategory" value="residential">

                <!-- Locality / Project -->
                <div class="lg:col-span-5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Locality, Landmark or Project</label>
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
                        <input type="text" name="q" placeholder="e.g. Bandra West, HSR Layout, DLF Phase 5, Cyber City..." class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl pl-9 pr-4 py-3 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan transition font-medium">
                    </div>
                </div>

                <!-- City -->
                <div class="lg:col-span-3">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">City</label>
                    <select name="city" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3 py-3 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan transition font-medium">
                        <option value="">All Indian Cities</option>
                        <option value="Bengaluru">Bengaluru</option>
                        <option value="Mumbai">Mumbai</option>
                        <option value="Delhi">Delhi NCR</option>
                        <option value="Gurugram">Gurugram</option>
                        <option value="Hyderabad">Hyderabad</option>
                        <option value="Pune">Pune</option>
                        <option value="Ahmedabad">Ahmedabad</option>
                        <option value="Jaipur">Jaipur</option>
                        <option value="Goa">Goa</option>
                    </select>
                </div>

                <!-- Budget Range Quick Helper -->
                <div class="lg:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Max Budget</label>
                    <select name="max_price" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3 py-3 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan transition font-medium">
                        <option value="">Any Budget</option>
                        <option value="30000">Under ₹30,000 /mo</option>
                        <option value="60000">Under ₹60,000 /mo</option>
                        <option value="150000">Under ₹1.5 Lakhs</option>
                        <option value="10000000">Under ₹1 Crore</option>
                        <option value="30000000">Under ₹3 Crores</option>
                        <option value="50000000">Under ₹5 Crores</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="lg:col-span-2">
                    <button type="submit" class="w-full py-3 px-4 rounded-xl bg-brand-cyan hover:bg-brand-cyan-hover text-white font-bold text-xs uppercase tracking-wider transition-all duration-200 shadow-md shadow-brand-cyan/20 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <span>Search</span>
                    </button>
                </div>
            </form>
        </div>

        <script>
            function setSearchIntent(purpose, category, btn) {
                document.getElementById('heroPurpose').value = purpose;
                document.getElementById('heroCategory').value = category;
                document.querySelectorAll('.intent-btn').forEach(b => {
                    b.classList.remove('bg-brand-navy', 'text-white', 'shadow-sm');
                    b.classList.add('bg-slate-100', 'text-slate-700');
                });
                btn.classList.remove('bg-slate-100', 'text-slate-700');
                btn.classList.add('bg-brand-navy', 'text-white', 'shadow-sm');
            }
        </script>

        <!-- ── HOUSING365 CARE & OWNER ZERO FEE ASSURANCE BANNER ── -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Owner-First Free Leasing Card -->
            <div class="relative overflow-hidden rounded-3xl bg-brand-navy p-6 sm:p-8 text-white shadow-xl border border-slate-700">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-cyan/20 text-brand-cyan text-[11px] font-black uppercase tracking-wider mb-2.5">
                    <i class="fa-solid fa-tag"></i>
                    <span>OWNER-FIRST PRICING</span>
                </div>
                <h3 class="font-display text-2xl font-bold text-white">
                    ₹0 Standard Listing &amp; Placement Fee
                </h3>
                <p class="text-slate-300 text-xs sm:text-sm mt-2 leading-relaxed">
                    List residential properties for free. Connect directly with verified tenants, manage applications, and execute legal agreements with zero placement charges.
                </p>
                <div class="mt-4 pt-4 border-t border-slate-800 flex items-center justify-between">
                    <span class="text-xs text-slate-400">Leasing Only: <strong class="text-emerald-400">Free</strong> &bull; Mgmt: <strong class="text-white">Optional</strong></span>
                    <a href="{{ route('pricing') }}" class="text-xs font-bold text-brand-cyan hover:underline flex items-center gap-1">
                        <span>View Pricing</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- Housing365 Care Concept Banner -->
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-[#0B1D3A] to-slate-950 p-6 sm:p-8 text-white shadow-xl border border-cyan-500/20">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-cyan/20 text-brand-cyan text-[11px] font-black uppercase tracking-wider mb-2.5">
                    <i class="fa-solid fa-shield-heart"></i>
                    <span>HOUSING365 CARE</span>
                </div>
                <h3 class="font-display text-2xl font-bold text-white">
                    "Your Property, Taken Care Of."
                </h3>
                <p class="text-slate-300 text-xs sm:text-sm mt-2 leading-relaxed">
                    Structured maintenance triage, periodic inspections, rental preparation, and optional property management for discerning property owners and NRIs across India.
                </p>
                <div class="mt-4 pt-4 border-t border-slate-800 flex items-center justify-between">
                    <span class="text-xs text-slate-400">Dedicated Service Coordinators</span>
                    <a href="{{ route('services.care') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-brand-cyan text-brand-navy font-bold text-xs hover:bg-cyan-300 transition">
                        <span>Explore Care</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- 5-Metric Stats Strip -->
        <div class="mt-12 rounded-2xl border border-slate-100 bg-white p-6 shadow-sm shadow-slate-200/50 sm:mt-16 sm:p-8">
            <div class="grid grid-cols-2 gap-6 sm:grid-cols-3 md:grid-cols-5 lg:divide-x lg:divide-slate-100">
                <div class="flex items-center gap-3.5">
                    <div class="flex size-11 shrink-0 items-center justify-center rounded-full bg-sky-50 text-brand-cyan ring-1 ring-brand-cyan/15">
                        <i class="fa-solid fa-house text-base"></i>
                    </div>
                    <div>
                        <p class="font-display text-lg font-bold text-brand-navy sm:text-xl">10K+</p>
                        <p class="text-xs text-slate-500">Properties Leased</p>
                    </div>
                </div>

                <div class="flex items-center gap-3.5 lg:pl-6">
                    <div class="flex size-11 shrink-0 items-center justify-center rounded-full bg-sky-50 text-brand-cyan ring-1 ring-brand-cyan/15">
                        <i class="fa-solid fa-heart text-base"></i>
                    </div>
                    <div>
                        <p class="font-display text-lg font-bold text-brand-navy sm:text-xl">98%</p>
                        <p class="text-xs text-slate-500">Owner Satisfaction</p>
                    </div>
                </div>

                <div class="flex items-center gap-3.5 lg:pl-6">
                    <div class="flex size-11 shrink-0 items-center justify-center rounded-full bg-sky-50 text-brand-cyan ring-1 ring-brand-cyan/15">
                        <i class="fa-solid fa-city text-base"></i>
                    </div>
                    <div>
                        <p class="font-display text-lg font-bold text-brand-navy sm:text-xl">50+</p>
                        <p class="text-xs text-slate-500">Indian Cities</p>
                    </div>
                </div>

                <div class="flex items-center gap-3.5 lg:pl-6">
                    <div class="flex size-11 shrink-0 items-center justify-center rounded-full bg-sky-50 text-brand-cyan ring-1 ring-brand-cyan/15">
                        <i class="fa-solid fa-shield-halved text-base"></i>
                    </div>
                    <div>
                        <p class="font-display text-lg font-bold text-brand-navy sm:text-xl">₹0 Fee</p>
                        <p class="text-xs text-slate-500">Owner Residential Listing</p>
                    </div>
                </div>

                <div class="flex items-center gap-3.5 lg:pl-6">
                    <div class="flex size-11 shrink-0 items-center justify-center rounded-full bg-sky-50 text-brand-cyan ring-1 ring-brand-cyan/15">
                        <i class="fa-solid fa-users text-base"></i>
                    </div>
                    <div>
                        <p class="font-display text-lg font-bold text-brand-navy sm:text-xl">100%</p>
                        <p class="text-xs text-slate-500">Direct Verified Network</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- ── SECTION 1.5: FEATURED MARKETPLACE PROPERTIES, PLOTS & COMMERCIAL LEASING ── -->
<section class="py-16 sm:py-20 lg:py-24 bg-[#F8FAFD] border-b border-slate-200/80">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        
        <!-- Section Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
            <div>
                <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                    <span class="h-1.5 w-1.5 rounded-full bg-brand-cyan"></span>
                    VERIFIED REAL ESTATE MARKETPLACE
                </div>
                <h2 class="font-display text-3xl sm:text-4xl lg:text-[2.6rem] font-normal tracking-tight text-brand-navy">
                    Properties, Commercial Spaces, Plots &amp; Lands
                </h2>
                <p class="mt-3 text-slate-600 text-sm sm:text-base max-w-2xl">
                    Discover prime verified properties across India's top metro hubs with 100% direct owner connect and zero brokerage.
                </p>
            </div>

            <!-- Quick Intent Filter Buttons -->
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('properties.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold bg-brand-navy text-white shadow-sm hover:bg-slate-800 transition">
                    All
                </a>
                <a href="{{ route('properties.index', ['purpose' => 'rent']) }}" class="px-4 py-2 rounded-xl text-xs font-bold bg-white text-slate-700 border border-slate-200 hover:border-brand-cyan transition">
                    Rentals
                </a>
                <a href="{{ route('properties.index', ['purpose' => 'sale']) }}" class="px-4 py-2 rounded-xl text-xs font-bold bg-white text-slate-700 border border-slate-200 hover:border-emerald-600 transition">
                    Buy / Sale
                </a>
                <a href="{{ route('properties.index', ['category' => 'commercial']) }}" class="px-4 py-2 rounded-xl text-xs font-bold bg-white text-slate-700 border border-slate-200 hover:border-indigo-600 transition">
                    Commercial
                </a>
                <a href="{{ route('properties.index', ['category' => 'plot_land']) }}" class="px-4 py-2 rounded-xl text-xs font-bold bg-white text-slate-700 border border-slate-200 hover:border-amber-600 transition">
                    Plots &amp; Land
                </a>
                <a href="{{ route('properties.post') }}" class="px-4 py-2 rounded-xl text-xs font-bold bg-amber-500 hover:bg-amber-600 text-white transition shadow-sm flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-plus"></i> Post Free
                </a>
            </div>
        </div>

        <!-- 6 Properties Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($featuredProperties as $prop)
                <div class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-sm transition-all duration-300 hover:shadow-xl hover:-translate-y-1 flex flex-col group">
                    <!-- Image Header -->
                    <div class="relative h-64 bg-slate-100 overflow-hidden">
                        <img src="{{ $prop->image ?? '/city-placeholder-b.svg' }}" 
                             alt="{{ $prop->title }}" 
                             onerror="this.src='/city-placeholder-b.svg'"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        
                        <!-- Top Left Badges: Purpose & Category -->
                        <div class="absolute top-4 left-4 flex flex-wrap gap-1.5 max-w-[80%]">
                            @if($prop->purpose === 'sale')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-600 text-white shadow-md">
                                    FOR SALE
                                </span>
                            @elseif($prop->purpose === 'lease')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-indigo-600 text-white shadow-md">
                                    COMMERCIAL LEASE
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-brand-navy/90 text-white backdrop-blur-md">
                                    FOR RENT
                                </span>
                            @endif

                            @if($prop->category === 'plot_land')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-amber-500 text-white shadow-md">
                                    PLOT / LAND
                                </span>
                            @elseif($prop->category === 'commercial')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-purple-600 text-white shadow-md">
                                    COMMERCIAL
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-white/90 text-slate-800 backdrop-blur-md">
                                    {{ $prop->property_type }}
                                </span>
                            @endif
                        </div>

                        <!-- Top Right: Zero Brokerage -->
                        <div class="absolute top-4 right-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-teal-500/90 text-white backdrop-blur-md shadow-sm">
                                Zero Brokerage
                            </span>
                        </div>

                        <!-- Bottom Right: Price Badge -->
                        <div class="absolute bottom-4 right-4 bg-white/95 backdrop-blur-md px-3.5 py-1.5 rounded-xl shadow-lg border border-white/60">
                            @if(!empty($prop->price_display))
                                <span class="text-base sm:text-lg font-bold text-brand-navy">{{ $prop->price_display }}</span>
                            @else
                                <span class="text-base sm:text-lg font-bold text-brand-navy">₹{{ number_format($prop->price, 0) }}</span>
                                <span class="text-xs text-slate-500 font-medium">{{ $prop->purpose === 'sale' ? '' : '/' . ($prop->price_frequency ?? 'mo') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="p-7 flex-grow flex flex-col justify-between">
                        <div>
                            <div class="text-xs font-bold text-brand-cyan mb-2 flex items-center justify-between">
                                <span class="flex items-center gap-1.5">
                                    <i class="fa-solid fa-location-dot"></i>
                                    <span>{{ $prop->locality ? $prop->locality . ', ' : '' }}{{ $prop->city }}, {{ $prop->state }}</span>
                                </span>
                                @if($prop->posted_by)
                                    <span class="text-[10px] uppercase font-bold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-md">By {{ $prop->posted_by }}</span>
                                @endif
                            </div>
                            <h3 class="font-heading font-bold text-lg text-brand-navy line-clamp-1 group-hover:text-brand-cyan transition-colors mb-2">
                                <a href="{{ route('properties.show', $prop->slug) }}">{{ $prop->title }}</a>
                            </h3>
                            <p class="text-slate-500 text-xs sm:text-sm line-clamp-2 mb-4 leading-relaxed">{{ $prop->description }}</p>
                        </div>

                        <!-- Specs Bar tailored to Category -->
                        <div class="pt-5 border-t border-slate-100">
                            <div class="flex items-center justify-between text-xs text-slate-600 font-medium mb-4">
                                @if($prop->category === 'plot_land')
                                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-vector-square text-brand-cyan"></i> {{ $prop->sqft ? $prop->sqft . ' ' . ($prop->area_unit ?? 'Sq.Yd') : 'Plot Area' }}</span>
                                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-shield-halved text-brand-cyan"></i> RERA Approved</span>
                                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-compass text-brand-cyan"></i> Clear Title</span>
                                @elseif($prop->category === 'commercial')
                                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-building text-brand-cyan"></i> {{ $prop->property_type ?? 'Office' }}</span>
                                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-ruler-combined text-brand-cyan"></i> {{ $prop->sqft ? number_format($prop->sqft) . ' ' . ($prop->area_unit ?? 'sqft') : 'Spacious' }}</span>
                                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-couch text-brand-cyan"></i> {{ $prop->furnishing ? ucfirst($prop->furnishing) : 'Fitted' }}</span>
                                @else
                                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-bed text-brand-cyan"></i> {{ $prop->bedrooms ?? 2 }} BHK</span>
                                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-bath text-brand-cyan"></i> {{ $prop->bathrooms ?? 2 }} Baths</span>
                                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-ruler-combined text-brand-cyan"></i> {{ $prop->sqft ? $prop->sqft . ' sqft' : 'Spacious' }}</span>
                                @endif
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <a href="{{ route('properties.show', $prop->slug) }}" class="block text-center py-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 text-brand-navy font-bold text-xs uppercase tracking-wider transition">
                                    View Details
                                </a>
                                <a href="https://wa.me/916376163263?text=Hi,%20I%20am%20interested%20in%20your%20listing:%20{{ urlencode($prop->title) }}%20(Price:%20{{ urlencode($prop->price_display ?? '₹' . number_format($prop->price)) }})" target="_blank" class="block text-center py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs uppercase tracking-wider transition shadow-sm flex items-center justify-center gap-1.5">
                                    <i class="fa-brands fa-whatsapp"></i> Contact
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 bg-white rounded-3xl border border-slate-200">
                    <p class="text-slate-500 text-sm">Listings are loading...</p>
                </div>
            @endforelse
        </div>

        <!-- Marketplace Banner Box -->
        <div class="mt-14 bg-gradient-to-r from-brand-navy via-brand-navy-light to-slate-900 rounded-3xl p-8 sm:p-12 text-white shadow-xl relative overflow-hidden">
            <div class="pointer-events-none absolute -right-20 -bottom-20 h-64 w-64 rounded-full bg-brand-cyan/20 blur-3xl"></div>
            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div>
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-brand-cyan/20 text-brand-cyan mb-3">
                        <i class="fa-solid fa-bullhorn text-xs"></i> 100% Free Listing Platform
                    </span>
                    <h3 class="font-display text-2xl sm:text-3xl font-bold tracking-tight text-white mb-2">
                        Have a Property to Sell, Rent or Commercial Space to Lease?
                    </h3>
                    <p class="text-slate-300 text-sm sm:text-base max-w-2xl">
                        List your flat, plot, office or commercial showroom in under 60 seconds with photos, price, and specs. Connect directly with genuine buyers &amp; tenants with zero brokerage.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-3 shrink-0">
                    <a href="{{ route('properties.post') }}" class="px-6 py-3.5 rounded-2xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs sm:text-sm uppercase tracking-wider shadow-lg shadow-amber-500/25 transition-all duration-200 hover:-translate-y-0.5 flex items-center gap-2">
                        <i class="fa-solid fa-circle-plus"></i>
                        <span>+ Post Free Property</span>
                    </a>
                    <a href="{{ route('properties.index') }}" class="px-6 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs sm:text-sm uppercase tracking-wider border border-white/20 transition">
                        View All Listings ({{ \App\Models\Property::count() }}+) &rarr;
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- ── SECTION 2: COMPLETE LEASING & PROPERTY MANAGEMENT SOLUTIONS (8 SERVICES) ── -->
<section class="bg-white py-16 sm:py-20 lg:py-24">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <!-- Section Header -->
        <div class="flex flex-col justify-between gap-4 md:flex-row md:items-end">
            <div class="max-w-3xl">
                <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                    <span class="h-1.5 w-1.5 rounded-full bg-brand-cyan"></span>
                    FOR PROPERTY OWNERS
                </div>
                <h2 class="font-display text-3xl font-normal tracking-tight text-brand-navy sm:text-4xl lg:text-[2.6rem]">
                    Complete Leasing &amp; Property Management Solutions
                </h2>
                <p class="mt-4 text-base leading-relaxed text-slate-600 sm:text-lg">
                    We handle everything from marketing and tenant screening to lease execution and ongoing management — so you get qualified tenants, faster lease-ups, and maximum rental value.
                </p>
            </div>

            <a href="{{ route('services') }}" class="group inline-flex shrink-0 items-center gap-1.5 text-sm font-bold text-brand-cyan transition hover:text-brand-cyan-hover">
                <span>Explore All Services</span>
                <i class="fa-solid fa-arrow-right text-xs transition-transform group-hover:translate-x-1"></i>
            </a>
        </div>

        <!-- 8-Service Cards Grid -->
        <div class="mt-12 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($services as $s)
                <div class="group relative flex flex-col justify-between rounded-2xl border border-slate-100 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-brand-cyan/30 hover:shadow-xl hover:shadow-brand-cyan/10">
                    <div>
                        <div class="flex size-12 items-center justify-center rounded-xl bg-sky-50 text-brand-cyan ring-1 ring-brand-cyan/20 transition-colors group-hover:bg-brand-cyan group-hover:text-white">
                            @if(str_contains($s->slug, 'placement'))
                                <i class="fa-solid fa-house text-xl"></i>
                            @elseif(str_contains($s->slug, 'leasing'))
                                <i class="fa-solid fa-clipboard-check text-xl"></i>
                            @elseif(str_contains($s->slug, 'screening'))
                                <i class="fa-solid fa-shield-halved text-xl"></i>
                            @elseif(str_contains($s->slug, 'agreement') || str_contains($s->slug, 'registration'))
                                <i class="fa-solid fa-file-contract text-xl"></i>
                            @elseif(str_contains($s->slug, 'insurance'))
                                <i class="fa-solid fa-shield text-xl"></i>
                            @elseif(str_contains($s->slug, 'guarantor'))
                                <i class="fa-solid fa-user-check text-xl"></i>
                            @elseif(str_contains($s->slug, 'preparation'))
                                <i class="fa-solid fa-wand-magic-sparkles text-xl"></i>
                            @else
                                <i class="fa-solid fa-display text-xl"></i>
                            @endif
                        </div>
                        <h3 class="mt-5 text-lg font-bold text-brand-navy">
                            {{ $s->title }}
                        </h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">
                            {{ $s->short_description }}
                        </p>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-100/80">
                        <a href="{{ route('services') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-brand-cyan transition group-hover:text-brand-cyan-hover">
                            <span>Learn More</span>
                            <i class="fa-solid fa-arrow-right text-[10px] transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ── SECTION 3: MAXIMIZE RENTAL VALUE WITH A TRUSTED PARTNER ── -->
<section class="relative overflow-hidden bg-[#F8FAFD] py-16 sm:py-20 lg:py-24">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="grid grid-cols-1 items-center gap-10 lg:grid-cols-12 lg:gap-8">
            
            <!-- Left Column: Interactive Video Player (matching React OwnerVideoPlayer) -->
            <div class="relative lg:col-span-5">
                @include('partials.owner-video-player')
            </div>

            <!-- Center Column: Text & Checklist -->
            <div class="lg:col-span-4 lg:pl-2">
                <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                    <span class="h-1.5 w-1.5 rounded-full bg-brand-cyan"></span>
                    {{ $settings['owner_val_eyebrow'] ?? 'OWNERS & LANDLORDS' }}
                </div>

                <h2 class="font-display text-3xl font-normal tracking-tight text-brand-navy sm:text-4xl">
                    {{ $settings['owner_val_title'] ?? 'Maximize Rental Value with a Trusted Partner' }}
                </h2>

                <p class="mt-4 text-sm leading-relaxed text-slate-600 sm:text-base">
                    From independent flats to luxury villas and multi-unit portfolios, choose the service model that fits your lifestyle.
                </p>

                <!-- 2 Service Options Comparison (Free Leasing vs Optional Paid Management) -->
                <div class="mt-6 space-y-3">
                    <div class="p-3.5 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-brand-navy flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-check text-emerald-500"></i>
                                <span>Leasing Only</span>
                            </span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase bg-emerald-100 text-emerald-800">₹0 Free</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Free standard listing, direct tenant verification, and digital agreement support with zero placement commissions.</p>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-cyan-50/50 border border-cyan-200/80 shadow-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-brand-navy flex items-center gap-1.5">
                                <i class="fa-solid fa-shield-heart text-brand-cyan"></i>
                                <span>Leasing + Property Management</span>
                            </span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase bg-brand-cyan/20 text-brand-navy">Opt-In &bull; Paid</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">End-to-end periodic inspections, maintenance triage, and tenant ledger. <em>Strictly optional — never mandatory.</em></p>
                    </div>
                </div>

                <!-- CTA -->
                <div class="mt-8 flex items-center gap-3">
                    <a href="{{ route('properties.post') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand-navy px-6 py-3.5 text-xs font-bold text-white shadow-md transition-all duration-200 hover:-translate-y-0.5 hover:bg-brand-navy-light">
                        <i class="fa-solid fa-circle-plus text-brand-cyan"></i>
                        <span>List Property Free</span>
                    </a>
                    <a href="{{ route('pricing') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                        <span>Compare Plans</span>
                    </a>
                </div>
            </div>

            <!-- Right Column: 3 Vertical Metric Cards -->
            <div class="space-y-4 lg:col-span-3">
                <div class="flex items-center gap-4 rounded-2xl border border-slate-100 bg-white p-5 shadow-sm transition hover:shadow-md">
                    <div class="flex size-14 shrink-0 items-center justify-center rounded-2xl bg-sky-50 text-brand-cyan ring-1 ring-brand-cyan/20">
                        <i class="fa-solid fa-rocket text-2xl"></i>
                    </div>
                    <div>
                        <p class="font-display text-2xl font-bold text-brand-navy sm:text-3xl">{{ $settings['owner_metric_1_val'] ?? '30%' }}</p>
                        <p class="text-xs font-semibold text-slate-500">{{ $settings['owner_metric_1_lbl'] ?? 'Faster Lease-Up' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 rounded-2xl border border-slate-100 bg-white p-5 shadow-sm transition hover:shadow-md">
                    <div class="flex size-14 shrink-0 items-center justify-center rounded-2xl bg-sky-50 text-brand-cyan ring-1 ring-brand-cyan/20">
                        <i class="fa-solid fa-arrow-trend-up text-2xl"></i>
                    </div>
                    <div>
                        <p class="font-display text-2xl font-bold text-brand-navy sm:text-3xl">{{ $settings['owner_metric_2_val'] ?? '15%' }}</p>
                        <p class="text-xs font-semibold text-slate-500">{{ $settings['owner_metric_2_lbl'] ?? 'Higher Rental Value' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 rounded-2xl border border-slate-100 bg-white p-5 shadow-sm transition hover:shadow-md">
                    <div class="flex size-14 shrink-0 items-center justify-center rounded-2xl bg-sky-50 text-brand-cyan ring-1 ring-brand-cyan/20">
                        <i class="fa-solid fa-award text-2xl"></i>
                    </div>
                    <div>
                        <p class="font-display text-2xl font-bold text-brand-navy sm:text-3xl">{{ $settings['owner_metric_3_val'] ?? '98%' }}</p>
                        <p class="text-xs font-semibold text-slate-500">{{ $settings['owner_metric_3_lbl'] ?? 'Owner Satisfaction' }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ── SECTION 4: FIND A HOME YOU'LL LOVE (FOR TENANTS WITH SEARCH) ── -->
<section class="bg-white py-16 sm:py-20 lg:py-24">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-12 lg:gap-10">
            
            <!-- Left Column: Copy & Actions -->
            <div class="lg:col-span-6">
                <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                    <span class="h-1.5 w-1.5 rounded-full bg-brand-cyan"></span>
                    {{ $settings['tenant_eyebrow'] ?? 'FOR TENANTS' }}
                </div>

                <h2 class="font-display text-3xl font-normal tracking-tight text-brand-navy sm:text-4xl lg:text-5xl">
                    {{ $settings['tenant_title'] ?? "Find a Home You'll Love" }}
                </h2>

                <p class="mt-4 max-w-xl text-base leading-relaxed text-slate-600 sm:text-lg">
                    {{ $settings['tenant_desc'] ?? 'Explore high-quality rental flats and apartments across top Indian metropolitan cities. We make the rental process simple, verified, and 100% broker-free.' }}
                </p>

                <!-- Action Buttons -->
                <div class="mt-8 flex flex-wrap items-center gap-4">
                    <a href="{{ route('properties.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand-cyan px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-brand-cyan/25 transition-all duration-200 hover:-translate-y-0.5 hover:bg-brand-cyan-hover">
                        <span>Browse Rentals</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>

                    <a href="{{ route('about') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-6 py-3.5 text-sm font-bold text-brand-navy shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:bg-slate-50">
                        Tenant Hub
                    </a>
                </div>

                <!-- 3 Pillars -->
                <div class="mt-10 grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="flex items-start gap-3">
                        <div class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-sky-50 text-brand-cyan ring-1 ring-brand-cyan/20">
                            <i class="fa-solid fa-shield-halved text-base"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-brand-navy">Verified Listings</p>
                            <p class="text-[11px] text-slate-500">100% genuine properties</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-sky-50 text-brand-cyan ring-1 ring-brand-cyan/20">
                            <i class="fa-solid fa-file-circle-check text-base"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-brand-navy">Easy Applications</p>
                            <p class="text-[11px] text-slate-500">Quick and secure process</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-sky-50 text-brand-cyan ring-1 ring-brand-cyan/20">
                            <i class="fa-solid fa-headset text-base"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-brand-navy">Responsive Support</p>
                            <p class="text-[11px] text-slate-500">We're here to help</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Photo with Property Search Card Overlay -->
            <div class="relative lg:col-span-6">
                <div class="relative aspect-[16/11] w-full overflow-hidden rounded-3xl shadow-xl shadow-slate-900/10 sm:aspect-[16/10]">
                    <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=1200&auto=format&fit=crop&q=80" 
                         alt="Happy couple looking for their dream rental home together" 
                         class="w-full h-full object-cover object-center">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/50 via-transparent to-transparent"></div>

                    <!-- Floating Property Search Box -->
                    <div class="absolute inset-x-4 bottom-4 rounded-2xl border border-white/40 bg-white/95 p-3.5 shadow-2xl backdrop-blur-md sm:inset-x-6 sm:bottom-6 sm:p-4">
                        <!-- Rent / Buy Tabs -->
                        <div class="flex items-center gap-1.5 pb-2.5">
                            <button type="button" class="rounded-lg px-3.5 py-1 text-xs font-bold bg-brand-cyan text-white shadow-sm">
                                Rent
                            </button>
                            <button type="button" class="rounded-lg px-3.5 py-1 text-xs font-bold text-slate-500 hover:text-slate-800">
                                Buy
                            </button>
                        </div>

                        <!-- Search Bar Form -->
                        <form action="{{ route('properties.index') }}" method="GET" class="flex items-center gap-2">
                            <div class="relative flex-1">
                                <input type="text" name="city" placeholder="City, society, or locality (e.g. Bandra, HSR Layout)" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-xs text-brand-navy placeholder-slate-400 outline-none ring-brand-cyan transition focus:border-brand-cyan focus:ring-2 sm:text-sm">
                            </div>
                            <button type="submit" aria-label="Search properties" class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-brand-cyan text-white shadow-md shadow-brand-cyan/25 transition hover:bg-brand-cyan-hover sm:size-11">
                                <i class="fa-solid fa-magnifying-glass text-sm"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ── SECTION 4.1: FLEXIBLE LIVING & WORKSPACES: PG, CO-LIVING & COWORKING ── -->
<section class="bg-[#F8FAFD] py-16 sm:py-20 lg:py-24 border-y border-slate-200/70">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
            <div>
                <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                    <span class="h-1.5 w-1.5 rounded-full bg-brand-cyan"></span>
                    MODERN LIVING &amp; HYBRID WORKSPACES
                </div>
                <h2 class="font-display text-3xl sm:text-4xl font-normal tracking-tight text-brand-navy">
                    PG, Co-Living &amp; Managed Coworking Spaces
                </h2>
                <p class="mt-3 text-slate-600 text-sm sm:text-base max-w-2xl">
                    Tailored accommodations for students, young professionals, and distributed startup teams across India's top business corridors.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('properties.index', ['property_type' => 'pg']) }}" class="px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-xs font-bold text-brand-navy hover:border-brand-cyan transition shadow-sm">
                    Browse All PGs
                </a>
                <a href="{{ route('properties.index', ['property_type' => 'coworking']) }}" class="px-4 py-2.5 rounded-xl bg-brand-navy text-white text-xs font-bold hover:bg-slate-800 transition shadow-sm">
                    Explore Coworking
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- PG Living Card -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm hover:shadow-lg transition flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl mb-4">
                        <i class="fa-solid fa-bed"></i>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded bg-amber-100 text-amber-800">Ready to Move</span>
                    <h3 class="font-display font-bold text-xl text-brand-navy mt-2">Paying Guest (PG) Stays</h3>
                    <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                        Single &amp; shared rooms with Wi-Fi, home-style meals, housekeeping, and biometric security in prime tech hubs.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-brand-navy">From ₹6,500/mo</span>
                    <a href="{{ route('properties.index', ['property_type' => 'pg']) }}" class="text-xs font-bold text-brand-cyan hover:underline">View PGs &rarr;</a>
                </div>
            </div>

            <!-- Co-Living Spaces Card -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm hover:shadow-lg transition flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-cyan-50 text-brand-cyan flex items-center justify-center text-xl mb-4">
                        <i class="fa-solid fa-people-roof"></i>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded bg-cyan-100 text-cyan-900">Community First</span>
                    <h3 class="font-display font-bold text-xl text-brand-navy mt-2">Managed Co-Living</h3>
                    <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                        Premium private suites with designer shared lounges, community game nights, gym access, and flexible lock-in terms.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-brand-navy">From ₹12,000/mo</span>
                    <a href="{{ route('properties.index', ['property_type' => 'co-living']) }}" class="text-xs font-bold text-brand-cyan hover:underline">Explore Co-Living &rarr;</a>
                </div>
            </div>

            <!-- Coworking Card -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm hover:shadow-lg transition flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl mb-4">
                        <i class="fa-solid fa-laptop-code"></i>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded bg-indigo-100 text-indigo-900">Workspaces</span>
                    <h3 class="font-display font-bold text-xl text-brand-navy mt-2">Coworking &amp; Private Cabins</h3>
                    <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                        Ergonomic hot desks, dedicated desks, and fully serviced sound-proof private team cabins with high-speed leased line internet.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-brand-navy">From ₹4,500/desk</span>
                    <a href="{{ route('properties.index', ['property_type' => 'coworking']) }}" class="text-xs font-bold text-brand-cyan hover:underline">Find Desks &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 4.2: PROFESSIONAL NETWORK: BROKERS, BUILDERS & FRANCHISING ── -->
<section class="bg-white py-16 sm:py-20 lg:py-24">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="relative overflow-hidden rounded-3xl bg-brand-navy p-8 sm:p-12 lg:p-16 text-white shadow-2xl">
            <div class="pointer-events-none absolute right-0 top-0 h-96 w-96 rounded-full bg-brand-cyan/15 blur-3xl"></div>
            
            <div class="relative z-10 max-w-3xl mb-10">
                <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                    <span class="h-1.5 w-1.5 rounded-full bg-brand-cyan"></span>
                    HOUSING365 PROFESSIONAL ECOSYSTEM
                </div>
                <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl font-normal tracking-tight text-white">
                    Built for Certified Brokers, Top Builders &amp; City Franchisees
                </h2>
                <p class="mt-4 text-slate-300 text-sm sm:text-base leading-relaxed">
                    We empower India's real estate professionals with institutional leasing infrastructure, verified buyer leads, and local franchise territories.
                </p>
            </div>

            <div class="relative z-10 grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Brokers -->
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/10">
                    <i class="fa-solid fa-id-badge text-2xl text-brand-cyan mb-3 block"></i>
                    <h3 class="font-bold text-lg text-white">Certified Broker Partner</h3>
                    <p class="text-xs text-slate-300 mt-2 leading-relaxed">
                        Zero platform fees on primary listings. Close verified tenant leads faster with digital agreements and automated commission escrows.
                    </p>
                    <a href="{{ route('franchising') }}#brokers" class="mt-4 inline-flex items-center gap-1.5 text-xs font-bold text-brand-cyan hover:text-cyan-200">
                        <span>Join Broker Network</span> &rarr;
                    </a>
                </div>

                <!-- Builders -->
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/10">
                    <i class="fa-solid fa-city text-2xl text-amber-400 mb-3 block"></i>
                    <h3 class="font-bold text-lg text-white">Builder Project Showcase</h3>
                    <p class="text-xs text-slate-300 mt-2 leading-relaxed">
                        Showcase RERA-registered inventory to pre-qualified high-net-worth buyers and institutional NRI investors pan-India.
                    </p>
                    <a href="{{ route('franchising') }}#builders" class="mt-4 inline-flex items-center gap-1.5 text-xs font-bold text-amber-400 hover:text-amber-300">
                        <span>Partner as a Builder</span> &rarr;
                    </a>
                </div>

                <!-- Franchising -->
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/10">
                    <i class="fa-solid fa-shop text-2xl text-emerald-400 mb-3 block"></i>
                    <h3 class="font-bold text-lg text-white">City Franchise Opportunities</h3>
                    <p class="text-xs text-slate-300 mt-2 leading-relaxed">
                        Exclusive pin-code and city operational rights. Operate Housing365 leasing, Care triage, and verification hubs locally.
                    </p>
                    <a href="{{ route('franchising') }}" class="mt-4 inline-flex items-center gap-1.5 text-xs font-bold text-emerald-400 hover:text-emerald-300">
                        <span>Explore Franchising</span> &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 5: EXPLORE VERIFIED RENTAL PROPERTIES ACROSS TOP INDIAN METRO CLUSTERS ── -->
<section class="bg-[#F8FAFD] py-16 sm:py-20 lg:py-24">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        
        <!-- Section Header -->
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <div class="mb-2 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                    <span class="h-1.5 w-1.5 rounded-full bg-brand-cyan"></span>
                    FEATURED RENTAL PROPERTIES
                </div>
                <h2 class="font-display text-3xl font-normal tracking-tight text-brand-navy sm:text-4xl">
                    Explore Rental Properties Across Top Indian Metro Clusters &amp; Cities
                </h2>
            </div>

            <a href="{{ route('properties.index') }}" class="group inline-flex shrink-0 items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-brand-navy shadow-sm transition hover:border-slate-300 hover:bg-slate-50">
                <span>View All Properties</span>
                <i class="fa-solid fa-arrow-right text-xs transition-transform group-hover:translate-x-1"></i>
            </a>
        </div>

        <!-- 4 Cards Grid -->
        <div class="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($featuredProperties as $p)
                <div class="group overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-200/50">
                    <!-- Image & Badges -->
                    <div class="relative aspect-[4/3] w-full overflow-hidden bg-slate-100">
                        <img src="{{ $p->image }}" 
                             alt="{{ $p->title }}" 
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        
                        <!-- Location Badge -->
                        <div class="absolute left-3 top-3 rounded-md bg-brand-cyan/90 px-2.5 py-1 text-[11px] font-bold text-white shadow-sm backdrop-blur-sm">
                            {{ $p->city }}, {{ $p->state }}
                        </div>

                        <!-- Heart Button -->
                        <button type="button" aria-label="Save property" class="absolute right-3 top-3 flex size-8 items-center justify-center rounded-full bg-black/30 text-white backdrop-blur-sm transition hover:bg-black/50">
                            <i class="fa-regular fa-heart text-xs"></i>
                        </button>
                    </div>

                    <!-- Card Body -->
                    <div class="p-4 sm:p-5">
                        <div class="flex items-baseline gap-1">
                            <span class="font-display text-xl font-bold text-brand-navy">
                                ₹{{ number_format($p->price, 0) }}
                            </span>
                            <span class="text-xs text-slate-500">/ month</span>
                        </div>

                        <h3 class="mt-1 text-sm font-bold text-brand-navy truncate">
                            {{ $p->title }}
                        </h3>

                        <!-- Specs -->
                        <div class="mt-4 flex items-center gap-3 text-xs text-slate-500 border-t border-slate-100 pt-3">
                            <span class="flex items-center gap-1">
                                <i class="fa-solid fa-bed text-brand-cyan text-[11px]"></i>
                                {{ $p->bedrooms }} BHK
                            </span>
                            <span>&bull;</span>
                            <span class="flex items-center gap-1">
                                <i class="fa-solid fa-bath text-brand-cyan text-[11px]"></i>
                                {{ $p->bathrooms }} Baths
                            </span>
                            <span>&bull;</span>
                            <span class="flex items-center gap-1">
                                <i class="fa-solid fa-ruler-combined text-brand-cyan text-[11px]"></i>
                                {{ number_format($p->sqft ?? 850) }} sqft
                            </span>
                        </div>

                        <!-- Link -->
                        <div class="mt-4">
                            <a href="{{ route('properties.show', $p->slug) }}" class="inline-flex items-center gap-1 text-xs font-bold text-brand-cyan transition group-hover:text-brand-cyan-hover">
                                <span>View Details</span>
                                <i class="fa-solid fa-arrow-right text-[10px] transition-transform group-hover:translate-x-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>

<!-- ── SECTION 6: RENTAL PROPERTIES IN MAJOR CITIES (6 CITY CARDS) ── -->
<section class="bg-white py-16 sm:py-20 lg:py-24">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        
        <!-- Section Header -->
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <div class="mb-2 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                    <span class="h-1.5 w-1.5 rounded-full bg-brand-cyan"></span>
                    ACROSS ALL 28 STATES &amp; UNION TERRITORIES OF INDIA
                </div>
                <h2 class="font-display text-3xl font-normal tracking-tight text-brand-navy sm:text-4xl">
                    Rental Properties in Major Cities
                </h2>
            </div>

            <a href="{{ route('locations') }}" class="group inline-flex shrink-0 items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-brand-navy shadow-sm transition hover:border-slate-300 hover:bg-slate-50">
                <span>View All Cities</span>
                <i class="fa-solid fa-arrow-right text-xs transition-transform group-hover:translate-x-1"></i>
            </a>
        </div>

        <!-- 6 City Cards Grid -->
        <div class="mt-10 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
            @foreach($cities as $c)
                <a href="{{ route('properties.index', ['city' => $c->name]) }}" class="group relative aspect-[3/4] overflow-hidden rounded-2xl bg-slate-900 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <img src="{{ $c->image }}" 
                         alt="{{ $c->name }} rental properties" 
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent transition-opacity group-hover:opacity-90"></div>
                    
                    <div class="absolute inset-x-0 bottom-0 p-4">
                        <p class="font-display text-base font-bold text-white transition-transform duration-300 group-hover:-translate-y-0.5 sm:text-lg">
                            {{ $c->name }}
                        </p>
                        <p class="text-xs text-white/75">
                            {{ $c->province_or_state }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>

    </div>
</section>

<!-- ── SECTION 7: CALL TO ACTION BANNER (LIGHT MODERN) ── -->
<section class="py-16 sm:py-20 lg:py-24 bg-gradient-to-r from-sky-50 via-white to-cyan-50">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="relative overflow-hidden rounded-3xl border border-brand-cyan/20 bg-white p-8 sm:p-14 lg:p-16 shadow-xl flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="max-w-2xl">
                <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                    <span class="size-2 rounded-full bg-brand-cyan animate-pulse"></span>
                    {{ $settings['cta_banner_eyebrow'] ?? 'GET STARTED TODAY' }}
                </div>

                <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold text-brand-navy">
                    {{ $settings['cta_banner_title'] ?? "Let's Make Your Rental Property a Success" }}
                </h2>

                <p class="mt-4 text-base sm:text-lg text-slate-600">
                    {{ $settings['cta_banner_desc'] ?? 'Connect with our dedicated leasing and property management team today.' }}
                </p>
            </div>

            <div class="shrink-0">
                <a href="{{ $settings['cta_banner_button_link'] ?? route('contact') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand-cyan px-8 py-4 text-base font-bold text-white shadow-lg shadow-brand-cyan/25 transition-all duration-200 hover:-translate-y-0.5 hover:bg-brand-cyan-hover">
                    <span>{{ $settings['cta_banner_button_text'] ?? 'Talk to an Expert' }}</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    </div>
</section>


@endsection
