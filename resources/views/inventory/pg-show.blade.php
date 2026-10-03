@extends('layouts.app')

@section('title', 'PG Room & Bed Selection | ' . $property->title . ' — Housing365 India')

@section('content')
<div class="bg-white border-b border-slate-100 py-3.5 text-sm text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-cyan transition font-medium">Home</a>
        <span class="text-slate-300">/</span>
        <a href="{{ route('properties.show', $property->slug) }}" class="hover:text-brand-cyan transition font-medium">{{ $property->title }}</a>
        <span class="text-slate-300">/</span>
        <span class="text-brand-navy font-bold">Room &amp; Bed Selection</span>
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
            <span class="text-xs font-bold uppercase tracking-wider text-brand-cyan">BED-LEVEL INVENTORY</span>
            <h1 class="text-2xl sm:text-3xl font-display font-extrabold text-brand-navy mt-1">Select Room &amp; Bed</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Property: <strong class="text-brand-navy">{{ $property->title }}</strong> &bull; {{ $property->locality ?? $property->city }}
            </p>
        </div>

        @if($property->pgRooms->isEmpty())
            <div class="bg-white rounded-3xl p-12 text-center border border-slate-200 shadow-sm">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-3 text-lg">
                    <i class="fa-solid fa-bed"></i>
                </div>
                <h3 class="text-base font-bold text-brand-navy">No Rooms Configured</h3>
                <p class="text-xs text-slate-500 mt-1">Bed inventory is currently being organized for this PG accommodation.</p>
            </div>
        @else
            <div class="space-y-6">
                @foreach($property->pgRooms as $room)
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-4 mb-6 gap-2">
                            <div>
                                <h3 class="text-lg font-bold text-brand-navy">Room {{ $room->room_number }}</h3>
                                <div class="text-xs text-slate-500 capitalize">
                                    {{ str_replace('_', ' ', $room->room_type) }} &bull; Gender Preference: <strong>{{ ucfirst($room->gender_preference) }}</strong>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-base font-bold font-mono text-brand-navy">₹{{ number_format((float) $room->monthly_rent_per_bed, 0) }} <span class="text-xs font-normal text-slate-400">/ bed / mo</span></div>
                                <div class="text-[11px] text-slate-400">Deposit: ₹{{ number_format((float) $room->deposit_per_bed, 0) }}</div>
                            </div>
                        </div>

                        <!-- Bed Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            @foreach($room->beds as $bed)
                                <div class="p-4 rounded-2xl border @if($bed->isAvailable()) border-emerald-200 bg-emerald-50/20 @else border-slate-200 bg-slate-50/60 opacity-75 @endif flex flex-col justify-between">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="font-mono font-bold text-sm text-brand-navy">{{ $bed->bed_code }}</span>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                            @if($bed->isAvailable()) bg-emerald-100 text-emerald-800
                                            @elseif($bed->status === 'HELD') bg-amber-100 text-amber-800
                                            @else bg-slate-200 text-slate-700 @endif">
                                            {{ $bed->isAvailable() ? 'AVAILABLE' : $bed->status }}
                                        </span>
                                    </div>

                                    @if($bed->isAvailable())
                                        <form action="{{ route('pg.beds.book', $bed->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="check_in_date" value="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                            <button type="submit" class="w-full py-2 rounded-xl bg-brand-navy hover:bg-brand-navy-light text-white font-bold text-xs uppercase tracking-wider transition">
                                                Book Bed &rarr;
                                            </button>
                                        </form>
                                    @else
                                        <button disabled class="w-full py-2 rounded-xl bg-slate-200 text-slate-400 font-bold text-xs uppercase tracking-wider cursor-not-allowed">
                                            Unavailable
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</section>
@endsection
