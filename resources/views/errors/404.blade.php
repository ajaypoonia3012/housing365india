@extends('layouts.app')

@section('title', '404 - Page Not Found | Housing365 India')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4 py-16 bg-slate-50">
    <div class="max-w-md w-full text-center">
        <div class="w-20 h-20 mx-auto rounded-2xl bg-[#00AEEF]/10 text-[#00AEEF] flex items-center justify-center mb-6">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h1 class="text-4xl font-extrabold text-[#03234B] tracking-tight mb-2">404</h1>
        <h2 class="text-xl font-bold text-slate-800 mb-3">Page Not Found</h2>
        <p class="text-slate-600 text-sm mb-8 leading-relaxed">
            The page or property listing you are looking for may have been moved, expired, or is temporarily unavailable.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('home') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 rounded-xl text-sm font-semibold text-white bg-[#03234B] hover:bg-[#021833] transition-colors shadow-sm">
                Return to Home
            </a>
            <a href="{{ route('properties.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 rounded-xl text-sm font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 transition-colors">
                Explore Properties
            </a>
        </div>
    </div>
</div>
@endsection
