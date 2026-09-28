@extends('layouts.app')

@section('title', 'Sign In to Housing365 | Member & Prime Access')
@section('meta_description', 'Sign in to your Housing365 account to access your Prime membership, saved properties, relationship manager, and direct owner contacts.')

@section('content')
<section class="py-16 sm:py-24 bg-gradient-to-b from-[#F7FAFD] via-white to-white min-h-[75vh] flex items-center">
    <div class="mx-auto w-full max-w-md px-4 sm:px-6">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 rounded-full border border-brand-cyan/30 bg-brand-cyan/10 px-3.5 py-1 text-xs font-extrabold uppercase tracking-wider text-brand-navy mb-3">
                <i class="fa-solid fa-arrow-right-to-bracket text-brand-cyan"></i>
                <span>MEMBER PORTAL</span>
            </div>
            <h1 class="font-display text-3xl font-extrabold text-brand-navy">Welcome Back</h1>
            <p class="text-slate-600 text-sm mt-1.5">Sign in to access your direct owner contacts &amp; Prime benefits.</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xl shadow-slate-900/5">
            @if (isset($errors) && $errors->any())
                <div class="mb-5 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('info'))
                <div class="mb-5 p-4 rounded-2xl bg-blue-50 border border-blue-200 text-blue-800 text-xs flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-blue-600"></i>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Email Address -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Email Address</label>
                    <div class="relative">
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl pl-10 pr-4 py-2.5 text-xs sm:text-sm focus:ring-2 focus:ring-brand-cyan outline-none" placeholder="name@example.com">
                        <i class="fa-regular fa-envelope absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Password</label>
                        <a href="https://wa.me/916376163263?text=Hi%20Housing365,%20I%20need%20help%20recovering%20my%20account%20access." target="_blank" class="text-[11px] font-bold text-brand-cyan hover:underline">Forgot?</a>
                    </div>
                    <div class="relative">
                        <input type="password" name="password" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl pl-10 pr-4 py-2.5 text-xs sm:text-sm focus:ring-2 focus:ring-brand-cyan outline-none" placeholder="••••••••">
                        <i class="fa-solid fa-lock absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember" class="size-4 rounded border-slate-300 text-brand-cyan focus:ring-brand-cyan">
                    <label for="remember" class="ml-2 block text-xs text-slate-600 select-none">Remember this device</label>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" class="w-full py-3.5 px-4 rounded-2xl bg-brand-navy hover:bg-brand-navy-hover text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-brand-navy/20 transition-all duration-200 hover:-translate-y-0.5">
                        Sign In &rarr;
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-5 border-t border-slate-100 flex flex-col gap-3 text-center text-xs">
                <div>
                    <span class="text-slate-500">Don't have an account yet?</span>
                    <a href="{{ route('register') }}" class="font-bold text-brand-cyan hover:text-brand-navy underline ml-1">Create Account</a>
                </div>

                <!-- Prime Prompt Banner -->
                <div class="p-3 rounded-2xl bg-amber-50/70 border border-amber-300/60 text-left flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-crown text-amber-500 text-sm"></i>
                        <div>
                            <span class="text-[11px] font-extrabold text-amber-950 block">Want Zero Brokerage?</span>
                            <span class="text-[10px] text-amber-800">Unlock 50+ owner numbers directly</span>
                        </div>
                    </div>
                    <a href="{{ route('prime') }}" class="px-2.5 py-1 rounded-lg bg-amber-400 hover:bg-amber-500 text-slate-950 font-black text-[10px] uppercase tracking-wider shrink-0 transition">
                        Join Prime
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection
