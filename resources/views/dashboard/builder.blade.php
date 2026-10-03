@extends('dashboard.layout')

@section('title', 'Builder / Developer Workspace | Housing365 India')
@section('workspace_badge', 'BUILDER WORKSPACE')
@section('workspace_icon', 'fa-city')
@section('workspace_subtitle', 'Manage residential projects, commercial developments, tower unit inventory, RERA compliance, and buyer site visits.')

@section('workspace_header_actions')
    <a href="{{ route('contact') }}" class="px-4 py-2 rounded-2xl bg-brand-cyan hover:bg-brand-cyan-hover text-brand-navy font-extrabold text-xs uppercase tracking-wider transition shadow-md shadow-brand-cyan/20 flex items-center gap-2">
        <i class="fa-solid fa-plus"></i>
        <span>Register New Project</span>
    </a>
@endsection

@section('workspace_content')
<!-- Metric Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Active Projects</span>
            <div class="w-8 h-8 rounded-xl bg-orange-500/10 text-orange-600 flex items-center justify-center text-xs">
                <i class="fa-solid fa-hotel"></i>
            </div>
        </div>
        <div class="text-2xl font-display font-extrabold text-brand-navy">0</div>
        <div class="text-[11px] text-slate-500 mt-1">Under construction &amp; ready to move</div>
    </div>

    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Available Units</span>
            <div class="w-8 h-8 rounded-xl bg-blue-500/10 text-brand-navy flex items-center justify-center text-xs">
                <i class="fa-solid fa-layer-group"></i>
            </div>
        </div>
        <div class="text-2xl font-display font-extrabold text-brand-navy">0</div>
        <div class="text-[11px] text-slate-500 mt-1">Towers, floors &amp; configurations</div>
    </div>

    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Site Visits</span>
            <div class="w-8 h-8 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center text-xs">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
        </div>
        <div class="text-2xl font-display font-extrabold text-brand-navy">0</div>
        <div class="text-[11px] text-slate-500 mt-1">Customer walkthroughs requested</div>
    </div>

    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">RERA Registered</span>
            <div class="w-8 h-8 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center text-xs">
                <i class="fa-solid fa-stamp"></i>
            </div>
        </div>
        <div class="text-xs font-extrabold text-slate-700 bg-slate-100 px-2 py-1 rounded-lg inline-block">SETUP REQUIRED</div>
        <div class="text-[11px] text-slate-500 mt-1">State Real Estate Regulatory Authority</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-6">
        <!-- Projects Section -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-brand-navy">Registered Projects</h2>
                <a href="{{ route('contact') }}" class="text-xs font-bold text-brand-cyan hover:underline">+ New Project</a>
            </div>
            <div class="p-8 text-center bg-slate-50/60 rounded-2xl border border-dashed border-slate-200">
                <i class="fa-solid fa-city text-slate-400 text-2xl mb-2"></i>
                <h3 class="text-sm font-bold text-slate-800 mb-1">No Projects Onboarded Yet</h3>
                <p class="text-xs text-slate-500 max-w-md mx-auto mb-4">
                    Showcase township, luxury residential, or commercial projects to millions of home seekers across India.
                </p>
                <a href="{{ route('contact') }}" class="px-4 py-2 rounded-xl bg-brand-cyan text-brand-navy text-xs font-bold uppercase tracking-wider transition">
                    Submit Project for Housing365 Verification
                </a>
            </div>
        </div>

        <!-- Sales Pipeline -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <h2 class="text-base font-bold text-brand-navy mb-4">Builder Lead &amp; Booking Pipeline</h2>
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-center text-xs">
                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200">
                    <div class="font-bold text-slate-700">Digital Inquiries</div>
                    <div class="text-lg font-extrabold text-brand-navy mt-1">0</div>
                </div>
                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200">
                    <div class="font-bold text-slate-700">Sample Flat Visits</div>
                    <div class="text-lg font-extrabold text-brand-navy mt-1">0</div>
                </div>
                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200">
                    <div class="font-bold text-slate-700">EOI / Token</div>
                    <div class="text-lg font-extrabold text-brand-navy mt-1">0</div>
                </div>
                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200">
                    <div class="font-bold text-slate-700">Allotment</div>
                    <div class="text-lg font-extrabold text-brand-navy mt-1">0</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Col: Builder Profile & RERA -->
    <div class="space-y-6">
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <h3 class="text-sm font-bold text-brand-navy mb-2">Developer Profile</h3>
            <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                Set up your development company profile, operating cities, headquarters, and RERA registration ID.
            </p>
            <div class="space-y-2 text-xs text-slate-600 mb-4">
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span>Developer Entity:</span>
                    <span class="font-bold text-brand-navy">{{ Auth::user()->name }}</span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span>Operating City:</span>
                    <span class="font-bold text-brand-navy">{{ Auth::user()->city ?? 'India' }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Verification:</span>
                    <span class="text-amber-600 font-bold">Unverified</span>
                </div>
            </div>
            <a href="{{ route('contact') }}" class="block w-full py-2.5 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold uppercase tracking-wider text-center transition">
                Developer Compliance Setup
            </a>
        </div>
    </div>
</div>
@endsection
