@extends('layouts.app')

@section('title', 'About Us | Housing 365 — Full-Service Leasing & Property Management')
@section('meta_description', 'Learn about Housing 365 mission, institutional-grade property management platform, executive leadership team, and innovative rental operations across Canada and the US.')

@section('content')

<!-- ── BREADCRUMB ── -->
<div class="bg-white border-b border-slate-100 py-3.5 text-sm text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-cyan transition font-medium">Home</a>
        <span class="text-slate-300">/</span>
        <span class="text-brand-navy font-bold">About Us</span>
    </div>
</div>

<!-- ── SECTION 1: HERO SHOWCASE (LIGHT ELEGANT DESIGN) ── -->
<section class="relative overflow-hidden bg-gradient-to-b from-[#F7FAFD] via-white to-[#F0F6FC] py-16 sm:py-20 lg:py-28">
    <div class="pointer-events-none absolute -left-40 top-0 h-[500px] w-[500px] rounded-full opacity-40 blur-3xl" style="background: radial-gradient(circle, rgba(0,174,239,0.18) 0%, transparent 70%)" aria-hidden="true"></div>
    <div class="pointer-events-none absolute right-0 top-20 h-[600px] w-[600px] rounded-full opacity-30 blur-3xl" style="background: radial-gradient(circle, rgba(3,35,75,0.06) 0%, transparent 70%)" aria-hidden="true"></div>

    <div class="relative mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-12 lg:gap-10">
            
            <!-- Left Column: Copy & Value Proposition -->
            <div class="lg:col-span-6 xl:col-span-6">
                <!-- Eyebrow Badge -->
                <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-brand-cyan/20 bg-brand-cyan/5 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                    <span class="h-2 w-2 rounded-full bg-brand-cyan animate-pulse"></span>
                    {{ $page->hero_badge ?? 'OUR MISSION & PURPOSE' }}
                </div>

                <!-- Headline -->
                <h1 class="font-display text-4xl font-extrabold leading-[1.12] tracking-tight text-brand-navy sm:text-5xl md:text-6xl">
                    Revolutionizing the <br>
                    <span class="text-brand-cyan font-extrabold">Rental Experience.</span>
                </h1>

                <!-- Subheadline -->
                <p class="mt-6 max-w-xl text-base sm:text-lg leading-relaxed text-slate-600">
                    {{ $page->hero_subtitle ?? 'We bridge the gap between quality tenants and institutional property owners through intelligent technology, complete transparency, and hospitality-driven leasing execution.' }}
                </p>

                <!-- Value Highlights -->
                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm font-semibold text-slate-700">
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-circle-check text-brand-cyan"></i>
                        <span>Zero Upfront Retainers</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-circle-check text-brand-cyan"></i>
                        <span>14-Day Average Lease Velocity</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-circle-check text-brand-cyan"></i>
                        <span>100% Verified Indian Tenancies</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-circle-check text-brand-cyan"></i>
                        <span>25+ Major Metros Served</span>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="mt-9 flex flex-wrap items-center gap-4">
                    <a href="{{ route('contact') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand-cyan px-8 py-4 text-base font-bold text-white shadow-lg shadow-brand-cyan/25 transition-all duration-200 hover:-translate-y-0.5 hover:bg-brand-cyan-hover">
                        <span>Talk to an Expert</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>

                    <a href="{{ route('properties.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-7 py-4 text-base font-bold text-brand-navy shadow-sm transition-all duration-200 hover:border-slate-400 hover:bg-slate-50">
                        Explore Available Rentals
                    </a>
                </div>
            </div>

            <!-- Right Column: Visual with Architecture Image + Floating Property Performance Card -->
            <div class="relative lg:col-span-6 xl:col-span-6">
                <div class="relative h-[440px] sm:h-[520px] lg:h-[580px] w-full overflow-hidden rounded-3xl shadow-2xl ring-1 ring-slate-200/80">
                    <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1600&auto=format&fit=crop&q=80" 
                         alt="Housing365 Corporate Headquarters & Properties" 
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent"></div>

                    <!-- Floating Milestone Badge Card -->
                    <div class="absolute bottom-6 left-4 right-4 rounded-2xl border border-white/80 bg-white/95 p-5 shadow-2xl backdrop-blur-md sm:bottom-8 sm:left-auto sm:right-8 sm:w-[420px]">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <div class="flex items-center gap-2.5">
                                <div class="flex size-10 items-center justify-center rounded-xl bg-brand-cyan/10 text-brand-cyan">
                                    <i class="fa-solid fa-award text-base"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-brand-navy">Institutional Excellence</h3>
                                    <p class="text-xs text-slate-500">Certified Property Management</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-1 text-brand-cyan text-xs">
                                <i class="fa-solid fa-star"></i>
                                <span class="font-bold text-slate-800 text-sm">4.9 / 5</span>
                            </div>
                        </div>

                        <div class="mt-3 grid grid-cols-3 gap-2 text-center">
                            <div class="rounded-xl bg-slate-50 p-2.5">
                                <span class="text-sm font-bold text-brand-navy block">{{ $stats['properties'] ?? '10,000+' }}</span>
                                <span class="text-xs text-slate-500">Suites Leased</span>
                            </div>
                            <div class="rounded-xl bg-emerald-50 p-2.5 text-emerald-700">
                                <span class="text-sm font-bold block">{{ $stats['satisfaction'] ?? '98.6%' }}</span>
                                <span class="text-xs text-emerald-600">Satisfaction</span>
                            </div>
                            <div class="rounded-xl bg-sky-50 p-2.5 text-brand-cyan">
                                <span class="text-sm font-bold block">{{ $stats['volume'] ?? '₹1,000+ Cr' }}</span>
                                <span class="text-xs text-slate-500">Annual GMV</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ── SECTION 2: 4-STAT PERFORMANCE STRIP (LIGHT) ── -->
<section class="border-y border-slate-200/80 bg-white py-12 sm:py-14">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="grid grid-cols-2 gap-6 md:grid-cols-4 lg:gap-8">
            <div class="rounded-3xl border border-slate-100 bg-[#F8FAFD] p-6 text-center shadow-sm">
                <span class="font-display text-4xl lg:text-5xl font-extrabold text-brand-navy block tracking-tight">
                    {{ $stats['properties'] ?? '10,000+' }}
                </span>
                <span class="mt-2 text-xs font-bold uppercase tracking-wider text-slate-500 block">
                    Properties Managed
                </span>
            </div>

            <div class="rounded-3xl border border-slate-100 bg-[#F8FAFD] p-6 text-center shadow-sm">
                <span class="font-display text-4xl lg:text-5xl font-extrabold text-brand-cyan block tracking-tight">
                    {{ $stats['satisfaction'] ?? '98.6%' }}
                </span>
                <span class="mt-2 text-xs font-bold uppercase tracking-wider text-slate-500 block">
                    Owner Satisfaction Rate
                </span>
            </div>

            <div class="rounded-3xl border border-slate-100 bg-[#F8FAFD] p-6 text-center shadow-sm">
                <span class="font-display text-4xl lg:text-5xl font-extrabold text-brand-navy block tracking-tight">
                    {{ $stats['cities'] ?? '25+' }}
                </span>
                <span class="mt-2 text-xs font-bold uppercase tracking-wider text-slate-500 block">
                    Major Metros Served
                </span>
            </div>

            <div class="rounded-3xl border border-slate-100 bg-[#F8FAFD] p-6 text-center shadow-sm">
                <span class="font-display text-4xl lg:text-5xl font-extrabold text-brand-cyan block tracking-tight">
                    {{ $stats['volume'] ?? '₹1,000+ Cr' }}
                </span>
                <span class="mt-2 text-xs font-bold uppercase tracking-wider text-slate-500 block">
                    Annual Leased Volume
                </span>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 3: STORY & VALUES ── -->
<section class="relative overflow-hidden bg-[#F8FAFD] py-16 sm:py-20 lg:py-24 border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-12 lg:gap-12">
            
            <!-- Left Narrative -->
            <div class="lg:col-span-5">
                <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                    <span class="h-2 w-2 rounded-full bg-brand-cyan"></span>
                    OUR PHILOSOPHY
                </div>

                <h2 class="font-display text-3xl font-extrabold tracking-tight text-brand-navy sm:text-4xl">
                    Designed for Modern Living, Engineered for Peace of Mind
                </h2>

                <div class="mt-6 space-y-4 text-base leading-relaxed text-slate-600">
                    <p>
                        Housing 365 was founded with a singular conviction: renting a home should be as seamless, transparent, and dignified as booking a 5-star hotel.
                    </p>
                    <p>
                        For too long, the rental market was plagued by fragmented paper contracts, misleading classified listings, delayed maintenance, and distrust between owners and occupants. Housing 365 brings institution-grade operational excellence to residential communities across North America.
                    </p>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-200">
                    <div class="flex items-center gap-4">
                        <div class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-brand-cyan text-white shadow-md shadow-brand-cyan/25">
                            <i class="fa-solid fa-shield-halved text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-heading font-bold text-brand-navy text-sm">Full Landlord Protection</h4>
                            <p class="text-xs text-slate-500">Every single lease is legally compliant with state and provincial tenancy acts.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right 4 Bento Value Cards -->
            <div class="lg:col-span-7">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="rounded-3xl bg-white p-8 border border-slate-200/80 shadow-sm transition hover:shadow-xl hover:-translate-y-1 duration-200">
                        <div class="size-12 rounded-2xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-xl font-bold mb-5">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>
                        <h3 class="font-heading font-bold text-brand-navy text-lg mb-2">Radical Transparency</h3>
                        <p class="text-slate-600 text-sm leading-relaxed">
                            No hidden lease fees, zero surprise expenses, and live digital accounting tracking for every rupee collected and disbursed.
                        </p>
                    </div>

                    <div class="rounded-3xl bg-white p-8 border border-slate-200/80 shadow-sm transition hover:shadow-xl hover:-translate-y-1 duration-200">
                        <div class="size-12 rounded-2xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-xl font-bold mb-5">
                            <i class="fa-solid fa-bolt-lightning"></i>
                        </div>
                        <h3 class="font-heading font-bold text-brand-navy text-lg mb-2">High Velocity Execution</h3>
                        <p class="text-slate-600 text-sm leading-relaxed">
                            Automated screening algorithms that verify credit, employment, and rental records to approve vetted tenants within hours.
                        </p>
                    </div>

                    <div class="rounded-3xl bg-white p-8 border border-slate-200/80 shadow-sm transition hover:shadow-xl hover:-translate-y-1 duration-200">
                        <div class="size-12 rounded-2xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-xl font-bold mb-5">
                            <i class="fa-solid fa-gem"></i>
                        </div>
                        <h3 class="font-heading font-bold text-brand-navy text-lg mb-2">Curated Quality Standards</h3>
                        <p class="text-slate-600 text-sm leading-relaxed">
                            Every home undergoes our comprehensive physical inspection audit before listing to ensure premium presentation.
                        </p>
                    </div>

                    <div class="rounded-3xl bg-white p-8 border border-slate-200/80 shadow-sm transition hover:shadow-xl hover:-translate-y-1 duration-200">
                        <div class="size-12 rounded-2xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-xl font-bold mb-5">
                            <i class="fa-solid fa-people-roof"></i>
                        </div>
                        <h3 class="font-heading font-bold text-brand-navy text-lg mb-2">Community &amp; Resident Care</h3>
                        <p class="text-slate-600 text-sm leading-relaxed">
                            Cultivating safe, welcoming neighbourhoods where residents feel respected and landlords enjoy zero vacancy loss.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ── SECTION 4: LEADERSHIP & OPERATIONAL TEAM ── -->
@if(count($team) > 0)
<section class="py-16 sm:py-20 lg:py-24 bg-white border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                <span class="h-2 w-2 rounded-full bg-brand-cyan"></span>
                EXECUTIVE LEADERSHIP
            </div>
            <h2 class="font-display text-3xl font-extrabold tracking-tight text-brand-navy sm:text-4xl">
                Meet the Team Driving Housing 365
            </h2>
            <p class="mt-4 text-slate-600 text-base leading-relaxed">
                Dedicated real estate technologists, licensed brokers, and property managers committed to elevating rental standards across North America.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($team as $member)
                <div class="group rounded-3xl bg-white p-8 border border-slate-200/80 shadow-sm transition-all duration-300 hover:shadow-xl hover:-translate-y-1 text-center">
                    <!-- Avatar Portrait -->
                    <div class="relative w-28 h-28 mx-auto mb-6">
                        @if($member->avatar)
                            <img src="{{ asset($member->avatar) }}" 
                                 alt="{{ $member->name }}" 
                                 class="w-full h-full object-cover rounded-full ring-4 ring-slate-100 group-hover:ring-brand-cyan/30 transition">
                        @else
                            <div class="w-full h-full rounded-full bg-gradient-to-tr from-brand-navy to-brand-navy-light text-white font-heading font-black text-3xl flex items-center justify-center ring-4 ring-slate-100 group-hover:ring-brand-cyan/30 transition shadow-inner">
                                {{ strtoupper(substr($member->name, 0, 1)) }}
                            </div>
                        @endif
                        <span class="absolute bottom-1 right-1 size-5 rounded-full bg-emerald-500 ring-2 ring-white" title="Active Leadership"></span>
                    </div>

                    <h3 class="font-heading text-lg font-bold text-brand-navy">
                        {{ $member->name }}
                    </h3>
                    
                    <span class="mt-1 text-xs font-bold uppercase tracking-wider text-brand-cyan block mb-4">
                        {{ $member->role }}
                    </span>

                    <p class="text-slate-600 text-sm leading-relaxed mb-6">
                        {{ $member->bio }}
                    </p>

                    <div class="flex items-center justify-center gap-3 pt-4 border-t border-slate-100 text-slate-400">
                        @if($member->email)
                            <a href="mailto:{{ $member->email }}" 
                               class="size-10 rounded-xl bg-slate-50 hover:bg-brand-cyan hover:text-white flex items-center justify-center text-sm transition" 
                               title="Email {{ $member->name }}">
                                <i class="fa-solid fa-envelope"></i>
                            </a>
                        @endif
                        @if($member->linkedin)
                            <a href="{{ $member->linkedin }}" 
                               target="_blank" 
                               rel="noopener"
                               class="size-10 rounded-xl bg-slate-50 hover:bg-brand-cyan hover:text-white flex items-center justify-center text-sm transition" 
                               title="LinkedIn Profile">
                                <i class="fa-brands fa-linkedin-in"></i>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ── SECTION 5: CALL TO ACTION BANNER (LIGHT MODERN) ── -->
<section class="py-16 sm:py-20 lg:py-24 bg-gradient-to-r from-sky-50 via-white to-cyan-50">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="relative overflow-hidden rounded-3xl border border-brand-cyan/20 bg-white p-8 sm:p-14 lg:p-16 shadow-xl flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="max-w-2xl">
                <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                    <span class="size-2 rounded-full bg-brand-cyan animate-pulse"></span>
                    START PARTNERING TODAY
                </div>

                <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold text-brand-navy">
                    Let's Make Your Rental Property a Proven Success
                </h2>

                <p class="mt-4 text-base sm:text-lg text-slate-600">
                    Connect with our dedicated leasing and property management advisory team today.
                </p>
            </div>

            <div class="shrink-0">
                <a href="{{ route('contact') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand-cyan px-8 py-4 text-base font-bold text-white shadow-lg shadow-brand-cyan/25 transition-all duration-200 hover:-translate-y-0.5 hover:bg-brand-cyan-hover">
                    <span>Talk to an Expert</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
