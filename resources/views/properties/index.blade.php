@extends('layouts.app')

@section('title', 'Verified Rental Properties Across India | Housing 365 India')
@section('meta_description', 'Search 100% owner-verified rental apartments, builder floors, and gated villas across Mumbai, Bengaluru, Delhi NCR, Hyderabad, and Pune. Zero broker spam.')

@section('content')

<!-- ── SECTION 1: HERO SHOWCASE ── -->
<section class="relative overflow-hidden bg-gradient-to-b from-[#F7FAFD] via-white to-white pt-10 pb-14 sm:pt-16 sm:pb-20 lg:pt-20 lg:pb-24">
    <!-- Ambient Glow Effects -->
    <div class="pointer-events-none absolute -left-40 top-0 h-[500px] w-[500px] rounded-full opacity-40 blur-3xl" style="background: radial-gradient(circle, rgba(0,174,239,0.18) 0%, transparent 70%)" aria-hidden="true"></div>
    <div class="pointer-events-none absolute right-0 top-20 h-[600px] w-[600px] rounded-full opacity-30 blur-3xl" style="background: radial-gradient(circle, rgba(0,174,239,0.12) 0%, transparent 70%)" aria-hidden="true"></div>

    <div class="relative mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="max-w-3xl mx-auto text-center">
            <!-- Eyebrow Badge -->
            <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-brand-cyan/20 bg-brand-cyan/5 px-3.5 py-1 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                <span class="h-1.5 w-1.5 rounded-full bg-brand-cyan"></span>
                VERIFIED SUITES & HOMES
            </div>

            <!-- Headline -->
            <h1 class="font-display text-4xl font-normal leading-[1.12] tracking-tight text-brand-navy sm:text-5xl md:text-6xl">
                Explore Available 
                <span class="font-display font-medium text-brand-cyan">Rental Properties.</span>
            </h1>

            <!-- Subheadline -->
            <p class="mt-5 text-base leading-relaxed text-slate-600 sm:text-lg">
                Browse our curated portfolio of verified apartments, luxury condos, and executive townhomes with 360° virtual tours and rapid digital lease applications.
            </p>

            <!-- Value Highlights -->
            <div class="mt-8 flex flex-wrap justify-center items-center gap-6 text-xs font-semibold text-slate-700">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-brand-cyan"></i>
                    <span>75-Point Physical Inspection</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-brand-cyan"></i>
                    <span>24/7 Digital Lease Application</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-brand-cyan"></i>
                    <span>Zero Duplicate Listings</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 2: SEARCH & LISTINGS ── -->
<section class="py-12 sm:py-16 bg-[#F8FAFD]">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        
        <!-- Filter Bar -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-md mb-10">
            <form action="{{ route('properties.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                <!-- City -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">City</label>
                    <select name="city" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan transition">
                        <option value="">All Cities</option>
                        @foreach($cities as $c)
                            <option value="{{ $c }}" {{ request('city') == $c ? 'selected' : '' }}>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Property Type -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Property Type</label>
                    <select name="property_type" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan transition">
                        <option value="">All Types</option>
                        @foreach($types as $t)
                            <option value="{{ $t }}" {{ request('property_type') == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Bedrooms -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Bedrooms</label>
                    <select name="bedrooms" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan transition">
                        <option value="">Any BHK Configuration</option>
                        <option value="1">1 BHK</option><option style="display:none;" value="1_legacy" {{ request('bedrooms') == '1' ? 'selected' : '' }}>1 BHK</option>
                        <option value="2" {{ request('bedrooms') == '2' ? 'selected' : '' }}>2 BHK</option>
                        <option value="3" {{ request('bedrooms') == '3' ? 'selected' : '' }}>3 BHK</option>
                    </select>
                </div>

                <!-- Max Price -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Max Rent</label>
                    <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max Monthly Rent" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan transition">
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-brand-cyan hover:bg-brand-cyan-hover text-white font-bold py-2.5 px-4 rounded-xl text-xs uppercase tracking-wider transition shadow-md shadow-brand-cyan/20">
                        Filter
                    </button>
                    <a href="{{ route('properties.index') }}" class="py-2.5 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs uppercase tracking-wider transition flex items-center justify-center">
                        Reset
                    </a>
                </div>
            </form>
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
                        <div class="absolute top-4 left-4 flex gap-2">
                            <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-brand-navy/90 text-white backdrop-blur-md">
                                {{ $prop->property_type }}
                            </span>
                            @if($prop->featured)
                                <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-brand-cyan text-white shadow-md">
                                    Featured
                                </span>
                            @endif
                        </div>
                        <div class="absolute bottom-4 right-4 bg-white/95 backdrop-blur-md px-3.5 py-1.5 rounded-xl shadow-lg border border-white/60">
                            <span class="text-base sm:text-lg font-bold text-brand-navy">₹{{ number_format($prop->price, 0) }}</span>
                            <span class="text-xs text-slate-500 font-medium">/{{ $prop->price_frequency }}</span>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="p-7 flex-grow flex flex-col justify-between">
                        <div>
                            <div class="text-xs font-bold text-brand-cyan mb-2 flex items-center gap-1.5">
                                <i class="fa-solid fa-location-dot"></i>
                                <span>{{ $prop->city }}, {{ $prop->state }}</span>
                            </div>
                            <h3 class="font-heading font-bold text-lg text-brand-navy line-clamp-1 group-hover:text-brand-cyan transition-colors mb-2">
                                <a href="{{ route('properties.show', $prop->slug) }}">{{ $prop->title }}</a>
                            </h3>
                            <p class="text-slate-500 text-xs sm:text-sm line-clamp-2 mb-4 leading-relaxed">{{ $prop->description }}</p>
                        </div>

                        <!-- Amenities Bar -->
                        <div class="pt-5 border-t border-slate-100">
                            <div class="flex items-center justify-between text-xs text-slate-600 font-medium mb-4">
                                <span class="flex items-center gap-1.5"><i class="fa-solid fa-bed text-brand-cyan"></i> {{ $prop->bedrooms }} BHK</span>
                                <span class="flex items-center gap-1.5"><i class="fa-solid fa-bath text-brand-cyan"></i> {{ $prop->bathrooms }} Baths</span>
                                <span class="flex items-center gap-1.5"><i class="fa-solid fa-ruler-combined text-brand-cyan"></i> {{ $prop->sqft ? $prop->sqft . ' sqft' : 'Spacious' }}</span>
                            </div>
                            <a href="{{ route('properties.show', $prop->slug) }}" class="w-full block text-center py-3 rounded-xl bg-slate-50 hover:bg-brand-cyan hover:text-white text-brand-navy font-bold text-xs uppercase tracking-wider transition duration-200">
                                View Details & Apply
                            </a>
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
