@extends('layouts.app')

@section('title', 'My Rewards & Payouts | Housing365 India')

@section('content')
<section class="py-8 sm:py-12 bg-slate-50 min-h-[85vh]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Top Header & Breadcrumb -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('dashboard.referrals') }}" class="text-xs font-bold text-slate-500 hover:text-brand-navy flex items-center gap-1">
                        <i class="fa-solid fa-arrow-left"></i>
                        <span>My Referrals</span>
                    </a>
                </div>
                <h1 class="text-2xl sm:text-3xl font-display font-extrabold text-brand-navy">
                    My Rewards Ledger
                </h1>
                <p class="text-xs sm:text-sm text-slate-600">
                    Transparent earnings ledger and direct UPI payout management.
                </p>
            </div>

            <div class="flex items-center gap-3">
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

        @if(session('error'))
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm font-semibold flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Rewards Metric Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
            <div class="bg-white rounded-2xl p-5 border border-emerald-200 bg-gradient-to-br from-white to-emerald-50/40 shadow-sm">
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Available for Payout</span>
                <div class="text-2xl sm:text-3xl font-black text-emerald-700 mt-1">
                    ₹{{ number_format($rewardStats['available_for_payout'], 2) }}
                </div>
                <span class="text-[11px] text-emerald-600 mt-1 block">Approved &amp; Ready</span>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Paid Out</span>
                <div class="text-2xl sm:text-3xl font-black text-brand-navy mt-1">
                    ₹{{ number_format($rewardStats['paid_amount'], 2) }}
                </div>
                <span class="text-[11px] text-slate-400 mt-1 block">Disbursed via UPI</span>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
                <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">Pending Qualification</span>
                <div class="text-2xl sm:text-3xl font-black text-amber-600 mt-1">
                    ₹{{ number_format($rewardStats['pending_amount'], 2) }}
                </div>
                <span class="text-[11px] text-slate-400 mt-1 block">Awaiting admin review</span>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-brand-cyan/30 shadow-sm">
                <span class="text-xs font-bold text-brand-cyan uppercase tracking-wider">Total Lifetime Earned</span>
                <div class="text-2xl sm:text-3xl font-black text-brand-navy mt-1">
                    ₹{{ number_format($rewardStats['total_earned'], 2) }}
                </div>
                <span class="text-[11px] text-slate-400 mt-1 block">{{ $rewardStats['rewards_count'] }} Qualifying Referrals</span>
            </div>
        </div>

        <!-- Payout Management Strip -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8">
            <!-- UPI Settings Card -->
            <div class="lg:col-span-6 bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-sm">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center font-bold">
                        <i class="fa-solid fa-qrcode text-base"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-brand-navy">UPI Payout Destination</h3>
                        <p class="text-[11px] text-slate-500">Your registered UPI address for ₹100 reward transfers</p>
                    </div>
                </div>

                @if($user->upi_id)
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 mb-4 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Saved UPI ID</span>
                            <span class="font-mono font-bold text-brand-navy text-sm">{{ $user->masked_upi }}</span>
                        </div>
                        <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-100 flex items-center gap-1">
                            <i class="fa-solid fa-circle-check text-[10px]"></i>
                            <span>Verified</span>
                        </span>
                    </div>
                @endif

                <form action="{{ route('dashboard.payout-method') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-brand-navy mb-1">
                            {{ $user->upi_id ? 'Update UPI ID' : 'Add Your UPI ID' }}
                        </label>
                        <input type="text" name="upi_id" required placeholder="e.g. yourname@okhdfcbank or 9876543210@paytm"
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-cyan font-mono">
                        <span class="text-[10px] text-slate-400 mt-1 block">Valid handles: @okhdfcbank, @oksbi, @paytm, @ybl, etc.</span>
                    </div>

                    <button type="submit"
                            class="px-4 py-2.5 rounded-xl bg-brand-navy hover:bg-brand-navy-light text-white font-bold text-xs transition">
                        Save UPI Destination
                    </button>
                </form>
            </div>

            <!-- Request Payout Action Card -->
            <div class="lg:col-span-6 bg-gradient-to-br from-brand-navy to-brand-navy-light rounded-3xl p-6 sm:p-7 text-white shadow-xl border border-white/10 flex flex-col justify-between">
                <div>
                    <span class="px-3 py-1 rounded-full bg-brand-cyan/20 border border-brand-cyan/40 text-brand-cyan text-[11px] font-black uppercase tracking-wider">
                        Direct Disbursement
                    </span>
                    <h3 class="text-xl font-bold text-white mt-3 mb-1">Request Reward Payout</h3>
                    <p class="text-xs text-slate-300 leading-relaxed mb-4">
                        Rewards are disbursed directly to your saved UPI address. Payout requests are reviewed and settled by Housing365 operations within 24–48 business hours.
                    </p>

                    <div class="p-3.5 rounded-2xl bg-white/10 border border-white/15 mb-4 flex items-center justify-between">
                        <span class="text-xs text-slate-300">Ready for withdrawal:</span>
                        <span class="text-lg font-black text-brand-cyan">
                            ₹{{ number_format($rewardStats['available_for_payout'], 2) }}
                        </span>
                    </div>
                </div>

                <form action="{{ route('dashboard.rewards.request-payout') }}" method="POST">
                    @csrf
                    <button type="submit"
                            {{ $rewardStats['available_for_payout'] <= 0 || empty($user->upi_id) ? 'disabled' : '' }}
                            class="w-full py-3.5 rounded-xl font-black text-xs tracking-wider uppercase transition flex items-center justify-center gap-2 {{ $rewardStats['available_for_payout'] > 0 && !empty($user->upi_id) ? 'bg-brand-cyan hover:bg-brand-cyan-hover text-brand-navy shadow-lg shadow-brand-cyan/25' : 'bg-white/20 text-white/50 cursor-not-allowed' }}">
                        <i class="fa-solid fa-arrow-down-to-bracket text-xs"></i>
                        <span>Withdraw ₹{{ number_format($rewardStats['available_for_payout'], 2) }} to UPI</span>
                    </button>
                    @if(empty($user->upi_id))
                        <span class="text-[11px] text-amber-300 mt-2 block text-center font-medium">
                            * Please save your UPI ID on the left before requesting payout.
                        </span>
                    @endif
                </form>
            </div>
        </div>

        <!-- Rewards Ledger Table -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden mb-8">
            <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-brand-navy">Rewards Financial Ledger</h3>
                    <p class="text-xs text-slate-500">Immutable record of every ₹100 earned from verified referrals</p>
                </div>
            </div>

            @if($rewards->isEmpty())
                <div class="p-10 text-center text-xs text-slate-400">
                    No rewards recorded yet. Refer a property owner to earn your first ₹100!
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50/75 border-b border-slate-200 text-slate-500 uppercase tracking-wider text-[11px] font-bold">
                                <th class="p-4">Reward ID</th>
                                <th class="p-4">Referral Ref</th>
                                <th class="p-4">Amount</th>
                                <th class="p-4">Status</th>
                                <th class="p-4">Approved At</th>
                                <th class="p-4">Payout ID</th>
                                <th class="p-4">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                            @foreach($rewards as $rwd)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="p-4 font-mono font-bold text-brand-navy">
                                        #RWD-{{ str_pad($rwd->id, 5, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="p-4">
                                        <a href="{{ route('dashboard.referrals.show', $rwd->referral_id) }}" class="font-mono text-brand-cyan hover:underline font-bold">
                                            #REF-{{ str_pad($rwd->referral_id, 5, '0', STR_PAD_LEFT) }}
                                        </a>
                                    </td>
                                    <td class="p-4 font-bold text-emerald-700">
                                        ₹{{ number_format($rwd->amount, 2) }}
                                    </td>
                                    <td class="p-4">
                                        @php
                                            $rwdBadge = match($rwd->status) {
                                                'PENDING' => 'bg-amber-50 text-amber-700 border border-amber-200',
                                                'APPROVED' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                                                'PAID' => 'bg-emerald-100 text-emerald-900 border border-emerald-300 font-bold',
                                                'ON_HOLD' => 'bg-slate-100 text-slate-700',
                                                default => 'bg-slate-100 text-slate-600',
                                            };
                                        @endphp
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $rwdBadge }}">
                                            {{ $rwd->status }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-slate-500 whitespace-nowrap">
                                        {{ $rwd->approved_at ? $rwd->approved_at->format('M d, Y') : '—' }}
                                    </td>
                                    <td class="p-4 font-mono text-slate-500">
                                        {{ $rwd->payout ? $rwd->payout->payout_number : '—' }}
                                    </td>
                                    <td class="p-4 text-slate-500 whitespace-nowrap">
                                        {{ $rwd->created_at->format('M d, Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($rewards->hasPages())
                    <div class="p-4 border-t border-slate-100">
                        {{ $rewards->links() }}
                    </div>
                @endif
            @endif
        </div>

        <!-- Payout History Table -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-5 sm:p-6 border-b border-slate-100">
                <h3 class="text-base font-bold text-brand-navy">Payout Disbursement History</h3>
                <p class="text-xs text-slate-500">Settlement requests submitted to Housing365 finance</p>
            </div>

            @if($payouts->isEmpty())
                <div class="p-8 text-center text-xs text-slate-400">
                    No payout disbursements requested yet.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50/75 border-b border-slate-200 text-slate-500 uppercase tracking-wider text-[11px] font-bold">
                                <th class="p-4">Payout Number</th>
                                <th class="p-4">Amount</th>
                                <th class="p-4">Method / Destination</th>
                                <th class="p-4">Status</th>
                                <th class="p-4">Transaction Ref</th>
                                <th class="p-4">Requested Date</th>
                                <th class="p-4">Settled Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                            @foreach($payouts as $pay)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="p-4 font-mono font-bold text-brand-navy">
                                        {{ $pay->payout_number }}
                                    </td>
                                    <td class="p-4 font-bold text-emerald-700">
                                        ₹{{ number_format($pay->amount, 2) }}
                                    </td>
                                    <td class="p-4 font-mono text-slate-600">
                                        {{ $pay->payout_method }}: {{ $pay->payout_destination_masked }}
                                    </td>
                                    <td class="p-4">
                                        @php
                                            $payBadge = match($pay->status) {
                                                'PENDING', 'PROCESSING' => 'bg-amber-50 text-amber-800 border border-amber-200',
                                                'PAID' => 'bg-emerald-100 text-emerald-900 border border-emerald-300 font-bold',
                                                'FAILED' => 'bg-rose-50 text-rose-700 border border-rose-200',
                                                default => 'bg-slate-100 text-slate-700',
                                            };
                                        @endphp
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $payBadge }}">
                                            {{ $pay->status }}
                                        </span>
                                    </td>
                                    <td class="p-4 font-mono text-slate-500">
                                        {{ $pay->transaction_reference ?: '—' }}
                                    </td>
                                    <td class="p-4 text-slate-500 whitespace-nowrap">
                                        {{ $pay->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="p-4 text-slate-500 whitespace-nowrap">
                                        {{ $pay->processed_at ? $pay->processed_at->format('M d, Y') : '—' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>
</section>
@endsection
