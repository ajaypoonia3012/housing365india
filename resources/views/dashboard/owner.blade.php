@extends('dashboard.layout')

@section('title', 'Owner Workspace | Housing365 India')
@section('workspace_badge', 'OWNER WORKSPACE')
@section('workspace_icon', 'fa-key')
@section('workspace_subtitle', 'Manage free rental & sale listings, tenant leads, digital agreements, and optional property management.')

@section('workspace_header_actions')
    <a href="{{ route('properties.post') }}" class="px-4 py-2 rounded-2xl bg-brand-cyan hover:bg-brand-cyan-hover text-brand-navy font-extrabold text-xs uppercase tracking-wider transition shadow-md shadow-brand-cyan/20 flex items-center gap-2">
        <i class="fa-solid fa-plus"></i>
        <span>List Property Free</span>
    </a>
@endsection

@section('workspace_content')
<!-- Metric Cards (Honest Real Counts) -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">My Properties</span>
            <div class="w-8 h-8 rounded-xl bg-brand-cyan/10 text-brand-navy flex items-center justify-center text-xs">
                <i class="fa-solid fa-building"></i>
            </div>
        </div>
        <div class="text-2xl font-display font-extrabold text-brand-navy">{{ $properties->count() }}</div>
        <div class="text-[11px] text-slate-500 mt-1">₹0 listing fee on Housing365</div>
    </div>

    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Verified Leads</span>
            <div class="w-8 h-8 rounded-xl bg-blue-500/10 text-brand-navy flex items-center justify-center text-xs">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
        <div class="text-2xl font-display font-extrabold text-brand-navy">{{ $leadsCount ?? 0 }} Leads</div>
        <div class="text-[11px] text-slate-500 mt-1"><a href="{{ route('crm.leads.index') }}" class="text-[#00AEEF] font-semibold hover:underline">View CRM Pipeline &rarr;</a></div>
    </div>

    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Agreements</span>
            <div class="w-8 h-8 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center text-xs">
                <i class="fa-solid fa-file-contract"></i>
            </div>
        </div>
        <div class="text-2xl font-display font-extrabold text-brand-navy">0</div>
        <div class="text-[11px] text-slate-500 mt-1">Digital e-stamping assistance ready</div>
    </div>

    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Property Management</span>
            <div class="w-8 h-8 rounded-xl bg-purple-500/10 text-purple-600 flex items-center justify-center text-xs">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
        </div>
        @php
            $activeEnrollmentsCount = $enrollments->where('status', 'ACTIVE')->count();
        @endphp
        @if($activeEnrollmentsCount > 0)
            <div class="text-2xl font-display font-extrabold text-brand-navy">{{ $activeEnrollmentsCount }} Active</div>
            <div class="text-[11px] text-emerald-600 font-semibold mt-1">Properties managed by Housing365</div>
        @else
            <div class="text-xs font-extrabold text-slate-700 bg-slate-100 px-2 py-1 rounded-lg inline-block">OPTIONAL (Not Enrolled)</div>
            <div class="text-[11px] text-slate-500 mt-1">Path A &bull; Self-managed ₹0</div>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left 2 Cols: Properties & Operations -->
    <div class="lg:col-span-2 space-y-8">
        
        <!-- My Properties Section -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold text-brand-navy">My Listed Properties</h2>
                    <p class="text-xs text-slate-500">Directly associated with your verified phone and email.</p>
                </div>
                <a href="{{ route('properties.post') }}" class="text-xs font-bold text-brand-cyan hover:underline">
                    + Add New Listing
                </a>
            </div>

            @if($properties->count() > 0)
                <div class="divide-y divide-slate-100">
                    @foreach($properties as $property)
                        <div class="py-4 first:pt-0 last:pb-0 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-2xl bg-slate-100 overflow-hidden flex-shrink-0 relative">
                                    @if(!empty($property->image_url))
                                        <img src="{{ $property->image_url }}" alt="{{ $property->title }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-400">
                                            <i class="fa-solid fa-house text-lg"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-brand-navy hover:text-brand-cyan transition line-clamp-1">
                                        <a href="{{ route('properties.show', $property->slug) }}">{{ $property->title }}</a>
                                    </h3>
                                    <div class="text-xs text-slate-500 mt-0.5 flex items-center gap-2">
                                        <span>{{ $property->city ?? 'India' }}</span>
                                        <span>&bull;</span>
                                        <span class="font-bold text-brand-navy">₹{{ number_format($property->price) }}/mo</span>
                                    </div>
                                    <div class="mt-1 flex items-center gap-1.5 flex-wrap">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Active Listing
                                        </span>
                                        @if(isset($enrollments[$property->id]) && $enrollments[$property->id]->status === 'ACTIVE')
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-purple-50 text-purple-700 border border-purple-200">
                                                <i class="fa-solid fa-shield-check mr-0.5"></i> Managed ({{ $enrollments[$property->id]->plan->name }})
                                            </span>
                                        @elseif(isset($enrollments[$property->id]) && $enrollments[$property->id]->status === 'AGREEMENT_PENDING')
                                            <a href="{{ route('management.agreement', $enrollments[$property->id]->id) }}" class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-50 text-amber-800 border border-amber-300 hover:bg-amber-100">
                                                <i class="fa-solid fa-clock mr-0.5"></i> Sign Agreement
                                            </a>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200">
                                                Leasing Only (₹0)
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                @if(!isset($enrollments[$property->id]) || $enrollments[$property->id]->status === 'CANCELLED')
                                    <a href="{{ route('management.explore', $property->id) }}" class="px-3 py-1.5 rounded-xl bg-purple-50 hover:bg-purple-100 text-xs font-bold text-purple-700 border border-purple-200 transition">
                                        + Add Management
                                    </a>
                                @else
                                    <a href="{{ route('management.explore', $property->id) }}" class="px-3 py-1.5 rounded-xl border border-purple-200 hover:border-purple-300 text-xs font-bold text-purple-700 transition">
                                        Management Settings
                                    </a>
                                @endif
                                <a href="{{ route('properties.show', $property->slug) }}" class="px-3 py-1.5 rounded-xl border border-slate-200 hover:border-brand-cyan text-xs font-bold text-slate-700 hover:text-brand-navy transition">
                                    View
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-8 text-center bg-slate-50/60 rounded-2xl border border-dashed border-slate-200">
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3 text-lg">
                        <i class="fa-solid fa-house"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800 mb-1">No Properties Listed Yet</h3>
                    <p class="text-xs text-slate-500 max-w-md mx-auto mb-4">
                        Post your residential apartment, villa, or commercial property with zero listing fee. Reach genuine tenants without broker spam.
                    </p>
                    <a href="{{ route('properties.post') }}" class="px-4 py-2 rounded-xl bg-brand-cyan text-brand-navy text-xs font-bold uppercase tracking-wider shadow-sm hover:bg-brand-cyan-hover transition">
                        + List my Property — Free
                    </a>
                </div>
            @endif
        </div>

        <!-- Housing365 Care Maintenance Requests -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold text-brand-navy">Housing365 Care Service Requests</h2>
                    <p class="text-xs text-slate-500">Maintenance, deep cleaning, painting &amp; repairs for your properties.</p>
                </div>
                <a href="{{ route('care.request') }}" class="text-xs font-bold text-brand-cyan hover:underline">
                    + Book Care Service
                </a>
            </div>

            @if(isset($careRequests) && $careRequests->isNotEmpty())
                <div class="divide-y divide-slate-100">
                    @foreach($careRequests as $cr)
                        <div class="py-3.5 first:pt-0 last:pb-0 flex items-center justify-between gap-4">
                            <div>
                                <div class="text-xs font-bold text-brand-navy flex items-center gap-2">
                                    <span>{{ $cr->title ?: $cr->category->name }}</span>
                                    <span class="text-slate-400">#{{ $cr->request_number }}</span>
                                </div>
                                <div class="text-[11px] text-slate-500 mt-0.5">
                                    {{ $cr->category->name }} &bull; {{ $cr->created_at->format('d M Y') }} &bull; Priority: {{ ucfirst($cr->priority) }}
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider
                                    @if($cr->status === 'CLOSED') bg-emerald-50 text-emerald-700 border border-emerald-200
                                    @elseif($cr->status === 'AWAITING_APPROVAL') bg-amber-50 text-amber-800 border border-amber-300
                                    @else bg-blue-50 text-blue-700 border border-blue-200
                                    @endif">
                                    {{ str_replace('_', ' ', $cr->status) }}
                                </span>
                                <a href="{{ route('care.requests.show', $cr->id) }}" class="px-2.5 py-1 rounded-lg border border-slate-200 hover:border-brand-cyan text-xs font-bold text-slate-700">
                                    View
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-6 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200 text-slate-500">
                    <p class="text-xs text-slate-500 mb-2">No maintenance or care requests active.</p>
                    <a href="{{ route('care.request') }}" class="text-xs font-bold text-brand-cyan hover:underline">
                        Book cleaning, painting, or repairs &rarr;
                    </a>
                </div>
            @endif
        </div>

        <!-- Tenant Verification & Digital Rent Agreements -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
                <div class="w-10 h-10 rounded-2xl bg-blue-500/10 text-brand-navy flex items-center justify-center text-sm mb-4">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
                <h3 class="text-sm font-bold text-brand-navy mb-1">Tenant Verification</h3>
                <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                    Identity check, police verification coordination, and court record checks before handing over keys.
                </p>
                <a href="{{ route('services.show', 'tenant-verification') }}" class="text-xs font-bold text-brand-cyan hover:underline">
                    Request Verification &rarr;
                </a>
            </div>

            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
                <div class="w-10 h-10 rounded-2xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center text-sm mb-4">
                    <i class="fa-solid fa-stamp"></i>
                </div>
                <h3 class="text-sm font-bold text-brand-navy mb-1">11-Month Rent Agreement</h3>
                <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                    Legally vetted 11-month rent agreements with official state e-stamping and biometric police verification assistance.
                </p>
                <a href="{{ route('services.show', 'rent-agreement') }}" class="text-xs font-bold text-brand-cyan hover:underline">
                    Draft Agreement &rarr;
                </a>
            </div>
        </div>

    </div>

    <!-- Right Col: Optional Property Management & Care -->
    <div class="space-y-6">
        
        <!-- Optional Property Management Card -->
        <div class="bg-gradient-to-br from-[#03234B] to-[#0A3D78] rounded-3xl p-6 text-white border border-white/10 shadow-lg relative overflow-hidden">
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-400 text-slate-950 text-[10px] font-black uppercase tracking-wider mb-3">
                OPTIONAL SERVICE
            </div>
            <h3 class="text-base font-bold text-white mb-1">Housing365 Property Management</h3>
            <p class="text-xs text-white/70 leading-relaxed mb-4">
                Hands-off leasing, guaranteed tenant placement, on-demand maintenance, and automated rent collection. 100% optional — self-listing is always free.
            </p>
            <ul class="text-xs text-white/80 space-y-2 mb-6">
                <li class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-brand-cyan text-[11px]"></i>
                    <span>Dedicated Property Manager</span>
                </li>
                <li class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-brand-cyan text-[11px]"></i>
                    <span>Pre-occupancy & move-out inspection</span>
                </li>
                <li class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-brand-cyan text-[11px]"></i>
                    <span>Clear, transparent monthly fee (opt-in only)</span>
                </li>
            </ul>
            <a href="{{ route('property-managers') }}" class="block w-full py-2.5 px-4 rounded-xl bg-brand-cyan hover:bg-brand-cyan-hover text-brand-navy font-bold text-xs uppercase tracking-wider text-center transition">
                Learn About Management &rarr;
            </a>
        </div>

        <!-- Housing365 Care Quick Trigger -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <div class="w-10 h-10 rounded-2xl bg-cyan-500/10 text-brand-cyan flex items-center justify-center text-sm mb-3">
                <i class="fa-solid fa-screwdriver-wrench"></i>
            </div>
            <h3 class="text-sm font-bold text-brand-navy mb-1">Housing365 Care</h3>
            <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                Book professional deep cleaning, painting, AC maintenance, or plumbing for your properties before the new tenant moves in.
            </p>
            <a href="{{ route('care') }}" class="text-xs font-bold text-brand-cyan hover:underline">
                Explore Care Services &rarr;
            </a>
        </div>

    </div>
</div>
@endsection
