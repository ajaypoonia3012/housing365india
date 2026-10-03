@extends('layouts.app')

@section('title', 'Service Request #' . $serviceRequest->request_number . ' | Housing365 Care')

@section('content')

<!-- ── BREADCRUMB ── -->
<div class="bg-white border-b border-slate-100 py-3.5 text-sm text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-cyan transition font-medium">Home</a>
        <span class="text-slate-300">/</span>
        <a href="{{ route('care.index') }}" class="hover:text-brand-cyan transition font-medium">Housing365 Care</a>
        <span class="text-slate-300">/</span>
        <span class="text-brand-navy font-bold">Request #{{ $serviceRequest->request_number }}</span>
    </div>
</div>

<section class="py-12 sm:py-16 bg-slate-50/60 min-h-screen">
    <div class="mx-auto w-full max-w-5xl px-4 sm:px-8">
        
        <!-- Alerts -->
        @if(session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('info'))
            <div class="mb-6 p-4 rounded-2xl bg-blue-50 border border-blue-200 text-blue-800 text-xs font-semibold flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-blue-600"></i>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Main Header Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-xs font-extrabold uppercase tracking-wider text-brand-cyan bg-brand-cyan/10 px-3 py-1 rounded-full">
                            {{ $serviceRequest->category->name }}
                        </span>
                        <span class="text-xs font-bold text-slate-400">#{{ $serviceRequest->request_number }}</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-display font-extrabold text-brand-navy">
                        {{ $serviceRequest->title ?: $serviceRequest->category->name . ' Service' }}
                    </h1>
                    <div class="text-xs text-slate-500 mt-1 flex flex-wrap items-center gap-3">
                        <span>Submitted {{ $serviceRequest->created_at->format('d M Y, h:i A') }}</span>
                        <span>&bull;</span>
                        <span class="capitalize font-semibold text-slate-700">Priority: {{ $serviceRequest->priority }}</span>
                        @if($serviceRequest->property)
                            <span>&bull;</span>
                            <span class="font-semibold text-slate-700">Property: {{ $serviceRequest->property->title }}</span>
                        @endif
                    </div>
                </div>

                <div class="flex-shrink-0">
                    <span class="px-4 py-2 rounded-2xl text-xs font-black uppercase tracking-wider inline-flex items-center gap-1.5 shadow-sm
                        @if($serviceRequest->status === 'CLOSED' || $serviceRequest->status === 'QC_COMPLETED') bg-emerald-50 text-emerald-700 border border-emerald-200
                        @elseif($serviceRequest->status === 'IN_PROGRESS' || $serviceRequest->status === 'SCHEDULED') bg-blue-50 text-blue-700 border border-blue-200
                        @elseif($serviceRequest->status === 'AWAITING_APPROVAL') bg-amber-50 text-amber-800 border border-amber-300 animate-pulse
                        @elseif($serviceRequest->status === 'CANCELLED') bg-slate-100 text-slate-500 border border-slate-200
                        @else bg-slate-100 text-brand-navy border border-slate-200
                        @endif">
                        <span class="h-2 w-2 rounded-full @if($serviceRequest->status === 'CLOSED') bg-emerald-500 @else bg-brand-cyan @endif"></span>
                        <span>{{ str_replace('_', ' ', $serviceRequest->status) }}</span>
                    </span>
                </div>
            </div>

            <!-- Issue Description & Photos -->
            <div class="pt-6">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Issue Description</h3>
                <p class="text-sm text-slate-700 leading-relaxed bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    {{ $serviceRequest->description }}
                </p>

                @if(!empty($serviceRequest->photos) && is_array($serviceRequest->photos))
                    <div class="mt-4">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Attached Photos</h4>
                        <div class="flex flex-wrap gap-3">
                            @foreach($serviceRequest->photos as $photo)
                                <a href="{{ $photo }}" target="_blank" class="w-20 h-20 rounded-2xl overflow-hidden border border-slate-200 hover:opacity-90 transition block">
                                    <img src="{{ $photo }}" alt="Issue photo" class="w-full h-full object-cover">
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Assigned Vendor Card (Zero Personal Contact Leaks) -->
        @if($serviceRequest->assignedVendor)
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-bold text-brand-navy flex items-center gap-2">
                        <i class="fa-solid fa-user-gear text-brand-cyan"></i>
                        <span>Assigned Partner Vendor</span>
                    </h3>
                    <span class="text-xs font-bold text-emerald-600 bg-emerald-50 border border-emerald-200 px-3 py-1 rounded-full">
                        <i class="fa-solid fa-shield-halved mr-1"></i> Housing365 Verified
                    </span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-brand-cyan/15 text-brand-navy flex items-center justify-center font-display font-black text-xl">
                        {{ substr($serviceRequest->assignedVendor->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="text-sm font-bold text-brand-navy">
                            {{ $serviceRequest->assignedVendor->vendorProfile->business_name ?? $serviceRequest->assignedVendor->name }}
                        </div>
                        <div class="text-xs text-slate-500 mt-0.5 flex items-center gap-2">
                            <span>Rating: <strong class="text-amber-500 font-bold">&#9733; {{ $serviceRequest->assignedVendor->vendorProfile->rating ?? '5.0' }}</strong></span>
                            <span>&bull;</span>
                            <span>{{ $serviceRequest->assignedVendor->vendorProfile->jobs_completed ?? 0 }} Jobs Completed</span>
                        </div>
                        <div class="text-[11px] text-slate-400 mt-1">
                            Dispatched via Housing365 Operations Network
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Rework Notification Banner if Applicable -->
        @if($serviceRequest->status === 'REWORK_REQUIRED')
            <div class="mb-6 p-5 rounded-3xl bg-amber-50 border border-amber-300 text-amber-900 shadow-sm flex items-start gap-3">
                <i class="fa-solid fa-screwdriver-wrench text-amber-600 mt-1"></i>
                <div>
                    <h4 class="text-sm font-bold text-amber-950">Rework Requested (Cycle #{{ $serviceRequest->rework_count }})</h4>
                    <p class="text-xs text-amber-800 mt-0.5">
                        {{ $serviceRequest->rework_notes ?: 'Customer or Operations requested rework. The assigned technician is resolving the reported issues.' }}
                    </p>
                </div>
            </div>
        @endif

        <!-- Care Invoice Card if Generated -->
        @if($serviceRequest->latestInvoice)
            @php $inv = $serviceRequest->latestInvoice; @endphp
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                    <div>
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Housing365 Care Invoice</div>
                        <h3 class="text-xl font-display font-extrabold text-brand-navy mt-0.5">#{{ $inv->invoice_number }}</h3>
                        <div class="text-xs text-slate-500 mt-1">
                            Amount: <strong class="text-brand-navy font-bold text-sm">₹{{ number_format($inv->total_amount, 2) }}</strong>
                            &bull; Status:
                            <span class="font-bold @if($inv->payment_status === 'PAID') text-emerald-600 @else text-amber-600 @endif">
                                {{ $inv->payment_status }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <a href="{{ route('care.invoices.show', $inv->id) }}" class="px-5 py-2.5 rounded-xl bg-brand-navy hover:bg-brand-navy-light text-white font-bold text-xs uppercase tracking-wider transition inline-flex items-center gap-1.5 shadow-sm">
                            <i class="fa-solid fa-file-invoice"></i>
                            <span>View &amp; Settle Invoice</span>
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Quotes Section -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm mb-6">
            <h3 class="text-base font-bold text-brand-navy mb-4 flex items-center gap-2">
                <i class="fa-solid fa-receipt text-brand-cyan"></i>
                <span>Service Quotes &amp; Estimates</span>
            </h3>

            @if($serviceRequest->quotes->isNotEmpty())
                <div class="space-y-4">
                    @foreach($serviceRequest->quotes as $quote)
                        <div class="p-6 rounded-2xl border @if($quote->status === 'APPROVED') border-emerald-300 bg-emerald-50/30 @elseif($quote->status === 'PENDING') border-amber-300 bg-amber-50/20 @else border-slate-200 bg-slate-50/50 @endif">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
                                <div>
                                    <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Quote #{{ $quote->quote_number }}</div>
                                    <div class="text-2xl font-display font-extrabold text-brand-navy mt-1">
                                        ₹{{ number_format($quote->total_amount, 2) }}
                                    </div>
                                    <div class="text-xs text-slate-500 mt-0.5">
                                        Estimated Duration: <strong>{{ $quote->estimated_duration ?: '1-2 Days' }}</strong>
                                    </div>
                                </div>
                                <div>
                                    <span class="px-3 py-1 rounded-xl text-xs font-black uppercase tracking-wider
                                        @if($quote->status === 'APPROVED') bg-emerald-100 text-emerald-800
                                        @elseif($quote->status === 'PENDING') bg-amber-100 text-amber-800
                                        @else bg-slate-200 text-slate-700
                                        @endif">
                                        {{ $quote->status }}
                                    </span>
                                </div>
                            </div>

                            <!-- Cost Breakdown -->
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 py-4 text-xs">
                                <div>
                                    <span class="text-slate-400 block">Labour:</span>
                                    <span class="font-bold text-slate-700">₹{{ number_format($quote->labour_amount, 2) }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-400 block">Materials/Parts:</span>
                                    <span class="font-bold text-slate-700">₹{{ number_format($quote->materials_amount, 2) }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-400 block">Taxes:</span>
                                    <span class="font-bold text-slate-700">₹{{ number_format($quote->tax_amount, 2) }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-400 block">Other:</span>
                                    <span class="font-bold text-slate-700">₹{{ number_format($quote->other_charges, 2) }}</span>
                                </div>
                            </div>

                            @if($quote->notes)
                                <div class="text-xs text-slate-600 bg-white p-3 rounded-xl border border-slate-200 mb-4">
                                    <strong class="text-slate-700">Notes from Technician:</strong> {{ $quote->notes }}
                                </div>
                            @endif

                            <!-- Action Buttons if Pending Quote -->
                            @if($quote->status === 'PENDING')
                                <div class="pt-2 flex flex-wrap items-center gap-3">
                                    <form action="{{ route('care.quotes.approve', $quote->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Approve quote of ₹{{ number_format($quote->total_amount, 2) }}? This will issue the work order to the technician.')" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs uppercase tracking-wider transition shadow-sm flex items-center gap-1.5">
                                            <i class="fa-solid fa-check"></i>
                                            <span>Approve Quote &amp; Issue Work Order</span>
                                        </button>
                                    </form>

                                    <form action="{{ route('care.quotes.decline', $quote->id) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        <input type="text" name="reason" placeholder="Reason for declining..." class="text-xs px-3 py-2 rounded-xl border border-slate-300 w-44">
                                        <button type="submit" onclick="return confirm('Decline this quote?')" class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs uppercase tracking-wider transition">
                                            Decline
                                        </button>
                                    </form>
                                </div>
                            @elseif($quote->status === 'APPROVED')
                                <div class="text-xs font-semibold text-emerald-700 flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>Quote approved on {{ $quote->approved_at ? $quote->approved_at->format('d M Y, h:i A') : 'N/A' }}</span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-8 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200 text-slate-500">
                    <i class="fa-solid fa-hourglass-half text-2xl text-slate-400 mb-2"></i>
                    <div class="text-sm font-bold text-slate-700">Quote Pending</div>
                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                        Our assigned technician is reviewing your service details. An itemized quote will be posted here for your approval.
                    </p>
                </div>
            @endif
        </div>

        <!-- Work Order Execution Section -->
        @if($serviceRequest->workOrder)
            @php $wo = $serviceRequest->workOrder; @endphp
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm mb-6">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <div>
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Work Order</div>
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

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs mb-4">
                    <div>
                        <span class="text-slate-400 block">Work Started:</span>
                        <span class="font-bold text-slate-700">{{ $wo->started_at ? $wo->started_at->format('d M Y, h:i A') : 'Scheduled' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Work Completed:</span>
                        <span class="font-bold text-slate-700">{{ $wo->completed_at ? $wo->completed_at->format('d M Y, h:i A') : 'Pending Completion' }}</span>
                    </div>
                </div>

                <!-- Photos Evidence -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Before Photos</h4>
                        @if(!empty($wo->before_photos) && is_array($wo->before_photos))
                            <div class="flex flex-wrap gap-2">
                                @foreach($wo->before_photos as $bp)
                                    <a href="{{ $bp }}" target="_blank" class="w-16 h-16 rounded-xl overflow-hidden border border-slate-200 block">
                                        <img src="{{ $bp }}" alt="Before work" class="w-full h-full object-cover">
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-xs text-slate-400 italic">No before photos logged yet.</div>
                        @endif
                    </div>

                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">After Photos</h4>
                        @if(!empty($wo->after_photos) && is_array($wo->after_photos))
                            <div class="flex flex-wrap gap-2">
                                @foreach($wo->after_photos as $ap)
                                    <a href="{{ $ap }}" target="_blank" class="w-16 h-16 rounded-xl overflow-hidden border border-slate-200 block">
                                        <img src="{{ $ap }}" alt="After work" class="w-full h-full object-cover">
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-xs text-slate-400 italic">Photos will appear once work is marked complete.</div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Quality Control & Rating Review Section -->
        @if($serviceRequest->status === 'QC_PENDING' || $serviceRequest->status === 'QC_COMPLETED' || $serviceRequest->status === 'CLOSED')
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm">
                <h3 class="text-base font-bold text-brand-navy mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-star text-amber-400"></i>
                    <span>Quality Control &amp; Completion Review</span>
                </h3>

                @if($serviceRequest->status === 'QC_PENDING')
                    <p class="text-xs text-slate-600 mb-6">
                        The technician has finished the work and submitted photo documentation. Please verify the completed work and submit your review.
                    </p>

                    <form action="{{ route('care.requests.qc', $serviceRequest->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                    Verification Decision <span class="text-rose-500">*</span>
                                </label>
                                <select name="decision" required class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-brand-cyan text-sm font-medium text-slate-800 bg-white">
                                    <option value="approve">Approve Work &amp; Close Request (Work Completed Satisfactorily)</option>
                                    <option value="dispute">Dispute / Request Rework (Issue Not Fixed)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                    Rating (1 - 5 Stars)
                                </label>
                                <select name="rating" class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-brand-cyan text-sm font-medium text-slate-800 bg-white">
                                    <option value="5">&#9733;&#9733;&#9733;&#9733;&#9733; 5 Stars (Excellent)</option>
                                    <option value="4">&#9733;&#9733;&#9733;&#9733; 4 Stars (Good)</option>
                                    <option value="3">&#9733;&#9733;&#9733; 3 Stars (Satisfactory)</option>
                                    <option value="2">&#9733;&#9733; 2 Stars (Needs Improvement)</option>
                                    <option value="1">&#9733; 1 Star (Unsatisfactory)</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                                Review &amp; Quality Notes (Optional)
                            </label>
                            <textarea name="qc_notes" rows="3" placeholder="Add any comments about the technician's professionalism, cleanliness, or work quality..." class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-brand-cyan text-sm font-medium text-slate-800"></textarea>
                        </div>

                        <button type="submit" class="px-6 py-3 rounded-2xl bg-brand-cyan hover:bg-brand-cyan-hover text-brand-navy font-bold text-xs uppercase tracking-wider transition shadow-md">
                            Submit Quality Control Review
                        </button>
                    </form>
                @else
                    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 space-y-1">
                        <div class="font-bold flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i>
                            <span>Quality Control Verified &amp; Service Request Closed</span>
                        </div>
                        @if($serviceRequest->rating)
                            <div class="text-amber-500 font-bold mt-2">
                                Rating: {{ str_repeat('★', $serviceRequest->rating) }} ({{ $serviceRequest->rating }} / 5)
                            </div>
                        @endif
                        @if($serviceRequest->review)
                            <div class="text-slate-600 italic mt-1">"{{ $serviceRequest->review }}"</div>
                        @endif
                    </div>
                @endif
            </div>
        @endif

    </div>
</section>

@endsection
