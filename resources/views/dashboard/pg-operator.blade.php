@extends('dashboard.layout')

@section('title', 'PG Operator Workspace | Housing365 India')
@section('workspace_badge', 'PG OPERATOR WORKSPACE')
@section('workspace_icon', 'fa-bed')
@section('workspace_subtitle', 'Manage Paying Guest properties, room sharing (single/double/triple), meals, student & professional tenants.')

@section('workspace_header_actions')
    <a href="{{ route('properties.post') }}" class="px-4 py-2 rounded-2xl bg-brand-cyan hover:bg-brand-cyan-hover text-brand-navy font-extrabold text-xs uppercase tracking-wider transition shadow-md shadow-brand-cyan/20 flex items-center gap-2">
        <i class="fa-solid fa-plus"></i>
        <span>Add PG Property</span>
    </a>
@endsection

@section('workspace_content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">PG Hostels</span>
        <div class="text-2xl font-display font-extrabold text-brand-navy mt-1">0</div>
        <div class="text-[11px] text-slate-500 mt-1">Boys, Girls &amp; Unisex buildings</div>
    </div>

    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Available Beds</span>
        <div class="text-2xl font-display font-extrabold text-brand-navy mt-1">0</div>
        <div class="text-[11px] text-slate-500 mt-1">Single, Double &amp; Triple occupancy</div>
    </div>

    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Active Residents</span>
        <div class="text-2xl font-display font-extrabold text-brand-navy mt-1">0</div>
        <div class="text-[11px] text-slate-500 mt-1">Verified student/working professionals</div>
    </div>

    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Occupancy Rate</span>
        <div class="text-2xl font-display font-extrabold text-brand-navy mt-1">0%</div>
        <div class="text-[11px] text-slate-500 mt-1">Ready for Phase 7 inventory engine</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-brand-navy">PG Accommodation Inventory</h2>
                <a href="{{ route('properties.post') }}" class="text-xs font-bold text-brand-cyan hover:underline">+ New PG Space</a>
            </div>
            <div class="p-8 text-center bg-slate-50/60 rounded-2xl border border-dashed border-slate-200">
                <i class="fa-solid fa-bed text-slate-400 text-2xl mb-2"></i>
                <h3 class="text-sm font-bold text-slate-800 mb-1">No PG Spaces Listed Yet</h3>
                <p class="text-xs text-slate-500 max-w-md mx-auto mb-4">
                    List your PG hostel, define rules, food inclusions, WiFi, and air conditioning to receive verified inquiries.
                </p>
                <a href="{{ route('properties.post') }}" class="px-4 py-2 rounded-xl bg-brand-cyan text-brand-navy text-xs font-bold uppercase tracking-wider transition">
                    + Post PG Property
                </a>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <h3 class="text-sm font-bold text-brand-navy mb-2">PG Operator Profile</h3>
            <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                Configure food policy (veg/non-veg/both), gate closing timings, visitor policy, and security deposit terms.
            </p>
            <div class="space-y-2 text-xs text-slate-600 mb-4">
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span>Operator:</span>
                    <span class="font-bold text-brand-navy">{{ Auth::user()->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span>City:</span>
                    <span class="font-bold text-brand-navy">{{ Auth::user()->city ?? 'Jaipur' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
