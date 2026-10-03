@extends('layouts.app')

@section('title', 'Property Applicants | ' . $property->title . ' — Housing365 India')

@section('content')
<div class="bg-white border-b border-slate-100 py-3.5 text-sm text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-cyan transition font-medium">Home</a>
        <span class="text-slate-300">/</span>
        <a href="{{ route('dashboard.owner') }}" class="hover:text-brand-cyan transition font-medium">Owner Workspace</a>
        <span class="text-slate-300">/</span>
        <span class="text-brand-navy font-bold">Applicants</span>
    </div>
</div>

<section class="py-10 bg-slate-50/60 min-h-screen">
    <div class="mx-auto w-full max-w-5xl px-4 sm:px-6">

        <div class="mb-8">
            <span class="text-xs font-bold uppercase tracking-wider text-brand-cyan">TENANT EVALUATION</span>
            <h1 class="text-2xl sm:text-3xl font-display font-extrabold text-brand-navy mt-1">Property Applicants</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                For property: <strong class="text-brand-navy">{{ $property->title }}</strong> &bull; {{ $property->city ?? 'India' }}
            </p>
        </div>

        @if($property->tenantApplications->isEmpty())
            <div class="bg-white rounded-3xl p-12 text-center border border-slate-200 shadow-sm">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-3 text-lg">
                    <i class="fa-solid fa-users-slash"></i>
                </div>
                <h3 class="text-base font-bold text-brand-navy">No Applications Received Yet</h3>
                <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto">
                    Prospective tenants will appear here once they submit an application for this property.
                </p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($property->tenantApplications as $app)
                    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2 mb-1.5">
                                <h3 class="text-base font-bold text-brand-navy">{{ $app->tenant->name ?? 'Applicant' }}</h3>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700">
                                    {{ $app->status }}
                                </span>
                            </div>
                            <div class="text-xs text-slate-500 space-y-1">
                                <div>Occupation: <strong class="text-slate-700">{{ $app->occupation ?? 'Not Specified' }}</strong> ({{ $app->employer_name ?? 'Self-employed / Private' }})</div>
                                <div>Monthly Income: <strong class="text-slate-700 font-mono">₹{{ number_format((float) $app->monthly_income, 0) }}</strong> &bull; Tenancy: {{ $app->tenancy_months }} Months</div>
                                <div>Screening Status: 
                                    <span class="font-bold @if($app->screening?->status === 'VERIFIED') text-emerald-600 @else text-amber-600 @endif">
                                        {{ str_replace('_', ' ', $app->screening?->status ?? 'NOT_INITIATED') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            @if($app->screening)
                                <a href="{{ route('tenant.screenings.show', $app->screening->id) }}" class="px-4 py-2 rounded-xl bg-brand-navy hover:bg-brand-navy-light text-white font-bold text-xs uppercase tracking-wider transition">
                                    View Screening &rarr;
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</section>
@endsection
