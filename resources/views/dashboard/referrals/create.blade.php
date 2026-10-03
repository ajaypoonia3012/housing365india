@extends('layouts.app')

@section('title', 'Refer a Property | Housing365 India')

@section('content')
<section class="py-8 sm:py-12 bg-slate-50 min-h-[85vh]">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Breadcrumb & Back -->
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('dashboard.referrals') }}" class="text-xs font-bold text-slate-500 hover:text-brand-navy flex items-center gap-1.5 transition">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to My Referrals</span>
            </a>
            <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">
                ₹{{ $rewardAmount }} Reward on Qualification
            </span>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold">
                <div class="flex items-center gap-2 mb-1 text-sm font-bold">
                    <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                    <span>Please fix the following issues:</span>
                </div>
                <ul class="list-disc pl-5 space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 shadow-sm">
            <div class="border-b border-slate-100 pb-5 mb-8">
                <h1 class="text-2xl font-display font-extrabold text-brand-navy">
                    Refer a Property Owner
                </h1>
                <p class="text-xs text-slate-500 mt-1">
                    Submit genuine property details or upload a photo of a "To Let" / "For Sale" sign.
                </p>
            </div>

            <!-- Method Switcher Pills -->
            <div class="flex items-center gap-2 p-1.5 rounded-2xl bg-slate-100 mb-8 max-w-md mx-auto sm:mx-0">
                <button type="button" onclick="switchMethod('refer_owner')" id="btnMethodOwner"
                        class="flex-1 py-2 px-4 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 bg-brand-navy text-white shadow-sm">
                    <i class="fa-solid fa-user-check"></i>
                    <span>Method A: Owner Details</span>
                </button>
                <button type="button" onclick="switchMethod('property_board_photo')" id="btnMethodBoard"
                        class="flex-1 py-2 px-4 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 text-slate-600 hover:text-brand-navy">
                    <i class="fa-solid fa-camera"></i>
                    <span>Method B: Board Photo</span>
                </button>
            </div>

            <form action="{{ route('dashboard.referrals.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" name="submission_method" id="inputSubmissionMethod" value="refer_owner">

                <!-- Method B Photo Upload Area (conditionally shown) -->
                <div id="photoUploadSection" class="hidden p-5 rounded-2xl bg-amber-50/50 border border-amber-200/80 space-y-3">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-camera text-amber-600"></i>
                        <span class="text-xs font-bold text-amber-900 uppercase tracking-wider">Property Board Photo</span>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Upload a clear photo of the "To Let" or "For Sale" banner/board clearly displaying the contact phone number. Files are securely stored in private storage.
                    </p>
                    <input type="file" name="photo" accept="image/jpeg,image/png,image/webp"
                           class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brand-navy file:text-white hover:file:bg-brand-navy-light cursor-pointer">
                    <span class="text-[11px] text-slate-400 block">Accepted formats: JPG, PNG, WebP (Max 5MB)</span>
                </div>

                <!-- Section: Owner & Contact Info -->
                <div class="space-y-4">
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-400">1. Owner / Contact Information</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-brand-navy mb-1">Owner Name *</label>
                            <input type="text" name="owner_name" value="{{ old('owner_name') }}" required placeholder="e.g. Ramesh Kumar"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-brand-navy mb-1">Owner 10-Digit Mobile *</label>
                            <div class="flex items-center">
                                <span class="px-3 py-2.5 rounded-l-xl bg-slate-100 border border-r-0 border-slate-200 text-xs font-bold text-slate-500">+91</span>
                                <input type="tel" name="owner_phone" value="{{ old('owner_phone') }}" required placeholder="9876543210"
                                       class="flex-1 px-3.5 py-2.5 rounded-r-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-brand-navy mb-1">Owner Email <span class="text-slate-400 font-normal">(Optional)</span></label>
                            <input type="email" name="owner_email" value="{{ old('owner_email') }}" placeholder="owner@example.com"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-brand-navy mb-1">Your Relationship <span class="text-slate-400 font-normal">(Optional)</span></label>
                            <select name="referrer_relationship" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan">
                                <option value="">Select relationship</option>
                                <option value="Friend / Acquaintance" {{ old('referrer_relationship') == 'Friend / Acquaintance' ? 'selected' : '' }}>Friend / Acquaintance</option>
                                <option value="Neighbor" {{ old('referrer_relationship') == 'Neighbor' ? 'selected' : '' }}>Neighbor</option>
                                <option value="Relative / Family" {{ old('referrer_relationship') == 'Relative / Family' ? 'selected' : '' }}>Relative / Family</option>
                                <option value="Current Tenant" {{ old('referrer_relationship') == 'Current Tenant' ? 'selected' : '' }}>Current Tenant</option>
                                <option value="Saw Board / Signage" {{ old('referrer_relationship') == 'Saw Board / Signage' ? 'selected' : '' }}>Saw Board / Signage</option>
                                <option value="Other" {{ old('referrer_relationship') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section: Property Location & Classification -->
                <div class="space-y-4 pt-4 border-t border-slate-100">
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-400">2. Property Details</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-brand-navy mb-1">City *</label>
                            <select name="city" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan">
                                <option value="">Select City</option>
                                @foreach($cities as $c)
                                    <option value="{{ $c }}" {{ old('city') == $c ? 'selected' : '' }}>{{ $c }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-brand-navy mb-1">Locality / Sector *</label>
                            <input type="text" name="locality" value="{{ old('locality') }}" required placeholder="e.g. Whitefield, Indiranagar"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-brand-navy mb-1">Property Type *</label>
                            <select name="property_type" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan">
                                <option value="Flat / Apartment" {{ old('property_type') == 'Flat / Apartment' ? 'selected' : '' }}>Flat / Apartment</option>
                                <option value="Independent House / Villa" {{ old('property_type') == 'Independent House / Villa' ? 'selected' : '' }}>Independent House / Villa</option>
                                <option value="Builder Floor" {{ old('property_type') == 'Builder Floor' ? 'selected' : '' }}>Builder Floor</option>
                                <option value="PG / Hostel" {{ old('property_type') == 'PG / Hostel' ? 'selected' : '' }}>PG / Hostel</option>
                                <option value="Commercial Office" {{ old('property_type') == 'Commercial Office' ? 'selected' : '' }}>Commercial Office</option>
                                <option value="Retail Shop" {{ old('property_type') == 'Retail Shop' ? 'selected' : '' }}>Retail Shop</option>
                                <option value="Plot / Land" {{ old('property_type') == 'Plot / Land' ? 'selected' : '' }}>Plot / Land</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-brand-navy mb-1">Listing Intent *</label>
                            <select name="listing_intent" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan">
                                <option value="rent" {{ old('listing_intent') == 'rent' ? 'selected' : '' }}>For Rent</option>
                                <option value="sale" {{ old('listing_intent') == 'sale' ? 'selected' : '' }}>For Sale</option>
                                <option value="pg" {{ old('listing_intent') == 'pg' ? 'selected' : '' }}>PG Accommodations</option>
                                <option value="coliving" {{ old('listing_intent') == 'coliving' ? 'selected' : '' }}>Co-Living</option>
                                <option value="commercial" {{ old('listing_intent') == 'commercial' ? 'selected' : '' }}>Commercial Lease / Sale</option>
                                <option value="land" {{ old('listing_intent') == 'land' ? 'selected' : '' }}>Plot / Land Sale</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-brand-navy mb-1">Approximate Address / Landmark <span class="text-slate-400 font-normal">(Optional)</span></label>
                        <input type="text" name="property_address" value="{{ old('property_address') }}" placeholder="e.g. Near Metro Station, 2nd Main Road"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-brand-navy mb-1">Additional Notes <span class="text-slate-400 font-normal">(Optional)</span></label>
                        <textarea name="notes" rows="2" placeholder="e.g. Expected rent ₹25,000, 2BHK furnished, available next month"
                                  class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Non-Negotiable Genuine Confirmation Checkbox -->
                <div class="pt-4 border-t border-slate-100">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="consent_confirmed" value="1" required {{ old('consent_confirmed') ? 'checked' : '' }}
                               class="mt-1 rounded border-slate-300 text-brand-cyan focus:ring-brand-cyan">
                        <span class="text-xs text-slate-600 leading-relaxed font-medium">
                            I confirm that the information provided is genuine and that I have a legitimate basis for referring this property/owner to Housing365. I understand that duplicate submissions, self-referrals, or broker leads do not qualify for the ₹100 reward.
                        </span>
                    </label>
                </div>

                <div class="pt-4">
                    <button type="submit"
                            class="w-full py-4 rounded-2xl bg-brand-cyan hover:bg-brand-cyan-hover text-brand-navy font-black text-sm tracking-wide shadow-lg shadow-brand-cyan/25 transition">
                        Submit Referral &amp; Queue for ₹{{ $rewardAmount }} Reward
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
function switchMethod(method) {
    const input = document.getElementById('inputSubmissionMethod');
    const photoSection = document.getElementById('photoUploadSection');
    const btnOwner = document.getElementById('btnMethodOwner');
    const btnBoard = document.getElementById('btnMethodBoard');

    input.value = method;

    if (method === 'property_board_photo') {
        photoSection.classList.remove('hidden');
        btnBoard.classList.remove('text-slate-600');
        btnBoard.classList.add('bg-brand-navy', 'text-white', 'shadow-sm');
        btnOwner.classList.remove('bg-brand-navy', 'text-white', 'shadow-sm');
        btnOwner.classList.add('text-slate-600');
    } else {
        photoSection.classList.add('hidden');
        btnOwner.classList.remove('text-slate-600');
        btnOwner.classList.add('bg-brand-navy', 'text-white', 'shadow-sm');
        btnBoard.classList.remove('bg-brand-navy', 'text-white', 'shadow-sm');
        btnBoard.classList.add('text-slate-600');
    }
}
</script>
@endsection
