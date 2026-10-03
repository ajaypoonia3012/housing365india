@extends('layouts.app')

@section('title', 'Rent Collection Overview — Housing365 India')

@section('content')
<div class="bg-white border-b border-slate-100 py-3.5 text-sm text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-cyan transition font-medium">Home</a>
        <span class="text-slate-300">/</span>
        <a href="{{ route('dashboard.owner') }}" class="hover:text-brand-cyan transition font-medium">Owner Workspace</a>
        <span class="text-slate-300">/</span>
        <span class="text-brand-navy font-bold">Rent Collections</span>
    </div>
</div>

<section class="py-10 bg-slate-50/60 min-h-screen">
    <div class="mx-auto w-full max-w-5xl px-4 sm:px-6">

        <div class="mb-8">
            <span class="text-xs font-bold uppercase tracking-wider text-brand-cyan">COLLECTIONS &amp; LEDGERS</span>
            <h1 class="text-2xl sm:text-3xl font-display font-extrabold text-brand-navy mt-1">Rent Collections</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Real-time tracking of monthly tenant rent collections across your properties.
            </p>
        </div>

        @if($ledgers->isEmpty())
            <div class="bg-white rounded-3xl p-12 text-center border border-slate-200 shadow-sm">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-3 text-lg">
                    <i class="fa-solid fa-coins"></i>
                </div>
                <h3 class="text-base font-bold text-brand-navy">No Active Rent Ledgers</h3>
                <p class="text-xs text-slate-500 mt-1">Establish tenancy terms to start generating monthly digital rent billing ledgers.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($ledgers as $ledger)
                    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="text-base font-bold text-brand-navy">{{ $ledger->property->title }}</h3>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    @if($ledger->status === 'PAID') bg-emerald-50 text-emerald-700
                                    @elseif($ledger->status === 'OVERDUE') bg-rose-50 text-rose-700
                                    @else bg-amber-50 text-amber-700 @endif">
                                    {{ $ledger->status }}
                                </span>
                            </div>
                            <div class="text-xs text-slate-500">
                                Tenant: <strong class="text-slate-700">{{ $ledger->tenant->name ?? 'N/A' }}</strong>
                                &bull; Period: <strong class="text-slate-700">{{ $ledger->billing_period }}</strong>
                                &bull; Due: <strong class="text-slate-700">{{ $ledger->due_date ? $ledger->due_date->format('d M Y') : 'N/A' }}</strong>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="text-right">
                                <div class="text-xs text-slate-400">Received / Total</div>
                                <div class="text-base font-bold font-mono text-emerald-600">
                                    ₹{{ number_format((float) $ledger->amount_paid, 0) }} / ₹{{ number_format((float) $ledger->amount_due, 0) }}
                                </div>
                            </div>
                            <a href="{{ route('rent.ledgers.show', $ledger->id) }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-brand-navy font-bold text-xs uppercase tracking-wider transition">
                                Details &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach

                <div class="mt-4">
                    {{ $ledgers->links() }}
                </div>
            </div>
        @endif

    </div>
</section>
@endsection
