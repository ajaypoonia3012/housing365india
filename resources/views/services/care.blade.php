@extends('layouts.app')

@section('title', 'Housing365 Care | Your Property, Taken Care Of. — India')
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
<section class="relative overflow-hidden bg-gradient-to-b from-[#F7FAFD] via-white to-[#F0F6FC] py-16 sm:py-20 lg:py-28">
    <div class="pointer-events-none absolute -left-40 top-0 h-[500px] w-[500px] rounded-full opacity-40 blur-3xl" style="background: radial-gradient(circle, rgba(0,174,239,0.18) 0%, transparent 70%)" aria-hidden="true"></div>

    <div class="relative mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 items-center gap-12">
            <div class="lg:col-span-7">
                <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-brand-cyan/20 bg-brand-cyan/5 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                    <span class="h-2 w-2 rounded-full bg-brand-cyan animate-pulse"></span>
                    HOUSING365 CARE &bull; ON-DEMAND PROPERTY CARE
                </div>
                <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-extrabold text-brand-navy leading-tight tracking-tight">
                    Housing365 Care.<br>
                    <span class="text-brand-cyan">Your Property, Taken Care Of.</span>
                </h1>
                <p class="mt-5 text-base sm:text-lg text-slate-600 leading-relaxed max-w-2xl">
                    Whether you are an owner managing a rented flat or a tenant needing prompt maintenance, Housing365 Care connects you with vetted, local service professionals with transparent quotes and quality assurance.
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-4">
                    <a href="#request-service" class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand-cyan px-7 py-3.5 text-sm font-bold text-white shadow-lg shadow-brand-cyan/25 transition hover:bg-brand-cyan-hover">
                        <span>Book a Service</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                    <a href="#how-it-works" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-7 py-3.5 text-sm font-bold text-brand-navy shadow-sm transition hover:bg-slate-50">
                        How It Works
                    </a>
                </div>

                <!-- Verified Features -->
                <div class="mt-10 grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs font-semibold text-slate-700">
                    <div class="p-3 rounded-xl bg-white border border-slate-200 text-center">
                        <i class="fa-solid fa-user-check text-brand-cyan text-base mb-1 block"></i>
                        <span>Vetted Technicians</span>
                    </div>
                    <div class="p-3 rounded-xl bg-white border border-slate-200 text-center">
                        <i class="fa-solid fa-file-invoice text-brand-cyan text-base mb-1 block"></i>
                        <span>Upfront Quotes</span>
                    </div>
                    <div class="p-3 rounded-xl bg-white border border-slate-200 text-center">
                        <i class="fa-solid fa-certificate text-brand-cyan text-base mb-1 block"></i>
                        <span>Quality Checks</span>
                    </div>
                    <div class="p-3 rounded-xl bg-white border border-slate-200 text-center">
                        <i class="fa-solid fa-shield-halved text-brand-cyan text-base mb-1 block"></i>
                        <span>Owner Approval First</span>
                    </div>
                </div>
            </div>

            <!-- Right Column: Service Request Card -->
            <div class="lg:col-span-5" id="request-service">
                <div class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200/80 shadow-2xl">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                        <h3 class="font-display text-xl sm:text-2xl font-bold text-brand-navy">Request Housing365 Care</h3>
                        <span class="text-[10px] font-bold uppercase bg-brand-cyan/10 text-brand-cyan px-2.5 py-1 rounded-full">Fast Response</span>
                    </div>
                    <p class="text-xs text-slate-500 mb-6">Describe the issue and our triage team will assign an approved partner vendor for quote and scheduling.</p>

                    <form action="{{ route('inquiry.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="service_type" value="Housing365 Care Request">
                        <input type="hidden" name="subject" value="Service Request - Housing365 Care">

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Service Category *</label>
                            <select name="subject" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2.5 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan font-medium">
                                <option value="Care: Plumbing Repair">Plumbing (Leakage, Tap, Pipe, Flush)</option>
                                <option value="Care: Electrical Issue">Electrical (Wiring, MCB, Switches, Lighting)</option>
                                <option value="Care: AC Service & Gas">AC Servicing, Installation &amp; Gas Refill</option>
                                <option value="Care: RO & Geyser Service">RO Water Purifier / Geyser Repair</option>
                                <option value="Care: Appliance Repair">Appliance (Washing Machine, Refrigerator)</option>
                                <option value="Care: Carpentry">Carpentry &amp; Door/Window Lock Repair</option>
                                <option value="Care: Painting & Touchups">Home Painting &amp; Waterproofing</option>
                                <option value="Care: Pest Control">Pest Control &amp; Termite Treatment</option>
                                <option value="Care: Deep Cleaning">Deep Cleaning / Move-in &amp; Move-out</option>
                                <option value="Care: Property Inspection">Move-in / Move-out Property Inspection</option>
                                <option value="Care: Emergency Request">Emergency Maintenance Request</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Your Name *</label>
                                <input type="text" name="name" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2.5 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan" placeholder="Vikram Singh">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Phone *</label>
                                <input type="tel" name="phone" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2.5 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan" placeholder="+91 98765 43210">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Email *</label>
                            <input type="email" name="email" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2.5 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan" placeholder="vikram@example.com">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">City &amp; Locality *</label>
                            <input type="text" name="message" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-2.5 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan" placeholder="e.g. HSR Layout, Bengaluru — Flat 302, Master Bath leak">
                        </div>

                        <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-brand-cyan hover:bg-brand-cyan-hover text-white font-bold text-xs uppercase tracking-wider shadow-md transition">
                            Submit Care Request
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION: HOW HOUSING365 CARE WORKS (THE 10-STEP TRANSPARENT WORKFLOW) ── -->
<section class="py-16 sm:py-20 bg-white border-b border-slate-100" id="how-it-works">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="text-center max-w-3xl mx-auto mb-14">
            <span class="text-xs font-bold uppercase tracking-wider text-brand-cyan">OUR FULFILLMENT WORKFLOW</span>
            <h2 class="font-display text-3xl sm:text-4xl text-brand-navy font-extrabold mt-2">
                How Housing365 Care Works
            </h2>
            <p class="text-slate-600 text-sm sm:text-base mt-3">
                Transparent partner-fulfilled maintenance with owner approval at every critical financial stage.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="p-6 rounded-2xl bg-[#F8FAFD] border border-slate-200">
                <span class="size-8 rounded-full bg-brand-cyan text-white font-bold flex items-center justify-center text-xs mb-4">1</span>
                <h4 class="font-bold text-brand-navy text-sm mb-1">Service Request</h4>
                <p class="text-xs text-slate-500 leading-relaxed">Tenant or owner logs the issue with description and photos/video.</p>
            </div>
            <div class="p-6 rounded-2xl bg-[#F8FAFD] border border-slate-200">
                <span class="size-8 rounded-full bg-brand-cyan text-white font-bold flex items-center justify-center text-xs mb-4">2</span>
                <h4 class="font-bold text-brand-navy text-sm mb-1">Issue Triage</h4>
                <p class="text-xs text-slate-500 leading-relaxed">Housing365 evaluates severity and matches with a verified local technician.</p>
            </div>
            <div class="p-6 rounded-2xl bg-[#F8FAFD] border border-slate-200">
                <span class="size-8 rounded-full bg-brand-cyan text-white font-bold flex items-center justify-center text-xs mb-4">3</span>
                <h4 class="font-bold text-brand-navy text-sm mb-1">Transparent Quote</h4>
                <p class="text-xs text-slate-500 leading-relaxed">Detailed estimate presented for owner approval before any repair begins.</p>
            </div>
            <div class="p-6 rounded-2xl bg-[#F8FAFD] border border-slate-200">
                <span class="size-8 rounded-full bg-brand-cyan text-white font-bold flex items-center justify-center text-xs mb-4">4</span>
                <h4 class="font-bold text-brand-navy text-sm mb-1">Job Scheduled</h4>
                <p class="text-xs text-slate-500 leading-relaxed">Visit scheduled as per tenant convenience with digital arrival notification.</p>
            </div>
            <div class="p-6 rounded-2xl bg-[#F8FAFD] border border-slate-200">
                <span class="size-8 rounded-full bg-brand-cyan text-white font-bold flex items-center justify-center text-xs mb-4">5</span>
                <h4 class="font-bold text-brand-navy text-sm mb-1">Quality Check &amp; Close</h4>
                <p class="text-xs text-slate-500 leading-relaxed">Work completed, photo evidence verified, digital invoice generated, and reviewed.</p>
            </div>
        </div>
    </div>
</section>

<!-- ── SERVICES GRID ── -->
<section class="py-16 sm:py-20 bg-[#F8FAFD]">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="text-center max-w-3xl mx-auto mb-14">
            <span class="text-xs font-bold uppercase tracking-wider text-brand-cyan">MAINTENANCE CATALOG</span>
            <h2 class="font-display text-3xl sm:text-4xl text-brand-navy font-extrabold mt-2">
                Services Covered by Housing365 Care
            </h2>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
            <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <i class="fa-solid fa-faucet-drip text-brand-cyan text-2xl mb-3"></i>
                    <h3 class="font-bold text-brand-navy text-base">Plumbing &amp; Sanitation</h3>
                    <p class="text-xs text-slate-500 mt-1">Leak repairs, pipeline blockage, bathroom fittings, flush tanks.</p>
                </div>
            </div>
            <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <i class="fa-solid fa-bolt text-amber-500 text-2xl mb-3"></i>
                    <h3 class="font-bold text-brand-navy text-base">Electrical &amp; Wiring</h3>
                    <p class="text-xs text-slate-500 mt-1">Short circuit fixing, switchboards, MCB repair, chandelier/fan fitting.</p>
                </div>
            </div>
            <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <i class="fa-solid fa-snowflake text-sky-500 text-2xl mb-3"></i>
                    <h3 class="font-bold text-brand-navy text-base">AC Service &amp; Repair</h3>
                    <p class="text-xs text-slate-500 mt-1">Deep jet cleaning, gas charging, compressor repair, uninstallation.</p>
                </div>
            </div>
            <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <i class="fa-solid fa-water text-cyan-500 text-2xl mb-3"></i>
                    <h3 class="font-bold text-brand-navy text-base">RO &amp; Geyser Care</h3>
                    <p class="text-xs text-slate-500 mt-1">Filter replacement, membrane check, water heater thermostat repair.</p>
                </div>
            </div>
            <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <i class="fa-solid fa-screwdriver-wrench text-indigo-500 text-2xl mb-3"></i>
                    <h3 class="font-bold text-brand-navy text-base">Carpentry &amp; Locks</h3>
                    <p class="text-xs text-slate-500 mt-1">Door hinges, wardrobe repair, lock replacement, furniture assembly.</p>
                </div>
            </div>
            <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <i class="fa-solid fa-paint-roller text-rose-500 text-2xl mb-3"></i>
                    <h3 class="font-bold text-brand-navy text-base">Painting &amp; Touch-ups</h3>
                    <p class="text-xs text-slate-500 mt-1">Full apartment painting, tenant move-out touch-ups, waterproofing.</p>
                </div>
            </div>
            <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <i class="fa-solid fa-bug text-emerald-600 text-2xl mb-3"></i>
                    <h3 class="font-bold text-brand-navy text-base">Pest Control</h3>
                    <p class="text-xs text-slate-500 mt-1">Cockroach herbal gel, termite treatment, bedbug eradication.</p>
                </div>
            </div>
            <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <i class="fa-solid fa-broom text-teal-600 text-2xl mb-3"></i>
                    <h3 class="font-bold text-brand-navy text-base">Deep Cleaning</h3>
                    <p class="text-xs text-slate-500 mt-1">Full house deep scrubbing, kitchen degreasing, bathroom descaling.</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
