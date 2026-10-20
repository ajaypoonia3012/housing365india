@extends('layouts.app')

@section('title', 'RERA, Renting & Home Buying Guides | Housing365 India')
@section('meta_description', 'Expert articles on RERA compliance, Model Tenancy Act, stamp duty & registration, tenant verification, and property investment across India.')

@section('content')

<!-- Standard Hero Section -->
<section class="relative overflow-hidden bg-gradient-to-b from-[#F7FAFD] via-white to-white py-16 sm:py-24 border-b border-slate-100">
    <div class="pointer-events-none absolute -top-40 right-1/4 h-96 w-96 rounded-full bg-[radial-gradient(circle,rgba(0,174,239,0.18)_0%,transparent_70%)] blur-3xl"></div>
    <div class="pointer-events-none absolute top-10 left-10 h-72 w-72 rounded-full bg-[radial-gradient(circle,rgba(3,35,75,0.08)_0%,transparent_70%)] blur-2xl"></div>

    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 relative z-10 text-center">
        <div class="inline-flex items-center gap-2 rounded-full border border-brand-cyan/25 bg-brand-cyan/10 px-3.5 py-1 text-xs font-semibold uppercase tracking-wider text-brand-cyan mb-4">
            <span class="h-1.5 w-1.5 rounded-full bg-brand-cyan"></span>
            Resource Hub &amp; Market Intelligence
        </div>
        <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl font-normal tracking-tight text-brand-navy leading-[1.15] max-w-3xl mx-auto">
            Rental Guides &amp; <span class="text-brand-cyan">Market Updates</span>
        </h1>
        <p class="mt-4 max-w-2xl text-base sm:text-lg text-slate-600 font-normal leading-relaxed mx-auto">
            Stay ahead with authoritative guidance on residential tenancy regulations, Model Tenancy Act compliance, property yield optimization, and real estate trends across India.
        </p>
    </div>
</section>

<!-- Blog Grid Section -->
<section class="py-16 sm:py-20 bg-slate-50/50">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($posts as $post)
                <article class="rounded-3xl bg-white border border-slate-200/80 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between overflow-hidden group">
                    <div class="p-8">
                        <div class="flex items-center justify-between gap-3 mb-5">
                            <span class="inline-flex items-center rounded-full bg-brand-cyan/10 border border-brand-cyan/20 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-brand-cyan">
                                {{ $post->category ?? 'Insights' }}
                            </span>
                            <span class="flex items-center gap-1.5 text-xs text-slate-400 font-medium">
                                <i class="fa-regular fa-clock text-[11px] text-brand-cyan"></i>
                                {{ $post->read_time ?? '5 min read' }}
                            </span>
                        </div>
                        <h2 class="font-heading text-xl font-bold text-brand-navy mb-3 group-hover:text-brand-cyan transition leading-snug">
                            <a href="{{ route('blog.show', $post->slug) }}" class="focus:outline-none">
                                {{ $post->title }}
                            </a>
                        </h2>
                        <p class="text-slate-600 text-sm leading-relaxed line-clamp-3">
                            {{ $post->excerpt }}
                        </p>
                    </div>

                    <div class="px-8 py-5 bg-slate-50/80 border-t border-slate-100 flex items-center justify-between text-xs font-medium">
                        <div class="flex items-center gap-2 text-slate-600">
                            <div class="w-7 h-7 rounded-full bg-brand-navy/10 text-brand-navy flex items-center justify-center font-bold text-[10px]">
                                {{ substr($post->author_name ?? 'H', 0, 1) }}
                            </div>
                            <span class="font-semibold text-brand-navy">{{ $post->author_name ?? 'Housing 365 Editorial' }}</span>
                        </div>
                        <a href="{{ route('blog.show', $post->slug) }}" class="inline-flex items-center gap-1.5 font-bold text-brand-cyan hover:text-brand-cyan-hover transition">
                            <span>Read Guide</span>
                            <i class="fa-solid fa-arrow-right text-[11px] group-hover:translate-x-0.5 transition-transform"></i>
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-full py-16 text-center bg-white rounded-3xl border border-slate-200/80 p-12">
                    <div class="w-16 h-16 rounded-full bg-brand-cyan/10 text-brand-cyan flex items-center justify-center mx-auto mb-4 text-2xl">
                        <i class="fa-regular fa-newspaper"></i>
                    </div>
                    <h3 class="font-heading text-xl font-bold text-brand-navy mb-2">No Articles Found</h3>
                    <p class="text-slate-600 text-sm max-w-md mx-auto">Check back soon for fresh rental guides, tenancy legal updates, and market intelligence reports.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($posts->hasPages())
            <div class="mt-14 flex justify-center">
                {{ $posts->links() }}
            </div>
        @endif

        <!-- Light Modern Bottom CTA Banner -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-sky-50 via-white to-cyan-50 border border-slate-200/90 py-14 sm:py-16 px-6 sm:px-12 text-center mt-20 shadow-sm">
            <div class="pointer-events-none absolute -bottom-20 -right-20 h-72 w-72 rounded-full bg-[radial-gradient(circle,rgba(0,174,239,0.15)_0%,transparent_70%)] blur-2xl"></div>
            <div class="pointer-events-none absolute -top-20 -left-20 h-72 w-72 rounded-full bg-[radial-gradient(circle,rgba(0,174,239,0.1)_0%,transparent_70%)] blur-2xl"></div>
            
            <div class="relative z-10 max-w-2xl mx-auto">
                <span class="inline-flex items-center gap-2 rounded-full border border-brand-cyan/25 bg-brand-cyan/10 px-3.5 py-1 text-xs font-bold uppercase tracking-wider text-brand-cyan mb-4">
                    Have Specific Portfolio Inquiries?
                </span>
                <h3 class="font-display text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-brand-navy mb-4">
                    Speak Directly With a <span class="text-brand-cyan">Licensed Advisory Specialist</span>
                </h3>
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed mb-8">
                    Get custom rent comps, yield evaluations, and localized regulatory assessments tailored to your residential investments.
                </p>
                <div class="flex flex-wrap items-center justify-center gap-4">
                    <a href="{{ route('contact') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand-cyan px-7 py-3.5 text-xs font-bold uppercase tracking-wider text-white shadow-lg shadow-brand-cyan/25 hover:bg-brand-cyan-hover transition">
                        <span>Talk to an Expert</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                    <a href="tel:{{ $settings['contact_phone'] ?? '+916376163263' }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white shadow-sm px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-brand-navy hover:bg-slate-50 transition">
                        <i class="fa-solid fa-phone text-brand-cyan"></i>
                        <span>{{ $settings['contact_phone'] ?? '+91 63761 63263' }}</span>
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection
