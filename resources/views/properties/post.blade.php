@extends('layouts.app')

@section('title', 'Post Free Property Ads | Rent & Sell Property Online | Housing 365 India')
@section('meta_description', 'Post free property ads to rent and sell flat, apartment, house, villa, commercial office and plots online. Zero brokerage on Housing 365 India.')

@section('content')

<!-- ── TOP BREADCRUMB ── -->
<div class="bg-[#FAFBFD] border-b border-slate-200/80 py-3 text-xs sm:text-sm text-slate-500">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 flex items-center justify-between">
        <div class="flex items-center space-x-2">
            <a href="{{ route('home') }}" class="hover:text-[#d8232a] transition font-medium">Home</a>
            <span class="text-slate-300">/</span>
            <span class="text-slate-700 font-semibold">Post Property Ads</span>
        </div>
        <div class="hidden sm:flex items-center gap-2 text-xs font-semibold text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">
            <span class="size-2 rounded-full bg-emerald-500 animate-pulse"></span>
            100% Free Listing &bull; Zero Brokerage &bull; Direct Buyer/Tenant Enquiries
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════════════ -->
<!-- STAGE 1: LANDING & QUICK STARTER (post.magicbricks.com UI)         -->
<!-- ══════════════════════════════════════════════════════════════════ -->
<section id="stage-1-container" class="{{ (!empty($errors) && $errors->any()) ? 'hidden' : 'block' }} py-8 sm:py-14 bg-gradient-to-b from-white via-[#FCFDFE] to-[#F5F7FB]">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">

            <!-- LEFT COLUMN: Trust, Hero & How It Works -->
            <div class="lg:col-span-7 space-y-10">
                <!-- Hero Heading & Trust Badges -->
                <div class="space-y-4">
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 tracking-tight leading-[1.15]">
                        Post your property <br>
                        Ad to sell or rent <br>
                        online for <span class="text-emerald-600 font-extrabold underline decoration-emerald-300 decoration-wavy">Free!</span>
                    </h1>

                    <ul class="space-y-3 pt-2 text-sm sm:text-base font-semibold text-slate-700">
                        <li class="flex items-center gap-3">
                            <span class="flex size-6 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 shrink-0">
                                <i class="fa-solid fa-check text-xs"></i>
                            </span>
                            <span>Get Access to <strong class="text-slate-900">4 Lakh+</strong> Genuine Buyers &amp; Tenants</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="flex size-6 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 shrink-0">
                                <i class="fa-solid fa-check text-xs"></i>
                            </span>
                            <span>Sell &amp; Rent Faster with Verified Prime Visibility</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="flex size-6 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 shrink-0">
                                <i class="fa-solid fa-check text-xs"></i>
                            </span>
                            <span>Get Expert Advice on Local Market Trends and Valuation</span>
                        </li>
                    </ul>
                </div>

                <!-- Visual Hero Illustration with House Frame -->
                <div class="relative bg-gradient-to-tr from-rose-50 via-sky-50 to-amber-50 rounded-3xl p-6 sm:p-8 border border-slate-200/80 overflow-hidden shadow-sm flex items-center justify-between gap-6">
                    <div class="space-y-3 z-10 max-w-sm">
                        <span class="inline-block bg-[#d8232a] text-white text-[11px] font-extrabold uppercase px-3 py-1 rounded-full tracking-wider shadow">
                            Zero Brokerage Guaranteed
                        </span>
                        <h3 class="text-xl sm:text-2xl font-black text-slate-900 leading-snug">
                            Over 1,200+ Homes Listed Daily Across India
                        </h3>
                        <p class="text-xs text-slate-600">
                            Post once and get connected directly with pre-screened tenants and buyers on WhatsApp &amp; phone.
                        </p>
                    </div>

                    <div class="relative shrink-0 hidden sm:block">
                        <div class="size-44 rounded-2xl overflow-hidden shadow-lg border-4 border-white transform rotate-2 hover:rotate-0 transition duration-300">
                            <img src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=500&auto=format&fit=crop&q=80" alt="Happy property owner" class="w-full h-full object-cover">
                        </div>
                    </div>
                </div>

                <!-- How it works 3 Steps -->
                <div class="pt-2">
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 mb-6">How it works</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <!-- Step 1 -->
                        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-sm hover:shadow-md transition">
                            <div class="size-10 rounded-xl bg-rose-50 text-[#d8232a] flex items-center justify-center font-black text-base mb-3 border border-rose-100">
                                1
                            </div>
                            <h4 class="font-bold text-slate-900 text-sm mb-1.5">Post your Property Ad</h4>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                Enter all details like locality name, configuration, amenities along with uploading photos.
                            </p>
                        </div>

                        <!-- Step 2 -->
                        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-sm hover:shadow-md transition">
                            <div class="size-10 rounded-xl bg-sky-50 text-brand-cyan flex items-center justify-center font-black text-base mb-3 border border-sky-100">
                                2
                            </div>
                            <h4 class="font-bold text-slate-900 text-sm mb-1.5">Check Direct Responses</h4>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                Get instant WhatsApp and phone notifications when matching tenants or buyers enquire.
                            </p>
                        </div>

                        <!-- Step 3 -->
                        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-sm hover:shadow-md transition">
                            <div class="size-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-base mb-3 border border-emerald-100">
                                3
                            </div>
                            <h4 class="font-bold text-slate-900 text-sm mb-1.5">Close the Deal</h4>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                Schedule visit, agree on terms and close deal with zero broker commission fees.
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN: MagicBricks "Let's get you started" Starter Card -->
            <div class="lg:col-span-5">
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xl relative sticky top-24">
                    <h3 class="text-2xl font-black text-slate-900 mb-6">Let's get you started</h3>

                    <!-- 1. You are: (Owner / Agent / Builder) -->
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5">You are:</label>
                        <div class="grid grid-cols-3 gap-2.5" id="starter-user-type-group">
                            <button type="button" class="starter-role-chip py-2.5 px-3 rounded-full border border-slate-300 text-slate-700 text-sm font-semibold hover:border-slate-400 text-center transition" data-role="Owner" onclick="selectStarterRole(this)">
                                Owner
                            </button>
                            <button type="button" class="starter-role-chip py-2.5 px-3 rounded-full border border-slate-300 text-slate-700 text-sm font-semibold hover:border-slate-400 text-center transition" data-role="Agent" onclick="selectStarterRole(this)">
                                Agent
                            </button>
                            <button type="button" class="starter-role-chip py-2.5 px-3 rounded-full border border-slate-300 text-slate-700 text-sm font-semibold hover:border-slate-400 text-center transition" data-role="Builder" onclick="selectStarterRole(this)">
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
                            <button type="button" class="starter-purpose-chip py-2.5 px-3 rounded-full border border-slate-300 text-slate-700 text-sm font-semibold hover:border-slate-400 text-center transition" data-purpose="sale" onclick="selectStarterPurpose(this)">
                                Sell
                            </button>
                            <button type="button" class="starter-purpose-chip py-2.5 px-3 rounded-full border border-slate-300 text-slate-700 text-sm font-semibold hover:border-slate-400 text-center transition" data-purpose="rent" onclick="selectStarterPurpose(this)">
                                Rent/lease
                            </button>
                            <button type="button" class="starter-purpose-chip py-2.5 px-3 rounded-full border border-slate-300 text-slate-700 text-sm font-semibold hover:border-slate-400 text-center transition" data-purpose="pg" onclick="selectStarterPurpose(this)">
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
                        <div class="flex items-center rounded-xl border border-slate-300 bg-slate-50/50 overflow-hidden focus-within:border-[#d8232a] focus-within:ring-1 focus-within:ring-[#d8232a] transition">
                            <div class="px-3.5 py-3 border-r border-slate-200 text-slate-700 font-bold text-sm bg-slate-100 flex items-center gap-1.5 shrink-0">
                                <span>IND +91</span>
                                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                            </div>
                            <input type="tel" id="starter-phone-input" value="6376163263" placeholder="WhatsApp Number" class="w-full bg-transparent px-3.5 py-3 text-sm font-bold text-slate-800 placeholder-slate-400 focus:outline-none">
                        </div>
                        <div id="starter-phone-error" class="hidden text-xs text-rose-600 font-semibold mt-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <span>Please enter a valid 10-digit WhatsApp phone number!</span>
                        </div>
                    </div>

                    <!-- 4. WhatsApp Callout Reassurance -->
                    <div class="mb-6 p-3.5 rounded-xl border border-amber-300/80 bg-amber-50/80 flex items-center gap-2.5 text-xs text-slate-700 font-medium">
                        <i class="fa-brands fa-whatsapp text-emerald-600 text-lg shrink-0"></i>
                        <span>Enter your WhatsApp No. to get enquiries from Buyer/Tenant</span>
                    </div>

                    <!-- 5. Red Action Button -->
                    <button type="button" onclick="startListingWizard()" class="w-full py-4 px-6 rounded-xl bg-[#d8232a] hover:bg-[#b81d23] text-white font-bold text-base shadow-lg shadow-rose-600/20 transition-all duration-200 transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <span>Start Now</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>

                    <!-- Trust Footer in Card -->
                    <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                        <span class="flex items-center gap-1.5 text-emerald-700 font-semibold">
                            <i class="fa-solid fa-shield-check text-emerald-500"></i> 100% Free Listing
                        </span>
                        <a href="javascript:void(0)" onclick="openAssistanceModal()" class="text-[#d8232a] font-bold hover:underline">
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
<section id="stage-2-container" class="{{ (!empty($errors) && $errors->any()) ? 'block' : 'hidden' }} py-8 sm:py-12 bg-[#F6F8FB]">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">

        <!-- Top Header & Free Tag -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-5">
            <div>
                <button type="button" onclick="backToStarter()" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-[#d8232a] mb-2 transition">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Change basic choices</span>
                </button>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900">
                    Sell or Rent your Property
                </h1>
                <p class="text-xs sm:text-sm text-slate-600 mt-1">
                    You are posting this property for <span class="bg-amber-300 text-slate-950 font-black px-2 py-0.5 rounded text-xs tracking-wider uppercase">FREE!</span>
                </p>
            </div>

            <div class="flex items-center gap-3">
                <button type="button" onclick="openAssistanceModal()" class="inline-flex items-center gap-2 bg-white px-4 py-2 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 hover:border-slate-400 shadow-sm transition">
                    <i class="fa-solid fa-headset text-[#d8232a]"></i>
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
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

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
                    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/90 shadow-sm">
                        <h3 class="text-base sm:text-lg font-black text-slate-900 border-b border-slate-100 pb-3 mb-5">
                            Personal Details
                        </h3>

                        <div class="space-y-4">
                            <!-- I am: Radio Options -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">I am</label>
                                <div class="flex items-center gap-6 text-sm font-semibold text-slate-800">
                                    <label class="inline-flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="posted_by" value="Owner" class="size-4 text-[#d8232a] focus:ring-[#d8232a]" {{ old('posted_by', 'Owner') === 'Owner' ? 'checked' : '' }}>
                                        <span>Owner</span>
                                    </label>
                                    <label class="inline-flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="posted_by" value="Agent" class="size-4 text-[#d8232a] focus:ring-[#d8232a]" {{ old('posted_by') === 'Agent' ? 'checked' : '' }}>
                                        <span>Agent</span>
                                    </label>
                                    <label class="inline-flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="posted_by" value="Builder" class="size-4 text-[#d8232a] focus:ring-[#d8232a]" {{ old('posted_by') === 'Builder' ? 'checked' : '' }}>
                                        <span>Builder</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Name -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Name *</label>
                                <input type="text" name="seller_name" id="wizard_name" required value="{{ old('seller_name', 'Ajay Poonia') }}" placeholder="Enter Your Name" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#d8232a] transition font-medium">
                            </div>

                            <!-- Mobile with IND +91 -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Mobile *</label>
                                <div class="flex items-center rounded-xl border border-slate-200 bg-slate-50 overflow-hidden focus-within:border-[#d8232a] focus-within:ring-2 focus-within:ring-[#d8232a] transition">
                                    <div class="px-3.5 py-3 border-r border-slate-200 text-slate-700 font-bold text-sm bg-slate-100/80 flex items-center gap-1.5 shrink-0">
                                        <span>IND +91</span>
                                        <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                                    </div>
                                    <input type="tel" name="seller_phone" id="wizard_phone" required value="{{ old('seller_phone', '6376163263') }}" placeholder="Mobile Number" class="w-full bg-transparent px-4 py-3 text-sm font-bold text-slate-900 focus:outline-none">
                                </div>
                            </div>

                            <!-- Amber WhatsApp Banner -->
                            <div class="p-3.5 rounded-xl border border-amber-300 bg-amber-50/90 flex items-center gap-2.5 text-xs text-slate-800 font-medium">
                                <i class="fa-brands fa-whatsapp text-emerald-600 text-xl shrink-0"></i>
                                <span>Enter your WhatsApp No. to get enquiries from Buyer/Tenant</span>
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email</label>
                                <input type="email" name="seller_email" id="wizard_email" value="{{ old('seller_email') }}" placeholder="Enter Your Email" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#d8232a] transition">
                            </div>
                        </div>
                    </div>

                    <!-- 2. PROPERTY DETAILS -->
                    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/90 shadow-sm">
                        <h3 class="text-base sm:text-lg font-black text-slate-900 border-b border-slate-100 pb-3 mb-5">
                            Property Details
                        </h3>

                        <div class="space-y-5">
                            <!-- For: Radio Options (Sale / Rent / PG) -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">For</label>
                                <div class="flex items-center gap-6 text-sm font-semibold text-slate-800">
                                    <label class="inline-flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="purpose" value="sale" class="size-4 text-[#d8232a] focus:ring-[#d8232a]" onchange="syncPurposeChange('sale')" {{ old('purpose') === 'sale' ? 'checked' : '' }}>
                                        <span>Sale</span>
                                    </label>
                                    <label class="inline-flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="purpose" value="rent" class="size-4 text-[#d8232a] focus:ring-[#d8232a]" onchange="syncPurposeChange('rent')" {{ old('purpose', 'rent') === 'rent' ? 'checked' : '' }}>
                                        <span>Rent/ Lease</span>
                                    </label>
                                    <label class="inline-flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="purpose" value="lease" class="size-4 text-[#d8232a] focus:ring-[#d8232a]" onchange="syncPurposeChange('lease')" {{ old('purpose') === 'lease' ? 'checked' : '' }}>
                                        <span>PG / Hostel</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Property Type Dropdown -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Property Type *</label>
                                <select name="property_type" id="wizard_property_type" required onchange="handlePropertyTypeSelect(this.value)" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#d8232a] transition font-semibold">
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
                                            <input type="radio" name="posting_as_radio" value="Full House" checked onchange="setHiddenField('hidden_posting_as', this.value)" class="size-4 text-[#d8232a] focus:ring-[#d8232a]">
                                            <span>Full House</span>
                                        </label>
                                        <label class="inline-flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="posting_as_radio" value="On sharing basis" onchange="setHiddenField('hidden_posting_as', this.value)" class="size-4 text-[#d8232a] focus:ring-[#d8232a]">
                                            <span>On sharing basis</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Total No. of Flats in Your Society -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Total No. of Flats in Your Society</label>
                                    <div class="flex items-center gap-2.5 max-w-sm">
                                        <button type="button" class="society-flats-chip flex-1 py-2 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 hover:border-slate-400 text-center transition" onclick="selectSocietyFlats(this, '<50')">
                                            &lt;50
                                        </button>
                                        <button type="button" class="society-flats-chip flex-1 py-2 rounded-xl border-2 border-emerald-600 bg-emerald-50 text-xs font-bold text-emerald-800 text-center transition" onclick="selectSocietyFlats(this, '50-100')">
                                            50-100
                                        </button>
                                        <button type="button" class="society-flats-chip flex-1 py-2 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 hover:border-slate-400 text-center transition" onclick="selectSocietyFlats(this, '>100')">
                                            &gt;100
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- 3. PROPERTY LOCATION -->
                    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/90 shadow-sm">
                        <h3 class="text-base sm:text-lg font-black text-slate-900 border-b border-slate-100 pb-3 mb-5">
                            Property Location
                        </h3>

                        <div class="space-y-4">
                            <!-- City Autocomplete -->
                            <div class="relative">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">City *</label>
                                <input type="text" name="city" id="city_autocomplete_input" required value="{{ old('city', 'Jaipur') }}" placeholder="Type City (e.g. Jaipur, Mumbai, Delhi, Bengaluru)" autocomplete="off" oninput="handleCitySearch(this.value)" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#d8232a] transition font-bold">
                                
                                <!-- Suggestions Dropdown List -->
                                <div id="city-suggestions-dropdown" class="hidden absolute left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-2xl z-30 max-h-56 overflow-y-auto divide-y divide-slate-100">
                                    <!-- Populated via JS -->
                                </div>
                            </div>

                            <!-- Locality / Society / Project -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Locality / Society / Project Name *</label>
                                <input type="text" name="locality" id="locality_input" required value="{{ old('locality') }}" placeholder="e.g. Mansarovar, Jagatpura, Vaishali Nagar, Bandra West, HSR Layout" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#d8232a] transition font-medium">
                                <span class="text-[11px] text-slate-400 mt-1 block">Mention the exact sub-locality, colony or apartment complex name.</span>
                            </div>

                            <!-- Street Address / Landmark -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Street Address / Landmark (Optional)</label>
                                <input type="text" name="address" value="{{ old('address') }}" placeholder="e.g. Flat 302, Block A, Near Metro Station" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#d8232a] transition">
                            </div>
                        </div>
                    </div>

                    <!-- 4. PROPERTY FEATURES (Interactive Chips) -->
                    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/90 shadow-sm" id="property-features-section">
                        <h3 class="text-base sm:text-lg font-black text-slate-900 border-b border-slate-100 pb-3 mb-5">
                            Property Features
                        </h3>

                        <div class="space-y-6">
                            <!-- Bedrooms -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Bedrooms</label>
                                <div class="flex flex-wrap items-center gap-2" id="bedrooms-chips">
                                    <button type="button" class="feature-chip py-2 px-4 rounded-xl border border-slate-300 text-sm font-semibold hover:border-slate-400 transition" onclick="selectChip(this, 'hidden_bedrooms', '1')">1</button>
                                    <button type="button" class="feature-chip py-2 px-4 rounded-xl border-2 border-emerald-600 bg-emerald-50 text-emerald-800 text-sm font-bold transition" onclick="selectChip(this, 'hidden_bedrooms', '2')">2</button>
                                    <button type="button" class="feature-chip py-2 px-4 rounded-xl border border-slate-300 text-sm font-semibold hover:border-slate-400 transition" onclick="selectChip(this, 'hidden_bedrooms', '3')">3</button>
                                    <button type="button" class="feature-chip py-2 px-4 rounded-xl border border-slate-300 text-sm font-semibold hover:border-slate-400 transition" onclick="selectChip(this, 'hidden_bedrooms', '4')">4</button>
                                    <button type="button" class="feature-chip py-2 px-4 rounded-xl border border-slate-300 text-sm font-semibold hover:border-slate-400 transition" onclick="selectChip(this, 'hidden_bedrooms', '5')">5+ &blacktriangledown;</button>
                                </div>
                            </div>

                            <!-- Balconies -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Balconies</label>
                                <div class="flex flex-wrap items-center gap-2" id="balconies-chips">
                                    <button type="button" class="feature-chip py-2 px-4 rounded-xl border border-slate-300 text-sm font-semibold hover:border-slate-400 transition" onclick="selectChip(this, 'hidden_balconies', '0')">0</button>
                                    <button type="button" class="feature-chip py-2 px-4 rounded-xl border-2 border-emerald-600 bg-emerald-50 text-emerald-800 text-sm font-bold transition" onclick="selectChip(this, 'hidden_balconies', '1')">1</button>
                                    <button type="button" class="feature-chip py-2 px-4 rounded-xl border border-slate-300 text-sm font-semibold hover:border-slate-400 transition" onclick="selectChip(this, 'hidden_balconies', '2')">2</button>
                                    <button type="button" class="feature-chip py-2 px-4 rounded-xl border border-slate-300 text-sm font-semibold hover:border-slate-400 transition" onclick="selectChip(this, 'hidden_balconies', '3')">3</button>
                                    <button type="button" class="feature-chip py-2 px-4 rounded-xl border border-slate-300 text-sm font-semibold hover:border-slate-400 transition" onclick="selectChip(this, 'hidden_balconies', '4')">3+ &blacktriangledown;</button>
                                </div>
                            </div>

                            <!-- Floor No -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Floor No</label>
                                <div class="flex flex-wrap items-center gap-2" id="floor-no-chips">
                                    <button type="button" class="feature-chip py-2 px-3 rounded-xl border border-slate-300 text-xs font-semibold hover:border-slate-400 transition" onclick="selectChip(this, 'hidden_floor_no', 'Lower Basement')">Lower Basement</button>
                                    <button type="button" class="feature-chip py-2 px-3 rounded-xl border border-slate-300 text-xs font-semibold hover:border-slate-400 transition" onclick="selectChip(this, 'hidden_floor_no', 'Upper Basement')">Upper Basement</button>
                                    <button type="button" class="feature-chip py-2 px-3 rounded-xl border-2 border-emerald-600 bg-emerald-50 text-emerald-800 text-xs font-bold transition" onclick="selectChip(this, 'hidden_floor_no', 'Ground')">Ground</button>
                                    <button type="button" class="feature-chip py-2 px-3.5 rounded-xl border border-slate-300 text-xs font-semibold hover:border-slate-400 transition" onclick="selectChip(this, 'hidden_floor_no', '1')">1</button>
                                    <button type="button" class="feature-chip py-2 px-3.5 rounded-xl border border-slate-300 text-xs font-semibold hover:border-slate-400 transition" onclick="selectChip(this, 'hidden_floor_no', '2')">2</button>
                                    <button type="button" class="feature-chip py-2 px-3.5 rounded-xl border border-slate-300 text-xs font-semibold hover:border-slate-400 transition" onclick="selectChip(this, 'hidden_floor_no', '3')">3</button>
                                    <button type="button" class="feature-chip py-2 px-3.5 rounded-xl border border-slate-300 text-xs font-semibold hover:border-slate-400 transition" onclick="selectChip(this, 'hidden_floor_no', '4')">4</button>
                                    <button type="button" class="feature-chip py-2 px-3.5 rounded-xl border border-slate-300 text-xs font-semibold hover:border-slate-400 transition" onclick="selectChip(this, 'hidden_floor_no', '5')">5</button>
                                    <button type="button" class="feature-chip py-2 px-3.5 rounded-xl border border-slate-300 text-xs font-semibold hover:border-slate-400 transition" onclick="selectChip(this, 'hidden_floor_no', '5+')">5+ &blacktriangledown;</button>
                                </div>
                            </div>

                            <!-- Total Floors -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Total Floors</label>
                                <div class="flex flex-wrap items-center gap-1.5" id="total-floors-chips">
                                    @for($f = 1; $f <= 13; $f++)
                                        <button type="button" class="feature-chip py-2 px-3 rounded-xl border {{ $f == 4 ? 'border-2 border-emerald-600 bg-emerald-50 text-emerald-800 font-bold' : 'border-slate-300 text-slate-700' }} text-xs hover:border-slate-400 transition" onclick="selectChip(this, 'hidden_total_floors', '{{ $f }}')">
                                            {{ $f }}
                                        </button>
                                    @endfor
                                    <button type="button" class="feature-chip py-2 px-3 rounded-xl border border-slate-300 text-xs text-slate-700 hover:border-slate-400 transition" onclick="selectChip(this, 'hidden_total_floors', '13+')">
                                        13+ &blacktriangledown;
                                    </button>
                                </div>
                            </div>

                            <!-- Furnished Status -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Furnished Status</label>
                                <div class="flex flex-wrap items-center gap-2.5" id="furnishing-chips">
                                    <button type="button" class="feature-chip py-2.5 px-4 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 hover:border-slate-400 transition" onclick="selectChip(this, 'hidden_furnishing', 'Fully Furnished')">
                                        Fully Furnished
                                    </button>
                                    <button type="button" class="feature-chip py-2.5 px-4 rounded-xl border-2 border-emerald-600 bg-emerald-50 text-emerald-800 text-xs font-bold transition" onclick="selectChip(this, 'hidden_furnishing', 'Semi-Furnished')">
                                        Semi-Furnished
                                    </button>
                                    <button type="button" class="feature-chip py-2.5 px-4 rounded-xl border border-slate-300 text-xs font-bold text-slate-700 hover:border-slate-400 transition" onclick="selectChip(this, 'hidden_furnishing', 'Unfurnished')">
                                        Unfurnished
                                    </button>
                                </div>
                            </div>

                            <!-- Bathrooms & Carpet Area Row -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Bathrooms</label>
                                    <div class="flex items-center gap-2">
                                        <button type="button" class="feature-chip py-2 px-4 rounded-xl border border-slate-300 text-xs font-semibold hover:border-slate-400 transition" onclick="selectChip(this, 'hidden_bathrooms', '1')">1</button>
                                        <button type="button" class="feature-chip py-2 px-4 rounded-xl border-2 border-emerald-600 bg-emerald-50 text-emerald-800 text-xs font-bold transition" onclick="selectChip(this, 'hidden_bathrooms', '2')">2</button>
                                        <button type="button" class="feature-chip py-2 px-4 rounded-xl border border-slate-300 text-xs font-semibold hover:border-slate-400 transition" onclick="selectChip(this, 'hidden_bathrooms', '3')">3</button>
                                        <button type="button" class="feature-chip py-2 px-4 rounded-xl border border-slate-300 text-xs font-semibold hover:border-slate-400 transition" onclick="selectChip(this, 'hidden_bathrooms', '4')">4+</button>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Carpet / Built-up Area *</label>
                                    <div class="flex items-center rounded-xl border border-slate-200 bg-slate-50 overflow-hidden focus-within:border-[#d8232a] focus-within:ring-2 focus-within:ring-[#d8232a] transition">
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
                    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/90 shadow-sm">
                        <h3 class="text-base sm:text-lg font-black text-slate-900 border-b border-slate-100 pb-3 mb-5">
                            Pricing &amp; Charges
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <!-- Expected Price / Rent -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" id="wizard-price-label">
                                    Expected Monthly Rent (in ₹) *
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-3 text-slate-400 font-bold text-base">₹</span>
                                    <input type="number" name="price" id="wizard_price_input" required value="{{ old('price', '25000') }}" placeholder="e.g. 25000" oninput="updateLivePriceText(this.value)" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl pl-8 pr-4 py-3 text-base font-black focus:outline-none focus:ring-2 focus:ring-[#d8232a] transition">
                                </div>
                                <div class="mt-1.5 flex items-center justify-between text-xs">
                                    <span id="wizard-price-words" class="font-bold text-emerald-700">₹ 25,000 / month</span>
                                    <span class="text-[11px] text-slate-400">All prices in INR</span>
                                </div>
                            </div>

                            <!-- Monthly Maintenance -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Maintenance (Monthly)
                                </label>
                                <input type="text" name="maintenance" value="{{ old('maintenance', 'Included in rent') }}" placeholder="e.g. ₹ 2,000 / month or Included" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#d8232a] transition font-medium">
                                <span class="text-[11px] text-slate-400 mt-1 block">Specify if society maintenance is included or extra.</span>
                            </div>
                        </div>
                    </div>

                    <!-- 6. PHOTOS, TITLE & AMENITIES -->
                    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/90 shadow-sm">
                        <h3 class="text-base sm:text-lg font-black text-slate-900 border-b border-slate-100 pb-3 mb-5">
                            Photos, Description &amp; Amenities
                        </h3>

                        <div class="space-y-5">
                            <!-- Optional Title -->
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Property Title</label>
                                    <span class="text-[11px] text-slate-400">Auto-generated if left blank</span>
                                </div>
                                <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. Spacious 2 BHK Apartment in Mansarovar with Modular Kitchen" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#d8232a] transition">
                            </div>

                            <!-- Description -->
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Description</label>
                                    <span class="text-[11px] text-slate-400">Auto-generated with complete specs if blank</span>
                                </div>
                                <textarea name="description" rows="3" placeholder="Highlight property advantages: road connectivity, sunlight, nearby metro/schools, water supply..." class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl p-3.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d8232a] transition leading-relaxed">{{ old('description') }}</textarea>
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
                                            <input type="checkbox" name="amenities[]" value="{{ $am[0] }}" checked class="size-4 text-[#d8232a] focus:ring-[#d8232a] rounded">
                                            <i class="fa-solid {{ $am[1] }} text-slate-500 text-xs"></i>
                                            <span>{{ $am[0] }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Photo Upload -->
                            <div class="pt-3 border-t border-slate-100">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Upload Property Photos</label>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-center">
                                    <label class="sm:col-span-2 flex flex-col items-center justify-center p-6 border-2 border-dashed border-slate-300 hover:border-[#d8232a] rounded-2xl cursor-pointer bg-slate-50 hover:bg-rose-50/30 transition text-center">
                                        <i class="fa-solid fa-cloud-arrow-up text-3xl text-[#d8232a] mb-2"></i>
                                        <span class="text-xs font-bold text-slate-900">Click or Drag &amp; Drop Photos Here</span>
                                        <span class="text-[11px] text-slate-400 mt-1">JPEG, PNG, WebP up to 8MB</span>
                                        <input type="file" name="image_file" accept="image/*" class="sr-only" onchange="previewSelectedPhoto(this)">
                                    </label>
                                    <div class="text-xs text-slate-500 space-y-1.5">
                                        <span class="font-bold text-slate-700 block">Or Image URL:</span>
                                        <input type="url" name="image_url" placeholder="https://..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:ring-1 focus:ring-[#d8232a]">
                                        <p class="text-[10px] text-slate-400 leading-tight">If none provided, verified architectural photography will be automatically attached.</p>
                                    </div>
                                </div>
                                <div id="selected-photo-notice" class="hidden mt-2 text-xs font-bold text-emerald-700 flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-check text-emerald-600"></i>
                                    <span id="selected-photo-name">Photo selected</span>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- SUBMIT BAR -->
                    <div class="p-6 bg-white rounded-2xl border border-slate-200/90 shadow-lg flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-xs text-slate-600">
                            <span class="font-black text-slate-900 text-sm block">100% Free Live Publication</span>
                            Your listing will instantly reach thousands of genuine tenants and buyers.
                        </div>
                        <button type="submit" class="w-full sm:w-auto py-4 px-10 rounded-xl bg-[#d8232a] hover:bg-[#b81d23] text-white font-black text-base shadow-xl shadow-rose-600/25 transition-all duration-200 transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            <span>Publish Property Free</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>
                    </div>

                </form>
            </div>

            <!-- RIGHT STICKY HELPER SIDEBAR ("How to find the right Tenant / Buyer?") -->
            <div class="lg:col-span-4 sticky top-24 space-y-6">
                <!-- Helper Guide Card Matching Screenshot 5 -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-sm space-y-5">
                    <h3 class="text-base font-black text-slate-900">
                        How to find the right <span id="sidebar-target-role" class="text-emerald-700">Tenant</span>?
                    </h3>

                    <!-- Item 1: Respond to Inquiries -->
                    <div class="flex items-start gap-3.5">
                        <div class="size-10 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-phone-volume text-sm"></i>
                        </div>
                        <div class="text-xs leading-relaxed text-slate-600">
                            <h4 class="font-bold text-slate-900 text-sm mb-0.5">Respond to Inquiries</h4>
                            Connect with verified searchers when they contact on your property. <strong class="text-slate-900">View contact details 100% FREE!</strong>
                        </div>
                    </div>

                    <!-- Item 2: Connect with Matching Leads -->
                    <div class="flex items-start gap-3.5">
                        <div class="size-10 rounded-full bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-user-check text-sm"></i>
                        </div>
                        <div class="text-xs leading-relaxed text-slate-600">
                            <h4 class="font-bold text-slate-900 text-sm mb-0.5">Connect with Matching Leads</h4>
                            Actively check algorithm-matched buyers &amp; tenants looking in your locality.
                        </div>
                    </div>

                    <!-- Item 3: Direct WhatsApp Updates -->
                    <div class="flex items-start gap-3.5">
                        <div class="size-10 rounded-full bg-rose-50 border border-rose-100 text-[#d8232a] flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-mobile-screen-button text-sm"></i>
                        </div>
                        <div class="text-xs leading-relaxed text-slate-600">
                            <h4 class="font-bold text-slate-900 text-sm mb-0.5">Instant WhatsApp Alerts</h4>
                            Get notified the moment a new enquiry is received and connect instantly.
                        </div>
                    </div>
                </div>

                <!-- Concierge / Expert Hotline Card -->
                <div class="bg-gradient-to-br from-slate-900 to-[#03234B] rounded-2xl p-6 text-white shadow-md space-y-4">
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
                    <a href="tel:+916376163263" class="w-full inline-flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-[#d8232a] hover:bg-[#b81d23] text-white text-xs font-bold transition shadow">
                        <i class="fa-solid fa-phone"></i>
                        <span>Call +91 63761 63263</span>
                    </a>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- ══════════════════════════════════════════════════════════════════ -->
<!-- PROACTIVE ASSISTANCE MODAL (post.magicbricks.com Screenshot 1)     -->
<!-- ══════════════════════════════════════════════════════════════════ -->
<div id="expert-assistance-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
    <div class="relative w-full max-w-md bg-white rounded-3xl p-6 sm:p-8 shadow-2xl transform scale-95 transition-transform duration-300 border border-slate-100" id="assistance-modal-box">
        <!-- Close 'X' -->
        <button type="button" onclick="closeAssistanceModal()" class="absolute top-4 right-4 size-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition">
            <i class="fa-solid fa-xmark text-sm"></i>
        </button>

        <div class="text-center space-y-2 mb-6">
            <h3 class="text-xl sm:text-2xl font-black text-slate-900">
                Need help in posting your property?
            </h3>
            <p class="text-sm text-slate-600">
                Let our Property Expert help you.
            </p>
        </div>

        <form id="assistance-form" onsubmit="submitAssistanceRequest(event)" class="space-y-5">
            @csrf
            <div>
                <div class="flex items-center rounded-xl border border-slate-300 bg-slate-50 overflow-hidden focus-within:border-[#d8232a] focus-within:ring-2 focus-within:ring-[#d8232a] transition">
                    <span class="px-3.5 py-3 border-r border-slate-200 text-slate-700 font-bold text-sm bg-slate-100">
                        +91
                    </span>
                    <input type="tel" name="phone" id="modal-phone-input" required value="6376163263" class="w-full bg-transparent px-4 py-3 text-base font-bold text-slate-900 focus:outline-none">
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="button" onclick="closeAssistanceModal()" class="w-1/2 py-3 rounded-full border border-[#d8232a] text-[#d8232a] font-bold text-sm hover:bg-rose-50 transition text-center">
                    Maybe Later
                </button>
                <button type="submit" id="modal-submit-btn" class="w-1/2 py-3 rounded-full bg-[#d8232a] hover:bg-[#b81d23] text-white font-bold text-sm shadow-md transition text-center">
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
            Need some help? Give us a call at <a href="tel:+916376163263" class="font-bold text-slate-800 hover:text-[#d8232a]">+91 63761 63263</a>
        </div>
    </div>
</div>

<!-- Floating Floating 'Need Help?' Badge on Bottom-Right -->
<div class="fixed bottom-6 right-6 z-40">
    <button type="button" onclick="openAssistanceModal()" class="inline-flex items-center gap-2.5 bg-[#d8232a] hover:bg-[#b81d23] text-white font-bold text-xs py-3 px-4 rounded-full shadow-2xl shadow-rose-900/30 border border-white/20 transition-all duration-200 transform hover:scale-105">
        <i class="fa-solid fa-headset text-sm"></i>
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
            b.classList.remove('border-2', 'border-emerald-600', 'bg-emerald-50', 'text-emerald-800', 'font-bold');
            b.classList.add('border-slate-300', 'text-slate-700');
        });
        btn.classList.remove('border-slate-300', 'text-slate-700');
        btn.classList.add('border-2', 'border-emerald-600', 'bg-emerald-50', 'text-emerald-800', 'font-bold');
        selectedStarterRole = btn.getAttribute('data-role');
        document.getElementById('starter-role-error').classList.add('hidden');
    }

    // 2. Starter Purpose Selection (Chips)
    function selectStarterPurpose(btn) {
        document.querySelectorAll('.starter-purpose-chip').forEach(b => {
            b.classList.remove('border-2', 'border-emerald-600', 'bg-emerald-50', 'text-emerald-800', 'font-bold');
            b.classList.add('border-slate-300', 'text-slate-700');
        });
        btn.classList.remove('border-slate-300', 'text-slate-700');
        btn.classList.add('border-2', 'border-emerald-600', 'bg-emerald-50', 'text-emerald-800', 'font-bold');
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
        // Sync role
        const roleRadio = document.querySelector(`input[name="posted_by"][value="${selectedStarterRole}"]`);
        if (roleRadio) roleRadio.checked = true;

        // Sync purpose
        let mappedPurpose = selectedStarterPurpose === 'pg' ? 'lease' : selectedStarterPurpose;
        const purposeRadio = document.querySelector(`input[name="purpose"][value="${mappedPurpose}"]`);
        if (purposeRadio) purposeRadio.checked = true;
        syncPurposeChange(mappedPurpose);

        // Sync phone
        document.getElementById('wizard_phone').value = phone;
        document.getElementById('modal-phone-input').value = phone;

        // Switch stages smoothly
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
            b.classList.remove('border-2', 'border-emerald-600', 'bg-emerald-50', 'text-emerald-800', 'font-bold');
            b.classList.add('border-slate-300', 'text-slate-700');
        });
        btn.classList.remove('border-slate-300', 'text-slate-700');
        btn.classList.add('border-2', 'border-emerald-600', 'bg-emerald-50', 'text-emerald-800', 'font-bold');
        document.getElementById(hiddenInputId).value = value;
    }

    function selectSocietyFlats(btn, value) {
        document.querySelectorAll('.society-flats-chip').forEach(b => {
            b.classList.remove('border-2', 'border-emerald-600', 'bg-emerald-50', 'text-emerald-800', 'font-bold');
            b.classList.add('border-slate-300', 'text-slate-700');
        });
        btn.classList.remove('border-slate-300', 'text-slate-700');
        btn.classList.add('border-2', 'border-emerald-600', 'bg-emerald-50', 'text-emerald-800', 'font-bold');
        document.getElementById('hidden_flats_society').value = value;
    }

    function setHiddenField(id, val) {
        document.getElementById(id).value = val;
    }

    // 6. Property Type Select (Flat vs Commercial vs Plot)
    function handlePropertyTypeSelect(type) {
        const flatBox = document.getElementById('flat-specific-box');
        const featuresSection = document.getElementById('property-features-section');
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

        // Always show primary matches and suburb match like MagicBricks
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
            item.className = 'px-4 py-2.5 text-sm text-slate-800 font-semibold hover:bg-rose-50 hover:text-[#d8232a] cursor-pointer flex items-center justify-between transition';
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

    // 8. Photo Upload Preview
    function previewSelectedPhoto(input) {
        if (input.files && input.files[0]) {
            document.getElementById('selected-photo-notice').classList.remove('hidden');
            document.getElementById('selected-photo-name').textContent = 'Selected: ' + input.files[0].name;
        }
    }

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
            // Fallback: normal submit if fetch fails
            e.target.submit();
        }
    }

    // Initialize Default States on DOM Load
    document.addEventListener('DOMContentLoaded', function() {
        // Starter defaults: Owner & Rent selected
        const defaultOwnerBtn = document.querySelector('.starter-role-chip[data-role="Owner"]');
        if (defaultOwnerBtn) selectStarterRole(defaultOwnerBtn);

        const defaultRentBtn = document.querySelector('.starter-purpose-chip[data-purpose="rent"]');
        if (defaultRentBtn) selectStarterPurpose(defaultRentBtn);

        // Live price init
        const priceInput = document.getElementById('wizard_price_input');
        if (priceInput) updateLivePriceText(priceInput.value);

        // Auto prompt assistance modal after 10s on page if still on Stage 1
        setTimeout(() => {
            const stage1 = document.getElementById('stage-1-container');
            if (stage1 && !stage1.classList.contains('hidden')) {
                openAssistanceModal();
            }
        }, 10000);
    });
</script>
@endpush
