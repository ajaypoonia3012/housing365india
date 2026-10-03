@extends('layouts.app')

@section('title', 'Rent Invoice — Period: ' . $ledger->billing_period . ' — Housing365 India')

@section('content')
<div class="bg-white border-b border-slate-100 py-3.5 text-sm text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-cyan transition font-medium">Home</a>
        <span class="text-slate-300">/</span>
        <a href="{{ route('dashboard.tenant') }}" class="hover:text-brand-cyan transition font-medium">Tenant Hub</a>
        <span class="text-slate-300">/</span>
        <span class="text-brand-navy font-bold">Rent Invoice</span>
    </div>
</div>

<section class="py-10 bg-slate-50/60 min-h-screen">
    <div class="mx-auto w-full max-w-3xl px-4 sm:px-6">

        @if(session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-6 mb-6 gap-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-brand-cyan">HOUSING365 RENT SETTLEMENT</span>
                    <h1 class="text-2xl font-display font-extrabold text-brand-navy mt-1">Invoice: Period {{ $ledger->billing_period }}</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Property: <strong>{{ $ledger->property->title }}</strong></p>
                </div>
                <div>
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider
                        @if($ledger->status === 'PAID') bg-emerald-50 text-emerald-700 border border-emerald-200
                        @elseif($ledger->status === 'OVERDUE') bg-rose-50 text-rose-700 border border-rose-200
                        @else bg-amber-50 text-amber-700 border border-amber-200 @endif">
                        {{ $ledger->status }}
                    </span>
                </div>
            </div>

            <!-- Due Breakdown -->
            <div class="space-y-3 mb-8 text-xs text-slate-600">
                <div class="flex justify-between py-1.5 border-b border-slate-50">
                    <span>Due Date</span>
                    <span class="font-bold text-slate-800">{{ $ledger->due_date ? $ledger->due_date->format('d M Y') : 'N/A' }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-50">
                    <span>Monthly Rent Amount</span>
                    <span class="font-bold font-mono text-slate-800">₹{{ number_format((float) $ledger->amount_due, 2) }}</span>
                </div>
                @if($ledger->late_fee > 0)
                    <div class="flex justify-between py-1.5 border-b border-slate-50 text-rose-600">
                        <span>Late Fee Assessment</span>
                        <span class="font-bold font-mono">+₹{{ number_format((float) $ledger->late_fee, 2) }}</span>
                    </div>
                @endif
                <div class="flex justify-between py-1.5 border-b border-slate-50">
                    <span>Amount Paid to Date</span>
                    <span class="font-bold font-mono text-emerald-600">₹{{ number_format((float) $ledger->amount_paid, 2) }}</span>
                </div>
                <div class="flex justify-between py-2.5 text-sm font-extrabold text-brand-navy bg-slate-50 px-3 rounded-xl">
                    <span>Outstanding Due</span>
                    <span class="font-mono text-base text-brand-navy">₹{{ number_format((float) $ledger->outstanding_balance, 2) }}</span>
                </div>
            </div>

            <!-- Payment Action -->
            @if($ledger->outstanding_balance > 0 && $isTenant)
                <form action="{{ route('rent.ledgers.pay', $ledger->id) }}" method="POST" class="p-6 rounded-2xl bg-gradient-to-r from-brand-navy to-brand-navy-light text-white flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    @csrf
                    <input type="hidden" name="amount" value="{{ $ledger->outstanding_balance }}">
                    <div>
                        <div class="text-xs uppercase tracking-wider text-brand-cyan font-bold">Secure Online Settlement</div>
                        <div class="text-lg font-bold">Pay ₹{{ number_format((float) $ledger->outstanding_balance, 2) }}</div>
                        <div class="text-[11px] text-white/70">Instant confirmation via UPI, Cards, or NetBanking</div>
                    </div>
                    <button type="submit" class="px-6 py-3 rounded-xl bg-brand-cyan hover:bg-brand-cyan/90 text-brand-navy font-extrabold text-xs uppercase tracking-wider transition shadow">
                        Pay Rent Online &rarr;
                    </button>
                </form>
            @elseif($ledger->status === 'PAID')
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                    <span>This rent invoice is fully paid and settled on {{ $ledger->paid_at ? $ledger->paid_at->format('d M Y') : 'Date of payment' }}.</span>
                </div>
            @endif

        </div>

    </div>
</section>
@endsection
