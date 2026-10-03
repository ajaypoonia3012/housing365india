@extends('layouts.app')

@section('title', '429 - Too Many Requests | Housing365 India')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4 py-16 bg-slate-50">
    <div class="max-w-md w-full text-center">
        <div class="w-20 h-20 mx-auto rounded-2xl bg-amber-500/10 text-amber-600 flex items-center justify-center mb-6">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <h1 class="text-4xl font-extrabold text-[#03234B] tracking-tight mb-2">429</h1>
        <h2 class="text-xl font-bold text-slate-800 mb-3">Too Many Requests</h2>
        <p class="text-slate-600 text-sm mb-8 leading-relaxed">
            You have made too many requests in a short period. Please slow down and wait a minute before trying again.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('home') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 rounded-xl text-sm font-semibold text-white bg-[#03234B] hover:bg-[#021833] transition-colors shadow-sm">
                Return to Home
            </a>
        </div>
    </div>
</div>
@endsection
