@extends('layouts.app')

@section('title', 'Tax Invoice #' . $invoice->invoice_number . ' — Housing365 India')

@section('content')
<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-3xl mx-auto px-4 sm:px-6">
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('monetization.plans') }}" class="text-sm font-semibold text-gray-500 hover:text-[#03234B]">
                &larr; Back to Plans
            </a>
            <button onclick="window.print()" class="px-4 py-2 bg-slate-800 text-white text-xs font-bold rounded-lg hover:bg-slate-700">
                Print / Save PDF
            </button>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            {{-- Header --}}
            <div class="flex justify-between items-start pb-6 border-b border-gray-200">
                <div>
                    <h1 class="text-2xl font-black text-[#03234B]">HOUSING<span class="text-[#00AEEF]">365</span></h1>
                    <p class="text-xs text-gray-500 mt-0.5">Housing365 India Real Estate Platforms Pvt Ltd</p>
                    <p class="text-xs text-gray-400">GSTIN: 07AAACH3651Z1Z5</p>
                </div>
                <div class="text-right">
                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 uppercase tracking-wide">
                        PAID
                    </span>
                    <h2 class="text-sm font-bold text-gray-800 mt-2">{{ $invoice->invoice_number }}</h2>
                    <p class="text-xs text-gray-400">{{ $invoice->issued_at->format('d M Y') }}</p>
                </div>
            </div>

            {{-- Customer & Billing info --}}
            <div class="grid grid-cols-2 gap-6 py-6 border-b border-gray-100 text-xs">
                <div>
                    <span class="font-bold text-gray-400 uppercase tracking-wider">Billed To:</span>
                    <div class="font-bold text-gray-900 text-sm mt-1">{{ $invoice->customer_name }}</div>
                    <div class="text-gray-500">{{ $invoice->customer_email }}</div>
                    <div class="text-gray-500">{{ $invoice->customer_phone ?? 'Phone on file' }}</div>
                </div>
                <div class="text-right">
                    <span class="font-bold text-gray-400 uppercase tracking-wider">Payment Details:</span>
                    <div class="text-gray-800 mt-1">Status: <span class="font-bold text-emerald-600">Successful</span></div>
                    <div class="text-gray-500">Method: Gateway Direct / UPI / Card</div>
                    <div class="text-gray-400 text-[11px] truncate">Ref: {{ $invoice->transaction?->gateway_payment_id ?? 'Verified' }}</div>
                </div>
            </div>

            {{-- Line Items Table --}}
            <div class="py-6">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-gray-200 text-gray-400 font-bold uppercase">
                            <th class="pb-2">Description</th>
                            <th class="pb-2 text-center">Qty</th>
                            <th class="pb-2 text-right">Unit Price</th>
                            <th class="pb-2 text-right">GST (18%)</th>
                            <th class="pb-2 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($invoice->items as $item)
                            <tr>
                                <td class="py-3 font-semibold text-gray-800">{{ $item->item_description }}</td>
                                <td class="py-3 text-center text-gray-500">{{ $item->quantity }}</td>
                                <td class="py-3 text-right text-gray-700">₹{{ number_format($item->unit_price, 2) }}</td>
                                <td class="py-3 text-right text-gray-500">₹{{ number_format($item->tax_amount, 2) }}</td>
                                <td class="py-3 text-right font-bold text-gray-900">₹{{ number_format($item->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Totals --}}
            <div class="pt-4 border-t border-gray-200">
                <div class="w-1/2 ml-auto space-y-2 text-xs text-gray-600">
                    <div class="flex justify-between">
                        <span>Subtotal:</span>
                        <span class="font-semibold text-gray-800">₹{{ number_format($invoice->subtotal, 2) }}</span>
                    </div>
                    @if($invoice->discount > 0)
                        <div class="flex justify-between text-emerald-600">
                            <span>Discount:</span>
                            <span>-₹{{ number_format($invoice->discount, 2) }}</span>
                        </div>
                    @endif
                    @if(($invoice->tax_enabled ?? true) && $invoice->tax > 0)
                        @if(($invoice->cgst_amount > 0 || $invoice->sgst_amount > 0))
                            <div class="flex justify-between">
                                <span>CGST ({{ number_format(($invoice->tax_details['cgst_rate'] ?? ($invoice->tax_rate ? $invoice->tax_rate / 2 : 0)), 1) }}%):</span>
                                <span class="text-gray-800">₹{{ number_format($invoice->cgst_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>SGST ({{ number_format(($invoice->tax_details['sgst_rate'] ?? ($invoice->tax_rate ? $invoice->tax_rate / 2 : 0)), 1) }}%):</span>
                                <span class="text-gray-800">₹{{ number_format($invoice->sgst_amount, 2) }}</span>
                            </div>
                        @elseif($invoice->igst_amount > 0)
                            <div class="flex justify-between">
                                <span>IGST ({{ number_format(($invoice->tax_details['igst_rate'] ?? $invoice->tax_rate), 1) }}%):</span>
                                <span class="text-gray-800">₹{{ number_format($invoice->igst_amount, 2) }}</span>
                            </div>
                        @else
                            <div class="flex justify-between">
                                <span>{{ $invoice->tax_name ?? 'Tax' }} ({{ number_format($invoice->tax_rate, 1) }}%):</span>
                                <span class="text-gray-800">₹{{ number_format($invoice->tax, 2) }}</span>
                            </div>
                        @endif
                    @else
                        <div class="flex justify-between text-gray-500">
                            <span>Tax:</span>
                            <span class="font-medium text-gray-600">Not Applied</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-sm font-extrabold text-[#03234B] pt-2 border-t border-gray-200">
                        <span>Grand Total Paid:</span>
                        <span>₹{{ number_format($invoice->total, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Footer Note --}}
            <div class="mt-8 pt-6 border-t border-gray-100 text-center text-xs text-gray-400">
                This is a computer-generated tax invoice and requires no physical signature. Thank you for partnering with Housing365 India.
            </div>
        </div>
    </div>
</div>
@endsection
