@extends('dashboard.layout')

@section('title', 'Buyer Workspace | Housing365 India')
@section('workspace_badge', 'BUYER WORKSPACE')
@section('workspace_icon', 'fa-money-bill-wave')
@section('workspace_subtitle', 'Discover verified apartments, villas, plots, and commercial assets for purchase across top Indian cities.')

@section('workspace_header_actions')
    <a href="{{ route('properties.index') }}" class="px-4 py-2 rounded-2xl bg-brand-cyan hover:bg-brand-cyan-hover text-brand-navy font-extrabold text-xs uppercase tracking-wider transition shadow-md shadow-brand-cyan/20 flex items-center gap-2">
        <i class="fa-solid fa-magnifying-glass"></i>
        <span>Explore Properties to Buy</span>
    </a>
@endsection

@section('workspace_content')
<!-- Metrics -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Saved Properties</span>
        <div class="text-2xl font-display font-extrabold text-brand-navy mt-1">0</div>
        <div class="text-[11px] text-slate-500 mt-1">Bookmarked listings</div>
    </div>

    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Purchase Inquiries</span>
        <div class="text-2xl font-display font-extrabold text-brand-navy mt-1">0</div>
        <div class="text-[11px] text-slate-500 mt-1">Direct seller &amp; builder queries</div>
    </div>

    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Site Visits</span>
        <div class="text-2xl font-display font-extrabold text-brand-navy mt-1">0</div>
        <div class="text-[11px] text-slate-500 mt-1">Physical walkthroughs arranged</div>
    </div>

    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Offers / Expressions of Interest</span>
        <div class="text-2xl font-display font-extrabold text-brand-navy mt-1">0</div>
        <div class="text-[11px] text-slate-500 mt-1">Formal price proposals</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm">
            <h2 class="text-lg font-bold text-brand-navy mb-4">Saved Properties &amp; Wishlist</h2>
            <div class="p-8 text-center bg-slate-50/60 rounded-2xl border border-dashed border-slate-200">
                <i class="fa-solid fa-heart text-slate-400 text-2xl mb-2"></i>
                <h3 class="text-sm font-bold text-slate-800 mb-1">No Saved Properties</h3>
                <p class="text-xs text-slate-500 max-w-md mx-auto mb-4">
                    Browse resale homes, new builder projects, and verified plots to build your shortlist.
                </p>
                <a href="{{ route('properties.index') }}" class="px-4 py-2 rounded-xl bg-brand-cyan text-brand-navy text-xs font-bold uppercase tracking-wider transition">
                    Explore Buy Marketplace &rarr;
                </a>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <h2 class="text-base font-bold text-brand-navy mb-2">Home Loan &amp; Legal Title Verification</h2>
            <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                Housing365 coordinates 30-year title deed checks, encumbrance certificates (EC), and municipal approvals for safe purchases.
            </p>
            <a href="{{ route('services') }}" class="text-xs font-bold text-brand-cyan hover:underline">
                Explore Legal &amp; Valuation Services &rarr;
            </a>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <h3 class="text-sm font-bold text-brand-navy mb-2">Buyer Preference Profile</h3>
            <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                Save your target budget, desired BHK configuration, and localities to receive alerts when matching properties go live.
            </p>
            <div class="space-y-2 text-xs text-slate-600 mb-4">
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span>Target City:</span>
                    <span class="font-bold text-brand-navy">{{ Auth::user()->city ?? 'Jaipur' }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Preferred Budget:</span>
                    <span class="text-slate-500 font-medium">Any Range</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
