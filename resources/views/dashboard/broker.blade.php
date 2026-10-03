@extends('dashboard.layout')

@section('title', 'Broker Workspace | Housing365 India')
@section('workspace_badge', 'BROKER WORKSPACE')
@section('workspace_icon', 'fa-briefcase')
@section('workspace_subtitle', 'Manage client mandates, verified leads, scheduled viewings, deal pipeline, and commission tracking.')

@section('workspace_header_actions')
    <a href="{{ route('properties.post') }}" class="px-4 py-2 rounded-2xl bg-brand-cyan hover:bg-brand-cyan-hover text-brand-navy font-extrabold text-xs uppercase tracking-wider transition shadow-md shadow-brand-cyan/20 flex items-center gap-2">
        <i class="fa-solid fa-plus"></i>
        <span>Add Client Listing</span>
    </a>
@endsection

@section('workspace_content')
<!-- Metrics Overview (Honest empty state foundation) -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Active Mandates</span>
            <div class="w-8 h-8 rounded-xl bg-indigo-500/10 text-indigo-600 flex items-center justify-center text-xs">
                <i class="fa-solid fa-handshake"></i>
            </div>
        </div>
        <div class="text-2xl font-display font-extrabold text-brand-navy">0</div>
        <div class="text-[11px] text-slate-500 mt-1">Exclusive &amp; shared mandates</div>
    </div>

    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Active Leads</span>
            <div class="w-8 h-8 rounded-xl bg-blue-500/10 text-brand-navy flex items-center justify-center text-xs">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
        <div class="text-2xl font-display font-extrabold text-brand-navy">0</div>
        <div class="text-[11px] text-slate-500 mt-1">Buyer &amp; tenant enquiries</div>
    </div>

    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Scheduled Viewings</span>
            <div class="w-8 h-8 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center text-xs">
                <i class="fa-solid fa-calendar-days"></i>
            </div>
        </div>
        <div class="text-2xl font-display font-extrabold text-brand-navy">0</div>
        <div class="text-[11px] text-slate-500 mt-1">Site visits coordinated</div>
    </div>

    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">RERA Status</span>
            <div class="w-8 h-8 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center text-xs">
                <i class="fa-solid fa-certificate"></i>
            </div>
        </div>
        <div class="text-xs font-extrabold text-amber-800 bg-amber-50 border border-amber-200 px-2 py-1 rounded-lg inline-block">PENDING SUBMISSION</div>
        <div class="text-[11px] text-slate-500 mt-1">Submit State RERA registration</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-6">
        <!-- Mandates & Listings -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-brand-navy">Broker Client Listings</h2>
                <a href="{{ route('properties.post') }}" class="text-xs font-bold text-brand-cyan hover:underline">+ New Client Listing</a>
            </div>
            <div class="p-8 text-center bg-slate-50/60 rounded-2xl border border-dashed border-slate-200">
                <i class="fa-solid fa-briefcase text-slate-400 text-2xl mb-2"></i>
                <h3 class="text-sm font-bold text-slate-800 mb-1">No Active Listings Under Mandate</h3>
                <p class="text-xs text-slate-500 max-w-md mx-auto mb-4">
                    List properties for your homeowner or commercial clients on Housing365 with professional verification badges.
                </p>
                <a href="{{ route('properties.post') }}" class="px-4 py-2 rounded-xl bg-brand-cyan text-brand-navy text-xs font-bold uppercase tracking-wider transition">
                    + Post Client Property
                </a>
            </div>
        </div>

        <!-- Deal Pipeline -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <h2 class="text-base font-bold text-brand-navy mb-4">Deal Pipeline &amp; Transactions</h2>
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-center text-xs">
                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200">
                    <div class="font-bold text-slate-700">New Inquiry</div>
                    <div class="text-lg font-extrabold text-brand-navy mt-1">0</div>
                </div>
                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200">
                    <div class="font-bold text-slate-700">Site Visit</div>
                    <div class="text-lg font-extrabold text-brand-navy mt-1">0</div>
                </div>
                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200">
                    <div class="font-bold text-slate-700">Negotiation</div>
                    <div class="text-lg font-extrabold text-brand-navy mt-1">0</div>
                </div>
                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200">
                    <div class="font-bold text-slate-700">Closed Deal</div>
                    <div class="text-lg font-extrabold text-brand-navy mt-1">0</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Col: Profile & Organization Setup -->
    <div class="space-y-6">
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <h3 class="text-sm font-bold text-brand-navy mb-2">Broker Profile &amp; KYC</h3>
            <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                Complete your broker profile with State RERA registration, operating localities, and agency details to receive verified buyer mandates.
            </p>
            <div class="space-y-2 text-xs text-slate-600 mb-4">
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span>Operating City:</span>
                    <span class="font-bold text-brand-navy">{{ Auth::user()->city ?? 'Jaipur' }}</span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span>Agency / Firm:</span>
                    <span class="font-bold text-slate-500">Individual Agent</span>
                </div>
                <div class="flex justify-between">
                    <span>Verification:</span>
                    <span class="text-amber-600 font-bold">Level 1 (Basic)</span>
                </div>
            </div>
            <button type="button" class="w-full py-2.5 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold uppercase tracking-wider transition">
                Submit RERA Certificate
            </button>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <h3 class="text-sm font-bold text-brand-navy mb-2">Commission &amp; Invoicing</h3>
            <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                Track client agreement generation and generate formal broker fee invoices for landlord or tenant settlements.
            </p>
            <span class="text-xs text-slate-400 font-medium">Invoicing module ready for Phase 6.</span>
        </div>
    </div>
</div>
@endsection
