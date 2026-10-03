@extends('layouts.app')

@section('title', 'Growth & Marketing Plans — Housing365 India')

@php
    $activeTaxRate = $taxConfig && $taxConfig->tax_enabled ? (float) $taxConfig->tax_rate : 0.0;
    $taxLabel = $taxConfig && $taxConfig->tax_enabled ? ($taxConfig->tax_name . ' (' . $activeTaxRate . '%)') : 'Tax (Not Applied)';
    $taxEnabled = $taxConfig && $taxConfig->tax_enabled;
@endphp

@section('content')
<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Hero Header --}}
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="text-xs uppercase font-extrabold tracking-widest text-[#00AEEF]">Marketplace Growth Engine</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-[#03234B] mt-2">Scale Your Reach with Housing365 Plans</h1>
            <p class="text-base text-gray-600 mt-3">
                Standard residential listings remain <span class="font-bold text-[#03234B]">100% Free</span>. Accelerate discoverability, unlock direct contact leads, or subscribe to professional real estate growth solutions.
            </p>
        </div>

        @if(session('success'))
            <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-xl max-w-2xl mx-auto text-center">
                {{ session('success') }}
            </div>
        @endif

        {{-- Products & Plans Grid --}}
        <div class="space-y-12">
            @foreach($products as $product)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between pb-6 border-b border-gray-100">
                        <div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-sky-50 text-[#00AEEF] uppercase tracking-wide">
                                {{ ucwords(str_replace('_', ' ', $product->target_role ?? 'Universal')) }}
                            </span>
                            <h2 class="text-xl font-bold text-[#03234B] mt-1">{{ $product->name }}</h2>
                            <p class="text-sm text-gray-500 mt-0.5">{{ $product->description }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                        @foreach($product->plans as $plan)
                            <div class="border border-gray-200 hover:border-[#00AEEF] rounded-xl p-6 flex flex-col justify-between transition hover:shadow-md bg-white">
                                <div>
                                    <h3 class="text-base font-bold text-[#03234B]">{{ $plan->name }}</h3>
                                    <div class="mt-4 flex items-baseline">
                                        <span class="text-3xl font-extrabold text-[#03234B]">₹{{ number_format($plan->price) }}</span>
                                        <span class="ml-1 text-xs text-gray-500">+ {{ $taxLabel }}</span>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-1">Valid for {{ $plan->validity_days }} days</p>

                                    @if(!empty($plan->features))
                                        <ul class="mt-6 space-y-2.5 text-xs text-gray-600">
                                            @foreach($plan->features as $feat)
                                                <li class="flex items-center">
                                                    <svg class="h-4 w-4 text-emerald-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    {{ $feat }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>

                                <div class="mt-8 pt-4 border-t border-gray-100">
                                    @auth
                                        <button onclick="openCheckoutModal({{ $plan->id }}, '{{ addslashes($plan->name) }}', {{ $plan->price }}, {{ $activeTaxRate }}, {{ $taxEnabled ? 'true' : 'false' }}, '{{ $taxConfig?->tax_name ?? 'GST' }}')" class="w-full py-2.5 px-4 bg-[#03234B] hover:bg-[#00AEEF] text-white text-xs font-bold rounded-lg transition text-center shadow-sm">
                                            Select Plan &rarr;
                                        </button>
                                    @else
                                        <a href="{{ route('login') }}" class="block w-full py-2.5 px-4 bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-bold rounded-lg transition text-center">
                                            Login to Purchase
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Checkout Modal --}}
<div id="checkoutModal" class="fixed inset-0 z-50 overflow-y-auto hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-[#03234B]" id="modalPlanName">Complete Checkout</h3>
            <button onclick="closeCheckoutModal()" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
        </div>

        <div class="mt-4 space-y-4 text-sm">
            @if($userProperties->isNotEmpty())
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Target Property (Optional)</label>
                    <select id="modalPropertySelect" class="w-full text-xs border-gray-300 rounded-lg">
                        <option value="">None / Account Level Plan</option>
                        @foreach($userProperties as $prop)
                            <option value="{{ $prop->id }}" {{ $selectedPropertyId == $prop->id ? 'selected' : '' }}>
                                {{ $prop->title }} ({{ $prop->locality }}, {{ $prop->city }})
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Promo / Coupon Code</label>
                <div class="flex space-x-2">
                    <input type="text" id="couponCodeInput" placeholder="Enter coupon code" class="flex-1 text-xs border-gray-300 rounded-lg uppercase">
                    <button type="button" onclick="applyCoupon()" class="px-3 py-1.5 bg-slate-800 text-white text-xs font-bold rounded-lg hover:bg-slate-700">Apply</button>
                </div>
                <div id="couponMessage" class="text-xs mt-1"></div>
            </div>

            {{-- Summary --}}
            <div class="bg-slate-50 p-3 rounded-lg space-y-1.5 text-xs text-gray-600">
                <div class="flex justify-between">
                    <span>Base Price</span>
                    <span id="summaryBasePrice">₹0.00</span>
                </div>
                <div class="flex justify-between text-emerald-600 font-semibold" id="summaryDiscountRow" style="display: none;">
                    <span>Coupon Discount</span>
                    <span id="summaryDiscount">₹0.00</span>
                </div>
                <div class="flex justify-between" id="summaryTaxRow">
                    <span id="summaryTaxLabel">{{ $taxLabel }}</span>
                    <span id="summaryTax">₹0.00</span>
                </div>
                <div class="flex justify-between text-sm font-extrabold text-[#03234B] pt-2 border-t border-gray-200">
                    <span>Total Amount</span>
                    <span id="summaryTotal">₹0.00</span>
                </div>
            </div>

            <div id="checkoutStatus" class="text-xs text-center"></div>

            <button type="button" id="payButton" onclick="processCheckout()" class="w-full py-3 px-4 bg-[#00AEEF] hover:bg-sky-500 text-white font-bold rounded-xl transition text-sm shadow-md">
                Confirm & Pay Now
            </button>
        </div>
    </div>
</div>

<script>
let currentPlanId = null;
let currentBasePrice = 0;
let currentTaxRate = {{ $activeTaxRate }};
let currentTaxEnabled = {{ $taxEnabled ? 'true' : 'false' }};
let currentTaxName = "{{ $taxConfig?->tax_name ?? 'GST' }}";
let appliedDiscount = 0;
let appliedCouponCode = null;

function openCheckoutModal(planId, name, price, taxRate, taxEnabled, taxName) {
    currentPlanId = planId;
    currentBasePrice = price;
    currentTaxRate = typeof taxRate !== 'undefined' ? taxRate : {{ $activeTaxRate }};
    currentTaxEnabled = typeof taxEnabled !== 'undefined' ? taxEnabled : {{ $taxEnabled ? 'true' : 'false' }};
    currentTaxName = taxName || "{{ $taxConfig?->tax_name ?? 'GST' }}";
    appliedDiscount = 0;
    appliedCouponCode = null;

    document.getElementById('modalPlanName').innerText = name;
    document.getElementById('couponCodeInput').value = '';
    document.getElementById('couponMessage').innerText = '';
    document.getElementById('summaryDiscountRow').style.display = 'none';
    document.getElementById('checkoutStatus').innerText = '';

    recalculateSummary();
    document.getElementById('checkoutModal').classList.remove('hidden');
}

function closeCheckoutModal() {
    document.getElementById('checkoutModal').classList.add('hidden');
}

function recalculateSummary() {
    const taxable = Math.max(0, currentBasePrice - appliedDiscount);
    let tax = 0.0;
    if (currentTaxEnabled && currentTaxRate > 0) {
        tax = Math.round((taxable * (currentTaxRate / 100)) * 100) / 100;
        document.getElementById('summaryTaxLabel').innerText = currentTaxName + ' (' + currentTaxRate + '%)';
        document.getElementById('summaryTax').innerText = '₹' + tax.toFixed(2);
    } else {
        document.getElementById('summaryTaxLabel').innerText = 'Tax:';
        document.getElementById('summaryTax').innerHTML = '<span class="text-gray-400 font-normal">Not Applied</span>';
    }
    const total = Math.round((taxable + tax) * 100) / 100;

    document.getElementById('summaryBasePrice').innerText = '₹' + currentBasePrice.toFixed(2);
    document.getElementById('summaryTotal').innerText = '₹' + total.toFixed(2);

    if (appliedDiscount > 0) {
        document.getElementById('summaryDiscountRow').style.display = 'flex';
        document.getElementById('summaryDiscount').innerText = '-₹' + appliedDiscount.toFixed(2);
    }
}

async function applyCoupon() {
    const code = document.getElementById('couponCodeInput').value.trim();
    if (!code) return;

    try {
        const res = await fetch("{{ route('monetization.validate-coupon') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ coupon_code: code, amount: currentBasePrice })
        });
        const data = await res.json();
        if (data.success) {
            appliedDiscount = data.discount;
            appliedCouponCode = code;
            if (typeof data.tax_enabled !== 'undefined') {
                currentTaxEnabled = data.tax_enabled;
                currentTaxRate = data.tax_rate;
                currentTaxName = data.tax_name;
            }
            document.getElementById('couponMessage').className = 'text-xs text-emerald-600 mt-1';
            document.getElementById('couponMessage').innerText = data.message;
            recalculateSummary();
        } else {
            document.getElementById('couponMessage').className = 'text-xs text-red-600 mt-1';
            document.getElementById('couponMessage').innerText = data.message || 'Invalid coupon';
        }
    } catch (e) {
        document.getElementById('couponMessage').innerText = 'Error validating coupon';
    }
}

async function processCheckout() {
    const btn = document.getElementById('payButton');
    const status = document.getElementById('checkoutStatus');
    btn.disabled = true;
    btn.innerText = 'Creating Secure Order...';

    const propSelect = document.getElementById('modalPropertySelect');
    const propertyId = propSelect ? propSelect.value : null;

    try {
        const orderRes = await fetch("{{ route('monetization.checkout') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                plan_id: currentPlanId,
                property_id: propertyId,
                coupon_code: appliedCouponCode
            })
        });
        const orderData = await orderRes.json();
        if (!orderData.success) {
            throw new Error(orderData.message || 'Checkout failed');
        }

        // Simulate Gateway Server Verification (Sandbox)
        btn.innerText = 'Verifying Server-side Payment...';
        const verifyRes = await fetch("{{ route('monetization.verify-payment') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                idempotency_key: orderData.idempotency_key,
                gateway_order_id: orderData.gateway_order_id,
                gateway_payment_id: 'pay_sbx_' + Date.now(),
                gateway_signature: 'sandbox_valid_signature'
            })
        });
        const verifyData = await verifyRes.json();
        if (verifyData.success) {
            window.location.href = "/invoices/" + verifyData.invoice_id;
        } else {
            throw new Error(verifyData.message || 'Verification failed');
        }
    } catch (err) {
        status.className = 'text-xs text-red-600 mt-2';
        status.innerText = err.message;
        btn.disabled = false;
        btn.innerText = 'Confirm & Pay Now';
    }
}
</script>
@endsection
