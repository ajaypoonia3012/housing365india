@extends('layouts.app')

@section('title', 'My Housing365 Prime Dashboard | Member Portal')
@section('meta_description', 'Manage your Housing365 Prime membership, connect with your personal relationship manager, track unlocked owner contacts, and browse verified listings.')

@section('content')
<section class="py-10 sm:py-16 bg-[#F8FAFD] min-h-screen">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        
        <!-- Welcome & Breadcrumb Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <div class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                    <a href="{{ route('home') }}" class="hover:text-brand-navy">Home</a>
                    <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                    <span>Member Dashboard</span>
                </div>
                <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-brand-navy flex items-center gap-3">
                    <span>Welcome, {{ $user->name }}</span>
                    @if($user->isPrimeActive())
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-gradient-to-r from-amber-400 to-yellow-500 text-slate-950 shadow-sm">
                            <i class="fa-solid fa-crown text-[11px]"></i> {{ $user->prime_plan ?? 'Prime' }} Member
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-200 text-slate-700">
                            Standard Account
                        </span>
                    @endif
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    {{ ucfirst($user->user_type ?? 'User') }} &bull; City: {{ $user->city ?? 'Jaipur' }} &bull; Registered: {{ $user->created_at->format('M d, Y') }}
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('properties.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 hover:text-brand-navy font-bold text-xs uppercase tracking-wider shadow-sm transition">
                    <i class="fa-solid fa-magnifying-glass text-brand-cyan"></i>
                    <span>Browse Properties</span>
                </a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs uppercase tracking-wider transition">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- 3-Column Overview Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
            
            <!-- Prime Membership Status Card -->
            <div class="rounded-3xl p-6 sm:p-7 relative overflow-hidden border {{ $user->isPrimeActive() ? 'bg-gradient-to-br from-amber-500 via-amber-400 to-yellow-500 text-slate-950 border-amber-300 shadow-xl shadow-amber-500/10' : 'bg-white text-slate-800 border-slate-200 shadow-sm' }}">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <span class="text-[11px] font-black uppercase tracking-wider {{ $user->isPrimeActive() ? 'text-slate-900/80' : 'text-slate-500' }}">MEMBERSHIP STATUS</span>
                        <h2 class="font-display text-2xl font-black mt-1">
                            {{ $user->isPrimeActive() ? ($user->prime_plan ?? 'Housing365 Prime') : 'No Active Prime Plan' }}
                        </h2>
                    </div>
                    <div class="size-12 rounded-2xl {{ $user->isPrimeActive() ? 'bg-slate-950/10 text-slate-950' : 'bg-amber-100 text-amber-600' }} flex items-center justify-center text-xl">
                        <i class="fa-solid fa-crown"></i>
                    </div>
                </div>

                @if($user->isPrimeActive())
                    <p class="text-xs font-medium text-slate-900/90 leading-relaxed mb-6">
                        Zero Brokerage Guaranteed. You have direct, unmasked access to verified landlords and property sellers.
                    </p>

                    <!-- Contact Quota Meter -->
                    <div class="bg-white/40 backdrop-blur-md rounded-2xl p-4 border border-white/50 mb-6">
                        <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                            <span>Owner Contacts Quota</span>
                            <span>{{ $user->remainingContacts() }} Available</span>
                        </div>
                        <div class="w-full h-2.5 rounded-full bg-slate-950/20 overflow-hidden">
                            @php
                                $total = $user->prime_contacts_limit ?: 50;
                                $unlocked = $user->unlocked_contacts_count ?: 0;
                                $pct = min(100, round(($unlocked / $total) * 100));
                            @endphp
                            <div class="h-full bg-slate-950 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                        </div>
                        <div class="flex items-center justify-between text-[11px] text-slate-800 font-medium mt-1">
                            <span>{{ $unlocked }} unlocked</span>
                            <span>Limit: {{ $total >= 999 ? 'Unlimited' : $total }} contacts</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-xs font-bold pt-2 border-t border-slate-950/10">
                        <span>Valid Until:</span>
                        <span>{{ $user->prime_expires_at ? $user->prime_expires_at->format('d M Y') : 'Active' }}</span>
                    </div>
                @else
                    <p class="text-xs text-slate-600 leading-relaxed mb-6">
                        You are currently on a free account. Upgrade to Housing365 Prime to unlock 25 to 50+ genuine owner numbers and save ₹25,000 to ₹1,00,000 in broker fees.
                    </p>
                    <a href="{{ route('prime') }}" class="inline-flex items-center justify-center gap-2 w-full py-3.5 px-4 rounded-2xl bg-gradient-to-r from-amber-500 to-yellow-500 hover:from-amber-600 hover:to-yellow-600 text-slate-950 font-extrabold text-xs uppercase tracking-wider shadow-md transition">
                        <i class="fa-solid fa-crown"></i>
                        <span>Activate Prime from ₹999 &rarr;</span>
                    </a>
                @endif
            </div>

            <!-- Dedicated Relationship Manager Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <span class="text-[11px] font-bold text-brand-cyan uppercase tracking-wider">YOUR CONCIERGE</span>
                            <h2 class="font-display text-xl font-bold text-brand-navy mt-1">Personal Relationship Manager</h2>
                        </div>
                        <div class="size-11 rounded-2xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-lg">
                            <i class="fa-solid fa-user-tie"></i>
                        </div>
                    </div>

                    <div class="flex items-center gap-3.5 p-3 rounded-2xl bg-slate-50 border border-slate-100 mb-4">
                        <div class="size-12 rounded-xl bg-gradient-to-br from-brand-navy to-brand-cyan text-white flex items-center justify-center font-bold text-sm">
                            PV
                        </div>
                        <div>
                            <h3 class="font-bold text-sm text-brand-navy">Priya Verma</h3>
                            <span class="text-xs text-slate-500">Senior Real Estate Consultant &bull; Jaipur</span>
                        </div>
                    </div>

                    <p class="text-xs text-slate-600 leading-relaxed mb-4">
                        Your manager handles shortlisting verified flats, coordinating owner call times, and drafting your 11-month legal rent agreement.
                    </p>
                </div>

                <div class="space-y-2 pt-2 border-t border-slate-100">
                    <a href="https://wa.me/916376163263?text=Hi%20Priya,%20I%20am%20Housing365%20Prime%20member%20{{ urlencode($user->name) }}.%20Can%20you%20help%20me%20shortlist%20properties?" target="_blank" class="flex items-center justify-center gap-2 w-full py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs uppercase tracking-wider shadow-sm transition">
                        <i class="fa-brands fa-whatsapp text-sm"></i>
                        <span>WhatsApp Relationship Manager</span>
                    </a>
                    <a href="tel:+916376163263" class="flex items-center justify-center gap-2 w-full py-2.5 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs uppercase tracking-wider transition">
                        <i class="fa-solid fa-phone text-xs"></i>
                        <span>Call +91 63761 63263</span>
                    </a>
                </div>
            </div>

            <!-- Prime Member Perks Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <span class="text-[11px] font-bold text-indigo-600 uppercase tracking-wider">MEMBER BENEFITS</span>
                            <h2 class="font-display text-xl font-bold text-brand-navy mt-1">Prime Included Services</h2>
                        </div>
                        <div class="size-11 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                    </div>

                    <ul class="space-y-3 text-xs text-slate-700 mb-6">
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-sm shrink-0 mt-0.5"></i>
                            <span><strong>Direct Owner Contact:</strong> 100% verified mobile numbers without broker commission</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-sm shrink-0 mt-0.5"></i>
                            <span><strong>Legal Rent Agreement:</strong> Complimentary 11-month draft with e-stamping guide</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-sm shrink-0 mt-0.5"></i>
                            <span><strong>Police &amp; Society Verification:</strong> Assistance with society approvals &amp; tenant verification</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-sm shrink-0 mt-0.5"></i>
                            <span><strong>WhatsApp Instant Alerts:</strong> Receive freshly listed owner properties before brokers see them</span>
                        </li>
                    </ul>
                </div>

                <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span>Average Member Savings:</span>
                    <strong class="text-emerald-600 font-bold">₹28,500 Saved</strong>
                </div>
            </div>

        </div>

        <!-- Recommended Direct Owner Properties -->
        <div class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <span class="text-xs font-extrabold uppercase tracking-widest text-brand-cyan">ZERO-BROKERAGE SELECTION</span>
                    <h2 class="font-display text-2xl font-bold text-brand-navy mt-1">Recommended Verified Properties in {{ $user->city ?? 'Jaipur' }}</h2>
                </div>
                <a href="{{ route('properties.index', ['city' => $user->city ?? 'Jaipur']) }}" class="text-xs font-bold text-brand-cyan hover:underline flex items-center gap-1">
                    <span>View All Listings</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            @if($recommendedProperties->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($recommendedProperties as $property)
                        <div class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-sm hover:shadow-md transition group flex flex-col justify-between">
                            <div>
                                <!-- Image & Tags -->
                                <div class="relative h-48 bg-slate-100 overflow-hidden">
                                    <img src="{{ $property->primary_image_url }}" alt="{{ $property->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    <div class="absolute top-3 left-3 flex flex-wrap gap-1.5">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-600 text-white shadow-sm">
                                            {{ $property->listing_type ?? 'Rent' }}
                                        </span>
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-400 text-slate-950 shadow-sm flex items-center gap-1">
                                            <i class="fa-solid fa-crown text-[9px]"></i> Owner
                                        </span>
                                    </div>
                                    <div class="absolute bottom-3 left-3 bg-slate-950/80 backdrop-blur-md px-3 py-1 rounded-xl text-white font-extrabold text-sm">
                                        ₹{{ number_format($property->price) }}<span class="text-[10px] font-normal text-slate-300">/mo</span>
                                    </div>
                                </div>

                                <!-- Property Info -->
                                <div class="p-5">
                                    <h3 class="font-bold text-base text-brand-navy group-hover:text-brand-cyan transition line-clamp-1">
                                        {{ $property->title }}
                                    </h3>
                                    <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                                        <i class="fa-solid fa-location-dot text-brand-cyan"></i>
                                        <span>{{ $property->locality ? $property->locality . ', ' : '' }}{{ $property->city }}</span>
                                    </p>

                                    <div class="flex items-center gap-4 text-xs text-slate-600 mt-4 pt-3 border-t border-slate-100">
                                        <span><strong>{{ $property->bedrooms ?? 2 }}</strong> BHK</span>
                                        <span>&bull;</span>
                                        <span><strong>{{ $property->bathrooms ?? 2 }}</strong> Baths</span>
                                        <span>&bull;</span>
                                        <span><strong>{{ $property->carpet_area_sqft ?? 1150 }}</strong> sq.ft</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Bar -->
                            <div class="p-5 pt-0">
                                <div class="p-3 rounded-2xl bg-amber-50/60 border border-amber-200/80 flex items-center justify-between">
                                    <div>
                                        <span class="text-[10px] font-bold text-slate-500 uppercase block">Owner Contact</span>
                                        <span class="text-xs font-bold text-slate-700 tracking-wider">+91 ••••• •••••</span>
                                    </div>

                                    <a href="{{ route('properties.show', $property->slug) }}" class="px-3 py-1.5 rounded-xl bg-brand-navy hover:bg-slate-800 text-white font-bold text-xs uppercase tracking-wider transition">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-3xl p-8 text-center border border-slate-200">
                    <p class="text-slate-500 text-sm">No properties currently found in your city. Check all Indian cities:</p>
                    <a href="{{ route('properties.index') }}" class="mt-3 inline-block font-bold text-brand-cyan hover:underline text-xs">Browse All India Properties &rarr;</a>
                </div>
            @endif
        </div>

    </div>
</section>
@endsection
