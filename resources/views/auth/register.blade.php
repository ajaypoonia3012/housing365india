@extends('layouts.app')

@section('title', 'Register & Join Housing365 Prime | Zero Brokerage Real Estate')
@section('meta_description', 'Create your Housing365 India account to unlock verified direct owner contacts, save lakhs in brokerage, or list your properties for free.')

@section('content')
<section class="py-16 sm:py-24 bg-gradient-to-b from-[#F7FAFD] via-white to-white">
    <div class="mx-auto w-full max-w-xl px-4 sm:px-6">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 rounded-full border border-amber-400/40 bg-amber-400/10 px-3.5 py-1 text-xs font-extrabold uppercase tracking-wider text-amber-800 mb-3">
                <i class="fa-solid fa-crown text-amber-600"></i>
                <span>HOUSING365 MEMBERSHIP</span>
            </div>
            <h1 class="font-display text-3xl sm:text-4xl font-extrabold text-brand-navy">Create Your Account</h1>
            <p class="text-slate-600 text-sm mt-2">Connect directly with verified property owners with zero broker spam.</p>
        </div>

        <!-- Registration Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200/80 shadow-xl shadow-slate-900/5">
            @if (isset($errors) && $errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf

                <!-- User Type Selector -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">I am a:</label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="user_type" value="tenant" class="peer sr-only" {{ old('user_type', 'tenant') === 'tenant' ? 'checked' : '' }}>
                            <div class="p-2.5 rounded-xl border border-slate-200 peer-checked:border-brand-cyan peer-checked:bg-brand-cyan/5 text-center transition">
                                <i class="fa-solid fa-key text-slate-400 peer-checked:text-brand-cyan block text-sm mb-0.5"></i>
                                <span class="text-xs font-bold text-slate-700 peer-checked:text-brand-navy block">Tenant</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="user_type" value="buyer" class="peer sr-only" {{ old('user_type') === 'buyer' ? 'checked' : '' }}>
                            <div class="p-2.5 rounded-xl border border-slate-200 peer-checked:border-emerald-600 peer-checked:bg-emerald-50 text-center transition">
                                <i class="fa-solid fa-house-chimney text-slate-400 peer-checked:text-emerald-600 block text-sm mb-0.5"></i>
                                <span class="text-xs font-bold text-slate-700 peer-checked:text-brand-navy block">Buyer</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="user_type" value="owner" class="peer sr-only" {{ old('user_type') === 'owner' ? 'checked' : '' }}>
                            <div class="p-2.5 rounded-xl border border-slate-200 peer-checked:border-amber-500 peer-checked:bg-amber-50 text-center transition">
                                <i class="fa-solid fa-user-shield text-slate-400 peer-checked:text-amber-600 block text-sm mb-0.5"></i>
                                <span class="text-xs font-bold text-slate-700 peer-checked:text-brand-navy block">Owner</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Full Name -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2.5 text-xs sm:text-sm focus:ring-2 focus:ring-brand-cyan outline-none" placeholder="Ajay Poonia">
                </div>

                <!-- Email Address -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Email Address *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2.5 text-xs sm:text-sm focus:ring-2 focus:ring-brand-cyan outline-none" placeholder="ajay@example.com">
                </div>

                <!-- Phone Number & City -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">WhatsApp Phone *</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2.5 text-xs sm:text-sm focus:ring-2 focus:ring-brand-cyan outline-none" placeholder="+91 63761 63263">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">City</label>
                        <select name="city" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3 py-2.5 text-xs sm:text-sm focus:ring-2 focus:ring-brand-cyan outline-none font-medium">
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
                </div>

                <!-- Password & Confirm -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Password *</label>
                        <input type="password" name="password" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2.5 text-xs sm:text-sm focus:ring-2 focus:ring-brand-cyan outline-none" placeholder="••••••••">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Confirm Password *</label>
                        <input type="password" name="password_confirmation" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2.5 text-xs sm:text-sm focus:ring-2 focus:ring-brand-cyan outline-none" placeholder="••••••••">
                    </div>
                </div>

                <!-- Housing365 Prime Membership Option -->
                <div class="pt-3 border-t border-slate-100">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                            <i class="fa-solid fa-crown text-amber-500"></i>
                            <span>Select Membership Option</span>
                        </label>
                        <a href="{{ route('prime') }}" target="_blank" class="text-[11px] font-bold text-brand-cyan hover:underline">Compare Plans &rarr;</a>
                    </div>

                    <div class="space-y-2">
                        <label class="flex items-center justify-between p-3 rounded-2xl border border-slate-200 bg-slate-50/50 hover:bg-slate-50 cursor-pointer">
                            <div class="flex items-center gap-2.5">
                                <input type="radio" name="plan" value="" class="accent-brand-cyan" {{ empty($selectedPlan) ? 'checked' : '' }}>
                                <div>
                                    <span class="text-xs font-bold text-slate-800 block">Standard Free Account</span>
                                    <span class="text-[11px] text-slate-500">Browse verified listings &bull; Post free property</span>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-slate-600">₹0</span>
                        </label>

                        <label class="flex items-center justify-between p-3 rounded-2xl border-2 border-amber-400 bg-amber-50/40 hover:bg-amber-50 cursor-pointer">
                            <div class="flex items-center gap-2.5">
                                <input type="radio" name="plan" value="Prime Pro" class="accent-amber-500" {{ $selectedPlan === 'Prime Pro' || empty($selectedPlan) ? 'checked' : '' }}>
                                <div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-xs font-extrabold text-brand-navy block">Prime Pro (Assisted Search)</span>
                                        <span class="px-1.5 py-0.5 rounded bg-amber-400 text-slate-950 text-[9px] font-black uppercase">POPULAR</span>
                                    </div>
                                    <span class="text-[11px] text-slate-600">50 Owner Contacts &bull; Dedicated Relationship Manager</span>
                                </div>
                            </div>
                            <span class="text-xs font-extrabold text-brand-navy">₹1,999</span>
                        </label>

                        <label class="flex items-center justify-between p-3 rounded-2xl border border-slate-200 bg-slate-50/50 hover:bg-slate-50 cursor-pointer">
                            <div class="flex items-center gap-2.5">
                                <input type="radio" name="plan" value="Prime Basic" class="accent-brand-cyan" {{ $selectedPlan === 'Prime Basic' ? 'checked' : '' }}>
                                <div>
                                    <span class="text-xs font-bold text-slate-800 block">Prime Basic (Self Search)</span>
                                    <span class="text-[11px] text-slate-500">25 Owner Contacts &bull; Direct Numbers Unlocked</span>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-slate-800">₹999</span>
                        </label>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-3.5 px-4 rounded-2xl bg-brand-cyan hover:bg-brand-cyan-hover text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-brand-cyan/25 transition-all duration-200 hover:-translate-y-0.5">
                        Create Account &amp; Continue &rarr;
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center text-xs text-slate-500">
                Already have an account? 
                <a href="{{ route('login') }}" class="font-bold text-brand-cyan hover:text-brand-navy underline ml-1">Sign In</a>
            </div>
        </div>

    </div>
</section>
@endsection
