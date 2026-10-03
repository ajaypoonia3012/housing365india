@extends('layouts.app')

@section('title', 'Saved Properties | Housing 365 India')
@section('meta_description', 'View your saved apartments, houses, villas, and commercial spaces on Housing 365 India.')

@section('content')
<div class="bg-white border-b border-slate-100 py-3.5 text-xs text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-cyan transition font-medium">Home</a>
        <span class="text-slate-300">/</span>
        <a href="{{ route('properties.index') }}" class="hover:text-brand-cyan transition font-medium">Properties</a>
        <span class="text-slate-300">/</span>
        <span class="text-brand-navy font-bold">Saved Properties</span>
    </div>
</div>

<section class="py-10 sm:py-14 bg-[#F8FAFD] min-h-[70vh]">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-brand-navy">Your Saved Properties</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Review your short-listed residences and commercial spaces. Zero brokerage guaranteed.</p>
            </div>
            <a href="{{ route('properties.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-brand-cyan text-white text-xs font-bold uppercase tracking-wider shadow-sm hover:bg-brand-cyan-hover transition">
                <i class="fa-solid fa-magnifying-glass"></i> Explore More Properties
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($properties as $prop)
                <div class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-sm transition-all duration-300 hover:shadow-xl hover:-translate-y-1 flex flex-col group">
                    <div class="relative h-64 bg-slate-100 overflow-hidden">
                        <img src="{{ $prop->image ?? '/city-placeholder-b.svg' }}" 
                             alt="{{ $prop->title }}" 
                             onerror="this.src='/city-placeholder-b.svg'"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        
                        <div class="absolute top-4 left-4 flex flex-wrap gap-1.5 max-w-[80%]">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-brand-navy/90 text-white backdrop-blur-md">
                                {{ strtoupper($prop->purpose) }}
                            </span>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-white/90 text-slate-800 backdrop-blur-md">
                                {{ $prop->property_type }}
                            </span>
                        </div>

                        <!-- Price Badge -->
                        <div class="absolute bottom-4 right-4 bg-white/95 backdrop-blur-md px-3.5 py-1.5 rounded-xl shadow-lg border border-white/60">
                            @if(!empty($prop->price_display))
                                <span class="text-base sm:text-lg font-bold text-brand-navy">{{ $prop->price_display }}</span>
                            @else
                                <span class="text-base sm:text-lg font-bold text-brand-navy">₹{{ number_format($prop->price, 0) }}</span>
                                <span class="text-xs text-slate-500 font-medium">{{ $prop->purpose === 'sale' ? '' : '/' . ($prop->price_frequency ?? 'mo') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="p-7 flex-grow flex flex-col justify-between">
                        <div>
                            <div class="text-xs font-bold text-brand-cyan mb-2 flex items-center justify-between">
                                <span class="flex items-center gap-1.5">
                                    <i class="fa-solid fa-location-dot"></i>
                                    <span>{{ $prop->locality ? $prop->locality . ', ' : '' }}{{ $prop->city }}, {{ $prop->state }}</span>
                                </span>
                            </div>
                            <h3 class="font-heading font-bold text-lg text-brand-navy line-clamp-1 group-hover:text-brand-cyan transition-colors mb-2">
                                <a href="{{ route('properties.show', $prop->slug) }}">{{ $prop->title }}</a>
                            </h3>
                            <p class="text-slate-500 text-xs line-clamp-2 mb-4">{{ $prop->description }}</p>
                        </div>

                        <div class="pt-5 border-t border-slate-100 flex items-center justify-between gap-2">
                            <a href="{{ route('properties.show', $prop->slug) }}" class="flex-1 text-center py-2.5 rounded-xl bg-brand-navy text-white font-bold text-xs uppercase tracking-wider hover:bg-slate-800 transition">
                                View Details
                            </a>
                            <form action="{{ route('properties.save', $prop->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="p-2.5 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100 transition" title="Remove from saved">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-16 bg-white rounded-3xl border border-slate-200/80 shadow-sm">
                    <div class="w-16 h-16 rounded-full bg-slate-50 text-slate-400 flex items-center justify-center mx-auto mb-4 text-2xl">
                        <i class="fa-regular fa-bookmark"></i>
                    </div>
                    <h3 class="font-heading text-lg font-bold text-brand-navy mb-1">No saved properties yet</h3>
                    <p class="text-slate-500 text-sm mb-6">Explore our verified marketplace and tap the bookmark icon on any property to save it here.</p>
                    <a href="{{ route('properties.index') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-brand-cyan text-white text-xs font-bold uppercase tracking-wider">Browse Properties</a>
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $properties->links() }}
        </div>
    </div>
</section>
@endsection
