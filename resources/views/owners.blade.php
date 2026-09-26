@extends('layouts.app')

@section('title', $page->meta_title ?? 'Full-Service Leasing for Landlords & Property Owners | Housing 365')
@section('meta_description', $page->meta_description ?? 'Strategic pricing, professional 4K marketing, structured tenant screening, and legal lease execution for Canadian and US rental property owners. Performance-based leasing.')

@section('content')

<!-- ── BREADCRUMB ── -->
<div class="bg-white border-b border-slate-100 py-3.5 text-sm text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-cyan transition font-medium">Home</a>
        <span class="text-slate-300">/</span>
        <span class="text-brand-navy font-bold">Property Owners</span>
    </div>
</div>

<!-- ── SECTION 1: HERO SHOWCASE (LIGHT ELEGANT DESIGN) ── -->
<section class="relative overflow-hidden bg-gradient-to-b from-[#F7FAFD] via-white to-[#F0F6FC] py-16 sm:py-20 lg:py-28">
    <div class="pointer-events-none absolute -left-40 top-0 h-[500px] w-[500px] rounded-full opacity-40 blur-3xl" style="background: radial-gradient(circle, rgba(0,174,239,0.18) 0%, transparent 70%)" aria-hidden="true"></div>
    <div class="pointer-events-none absolute right-0 top-20 h-[600px] w-[600px] rounded-full opacity-30 blur-3xl" style="background: radial-gradient(circle, rgba(3,35,75,0.06) 0%, transparent 70%)" aria-hidden="true"></div>

    <div class="relative mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 items-center gap-12">
            <div class="lg:col-span-7">
                <!-- Eyebrow Badge -->
                <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-brand-cyan/20 bg-brand-cyan/5 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                    <span class="h-2 w-2 rounded-full bg-brand-cyan animate-pulse"></span>
                    {{ $page->hero_badge ?? 'FOR PROPERTY OWNERS & LANDLORDS' }}
                </div>

                <!-- Headline -->
                <h1 class="font-display text-4xl font-extrabold leading-[1.12] tracking-tight text-brand-navy sm:text-5xl lg:text-6xl">
                    Stress-Free Ownership. <br class="hidden sm:inline">
                    <span class="text-brand-cyan font-extrabold">Maximized Rental Yield.</span>
                </h1>

                <!-- Subheadline -->
                <p class="mt-6 text-base sm:text-lg leading-relaxed text-slate-600 max-w-2xl">
                    {{ $page->hero_subtitle ?? 'Full-service leasing execution built for landlords who value their time. We handle pricing analysis, 4K marketing, showings, 75-point tenant qualification, and legal lease execution across Ontario and major US hubs.' }}
                </p>

                <!-- Value Highlights -->
                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm font-semibold text-slate-700">
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-circle-check text-brand-cyan"></i>
                        <span>No Monthly Retainers or Management %</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-circle-check text-brand-cyan"></i>
                        <span>14-Day Average Tenant Placement</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-circle-check text-brand-cyan"></i>
                        <span>11-Month Legal Lease &amp; E-Stamping</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-circle-check text-brand-cyan"></i>
                        <span>Model Tenancy Act &amp; State Rent Rules Compliant</span>
                    </div>
                </div>

                <!-- CTAs -->
                <div class="mt-9 flex flex-wrap items-center gap-4">
                    <a href="{{ route('contact') }}" 
                       class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand-cyan px-8 py-4 text-base font-bold text-white shadow-lg shadow-brand-cyan/25 transition-all duration-200 hover:-translate-y-0.5 hover:bg-brand-cyan-hover">
                        <span>Get Free Rental Market Valuation</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>

                    <a href="{{ route('services') }}" 
                       class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-7 py-4 text-base font-bold text-brand-navy shadow-sm transition hover:border-slate-400 hover:bg-slate-50">
                        <span>Explore Owner Services</span>
                    </a>
                </div>
            </div>

            <!-- Right Column: Interactive Video Motion Component -->
            <div class="lg:col-span-5">
                <div class="rounded-3xl border border-slate-200/90 bg-white p-3 shadow-2xl">
                    @include('partials.owner-video-player')
                </div>
            </div>
        </div>

        <!-- 4-Stat Strip (Light Modern) -->
        <div class="mt-16 grid grid-cols-2 sm:grid-cols-4 gap-6 pt-10 border-t border-slate-200">
            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm text-center">
                <span class="font-display text-3xl sm:text-4xl font-extrabold text-brand-cyan">$0</span>
                <p class="text-sm font-bold text-brand-navy mt-1">Upfront Fees</p>
                <p class="text-xs text-slate-500 mt-0.5">Success-fee only</p>
            </div>
            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm text-center">
                <span class="font-display text-3xl sm:text-4xl font-extrabold text-brand-navy">12–14 Days</span>
                <p class="text-sm font-bold text-brand-navy mt-1">Average Lease Velocity</p>
                <p class="text-xs text-slate-500 mt-0.5">Rapid tenant placement</p>
            </div>
            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm text-center">
                <span class="font-display text-3xl sm:text-4xl font-extrabold text-emerald-600">99.4%</span>
                <p class="text-sm font-bold text-brand-navy mt-1">On-Time Rent Collection</p>
                <p class="text-xs text-slate-500 mt-0.5">Vetted high-quality tenants</p>
            </div>
            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm text-center">
                <span class="font-display text-3xl sm:text-4xl font-extrabold text-brand-cyan">40+</span>
                <p class="text-sm font-bold text-brand-navy mt-1">Syndicated Listing Sites</p>
                <p class="text-xs text-slate-500 mt-0.5">Realtor.ca, Zillow &amp; more</p>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 2: THE LANDLORD'S DILEMMA (PAIN POINTS) ── -->
<section class="bg-white py-16 sm:py-20 lg:py-24 border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="max-w-3xl mb-14">
            <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-rose-500">
                <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                THE COST OF DIY LEASING
            </div>
            <h2 class="font-display text-3xl font-extrabold tracking-tight text-brand-navy sm:text-4xl">
                Why Self-Managing Leasing Carries Expensive Risks
            </h2>
            <p class="mt-4 text-base text-slate-600">
                Placing the wrong tenant costs thousands in unpaid rent, LTB hearings, legal representation, and property damage.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="rounded-3xl border border-rose-100 bg-rose-50/30 p-7 shadow-sm hover:shadow-md transition">
                <div class="size-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center mb-5">
                    <i class="fa-solid fa-clock text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-brand-navy mb-2">Weeks of Lost Rent</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Overpricing or weak syndication causes listings to sit on the market for 6–8 weeks, costing an entire month of net revenue.
                </p>
            </div>

            <div class="rounded-3xl border border-rose-100 bg-rose-50/30 p-7 shadow-sm hover:shadow-md transition">
                <div class="size-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center mb-5">
                    <i class="fa-solid fa-user-xmark text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-brand-navy mb-2">Fabricated Applications</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Sophisticated fraudulent paystubs and fake references slip right past basic credit checks without forensic verification.
                </p>
            </div>

            <div class="rounded-3xl border border-rose-100 bg-rose-50/30 p-7 shadow-sm hover:shadow-md transition">
                <div class="size-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center mb-5">
                    <i class="fa-solid fa-gavel text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-brand-navy mb-2">Tenancy Board Disputes</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Missing addenda or non-standard riders can invalidate your legal position before the Landlord & Tenant Board.
                </p>
            </div>

            <div class="rounded-3xl border border-rose-100 bg-rose-50/30 p-7 shadow-sm hover:shadow-md transition">
                <div class="size-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center mb-5">
                    <i class="fa-solid fa-calendar-xmark text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-brand-navy mb-2">Endless Unqualified Tours</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Hours spent rushing to evening showings only to have unvetted prospects fail to show or fail basic income criteria.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 3: THE 5-PILLAR LANDLORD PROTECTION SUITE ── -->
<section class="py-16 sm:py-20 lg:py-24 bg-[#F8FAFD] border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="max-w-3xl mb-14">
            <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                <span class="h-2 w-2 rounded-full bg-brand-cyan"></span>
                FULL PROTECTION
            </div>
            <h2 class="font-display text-3xl font-extrabold tracking-tight text-brand-navy sm:text-4xl">
                The Housing 365 5-Tier Landlord Defense Suite
            </h2>
            <p class="mt-4 text-base text-slate-600">
                Every property owner benefits from institutional-grade leasing safeguards that eliminate the common pitfalls of private renting.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="size-12 rounded-2xl bg-sky-50 text-brand-cyan flex items-center justify-center text-xl mb-5">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h3 class="text-xl font-bold text-brand-navy mb-3">75-Point Tenant Underwriting</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        We don't rely on self-reported applicant forms. We connect directly with payroll APIs, verify credit scores through Equifax, audit previous landlord references, and cross-reference public court registries.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 text-xs text-brand-cyan font-bold">
                    Zero guesswork screening
                </div>
            </div>

            <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="size-12 rounded-2xl bg-sky-50 text-brand-cyan flex items-center justify-center text-xl mb-5">
                        <i class="fa-solid fa-file-contract"></i>
                    </div>
                    <h3 class="text-xl font-bold text-brand-navy mb-3">11-Month Legal Lease &amp; E-Stamping</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Complete legal rental agreement drafting, state e-stamping, notary, and local police verification. (Government stamp duty, notary, and registration charges at actual extra cost).
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 text-xs text-emerald-600 font-bold">
                    100% legally watertight tenancy
                </div>
            </div>

            <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="size-12 rounded-2xl bg-sky-50 text-brand-cyan flex items-center justify-center text-xl mb-5">
                        <i class="fa-solid fa-camera"></i>
                    </div>
                    <h3 class="text-xl font-bold text-brand-navy mb-3">4K HDR Media &amp; Virtual Tours</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        High-quality listings attract high-quality tenants. We produce magazine-grade interior photography, 3D interactive virtual walkthroughs, and schematic floor plans.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 text-xs text-brand-cyan font-bold">
                    Commands top-of-market rent
                </div>
            </div>

            <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="size-12 rounded-2xl bg-sky-50 text-brand-cyan flex items-center justify-center text-xl mb-5">
                        <i class="fa-solid fa-bullhorn"></i>
                    </div>
                    <h3 class="text-xl font-bold text-brand-navy mb-3">40+ Syndication Network</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Your vacancy is instantly broadcasted across MLS Realtor.ca, Zillow, Rentals.ca, PadMapper, and targeted social media buyer campaigns to drive massive applicant volume.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 text-xs text-brand-cyan font-bold">
                    Maximum tenant exposure
                </div>
            </div>

            <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="size-12 rounded-2xl bg-sky-50 text-brand-cyan flex items-center justify-center text-xl mb-5">
                        <i class="fa-solid fa-file-contract"></i>
                    </div>
                    <h3 class="text-xl font-bold text-brand-navy mb-3">Compliant Legal Leases</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        We draft and execute official Ontario Standard Leases (OSL) complete with enforceable condominium bylaws, pet covenants, tenant insurance riders, and utility agreements.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 text-xs text-brand-cyan font-bold">
                    LTB &amp; Tribunal ready
                </div>
            </div>

            <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="size-12 rounded-2xl bg-sky-50 text-brand-cyan flex items-center justify-center text-xl mb-5">
                        <i class="fa-solid fa-desktop"></i>
                    </div>
                    <h3 class="text-xl font-bold text-brand-navy mb-3">24/7 Real-Time Owner Portal</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Track upcoming showings, view tenant credit summaries, countersign leases, and access year-end tax statements with direct EFT deposit tracking.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 text-xs text-brand-cyan font-bold">
                    Zero black-box communication
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 4: COMPARISON TABLE (HOUSING365 VS DIY VS TRADITIONAL BROKER) ── -->
<section class="bg-white py-16 sm:py-20 lg:py-24 border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="max-w-3xl mb-12">
            <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                <span class="h-2 w-2 rounded-full bg-brand-cyan"></span>
                THE PROVEN DIFFERENCE
            </div>
            <h2 class="font-display text-3xl font-extrabold tracking-tight text-brand-navy sm:text-4xl">
                How Housing365 Compares
            </h2>
            <p class="mt-3 text-base text-slate-600">See the difference between casual renting and institutional leasing execution.</p>
        </div>

        <div class="overflow-x-auto rounded-3xl border border-slate-200/80 bg-white shadow-sm">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50 text-slate-700">
                        <th class="p-6 font-bold">Execution Feature</th>
                        <th class="p-6 font-bold text-brand-cyan bg-sky-50/50 border-x border-brand-cyan/20">Housing365 Managed</th>
                        <th class="p-6 font-bold text-slate-500">DIY Landlord</th>
                        <th class="p-6 font-bold text-slate-500">Traditional Broker</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <tr>
                        <td class="p-6 font-bold text-brand-navy">Upfront Fees &amp; Retainers</td>
                        <td class="p-6 bg-sky-50/30 border-x border-brand-cyan/20 font-bold text-emerald-600"><i class="fa-solid fa-check mr-2"></i> $0 Upfront</td>
                        <td class="p-6 text-slate-500">Free, but 40+ hours lost</td>
                        <td class="p-6 text-slate-500">Retainer or listing fee</td>
                    </tr>
                    <tr>
                        <td class="p-6 font-bold text-brand-navy">Listing Syndication Reach</td>
                        <td class="p-6 bg-sky-50/30 border-x border-brand-cyan/20 font-bold text-emerald-600"><i class="fa-solid fa-check mr-2"></i> 40+ Portals &amp; MLS</td>
                        <td class="p-6 text-slate-500">Kijiji &amp; FB Marketplace</td>
                        <td class="p-6 text-slate-500">Realtor.ca MLS only</td>
                    </tr>
                    <tr>
                        <td class="p-6 font-bold text-brand-navy">Screening &amp; Fraud Vetting</td>
                        <td class="p-6 bg-sky-50/30 border-x border-brand-cyan/20 font-bold text-emerald-600"><i class="fa-solid fa-check mr-2"></i> 75-Point Automated Plaid &amp; Equifax</td>
                        <td class="p-6 text-slate-500">Easily forged paper paystubs</td>
                        <td class="p-6 text-slate-500">Basic credit check</td>
                    </tr>
                    <tr>
                        <td class="p-6 font-bold text-brand-navy">Rental Agreement &amp; E-Stamping</td>
                        <td class="p-6 bg-sky-50/30 border-x border-brand-cyan/20 font-bold text-emerald-600"><i class="fa-solid fa-check mr-2"></i> Custom 11-Mo Draft + State E-Stamp (Govt stamp duty at actuals)</td>
                        <td class="p-6 text-slate-500">Unverified stationary shop draft</td>
                        <td class="p-6 text-slate-500">Arbitrary lawyer markup fees</td>
                    </tr>
                    <tr>
                        <td class="p-6 font-bold text-brand-navy">Digital Owner Portal</td>
                        <td class="p-6 bg-sky-50/30 border-x border-brand-cyan/20 font-bold text-emerald-600"><i class="fa-solid fa-check mr-2"></i> 24/7 Live Status</td>
                        <td class="p-6 text-slate-500">Spreadsheets</td>
                        <td class="p-6 text-slate-500">Periodic phone calls</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- ── SECTION 5: OWNER FAQS (FULL WIDTH 2-COLUMN) ── -->
<section class="bg-[#F8FAFD] py-16 sm:py-20 lg:py-24 border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12 pb-6 border-b border-slate-200">
            <div>
                <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                    <span class="size-2 rounded-full bg-brand-cyan animate-pulse"></span>
                    LANDLORD ANSWERS &bull; OWNER FAQ
                </div>
                <h2 class="font-display text-3xl sm:text-4xl text-brand-navy font-extrabold tracking-tight">
                    Frequently Asked Questions for Landlords
                </h2>
                <p class="mt-2 text-base text-slate-600 max-w-2xl">
                    Clear, transparent answers about our success-fee pricing, tenant vetting protocol, and rental documentation.
                </p>
            </div>
            <div class="shrink-0">
                <a href="{{ route('pricing') }}" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl bg-white border border-slate-300 text-brand-navy font-bold text-xs uppercase tracking-wider shadow-sm hover:border-brand-cyan hover:text-brand-cyan hover:bg-slate-50 transition">
                    <span>View Transparent Pricing</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start w-full">
            <div class="rounded-3xl border border-slate-200/90 bg-white p-6 sm:p-8 hover:border-brand-cyan/40 hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <h3 class="font-heading font-bold text-brand-navy text-lg mb-3 flex items-start gap-3">
                        <span class="size-8 rounded-xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-sm shrink-0 font-bold mt-0.5">Q</span>
                        <span>When do I pay for your leasing services?</span>
                    </h3>
                    <p class="text-slate-600 text-sm sm:text-base leading-relaxed pl-11">
                        We operate on a transparent pricing model with no monthly retainers. Our one-time leasing success fee is earned only when a qualified tenant signs the lease agreement and pays the certified deposit.
                    </p>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200/90 bg-white p-6 sm:p-8 hover:border-brand-cyan/40 hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <h3 class="font-heading font-bold text-brand-navy text-lg mb-3 flex items-start gap-3">
                        <span class="size-8 rounded-xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-sm shrink-0 font-bold mt-0.5">Q</span>
                        <span>How do you determine the optimal rent price for my suite?</span>
                    </h3>
                    <p class="text-slate-600 text-sm sm:text-base leading-relaxed pl-11">
                        We combine real-time MLS leased comparables with syndicated rental portal demand data. We factor in your exact square footage, floor height, exposure, finish quality, parking, and building amenities to price at the absolute highest rent that leases in under 14 days.
                    </p>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200/90 bg-white p-6 sm:p-8 hover:border-brand-cyan/40 hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <h3 class="font-heading font-bold text-brand-navy text-lg mb-3 flex items-start gap-3">
                        <span class="size-8 rounded-xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-sm shrink-0 font-bold mt-0.5">Q</span>
                        <span>Do I have the final say on which tenant is approved?</span>
                    </h3>
                    <p class="text-slate-600 text-sm sm:text-base leading-relaxed pl-11">
                        Yes. Once an applicant passes our strict 75-point underwriting threshold, we present you with a clean executive summary containing their credit report, employment confirmation, income ratio, and references. You always make the final approval decision.
                    </p>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200/90 bg-white p-6 sm:p-8 hover:border-brand-cyan/40 hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <h3 class="font-heading font-bold text-brand-navy text-lg mb-3 flex items-start gap-3">
                        <span class="size-8 rounded-xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-sm shrink-0 font-bold mt-0.5">Q</span>
                        <span>What happens if the tenant breaks the lease early?</span>
                    </h3>
                    <p class="text-slate-600 text-sm sm:text-base leading-relaxed pl-11">
                        We stand behind our tenant placements. If a placed tenant defaults or breaks their lease within the warranty period (up to 12 months), Housing 365 re-leases your property free of placement fees.
                    </p>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200/90 bg-white p-6 sm:p-8 hover:border-brand-cyan/40 hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <h3 class="font-heading font-bold text-brand-navy text-lg mb-3 flex items-start gap-3">
                        <span class="size-8 rounded-xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-sm shrink-0 font-bold mt-0.5">Q</span>
                        <span>How is the rental agreement and stamp duty handled?</span>
                    </h3>
                    <p class="text-slate-600 text-sm sm:text-base leading-relaxed pl-11">
                        We draft custom 11-month or multi-year leave &amp; license agreements compliant with state rent control acts. Digital e-stamping, notary, and local police verification paperwork are coordinated seamlessly. Government stamp duty and registration fees are charged at actual cost with zero hidden markups.
                    </p>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200/90 bg-white p-6 sm:p-8 hover:border-brand-cyan/40 hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <h3 class="font-heading font-bold text-brand-navy text-lg mb-3 flex items-start gap-3">
                        <span class="size-8 rounded-xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-sm shrink-0 font-bold mt-0.5">Q</span>
                        <span>What listing portals do you syndicate to?</span>
                    </h3>
                    <p class="text-slate-600 text-sm sm:text-base leading-relaxed pl-11">
                        Your property is simultaneously syndicated to 40+ leading rental channels including Zillow, Realtor.com, Trulia, Rentals.ca, PadMapper, Facebook Marketplace, Kijiji, and local MLS boards.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 6: CALL TO ACTION BANNER (LIGHT MODERN) ── -->
<section class="py-16 sm:py-20 lg:py-24 bg-gradient-to-r from-sky-50 via-white to-cyan-50">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="relative overflow-hidden rounded-3xl border border-brand-cyan/20 bg-white p-8 sm:p-14 lg:p-16 shadow-xl flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="max-w-2xl">
                <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                    <span class="size-2 rounded-full bg-brand-cyan animate-pulse"></span>
                    START IN 60 SECONDS
                </div>
                <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold text-brand-navy">
                    Get Your Property Leased with Total Peace of Mind
                </h2>
                <p class="mt-4 text-base sm:text-lg text-slate-600">
                    Speak with a licensed leasing expert today. Receive an in-depth rental comparative market analysis within one business day.
                </p>
            </div>

            <div class="shrink-0">
                <a href="{{ route('contact') }}" 
                   class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand-cyan px-8 py-4 text-base font-bold text-white shadow-lg shadow-brand-cyan/25 transition-all duration-200 hover:-translate-y-0.5 hover:bg-brand-cyan-hover">
                    <span>Talk to an Expert</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
