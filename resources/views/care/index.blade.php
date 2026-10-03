@extends('layouts.app')

@section('title', 'Housing365 Care | On-Demand Property Maintenance & Care Across India')
@section('meta_description', 'Housing365 Care: On-demand property maintenance and care for owners and tenants across India. Plumbing, electrical, AC, carpentry, painting, deep cleaning, and emergency repair fulfilled by verified partner vendors.')

@section('content')

<!-- ── BREADCRUMB ── -->
<div class="bg-white border-b border-slate-100 py-3.5 text-sm text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-cyan transition font-medium">Home</a>
        <span class="text-slate-300">/</span>
        <a href="{{ route('services') }}" class="hover:text-brand-cyan transition font-medium">Services</a>
        <span class="text-slate-300">/</span>
        <span class="text-brand-navy font-bold">Housing365 Care</span>
    </div>
</div>

<!-- ── HERO SECTION ── -->
<section class="relative overflow-hidden bg-gradient-to-b from-[#F7FAFD] via-white to-[#F0F6FC] py-16 sm:py-20 lg:py-24">
    <div class="pointer-events-none absolute -left-40 top-0 h-[500px] w-[500px] rounded-full opacity-40 blur-3xl" style="background: radial-gradient(circle, rgba(0,174,239,0.18) 0%, transparent 70%)" aria-hidden="true"></div>

    <div class="relative mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="max-w-3xl">
            <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-brand-cyan/25 bg-brand-cyan/10 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                <span class="h-2 w-2 rounded-full bg-brand-cyan animate-pulse"></span>
                HOUSING365 CARE &bull; ON-DEMAND PROPERTY SERVICES
            </div>
            <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-extrabold text-brand-navy leading-tight tracking-tight">
                Housing365 Care.<br>
                <span class="text-brand-cyan">Your Property, Taken Care Of.</span>
            </h1>
            <p class="mt-5 text-base sm:text-lg text-slate-600 leading-relaxed">
                Professional maintenance, repairs, deep cleaning, and emergency care for Indian homes. Available to both free leasing landlords and tenants with upfront quotes, verified technicians, and quality control.
            </p>

            <div class="mt-8 flex flex-wrap items-center gap-4">
                <a href="{{ route('care.request') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-brand-cyan px-7 py-3.5 text-sm font-bold text-brand-navy shadow-lg shadow-brand-cyan/25 transition hover:bg-brand-cyan-hover">
                    <i class="fa-solid fa-calendar-plus text-xs"></i>
                    <span>Book a Service Request</span>
                </a>
                <a href="#categories" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-7 py-3.5 text-sm font-bold text-brand-navy shadow-sm transition hover:bg-slate-50">
                    Browse All Services
                </a>
            </div>

            <!-- Value Highlights -->
            <div class="mt-10 grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs font-semibold text-slate-700">
                <div class="p-3 rounded-2xl bg-white border border-slate-200 text-center shadow-sm">
                    <i class="fa-solid fa-user-check text-brand-cyan text-base mb-1 block"></i>
                    <span>Vetted Technicians</span>
                </div>
                <div class="p-3 rounded-2xl bg-white border border-slate-200 text-center shadow-sm">
                    <i class="fa-solid fa-file-invoice text-brand-cyan text-base mb-1 block"></i>
                    <span>Upfront Quotes</span>
                </div>
                <div class="p-3 rounded-2xl bg-white border border-slate-200 text-center shadow-sm">
                    <i class="fa-solid fa-certificate text-brand-cyan text-base mb-1 block"></i>
                    <span>Owner Approval First</span>
                </div>
                <div class="p-3 rounded-2xl bg-white border border-slate-200 text-center shadow-sm">
                    <i class="fa-solid fa-shield-halved text-brand-cyan text-base mb-1 block"></i>
                    <span>Quality Control</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── EMERGENCY CARE ALERT BANNER ── -->
@if($emergencyCategories->isNotEmpty())
<section class="bg-gradient-to-r from-red-600 via-rose-600 to-amber-600 py-4 text-white shadow-inner">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center text-sm font-bold flex-shrink-0 animate-bounce">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </span>
            <div>
                <div class="text-sm font-bold">24/7 Priority Emergency Maintenance Available</div>
                <div class="text-xs text-white/90">Rapid dispatch for urgent plumbing leaks, electrical short circuits, and critical issues.</div>
            </div>
        </div>
        <a href="{{ route('care.request', ['priority' => 'emergency']) }}" class="px-5 py-2 rounded-xl bg-white text-rose-700 hover:bg-slate-100 font-bold text-xs uppercase tracking-wider transition shadow flex items-center gap-1.5 flex-shrink-0">
            <i class="fa-solid fa-bolt"></i>
            <span>Request Emergency Help</span>
        </a>
    </div>
</section>
@endif

<!-- ── SERVICE CATEGORIES GRID ── -->
<section id="categories" class="py-16 sm:py-20 bg-slate-50/60">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="text-xs font-bold uppercase tracking-wider text-brand-cyan">15 COMPREHENSIVE SERVICE CATEGORIES</span>
            <h2 class="text-3xl font-display font-extrabold text-brand-navy mt-1">What can we help you fix today?</h2>
            <p class="text-sm text-slate-500 mt-2">Every service is executed by background-verified technicians with transparent pricing.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-5">
            @foreach($categories as $category)
                <div class="group bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm hover:shadow-xl hover:border-brand-cyan/40 transition flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-brand-cyan/10 text-brand-navy flex items-center justify-center text-lg group-hover:scale-110 group-hover:bg-brand-cyan group-hover:text-brand-navy transition">
                                <i class="{{ $category->icon ?: 'fa-solid fa-wrench' }}"></i>
                            </div>
                            @if($category->is_emergency_available)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-rose-50 text-rose-600 border border-rose-200">
                                    Emergency
                                </span>
                            @endif
                        </div>

                        <h3 class="text-base font-bold text-brand-navy group-hover:text-brand-cyan transition mb-2">
                            <a href="{{ route('care.category', $category->slug) }}">{{ $category->name }}</a>
                        </h3>
                        <p class="text-xs text-slate-500 leading-relaxed mb-4 line-clamp-3">
                            {{ $category->description }}
                        </p>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <div class="text-xs">
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Pricing</span>
                            <span class="font-extrabold text-brand-navy">{{ $category->price_display }}</span>
                        </div>
                        <a href="{{ route('care.request', ['category' => $category->slug]) }}" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-brand-cyan hover:text-brand-navy text-slate-700 text-xs font-bold transition">
                            Book &rarr;
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ── HOW HOUSING365 CARE WORKS ── -->
<section class="py-16 sm:py-20 bg-white border-t border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-xs font-bold uppercase tracking-wider text-brand-cyan">TRANSPARENT 5-STEP PROCESS</span>
            <h2 class="text-3xl font-display font-extrabold text-brand-navy mt-1">How Housing365 Care Works</h2>
            <p class="text-sm text-slate-500 mt-2">Zero surprises. You approve quotes before work begins, and verify completion before closure.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 relative">
            <div class="bg-slate-50 rounded-3xl p-6 border border-slate-200 text-center relative">
                <div class="w-10 h-10 rounded-2xl bg-brand-cyan text-brand-navy font-black text-sm flex items-center justify-center mx-auto mb-3 shadow">1</div>
                <h3 class="text-sm font-bold text-brand-navy mb-1">Submit Request</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Choose category, describe the issue, set priority, and upload photos.</p>
            </div>

            <div class="bg-slate-50 rounded-3xl p-6 border border-slate-200 text-center relative">
                <div class="w-10 h-10 rounded-2xl bg-brand-navy text-white font-black text-sm flex items-center justify-center mx-auto mb-3 shadow">2</div>
                <h3 class="text-sm font-bold text-brand-navy mb-1">Operations Triage</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Housing365 assigns a verified, background-checked local vendor partner.</p>
            </div>

            <div class="bg-slate-50 rounded-3xl p-6 border border-slate-200 text-center relative">
                <div class="w-10 h-10 rounded-2xl bg-amber-400 text-brand-navy font-black text-sm flex items-center justify-center mx-auto mb-3 shadow">3</div>
                <h3 class="text-sm font-bold text-brand-navy mb-1">Upfront Quote</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Vendor inspects and submits itemized quote. Work starts ONLY after your approval.</p>
            </div>

            <div class="bg-slate-50 rounded-3xl p-6 border border-slate-200 text-center relative">
                <div class="w-10 h-10 rounded-2xl bg-blue-600 text-white font-black text-sm flex items-center justify-center mx-auto mb-3 shadow">4</div>
                <h3 class="text-sm font-bold text-brand-navy mb-1">Work Execution</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Technician performs work with photo evidence (before &amp; after documentation).</p>
            </div>

            <div class="bg-slate-50 rounded-3xl p-6 border border-slate-200 text-center relative">
                <div class="w-10 h-10 rounded-2xl bg-emerald-500 text-white font-black text-sm flex items-center justify-center mx-auto mb-3 shadow">5</div>
                <h3 class="text-sm font-bold text-brand-navy mb-1">QC &amp; Closure</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Quality control verification, client rating, and warranty assurance.</p>
            </div>
        </div>

        <div class="mt-12 text-center">
            <a href="{{ route('care.request') }}" class="inline-flex items-center gap-2 px-8 py-4 rounded-2xl bg-brand-navy text-white hover:bg-brand-navy/90 font-extrabold text-sm uppercase tracking-wider transition shadow-lg">
                <span>Book Housing365 Care Now</span>
                <i class="fa-solid fa-arrow-right text-xs text-brand-cyan"></i>
            </a>
        </div>
    </div>
</section>

@endsection
