@extends('layouts.app')

@section('title', 'Post Your Property Free | Buy, Sell, Rent, Commercial & Plots | Housing 365 India')
@section('meta_description', 'List your property for free on Housing 365 India. Post residential apartments, villas, commercial office spaces, retail shops, and plots for sale or rent. Zero brokerage.')

@section('content')

<!-- ── BREADCRUMB ── -->
<div class="bg-white border-b border-slate-100 py-3.5 text-sm text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-cyan transition font-medium">Home</a>
        <span class="text-slate-300">/</span>
        <a href="{{ route('properties.index') }}" class="hover:text-brand-cyan transition font-medium">Properties</a>
        <span class="text-slate-300">/</span>
        <span class="text-brand-navy font-bold">Post Property</span>
    </div>
</div>

<!-- ── HEADER BANNER ── -->
<section class="relative overflow-hidden bg-gradient-to-b from-[#F7FAFD] via-white to-[#F0F6FC] pt-10 pb-14 sm:pt-14 sm:pb-16 border-b border-slate-100">
    <div class="pointer-events-none absolute -left-40 top-0 h-[450px] w-[450px] rounded-full opacity-40 blur-3xl" style="background: radial-gradient(circle, rgba(0,174,239,0.18) 0%, transparent 70%)" aria-hidden="true"></div>
    <div class="pointer-events-none absolute right-0 top-10 h-[500px] w-[500px] rounded-full opacity-30 blur-3xl" style="background: radial-gradient(circle, rgba(3,35,75,0.06) 0%, transparent 70%)" aria-hidden="true"></div>

    <div class="relative mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 text-center">
        <!-- Eyebrow Badge -->
        <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-emerald-500/20 bg-emerald-50 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-emerald-700 shadow-sm">
            <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
            100% FREE PROPERTY LISTING &bull; ZERO BROKERAGE
        </div>

        <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold text-brand-navy tracking-tight max-w-4xl mx-auto">
            Sell or Rent Your Property on <br class="hidden sm:inline">
            <span class="text-brand-cyan font-bold">India's Premier Marketplace</span>
        </h1>

        <p class="mt-4 text-base sm:text-lg text-slate-600 max-w-2xl mx-auto">
            Connect directly with verified buyers and tenants. Post apartments, luxury villas, commercial spaces, and plots with zero listing charges.
        </p>

        <!-- Highlights Row -->
        <div class="mt-8 flex flex-wrap justify-center items-center gap-4 sm:gap-8 text-xs sm:text-sm font-semibold text-slate-700">
            <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-xl border border-slate-200 shadow-sm">
                <i class="fa-solid fa-circle-check text-emerald-500 text-base"></i>
                <span>Direct Buyer/Tenant Inquiries</span>
            </div>
            <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-xl border border-slate-200 shadow-sm">
                <i class="fa-solid fa-bolt text-brand-cyan text-base"></i>
                <span>Instant Live Publishing</span>
            </div>
            <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-xl border border-slate-200 shadow-sm">
                <i class="fa-solid fa-shield-halved text-brand-navy text-base"></i>
                <span>Zero Broker Spam Protection</span>
            </div>
        </div>
    </div>
</section>

<!-- ── MAIN POST FORM CONTAINER ── -->
<section class="py-12 sm:py-16 bg-[#F8FAFD]">
    <div class="mx-auto w-full max-w-4xl px-4 sm:px-6">

        @if ($errors->any())
            <div class="mb-8 rounded-2xl bg-rose-50 border border-rose-200 p-5 text-rose-800">
                <div class="flex items-center gap-3 font-bold mb-2">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 text-lg"></i>
                    <span>Please correct the errors below before submitting:</span>
                </div>
                <ul class="list-disc list-inside text-sm space-y-1 text-rose-700 pl-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('properties.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8" id="post-property-form">
            @csrf

            <!-- STEP 1: PURPOSE & CATEGORY -->
            <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200/90 shadow-sm">
                <div class="flex items-center gap-3 pb-6 mb-6 border-b border-slate-100">
                    <span class="flex size-9 items-center justify-center rounded-xl bg-brand-cyan text-white font-bold text-sm">01</span>
                    <div>
                        <h2 class="font-display text-xl font-bold text-brand-navy">What would you like to do?</h2>
                        <p class="text-xs text-slate-500">Choose whether you are looking to rent out or sell outright.</p>
                    </div>
                </div>

                <!-- Purpose Selector (Rent vs Sale vs Lease) -->
                <div class="mb-8">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-3">Property Intent *</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <label class="cursor-pointer relative">
                            <input type="radio" name="purpose" value="rent" class="peer sr-only" {{ old('purpose', 'rent') === 'rent' ? 'checked' : '' }} onchange="handlePurposeChange('rent')">
                            <div class="p-4 rounded-2xl border-2 border-slate-200 peer-checked:border-brand-cyan peer-checked:bg-brand-cyan/5 peer-checked:text-brand-navy hover:border-slate-300 transition text-center flex flex-col items-center gap-2">
                                <i class="fa-solid fa-key text-xl text-brand-cyan"></i>
                                <span class="font-bold text-sm">Rent Out</span>
                                <span class="text-[11px] text-slate-500">Residential Monthly Rent</span>
                            </div>
                        </label>

                        <label class="cursor-pointer relative">
                            <input type="radio" name="purpose" value="sale" class="peer sr-only" {{ old('purpose') === 'sale' ? 'checked' : '' }} onchange="handlePurposeChange('sale')">
                            <div class="p-4 rounded-2xl border-2 border-slate-200 peer-checked:border-emerald-600 peer-checked:bg-emerald-50/50 peer-checked:text-brand-navy hover:border-slate-300 transition text-center flex flex-col items-center gap-2">
                                <i class="fa-solid fa-tags text-xl text-emerald-600"></i>
                                <span class="font-bold text-sm">Sell Outright</span>
                                <span class="text-[11px] text-slate-500">Flat, House, Plot or Land</span>
                            </div>
                        </label>

                        <label class="cursor-pointer relative col-span-2 sm:col-span-1">
                            <input type="radio" name="purpose" value="lease" class="peer sr-only" {{ old('purpose') === 'lease' ? 'checked' : '' }} onchange="handlePurposeChange('lease')">
                            <div class="p-4 rounded-2xl border-2 border-slate-200 peer-checked:border-indigo-600 peer-checked:bg-indigo-50/50 peer-checked:text-brand-navy hover:border-slate-300 transition text-center flex flex-col items-center gap-2">
                                <i class="fa-solid fa-building text-xl text-indigo-600"></i>
                                <span class="font-bold text-sm">Commercial Lease</span>
                                <span class="text-[11px] text-slate-500">Office, Retail or Warehouse</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Category Selector (Residential vs Commercial vs Plot/Land) -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-3">Property Category *</label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <label class="cursor-pointer relative">
                            <input type="radio" name="category" value="residential" class="peer sr-only" {{ old('category', 'residential') === 'residential' ? 'checked' : '' }} onchange="handleCategoryChange('residential')">
                            <div class="p-4 rounded-2xl border-2 border-slate-200 peer-checked:border-brand-cyan peer-checked:bg-brand-cyan/5 peer-checked:text-brand-navy hover:border-slate-300 transition text-center flex flex-col items-center gap-2">
                                <i class="fa-solid fa-house-chimney text-xl text-brand-cyan"></i>
                                <span class="font-bold text-sm">Residential</span>
                                <span class="text-[11px] text-slate-500">Apartments, Villas, Floors</span>
                            </div>
                        </label>

                        <label class="cursor-pointer relative">
                            <input type="radio" name="category" value="commercial" class="peer sr-only" {{ old('category') === 'commercial' ? 'checked' : '' }} onchange="handleCategoryChange('commercial')">
                            <div class="p-4 rounded-2xl border-2 border-slate-200 peer-checked:border-indigo-600 peer-checked:bg-indigo-50/50 peer-checked:text-brand-navy hover:border-slate-300 transition text-center flex flex-col items-center gap-2">
                                <i class="fa-solid fa-briefcase text-xl text-indigo-600"></i>
                                <span class="font-bold text-sm">Commercial</span>
                                <span class="text-[11px] text-slate-500">Offices, Shops, Sheds</span>
                            </div>
                        </label>

                        <label class="cursor-pointer relative">
                            <input type="radio" name="category" value="plot_land" class="peer sr-only" {{ old('category') === 'plot_land' ? 'checked' : '' }} onchange="handleCategoryChange('plot_land')">
                            <div class="p-4 rounded-2xl border-2 border-slate-200 peer-checked:border-amber-600 peer-checked:bg-amber-50/50 peer-checked:text-brand-navy hover:border-slate-300 transition text-center flex flex-col items-center gap-2">
                                <i class="fa-solid fa-map-location text-xl text-amber-600"></i>
                                <span class="font-bold text-sm">Plots &amp; Land</span>
                                <span class="text-[11px] text-slate-500">Residential, Commercial, Farm</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Specific Property Type -->
                <div class="mt-6">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Specific Property Type *</label>
                    <select name="property_type" id="property_type_select" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan transition font-medium">
                        <!-- Populated by JavaScript according to category -->
                    </select>
                </div>
            </div>

            <!-- STEP 2: LOCATION DETAILS -->
            <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200/90 shadow-sm">
                <div class="flex items-center gap-3 pb-6 mb-6 border-b border-slate-100">
                    <span class="flex size-9 items-center justify-center rounded-xl bg-brand-cyan text-white font-bold text-sm">02</span>
                    <div>
                        <h2 class="font-display text-xl font-bold text-brand-navy">Where is your property located?</h2>
                        <p class="text-xs text-slate-500">Accurate location helps get 5x more genuine local buyers and tenants.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- City Dropdown -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">City *</label>
                        <select name="city" id="city_select" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan transition font-medium">
                            <option value="">Select City</option>
                            @foreach($cities as $c)
                                <option value="{{ $c }}" {{ old('city') === $c ? 'selected' : '' }}>{{ $c }}</option>
                            @endforeach
                            <option value="Other">Other City in India</option>
                        </select>
                    </div>

                    <!-- State -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">State</label>
                        <input type="text" name="state" value="{{ old('state') }}" placeholder="e.g. Maharashtra, Karnataka, Delhi" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan transition">
                    </div>

                    <!-- Locality / Society -->
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Locality / Society / Project Name *</label>
                        <input type="text" name="locality" required value="{{ old('locality') }}" placeholder="e.g. Bandra West, HSR Layout Sector 2, DLF Cyber City Phase 2, Gachibowli" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan transition">
                        <span class="text-[11px] text-slate-400 mt-1 block">Mention society or landmark so buyers can pinpoint the area.</span>
                    </div>

                    <!-- Full Address -->
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Street Address / Landmark (Optional)</label>
                        <input type="text" name="address" value="{{ old('address') }}" placeholder="e.g. Flat 402, Tower B, Prestige Acropolis, Near BDA Complex" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan transition">
                    </div>
                </div>
            </div>

            <!-- STEP 3: PROPERTY DETAILS, PRICING & MEASUREMENTS -->
            <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200/90 shadow-sm">
                <div class="flex items-center gap-3 pb-6 mb-6 border-b border-slate-100">
                    <span class="flex size-9 items-center justify-center rounded-xl bg-brand-cyan text-white font-bold text-sm">03</span>
                    <div>
                        <h2 class="font-display text-xl font-bold text-brand-navy">Pricing, Dimensions &amp; Specs</h2>
                        <p class="text-xs text-slate-500">Provide clear pricing and dimensions to attract high-intent leads.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Expected Price -->
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5" id="price-label">Expected Price (in ₹ INR) *</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 text-slate-400 font-bold text-base">₹</span>
                            <input type="number" name="price" id="price_input" required value="{{ old('price') }}" placeholder="e.g. 45000 for rent or 18500000 for sale" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl pl-9 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan transition font-bold text-base" oninput="updatePriceHelper(this.value)">
                        </div>
                        <div class="mt-1 flex items-center justify-between">
                            <span class="text-xs font-semibold text-brand-cyan" id="price-helper-text">Enter numbers only</span>
                            <span class="text-[11px] text-slate-400">All prices in Indian Rupees (INR)</span>
                        </div>
                    </div>

                    <!-- Area Size -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Carpet / Plot Area *</label>
                        <input type="number" name="sqft" required value="{{ old('sqft') }}" placeholder="e.g. 1450" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan transition">
                    </div>

                    <!-- Area Unit -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Area Unit *</label>
                        <select name="area_unit" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan transition font-medium">
                            <option value="sqft" {{ old('area_unit') === 'sqft' ? 'selected' : '' }}>Sq. Ft (Square Feet)</option>
                            <option value="sq.yards" {{ old('area_unit') === 'sq.yards' ? 'selected' : '' }}>Sq. Yards (Gaj)</option>
                            <option value="acres" {{ old('area_unit') === 'acres' ? 'selected' : '' }}>Acres</option>
                            <option value="bigha" {{ old('area_unit') === 'bigha' ? 'selected' : '' }}>Bigha</option>
                        </select>
                    </div>

                    <!-- Residential Specific (BHK & Bathrooms) -->
                    <div id="residential-fields" class="sm:col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-5 pt-2">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Bedrooms (BHK)</label>
                            <select name="bedrooms" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan transition">
                                <option value="1">1 BHK</option>
                                <option value="2" selected>2 BHK</option>
                                <option value="3">3 BHK</option>
                                <option value="4">4 BHK</option>
                                <option value="5">5+ BHK / Penthouse</option>
                                <option value="0">Studio / 1 RK</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Bathrooms</label>
                            <select name="bathrooms" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan transition">
                                <option value="1">1 Bathroom</option>
                                <option value="2" selected>2 Bathrooms</option>
                                <option value="3">3 Bathrooms</option>
                                <option value="4">4+ Bathrooms</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Furnishing</label>
                            <select name="furnishing" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan transition">
                                <option value="Semi-Furnished" selected>Semi-Furnished</option>
                                <option value="Fully Furnished">Fully Furnished</option>
                                <option value="Unfurnished">Unfurnished</option>
                                <option value="Bare Shell">Bare Shell</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 4: TITLE, DESCRIPTION & PHOTOS -->
            <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200/90 shadow-sm">
                <div class="flex items-center gap-3 pb-6 mb-6 border-b border-slate-100">
                    <span class="flex size-9 items-center justify-center rounded-xl bg-brand-cyan text-white font-bold text-sm">04</span>
                    <div>
                        <h2 class="font-display text-xl font-bold text-brand-navy">Title, Description &amp; Photos</h2>
                        <p class="text-xs text-slate-500">Provide an attractive title and clear photos to stand out.</p>
                    </div>
                </div>

                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Listing Title *</label>
                        <input type="text" name="title" required value="{{ old('title') }}" placeholder="e.g. Beautiful East-Facing 3 BHK Apartment in HSR Layout Sector 2" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan transition font-medium">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Detailed Description *</label>
                        <textarea name="description" rows="5" required placeholder="Describe your property: floor number, facing, nearby metro/schools, society amenities, maintenance, reason for selling/renting..." class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl p-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan transition leading-relaxed">{{ old('description') }}</textarea>
                    </div>

                    <!-- Amenities Checkboxes -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2.5">Key Amenities &amp; Highlights</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 text-xs text-slate-700 font-medium">
                            @php
                                $amenityOptions = [
                                    '24/7 Security', 'Power Backup (DG)', 'Lift Access', 'Covered Car Parking',
                                    'Gated Community', 'Swimming Pool', 'Clubhouse & Gym', 'Children Play Area',
                                    'Close to Metro', 'Water Storage', 'Vastu Compliant', 'Park / Garden Facing'
                                ];
                            @endphp
                            @foreach($amenityOptions as $am)
                                <label class="flex items-center gap-2 p-2 rounded-lg bg-slate-50 hover:bg-slate-100 transition cursor-pointer">
                                    <input type="checkbox" name="amenities[]" value="{{ $am }}" class="rounded text-brand-cyan focus:ring-brand-cyan" checked>
                                    <span>{{ $am }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Photos Upload -->
                    <div class="pt-4 border-t border-slate-100">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Upload Property Image</label>
                        <div class="flex flex-col sm:flex-row items-center gap-4">
                            <label class="w-full sm:w-2/3 flex flex-col items-center justify-center p-6 border-2 border-dashed border-slate-300 hover:border-brand-cyan rounded-2xl cursor-pointer bg-slate-50 hover:bg-sky-50/50 transition">
                                <i class="fa-solid fa-cloud-arrow-up text-3xl text-brand-cyan mb-2"></i>
                                <span class="text-xs font-bold text-brand-navy">Click to browse or drop photo</span>
                                <span class="text-[11px] text-slate-400 mt-1">JPEG, PNG, WebP up to 8MB</span>
                                <input type="file" name="image_file" accept="image/*" class="sr-only" onchange="previewUpload(this)">
                            </label>

                            <div class="w-full sm:w-1/3 text-xs text-slate-500 space-y-2">
                                <p class="font-bold text-slate-700">Or Paste Image URL:</p>
                                <input type="url" name="image_url" placeholder="https://..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:ring-2 focus:ring-brand-cyan">
                                <p class="text-[10px] text-slate-400 leading-normal">If no image is uploaded, high-definition verified architecture photography will be automatically attached.</p>
                            </div>
                        </div>
                        <div id="upload-preview-box" class="hidden mt-3 text-xs text-emerald-700 font-semibold flex items-center gap-2">
                            <i class="fa-solid fa-circle-check text-emerald-500"></i>
                            <span id="upload-filename">Image selected</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 5: SELLER CONTACT DETAILS -->
            <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200/90 shadow-sm">
                <div class="flex items-center gap-3 pb-6 mb-6 border-b border-slate-100">
                    <span class="flex size-9 items-center justify-center rounded-xl bg-brand-cyan text-white font-bold text-sm">05</span>
                    <div>
                        <h2 class="font-display text-xl font-bold text-brand-navy">Seller &amp; Contact Details</h2>
                        <p class="text-xs text-slate-500">Interested buyers and tenants will contact you directly on these details.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">You Are *</label>
                        <select name="posted_by" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan transition font-semibold">
                            <option value="Owner" selected>Individual Property Owner</option>
                            <option value="Builder">Builder / Developer</option>
                            <option value="Agent">Real Estate Professional / Agent</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Your Full Name *</label>
                        <input type="text" name="seller_name" required value="{{ old('seller_name') }}" placeholder="e.g. Ajay Poonia" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan transition font-medium">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Mobile / WhatsApp Number *</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-slate-500 font-bold text-sm">+91</span>
                            <input type="tel" name="seller_phone" required value="{{ old('seller_phone', '6376163263') }}" placeholder="6376163263" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl pl-14 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan transition font-bold text-slate-800">
                        </div>
                        <span class="text-[11px] text-slate-400 mt-1 block">Inquiries will be received via WhatsApp and direct call.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Email Address</label>
                        <input type="email" name="seller_email" value="{{ old('seller_email') }}" placeholder="e.g. yourname@example.com" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan transition">
                    </div>
                </div>

                <!-- Terms agreement -->
                <div class="mt-8 pt-6 border-t border-slate-100 flex items-start gap-3">
                    <input type="checkbox" id="terms" required checked class="mt-1 rounded text-brand-cyan focus:ring-brand-cyan">
                    <label for="terms" class="text-xs text-slate-500 leading-relaxed cursor-pointer">
                        I confirm that I am the authorized owner or representative of this property and all details provided are accurate and genuine. I agree to the <a href="{{ route('pricing') }}" class="text-brand-cyan font-bold hover:underline">Housing 365 Terms</a>.
                    </label>
                </div>
            </div>

            <!-- SUBMIT BUTTON BAR -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-6 bg-white rounded-3xl border border-slate-200/90 shadow-lg">
                <div class="text-xs text-slate-500">
                    <span class="font-bold text-brand-navy block text-sm">Free Instant Publication</span>
                    Your property will appear immediately on Housing 365 India.
                </div>
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-2xl bg-brand-cyan hover:bg-brand-cyan-hover px-10 py-4 text-base font-bold text-white shadow-xl shadow-brand-cyan/25 transition-all duration-200 hover:-translate-y-0.5">
                    <span>Publish Property Free</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </div>
        </form>

    </div>
</section>

@endsection

@push('scripts')
<script>
    const TYPE_OPTIONS = {
        residential: ['Apartment', 'Independent House', 'Builder Floor', 'Villa', 'Penthouse', 'Studio Apartment'],
        commercial: ['Commercial Office', 'Retail Shop', 'Showroom', 'Warehouse', 'Co-Working Space', 'Industrial Shed'],
        plot_land: ['Residential Plot', 'Commercial Land', 'Agricultural Farm Land', 'Industrial Land']
    };

    function handleCategoryChange(cat) {
        const select = document.getElementById('property_type_select');
        select.innerHTML = '';
        const types = TYPE_OPTIONS[cat] || TYPE_OPTIONS.residential;
        types.forEach(t => {
            const opt = document.createElement('option');
            opt.value = t;
            opt.textContent = t;
            select.appendChild(opt);
        });

        // Hide/show residential fields (BHK/baths)
        const resFields = document.getElementById('residential-fields');
        if (cat === 'residential') {
            resFields.classList.remove('hidden');
        } else {
            resFields.classList.add('hidden');
        }
    }

    function handlePurposeChange(purpose) {
        const label = document.getElementById('price-label');
        const helper = document.getElementById('price-helper-text');
        const input = document.getElementById('price_input');

        if (purpose === 'sale') {
            label.textContent = 'Total Selling Price (in ₹ INR) *';
            input.placeholder = 'e.g. 18500000 for ₹1.85 Cr, 6500000 for ₹65 Lakhs';
        } else {
            label.textContent = 'Expected Monthly Rent (in ₹ INR) *';
            input.placeholder = 'e.g. 45000 for ₹45,000/month';
        }
        updatePriceHelper(input.value);
    }

    function updatePriceHelper(val) {
        const num = parseFloat(val);
        const helper = document.getElementById('price-helper-text');
        if (!num || isNaN(num) || num <= 0) {
            helper.textContent = 'Enter numbers only';
            return;
        }

        const isSale = document.querySelector('input[name="purpose"]:checked')?.value === 'sale';
        if (isSale) {
            if (num >= 10000000) {
                helper.textContent = '≈ ₹ ' + (num / 10000000).toFixed(2) + ' Crore (Total Price)';
            } else if (num >= 100000) {
                helper.textContent = '≈ ₹ ' + (num / 100000).toFixed(2) + ' Lakhs (Total Price)';
            } else {
                helper.textContent = '₹ ' + num.toLocaleString('en-IN') + ' (Total Price)';
            }
        } else {
            helper.textContent = '₹ ' + num.toLocaleString('en-IN') + ' / Month';
        }
    }

    function previewUpload(input) {
        if (input.files && input.files[0]) {
            document.getElementById('upload-preview-box').classList.remove('hidden');
            document.getElementById('upload-filename').textContent = 'Selected: ' + input.files[0].name;
        }
    }

    // Initialize on load
    document.addEventListener('DOMContentLoaded', function() {
        const initialCategory = document.querySelector('input[name="category"]:checked')?.value || 'residential';
        handleCategoryChange(initialCategory);

        const initialPurpose = document.querySelector('input[name="purpose"]:checked')?.value || 'rent';
        handlePurposeChange(initialPurpose);
    });
</script>
@endpush
