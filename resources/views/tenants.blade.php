@extends('layouts.app')

@section('title', $page->meta_title ?? 'Verified Rental Homes & Flats Across India | Tenants Hub | Housing365')
@section('meta_description', $page->meta_description ?? 'Browse verified rental apartments, flats, independent floors, and PG accommodations across Mumbai, Bengaluru, Delhi NCR, Hyderabad, Pune, and all Indian cities. 100% verified listings and direct owner connect.')

@section('content')

<!-- ── BREADCRUMB ── -->
<div class="bg-white border-b border-slate-100 py-3.5 text-sm text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-cyan transition font-medium">Home</a>
        <span class="text-slate-300">/</span>
        <span class="text-brand-navy font-bold">For Tenants</span>
    </div>
</div>

<!-- ── SECTION 1: HERO SHOWCASE (LIGHT ELEGANT DESIGN) ── -->
<section class="relative overflow-hidden bg-gradient-to-b from-[#F7FAFD] via-white to-[#F0F6FC] py-16 sm:py-20 lg:py-28">
    <div class="pointer-events-none absolute -left-40 top-0 h-[500px] w-[500px] rounded-full opacity-40 blur-3xl" style="background: radial-gradient(circle, rgba(0,174,239,0.18) 0%, transparent 70%)" aria-hidden="true"></div>

    <div class="relative mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 items-center gap-12">
            <div class="lg:col-span-6">
                <!-- Eyebrow Badge -->
                <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-brand-cyan/20 bg-brand-cyan/5 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                    <span class="h-2 w-2 rounded-full bg-brand-cyan animate-pulse"></span>
                    {{ $page->hero_badge ?? 'VERIFIED RENTAL HOMES & APARTMENTS ACROSS INDIA' }}
                </div>

                <!-- Headline -->
                <h1 class="font-display text-4xl font-extrabold leading-[1.12] tracking-tight text-brand-navy sm:text-5xl md:text-6xl">
                    Find a Rental Home <br>
                    <span class="text-brand-cyan font-extrabold">You'll Truly Love.</span>
                </h1>

                <!-- Subheadline -->
                <p class="mt-6 text-base sm:text-lg leading-relaxed text-slate-600 max-w-xl">
                    {{ $page->hero_subtitle ?? 'Explore curated, verified rental properties across Mumbai, Bengaluru, Delhi NCR, and 50+ Indian cities. We eliminate fake listings, broker spam, and deposit confusion with 100% verified owners and legal e-stamped agreements.' }}
                </p>

                <!-- Search Bar -->
                <div class="mt-8 max-w-xl">
                    <form action="{{ route('properties.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2 p-2 bg-white rounded-2xl border border-slate-200 shadow-lg">
                        <div class="relative flex-1 w-full">
                            <i class="fa-solid fa-location-dot absolute left-4 top-1/2 -translate-y-1/2 text-brand-cyan text-sm"></i>
                            <input type="text" name="city" placeholder="Enter city, neighbourhood or postal code..." 
                                   class="w-full pl-11 pr-4 py-3.5 text-sm text-brand-navy placeholder-slate-400 outline-none rounded-xl">
                        </div>
                        <button type="submit" class="w-full sm:w-auto flex items-center justify-center gap-2 rounded-xl bg-brand-cyan px-7 py-3.5 text-sm font-bold text-white shadow-md shadow-brand-cyan/25 transition hover:bg-brand-cyan-hover shrink-0">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            <span>Find Rentals</span>
                        </button>
                    </form>
                </div>

                <!-- 3 Pillars -->
                <div class="mt-10 grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="flex items-start gap-3">
                        <div class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-sky-50 text-brand-cyan ring-1 ring-brand-cyan/20">
                            <i class="fa-solid fa-shield-halved text-base"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-brand-navy">100% Verified</p>
                            <p class="text-xs text-slate-500">No scams or phantom listings</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-sky-50 text-brand-cyan ring-1 ring-brand-cyan/20">
                            <i class="fa-solid fa-file-circle-check text-base"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-brand-navy">Digital Applications</p>
                            <p class="text-xs text-slate-500">Decisions within 24-48 hours</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-sky-50 text-brand-cyan ring-1 ring-brand-cyan/20">
                            <i class="fa-solid fa-headset text-base"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-brand-navy">Direct Support</p>
                            <p class="text-xs text-slate-500">Assistance from tour to move-in</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column Visual -->
            <div class="lg:col-span-6">
                <div class="relative aspect-[16/11] w-full overflow-hidden rounded-3xl shadow-2xl border border-slate-200/80">
                    <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=1200&auto=format&fit=crop&q=80" 
                         alt="Happy tenants moving into their new rental apartment" 
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent"></div>
                    
                    <div class="absolute bottom-6 left-6 right-6 bg-white/95 backdrop-blur-md p-5 rounded-2xl border border-white/80 shadow-lg flex items-center justify-between">
                        <div>
                            <span class="text-sm font-bold text-brand-navy block">Ready to tour your next home?</span>
                            <span class="text-xs text-slate-500">Book instant in-person showings or 4K virtual tours.</span>
                        </div>
                        <a href="{{ route('properties.index') }}" class="px-5 py-2.5 bg-brand-cyan hover:bg-brand-cyan-hover text-white text-xs font-bold uppercase tracking-wider rounded-xl transition shadow">
                            Browse Units
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 2: THE 4-STEP RENTAL JOURNEY ── -->
<section class="bg-white py-16 sm:py-20 lg:py-24 border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="max-w-3xl mb-14">
            <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                <span class="h-2 w-2 rounded-full bg-brand-cyan"></span>
                THE RENTAL JOURNEY
            </div>
            <h2 class="font-display text-3xl font-extrabold tracking-tight text-brand-navy sm:text-4xl">
                How Renting with Housing 365 Works
            </h2>
            <p class="mt-4 text-base text-slate-600">
                We've redesigned the rental process from the ground up to make finding and securing your next home fast, transparent, and respectful.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="rounded-3xl border border-slate-200/80 bg-[#F8FAFD] p-7 flex flex-col justify-between hover:shadow-md transition">
                <div>
                    <span class="font-display text-3xl font-extrabold text-brand-cyan">01</span>
                    <h3 class="text-lg font-bold text-brand-navy mt-4 mb-2">Browse Verified Homes</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Filter by bedrooms, price, pet policies, and neighborhood amenities with authentic high-resolution photos and floor plans.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-200 text-xs text-slate-500 font-semibold">
                    100% verified listings
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200/80 bg-[#F8FAFD] p-7 flex flex-col justify-between hover:shadow-md transition">
                <div>
                    <span class="font-display text-3xl font-extrabold text-brand-cyan">02</span>
                    <h3 class="text-lg font-bold text-brand-navy mt-4 mb-2">Schedule a Tour</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Choose a viewing time that matches your schedule. Tour with our licensed showing coordinator or complete a live 4K video walkthrough.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-200 text-xs text-slate-500 font-semibold">
                    Available 7 days a week
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200/80 bg-[#F8FAFD] p-7 flex flex-col justify-between hover:shadow-md transition">
                <div>
                    <span class="font-display text-3xl font-extrabold text-brand-cyan">03</span>
                    <h3 class="text-lg font-bold text-brand-navy mt-4 mb-2">Apply Online in 10 Mins</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Upload ID and proof of income through our secure portal. Receive a transparent application status update within 24 to 48 hours.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-200 text-xs text-slate-500 font-semibold">
                    Fast 24-48h turnaround
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200/80 bg-[#F8FAFD] p-7 flex flex-col justify-between hover:shadow-md transition">
                <div>
                    <span class="font-display text-3xl font-extrabold text-brand-cyan">04</span>
                    <h3 class="text-lg font-bold text-brand-navy mt-4 mb-2">Sign &amp; Move In</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Execute official government standard lease agreements digitally, send rent deposits securely, collect keys, and settle in.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-200 text-xs text-slate-500 font-semibold">
                    Digital keyless handoff
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 3: APPLICATION READINESS CHECKLIST ── -->
<section class="py-16 sm:py-20 lg:py-24 bg-[#F8FAFD] border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-6">
                <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                    <span class="h-2 w-2 rounded-full bg-brand-cyan"></span>
                    BE APPLICATION-READY
                </div>
                <h2 class="font-display text-3xl font-extrabold tracking-tight text-brand-navy sm:text-4xl">
                    Documents Needed to Secure Your Rental Quickly
                </h2>
                <p class="mt-4 text-base text-slate-600 leading-relaxed">
                    Great rental suites in competitive markets lease quickly. Having your tenant file pre-assembled guarantees you are first in line when submitting your application.
                </p>

                <div class="mt-8 space-y-4">
                    <div class="p-4 rounded-2xl bg-white border border-slate-200/80 flex items-start gap-4">
                        <div class="size-8 rounded-xl bg-sky-50 text-brand-cyan flex items-center justify-center font-bold text-sm shrink-0">1</div>
                        <div>
                            <h4 class="font-bold text-sm text-brand-navy">Government-Issued Photo ID</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Aadhaar Card, PAN Card, Passport, Voter ID, or Indian Driving License.</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-white border border-slate-200/80 flex items-start gap-4">
                        <div class="size-8 rounded-xl bg-sky-50 text-brand-cyan flex items-center justify-center font-bold text-sm shrink-0">2</div>
                        <div>
                            <h4 class="font-bold text-sm text-brand-navy">Proof of Income &amp; Employment</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Recent 2-3 salary slips, formal employment offer/appointment letter, or Form 16 / ITR for self-employed individuals.</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-white border border-slate-200/80 flex items-start gap-4">
                        <div class="size-8 rounded-xl bg-sky-50 text-brand-cyan flex items-center justify-center font-bold text-sm shrink-0">3</div>
                        <div>
                            <h4 class="font-bold text-sm text-brand-navy">Full Equifax or TransUnion Credit Report</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Complete credit report showing credit score and payment history (or authorize digital screening in our portal).</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-white border border-slate-200/80 flex items-start gap-4">
                        <div class="size-8 rounded-xl bg-sky-50 text-brand-cyan flex items-center justify-center font-bold text-sm shrink-0">4</div>
                        <div>
                            <h4 class="font-bold text-sm text-brand-navy">Landlord References</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Contact details of current or previous landlords confirming on-time rent payment and tenancy history.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Newcomer & Student Support -->
            <div class="lg:col-span-6">
                <div class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200/80 shadow-xl">
                    <div class="size-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl mb-6">
                        <i class="fa-solid fa-map-location-dot"></i>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">PAN-INDIA RELOCATION SUPPORT</span>
                    <h3 class="text-2xl font-bold text-brand-navy mt-1 mb-4">Relocating Inter-City or Moving for a New Job?</h3>
                    <p class="text-slate-600 text-sm sm:text-base leading-relaxed mb-6">
                        Moving to Bengaluru, Mumbai, Gurgaon, Pune, or Hyderabad from another state? You shouldn't face predatory broker demands or advance security deposit fraud. Housing365 helps tech professionals, students, and relocating families find verified homes with transparent digital agreements.
                    </p>

                    <div class="space-y-3 pt-4 border-t border-slate-100 text-sm font-medium text-slate-700">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-circle-check text-brand-cyan"></i>
                            <span>Accepted corporate offer letters &amp; company ID verification</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-circle-check text-brand-cyan"></i>
                            <span>Biometric digital police verification assistance</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-circle-check text-brand-cyan"></i>
                            <span>Legally binding e-stamped 11-month rent agreements delivered online</span>
                        </div>
                    </div>

                    <div class="mt-8">
                        <a href="{{ url('/services/tenant-guarantor') }}" class="inline-flex items-center gap-2 text-brand-cyan font-bold text-sm hover:underline">
                            <span>Learn more about Tenant Guarantor Solutions</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 4: TENANT PERKS & RIGHTS ── -->
<section class="bg-white py-16 sm:py-20 lg:py-24 border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="max-w-3xl mb-14">
            <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                <span class="h-2 w-2 rounded-full bg-brand-cyan"></span>
                RESIDENT ADVANTAGES
            </div>
            <h2 class="font-display text-3xl font-extrabold tracking-tight text-brand-navy sm:text-4xl">
                Renting with Confidence &amp; Transparent Legal Protections
            </h2>
            <p class="mt-4 text-base text-slate-600">
                We believe tenants deserve fair treatment, clear agreements, and modern technology.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="rounded-3xl border border-slate-200/80 bg-[#F8FAFD] p-8">
                <div class="size-12 rounded-2xl bg-sky-50 text-brand-cyan flex items-center justify-center text-xl mb-6">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
                <h3 class="text-xl font-bold text-brand-navy mb-3">Credit-Building On-Time Rent</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Every on-time rent payment you make through our resident portal is reported to Equifax, helping you build positive credit history towards your future home ownership goals.
                </p>
            </div>

            <div class="rounded-3xl border border-slate-200/80 bg-[#F8FAFD] p-8">
                <div class="size-12 rounded-2xl bg-sky-50 text-brand-cyan flex items-center justify-center text-xl mb-6">
                    <i class="fa-solid fa-scale-balanced"></i>
                </div>
                <h3 class="text-xl font-bold text-brand-navy mb-3">Standard Legal Leases</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    All leases use legally vetted 11-month rent agreements with official state e-stamping and biometric police verification assistance. No illegal clauses, unfair penalties, or surprise fee addenda.
                </p>
            </div>

            <div class="rounded-3xl border border-slate-200/80 bg-[#F8FAFD] p-8">
                <div class="size-12 rounded-2xl bg-sky-50 text-brand-cyan flex items-center justify-center text-xl mb-6">
                    <i class="fa-solid fa-wrench"></i>
                </div>
                <h3 class="text-xl font-bold text-brand-navy mb-3">24/7 Emergency Maintenance</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Submit repair requests in 30 seconds via your phone with photo uploads. Emergency plumbing or electrical failures are dispatched immediately to verified local technicians.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 5: TENANT FREQUENTLY ASKED QUESTIONS (FULL WIDTH 2-COLUMN) ── -->
<section class="bg-[#F8FAFD] py-16 sm:py-20 lg:py-24 border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12 pb-6 border-b border-slate-200">
            <div>
                <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                    <span class="size-2 rounded-full bg-brand-cyan animate-pulse"></span>
                    TENANT ADVISORY &bull; RENTER FAQ
                </div>
                <h2 class="font-display text-3xl sm:text-4xl text-brand-navy font-extrabold tracking-tight">
                    Renter Frequently Asked Questions
                </h2>
                <p class="mt-2 text-base text-slate-600 max-w-2xl">
                    Get clear answers to all your rental, deposit, documentation verification, and move-in questions.
                </p>
            </div>
            <div class="shrink-0">
                <a href="{{ route('properties.index') }}" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl bg-white border border-slate-300 text-brand-navy font-bold text-xs uppercase tracking-wider shadow-sm hover:border-brand-cyan hover:text-brand-cyan hover:bg-slate-50 transition">
                    <span>Explore Available Units</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start w-full">
            <div class="rounded-3xl border border-slate-200/90 bg-white p-6 sm:p-8 hover:border-brand-cyan/40 hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <h3 class="font-heading font-bold text-brand-navy text-lg mb-3 flex items-start gap-3">
                        <span class="size-8 rounded-xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-sm shrink-0 font-bold mt-0.5">Q</span>
                        <span>Do you charge tenants any application fees to apply?</span>
                    </h3>
                    <p class="text-slate-600 text-sm sm:text-base leading-relaxed pl-11">
                        No. In accordance with fair housing guidelines, Housing365 never charges prospective tenants application fees or viewing fees. Exploring and contacting owners across our rental properties is 100% free.
                    </p>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200/90 bg-white p-6 sm:p-8 hover:border-brand-cyan/40 hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <h3 class="font-heading font-bold text-brand-navy text-lg mb-3 flex items-start gap-3">
                        <span class="size-8 rounded-xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-sm shrink-0 font-bold mt-0.5">Q</span>
                        <span>What rental deposits are legally required when I get approved?</span>
                    </h3>
                    <p class="text-slate-600 text-sm sm:text-base leading-relaxed pl-11">
                        Across Indian states, standard security deposits are clearly specified in the registered e-stamped agreement and settled directly with the verified owner. We enforce transparent terms with no hidden lock-ins or arbitrary deductions.
                    </p>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200/90 bg-white p-6 sm:p-8 hover:border-brand-cyan/40 hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <h3 class="font-heading font-bold text-brand-navy text-lg mb-3 flex items-start gap-3">
                        <span class="size-8 rounded-xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-sm shrink-0 font-bold mt-0.5">Q</span>
                        <span>Can I bring my pet? Are pets allowed?</span>
                    </h3>
                    <p class="text-slate-600 text-sm sm:text-base leading-relaxed pl-11">
                        Under standard Indian tenancy agreements, pet permissions depend on landlord approval and resident welfare association (RWA) / society guidelines. Check the property listing specs or ask our leasing coordinator for society pet bylaws.
                    </p>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200/90 bg-white p-6 sm:p-8 hover:border-brand-cyan/40 hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <h3 class="font-heading font-bold text-brand-navy text-lg mb-3 flex items-start gap-3">
                        <span class="size-8 rounded-xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-sm shrink-0 font-bold mt-0.5">Q</span>
                        <span>Is tenant contents &amp; liability insurance recommended?</span>
                    </h3>
                    <p class="text-slate-600 text-sm sm:text-base leading-relaxed pl-11">
                        Yes. While optional in some agreements, residents are strongly encouraged to maintain renter insurance (typically ₹25 Lakhs to ₹50 Lakhs personal contents/liability coverage). Renter insurance protects your personal electronics and belongings against fire or water damage and usually costs only ₹299 to ₹499 per month.
                    </p>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200/90 bg-white p-6 sm:p-8 hover:border-brand-cyan/40 hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <h3 class="font-heading font-bold text-brand-navy text-lg mb-3 flex items-start gap-3">
                        <span class="size-8 rounded-xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-sm shrink-0 font-bold mt-0.5">Q</span>
                        <span>How quickly will I know if my application was accepted?</span>
                    </h3>
                    <p class="text-slate-600 text-sm sm:text-base leading-relaxed pl-11">
                        Most completed applications receive a formal decision within 24 to 48 business hours once all supporting income documents and landlord references are confirmed.
                    </p>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200/90 bg-white p-6 sm:p-8 hover:border-brand-cyan/40 hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <h3 class="font-heading font-bold text-brand-navy text-lg mb-3 flex items-start gap-3">
                        <span class="size-8 rounded-xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-sm shrink-0 font-bold mt-0.5">Q</span>
                        <span>What if I am relocating from another city or starting a new job?</span>
                    </h3>
                    <p class="text-slate-600 text-sm sm:text-base leading-relaxed pl-11">
                        We welcome professionals and students relocating across India! You can easily qualify by providing your company employment/offer letter, verified bank statements, college student ID, or official KYC documents (Aadhaar / PAN).
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
                    START YOUR RENTAL SEARCH
                </div>
                <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold text-brand-navy">
                    Ready to Find Your Next Home?
                </h2>
                <p class="mt-4 text-base sm:text-lg text-slate-600">
                    Explore available rental condominiums, townhomes, and suites with immediate showing availability.
                </p>
            </div>
            <div class="shrink-0">
                <a href="{{ route('properties.index') }}" 
                   class="inline-flex items-center gap-2 rounded-xl bg-brand-cyan px-8 py-4 text-base font-bold text-white shadow-lg shadow-brand-cyan/25 transition-all duration-200 hover:-translate-y-0.5 hover:bg-brand-cyan-hover">
                    <span>Browse All Rentals</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
