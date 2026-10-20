@extends('layouts.app')

@section('title', $page->meta_title ?? 'Outsourced Leasing for Property Managers & PMCs | Housing 365')
@section('meta_description', $page->meta_description ?? 'Outsource the leasing operation and keep the management contract. White-label leasing-as-a-service for PM firms with rapid 14-day lease-up, structured screening, and PMS-integrated handoff.')

@section('content')

<!-- ── BREADCRUMB ── -->
<div class="bg-white border-b border-slate-100 py-3.5 text-sm text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-cyan transition font-medium">Home</a>
        <span class="text-slate-300">/</span>
        <span class="text-brand-navy font-bold">Property Managers</span>
    </div>
</div>

<!-- ── SECTION 1: HERO SHOWCASE (LIGHT ELEGANT DESIGN) ── -->
<section class="relative overflow-hidden bg-gradient-to-b from-[#F7FAFD] via-white to-[#F0F6FC] py-16 sm:py-20 lg:py-28">
    <!-- Ambient Glow Effects -->
    <div class="pointer-events-none absolute -left-40 top-0 h-[500px] w-[500px] rounded-full opacity-40 blur-3xl" style="background: radial-gradient(circle, rgba(0,174,239,0.15) 0%, transparent 70%)" aria-hidden="true"></div>
    <div class="pointer-events-none absolute right-0 top-20 h-[600px] w-[600px] rounded-full opacity-30 blur-3xl" style="background: radial-gradient(circle, rgba(3,35,75,0.06) 0%, transparent 70%)" aria-hidden="true"></div>

    <div class="relative mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 items-center gap-12">
            <div class="lg:col-span-7">
                <!-- Eyebrow Badge -->
                <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-brand-cyan/20 bg-brand-cyan/5 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                    <span class="h-2 w-2 rounded-full bg-brand-cyan animate-pulse"></span>
                    {{ $page->hero_badge ?? 'WHITE-LABEL LEASING-AS-A-SERVICE FOR PMCS' }}
                </div>

                <!-- Headline -->
                <h1 class="font-display text-4xl font-extrabold leading-[1.12] tracking-tight text-brand-navy sm:text-5xl lg:text-6xl">
                    Outsource the Leasing. <br>
                    <span class="text-brand-cyan font-extrabold">Keep the Management Contract.</span>
                </h1>

                <!-- Subheadline -->
                <p class="mt-6 text-base sm:text-lg leading-relaxed text-slate-600 max-w-2xl">
                    {{ $page->hero_subtitle ?? 'End-to-end leasing execution engineered specifically for property management companies and asset managers. We absorb the marketing, showing, and screening overhead while you retain long-term property management fees.' }}
                </p>

                <!-- Key Benefits Checklist -->
                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm font-semibold text-slate-700">
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-circle-check text-brand-cyan"></i>
                        <span>Zero upfront retainers or fixed payroll</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-circle-check text-brand-cyan"></i>
                        <span>100% white-label or co-branded delivery</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-circle-check text-brand-cyan"></i>
                        <span>Yardi, AppFolio &amp; Buildium file handoff</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-circle-check text-brand-cyan"></i>
                        <span>Guaranteed 14-day average lease velocity</span>
                    </div>
                </div>

                <!-- CTAs -->
                <div class="mt-9 flex flex-wrap items-center gap-4">
                    <a href="{{ route('contact', ['type' => 'pmc']) }}" 
                       class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand-cyan px-8 py-4 text-base font-bold text-white shadow-lg shadow-brand-cyan/25 transition-all duration-200 hover:-translate-y-0.5 hover:bg-brand-cyan-hover">
                        <span>Schedule PMC Partnership Discovery</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>

                    <a href="#integration-model" 
                       class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-7 py-4 text-base font-bold text-brand-navy shadow-sm transition hover:border-slate-400 hover:bg-slate-50">
                        <span>How It Integrates</span>
                    </a>
                </div>
            </div>

            <!-- Right Column: Visual Card -->
            <div class="lg:col-span-5">
                <div class="relative rounded-3xl border border-slate-200/90 bg-white p-7 shadow-xl">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="size-11 rounded-2xl bg-sky-50 text-brand-cyan flex items-center justify-center text-lg">
                                <i class="fa-solid fa-handshake"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-base text-brand-navy">Partner Command Center</h3>
                                <p class="text-xs text-slate-500">Live PMS Synced Pipeline</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700">
                            <span class="size-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Active Sync
                        </span>
                    </div>

                    <div class="space-y-4">
                        <div class="p-4 rounded-2xl bg-[#F8FAFD] border border-slate-100 flex items-center justify-between">
                            <div>
                                <span class="text-xs text-slate-500 block">Current Portfolio Occupancy</span>
                                <span class="text-2xl font-bold text-brand-navy">99.2%</span>
                            </div>
                            <span class="text-xs font-bold text-emerald-600 bg-emerald-100/60 px-2.5 py-1 rounded-lg">+4.1% YoY</span>
                        </div>

                        <div class="p-4 rounded-2xl bg-[#F8FAFD] border border-slate-100 flex items-center justify-between">
                            <div>
                                <span class="text-xs text-slate-500 block">Average Days on Market</span>
                                <span class="text-2xl font-bold text-brand-cyan">11.4 Days</span>
                            </div>
                            <span class="text-xs font-bold text-slate-600">vs 34d Market Avg</span>
                        </div>

                        <div class="p-4 rounded-2xl bg-[#F8FAFD] border border-slate-100">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-slate-700">Automated Applicant Screening Dossiers</span>
                                <span class="text-xs font-bold text-brand-navy">75-pt Underwriting</span>
                            </div>
                            <div class="w-full bg-slate-200 h-2 rounded-full overflow-hidden">
                                <div class="bg-brand-cyan h-full rounded-full" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-5 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-semibold">
                        <span>Compatible with:</span>
                        <div class="flex items-center gap-3 text-slate-600 font-bold">
                            <span>Yardi</span> &bull; <span>AppFolio</span> &bull; <span>Buildium</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4-Stat Strip (Light Modern) -->
        <div class="mt-16 grid grid-cols-2 sm:grid-cols-4 gap-6 pt-10 border-t border-slate-200">
            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm text-center">
                <span class="font-display text-3xl sm:text-4xl font-extrabold text-brand-cyan">100%</span>
                <p class="text-sm font-bold text-brand-navy mt-1">Management Retention</p>
                <p class="text-xs text-slate-500 mt-0.5">You keep all monthly fees</p>
            </div>
            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm text-center">
                <span class="font-display text-3xl sm:text-4xl font-extrabold text-brand-navy">12 Days</span>
                <p class="text-sm font-bold text-brand-navy mt-1">Average Turnaround</p>
                <p class="text-xs text-slate-500 mt-0.5">Rapid tenant placement</p>
            </div>
            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm text-center">
                <span class="font-display text-3xl sm:text-4xl font-extrabold text-brand-cyan">₹0</span>
                <p class="text-sm font-bold text-brand-navy mt-1">Fixed Payroll Overhead</p>
                <p class="text-xs text-slate-500 mt-0.5">Pure variable success fee</p>
            </div>
            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm text-center">
                <span class="font-display text-3xl sm:text-4xl font-extrabold text-emerald-600">40+</span>
                <p class="text-sm font-bold text-brand-navy mt-1">Syndicated Portals</p>
                <p class="text-xs text-slate-500 mt-0.5">Maximum tenant reach</p>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 2: THE PMC BOTTLENECK (WHY IN-HOUSE LEASING DRAINS MARGINS) ── -->
<section class="bg-white py-16 sm:py-20 lg:py-24 border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="max-w-3xl mb-14">
            <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-rose-500">
                <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                THE IN-HOUSE BOTTLENECK
            </div>
            <h2 class="font-display text-3xl font-extrabold tracking-tight text-brand-navy sm:text-4xl">
                Why Self-Managing Leasing Drains Your Management Margins
            </h2>
            <p class="mt-4 text-base text-slate-600">
                Property management companies excel at property upkeep, rent collection, owner reporting, and tenant retention. But leasing is an entirely different operational animal requiring dedicated sales agents, weekend showings, and multi-channel marketing campaigns.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="rounded-3xl border border-rose-100 bg-rose-50/30 p-8 flex flex-col justify-between">
                <div>
                    <div class="size-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-xl mb-6">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <h3 class="text-xl font-bold text-brand-navy mb-3">Showing Fatigue &amp; Weekend Burnout</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Your property managers spend 60% of their working hours driving across town for no-show showings, answering repetitive classified inquiries, and rescheduling viewings instead of maintaining client buildings.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-rose-100 text-xs font-bold text-rose-700">
                    Average cost: 22 wasted hours per vacancy
                </div>
            </div>

            <div class="rounded-3xl border border-indigo-100 bg-indigo-50/30 p-8 flex flex-col justify-between">
                <div>
                    <div class="size-12 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-xl mb-6">
                        <i class="fa-solid fa-money-bill-trend-up"></i>
                    </div>
                    <h3 class="text-xl font-bold text-brand-navy mb-3">Costly Seasonal Payroll Swings</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Hiring full-time leasing agents leaves you overstaffed in slow winter months and overwhelmed during spring/summer turnover spikes. Fixed salary overhead erodes your monthly property management net profit.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-indigo-100 text-xs font-bold text-indigo-700">
                    Save ₹8,00,000+ in leasing overheads
                </div>
            </div>

            <div class="rounded-3xl border border-sky-100 bg-sky-50/30 p-8 flex flex-col justify-between">
                <div>
                    <div class="size-12 rounded-2xl bg-sky-100 text-brand-cyan flex items-center justify-center text-xl mb-6">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h3 class="text-xl font-bold text-brand-navy mb-3">Applicant Fraud &amp; Compliance Risks</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Photoshoped pay stubs, fabricated credit scores, and complex Residential Tenancies Act regulations expose your PMC to bad tenant placements and angry property owners demanding contract cancellations.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-sky-100 text-xs font-bold text-brand-cyan">
                    75-Point automated anti-fraud underwriting
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 3: THE 6-STAGE INTEGRATION MODEL ── -->
<section id="integration-model" class="py-16 sm:py-20 lg:py-24 bg-[#F8FAFD] border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="max-w-3xl mb-14">
            <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                <span class="h-2 w-2 rounded-full bg-brand-cyan"></span>
                STRUCTURED WORKFLOW
            </div>
            <h2 class="font-display text-3xl font-extrabold tracking-tight text-brand-navy sm:text-4xl">
                The 6-Stage White-Label Leasing Protocol
            </h2>
            <p class="mt-4 text-base text-slate-600">
                A seamless, institutional leasing machine operating as an invisible extension of your property management brand.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Step 1 -->
            <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-5">
                        <span class="size-10 rounded-xl bg-brand-cyan/10 text-brand-cyan font-bold text-sm flex items-center justify-center">01</span>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Step 1</span>
                    </div>
                    <h3 class="text-lg font-bold text-brand-navy mb-2">Automated Unit Intake</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Submit upcoming vacancies via portal or direct PMS webhook. We immediately pull floor plans, unit specs, target rent bands, and custom building bylaws.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 text-xs text-slate-500 font-semibold">
                    Turnaround: Less than 24 hours
                </div>
            </div>

            <!-- Step 2 -->
            <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-5">
                        <span class="size-10 rounded-xl bg-brand-cyan/10 text-brand-cyan font-bold text-sm flex items-center justify-center">02</span>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Step 2</span>
                    </div>
                    <h3 class="text-lg font-bold text-brand-navy mb-2">4K Media &amp; Virtual Tours</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Our on-demand media crew captures ultra-high-definition HDR interior photography, 3D interactive virtual tours, and drone community footage to command peak market rent.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 text-xs text-slate-500 font-semibold">
                    Turnaround: Within 48 hours of key access
                </div>
            </div>

            <!-- Step 3 -->
            <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-5">
                        <span class="size-10 rounded-xl bg-brand-cyan/10 text-brand-cyan font-bold text-sm flex items-center justify-center">03</span>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Step 3</span>
                    </div>
                    <h3 class="text-lg font-bold text-brand-navy mb-2">Omnichannel Syndication</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Your vacancy is instantly broadcasted across 40+ leading rental channels including Realtor.ca, Rentals.ca, Zillow, PadMapper, and targeted social media buyer campaigns.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 text-xs text-slate-500 font-semibold">
                    Reach: 40+ listing portals simultaneously
                </div>
            </div>

            <!-- Step 4 -->
            <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-5">
                        <span class="size-10 rounded-xl bg-brand-cyan/10 text-brand-cyan font-bold text-sm flex items-center justify-center">04</span>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Step 4</span>
                    </div>
                    <h3 class="text-lg font-bold text-brand-navy mb-2">High-Touch Showing Execution</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Licensed, background-checked showing coordinators conduct in-person guided walkthroughs 7 days a week, handling inquiries, building check-ins, and key management seamlessly.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 text-xs text-slate-500 font-semibold">
                    Availability: 7 days a week, 9 AM – 8 PM
                </div>
            </div>

            <!-- Step 5 -->
            <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-5">
                        <span class="size-10 rounded-xl bg-brand-cyan/10 text-brand-cyan font-bold text-sm flex items-center justify-center">05</span>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Step 5</span>
                    </div>
                    <h3 class="text-lg font-bold text-brand-navy mb-2">75-Point Underwriting Dossier</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Comprehensive CIBIL/Experian credit reporting, employer HR verification, landlord reference auditing, and civil court litigation registry checks.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 text-xs text-slate-500 font-semibold">
                    Approval rate: Top 5% of applicants only
                </div>
            </div>

            <!-- Step 6 -->
            <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-5">
                        <span class="size-10 rounded-xl bg-brand-cyan/10 text-brand-cyan font-bold text-sm flex items-center justify-center">06</span>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Step 6</span>
                    </div>
                    <h3 class="text-lg font-bold text-brand-navy mb-2">Digital Lease &amp; PMS Handoff</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Execution of standard provincial or state leases, certified deposit escrow collection, tenant contents insurance verification, and automated data export into your PMS.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 text-xs text-slate-500 font-semibold">
                    Handoff: Complete turnkey tenant file
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 4: PMS INTEGRATION ECOSYSTEM ── -->
<section class="bg-white py-16 sm:py-20 lg:py-24 border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 items-center gap-12">
            <div class="lg:col-span-6">
                <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                    <span class="h-2 w-2 rounded-full bg-brand-cyan"></span>
                    SOFTWARE INTEGRATIONS
                </div>
                <h2 class="font-display text-3xl font-extrabold tracking-tight text-brand-navy sm:text-4xl">
                    Works Seamlessly with Your Existing Property Management Stack
                </h2>
                <p class="mt-4 text-base text-slate-600 leading-relaxed">
                    No new logins for your property owners or accountants. Housing 365 exports fully executed tenancy contracts, tenant contact dossiers, emergency contacts, insurance certificates, and rent ledgers directly into your core management software.
                </p>

                <div class="mt-8 space-y-4">
                    <div class="flex items-start gap-3.5">
                        <div class="size-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs shrink-0 mt-0.5">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-brand-navy">Native Tenant File Export</h4>
                            <p class="text-xs sm:text-sm text-slate-500">PDF leases, government IDs, and payment receipts pre-organized for audit compliance.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3.5">
                        <div class="size-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs shrink-0 mt-0.5">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-brand-navy">Automated Rent &amp; Deposit Escrow</h4>
                            <p class="text-xs sm:text-sm text-slate-500">First and last month rent deposits transferred straight to your PMC trust account.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3.5">
                        <div class="size-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs shrink-0 mt-0.5">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-brand-navy">Real-Time Showing &amp; Lead Analytics</h4>
                            <p class="text-xs sm:text-sm text-slate-500">Share live vacancy status with building owners with one click to prove your leasing execution.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-6">
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-6 rounded-3xl bg-[#F8FAFD] border border-slate-200/80 text-center hover:shadow-md transition">
                        <span class="font-display text-2xl font-bold text-brand-navy block">AppFolio</span>
                        <span class="text-xs text-slate-500 mt-1 block">Property Manager Ready</span>
                    </div>
                    <div class="p-6 rounded-3xl bg-[#F8FAFD] border border-slate-200/80 text-center hover:shadow-md transition">
                        <span class="font-display text-2xl font-bold text-brand-navy block">Yardi</span>
                        <span class="text-xs text-slate-500 mt-1 block">Voyager &amp; Breeze API</span>
                    </div>
                    <div class="p-6 rounded-3xl bg-[#F8FAFD] border border-slate-200/80 text-center hover:shadow-md transition">
                        <span class="font-display text-2xl font-bold text-brand-navy block">Buildium</span>
                        <span class="text-xs text-slate-500 mt-1 block">Sync &amp; Export</span>
                    </div>
                    <div class="p-6 rounded-3xl bg-[#F8FAFD] border border-slate-200/80 text-center hover:shadow-md transition">
                        <span class="font-display text-2xl font-bold text-brand-navy block">RealPage</span>
                        <span class="text-xs text-slate-500 mt-1 block">Multi-Family Institutional</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 5: PROVEN PMC CASE STUDIES ── -->
<section class="py-16 sm:py-20 lg:py-24 bg-[#F8FAFD] border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="max-w-3xl mb-14">
            <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                <span class="h-2 w-2 rounded-full bg-brand-cyan"></span>
                PROVEN RESULTS
            </div>
            <h2 class="font-display text-3xl font-extrabold tracking-tight text-brand-navy sm:text-4xl">
                Real PMC Portfolio Case Studies
            </h2>
            <p class="mt-4 text-base text-slate-600">
                Discover how leading property management firms scale their doors without hiring additional leasing personnel.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Case Study 1 -->
            <div class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-sky-50 text-brand-cyan">Multi-Family PMC Partner</span>
                        <span class="text-xs text-slate-400 font-semibold">Bengaluru &amp; Pune, India</span>
                    </div>
                    <h3 class="font-display text-2xl font-bold text-brand-navy mb-3">120-Unit Rental Portfolio Leased in 28 Days</h3>
                    <p class="text-slate-600 text-sm sm:text-base leading-relaxed mb-6">
                        A mid-sized management company acquired a new 120-suite multi-family asset with 14% vacant units. With an impending deadline from building owners, Housing 365 deployed 3 dedicated showing coordinators, launched hyper-local social campaigns, and achieved 100% full occupancy in under a month.
                    </p>
                </div>
                <div class="pt-6 border-t border-slate-100 grid grid-cols-3 gap-4 text-center">
                    <div>
                        <span class="text-xl font-bold text-brand-navy block">0.8%</span>
                        <span class="text-xs text-slate-500">Vacancy Now</span>
                    </div>
                    <div>
                        <span class="text-xl font-bold text-brand-cyan block">14 Days</span>
                        <span class="text-xs text-slate-500">Avg Placement</span>
                    </div>
                    <div>
                        <span class="text-xl font-bold text-emerald-600 block">100%</span>
                        <span class="text-xs text-slate-500">PMC Fee Retained</span>
                    </div>
                </div>
            </div>

            <!-- Case Study 2 -->
            <div class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700">Scattered Single-Family PMC</span>
                        <span class="text-xs text-slate-400 font-semibold">Delhi NCR, India</span>
                    </div>
                    <h3 class="font-display text-2xl font-bold text-brand-navy mb-3">Eliminating ₹10 Lakhs in Leasing Payroll Liability</h3>
                    <p class="text-slate-600 text-sm sm:text-base leading-relaxed mb-6">
                        Managing 65 single-family homes and townhouses scattered across 5 municipalities required endless driving. By delegating all leasing and photography to Housing 365, the PMC eliminated full-time leasing agent payroll and improved landlord retention to 99.4%.
                    </p>
                </div>
                <div class="pt-6 border-t border-slate-100 grid grid-cols-3 gap-4 text-center">
                    <div>
                        <span class="text-xl font-bold text-brand-navy block">₹12,50,000</span>
                        <span class="text-xs text-slate-500">Annual Payroll Saved</span>
                    </div>
                    <div>
                        <span class="text-xl font-bold text-brand-cyan block">750+</span>
                        <span class="text-xs text-slate-500">Avg Credit Score</span>
                    </div>
                    <div>
                        <span class="text-xl font-bold text-emerald-600 block">Zero</span>
                        <span class="text-xs text-slate-500">Evictions to Date</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 6: PMC FREQUENTLY ASKED QUESTIONS (FULL WIDTH 2-COLUMN) ── -->
<section class="bg-white py-16 sm:py-20 lg:py-24 border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12 pb-6 border-b border-slate-200">
            <div>
                <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                    <span class="size-2 rounded-full bg-brand-cyan animate-pulse"></span>
                    PARTNERSHIP CLARITY &bull; PMC FAQ
                </div>
                <h2 class="font-display text-3xl sm:text-4xl text-brand-navy font-extrabold tracking-tight">
                    Frequently Asked Questions for Property Managers
                </h2>
                <p class="mt-2 text-base text-slate-600 max-w-2xl">
                    Everything you need to know about partnering with Housing 365, white-label execution, and legal non-solicitation guarantees.
                </p>
            </div>
            <div class="shrink-0">
                <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl bg-white border border-slate-300 text-brand-navy font-bold text-xs uppercase tracking-wider shadow-sm hover:border-brand-cyan hover:text-brand-cyan hover:bg-slate-50 transition">
                    <span>Partner Desk Inquiry</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start w-full">
            <div class="rounded-3xl border border-slate-200/90 bg-[#F8FAFD] p-6 sm:p-8 hover:border-brand-cyan/40 hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <h3 class="font-heading font-bold text-brand-navy text-lg mb-3 flex items-start gap-3">
                        <span class="size-8 rounded-xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-sm shrink-0 font-bold mt-0.5">Q</span>
                        <span>Will Housing 365 ever attempt to solicit my property management clients?</span>
                    </h3>
                    <p class="text-slate-600 text-sm sm:text-base leading-relaxed pl-11">
                        Never. Our partnership agreements contain a binding, legally enforceable non-solicitation clause. We specialize strictly in leasing operations, tenant marketing, and screening execution. You retain 100% ownership of your client relationship and ongoing property management revenue.
                    </p>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200/90 bg-[#F8FAFD] p-6 sm:p-8 hover:border-brand-cyan/40 hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <h3 class="font-heading font-bold text-brand-navy text-lg mb-3 flex items-start gap-3">
                        <span class="size-8 rounded-xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-sm shrink-0 font-bold mt-0.5">Q</span>
                        <span>How does white-label branding work during showings and communications?</span>
                    </h3>
                    <p class="text-slate-600 text-sm sm:text-base leading-relaxed pl-11">
                        We can operate completely under your brand umbrella or as your official leasing execution partner. Signage, digital applications, email notifications, and lease paperwork will feature your company logo and styling.
                    </p>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200/90 bg-[#F8FAFD] p-6 sm:p-8 hover:border-brand-cyan/40 hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <h3 class="font-heading font-bold text-brand-navy text-lg mb-3 flex items-start gap-3">
                        <span class="size-8 rounded-xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-sm shrink-0 font-bold mt-0.5">Q</span>
                        <span>Who signs the lease agreement and holds the security/rent deposit?</span>
                    </h3>
                    <p class="text-slate-600 text-sm sm:text-base leading-relaxed pl-11">
                        The lease is executed between the property owner (or your PMC as authorized agent) and the approved tenant. All deposit funds are deposited directly into your designated trust account or transferred electronically via certified wire/EFT.
                    </p>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200/90 bg-[#F8FAFD] p-6 sm:p-8 hover:border-brand-cyan/40 hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <h3 class="font-heading font-bold text-brand-navy text-lg mb-3 flex items-start gap-3">
                        <span class="size-8 rounded-xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-sm shrink-0 font-bold mt-0.5">Q</span>
                        <span>What is the fee structure for property management companies?</span>
                    </h3>
                    <p class="text-slate-600 text-sm sm:text-base leading-relaxed pl-11">
                        We offer preferred wholesale partner rates with zero upfront retainers. You pay only a one-time success fee when a fully vetted tenant signs the lease and pays the initial deposit. Many PMCs mark up our leasing fee or split the leasing commission to create an additional profit center.
                    </p>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200/90 bg-[#F8FAFD] p-6 sm:p-8 hover:border-brand-cyan/40 hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <h3 class="font-heading font-bold text-brand-navy text-lg mb-3 flex items-start gap-3">
                        <span class="size-8 rounded-xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-sm shrink-0 font-bold mt-0.5">Q</span>
                        <span>How quickly can you start leasing our units?</span>
                    </h3>
                    <p class="text-slate-600 text-sm sm:text-base leading-relaxed pl-11">
                        Once we establish your partner account and receive keys or lockbox codes, our media team shoots 4K photography within 48 hours, and your listing goes live across 40+ syndicated portals immediately.
                    </p>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200/90 bg-[#F8FAFD] p-6 sm:p-8 hover:border-brand-cyan/40 hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <h3 class="font-heading font-bold text-brand-navy text-lg mb-3 flex items-start gap-3">
                        <span class="size-8 rounded-xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-sm shrink-0 font-bold mt-0.5">Q</span>
                        <span>What happens if an approved tenant breaks their lease early?</span>
                    </h3>
                    <p class="text-slate-600 text-sm sm:text-base leading-relaxed pl-11">
                        All our white-label placements are backed by our 12-month tenant replacement warranty. If a resident breaks the lease or leaves before completing the initial lease term, we re-market and place a replacement resident at zero fee.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 7: CALL TO ACTION BANNER (LIGHT CRISP DESIGN) ── -->
<section class="py-16 sm:py-20 lg:py-24 bg-gradient-to-r from-sky-50 via-white to-cyan-50">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="relative overflow-hidden rounded-3xl border border-brand-cyan/20 bg-white p-8 sm:p-14 lg:p-16 shadow-xl flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="max-w-2xl">
                <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                    <span class="size-2 rounded-full bg-brand-cyan animate-pulse"></span>
                    PARTNER WITH HOUSING 365
                </div>
                <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold text-brand-navy">
                    Ready to Streamline Your Leasing Operations?
                </h2>
                <p class="mt-4 text-base sm:text-lg text-slate-600">
                    Schedule a confidential 20-minute discovery call to explore custom white-label pricing and portfolio onboarding.
                </p>
            </div>
            <div class="shrink-0">
                <a href="{{ route('contact', ['type' => 'pmc']) }}" 
                   class="inline-flex items-center gap-2 rounded-xl bg-brand-cyan px-8 py-4 text-base font-bold text-white shadow-lg shadow-brand-cyan/25 transition-all duration-200 hover:-translate-y-0.5 hover:bg-brand-cyan-hover">
                    <span>Schedule Partner Discovery</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
