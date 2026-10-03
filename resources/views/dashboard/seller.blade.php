@extends('dashboard.layout')

@section('title', 'Seller Workspace | Housing365 India')
@section('workspace_badge', 'SELLER WORKSPACE')
@section('workspace_icon', 'fa-handshake')
@section('workspace_subtitle', 'List residential and commercial assets for outright sale, manage genuine buyer inquiries, and arrange site visits.')

@section('workspace_header_actions')
    <a href="{{ route('properties.post') }}" class="px-4 py-2 rounded-2xl bg-brand-cyan hover:bg-brand-cyan-hover text-brand-navy font-extrabold text-xs uppercase tracking-wider transition shadow-md shadow-brand-cyan/20 flex items-center gap-2">
        <i class="fa-solid fa-plus"></i>
        <span>List Property for Sale</span>
    </a>
@endsection

@section('workspace_content')
<!-- Metrics -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Properties for Sale</span>
        <div class="text-2xl font-display font-extrabold text-brand-navy mt-1">0</div>
        <div class="text-[11px] text-slate-500 mt-1">Resale apartments, plots &amp; villas</div>
    </div>

    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Buyer Inquiries</span>
        <div class="text-2xl font-display font-extrabold text-brand-navy mt-1">0</div>
        <div class="text-[11px] text-slate-500 mt-1">Direct inquiries via Housing365</div>
    </div>

    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Site Visits</span>
        <div class="text-2xl font-display font-extrabold text-brand-navy mt-1">0</div>
        <div class="text-[11px] text-slate-500 mt-1">Scheduled walkthroughs</div>
    </div>

    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Sale Agreement</span>
        <div class="text-xs font-extrabold text-slate-700 bg-slate-100 px-2 py-1 rounded-lg inline-block mt-2">DRAFTING READY</div>
        <div class="text-[11px] text-slate-500 mt-1">Agreement to Sell &amp; Deed assistance</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-brand-navy">Properties Listed for Sale</h2>
                <a href="{{ route('properties.post') }}" class="text-xs font-bold text-brand-cyan hover:underline">+ New Sale Listing</a>
            </div>
            <div class="p-8 text-center bg-slate-50/60 rounded-2xl border border-dashed border-slate-200">
                <i class="fa-solid fa-house-chimney text-slate-400 text-2xl mb-2"></i>
                <h3 class="text-sm font-bold text-slate-800 mb-1">No Sale Properties Listed</h3>
                <p class="text-xs text-slate-500 max-w-md mx-auto mb-4">
                    List your residential property, commercial unit, or plot for sale with verified pricing and photos.
                </p>
                <a href="{{ route('properties.post') }}" class="px-4 py-2 rounded-xl bg-brand-cyan text-brand-navy text-xs font-bold uppercase tracking-wider transition">
                    + Post Property for Sale
                </a>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <h2 class="text-base font-bold text-brand-navy mb-2">Property Valuation &amp; Marketing</h2>
            <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                Connect with certified local property valuers to price your asset accurately and attract genuine buyers without prolonged negotiations.
            </p>
            <a href="{{ route('services') }}" class="text-xs font-bold text-brand-cyan hover:underline">
                Request Property Valuation &rarr;
            </a>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <h3 class="text-sm font-bold text-brand-navy mb-2">Seller Profile</h3>
            <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                Verify your title documents and ownership details to earn the "Housing365 Verified Seller" badge.
            </p>
            <div class="space-y-2 text-xs text-slate-600 mb-4">
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span>Seller:</span>
                    <span class="font-bold text-brand-navy">{{ Auth::user()->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span>City:</span>
                    <span class="font-bold text-brand-navy">{{ Auth::user()->city ?? 'India' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
