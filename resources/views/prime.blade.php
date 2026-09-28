@extends('layouts.app')

@section('title', 'Housing365 Prime Membership | Direct Owner Contacts & Zero Brokerage')
@section('meta_description', 'Join Housing365 Prime to unlock verified direct owner phone numbers, personal relationship manager assistance, and save ₹50,000+ in broker fees across Jaipur, Mumbai, Bengaluru, and Delhi NCR.')

@section('content')

<!-- ── HERO SECTION: PRIME MEMBERSHIP ── -->
<section class="relative overflow-hidden bg-gradient-to-b from-[#021630] via-[#03234B] to-[#0B1D3A] text-white pt-16 pb-20 sm:pt-20 sm:pb-28">
    <!-- Ambient Gold & Cyan Glows -->
    <div class="pointer-events-none absolute -left-40 top-0 h-[600px] w-[600px] rounded-full opacity-25 blur-3xl" style="background: radial-gradient(circle, rgba(245,158,11,0.35) 0%, transparent 70%)" aria-hidden="true"></div>
    <div class="pointer-events-none absolute right-0 top-20 h-[600px] w-[600px] rounded-full opacity-20 blur-3xl" style="background: radial-gradient(circle, rgba(0,174,239,0.3) 0%, transparent 70%)" aria-hidden="true"></div>

    <div class="relative mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 text-center">
        <!-- Eyebrow Badge -->
        <div class="inline-flex items-center gap-2 rounded-full border border-amber-400/40 bg-amber-400/10 px-4 py-1.5 text-xs font-extrabold uppercase tracking-wider text-amber-300 shadow-lg shadow-amber-500/10 mb-6 backdrop-blur-md">
            <i class="fa-solid fa-crown text-amber-400"></i>
            <span>HOUSING 365 PRIME &bull; ZERO BROKERAGE CLUB</span>
        </div>

        <!-- Heading -->
        <h1 class="font-display text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-[1.15] max-w-4xl mx-auto">
            Connect Directly with Property Owners. <br>
            <span class="bg-gradient-to-r from-amber-300 via-yellow-200 to-amber-400 bg-clip-text text-transparent">Save ₹50,000+ in Brokerage.</span>
        </h1>

        <!-- Subheadline -->
        <p class="mt-5 text-base sm:text-lg text-slate-300 max-w-2xl mx-auto leading-relaxed">
            Unlock verified direct owner phone numbers, get a dedicated Relationship Manager to shortlist and schedule site visits, and secure your dream home with zero middleman hassle.
        </p>

        <!-- Toggle Pills: Tenant vs Buyer -->
        <div class="mt-8 inline-flex p-1.5 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15">
            <button type="button" id="tabTenantBtn" onclick="switchPrimeTab('tenant')" class="px-6 py-2.5 rounded-xl text-xs sm:text-sm font-extrabold uppercase tracking-wider transition-all duration-200 bg-gradient-to-r from-amber-500 to-amber-400 text-slate-950 shadow-md">
                <i class="fa-solid fa-key mr-1.5"></i> I Want to Rent (Tenant Prime)
            </button>
            <button type="button" id="tabBuyerBtn" onclick="switchPrimeTab('buyer')" class="px-6 py-2.5 rounded-xl text-xs sm:text-sm font-extrabold uppercase tracking-wider transition-all duration-200 text-white/80 hover:text-white">
                <i class="fa-solid fa-house-chimney mr-1.5"></i> I Want to Buy (Buyer Prime)
            </button>
        </div>

        <!-- 4 Live Stats Strip -->
        <div class="mt-14 max-w-5xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
            <div class="p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm">
                <span class="font-display text-2xl sm:text-3xl font-extrabold text-amber-400">100%</span>
                <span class="text-xs text-slate-300 font-semibold block mt-1">Direct Owner Properties</span>
            </div>
            <div class="p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm">
                <span class="font-display text-2xl sm:text-3xl font-extrabold text-emerald-400">₹4.2+ Cr</span>
                <span class="text-xs text-slate-300 font-semibold block mt-1">Brokerage Saved</span>
            </div>
            <div class="p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm">
                <span class="font-display text-2xl sm:text-3xl font-extrabold text-brand-cyan">12 Days</span>
                <span class="text-xs text-slate-300 font-semibold block mt-1">Average Time to Close</span>
            </div>
            <div class="p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm">
                <span class="font-display text-2xl sm:text-3xl font-extrabold text-amber-400">4.9 / 5★</span>
                <span class="text-xs text-slate-300 font-semibold block mt-1">Member Satisfaction</span>
            </div>
        </div>
    </div>
</section>

<!-- ── PRIME PACKAGES SECTION (MagicBricks Inspired) ── -->
<section class="py-16 sm:py-24 bg-[#F8FAFD]" id="packages">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        
        <div class="max-w-3xl mx-auto text-center mb-14">
            <span class="text-xs font-extrabold uppercase tracking-widest text-brand-cyan">TRANSPARENT MEMBERSHIP PLANS</span>
            <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold text-brand-navy mt-2">
                Choose the Right Prime Plan for You
            </h2>
            <p class="text-slate-600 text-sm sm:text-base mt-3 leading-relaxed">
                Pay a small one-time fee to unlock direct owner phone numbers and get full assisted support. Zero commission forever.
            </p>
        </div>

        <!-- Pricing Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto items-stretch">
            
            <!-- Plan 1: Prime Basic -->
            <div class="bg-white rounded-3xl p-8 border border-slate-200/90 shadow-sm flex flex-col justify-between transition-all duration-300 hover:shadow-xl">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-slate-100 text-slate-700">
                            Self Search
                        </span>
                        <span class="text-xs font-bold text-slate-400">Validity: 45 Days</span>
                    </div>

                    <h3 class="font-display text-2xl font-bold text-brand-navy">Prime Basic</h3>
                    <p class="text-xs text-slate-500 mt-1">Ideal for renters &amp; buyers who prefer browsing and contacting owners directly.</p>

                    <div class="mt-6 mb-6 pb-6 border-b border-slate-100">
                        <div class="flex items-baseline gap-2">
                            <span class="font-display text-4xl font-extrabold text-brand-navy">₹999</span>
                            <span class="text-base text-slate-400 line-through">₹1,999</span>
                            <span class="text-xs font-bold uppercase text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">50% OFF</span>
                        </div>
                        <span class="text-[11px] text-slate-400 block mt-1">+ GST &bull; One-Time Payment</span>
                    </div>

                    <!-- Feature List -->
                    <ul class="space-y-3.5 text-xs text-slate-700">
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-sm mt-0.5 shrink-0"></i>
                            <span>Contact up to <strong>25 Verified Property Owners</strong></span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-sm mt-0.5 shrink-0"></i>
                            <span><strong>Zero Brokerage Guarantee</strong> (Direct Deals)</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-sm mt-0.5 shrink-0"></i>
                            <span>Instant Phone Number Unlocks &amp; WhatsApp Chat</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-sm mt-0.5 shrink-0"></i>
                            <span>Instant WhatsApp Alerts for new locality matches</span>
                        </li>
                        <li class="flex items-start gap-2.5 text-slate-400">
                            <i class="fa-solid fa-circle-xmark text-slate-300 text-sm mt-0.5 shrink-0"></i>
                            <span>Personal Relationship Manager (RM)</span>
                        </li>
                        <li class="flex items-start gap-2.5 text-slate-400">
                            <i class="fa-solid fa-circle-xmark text-slate-300 text-sm mt-0.5 shrink-0"></i>
                            <span>Free Legal Rental Agreement &amp; e-Stamping</span>
                        </li>
                    </ul>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-100">
                    <button type="button" onclick="openPrimeModal('Prime Basic', '₹999', 25)" class="w-full py-3.5 px-4 rounded-2xl bg-brand-navy hover:bg-brand-navy-hover text-white text-xs font-bold uppercase tracking-wider transition shadow-sm">
                        Get Prime Basic &rarr;
                    </button>
                </div>
            </div>

            <!-- Plan 2: Prime Pro (Most Popular / Assisted Search) -->
            <div class="relative bg-white rounded-3xl p-8 border-2 border-amber-400 shadow-2xl flex flex-col justify-between scale-[1.02] transition-all duration-300">
                <!-- Highlight Pill -->
                <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full bg-gradient-to-r from-amber-500 to-yellow-500 text-slate-950 font-extrabold text-[11px] uppercase tracking-wider shadow-md flex items-center gap-1.5">
                    <i class="fa-solid fa-crown text-xs"></i>
                    <span>MOST POPULAR &bull; ASSISTED SEARCH</span>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-4 mt-1">
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-amber-50 text-amber-800 border border-amber-200">
                            Dedicated RM
                        </span>
                        <span class="text-xs font-bold text-amber-700">Validity: 90 Days</span>
                    </div>

                    <h3 class="font-display text-2xl font-bold text-brand-navy">Prime Pro</h3>
                    <p class="text-xs text-slate-500 mt-1">Best value for busy professionals who want an expert to shortlist and call owners for them.</p>

                    <div class="mt-6 mb-6 pb-6 border-b border-slate-100">
                        <div class="flex items-baseline gap-2">
                            <span class="font-display text-4xl font-extrabold text-brand-navy">₹1,999</span>
                            <span class="text-base text-slate-400 line-through">₹3,999</span>
                            <span class="text-xs font-bold uppercase text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">50% OFF</span>
                        </div>
                        <span class="text-[11px] text-slate-400 block mt-1">+ GST &bull; Dedicated Expert Support</span>
                    </div>

                    <!-- Feature List -->
                    <ul class="space-y-3.5 text-xs text-slate-700">
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-amber-500 text-sm mt-0.5 shrink-0"></i>
                            <span>Contact up to <strong>50 Verified Property Owners</strong></span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-amber-500 text-sm mt-0.5 shrink-0"></i>
                            <span><strong>Dedicated Relationship Manager (RM)</strong> assigned</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-amber-500 text-sm mt-0.5 shrink-0"></i>
                            <span>RM calls owners, checks restrictions &amp; schedules visits</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-amber-500 text-sm mt-0.5 shrink-0"></i>
                            <span>Handpicked property shortlists delivered on WhatsApp</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-amber-500 text-sm mt-0.5 shrink-0"></i>
                            <span>Free Digital Registered Rental Agreement Format</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-amber-500 text-sm mt-0.5 shrink-0"></i>
                            <span>Priority WhatsApp &amp; On-Call Assistance</span>
                        </li>
                    </ul>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-100">
                    <button type="button" onclick="openPrimeModal('Prime Pro', '₹1,999', 50)" class="w-full py-4 px-4 rounded-2xl bg-gradient-to-r from-amber-500 via-amber-400 to-yellow-500 hover:from-amber-600 hover:to-yellow-600 text-slate-950 text-xs font-extrabold uppercase tracking-wider transition shadow-lg shadow-amber-500/25 hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-crown text-sm"></i>
                        <span>Get Prime Pro Now &rarr;</span>
                    </button>
                </div>
            </div>

            <!-- Plan 3: Prime VIP (Concierge) -->
            <div class="bg-white rounded-3xl p-8 border border-slate-200/90 shadow-sm flex flex-col justify-between transition-all duration-300 hover:shadow-xl">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-purple-50 text-purple-700 border border-purple-200">
                            VIP Concierge
                        </span>
                        <span class="text-xs font-bold text-slate-400">Validity: 180 Days</span>
                    </div>

                    <h3 class="font-display text-2xl font-bold text-brand-navy">Prime VIP</h3>
                    <p class="text-xs text-slate-500 mt-1">Complete end-to-end white glove assistance from home search to move-in day.</p>

                    <div class="mt-6 mb-6 pb-6 border-b border-slate-100">
                        <div class="flex items-baseline gap-2">
                            <span class="font-display text-4xl font-extrabold text-brand-navy">₹3,999</span>
                            <span class="text-base text-slate-400 line-through">₹7,999</span>
                            <span class="text-xs font-bold uppercase text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">50% OFF</span>
                        </div>
                        <span class="text-[11px] text-slate-400 block mt-1">+ GST &bull; Complete Peace of Mind</span>
                    </div>

                    <!-- Feature List -->
                    <ul class="space-y-3.5 text-xs text-slate-700">
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-purple-600 text-sm mt-0.5 shrink-0"></i>
                            <span><strong>Unlimited Verified Owner Contacts</strong></span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-purple-600 text-sm mt-0.5 shrink-0"></i>
                            <span>Senior Relationship Manager assigned</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-purple-600 text-sm mt-0.5 shrink-0"></i>
                            <span>Rent &amp; Price Negotiation Support with Landlord</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-purple-600 text-sm mt-0.5 shrink-0"></i>
                            <span><strong>Free Legal e-Stamp &amp; Tenancy Agreement</strong> delivered</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-purple-600 text-sm mt-0.5 shrink-0"></i>
                            <span>Police Verification &amp; Society NOC Assistance</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-purple-600 text-sm mt-0.5 shrink-0"></i>
                            <span>Move-in Day Concierge &amp; 20% Packers/Movers Discount</span>
                        </li>
                    </ul>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-100">
                    <button type="button" onclick="openPrimeModal('Prime VIP', '₹3,999', 'Unlimited')" class="w-full py-3.5 px-4 rounded-2xl bg-brand-navy hover:bg-brand-navy-hover text-white text-xs font-bold uppercase tracking-wider transition shadow-sm">
                        Get Prime VIP &rarr;
                    </button>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- ── WHY JOIN PRIME: THE ZERO-BROKER ADVANTAGE ── -->
<section class="py-16 sm:py-24 bg-white border-y border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="max-w-3xl mb-14">
            <span class="text-xs font-extrabold uppercase tracking-widest text-brand-cyan">BENEFITS &amp; SAVINGS</span>
            <h2 class="font-display text-3xl sm:text-4xl font-extrabold text-brand-navy mt-2">
                Why Thousands of Tenants &amp; Buyers Choose Prime
            </h2>
            <p class="text-slate-600 text-sm sm:text-base mt-3 leading-relaxed">
                Traditional brokers charge 15 to 30 days of rent or a 2% purchase commission. Housing365 Prime gives you full control and direct access to genuine owners.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="p-8 rounded-3xl bg-[#F8FAFD] border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="size-12 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl mb-6">
                        <i class="fa-solid fa-phone-volume"></i>
                    </div>
                    <h3 class="font-bold text-lg text-brand-navy mb-2">100% Direct Owner Contacts</h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Say goodbye to fake broker listings and duplicate classifieds. Every Prime listing is verified with direct landlord mobile numbers.
                    </p>
                </div>
                <span class="mt-6 text-xs font-bold text-amber-600">Zero Middlemen</span>
            </div>

            <div class="p-8 rounded-3xl bg-[#F8FAFD] border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="size-12 rounded-2xl bg-brand-cyan/15 text-brand-cyan flex items-center justify-center text-xl mb-6">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                    <h3 class="font-bold text-lg text-brand-navy mb-2">Assisted Search by RM</h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Don't have time to call dozens of properties? Your dedicated Relationship Manager handles scheduling, enquiries, and shortlisting.
                    </p>
                </div>
                <span class="mt-6 text-xs font-bold text-brand-cyan">Save 20+ Hours</span>
            </div>

            <div class="p-8 rounded-3xl bg-[#F8FAFD] border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="size-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl mb-6">
                        <i class="fa-brands fa-whatsapp"></i>
                    </div>
                    <h3 class="font-bold text-lg text-brand-navy mb-2">Instant WhatsApp Alerts</h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Good rental flats in Vaishali Nagar, Jagatpura, and Bandra get booked in hours. Be the first to get alerted on WhatsApp as soon as an owner lists.
                    </p>
                </div>
                <span class="mt-6 text-xs font-bold text-emerald-600">Real-Time Sync</span>
            </div>

            <div class="p-8 rounded-3xl bg-[#F8FAFD] border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="size-12 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-xl mb-6">
                        <i class="fa-solid fa-file-contract"></i>
                    </div>
                    <h3 class="font-bold text-lg text-brand-navy mb-2">Free Legal Rent Agreement</h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Get a standardized 11-month lease draft compliant with state tenancy acts, with digital e-stamping delivered right to your email.
                    </p>
                </div>
                <span class="mt-6 text-xs font-bold text-indigo-600">Save ₹2,500</span>
            </div>
        </div>
    </div>
</section>

<!-- ── PRIME MEMBER TESTIMONIALS ── -->
<section class="py-16 sm:py-24 bg-[#F8FAFD]">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="max-w-3xl mb-12">
            <span class="text-xs font-extrabold uppercase tracking-widest text-brand-cyan">REAL STORIES FROM PRIME MEMBERS</span>
            <h2 class="font-display text-3xl sm:text-4xl font-extrabold text-brand-navy mt-2">
                Saved Lakhs in Brokerage in Days
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex text-amber-400 text-sm mb-4">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="text-slate-600 text-sm leading-relaxed mb-6">
                        "I shifted from Delhi to Jaipur for my new job. Local brokers were demanding 1 month rent (₹28,000) for a flat in Vaishali Nagar. With Housing365 Prime Pro, I got direct owner contact in 2 days and saved ₹28,000 completely!"
                    </p>
                </div>
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <div>
                        <strong class="text-brand-navy text-sm block">Rohit Sharma</strong>
                        <span class="text-xs text-slate-400">Software Engineer &bull; Vaishali Nagar, Jaipur</span>
                    </div>
                    <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">Saved ₹28,000</span>
                </div>
            </div>

            <div class="bg-white p-8 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex text-amber-400 text-sm mb-4">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="text-slate-600 text-sm leading-relaxed mb-6">
                        "The Relationship Manager was a lifesaver. She called 6 apartment owners in Jagatpura, verified society rules regarding parking, and lined up 3 viewings on my Saturday afternoon. Outstanding service!"
                    </p>
                </div>
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <div>
                        <strong class="text-brand-navy text-sm block">Pooja Rathore</strong>
                        <span class="text-xs text-slate-400">Architect &bull; Jagatpura, Jaipur</span>
                    </div>
                    <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">Saved ₹22,000</span>
                </div>
            </div>

            <div class="bg-white p-8 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex text-amber-400 text-sm mb-4">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="text-slate-600 text-sm leading-relaxed mb-6">
                        "Finding a pet-friendly flat in Mumbai without paying 50k to brokers seemed impossible. Prime VIP gave me verified landlord numbers and handled my rent agreement seamlessly. 100% recommended."
                    </p>
                </div>
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <div>
                        <strong class="text-brand-navy text-sm block">Vikramaditya S.</strong>
                        <span class="text-xs text-slate-400">Marketing Lead &bull; Bandra West, Mumbai</span>
                    </div>
                    <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">Saved ₹75,000</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── FAQ SECTION ── -->
<section class="py-16 sm:py-24 bg-white border-t border-slate-100">
    <div class="mx-auto w-full max-w-4xl px-4 sm:px-8">
        <div class="text-center mb-12">
            <span class="text-xs font-extrabold uppercase tracking-widest text-brand-cyan">QUESTIONS &amp; ANSWERS</span>
            <h2 class="font-display text-3xl sm:text-4xl font-extrabold text-brand-navy mt-2">
                Frequently Asked Questions About Prime
            </h2>
        </div>

        <div class="space-y-4">
            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80">
                <h3 class="font-bold text-brand-navy text-sm sm:text-base mb-2">How does Housing365 Prime help me save brokerage?</h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    Unlike traditional real estate brokers who demand 15 days to 1 full month of rent (or 2% of property sale price), Housing365 Prime gives you direct, unmasked contact numbers of genuine landlords and sellers. You deal directly with the owner, saving ₹25,000 to ₹1,50,000+ in fees.
                </p>
            </div>

            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80">
                <h3 class="font-bold text-brand-navy text-sm sm:text-base mb-2">What does a dedicated Relationship Manager (RM) do?</h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    Your personal Relationship Manager is an experienced property specialist who takes your criteria (BHK, budget, preferred micro-localities, pet policies), searches and shortlists verified properties for you, contacts owners to verify current availability, and coordinates site visit times at your convenience.
                </p>
            </div>

            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80">
                <h3 class="font-bold text-brand-navy text-sm sm:text-base mb-2">How long is my Prime membership valid?</h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    Prime Basic is valid for 45 days, Prime Pro is valid for 90 days, and Prime VIP is valid for 180 days. Most of our members find and finalize their home within the first 7 to 14 days.
                </p>
            </div>

            <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80">
                <h3 class="font-bold text-brand-navy text-sm sm:text-base mb-2">Can I upgrade my Prime plan later?</h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    Yes! You can upgrade from Basic to Pro or VIP at any time by simply paying the nominal difference in price.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ── PRIME BOOKING & ENQUIRY MODAL ── -->
<div id="primeModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-950/75 backdrop-blur-sm transition-opacity duration-300" role="dialog" aria-modal="true">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl relative max-h-[92vh] overflow-y-auto border border-slate-100">
        <!-- Close Button -->
        <button type="button" onclick="closePrimeModal()" class="absolute top-5 right-5 size-9 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center text-sm transition">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div id="primeFormContainer">
            <div class="mb-5 pr-8">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-amber-100 text-amber-800 mb-2">
                    <i class="fa-solid fa-crown text-amber-600"></i> Join Housing365 Prime
                </span>
                <h3 class="font-display text-2xl font-bold text-brand-navy" id="modalPlanTitle">Prime Pro</h3>
                <p class="text-xs text-slate-500 mt-0.5" id="modalPlanPrice">One-Time Fee: ₹1,999 &bull; Zero Brokerage Guaranteed</p>
            </div>

            <form id="primeLeadForm" onsubmit="handlePrimeSubmit(event)" class="space-y-4">
                <input type="hidden" id="selectedPlanInput" name="plan" value="Prime Pro">
                <input type="hidden" id="selectedPriceInput" name="price" value="₹1,999">

                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">Your Full Name *</label>
                    <input type="text" id="primeName" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-brand-cyan outline-none" placeholder="Ajay Poonia">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">WhatsApp Mobile *</label>
                        <input type="tel" id="primePhone" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-brand-cyan outline-none" placeholder="+91 63761 63263">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">City *</label>
                        <select id="primeCity" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3 py-2.5 text-xs focus:ring-2 focus:ring-brand-cyan outline-none font-medium">
                            <option value="Jaipur" selected>Jaipur</option>
                            <option value="Mumbai">Mumbai</option>
                            <option value="Bengaluru">Bengaluru</option>
                            <option value="Delhi">Delhi NCR</option>
                            <option value="Gurugram">Gurugram</option>
                            <option value="Hyderabad">Hyderabad</option>
                            <option value="Pune">Pune</option>
                            <option value="Other">Other City</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">Preferred Localities</label>
                        <input type="text" id="primeLocalities" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-brand-cyan outline-none" placeholder="e.g. Vaishali Nagar, Jagatpura">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">Configuration</label>
                        <select id="primeBhk" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3 py-2.5 text-xs focus:ring-2 focus:ring-brand-cyan outline-none font-medium">
                            <option value="1 BHK">1 BHK</option>
                            <option value="2 BHK" selected>2 BHK</option>
                            <option value="3 BHK">3 BHK</option>
                            <option value="4+ BHK">4+ BHK / Villa</option>
                            <option value="Commercial">Commercial / Office</option>
                        </select>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3.5 px-4 rounded-2xl bg-gradient-to-r from-amber-500 via-amber-400 to-yellow-500 hover:from-amber-600 hover:to-yellow-600 text-slate-950 font-extrabold text-xs uppercase tracking-wider shadow-lg shadow-amber-500/25 flex items-center justify-center gap-2 transition hover:-translate-y-0.5">
                        <i class="fa-solid fa-crown text-sm"></i>
                        <span>Activate Prime &bull; Connect on WhatsApp</span>
                    </button>
                    <p class="text-[11px] text-center text-slate-400 mt-2">Instant activation &bull; Dedicated Relationship Manager support.</p>
                </div>
            </form>
        </div>

        <div id="primeSuccessContainer" class="hidden text-center py-8">
            <div class="size-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-3xl mx-auto mb-4 animate-bounce">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <h3 class="font-display text-2xl font-bold text-brand-navy mb-2">Prime Request Received!</h3>
            <p class="text-xs sm:text-sm text-slate-600 max-w-sm mx-auto mb-6">
                Thank you! Your Prime membership onboarding has been initiated. A dedicated Housing365 Relationship Manager is reaching out to you on WhatsApp right now.
            </p>
            <a id="primeWhatsappLink" href="#" target="_blank" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs uppercase tracking-wider shadow-md">
                <i class="fa-brands fa-whatsapp text-lg"></i>
                <span>Open WhatsApp Now</span>
            </a>
        </div>
    </div>
</div>

<script>
function switchPrimeTab(tab) {
    const tenantBtn = document.getElementById('tabTenantBtn');
    const buyerBtn = document.getElementById('tabBuyerBtn');
    if (tab === 'tenant') {
        tenantBtn.className = 'px-6 py-2.5 rounded-xl text-xs sm:text-sm font-extrabold uppercase tracking-wider transition-all duration-200 bg-gradient-to-r from-amber-500 to-amber-400 text-slate-950 shadow-md';
        buyerBtn.className = 'px-6 py-2.5 rounded-xl text-xs sm:text-sm font-extrabold uppercase tracking-wider transition-all duration-200 text-white/80 hover:text-white';
    } else {
        buyerBtn.className = 'px-6 py-2.5 rounded-xl text-xs sm:text-sm font-extrabold uppercase tracking-wider transition-all duration-200 bg-gradient-to-r from-amber-500 to-amber-400 text-slate-950 shadow-md';
        tenantBtn.className = 'px-6 py-2.5 rounded-xl text-xs sm:text-sm font-extrabold uppercase tracking-wider transition-all duration-200 text-white/80 hover:text-white';
    }
}

function openPrimeModal(planName, price, contacts) {
    document.getElementById('modalPlanTitle').innerText = planName;
    document.getElementById('modalPlanPrice').innerText = `One-Time Fee: ${price} • Up to ${contacts} Verified Owner Contacts`;
    document.getElementById('selectedPlanInput').value = planName;
    document.getElementById('selectedPriceInput').value = price;
    document.getElementById('primeModal').classList.remove('hidden');
    document.getElementById('primeModal').classList.add('flex');
}

function closePrimeModal() {
    document.getElementById('primeModal').classList.add('hidden');
    document.getElementById('primeModal').classList.remove('flex');
}

function handlePrimeSubmit(e) {
    e.preventDefault();
    const name = document.getElementById('primeName').value;
    const phone = document.getElementById('primePhone').value;
    const city = document.getElementById('primeCity').value;
    const loc = document.getElementById('primeLocalities').value || 'Any';
    const bhk = document.getElementById('primeBhk').value;
    const plan = document.getElementById('selectedPlanInput').value;
    const price = document.getElementById('selectedPriceInput').value;

    const message = `Hi Housing365,%20I%20want%20to%20activate%20${encodeURIComponent(plan)}%20(${encodeURIComponent(price)}).%0AName:%20${encodeURIComponent(name)}%0APhone:%20${encodeURIComponent(phone)}%0ACity:%20${encodeURIComponent(city)}%0ALocalities:%20${encodeURIComponent(loc)}%0AConfiguration:%20${encodeURIComponent(bhk)}`;

    const waUrl = `https://wa.me/916376163263?text=${message}`;

    document.getElementById('primeFormContainer').classList.add('hidden');
    document.getElementById('primeSuccessContainer').classList.remove('hidden');
    document.getElementById('primeWhatsappLink').href = waUrl;

    // Open WhatsApp directly in new window
    window.open(waUrl, '_blank');
}
</script>

@endsection
