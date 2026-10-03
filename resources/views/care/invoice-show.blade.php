@extends('layouts.app')

@section('title', 'Care Invoice #' . $invoice->invoice_number . ' | Housing365')

@section('content')
<div class="bg-white border-b border-slate-100 py-3.5 text-sm text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-cyan transition font-medium">Home</a>
        <span class="text-slate-300">/</span>
        <a href="{{ route('care.index') }}" class="hover:text-brand-cyan transition font-medium">Housing365 Care</a>
        <span class="text-slate-300">/</span>
        <a href="{{ route('care.requests.show', $invoice->service_request_id) }}" class="hover:text-brand-cyan transition font-medium">Request #{{ $invoice->serviceRequest->request_number }}</a>
        <span class="text-slate-300">/</span>
        <span class="text-brand-navy font-bold">Invoice #{{ $invoice->invoice_number }}</span>
    </div>
</div>

<section class="py-12 sm:py-16 bg-slate-50/60 min-h-screen">
    <div class="mx-auto w-full max-w-3xl px-4 sm:px-8">
        
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

        <div class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200 shadow-sm relative overflow-hidden">
            <!-- Header Pattern -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
                <div>
                    <span class="text-xs font-extrabold uppercase tracking-widest text-brand-cyan">HOUSING365 CARE</span>
                    <h1 class="text-2xl sm:text-3xl font-display font-extrabold text-brand-navy mt-1">Service Tax Invoice</h1>
                    <div class="text-xs text-slate-400 mt-1">Invoice Number: <strong class="text-slate-700">#{{ $invoice->invoice_number }}</strong></div>
                </div>

                <div>
                    <span class="px-4 py-2 rounded-2xl text-xs font-black uppercase tracking-wider inline-flex items-center gap-1.5
                        @if($invoice->payment_status === 'PAID') bg-emerald-50 text-emerald-700 border border-emerald-200
                        @elseif($invoice->payment_status === 'FAILED') bg-rose-50 text-rose-700 border border-rose-200
                        @else bg-amber-50 text-amber-800 border border-amber-300 animate-pulse
                        @endif">
                        <span class="h-2 w-2 rounded-full @if($invoice->payment_status === 'PAID') bg-emerald-500 @else bg-amber-500 @endif"></span>
                        <span>{{ $invoice->payment_status }}</span>
                    </span>
                </div>
            </div>

            <!-- Customer & Service Metadata -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 py-6 border-b border-slate-100 text-xs">
                <div>
                    <span class="text-slate-400 block uppercase tracking-wider font-bold mb-1">Billed To</span>
                    <div class="font-bold text-slate-800 text-sm">{{ $invoice->user->name }}</div>
                    <div class="text-slate-500">{{ $invoice->user->email }}</div>
                    @if($invoice->serviceRequest->property)
                        <div class="text-slate-600 mt-2 font-medium">Property: {{ $invoice->serviceRequest->property->title }}</div>
                    @endif
                </div>

                <div>
                    <span class="text-slate-400 block uppercase tracking-wider font-bold mb-1">Service Details</span>
                    <div class="font-bold text-slate-800 text-sm">{{ $invoice->serviceRequest->category->name }}</div>
                    <div class="text-slate-500">Service Request: #{{ $invoice->serviceRequest->request_number }}</div>
                    @if($invoice->workOrder)
                        <div class="text-slate-500">Work Order: #{{ $invoice->workOrder->work_order_number }}</div>
                    @endif
                    <div class="text-slate-400 mt-1">Date: {{ $invoice->created_at->format('d M Y') }}</div>
                </div>
            </div>

            <!-- Line Items Table -->
            <div class="py-6 border-b border-slate-100">
                <table class="w-full text-xs text-left">
                    <thead>
                        <tr class="text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">
                            <th class="py-2">Description</th>
                            <th class="py-2 text-right">Amount (INR)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-slate-700">
                        <tr>
                            <td class="py-3">
                                <div class="font-bold text-slate-800">Labour &amp; Professional Service Charges</div>
                                <div class="text-[11px] text-slate-400">Verified technician labor and equipment</div>
                            </td>
                            <td class="py-3 text-right font-medium">₹{{ number_format($invoice->subtotal, 2) }}</td>
                        </tr>
                        @if($invoice->tax_amount > 0)
                            <tr>
                                <td class="py-3">
                                    <div class="font-bold text-slate-800">GST / Statutory Taxes</div>
                                    <div class="text-[11px] text-slate-400">Applicable service tax</div>
                                </td>
                                <td class="py-3 text-right font-medium">₹{{ number_format($invoice->tax_amount, 2) }}</td>
                            </tr>
                        @endif
                        @if($invoice->discount_amount > 0)
                            <tr>
                                <td class="py-3 text-emerald-600 font-bold">Promotional Discount</td>
                                <td class="py-3 text-right text-emerald-600 font-bold">-₹{{ number_format($invoice->discount_amount, 2) }}</td>
                            </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr class="border-t border-slate-200">
                            <td class="pt-4 text-sm font-black text-brand-navy uppercase tracking-wider">Total Payable</td>
                            <td class="pt-4 text-xl font-display font-black text-brand-navy text-right">₹{{ number_format($invoice->total_amount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Payment Action / Paid Details -->
            <div class="pt-6">
                @if($invoice->payment_status === 'PAID')
                    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800">
                        <div class="font-bold text-sm flex items-center gap-2 mb-1">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i>
                            <span>Invoice Paid in Full</span>
                        </div>
                        <div>Payment Reference: <strong class="font-mono">{{ $invoice->payment_reference }}</strong></div>
                        <div>Paid On: {{ $invoice->paid_at ? $invoice->paid_at->format('d M Y, h:i A') : 'Confirmed' }}</div>
                        <div>Payment Method: <span class="capitalize">{{ $invoice->payment_method }}</span></div>
                    </div>
                @else
                    @php
                        $orderId = 'order_care_sbx_' . bin2hex(random_bytes(6));
                        $signature = hash_hmac('sha256', $orderId . '|' . $invoice->total_amount, config('app.key', 'secret_key'));
                        $idempotencyKey = (string) \Illuminate\Support\Str::uuid();
                    @endphp

                    <form action="{{ route('care.invoices.pay', $invoice->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="gateway_order_id" value="{{ $orderId }}">
                        <input type="hidden" name="gateway_payment_id" value="pay_sbx_{{ bin2hex(random_bytes(6)) }}">
                        <input type="hidden" name="gateway_signature" value="{{ $signature }}">
                        <input type="hidden" name="payment_method" value="upi">
                        <input type="hidden" name="idempotency_key" value="{{ $idempotencyKey }}">

                        <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200 text-xs text-blue-800 flex items-center gap-3">
                            <i class="fa-solid fa-shield-halved text-blue-600 text-base"></i>
                            <div>
                                <span class="font-bold">Housing365 Secure Payment Gateway</span>
                                <p class="text-[11px] text-blue-700">Encrypted server-side checkout with duplicate payment protection.</p>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-4 rounded-2xl bg-brand-navy hover:bg-brand-navy-light text-white font-bold text-sm uppercase tracking-wider transition shadow-md flex items-center justify-center gap-2">
                            <i class="fa-solid fa-lock"></i>
                            <span>Pay ₹{{ number_format($invoice->total_amount, 2) }} Now</span>
                        </button>
                    </form>
                @endif
            </div>

            <!-- Back Link -->
            <div class="mt-6 text-center">
                <a href="{{ route('care.requests.show', $invoice->service_request_id) }}" class="text-xs font-semibold text-slate-500 hover:text-brand-cyan transition">
                    &larr; Back to Service Request
                </a>
            </div>
        </div>

    </div>
</section>
@endsection
