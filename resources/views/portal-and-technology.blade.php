@extends('layouts.app')

@section('title', $page->meta_title ?? 'Platform & Technology | Cloud Leasing Intelligence | Housing 365')
@section('meta_description', $page->meta_description ?? 'Experience the Housing 365 owner command center. 24/7 real-time showing updates, tenant application tracking, digital lease agreements, and transparent financial reporting.')

@section('content')

<!-- ── BREADCRUMB ── -->
<div class="bg-white border-b border-slate-100 py-3.5 text-sm text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-cyan transition font-medium">Home</a>
        <span class="text-slate-300">/</span>
        <span class="text-brand-navy font-bold">Portal &amp; Technology</span>
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
                {{ $page->hero_badge ?? 'PROPRIETARY LEASING INTELLIGENCE' }}
            </div>

            <!-- Headline -->
            <h1 class="font-display text-4xl font-extrabold leading-[1.12] tracking-tight text-brand-navy sm:text-5xl lg:text-6xl">
                Real-Time Transparency. <br>
                <span class="text-brand-cyan font-extrabold">Zero Black-Box Management.</span>
            </h1>

            <!-- Subheadline -->
            <p class="mt-6 text-base sm:text-lg leading-relaxed text-slate-600 max-w-2xl mx-auto">
                {{ $page->hero_subtitle ?? 'No more chasing down real estate agents for email updates. Watch showings booked in real time, inspect applicant credit dossiers, and countersign legal leases directly from your smartphone or desktop.' }}
            </p>

            <!-- CTAs -->
            <div class="mt-8 flex flex-wrap justify-center items-center gap-4">
                <a href="{{ route('contact') }}" 
                   class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand-cyan px-8 py-4 text-base font-bold text-white shadow-lg shadow-brand-cyan/25 transition-all duration-200 hover:-translate-y-0.5 hover:bg-brand-cyan-hover">
                    <span>Request Live Portal Demo</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>

                <a href="#core-capabilities" 
                   class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-7 py-4 text-base font-bold text-brand-navy shadow-sm transition hover:border-slate-400 hover:bg-slate-50">
                    <span>Explore Platform Capabilities</span>
                </a>
            </div>
        </div>

        <!-- ── LIGHT-MODE BROWSER MOCKUP PREVIEW ── -->
        <div class="mt-14 max-w-5xl mx-auto rounded-3xl border border-slate-200/90 bg-white shadow-2xl overflow-hidden">
            <!-- Browser Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-slate-50">
                <div class="flex items-center gap-2">
                    <span class="size-3 rounded-full bg-rose-400"></span>
                    <span class="size-3 rounded-full bg-amber-400"></span>
                    <span class="size-3 rounded-full bg-emerald-400"></span>
                </div>
                <div class="px-6 py-1.5 rounded-lg bg-white border border-slate-200 text-xs font-mono text-slate-500 shadow-inner flex items-center gap-2">
                    <i class="fa-solid fa-lock text-emerald-500 text-[10px]"></i>
                    <span>https://app.housing365.com/owner/dashboard</span>
                </div>
                <div class="flex items-center gap-2 text-slate-500 text-xs font-semibold">
                    <i class="fa-solid fa-shield-halved text-brand-cyan"></i>
                    <span>256-Bit Encrypted</span>
                </div>
            </div>

            <!-- Tabbed Portal View Content (Clean Light UI) -->
            <div class="p-6 sm:p-8 bg-white">
                <!-- Navigation Tabs -->
                <div class="flex flex-wrap gap-2 border-b border-slate-100 pb-4 mb-6">
                    <button type="button" onclick="switchPortalTab(0)" id="portal-tab-0" class="portal-tab-btn active px-4 py-2 rounded-xl text-xs font-bold bg-brand-cyan text-white shadow">
                        Owner Overview
                    </button>
                    <button type="button" onclick="switchPortalTab(1)" id="portal-tab-1" class="portal-tab-btn px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:text-brand-navy hover:bg-slate-100 transition">
                        Live Showings &amp; Offers
                    </button>
                    <button type="button" onclick="switchPortalTab(2)" id="portal-tab-2" class="portal-tab-btn px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:text-brand-navy hover:bg-slate-100 transition">
                        Syndication &amp; Reach
                    </button>
                    <button type="button" onclick="switchPortalTab(3)" id="portal-tab-3" class="portal-tab-btn px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:text-brand-navy hover:bg-slate-100 transition">
                        Payouts &amp; Tax Ledgers
                    </button>
                </div>

                <!-- Tab 0: Owner Overview -->
                <div id="portal-screen-0" class="portal-screen-content">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="p-5 rounded-2xl bg-[#F8FAFD] border border-slate-200/80">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Total Monthly Rent Collected</span>
                            <span class="font-display text-2xl sm:text-3xl font-extrabold text-emerald-600">$18,450.00</span>
                            <span class="text-xs text-emerald-600 font-bold block mt-1"><i class="fa-solid fa-arrow-trend-up mr-1"></i> 100% on-time collection</span>
                        </div>
                        <div class="p-5 rounded-2xl bg-[#F8FAFD] border border-slate-200/80">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Portfolio Occupancy</span>
                            <span class="font-display text-2xl sm:text-3xl font-extrabold text-brand-navy">100%</span>
                            <span class="text-xs text-slate-500 font-semibold block mt-1">6 Active Units &bull; 0 Vacancies</span>
                        </div>
                        <div class="p-5 rounded-2xl bg-[#F8FAFD] border border-slate-200/80">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Upcoming Tour Appointments</span>
                            <span class="font-display text-2xl sm:text-3xl font-extrabold text-brand-cyan">4 Booked</span>
                            <span class="text-xs text-brand-cyan font-bold block mt-1">Next: Unit 1402 &bull; Today 3:00 PM</span>
                        </div>
                    </div>

                    <div class="p-5 rounded-2xl bg-[#F8FAFD] border border-slate-200/80">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-4">Real-Time Portfolio Activity Stream</h4>
                        <div class="space-y-3 text-sm">
                            <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                                <div class="flex items-center gap-3">
                                    <span class="size-2 rounded-full bg-emerald-500"></span>
                                    <span class="font-medium text-slate-800">Deposit confirmed for Unit 2204 ($2,750.00)</span>
                                </div>
                                <span class="text-slate-400 text-xs font-mono">10 mins ago</span>
                            </div>
                            <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                                <div class="flex items-center gap-3">
                                    <span class="size-2 rounded-full bg-brand-cyan"></span>
                                    <span class="font-medium text-slate-800">Equifax 780 score applicant dossier ready for review</span>
                                </div>
                                <span class="text-slate-400 text-xs font-mono">1 hour ago</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="size-2 rounded-full bg-blue-500"></span>
                                    <span class="font-medium text-slate-800">MLS listing refreshed across Realtor.ca and Rentals.ca</span>
                                </div>
                                <span class="text-slate-400 text-xs font-mono">4 hours ago</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 1: Showings & Offers -->
                <div id="portal-screen-1" class="portal-screen-content hidden">
                    <div class="space-y-3 text-sm">
                        <div class="p-4 rounded-xl bg-[#F8FAFD] border border-slate-200 flex items-center justify-between">
                            <div>
                                <span class="font-bold text-brand-navy block text-base">Unit 1402 — 2 Bed Luxury Downtown Condo</span>
                                <span class="text-slate-500 text-xs">In-Person Showing &bull; Lead: Michael R. &bull; Verified ID &bull; Pre-Screened Credit</span>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">Confirmed</span>
                        </div>
                        <div class="p-4 rounded-xl bg-[#F8FAFD] border border-slate-200 flex items-center justify-between">
                            <div>
                                <span class="font-bold text-brand-navy block text-base">Unit 805 — Modern Yorkville Loft</span>
                                <span class="text-slate-500 text-xs">Formal Tenancy Offer: $2,450/mo &bull; Move-in: 1st of Next Month</span>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-sky-100 text-brand-cyan text-xs font-bold">Under Review</span>
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Syndication & Reach -->
                <div id="portal-screen-2" class="portal-screen-content hidden">
                    <div class="p-8 rounded-2xl bg-[#F8FAFD] border border-slate-200 text-center">
                        <span class="font-display text-4xl sm:text-5xl font-extrabold text-brand-cyan">148</span>
                        <p class="text-sm font-bold text-brand-navy mt-2">Active Prospective Tenant Inquiries This Week</p>
                        <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto">Broadcasting across Realtor.ca, Rentals.ca, Zillow, PadMapper, and targeted social ad audiences.</p>
                    </div>
                </div>

                <!-- Tab 3: Payouts & Tax Ledgers -->
                <div id="portal-screen-3" class="portal-screen-content hidden">
                    <div class="p-6 rounded-2xl bg-[#F8FAFD] border border-slate-200">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-2">Automated Payout Schedule</span>
                        <p class="text-sm text-slate-700 leading-relaxed mb-4">
                            Pre-authorized ACH / EFT direct deposits sent directly to your institutional bank account on the 1st of each month with automated year-end tax reconciliations and itemized deductible repair logs.
                        </p>
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-bold">
                            <i class="fa-solid fa-circle-check"></i> Next Direct Deposit: 1st of Next Month ($18,450.00)
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 2: 6 CORE PROPTECH PILLARS ── -->
<section id="core-capabilities" class="py-16 sm:py-20 lg:py-24 bg-white border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="max-w-3xl mb-14">
            <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                <span class="h-2 w-2 rounded-full bg-brand-cyan"></span>
                CORE INFRASTRUCTURE
            </div>
            <h2 class="font-display text-3xl font-extrabold tracking-tight text-brand-navy sm:text-4xl">
                Proprietary Features Designed for Landlord Yield
            </h2>
            <p class="mt-4 text-base text-slate-600">
                Explore the technological capabilities that make Housing 365 three times faster than traditional real estate brokerages.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="rounded-3xl border border-slate-200/80 bg-[#F8FAFD] p-8 transition hover:shadow-lg">
                <div class="size-12 rounded-2xl bg-sky-50 text-brand-cyan flex items-center justify-center text-xl mb-6">
                    <i class="fa-solid fa-fingerprint"></i>
                </div>
                <h3 class="text-xl font-bold text-brand-navy mb-3">75-Point Automated Underwriting</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Direct API integration with Equifax, TransUnion, Plaid bank payroll verification, and landlord dispute court records to prevent rental defaults before they happen.
                </p>
            </div>

            <div class="rounded-3xl border border-slate-200/80 bg-[#F8FAFD] p-8 transition hover:shadow-lg">
                <div class="size-12 rounded-2xl bg-sky-50 text-brand-cyan flex items-center justify-center text-xl mb-6">
                    <i class="fa-solid fa-key"></i>
                </div>
                <h3 class="text-xl font-bold text-brand-navy mb-3">Smart Lockbox &amp; Showing Engine</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Automated calendar self-scheduling with two-factor biometric applicant verification. Renters tour on their schedule; landlords view full entry timestamps.
                </p>
            </div>

            <div class="rounded-3xl border border-slate-200/80 bg-[#F8FAFD] p-8 transition hover:shadow-lg">
                <div class="size-12 rounded-2xl bg-sky-50 text-brand-cyan flex items-center justify-center text-xl mb-6">
                    <i class="fa-solid fa-tower-broadcast"></i>
                </div>
                <h3 class="text-xl font-bold text-brand-navy mb-3">Omnichannel Syndication Router</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    One-click broadcast across 40+ portals including MLS Realtor.ca, Zillow, Rentals.ca, PadMapper, and targeted Facebook Marketplace ads.
                </p>
            </div>

            <div class="rounded-3xl border border-slate-200/80 bg-[#F8FAFD] p-8 transition hover:shadow-lg">
                <div class="size-12 rounded-2xl bg-sky-50 text-brand-cyan flex items-center justify-center text-xl mb-6">
                    <i class="fa-solid fa-file-signature"></i>
                </div>
                <h3 class="text-xl font-bold text-brand-navy mb-3">Digital Legal Lease Generation</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Automated creation of Ontario Standard Leases (OSL) and state-specific statutory agreements with customized condominium rules, pet clauses, and e-signatures.
                </p>
            </div>

            <div class="rounded-3xl border border-slate-200/80 bg-[#F8FAFD] p-8 transition hover:shadow-lg">
                <div class="size-12 rounded-2xl bg-sky-50 text-brand-cyan flex items-center justify-center text-xl mb-6">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <h3 class="text-xl font-bold text-brand-navy mb-3">Automated Financial &amp; Tax Ledgers</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Export CPA-ready financial summaries, direct EFT payouts, and end-of-year tax statements (T5013 / Schedule 88) with one single click.
                </p>
            </div>

            <div class="rounded-3xl border border-slate-200/80 bg-[#F8FAFD] p-8 transition hover:shadow-lg">
                <div class="size-12 rounded-2xl bg-sky-50 text-brand-cyan flex items-center justify-center text-xl mb-6">
                    <i class="fa-solid fa-mobile-screen-button"></i>
                </div>
                <h3 class="text-xl font-bold text-brand-navy mb-3">Resident Mobile App</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Tenants pay rent via credit/debit or auto-debit, report maintenance issues with photos, and build credit with on-time rent reporting to Equifax.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 3: SIDE-BY-SIDE COMPARISON TABLE ── -->
<section class="py-16 sm:py-20 lg:py-24 bg-[#F8FAFD] border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="max-w-3xl mb-14">
            <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                <span class="h-2 w-2 rounded-full bg-brand-cyan"></span>
                THE HOUSING 365 ADVANTAGE
            </div>
            <h2 class="font-display text-3xl font-extrabold tracking-tight text-brand-navy sm:text-4xl">
                Traditional Real Estate Agents vs Housing 365 Platform
            </h2>
            <p class="mt-4 text-base text-slate-600">
                See why institutional landlords and condo owners are upgrading from casual realtors to purpose-built leasing technology.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50 text-xs font-bold uppercase tracking-wider text-slate-500">
                        <th class="p-6">Feature / Capability</th>
                        <th class="p-6 text-slate-400">Traditional Realtor</th>
                        <th class="p-6 text-brand-cyan bg-sky-50/50">Housing 365 Platform</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    <tr>
                        <td class="p-6 font-bold text-brand-navy">Owner Live Dashboard</td>
                        <td class="p-6 text-rose-500"><i class="fa-solid fa-xmark mr-2"></i> No (Sporadic texts/emails)</td>
                        <td class="p-6 text-emerald-600 font-bold bg-sky-50/30"><i class="fa-solid fa-check mr-2"></i> 24/7 Real-Time Access</td>
                    </tr>
                    <tr>
                        <td class="p-6 font-bold text-brand-navy">Average Days to Lease</td>
                        <td class="p-6 text-slate-500">30 – 45 Days</td>
                        <td class="p-6 text-brand-cyan font-bold bg-sky-50/30">12 – 14 Days</td>
                    </tr>
                    <tr>
                        <td class="p-6 font-bold text-brand-navy">Applicant Verification</td>
                        <td class="p-6 text-rose-500"><i class="fa-solid fa-xmark mr-2"></i> Easily faked paper PDFs</td>
                        <td class="p-6 text-emerald-600 font-bold bg-sky-50/30"><i class="fa-solid fa-check mr-2"></i> 75-Point Automated Plaid &amp; Equifax API</td>
                    </tr>
                    <tr>
                        <td class="p-6 font-bold text-brand-navy">Listing Syndication Reach</td>
                        <td class="p-6 text-slate-500">MLS only (1 portal)</td>
                        <td class="p-6 text-emerald-600 font-bold bg-sky-50/30"><i class="fa-solid fa-check mr-2"></i> 40+ Consumer &amp; Social Portals</td>
                    </tr>
                    <tr>
                        <td class="p-6 font-bold text-brand-navy">Rental Agreement &amp; E-Stamping</td>
                        <td class="p-6 text-rose-500"><i class="fa-solid fa-xmark mr-2"></i> Generic stationer paper draft</td>
                        <td class="p-6 text-emerald-600 font-bold bg-sky-50/30"><i class="fa-solid fa-check mr-2"></i> Legal 11-Month Draft + State E-Stamp (Stamp duty at actuals)</td>
                    </tr>
                    <tr>
                        <td class="p-6 font-bold text-brand-navy">Recurring Retainers / Broker Commissions</td>
                        <td class="p-6 text-slate-500">1 to 2 months rent broker commission</td>
                        <td class="p-6 text-emerald-600 font-bold bg-sky-50/30"><i class="fa-solid fa-check mr-2"></i> ₹0 Monthly Retainer, Performance-Only</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- ── SECTION 4: SECURITY & COMPLIANCE ── -->
<section class="py-16 sm:py-20 bg-white border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 text-center">
        <div class="max-w-2xl mx-auto mb-10">
            <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                <i class="fa-solid fa-lock text-xs"></i>
                BANK-GRADE SECURITY
            </div>
            <h2 class="font-display text-3xl font-extrabold tracking-tight text-brand-navy">
                Enterprise Data Privacy &amp; Legal Compliance
            </h2>
            <p class="mt-3 text-base text-slate-600">Your financial documents, applicant identities, and lease contracts are protected under strict security controls.</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto">
            <div class="p-6 rounded-2xl bg-[#F8FAFD] border border-slate-200/80">
                <i class="fa-solid fa-shield-halved text-2xl text-brand-cyan mb-2"></i>
                <h4 class="font-bold text-sm text-brand-navy">SOC 2 Type II</h4>
                <p class="text-xs text-slate-500 mt-1">Certified cloud hosting</p>
            </div>
            <div class="p-6 rounded-2xl bg-[#F8FAFD] border border-slate-200/80">
                <i class="fa-solid fa-key text-2xl text-brand-cyan mb-2"></i>
                <h4 class="font-bold text-sm text-brand-navy">256-Bit AES</h4>
                <p class="text-xs text-slate-500 mt-1">End-to-end encryption</p>
            </div>
            <div class="p-6 rounded-2xl bg-[#F8FAFD] border border-slate-200/80">
                <i class="fa-solid fa-scale-balanced text-2xl text-brand-cyan mb-2"></i>
                <h4 class="font-bold text-sm text-brand-navy">PIPEDA &amp; CCPA</h4>
                <p class="text-xs text-slate-500 mt-1">Privacy compliance</p>
            </div>
            <div class="p-6 rounded-2xl bg-[#F8FAFD] border border-slate-200/80">
                <i class="fa-solid fa-cloud-arrow-up text-2xl text-brand-cyan mb-2"></i>
                <h4 class="font-bold text-sm text-brand-navy">Daily Backups</h4>
                <p class="text-xs text-slate-500 mt-1">Immutable data history</p>
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
                    EXPERIENCE THE PLATFORM
                </div>
                <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold text-brand-navy">
                    Ready to Upgrade Your Property Management?
                </h2>
                <p class="mt-4 text-base sm:text-lg text-slate-600">
                    Connect with our technical team today to activate your owner dashboard and list your properties with zero upfront fees.
                </p>
            </div>
            <div class="shrink-0">
                <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 rounded-xl bg-brand-cyan px-8 py-4 text-base font-bold text-white shadow-lg shadow-brand-cyan/25 transition-all duration-200 hover:-translate-y-0.5 hover:bg-brand-cyan-hover">
                    <span>Talk to an Expert</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    function switchPortalTab(idx) {
        document.querySelectorAll('.portal-screen-content').forEach((screen, i) => {
            if (i === idx) {
                screen.classList.remove('hidden');
            } else {
                screen.classList.add('hidden');
            }
        });

        document.querySelectorAll('.portal-tab-btn').forEach((btn, i) => {
            if (i === idx) {
                btn.className = 'portal-tab-btn active px-4 py-2 rounded-xl text-xs font-bold bg-brand-cyan text-white shadow';
            } else {
                btn.className = 'portal-tab-btn px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:text-brand-navy hover:bg-slate-100 transition';
            }
        });
    }
</script>
@endpush

@endsection
