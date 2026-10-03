@extends('layouts.app')

@section('title', 'Property Management Options | ' . $property->title . ' — Housing365 India')

@section('content')

<!-- ── BREADCRUMB ── -->
<div class="bg-white border-b border-slate-100 py-3.5 text-sm text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-cyan transition font-medium">Home</a>
        <span class="text-slate-300">/</span>
        <a href="{{ route('dashboard.owner') }}" class="hover:text-brand-cyan transition font-medium">Owner Workspace</a>
        <span class="text-slate-300">/</span>
        <span class="text-brand-navy font-bold">Property Management Options</span>
    </div>
</div>

<section class="py-12 sm:py-16 bg-slate-50/60 min-h-screen">
    <div class="mx-auto w-full max-w-5xl px-4 sm:px-8">
        
        <!-- Header -->
        <div class="mb-10 text-center sm:text-left">
            <span class="text-xs font-bold uppercase tracking-wider text-brand-cyan">TRANSPARENT OWNER CHOICES</span>
            <h1 class="text-3xl font-display font-extrabold text-brand-navy mt-1">Property Management Options</h1>
            <p class="text-sm text-slate-500 mt-1">
                For property: <strong class="text-brand-navy">{{ $property->title }}</strong> ({{ $property->city ?? 'India' }})
            </p>
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

        <!-- Current Status Banner -->
        @if($enrollment && $enrollment->status === 'ACTIVE')
            <div class="mb-8 p-6 rounded-3xl bg-gradient-to-r from-emerald-600 to-teal-700 text-white shadow-lg flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 text-xs font-bold uppercase tracking-wider mb-2">
                        <i class="fa-solid fa-shield-check"></i>
                        <span>ACTIVELY MANAGED</span>
                    </div>
                    <h2 class="text-xl font-bold">{{ $enrollment->plan->name }} (Active)</h2>
                    <p class="text-xs text-white/90 mt-1">
                        Agreement Ref: <strong>{{ $enrollment->agreement_reference }}</strong> &bull; Activated {{ $enrollment->agreement_accepted_at ? $enrollment->agreement_accepted_at->format('d M Y') : 'Active' }}
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('management.agreement', $enrollment->id) }}" class="px-4 py-2.5 rounded-xl bg-white text-emerald-800 font-bold text-xs uppercase tracking-wider hover:bg-slate-100 transition shadow">
                        View Agreement
                    </a>
                    <form action="{{ route('management.agreement.cancel', $enrollment->id) }}" method="POST" onsubmit="return confirm('Cancel Property Management for this property? The property will revert to standard Leasing Only (₹0 fee).');">
                        @csrf
                        <button type="submit" class="px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs uppercase tracking-wider transition">
                            Revert to Leasing Only
                        </button>
                    </form>
                </div>
            </div>
        @elseif($enrollment && ($enrollment->status === 'AGREEMENT_PENDING' || $enrollment->status === 'REQUESTED'))
            <div class="mb-8 p-6 rounded-3xl bg-gradient-to-r from-amber-500 to-amber-600 text-white shadow-lg flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 text-xs font-bold uppercase tracking-wider mb-2">
                        <i class="fa-solid fa-clock"></i>
                        <span>AGREEMENT SIGNATURE PENDING</span>
                    </div>
                    <h2 class="text-xl font-bold">Enrollment Requested: {{ $enrollment->plan->name }}</h2>
                    <p class="text-xs text-white/90 mt-1">
                        Please review and accept the official Property Management Agreement to activate coverage.
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('management.agreement', $enrollment->id) }}" class="px-5 py-3 rounded-xl bg-white text-amber-900 font-extrabold text-xs uppercase tracking-wider hover:bg-slate-100 transition shadow">
                        Review &amp; Sign Agreement &rarr;
                    </a>
                </div>
            </div>
        @endif

        <!-- Two Transparent Paths Comparison -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
            
            <!-- PATH A: LEASING ONLY (DEFAULT & FREE) -->
            <div class="bg-white rounded-3xl p-8 border-2 @if(!$enrollment || $enrollment->status === 'CANCELLED') border-brand-cyan/60 shadow-lg @else border-slate-200 shadow-sm @endif flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-slate-100 text-slate-700">
                            PATH A &bull; STANDARD
                        </span>
                        <span class="text-xs font-extrabold text-emerald-600 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full">
                            ₹0 FREE FOREVER
                        </span>
                    </div>

                    <h2 class="text-2xl font-display font-extrabold text-brand-navy mb-2">Leasing Only</h2>
                    <p class="text-xs text-slate-500 leading-relaxed mb-6">
                        Self-manage your property with zero mandatory fees. List for free, receive direct tenant inquiries, coordinate viewings yourself, and call Housing365 Care on-demand whenever maintenance is needed.
                    </p>

                    <div class="space-y-3 text-xs text-slate-700 mb-8">
                        <div class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-sm mt-0.5"></i>
                            <span><strong>100% Free Listing:</strong> Zero listing charges, zero mandatory subscription.</span>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-sm mt-0.5"></i>
                            <span><strong>Direct Enquiries:</strong> Inquiries sent directly to your owner workspace.</span>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-sm mt-0.5"></i>
                            <span><strong>Housing365 Care On-Demand:</strong> Book repairs, cleaning, or painting only when needed.</span>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-sm mt-0.5"></i>
                            <span><strong>You Retain Full Control:</strong> Self-schedule visits and collect rent directly.</span>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100">
                    <div class="text-xs font-bold text-slate-600 mb-2">Current Status:</div>
                    <div class="p-3 rounded-2xl bg-slate-50 text-xs font-semibold text-slate-700 flex items-center gap-2">
                        <i class="fa-solid fa-circle-dot text-emerald-500"></i>
                        <span>Active Default Mode (No management fee)</span>
                    </div>
                </div>
            </div>

            <!-- PATH B: LEASING + OPTIONAL PROPERTY MANAGEMENT -->
            <div class="bg-white rounded-3xl p-8 border-2 @if($enrollment && $enrollment->status === 'ACTIVE') border-brand-cyan/60 shadow-lg @else border-slate-200 shadow-sm @endif flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-brand-cyan/15 text-brand-navy">
                            PATH B &bull; OPTIONAL
                        </span>
                        <span class="text-xs font-extrabold text-purple-700 bg-purple-50 border border-purple-200 px-2.5 py-0.5 rounded-full">
                            8% - 10% MONTHLY
                        </span>
                    </div>

                    <h2 class="text-2xl font-display font-extrabold text-brand-navy mb-2">Full Property Management</h2>
                    <p class="text-xs text-slate-500 leading-relaxed mb-6">
                        Complete peace of mind for busy landlords and NRI owners. Dedicated property manager, physical routine inspections, automated rent tracking, and supervised tenant maintenance.
                    </p>

                    <div class="space-y-3 text-xs text-slate-700 mb-8">
                        <div class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-brand-cyan text-sm mt-0.5"></i>
                            <span><strong>Dedicated Manager:</strong> Single point of contact for tenant coordination.</span>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-brand-cyan text-sm mt-0.5"></i>
                            <span><strong>Periodic Inspections:</strong> Scheduled photographic walkthroughs of your home.</span>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-brand-cyan text-sm mt-0.5"></i>
                            <span><strong>Maintenance Supervision:</strong> Rapid triage &amp; quotes via Housing365 Care.</span>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-brand-cyan text-sm mt-0.5"></i>
                            <span><strong>Configurable Cancellation:</strong> Cancel according to configured plan policy with zero exit penalty.</span>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100">
                    @if(!$enrollment || $enrollment->status === 'CANCELLED')
                        <a href="#enroll-form" class="block w-full py-3 px-4 rounded-2xl bg-brand-cyan hover:bg-brand-cyan-hover text-brand-navy font-bold text-xs uppercase tracking-wider text-center transition shadow-md shadow-brand-cyan/20">
                            Select a Management Plan &rarr;
                        </a>
                    @else
                        <div class="p-3 rounded-2xl bg-emerald-50 text-xs font-semibold text-emerald-800 flex items-center gap-2">
                            <i class="fa-solid fa-check-circle text-emerald-600"></i>
                            <span>Plan Active: {{ $enrollment->plan->name }}</span>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- Enrollment Form (Only if not already enrolled) -->
        @if(!$enrollment || $enrollment->status === 'CANCELLED')
            <div id="enroll-form" class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200/80 shadow-xl">
                <h3 class="text-xl font-display font-extrabold text-brand-navy mb-2">Enroll in Property Management</h3>
                <p class="text-xs text-slate-500 mb-6">
                    Select your preferred plan. After submission, you will be presented with the official Property Management Agreement to review and accept before any services are activated.
                </p>

                <form action="{{ route('management.request', $property->id) }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-3">
                            Select Management Plan <span class="text-rose-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($plans as $plan)
                                <label class="relative flex flex-col p-5 rounded-2xl border-2 cursor-pointer transition hover:border-brand-cyan has-[:checked]:border-brand-cyan has-[:checked]:bg-brand-cyan/5">
                                    <input type="radio" name="management_plan_id" value="{{ $plan->id }}" {{ $loop->first ? 'checked' : '' }} class="sr-only">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-bold text-sm text-brand-navy">{{ $plan->name }}</span>
                                        <span class="font-extrabold text-xs text-brand-cyan bg-brand-cyan/10 px-2.5 py-1 rounded-full">{{ $plan->fee_display }}</span>
                                    </div>
                                    <p class="text-xs text-slate-500 leading-relaxed mb-3">{{ $plan->description }}</p>
                                    @if(!empty($plan->features) && is_array($plan->features))
                                        <ul class="space-y-1 text-[11px] text-slate-600">
                                            @foreach(array_slice($plan->features, 0, 4) as $feat)
                                                <li class="flex items-center gap-1.5">
                                                    <i class="fa-solid fa-check text-brand-cyan text-[10px]"></i>
                                                    <span>{{ $feat }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label for="notes" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                            Special Instructions or Property Details (Optional)
                        </label>
                        <textarea id="notes" name="notes" rows="3" placeholder="e.g. Current tenant moving out next month, key held with building security..." class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-brand-cyan text-sm font-medium text-slate-800"></textarea>
                    </div>

                    <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-xs text-amber-800 leading-relaxed">
                        <i class="fa-solid fa-file-contract mr-1 text-amber-600"></i>
                        <strong>Separate Agreement Required:</strong> Submitting this request creates an agreement draft. No fees will be charged until you formally accept the terms in the next step.
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <a href="{{ route('dashboard.owner') }}" class="text-xs font-bold text-slate-500 hover:text-brand-navy transition">
                            &larr; Return to Owner Workspace
                        </a>
                        <button type="submit" class="px-8 py-3.5 rounded-2xl bg-brand-cyan hover:bg-brand-cyan-hover text-brand-navy font-extrabold text-sm uppercase tracking-wider transition shadow-lg shadow-brand-cyan/25 flex items-center gap-2">
                            <span>Proceed to Agreement</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>
                    </div>
                </form>
            </div>
        @endif

    </div>
</section>

@endsection
