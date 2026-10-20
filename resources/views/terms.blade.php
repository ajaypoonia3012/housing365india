@extends('layouts.app')

@section('title', 'Terms of Service | Housing365 India')
@section('meta_description', 'Terms and conditions governing the use of Housing365 India real estate platform, verified listings, leasing agreements, Care services, and monetization.')

@section('content')
<section class="relative overflow-hidden bg-gradient-to-b from-[#F7FAFD] via-white to-white py-14 sm:py-20 border-b border-slate-100">
    <div class="pointer-events-none absolute -top-40 right-1/4 h-96 w-96 rounded-full bg-[radial-gradient(circle,rgba(0,174,239,0.18)_0%,transparent_70%)] blur-3xl"></div>
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 relative z-10 max-w-4xl text-center">
        <div class="inline-flex items-center gap-2 rounded-full border border-brand-cyan/25 bg-brand-cyan/10 px-3.5 py-1 text-xs font-semibold uppercase tracking-wider text-brand-cyan mb-4">
            <span class="h-1.5 w-1.5 rounded-full bg-brand-cyan"></span>
            Legal Terms &amp; Conditions
        </div>
        <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight text-brand-navy leading-[1.2] mb-4">
            Housing365 India <span class="text-brand-cyan">Terms of Service</span>
        </h1>
        <p class="text-slate-600 text-sm sm:text-base max-w-2xl mx-auto">
            Governing Marketplace Listings, Tenancy Documentation, Housing365 Care, and Platform Services across India.
        </p>
    </div>
</section>

<section class="py-14 sm:py-16 bg-slate-50/50">
    <div class="mx-auto w-full max-w-4xl px-4 sm:px-8">
        <div class="bg-white rounded-3xl p-8 sm:p-12 border border-slate-200/80 shadow-sm leading-relaxed text-slate-700 space-y-8">
            <div>
                <h2 class="text-xl font-bold text-brand-navy mb-3">1. Acceptance of Terms</h2>
                <p class="text-sm sm:text-base leading-relaxed">
                    By registering, accessing, browsing, or listing a property on Housing365 India ("Platform"), you acknowledge that you have read, understood, and agree to be bound by these Terms of Service, our Privacy Policy, and all applicable Indian laws including the Real Estate (Regulation and Development) Act 2016 (RERA), the Model Tenancy Act 2021, and the Indian Contract Act 1872.
                </p>
            </div>

            <div>
                <h2 class="text-xl font-bold text-brand-navy mb-3">2. Listing Authenticity &amp; Zero Brokerage Commitment</h2>
                <ul class="list-disc pl-6 space-y-2 text-sm sm:text-base text-slate-600">
                    <li><strong>Direct Owner Listings:</strong> Standard residential listings by legitimate property owners are completely free (₹0 listing fee). Owners warrant that they hold valid legal authority or ownership title over the listed premises.</li>
                    <li><strong>Zero Fake Advertisements:</strong> Misrepresenting property dimensions, photos, amenities, or ownership status constitutes a violation of these terms and will result in permanent account suspension and reporting to consumer authorities.</li>
                    <li><strong>Brokers &amp; Builders:</strong> Commercial entities, builders, and licensed real estate agents must operate under their verified RERA registration number and disclose their business representation clearly.</li>
                </ul>
            </div>

            <div>
                <h2 class="text-xl font-bold text-brand-navy mb-3">3. Rental Agreements &amp; Legal Stamping</h2>
                <p class="text-sm sm:text-base leading-relaxed">
                    Housing365 assists users in drafting standard 11-month residential tenancy agreements, procuring state e-stamp certificates, and executing digital signatures. While our drafts reflect legal best practices, users are encouraged to review custom clauses with independent legal counsel. The user remains solely responsible for the factual accuracy of party names, addresses, and agreed financial terms.
                </p>
            </div>

            <div>
                <h2 class="text-xl font-bold text-brand-navy mb-3">4. Housing365 Care &amp; Maintenance Services</h2>
                <ul class="list-disc pl-6 space-y-2 text-sm sm:text-base text-slate-600">
                    <li>Housing365 Care connects property owners and tenants with verified trade technicians (plumbing, electrical, deep cleaning, and appliance servicing).</li>
                    <li>All quotes issued are transparent, itemized, and require explicit customer authorization before work commencement.</li>
                    <li>Emergency dispatch services are subject to technician availability and local civic safety conditions.</li>
                </ul>
            </div>

            <div>
                <h2 class="text-xl font-bold text-brand-navy mb-3">5. Contact Access Passes &amp; Monetization</h2>
                <p class="text-sm sm:text-base leading-relaxed">
                    Prospective tenants or buyers purchasing contact access packages receive verified direct phone and WhatsApp contact details of listing owners. Unlocked credits are non-refundable once contact credentials have been rendered.
                </p>
            </div>

            <div>
                <h2 class="text-xl font-bold text-brand-navy mb-3">6. Governing Law &amp; Jurisdiction</h2>
                <p class="text-sm sm:text-base leading-relaxed">
                    These Terms of Service are governed by and construed in accordance with the laws of the Republic of India. Any disputes arising out of or related to these terms shall be subject to the exclusive jurisdiction of the competent courts in Bengaluru, Karnataka, India.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
