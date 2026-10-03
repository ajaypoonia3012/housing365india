@extends('layouts.app')

@section('title', 'Rent Checkout — Housing365 India')

@section('content')
<section class="py-12 bg-slate-50/60 min-h-screen">
    <div class="mx-auto w-full max-w-lg px-4 sm:px-6">

        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-lg">
            <div class="text-center mb-6">
                <div class="w-12 h-12 rounded-2xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center mx-auto mb-2 text-xl">
                    <i class="fa-solid fa-credit-card"></i>
                </div>
                <h1 class="text-2xl font-display font-extrabold text-brand-navy">Rent Payment Gateway</h1>
                <p class="text-xs text-slate-500 mt-1">Invoice Period: {{ $ledger->billing_period }}</p>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 mb-6 text-xs text-slate-600 space-y-2">
                <div class="flex justify-between">
                    <span>Order Reference</span>
                    <span class="font-mono font-bold text-slate-800">{{ $payment->gateway_order_id }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Payable Amount</span>
                    <span class="font-mono font-bold text-base text-brand-navy">₹{{ number_format((float) $payment->amount, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Tenant</span>
                    <span class="font-bold text-slate-800">{{ $user->name }}</span>
                </div>
            </div>

            <!-- Complete Payment Form (Simulates Gateway Success Callback) -->
            <form action="{{ route('rent.payments.confirm', $payment->id) }}" method="POST">
                @csrf
                <input type="hidden" name="gateway_payment_id" value="pay_sbx_{{ uniqid() }}">
                <input type="hidden" name="gateway_signature" value="sandbox_valid_test_signature">

                <button type="submit" class="w-full py-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs uppercase tracking-wider transition shadow">
                    Complete Payment &amp; Authorize Settlement &rarr;
                </button>
            </form>

            <div class="mt-4 text-center">
                <a href="{{ route('rent.ledgers.show', $ledger->id) }}" class="text-xs text-slate-400 hover:text-slate-600">
                    Cancel and return to invoice
                </a>
            </div>
        </div>

    </div>
</section>
@endsection
