@extends('layouts.app')

@section('title', 'Trust, Verification & Compliance Hub | Housing 365')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-[#03234B] tracking-tight">Trust &amp; Verification Hub</h1>
                    <p class="text-sm text-slate-500 mt-1">Manage government KYC, property ownership validations, and trust credentials on Housing365.</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 text-sm font-semibold text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition">
                        &larr; Back to Dashboard
                    </a>
                </div>
            </div>
        </div>

        @if(session('status'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if($activeSuspension)
            <div class="mb-6 p-5 rounded-xl bg-rose-50 border border-rose-200 text-rose-900">
                <div class="flex items-start gap-3">
                    <span class="text-2xl">⚠️</span>
                    <div>
                        <h3 class="font-bold text-base">Account Notice: {{ $activeSuspension->suspension_type }} Restriction</h3>
                        <p class="text-sm text-rose-700 mt-1">{{ $activeSuspension->reason }}</p>
                        <p class="text-xs text-rose-600 mt-2">Active from {{ $activeSuspension->starts_at->format('d M Y') }}. You may file an appeal below for compliance review.</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left: Active Trust Badges & Submit Verification -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Badges Card -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                    <h2 class="text-base font-bold text-[#03234B] mb-4 flex items-center gap-2">
                        <span class="text-[#00AEEF]">★</span> Active Trust Credentials
                    </h2>
                    @if($badges->isEmpty())
                        <div class="text-center py-6">
                            <div class="w-12 h-12 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-2">
                                🛡️
                            </div>
                            <p class="text-xs text-slate-500 font-medium">No verified credentials yet.</p>
                            <p class="text-xs text-slate-400 mt-1">Complete your identity or property verification to unlock verified badges.</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($badges as $b)
                                <div class="p-3 rounded-xl bg-sky-50 border border-sky-100 flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-full bg-[#00AEEF] text-white flex items-center justify-center text-xs font-bold shrink-0">
                                        ✓
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-[#03234B]">{{ $b->trustBadge->name }}</h4>
                                        <p class="text-[11px] text-slate-500 mt-0.5">{{ $b->trustBadge->description }}</p>
                                        <span class="text-[10px] text-slate-400">Awarded: {{ $b->assigned_at->format('d M Y') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Submit Verification Form -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                    <h2 class="text-base font-bold text-[#03234B] mb-2 flex items-center gap-2">
                        <span>📋</span> Submit Verification
                    </h2>
                    <p class="text-xs text-slate-500 mb-4">Upload official government or property documents for validation.</p>

                    <form action="{{ route('trust.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Verification Category</label>
                            <select name="verification_type_code" class="w-full text-xs rounded-lg border-slate-300 shadow-sm focus:border-[#00AEEF] focus:ring-[#00AEEF]" required>
                                @foreach($verificationTypes as $vt)
                                    <option value="{{ $vt->code }}">{{ $vt->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if($userProperties->isNotEmpty())
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Link to Property (Optional)</label>
                                <select name="subject_id" class="w-full text-xs rounded-lg border-slate-300 shadow-sm focus:border-[#00AEEF] focus:ring-[#00AEEF]">
                                    <option value="">Personal / Operator Verification</option>
                                    @foreach($userProperties as $prop)
                                        <option value="{{ $prop->id }}">{{ $prop->title }} ({{ $prop->city }})</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="subject_type" value="property">
                            </div>
                        @endif

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Document Upload (Private & Encrypted)</label>
                            <input type="file" name="document" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-sky-50 file:text-[#00AEEF] hover:file:bg-sky-100">
                            <p class="text-[10px] text-slate-400 mt-1">Allowed: PDF, JPG, PNG (Max 5MB). Stored securely off public web.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Document Number (Optional Masked)</label>
                            <input type="text" name="document_number" placeholder="e.g. Last 4 digits or ref number" class="w-full text-xs rounded-lg border-slate-300 shadow-sm focus:border-[#00AEEF] focus:ring-[#00AEEF]">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Additional Notes</label>
                            <textarea name="notes" rows="2" placeholder="Details for compliance reviewer..." class="w-full text-xs rounded-lg border-slate-300 shadow-sm focus:border-[#00AEEF] focus:ring-[#00AEEF]"></textarea>
                        </div>

                        <button type="submit" class="w-full py-2.5 px-4 bg-[#03234B] hover:bg-[#00AEEF] text-white text-xs font-bold rounded-xl transition shadow-sm">
                            Submit for Verification
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right: Verification History & Appeals -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Verifications List -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                    <h2 class="text-base font-bold text-[#03234B] mb-4 flex items-center justify-between">
                        <span>Submitted Verifications</span>
                        <span class="text-xs font-normal text-slate-400">{{ $verifications->count() }} total</span>
                    </h2>

                    @if($verifications->isEmpty())
                        <div class="text-center py-10 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                            <p class="text-sm font-semibold text-slate-600">No verifications on file</p>
                            <p class="text-xs text-slate-400 mt-1">Submit your KYC or property documents to begin building trust on Housing365.</p>
                        </div>
                    @else
                        <div class="divide-y divide-slate-100">
                            @foreach($verifications as $v)
                                <div class="py-4 first:pt-0 last:pb-0">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <h4 class="text-sm font-bold text-[#03234B]">{{ $v->verificationType?->name ?? $v->verification_type_code }}</h4>
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                                    @if($v->status === 'VERIFIED') bg-emerald-100 text-emerald-800
                                                    @elseif($v->status === 'PENDING') bg-amber-100 text-amber-800
                                                    @elseif($v->status === 'UNDER_REVIEW') bg-sky-100 text-sky-800
                                                    @elseif($v->status === 'REJECTED') bg-rose-100 text-rose-800
                                                    @else bg-slate-100 text-slate-700 @endif">
                                                    {{ str_replace('_', ' ', $v->status) }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-slate-500 mt-0.5">
                                                Submitted on {{ $v->submitted_at?->format('d M Y, h:i A') ?? 'N/A' }}
                                                @if($v->expires_at)
                                                    &bull; Valid until {{ $v->expires_at->format('d M Y') }}
                                                @endif
                                            </p>
                                            @if($v->rejection_reason)
                                                <p class="text-xs text-rose-600 mt-1 font-medium bg-rose-50 p-2 rounded-lg border border-rose-100">
                                                    Rejection Reason: {{ $v->rejection_reason }}
                                                </p>
                                            @endif
                                        </div>

                                        <!-- Document downloads (Author/Admin only) -->
                                        @if($v->documents->isNotEmpty())
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($v->documents as $doc)
                                                    <a href="{{ route('trust.document.download', $doc) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 transition">
                                                        <span>🔒</span>
                                                        <span>{{ $doc->original_filename ? Str::limit($doc->original_filename, 15) : 'Document' }}</span>
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Appeals Center -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                    <h2 class="text-base font-bold text-[#03234B] mb-2 flex items-center gap-2">
                        <span>⚖️</span> Appeals & Dispute Center
                    </h2>
                    <p class="text-xs text-slate-500 mb-4">If a verification request was rejected or your account was restricted, you may submit a formal appeal for human compliance review.</p>

                    @if($appeals->isNotEmpty())
                        <div class="mb-6 space-y-3">
                            <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Your Active Appeals</h3>
                            @foreach($appeals as $app)
                                <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="text-xs font-bold text-[#03234B]">Appeal #{{ $app->id }} ({{ class_basename($app->appealable_type) }})</span>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                            @if($app->status === 'APPROVED') bg-emerald-100 text-emerald-800
                                            @elseif($app->status === 'SUBMITTED') bg-amber-100 text-amber-800
                                            @elseif($app->status === 'UNDER_REVIEW') bg-sky-100 text-sky-800
                                            @else bg-rose-100 text-rose-800 @endif">
                                            {{ $app->status }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-600 mt-1 italic">"{{ $app->reason }}"</p>
                                    @if($app->admin_response)
                                        <p class="text-xs text-slate-700 mt-2 font-medium bg-white p-2 rounded border border-slate-200">
                                            Compliance Decision: {{ $app->admin_response }}
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Appeal Form -->
                    <form action="{{ route('trust.appeal') }}" method="POST" class="space-y-4 pt-2 border-t border-slate-100">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Appeal Target</label>
                                <select name="appealable_type" class="w-full text-xs rounded-lg border-slate-300 shadow-sm focus:border-[#00AEEF] focus:ring-[#00AEEF]" required>
                                    <option value="verification">Rejected Verification</option>
                                    @if($activeSuspension)
                                        <option value="suspension">Account Restriction</option>
                                    @endif
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Target ID / Verification #</label>
                                <input type="number" name="appealable_id" placeholder="e.g. Verification ID" class="w-full text-xs rounded-lg border-slate-300 shadow-sm focus:border-[#00AEEF] focus:ring-[#00AEEF]" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Reason for Appeal & Evidence Description</label>
                            <textarea name="reason" rows="3" placeholder="Explain why the rejection should be reconsidered..." class="w-full text-xs rounded-lg border-slate-300 shadow-sm focus:border-[#00AEEF] focus:ring-[#00AEEF]" required></textarea>
                        </div>

                        <button type="submit" class="px-5 py-2 bg-slate-800 hover:bg-[#03234B] text-white text-xs font-bold rounded-xl transition shadow-sm">
                            Submit Compliance Appeal
                        </button>
                    </form>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
