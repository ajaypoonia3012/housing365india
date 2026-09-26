@extends('layouts.app')

@section('title', "Verified Rental Properties & Leasing in " . ($stateData['name'] ?? 'India') . " | Housing 365 India")
@section('meta_description', "Explore verified rental apartments, flats, and villas in " . ($stateData['name'] ?? 'India') . ". Compliant with " . ($stateData['tenancy_act'] ?? 'Tenancy Rules') . ". Zero broker spam, online police verification, and legal lease registration.")

@section('content')

<!-- ── BREADCRUMB ── -->
<div class="bg-white border-b border-slate-100 py-3.5 text-sm text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-cyan transition font-medium">Home</a>
        <span class="text-slate-300">/</span>
        <a href="{{ route('india.index') }}" class="hover:text-brand-cyan transition font-medium">India Network</a>
        <span class="text-slate-300">/</span>
        <span class="text-brand-navy font-bold">{{ $stateData['name'] }}</span>
    </div>
</div>

<!-- ── HERO ── -->
<section class="relative overflow-hidden bg-gradient-to-b from-[#F7FAFD] via-white to-[#F0F6FC] py-16 sm:py-20">
    <div class="relative mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="max-w-4xl">
            <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-brand-cyan/20 bg-brand-cyan/5 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                <span class="h-2 w-2 rounded-full bg-brand-cyan"></span>
                STATE TENANCY JURISDICTION &middot; {{ strtoupper($stateData['name']) }}
            </div>
            <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-extrabold text-brand-navy leading-tight">
                Verified Rental Housing &amp; Leasing in <span class="text-brand-cyan">{{ $stateData['name'] }}</span>
            </h1>
            <p class="mt-5 text-base sm:text-lg text-slate-600 leading-relaxed max-w-3xl">
                Explore transparent tenant placement and property leasing across {{ $stateData['name'] }}. Every property is 100% verified, legal agreements adhere to {{ $stateData['tenancy_act'] }}, and both landlords and tenants enjoy direct digital coordination without broker spam.
            </p>

            <!-- State Regulatory Badges -->
            <div class="mt-8 flex flex-wrap gap-4 text-xs font-semibold text-slate-700">
                <div class="flex items-center gap-2 p-3 rounded-xl bg-white border border-slate-200 shadow-sm">
                    <i class="fa-solid fa-scale-balanced text-brand-cyan text-sm"></i>
                    <span>{{ $stateData['tenancy_act'] }}</span>
                </div>
                <div class="flex items-center gap-2 p-3 rounded-xl bg-white border border-slate-200 shadow-sm">
                    <i class="fa-solid fa-stamp text-brand-cyan text-sm"></i>
                    <span>Stamp Duty: {{ $stateData['stamp_duty_note'] ?? 'Standard e-Stamp' }}</span>
                </div>
                <div class="flex items-center gap-2 p-3 rounded-xl bg-white border border-slate-200 shadow-sm">
                    <i class="fa-solid fa-shield-halved text-brand-cyan text-sm"></i>
                    <span>{{ $stateData['police_verification'] }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── CITIES DIRECTORY GRID ── -->
<section class="py-16 sm:py-20 bg-white border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="mb-12">
            <span class="text-xs font-bold uppercase tracking-widest text-brand-cyan">METROPOLITAN HUBS</span>
            <h2 class="font-display text-3xl sm:text-4xl text-brand-navy font-extrabold mt-2">
                Active Rental Markets in {{ $stateData['name'] }}
            </h2>
            <p class="text-slate-600 text-base mt-2">Select a metropolitan cluster to view neighborhood breakdowns, BHK pricing benchmarks, and available homes.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($stateData['cities'] as $cityKey => $city)
                <div class="rounded-3xl border border-slate-200/90 bg-[#F8FAFD] p-8 hover:border-brand-cyan/40 hover:shadow-xl transition duration-300 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-display text-2xl font-bold text-brand-navy">
                                <a href="{{ route('india.city', ['state' => $stateSlug, 'city' => $cityKey]) }}" class="hover:text-brand-cyan transition">
                                    {{ $city['name'] }}
                                </a>
                            </h3>
                            <span class="text-xs font-bold px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">
                                100% Verified
                            </span>
                        </div>
                        <p class="text-sm text-slate-600 mb-6 leading-relaxed">
                            {{ $city['meta_desc'] }}
                        </p>

                        <!-- BHK Rates Breakdown -->
                        <div class="space-y-2.5 bg-white p-4 rounded-2xl border border-slate-200/80 mb-6">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-slate-500 font-medium">1 BHK Avg Rent:</span>
                                <span class="font-bold text-brand-navy">₹{{ number_format($city['avg_rent_1bhk']) }}/mo</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-slate-500 font-medium">2 BHK Avg Rent:</span>
                                <span class="font-bold text-brand-cyan">₹{{ number_format($city['avg_rent_2bhk']) }}/mo</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-slate-500 font-medium">3 BHK Avg Rent:</span>
                                <span class="font-bold text-brand-navy">₹{{ number_format($city['avg_rent_3bhk']) }}/mo</span>
                            </div>
                            <div class="flex items-center justify-between text-xs border-t border-slate-100 pt-2">
                                <span class="text-slate-500 font-medium">Typical Deposit:</span>
                                <span class="font-semibold text-slate-700">{{ $city['deposit_months'] }}</span>
                            </div>
                        </div>

                        <!-- Top Localities Tags -->
                        <div class="mb-6">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-2">Prime Localities:</span>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach(array_slice($city['localities'], 0, 5) as $loc)
                                    <span class="text-xs px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 font-medium">
                                        {{ $loc }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('india.city', ['state' => $stateSlug, 'city' => $cityKey]) }}" class="inline-flex items-center justify-center gap-2 w-full py-3.5 px-4 rounded-xl bg-brand-navy text-xs font-bold text-white hover:bg-brand-cyan transition shadow-md">
                        <span>View {{ $city['name'] }} Rentals &amp; Analytics</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ── SECTION 3: LEGAL & LEASING PROCESS IN THIS STATE ── -->
<section class="py-16 sm:py-20 bg-[#F8FAFD] border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="text-center max-w-3xl mx-auto mb-14">
            <span class="text-xs font-bold uppercase tracking-widest text-brand-cyan">COMPLIANCE &amp; SECURITY</span>
            <h2 class="font-display text-3xl sm:text-4xl text-brand-navy font-extrabold mt-2">
                Leasing Safely in {{ $stateData['name'] }}
            </h2>
            <p class="text-slate-600 text-base mt-3">We handle the entire end-to-end legal protocol so owners and tenants stay 100% compliant.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="p-8 rounded-3xl bg-white border border-slate-200/80 shadow-sm">
                <div class="size-12 rounded-xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-xl mb-5">
                    <i class="fa-solid fa-file-signature"></i>
                </div>
                <h3 class="font-display text-xl font-bold text-brand-navy mb-2">11-Month Registered Lease</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Custom drafted digital Leave &amp; License or Tenancy contracts aligned with {{ $stateData['tenancy_act'] }}. Doorstep biometric registration or digital e-signing available.
                </p>
            </div>
            <div class="p-8 rounded-3xl bg-white border border-slate-200/80 shadow-sm">
                <div class="size-12 rounded-xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-xl mb-5">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
                <h3 class="font-display text-xl font-bold text-brand-navy mb-2">Police Tenant Verification</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Mandatory submission via {{ $stateData['police_verification'] }}. We collect Aadhaar, employer credentials, and submit legal police acknowledgment copies to the housing society.
                </p>
            </div>
            <div class="p-8 rounded-3xl bg-white border border-slate-200/80 shadow-sm">
                <div class="size-12 rounded-xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-xl mb-5">
                    <i class="fa-solid fa-file-contract"></i>
                </div>
                <h3 class="font-display text-xl font-bold text-brand-navy mb-2">Legal Lease &amp; E-Stamping</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Complete legal documentation compliant with state tenancy rules. We handle custom drafting, e-stamping, and notary registration. (Government stamp duty and registration charges at actual extra cost).
                </p>
            </div>
        </div>
    </div>
</section>

@endsection
