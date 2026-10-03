@extends('layouts.app')

@section('title', 'Franchising & Partner Network | Housing365 India — Everything Property. One Platform.')
@section('meta_description', 'Partner with Housing365 India. City and regional franchise opportunities for real estate operators, property managers, and broker networks across tier-1 and tier-2 Indian cities.')

@section('content')

<!-- ── BREADCRUMB ── -->
<div class="bg-white border-b border-slate-100 py-3.5 text-sm text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-cyan transition font-medium">Home</a>
        <span class="text-slate-300">/</span>
        <span class="text-brand-navy font-bold">Franchising &amp; Network</span>
    </div>
</div>

<!-- ── HERO SECTION ── -->
<section class="relative overflow-hidden bg-gradient-to-b from-[#F7FAFD] via-white to-[#F0F6FC] py-16 sm:py-20 lg:py-28">
    <div class="pointer-events-none absolute -left-40 top-0 h-[500px] w-[500px] rounded-full opacity-40 blur-3xl" style="background: radial-gradient(circle, rgba(0,174,239,0.18) 0%, transparent 70%)" aria-hidden="true"></div>

    <div class="relative mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 items-center gap-12">
            <div class="lg:col-span-7">
                <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-brand-cyan/20 bg-brand-cyan/5 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                    <span class="h-2 w-2 rounded-full bg-brand-cyan"></span>
                    NATIONAL EXPANSION &bull; CITY PARTNERS
                </div>
                <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-extrabold text-brand-navy leading-tight tracking-tight">
                    Power Your Real Estate Business with <span class="text-brand-cyan">Housing365 India</span>
                </h1>
                <p class="mt-5 text-base sm:text-lg text-slate-600 leading-relaxed max-w-2xl">
                    Join India's unified property lifecycle platform. Build a high-yield local property franchise with institutional leasing, tenant placement, Housing365 Care, and optional property management.
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-4">
                    <a href="#partner-form" class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand-cyan px-7 py-3.5 text-sm font-bold text-white shadow-lg shadow-brand-cyan/25 transition hover:bg-brand-cyan-hover">
                        <span>Apply for City Franchise</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                    <a href="{{ route('contact') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-7 py-3.5 text-sm font-bold text-brand-navy shadow-sm transition hover:bg-slate-50">
                        Schedule a Discussion
                    </a>
                </div>

                <!-- 3 Highlights -->
                <div class="mt-10 grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-4 rounded-2xl bg-white border border-slate-200">
                        <span class="text-xl font-bold text-brand-navy block">Turnkey Tech</span>
                        <span class="text-xs text-slate-500 mt-1 block">CRM, owner portal, tenant workflows</span>
                    </div>
                    <div class="p-4 rounded-2xl bg-white border border-slate-200">
                        <span class="text-xl font-bold text-brand-navy block">6 Revenue Streams</span>
                        <span class="text-xs text-slate-500 mt-1 block">Leasing, care, commercial, docs &amp; PM</span>
                    </div>
                    <div class="p-4 rounded-2xl bg-white border border-slate-200">
                        <span class="text-xl font-bold text-brand-navy block">Exclusive Territory</span>
                        <span class="text-xs text-slate-500 mt-1 block">City and micro-market protected rights</span>
                    </div>
                </div>
            </div>

            <!-- Right Column: Franchise Inquiry Form -->
            <div class="lg:col-span-5" id="partner-form">
                <div class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200/80 shadow-2xl">
                    <h3 class="font-display text-2xl font-bold text-brand-navy mb-2">Franchise Inquiry</h3>
                    <p class="text-xs text-slate-500 mb-6">Our national network team will review your credentials and contact you within 24 business hours.</p>

                    <form action="{{ route('inquiry.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="service_type" value="Franchise & Partner Network Inquiry">
                        <input type="hidden" name="subject" value="City Franchise Application">

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Full Name *</label>
                            <input type="text" name="name" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2.5 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan" placeholder="Rahul Sharma">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Email Address *</label>
                            <input type="email" name="email" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2.5 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan" placeholder="rahul@example.com">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Phone Number *</label>
                            <input type="tel" name="phone" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2.5 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan" placeholder="+91 98765 43210">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Target City / State *</label>
                            <input type="text" name="message" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2.5 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan" placeholder="e.g. Pune, Indore, Ahmedabad, Chandigarh...">
                        </div>

                        <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-brand-cyan hover:bg-brand-cyan-hover text-white font-bold text-xs uppercase tracking-wider shadow-md transition">
                            Submit Franchise Application
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── PILLARS OF PARTNERSHIP ── -->
<section class="py-16 sm:py-20 bg-white border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="text-center max-w-3xl mx-auto mb-14">
            <span class="text-xs font-bold uppercase tracking-wider text-brand-cyan">WHY PARTNER WITH US</span>
            <h2 class="font-display text-3xl sm:text-4xl text-brand-navy font-extrabold mt-2">
                Built to Scale Across Every Indian City
            </h2>
            <p class="text-slate-600 text-sm sm:text-base mt-3">
                Housing365 partners with local entrepreneurs, experienced brokers, and property service companies to provide an institutional-grade customer experience.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="p-8 rounded-3xl border border-slate-200 bg-[#F8FAFD]">
                <div class="size-12 rounded-2xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-xl mb-6">
                    <i class="fa-solid fa-laptop-code"></i>
                </div>
                <h3 class="text-xl font-bold text-brand-navy mb-2">Central Tech &amp; Lead Engine</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Access our national web marketplace, tenant verification infrastructure, digital agreements, and programmatic SEO traffic pipeline for your city.
                </p>
            </div>

            <div class="p-8 rounded-3xl border border-slate-200 bg-[#F8FAFD]">
                <div class="size-12 rounded-2xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-xl mb-6">
                    <i class="fa-solid fa-handshake"></i>
                </div>
                <h3 class="text-xl font-bold text-brand-navy mb-2">Standard Operating Procedures</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Full playbook for owner onboarding, tenant screening, move-in inspections, vendor triage, and legal documentation compliance.
                </p>
            </div>

            <div class="p-8 rounded-3xl border border-slate-200 bg-[#F8FAFD]">
                <div class="size-12 rounded-2xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-xl mb-6">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
                <h3 class="text-xl font-bold text-brand-navy mb-2">Transparent Unit Economics</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Healthy revenue shares on leasing success fees, Housing365 Care vendor jobs, and optional ongoing property management contracts.
                </p>
            </div>
        </div>
    </div>
</section>

@endsection
