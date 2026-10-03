@extends('layouts.app')

@section('title', '419 - Session Expired | Housing365 India')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4 py-16 bg-slate-50">
    <div class="max-w-md w-full text-center">
        <div class="w-20 h-20 mx-auto rounded-2xl bg-[#00AEEF]/10 text-[#00AEEF] flex items-center justify-center mb-6">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h1 class="text-4xl font-extrabold text-[#03234B] tracking-tight mb-2">419</h1>
        <h2 class="text-xl font-bold text-slate-800 mb-3">Session Expired</h2>
        <p class="text-slate-600 text-sm mb-8 leading-relaxed">
            Your security token or browsing session has expired. Please refresh the page and try submitting the form again.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="javascript:window.location.reload();" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 rounded-xl text-sm font-semibold text-white bg-[#03234B] hover:bg-[#021833] transition-colors shadow-sm">
                Refresh Page
            </a>
            <a href="{{ route('home') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 rounded-xl text-sm font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 transition-colors">
                Return to Home
            </a>
        </div>
    </div>
</div>
@endsection
