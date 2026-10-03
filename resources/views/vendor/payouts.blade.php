@extends('layouts.app')

@section('title', 'Vendor Earnings & Payouts | Housing365')

@section('content')
<div class="bg-white border-b border-slate-100 py-3.5 text-sm text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-cyan transition font-medium">Home</a>
        <span class="text-slate-300">/</span>
        <a href="{{ route('dashboard.vendor') }}" class="hover:text-brand-cyan transition font-medium">Vendor Portal</a>
        <span class="text-slate-300">/</span>
        <span class="text-brand-navy font-bold">Earnings &amp; Payouts</span>
    </div>
</div>

<section class="py-12 sm:py-16 bg-slate-50/60 min-h-screen">
    <div class="mx-auto w-full max-w-6xl px-4 sm:px-8">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <span class="text-xs font-extrabold uppercase tracking-widest text-brand-cyan">HOUSING365 PARTNER NETWORK</span>
                <h1 class="text-2xl sm:text-3xl font-display font-extrabold text-brand-navy mt-1">Vendor Payouts &amp; Settlements</h1>
                <p class="text-xs text-slate-500 mt-1">Track job earnings, platform commission deductions, and automated payout settlements.</p>
            </div>
            <div>
                <a href="{{ route('vendor.jobs') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 hover:border-brand-cyan text-brand-navy font-bold text-xs uppercase tracking-wider transition bg-white inline-flex items-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-briefcase"></i>
                    <span>Assigned Jobs</span>
                </a>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Settled Earnings</span>
                <div class="text-3xl font-display font-black text-brand-navy mt-2">
                    ₹{{ number_format($totalEarned, 2) }}
                </div>
                <div class="text-[11px] text-emerald-600 font-semibold mt-1">
                    <i class="fa-solid fa-circle-check mr-1"></i> Paid into registered bank account
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Pending Settlements</span>
                <div class="text-3xl font-display font-black text-amber-600 mt-2">
                    ₹{{ number_format($pendingPayout, 2) }}
                </div>
                <div class="text-[11px] text-slate-500 mt-1">
                    Processed within 24-48 hours of invoice payment
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Platform Commission</span>
                <div class="text-3xl font-display font-black text-slate-700 mt-2">
                    10.0%
                </div>
                <div class="text-[11px] text-slate-500 mt-1">
                    Standard verified partner service fee
                </div>
            </div>
        </div>

        <!-- Payouts Table -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-base font-bold text-brand-navy">Payout Ledger</h3>
                <span class="text-xs text-slate-500">{{ $payouts->total() }} total transactions</span>
            </div>

            @if($payouts->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-50/75 text-slate-400 uppercase tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="p-4">Payout Ref</th>
                                <th class="p-4">Work Order / Category</th>
                                <th class="p-4 text-right">Gross Amount</th>
                                <th class="p-4 text-right">Platform Fee (10%)</th>
                                <th class="p-4 text-right">Net Payout</th>
                                <th class="p-4">Status</th>
                                <th class="p-4">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($payouts as $payout)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="p-4 font-mono font-bold text-slate-800">
                                        {{ $payout->payout_number }}
                                    </td>
                                    <td class="p-4">
                                        <div class="font-bold text-brand-navy">
                                            {{ $payout->workOrder->serviceRequest->category->name ?? 'Service Job' }}
                                        </div>
                                        <div class="text-[11px] text-slate-400">
                                            WO: #{{ $payout->workOrder->work_order_number }}
                                        </div>
                                    </td>
                                    <td class="p-4 text-right font-medium">₹{{ number_format($payout->gross_amount, 2) }}</td>
                                    <td class="p-4 text-right text-rose-600 font-medium">-₹{{ number_format($payout->platform_fee, 2) }}</td>
                                    <td class="p-4 text-right font-black text-brand-navy text-sm">₹{{ number_format($payout->net_amount, 2) }}</td>
                                    <td class="p-4">
                                        <span class="px-2.5 py-1 rounded-xl text-[10px] font-black uppercase tracking-wider
                                            @if($payout->status === 'PAID') bg-emerald-100 text-emerald-800
                                            @elseif($payout->status === 'PROCESSING') bg-blue-100 text-blue-800
                                            @else bg-amber-100 text-amber-800
                                            @endif">
                                            {{ $payout->status }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-slate-500">
                                        {{ $payout->created_at->format('d M Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-slate-100">
                    {{ $payouts->links() }}
                </div>
            @else
                <div class="p-12 text-center text-slate-500">
                    <i class="fa-solid fa-coins text-3xl text-slate-300 mb-2"></i>
                    <div class="text-sm font-bold text-slate-700">No Payout Records Yet</div>
                    <p class="text-xs text-slate-400 mt-1">Payouts are generated automatically when a customer settles an approved service invoice.</p>
                </div>
            @endif
        </div>

    </div>
</section>
@endsection
