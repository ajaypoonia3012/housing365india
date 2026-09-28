@extends('layouts.app')

@section('title', $property->title . ' | Housing 365')
@section('meta_description', Str::limit(strip_tags($property->description), 150))

@section('content')

<!-- ── BREADCRUMB ── -->
<div class="bg-white border-b border-slate-100 py-3.5 text-xs text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-cyan transition font-medium">Home</a>
        <span class="text-slate-300">/</span>
        <a href="{{ route('properties.index') }}" class="hover:text-brand-cyan transition font-medium">Properties</a>
        <span class="text-slate-300">/</span>
        <span class="text-brand-navy font-bold truncate max-w-xs">{{ $property->title }}</span>
    </div>
</div>

<!-- ── PROPERTY DETAILS ── -->
<section class="py-10 sm:py-14 lg:py-16 bg-[#F8FAFD]">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        
        <!-- Header Row -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8 bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm">
            <div>
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    @if($property->purpose === 'sale')
                        <span class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-emerald-600 text-white shadow-sm">
                            FOR SALE
                        </span>
                    @elseif($property->purpose === 'lease')
                        <span class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-indigo-600 text-white shadow-sm">
                            COMMERCIAL LEASE
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-brand-navy text-white">
                            FOR RENT
                        </span>
                    @endif

                    @if($property->category === 'plot_land')
                        <span class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-amber-500 text-white shadow-sm">
                            PLOT / LAND
                        </span>
                    @elseif($property->category === 'commercial')
                        <span class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-purple-600 text-white shadow-sm">
                            COMMERCIAL
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700">
                            {{ $property->property_type }}
                        </span>
                    @endif

                    <span class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-teal-50 text-teal-700 border border-teal-200">
                        Zero Brokerage
                    </span>

                    <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700">
                        <i class="fa-solid fa-circle-check text-[10px] mr-1"></i> Verified
                    </span>

                    <span class="text-xs text-slate-400 font-semibold">&bull; Property ID: H365-{{ $property->id + 1000 }}</span>
                </div>
                <h1 class="font-display text-2xl sm:text-3xl lg:text-4xl font-normal text-brand-navy tracking-tight">{{ $property->title }}</h1>
                <p class="text-slate-500 text-xs sm:text-sm mt-2 flex items-center gap-1.5">
                    <i class="fa-solid fa-location-dot text-brand-cyan"></i>
                    <span>{{ $property->locality ? $property->locality . ', ' : '' }}{{ $property->address }}, {{ $property->city }}, {{ $property->state }} {{ $property->postal_code }}</span>
                </p>
            </div>

            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                <div class="bg-[#F8FAFD] p-5 rounded-2xl border border-slate-200/80 text-left md:text-right md:min-w-[220px]">
                    <span class="text-xs text-slate-400 font-bold uppercase tracking-wider block">
                        {{ $property->purpose === 'sale' ? 'Total Asking Price' : ($property->purpose === 'lease' ? 'Monthly Lease Rent' : 'Monthly Rental') }}
                    </span>
                    <div class="flex items-baseline md:justify-end gap-1 my-1">
                        @if(!empty($property->price_display))
                            <span class="font-display text-3xl sm:text-4xl font-normal text-brand-navy">{{ $property->price_display }}</span>
                        @else
                            <span class="font-display text-3xl sm:text-4xl font-normal text-brand-navy">₹{{ number_format($property->price, 0) }}</span>
                            <span class="text-xs font-semibold text-slate-500">{{ $property->purpose === 'sale' ? '' : '/' . ($property->price_frequency ?? 'mo') }}</span>
                        @endif
                    </div>
                    <span class="text-[11px] text-emerald-600 font-bold flex items-center md:justify-end gap-1.5">
                        <i class="fa-solid fa-circle-check text-xs"></i> 100% Direct Owner &bull; No Brokerage
                    </span>
                </div>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $property->seller_phone ?? '916376163263') }}?text=Hi,%20I%20am%20interested%20in%20your%20listing:%20{{ urlencode($property->title) }}%20({{ urlencode(url()->current()) }})" target="_blank" class="w-full sm:w-auto px-6 py-4 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-bold uppercase tracking-wider shadow-lg shadow-emerald-600/30 flex items-center justify-center gap-2 transition hover:-translate-y-0.5">
                    <i class="fa-brands fa-whatsapp text-lg"></i>
                    Chat on WhatsApp
                </a>
            </div>
        </div>

        <!-- Main Property Hero Showcase -->
        <div class="rounded-3xl overflow-hidden shadow-2xl h-[400px] sm:h-[500px] lg:h-[560px] bg-slate-900 relative mb-12 border border-slate-200/80">
            <img src="{{ $property->image ?? '/city-placeholder-c.svg' }}" 
                 alt="{{ $property->title }}" 
                 onerror="this.src='/city-placeholder-c.svg'"
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/60 via-transparent to-transparent"></div>
            
            <div class="absolute bottom-6 left-6 right-6 flex flex-wrap justify-between items-end gap-4">
                <span class="px-4 py-2 rounded-xl bg-slate-950/70 backdrop-blur-md text-white text-xs font-bold border border-white/10 flex items-center gap-2">
                    <i class="fa-solid fa-camera text-brand-cyan"></i>
                    High-Definition Architectural Tour
                </span>
                <button type="button" onclick="openTourModal()" class="px-5 py-2.5 rounded-xl bg-brand-cyan/90 hover:bg-brand-cyan backdrop-blur-md text-white text-xs font-bold uppercase tracking-wider border border-white/20 flex items-center gap-2 shadow-lg transition">
                    <i class="fa-regular fa-calendar-check"></i> Book Showing Slot
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12">
            <!-- Left 8 Cols: Specs, Story & Amenities -->
            <div class="lg:col-span-8 space-y-10">
                <!-- Key Specs Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 bg-white p-6 sm:p-7 rounded-3xl border border-slate-200/80 shadow-sm text-center">
                    @if($property->category === 'plot_land')
                        <div class="p-4 bg-[#F8FAFD] rounded-2xl border border-slate-100">
                            <span class="block text-slate-400 text-xs font-bold uppercase tracking-wider mb-1"><i class="fa-solid fa-vector-square text-brand-cyan mr-1"></i> Plot Area</span>
                            <span class="font-display text-xl sm:text-2xl font-normal text-brand-navy">{{ $property->sqft ? $property->sqft . ' ' . ($property->area_unit ?? 'Sq.Yds') : 'Open Land' }}</span>
                        </div>
                        <div class="p-4 bg-[#F8FAFD] rounded-2xl border border-slate-100">
                            <span class="block text-slate-400 text-xs font-bold uppercase tracking-wider mb-1"><i class="fa-solid fa-stamp text-brand-cyan mr-1"></i> Ownership</span>
                            <span class="font-display text-xl sm:text-2xl font-normal text-brand-navy">Freehold Clear Title</span>
                        </div>
                        <div class="p-4 bg-[#F8FAFD] rounded-2xl border border-slate-100">
                            <span class="block text-slate-400 text-xs font-bold uppercase tracking-wider mb-1"><i class="fa-solid fa-shield-halved text-brand-cyan mr-1"></i> Approvals</span>
                            <span class="font-display text-xl sm:text-2xl font-normal text-brand-navy">RERA / Approved</span>
                        </div>
                        <div class="p-4 bg-[#F8FAFD] rounded-2xl border border-slate-100">
                            <span class="block text-slate-400 text-xs font-bold uppercase tracking-wider mb-1"><i class="fa-solid fa-key text-brand-cyan mr-1"></i> Possession</span>
                            <span class="font-display text-xl sm:text-2xl font-normal text-brand-navy">Immediate</span>
                        </div>
                    @elseif($property->category === 'commercial')
                        <div class="p-4 bg-[#F8FAFD] rounded-2xl border border-slate-100">
                            <span class="block text-slate-400 text-xs font-bold uppercase tracking-wider mb-1"><i class="fa-solid fa-building text-brand-cyan mr-1"></i> Asset Type</span>
                            <span class="font-display text-xl sm:text-2xl font-normal text-brand-navy">{{ $property->property_type ?? 'Commercial' }}</span>
                        </div>
                        <div class="p-4 bg-[#F8FAFD] rounded-2xl border border-slate-100">
                            <span class="block text-slate-400 text-xs font-bold uppercase tracking-wider mb-1"><i class="fa-solid fa-ruler-combined text-brand-cyan mr-1"></i> Super Built-up</span>
                            <span class="font-display text-xl sm:text-2xl font-normal text-brand-navy">{{ $property->sqft ? number_format($property->sqft) . ' ' . ($property->area_unit ?? 'sqft') : 'Spacious' }}</span>
                        </div>
                        <div class="p-4 bg-[#F8FAFD] rounded-2xl border border-slate-100">
                            <span class="block text-slate-400 text-xs font-bold uppercase tracking-wider mb-1"><i class="fa-solid fa-couch text-brand-cyan mr-1"></i> Fit-Out</span>
                            <span class="font-display text-xl sm:text-2xl font-normal text-brand-navy">{{ $property->furnishing ? ucfirst($property->furnishing) : 'Fitted / Ready' }}</span>
                        </div>
                        <div class="p-4 bg-[#F8FAFD] rounded-2xl border border-slate-100">
                            <span class="block text-slate-400 text-xs font-bold uppercase tracking-wider mb-1"><i class="fa-solid fa-key text-brand-cyan mr-1"></i> Occupancy</span>
                            <span class="font-display text-xl sm:text-2xl font-normal text-brand-navy">Ready to Move</span>
                        </div>
                    @else
                        <div class="p-4 bg-[#F8FAFD] rounded-2xl border border-slate-100">
                            <span class="block text-slate-400 text-xs font-bold uppercase tracking-wider mb-1"><i class="fa-solid fa-bed text-brand-cyan mr-1"></i> Configuration</span>
                            <span class="font-display text-xl sm:text-2xl font-normal text-brand-navy">{{ $property->bedrooms ?? 2 }} BHK Home</span>
                        </div>
                        <div class="p-4 bg-[#F8FAFD] rounded-2xl border border-slate-100">
                            <span class="block text-slate-400 text-xs font-bold uppercase tracking-wider mb-1"><i class="fa-solid fa-bath text-brand-cyan mr-1"></i> Bathrooms</span>
                            <span class="font-display text-xl sm:text-2xl font-normal text-brand-navy">{{ $property->bathrooms ?? 2 }} Bath{{ ($property->bathrooms ?? 2) > 1 ? 's' : '' }}</span>
                        </div>
                        <div class="p-4 bg-[#F8FAFD] rounded-2xl border border-slate-100">
                            <span class="block text-slate-400 text-xs font-bold uppercase tracking-wider mb-1"><i class="fa-solid fa-ruler-combined text-brand-cyan mr-1"></i> Area</span>
                            <span class="font-display text-xl sm:text-2xl font-normal text-brand-navy">{{ $property->sqft ? $property->sqft . ' Sq Ft' : 'Spacious' }}</span>
                        </div>
                        <div class="p-4 bg-[#F8FAFD] rounded-2xl border border-slate-100">
                            <span class="block text-slate-400 text-xs font-bold uppercase tracking-wider mb-1"><i class="fa-solid fa-couch text-brand-cyan mr-1"></i> Furnishing</span>
                            <span class="font-display text-xl sm:text-2xl font-normal text-brand-navy">{{ $property->furnishing ? ucfirst($property->furnishing) : 'Semi-Furnished' }}</span>
                        </div>
                    @endif
                </div>

                @if($property->balconies !== null || $property->floor_no || $property->posting_as || $property->maintenance)
                    <div class="bg-white px-6 py-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-wrap items-center gap-6 text-xs font-bold text-slate-700">
                        @if($property->balconies !== null)
                            <div class="flex items-center gap-2">
                                <span class="size-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center"><i class="fa-solid fa-tree"></i></span>
                                <span>{{ $property->balconies }} Balcon{{ $property->balconies == 1 ? 'y' : 'ies' }}</span>
                            </div>
                        @endif
                        @if($property->floor_no)
                            <div class="flex items-center gap-2">
                                <span class="size-7 rounded-lg bg-sky-50 text-brand-cyan flex items-center justify-center"><i class="fa-solid fa-layer-group"></i></span>
                                <span>Floor: {{ $property->floor_no }} of {{ $property->total_floors ?? '4' }}</span>
                            </div>
                        @endif
                        @if($property->posting_as)
                            <div class="flex items-center gap-2">
                                <span class="size-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center"><i class="fa-solid fa-house-user"></i></span>
                                <span>{{ $property->posting_as }}</span>
                            </div>
                        @endif
                        @if($property->maintenance)
                            <div class="flex items-center gap-2">
                                <span class="size-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center"><i class="fa-solid fa-receipt"></i></span>
                                <span>Maintenance: {{ $property->maintenance }}</span>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Description Card -->
                <div class="bg-white p-8 sm:p-10 rounded-3xl border border-slate-200/80 shadow-sm">
                    <h3 class="font-display text-2xl font-normal text-brand-navy mb-4 pb-4 border-b border-slate-100">About This Residence</h3>
                    <div class="text-slate-600 text-sm sm:text-base leading-relaxed space-y-4">
                        <p>{{ $property->description }}</p>
                    </div>
                </div>

                <!-- Amenities -->
                @if($property->amenities && is_array($property->amenities))
                <div class="bg-white p-8 sm:p-10 rounded-3xl border border-slate-200/80 shadow-sm">
                    <h3 class="font-display text-2xl font-normal text-brand-navy mb-6 pb-4 border-b border-slate-100">Features & Building Amenities</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($property->amenities as $amenity)
                            <div class="flex items-center text-sm text-slate-700 gap-3">
                                <div class="size-6 rounded-full bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-xs shrink-0">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <span class="font-medium">{{ $amenity }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Lease & Legal Assurance -->
                <div class="bg-white p-8 sm:p-10 rounded-3xl border border-slate-200/80 shadow-sm">
                    <h3 class="font-display text-2xl font-normal text-brand-navy mb-4 pb-4 border-b border-slate-100 flex items-center justify-between">
                        <span>Housing 365 Resident &amp; Buyer Assurance</span>
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full"><i class="fa-solid fa-shield-halved mr-1"></i> 100% Verified</span>
                    </h3>
                    <ul class="space-y-3.5 text-xs sm:text-sm text-slate-600">
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-check text-brand-cyan text-sm mt-0.5"></i>
                            <span><strong>Standard Model Tenancy Lease:</strong> Fully compliant with Model Tenancy Act &amp; State Rent Control guidelines with registered 11-month agreement and digital e-stamping.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-check text-brand-cyan text-sm mt-0.5"></i>
                            <span><strong>Rapid Digital Verification:</strong> Fast application approval within 24-48 business hours with verified CIBIL and employer background checks.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-check text-brand-cyan text-sm mt-0.5"></i>
                            <span><strong>Direct Owner Deal (Zero Brokerage):</strong> No hidden broker commissions or middleman markups. Connect directly with verified landlords and builders.</span>
                        </li>
                    </ul>
                </div>

                <!-- ── INTERACTIVE HOME LOAN EMI CALCULATOR (MagicBricks Style) ── -->
                <div class="bg-white p-8 sm:p-10 rounded-3xl border border-slate-200/80 shadow-md">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 pb-5 border-b border-slate-100">
                        <div>
                            <div class="inline-flex items-center gap-2 text-[11px] font-extrabold uppercase tracking-wider text-brand-cyan mb-1">
                                <i class="fa-solid fa-calculator"></i>
                                <span>FINANCIAL AFFORDABILITY TOOL</span>
                            </div>
                            <h3 class="font-display text-2xl font-bold text-brand-navy">Home Loan EMI Calculator</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Estimate your monthly mortgage payments with prevailing Indian bank rates (SBI, HDFC, ICICI).</p>
                        </div>
                        <div class="bg-emerald-50 px-3.5 py-2 rounded-2xl border border-emerald-100 text-left sm:text-right shrink-0">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 block">Bank Rates From</span>
                            <span class="text-base font-extrabold text-emerald-700">8.40% p.a.</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                        <!-- Sliders Left (7 cols) -->
                        <div class="lg:col-span-7 space-y-6">
                            <!-- Loan Amount -->
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label class="text-xs font-bold uppercase tracking-wider text-slate-700">Loan Amount (₹)</label>
                                    <span id="loanAmountDisplay" class="text-sm font-extrabold text-brand-navy bg-slate-50 px-3 py-1 rounded-xl border border-slate-200">₹{{ number_format(($property->price > 100000 ? $property->price * 0.8 : 4500000), 0) }}</span>
                                </div>
                                <input type="range" id="loanAmountSlider" 
                                       min="500000" 
                                       max="{{ max(20000000, (int)($property->price > 100000 ? $property->price : 10000000)) }}" 
                                       step="50000" 
                                       value="{{ (int)($property->price > 100000 ? $property->price * 0.8 : 4500000) }}" 
                                       class="w-full accent-brand-cyan h-2 bg-slate-100 rounded-lg cursor-pointer">
                                <div class="flex justify-between text-[10px] text-slate-400 font-semibold mt-1">
                                    <span>₹5 Lakh</span>
                                    <span>₹1 Crore</span>
                                    <span>₹2 Crore+</span>
                                </div>
                            </div>

                            <!-- Interest Rate -->
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label class="text-xs font-bold uppercase tracking-wider text-slate-700">Interest Rate (% p.a.)</label>
                                    <span id="interestRateDisplay" class="text-sm font-extrabold text-brand-cyan bg-sky-50 px-3 py-1 rounded-xl border border-sky-100">8.50%</span>
                                </div>
                                <input type="range" id="interestRateSlider" min="7.0" max="13.0" step="0.1" value="8.5" class="w-full accent-brand-cyan h-2 bg-slate-100 rounded-lg cursor-pointer">
                                <div class="flex justify-between text-[10px] text-slate-400 font-semibold mt-1">
                                    <span>7.0%</span>
                                    <span>8.5% (Typical Bank)</span>
                                    <span>13.0%</span>
                                </div>
                            </div>

                            <!-- Loan Tenure -->
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label class="text-xs font-bold uppercase tracking-wider text-slate-700">Loan Tenure</label>
                                    <span id="tenureDisplay" class="text-sm font-extrabold text-brand-navy bg-slate-50 px-3 py-1 rounded-xl border border-slate-200">20 Years</span>
                                </div>
                                <div class="grid grid-cols-5 gap-1.5 mb-2">
                                    <button type="button" onclick="setTenure(5, this)" class="tenure-btn py-1.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:border-brand-cyan transition">5Y</button>
                                    <button type="button" onclick="setTenure(10, this)" class="tenure-btn py-1.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:border-brand-cyan transition">10Y</button>
                                    <button type="button" onclick="setTenure(15, this)" class="tenure-btn py-1.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:border-brand-cyan transition">15Y</button>
                                    <button type="button" onclick="setTenure(20, this)" class="tenure-btn py-1.5 rounded-xl border-2 border-brand-cyan bg-brand-cyan/5 text-xs font-bold text-brand-navy transition">20Y</button>
                                    <button type="button" onclick="setTenure(30, this)" class="tenure-btn py-1.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:border-brand-cyan transition">30Y</button>
                                </div>
                                <input type="range" id="tenureSlider" min="5" max="30" step="1" value="20" class="w-full accent-brand-cyan h-2 bg-slate-100 rounded-lg cursor-pointer">
                            </div>
                        </div>

                        <!-- Results Card Right (5 cols) -->
                        <div class="lg:col-span-5 bg-gradient-to-br from-slate-900 to-brand-navy p-6 rounded-3xl text-white shadow-xl flex flex-col justify-between">
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-brand-cyan block mb-1">Your Monthly EMI</span>
                                <div class="font-display text-3xl sm:text-4xl font-extrabold text-white" id="monthlyEmiOutput">
                                    ₹39,088<span class="text-sm font-semibold text-slate-300">/mo</span>
                                </div>

                                <div class="mt-6 pt-5 border-t border-white/10 space-y-2.5 text-xs">
                                    <div class="flex justify-between items-center">
                                        <span class="text-slate-300 flex items-center gap-1.5">
                                            <span class="size-2.5 rounded-full bg-brand-cyan"></span> Principal Loan
                                        </span>
                                        <strong id="principalBreakdown" class="text-white font-bold">₹45,00,000</strong>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-slate-300 flex items-center gap-1.5">
                                            <span class="size-2.5 rounded-full bg-amber-400"></span> Total Interest
                                        </span>
                                        <strong id="interestBreakdown" class="text-amber-300 font-bold">₹48,81,180</strong>
                                    </div>
                                    <div class="flex justify-between items-center pt-2 border-t border-white/10">
                                        <span class="text-slate-200 font-bold">Total Payable</span>
                                        <strong id="totalPayableBreakdown" class="text-white font-bold text-sm">₹93,81,180</strong>
                                    </div>
                                </div>

                                <!-- Progress Distribution Bar -->
                                <div class="mt-4 h-2 w-full bg-white/20 rounded-full overflow-hidden flex">
                                    <div id="principalBar" class="bg-brand-cyan h-full transition-all duration-300" style="width: 48%"></div>
                                    <div id="interestBar" class="bg-amber-400 h-full transition-all duration-300" style="width: 52%"></div>
                                </div>
                            </div>

                            <a href="https://wa.me/916376163263?text=Hi,%20I%20would%20like%20to%20apply%20for%20a%20Home%20Loan%20for%20property:%20{{ urlencode($property->title) }}%20(Price:%20{{ urlencode($property->price_display ?? '₹' . number_format($property->price)) }})" 
                               target="_blank" 
                               class="mt-6 block text-center py-3 px-4 rounded-xl bg-brand-cyan hover:bg-brand-cyan-hover text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-brand-cyan/25 transition">
                                <i class="fa-solid fa-building-columns mr-1.5"></i> Check Bank Eligibility &amp; Apply
                            </a>
                        </div>
                    </div>
                </div>

                <!-- ── LOCALITY INSIGHTS & NEIGHBORHOOD BENCHMARKS (MagicBricks Style) ── -->
                <div class="bg-white p-8 sm:p-10 rounded-3xl border border-slate-200/80 shadow-sm">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 pb-5 border-b border-slate-100">
                        <div>
                            <div class="inline-flex items-center gap-2 text-[11px] font-extrabold uppercase tracking-wider text-brand-cyan mb-1">
                                <i class="fa-solid fa-map-location-dot"></i>
                                <span>MICRO-MARKET INTELLIGENCE</span>
                            </div>
                            <h3 class="font-display text-2xl font-bold text-brand-navy">{{ $property->locality ?? $property->city }} Locality Rating</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Live neighborhood livability, infrastructure connectivity, and social amenities overview.</p>
                        </div>
                        <div class="flex items-center gap-2 bg-amber-50 px-4 py-2 rounded-2xl border border-amber-200/60 shrink-0">
                            <span class="font-display text-2xl font-black text-amber-600">4.8</span>
                            <div>
                                <div class="text-[10px] text-amber-500 flex">
                                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i>
                                </div>
                                <span class="text-[10px] font-bold text-slate-600 uppercase tracking-wider">Top Rated Area</span>
                            </div>
                        </div>
                    </div>

                    <!-- 4-Pillar Scorecard Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                        <div class="p-4 rounded-2xl bg-[#F8FAFD] border border-slate-100 text-center">
                            <span class="text-xs font-bold text-slate-500 block mb-1">Connectivity</span>
                            <span class="text-xl font-extrabold text-brand-navy block">4.9 / 5</span>
                            <span class="text-[10px] text-emerald-600 font-bold">Metro &amp; Highways</span>
                        </div>
                        <div class="p-4 rounded-2xl bg-[#F8FAFD] border border-slate-100 text-center">
                            <span class="text-xs font-bold text-slate-500 block mb-1">Safety &amp; Security</span>
                            <span class="text-xl font-extrabold text-brand-navy block">4.8 / 5</span>
                            <span class="text-[10px] text-emerald-600 font-bold">Gated Societies</span>
                        </div>
                        <div class="p-4 rounded-2xl bg-[#F8FAFD] border border-slate-100 text-center">
                            <span class="text-xs font-bold text-slate-500 block mb-1">Schools &amp; Edu</span>
                            <span class="text-xl font-extrabold text-brand-navy block">4.7 / 5</span>
                            <span class="text-[10px] text-emerald-600 font-bold">Top CBSE &amp; IB</span>
                        </div>
                        <div class="p-4 rounded-2xl bg-[#F8FAFD] border border-slate-100 text-center">
                            <span class="text-xs font-bold text-slate-500 block mb-1">Healthcare</span>
                            <span class="text-xl font-extrabold text-brand-navy block">4.9 / 5</span>
                            <span class="text-[10px] text-emerald-600 font-bold">Super Specialty</span>
                        </div>
                    </div>

                    <!-- Landmark Distances -->
                    <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200/80">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-3 flex items-center gap-1.5">
                            <i class="fa-solid fa-compass text-brand-cyan"></i>
                            <span>Key Commute Distances from Property:</span>
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 text-xs text-slate-700">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-train-subway text-brand-cyan"></i>
                                <span>Nearest Metro / Station: <strong>~700 meters</strong></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-plane-departure text-brand-cyan"></i>
                                <span>Airport: <strong>18-25 mins drive</strong></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-hospital text-rose-500"></i>
                                <span>Multi-Specialty Hospital: <strong>~1.2 km</strong></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-graduation-cap text-indigo-500"></i>
                                <span>Renowned Schools: <strong>~1.5 km</strong></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-cart-shopping text-emerald-600"></i>
                                <span>Shopping Mall &amp; Supermarket: <strong>~500m</strong></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-shield-halved text-brand-cyan"></i>
                                <span>Local Police Station: <strong>~1.0 km</strong></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── STAMP DUTY & REGISTRATION ESTIMATE NOTE (Indian Market Standard) ── -->
                <div class="bg-amber-50/60 p-6 rounded-3xl border border-amber-200/70 text-xs text-slate-700 space-y-2">
                    <div class="flex items-center gap-2 font-bold text-amber-900 text-sm">
                        <i class="fa-solid fa-scale-balanced text-amber-600"></i>
                        <span>Transparent Govt. Stamp Duty &amp; Registration Guidance</span>
                    </div>
                    <p class="leading-relaxed text-slate-600">
                        In India, property purchases attract state stamp duty (typically 5%–6% depending on state and gender concessions) plus a 1% registration charge. For rental agreements, nominal e-stamp duty (~₹100–₹500) and registration apply. Housing 365 offers complete digital document execution support.
                    </p>
                </div>
            </div>

            <!-- Right 4 Cols: Seller Card & Inquiry Form -->
            <div class="lg:col-span-4">
                <div class="sticky top-28 space-y-6">
                    
                    <!-- Direct Owner / Seller Contact Card (OLX / MagicBricks style) -->
                    <div class="bg-gradient-to-br from-slate-900 to-brand-navy p-6 rounded-3xl text-white shadow-xl border border-slate-700/50">
                        <div class="flex items-center gap-3.5 mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-brand-cyan/20 border border-brand-cyan/40 text-brand-cyan flex items-center justify-center font-bold text-lg shrink-0">
                                <i class="fa-solid fa-user-shield"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-1.5">
                                    <h4 class="font-bold text-white text-base truncate">{{ $property->seller_name ?? 'Property Owner' }}</h4>
                                    <i class="fa-solid fa-circle-check text-emerald-400 text-xs shrink-0" title="Verified Owner"></i>
                                </div>
                                <span class="text-xs text-slate-300 font-medium block">Posted by {{ ucfirst($property->posted_by ?? 'Owner') }} &bull; Direct Deal</span>
                            </div>
                        </div>

                        @php
                            $isPrime = auth()->check() && auth()->user()->isPrimeActive();
                        @endphp

                        <div class="space-y-2 mb-5">
                            <div class="flex items-center justify-between text-xs bg-white/10 px-3.5 py-2 rounded-xl backdrop-blur-sm">
                                <span class="text-slate-300">Brokerage</span>
                                <span class="font-bold text-emerald-300 uppercase">₹0 (Zero Brokerage)</span>
                            </div>
                            <div class="flex items-center justify-between text-xs bg-white/10 px-3.5 py-2 rounded-xl backdrop-blur-sm">
                                <span class="text-slate-300">Direct Contact</span>
                                <span id="ownerPhoneDisplay" class="font-bold text-white tracking-wider">
                                    @if($isPrime)
                                        {{ $property->seller_phone ?? '+91 63761 63263' }}
                                    @else
                                        +91 63761 •••••
                                    @endif
                                </span>
                            </div>
                        </div>

                        <!-- Direct Contact Actions -->
                        <div id="ownerContactActionContainer">
                            @if($isPrime)
                                <div class="grid grid-cols-2 gap-2.5">
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $property->seller_phone ?? '916376163263') }}?text=Hi,%20I%20am%20a%20Housing365%20Prime%20member%20interested%20in%20your%20property%20listing:%20{{ urlencode($property->title) }}" 
                                       target="_blank" 
                                       class="flex items-center justify-center gap-2 py-3 px-3 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs uppercase tracking-wider transition shadow-md shadow-emerald-500/20">
                                        <i class="fa-brands fa-whatsapp text-sm"></i>
                                        <span>WhatsApp</span>
                                    </a>
                                    <a href="tel:{{ $property->seller_phone ?? '+916376163263' }}" 
                                       class="flex items-center justify-center gap-2 py-3 px-3 rounded-xl bg-brand-cyan hover:bg-brand-cyan-hover text-white font-bold text-xs uppercase tracking-wider transition shadow-md shadow-brand-cyan/20">
                                        <i class="fa-solid fa-phone text-xs"></i>
                                        <span>Call Now</span>
                                    </a>
                                </div>
                                <div class="mt-2.5 text-center">
                                    <span class="text-[10px] text-amber-300 font-bold flex items-center justify-center gap-1">
                                        <i class="fa-solid fa-crown text-[9px]"></i> Prime Active &bull; {{ auth()->user()->remainingContacts() }} Contact Unlocks Left
                                    </span>
                                </div>
                            @elseif(auth()->check())
                                <button type="button" onclick="unlockContact({{ $property->id }})" class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-amber-500 via-amber-400 to-yellow-500 hover:from-amber-600 hover:to-yellow-600 text-slate-950 font-black text-xs uppercase tracking-wider shadow-md transition flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-crown text-xs"></i>
                                    <span>Unlock Direct Owner Number</span>
                                </button>
                            @else
                                <a href="{{ route('prime') }}" class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-amber-500 via-amber-400 to-yellow-500 hover:from-amber-600 hover:to-yellow-600 text-slate-950 font-black text-xs uppercase tracking-wider shadow-md transition flex items-center justify-center gap-2 text-center">
                                    <i class="fa-solid fa-crown text-xs"></i>
                                    <span>Unlock Owner Number with Prime</span>
                                </a>
                                <div class="mt-2 flex items-center justify-center gap-1.5 text-[11px] text-slate-300">
                                    <span>Already Prime Member?</span>
                                    <a href="{{ route('login') }}" class="text-amber-400 font-bold underline">Sign In</a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Instant Tour & Inquiry Form -->
                    <div class="bg-white p-7 rounded-3xl border border-slate-200/80 shadow-md">
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-brand-cyan/10 text-brand-cyan mb-3">
                            <span class="size-1.5 rounded-full bg-brand-cyan"></span> Instant Showing
                        </span>
                        <h3 class="font-display text-2xl font-normal text-brand-navy mb-1">Book a Tour / Apply</h3>
                        <p class="text-xs text-slate-500 mb-6">Schedule an in-person or live virtual showing with our property specialist.</p>

                        <form action="{{ route('inquiry.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="hidden" style="display:none !important;" aria-hidden="true">
                                <label for="website_url_hp">Security Check - Leave Empty</label>
                                <input type="text" name="website_url_hp" id="website_url_hp" tabindex="-1" autocomplete="off">
                            </div>
                            <input type="hidden" name="service_type" value="Property Tour / Application">
                            <input type="hidden" name="subject" value="Inquiry for {{ $property->title }} (ID: {{ $property->id }})">

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Your Full Name *</label>
                                <input type="text" name="name" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2.5 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan transition" placeholder="Ajay Poonia">
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Email Address *</label>
                                <input type="email" name="email" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2.5 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan transition" placeholder="name@example.com">
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Phone Number</label>
                                <input type="tel" name="phone" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2.5 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan transition" placeholder="+91 63761 63263">
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Preferred Date / Message</label>
                                <textarea name="message" rows="3" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2.5 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan transition" placeholder="I would like to schedule a site visit / inquiry..."></textarea>
                            </div>

                            <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-brand-cyan hover:bg-brand-cyan-hover text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-brand-cyan/25 transition-all duration-200 hover:-translate-y-0.5">
                                Submit Inquiry & Book Tour
                            </button>

                            <p class="text-[11px] text-center text-slate-400 mt-3 flex items-center justify-center gap-1">
                                <i class="fa-solid fa-lock text-[10px]"></i> Your information is private & encrypted.
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- ── SCHEDULE TOUR MODAL ── -->
<div id="tourModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-950/75 backdrop-blur-sm transition-opacity duration-300" role="dialog" aria-modal="true">
    <div class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-8 shadow-2xl relative max-h-[92vh] overflow-y-auto border border-slate-100">
        <!-- Close Button -->
        <button type="button" onclick="closeTourModal()" class="absolute top-5 right-5 size-9 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center text-sm transition">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <!-- Initial Form State -->
        <div id="tourFormContainer">
            <div class="mb-5 pr-8">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-brand-cyan/10 text-brand-cyan mb-2">
                    <span class="size-1.5 rounded-full bg-brand-cyan animate-pulse"></span> Instant Tour Booking
                </span>
                <h3 class="font-display text-2xl font-bold text-brand-navy">Schedule a Showing</h3>
                <p class="text-xs text-slate-500 mt-0.5 truncate">{{ $property->title }} &bull; {{ $property->city }}, {{ $property->state }}</p>
            </div>

            <!-- Tour Type Selector -->
            <div class="mb-6">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Select Tour Experience</label>
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" id="tourTypeInPerson" onclick="setTourType('In-Person Tour')" class="flex items-center justify-center gap-2 p-3 rounded-2xl border-2 border-brand-cyan bg-brand-cyan/5 text-brand-navy text-xs font-bold transition">
                        <i class="fa-solid fa-person-walking text-brand-cyan"></i>
                        <span>In-Person Tour</span>
                    </button>
                    <button type="button" id="tourTypeVideo" onclick="setTourType('Live Video Walkthrough')" class="flex items-center justify-center gap-2 p-3 rounded-2xl border-2 border-slate-200 hover:border-slate-300 bg-white text-slate-600 text-xs font-bold transition">
                        <i class="fa-solid fa-video text-slate-400"></i>
                        <span>Live Video Tour</span>
                    </button>
                </div>
            </div>

            <!-- Date Picker Carousel -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-2">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-700">Choose Showing Date</label>
                    <span id="selectedDateLabel" class="text-xs font-bold text-brand-cyan"></span>
                </div>
                <div id="datesContainer" class="flex gap-2.5 overflow-x-auto pb-2 scrollbar-thin">
                    <!-- Dynamic Date Buttons rendered by JS -->
                </div>
            </div>

            <!-- Time Slots Grid -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-2">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-700">Available Time Slots</label>
                    <span id="selectedTimeLabel" class="text-xs font-bold text-brand-cyan"></span>
                </div>
                <div id="slotsGrid" class="grid grid-cols-3 gap-2.5">
                    <button type="button" onclick="selectSlot('10:00 AM', this)" class="slot-btn p-2.5 rounded-xl border border-slate-200 hover:border-brand-cyan text-center text-xs font-bold text-slate-700 hover:text-brand-navy transition">10:00 AM</button>
                    <button type="button" onclick="selectSlot('11:30 AM', this)" class="slot-btn p-2.5 rounded-xl border border-slate-200 hover:border-brand-cyan text-center text-xs font-bold text-slate-700 hover:text-brand-navy transition">11:30 AM</button>
                    <button type="button" onclick="selectSlot('1:00 PM', this)" class="slot-btn p-2.5 rounded-xl border border-slate-200 hover:border-brand-cyan text-center text-xs font-bold text-slate-700 hover:text-brand-navy transition">1:00 PM</button>
                    <button type="button" onclick="selectSlot('2:30 PM', this)" class="slot-btn p-2.5 rounded-xl border border-slate-200 hover:border-brand-cyan text-center text-xs font-bold text-slate-700 hover:text-brand-navy transition">2:30 PM</button>
                    <button type="button" onclick="selectSlot('4:00 PM', this)" class="slot-btn p-2.5 rounded-xl border border-slate-200 hover:border-brand-cyan text-center text-xs font-bold text-slate-700 hover:text-brand-navy transition">4:00 PM</button>
                    <button type="button" onclick="selectSlot('5:30 PM', this)" class="slot-btn p-2.5 rounded-xl border border-slate-200 hover:border-brand-cyan text-center text-xs font-bold text-slate-700 hover:text-brand-navy transition">5:30 PM</button>
                </div>
            </div>

            <!-- Contact Information Form -->
            <form id="tourBookingForm" onsubmit="handleTourSubmit(event)" class="space-y-4">
                <input type="hidden" id="tourTypeInput" name="tour_type" value="In-Person Tour">
                <input type="hidden" id="selectedDateInput" name="showing_date" value="">
                <input type="hidden" id="selectedTimeInput" name="showing_time" value="10:00 AM">
                <input type="hidden" name="property_id" value="{{ $property->id }}">
                <input type="hidden" name="property_title" value="{{ $property->title }}">

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">First Name *</label>
                        <input type="text" id="tourFirstName" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-brand-cyan outline-none" placeholder="Alex">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">Last Name *</label>
                        <input type="text" id="tourLastName" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-brand-cyan outline-none" placeholder="Smith">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">Email *</label>
                        <input type="email" id="tourEmail" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-brand-cyan outline-none" placeholder="alex@example.com">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">Phone Number *</label>
                        <input type="tel" id="tourPhone" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-brand-cyan outline-none" placeholder="+91 98765 43210">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">Target Move-In Date</label>
                    <input type="date" id="tourMoveIn" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3.5 py-2 text-xs focus:ring-2 focus:ring-brand-cyan outline-none">
                </div>

                <div id="tourSubmitError" class="hidden p-3 rounded-xl bg-red-50 text-red-700 text-xs font-semibold"></div>

                <button type="submit" id="tourSubmitBtn" class="w-full py-3.5 px-4 rounded-xl bg-brand-cyan hover:bg-brand-cyan-hover text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-brand-cyan/25 flex items-center justify-center gap-2 transition hover:-translate-y-0.5">
                    <span id="tourSubmitText">Confirm & Schedule Tour</span>
                    <i id="tourSpinner" class="fa-solid fa-spinner fa-spin hidden"></i>
                </button>
            </form>
        </div>

        <!-- Success Confirmation State -->
        <div id="tourSuccessContainer" class="hidden text-center py-8">
            <div class="size-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-3xl mx-auto mb-4 animate-bounce">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <h3 class="font-display text-2xl font-bold text-brand-navy mb-2">Tour Request Confirmed!</h3>
            <p class="text-xs sm:text-sm text-slate-600 max-w-sm mx-auto mb-6">
                Your tour for <strong class="text-brand-navy">{{ $property->title }}</strong> has been scheduled. A showing specialist has received your request and will send calendar confirmation shortly.
            </p>
            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 max-w-xs mx-auto text-left text-xs space-y-1.5 mb-6">
                <div><span class="text-slate-400 font-semibold">Date:</span> <strong id="confirmDate" class="text-slate-800"></strong></div>
                <div><span class="text-slate-400 font-semibold">Time:</span> <strong id="confirmTime" class="text-slate-800"></strong></div>
                <div><span class="text-slate-400 font-semibold">Type:</span> <strong id="confirmType" class="text-slate-800"></strong></div>
            </div>
            <button type="button" onclick="closeTourModal()" class="px-6 py-2.5 rounded-xl bg-brand-navy hover:bg-brand-navy-hover text-white text-xs font-bold uppercase tracking-wider transition">
                Done
            </button>
        </div>
    </div>
</div>

<script>
let currentTourType = 'In-Person Tour';
let currentSelectedDate = '';
let currentSelectedTime = '10:00 AM';

function generateTourDates() {
    const container = document.getElementById('datesContainer');
    if (!container) return;
    container.innerHTML = '';

    const days = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];
    const months = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];

    let dateObj = new Date();
    let count = 0;

    while (count < 7) {
        dateObj.setDate(dateObj.getDate() + 1);
        const dayIdx = dateObj.getDay();
        if (dayIdx !== 0 && dayIdx !== 6) { // skip weekends
            const yyyy = dateObj.getFullYear();
            const mm = String(dateObj.getMonth() + 1).padStart(2, '0');
            const dd = String(dateObj.getDate()).padStart(2, '0');
            const dateStr = `${yyyy}-${mm}-${dd}`;
            const dayName = days[dayIdx];
            const monthName = months[dateObj.getMonth()];
            const dayNum = dateObj.getDate();

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = `date-btn shrink-0 flex flex-col items-center justify-center p-3 rounded-2xl border min-w-[70px] text-center transition ${count === 0 ? 'border-brand-cyan bg-brand-cyan text-white shadow-md' : 'border-slate-200 hover:border-brand-cyan bg-slate-50 text-slate-700'}`;
            btn.innerHTML = `
                <span class="text-[10px] font-bold uppercase tracking-wider ${count === 0 ? 'text-white/80' : 'text-slate-400'}">${dayName}</span>
                <span class="font-display text-lg font-bold my-0.5">${dayNum}</span>
                <span class="text-[9px] uppercase font-bold ${count === 0 ? 'text-white/90' : 'text-slate-500'}">${monthName}</span>
            `;

            btn.onclick = () => selectDate(dateStr, `${dayName}, ${monthName} ${dayNum}`, btn);
            container.appendChild(btn);

            if (count === 0) {
                currentSelectedDate = dateStr;
                document.getElementById('selectedDateInput').value = dateStr;
                document.getElementById('selectedDateLabel').innerText = `${dayName}, ${monthName} ${dayNum}`;
            }

            count++;
        }
    }
}

function selectDate(dateStr, label, element) {
    currentSelectedDate = dateStr;
    document.getElementById('selectedDateInput').value = dateStr;
    document.getElementById('selectedDateLabel').innerText = label;

    document.querySelectorAll('.date-btn').forEach(b => {
        b.className = 'date-btn shrink-0 flex flex-col items-center justify-center p-3 rounded-2xl border min-w-[70px] text-center transition border-slate-200 hover:border-brand-cyan bg-slate-50 text-slate-700';
    });
    element.className = 'date-btn shrink-0 flex flex-col items-center justify-center p-3 rounded-2xl border min-w-[70px] text-center transition border-brand-cyan bg-brand-cyan text-white shadow-md';
}

function selectSlot(timeStr, element) {
    currentSelectedTime = timeStr;
    document.getElementById('selectedTimeInput').value = timeStr;
    document.getElementById('selectedTimeLabel').innerText = timeStr;

    document.querySelectorAll('.slot-btn').forEach(b => {
        b.className = 'slot-btn p-2.5 rounded-xl border border-slate-200 hover:border-brand-cyan text-center text-xs font-bold text-slate-700 hover:text-brand-navy transition';
    });
    element.className = 'slot-btn p-2.5 rounded-xl border-2 border-brand-cyan bg-brand-cyan/10 text-center text-xs font-bold text-brand-navy transition';
}

function setTourType(type) {
    currentTourType = type;
    document.getElementById('tourTypeInput').value = type;
    const inPersonBtn = document.getElementById('tourTypeInPerson');
    const videoBtn = document.getElementById('tourTypeVideo');

    if (type === 'In-Person Tour') {
        inPersonBtn.className = 'flex items-center justify-center gap-2 p-3 rounded-2xl border-2 border-brand-cyan bg-brand-cyan/5 text-brand-navy text-xs font-bold transition';
        videoBtn.className = 'flex items-center justify-center gap-2 p-3 rounded-2xl border-2 border-slate-200 hover:border-slate-300 bg-white text-slate-600 text-xs font-bold transition';
    } else {
        videoBtn.className = 'flex items-center justify-center gap-2 p-3 rounded-2xl border-2 border-brand-cyan bg-brand-cyan/5 text-brand-navy text-xs font-bold transition';
        inPersonBtn.className = 'flex items-center justify-center gap-2 p-3 rounded-2xl border-2 border-slate-200 hover:border-slate-300 bg-white text-slate-600 text-xs font-bold transition';
    }
}

function openTourModal() {
    const modal = document.getElementById('tourModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
    generateTourDates();
}

function closeTourModal() {
    const modal = document.getElementById('tourModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
}

async function handleTourSubmit(e) {
    e.preventDefault();
    const errorEl = document.getElementById('tourSubmitError');
    const submitBtn = document.getElementById('tourSubmitBtn');
    const spinnerText = document.getElementById('tourSubmitText');
    const spinner = document.getElementById('tourSpinner');

    errorEl.classList.add('hidden');
    submitBtn.disabled = true;
    spinnerText.innerText = 'Scheduling Tour...';
    spinner.classList.remove('hidden');

    const firstName = document.getElementById('tourFirstName').value;
    const lastName = document.getElementById('tourLastName').value;
    const email = document.getElementById('tourEmail').value;
    const phone = document.getElementById('tourPhone').value;
    const moveIn = document.getElementById('tourMoveIn').value;

    const payload = {
        name: `${firstName} ${lastName}`,
        email: email,
        phone: phone,
        service_type: 'Property Tour',
        subject: `Tour Request: ${currentTourType} for {{ $property->title }}`,
        message: `Preferred Tour Date: ${currentSelectedDate}\nTime Slot: ${currentSelectedTime}\nTour Type: ${currentTourType}\nTarget Move-in: ${moveIn || 'Immediate'}\nProperty: {{ $property->title }} (ID: {{ $property->id }})`,
        _token: '{{ csrf_token() }}'
    };

    try {
        const res = await fetch('{{ route("inquiry.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(payload)
        });

        if (res.ok) {
            document.getElementById('tourFormContainer').classList.add('hidden');
            document.getElementById('confirmDate').innerText = currentSelectedDate;
            document.getElementById('confirmTime').innerText = currentSelectedTime;
            document.getElementById('confirmType').innerText = currentTourType;
            document.getElementById('tourSuccessContainer').classList.remove('hidden');
        } else {
            const data = await res.json().catch(() => ({}));
            throw new Error(data.message || 'Unable to schedule tour. Please try again or contact us directly.');
        }
    } catch (err) {
        errorEl.innerText = err.message || 'An error occurred while booking. Please try again.';
        errorEl.classList.remove('hidden');
    } finally {
        submitBtn.disabled = false;
        spinnerText.innerText = 'Confirm & Schedule Tour';
        spinner.classList.add('hidden');
    }
}

// ── EMI CALCULATOR LOGIC (MagicBricks Style) ──
const loanSlider = document.getElementById('loanAmountSlider');
const loanDisplay = document.getElementById('loanAmountDisplay');
const rateSlider = document.getElementById('interestRateSlider');
const rateDisplay = document.getElementById('interestRateDisplay');
const tenureSlider = document.getElementById('tenureSlider');
const tenureDisplay = document.getElementById('tenureDisplay');

const emiOutput = document.getElementById('monthlyEmiOutput');
const principalBreakdown = document.getElementById('principalBreakdown');
const interestBreakdown = document.getElementById('interestBreakdown');
const totalPayableBreakdown = document.getElementById('totalPayableBreakdown');
const principalBar = document.getElementById('principalBar');
const interestBar = document.getElementById('interestBar');

function formatInr(val) {
    return '₹' + Math.round(val).toLocaleString('en-IN');
}

function calculateEMI() {
    if (!loanSlider || !rateSlider || !tenureSlider) return;
    const P = parseFloat(loanSlider.value);
    const r = (parseFloat(rateSlider.value) / 12) / 100;
    const n = parseInt(tenureSlider.value) * 12;

    loanDisplay.innerText = formatInr(P);
    rateDisplay.innerText = parseFloat(rateSlider.value).toFixed(2) + '%';
    tenureDisplay.innerText = tenureSlider.value + ' Years';

    const emi = (P * r * Math.pow(1 + r, n)) / (Math.pow(1 + r, n) - 1);
    const totalPayable = emi * n;
    const totalInterest = totalPayable - P;

    if (emiOutput) emiOutput.innerHTML = formatInr(emi) + '<span class="text-sm font-semibold text-slate-300">/mo</span>';
    if (principalBreakdown) principalBreakdown.innerText = formatInr(P);
    if (interestBreakdown) interestBreakdown.innerText = formatInr(totalInterest);
    if (totalPayableBreakdown) totalPayableBreakdown.innerText = formatInr(totalPayable);

    const principalPct = Math.round((P / totalPayable) * 100);
    const interestPct = 100 - principalPct;

    if (principalBar) principalBar.style.width = principalPct + '%';
    if (interestBar) interestBar.style.width = interestPct + '%';
}

function setTenure(years, btn) {
    if (!tenureSlider) return;
    tenureSlider.value = years;
    document.querySelectorAll('.tenure-btn').forEach(b => {
        b.className = 'tenure-btn py-1.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:border-brand-cyan transition';
    });
    btn.className = 'tenure-btn py-1.5 rounded-xl border-2 border-brand-cyan bg-brand-cyan/5 text-xs font-bold text-brand-navy transition';
    calculateEMI();
}

if (loanSlider) {
    loanSlider.addEventListener('input', calculateEMI);
    rateSlider.addEventListener('input', calculateEMI);
    tenureSlider.addEventListener('input', calculateEMI);
    calculateEMI();
}

async function unlockContact(propertyId) {
    try {
        const response = await fetch(`/properties/${propertyId}/unlock-contact`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        const data = await response.json();
        if (data.require_login) {
            window.location.href = "{{ route('login') }}";
            return;
        }
        if (data.require_prime) {
            window.location.href = "{{ route('prime') }}";
            return;
        }
        if (data.success) {
            const phoneEl = document.getElementById('ownerPhoneDisplay');
            if (phoneEl) phoneEl.innerText = data.owner_phone;
            const container = document.getElementById('ownerContactActionContainer');
            if (container) {
                container.innerHTML = `
                    <div class="grid grid-cols-2 gap-2.5">
                        <a href="https://wa.me/${data.owner_phone.replace(/[^0-9]/g, '')}?text=Hi,%20I%20am%20a%20Housing365%20Prime%20member%20interested%20in%20your%20property" 
                           target="_blank" 
                           class="flex items-center justify-center gap-2 py-3 px-3 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs uppercase tracking-wider transition shadow-md shadow-emerald-500/20">
                            <i class="fa-brands fa-whatsapp text-sm"></i>
                            <span>WhatsApp</span>
                        </a>
                        <a href="tel:${data.owner_phone}" 
                           class="flex items-center justify-center gap-2 py-3 px-3 rounded-xl bg-brand-cyan hover:bg-brand-cyan-hover text-white font-bold text-xs uppercase tracking-wider transition shadow-md shadow-brand-cyan/20">
                            <i class="fa-solid fa-phone text-xs"></i>
                            <span>Call Now</span>
                        </a>
                    </div>
                    <div class="mt-2.5 text-center">
                        <span class="text-[10px] text-amber-300 font-bold flex items-center justify-center gap-1">
                            <i class="fa-solid fa-crown text-[9px]"></i> Prime Active &bull; ${data.remaining_contacts} Contact Unlocks Left
                        </span>
                    </div>
                `;
            }
        }
    } catch (err) {
        console.error('Failed to unlock contact:', err);
    }
}
</script>

<!-- ── STICKY MOBILE BOTTOM ACTION BAR (MagicBricks Style) ── -->
<div class="sm:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200 px-4 py-3 flex items-center justify-between shadow-2xl">
    <div>
        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Price</span>
        <span class="font-display text-base font-bold text-brand-navy">
            {{ !empty($property->price_display) ? $property->price_display : '₹' . number_format($property->price) . ($property->purpose === 'sale' ? '' : '/mo') }}
        </span>
    </div>
    <div class="flex items-center gap-2">
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $property->seller_phone ?? '916376163263') }}?text=Hi,%20I%20am%20interested%20in:%20{{ urlencode($property->title) }}" 
           target="_blank" 
           class="px-4 py-2.5 rounded-xl bg-emerald-600 text-white font-bold text-xs uppercase tracking-wider flex items-center gap-1.5 shadow-md">
            <i class="fa-brands fa-whatsapp text-sm"></i>
            <span>WhatsApp</span>
        </a>
        <button type="button" onclick="openTourModal()" class="px-4 py-2.5 rounded-xl bg-brand-cyan text-white font-bold text-xs uppercase tracking-wider shadow-md">
            Book Visit
        </button>
    </div>
</div>

@endsection
