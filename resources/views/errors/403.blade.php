@extends('layouts.app')

@section('title', '403 - Access Forbidden | Housing365 India')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4 py-16 bg-slate-50">
    <div class="max-w-md w-full text-center">
        <div class="w-20 h-20 mx-auto rounded-2xl bg-amber-500/10 text-amber-600 flex items-center justify-center mb-6">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
        <h1 class="text-4xl font-extrabold text-[#03234B] tracking-tight mb-2">403</h1>
        <h2 class="text-xl font-bold text-slate-800 mb-3">Access Restricted</h2>
        <p class="text-slate-600 text-sm mb-8 leading-relaxed">
            You do not have authorization to view this resource. If you believe this is an error, please verify your account credentials or contact support.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('home') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 rounded-xl text-sm font-semibold text-white bg-[#03234B] hover:bg-[#021833] transition-colors shadow-sm">
                Return to Home
            </a>
            <a href="{{ route('contact') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 rounded-xl text-sm font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 transition-colors">
                Contact Support
            </a>
        </div>
    </div>
</div>
@endsection
