@extends('layouts.app')

@section('title', 'Property Management & Rental Services | Housing 365')
@section('meta_description', 'Full-service residential property management, tenant placement, corporate relocation housing, and institutional lease-up services across Canada and the US.')

@section('content')

<!-- ── BREADCRUMB ── -->
<div class="bg-white border-b border-slate-100 py-3.5 text-sm text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-cyan transition font-medium">Home</a>
        <span class="text-slate-300">/</span>
        <span class="text-brand-navy font-bold">Services</span>
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
                FULL-SERVICE RESIDENTIAL LEASING &amp; MANAGEMENT
            </div>

            <!-- Headline -->
            <h1 class="font-display text-4xl font-extrabold leading-[1.12] tracking-tight text-brand-navy sm:text-5xl md:text-6xl">
                Comprehensive Property Management &amp; <br>
                <span class="text-brand-cyan font-extrabold">Leasing Execution.</span>
            </h1>

            <!-- Subheadline -->
            <p class="mt-6 text-base sm:text-lg leading-relaxed text-slate-600">
                From individual condominium investors to institutional multi-family portfolios, we deliver end-to-end leasing operations with zero upfront costs, guaranteed on-time rent, and legal compliance.
            </p>

            <!-- Value Highlights -->
            <div class="mt-8 flex flex-wrap justify-center items-center gap-6 text-sm font-semibold text-slate-700">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-brand-cyan"></i>
                    <span>₹0 Upfront Retainer</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-brand-cyan"></i>
                    <span>14-Day Average Lease Velocity</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-brand-cyan"></i>
                    <span>100% Verified Tenants &amp; E-Stamping</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-brand-cyan"></i>
                    <span>24/7 Digital Owner Dashboard</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 2: SERVICES DIRECTORY ── -->
<section class="py-16 sm:py-20 lg:py-24 bg-[#F8FAFD]">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="max-w-3xl mb-14">
            <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                <span class="h-2 w-2 rounded-full bg-brand-cyan"></span>
                OUR SERVICE MATRIX
            </div>
            <h2 class="font-display text-3xl font-extrabold tracking-tight text-brand-navy sm:text-4xl">
                Tailored Solutions for Every Property Strategy
            </h2>
            <p class="mt-4 text-base text-slate-600">
                Select from our complete catalog of specialized leasing, underwriting, protection, and operational management services.
            </p>
        </div>

        <div class="space-y-12">
            @foreach($services as $idx => $srv)
                <div class="bg-white rounded-3xl p-8 sm:p-12 border border-slate-200/80 shadow-sm flex flex-col lg:flex-row items-center gap-10 transition-all duration-300 hover:shadow-xl {{ $idx % 2 == 1 ? 'lg:flex-row-reverse' : '' }}">
                    
                    <!-- Details Column -->
                    <div class="w-full lg:w-3/5">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-brand-cyan/10 text-brand-cyan">
                                Solution #0{{ $idx + 1 }}
                            </span>
                            @if($srv->pricing_info)
                                <span class="text-xs font-bold text-slate-500">
                                    <i class="fa-solid fa-tag text-brand-cyan mr-1"></i> {{ $srv->pricing_info }}
                                </span>
                            @endif
                        </div>

                        <h2 class="font-display text-2xl sm:text-3xl font-bold text-brand-navy mb-4 tracking-tight">
                            {{ $srv->title }}
                        </h2>

                        <p class="text-slate-600 text-sm sm:text-base leading-relaxed mb-6">
                            {{ $srv->full_description ?? $srv->short_description }}
                        </p>

                        @if($srv->features && is_array($srv->features))
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 mb-8">
                                @foreach($srv->features as $feat)
                                    <div class="flex items-start text-sm text-slate-700 font-medium gap-2.5">
                                        <div class="flex size-5 shrink-0 items-center justify-center rounded-full bg-brand-cyan/10 text-brand-cyan mt-0.5">
                                            <i class="fa-solid fa-check text-[10px]"></i>
                                        </div>
                                        <span>{{ $feat }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="flex flex-wrap items-center gap-4">
                            <a href="{{ route('contact', ['service' => $srv->slug ?? '']) }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand-cyan px-7 py-3.5 text-xs font-bold uppercase tracking-wider text-white shadow-md shadow-brand-cyan/20 hover:bg-brand-cyan-hover transition">
                                <span>Inquire For This Service</span>
                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>
                            <a href="{{ route('pricing') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-6 py-3.5 text-xs font-bold text-brand-navy hover:bg-slate-50 transition">
                                View Pricing Plans
                            </a>
                        </div>
                    </div>

                    <!-- Assurance Card Column (Clean Light Design) -->
                    <div class="w-full lg:w-2/5 bg-gradient-to-br from-[#F8FAFD] to-slate-100/80 rounded-3xl p-8 sm:p-10 border border-slate-200/80 flex flex-col justify-center items-center text-center">
                        <div class="w-16 h-16 rounded-2xl bg-brand-cyan text-white flex items-center justify-center text-2xl mb-5 shadow-lg shadow-brand-cyan/25">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <h3 class="font-heading font-bold text-lg text-brand-navy mb-2">Housing 365 Managed Assurance</h3>
                        <p class="text-slate-500 text-sm leading-relaxed max-w-sm mb-6">
                            Every contract is backed by rigorous operational standards, licensed trades partnerships, and full legal tenancy board compliance.
                        </p>
                        <div class="w-full pt-4 border-t border-slate-200/80 flex items-center justify-around text-xs font-semibold text-slate-600">
                            <span class="flex items-center gap-1.5"><i class="fa-solid fa-check text-brand-cyan"></i> 100% Vetted</span>
                            <span class="flex items-center gap-1.5"><i class="fa-solid fa-check text-brand-cyan"></i> 24/7 Portal</span>
                            <span class="flex items-center gap-1.5"><i class="fa-solid fa-check text-brand-cyan"></i> Zero Retainer</span>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ── SECTION 3: 14-DAY LEASING TIMELINE ── -->
<section class="py-16 sm:py-20 lg:py-24 bg-white border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="max-w-3xl mb-14">
            <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                <span class="h-2 w-2 rounded-full bg-brand-cyan"></span>
                RAPID EXECUTION
            </div>
            <h2 class="font-display text-3xl font-extrabold tracking-tight text-brand-navy sm:text-4xl">
                The 14-Day Placement Velocity Timeline
            </h2>
            <p class="mt-4 text-base text-slate-600">How we turn vacant properties into cash-flowing rental assets in 14 days or less.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="p-7 rounded-3xl bg-[#F8FAFD] border border-slate-200/80">
                <span class="text-xs font-bold uppercase tracking-wider text-brand-cyan">Days 1 – 2</span>
                <h3 class="text-lg font-bold text-brand-navy mt-2 mb-2">Intake &amp; 4K Media</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Comparative market analysis, optimal rent pricing recommendation, lockbox placement, and professional HDR photography &amp; 3D tour capture.
                </p>
            </div>

            <div class="p-7 rounded-3xl bg-[#F8FAFD] border border-slate-200/80">
                <span class="text-xs font-bold uppercase tracking-wider text-brand-cyan">Days 3 – 5</span>
                <h3 class="text-lg font-bold text-brand-navy mt-2 mb-2">Syndication &amp; Tours</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Live broadcast across 40+ listing portals. Showing coordinators handle high-volume inquiries and host in-person prospective tenant tours.
                </p>
            </div>

            <div class="p-7 rounded-3xl bg-[#F8FAFD] border border-slate-200/80">
                <span class="text-xs font-bold uppercase tracking-wider text-brand-cyan">Days 6 – 10</span>
                <h3 class="text-lg font-bold text-brand-navy mt-2 mb-2">75-Point Underwriting</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Digital application intake, Equifax credit evaluation, automated bank payroll verification, previous landlord audits, and owner review.
                </p>
            </div>

            <div class="p-7 rounded-3xl bg-[#F8FAFD] border border-slate-200/80">
                <span class="text-xs font-bold uppercase tracking-wider text-brand-cyan">Days 11 – 14</span>
                <h3 class="text-lg font-bold text-brand-navy mt-2 mb-2">Lease Sign &amp; Key Handoff</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Electronic execution of official provincial standard leases, certified deposit escrow collection, tenant insurance verification, and move-in inspection.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 4: FREQUENTLY ASKED QUESTIONS (FULL WIDTH 2-COLUMN) ── -->
@if(count($faqs) > 0)
<section class="py-16 sm:py-20 lg:py-24 bg-[#F8FAFD] border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12 pb-6 border-b border-slate-200">
            <div>
                <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                    <span class="size-2 rounded-full bg-brand-cyan animate-pulse"></span>
                    CLARITY &amp; TRANSPARENCY &bull; SERVICE FAQS
                </div>
                <h2 class="font-display text-3xl sm:text-4xl text-brand-navy font-extrabold tracking-tight">
                    Service Inquiries &amp; Frequently Asked Questions
                </h2>
                <p class="mt-2 text-base text-slate-600 max-w-2xl">
                    Answers about our leasing guarantee, multi-channel marketing, screening methodology, and digital owner management.
                </p>
            </div>
            <div class="shrink-0">
                <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl bg-white border border-slate-300 text-brand-navy font-bold text-xs uppercase tracking-wider shadow-sm hover:border-brand-cyan hover:text-brand-cyan hover:bg-slate-50 transition">
                    <span>Ask Our Leasing Team</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start w-full">
            @foreach($faqs as $f)
                <div class="rounded-3xl border border-slate-200/90 bg-white p-6 sm:p-8 hover:border-brand-cyan/40 hover:shadow-md transition flex flex-col justify-between">
                    <div>
                        <h3 class="font-heading font-bold text-brand-navy text-lg mb-3 flex items-start gap-3">
                            <span class="size-8 rounded-xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-sm shrink-0 font-bold mt-0.5">Q</span>
                            <span>{{ $f->question }}</span>
                        </h3>
                        <p class="text-slate-600 text-sm sm:text-base leading-relaxed pl-11">{{ $f->answer }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ── SECTION 5: CALL TO ACTION BANNER (LIGHT MODERN) ── -->
<section class="py-16 sm:py-20 lg:py-24 bg-gradient-to-r from-sky-50 via-white to-cyan-50">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="relative overflow-hidden rounded-3xl border border-brand-cyan/20 bg-white p-8 sm:p-14 lg:p-16 shadow-xl flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="max-w-2xl">
                <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                    <span class="size-2 rounded-full bg-brand-cyan animate-pulse"></span>
                    CUSTOM LEASING PROPOSALS
                </div>
                <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold text-brand-navy">
                    Ready to Maximize Your Rental Portfolio?
                </h2>
                <p class="mt-4 text-base sm:text-lg text-slate-600">
                    Speak with a licensed Housing 365 property specialist for a free rental yield assessment today.
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
