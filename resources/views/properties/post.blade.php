@extends('layouts.app')

@section('title', 'Post Free Property Ads | Rent & Sell Property Online | Housing 365 India')
@section('meta_description', 'Post free property ads to rent and sell flat, apartment, house, villa, commercial office and plots online. Zero brokerage on Housing 365 India.')

@section('content')

<!-- ── TOP BREADCRUMB ── -->
<div class="bg-white border-b border-slate-100 py-3.5 text-xs sm:text-sm text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 flex items-center justify-between">
        <div class="flex items-center space-x-2">
            <a href="{{ route('home') }}" class="hover:text-brand-cyan transition font-medium">Home</a>
            <span class="text-slate-300">/</span>
            <a href="{{ route('properties.index') }}" class="hover:text-brand-cyan transition font-medium">Properties</a>
            <span class="text-slate-300">/</span>
            <span class="text-brand-navy font-bold">Post Property Ads</span>
        </div>
        <div class="hidden sm:inline-flex items-center gap-2 rounded-full border border-brand-cyan/20 bg-brand-cyan/5 px-3.5 py-1 text-xs font-bold uppercase tracking-wider text-brand-cyan">
            <span class="h-1.5 w-1.5 rounded-full bg-brand-cyan animate-pulse"></span>
            100% Free Listing &bull; Zero Brokerage &bull; Direct Buyer/Tenant Enquiries
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════════════ -->
<!-- STAGE 1: LANDING & QUICK STARTER (Matching Home Page Brand Theme)  -->
<!-- ══════════════════════════════════════════════════════════════════ -->
<section id="stage-1-container" class="{{ (!empty($errors) && $errors->any()) ? 'hidden' : 'block' }} relative overflow-hidden bg-gradient-to-b from-[#F7FAFD] via-white to-[#F0F6FC] py-10 sm:py-16 lg:py-20 border-b border-slate-100">
    <!-- Ambient Glow Effects (identical to home page) -->
    <div class="pointer-events-none absolute -left-40 top-0 h-[500px] w-[500px] rounded-full opacity-40 blur-3xl" style="background: radial-gradient(circle, rgba(0,174,239,0.18) 0%, transparent 70%)" aria-hidden="true"></div>
    <div class="pointer-events-none absolute right-0 top-20 h-[600px] w-[600px] rounded-full opacity-30 blur-3xl" style="background: radial-gradient(circle, rgba(3,35,75,0.06) 0%, transparent 70%)" aria-hidden="true"></div>

    <div class="relative mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-start">

            <!-- LEFT COLUMN: Trust, Hero & How It Works -->
            <div class="lg:col-span-7 space-y-10">
                <!-- Hero Heading & Trust Badges -->
                <div class="space-y-4">
                    <!-- Eyebrow Badge -->
                    <div class="inline-flex items-center gap-2 rounded-full border border-brand-cyan/20 bg-brand-cyan/5 px-3.5 py-1 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                        <span class="h-1.5 w-1.5 rounded-full bg-brand-cyan"></span>
                        100% FREE PROPERTY LISTING &bull; ZERO BROKERAGE
                    </div>

                    <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-normal leading-[1.12] tracking-tight text-brand-navy">
                        Post your property <br>
                        Ad to sell or rent <br>
                        online for <span class="font-display font-medium text-brand-cyan">Free!</span>
                    </h1>

                    <p class="max-w-xl text-base leading-relaxed text-slate-600 sm:text-lg">
                        List your residential flat, independent villa, commercial office, or plot. Get discovered directly by thousands of verified buyers and tenants.
                    </p>

                    <ul class="space-y-3 pt-3 text-sm sm:text-base font-semibold text-slate-700">
                        <li class="flex items-center gap-3">
                            <span class="flex size-6 items-center justify-center rounded-full bg-brand-cyan/10 text-brand-cyan shrink-0">
                                <i class="fa-solid fa-check text-xs"></i>
                            </span>
                            <span>Get Access to <strong class="text-brand-navy">4 Lakh+</strong> Genuine Buyers &amp; Tenants</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="flex size-6 items-center justify-center rounded-full bg-brand-cyan/10 text-brand-cyan shrink-0">
                                <i class="fa-solid fa-check text-xs"></i>
                            </span>
                            <span>Sell &amp; Rent Faster with Verified Prime Visibility</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="flex size-6 items-center justify-center rounded-full bg-brand-cyan/10 text-brand-cyan shrink-0">
                                <i class="fa-solid fa-check text-xs"></i>
                            </span>
                            <span>Get Expert Advice on Local Market Trends and Property Valuation</span>
                        </li>
                    </ul>
                </div>

                <!-- Visual Hero Illustration with House Frame -->
                <div class="relative bg-white/90 backdrop-blur-md rounded-3xl p-6 sm:p-8 border border-slate-200/80 overflow-hidden shadow-lg shadow-slate-900/5 flex items-center justify-between gap-6">
                    <div class="space-y-3 z-10 max-w-sm">
                        <span class="inline-block bg-brand-navy text-white text-[11px] font-extrabold uppercase px-3 py-1 rounded-full tracking-wider shadow">
                            Zero Brokerage Guaranteed
                        </span>
                        <h3 class="font-display text-xl sm:text-2xl font-bold text-brand-navy leading-snug">
                            Over 1,200+ Homes Listed Daily Across India
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Post once and get connected directly with pre-screened tenants and buyers on WhatsApp &amp; direct phone calls.
                        </p>
                    </div>

                    <div class="relative shrink-0 hidden sm:block">
                        <div class="size-40 rounded-2xl overflow-hidden shadow-lg border-4 border-white transform rotate-2 hover:rotate-0 transition duration-300">
                            <img src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=500&auto=format&fit=crop&q=80" alt="Happy property owner" class="w-full h-full object-cover">
                        </div>
                    </div>
                </div>

                <!-- How it works 3 Steps -->
                <div class="pt-2">
                    <h2 class="font-display text-2xl font-bold text-brand-navy mb-6">How it works</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <!-- Step 1 -->
                        <div class="bg-white rounded-3xl p-5 border border-slate-200/90 shadow-sm hover:shadow-md transition">
                            <div class="size-10 rounded-xl bg-brand-cyan/10 text-brand-navy flex items-center justify-center font-bold text-sm mb-3 border border-brand-cyan/20">
                                01
                            </div>
                            <h4 class="font-bold text-brand-navy text-sm mb-1.5">Post your Property Ad</h4>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                Enter all details like locality name, configuration, amenities along with uploading photos.
                            </p>
                        </div>

                        <!-- Step 2 -->
                        <div class="bg-white rounded-3xl p-5 border border-slate-200/90 shadow-sm hover:shadow-md transition">
                            <div class="size-10 rounded-xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center font-bold text-sm mb-3 border border-brand-cyan/20">
                                02
                            </div>
                            <h4 class="font-bold text-brand-navy text-sm mb-1.5">Check Direct Responses</h4>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                Get instant WhatsApp and phone notifications when matching tenants or buyers enquire.
                            </p>
                        </div>

                        <!-- Step 3 -->
                        <div class="bg-white rounded-3xl p-5 border border-slate-200/90 shadow-sm hover:shadow-md transition">
                            <div class="size-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm mb-3 border border-emerald-100">
                                03
                            </div>
                            <h4 class="font-bold text-brand-navy text-sm mb-1.5">Close the Deal</h4>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                Schedule visit, agree on terms and close deal with zero broker commission fees.
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN: Starter Card ("Let's get you started") -->
            <div class="lg:col-span-5">
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-2xl shadow-slate-900/5 relative sticky top-24">
                    <h3 class="font-display text-2xl sm:text-3xl font-bold text-brand-navy mb-6">Let's get you started</h3>

                    <!-- 1. You are: (Owner / Agent / Builder) -->
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5">You are:</label>
                        <div class="grid grid-cols-3 gap-2.5" id="starter-user-type-group">
                            <button type="button" class="starter-role-chip py-2.5 px-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-700 text-xs sm:text-sm font-semibold hover:border-brand-cyan hover:bg-white text-center transition" data-role="Owner" onclick="selectStarterRole(this)">
                                Owner
                            </button>
                            <button type="button" class="starter-role-chip py-2.5 px-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-700 text-xs sm:text-sm font-semibold hover:border-brand-cyan hover:bg-white text-center transition" data-role="Agent" onclick="selectStarterRole(this)">
                                Agent
                            </button>
                            <button type="button" class="starter-role-chip py-2.5 px-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-700 text-xs sm:text-sm font-semibold hover:border-brand-cyan hover:bg-white text-center transition" data-role="Builder" onclick="selectStarterRole(this)">
                                Builder
                            </button>
                        </div>
                        <div id="starter-role-error" class="hidden text-xs text-rose-600 font-semibold mt-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <span>Compulsory field. Please select user type!!</span>
                        </div>
                    </div>

                    <!-- 2. You are here to: (Sell / Rent/lease / List as PG) -->
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5">You are here to:</label>
                        <div class="grid grid-cols-3 gap-2.5" id="starter-purpose-group">
                            <button type="button" class="starter-purpose-chip py-2.5 px-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-700 text-xs sm:text-sm font-semibold hover:border-brand-cyan hover:bg-white text-center transition" data-purpose="sale" onclick="selectStarterPurpose(this)">
                                Sell
                            </button>
                            <button type="button" class="starter-purpose-chip py-2.5 px-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-700 text-xs sm:text-sm font-semibold hover:border-brand-cyan hover:bg-white text-center transition" data-purpose="rent" onclick="selectStarterPurpose(this)">
                                Rent/lease
                            </button>
                            <button type="button" class="starter-purpose-chip py-2.5 px-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-700 text-xs sm:text-sm font-semibold hover:border-brand-cyan hover:bg-white text-center transition" data-purpose="pg" onclick="selectStarterPurpose(this)">
                                List as PG
                            </button>
                        </div>
                        <div id="starter-purpose-error" class="hidden text-xs text-rose-600 font-semibold mt-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <span>Compulsory field. Please select property type!!</span>
                        </div>
                    </div>

                    <!-- 3. Your contact number -->
                    <div class="mb-5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Your contact number</label>
                        <div class="flex items-center rounded-xl border border-slate-200 bg-slate-50/80 overflow-hidden focus-within:border-brand-cyan focus-within:ring-2 focus-within:ring-brand-cyan/20 transition">
                            <div class="px-3.5 py-3 border-r border-slate-200 text-brand-navy font-bold text-sm bg-slate-100 flex items-center gap-1.5 shrink-0">
                                <span>IND +91</span>
                                <i class="fa-solid fa-chevron-down text-[9px] text-slate-400"></i>
                            </div>
                            <input type="tel" id="starter-phone-input" value="6376163263" placeholder="WhatsApp Number" class="w-full bg-transparent px-3.5 py-3 text-sm font-bold text-slate-800 placeholder-slate-400 focus:outline-none">
                        </div>
                        <div id="starter-phone-error" class="hidden text-xs text-rose-600 font-semibold mt-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <span>Please enter a valid 10-digit WhatsApp phone number!</span>
                        </div>
                    </div>

                    <!-- 4. WhatsApp Callout Reassurance -->
                    <div class="mb-6 p-3.5 rounded-xl border border-emerald-200 bg-emerald-50/70 flex items-center gap-2.5 text-xs text-slate-700 font-medium">
                        <i class="fa-brands fa-whatsapp text-emerald-600 text-lg shrink-0"></i>
                        <span>Enter your WhatsApp No. to get enquiries from Buyer/Tenant</span>
                    </div>

                    <!-- 5. Primary Action Button -->
                    <button type="button" onclick="startListingWizard()" class="w-full py-4 px-6 rounded-xl bg-brand-cyan hover:bg-brand-cyan-hover text-white font-bold text-base shadow-lg shadow-brand-cyan/25 transition-all duration-200 hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <span>Start Now</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>

                    <!-- Trust Footer in Card -->
                    <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                        <span class="flex items-center gap-1.5 text-emerald-700 font-semibold">
                            <i class="fa-solid fa-shield-check text-emerald-500"></i> 100% Free Listing
                        </span>
                        <a href="javascript:void(0)" onclick="openAssistanceModal()" class="text-brand-cyan font-bold hover:underline">
                            Need help?
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════════════════════════════ -->
<!-- STAGE 2: FULL PROPERTY WIZARD ("Sell or Rent your Property")        -->
<!-- ══════════════════════════════════════════════════════════════════ -->
<section id="stage-2-container" class="{{ (!empty($errors) && $errors->any()) ? 'block' : 'hidden' }} py-10 sm:py-16 bg-[#F8FAFD]">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">

        <!-- Top Header & Free Tag -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-5">
            <div>
                <button type="button" onclick="backToStarter()" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-brand-cyan mb-2 transition">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Change basic choices</span>
                </button>
                <h1 class="font-display text-3xl sm:text-4xl font-extrabold text-brand-navy tracking-tight">
                    Sell or Rent your Property
                </h1>
                <p class="text-xs sm:text-sm text-slate-600 mt-1">
                    You are posting this property for <span class="bg-brand-cyan/15 text-brand-navy font-black px-2.5 py-0.5 rounded text-xs tracking-wider uppercase border border-brand-cyan/30">FREE!</span>
                </p>
            </div>

            <div class="flex items-center gap-3">
                <button type="button" onclick="openAssistanceModal()" class="inline-flex items-center gap-2 bg-white px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-brand-navy hover:border-brand-cyan shadow-sm transition">
                    <i class="fa-solid fa-headset text-brand-cyan"></i>
                    <span>Need Help? Call Property Expert</span>
                </button>
            </div>
        </div>

        @if (!empty($errors) && $errors->any())
            <div class="mb-8 rounded-2xl bg-rose-50 border border-rose-200 p-5 text-rose-800">
                <div class="flex items-center gap-3 font-bold mb-2">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 text-lg"></i>
                    <span>Please correct the errors below before publishing:</span>
                </div>
                <ul class="list-disc list-inside text-xs space-y-1 text-rose-700 pl-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Two Column Main Layout: 70% Form + 30% Sticky Helper Sidebar -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">

            <!-- LEFT FORM COLUMN (Wizard) -->
            <div class="lg:col-span-8">
                <form action="{{ route('properties.store') }}" method="POST" enctype="multipart/form-data" id="main-wizard-form" class="space-y-6">
                    @csrf

                    <!-- Hidden Fields Managed by Script -->
                    <input type="hidden" name="category" id="hidden_category" value="{{ old('category', 'residential') }}">
                    <input type="hidden" name="bedrooms" id="hidden_bedrooms" value="{{ old('bedrooms', '2') }}">
                    <input type="hidden" name="balconies" id="hidden_balconies" value="{{ old('balconies', '1') }}">
                    <input type="hidden" name="floor_no" id="hidden_floor_no" value="{{ old('floor_no', 'Ground') }}">
                    <input type="hidden" name="total_floors" id="hidden_total_floors" value="{{ old('total_floors', '4') }}">
                    <input type="hidden" name="furnishing" id="hidden_furnishing" value="{{ old('furnishing', 'Semi-Furnished') }}">
                    <input type="hidden" name="posting_as" id="hidden_posting_as" value="{{ old('posting_as', 'Full House') }}">
                    <input type="hidden" name="total_flats_in_society" id="hidden_flats_society" value="{{ old('total_flats_in_society', '50-100') }}">
                    <input type="hidden" name="bathrooms" id="hidden_bathrooms" value="{{ old('bathrooms', '2') }}">

                    <!-- 1. PERSONAL DETAILS -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm">
                        <h3 class="font-display text-lg sm:text-xl font-bold text-brand-navy border-b border-slate-100 pb-3 mb-5 flex items-center gap-2.5">
                            <span class="flex size-7 items-center justify-center rounded-lg bg-brand-cyan/10 text-brand-cyan text-xs font-bold">1</span>
                            <span>Personal Details</span>
                        </h3>

                        <div class="space-y-4">
                            <!-- I am: Radio Options -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">I am</label>
                                <div class="flex items-center gap-6 text-sm font-semibold text-slate-800">
                                    <label class="inline-flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="posted_by" value="Owner" class="size-4 text-brand-cyan focus:ring-brand-cyan" {{ old('posted_by', 'Owner') === 'Owner' ? 'checked' : '' }}>
                                        <span>Owner</span>
                                    </label>
                                    <label class="inline-flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="posted_by" value="Agent" class="size-4 text-brand-cyan focus:ring-brand-cyan" {{ old('posted_by') === 'Agent' ? 'checked' : '' }}>
                                        <span>Agent</span>
                                    </label>
                                    <label class="inline-flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="posted_by" value="Builder" class="size-4 text-brand-cyan focus:ring-brand-cyan" {{ old('posted_by') === 'Builder' ? 'checked' : '' }}>
                                        <span>Builder</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Name -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Name *</label>
                                <input type="text" name="seller_name" id="wizard_name" required value="{{ old('seller_name', 'Ajay Poonia') }}" placeholder="Enter Your Name" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan/20 focus:border-brand-cyan transition font-medium">
                            </div>

                            <!-- Mobile with IND +91 -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Mobile *</label>
                                <div class="flex items-center rounded-xl border border-slate-200 bg-slate-50 overflow-hidden focus-within:border-brand-cyan focus-within:ring-2 focus-within:ring-brand-cyan/20 transition">
                                    <div class="px-3.5 py-3 border-r border-slate-200 text-brand-navy font-bold text-sm bg-slate-100 flex items-center gap-1.5 shrink-0">
                                        <span>IND +91</span>
                                        <i class="fa-solid fa-chevron-down text-[9px] text-slate-400"></i>
                                    </div>
                                    <input type="tel" name="seller_phone" id="wizard_phone" required value="{{ old('seller_phone', '6376163263') }}" placeholder="Mobile Number" class="w-full bg-transparent px-4 py-3 text-sm font-bold text-slate-900 focus:outline-none">
                                </div>
                            </div>

                            <!-- WhatsApp Callout Banner -->
                            <div class="p-3.5 rounded-xl border border-emerald-200 bg-emerald-50/70 flex items-center gap-2.5 text-xs text-slate-800 font-medium">
                                <i class="fa-brands fa-whatsapp text-emerald-600 text-xl shrink-0"></i>
                                <span>Enter your WhatsApp No. to get enquiries from Buyer/Tenant</span>
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email</label>
                                <input type="email" name="seller_email" id="wizard_email" value="{{ old('seller_email') }}" placeholder="Enter Your Email" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan/20 focus:border-brand-cyan transition">
                            </div>
                        </div>
                    </div>

                    <!-- 2. PROPERTY DETAILS -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm">
                        <h3 class="font-display text-lg sm:text-xl font-bold text-brand-navy border-b border-slate-100 pb-3 mb-5 flex items-center gap-2.5">
                            <span class="flex size-7 items-center justify-center rounded-lg bg-brand-cyan/10 text-brand-cyan text-xs font-bold">2</span>
                            <span>Property Details</span>
                        </h3>

                        <div class="space-y-5">
                            <!-- For: Radio Options (Sale / Rent / PG) -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Listing Purpose *</label>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-2.5 text-xs font-semibold text-slate-800">
                                    <label class="p-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:border-brand-cyan flex items-center gap-2 cursor-pointer transition">
                                        <input type="radio" name="purpose" value="rent" class="size-4 text-brand-cyan focus:ring-brand-cyan" onchange="syncPurposeChange('rent')" {{ old('purpose', 'rent') === 'rent' ? 'checked' : '' }}>
                                        <span>Rent (₹0 Free)</span>
                                    </label>
                                    <label class="p-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:border-brand-cyan flex items-center gap-2 cursor-pointer transition">
                                        <input type="radio" name="purpose" value="sale" class="size-4 text-brand-cyan focus:ring-brand-cyan" onchange="syncPurposeChange('sale')" {{ old('purpose') === 'sale' ? 'checked' : '' }}>
                                        <span>Sale</span>
                                    </label>
                                    <label class="p-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:border-brand-cyan flex items-center gap-2 cursor-pointer transition">
                                        <input type="radio" name="purpose" value="commercial_rent" class="size-4 text-brand-cyan focus:ring-brand-cyan" onchange="syncPurposeChange('commercial_rent')" {{ old('purpose') === 'commercial_rent' ? 'checked' : '' }}>
                                        <span>Commercial Rent</span>
                                    </label>
                                    <label class="p-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:border-brand-cyan flex items-center gap-2 cursor-pointer transition">
                                        <input type="radio" name="purpose" value="commercial_sale" class="size-4 text-brand-cyan focus:ring-brand-cyan" onchange="syncPurposeChange('commercial_sale')" {{ old('purpose') === 'commercial_sale' ? 'checked' : '' }}>
                                        <span>Commercial Sale</span>
                                    </label>
                                    <label class="p-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:border-brand-cyan flex items-center gap-2 cursor-pointer transition">
                                        <input type="radio" name="purpose" value="land_sale" class="size-4 text-brand-cyan focus:ring-brand-cyan" onchange="syncPurposeChange('land_sale')" {{ old('purpose') === 'land_sale' ? 'checked' : '' }}>
                                        <span>Plot / Land</span>
                                    </label>
                                    <label class="p-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:border-brand-cyan flex items-center gap-2 cursor-pointer transition">
                                        <input type="radio" name="purpose" value="pg" class="size-4 text-brand-cyan focus:ring-brand-cyan" onchange="syncPurposeChange('pg')" {{ old('purpose') === 'pg' ? 'checked' : '' }}>
                                        <span>PG</span>
                                    </label>
                                    <label class="p-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:border-brand-cyan flex items-center gap-2 cursor-pointer transition">
                                        <input type="radio" name="purpose" value="coliving" class="size-4 text-brand-cyan focus:ring-brand-cyan" onchange="syncPurposeChange('coliving')" {{ old('purpose') === 'coliving' ? 'checked' : '' }}>
                                        <span>Co-Living</span>
                                    </label>
                                    <label class="p-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:border-brand-cyan flex items-center gap-2 cursor-pointer transition">
                                        <input type="radio" name="purpose" value="coworking" class="size-4 text-brand-cyan focus:ring-brand-cyan" onchange="syncPurposeChange('coworking')" {{ old('purpose') === 'coworking' ? 'checked' : '' }}>
                                        <span>Coworking</span>
                                    </label>
                                    <label class="p-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:border-brand-cyan flex items-center gap-2 cursor-pointer transition">
                                        <input type="radio" name="purpose" value="project" class="size-4 text-brand-cyan focus:ring-brand-cyan" onchange="syncPurposeChange('project')" {{ old('purpose') === 'project' ? 'checked' : '' }}>
                                        <span>Builder Project</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Property Type Dropdown -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Property Type *</label>
                                <select name="property_type" id="wizard_property_type" required onchange="handlePropertyTypeSelect(this.value)" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan/20 focus:border-brand-cyan transition font-semibold">
                                    <optgroup label="Residential">
                                        <option value="Flat / Apartment" {{ old('property_type', 'Flat / Apartment') === 'Flat / Apartment' ? 'selected' : '' }}>Flat / Apartment</option>
                                        <option value="House / Villa" {{ old('property_type') === 'House / Villa' ? 'selected' : '' }}>House / Villa</option>
                                        <option value="Builder Floor" {{ old('property_type') === 'Builder Floor' ? 'selected' : '' }}>Builder Floor</option>
                                        <option value="Penthouse" {{ old('property_type') === 'Penthouse' ? 'selected' : '' }}>Penthouse</option>
                                        <option value="Studio Apartment" {{ old('property_type') === 'Studio Apartment' ? 'selected' : '' }}>Studio Apartment</option>
                                    </optgroup>
                                    <optgroup label="Commercial">
                                        <option value="Commercial Office" {{ old('property_type') === 'Commercial Office' ? 'selected' : '' }}>Commercial Office</option>
                                        <option value="Retail Shop" {{ old('property_type') === 'Retail Shop' ? 'selected' : '' }}>Retail Shop</option>
                                        <option value="Showroom" {{ old('property_type') === 'Showroom' ? 'selected' : '' }}>Showroom</option>
                                        <option value="Warehouse" {{ old('property_type') === 'Warehouse' ? 'selected' : '' }}>Warehouse</option>
                                    </optgroup>
                                    <optgroup label="Plots & Land">
                                        <option value="Residential Plot" {{ old('property_type') === 'Residential Plot' ? 'selected' : '' }}>Residential Plot</option>
                                        <option value="Commercial Land" {{ old('property_type') === 'Commercial Land' ? 'selected' : '' }}>Commercial Land</option>
                                        <option value="Agricultural Farm Land" {{ old('property_type') === 'Agricultural Farm Land' ? 'selected' : '' }}>Agricultural Farm Land</option>
                                    </optgroup>
                                </select>
                            </div>

                            <!-- Conditional Sub-Options (Flat / Apartment specific) -->
                            <div id="flat-specific-box" class="space-y-5 pt-2">
                                <!-- You are posting as: (Full House vs On sharing basis) -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">You are posting as</label>
                                    <div class="flex items-center gap-6 text-sm font-semibold text-slate-800">
                                        <label class="inline-flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="posting_as_radio" value="Full House" checked onchange="setHiddenField('hidden_posting_as', this.value)" class="size-4 text-brand-cyan focus:ring-brand-cyan">
                                            <span>Full House</span>
                                        </label>
                                        <label class="inline-flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="posting_as_radio" value="On sharing basis" onchange="setHiddenField('hidden_posting_as', this.value)" class="size-4 text-brand-cyan focus:ring-brand-cyan">
                                            <span>On sharing basis</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Total No. of Flats in Your Society -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Total No. of Flats in Your Society</label>
                                    <div class="flex items-center gap-2.5 max-w-sm">
                                        <button type="button" class="society-flats-chip flex-1 py-2 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-700 hover:border-brand-cyan text-center transition" onclick="selectSocietyFlats(this, '<50')">
                                            &lt;50
                                        </button>
                                        <button type="button" class="society-flats-chip flex-1 py-2 rounded-xl border-2 border-brand-cyan bg-brand-cyan/10 text-xs font-bold text-brand-navy text-center transition shadow-sm" onclick="selectSocietyFlats(this, '50-100')">
                                            50-100
                                        </button>
                                        <button type="button" class="society-flats-chip flex-1 py-2 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-700 hover:border-brand-cyan text-center transition" onclick="selectSocietyFlats(this, '>100')">
                                            &gt;100
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- 3. PROPERTY LOCATION -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm">
                        <h3 class="font-display text-lg sm:text-xl font-bold text-brand-navy border-b border-slate-100 pb-3 mb-5 flex items-center gap-2.5">
                            <span class="flex size-7 items-center justify-center rounded-lg bg-brand-cyan/10 text-brand-cyan text-xs font-bold">3</span>
                            <span>Property Location</span>
                        </h3>

                        <div class="space-y-4">
                            <!-- City Autocomplete -->
                            <div class="relative">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">City *</label>
                                <input type="text" name="city" id="city_autocomplete_input" required value="{{ old('city', 'Jaipur') }}" placeholder="Type City (e.g. Jaipur, Mumbai, Delhi, Bengaluru)" autocomplete="off" oninput="handleCitySearch(this.value)" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan/20 focus:border-brand-cyan transition font-bold">
                                
                                <!-- Suggestions Dropdown List -->
                                <div id="city-suggestions-dropdown" class="hidden absolute left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-2xl shadow-2xl z-30 max-h-56 overflow-y-auto divide-y divide-slate-100">
                                    <!-- Populated via JS -->
                                </div>
                            </div>

                            <!-- Locality / Society / Project -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Locality / Society / Project Name *</label>
                                <input type="text" name="locality" id="locality_input" required value="{{ old('locality') }}" placeholder="e.g. Mansarovar, Jagatpura, Vaishali Nagar, Bandra West, HSR Layout" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan/20 focus:border-brand-cyan transition font-medium">
                                <span class="text-[11px] text-slate-400 mt-1 block">Mention the exact sub-locality, colony or apartment complex name.</span>
                            </div>

                            <!-- Street Address / Landmark -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Street Address / Landmark (Optional)</label>
                                <input type="text" name="address" value="{{ old('address') }}" placeholder="e.g. Flat 302, Block A, Near Metro Station" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan/20 focus:border-brand-cyan transition">
                            </div>
                        </div>
                    </div>

                    <!-- 4. PROPERTY FEATURES (Interactive Chips) -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm" id="property-features-section">
                        <h3 class="font-display text-lg sm:text-xl font-bold text-brand-navy border-b border-slate-100 pb-3 mb-5 flex items-center gap-2.5">
                            <span class="flex size-7 items-center justify-center rounded-lg bg-brand-cyan/10 text-brand-cyan text-xs font-bold">4</span>
                            <span>Property Features</span>
                        </h3>

                        <div class="space-y-6">
                            <!-- Bedrooms -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Bedrooms</label>
                                <div class="flex flex-wrap items-center gap-2" id="bedrooms-chips">
                                    <button type="button" class="feature-chip py-2 px-4 rounded-xl border border-slate-200 bg-white text-xs sm:text-sm font-semibold text-slate-700 hover:border-brand-cyan transition" onclick="selectChip(this, 'hidden_bedrooms', '1')">1</button>
                                    <button type="button" class="feature-chip py-2 px-4 rounded-xl border-2 border-brand-cyan bg-brand-cyan/10 text-brand-navy text-xs sm:text-sm font-bold shadow-sm transition" onclick="selectChip(this, 'hidden_bedrooms', '2')">2</button>
                                    <button type="button" class="feature-chip py-2 px-4 rounded-xl border border-slate-200 bg-white text-xs sm:text-sm font-semibold text-slate-700 hover:border-brand-cyan transition" onclick="selectChip(this, 'hidden_bedrooms', '3')">3</button>
                                    <button type="button" class="feature-chip py-2 px-4 rounded-xl border border-slate-200 bg-white text-xs sm:text-sm font-semibold text-slate-700 hover:border-brand-cyan transition" onclick="selectChip(this, 'hidden_bedrooms', '4')">4</button>
                                    <button type="button" class="feature-chip py-2 px-4 rounded-xl border border-slate-200 bg-white text-xs sm:text-sm font-semibold text-slate-700 hover:border-brand-cyan transition" onclick="selectChip(this, 'hidden_bedrooms', '5')">5+ &blacktriangledown;</button>
                                </div>
                            </div>

                            <!-- Balconies -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Balconies</label>
                                <div class="flex flex-wrap items-center gap-2" id="balconies-chips">
                                    <button type="button" class="feature-chip py-2 px-4 rounded-xl border border-slate-200 bg-white text-xs sm:text-sm font-semibold text-slate-700 hover:border-brand-cyan transition" onclick="selectChip(this, 'hidden_balconies', '0')">0</button>
                                    <button type="button" class="feature-chip py-2 px-4 rounded-xl border-2 border-brand-cyan bg-brand-cyan/10 text-brand-navy text-xs sm:text-sm font-bold shadow-sm transition" onclick="selectChip(this, 'hidden_balconies', '1')">1</button>
                                    <button type="button" class="feature-chip py-2 px-4 rounded-xl border border-slate-200 bg-white text-xs sm:text-sm font-semibold text-slate-700 hover:border-brand-cyan transition" onclick="selectChip(this, 'hidden_balconies', '2')">2</button>
                                    <button type="button" class="feature-chip py-2 px-4 rounded-xl border border-slate-200 bg-white text-xs sm:text-sm font-semibold text-slate-700 hover:border-brand-cyan transition" onclick="selectChip(this, 'hidden_balconies', '3')">3</button>
                                    <button type="button" class="feature-chip py-2 px-4 rounded-xl border border-slate-200 bg-white text-xs sm:text-sm font-semibold text-slate-700 hover:border-brand-cyan transition" onclick="selectChip(this, 'hidden_balconies', '4')">3+ &blacktriangledown;</button>
                                </div>
                            </div>

                            <!-- Floor No -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Floor No</label>
                                <div class="flex flex-wrap items-center gap-2" id="floor-no-chips">
                                    <button type="button" class="feature-chip py-2 px-3 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-700 hover:border-brand-cyan transition" onclick="selectChip(this, 'hidden_floor_no', 'Lower Basement')">Lower Basement</button>
                                    <button type="button" class="feature-chip py-2 px-3 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-700 hover:border-brand-cyan transition" onclick="selectChip(this, 'hidden_floor_no', 'Upper Basement')">Upper Basement</button>
                                    <button type="button" class="feature-chip py-2 px-3 rounded-xl border-2 border-brand-cyan bg-brand-cyan/10 text-brand-navy text-xs font-bold shadow-sm transition" onclick="selectChip(this, 'hidden_floor_no', 'Ground')">Ground</button>
                                    <button type="button" class="feature-chip py-2 px-3.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-700 hover:border-brand-cyan transition" onclick="selectChip(this, 'hidden_floor_no', '1')">1</button>
                                    <button type="button" class="feature-chip py-2 px-3.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-700 hover:border-brand-cyan transition" onclick="selectChip(this, 'hidden_floor_no', '2')">2</button>
                                    <button type="button" class="feature-chip py-2 px-3.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-700 hover:border-brand-cyan transition" onclick="selectChip(this, 'hidden_floor_no', '3')">3</button>
                                    <button type="button" class="feature-chip py-2 px-3.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-700 hover:border-brand-cyan transition" onclick="selectChip(this, 'hidden_floor_no', '4')">4</button>
                                    <button type="button" class="feature-chip py-2 px-3.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-700 hover:border-brand-cyan transition" onclick="selectChip(this, 'hidden_floor_no', '5')">5</button>
                                    <button type="button" class="feature-chip py-2 px-3.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-700 hover:border-brand-cyan transition" onclick="selectChip(this, 'hidden_floor_no', '5+')">5+ &blacktriangledown;</button>
                                </div>
                            </div>

                            <!-- Total Floors -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Total Floors</label>
                                <div class="flex flex-wrap items-center gap-1.5" id="total-floors-chips">
                                    @for($f = 1; $f <= 13; $f++)
                                        <button type="button" class="feature-chip py-2 px-3 rounded-xl border {{ $f == 4 ? 'border-2 border-brand-cyan bg-brand-cyan/10 text-brand-navy font-bold shadow-sm' : 'border-slate-200 bg-white text-slate-700' }} text-xs hover:border-brand-cyan transition" onclick="selectChip(this, 'hidden_total_floors', '{{ $f }}')">
                                            {{ $f }}
                                        </button>
                                    @endfor
                                    <button type="button" class="feature-chip py-2 px-3 rounded-xl border border-slate-200 bg-white text-xs text-slate-700 hover:border-brand-cyan transition" onclick="selectChip(this, 'hidden_total_floors', '13+')">
                                        13+ &blacktriangledown;
                                    </button>
                                </div>
                            </div>

                            <!-- Furnished Status -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Furnished Status</label>
                                <div class="flex flex-wrap items-center gap-2.5" id="furnishing-chips">
                                    <button type="button" class="feature-chip py-2.5 px-4 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-700 hover:border-brand-cyan transition" onclick="selectChip(this, 'hidden_furnishing', 'Fully Furnished')">
                                        Fully Furnished
                                    </button>
                                    <button type="button" class="feature-chip py-2.5 px-4 rounded-xl border-2 border-brand-cyan bg-brand-cyan/10 text-brand-navy text-xs font-bold shadow-sm transition" onclick="selectChip(this, 'hidden_furnishing', 'Semi-Furnished')">
                                        Semi-Furnished
                                    </button>
                                    <button type="button" class="feature-chip py-2.5 px-4 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-700 hover:border-brand-cyan transition" onclick="selectChip(this, 'hidden_furnishing', 'Unfurnished')">
                                        Unfurnished
                                    </button>
                                </div>
                            </div>

                            <!-- Bathrooms & Carpet Area Row -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Bathrooms</label>
                                    <div class="flex items-center gap-2">
                                        <button type="button" class="feature-chip py-2 px-4 rounded-xl border border-slate-200 bg-white text-xs font-semibold hover:border-brand-cyan transition" onclick="selectChip(this, 'hidden_bathrooms', '1')">1</button>
                                        <button type="button" class="feature-chip py-2 px-4 rounded-xl border-2 border-brand-cyan bg-brand-cyan/10 text-brand-navy text-xs font-bold shadow-sm transition" onclick="selectChip(this, 'hidden_bathrooms', '2')">2</button>
                                        <button type="button" class="feature-chip py-2 px-4 rounded-xl border border-slate-200 bg-white text-xs font-semibold hover:border-brand-cyan transition" onclick="selectChip(this, 'hidden_bathrooms', '3')">3</button>
                                        <button type="button" class="feature-chip py-2 px-4 rounded-xl border border-slate-200 bg-white text-xs font-semibold hover:border-brand-cyan transition" onclick="selectChip(this, 'hidden_bathrooms', '4')">4+</button>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Carpet / Built-up Area *</label>
                                    <div class="flex items-center rounded-xl border border-slate-200 bg-slate-50 overflow-hidden focus-within:border-brand-cyan focus-within:ring-2 focus-within:ring-brand-cyan/20 transition">
                                        <input type="number" name="sqft" required value="{{ old('sqft', '1250') }}" placeholder="e.g. 1250" class="w-full bg-transparent px-4 py-2.5 text-sm font-bold text-slate-900 focus:outline-none">
                                        <select name="area_unit" class="bg-slate-100 border-l border-slate-200 px-3 py-2.5 text-xs font-bold text-slate-700 focus:outline-none">
                                            <option value="sqft">Sq. Ft</option>
                                            <option value="sq.yards">Sq. Yards</option>
                                            <option value="acres">Acres</option>
                                            <option value="bigha">Bigha</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- 5. PRICING & CHARGES -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm">
                        <h3 class="font-display text-lg sm:text-xl font-bold text-brand-navy border-b border-slate-100 pb-3 mb-5 flex items-center gap-2.5">
                            <span class="flex size-7 items-center justify-center rounded-lg bg-brand-cyan/10 text-brand-cyan text-xs font-bold">5</span>
                            <span>Pricing &amp; Charges</span>
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <!-- Expected Price / Rent -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" id="wizard-price-label">
                                    Expected Monthly Rent / Total Price (in ₹) *
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-3 text-slate-400 font-bold text-base">₹</span>
                                    <input type="number" name="price" id="wizard_price_input" required value="{{ old('price', '25000') }}" placeholder="e.g. 25000" oninput="updateLivePriceText(this.value)" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl pl-8 pr-4 py-3 text-base font-black focus:outline-none focus:ring-2 focus:ring-brand-cyan/20 focus:border-brand-cyan transition">
                                </div>
                                <div class="mt-1.5 flex items-center justify-between text-xs">
                                    <span id="wizard-price-words" class="font-bold text-brand-cyan">₹ 25,000 / month</span>
                                    <span class="text-[11px] text-slate-400">All prices in INR</span>
                                </div>
                            </div>

                            <!-- Security Deposit (Optional for Rent / PG) -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Security Deposit (₹)
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-3 text-slate-400 font-bold text-base">₹</span>
                                    <input type="number" name="deposit_amount" value="{{ old('deposit_amount') }}" placeholder="e.g. 50000" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl pl-8 pr-4 py-3 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-brand-cyan/20 focus:border-brand-cyan transition">
                                </div>
                                <span class="text-[11px] text-slate-400 mt-1 block">Usually 1-2 months rent for residential / PG.</span>
                            </div>

                            <!-- Monthly Maintenance -->
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Maintenance Charges
                                </label>
                                <input type="text" name="maintenance" value="{{ old('maintenance', 'Included in rent') }}" placeholder="e.g. ₹ 2,000 / month or Included" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan/20 focus:border-brand-cyan transition font-medium">
                            </div>
                        </div>

                        <!-- CLEAR DISTINCTION: Free Leasing vs Optional Property Management -->
                        <div class="mt-6 p-5 rounded-2xl border-2 border-dashed border-brand-cyan/40 bg-brand-cyan/5">
                            <div class="flex items-start gap-3">
                                <div class="size-6 rounded-full bg-brand-cyan text-white flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">
                                    <i class="fa-solid fa-handshake"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-brand-navy">Choose Your Housing365 Service Model</h4>
                                    <p class="text-xs text-slate-600 mt-0.5">
                                        Standard residential listing and tenant placement on Housing 365 is <strong>100% FREE (₹0 Brokerage)</strong>. You only pay if you voluntarily opt in to full-service property management.
                                    </p>
                                    <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                                        <label class="p-3 rounded-xl border border-slate-200 bg-white hover:border-brand-cyan cursor-pointer flex items-start gap-2.5">
                                            <input type="radio" name="opt_in_management" value="0" checked class="size-4 text-brand-cyan mt-0.5">
                                            <div>
                                                <span class="font-bold text-brand-navy block">Leasing Only (₹0 Free)</span>
                                                <span class="text-slate-500 text-[11px]">Free listing, free enquiries, you manage property directly.</span>
                                            </div>
                                        </label>
                                        <label class="p-3 rounded-xl border border-slate-200 bg-white hover:border-brand-cyan cursor-pointer flex items-start gap-2.5">
                                            <input type="radio" name="opt_in_management" value="1" class="size-4 text-brand-cyan mt-0.5">
                                            <div>
                                                <span class="font-bold text-brand-navy block">Leasing + Property Management</span>
                                                <span class="text-slate-500 text-[11px]">Optional paid service (rent guarantee, vendor maintenance, inspection).</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 6. PHOTOS, TITLE & AMENITIES -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm">
                        <h3 class="font-display text-lg sm:text-xl font-bold text-brand-navy border-b border-slate-100 pb-3 mb-5 flex items-center gap-2.5">
                            <span class="flex size-7 items-center justify-center rounded-lg bg-brand-cyan/10 text-brand-cyan text-xs font-bold">6</span>
                            <span>Photos, Description &amp; Amenities</span>
                        </h3>

                        <div class="space-y-5">
                            <!-- Optional Title -->
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Property Title</label>
                                    <span class="text-[11px] text-slate-400">Auto-generated if left blank</span>
                                </div>
                                <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. Spacious 2 BHK Apartment in Mansarovar with Modular Kitchen" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan/20 focus:border-brand-cyan transition">
                            </div>

                            <!-- Description -->
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Description</label>
                                    <span class="text-[11px] text-slate-400">Auto-generated with complete specs if blank</span>
                                </div>
                                <textarea name="description" rows="3" placeholder="Highlight property advantages: road connectivity, sunlight, nearby metro/schools, water supply..." class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl p-3.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan/20 focus:border-brand-cyan transition leading-relaxed">{{ old('description') }}</textarea>
                            </div>

                            <!-- Purpose-specific Additional Details (PG / Builder) -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 rounded-2xl bg-[#F8FAFD] border border-slate-200">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">PG / Co-Living Gender</label>
                                    <select name="pg_gender" class="w-full bg-white border border-slate-200 text-slate-900 rounded-xl px-3 py-2 text-xs font-medium">
                                        <option value="">Not Applicable / Unisex</option>
                                        <option value="boys">Boys Only</option>
                                        <option value="girls">Girls Only</option>
                                        <option value="unisex">Unisex / All Welcome</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">PG Occupancy Type</label>
                                    <input type="text" name="occupancy_type" placeholder="e.g. Single Room, Twin Sharing" class="w-full bg-white border border-slate-200 text-slate-900 rounded-xl px-3 py-2 text-xs font-medium">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Project Name (For Builders)</label>
                                    <input type="text" name="project_name" placeholder="e.g. Royal Heights Phase 2" class="w-full bg-white border border-slate-200 text-slate-900 rounded-xl px-3 py-2 text-xs font-medium">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">RERA Registration No.</label>
                                    <input type="text" name="rera_number" placeholder="e.g. RAJ/P/2024/1234" class="w-full bg-white border border-slate-200 text-slate-900 rounded-xl px-3 py-2 text-xs font-medium">
                                </div>
                            </div>

                            <!-- Amenities Grid -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Amenities &amp; Society Features</label>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 text-xs font-semibold text-slate-700">
                                    @php
                                        $amenitiesList = [
                                            ['Lift Access', 'fa-elevator'],
                                            ['Covered Parking', 'fa-square-parking'],
                                            ['24/7 Security & CCTV', 'fa-shield-halved'],
                                            ['Power Backup', 'fa-bolt'],
                                            ['Gated Society', 'fa-door-closed'],
                                            ['Park / Garden', 'fa-tree'],
                                            ['Gym / Fitness', 'fa-dumbbell'],
                                            ['Swimming Pool', 'fa-person-swimming'],
                                            ['Gas Pipeline', 'fa-fire-flame-simple'],
                                        ];
                                    @endphp
                                    @foreach($amenitiesList as $am)
                                        <label class="flex items-center gap-2 p-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200/80 cursor-pointer transition">
                                            <input type="checkbox" name="amenities[]" value="{{ $am[0] }}" checked class="size-4 text-brand-cyan focus:ring-brand-cyan rounded">
                                            <i class="fa-solid {{ $am[1] }} text-brand-cyan text-xs"></i>
                                            <span>{{ $am[0] }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Photo Upload (Min 5, Max 12 Mandatory) -->
                            <div class="pt-5 border-t border-slate-100" id="photo-upload-section">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider">
                                            Upload Property Photos (Min 5, Max 12 Photos Mandatory) *
                                        </label>
                                        <span class="text-[11px] text-slate-500">कम से कम 5 फोटो एवं अधिकतम 12 फोटो अपलोड करना अनिवार्य है।</span>
                                    </div>
                                    <!-- Dynamic Badge Status -->
                                    <div id="photo-count-badge" class="px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200 flex items-center gap-1.5 self-start sm:self-auto">
                                        <i class="fa-solid fa-camera text-xs"></i>
                                        <span id="photo-count-text">0 / 12</span>
                                        <span class="text-[10px] opacity-80" id="photo-count-status">(Min 5 required)</span>
                                    </div>
                                </div>

                                <!-- Validation Alert (hidden by default) -->
                                <div id="photo-validation-alert" class="hidden mb-3 p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center gap-2.5">
                                    <i class="fa-solid fa-circle-exclamation text-rose-600 text-base shrink-0"></i>
                                    <span id="photo-validation-message">कम से कम 5 फोटो अपलोड करना अनिवार्य है (Please select at least 5 photos before publishing).</span>
                                </div>

                                <!-- Multi-Photo Dropzone -->
                                <div id="photos-main-dropzone" 
                                     ondragover="handleDragOver(event)" 
                                     ondragleave="handleDragLeave(event)" 
                                     ondrop="handleDrop(event)"
                                     class="relative p-6 sm:p-8 border-2 border-dashed border-slate-300 hover:border-brand-cyan rounded-2xl cursor-pointer bg-slate-50 hover:bg-brand-cyan/5 transition-all text-center group">
                                    
                                    <input type="file" 
                                           name="photos[]" 
                                           id="property_photos_input" 
                                           accept="image/jpeg,image/png,image/webp,image/jpg" 
                                           multiple 
                                           class="sr-only" 
                                           onchange="handlePhotosSelected(event)">

                                    <div class="pointer-events-none flex flex-col items-center justify-center">
                                        <div class="size-14 rounded-2xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-2xl mb-3 group-hover:scale-110 transition-transform">
                                            <i class="fa-solid fa-cloud-arrow-up"></i>
                                        </div>
                                        <span class="text-sm font-bold text-brand-navy">Click or Drag &amp; Drop Photos Here</span>
                                        <p class="text-xs text-slate-500 mt-1">Select multiple photos at once (Min 5 to Max 12 images)</p>
                                        <div class="mt-2.5 flex items-center gap-2 text-[11px] text-slate-400 font-medium">
                                            <span>JPEG, PNG, WebP up to 15MB</span>
                                            <span>&bull;</span>
                                            <span class="text-brand-cyan font-bold">1st photo will be Cover Photo</span>
                                        </div>
                                    </div>
                                    <button type="button" onclick="document.getElementById('property_photos_input').click()" class="mt-4 px-5 py-2 rounded-xl bg-white border border-slate-200 text-brand-navy text-xs font-bold shadow-sm hover:border-brand-cyan hover:text-brand-cyan transition">
                                        Browse Photos
                                    </button>
                                </div>

                                <!-- Previews Grid -->
                                <div id="photos-preview-grid" class="hidden mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                                    <!-- Populated dynamically via JS -->
                                </div>

                                <!-- Or Image URL fallback (Optional) -->
                                <div class="mt-3 flex items-center gap-2">
                                    <button type="button" onclick="toggleImageUrlInput()" class="text-[11px] text-slate-500 hover:text-brand-cyan font-semibold flex items-center gap-1 transition">
                                        <i class="fa-solid fa-link text-[10px]"></i>
                                        <span>Have an image URL instead? Click here</span>
                                    </button>
                                </div>
                                <div id="image-url-container" class="hidden mt-2">
                                    <input type="url" name="image_url" placeholder="https://... (Optional Image URL)" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:outline-none focus:ring-1 focus:ring-brand-cyan font-medium">
                                </div>
                            </div>

                            <!-- Video Upload Section (Optional 1 Video) -->
                            <div class="pt-5 border-t border-slate-100" id="video-upload-section">
                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                                            <i class="fa-solid fa-video text-brand-cyan"></i>
                                            <span>Property Video Walkthrough / Tour (Optional - 1 Video)</span>
                                        </label>
                                        <span class="text-[11px] text-slate-500">प्रॉपर्टी का वॉकथ्रू वीडियो जोड़ें (वैकल्पिक / Optional)</span>
                                    </div>
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">Max 1 Video</span>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <!-- Option A: File Upload -->
                                    <div class="relative p-4 border-2 border-dashed border-slate-200 hover:border-brand-cyan rounded-2xl bg-slate-50 hover:bg-brand-cyan/5 transition text-center flex flex-col items-center justify-center">
                                        <input type="file" 
                                               name="video_file" 
                                               id="property_video_input" 
                                               accept="video/mp4,video/webm,video/quicktime,video/mkv" 
                                               class="sr-only" 
                                               onchange="previewSelectedVideo(this)">
                                        
                                        <div class="size-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-lg mb-2">
                                            <i class="fa-solid fa-file-video"></i>
                                        </div>
                                        <span class="text-xs font-bold text-brand-navy">Upload Video File</span>
                                        <span class="text-[10px] text-slate-400 mt-0.5">MP4, WebM, MOV (Max 100MB)</span>
                                        <button type="button" onclick="document.getElementById('property_video_input').click()" class="mt-2.5 px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-xs font-bold text-brand-navy hover:border-brand-cyan hover:text-brand-cyan transition shadow-2xs">
                                            Choose Video
                                        </button>

                                        <!-- Selected Video Notice -->
                                        <div id="selected-video-notice" class="hidden mt-2 p-2 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-[11px] font-bold flex items-center justify-between w-full">
                                            <div class="flex items-center gap-1.5 truncate">
                                                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                                                <span id="selected-video-name" class="truncate">video.mp4</span>
                                                <span id="selected-video-size" class="text-[10px] font-normal text-emerald-600">(0 MB)</span>
                                            </div>
                                            <button type="button" onclick="removeSelectedVideo()" class="text-rose-600 hover:text-rose-800 ml-2" title="Remove video">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Option B: Video Link / URL -->
                                    <div class="p-4 border border-slate-200 rounded-2xl bg-slate-50 flex flex-col justify-between">
                                        <div>
                                            <div class="flex items-center gap-2 mb-1.5">
                                                <i class="fa-brands fa-youtube text-red-600 text-base"></i>
                                                <span class="text-xs font-bold text-brand-navy">Or Video Link / Walkthrough URL</span>
                                            </div>
                                            <p class="text-[10px] text-slate-500 mb-2">Paste YouTube, Vimeo, Google Drive or Cloud video link</p>
                                        </div>
                                        <div class="relative">
                                            <input type="text" 
                                                   name="video_url" 
                                                   id="wizard_video_url"
                                                   value="{{ old('video_url') }}"
                                                   placeholder="https://youtube.com/watch?v=... or https://..." 
                                                   class="w-full bg-white border border-slate-200 text-slate-900 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-1 focus:ring-brand-cyan font-medium">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- SUBMIT BAR -->
                    <div class="p-6 bg-white rounded-3xl border border-slate-200/90 shadow-lg flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-xs text-slate-600">
                            <span class="font-bold text-brand-navy text-sm block">100% Free Live Publication</span>
                            Your listing will instantly reach thousands of genuine tenants and buyers.
                        </div>
                        <button type="submit" class="w-full sm:w-auto py-4 px-10 rounded-xl bg-brand-cyan hover:bg-brand-cyan-hover text-white font-bold text-base shadow-xl shadow-brand-cyan/25 transition-all duration-200 hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            <span>Publish Property Free</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>
                    </div>

                </form>
            </div>

            <!-- RIGHT STICKY HELPER SIDEBAR ("How to find the right Tenant / Buyer?") -->
            <div class="lg:col-span-4 sticky top-24 space-y-6">
                <!-- Helper Guide Card -->
                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm space-y-5">
                    <h3 class="font-display text-base font-bold text-brand-navy">
                        How to find the right <span id="sidebar-target-role" class="text-brand-cyan">Tenant</span>?
                    </h3>

                    <!-- Item 1: Respond to Inquiries -->
                    <div class="flex items-start gap-3.5">
                        <div class="size-10 rounded-full bg-brand-cyan/10 border border-brand-cyan/20 text-brand-cyan flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-phone-volume text-sm"></i>
                        </div>
                        <div class="text-xs leading-relaxed text-slate-600">
                            <h4 class="font-bold text-brand-navy text-sm mb-0.5">Respond to Inquiries</h4>
                            Connect with verified searchers when they contact on your property. <strong class="text-brand-navy">View contact details 100% FREE!</strong>
                        </div>
                    </div>

                    <!-- Item 2: Connect with Matching Leads -->
                    <div class="flex items-start gap-3.5">
                        <div class="size-10 rounded-full bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-user-check text-sm"></i>
                        </div>
                        <div class="text-xs leading-relaxed text-slate-600">
                            <h4 class="font-bold text-brand-navy text-sm mb-0.5">Connect with Matching Leads</h4>
                            Actively check algorithm-matched buyers &amp; tenants looking in your locality.
                        </div>
                    </div>

                    <!-- Item 3: Direct WhatsApp Updates -->
                    <div class="flex items-start gap-3.5">
                        <div class="size-10 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center shrink-0">
                            <i class="fa-brands fa-whatsapp text-base"></i>
                        </div>
                        <div class="text-xs leading-relaxed text-slate-600">
                            <h4 class="font-bold text-brand-navy text-sm mb-0.5">Instant WhatsApp Alerts</h4>
                            Get notified the moment a new enquiry is received and connect instantly.
                        </div>
                    </div>
                </div>

                <!-- Concierge / Expert Hotline Card (Consistent with brand navy theme) -->
                <div class="bg-gradient-to-br from-brand-navy to-brand-navy-light rounded-3xl p-6 text-white shadow-xl shadow-brand-navy/15 border border-white/10 space-y-4">
                    <div class="flex items-center gap-3">
                        <span class="flex size-10 items-center justify-center rounded-xl bg-white/10 text-brand-cyan text-lg">
                            <i class="fa-solid fa-headset"></i>
                        </span>
                        <div>
                            <h4 class="text-sm font-bold">Property Expert Assistance</h4>
                            <p class="text-[11px] text-slate-300">Free dedicated listing support</p>
                        </div>
                    </div>
                    <p class="text-xs text-slate-200 leading-relaxed">
                        Don't have time to fill details? Our executive will list your property for free over a quick phone call.
                    </p>
                    <a href="tel:+916376163263" class="w-full inline-flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-brand-cyan hover:bg-brand-cyan-hover text-white text-xs font-bold transition shadow-lg shadow-brand-cyan/25">
                        <i class="fa-solid fa-phone"></i>
                        <span>Call +91 63761 63263</span>
                    </a>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- ══════════════════════════════════════════════════════════════════ -->
<!-- PROACTIVE ASSISTANCE MODAL (Consistent Brand Theme)                 -->
<!-- ══════════════════════════════════════════════════════════════════ -->
<div id="expert-assistance-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
    <div class="relative w-full max-w-md bg-white rounded-3xl p-6 sm:p-8 shadow-2xl transform scale-95 transition-transform duration-300 border border-slate-100" id="assistance-modal-box">
        <!-- Close 'X' -->
        <button type="button" onclick="closeAssistanceModal()" class="absolute top-4 right-4 size-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition">
            <i class="fa-solid fa-xmark text-sm"></i>
        </button>

        <div class="text-center space-y-2 mb-6">
            <h3 class="font-display text-xl sm:text-2xl font-bold text-brand-navy">
                Need help in posting your property?
            </h3>
            <p class="text-sm text-slate-600">
                Let our Property Expert help you.
            </p>
        </div>

        <form id="assistance-form" onsubmit="submitAssistanceRequest(event)" class="space-y-5">
            @csrf
            <div>
                <div class="flex items-center rounded-xl border border-slate-200 bg-slate-50 overflow-hidden focus-within:border-brand-cyan focus-within:ring-2 focus-within:ring-brand-cyan/20 transition">
                    <span class="px-3.5 py-3 border-r border-slate-200 text-brand-navy font-bold text-sm bg-slate-100">
                        +91
                    </span>
                    <input type="tel" name="phone" id="modal-phone-input" required value="6376163263" class="w-full bg-transparent px-4 py-3 text-base font-bold text-slate-900 focus:outline-none">
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="button" onclick="closeAssistanceModal()" class="w-1/2 py-3 rounded-xl border border-slate-300 text-slate-700 font-bold text-sm hover:bg-slate-50 transition text-center">
                    Maybe Later
                </button>
                <button type="submit" id="modal-submit-btn" class="w-1/2 py-3 rounded-xl bg-brand-cyan hover:bg-brand-cyan-hover text-white font-bold text-sm shadow-lg shadow-brand-cyan/25 transition text-center">
                    Submit
                </button>
            </div>
        </form>

        <div id="modal-success-msg" class="hidden mt-4 p-3 rounded-xl bg-emerald-50 text-emerald-800 text-xs font-semibold text-center border border-emerald-200">
            <i class="fa-solid fa-circle-check text-emerald-600 mr-1.5"></i>
            Request received! Our expert will call you shortly.
        </div>

        <!-- Footer hotline text -->
        <div class="mt-6 pt-5 border-t border-dashed border-slate-200 text-center text-xs text-slate-500">
            Need some help? Give us a call at <a href="tel:+916376163263" class="font-bold text-brand-navy hover:text-brand-cyan">+91 63761 63263</a>
        </div>
    </div>
</div>

<!-- Floating 'Need Help?' Badge on Bottom-Right (Consistent Brand Theme) -->
<div class="fixed bottom-6 right-6 z-40">
    <button type="button" onclick="openAssistanceModal()" class="inline-flex items-center gap-2.5 bg-brand-navy hover:bg-brand-navy-light text-white font-bold text-xs py-3 px-5 rounded-full shadow-2xl shadow-brand-navy/40 border border-brand-cyan/30 transition-all duration-200 transform hover:scale-105">
        <i class="fa-solid fa-headset text-brand-cyan text-sm"></i>
        <span>Need Help? Free Call</span>
    </button>
</div>

@endsection

@php
    $citiesJson = json_encode($cities ?? ['Jaipur', 'Jaipur Suburbs', 'Mumbai', 'Delhi', 'Bengaluru', 'Gurgaon', 'Noida', 'Pune', 'Hyderabad', 'Ahmedabad', 'Kolkata', 'Chennai', 'Chandigarh']);
@endphp

@push('scripts')
<script>
    // State variables
    let selectedStarterRole = 'Owner';
    let selectedStarterPurpose = 'rent';
    const CITIES_LIST = {!! $citiesJson !!};

    // 1. Starter Role Selection (Chips)
    function selectStarterRole(btn) {
        document.querySelectorAll('.starter-role-chip').forEach(b => {
            b.classList.remove('border-2', 'border-brand-cyan', 'bg-brand-cyan/10', 'text-brand-navy', 'font-bold', 'shadow-sm');
            b.classList.add('border-slate-200', 'bg-slate-50', 'text-slate-700');
        });
        btn.classList.remove('border-slate-200', 'bg-slate-50', 'text-slate-700');
        btn.classList.add('border-2', 'border-brand-cyan', 'bg-brand-cyan/10', 'text-brand-navy', 'font-bold', 'shadow-sm');
        selectedStarterRole = btn.getAttribute('data-role');
        document.getElementById('starter-role-error').classList.add('hidden');
    }

    // 2. Starter Purpose Selection (Chips)
    function selectStarterPurpose(btn) {
        document.querySelectorAll('.starter-purpose-chip').forEach(b => {
            b.classList.remove('border-2', 'border-brand-cyan', 'bg-brand-cyan/10', 'text-brand-navy', 'font-bold', 'shadow-sm');
            b.classList.add('border-slate-200', 'bg-slate-50', 'text-slate-700');
        });
        btn.classList.remove('border-slate-200', 'bg-slate-50', 'text-slate-700');
        btn.classList.add('border-2', 'border-brand-cyan', 'bg-brand-cyan/10', 'text-brand-navy', 'font-bold', 'shadow-sm');
        selectedStarterPurpose = btn.getAttribute('data-purpose');
        document.getElementById('starter-purpose-error').classList.add('hidden');
    }

    // 3. Start Listing Wizard (Transition from Stage 1 -> Stage 2)
    function startListingWizard() {
        let hasError = false;
        if (!selectedStarterRole) {
            document.getElementById('starter-role-error').classList.remove('hidden');
            hasError = true;
        }
        if (!selectedStarterPurpose) {
            document.getElementById('starter-purpose-error').classList.remove('hidden');
            hasError = true;
        }
        const phone = document.getElementById('starter-phone-input').value.trim();
        if (phone.length < 10) {
            document.getElementById('starter-phone-error').classList.remove('hidden');
            hasError = true;
        } else {
            document.getElementById('starter-phone-error').classList.add('hidden');
        }

        if (hasError) return;

        // Bridge values to Stage 2
        const roleRadio = document.querySelector(`input[name="posted_by"][value="${selectedStarterRole}"]`);
        if (roleRadio) roleRadio.checked = true;

        let mappedPurpose = selectedStarterPurpose === 'pg' ? 'lease' : selectedStarterPurpose;
        const purposeRadio = document.querySelector(`input[name="purpose"][value="${mappedPurpose}"]`);
        if (purposeRadio) purposeRadio.checked = true;
        syncPurposeChange(mappedPurpose);

        document.getElementById('wizard_phone').value = phone;
        document.getElementById('modal-phone-input').value = phone;

        document.getElementById('stage-1-container').classList.add('hidden');
        const stage2 = document.getElementById('stage-2-container');
        stage2.classList.remove('hidden');
        stage2.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function backToStarter() {
        document.getElementById('stage-2-container').classList.add('hidden');
        document.getElementById('stage-1-container').classList.remove('hidden');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // 4. Purpose Change Synchronization
    function syncPurposeChange(purpose) {
        const priceLabel = document.getElementById('wizard-price-label');
        const priceInput = document.getElementById('wizard_price_input');
        const sidebarTarget = document.getElementById('sidebar-target-role');

        if (purpose === 'sale') {
            priceLabel.textContent = 'Expected Selling Price (in ₹) *';
            priceInput.placeholder = 'e.g. 8500000';
            sidebarTarget.textContent = 'Buyer';
        } else {
            priceLabel.textContent = 'Expected Monthly Rent (in ₹) *';
            priceInput.placeholder = 'e.g. 25000';
            sidebarTarget.textContent = 'Tenant';
        }
        updateLivePriceText(priceInput.value);
    }

    function updateLivePriceText(val) {
        const num = parseFloat(val);
        const display = document.getElementById('wizard-price-words');
        if (!num || isNaN(num) || num <= 0) {
            display.textContent = 'Enter numbers only';
            return;
        }
        const isSale = document.querySelector('input[name="purpose"]:checked')?.value === 'sale';
        if (isSale) {
            if (num >= 10000000) {
                display.textContent = '≈ ₹ ' + (num / 10000000).toFixed(2) + ' Crore (Total Price)';
            } else if (num >= 100000) {
                display.textContent = '≈ ₹ ' + (num / 100000).toFixed(2) + ' Lakhs (Total Price)';
            } else {
                display.textContent = '₹ ' + num.toLocaleString('en-IN') + ' (Total Price)';
            }
        } else {
            display.textContent = '₹ ' + num.toLocaleString('en-IN') + ' / month';
        }
    }

    // 5. Generic Chip Selection
    function selectChip(btn, hiddenInputId, value) {
        const parent = btn.parentElement;
        parent.querySelectorAll('.feature-chip').forEach(b => {
            b.classList.remove('border-2', 'border-brand-cyan', 'bg-brand-cyan/10', 'text-brand-navy', 'font-bold', 'shadow-sm');
            b.classList.add('border-slate-200', 'bg-white', 'text-slate-700');
        });
        btn.classList.remove('border-slate-200', 'bg-white', 'text-slate-700');
        btn.classList.add('border-2', 'border-brand-cyan', 'bg-brand-cyan/10', 'text-brand-navy', 'font-bold', 'shadow-sm');
        document.getElementById(hiddenInputId).value = value;
    }

    function selectSocietyFlats(btn, value) {
        document.querySelectorAll('.society-flats-chip').forEach(b => {
            b.classList.remove('border-2', 'border-brand-cyan', 'bg-brand-cyan/10', 'text-brand-navy', 'font-bold', 'shadow-sm');
            b.classList.add('border-slate-200', 'bg-white', 'text-slate-700');
        });
        btn.classList.remove('border-slate-200', 'bg-white', 'text-slate-700');
        btn.classList.add('border-2', 'border-brand-cyan', 'bg-brand-cyan/10', 'text-brand-navy', 'font-bold', 'shadow-sm');
        document.getElementById('hidden_flats_society').value = value;
    }

    function setHiddenField(id, val) {
        document.getElementById(id).value = val;
    }

    // 6. Property Type Select (Flat vs Commercial vs Plot)
    function handlePropertyTypeSelect(type) {
        const flatBox = document.getElementById('flat-specific-box');
        const isFlat = type === 'Flat / Apartment' || type === 'Builder Floor';
        const isPlot = type.includes('Plot') || type.includes('Land');

        if (flatBox) flatBox.style.display = isFlat ? 'block' : 'none';
        if (isPlot) {
            document.getElementById('hidden_category').value = 'plot_land';
        } else if (type.includes('Commercial') || type.includes('Shop') || type.includes('Office') || type.includes('Warehouse')) {
            document.getElementById('hidden_category').value = 'commercial';
        } else {
            document.getElementById('hidden_category').value = 'residential';
        }
    }

    // 7. City Autocomplete Search
    function handleCitySearch(val) {
        const dropdown = document.getElementById('city-suggestions-dropdown');
        dropdown.innerHTML = '';
        if (!val || val.trim().length === 0) {
            dropdown.classList.add('hidden');
            return;
        }

        const query = val.toLowerCase().trim();
        const matches = CITIES_LIST.filter(c => c.toLowerCase().includes(query));

        let results = [...matches];
        if (query.startsWith('jaip') || query.startsWith('jai')) {
            if (!results.includes('Jaipur')) results.unshift('Jaipur');
            if (!results.includes('Jaipur Suburbs')) results.push('Jaipur Suburbs');
        }

        if (results.length === 0) {
            dropdown.classList.add('hidden');
            return;
        }

        results.slice(0, 6).forEach(city => {
            const item = document.createElement('div');
            item.className = 'px-4 py-2.5 text-sm text-slate-800 font-semibold hover:bg-brand-cyan/10 hover:text-brand-navy cursor-pointer flex items-center justify-between transition';
            item.innerHTML = `<span>${city}</span> <span class="text-[11px] text-slate-400 font-normal">India</span>`;
            item.onclick = function() {
                document.getElementById('city_autocomplete_input').value = city;
                dropdown.classList.add('hidden');
            };
            dropdown.appendChild(item);
        });
        dropdown.classList.remove('hidden');
    }

    // Close city dropdown when clicking outside
    document.addEventListener('click', function(e) {
        const dropdown = document.getElementById('city-suggestions-dropdown');
        const input = document.getElementById('city_autocomplete_input');
        if (dropdown && !dropdown.contains(e.target) && e.target !== input) {
            dropdown.classList.add('hidden');
        }
    });

    // 8. Multi-Photo Upload & Limit Enforcement (Min 5, Max 12)
    let selectedPhotos = [];
    const MIN_PHOTOS = 5;
    const MAX_PHOTOS = 12;

    function handlePhotosSelected(event) {
        const files = Array.from(event.target.files || []);
        addPhotosToSelection(files);
        event.target.value = ''; // Reset input to allow re-selection
    }

    function addMorePhotos(input) {
        if (input.files && input.files.length > 0) {
            addPhotosToSelection(Array.from(input.files));
            input.value = '';
        }
    }

    function addPhotosToSelection(newFiles) {
        const validationAlert = document.getElementById('photo-validation-alert');
        if (validationAlert) validationAlert.classList.add('hidden');

        for (let file of newFiles) {
            if (!file.type.startsWith('image/')) continue;
            if (selectedPhotos.length >= MAX_PHOTOS) {
                alert(`अधिकतम ${MAX_PHOTOS} फोटो ही अपलोड कर सकते हैं (Maximum ${MAX_PHOTOS} photos limit reached).`);
                break;
            }
            // Avoid duplicate by name & size
            const isDuplicate = selectedPhotos.some(p => p.name === file.name && p.size === file.size);
            if (!isDuplicate) {
                selectedPhotos.push(file);
            }
        }
        syncPhotosInput();
        renderPhotosPreviews();
    }

    function removePhoto(index) {
        selectedPhotos.splice(index, 1);
        syncPhotosInput();
        renderPhotosPreviews();
    }

    function syncPhotosInput() {
        const input = document.getElementById('property_photos_input');
        if (!input) return;
        try {
            const dt = new DataTransfer();
            selectedPhotos.forEach(f => dt.items.add(f));
            input.files = dt.files;
        } catch (e) {
            console.error('DataTransfer sync error:', e);
        }
    }

    function renderPhotosPreviews() {
        const grid = document.getElementById('photos-preview-grid');
        const badge = document.getElementById('photo-count-badge');
        const countText = document.getElementById('photo-count-text');
        const countStatus = document.getElementById('photo-count-status');
        const count = selectedPhotos.length;

        if (countText) countText.textContent = `${count} / ${MAX_PHOTOS}`;

        if (countStatus && badge) {
            if (count === 0) {
                countStatus.textContent = `(कम से कम 5 फोटो अनिवार्य)`;
                badge.className = 'px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200 flex items-center gap-1.5 self-start sm:self-auto';
            } else if (count < MIN_PHOTOS) {
                countStatus.textContent = `(${MIN_PHOTOS - count} और आवश्यक / Need ${MIN_PHOTOS - count} more)`;
                badge.className = 'px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200 flex items-center gap-1.5 self-start sm:self-auto';
            } else {
                countStatus.textContent = `(शर्त पूरी हुई / ${count} Photos Ready)`;
                badge.className = 'px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 flex items-center gap-1.5 self-start sm:self-auto';
            }
        }

        if (!grid) return;

        if (count === 0) {
            grid.innerHTML = '';
            grid.classList.add('hidden');
            return;
        }

        grid.classList.remove('hidden');
        grid.innerHTML = '';

        selectedPhotos.forEach((file, idx) => {
            const card = document.createElement('div');
            card.className = 'relative group aspect-square rounded-2xl overflow-hidden border-2 ' + 
                (idx === 0 ? 'border-brand-cyan shadow-md shadow-brand-cyan/20 ring-2 ring-brand-cyan/20' : 'border-slate-200') + 
                ' bg-slate-900 flex items-center justify-center transition-all duration-200';

            const img = document.createElement('img');
            img.className = 'w-full h-full object-cover';
            img.src = URL.createObjectURL(file);
            img.alt = file.name;

            // Index / Cover Badge
            const topBadge = document.createElement('span');
            topBadge.className = 'absolute top-2 left-2 px-2 py-0.5 rounded-lg text-[10px] font-extrabold uppercase tracking-wider backdrop-blur-md ' +
                (idx === 0 ? 'bg-brand-cyan text-white shadow-sm' : 'bg-slate-950/75 text-white');
            topBadge.innerHTML = idx === 0 ? '<i class="fa-solid fa-star text-[9px] mr-1"></i> Cover' : `#${idx + 1}`;

            // Remove Button
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.title = 'Remove photo';
            removeBtn.className = 'absolute top-2 right-2 size-6 rounded-full bg-rose-600/90 hover:bg-rose-600 text-white flex items-center justify-center text-xs shadow-md transition-transform hover:scale-110';
            removeBtn.innerHTML = '<i class="fa-solid fa-xmark"></i>';
            removeBtn.onclick = function(e) {
                e.stopPropagation();
                removePhoto(idx);
            };

            // Bottom metadata strip
            const meta = document.createElement('div');
            meta.className = 'absolute inset-x-0 bottom-0 bg-gradient-to-t from-slate-950/90 via-slate-950/50 to-transparent p-2 pt-4 text-white text-[10px] truncate';
            meta.innerHTML = `<span class="truncate block font-semibold">${file.name}</span><span class="text-[9px] text-slate-300">${(file.size / 1024 / 1024).toFixed(1)} MB</span>`;

            card.appendChild(img);
            card.appendChild(topBadge);
            card.appendChild(removeBtn);
            card.appendChild(meta);
            grid.appendChild(card);
        });

        // Add "+ Add More Photos" button tile inside grid if < MAX_PHOTOS
        if (count < MAX_PHOTOS) {
            const addTile = document.createElement('label');
            addTile.className = 'aspect-square rounded-2xl border-2 border-dashed border-slate-300 hover:border-brand-cyan bg-slate-50 hover:bg-brand-cyan/5 flex flex-col items-center justify-center p-3 text-center cursor-pointer transition-all';
            addTile.innerHTML = `
                <div class="size-8 rounded-full bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-sm mb-1.5">
                    <i class="fa-solid fa-plus"></i>
                </div>
                <span class="text-xs font-bold text-brand-navy">Add More</span>
                <span class="text-[10px] text-slate-400">Up to ${MAX_PHOTOS}</span>
                <input type="file" accept="image/jpeg,image/png,image/jpg,image/webp" multiple class="sr-only" onchange="addMorePhotos(this)">
            `;
            grid.appendChild(addTile);
        }
    }

    // Drag and Drop handlers
    function handleDragOver(e) {
        e.preventDefault();
        e.stopPropagation();
        const dropzone = document.getElementById('photos-main-dropzone');
        if (dropzone) {
            dropzone.classList.add('border-brand-cyan', 'bg-brand-cyan/10');
        }
    }

    function handleDragLeave(e) {
        e.preventDefault();
        e.stopPropagation();
        const dropzone = document.getElementById('photos-main-dropzone');
        if (dropzone) {
            dropzone.classList.remove('border-brand-cyan', 'bg-brand-cyan/10');
        }
    }

    function handleDrop(e) {
        e.preventDefault();
        e.stopPropagation();
        const dropzone = document.getElementById('photos-main-dropzone');
        if (dropzone) {
            dropzone.classList.remove('border-brand-cyan', 'bg-brand-cyan/10');
        }
        if (e.dataTransfer && e.dataTransfer.files) {
            addPhotosToSelection(Array.from(e.dataTransfer.files));
        }
    }

    function toggleImageUrlInput() {
        const box = document.getElementById('image-url-container');
        if (box) box.classList.toggle('hidden');
    }

    // 8b. Video Preview Handlers
    function previewSelectedVideo(input) {
        const notice = document.getElementById('selected-video-notice');
        const nameEl = document.getElementById('selected-video-name');
        const sizeEl = document.getElementById('selected-video-size');
        if (input.files && input.files[0]) {
            const file = input.files[0];
            if (notice) notice.classList.remove('hidden');
            if (nameEl) nameEl.textContent = file.name;
            if (sizeEl) sizeEl.textContent = `(${(file.size / 1024 / 1024).toFixed(1)} MB)`;
        }
    }

    function removeSelectedVideo() {
        const input = document.getElementById('property_video_input');
        const notice = document.getElementById('selected-video-notice');
        if (input) input.value = '';
        if (notice) notice.classList.add('hidden');
    }

    // Form Submission Interception for Min 5 Photos Mandatory Validation
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('main-wizard-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                // Ensure photos are synced
                syncPhotosInput();

                if (selectedPhotos.length < MIN_PHOTOS) {
                    e.preventDefault();
                    e.stopPropagation();

                    const alertBox = document.getElementById('photo-validation-alert');
                    const alertMsg = document.getElementById('photo-validation-message');
                    if (alertMsg) {
                        alertMsg.textContent = `कम से कम 5 फोटो अपलोड करना अनिवार्य है! आपने अभी केवल ${selectedPhotos.length} फोटो चुनी हैं। (Please upload at least 5 photos before publishing. Currently selected: ${selectedPhotos.length})`;
                    }
                    if (alertBox) {
                        alertBox.classList.remove('hidden');
                        alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                    
                    const section = document.getElementById('photo-upload-section');
                    if (section) {
                        section.classList.add('ring-4', 'ring-rose-500/40', 'rounded-2xl', 'p-2', 'bg-rose-50/20');
                        setTimeout(() => {
                            section.classList.remove('ring-4', 'ring-rose-500/40', 'bg-rose-50/20');
                        }, 3000);
                    }
                    return false;
                }

                if (selectedPhotos.length > MAX_PHOTOS) {
                    e.preventDefault();
                    alert(`अधिकतम ${MAX_PHOTOS} फोटो ही अपलोड कर सकते हैं (Maximum ${MAX_PHOTOS} photos limit).`);
                    return false;
                }

                // Show uploading spinner on publish button
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> <span>Uploading ' + selectedPhotos.length + ' Photos &amp; Publishing...</span>';
                }
            });
        }
    });

    // 9. Expert Assistance Modal Actions
    function openAssistanceModal() {
        const modal = document.getElementById('expert-assistance-modal');
        const box = document.getElementById('assistance-modal-box');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            box.classList.remove('scale-95');
            box.classList.add('scale-100');
        }, 10);
    }

    function closeAssistanceModal() {
        const modal = document.getElementById('expert-assistance-modal');
        const box = document.getElementById('assistance-modal-box');
        modal.classList.add('opacity-0');
        box.classList.remove('scale-100');
        box.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    async function submitAssistanceRequest(e) {
        e.preventDefault();
        const phone = document.getElementById('modal-phone-input').value.trim();
        const btn = document.getElementById('modal-submit-btn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Submitting...';

        try {
            const res = await fetch("{{ route('properties.assistance') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ phone: phone })
            });
            const data = await res.json();
            document.getElementById('modal-success-msg').classList.remove('hidden');
            setTimeout(() => {
                closeAssistanceModal();
                btn.disabled = false;
                btn.innerHTML = 'Submit';
            }, 2500);
        } catch (err) {
            e.target.submit();
        }
    }

    // Initialize Default States on DOM Load
    document.addEventListener('DOMContentLoaded', function() {
        const defaultOwnerBtn = document.querySelector('.starter-role-chip[data-role="Owner"]');
        if (defaultOwnerBtn) selectStarterRole(defaultOwnerBtn);

        const defaultRentBtn = document.querySelector('.starter-purpose-chip[data-purpose="rent"]');
        if (defaultRentBtn) selectStarterPurpose(defaultRentBtn);

        const priceInput = document.getElementById('wizard_price_input');
        if (priceInput) updateLivePriceText(priceInput.value);

        setTimeout(() => {
            const stage1 = document.getElementById('stage-1-container');
            if (stage1 && !stage1.classList.contains('hidden')) {
                openAssistanceModal();
            }
        }, 10000);
    });
</script>
@endpush
