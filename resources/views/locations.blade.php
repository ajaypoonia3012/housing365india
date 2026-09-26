@extends('layouts.app')

@section('title', 'Metros & City Locations | Housing 365')
@section('meta_description', 'Explore residential and corporate rental locations across Canada and the United States managed by Housing 365.')

@section('content')

<!-- ── BREADCRUMB ── -->
<div class="bg-white border-b border-slate-100 py-3.5 text-sm text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-cyan transition font-medium">Home</a>
        <span class="text-slate-300">/</span>
        <span class="text-brand-navy font-bold">Locations</span>
    </div>
</div>

<!-- ── SECTION 1: HERO SHOWCASE (LIGHT ELEGANT DESIGN) ── -->
<section class="relative overflow-hidden bg-gradient-to-b from-[#F7FAFD] via-white to-[#F0F6FC] py-16 sm:py-20 lg:py-28">
    <div class="pointer-events-none absolute -left-40 top-0 h-[500px] w-[500px] rounded-full opacity-40 blur-3xl" style="background: radial-gradient(circle, rgba(0,174,239,0.18) 0%, transparent 70%)" aria-hidden="true"></div>
    <div class="pointer-events-none absolute right-0 top-20 h-[600px] w-[600px] rounded-full opacity-30 blur-3xl" style="background: radial-gradient(circle, rgba(3,35,75,0.06) 0%, transparent 70%)" aria-hidden="true"></div>

    <div class="relative mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="max-w-3xl mx-auto text-center">
            <!-- Eyebrow Badge -->
            <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-brand-cyan/20 bg-brand-cyan/5 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                <span class="h-2 w-2 rounded-full bg-brand-cyan animate-pulse"></span>
                CROSS-BORDER METRO COVERAGE
            </div>

            <!-- Headline -->
            <h1 class="font-display text-4xl font-extrabold leading-[1.12] tracking-tight text-brand-navy sm:text-5xl md:text-6xl">
                Active Markets across <br>
                <span class="text-brand-cyan font-extrabold">Canada &amp; United States.</span>
            </h1>

            <!-- Subheadline -->
            <p class="mt-6 text-base leading-relaxed text-slate-600 sm:text-lg">
                Discover verified rental properties and localized on-the-ground property management teams across 160+ major metropolitan areas.
            </p>

            <!-- Value Highlights -->
            <div class="mt-8 flex flex-wrap justify-center items-center gap-6 text-sm font-semibold text-slate-700">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-brand-cyan"></i>
                    <span>25+ Tier-1 Metros</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-brand-cyan"></i>
                    <span>Local Licensed Brokers</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-brand-cyan"></i>
                    <span>Real-Time Market Rent Comps</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-brand-cyan"></i>
                    <span>Cross-Border Tax Advisory</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 2: METROS DIRECTORY ── -->
<section class="py-16 sm:py-20 lg:py-24 bg-[#F8FAFD] border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 space-y-20">
        
        <!-- Canada Section -->
        <div>
            <div class="flex items-center gap-4 mb-10 pb-4 border-b border-slate-200">
                <span class="text-4xl">🇨🇦</span>
                <div>
                    <h2 class="font-display text-3xl font-extrabold text-brand-navy tracking-tight">Canadian Metros</h2>
                    <p class="text-sm text-slate-500 font-medium">Ontario, British Columbia, Alberta, Quebec &amp; Atlantic Canada</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($canadaCities as $city)
                    <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm transition-all duration-300 hover:shadow-xl hover:-translate-y-1 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="font-heading font-bold text-xl text-brand-navy">{{ $city->name }}</h3>
                                    <span class="text-xs text-slate-500 font-semibold">{{ $city->province_or_state }}, Canada</span>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-brand-cyan/10 text-brand-cyan">
                                    Tier {{ $city->tier ?? '1' }} Metro
                                </span>
                            </div>
                            <p class="text-slate-600 text-sm leading-relaxed mb-6">{{ $city->description }}</p>
                        </div>

                        <div class="pt-5 border-t border-slate-100 flex items-center justify-between">
                            <div>
                                <span class="text-xs text-slate-400 block font-bold uppercase tracking-wider">Average Rent</span>
                                <span class="text-base font-extrabold text-brand-navy">{{ $city->avg_rent ?? 'Market Rate' }}</span>
                            </div>
                            <a href="{{ route('properties.index', ['city' => $city->name]) }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-brand-cyan hover:bg-brand-cyan-hover text-white font-bold text-xs uppercase tracking-wider transition shadow-sm">
                                <span>Explore Units</span>
                                <span class="text-[11px] opacity-80">({{ $city->available_units_count ?? 15 }}+)</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- US Section -->
        <div>
            <div class="flex items-center gap-4 mb-10 pb-4 border-b border-slate-200">
                <span class="text-4xl">🇺🇸</span>
                <div>
                    <h2 class="font-display text-3xl font-extrabold text-brand-navy tracking-tight">United States Metros</h2>
                    <p class="text-sm text-slate-500 font-medium">Texas, Florida, California, North Carolina, Arizona &amp; Major US Growth Hubs</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($usCities as $city)
                    <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm transition-all duration-300 hover:shadow-xl hover:-translate-y-1 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="font-heading font-bold text-xl text-brand-navy">{{ $city->name }}</h3>
                                    <span class="text-xs text-slate-500 font-semibold">{{ $city->province_or_state }}, USA</span>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-brand-cyan/10 text-brand-cyan">
                                    Tier {{ $city->tier ?? '1' }} Metro
                                </span>
                            </div>
                            <p class="text-slate-600 text-sm leading-relaxed mb-6">{{ $city->description }}</p>
                        </div>

                        <div class="pt-5 border-t border-slate-100 flex items-center justify-between">
                            <div>
                                <span class="text-xs text-slate-400 block font-bold uppercase tracking-wider">Average Rent</span>
                                <span class="text-base font-extrabold text-brand-navy">{{ $city->avg_rent ?? 'Market Rate' }}</span>
                            </div>
                            <a href="{{ route('properties.index', ['city' => $city->name]) }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-brand-cyan hover:bg-brand-cyan-hover text-white font-bold text-xs uppercase tracking-wider transition shadow-sm">
                                <span>Explore Units</span>
                                <span class="text-[11px] opacity-80">({{ $city->available_units_count ?? 10 }}+)</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</section>

<!-- ── SECTION 3: REGIONAL REGULATORY EXPERTISE ── -->
<section class="py-16 sm:py-20 lg:py-24 bg-white border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="max-w-3xl mb-14">
            <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                <span class="h-2 w-2 rounded-full bg-brand-cyan"></span>
                REGULATORY COMPLIANCE
            </div>
            <h2 class="font-display text-3xl font-extrabold tracking-tight text-brand-navy sm:text-4xl">
                Localized Compliance Across Every Jurisdiction
            </h2>
            <p class="mt-4 text-base text-slate-600">
                Rental legislation varies wildly between provinces and states. Our localized real estate directors ensure strict legal compliance in every regional market.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="rounded-3xl border border-slate-200/80 bg-[#F8FAFD] p-8">
                <div class="size-12 rounded-2xl bg-sky-50 text-brand-cyan flex items-center justify-center text-xl mb-5">
                    <i class="fa-solid fa-gavel"></i>
                </div>
                <h3 class="text-xl font-bold text-brand-navy mb-3">Ontario &amp; GTA Specialists</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Full expertise in the Ontario Residential Tenancies Act (RTA), Ontario Standard Lease (OSL) drafting, LTB filings, rent increase guidelines, and municipal short-term licensing.
                </p>
            </div>

            <div class="rounded-3xl border border-slate-200/80 bg-[#F8FAFD] p-8">
                <div class="size-12 rounded-2xl bg-sky-50 text-brand-cyan flex items-center justify-center text-xl mb-5">
                    <i class="fa-solid fa-scale-balanced"></i>
                </div>
                <h3 class="text-xl font-bold text-brand-navy mb-3">Western Canada (BC &amp; AB)</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Navigating British Columbia Residential Tenancy Branch (RTB) rules and Alberta Residential Tenancies Act requirements with strict escrow handling and condition reports.
                </p>
            </div>

            <div class="rounded-3xl border border-slate-200/80 bg-[#F8FAFD] p-8">
                <div class="size-12 rounded-2xl bg-sky-50 text-brand-cyan flex items-center justify-center text-xl mb-5">
                    <i class="fa-solid fa-passport"></i>
                </div>
                <h3 class="text-xl font-bold text-brand-navy mb-3">Cross-Border US Expansion</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Specialized management in Florida Chapter 83 and Texas Property Code markets, coordinating FIRPTA withholdings, W-8BEN compliance, and non-resident investor tax ledgers.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 4: LOCATION FAQS (FULL WIDTH 2-COLUMN) ── -->
<section class="py-16 sm:py-20 lg:py-24 bg-[#F8FAFD] border-t border-slate-200/80">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12 pb-6 border-b border-slate-200">
            <div>
                <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                    <span class="h-2 w-2 rounded-full bg-brand-cyan"></span>
                    GEOGRAPHIC COVERAGE &middot; LOCATION FAQ
                </div>
                <h2 class="font-display text-3xl font-extrabold tracking-tight text-brand-navy sm:text-4xl">
                    Location &amp; Expansion Inquiries
                </h2>
                <p class="mt-3 text-base text-slate-600 max-w-2xl">
                    Answers about our operating zones, local licensed representation, provincial compliance, and market entry.
                </p>
            </div>
            <div class="shrink-0">
                <a href="{{ url('/contact?type=owner') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-bold text-brand-navy shadow-sm transition-all hover:bg-slate-50 hover:border-slate-400">
                    <span>Inquire About Your City</span>
                    <i class="fa-solid fa-arrow-right text-xs text-brand-cyan"></i>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start w-full">
            <div class="rounded-2xl border border-slate-200/90 bg-white p-6 shadow-sm transition hover:border-brand-cyan/40 hover:shadow-md">
                <h3 class="font-heading font-bold text-brand-navy text-base sm:text-lg mb-2 flex items-start gap-3">
                    <span class="inline-flex items-center justify-center rounded-lg bg-sky-50 px-2.5 py-1 text-xs font-bold text-brand-cyan border border-sky-100">01</span>
                    <span>Do you have on-the-ground teams in every city listed?</span>
                </h3>
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed pl-9">
                    Yes. We partner with licensed local brokers, verified showing agents, and vetted trade contractors in each active metropolitan area so in-person property tours, inspections, and emergency repairs are handled immediately.
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200/90 bg-white p-6 shadow-sm transition hover:border-brand-cyan/40 hover:shadow-md">
                <h3 class="font-heading font-bold text-brand-navy text-base sm:text-lg mb-2 flex items-start gap-3">
                    <span class="inline-flex items-center justify-center rounded-lg bg-sky-50 px-2.5 py-1 text-xs font-bold text-brand-cyan border border-sky-100">02</span>
                    <span>Can you lease a property in a city not currently listed?</span>
                </h3>
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed pl-9">
                    Frequently, yes. Our digital syndication network reaches prospective tenants nationwide across 40+ listing portals. Contact our regional expansion team to confirm service coverage for your specific suburban or regional municipality.
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200/90 bg-white p-6 shadow-sm transition hover:border-brand-cyan/40 hover:shadow-md">
                <h3 class="font-heading font-bold text-brand-navy text-base sm:text-lg mb-2 flex items-start gap-3">
                    <span class="inline-flex items-center justify-center rounded-lg bg-sky-50 px-2.5 py-1 text-xs font-bold text-brand-cyan border border-sky-100">03</span>
                    <span>How does cross-border management work for Canadian investors in the US?</span>
                </h3>
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed pl-9">
                    We manage US properties through licensed local brokerages and title agents, providing cross-border investors with USD rent payouts, 1042-S tax summaries, and turnkey maintenance dispatch.
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200/90 bg-white p-6 shadow-sm transition hover:border-brand-cyan/40 hover:shadow-md">
                <h3 class="font-heading font-bold text-brand-navy text-base sm:text-lg mb-2 flex items-start gap-3">
                    <span class="inline-flex items-center justify-center rounded-lg bg-sky-50 px-2.5 py-1 text-xs font-bold text-brand-cyan border border-sky-100">04</span>
                    <span>Are leases customized for each province or state's residential tenancy laws?</span>
                </h3>
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed pl-9">
                    Absolutely. Every tenancy agreement utilizes the mandatory provincial standard form (such as the Ontario Standard Lease Form 2229E or BC RTB-1) coupled with custom legal schedules tailored to your municipality's bylaws.
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200/90 bg-white p-6 shadow-sm transition hover:border-brand-cyan/40 hover:shadow-md">
                <h3 class="font-heading font-bold text-brand-navy text-base sm:text-lg mb-2 flex items-start gap-3">
                    <span class="inline-flex items-center justify-center rounded-lg bg-sky-50 px-2.5 py-1 text-xs font-bold text-brand-cyan border border-sky-100">05</span>
                    <span>How do showings work if I live in a different province or abroad?</span>
                </h3>
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed pl-9">
                    You don't need to do a thing. Our local licensed agents and digital lockbox networks facilitate accompanied showings with pre-screened applicants. You receive real-time showing feedback directly through your owner portal.
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200/90 bg-white p-6 shadow-sm transition hover:border-brand-cyan/40 hover:shadow-md">
                <h3 class="font-heading font-bold text-brand-navy text-base sm:text-lg mb-2 flex items-start gap-3">
                    <span class="inline-flex items-center justify-center rounded-lg bg-sky-50 px-2.5 py-1 text-xs font-bold text-brand-cyan border border-sky-100">06</span>
                    <span>How quickly can you activate and launch a new property listing?</span>
                </h3>
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed pl-9">
                    Once we receive property access, high-definition photography and 3D architectural scanning are completed within 24 to 48 hours. Syndication goes live across all major channels within 4 business hours of photo editing.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 5: CALL TO ACTION BANNER (LIGHT MODERN) ── -->
<section class="py-16 sm:py-20 lg:py-24 bg-gradient-to-r from-sky-50 via-white to-cyan-50">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="relative overflow-hidden rounded-3xl border border-brand-cyan/20 bg-white p-8 sm:p-14 lg:p-16 shadow-xl flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="max-w-2xl">
                <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                    <span class="size-2 rounded-full bg-brand-cyan animate-pulse"></span>
                    EXPANDING PORTFOLIO
                </div>
                <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold text-brand-navy">
                    Looking to Lease in a Specific Metro?
                </h2>
                <p class="mt-4 text-base sm:text-lg text-slate-600">
                    Our regional real estate directors provide immediate local market rent estimates and tenant placement services.
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
