@extends('layouts.app')

@section('title', 'Coworking Desks & Cabins | ' . $property->title . ' — Housing365 India')

@section('content')
<div class="bg-white border-b border-slate-100 py-3.5 text-sm text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-cyan transition font-medium">Home</a>
        <span class="text-slate-300">/</span>
        <a href="{{ route('properties.show', $property->slug) }}" class="hover:text-brand-cyan transition font-medium">{{ $property->title }}</a>
        <span class="text-slate-300">/</span>
        <span class="text-brand-navy font-bold">Desks &amp; Cabins</span>
    </div>
</div>

<section class="py-10 bg-slate-50/60 min-h-screen">
    <div class="mx-auto w-full max-w-5xl px-4 sm:px-6">

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

        <div class="mb-8">
            <span class="text-xs font-bold uppercase tracking-wider text-brand-cyan">DESK &amp; CABIN INVENTORY</span>
            <h1 class="text-2xl sm:text-3xl font-display font-extrabold text-brand-navy mt-1">Book Workspace</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Property: <strong class="text-brand-navy">{{ $property->title }}</strong> &bull; {{ $property->locality ?? $property->city }}
            </p>
        </div>

        @if($property->coworkingInventories->isEmpty())
            <div class="bg-white rounded-3xl p-12 text-center border border-slate-200 shadow-sm">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-3 text-lg">
                    <i class="fa-solid fa-chair"></i>
                </div>
                <h3 class="text-base font-bold text-brand-navy">No Desks Configured</h3>
                <p class="text-xs text-slate-500 mt-1">Workspace inventory is currently being organized for this coworking hub.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($property->coworkingInventories as $item)
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-brand-cyan/10 text-brand-navy">
                                    {{ str_replace('_', ' ', $item->inventory_type) }}
                                </span>
                                <span class="font-mono font-bold text-sm text-slate-600">{{ $item->inventory_code }}</span>
                            </div>

                            <h3 class="text-xl font-bold text-brand-navy mb-1 capitalize">{{ str_replace('_', ' ', $item->inventory_type) }}</h3>
                            <p class="text-xs text-slate-500 mb-4">Capacity: <strong>{{ $item->capacity }} Seat(s)</strong></p>

                            <!-- Pricing Matrix -->
                            <div class="grid grid-cols-3 gap-2 p-3 bg-slate-50 rounded-2xl mb-6 text-center text-xs">
                                <div>
                                    <div class="text-[10px] text-slate-400 uppercase font-semibold">Hourly</div>
                                    <div class="font-mono font-bold text-slate-800">₹{{ number_format((float) $item->hourly_rate, 0) }}</div>
                                </div>
                                <div class="border-x border-slate-200">
                                    <div class="text-[10px] text-slate-400 uppercase font-semibold">Daily</div>
                                    <div class="font-mono font-bold text-slate-800">₹{{ number_format((float) $item->daily_rate, 0) }}</div>
                                </div>
                                <div>
                                    <div class="text-[10px] text-slate-400 uppercase font-semibold">Monthly</div>
                                    <div class="font-mono font-bold text-emerald-700">₹{{ number_format((float) $item->monthly_rate, 0) }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Booking Form -->
                        <form action="{{ route('coworking.book', $item->id) }}" method="POST" class="space-y-3">
                            @csrf
                            <input type="hidden" name="booking_type" value="daily">
                            <input type="hidden" name="start_datetime" value="{{ date('Y-m-d 09:00:00', strtotime('+1 day')) }}">
                            <input type="hidden" name="end_datetime" value="{{ date('Y-m-d 18:00:00', strtotime('+1 day')) }}">
                            <input type="hidden" name="duration_units" value="1">

                            <button type="submit" class="w-full py-3 rounded-xl bg-brand-navy hover:bg-brand-navy-light text-white font-bold text-xs uppercase tracking-wider transition shadow-sm">
                                Reserve 1 Day Pass (₹{{ number_format((float) $item->daily_rate, 0) }}) &rarr;
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</section>
@endsection
