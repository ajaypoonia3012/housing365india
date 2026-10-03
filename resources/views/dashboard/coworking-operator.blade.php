@extends('dashboard.layout')

@section('title', 'Coworking Space Operator Workspace | Housing365 India')
@section('workspace_badge', 'COWORKING OPERATOR WORKSPACE')
@section('workspace_icon', 'fa-laptop')
@section('workspace_subtitle', 'Manage shared hot desks, dedicated desks, private office cabins, enterprise teams, and meeting rooms.')

@section('workspace_header_actions')
    <a href="{{ route('properties.post') }}" class="px-4 py-2 rounded-2xl bg-brand-cyan hover:bg-brand-cyan-hover text-brand-navy font-extrabold text-xs uppercase tracking-wider transition shadow-md shadow-brand-cyan/20 flex items-center gap-2">
        <i class="fa-solid fa-plus"></i>
        <span>Add Coworking Center</span>
    </a>
@endsection

@section('workspace_content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Coworking Centers</span>
        <div class="text-2xl font-display font-extrabold text-brand-navy mt-1">0</div>
        <div class="text-[11px] text-slate-500 mt-1">Operating office locations</div>
    </div>

    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Flexible / Dedicated Desks</span>
        <div class="text-2xl font-display font-extrabold text-brand-navy mt-1">0</div>
        <div class="text-[11px] text-slate-500 mt-1">Available workstation seats</div>
    </div>

    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Private Cabins</span>
        <div class="text-2xl font-display font-extrabold text-brand-navy mt-1">0</div>
        <div class="text-[11px] text-slate-500 mt-1">Team suites (4-50 seater)</div>
    </div>

    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Meeting Rooms</span>
        <div class="text-2xl font-display font-extrabold text-brand-navy mt-1">0</div>
        <div class="text-[11px] text-slate-500 mt-1">Conference &amp; boardroom seats</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-brand-navy">Centers &amp; Office Spaces</h2>
                <a href="{{ route('properties.post') }}" class="text-xs font-bold text-brand-cyan hover:underline">+ New Center</a>
            </div>
            <div class="p-8 text-center bg-slate-50/60 rounded-2xl border border-dashed border-slate-200">
                <i class="fa-solid fa-laptop text-slate-400 text-2xl mb-2"></i>
                <h3 class="text-sm font-bold text-slate-800 mb-1">No Coworking Spaces Listed</h3>
                <p class="text-xs text-slate-500 max-w-md mx-auto mb-4">
                    List your commercial coworking hub, pricing per desk, day passes, and private cabin plans.
                </p>
                <a href="{{ route('properties.post') }}" class="px-4 py-2 rounded-xl bg-brand-cyan text-brand-navy text-xs font-bold uppercase tracking-wider transition">
                    + Post Coworking Center
                </a>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <h3 class="text-sm font-bold text-brand-navy mb-2">Center Amenities</h3>
            <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                Configure internet leased lines, biometric access, reception services, printing quotas, and cafeteria options.
            </p>
            <div class="space-y-2 text-xs text-slate-600 mb-4">
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span>Operator:</span>
                    <span class="font-bold text-brand-navy">{{ Auth::user()->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span>City:</span>
                    <span class="font-bold text-brand-navy">{{ Auth::user()->city ?? 'Mumbai' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
