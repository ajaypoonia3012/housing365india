@extends('layouts.app')

@section('title', "Verified Flats, 1BHK, 2BHK, 3BHK for Rent in " . ($cityData['name'] ?? 'City') . ", " . ($stateData['name'] ?? 'India') . " | Housing 365 India")
@section('meta_description', $cityData['meta_desc'] ?? ("Find 100% verified flats and apartments for rent in " . ($cityData['name'] ?? 'City') . ". Zero broker spam calls, 11-month registered lease, and online police verification."))

@section('content')

<!-- ── BREADCRUMB ── -->
<div class="bg-white border-b border-slate-100 py-3.5 text-sm text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-cyan transition font-medium">Home</a>
        <span class="text-slate-300">/</span>
        <a href="{{ route('india.index') }}" class="hover:text-brand-cyan transition font-medium">India Network</a>
        <span class="text-slate-300">/</span>
        <a href="{{ route('india.state', ['state' => $stateSlug]) }}" class="hover:text-brand-cyan transition font-medium">{{ $stateData['name'] }}</a>
        <span class="text-slate-300">/</span>
        <span class="text-brand-navy font-bold">{{ $cityData['name'] }}</span>
    </div>
</div>

<!-- ── HERO SECTION ── -->
<section class="relative overflow-hidden bg-gradient-to-b from-[#F7FAFD] via-white to-[#F0F6FC] py-16 sm:py-20 lg:py-24">
    <div class="pointer-events-none absolute -left-40 top-0 h-[500px] w-[500px] rounded-full opacity-40 blur-3xl" style="background: radial-gradient(circle, rgba(0,174,239,0.18) 0%, transparent 70%)" aria-hidden="true"></div>

    <div class="relative mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 items-center gap-12">
            <div class="lg:col-span-7">
                <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-brand-cyan/20 bg-brand-cyan/5 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                    <span class="h-2 w-2 rounded-full bg-brand-cyan"></span>
                    {{ strtoupper($cityData['name']) }} VERIFIED RENTAL ECOSYSTEM
                </div>
                <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-extrabold text-brand-navy leading-tight tracking-tight">
                    Verified Flats &amp; Homes for Rent in <span class="text-brand-cyan">{{ $cityData['name'] }}</span>
                </h1>
                <p class="mt-5 text-base sm:text-lg text-slate-600 leading-relaxed max-w-2xl">
                    {{ $cityData['meta_desc'] }}
                </p>

                <!-- Value Proposition Grid -->
                <div class="mt-8 grid grid-cols-2 sm:grid-cols-3 gap-4 text-xs font-semibold text-slate-700">
                    <div class="flex items-center gap-2 p-3 rounded-xl bg-white border border-slate-200 shadow-sm">
                        <i class="fa-solid fa-ban text-rose-500 text-sm"></i>
                        <span>Zero Fake Broker Calls</span>
                    </div>
                    <div class="flex items-center gap-2 p-3 rounded-xl bg-white border border-slate-200 shadow-sm">
                        <i class="fa-solid fa-stamp text-brand-cyan text-sm"></i>
                        <span>11-Month E-Stamp Deed</span>
                    </div>
                    <div class="flex items-center gap-2 p-3 rounded-xl bg-white border border-slate-200 shadow-sm">
                        <i class="fa-solid fa-shield-halved text-emerald-600 text-sm"></i>
                        <span>Police Intimation Filed</span>
                    </div>
                </div>
            </div>

            <!-- Inquiry / Rent Estimate Card -->
            <div class="lg:col-span-5">
                <div class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200/80 shadow-xl relative overflow-hidden">
                    <div class="absolute -right-10 -bottom-10 size-48 rounded-full bg-brand-cyan/10 blur-2xl"></div>
                    <h3 class="font-display text-2xl text-brand-navy font-extrabold mb-1">Get {{ $cityData['name'] }} Rental Estimate</h3>
                    <p class="text-xs text-slate-500 mb-6">Discover the optimal monthly rent, deposit benchmark, and tenant demand in your locality.</p>

                    <form action="{{ route('inquiry.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="hidden" style="display:none !important;" aria-hidden="true">
                            <label for="website_url_hp">Security Check - Leave Empty</label>
                            <input type="text" name="website_url_hp" id="website_url_hp" tabindex="-1" autocomplete="off">
                        </div>
                        <input type="hidden" name="service_type" value="Rental Market Analysis">
                        <input type="hidden" name="subject" value="Rental Analysis Request for {{ $cityData['name'] }}, {{ $stateData['name'] }}">

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Your Name *</label>
                            <input type="text" name="name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan" placeholder="Rahul Sharma">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Phone Number *</label>
                            <input type="tel" name="phone" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan" placeholder="+91 98765 43210">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Email Address</label>
                            <input type="email" name="email" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan" placeholder="rahul@example.com">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Locality / Society Name *</label>
                            <input type="text" name="message" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan" placeholder="e.g. {{ $cityData['localities'][0] ?? 'Prime Locality' }}">
                        </div>

                        <button type="submit" class="w-full py-4 rounded-xl bg-brand-cyan hover:bg-brand-cyan-hover text-white font-bold text-sm shadow-lg shadow-brand-cyan/25 transition">
                            Get Free Rental Valuation Report
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 2: LIVE LOCALITY BENCHMARKS & BHK RATES ── -->
<section class="py-16 sm:py-20 bg-white border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12 pb-6 border-b border-slate-200">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-brand-cyan">MARKET INTELLIGENCE</span>
                <h2 class="font-display text-3xl sm:text-4xl text-brand-navy font-extrabold mt-2">
                    {{ $cityData['name'] }} Rental Benchmarks by Configuration
                </h2>
                <p class="text-slate-600 text-base mt-2">Average monthly rentals and security deposit standards across gated communities and standalone builder floors.</p>
            </div>
            <div class="shrink-0">
                <span class="inline-flex items-center gap-2 text-xs font-bold px-3.5 py-1.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">
                    <span class="size-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Updated for 2026 Season
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- 1 BHK -->
            <div class="p-6 rounded-3xl bg-[#F8FAFD] border border-slate-200/90 shadow-sm">
                <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Entry Compact</div>
                <h3 class="font-display text-2xl font-bold text-brand-navy mb-3">1 BHK / 1 RK</h3>
                <div class="text-3xl font-extrabold text-brand-cyan mb-2">₹{{ number_format($cityData['avg_rent_1bhk']) }}<span class="text-sm text-slate-500 font-normal">/mo</span></div>
                <p class="text-xs text-slate-600 leading-relaxed mb-4">Ideal for working professionals and single tech tenants. 450 – 650 sq.ft.</p>
                <div class="text-xs text-slate-500 border-t border-slate-200 pt-3">
                    <span class="font-bold text-slate-700">Security Deposit:</span> {{ $cityData['deposit_months'] }}
                </div>
            </div>

            <!-- 2 BHK -->
            <div class="p-6 rounded-3xl bg-white border-2 border-brand-cyan/40 shadow-lg relative">
                <div class="absolute -top-3 right-5 text-[10px] font-bold uppercase px-3 py-1 rounded-full bg-brand-cyan text-white shadow-sm">
                    Most Popular
                </div>
                <div class="text-xs font-bold uppercase tracking-wider text-brand-cyan mb-1">Standard Family</div>
                <h3 class="font-display text-2xl font-bold text-brand-navy mb-3">2 BHK Apartment</h3>
                <div class="text-3xl font-extrabold text-brand-navy mb-2">₹{{ number_format($cityData['avg_rent_2bhk']) }}<span class="text-sm text-slate-500 font-normal">/mo</span></div>
                <p class="text-xs text-slate-600 leading-relaxed mb-4">The core leasing volume in gated societies with car parking. 950 – 1,250 sq.ft.</p>
                <div class="text-xs text-slate-500 border-t border-slate-200 pt-3">
                    <span class="font-bold text-slate-700">Security Deposit:</span> {{ $cityData['deposit_months'] }}
                </div>
            </div>

            <!-- 3 BHK -->
            <div class="p-6 rounded-3xl bg-[#F8FAFD] border border-slate-200/90 shadow-sm">
                <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Premium Living</div>
                <h3 class="font-display text-2xl font-bold text-brand-navy mb-3">3 BHK Apartment</h3>
                <div class="text-3xl font-extrabold text-brand-navy mb-2">₹{{ number_format($cityData['avg_rent_3bhk']) }}<span class="text-sm text-slate-500 font-normal">/mo</span></div>
                <p class="text-xs text-slate-600 leading-relaxed mb-4">Expansive layouts for corporate families and luxury communities. 1,450 – 2,100 sq.ft.</p>
                <div class="text-xs text-slate-500 border-t border-slate-200 pt-3">
                    <span class="font-bold text-slate-700">Security Deposit:</span> {{ $cityData['deposit_months'] }}
                </div>
            </div>

            <!-- 4 BHK & Villas -->
            <div class="p-6 rounded-3xl bg-[#F8FAFD] border border-slate-200/90 shadow-sm">
                <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Luxury &amp; Penthouse</div>
                <h3 class="font-display text-2xl font-bold text-brand-navy mb-3">4 BHK &amp; Villas</h3>
                <div class="text-3xl font-extrabold text-brand-navy mb-2">₹{{ number_format($cityData['avg_rent_3bhk'] * 1.6) }}<span class="text-sm text-slate-500 font-normal">+</span></div>
                <p class="text-xs text-slate-600 leading-relaxed mb-4">Exclusive gated villas, duplexes, and private penthouses. 2,500 – 4,500+ sq.ft.</p>
                <div class="text-xs text-slate-500 border-t border-slate-200 pt-3">
                    <span class="font-bold text-slate-700">Security Deposit:</span> {{ $cityData['deposit_months'] }}
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 3: TOP LOCALITIES CLUSTER ── -->
<section class="py-16 sm:py-20 bg-[#F8FAFD] border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="mb-10">
            <span class="text-xs font-bold uppercase tracking-widest text-brand-cyan">NEIGHBORHOOD GUIDE</span>
            <h2 class="font-display text-3xl sm:text-4xl text-brand-navy font-extrabold mt-2">
                Prime Residential Neighborhoods in {{ $cityData['name'] }}
            </h2>
            <p class="text-slate-600 text-base mt-2">High-demand residential enclaves with verified housing society clusters.</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($cityData['localities'] as $locality)
                <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-sm hover:border-brand-cyan/40 hover:shadow-md transition">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fa-solid fa-location-dot text-brand-cyan text-sm"></i>
                        <h4 class="font-bold text-brand-navy text-sm">{{ $locality }}</h4>
                    </div>
                    <p class="text-xs text-slate-500">Gated societies &middot; Metro access &middot; Verified</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ── SECTION 4: HOW WE OUTSMART COMPETITORS IN THIS CITY ── -->
<section class="py-16 sm:py-20 bg-white border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="text-center max-w-3xl mx-auto mb-14">
            <span class="text-xs font-bold uppercase tracking-widest text-brand-cyan">UNBEATABLE ADVANTAGE</span>
            <h2 class="font-display text-3xl sm:text-4xl text-brand-navy font-extrabold mt-2">
                Why {{ $cityData['name'] }} Tenants &amp; Owners Choose Housing 365
            </h2>
            <p class="text-slate-600 text-base mt-3">We have eliminated the frustrations that have plagued Indian real estate portals for 15 years.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="p-8 rounded-3xl bg-[#F8FAFD] border border-slate-200/80">
                <div class="size-12 rounded-xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-xl mb-5">
                    <i class="fa-solid fa-phone-slash"></i>
                </div>
                <h3 class="font-display text-xl font-bold text-brand-navy mb-2">Zero Spam Calls Guarantee</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Unlike legacy classified portals where listing your number leads to dozens of aggressive broker calls every day, Housing365 protects your privacy with direct verified messaging.
                </p>
            </div>
            <div class="p-8 rounded-3xl bg-[#F8FAFD] border border-slate-200/80">
                <div class="size-12 rounded-xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-xl mb-5">
                    <i class="fa-solid fa-fingerprint"></i>
                </div>
                <h3 class="font-display text-xl font-bold text-brand-navy mb-2">Doorstep E-Stamp &amp; Police Verification</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    We manage the complete legal stack: state-compliant 11-month Leave &amp; License agreements, biometric Aadhaar e-stamping, and {{ $stateData['police_verification'] }} submission for society approvals.
                </p>
            </div>
            <div class="p-8 rounded-3xl bg-[#F8FAFD] border border-slate-200/80">
                <div class="size-12 rounded-xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-xl mb-5">
                    <i class="fa-solid fa-file-signature"></i>
                </div>
                <h3 class="font-display text-xl font-bold text-brand-navy mb-2">Legal Lease &amp; Stamp Duty</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    We coordinate 11-month registered leave &amp; license agreements, digital e-stamping, and society NOC filings. (Government stamp duty, notary, and registration fees are charged at actuals).
                </p>
            </div>
        </div>
    </div>
</section>

@endsection
