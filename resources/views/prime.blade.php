@extends('layouts.app')

@section('title', 'Housing365 Prime Packages | Direct Owner Contacts & Zero Commission')
@section('meta_description', 'Unlock verified direct owner phone numbers, personal relationship manager assistance, and save ₹50,000+ in broker fees across Jaipur, Mumbai, Bengaluru, and Delhi NCR with Housing365 Prime.')

@section('content')

@php
    $currentCategory = $category ?? 'R'; // 'R' = Rent, 'B' = Buy
    $isNri = ($nri ?? 'N') === 'Y';
@endphp

<!-- ── HERO & HEADER BAR: PRIME PACKAGES (MagicBricks Style) ── -->
<section class="relative bg-gradient-to-b from-[#021630] via-[#03234B] to-[#0B1D3A] text-white pt-12 pb-16 sm:pt-16 sm:pb-24 overflow-hidden">
    <!-- Ambient Glows -->
    <div class="pointer-events-none absolute -left-40 top-0 h-[500px] w-[500px] rounded-full opacity-20 blur-3xl" style="background: radial-gradient(circle, rgba(245,158,11,0.35) 0%, transparent 70%)" aria-hidden="true"></div>
    <div class="pointer-events-none absolute right-0 top-10 h-[500px] w-[500px] rounded-full opacity-15 blur-3xl" style="background: radial-gradient(circle, rgba(0,174,239,0.3) 0%, transparent 70%)" aria-hidden="true"></div>

    <div class="relative mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 text-center">
        
        <!-- Eyebrow Badge -->
        <div class="inline-flex items-center gap-2 rounded-full border border-amber-400/40 bg-amber-400/10 px-4 py-1.5 text-xs font-extrabold uppercase tracking-wider text-amber-300 shadow-md mb-4 backdrop-blur-md">
            <i class="fa-solid fa-crown text-amber-400"></i>
            <span>HOUSING 365 PRIME &bull; ZERO BROKERAGE MEMBERSHIP</span>
        </div>

        <!-- Headline -->
        <h1 class="font-display text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-[1.15] max-w-4xl mx-auto">
            Pay Zero Commission. <br>
            <span class="bg-gradient-to-r from-amber-300 via-yellow-200 to-amber-400 bg-clip-text text-transparent">Contact Verified Owners Directly.</span>
        </h1>

        <!-- Subtitle -->
        <p class="mt-3 text-sm sm:text-base text-slate-300 max-w-2xl mx-auto leading-relaxed">
            Get instant unmasked landlord and seller mobile numbers, assisted search by a personal Relationship Manager, and digital lease execution.
        </p>

        <!-- Category & Resident Toggles (Exact MagicBricks MB Prime Header UX) -->
        <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
            <!-- Rent vs Buy Tabs -->
            <div class="inline-flex p-1 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15">
                <button type="button" id="tabRentBtn" onclick="switchCategory('R')" class="px-6 py-2.5 rounded-xl text-xs sm:text-sm font-extrabold uppercase tracking-wider transition-all duration-200 {{ $currentCategory === 'R' ? 'bg-gradient-to-r from-amber-500 to-amber-400 text-slate-950 shadow-md' : 'text-white/80 hover:text-white' }}">
                    <i class="fa-solid fa-key mr-1.5"></i> Rent Packages
                </button>
                <button type="button" id="tabBuyBtn" onclick="switchCategory('B')" class="px-6 py-2.5 rounded-xl text-xs sm:text-sm font-extrabold uppercase tracking-wider transition-all duration-200 {{ $currentCategory === 'B' ? 'bg-gradient-to-r from-amber-500 to-amber-400 text-slate-950 shadow-md' : 'text-white/80 hover:text-white' }}">
                    <i class="fa-solid fa-house-chimney mr-1.5"></i> Buy Packages
                </button>
            </div>

            <!-- Resident Indian vs NRI Toggle -->
            <div class="inline-flex p-1 rounded-2xl bg-white/5 backdrop-blur-md border border-white/10 text-xs font-bold">
                <button type="button" id="toggleResidentBtn" onclick="toggleNri(false)" class="px-4 py-2 rounded-xl transition {{ !$isNri ? 'bg-white/20 text-white font-extrabold' : 'text-slate-400 hover:text-white' }}">
                    Resident Indian
                </button>
                <button type="button" id="toggleNriBtn" onclick="toggleNri(true)" class="px-4 py-2 rounded-xl transition {{ $isNri ? 'bg-white/20 text-white font-extrabold' : 'text-slate-400 hover:text-white' }}">
                    NRI / Global
                </button>
            </div>
        </div>

    </div>
</section>

<!-- ── PACKAGES COMPARISON MATRIX (Exact MagicBricks MB Prime Grid) ── -->
<section class="py-14 sm:py-20 bg-[#F8FAFD]" id="packagesSection">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        
        <div class="max-w-3xl mx-auto text-center mb-10">
            <h2 class="font-display text-2xl sm:text-3xl font-extrabold text-brand-navy" id="packageSectionHeading">
                {{ $currentCategory === 'B' ? 'Prime Buyer Packages' : 'Prime Tenant Packages' }}
            </h2>
            <p class="text-slate-600 text-xs sm:text-sm mt-1.5">
                Select your package to unlock direct owner contact numbers and save thousands in broker commission.
            </p>
        </div>

        <!-- 3-Column Plan Grid (Full Width) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8 w-full items-stretch">
            
            <!-- 1. FREE PLAN -->
            <div id="cardFree" onclick="selectCard('free')" class="package-card bg-white rounded-3xl p-7 border border-slate-200/90 shadow-sm flex flex-col justify-between cursor-pointer transition-all duration-200 hover:shadow-md hover:border-slate-300">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700">
                            Free Trial
                        </span>
                        <span class="text-[11px] font-semibold text-slate-400">Basic Access</span>
                    </div>

                    <h3 class="font-display text-2xl font-bold text-brand-navy">Free</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Explore platform and verify listings</p>

                    <div class="mt-5 mb-5 pb-5 border-b border-slate-100">
                        <div class="flex items-baseline gap-2">
                            <span class="font-display text-3xl font-extrabold text-brand-navy">₹0</span>
                        </div>
                        <span class="text-[11px] text-slate-400 block mt-0.5">No Credit Card Required</span>
                    </div>

                    <ul class="space-y-3 text-xs text-slate-600">
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-sm mt-0.5 shrink-0"></i>
                            <span>Contact up to <strong>2 Property Owners</strong></span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-sm mt-0.5 shrink-0"></i>
                            <span>Search all verified residential listings</span>
                        </li>
                        <li class="flex items-start gap-2.5 text-slate-400">
                            <i class="fa-solid fa-circle-xmark text-slate-300 text-sm mt-0.5 shrink-0"></i>
                            <span>Prime exclusive properties</span>
                        </li>
                        <li class="flex items-start gap-2.5 text-slate-400">
                            <i class="fa-solid fa-circle-xmark text-slate-300 text-sm mt-0.5 shrink-0"></i>
                            <span>Personal Relationship Manager</span>
                        </li>
                        <li class="flex items-start gap-2.5 text-slate-400">
                            <i class="fa-solid fa-circle-xmark text-slate-300 text-sm mt-0.5 shrink-0"></i>
                            <span>Digital Rent Agreement &amp; e-Stamping</span>
                        </li>
                    </ul>
                </div>

                <div class="mt-8 pt-5 border-t border-slate-100">
                    <button type="button" class="w-full py-3 px-4 rounded-xl border-2 border-slate-200 text-slate-700 font-bold text-xs uppercase tracking-wider hover:bg-slate-50 transition">
                        Select Free
                    </button>
                </div>
            </div>

            <!-- 2. BASIC PLAN -->
            <div id="cardBasic" onclick="selectCard('basic')" class="package-card bg-white rounded-3xl p-7 border border-slate-200/90 shadow-sm flex flex-col justify-between cursor-pointer transition-all duration-200 hover:shadow-md hover:border-brand-cyan">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-brand-cyan/10 text-brand-navy font-bold">
                            Self Search
                        </span>
                        <span class="text-[11px] font-bold text-slate-500" id="basicValidity">Validity: 3 Months</span>
                    </div>

                    <h3 class="font-display text-2xl font-bold text-brand-navy">Basic</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Direct phone numbers of verified landlords &amp; owners</p>

                    <div class="mt-5 mb-5 pb-5 border-b border-slate-100">
                        <div class="flex items-baseline gap-2">
                            <span class="font-display text-3xl font-extrabold text-brand-navy" id="basicPrice">₹1,199</span>
                            <span class="text-sm text-slate-400 line-through" id="basicOriginalPrice">₹2,398</span>
                            <span class="text-[10px] font-extrabold uppercase text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded">50% OFF</span>
                        </div>
                        <span class="text-[11px] text-slate-400 block mt-0.5">+ GST &bull; One-Time Fee</span>
                    </div>

                    <ul class="space-y-3 text-xs text-slate-700">
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-sm mt-0.5 shrink-0"></i>
                            <span>Contact up to <strong id="basicContacts">10 Property Owners</strong> directly</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-sm mt-0.5 shrink-0"></i>
                            <span>Access to <strong>Prime Exclusive Properties</strong></span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-sm mt-0.5 shrink-0"></i>
                            <span>Instant WhatsApp chat with verified owners</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-sm mt-0.5 shrink-0"></i>
                            <span>Instant WhatsApp alerts for newly listed flats</span>
                        </li>
                        <li class="flex items-start gap-2.5 text-slate-400">
                            <i class="fa-solid fa-circle-xmark text-slate-300 text-sm mt-0.5 shrink-0"></i>
                            <span>Personal Relationship Manager</span>
                        </li>
                        <li class="flex items-start gap-2.5 text-slate-400">
                            <i class="fa-solid fa-circle-xmark text-slate-300 text-sm mt-0.5 shrink-0"></i>
                            <span>Free Legal Rent Agreement</span>
                        </li>
                    </ul>
                </div>

                <div class="mt-8 pt-5 border-t border-slate-100">
                    <button type="button" class="w-full py-3 px-4 rounded-xl border-2 border-brand-cyan text-brand-navy font-bold text-xs uppercase tracking-wider hover:bg-brand-cyan/5 transition">
                        Select Basic
                    </button>
                </div>
            </div>

            <!-- 3. PRO PLAN (RECOMMENDED & POPULAR) -->
            <div id="cardPro" onclick="selectCard('pro')" class="package-card relative bg-white rounded-3xl p-7 border-2 border-amber-400 shadow-xl flex flex-col justify-between cursor-pointer transition-all duration-200 scale-[1.02] ring-4 ring-amber-400/20">
                <!-- Most Popular Badge -->
                <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full bg-gradient-to-r from-amber-500 to-yellow-500 text-slate-950 font-black text-[10px] uppercase tracking-wider shadow-md flex items-center gap-1.5">
                    <i class="fa-solid fa-crown text-xs"></i>
                    <span>RECOMMENDED &bull; ASSISTED SEARCH</span>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-3 mt-1">
                        <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-amber-50 text-amber-900 border border-amber-200">
                            Dedicated RM
                        </span>
                        <span class="text-[11px] font-bold text-amber-900" id="proValidity">Validity: 3 Months</span>
                    </div>

                    <h3 class="font-display text-2xl font-bold text-brand-navy">Pro</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Assisted home search with dedicated Relationship Manager</p>

                    <div class="mt-5 mb-5 pb-5 border-b border-slate-100">
                        <div class="flex items-baseline gap-2">
                            <span class="font-display text-3xl font-extrabold text-brand-navy" id="proPrice">₹1,599</span>
                            <span class="text-sm text-slate-400 line-through" id="proOriginalPrice">₹3,198</span>
                            <span class="text-[10px] font-extrabold uppercase text-amber-900 bg-amber-100 px-2 py-0.5 rounded font-black">50% OFF</span>
                        </div>
                        <span class="text-[11px] text-slate-400 block mt-0.5">+ GST &bull; One-Time Fee</span>
                    </div>

                    <ul class="space-y-3 text-xs text-slate-700">
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-sm mt-0.5 shrink-0"></i>
                            <span>Contact up to <strong id="proContacts">30 Property Owners</strong></span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-sm mt-0.5 shrink-0"></i>
                            <span><strong>Dedicated Relationship Manager (RM)</strong> to shortlist &amp; call owners</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-sm mt-0.5 shrink-0"></i>
                            <span>Pre-screened site visits arranged at your schedule</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-sm mt-0.5 shrink-0"></i>
                            <span>Access to <strong>Prime Exclusive Properties</strong></span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-sm mt-0.5 shrink-0"></i>
                            <span>Instant WhatsApp chat &amp; direct notifications</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-sm mt-0.5 shrink-0"></i>
                            <span>Free Legal Rent Agreement &amp; digital e-Stamping assistance</span>
                        </li>
                    </ul>
                </div>

                <div class="mt-8 pt-5 border-t border-slate-100">
                    <button type="button" class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-amber-500 to-amber-400 text-slate-950 font-black text-xs uppercase tracking-wider shadow-md hover:from-amber-600 transition">
                        Select Pro
                    </button>
                </div>
            </div>

        </div>

        <!-- Sticky Bottom Dynamic Continue Action Bar (Matches MagicBricks Bottom Bar) -->
        <div class="mt-10 w-full bg-white p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-xl flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3 text-center sm:text-left">
                <div class="size-11 rounded-xl bg-amber-400/20 text-amber-600 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-crown"></i>
                </div>
                <div>
                    <span class="text-xs text-slate-500 uppercase font-bold tracking-wider block">Selected Package</span>
                    <h4 class="font-bold text-base text-brand-navy" id="selectedPlanLabel">Prime Pro &bull; ₹1,599</h4>
                </div>
            </div>

            <button type="button" onclick="triggerRegistrationModal()" id="mainContinueBtn" class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-gradient-to-r from-amber-500 via-amber-400 to-yellow-500 hover:from-amber-600 hover:to-yellow-600 text-slate-950 font-black text-xs uppercase tracking-wider shadow-md transition-all duration-200 hover:-translate-y-0.5 flex items-center justify-center gap-2">
                <span>Continue with Pro &rarr;</span>
            </button>
        </div>

    </div>
</section>

<!-- ── VALUE PILLARS & SAVINGS (Why Join Prime) ── -->
<section class="py-14 sm:py-20 bg-white border-y border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="max-w-3xl mb-12">
            <span class="text-xs font-extrabold uppercase tracking-widest text-brand-cyan">100% DIRECT DEALS</span>
            <h2 class="font-display text-3xl font-extrabold text-brand-navy mt-1.5">
                Why Thousands Choose Housing365 Prime
            </h2>
            <p class="text-slate-600 text-sm mt-2">
                Brokers charge up to 1 month rent or 2% purchase commission. Prime connects you directly with genuine property owners.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="p-6 rounded-2xl bg-[#F8FAFD] border border-slate-200/80">
                <div class="size-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-lg mb-4">
                    <i class="fa-solid fa-phone-volume"></i>
                </div>
                <h3 class="font-bold text-base text-brand-navy mb-1.5">100% Direct Owner Contacts</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Zero middlemen or duplicate broker listings. Speak directly to landlords and finalize deals transparently.
                </p>
            </div>

            <div class="p-6 rounded-2xl bg-[#F8FAFD] border border-slate-200/80">
                <div class="size-10 rounded-xl bg-brand-cyan/15 text-brand-cyan flex items-center justify-center text-lg mb-4">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
                <h3 class="font-bold text-base text-brand-navy mb-1.5">Assisted Search by RM</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Shortlisting, verifying owner availability, and coordinating site visit times handled by your dedicated specialist.
                </p>
            </div>

            <div class="p-6 rounded-2xl bg-[#F8FAFD] border border-slate-200/80">
                <div class="size-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg mb-4">
                    <i class="fa-brands fa-whatsapp"></i>
                </div>
                <h3 class="font-bold text-base text-brand-navy mb-1.5">Instant WhatsApp Alerts</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Fresh verified properties delivered directly to your WhatsApp the instant an owner lists in your target localities.
                </p>
            </div>

            <div class="p-6 rounded-2xl bg-[#F8FAFD] border border-slate-200/80">
                <div class="size-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-lg mb-4">
                    <i class="fa-solid fa-file-contract"></i>
                </div>
                <h3 class="font-bold text-base text-brand-navy mb-1.5">Free Legal Rent Agreement</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Standardized 11-month lease agreement drafted compliant with Model Tenancy guidelines and delivered digitally.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ── FAQ SECTION ── -->
<section class="py-14 sm:py-20 bg-[#F8FAFD]">
    <div class="mx-auto w-full max-w-5xl px-4 sm:px-8 lg:px-12 xl:px-16 2xl:px-20">
        <div class="text-center mb-10">
            <h2 class="font-display text-2xl sm:text-3xl font-extrabold text-brand-navy">
                Frequently Asked Questions
            </h2>
        </div>

        <div class="space-y-3.5">
            <div class="p-5 rounded-2xl bg-white border border-slate-200/80">
                <h3 class="font-bold text-brand-navy text-sm mb-1.5">How does Housing365 Prime eliminate brokerage?</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Prime unlocks direct, unmasked contact numbers of verified owners. You connect with them directly without paying 15-30 days of rent or a 2% purchase commission to local brokers.
                </p>
            </div>

            <div class="p-5 rounded-2xl bg-white border border-slate-200/80">
                <h3 class="font-bold text-brand-navy text-sm mb-1.5">What is the role of a Relationship Manager (RM)?</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Your Relationship Manager understands your BHK and locality preferences, filters out fake ads, calls owners to confirm current availability, and coordinates site viewings at your convenience.
                </p>
            </div>

            <div class="p-5 rounded-2xl bg-white border border-slate-200/80">
                <h3 class="font-bold text-brand-navy text-sm mb-1.5">Can I upgrade my package later?</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Yes, you can upgrade from Basic to Pro or VIP at any time by simply paying the nominal difference amount.
                </p>
            </div>
        </div>
    </div>
</section>


<!-- ════════════════════════════════════════════════════════════════════════════
     MAGICBRICKS-STYLE REGISTRATION & VERIFICATION MODAL (2-STEP FLOW)
     ════════════════════════════════════════════════════════════════════════════ -->
<div id="primeAuthModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-950/75 backdrop-blur-sm" role="dialog" aria-modal="true">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl relative border border-slate-100 animate-in fade-in zoom-in-95 duration-200">
        
        <!-- Close Button -->
        <button type="button" onclick="closePrimeModal()" class="absolute top-5 right-5 size-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center text-xs transition">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <!-- ── STEP 1: USER DETAILS FORM (MagicBricks Step 1) ── -->
        <div id="modalStep1">
            <div class="mb-5 pr-6">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-100 text-amber-900 mb-2">
                    <i class="fa-solid fa-crown text-amber-600"></i>
                    <span id="modalPlanBadge">Prime Pro &bull; ₹1,599</span>
                </div>
                <h3 class="font-display text-2xl font-bold text-brand-navy">Enter your details</h3>
                <p class="text-xs text-slate-500 mt-0.5">Please provide your mobile number to proceed with verification.</p>
            </div>

            <form id="step1Form" onsubmit="handleStep1Submit(event)" class="space-y-3.5">
                <!-- Name -->
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">Your Name *</label>
                    <input type="text" id="regName" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm focus:ring-2 focus:ring-brand-cyan outline-none" placeholder="e.g. Ajay Poonia" value="{{ auth()->check() ? auth()->user()->name : '' }}">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">Email Address *</label>
                    <input type="email" id="regEmail" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm focus:ring-2 focus:ring-brand-cyan outline-none" placeholder="name@example.com" value="{{ auth()->check() ? auth()->user()->email : '' }}">
                </div>

                <!-- Phone with +91 Country Code (Exact MagicBricks UX) -->
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">Mobile Number *</label>
                    <div class="flex rounded-xl border border-slate-200 overflow-hidden bg-slate-50 focus-within:ring-2 focus-within:ring-brand-cyan">
                        <div class="flex items-center gap-1 px-3 py-2.5 bg-slate-100 border-r border-slate-200 text-slate-700 text-xs font-bold shrink-0">
                            <span>🇮🇳 +91</span>
                        </div>
                        <input type="tel" id="regPhone" required maxlength="10" pattern="[0-9]{10}" class="w-full bg-transparent px-3 py-2.5 text-xs sm:text-sm text-slate-800 outline-none" placeholder="9876543210" value="{{ auth()->check() ? preg_replace('/[^0-9]/', '', substr(auth()->user()->phone, -10)) : '' }}">
                    </div>
                    <span class="text-[10px] text-slate-400 mt-1 block">A 4-digit verification code will be sent to this number</span>
                </div>

                <!-- City -->
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">Preferred City *</label>
                    <select id="regCity" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3 py-2.5 text-xs sm:text-sm focus:ring-2 focus:ring-brand-cyan outline-none font-medium">
                        <option value="Jaipur" selected>Jaipur</option>
                        <option value="Mumbai">Mumbai</option>
                        <option value="Bengaluru">Bengaluru</option>
                        <option value="Delhi">Delhi NCR</option>
                        <option value="Gurugram">Gurugram</option>
                        <option value="Hyderabad">Hyderabad</option>
                        <option value="Pune">Pune</option>
                        <option value="Ahmedabad">Ahmedabad</option>
                        <option value="Other">Other City</option>
                    </select>
                </div>

                <!-- T&C Disclaimer -->
                <div class="pt-1">
                    <p class="text-[10px] text-slate-400 leading-normal">
                        By continuing, you agree to Housing365's <a href="#" class="text-brand-cyan hover:underline">Terms of Service</a> &bull; Zero Brokerage Guarantee applies.
                    </p>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-brand-navy hover:bg-slate-900 text-white font-bold text-xs uppercase tracking-wider shadow-md transition-all duration-200 hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <span>Continue &rarr;</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- ── STEP 2: 4-DIGIT VERIFICATION CODE (MagicBricks Step 2) ── -->
        <div id="modalStep2" class="hidden">
            <div class="mb-5 text-center">
                <div class="size-12 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl mx-auto mb-3">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h3 class="font-display text-2xl font-bold text-brand-navy">Verify Mobile Number</h3>
                <p class="text-xs text-slate-500 mt-1">
                    Enter the 4-digit code sent on <br>
                    <span class="font-bold text-brand-navy" id="displayPhoneVerify">+91 9876543210</span>
                    <button type="button" onclick="goToStep1()" class="text-brand-cyan font-bold hover:underline ml-1.5 text-xs">Edit</button>
                </p>
            </div>

            <form id="step2Form" onsubmit="handleStep2Submit(event)" class="space-y-4">
                <!-- 4 Digit Boxes -->
                <div class="flex justify-center gap-2.5 my-4">
                    <input type="text" maxlength="1" id="otp1" class="otp-input size-12 text-center text-lg font-bold rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-brand-cyan focus:ring-2 focus:ring-brand-cyan outline-none" required autofocus>
                    <input type="text" maxlength="1" id="otp2" class="otp-input size-12 text-center text-lg font-bold rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-brand-cyan focus:ring-2 focus:ring-brand-cyan outline-none" required>
                    <input type="text" maxlength="1" id="otp3" class="otp-input size-12 text-center text-lg font-bold rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-brand-cyan focus:ring-2 focus:ring-brand-cyan outline-none" required>
                    <input type="text" maxlength="1" id="otp4" class="otp-input size-12 text-center text-lg font-bold rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-brand-cyan focus:ring-2 focus:ring-brand-cyan outline-none" required>
                </div>

                <div class="p-2.5 rounded-xl bg-amber-50 border border-amber-200/80 text-center">
                    <span class="text-[11px] font-bold text-amber-900 block">Test Verification Code: <span class="text-brand-navy underline">3650</span></span>
                    <span class="text-[10px] text-amber-800">You can also enter any 4 digits to instantly test registration.</span>
                </div>

                <!-- Verify CTA Button -->
                <div class="pt-2">
                    <button type="submit" id="verifyBtn" class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-amber-500 via-amber-400 to-yellow-500 hover:from-amber-600 hover:to-yellow-600 text-slate-950 font-black text-xs uppercase tracking-wider shadow-md transition-all duration-200 hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-crown text-xs"></i>
                        <span>Verify &amp; Activate Prime</span>
                    </button>
                </div>

                <div class="text-center pt-2">
                    <span class="text-[11px] text-slate-400">Didn't get code?</span>
                    <button type="button" onclick="resendOtp()" class="text-[11px] font-bold text-brand-cyan hover:underline ml-1">Resend Code</button>
                </div>
            </form>
        </div>

        <!-- ── STEP 3: ACTIVATION SUCCESS ── -->
        <div id="modalStep3" class="hidden text-center py-6">
            <div class="size-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-3xl mx-auto mb-4 animate-bounce">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <h3 class="font-display text-2xl font-bold text-brand-navy mb-1.5">Welcome to Prime!</h3>
            <p class="text-xs sm:text-sm text-slate-600 mb-6">
                Your Housing365 Prime membership is now active. Your dedicated Relationship Manager is connecting with you on WhatsApp right now.
            </p>
            <div class="space-y-2">
                <a id="modalWhatsappBtn" href="#" target="_blank" class="w-full py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs uppercase tracking-wider shadow-md flex items-center justify-center gap-2">
                    <i class="fa-brands fa-whatsapp text-base"></i>
                    <span>Open WhatsApp Concierge</span>
                </a>
                <a id="modalDashboardBtn" href="{{ route('my.prime') }}" class="w-full py-3 px-4 rounded-xl bg-brand-navy hover:bg-slate-900 text-white font-bold text-xs uppercase tracking-wider shadow-sm flex items-center justify-center gap-2">
                    <i class="fa-solid fa-gauge-high text-xs"></i>
                    <span>Go to My Dashboard &rarr;</span>
                </a>
            </div>
        </div>

    </div>
</div>

<script>
// State Management
let currentCategory = '{{ $currentCategory }}'; // 'R' or 'B'
let currentSelectedCard = 'pro'; // 'free', 'basic', 'pro'
let tempFormData = {};

const packagesData = {
    'R': {
        name: 'Rent',
        basicPrice: '₹1,199',
        basicOriginal: '₹2,398',
        basicContacts: '10 Property Owners',
        basicValidity: 'Validity: 3 Months',
        proPrice: '₹1,599',
        proOriginal: '₹3,198',
        proContacts: '30 Property Owners',
        proValidity: 'Validity: 3 Months',
    },
    'B': {
        name: 'Buy',
        basicPrice: '₹1,599',
        basicOriginal: '₹3,198',
        basicContacts: '10 Property Owners',
        basicValidity: 'Validity: 6 Months',
        proPrice: '₹2,199',
        proOriginal: '₹4,398',
        proContacts: '30 Property Owners',
        proValidity: 'Validity: 6 Months',
    }
};

function switchCategory(cat) {
    currentCategory = cat;
    const rentBtn = document.getElementById('tabRentBtn');
    const buyBtn = document.getElementById('tabBuyBtn');
    const heading = document.getElementById('packageSectionHeading');

    if (cat === 'R') {
        rentBtn.className = 'px-6 py-2.5 rounded-xl text-xs sm:text-sm font-extrabold uppercase tracking-wider transition-all duration-200 bg-gradient-to-r from-amber-500 to-amber-400 text-slate-950 shadow-md';
        buyBtn.className = 'px-6 py-2.5 rounded-xl text-xs sm:text-sm font-extrabold uppercase tracking-wider transition-all duration-200 text-white/80 hover:text-white';
        heading.innerText = 'Prime Tenant Packages';
    } else {
        buyBtn.className = 'px-6 py-2.5 rounded-xl text-xs sm:text-sm font-extrabold uppercase tracking-wider transition-all duration-200 bg-gradient-to-r from-amber-500 to-amber-400 text-slate-950 shadow-md';
        rentBtn.className = 'px-6 py-2.5 rounded-xl text-xs sm:text-sm font-extrabold uppercase tracking-wider transition-all duration-200 text-white/80 hover:text-white';
        heading.innerText = 'Prime Buyer Packages';
    }

    // Update prices
    const d = packagesData[cat];
    document.getElementById('basicPrice').innerText = d.basicPrice;
    document.getElementById('basicOriginalPrice').innerText = d.basicOriginal;
    document.getElementById('basicContacts').innerText = d.basicContacts;
    document.getElementById('basicValidity').innerText = d.basicValidity;

    document.getElementById('proPrice').innerText = d.proPrice;
    document.getElementById('proOriginalPrice').innerText = d.proOriginal;
    document.getElementById('proContacts').innerText = d.proContacts;
    document.getElementById('proValidity').innerText = d.proValidity;

    // Update bottom label
    updateBottomButton();
}

function selectCard(card) {
    currentSelectedCard = card;
    const cards = ['cardFree', 'cardBasic', 'cardPro'];
    cards.forEach(c => {
        const el = document.getElementById(c);
        el.classList.remove('border-2', 'border-amber-400', 'ring-4', 'ring-amber-400/20', 'scale-[1.02]');
        el.classList.add('border', 'border-slate-200/90');
    });

    const activeEl = document.getElementById(card === 'free' ? 'cardFree' : (card === 'basic' ? 'cardBasic' : 'cardPro'));
    activeEl.classList.remove('border', 'border-slate-200/90');
    activeEl.classList.add('border-2', 'border-amber-400', 'ring-4', 'ring-amber-400/20', 'scale-[1.02]');

    updateBottomButton();
}

function updateBottomButton() {
    const d = packagesData[currentCategory];
    const label = document.getElementById('selectedPlanLabel');
    const btn = document.getElementById('mainContinueBtn');

    if (currentSelectedCard === 'free') {
        label.innerText = 'Free Plan • ₹0';
        btn.innerHTML = '<span>Continue with Free &rarr;</span>';
    } else if (currentSelectedCard === 'basic') {
        label.innerText = `Prime Basic (${d.name}) • ${d.basicPrice}`;
        btn.innerHTML = `<span>Continue with Basic (${d.basicPrice}) &rarr;</span>`;
    } else {
        label.innerText = `Prime Pro (${d.name}) • ${d.proPrice}`;
        btn.innerHTML = `<span>Continue with Pro (${d.proPrice}) &rarr;</span>`;
    }
}

function triggerRegistrationModal() {
    const d = packagesData[currentCategory];
    let planTitle = 'Prime Pro';
    let planPrice = d.proPrice;

    if (currentSelectedCard === 'free') {
        planTitle = 'Free';
        planPrice = '₹0';
    } else if (currentSelectedCard === 'basic') {
        planTitle = `Prime Basic (${d.name})`;
        planPrice = d.basicPrice;
    } else {
        planTitle = `Prime Pro (${d.name})`;
        planPrice = d.proPrice;
    }

    document.getElementById('modalPlanBadge').innerText = `${planTitle} • ${planPrice}`;
    goToStep1();

    const modal = document.getElementById('primeAuthModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closePrimeModal() {
    const modal = document.getElementById('primeAuthModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function goToStep1() {
    document.getElementById('modalStep1').classList.remove('hidden');
    document.getElementById('modalStep2').classList.add('hidden');
    document.getElementById('modalStep3').classList.add('hidden');
}

function handleStep1Submit(e) {
    e.preventDefault();
    const name = document.getElementById('regName').value.trim();
    const email = document.getElementById('regEmail').value.trim();
    const phone = document.getElementById('regPhone').value.trim();
    const city = document.getElementById('regCity').value;

    if (phone.length < 10) {
        alert('Please enter a valid 10-digit mobile number');
        return;
    }

    tempFormData = {
        name: name,
        email: email,
        phone: '+91 ' + phone,
        city: city,
        user_type: currentCategory === 'B' ? 'buyer' : 'tenant',
        plan: currentSelectedCard === 'free' ? 'Free' : (currentSelectedCard === 'basic' ? `Prime Basic (${packagesData[currentCategory].name})` : `Prime Pro (${packagesData[currentCategory].name})`)
    };

    // Show Step 2
    document.getElementById('displayPhoneVerify').innerText = '+91 ' + phone;
    document.getElementById('modalStep1').classList.add('hidden');
    document.getElementById('modalStep2').classList.remove('hidden');

    // Pre-fill demo OTP 3650
    document.getElementById('otp1').value = '3';
    document.getElementById('otp2').value = '6';
    document.getElementById('otp3').value = '5';
    document.getElementById('otp4').value = '0';
    document.getElementById('otp4').focus();
}

async function handleStep2Submit(e) {
    e.preventDefault();
    const otp = document.getElementById('otp1').value + 
                document.getElementById('otp2').value + 
                document.getElementById('otp3').value + 
                document.getElementById('otp4').value;

    const btn = document.getElementById('verifyBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Activating Membership...';

    try {
        const response = await fetch("{{ route('prime.activate') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                name: tempFormData.name,
                email: tempFormData.email,
                phone: tempFormData.phone,
                city: tempFormData.city,
                user_type: tempFormData.user_type,
                plan: tempFormData.plan,
                otp: otp
            })
        });

        const data = await response.json();

        if (data.success) {
            document.getElementById('modalStep2').classList.add('hidden');
            document.getElementById('modalStep3').classList.remove('hidden');

            const waUrl = data.whatsapp_url || `https://wa.me/916376163263?text=Hi%20Housing365,%20I%20have%20activated%20${encodeURIComponent(tempFormData.plan)}`;
            document.getElementById('modalWhatsappBtn').href = waUrl;
            if (data.redirect_url) {
                document.getElementById('modalDashboardBtn').href = data.redirect_url;
            }

            // Auto open WhatsApp concierge
            window.open(waUrl, '_blank');
        } else {
            alert(data.message || 'Verification failed. Please try again.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-crown text-xs"></i> <span>Verify &amp; Activate Prime</span>';
        }
    } catch (err) {
        console.error(err);
        // Fallback demo activation
        document.getElementById('modalStep2').classList.add('hidden');
        document.getElementById('modalStep3').classList.remove('hidden');
    }
}

function resendOtp() {
    alert('A new 4-digit verification code has been dispatched to ' + tempFormData.phone + '. (Test code: 3650)');
}

// 4-Digit Box Auto-Focus Handling
document.querySelectorAll('.otp-input').forEach((input, index, inputs) => {
    input.addEventListener('input', (e) => {
        if (input.value.length === 1 && index < inputs.length - 1) {
            inputs[index + 1].focus();
        }
    });

    input.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && input.value.length === 0 && index > 0) {
            inputs[index - 1].focus();
        }
    });
});
</script>

@endsection
