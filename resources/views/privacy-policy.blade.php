@extends('layouts.app')

@section('title', 'Privacy Policy | Housing365 India')
@section('meta_description', 'Privacy Policy and data governance practices of Housing365 India, adhering to the Digital Personal Data Protection Act (DPDP), 2023 and IT Act, 2000.')

@section('content')
<section class="relative overflow-hidden bg-gradient-to-b from-[#F7FAFD] via-white to-white py-14 sm:py-20 border-b border-slate-100">
    <div class="pointer-events-none absolute -top-40 right-1/4 h-96 w-96 rounded-full bg-[radial-gradient(circle,rgba(0,174,239,0.18)_0%,transparent_70%)] blur-3xl"></div>
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 relative z-10 max-w-4xl text-center">
        <div class="inline-flex items-center gap-2 rounded-full border border-brand-cyan/25 bg-brand-cyan/10 px-3.5 py-1 text-xs font-semibold uppercase tracking-wider text-brand-cyan mb-4">
            <span class="h-1.5 w-1.5 rounded-full bg-brand-cyan"></span>
            Data Privacy &amp; Protection
        </div>
        <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight text-brand-navy leading-[1.2] mb-4">
            Housing365 India <span class="text-brand-cyan">Privacy Policy</span>
        </h1>
        <p class="text-slate-600 text-sm sm:text-base max-w-2xl mx-auto">
            Last Updated: January 2026 &bull; Compliant with the Digital Personal Data Protection Act (DPDP Act), 2023 &amp; Information Technology Act, 2000.
        </p>
    </div>
</section>

<section class="py-14 sm:py-16 bg-slate-50/50">
    <div class="mx-auto w-full max-w-4xl px-4 sm:px-8">
        <div class="bg-white rounded-3xl p-8 sm:p-12 border border-slate-200/80 shadow-sm leading-relaxed text-slate-700 space-y-8">
            <div>
                <h2 class="text-xl font-bold text-brand-navy mb-3">1. Introduction &amp; Commitment</h2>
                <p class="text-sm sm:text-base leading-relaxed">
                    Housing365 India ("Housing365", "we", "our", or "us") operates the premier unified digital platform for verified real estate leasing, property management, care services, and property transactions in India. We respect your fundamental right to privacy and are dedicated to processing personal data transparently, securely, and in strict accordance with the Digital Personal Data Protection Act, 2023 (DPDP Act) and the Information Technology (Reasonable Security Practices and Procedures and Sensitive Personal Data or Information) Rules, 2011.
                </p>
            </div>

            <div>
                <h2 class="text-xl font-bold text-brand-navy mb-3">2. Information We Collect</h2>
                <p class="text-sm sm:text-base leading-relaxed mb-3">We collect personal data that you provide voluntarily or through platform usage:</p>
                <ul class="list-disc pl-6 space-y-2 text-sm sm:text-base text-slate-600">
                    <li><strong>Account &amp; Contact Identifiers:</strong> Name, phone number, email address, password, and communication preferences.</li>
                    <li><strong>Identity Verification Records:</strong> Government photo ID details (Aadhaar last 4 digits, PAN, Voter ID) required for tenant screening, police verification dossier compilation, and RERA compliance.</li>
                    <li><strong>Property &amp; Transaction Information:</strong> Property title deeds, electricity bills, Khata certificates, agreement drafts, rent ledger balances, and payment transaction IDs.</li>
                    <li><strong>Technical Telemetry:</strong> IP address, device fingerprints, browser category, and session access timestamps to detect unauthorized crawling and prevent fraudulent listings.</li>
                </ul>
            </div>

            <div>
                <h2 class="text-xl font-bold text-brand-navy mb-3">3. Purpose and Legal Grounds of Processing</h2>
                <ul class="list-disc pl-6 space-y-2 text-sm sm:text-base text-slate-600">
                    <li>Executing verified residential and commercial property listings with zero fake ads.</li>
                    <li>Drafting and e-stamping legally binding 11-month rent agreements under the Model Tenancy Act.</li>
                    <li>Facilitating secure owner-tenant communication and dispatching Housing365 Care repair technicians.</li>
                    <li>Executing statutory obligations including Section 194-IA / 194-IB TDS reporting and municipal police tenant verification.</li>
                </ul>
            </div>

            <div>
                <h2 class="text-xl font-bold text-brand-navy mb-3">4. Data Sharing &amp; Third-Party Transfers</h2>
                <p class="text-sm sm:text-base leading-relaxed">
                    We <strong>never sell, lease, or monetize your personal information</strong> to unsolicited telemarketers or external broker syndicates. Data is shared exclusively with verified parties essential to your service request (such as property owners whose contact you deliberately unlock, authorized background check agencies, certified SHCIL e-stamp vendors, and law enforcement agencies upon receipt of official statutory process).
                </p>
            </div>

            <div>
                <h2 class="text-xl font-bold text-brand-navy mb-3">5. Data Retention &amp; Security Standards</h2>
                <p class="text-sm sm:text-base leading-relaxed">
                    All sensitive documents, agreements, and payment records are secured using AES-256 encryption at rest and TLS 1.3 encryption in transit. Data is hosted on ISO 27001-certified Indian data centers in Mumbai and Hyderabad, complying with RBI data localization directives.
                </p>
            </div>

            <div>
                <h2 class="text-xl font-bold text-brand-navy mb-3">6. Grievance Redressal Officer</h2>
                <p class="text-sm sm:text-base leading-relaxed">
                    In compliance with the DPDP Act and the Information Technology Act, 2000, our designated Grievance Officer details are:
                </p>
                <div class="mt-3 p-4 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm text-slate-700">
                    <p class="font-bold text-brand-navy">Grievance Redressal Officer: Legal Counsel, Housing365 India</p>
                    <p>Address: Indiqube Emerald, Sector 2, HSR Layout, Bengaluru, Karnataka 560102, India</p>
                    <p>Email: <a href="mailto:privacy@housing365.in" class="text-brand-cyan font-bold hover:underline">privacy@housing365.in</a> / <a href="mailto:contact@housing365.in" class="text-brand-cyan font-bold hover:underline">contact@housing365.in</a></p>
                    <p>Direct Support Line: +91 63761 63263</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
