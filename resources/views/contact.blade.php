@extends('layouts.app')

@section('title', 'Contact Us | Housing 365 — Real Estate Leasing & Property Management')
@section('meta_description', 'Contact Housing 365 property management advisory, localized leasing specialists, and landlord client support.')

@section('content')

<!-- ── BREADCRUMB ── -->
<div class="bg-white border-b border-slate-100 py-3.5 text-sm text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-cyan transition font-medium">Home</a>
        <span class="text-slate-300">/</span>
        <span class="text-brand-navy font-bold">Contact</span>
    </div>
</div>

<!-- ── SECTION 1: HERO SHOWCASE (LIGHT ELEGANT DESIGN) ── -->
<section class="relative overflow-hidden bg-gradient-to-b from-[#F7FAFD] via-white to-[#F0F6FC] py-16 sm:py-20 lg:py-24">
    <div class="pointer-events-none absolute -left-40 top-0 h-[500px] w-[500px] rounded-full opacity-40 blur-3xl" style="background: radial-gradient(circle, rgba(0,174,239,0.18) 0%, transparent 70%)" aria-hidden="true"></div>
    <div class="pointer-events-none absolute right-0 top-20 h-[600px] w-[600px] rounded-full opacity-30 blur-3xl" style="background: radial-gradient(circle, rgba(3,35,75,0.06) 0%, transparent 70%)" aria-hidden="true"></div>

    <div class="relative mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="max-w-3xl mx-auto text-center">
            <!-- Eyebrow Badge -->
            <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-brand-cyan/20 bg-brand-cyan/5 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                <span class="h-2 w-2 rounded-full bg-brand-cyan animate-pulse"></span>
                WE ARE HERE FOR YOU
            </div>

            <!-- Headline -->
            <h1 class="font-display text-4xl font-extrabold leading-[1.12] tracking-tight text-brand-navy sm:text-5xl md:text-6xl">
                Get in Touch with <br>
                <span class="text-brand-cyan font-extrabold">Housing 365.</span>
            </h1>

            <!-- Subheadline -->
            <p class="mt-6 text-base sm:text-lg leading-relaxed text-slate-600">
                Have questions about a property listing, lease agreement, or property management contract? Our dedicated specialists reply within 2 hours during business hours.
            </p>

            <!-- Value Highlights -->
            <div class="mt-8 flex flex-wrap justify-center items-center gap-6 text-sm font-semibold text-slate-700">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-brand-cyan"></i>
                    <span>2-Hour Advisory Response</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-brand-cyan"></i>
                    <span>24/7 Emergency Maintenance Dispatch</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-brand-cyan"></i>
                    <span>Zero Obligation Rental Consultation</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 2: CONTACT CARDS & FORM ── -->
<section class="py-16 sm:py-20 lg:py-24 bg-[#F8FAFD] border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12">
            
            <!-- Left Info Column -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Phone -->
                <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm transition hover:shadow-md">
                    <div class="size-12 rounded-2xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-xl mb-4">
                        <i class="fa-solid fa-phone"></i>
                    </div>
                    <h3 class="font-heading font-bold text-lg text-brand-navy mb-1">Direct Phone Support</h3>
                    <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                        Monday – Saturday: 9:00 AM – 7:00 PM IST<br>
                        Sunday: 10:00 AM – 4:00 PM IST
                    </p>
                    <div class="space-y-2.5">
                        <a href="tel:{{ $settings['contact_phone'] ?? '+916376163263' }}" class="text-brand-cyan font-bold text-sm hover:underline flex items-center gap-2 transition">
                            <i class="fa-solid fa-phone text-xs"></i>
                            <span>{{ $settings['contact_phone'] ?? '+91 63761 63263' }}</span>
                        </a>
                        <a href="tel:{{ $settings['contact_phone_alt'] ?? '+916376163263' }}" class="text-brand-cyan font-bold text-sm hover:underline flex items-center gap-2 transition">
                            <i class="fa-solid fa-phone text-xs"></i>
                            <span>{{ $settings['contact_phone_alt'] ?? '+91 63761 63263' }}</span>
                        </a>
                    </div>
                </div>

                <!-- Email -->
                <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm transition hover:shadow-md">
                    <div class="size-12 rounded-2xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-xl mb-4">
                        <i class="fa-solid fa-envelope-open-text"></i>
                    </div>
                    <h3 class="font-heading font-bold text-lg text-brand-navy mb-1">Email Advisory</h3>
                    <p class="text-xs text-slate-500 mb-4">We reply to all leasing and management inquiries within 2 hours during active business hours.</p>
                    <a href="mailto:{{ $settings['contact_email'] ?? 'contact@housing365.in' }}" class="text-brand-cyan font-bold text-sm hover:underline flex items-center gap-2">
                        <i class="fa-solid fa-envelope text-xs"></i>
                        <span>{{ $settings['contact_email'] ?? 'contact@housing365.in' }}</span>
                    </a>
                </div>

                <!-- Head Office -->
                <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm transition hover:shadow-md">
                    <div class="size-12 rounded-2xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-xl mb-4">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <h3 class="font-heading font-bold text-lg text-brand-navy mb-1">Head Office</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        {{ $settings['office_address'] ?? 'Indiqube Emerald, Sector 2, HSR Layout, Bengaluru, Karnataka 560102, India' }}
                    </p>
                    <div class="mt-4 pt-3 border-t border-slate-100 text-xs text-slate-500">
                        Conveniently located near HSR BDA Complex &amp; Outer Ring Road tech corridor.
                    </div>
                </div>

                <!-- Community & Social Channels -->
                <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm transition hover:shadow-md">
                    <div class="size-12 rounded-2xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-xl mb-4">
                        <i class="fa-solid fa-share-nodes"></i>
                    </div>
                    <h3 class="font-heading font-bold text-lg text-brand-navy mb-1">Connect on Social</h3>
                    <p class="text-xs text-slate-500 mb-5 leading-relaxed">Follow our latest rental updates, tenant advisory bulletins, and community notices across networks.</p>
                    
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ $settings['linkedin_url'] ?? 'https://www.linkedin.com/company/housing365/' }}" target="_blank" rel="noopener noreferrer" title="LinkedIn" class="inline-flex items-center gap-2 rounded-xl bg-slate-50 hover:bg-sky-50 border border-slate-200 hover:border-sky-300 px-3 py-2 text-xs font-semibold text-slate-700 hover:text-sky-700 transition">
                            <i class="fa-brands fa-linkedin-in text-[#0A66C2]"></i>
                            <span>LinkedIn</span>
                        </a>
                        <a href="{{ $settings['instagram_url'] ?? 'https://instagram.com/housing365' }}" target="_blank" rel="noopener noreferrer" title="Instagram" class="inline-flex items-center gap-2 rounded-xl bg-slate-50 hover:bg-rose-50 border border-slate-200 hover:border-rose-300 px-3 py-2 text-xs font-semibold text-slate-700 hover:text-rose-700 transition">
                            <i class="fa-brands fa-instagram text-[#E4405F]"></i>
                            <span>Instagram</span>
                        </a>
                        <a href="{{ $settings['facebook_url'] ?? 'https://facebook.com/housing365' }}" target="_blank" rel="noopener noreferrer" title="Facebook" class="inline-flex items-center gap-2 rounded-xl bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-blue-300 px-3 py-2 text-xs font-semibold text-slate-700 hover:text-blue-700 transition">
                            <i class="fa-brands fa-facebook-f text-[#1877F2]"></i>
                            <span>Facebook</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Form Column -->
            <div class="lg:col-span-8">
                <div class="bg-white rounded-3xl p-8 sm:p-12 border border-slate-200/80 shadow-xl">
                    <div class="mb-8">
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-brand-cyan/10 text-brand-cyan mb-3">
                            <span class="size-1.5 rounded-full bg-brand-cyan"></span> DIRECT INQUIRY
                        </span>
                        <h2 class="font-display text-2xl sm:text-3xl font-extrabold text-brand-navy">Send Us a Direct Message</h2>
                        <p class="text-slate-600 text-sm mt-1">Fill in your inquiry details and our localized real estate advisor will connect with you.</p>
                    </div>

                    <form action="{{ route('inquiry.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="hidden" style="display:none !important;" aria-hidden="true">
                            <label for="website_url_hp">Security Check - Leave Empty</label>
                            <input type="text" name="website_url_hp" id="website_url_hp" tabindex="-1" autocomplete="off">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Your Full Name *</label>
                                <input type="text" name="name" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan transition" placeholder="Alex Morgan">
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Email Address *</label>
                                <input type="email" name="email" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan transition" placeholder="alex@example.com">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Phone Number</label>
                                <input type="tel" name="phone" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan transition" placeholder="+1 (647) 000-0000">
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Inquiry Type</label>
                                <select name="service_type" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan transition">
                                    <option value="General">General Inquiry</option>
                                    <option value="Tenant Application">Tenant Application &amp; Tour</option>
                                    <option value="Owner Management">Property Owner Leasing &amp; Management</option>
                                    <option value="Corporate Housing">Corporate Relocation Housing</option>
                                    <option value="Institutional">Institutional Multi-Family Lease-Up</option>
                                    <option value="PMC Partnership">Property Manager (PMC) Partnership</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Subject</label>
                            <input type="text" name="subject" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan transition" placeholder="e.g. Inquiring about Toronto 2-bedroom rental listing">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Message *</label>
                            <textarea name="message" rows="5" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-cyan focus:border-brand-cyan transition" placeholder="Tell us about your property or rental requirements..."></textarea>
                        </div>

                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand-cyan px-8 py-4 text-xs font-bold uppercase tracking-wider text-white shadow-lg shadow-brand-cyan/25 transition-all duration-200 hover:-translate-y-0.5 hover:bg-brand-cyan-hover">
                            <span>Submit Inquiry</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ── SECTION 3: RESPONSE SLAs BY DEPARTMENT ── -->
<section class="py-16 sm:py-20 bg-white border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="max-w-2xl mb-12">
            <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                <span class="h-2 w-2 rounded-full bg-brand-cyan"></span>
                COMMUNICATION STANDARDS
            </div>
            <h2 class="font-display text-3xl font-extrabold tracking-tight text-brand-navy">
                Guaranteed Response Time Commitments
            </h2>
            <p class="mt-3 text-base text-slate-600">We hold ourselves to strict operational response metrics so you are never left waiting.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="p-6 rounded-3xl bg-[#F8FAFD] border border-slate-200/80">
                <div class="text-rose-500 font-bold text-sm mb-1 flex items-center gap-2">
                    <i class="fa-solid fa-bolt"></i> Emergency Maintenance
                </div>
                <span class="font-display text-2xl font-bold text-brand-navy block mt-2">&lt; 15 Minutes</span>
                <p class="text-xs text-slate-500 mt-2">Plumbing, heating or security emergencies dispatched 24/7/365.</p>
            </div>

            <div class="p-6 rounded-3xl bg-[#F8FAFD] border border-slate-200/80">
                <div class="text-brand-cyan font-bold text-sm mb-1 flex items-center gap-2">
                    <i class="fa-solid fa-house-chimney-user"></i> Tenant Tour Requests
                </div>
                <span class="font-display text-2xl font-bold text-brand-navy block mt-2">&lt; 2 Hours</span>
                <p class="text-xs text-slate-500 mt-2">Instant calendar booking confirmation and showing agent assignment.</p>
            </div>

            <div class="p-6 rounded-3xl bg-[#F8FAFD] border border-slate-200/80">
                <div class="text-emerald-600 font-bold text-sm mb-1 flex items-center gap-2">
                    <i class="fa-solid fa-chart-line"></i> Landlord Onboarding
                </div>
                <span class="font-display text-2xl font-bold text-brand-navy block mt-2">&lt; 2 Hours</span>
                <p class="text-xs text-slate-500 mt-2">Free comparative market analysis and tailored pricing recommendation.</p>
            </div>

            <div class="p-6 rounded-3xl bg-[#F8FAFD] border border-slate-200/80">
                <div class="text-brand-cyan font-bold text-sm mb-1 flex items-center gap-2">
                    <i class="fa-solid fa-file-invoice-dollar"></i> Accounting &amp; Tax Ledgers
                </div>
                <span class="font-display text-2xl font-bold text-brand-navy block mt-2">Same Day</span>
                <p class="text-xs text-slate-500 mt-2">Monthly statement reconciliations and CRA/IRS tax reporting assistance.</p>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 4: FREQUENTLY ASKED QUESTIONS (FULL WIDTH 2-COLUMN) ── -->
<section class="py-16 sm:py-20 lg:py-24 bg-[#F8FAFD] border-t border-slate-200/80">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12 pb-6 border-b border-slate-200">
            <div>
                <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                    <span class="h-2 w-2 rounded-full bg-brand-cyan"></span>
                    COMMUNICATIONS &middot; GENERAL CONTACT FAQ
                </div>
                <h2 class="font-display text-3xl font-extrabold text-brand-navy sm:text-4xl">General Contact Frequently Asked Questions</h2>
                <p class="text-slate-600 text-base mt-2 max-w-2xl">Answers to common inquiries regarding office visits, urgent requests, valuations, and response times.</p>
            </div>
            <div class="shrink-0">
                <a href="#contact-form" class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-bold text-brand-navy shadow-sm transition-all hover:bg-slate-50 hover:border-slate-400">
                    <span>Send Us a Direct Message</span>
                    <i class="fa-solid fa-arrow-up text-xs text-brand-cyan"></i>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start w-full">
            <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-sm transition hover:border-brand-cyan/40 hover:shadow-md">
                <h3 class="font-heading font-bold text-brand-navy text-base sm:text-lg mb-2 flex items-start gap-3">
                    <span class="inline-flex items-center justify-center rounded-lg bg-sky-50 px-2.5 py-1 text-xs font-bold text-brand-cyan border border-sky-100">01</span>
                    <span>Can I visit your office in Bengaluru without an appointment?</span>
                </h3>
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed pl-9">
                    While our office is open Monday through Saturday, our leasing directors and property specialists frequently host property showings and on-site owner assessments across Bengaluru and MMR. We strongly recommend booking an appointment in advance so the appropriate specialist is available to meet with you.
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-sm transition hover:border-brand-cyan/40 hover:shadow-md">
                <h3 class="font-heading font-bold text-brand-navy text-base sm:text-lg mb-2 flex items-start gap-3">
                    <span class="inline-flex items-center justify-center rounded-lg bg-sky-50 px-2.5 py-1 text-xs font-bold text-brand-cyan border border-sky-100">02</span>
                    <span>How do I submit an emergency maintenance request after hours?</span>
                </h3>
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed pl-9">
                    Current residents should call our 24/7 emergency dispatch line directly at +91 63761 63263 or submit an urgent maintenance ticket through the resident portal. For life-threatening emergencies or gas leaks, please contact local emergency authorities immediately.
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-sm transition hover:border-brand-cyan/40 hover:shadow-md">
                <h3 class="font-heading font-bold text-brand-navy text-base sm:text-lg mb-2 flex items-start gap-3">
                    <span class="inline-flex items-center justify-center rounded-lg bg-sky-50 px-2.5 py-1 text-xs font-bold text-brand-cyan border border-sky-100">03</span>
                    <span>Is there any fee for an in-person rental valuation of my home?</span>
                </h3>
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed pl-9">
                    No. We provide 100% complimentary on-site or digital rental evaluations for all property owners with zero obligation to list. We review comparable lease rates, current demand, and expected days on market.
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-sm transition hover:border-brand-cyan/40 hover:shadow-md">
                <h3 class="font-heading font-bold text-brand-navy text-base sm:text-lg mb-2 flex items-start gap-3">
                    <span class="inline-flex items-center justify-center rounded-lg bg-sky-50 px-2.5 py-1 text-xs font-bold text-brand-cyan border border-sky-100">04</span>
                    <span>How quickly does your team respond to new owner inquiries?</span>
                </h3>
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed pl-9">
                    During regular business hours (Mon-Sat 9am-7pm, Sun 10am-4pm IST), our leasing advisors respond within 60 minutes. Inquiries submitted overnight or on holidays are addressed by 9:00 AM the following morning.
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-sm transition hover:border-brand-cyan/40 hover:shadow-md">
                <h3 class="font-heading font-bold text-brand-navy text-base sm:text-lg mb-2 flex items-start gap-3">
                    <span class="inline-flex items-center justify-center rounded-lg bg-sky-50 px-2.5 py-1 text-xs font-bold text-brand-cyan border border-sky-100">05</span>
                    <span>Can I schedule a video consultation instead of an in-person meeting?</span>
                </h3>
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed pl-9">
                    Yes. We conduct dozens of video consultations weekly via Google Meet or Zoom for NRI and out-of-station property investors. Simply select "Virtual Consultation" when filling out our contact form.
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-sm transition hover:border-brand-cyan/40 hover:shadow-md">
                <h3 class="font-heading font-bold text-brand-navy text-base sm:text-lg mb-2 flex items-start gap-3">
                    <span class="inline-flex items-center justify-center rounded-lg bg-sky-50 px-2.5 py-1 text-xs font-bold text-brand-cyan border border-sky-100">06</span>
                    <span>Where should official tenancy notices or legal documents be served?</span>
                </h3>
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed pl-9">
                    Formal legal correspondence and tenancy notices should be delivered to our corporate headquarters at Indiqube Emerald, Sector 2, HSR Layout, Bengaluru, Karnataka 560102, India, or emailed directly to compliance@housing365.in with confirmation receipt requested.
                </p>
            </div>
        </div>
    </div>
</section>

@endsection
