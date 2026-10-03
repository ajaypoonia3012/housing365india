@extends('layouts.app')

@section('title', 'Your Housing365 Workspaces | Housing365 India')

@section('content')
<section class="py-12 sm:py-20 bg-gradient-to-b from-[#F7FAFD] via-slate-50 to-white min-h-[85vh]">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">

        <!-- Top Header -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center gap-2 rounded-full border border-brand-cyan/30 bg-brand-cyan/10 px-4 py-1 text-xs font-extrabold uppercase tracking-wider text-brand-navy mb-3">
                <i class="fa-solid fa-shapes text-brand-cyan"></i>
                <span>HOUSING365 MULTI-ROLE PLATFORM</span>
            </div>
            <h1 class="font-display text-3xl sm:text-4xl font-extrabold text-brand-navy">Your Housing365 Workspaces</h1>
            <p class="text-slate-600 text-sm sm:text-base mt-2 max-w-2xl mx-auto">
                Select an active persona workspace below to manage your listings, viewings, clients, or service tasks. You can switch between active workspaces at any time.
            </p>
        </div>

        <!-- Workspaces Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($activeRoles as $role)
                @php
                    $roleKey = $role->name;
                    $route = $roleRoutes[$roleKey] ?? 'dashboard';
                    
                    $meta = match($roleKey) {
                        'owner' => [
                            'icon' => 'fa-solid fa-key',
                            'color' => 'amber',
                            'badge' => 'Property Owner',
                            'summary' => 'Manage your free rental and sale listings, view tenant enquiries, and review digital agreements.',
                            'button' => 'Open Owner Workspace',
                        ],
                        'tenant' => [
                            'icon' => 'fa-solid fa-house-user',
                            'color' => 'blue',
                            'badge' => 'Tenant',
                            'summary' => 'Track your rental enquiries, saved properties, viewing requests, and move-in care services.',
                            'button' => 'Open Tenant Workspace',
                        ],
                        'broker' => [
                            'icon' => 'fa-solid fa-briefcase',
                            'color' => 'indigo',
                            'badge' => 'Real Estate Broker',
                            'summary' => 'Manage client listings, verified buyer/tenant leads, deal pipeline, and commission tracking.',
                            'button' => 'Open Broker Workspace',
                        ],
                        'builder' => [
                            'icon' => 'fa-solid fa-city',
                            'color' => 'orange',
                            'badge' => 'Builder / Developer',
                            'summary' => 'Showcase new projects, tower inventory, manage site visits, and monitor RERA project leads.',
                            'button' => 'Open Builder Workspace',
                        ],
                        'pg_operator' => [
                            'icon' => 'fa-solid fa-bed',
                            'color' => 'rose',
                            'badge' => 'PG Operator',
                            'summary' => 'Manage PG occupancy, room configurations, student/professional resident inquiries.',
                            'button' => 'Open PG Workspace',
                        ],
                        'coliving_operator' => [
                            'icon' => 'fa-solid fa-people-roof',
                            'color' => 'purple',
                            'badge' => 'Co-living Operator',
                            'summary' => 'Manage serviced community living spaces, community events, and shared amenities.',
                            'button' => 'Open Co-living Workspace',
                        ],
                        'coworking_operator' => [
                            'icon' => 'fa-solid fa-laptop',
                            'color' => 'teal',
                            'badge' => 'Coworking Operator',
                            'summary' => 'Manage flexible desks, dedicated private cabins, meeting room bookings, and enterprise passes.',
                            'button' => 'Open Coworking Workspace',
                        ],
                        'vendor' => [
                            'icon' => 'fa-solid fa-screwdriver-wrench',
                            'color' => 'cyan',
                            'badge' => 'Service Vendor',
                            'summary' => 'Accept Housing365 Care service requests, submit quotes, manage jobs, and track verified payouts.',
                            'button' => 'Open Vendor Workspace',
                        ],
                        'buyer' => [
                            'icon' => 'fa-solid fa-money-bill-wave',
                            'color' => 'emerald',
                            'badge' => 'Property Buyer',
                            'summary' => 'Track residential & commercial properties of interest, site visits, and purchase offers.',
                            'button' => 'Open Buyer Workspace',
                        ],
                        'seller' => [
                            'icon' => 'fa-solid fa-handshake',
                            'color' => 'violet',
                            'badge' => 'Property Seller',
                            'summary' => 'Manage high-value sale listings, verified buyer inquiries, and transaction progress.',
                            'button' => 'Open Seller Workspace',
                        ],
                        default => [
                            'icon' => 'fa-solid fa-user',
                            'color' => 'slate',
                            'badge' => ucfirst($roleKey),
                            'summary' => 'Access your Housing365 workspace tools and account features.',
                            'button' => 'Open Workspace',
                        ]
                    };
                @endphp

                <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:border-brand-cyan/40 transition-all duration-200 flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-brand-navy/5 text-brand-navy flex items-center justify-center text-lg group-hover:bg-brand-cyan group-hover:text-white transition">
                                <i class="{{ $meta['icon'] }}"></i>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-slate-100 text-slate-700">
                                Active
                            </span>
                        </div>
                        <h2 class="text-xl font-display font-extrabold text-brand-navy mb-2">
                            {{ $role->display_name }}
                        </h2>
                        <p class="text-xs text-slate-600 leading-relaxed mb-6">
                            {{ $meta['summary'] }}
                        </p>
                    </div>

                    <a href="{{ route('dashboard.switch', $roleKey) }}" 
                       class="w-full py-3 px-4 rounded-2xl bg-brand-navy group-hover:bg-brand-cyan text-white text-xs font-bold uppercase tracking-wider text-center transition shadow-md shadow-brand-navy/10 flex items-center justify-center gap-2">
                        <span>{{ $meta['button'] }}</span>
                        <i class="fa-solid fa-arrow-right text-[11px]"></i>
                    </a>
                </div>
            @endforeach
        </div>

        <!-- Add Another Persona / Role Footer Card -->
        <div class="mt-12 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 text-center">
            <h3 class="text-base font-bold text-brand-navy mb-1">Need to expand your Housing365 capabilities?</h3>
            <p class="text-xs text-slate-500 max-w-xl mx-auto mb-4">
                Whether you are starting a brokerage, listing a commercial building, or joining our vendor network, your single account scales with your business.
            </p>
            <div class="flex flex-wrap justify-center gap-3">
                <a href="{{ route('properties.post') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 hover:border-brand-cyan text-xs font-bold text-slate-700 hover:text-brand-navy transition">
                    + List a Property Free
                </a>
                <a href="{{ route('contact') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 hover:border-brand-cyan text-xs font-bold text-slate-700 hover:text-brand-navy transition">
                    Contact Partner Support
                </a>
            </div>
        </div>

    </div>
</section>
@endsection
