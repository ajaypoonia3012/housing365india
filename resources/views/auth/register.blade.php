@extends('layouts.app')

@section('title', 'Create Your Account | Housing365 India')
@section('meta_description', 'Join Housing365 India — Everything Property. One Platform. Access owner listings, rentals, commercial spaces, and verified real estate services.')

@section('content')
<section class="py-12 sm:py-20 bg-gradient-to-b from-[#F7FAFD] via-white to-white">
    <div class="mx-auto w-full max-w-2xl px-4 sm:px-6">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 rounded-full border border-brand-cyan/30 bg-brand-cyan/10 px-3.5 py-1 text-xs font-extrabold uppercase tracking-wider text-brand-navy mb-3">
                <i class="fa-solid fa-id-card text-brand-cyan"></i>
                <span>ONE ACCOUNT &bull; ALL PROPERTY LIFECYCLE</span>
            </div>
            <h1 class="font-display text-3xl sm:text-4xl font-extrabold text-brand-navy">Create Your Account</h1>
            <p class="text-slate-600 text-sm mt-2">Manage listings, discover verified homes, or deliver professional property services across India.</p>
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

            <form action="{{ route('register') }}" method="POST" class="space-y-6">
                @csrf

                <!-- What brings you to Housing365? (Multi-select Personas) -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-800">
                            What brings you to Housing365? <span class="text-brand-cyan font-normal lowercase">(select all that apply)</span>
                        </label>
                    </div>
                    <p class="text-xs text-slate-500 mb-3">You can hold multiple roles with a single login and switch workspaces anytime.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        <!-- Owner -->
                        <label class="relative flex items-center gap-3 p-3 rounded-2xl border border-slate-200 hover:border-brand-cyan/50 hover:bg-slate-50/60 cursor-pointer transition has-[:checked]:border-brand-cyan has-[:checked]:bg-brand-cyan/5">
                            <input type="checkbox" name="roles[]" value="owner" class="w-4 h-4 rounded text-brand-cyan focus:ring-brand-cyan" {{ in_array('owner', old('roles', [])) ? 'checked' : '' }}>
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center text-xs">
                                    <i class="fa-solid fa-key"></i>
                                </div>
                                <span class="text-xs font-bold text-slate-800">I own property</span>
                            </div>
                        </label>

                        <!-- Tenant / Rent seeker -->
                        <label class="relative flex items-center gap-3 p-3 rounded-2xl border border-slate-200 hover:border-brand-cyan/50 hover:bg-slate-50/60 cursor-pointer transition has-[:checked]:border-brand-cyan has-[:checked]:bg-brand-cyan/5">
                            <input type="checkbox" name="roles[]" value="tenant" class="w-4 h-4 rounded text-brand-cyan focus:ring-brand-cyan" {{ in_array('tenant', old('roles', ['tenant'])) ? 'checked' : '' }}>
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-xl bg-blue-500/10 text-brand-navy flex items-center justify-center text-xs">
                                    <i class="fa-solid fa-house-user"></i>
                                </div>
                                <span class="text-xs font-bold text-slate-800">I am looking for property</span>
                            </div>
                        </label>

                        <!-- Buyer -->
                        <label class="relative flex items-center gap-3 p-3 rounded-2xl border border-slate-200 hover:border-brand-cyan/50 hover:bg-slate-50/60 cursor-pointer transition has-[:checked]:border-brand-cyan has-[:checked]:bg-brand-cyan/5">
                            <input type="checkbox" name="roles[]" value="buyer" class="w-4 h-4 rounded text-brand-cyan focus:ring-brand-cyan" {{ in_array('buyer', old('roles', [])) ? 'checked' : '' }}>
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center text-xs">
                                    <i class="fa-solid fa-money-bill-wave"></i>
                                </div>
                                <span class="text-xs font-bold text-slate-800">I am buying property</span>
                            </div>
                        </label>

                        <!-- Seller -->
                        <label class="relative flex items-center gap-3 p-3 rounded-2xl border border-slate-200 hover:border-brand-cyan/50 hover:bg-slate-50/60 cursor-pointer transition has-[:checked]:border-brand-cyan has-[:checked]:bg-brand-cyan/5">
                            <input type="checkbox" name="roles[]" value="seller" class="w-4 h-4 rounded text-brand-cyan focus:ring-brand-cyan" {{ in_array('seller', old('roles', [])) ? 'checked' : '' }}>
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-xl bg-purple-500/10 text-purple-600 flex items-center justify-center text-xs">
                                    <i class="fa-solid fa-handshake"></i>
                                </div>
                                <span class="text-xs font-bold text-slate-800">I am selling property</span>
                            </div>
                        </label>

                        <!-- Broker -->
                        <label class="relative flex items-center gap-3 p-3 rounded-2xl border border-slate-200 hover:border-brand-cyan/50 hover:bg-slate-50/60 cursor-pointer transition has-[:checked]:border-brand-cyan has-[:checked]:bg-brand-cyan/5">
                            <input type="checkbox" name="roles[]" value="broker" class="w-4 h-4 rounded text-brand-cyan focus:ring-brand-cyan" {{ in_array('broker', old('roles', [])) ? 'checked' : '' }}>
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-xl bg-indigo-500/10 text-indigo-600 flex items-center justify-center text-xs">
                                    <i class="fa-solid fa-briefcase"></i>
                                </div>
                                <span class="text-xs font-bold text-slate-800">I am a broker</span>
                            </div>
                        </label>

                        <!-- Builder -->
                        <label class="relative flex items-center gap-3 p-3 rounded-2xl border border-slate-200 hover:border-brand-cyan/50 hover:bg-slate-50/60 cursor-pointer transition has-[:checked]:border-brand-cyan has-[:checked]:bg-brand-cyan/5">
                            <input type="checkbox" name="roles[]" value="builder" class="w-4 h-4 rounded text-brand-cyan focus:ring-brand-cyan" {{ in_array('builder', old('roles', [])) ? 'checked' : '' }}>
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-xl bg-orange-500/10 text-orange-600 flex items-center justify-center text-xs">
                                    <i class="fa-solid fa-city"></i>
                                </div>
                                <span class="text-xs font-bold text-slate-800">I represent a builder</span>
                            </div>
                        </label>

                        <!-- PG Operator -->
                        <label class="relative flex items-center gap-3 p-3 rounded-2xl border border-slate-200 hover:border-brand-cyan/50 hover:bg-slate-50/60 cursor-pointer transition has-[:checked]:border-brand-cyan has-[:checked]:bg-brand-cyan/5">
                            <input type="checkbox" name="roles[]" value="pg_operator" class="w-4 h-4 rounded text-brand-cyan focus:ring-brand-cyan" {{ in_array('pg_operator', old('roles', [])) ? 'checked' : '' }}>
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-xl bg-rose-500/10 text-rose-600 flex items-center justify-center text-xs">
                                    <i class="fa-solid fa-bed"></i>
                                </div>
                                <span class="text-xs font-bold text-slate-800">I operate PG / Co-living</span>
                            </div>
                        </label>

                        <!-- Coworking Operator -->
                        <label class="relative flex items-center gap-3 p-3 rounded-2xl border border-slate-200 hover:border-brand-cyan/50 hover:bg-slate-50/60 cursor-pointer transition has-[:checked]:border-brand-cyan has-[:checked]:bg-brand-cyan/5">
                            <input type="checkbox" name="roles[]" value="coworking_operator" class="w-4 h-4 rounded text-brand-cyan focus:ring-brand-cyan" {{ in_array('coworking_operator', old('roles', [])) ? 'checked' : '' }}>
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-xl bg-teal-500/10 text-teal-600 flex items-center justify-center text-xs">
                                    <i class="fa-solid fa-laptop"></i>
                                </div>
                                <span class="text-xs font-bold text-slate-800">I operate Coworking</span>
                            </div>
                        </label>

                        <!-- Vendor / Services -->
                        <label class="relative flex items-center gap-3 p-3 rounded-2xl border border-slate-200 hover:border-brand-cyan/50 hover:bg-slate-50/60 cursor-pointer transition has-[:checked]:border-brand-cyan has-[:checked]:bg-brand-cyan/5 sm:col-span-2">
                            <input type="checkbox" name="roles[]" value="vendor" class="w-4 h-4 rounded text-brand-cyan focus:ring-brand-cyan" {{ in_array('vendor', old('roles', [])) ? 'checked' : '' }}>
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-xl bg-cyan-500/10 text-brand-cyan flex items-center justify-center text-xs">
                                    <i class="fa-solid fa-screwdriver-wrench"></i>
                                </div>
                                <span class="text-xs font-bold text-slate-800">I provide property services (Housing365 Care, Cleaning, Repairs, Movers)</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Account Information -->
                <div class="space-y-4 pt-4 border-t border-slate-100">
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
                            <input type="tel" name="phone" value="{{ old('phone') }}" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2.5 text-xs sm:text-sm focus:ring-2 focus:ring-brand-cyan outline-none" placeholder="+91 98812 65370">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">City</label>
                            <select name="city" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3 py-2.5 text-xs sm:text-sm focus:ring-2 focus:ring-brand-cyan outline-none font-medium">
                                <option value="Jaipur" {{ old('city') === 'Jaipur' ? 'selected' : '' }}>Jaipur</option>
                                <option value="Mumbai" {{ old('city') === 'Mumbai' ? 'selected' : '' }}>Mumbai</option>
                                <option value="Bengaluru" {{ old('city') === 'Bengaluru' ? 'selected' : '' }}>Bengaluru</option>
                                <option value="Delhi" {{ old('city') === 'Delhi' ? 'selected' : '' }}>Delhi NCR</option>
                                <option value="Gurugram" {{ old('city') === 'Gurugram' ? 'selected' : '' }}>Gurugram</option>
                                <option value="Hyderabad" {{ old('city') === 'Hyderabad' ? 'selected' : '' }}>Hyderabad</option>
                                <option value="Pune" {{ old('city') === 'Pune' ? 'selected' : '' }}>Pune</option>
                                <option value="Ahmedabad" {{ old('city') === 'Ahmedabad' ? 'selected' : '' }}>Ahmedabad</option>
                                <option value="Other" {{ old('city') === 'Other' ? 'selected' : '' }}>Other City</option>
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
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-3.5 px-4 rounded-2xl bg-brand-cyan hover:bg-brand-cyan-hover text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-brand-cyan/25 transition-all duration-200 hover:-translate-y-0.5">
                        Create Account &amp; Enter Workspace &rarr;
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
