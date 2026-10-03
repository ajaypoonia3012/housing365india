@extends('dashboard.layout')

@section('title', 'Tenant Workspace | Housing365 India')
@section('workspace_badge', 'TENANT WORKSPACE')
@section('workspace_icon', 'fa-house-user')
@section('workspace_subtitle', 'Find verified homes, track enquiries, access unlocked owner contacts, and arrange move-in services.')

@section('workspace_header_actions')
    <a href="{{ route('properties.index') }}" class="px-4 py-2 rounded-2xl bg-brand-cyan hover:bg-brand-cyan-hover text-brand-navy font-extrabold text-xs uppercase tracking-wider transition shadow-md shadow-brand-cyan/20 flex items-center gap-2">
        <i class="fa-solid fa-magnifying-glass"></i>
        <span>Explore Verified Rentals</span>
    </a>
@endsection

@section('workspace_content')
<!-- Metric Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Contact Access Credits</span>
            <div class="w-8 h-8 rounded-xl bg-brand-cyan/10 text-brand-navy flex items-center justify-center text-xs">
                <i class="fa-solid fa-unlock-keyhole"></i>
            </div>
        </div>
        <div class="text-2xl font-display font-extrabold text-brand-navy">{{ $contactCredits }}</div>
        <div class="text-[11px] text-slate-500 mt-1">
            @if($contactCredits > 0)
                <span class="text-emerald-600 font-semibold">{{ $contactCredits }} unlocks available</span>
            @else
                <a href="{{ route('pricing') }}" class="text-brand-cyan font-bold hover:underline">Get Contact Credits &rarr;</a>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Unlocked Owners</span>
            <div class="w-8 h-8 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center text-xs">
                <i class="fa-solid fa-phone"></i>
            </div>
        </div>
        <div class="text-2xl font-display font-extrabold text-brand-navy">{{ $unlockedCount }}</div>
        <div class="text-[11px] text-slate-500 mt-1">Direct phone &amp; WhatsApp unlocked</div>
    </div>

    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Enquiries Submitted</span>
            <div class="w-8 h-8 rounded-xl bg-blue-500/10 text-brand-navy flex items-center justify-center text-xs">
                <i class="fa-solid fa-envelope"></i>
            </div>
        </div>
        <div class="text-2xl font-display font-extrabold text-brand-navy">{{ $inquiries->count() }}</div>
        <div class="text-[11px] text-slate-500 mt-1">100% Free Housing365 inquiries</div>
    </div>

    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Rent Agreements</span>
            <div class="w-8 h-8 rounded-xl bg-purple-500/10 text-purple-600 flex items-center justify-center text-xs">
                <i class="fa-solid fa-file-signature"></i>
            </div>
        </div>
        <div class="text-2xl font-display font-extrabold text-brand-navy">0</div>
        <div class="text-[11px] text-slate-500 mt-1">Legally vetted e-stamping assistance</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left 2 Cols: My Enquiries & Move-in Pipeline -->
    <div class="lg:col-span-2 space-y-8">
        
        <!-- Enquiries Section -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold text-brand-navy">My Recent Enquiries</h2>
                    <p class="text-xs text-slate-500">Enquiries and viewing requests sent via Housing365.</p>
                </div>
                <a href="{{ route('properties.index') }}" class="text-xs font-bold text-brand-cyan hover:underline">
                    Browse Rentals &rarr;
                </a>
            </div>

            @if($inquiries->count() > 0)
                <div class="divide-y divide-slate-100">
                    @foreach($inquiries as $inquiry)
                        <div class="py-4 first:pt-0 last:pb-0">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <div class="text-xs font-bold text-brand-navy">{{ $inquiry->subject ?? 'Property Enquiry' }}</div>
                                    <div class="text-xs text-slate-500 mt-0.5">{{ $inquiry->service_type ?? 'Rental Enquiry' }} &bull; {{ $inquiry->created_at->format('d M Y') }}</div>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-blue-50 text-brand-navy border border-blue-200">
                                    {{ ucfirst($inquiry->status ?? 'Submitted') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-8 text-center bg-slate-50/60 rounded-2xl border border-dashed border-slate-200">
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3 text-lg">
                        <i class="fa-solid fa-inbox"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800 mb-1">No Active Enquiries</h3>
                    <p class="text-xs text-slate-500 max-w-md mx-auto mb-4">
                        Search verified homes, request viewings with owners, or send free inquiries without any broker mediation.
                    </p>
                    <a href="{{ route('properties.index') }}" class="px-4 py-2 rounded-xl bg-brand-cyan text-brand-navy text-xs font-bold uppercase tracking-wider shadow-sm hover:bg-brand-cyan-hover transition">
                        Find a Rental &rarr;
                    </a>
                </div>
            @endif
        </div>

        <!-- Housing365 Care Maintenance Requests -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold text-brand-navy">My Maintenance &amp; Care Requests</h2>
                    <p class="text-xs text-slate-500">Track repairs, plumbing, electrical, and appliance service for your home.</p>
                </div>
                <a href="{{ route('care.request') }}" class="text-xs font-bold text-brand-cyan hover:underline">
                    + New Maintenance Request
                </a>
            </div>

            @if(isset($maintenanceRequests) && $maintenanceRequests->isNotEmpty())
                <div class="divide-y divide-slate-100">
                    @foreach($maintenanceRequests as $req)
                        <div class="py-3.5 first:pt-0 last:pb-0 flex items-center justify-between gap-4">
                            <div>
                                <div class="text-xs font-bold text-brand-navy flex items-center gap-2">
                                    <span>{{ $req->title ?: $req->category->name }}</span>
                                    <span class="text-slate-400">#{{ $req->request_number }}</span>
                                </div>
                                <div class="text-[11px] text-slate-500 mt-0.5">
                                    {{ $req->category->name }} &bull; {{ $req->created_at->format('d M Y') }} &bull; Priority: {{ ucfirst($req->priority) }}
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider
                                    @if($req->status === 'CLOSED') bg-emerald-50 text-emerald-700 border border-emerald-200
                                    @elseif($req->status === 'AWAITING_APPROVAL') bg-amber-50 text-amber-800 border border-amber-300
                                    @else bg-blue-50 text-blue-700 border border-blue-200
                                    @endif">
                                    {{ str_replace('_', ' ', $req->status) }}
                                </span>
                                <a href="{{ route('care.requests.show', $req->id) }}" class="px-2.5 py-1 rounded-lg border border-slate-200 hover:border-brand-cyan text-xs font-bold text-slate-700">
                                    View
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-6 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200 text-slate-500">
                    <p class="text-xs text-slate-500 mb-2">No maintenance requests submitted yet.</p>
                    <a href="{{ route('care.request') }}" class="text-xs font-bold text-brand-cyan hover:underline">
                        Need something fixed? Book Care Service &rarr;
                    </a>
                </div>
            @endif
        </div>

        <!-- Tenant Lifecycle Actions -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
                <div class="w-10 h-10 rounded-2xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center text-sm mb-4">
                    <i class="fa-solid fa-stamp"></i>
                </div>
                <h3 class="text-sm font-bold text-brand-navy mb-1">11-Month Rent Agreement</h3>
                <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                    Legally vetted 11-month rent agreements with official state e-stamping and biometric police verification assistance.
                </p>
                <a href="{{ route('services.show', 'rent-agreement') }}" class="text-xs font-bold text-brand-cyan hover:underline">
                    Create Rent Agreement &rarr;
                </a>
            </div>

            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
                <div class="w-10 h-10 rounded-2xl bg-blue-500/10 text-brand-navy flex items-center justify-center text-sm mb-4">
                    <i class="fa-solid fa-truck-ramp-box"></i>
                </div>
                <h3 class="text-sm font-bold text-brand-navy mb-1">Packers &amp; Movers</h3>
                <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                    Door-to-door shifting, careful furniture disassembly, packing, transport, and reassembly.
                </p>
                <a href="{{ route('services.show', 'packers-movers') }}" class="text-xs font-bold text-brand-cyan hover:underline">
                    Book Move &rarr;
                </a>
            </div>
        </div>

    </div>

    <!-- Right Col: Housing365 Care & Contact Access Info -->
    <div class="space-y-6">
        
        <!-- Contact Access Package Status -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <div class="w-10 h-10 rounded-2xl bg-brand-cyan/10 text-brand-navy flex items-center justify-center text-sm mb-3">
                <i class="fa-solid fa-unlock-keyhole"></i>
            </div>
            <h3 class="text-sm font-bold text-brand-navy mb-1">Direct Owner Contact Access</h3>
            <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                Connect directly with property owners and bypass brokerage fees. Unlock verified owner contact details using Contact Access credits.
            </p>
            <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200 mb-4 text-xs">
                <div class="flex justify-between text-slate-600 mb-1">
                    <span>Available Credits:</span>
                    <span class="font-extrabold text-brand-navy">{{ $contactCredits }}</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>Unlocked Owners:</span>
                    <span class="font-extrabold text-brand-navy">{{ $unlockedCount }}</span>
                </div>
            </div>
            <a href="{{ route('pricing') }}" class="block w-full py-2.5 px-4 rounded-xl bg-brand-navy hover:bg-brand-cyan hover:text-brand-navy text-white font-bold text-xs uppercase tracking-wider text-center transition">
                View Contact Access Passes &rarr;
            </a>
        </div>

        <!-- Housing365 Care for Move-in -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <div class="w-10 h-10 rounded-2xl bg-purple-500/10 text-purple-600 flex items-center justify-center text-sm mb-3">
                <i class="fa-solid fa-spray-can-sparkles"></i>
            </div>
            <h3 class="text-sm font-bold text-brand-navy mb-1">Move-In Deep Cleaning</h3>
            <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                Sanitize and refresh your new home before shifting with professional Housing365 Care cleaning partners.
            </p>
            <a href="{{ route('care') }}" class="text-xs font-bold text-brand-cyan hover:underline">
                Explore Cleaning Services &rarr;
            </a>
        </div>

    </div>
</div>
@endsection
