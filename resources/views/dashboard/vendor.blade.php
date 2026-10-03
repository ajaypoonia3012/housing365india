@extends('dashboard.layout')

@section('title', 'Service Vendor Workspace | Housing365 India')
@section('workspace_badge', 'VENDOR WORKSPACE')
@section('workspace_icon', 'fa-screwdriver-wrench')
@section('workspace_subtitle', 'Accept Housing365 Care service requests, submit estimates, deliver verified property services, and execute work orders.')

@section('workspace_header_actions')
    <a href="{{ route('vendor.jobs') }}" class="px-4 py-2 rounded-2xl bg-brand-cyan hover:bg-brand-cyan-hover text-brand-navy font-extrabold text-xs uppercase tracking-wider transition shadow-md shadow-brand-cyan/20 flex items-center gap-2">
        <i class="fa-solid fa-list-check"></i>
        <span>Manage Assigned Jobs</span>
    </a>
@endsection

@section('workspace_content')
<!-- Metrics -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Assigned Requests</span>
        <div class="text-2xl font-display font-extrabold text-brand-navy mt-1">{{ isset($assignedRequests) ? $assignedRequests->count() : 0 }}</div>
        <div class="text-[11px] text-slate-500 mt-1">Care requests in your queue</div>
    </div>

    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Active Work Orders</span>
        <div class="text-2xl font-display font-extrabold text-brand-navy mt-1">{{ isset($workOrders) ? $workOrders->count() : 0 }}</div>
        <div class="text-[11px] text-slate-500 mt-1">Scheduled &amp; in-progress</div>
    </div>

    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Completed Jobs</span>
        <div class="text-2xl font-display font-extrabold text-brand-navy mt-1">{{ $completedJobsCount ?? 0 }}</div>
        <div class="text-[11px] text-slate-500 mt-1">Delivered to customers</div>
    </div>

    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Partner Status</span>
        @if(isset($profile) && $profile->verification_status === 'verified')
            <div class="text-xs font-extrabold text-emerald-800 bg-emerald-50 border border-emerald-200 px-2 py-1 rounded-lg inline-block mt-2">
                VERIFIED PARTNER
            </div>
        @else
            <div class="text-xs font-extrabold text-amber-800 bg-amber-50 border border-amber-200 px-2 py-1 rounded-lg inline-block mt-2">
                ACTIVE VENDOR
            </div>
        @endif
        <div class="text-[11px] text-slate-500 mt-1">Rating: {{ $profile->rating ?? '5.0' }} / 5.0</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-6">
        <!-- Service Jobs Triage -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-brand-navy">Housing365 Care Job Requests</h2>
                <a href="{{ route('vendor.jobs') }}" class="text-xs font-bold text-brand-cyan hover:underline">
                    View All Jobs &rarr;
                </a>
            </div>

            @if(isset($assignedRequests) && $assignedRequests->isNotEmpty())
                <div class="divide-y divide-slate-100">
                    @foreach($assignedRequests as $req)
                        <div class="py-3.5 first:pt-0 last:pb-0 flex items-center justify-between gap-4">
                            <div>
                                <div class="text-xs font-bold text-brand-navy flex items-center gap-2">
                                    <span>{{ $req->title ?: $req->category->name }}</span>
                                    <span class="text-slate-400">#{{ $req->request_number }}</span>
                                </div>
                                <div class="text-[11px] text-slate-500 mt-0.5">
                                    {{ $req->category->name }} &bull; Priority: {{ ucfirst($req->priority) }} &bull; {{ $req->preferred_date ? $req->preferred_date->format('d M') : 'Flexible' }}
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider
                                    @if($req->status === 'AWAITING_APPROVAL') bg-amber-50 text-amber-800 border border-amber-200
                                    @elseif($req->status === 'IN_PROGRESS' || $req->status === 'SCHEDULED') bg-blue-50 text-blue-800 border border-blue-200
                                    @else bg-slate-100 text-slate-700
                                    @endif">
                                    {{ str_replace('_', ' ', $req->status) }}
                                </span>
                                <a href="{{ route('vendor.jobs.show', $req->id) }}" class="px-2.5 py-1 rounded-lg bg-brand-cyan hover:bg-brand-cyan-hover text-brand-navy text-xs font-bold transition">
                                    Review &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-8 text-center bg-slate-50/60 rounded-2xl border border-dashed border-slate-200">
                    <i class="fa-solid fa-clipboard-list text-slate-400 text-2xl mb-2"></i>
                    <h3 class="text-sm font-bold text-slate-800 mb-1">No Pending Service Requests</h3>
                    <p class="text-xs text-slate-500 max-w-md mx-auto mb-4">
                        Maintenance and care requests from landlords and tenants in your assigned coverage area will appear here.
                    </p>
                    <a href="{{ route('care') }}" class="px-4 py-2 rounded-xl bg-brand-cyan text-brand-navy text-xs font-bold uppercase tracking-wider transition">
                        View Supported Care Services
                    </a>
                </div>
            @endif
        </div>

        <!-- Quotes & Estimates -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <h2 class="text-base font-bold text-brand-navy mb-4">Quotes &amp; Estimates Performance</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-center text-xs">
                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200">
                    <div class="font-bold text-slate-700">Estimates Sent</div>
                    <div class="text-lg font-extrabold text-brand-navy mt-1">{{ $quotesCount ?? 0 }}</div>
                </div>
                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200">
                    <div class="font-bold text-slate-700">Accepted by Client</div>
                    <div class="text-lg font-extrabold text-brand-navy mt-1">{{ $acceptedQuotesCount ?? 0 }}</div>
                </div>
                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200">
                    <div class="font-bold text-slate-700">Jobs Executed</div>
                    <div class="text-lg font-extrabold text-brand-navy mt-1">{{ $completedJobsCount ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Col: Services & Operating Areas -->
    <div class="space-y-6">
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <h3 class="text-sm font-bold text-brand-navy mb-2">Partner Profile</h3>
            <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                Housing365 Care verified partner vendor profile.
            </p>
            <div class="space-y-2 text-xs text-slate-600 mb-4">
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span>Business Name:</span>
                    <span class="font-bold text-brand-navy">{{ $profile->business_name ?? Auth::user()->name }}</span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span>Contact Person:</span>
                    <span class="font-bold text-brand-navy">{{ Auth::user()->name }}</span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span>Base City:</span>
                    <span class="font-bold text-brand-navy">{{ Auth::user()->city ?? 'India' }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Vendor Rating:</span>
                    <span class="text-amber-500 font-bold">&#9733; {{ $profile->rating ?? '5.0' }}</span>
                </div>
            </div>
            <a href="{{ route('vendor.jobs') }}" class="block w-full py-2.5 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold uppercase tracking-wider text-center transition">
                Open Work Order Queue
            </a>
        </div>
    </div>
</div>
@endsection
