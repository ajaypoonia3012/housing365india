@extends('layouts.app')

@section('title', 'Client Reviews & Resident Testimonials | Housing 365')
@section('meta_description', 'Read real verified feedback from property owners, institutional landlords, and tenants across Housing 365 communities.')

@section('content')

<!-- ── BREADCRUMB ── -->
<div class="bg-white border-b border-slate-100 py-3.5 text-sm text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-cyan transition font-medium">Home</a>
        <span class="text-slate-300">/</span>
        <span class="text-brand-navy font-bold">Reviews &amp; Testimonials</span>
    </div>
</div>

<!-- ── SECTION 1: HERO SHOWCASE (LIGHT ELEGANT DESIGN) ── -->
<section class="relative overflow-hidden bg-gradient-to-b from-[#F7FAFD] via-white to-[#F0F6FC] py-16 sm:py-20 lg:py-24">
    <!-- Ambient Glow Effects -->
    <div class="pointer-events-none absolute -left-40 top-0 h-[500px] w-[500px] rounded-full opacity-40 blur-3xl" style="background: radial-gradient(circle, rgba(0,174,239,0.18) 0%, transparent 70%)" aria-hidden="true"></div>
    <div class="pointer-events-none absolute right-0 top-20 h-[600px] w-[600px] rounded-full opacity-30 blur-3xl" style="background: radial-gradient(circle, rgba(3,35,75,0.06) 0%, transparent 70%)" aria-hidden="true"></div>

    <div class="relative mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="max-w-3xl mx-auto text-center">
            <!-- Eyebrow Badge -->
            <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-brand-cyan/20 bg-brand-cyan/5 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                <span class="h-2 w-2 rounded-full bg-brand-cyan animate-pulse"></span>
                VERIFIED CLIENT &amp; RESIDENT FEEDBACK
            </div>

            <!-- Headline -->
            <h1 class="font-display text-4xl font-extrabold leading-[1.12] tracking-tight text-brand-navy sm:text-5xl md:text-6xl">
                Trusted by Owners, <br>
                <span class="text-brand-cyan font-extrabold">Loved by Residents.</span>
            </h1>

            <!-- Subheadline -->
            <p class="mt-6 text-base leading-relaxed text-slate-600 sm:text-lg">
                We measure our success by the long-term yields of the property owners who partner with us and the quality of life enjoyed by the residents who call our suites home.
            </p>

            <!-- Score Pill -->
            <div class="mt-8 inline-flex flex-wrap items-center justify-center gap-4 px-6 py-3.5 rounded-2xl bg-white border border-slate-200/90 shadow-md">
                <div class="flex text-brand-cyan text-sm space-x-1">
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                </div>
                <div class="h-4 w-px bg-slate-200 hidden sm:block"></div>
                <span class="text-sm font-bold text-brand-navy">4.9 / 5.0 Average Rating</span>
                <span class="text-xs text-slate-500 font-semibold">Google &amp; Trustpilot Verified</span>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 2: 4-METRIC BENCHMARKS (LIGHT STRIP) ── -->
<section class="border-y border-slate-200/80 bg-white py-10 sm:py-12">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div class="p-4 rounded-2xl bg-[#F8FAFD] border border-slate-100">
                <span class="font-display text-3xl sm:text-4xl font-extrabold text-brand-navy block">4.9 / 5</span>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 mt-1 block">Verified Rating Average</span>
            </div>
            <div class="p-4 rounded-2xl bg-[#F8FAFD] border border-slate-100">
                <span class="font-display text-3xl sm:text-4xl font-extrabold text-brand-cyan block">99.4%</span>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 mt-1 block">On-Time Rent Collection</span>
            </div>
            <div class="p-4 rounded-2xl bg-[#F8FAFD] border border-slate-100">
                <span class="font-display text-3xl sm:text-4xl font-extrabold text-brand-navy block">12 Days</span>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 mt-1 block">Average Placement Velocity</span>
            </div>
            <div class="p-4 rounded-2xl bg-[#F8FAFD] border border-slate-100">
                <span class="font-display text-3xl sm:text-4xl font-extrabold text-emerald-600 block">10,000+</span>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 mt-1 block">Happy Residents Housed</span>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 3: RATING CRITERIA BREAKDOWN ── -->
<section class="py-12 sm:py-16 bg-[#F8FAFD] border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="max-w-2xl mx-auto text-center mb-10">
            <h2 class="font-display text-2xl sm:text-3xl font-bold text-brand-navy">Performance by Service Category</h2>
            <p class="text-sm text-slate-600 mt-2">Independently audited ratings from thousands of landlord and tenant interactions.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-bold text-brand-navy">Tenant Screening &amp; Quality</span>
                    <span class="text-xs font-bold text-emerald-600">4.96 / 5</span>
                </div>
                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                    <div class="bg-emerald-500 h-full rounded-full" style="width: 99%"></div>
                </div>
                <p class="text-xs text-slate-500 mt-3">75-point Equifax &amp; bank payroll verification</p>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-bold text-brand-navy">Speed &amp; Leasing Velocity</span>
                    <span class="text-xs font-bold text-brand-cyan">4.92 / 5</span>
                </div>
                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                    <div class="bg-brand-cyan h-full rounded-full" style="width: 98%"></div>
                </div>
                <p class="text-xs text-slate-500 mt-3">12–14 day average time to signed lease</p>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-bold text-brand-navy">Communication &amp; Transparency</span>
                    <span class="text-xs font-bold text-brand-cyan">4.98 / 5</span>
                </div>
                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                    <div class="bg-brand-cyan h-full rounded-full" style="width: 99.5%"></div>
                </div>
                <p class="text-xs text-slate-500 mt-3">24/7 owner dashboard with live showing logs</p>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-bold text-brand-navy">Legal Compliance &amp; Leases</span>
                    <span class="text-xs font-bold text-emerald-600">5.0 / 5</span>
                </div>
                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                    <div class="bg-emerald-500 h-full rounded-full" style="width: 100%"></div>
                </div>
                <p class="text-xs text-slate-500 mt-3">Zero legal disputes across all registered agreements</p>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 4: REVIEWS DIRECTORY ── -->
<section class="py-16 sm:py-20 bg-white">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-10">
            <div>
                <h2 class="font-display text-2xl sm:text-3xl font-bold text-brand-navy">Verified Client Stories</h2>
                <p class="text-sm text-slate-500 mt-1">Read unfiltered feedback from property owners and residents.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Showing:</span>
                <span class="px-3 py-1 rounded-xl bg-sky-50 text-brand-cyan font-bold text-xs">All Verified Reviews</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($testimonials as $t)
                <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm transition-all duration-300 hover:shadow-xl hover:-translate-y-1 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex text-brand-cyan text-xs space-x-1">
                                @for($i = 0; $i < ($t->rating ?? 5); $i++)
                                    <i class="fa-solid fa-star"></i>
                                @endfor
                            </div>
                            <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-0.5 rounded-full">
                                <i class="fa-solid fa-circle-check text-[10px]"></i> Verified
                            </span>
                        </div>
                        <p class="text-slate-700 text-sm leading-relaxed italic mb-8">"{{ $t->content }}"</p>
                    </div>

                    <div class="pt-5 border-t border-slate-100 flex items-center gap-3.5">
                        <div class="size-11 rounded-2xl bg-gradient-to-tr from-brand-navy to-brand-navy-light text-white flex items-center justify-center font-bold text-sm shadow-md">
                            {{ strtoupper(substr($t->name, 0, 1)) }}
                        </div>
                        <div>
                            <h4 class="font-heading font-bold text-brand-navy text-sm">{{ $t->name }}</h4>
                            <span class="text-xs text-slate-500 font-medium">{{ $t->role }} &bull; {{ $t->location }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if(method_exists($testimonials, 'links'))
            <div class="mt-12">
                {{ $testimonials->links() }}
            </div>
        @endif
    </div>
</section>

<!-- ── SECTION 5: OWNER CASE STUDIES (DETAILED PROOFS) ── -->
<section class="py-16 sm:py-20 lg:py-24 bg-[#F8FAFD] border-t border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="max-w-3xl mb-14">
            <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                <span class="h-2 w-2 rounded-full bg-brand-cyan"></span>
                DETAILED CASE STUDIES
            </div>
            <h2 class="font-display text-3xl font-extrabold tracking-tight text-brand-navy sm:text-4xl">
                Real Numbers from Real Property Owners
            </h2>
            <p class="mt-4 text-base text-slate-600">See how Housing 365 transformed underperforming rentals into reliable, high-yield assets.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-sky-50 text-brand-cyan">Bandra West Luxury Flat</span>
                        <span class="text-xs text-slate-400 font-semibold">David K. &bull; Landlord of 3 Units</span>
                    </div>
                    <h3 class="font-display text-2xl font-bold text-brand-navy mb-3">Saved ₹1.5 Lakhs in Vacancy Loss After Previous Broker Failed</h3>
                    <p class="text-slate-600 text-sm leading-relaxed mb-6">
                        "My previous broker had my 2-bedroom Bandra West apartment listed for 45 days with zero signed offers. Housing 365 reshot the unit with HDR photography, launched syndicated ads, and placed a vetted corporate tenant with an 810 CIBIL score in just 9 days at ₹85,000/mo."
                    </p>
                </div>
                <div class="pt-6 border-t border-slate-100 grid grid-cols-3 gap-4 text-center">
                    <div>
                        <span class="text-xl font-bold text-brand-navy block">9 Days</span>
                        <span class="text-xs text-slate-500">Lease Velocity</span>
                    </div>
                    <div>
                        <span class="text-xl font-bold text-brand-cyan block">₹85,000/mo</span>
                        <span class="text-xs text-slate-500">Achieved Rent</span>
                    </div>
                    <div>
                        <span class="text-xl font-bold text-emerald-600 block">810</span>
                        <span class="text-xs text-slate-500">CIBIL Score</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700">Multi-Unit Portfolio</span>
                        <span class="text-xs text-slate-400 font-semibold">Dr. Ananya S. &bull; Bengaluru, Karnataka</span>
                    </div>
                    <h3 class="font-display text-2xl font-bold text-brand-navy mb-3">100% On-Time Rent with Zero Landlord Stress</h3>
                    <p class="text-slate-600 text-sm leading-relaxed mb-6">
                        "As a busy surgeon, I don't have time to answer broker calls or vet dozens of random classified messages. Housing 365 handles every step seamlessly. Their thorough tenant screening and legal 11-month lease execution give me absolute peace of mind."
                    </p>
                </div>
                <div class="pt-6 border-t border-slate-100 grid grid-cols-3 gap-4 text-center">
                    <div>
                        <span class="text-xl font-bold text-brand-navy block">100%</span>
                        <span class="text-xs text-slate-500">On-Time Rent</span>
                    </div>
                    <div>
                        <span class="text-xl font-bold text-brand-cyan block">14 Days</span>
                        <span class="text-xs text-slate-500">Average Lease</span>
                    </div>
                    <div>
                        <span class="text-xl font-bold text-emerald-600 block">Zero</span>
                        <span class="text-xs text-slate-500">Late Payments</span>
                    </div>
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
                    JOIN THOUSANDS OF SATISFIED OWNERS
                </div>
                <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold text-brand-navy">
                    Start Experiencing Better Rental Yields
                </h2>
                <p class="mt-4 text-base sm:text-lg text-slate-600">
                    Discover how Housing 365 eliminates leasing headaches while maximizing monthly cash flow.
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
