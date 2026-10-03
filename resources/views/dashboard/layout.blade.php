@extends('layouts.app')

@section('content')
<section class="py-8 sm:py-12 bg-gradient-to-b from-[#F7FAFD] via-slate-50 to-white min-h-[85vh]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Top Notification Banner -->
        @if (session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-950 text-xs font-bold uppercase">&times;</button>
            </div>
        @endif

        @if (session('info'))
            <div class="mb-6 p-4 rounded-2xl bg-brand-cyan/10 border border-brand-cyan/20 text-brand-navy text-sm font-semibold flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-circle-info text-brand-cyan text-lg"></i>
                    <span>{{ session('info') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-brand-navy hover:text-brand-navy/70 text-xs font-bold uppercase">&times;</button>
            </div>
        @endif

        <!-- Workspace Header & Role Switcher -->
        <div class="bg-brand-navy rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-brand-navy/10 mb-8 border border-white/10 relative overflow-hidden">
            <div class="absolute -right-16 -top-16 w-64 h-64 bg-brand-cyan/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <!-- User & Current Workspace Info -->
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-brand-cyan to-brand-cyan-light text-brand-navy flex items-center justify-center font-black text-xl shadow-lg shadow-brand-cyan/20">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-brand-cyan/20 text-brand-cyan border border-brand-cyan/30 text-[11px] font-extrabold uppercase tracking-wider">
                                <i class="fa-solid @yield('workspace_icon', 'fa-briefcase')"></i>
                                <span>@yield('workspace_badge', 'WORKSPACE')</span>
                            </span>
                            @if(Auth::user()->city)
                                <span class="text-xs text-white/60 flex items-center gap-1 font-medium">
                                    <i class="fa-solid fa-location-dot text-brand-cyan text-[10px]"></i>
                                    {{ Auth::user()->city }}
                                </span>
                            @endif
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-display font-extrabold text-white">
                            {{ Auth::user()->name }}
                        </h1>
                        <p class="text-xs sm:text-sm text-white/70 mt-0.5">
                            @yield('workspace_subtitle', 'Housing365 Unified Multi-Role Account')
                        </p>
                    </div>
                </div>

                <!-- Role Switcher & Quick Actions -->
                <div class="flex flex-wrap items-center gap-3">
                    @php
                        $userRoles = Auth::user()->activeRoles();
                    @endphp

                    @if($userRoles->count() > 1)
                        <div class="flex items-center gap-2 bg-white/10 backdrop-blur-md rounded-2xl p-1.5 border border-white/15">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-white/60 pl-2 pr-1 hidden sm:inline">
                                Switch:
                            </span>
                            @foreach($userRoles as $roleItem)
                                @php
                                    $roleKey = $roleItem->name;
                                    $roleRouteName = match($roleKey) {
                                        'owner' => 'dashboard.owner',
                                        'tenant' => 'dashboard.tenant',
                                        'broker' => 'dashboard.broker',
                                        'builder' => 'dashboard.builder',
                                        'pg_operator' => 'dashboard.pg-operator',
                                        'coliving_operator' => 'dashboard.coliving-operator',
                                        'coworking_operator' => 'dashboard.coworking-operator',
                                        'vendor' => 'dashboard.vendor',
                                        'buyer' => 'dashboard.buyer',
                                        'seller' => 'dashboard.seller',
                                        default => 'dashboard',
                                    };
                                    $isCurrent = ($currentRole ?? '') === $roleKey;
                                @endphp
                                <a href="{{ route('dashboard.switch', $roleKey) }}"
                                   class="px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ $isCurrent ? 'bg-brand-cyan text-brand-navy shadow-sm' : 'text-white/80 hover:text-white hover:bg-white/10' }}">
                                    <span>{{ $roleItem->display_name }}</span>
                                </a>
                            @endforeach
                            <a href="{{ route('dashboard') }}" title="View All Workspaces" class="px-2.5 py-1.5 rounded-xl text-xs font-bold text-white/70 hover:text-white hover:bg-white/10 transition">
                                <i class="fa-solid fa-grid-2"></i>
                            </a>
                        </div>
                    @endif

                    <a href="{{ route('trust.dashboard') }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 bg-white/10 hover:bg-white/20 text-white border border-white/20">
                        <i class="fa-solid fa-shield-halved text-brand-cyan"></i>
                        <span>Trust &amp; Verification</span>
                    </a>

                    @yield('workspace_header_actions')
                </div>
            </div>
        </div>

        <!-- Main Workspace Content -->
        @yield('workspace_content')

    </div>
</section>
@endsection
