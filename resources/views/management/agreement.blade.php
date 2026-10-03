@extends('layouts.app')

@section('title', 'Property Management Agreement #' . $enrollment->agreement_reference . ' | Housing365 India')

@section('content')

<!-- ── BREADCRUMB ── -->
<div class="bg-white border-b border-slate-100 py-3.5 text-sm text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-cyan transition font-medium">Home</a>
        <span class="text-slate-300">/</span>
        <a href="{{ route('dashboard.owner') }}" class="hover:text-brand-cyan transition font-medium">Owner Workspace</a>
        <span class="text-slate-300">/</span>
        <span class="text-brand-navy font-bold">Property Management Agreement</span>
    </div>
</div>

<section class="py-12 sm:py-16 bg-slate-50/60 min-h-screen">
    <div class="mx-auto w-full max-w-4xl px-4 sm:px-8">
        
        <!-- Header -->
        <div class="mb-8 text-center sm:text-left">
            <span class="text-xs font-bold uppercase tracking-wider text-brand-cyan">HOUSING365 INSTITUTIONAL MANAGEMENT</span>
            <h1 class="text-3xl font-display font-extrabold text-brand-navy mt-1">Property Management Agreement</h1>
            <p class="text-sm text-slate-500 mt-1">
                Agreement Reference: <strong class="text-brand-navy font-mono">{{ $enrollment->agreement_reference }}</strong>
            </p>
        </div>

        @if(session('error'))
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Legal Agreement Document Container -->
        <div class="bg-white rounded-3xl p-8 sm:p-12 border border-slate-200/80 shadow-xl mb-8">
            <div class="flex items-center justify-between pb-6 border-b border-slate-200">
                <div>
                    <div class="font-display text-xl font-extrabold text-brand-navy">HOUSING365 INDIA</div>
                    <div class="text-xs text-slate-500">Property Management Division &bull; Bangalore &bull; Mumbai &bull; NCR</div>
                </div>
                <div class="text-right">
                    <span class="text-[10px] font-black uppercase tracking-wider px-3 py-1 rounded-full
                        @if($enrollment->status === 'ACTIVE') bg-emerald-100 text-emerald-800
                        @elseif($enrollment->status === 'AGREEMENT_PENDING') bg-amber-100 text-amber-800
                        @else bg-slate-100 text-slate-700
                        @endif">
                        {{ $enrollment->status === 'ACTIVE' ? 'EXECUTED & ACTIVE' : 'PENDING ACCEPTANCE' }}
                    </span>
                    <div class="text-[11px] text-slate-400 mt-1">Date: {{ now()->format('d M Y') }}</div>
                </div>
            </div>

            <!-- Agreement Body -->
            <div class="py-8 space-y-6 text-xs text-slate-700 leading-relaxed border-b border-slate-200">
                
                <div>
                    <h3 class="text-sm font-bold text-brand-navy uppercase tracking-wider mb-2">1. Parties &amp; Property</h3>
                    <p>
                        This Property Management Agreement is entered into between <strong>Housing365 India Operations</strong> ("Manager") and <strong>{{ $user->name }}</strong> ("Owner"), owner/authorized custodian of the residential/commercial real estate property located at:
                    </p>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 mt-2 font-medium">
                        <strong>{{ $enrollment->property->title }}</strong><br>
                        {{ $enrollment->property->address ?? $enrollment->property->locality ?? 'Primary Residence' }}, {{ $enrollment->property->city ?? 'India' }}
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-brand-navy uppercase tracking-wider mb-2">2. Scope of Service &amp; Management Plan</h3>
                    <p>
                        The Owner has selected the <strong>{{ $enrollment->plan->name }}</strong>. Under this plan, Housing365 will provide:
                    </p>
                    <ul class="list-disc pl-5 mt-2 space-y-1">
                        @if(!empty($enrollment->plan->features) && is_array($enrollment->plan->features))
                            @foreach($enrollment->plan->features as $feature)
                                <li>{{ $feature }}</li>
                            @endforeach
                        @else
                            <li>Tenant onboarding, lease tracking, and move-in coordination.</li>
                            <li>Periodic physical condition inspections and photographic logs.</li>
                            <li>Maintenance triage, vendor bidding, and emergency dispatch via Housing365 Care.</li>
                        @endif
                    </ul>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-brand-navy uppercase tracking-wider mb-2">3. Management Fee &amp; Billing</h3>
                    <p>
                        The Management Fee shall be <strong>{{ $enrollment->plan->fee_display }}</strong>. In accordance with Housing365 institutional policy, management fees are earned only during active tenant tenancies. No upfront placement clawback or hidden charges shall apply.
                    </p>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-brand-navy uppercase tracking-wider mb-2">4. Plan Termination &amp; Cancellation</h3>
                    <p>
                        Either party may terminate this Property Management enrollment at any time according to the configured plan policies and notice period via the Owner Workspace with zero exit penalty. Upon cancellation, the property automatically reverts to the standard <strong>Leasing Only (₹0 Free)</strong> mode, preserving all listing and inquiry history.
                    </p>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-brand-navy uppercase tracking-wider mb-2">5. Maintenance Coordination &amp; Housing365 Care</h3>
                    <p>
                        Maintenance and repairs for the property are coordinated on-demand via Housing365 Care. Work orders require advance owner approval before dispatch in accordance with the owner's configured approval threshold.
                    </p>
                </div>

            </div>

            <!-- Execution / Signature Section -->
            @if($enrollment->status === 'AGREEMENT_PENDING' || $enrollment->status === 'REQUESTED')
                <div class="pt-8">
                    <form action="{{ route('management.agreement.accept', $enrollment->id) }}" method="POST" class="space-y-6">
                        @csrf

                        <label class="flex items-start gap-3 p-4 rounded-2xl bg-brand-cyan/5 border border-brand-cyan/20 cursor-pointer">
                            <input type="checkbox" name="confirm_terms" value="1" required class="mt-1 w-4 h-4 rounded text-brand-cyan focus:ring-brand-cyan">
                            <span class="text-xs text-slate-700 leading-relaxed">
                                <strong>I confirm and agree:</strong> I am the legal owner or authorized custodian of this property. I have read, understood, and accept the Housing365 Property Management Agreement terms, the {{ $enrollment->plan->fee_display }} management fee, and the 30-day cancellation rights.
                            </span>
                        </label>

                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                            <a href="{{ route('management.explore', $enrollment->property_id) }}" class="text-xs font-bold text-slate-500 hover:text-brand-navy transition order-2 sm:order-1">
                                &larr; Return to Management Options
                            </a>

                            <div class="flex items-center gap-3 w-full sm:w-auto order-1 sm:order-2">
                                <button type="submit" class="w-full sm:w-auto px-8 py-3.5 rounded-2xl bg-brand-cyan hover:bg-brand-cyan-hover text-brand-navy font-extrabold text-sm uppercase tracking-wider transition shadow-lg shadow-brand-cyan/25 flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-signature"></i>
                                    <span>Accept &amp; Activate Management</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @else
                <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-xs text-slate-600">
                        <i class="fa-solid fa-circle-check text-emerald-600 mr-1"></i>
                        <span>Electronically accepted on <strong>{{ $enrollment->agreement_accepted_at ? $enrollment->agreement_accepted_at->format('d M Y, h:i A') : 'N/A' }}</strong></span>
                        @if($enrollment->agreement_accepted_ip)
                            <span class="text-slate-400 font-mono">(IP: {{ $enrollment->agreement_accepted_ip }})</span>
                        @endif
                    </div>

                    <form action="{{ route('management.agreement.cancel', $enrollment->id) }}" method="POST" onsubmit="return confirm('Cancel Property Management and revert to standard Leasing Only?');">
                        @csrf
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-rose-50 hover:text-rose-700 text-slate-600 text-xs font-bold uppercase tracking-wider transition">
                            Cancel Management Enrollment
                        </button>
                    </form>
                </div>
            @endif

        </div>

    </div>
</section>

@endsection
