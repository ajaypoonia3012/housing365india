@extends('layouts.app')

@section('title', 'Tenant Screening & Verification — Housing365 India')

@section('content')
<div class="bg-white border-b border-slate-100 py-3.5 text-sm text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-cyan transition font-medium">Home</a>
        <span class="text-slate-300">/</span>
        <a href="{{ route('dashboard.tenant') }}" class="hover:text-brand-cyan transition font-medium">Tenant Hub</a>
        <span class="text-slate-300">/</span>
        <span class="text-brand-navy font-bold">Verification &amp; Screening</span>
    </div>
</div>

<section class="py-10 bg-slate-50/60 min-h-screen">
    <div class="mx-auto w-full max-w-4xl px-4 sm:px-6">

        <!-- Header -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-brand-cyan">SECURE TENANT VERIFICATION</span>
                <h1 class="text-2xl sm:text-3xl font-display font-extrabold text-brand-navy mt-1">Screening Application</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Ref: <strong class="text-brand-navy">{{ $screening->screening_number }}</strong>
                    @if($screening->property) &bull; Property: <strong>{{ $screening->property->title }}</strong> @endif
                </p>
            </div>
            <div>
                <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider
                    @if($screening->status === 'VERIFIED') bg-emerald-50 text-emerald-700 border border-emerald-200
                    @elseif($screening->status === 'FAILED') bg-rose-50 text-rose-700 border border-rose-200
                    @elseif($screening->status === 'CONSENT_REQUIRED') bg-amber-50 text-amber-700 border border-amber-200
                    @else bg-blue-50 text-brand-navy border border-blue-200 @endif">
                    {{ str_replace('_', ' ', $screening->status) }}
                </span>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- 1. Consent Gate Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm mb-8">
            <h2 class="text-lg font-bold text-brand-navy mb-2 flex items-center gap-2">
                <i class="fa-solid fa-shield-halved text-brand-cyan"></i>
                <span>1. Consent-First Verification</span>
            </h2>
            <p class="text-xs text-slate-500 leading-relaxed mb-6">
                In compliance with Indian data privacy principles, verification of identity and employment is only performed with your explicit, informed consent. We collect only required documents to verify your tenancy eligibility.
            </p>

            @if($screening->consent_granted_at)
                <div class="p-4 rounded-2xl bg-emerald-50/80 border border-emerald-200 text-xs text-emerald-800 flex items-start gap-3">
                    <i class="fa-solid fa-check-circle text-emerald-600 text-sm mt-0.5"></i>
                    <div>
                        <strong class="font-bold">Consent Recorded:</strong> Granted on {{ $screening->consent_granted_at->format('d M Y, h:i A') }} (IP: {{ $screening->consent_ip_address }})
                        <div class="text-[11px] text-emerald-700/90 mt-0.5">Purpose: {{ $screening->consent_purpose }}</div>
                    </div>
                </div>
            @elseif($isTenant)
                <form action="{{ route('tenant.screenings.consent', $screening->id) }}" method="POST" class="p-5 rounded-2xl bg-slate-50 border border-slate-200">
                    @csrf
                    <div class="flex items-start gap-3 mb-4">
                        <input type="checkbox" name="confirm_consent" id="confirm_consent" value="1" required class="mt-1 w-4 h-4 rounded text-brand-cyan focus:ring-brand-cyan border-slate-300">
                        <label for="confirm_consent" class="text-xs text-slate-700 leading-relaxed cursor-pointer">
                            I hereby grant explicit consent to Housing365 India and its authorized verification partners to verify my government identity proofs and income details solely for the purpose of residential tenancy evaluation.
                        </label>
                    </div>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-navy hover:bg-brand-navy-light text-white font-bold text-xs uppercase tracking-wider transition shadow-sm">
                        Grant Consent &amp; Proceed &rarr;
                    </button>
                </form>
            @endif
        </div>

        <!-- 2. Document Upload & OCR Status Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm mb-8">
            <h2 class="text-lg font-bold text-brand-navy mb-2 flex items-center gap-2">
                <i class="fa-solid fa-file-shield text-brand-cyan"></i>
                <span>2. Identification Documents &amp; OCR</span>
            </h2>
            <p class="text-xs text-slate-500 mb-6">
                Uploaded documents are encrypted and stored in private storage. Sensitive ID numbers are masked.
            </p>

            @if($isTenant && $screening->consent_granted_at)
                <form action="{{ route('tenant.screenings.document', $screening->id) }}" method="POST" enctype="multipart/form-data" class="mb-8 p-5 rounded-2xl bg-slate-50 border border-slate-200">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Document Type *</label>
                            <select name="document_type" required class="w-full h-10 px-3 rounded-xl border border-slate-200 text-xs bg-white text-slate-700 focus:ring-brand-cyan focus:border-brand-cyan">
                                <option value="aadhaar">Aadhaar Card</option>
                                <option value="pan">PAN Card</option>
                                <option value="passport">Passport</option>
                                <option value="driving_licence">Driving Licence</option>
                                <option value="salary_slip">Salary Slip / Offer Letter</option>
                                <option value="bank_statement">Bank Statement (Last 3 Months)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Document Number (Optional)</label>
                            <input type="text" name="document_number" placeholder="e.g. 987654321098" class="w-full h-10 px-3 rounded-xl border border-slate-200 text-xs bg-white text-slate-700 focus:ring-brand-cyan focus:border-brand-cyan">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">File (PDF/JPG/PNG max 10MB) *</label>
                            <input type="file" name="document_file" required accept=".pdf,.jpg,.jpeg,.png" class="w-full text-xs text-slate-500 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-cyan/10 file:text-brand-navy hover:file:bg-brand-cyan/20">
                        </div>
                    </div>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-brand-navy hover:bg-brand-navy-light text-white font-bold text-xs uppercase tracking-wider transition">
                        Upload Document
                    </button>
                </form>
            @endif

            <!-- Document List -->
            @if($screening->documents->isEmpty())
                <div class="p-6 text-center text-xs text-slate-400 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                    No documents uploaded yet.
                </div>
            @else
                <div class="space-y-3">
                    @foreach($screening->documents as $doc)
                        <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-brand-navy shadow-sm">
                                    <i class="fa-solid fa-file-lines text-sm"></i>
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-brand-navy capitalize">{{ str_replace('_', ' ', $doc->document_type) }}</div>
                                    <div class="text-[11px] text-slate-500">
                                        ID Number: <strong class="text-slate-700 font-mono">{{ $doc->document_number_masked ?? 'Verified' }}</strong>
                                        &bull; OCR Status: <span class="uppercase font-semibold text-slate-600">{{ $doc->ocr_status }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    @if($doc->verification_status === 'VERIFIED') bg-emerald-50 text-emerald-700 border border-emerald-200
                                    @elseif($doc->verification_status === 'REJECTED') bg-rose-50 text-rose-700 border border-rose-200
                                    @else bg-amber-50 text-amber-700 border border-amber-200 @endif">
                                    {{ $doc->verification_status }}
                                </span>
                                <a href="{{ route('tenant.documents.download', $doc->id) }}" class="p-2 rounded-lg bg-white border border-slate-200 text-slate-600 hover:text-brand-cyan hover:border-brand-cyan transition text-xs" title="Download Document">
                                    <i class="fa-solid fa-download"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</section>
@endsection
