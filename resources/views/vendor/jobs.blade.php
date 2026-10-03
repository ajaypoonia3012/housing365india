@extends('layouts.app')

@section('title', 'Assigned Service Jobs & Work Orders | Vendor Portal — Housing365 India')

@section('content')

<!-- ── BREADCRUMB ── -->
<div class="bg-white border-b border-slate-100 py-3.5 text-sm text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-cyan transition font-medium">Home</a>
        <span class="text-slate-300">/</span>
        <a href="{{ route('dashboard.vendor') }}" class="hover:text-brand-cyan transition font-medium">Vendor Workspace</a>
        <span class="text-slate-300">/</span>
        <span class="text-brand-navy font-bold">Assigned Jobs</span>
    </div>
</div>

<section class="py-12 sm:py-16 bg-slate-50/60 min-h-screen">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-brand-cyan">HOUSING365 PARTNER VENDOR NETWORK</span>
                <h1 class="text-3xl font-display font-extrabold text-brand-navy mt-1">Assigned Service Jobs</h1>
                <p class="text-sm text-slate-500 mt-1">Review customer requests, submit estimates, and execute work orders.</p>
            </div>
            <a href="{{ route('dashboard.vendor') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-xs font-bold text-slate-700 transition self-start sm:self-auto">
                &larr; Vendor Workspace
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Assigned Service Requests -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-brand-navy">Service Requests Needing Quote</h2>
                        <p class="text-xs text-slate-500">Assigned by Housing365 Operations for your area.</p>
                    </div>
                    <span class="text-xs font-extrabold text-brand-cyan bg-brand-cyan/10 px-3 py-1 rounded-full">
                        {{ $assignedRequests->total() }} Total
                    </span>
                </div>

                @if($assignedRequests->isNotEmpty())
                    <div class="divide-y divide-slate-100">
                        @foreach($assignedRequests as $req)
                            <div class="py-4 first:pt-0 last:pb-0">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="text-xs font-bold text-brand-cyan">{{ $req->category->name }}</span>
                                            <span class="text-xs text-slate-400">#{{ $req->request_number }}</span>
                                            @if($req->priority === 'emergency')
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-rose-50 text-rose-600 border border-rose-200">
                                                    EMERGENCY
                                                </span>
                                            @endif
                                        </div>
                                        <h3 class="text-sm font-bold text-brand-navy">
                                            <a href="{{ route('vendor.jobs.show', $req->id) }}" class="hover:text-brand-cyan transition">
                                                {{ $req->title ?: $req->category->name . ' Request' }}
                                            </a>
                                        </h3>
                                        <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $req->description }}</p>
                                        <div class="text-[11px] text-slate-400 mt-2 flex items-center gap-3">
                                            <span>Preferred Date: {{ $req->preferred_date ? $req->preferred_date->format('d M Y') : 'Flexible' }}</span>
                                            <span>&bull;</span>
                                            <span class="capitalize">{{ $req->preferred_time_window ?: 'Anytime' }}</span>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end gap-2 flex-shrink-0">
                                        <span class="px-2.5 py-1 rounded-xl text-[10px] font-black uppercase tracking-wider
                                            @if($req->status === 'AWAITING_APPROVAL') bg-amber-50 text-amber-800 border border-amber-200
                                            @elseif($req->status === 'IN_PROGRESS' || $req->status === 'SCHEDULED') bg-blue-50 text-blue-800 border border-blue-200
                                            @else bg-slate-100 text-slate-700
                                            @endif">
                                            {{ str_replace('_', ' ', $req->status) }}
                                        </span>
                                        <a href="{{ route('vendor.jobs.show', $req->id) }}" class="px-3 py-1.5 rounded-xl bg-brand-cyan hover:bg-brand-cyan-hover text-brand-navy text-xs font-bold transition shadow-sm">
                                            {{ $req->latestQuote ? 'View Quote' : 'Submit Quote' }} &rarr;
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        {{ $assignedRequests->links() }}
                    </div>
                @else
                    <div class="p-8 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200 text-slate-500">
                        <i class="fa-solid fa-clipboard-check text-2xl text-slate-400 mb-2"></i>
                        <div class="text-sm font-bold text-slate-700">No Assigned Jobs Pending</div>
                        <p class="text-xs text-slate-400 mt-1">New requests matching your category and service area will appear here automatically.</p>
                    </div>
                @endif
            </div>

            <!-- Active Work Orders -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-brand-navy">Approved Work Orders</h2>
                        <p class="text-xs text-slate-500">Customer-approved quotes ready for execution.</p>
                    </div>
                    <span class="text-xs font-extrabold text-emerald-600 bg-emerald-50 border border-emerald-200 px-3 py-1 rounded-full">
                        {{ $workOrders->total() }} Active
                    </span>
                </div>

                @if($workOrders->isNotEmpty())
                    <div class="divide-y divide-slate-100">
                        @foreach($workOrders as $wo)
                            <div class="py-4 first:pt-0 last:pb-0">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">
                                            Work Order #{{ $wo->work_order_number }}
                                        </div>
                                        <h3 class="text-sm font-bold text-brand-navy">
                                            <a href="{{ route('vendor.jobs.show', $wo->service_request_id) }}" class="hover:text-brand-cyan transition">
                                                {{ $wo->serviceRequest->title ?: $wo->serviceRequest->category->name }}
                                            </a>
                                        </h3>
                                        <div class="text-xs text-slate-600 mt-1">
                                            Approved Amount: <strong class="text-brand-navy font-bold">₹{{ number_format($wo->quote->total_amount, 2) }}</strong>
                                        </div>
                                        <div class="text-[11px] text-slate-400 mt-1">
                                            {{ $wo->property ? $wo->property->city : 'Customer Site' }}
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end gap-2 flex-shrink-0">
                                        <span class="px-2.5 py-1 rounded-xl text-[10px] font-black uppercase tracking-wider
                                            @if($wo->status === 'QC_COMPLETED') bg-emerald-100 text-emerald-800
                                            @elseif($wo->status === 'QC_PENDING') bg-amber-100 text-amber-800
                                            @elseif($wo->status === 'IN_PROGRESS') bg-blue-100 text-blue-800
                                            @else bg-slate-100 text-slate-700
                                            @endif">
                                            {{ str_replace('_', ' ', $wo->status) }}
                                        </span>
                                        <a href="{{ route('vendor.jobs.show', $wo->service_request_id) }}" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                                            Manage Work &rarr;
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        {{ $workOrders->links() }}
                    </div>
                @else
                    <div class="p-8 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200 text-slate-500">
                        <i class="fa-solid fa-wrench text-2xl text-slate-400 mb-2"></i>
                        <div class="text-sm font-bold text-slate-700">No Active Work Orders</div>
                        <p class="text-xs text-slate-400 mt-1">Once a customer approves your quote, an approved work order will appear here.</p>
                    </div>
                @endif
            </div>

        </div>

    </div>
</section>

@endsection
