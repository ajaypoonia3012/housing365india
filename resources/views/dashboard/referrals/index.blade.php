@extends('layouts.app')

@section('title', 'My Referrals | Housing365 India')

@section('content')
<section class="py-8 sm:py-12 bg-slate-50 min-h-[85vh]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Top Header & Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 rounded-full bg-brand-cyan/15 text-brand-cyan text-[11px] font-black uppercase tracking-wider">
                        Refer &amp; Earn ₹100
                    </span>
                    <span class="text-xs text-slate-500 font-mono">Code: {{ $userCode }}</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-display font-extrabold text-brand-navy">
                    My Referrals
                </h1>
                <p class="text-xs sm:text-sm text-slate-600">
                    Track your submitted property referrals, qualification milestones, and earned rewards.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard.rewards') }}"
                   class="px-4 py-2.5 rounded-xl bg-white hover:bg-slate-100 text-brand-navy border border-slate-200 text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-wallet text-brand-cyan"></i>
                    <span>My Rewards</span>
                </a>

                <a href="{{ route('dashboard.referrals.create') }}"
                   class="px-5 py-2.5 rounded-xl bg-brand-cyan hover:bg-brand-cyan-hover text-brand-navy text-xs font-black transition flex items-center gap-1.5 shadow-md shadow-brand-cyan/20">
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    <span>Refer a Property</span>
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-semibold flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Stats Overview Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Referrals</span>
                <div class="text-2xl sm:text-3xl font-black text-brand-navy mt-1">
                    {{ $stats['total'] }}
                </div>
                <span class="text-[11px] text-slate-400 mt-1 block">All submitted leads</span>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
                <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Qualified / Live</span>
                <div class="text-2xl sm:text-3xl font-black text-emerald-600 mt-1">
                    {{ $stats['successful'] }}
                </div>
                <span class="text-[11px] text-slate-400 mt-1 block">Properties listed</span>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
                <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">Pending Review</span>
                <div class="text-2xl sm:text-3xl font-black text-amber-600 mt-1">
                    {{ $stats['pending'] }}
                </div>
                <span class="text-[11px] text-slate-400 mt-1 block">Under verification</span>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-brand-cyan/30 bg-gradient-to-br from-white to-brand-cyan/5 shadow-sm">
                <span class="text-xs font-bold text-brand-cyan uppercase tracking-wider">Total Earned</span>
                <div class="text-2xl sm:text-3xl font-black text-brand-navy mt-1">
                    ₹{{ number_format($rewardStats['total_earned'], 2) }}
                </div>
                <span class="text-[11px] text-emerald-600 font-bold mt-1 block">
                    Available: ₹{{ number_format($rewardStats['available_for_payout'], 2) }}
                </span>
            </div>
        </div>

        <!-- Quick Share Strip -->
        <div class="bg-brand-navy rounded-2xl p-4 sm:p-5 text-white mb-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3 text-center sm:text-left">
                <div class="w-10 h-10 rounded-xl bg-brand-cyan/20 text-brand-cyan flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-share-nodes"></i>
                </div>
                <div>
                    <h4 class="text-xs sm:text-sm font-bold text-white">Your Personal Referral Link</h4>
                    <p class="text-[11px] text-slate-300">Share with property owners anywhere in India</p>
                </div>
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <input type="text" id="userReferralLink" readonly value="{{ route('refer-and-earn', ['ref' => $userCode]) }}"
                       class="px-3 py-2 rounded-xl bg-white/10 border border-white/20 text-xs font-mono text-white/90 truncate flex-1 sm:w-64">
                <button type="button" onclick="copyReferralLink()" id="btnCopyLink"
                        class="px-3.5 py-2 rounded-xl bg-brand-cyan hover:bg-brand-cyan-hover text-brand-navy font-bold text-xs transition shrink-0">
                    Copy
                </button>
            </div>
        </div>

        <!-- Referrals Table -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-brand-navy">Referral Portfolio</h3>
                    <p class="text-xs text-slate-500">History of owners and property boards you have referred</p>
                </div>
            </div>

            @if($referrals->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center text-2xl mb-3">
                        <i class="fa-solid fa-folder-open"></i>
                    </div>
                    <h4 class="text-sm font-bold text-brand-navy">No Referrals Yet</h4>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1 mb-5">
                        Know an owner renting or selling their home, PG, or commercial unit? Submit their details and earn ₹100!
                    </p>
                    <a href="{{ route('dashboard.referrals.create') }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-brand-navy text-white text-xs font-bold hover:bg-brand-navy-light transition">
                        <i class="fa-solid fa-plus text-[10px]"></i>
                        <span>Submit First Referral</span>
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50/75 border-b border-slate-200 text-slate-500 uppercase tracking-wider text-[11px] font-bold">
                                <th class="p-4">Referral ID</th>
                                <th class="p-4">Owner / Contact</th>
                                <th class="p-4">Location</th>
                                <th class="p-4">Property Type</th>
                                <th class="p-4">Status</th>
                                <th class="p-4">Reward</th>
                                <th class="p-4">Submitted</th>
                                <th class="p-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                            @foreach($referrals as $ref)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="p-4 font-mono font-bold text-brand-navy">
                                        #REF-{{ str_pad($ref->id, 5, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="p-4">
                                        <div class="font-bold text-brand-navy">{{ $ref->owner_name }}</div>
                                        <div class="text-[11px] text-slate-400">
                                            {{ substr($ref->owner_phone, 0, 3).'*****'.substr($ref->owner_phone, -2) }}
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <div>{{ $ref->locality }}</div>
                                        <div class="text-[11px] text-slate-400">{{ $ref->city }}</div>
                                    </td>
                                    <td class="p-4">
                                        <div class="capitalize">{{ $ref->property_type }}</div>
                                        <span class="text-[10px] uppercase font-bold text-slate-400">{{ $ref->listing_intent }}</span>
                                    </td>
                                    <td class="p-4">
                                        @php
                                            $badgeClass = match($ref->status) {
                                                'SUBMITTED' => 'bg-slate-100 text-slate-700',
                                                'UNDER_REVIEW', 'CONTACTED' => 'bg-blue-50 text-blue-700 border border-blue-200',
                                                'QUALIFIED' => 'bg-indigo-50 text-indigo-700 border border-indigo-200',
                                                'PROPERTY_LISTED', 'REWARD_PENDING', 'REWARD_APPROVED' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                                                'PAYOUT_PENDING' => 'bg-amber-50 text-amber-800 border border-amber-200',
                                                'PAID' => 'bg-emerald-100 text-emerald-900 border border-emerald-300 font-bold',
                                                'REJECTED', 'CANCELLED' => 'bg-rose-50 text-rose-700 border border-rose-200',
                                                default => 'bg-slate-100 text-slate-700',
                                            };
                                        @endphp
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $badgeClass }}">
                                            {{ str_replace('_', ' ', $ref->status) }}
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        @if($ref->latestReward)
                                            <span class="font-bold text-emerald-700">₹{{ number_format($ref->latestReward->amount, 2) }}</span>
                                            <span class="text-[10px] text-slate-400 block uppercase">({{ $ref->latestReward->status }})</span>
                                        @else
                                            <span class="text-slate-400">₹100 (On Listing)</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-slate-500 whitespace-nowrap">
                                        {{ $ref->submitted_at->format('M d, Y') }}
                                    </td>
                                    <td class="p-4 text-right">
                                        <a href="{{ route('dashboard.referrals.show', $ref->id) }}"
                                           class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-brand-navy hover:text-white text-brand-navy font-bold text-xs transition">
                                            Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($referrals->hasPages())
                    <div class="p-4 border-t border-slate-100">
                        {{ $referrals->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</section>

<script>
function copyReferralLink() {
    const el = document.getElementById('userReferralLink');
    const btn = document.getElementById('btnCopyLink');
    if (!el || !btn) return;

    el.select();
    navigator.clipboard.writeText(el.value).then(() => {
        btn.innerText = 'Copied!';
        setTimeout(() => { btn.innerText = 'Copy'; }, 2000);
    });
}
</script>
@endsection
