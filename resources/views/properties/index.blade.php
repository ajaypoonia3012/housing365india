@extends('layouts.app')

@section('title', 'Verified Properties, Plots, Lands & Commercial Leasing | Housing 365 India')
@section('meta_description', 'Search 100% owner-verified apartments, luxury villas, commercial offices, retail shops, and plots for sale and rent across India. Zero brokerage.')

@section('content')

<!-- ── SECTION 1: HERO SHOWCASE ── -->
<section class="relative overflow-hidden bg-gradient-to-b from-[#F7FAFD] via-white to-white pt-10 pb-12 sm:pt-14 sm:pb-16 border-b border-slate-100">
    <!-- Ambient Glow Effects -->
    <div class="pointer-events-none absolute -left-40 top-0 h-[500px] w-[500px] rounded-full opacity-40 blur-3xl" style="background: radial-gradient(circle, rgba(0,174,239,0.18) 0%, transparent 70%)" aria-hidden="true"></div>
    <div class="pointer-events-none absolute right-0 top-20 h-[600px] w-[600px] rounded-full opacity-30 blur-3xl" style="background: radial-gradient(circle, rgba(0,174,239,0.12) 0%, transparent 70%)" aria-hidden="true"></div>

    <div class="relative mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="max-w-4xl mx-auto text-center">
            <!-- Eyebrow Badge -->
            <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-brand-cyan/20 bg-brand-cyan/5 px-3.5 py-1 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                <span class="h-1.5 w-1.5 rounded-full bg-brand-cyan"></span>
                INDIA'S VERIFIED REAL ESTATE MARKETPLACE
            </div>

            <!-- Headline -->
            <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold leading-[1.15] tracking-tight text-brand-navy">
                Buy, Rent, Sell &amp; Lease <br class="hidden sm:inline">
                <span class="text-brand-cyan font-extrabold">Flats, Commercial Spaces, Plots &amp; Lands.</span>
            </h1>

            <!-- Subheadline -->
            <p class="mt-4 text-base leading-relaxed text-slate-600 sm:text-lg max-w-2xl mx-auto">
                Discover 100% verified properties with zero broker spam. Looking to sell or lease your property? Post it free in 60 seconds.
            </p>

            <!-- Action Pill CTAs -->
            <div class="mt-8 flex flex-wrap justify-center items-center gap-3">
                <a href="{{ route('properties.post') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-brand-cyan hover:bg-brand-cyan-hover text-white font-bold text-sm shadow-lg shadow-brand-cyan/25 transition-all duration-200 hover:-translate-y-0.5">
                    <i class="fa-solid fa-circle-plus"></i>
                    <span>+ Post Free Property</span>
                    <span class="px-2 py-0.5 text-[10px] rounded-full bg-white/20 font-bold uppercase">Zero Brokerage</span>
                </a>
                <a href="#marketplace-filters" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-white border border-slate-200 hover:border-brand-cyan text-brand-navy font-bold text-sm shadow-sm transition">
                    <i class="fa-solid fa-sliders text-brand-cyan"></i>
                    <span>Explore Listings ({{ $counts['all'] ?? 80 }}+)</span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 2: SEARCH & LISTINGS ── -->
<section class="py-10 sm:py-14 bg-[#F8FAFD]" id="marketplace-filters">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        
        <!-- Marketplace Quick Intent Tabs -->
        <div class="mb-6 flex flex-wrap items-center gap-2">
            <a href="{{ route('properties.index') }}" 
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ !request('purpose') && !request('category') ? 'bg-brand-navy text-white shadow-md' : 'bg-white text-slate-700 border border-slate-200 hover:border-brand-cyan' }}">
                <i class="fa-solid fa-layer-group"></i>
                <span>All Properties</span>
                <span class="ml-1 px-1.5 py-0.5 rounded-full text-[10px] {{ !request('purpose') && !request('category') ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $counts['all'] ?? 0 }}</span>
            </a>

            <a href="{{ route('properties.index', ['purpose' => 'rent']) }}" 
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ request('purpose') === 'rent' && !request('category') ? 'bg-brand-cyan text-white shadow-md' : 'bg-white text-slate-700 border border-slate-200 hover:border-brand-cyan' }}">
                <i class="fa-solid fa-key"></i>
                <span>For Rent</span>
                <span class="ml-1 px-1.5 py-0.5 rounded-full text-[10px] {{ request('purpose') === 'rent' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $counts['rent'] ?? 0 }}</span>
            </a>

            <a href="{{ route('properties.index', ['purpose' => 'sale']) }}" 
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ request('purpose') === 'sale' && !request('category') ? 'bg-emerald-600 text-white shadow-md' : 'bg-white text-slate-700 border border-slate-200 hover:border-brand-cyan' }}">
                <i class="fa-solid fa-tag"></i>
                <span>For Sale</span>
                <span class="ml-1 px-1.5 py-0.5 rounded-full text-[10px] {{ request('purpose') === 'sale' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $counts['sale'] ?? 0 }}</span>
            </a>

            <a href="{{ route('properties.index', ['category' => 'commercial']) }}" 
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ request('category') === 'commercial' ? 'bg-indigo-600 text-white shadow-md' : 'bg-white text-slate-700 border border-slate-200 hover:border-brand-cyan' }}">
                <i class="fa-solid fa-building"></i>
                <span>Commercial Leasing</span>
                <span class="ml-1 px-1.5 py-0.5 rounded-full text-[10px] {{ request('category') === 'commercial' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $counts['commercial'] ?? 0 }}</span>
            </a>

            <a href="{{ route('properties.index', ['category' => 'plot_land']) }}" 
               class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ request('category') === 'plot_land' ? 'bg-amber-600 text-white shadow-md' : 'bg-white text-slate-700 border border-slate-200 hover:border-brand-cyan' }}">
                <i class="fa-solid fa-map-location-dot"></i>
                <span>Plots &amp; Lands</span>
                <span class="ml-1 px-1.5 py-0.5 rounded-full text-[10px] {{ request('category') === 'plot_land' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $counts['plot_land'] ?? 0 }}</span>
            </a>

            <!-- Post Property Button right on tab strip -->
            <a href="{{ route('properties.post') }}" class="ml-auto inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs uppercase tracking-wider shadow-sm transition">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>List Your Property Free</span>
            </a>
        </div>

        <!-- Micro-Locality Quick Chips (MagicBricks Jaipur & Indian Metros Style) -->
        @if(!empty($popularLocalities))
        <div class="mb-5 flex items-center gap-2 overflow-x-auto pb-2 scrollbar-thin">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider shrink-0 flex items-center gap-1.5 mr-1">
                <i class="fa-solid fa-location-crosshairs text-brand-cyan"></i>
                <span>Top Localities{{ request('city') ? ' in ' . request('city') : '' }}:</span>
            </span>
            <a href="{{ route('properties.index', array_merge(request()->except('locality'), ['locality' => 'all'])) }}"
               class="px-3.5 py-1.5 rounded-full text-xs font-bold whitespace-nowrap transition {{ !request('locality') || request('locality') === 'all' ? 'bg-brand-navy text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:border-brand-cyan hover:text-brand-navy' }}">
                All Localities
            </a>
            @foreach($popularLocalities as $loc)
                <a href="{{ route('properties.index', array_merge(request()->except('locality'), ['locality' => $loc])) }}"
                   class="px-3.5 py-1.5 rounded-full text-xs font-bold whitespace-nowrap transition {{ request('locality') === $loc ? 'bg-brand-cyan text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:border-brand-cyan hover:text-brand-navy' }}">
                    {{ $loc }}
                </a>
            @endforeach
        </div>
        @endif

        <!-- Filter Bar -->
        <div class="bg-white p-5 sm:p-7 rounded-3xl border border-slate-200/80 shadow-md mb-8 space-y-5">
            <form action="{{ route('properties.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3.5 items-end">
                <!-- Keep locality if selected from pills -->
                @if(request('locality') && request('locality') !== 'all')
                    <input type="hidden" name="locality" value="{{ request('locality') }}">
                @endif

                <!-- Keyword / Locality Search -->
                <div class="lg:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Search Locality / Project / Builder</label>
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-xs"></i>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="e.g. Vaishali Nagar, Jagatpura, Bandra, Plot" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl pl-9 pr-3 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan transition">
                    </div>
                </div>

                <!-- City -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">City</label>
                    <select name="city" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan transition font-medium">
                        <option value="">All Cities</option>
                        @foreach($cities as $c)
                            <option value="{{ $c }}" {{ request('city') == $c ? 'selected' : '' }}>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Category</label>
                    <select name="category" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan transition font-medium">
                        <option value="">All Categories</option>
                        <option value="residential" {{ request('category') == 'residential' ? 'selected' : '' }}>Residential</option>
                        <option value="commercial" {{ request('category') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                        <option value="plot_land" {{ request('category') == 'plot_land' ? 'selected' : '' }}>Plots &amp; Land</option>
                    </select>
                </div>

                <!-- Purpose / Intent -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Intent</label>
                    <select name="purpose" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan transition font-medium">
                        <option value="">Rent &amp; Buy</option>
                        <option value="rent" {{ request('purpose') == 'rent' ? 'selected' : '' }}>For Rent</option>
                        <option value="sale" {{ request('purpose') == 'sale' ? 'selected' : '' }}>For Sale</option>
                        <option value="lease" {{ request('purpose') == 'lease' ? 'selected' : '' }}>Commercial Lease</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-brand-cyan hover:bg-brand-cyan-hover text-white font-bold py-2.5 px-3 rounded-xl text-xs uppercase tracking-wider transition shadow-sm">
                        Apply
                    </button>
                    <a href="{{ route('properties.index') }}" class="py-2.5 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs uppercase tracking-wider transition flex items-center justify-center">
                        Reset
                    </a>
                </div>
            </form>

            <!-- Secondary Quick-Filter Row: BHK & Budget & Zero Brokerage (MagicBricks Inspired) -->
            <div class="pt-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4">
                <!-- BHK Buttons -->
                <div class="flex items-center gap-1.5 flex-wrap">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mr-1">BHK:</span>
                    <a href="{{ route('properties.index', array_merge(request()->except('bhk'), ['bhk' => 'all'])) }}"
                       class="px-2.5 py-1 rounded-lg text-xs font-bold transition {{ !request('bhk') || request('bhk') === 'all' ? 'bg-brand-navy text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">All</a>
                    <a href="{{ route('properties.index', array_merge(request()->except('bhk'), ['bhk' => 1])) }}"
                       class="px-2.5 py-1 rounded-lg text-xs font-bold transition {{ request('bhk') == '1' ? 'bg-brand-cyan text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">1 BHK</a>
                    <a href="{{ route('properties.index', array_merge(request()->except('bhk'), ['bhk' => 2])) }}"
                       class="px-2.5 py-1 rounded-lg text-xs font-bold transition {{ request('bhk') == '2' ? 'bg-brand-cyan text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">2 BHK</a>
                    <a href="{{ route('properties.index', array_merge(request()->except('bhk'), ['bhk' => 3])) }}"
                       class="px-2.5 py-1 rounded-lg text-xs font-bold transition {{ request('bhk') == '3' ? 'bg-brand-cyan text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">3 BHK</a>
                    <a href="{{ route('properties.index', array_merge(request()->except('bhk'), ['bhk' => 4])) }}"
                       class="px-2.5 py-1 rounded-lg text-xs font-bold transition {{ request('bhk') == '4' ? 'bg-brand-cyan text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">4+ BHK</a>
                </div>

                <!-- Indian Budget Presets -->
                <div class="flex items-center gap-1.5 flex-wrap">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mr-1">Budget:</span>
                    @if(request('purpose') === 'rent')
                        <a href="{{ route('properties.index', array_merge(request()->except(['min_price', 'max_price']), ['max_price' => 15000])) }}"
                           class="px-2.5 py-1 rounded-lg text-xs font-bold transition {{ request('max_price') == 15000 && !request('min_price') ? 'bg-brand-cyan text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">&lt; ₹15k</a>
                        <a href="{{ route('properties.index', array_merge(request()->except(['min_price', 'max_price']), ['min_price' => 15000, 'max_price' => 25000])) }}"
                           class="px-2.5 py-1 rounded-lg text-xs font-bold transition {{ request('min_price') == 15000 && request('max_price') == 25000 ? 'bg-brand-cyan text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">₹15k-25k</a>
                        <a href="{{ route('properties.index', array_merge(request()->except(['min_price', 'max_price']), ['min_price' => 25000, 'max_price' => 50000])) }}"
                           class="px-2.5 py-1 rounded-lg text-xs font-bold transition {{ request('min_price') == 25000 && request('max_price') == 50000 ? 'bg-brand-cyan text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">₹25k-50k</a>
                        <a href="{{ route('properties.index', array_merge(request()->except(['min_price', 'max_price']), ['min_price' => 50000])) }}"
                           class="px-2.5 py-1 rounded-lg text-xs font-bold transition {{ request('min_price') == 50000 && !request('max_price') ? 'bg-brand-cyan text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">₹50k+</a>
                    @else
                        <a href="{{ route('properties.index', array_merge(request()->except(['min_price', 'max_price']), ['max_price' => 3000000])) }}"
                           class="px-2.5 py-1 rounded-lg text-xs font-bold transition {{ request('max_price') == 3000000 && !request('min_price') ? 'bg-brand-cyan text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">&lt; ₹30 Lac</a>
                        <a href="{{ route('properties.index', array_merge(request()->except(['min_price', 'max_price']), ['min_price' => 3000000, 'max_price' => 6000000])) }}"
                           class="px-2.5 py-1 rounded-lg text-xs font-bold transition {{ request('min_price') == 3000000 && request('max_price') == 6000000 ? 'bg-brand-cyan text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">₹30L - 60L</a>
                        <a href="{{ route('properties.index', array_merge(request()->except(['min_price', 'max_price']), ['min_price' => 6000000, 'max_price' => 10000000])) }}"
                           class="px-2.5 py-1 rounded-lg text-xs font-bold transition {{ request('min_price') == 6000000 && request('max_price') == 10000000 ? 'bg-brand-cyan text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">₹60L - 1 Cr</a>
                        <a href="{{ route('properties.index', array_merge(request()->except(['min_price', 'max_price']), ['min_price' => 10000000])) }}"
                           class="px-2.5 py-1 rounded-lg text-xs font-bold transition {{ request('min_price') == 10000000 && !request('max_price') ? 'bg-brand-cyan text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">₹1 Cr+</a>
                    @endif
                </div>

                <!-- Owner / Zero Brokerage & Verified Toggles -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('properties.index', array_merge(request()->all(), ['posted_by' => request('posted_by') === 'Owner' ? 'all' : 'Owner'])) }}"
                       class="px-3 py-1 rounded-lg text-xs font-bold transition flex items-center gap-1.5 {{ request('posted_by') === 'Owner' ? 'bg-teal-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                        <i class="fa-solid fa-user-check text-[11px]"></i>
                        <span>Zero Brokerage (Owners)</span>
                    </a>
                    <a href="{{ route('properties.index', array_merge(request()->all(), ['verified' => request('verified') == '1' ? '0' : '1'])) }}"
                       class="px-3 py-1 rounded-lg text-xs font-bold transition flex items-center gap-1.5 {{ request('verified') == '1' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                        <i class="fa-solid fa-circle-check text-[11px]"></i>
                        <span>Verified Only</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Listings Header & View Switcher -->
        <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 text-xs font-semibold text-slate-500">
            <span>Showing <strong class="text-brand-navy">{{ $properties->total() }}</strong> verified available suites</span>

            <!-- Grid vs Map Switcher -->
            <div class="flex items-center gap-1.5 p-1 bg-white rounded-2xl border border-slate-200 shadow-sm">
                <button type="button" id="btn-view-grid" onclick="switchView('grid')" class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold bg-brand-cyan text-white shadow-sm transition">
                    <i class="fa-solid fa-grip text-xs"></i>
                    <span>Grid</span>
                </button>
                <button type="button" id="btn-view-map" onclick="switchView('map')" class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold text-slate-500 hover:text-brand-navy transition">
                    <i class="fa-solid fa-map-location-dot text-xs"></i>
                    <span>Map View</span>
                </button>
            </div>
        </div>

        <!-- Interactive Leaflet Map Container -->
        <div id="properties-map-wrapper" class="hidden mb-12">
            <div class="relative w-full h-[600px] rounded-3xl overflow-hidden border border-slate-200/80 shadow-xl bg-slate-100">
                <div id="properties-leaflet-map" class="w-full h-full"></div>
            </div>
        </div>

        <!-- Properties Grid -->
        <div id="properties-grid-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($properties as $prop)
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

                            @if($prop->featured)
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-brand-cyan text-white shadow-md">
                                    Featured
                                </span>
                            @endif
                        </div>

                        <!-- Top Right: Zero Brokerage / Verified -->
                        <div class="absolute top-4 right-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-teal-500/90 text-white backdrop-blur-md shadow-sm">
                                <i class="fa-solid fa-check text-[9px] mr-1"></i> Zero Brokerage
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

                        <!-- Amenities / Specs Bar tailored to Category -->
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
                                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-ruler-combined text-brand-cyan"></i> {{ $prop->sqft ? $prop->sqft . ' ' . ($prop->area_unit ?? 'sqft') : 'Spacious' }}</span>
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
                <div class="col-span-3 text-center py-16 bg-white rounded-3xl border border-slate-200/80 shadow-sm">
                    <div class="w-16 h-16 rounded-full bg-slate-50 text-slate-400 flex items-center justify-center mx-auto mb-4 text-2xl">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <h3 class="font-heading text-lg font-bold text-brand-navy mb-1">No matching properties found</h3>
                    <p class="text-slate-500 text-sm mb-6">Try clearing some of your search filters or check nearby locations.</p>
                    <a href="{{ route('properties.index') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-brand-cyan text-white text-xs font-bold uppercase tracking-wider">Clear All Filters</a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $properties->links() }}
        </div>
    </div>
</section>

<!-- ── SECTION 3: CALL TO ACTION BANNER (LIGHT MODERN) ── -->
<section class="py-16 sm:py-20 lg:py-24 bg-gradient-to-r from-sky-50 via-white to-cyan-50 border-t border-slate-200/80">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="relative overflow-hidden rounded-3xl border border-brand-cyan/20 bg-white p-8 sm:p-14 lg:p-16 shadow-xl flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="max-w-2xl">
                <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                    <span class="size-2 rounded-full bg-brand-cyan animate-pulse"></span>
                    LOOKING TO LEASE YOUR PROPERTY?
                </div>
                <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold text-brand-navy">
                    Have a Property to Add to Our Portfolio?
                </h2>
                <p class="mt-4 text-base sm:text-lg text-slate-600">
                    List your suite with Housing 365 for fast tenant placement and guaranteed monthly payouts.
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
@push('scripts')
<!-- Leaflet JS & CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
    const geoProperties = @json($allProperties ?? []);
    let mapInstance = null;

    function switchView(mode) {
        const gridEl = document.getElementById('properties-grid-container');
        const mapEl = document.getElementById('properties-map-wrapper');
        const btnGrid = document.getElementById('btn-view-grid');
        const btnMap = document.getElementById('btn-view-map');

        if (mode === 'map') {
            if (gridEl) gridEl.classList.add('hidden');
            if (mapEl) mapEl.classList.remove('hidden');
            if (btnGrid) btnGrid.className = 'flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold text-slate-500 hover:text-brand-navy transition';
            if (btnMap) btnMap.className = 'flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold bg-brand-cyan text-white shadow-sm transition';
            initMap();
        } else {
            if (mapEl) mapEl.classList.add('hidden');
            if (gridEl) gridEl.classList.remove('hidden');
            if (btnMap) btnMap.className = 'flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold text-slate-500 hover:text-brand-navy transition';
            if (btnGrid) btnGrid.className = 'flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold bg-brand-cyan text-white shadow-sm transition';
        }
    }

    function initMap() {
        if (mapInstance) {
            setTimeout(() => mapInstance.invalidateSize(), 150);
            return;
        }

        const defaultLat = 43.6532; // Toronto
        const defaultLng = -79.3832;

        mapInstance = L.map('properties-leaflet-map').setView([defaultLat, defaultLng], 11);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(mapInstance);

        const bounds = [];

        geoProperties.forEach(p => {
            let lat = parseFloat(p.latitude);
            let lng = parseFloat(p.longitude);

            if (!lat || !lng || isNaN(lat) || isNaN(lng)) {
                if (p.city && p.city.toLowerCase().includes('vancouver')) {
                    lat = 49.2827; lng = -123.1207;
                } else if (p.city && p.city.toLowerCase().includes('new york')) {
                    lat = 40.7128; lng = -74.0060;
                } else if (p.city && p.city.toLowerCase().includes('miami')) {
                    lat = 25.7617; lng = -80.1918;
                } else {
                    lat = 43.6532 + (Math.random() - 0.5) * 0.08;
                    lng = -79.3832 + (Math.random() - 0.5) * 0.08;
                }
            }

            bounds.push([lat, lng]);

            const priceFormatted = '₹' + Number(p.price || 0).toLocaleString('en-IN');

            const icon = L.divIcon({
                className: 'custom-map-marker',
                html: `<div style="background:#03234B; color:white; font-weight:bold; font-size:11px; padding:4px 8px; border-radius:9999px; border:2px solid #00AEEF; box-shadow:0 4px 12px rgba(0,0,0,0.3); white-space:nowrap; cursor:pointer;">${priceFormatted}</div>`,
                iconSize: [60, 24],
                iconAnchor: [30, 12]
            });

            const popupContent = `
                <div style="width:230px; font-family:'DM Sans', sans-serif; padding:4px;">
                    <img src="${p.image || '/city-placeholder-b.svg'}" style="width:100%; height:110px; object-fit:cover; border-radius:10px; margin-bottom:8px;" />
                    <div style="font-weight:bold; font-size:15px; color:#03234B;">${priceFormatted}<span style="font-size:11px; color:#64748B; font-weight:normal;">/mo</span></div>
                    <div style="font-weight:bold; font-size:12px; color:#0F172A; margin-top:2px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">${p.title}</div>
                    <div style="font-size:11px; color:#64748B; margin-top:2px;">${p.city}, ${p.state}</div>
                    <div style="font-size:11px; color:#00AEEF; font-weight:bold; margin-top:6px; display:flex; gap:8px;">
                        <span>${p.bedrooms || 1} BHK</span> &bull; <span>${p.bathrooms || 1} Baths</span>
                    </div>
                    <a href="/properties/${p.slug}" style="display:block; text-align:center; background:#00AEEF; color:white; font-size:11px; font-weight:bold; padding:6px 12px; border-radius:8px; margin-top:8px; text-decoration:none;">View Details &rarr;</a>
                </div>
            `;

            L.marker([lat, lng], { icon: icon })
                .addTo(mapInstance)
                .bindPopup(popupContent);
        });

        if (bounds.length > 0) {
            mapInstance.fitBounds(bounds, { padding: [50, 50], maxZoom: 14 });
        }
    }
</script>
@endpush

@endsection
