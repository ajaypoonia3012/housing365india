@extends('layouts.app')

@section('title', 'India Real Estate & Verified Rental Housing Directory | Housing 365 India')
@section('meta_description', 'Search 100% verified rental flats, 1BHK, 2BHK, 3BHK apartments, independent builder floors, and gated societies across India. Zero broker spam calls, legal 11-month e-stamping, and verified tenancy.')

@section('content')

<!-- ── BREADCRUMB ── -->
<div class="bg-white border-b border-slate-100 py-3.5 text-sm text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-cyan transition font-medium">Home</a>
        <span class="text-slate-300">/</span>
        <span class="text-brand-navy font-bold">India Rental Network</span>
    </div>
</div>

<!-- ── SECTION 1: HERO & COMPETITOR ADVANTAGE ── -->
<section class="relative overflow-hidden bg-gradient-to-b from-[#F7FAFD] via-white to-[#F0F6FC] py-16 sm:py-20 lg:py-24">
    <div class="pointer-events-none absolute -left-40 top-0 h-[500px] w-[500px] rounded-full opacity-40 blur-3xl" style="background: radial-gradient(circle, rgba(0,174,239,0.18) 0%, transparent 70%)" aria-hidden="true"></div>
    <div class="pointer-events-none absolute right-0 top-20 h-[600px] w-[600px] rounded-full opacity-30 blur-3xl" style="background: radial-gradient(circle, rgba(3,35,75,0.06) 0%, transparent 70%)" aria-hidden="true"></div>

    <div class="relative mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="max-w-4xl mx-auto text-center">
            <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-brand-cyan/20 bg-brand-cyan/5 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                <span class="h-2 w-2 rounded-full bg-brand-cyan animate-pulse"></span>
                PAN-INDIA RENTAL &amp; LEASING ECOSYSTEM
            </div>
            <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-extrabold text-brand-navy leading-tight tracking-tight">
                India's Smarter Way to Lease.<br>
                <span class="text-brand-cyan">Zero Spam. 100% Verified.</span>
            </h1>
            <p class="mt-6 text-base sm:text-xl text-slate-600 leading-relaxed max-w-3xl mx-auto">
                Tired of fake broker listings, endless telemarketing spam calls, and advance payment fraud on legacy classifieds? Housing365 delivers 100% verified owners, digital 11-month registered agreements, and legal peace of mind across every major Indian city.
            </p>
        </div>

        <!-- Metric Cards -->
        <div class="mt-14 grid grid-cols-2 sm:grid-cols-4 gap-4 max-w-4xl mx-auto text-center">
            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
                <span class="font-display text-3xl font-extrabold text-brand-navy">28+</span>
                <span class="text-sm font-bold text-brand-navy block mt-1">States &amp; UTs</span>
                <span class="text-xs text-slate-500">Pan-India coverage</span>
            </div>
            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
                <span class="font-display text-3xl font-extrabold text-brand-navy">100%</span>
                <span class="text-sm font-bold text-brand-navy block mt-1">Verified Owners</span>
                <span class="text-xs text-slate-500">Aadhaar &amp; Title checked</span>
            </div>
            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
                <span class="font-display text-3xl font-extrabold text-brand-cyan">₹0</span>
                <span class="text-sm font-bold text-brand-navy block mt-1">Owner Listing Fee</span>
                <span class="text-xs text-slate-500">Standard residential leasing</span>
            </div>
            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
                <span class="font-display text-3xl font-extrabold text-emerald-600">Legal</span>
                <span class="text-sm font-bold text-brand-navy block mt-1">11-Mo E-Stamping</span>
                <span class="text-xs text-slate-500">Stamp duty at actuals</span>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 2: WHY WE OUTPERFORM TRADITIONAL MODELS ── -->
<section class="py-16 sm:py-20 bg-white border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="text-center max-w-3xl mx-auto mb-14">
            <span class="text-xs font-bold uppercase tracking-widest text-brand-cyan">MARKET COMPARISON</span>
            <h2 class="font-display text-3xl sm:text-4xl text-brand-navy font-extrabold mt-2">
                How Housing365 Outperforms Traditional Portals
            </h2>
            <p class="text-slate-600 text-base mt-3">Compare our verified institutional standard with legacy classifieds, aggregator apps, and unverified groups.</p>
        </div>

        <div class="overflow-x-auto rounded-3xl border border-slate-200 shadow-sm">
            <table class="w-full text-left text-sm text-slate-700">
                <thead class="bg-slate-50 text-xs font-bold uppercase text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="p-5">Feature / Guarantee</th>
                        <th class="p-5 bg-sky-50 text-brand-navy">Housing365 Institutional Standard</th>
                        <th class="p-5">Legacy Classified Portals</th>
                        <th class="p-5">Aggregator Apps</th>
                        <th class="p-5">Unverified Social Groups</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr>
                        <td class="p-5 font-bold text-brand-navy">Listing Authenticity</td>
                        <td class="p-5 font-bold text-emerald-600 bg-sky-50/50"><i class="fa-solid fa-circle-check mr-1.5"></i> 100% Title &amp; Aadhaar Verified</td>
                        <td class="p-5 text-rose-500"><i class="fa-solid fa-xmark mr-1.5"></i> Heavy fake broker listings</td>
                        <td class="p-5 text-amber-600"><i class="fa-solid fa-triangle-exclamation mr-1.5"></i> Partially verified</td>
                        <td class="p-5 text-rose-500"><i class="fa-solid fa-xmark mr-1.5"></i> High scam &amp; fraud risk</td>
                    </tr>
                    <tr>
                        <td class="p-5 font-bold text-brand-navy">Spam Calls &amp; Privacy</td>
                        <td class="p-5 font-bold text-emerald-600 bg-sky-50/50"><i class="fa-solid fa-circle-check mr-1.5"></i> Zero Spam: Number Protected</td>
                        <td class="p-5 text-rose-500"><i class="fa-solid fa-xmark mr-1.5"></i> 20-50 agent spam calls/day</td>
                        <td class="p-5 text-amber-600"><i class="fa-solid fa-triangle-exclamation mr-1.5"></i> Aggressive telemarketing calls</td>
                        <td class="p-5 text-rose-500"><i class="fa-solid fa-xmark mr-1.5"></i> Direct public phone exposure</td>
                    </tr>
                    <tr>
                        <td class="p-5 font-bold text-brand-navy">Legal Lease &amp; E-Stamping</td>
                        <td class="p-5 font-bold text-emerald-600 bg-sky-50/50"><i class="fa-solid fa-circle-check mr-1.5"></i> Doorstep Biometric &amp; E-Stamp</td>
                        <td class="p-5 text-slate-500">DIY / Self arranged</td>
                        <td class="p-5 text-slate-700">Add-on paid service</td>
                        <td class="p-5 text-rose-500"><i class="fa-solid fa-xmark mr-1.5"></i> No legal support</td>
                    </tr>
                    <tr>
                        <td class="p-5 font-bold text-brand-navy">Police Verification</td>
                        <td class="p-5 font-bold text-emerald-600 bg-sky-50/50"><i class="fa-solid fa-circle-check mr-1.5"></i> Police Tenant Intimation Included</td>
                        <td class="p-5 text-rose-500"><i class="fa-solid fa-xmark mr-1.5"></i> None</td>
                        <td class="p-5 text-slate-500">Extra charge per state</td>
                        <td class="p-5 text-rose-500"><i class="fa-solid fa-xmark mr-1.5"></i> None</td>
                    </tr>
                    <tr>
                        <td class="p-5 font-bold text-brand-navy">Rent Default Protection</td>
                        <td class="p-5 font-bold text-emerald-600 bg-sky-50/50"><i class="fa-solid fa-circle-check mr-1.5"></i> Up to ₹5 Lakh Protection</td>
                        <td class="p-5 text-rose-500"><i class="fa-solid fa-xmark mr-1.5"></i> None</td>
                        <td class="p-5 text-rose-500"><i class="fa-solid fa-xmark mr-1.5"></i> None</td>
                        <td class="p-5 text-rose-500"><i class="fa-solid fa-xmark mr-1.5"></i> None</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- ── SECTION 3: STATES & REGIONS DIRECTORY ── -->
<section class="py-16 sm:py-20 lg:py-24 bg-[#F8FAFD] border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="mb-12">
            <span class="text-xs font-bold uppercase tracking-widest text-brand-cyan">TERRITORIES &amp; HUBS</span>
            <h2 class="font-display text-3xl sm:text-4xl text-brand-navy font-extrabold mt-2">
                Explore Rental Markets Across India
            </h2>
            <p class="text-slate-600 text-base mt-2">Select your state or metropolitan zone to view live rental benchmarks, local tenancy laws, and verified properties.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($states as $stateKey => $state)
                <div class="rounded-3xl border border-slate-200/90 bg-white p-6 shadow-sm hover:border-brand-cyan/40 hover:shadow-lg transition duration-300 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs font-bold px-3 py-1 rounded-full bg-brand-cyan/10 text-brand-cyan uppercase tracking-wider">
                                {{ $state['region'] ?? 'India' }}
                            </span>
                            <span class="text-xs font-bold text-slate-500">
                                {{ count($state['cities'] ?? []) }} {{ count($state['cities'] ?? []) === 1 ? 'City' : 'Cities' }}
                            </span>
                        </div>

                        <h3 class="font-display text-xl font-bold text-brand-navy mb-2">
                            <a href="{{ route('india.state', ['state' => $stateKey]) }}" class="hover:text-brand-cyan transition">
                                {{ $state['name'] }}
                            </a>
                        </h3>
                        <p class="text-xs text-slate-500 mb-4">
                            <span class="font-semibold text-slate-700">Hub:</span> {{ $state['capital'] ?? 'State Center' }} &middot;
                            <span class="font-semibold text-slate-700">Tenancy:</span> {{ Str::limit($state['tenancy_act'], 38) }}
                        </p>

                        <!-- Cities list preview -->
                        <div class="space-y-1.5 border-t border-slate-100 pt-3 mb-6">
                            @foreach(array_slice($state['cities'] ?? [], 0, 3) as $cKey => $city)
                                <a href="{{ route('india.city', ['state' => $stateKey, 'city' => $cKey]) }}" class="flex items-center justify-between text-xs text-slate-600 hover:text-brand-cyan py-1 group">
                                    <span class="group-hover:translate-x-1 transition-transform font-medium">
                                        <i class="fa-solid fa-location-dot text-[10px] text-brand-cyan mr-1.5"></i>
                                        {{ $city['name'] }}
                                    </span>
                                    <span class="font-bold text-brand-navy">
                                        ₹{{ number_format($city['avg_rent_2bhk'] ?? 25000) }}/mo
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <a href="{{ route('india.state', ['state' => $stateKey]) }}" class="inline-flex items-center justify-center gap-2 w-full py-3 px-4 rounded-xl border border-slate-200 text-xs font-bold text-brand-navy hover:bg-brand-cyan hover:text-white hover:border-brand-cyan transition">
                        <span>Explore {{ $state['name'] }}</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ── SECTION 4: CALL TO ACTION ── -->
<section class="py-16 sm:py-20 bg-brand-navy text-white relative overflow-hidden">
    <div class="pointer-events-none absolute -right-20 -bottom-20 size-96 rounded-full bg-brand-cyan/20 blur-3xl"></div>
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 text-center relative z-10">
        <h2 class="font-display text-3xl sm:text-5xl font-extrabold mb-4">
            List Your Property Across India with Zero Advance Retainers
        </h2>
        <p class="text-slate-300 max-w-2xl mx-auto text-base sm:text-lg mb-8">
            Join thousands of property owners in Mumbai, Bengaluru, Delhi NCR, Hyderabad, and Pune who trust Housing 365 India for verified corporate tenant placement.
        </p>
        <div class="flex flex-wrap items-center justify-center gap-4">
            <a href="{{ route('contact') }}?type=owner" class="px-8 py-4 rounded-xl bg-brand-cyan text-white font-bold hover:bg-brand-cyan-hover transition shadow-lg shadow-brand-cyan/20">
                List My Property
            </a>
            <a href="{{ route('properties.index') }}" class="px-8 py-4 rounded-xl bg-white/10 text-white border border-white/20 font-bold hover:bg-white/20 transition">
                Browse Verified Rentals
            </a>
        </div>
    </div>
</section>

@endsection
