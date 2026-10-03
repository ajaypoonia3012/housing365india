@extends('layouts.app')

@section('title', 'Referral Details #REF-'.str_pad($referral->id, 5, '0', STR_PAD_LEFT).' | Housing365 India')

@section('content')
<section class="py-8 sm:py-12 bg-slate-50 min-h-[85vh]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Back to Referrals -->
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('dashboard.referrals') }}" class="text-xs font-bold text-slate-500 hover:text-brand-navy flex items-center gap-1.5 transition">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to My Referrals</span>
            </a>
            <span class="text-xs font-mono font-bold text-slate-400">
                #REF-{{ str_pad($referral->id, 5, '0', STR_PAD_LEFT) }}
            </span>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-semibold flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Referral Summary Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        @php
                            $badgeClass = match($referral->status) {
                                'SUBMITTED' => 'bg-slate-100 text-slate-700',
                                'UNDER_REVIEW', 'CONTACTED' => 'bg-blue-50 text-blue-700 border border-blue-200',
                                'QUALIFIED' => 'bg-indigo-50 text-indigo-700 border border-indigo-200',
                                'PROPERTY_LISTED', 'REWARD_PENDING', 'REWARD_APPROVED' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                                'PAYOUT_PENDING' => 'bg-amber-50 text-amber-800 border border-amber-200',
                                'PAID' => 'bg-emerald-100 text-emerald-900 border border-emerald-300 font-bold',
                                'REJECTED', 'CANCELLED' => 'bg-rose-50 text-rose-700 border border-rose-200',
                                default => 'bg-slate-100 text-slate-700',
                            };
                        @endphp
                        <span class="px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider {{ $badgeClass }}">
                            {{ str_replace('_', ' ', $referral->status) }}
                        </span>
                        <span class="text-xs text-slate-400 font-mono">
                            Submitted {{ $referral->submitted_at->format('M d, Y, h:i A') }}
                        </span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-bold text-brand-navy">
                        {{ $referral->owner_name }}
                    </h1>
                    <p class="text-xs text-slate-500">
                        {{ $referral->locality }}, {{ $referral->city }} • {{ $referral->property_type }} ({{ strtoupper($referral->listing_intent) }})
                    </p>
                </div>

                <div class="text-left sm:text-right p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Reward Status</span>
                    @if($referral->latestReward)
                        <span class="text-lg font-black text-emerald-600 block">
                            ₹{{ number_format($referral->latestReward->amount, 2) }}
                        </span>
                        <span class="text-[10px] uppercase font-bold text-slate-500">
                            {{ $referral->latestReward->status }}
                        </span>
                    @else
                        <span class="text-base font-bold text-slate-600 block">₹100.00</span>
                        <span class="text-[10px] uppercase font-bold text-slate-400">On Live Listing</span>
                    @endif
                </div>
            </div>

            <!-- Details Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 py-6 border-b border-slate-100 text-xs">
                <div>
                    <span class="text-slate-400 font-medium block mb-1">Owner Contact</span>
                    <span class="font-bold text-brand-navy font-mono">
                        {{ substr($referral->owner_phone, 0, 3).'*****'.substr($referral->owner_phone, -2) }}
                    </span>
                    @if($referral->owner_email)
                        <span class="text-slate-500 block truncate mt-0.5">{{ $referral->owner_email }}</span>
                    @endif
                </div>

                <div>
                    <span class="text-slate-400 font-medium block mb-1">Submission Method</span>
                    <span class="font-bold text-brand-navy">
                        {{ $referral->submission_method === 'property_board_photo' ? 'Method B (Board Photo)' : 'Method A (Direct Owner)' }}
                    </span>
                    @if($referral->photo_path)
                        <div class="mt-1">
                            <a href="{{ route('referrals.photo.download', $referral->id) }}" target="_blank"
                               class="text-brand-cyan font-bold hover:underline inline-flex items-center gap-1">
                                <i class="fa-solid fa-image"></i>
                                <span>View Uploaded Board</span>
                            </a>
                        </div>
                    @endif
                </div>

                <div>
                    <span class="text-slate-400 font-medium block mb-1">Relationship</span>
                    <span class="font-bold text-brand-navy">
                        {{ $referral->referrer_relationship ?: 'Direct Referral' }}
                    </span>
                </div>
            </div>

            @if($referral->qualifiedProperty)
                <div class="mt-6 p-4 rounded-2xl bg-emerald-50/60 border border-emerald-200 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold text-base">
                            <i class="fa-solid fa-house-circle-check"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-emerald-900">Live Property Match Onboarded</h4>
                            <p class="text-[11px] text-emerald-700">{{ $referral->qualifiedProperty->title }} in {{ $referral->qualifiedProperty->city }}</p>
                        </div>
                    </div>
                    <a href="{{ route('properties.show', $referral->qualifiedProperty->slug) }}" target="_blank"
                       class="px-3.5 py-1.5 rounded-lg bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700 transition">
                        View Live Listing
                    </a>
                </div>
            @endif

            @if($referral->rejection_reason)
                <div class="mt-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-xs text-rose-800">
                    <div class="flex items-center gap-2 font-bold mb-1">
                        <i class="fa-solid fa-circle-xmark text-rose-600"></i>
                        <span>Disqualification Reason</span>
                    </div>
                    <p>{{ $referral->rejection_reason }}</p>
                </div>
            @endif
        </div>

        <!-- Audit History Timeline -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
            <h3 class="text-base font-bold text-brand-navy mb-6">Qualification &amp; Event History</h3>

            @if($referral->auditLogs->isEmpty())
                <p class="text-xs text-slate-400">No events logged yet.</p>
            @else
                <div class="relative pl-6 space-y-6 before:absolute before:left-2.5 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">
                    @foreach($referral->auditLogs as $log)
                        <div class="relative">
                            <div class="absolute -left-6 top-1 w-3 h-3 rounded-full bg-brand-cyan border-2 border-white"></div>
                            <div class="text-xs font-bold text-brand-navy">
                                {{ ucwords(str_replace('_', ' ', $log->event)) }}
                            </div>
                            <div class="text-[11px] text-slate-400 mt-0.5">
                                {{ $log->created_at->format('M d, Y • h:i A') }}
                                @if($log->to_status)
                                    • Status: <span class="font-bold text-slate-600">{{ $log->to_status }}</span>
                                @endif
                            </div>
                            @if(!empty($log->metadata['reason']))
                                <p class="text-[11px] text-slate-600 mt-1 italic">"{{ $log->metadata['reason'] }}"</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
