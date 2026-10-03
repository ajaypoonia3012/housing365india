@extends('layouts.app')

@section('title', 'Manage Job #' . $serviceRequest->request_number . ' | Vendor Portal — Housing365 India')

@section('content')

<!-- ── BREADCRUMB ── -->
<div class="bg-white border-b border-slate-100 py-3.5 text-sm text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-cyan transition font-medium">Home</a>
        <span class="text-slate-300">/</span>
        <a href="{{ route('vendor.jobs') }}" class="hover:text-brand-cyan transition font-medium">Assigned Jobs</a>
        <span class="text-slate-300">/</span>
        <span class="text-brand-navy font-bold">Job #{{ $serviceRequest->request_number }}</span>
    </div>
</div>

<section class="py-12 sm:py-16 bg-slate-50/60 min-h-screen">
    <div class="mx-auto w-full max-w-4xl px-4 sm:px-8">
        
        <!-- Alerts -->
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

        <!-- Top Navigation Action -->
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('vendor.jobs') }}" class="text-xs font-semibold text-slate-500 hover:text-brand-cyan transition">
                &larr; Back to Assigned Jobs
            </a>
            <a href="{{ route('vendor.payouts') }}" class="text-xs font-bold text-brand-navy hover:text-brand-cyan transition flex items-center gap-1.5">
                <i class="fa-solid fa-coins text-amber-500"></i>
                <span>View Payouts Ledger</span>
            </a>
        </div>

        <!-- Assignment Offer Accept / Reject Card -->
        @if($serviceRequest->assignment_status === 'OFFERED' || $serviceRequest->status === 'VENDOR_ASSIGNED')
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50/50 rounded-3xl p-6 sm:p-8 border border-blue-200 shadow-sm mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <span class="text-xs font-black uppercase tracking-wider text-blue-700 bg-blue-100 px-3 py-1 rounded-full">
                            New Job Assignment Offer
                        </span>
                        <h3 class="text-lg font-bold text-brand-navy mt-2">Housing365 Dispatched Service Request</h3>
                        <p class="text-xs text-slate-600 mt-1 max-w-lg">
                            Please review the job details below and accept to confirm your availability. Once accepted, you can inspect and submit an itemized quote.
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <form action="{{ route('vendor.requests.accept', $serviceRequest->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs uppercase tracking-wider transition shadow-sm flex items-center gap-1.5">
                                <i class="fa-solid fa-check"></i>
                                <span>Accept Offer</span>
                            </button>
                        </form>

                        <form action="{{ route('vendor.requests.reject', $serviceRequest->id) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            <input type="text" name="reason" placeholder="Reason..." class="text-xs px-3 py-2 rounded-xl border border-slate-300 w-36">
                            <button type="submit" onclick="return confirm('Decline this assignment?')" class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs uppercase tracking-wider transition">
                                Decline
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-xs font-extrabold uppercase tracking-wider text-brand-cyan bg-brand-cyan/10 px-3 py-1 rounded-full">
                            {{ $serviceRequest->category->name }}
                        </span>
                        <span class="text-xs font-bold text-slate-400">#{{ $serviceRequest->request_number }}</span>
                        @if($serviceRequest->priority === 'emergency')
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-rose-50 text-rose-600 border border-rose-200">
                                Emergency
                            </span>
                        @endif
                    </div>
                    <h1 class="text-2xl font-display font-extrabold text-brand-navy">
                        {{ $serviceRequest->title ?: $serviceRequest->category->name . ' Service' }}
                    </h1>
                    <div class="text-xs text-slate-500 mt-1 flex flex-wrap items-center gap-3">
                        <span>Preferred Date: <strong>{{ $serviceRequest->preferred_date ? $serviceRequest->preferred_date->format('d M Y') : 'Flexible' }}</strong></span>
                        <span>&bull;</span>
                        <span class="capitalize">Window: <strong>{{ $serviceRequest->preferred_time_window ?: 'Anytime' }}</strong></span>
                    </div>
                </div>

                <div>
                    <span class="px-3.5 py-1.5 rounded-xl text-xs font-black uppercase tracking-wider inline-flex items-center gap-1.5
                        @if($serviceRequest->status === 'AWAITING_APPROVAL') bg-amber-50 text-amber-800 border border-amber-300
                        @elseif($serviceRequest->status === 'IN_PROGRESS' || $serviceRequest->status === 'SCHEDULED') bg-blue-50 text-blue-800 border border-blue-200
                        @elseif($serviceRequest->status === 'QC_PENDING' || $serviceRequest->status === 'CLOSED') bg-emerald-50 text-emerald-800 border border-emerald-200
                        @else bg-slate-100 text-slate-700 border border-slate-200
                        @endif">
                        {{ str_replace('_', ' ', $serviceRequest->status) }}
                    </span>
                </div>
            </div>

            <!-- Description & Customer Photos -->
            <div class="pt-6">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Customer Description</h3>
                <p class="text-sm text-slate-700 leading-relaxed bg-slate-50 p-4 rounded-2xl border border-slate-100 mb-4">
                    {{ $serviceRequest->description }}
                </p>

                @if(!empty($serviceRequest->photos) && is_array($serviceRequest->photos))
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Customer Uploaded Photos</h4>
                        <div class="flex flex-wrap gap-3">
                            @foreach($serviceRequest->photos as $photo)
                                <a href="{{ $photo }}" target="_blank" class="w-20 h-20 rounded-2xl overflow-hidden border border-slate-200 block">
                                    <img src="{{ $photo }}" alt="Problem photo" class="w-full h-full object-cover">
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="mt-4 p-4 rounded-2xl bg-blue-50/60 border border-blue-200 text-xs text-blue-900 flex items-center justify-between">
                    <div>
                        <strong>Service Location:</strong> {{ $serviceRequest->property ? $serviceRequest->property->locality . ', ' . $serviceRequest->property->city : 'Customer Site' }}
                    </div>
                    <span class="text-[10px] text-blue-700 uppercase font-bold tracking-wider">Housing365 Dispatched</span>
                </div>
            </div>
        </div>

        <!-- Quote Submission or Existing Quote -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm mb-6">
            <h3 class="text-base font-bold text-brand-navy mb-4 flex items-center gap-2">
                <i class="fa-solid fa-receipt text-brand-cyan"></i>
                <span>Quote &amp; Cost Estimation</span>
            </h3>

            @if($existingQuote)
                <div class="p-6 rounded-2xl border @if($existingQuote->status === 'APPROVED') border-emerald-300 bg-emerald-50/20 @else border-slate-200 bg-slate-50/50 @endif">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-200">
                        <div>
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Quote #{{ $existingQuote->quote_number }}</div>
                            <div class="text-2xl font-display font-extrabold text-brand-navy mt-1">₹{{ number_format($existingQuote->total_amount, 2) }}</div>
                            <div class="text-xs text-slate-500 mt-0.5">Estimated Duration: {{ $existingQuote->estimated_duration }}</div>
                        </div>
                        <span class="px-3 py-1 rounded-xl text-xs font-black uppercase tracking-wider
                            @if($existingQuote->status === 'APPROVED') bg-emerald-100 text-emerald-800
                            @elseif($existingQuote->status === 'PENDING') bg-amber-100 text-amber-800
                            @else bg-slate-200 text-slate-700
                            @endif">
                            {{ $existingQuote->status }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 py-4 text-xs">
                        <div>
                            <span class="text-slate-400 block">Labour:</span>
                            <span class="font-bold text-slate-700">₹{{ number_format($existingQuote->labour_amount, 2) }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block">Materials/Parts:</span>
                            <span class="font-bold text-slate-700">₹{{ number_format($existingQuote->materials_amount, 2) }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block">Taxes:</span>
                            <span class="font-bold text-slate-700">₹{{ number_format($existingQuote->tax_amount, 2) }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block">Other Charges:</span>
                            <span class="font-bold text-slate-700">₹{{ number_format($existingQuote->other_charges, 2) }}</span>
                        </div>
                    </div>

                    @if($existingQuote->notes)
                        <div class="text-xs text-slate-600 bg-white p-3 rounded-xl border border-slate-200">
                            <strong>Technician Notes:</strong> {{ $existingQuote->notes }}
                        </div>
                    @endif
                </div>
            @else
                <!-- Submit Quote Form -->
                <form action="{{ route('vendor.jobs.quote', $serviceRequest->id) }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="labour_amount" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                Labour Amount (INR) <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" step="0.01" id="labour_amount" name="labour_amount" required value="{{ old('labour_amount', 0) }}" class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-brand-cyan text-sm font-medium text-slate-800">
                        </div>

                        <div>
                            <label for="materials_amount" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                Materials / Parts Amount (INR)
                            </label>
                            <input type="number" step="0.01" id="materials_amount" name="materials_amount" value="{{ old('materials_amount', 0) }}" class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-brand-cyan text-sm font-medium text-slate-800">
                        </div>

                        <div>
                            <label for="tax_amount" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                GST / Taxes (INR)
                            </label>
                            <input type="number" step="0.01" id="tax_amount" name="tax_amount" value="{{ old('tax_amount', 0) }}" class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-brand-cyan text-sm font-medium text-slate-800">
                        </div>

                        <div>
                            <label for="other_charges" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                Other Charges / Travel (INR)
                            </label>
                            <input type="number" step="0.01" id="other_charges" name="other_charges" value="{{ old('other_charges', 0) }}" class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-brand-cyan text-sm font-medium text-slate-800">
                        </div>
                    </div>

                    <div>
                        <label for="estimated_duration" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                            Estimated Duration <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="estimated_duration" name="estimated_duration" required placeholder="e.g. 2 Hours, 1 Day" class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-brand-cyan text-sm font-medium text-slate-800">
                    </div>

                    <div>
                        <label for="notes" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                            Scope Details &amp; Warranty Notes (Optional)
                        </label>
                        <textarea id="notes" name="notes" rows="3" placeholder="Describe parts being replaced, warranties provided, or work procedure..." class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-brand-cyan text-sm font-medium text-slate-800"></textarea>
                    </div>

                    <button type="submit" class="px-6 py-3 rounded-2xl bg-brand-cyan hover:bg-brand-cyan-hover text-brand-navy font-bold text-xs uppercase tracking-wider transition shadow-md">
                        Submit Quote to Customer
                    </button>
                </form>
            @endif
        </div>

        <!-- Work Order Execution Controls (When Quote is Approved) -->
        @if($serviceRequest->workOrder)
            @php $wo = $serviceRequest->workOrder; @endphp
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm mb-6">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <div>
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Approved Work Order</div>
                        <h3 class="text-lg font-bold text-brand-navy">#{{ $wo->work_order_number }}</h3>
                    </div>
                    <span class="px-3 py-1 rounded-xl text-xs font-black uppercase tracking-wider
                        @if($wo->status === 'QC_COMPLETED') bg-emerald-100 text-emerald-800
                        @elseif($wo->status === 'QC_PENDING') bg-amber-100 text-amber-800
                        @elseif($wo->status === 'IN_PROGRESS') bg-blue-100 text-blue-800
                        @else bg-slate-100 text-slate-700
                        @endif">
                        {{ str_replace('_', ' ', $wo->status) }}
                    </span>
                </div>

                @if($wo->status === 'SCHEDULED')
                    <div class="p-6 rounded-2xl bg-blue-50/60 border border-blue-200">
                        <h4 class="text-sm font-bold text-brand-navy mb-2">Ready to Start Work</h4>
                        <p class="text-xs text-slate-600 mb-4 leading-relaxed">
                            When you arrive on site, upload optional before photos and mark the work order as in-progress.
                        </p>

                        <form action="{{ route('vendor.work-orders.start', $wo->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                    Upload Before Photos (Optional)
                                </label>
                                <input type="file" name="before_photos[]" multiple accept="image/*" class="w-full text-xs text-slate-600">
                            </div>
                            <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-wider transition shadow-sm">
                                Start Work Order
                            </button>
                        </form>
                    </div>
                @elseif($wo->status === 'IN_PROGRESS')
                    <div class="p-6 rounded-2xl bg-amber-50/60 border border-amber-200">
                        <h4 class="text-sm font-bold text-brand-navy mb-2">Work In Progress &bull; Complete &amp; Submit for QC</h4>
                        <p class="text-xs text-slate-600 mb-4 leading-relaxed">
                            Once the repair or maintenance is complete, upload after photos and submit for customer quality review.
                        </p>

                        <form action="{{ route('vendor.work-orders.complete', $wo->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                    Upload After Photos (Proof of Completion)
                                </label>
                                <input type="file" name="after_photos[]" multiple accept="image/*" class="w-full text-xs text-slate-600">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                    Work Notes
                                </label>
                                <textarea name="work_notes" rows="2" placeholder="Summary of work completed..." class="w-full px-4 py-2 rounded-xl border border-slate-200 text-xs text-slate-800"></textarea>
                            </div>
                            <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs uppercase tracking-wider transition shadow-sm">
                                Complete Work &amp; Submit for QC
                            </button>
                        </form>
                    </div>
                @else
                    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600"></i>
                        <span>Work marked as completed. Awaiting client Quality Control sign-off.</span>
                    </div>
                @endif
            </div>
        @endif

    </div>
</section>

@endsection
