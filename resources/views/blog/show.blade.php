@extends('layouts.app')

@section('title', $post->title . ' | Housing 365 Guides')
@section('meta_description', Str::limit(strip_tags($post->excerpt ?? $post->content), 150))

@section('content')

<!-- Breadcrumb -->
<div class="bg-slate-50 border-b border-slate-200/80 py-3.5 text-xs text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-cyan transition">Home</a>
        <span class="text-slate-300">/</span>
        <a href="{{ route('blog.index') }}" class="hover:text-brand-cyan transition">Guides &amp; Updates</a>
        <span class="text-slate-300">/</span>
        <span class="text-brand-navy font-bold truncate max-w-sm">{{ $post->title }}</span>
    </div>
</div>

<!-- Article Header / Hero -->
<section class="relative overflow-hidden bg-gradient-to-b from-[#F7FAFD] via-white to-white py-14 sm:py-20 border-b border-slate-100">
    <div class="pointer-events-none absolute -top-40 right-1/4 h-96 w-96 rounded-full bg-[radial-gradient(circle,rgba(0,174,239,0.18)_0%,transparent_70%)] blur-3xl"></div>
    <div class="pointer-events-none absolute top-10 left-10 h-72 w-72 rounded-full bg-[radial-gradient(circle,rgba(3,35,75,0.08)_0%,transparent_70%)] blur-2xl"></div>

    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 relative z-10 max-w-4xl text-center">
        <div class="inline-flex items-center gap-2 rounded-full border border-brand-cyan/25 bg-brand-cyan/10 px-3.5 py-1 text-xs font-semibold uppercase tracking-wider text-brand-cyan mb-4">
            <span class="h-1.5 w-1.5 rounded-full bg-brand-cyan"></span>
            {{ $post->category ?? 'Rental Guide' }}
        </div>
        <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl font-normal tracking-tight text-brand-navy leading-[1.2] mb-6">
            {{ $post->title }}
        </h1>
        <div class="flex flex-wrap items-center justify-center gap-3 sm:gap-6 text-xs text-slate-500 font-medium">
            <span class="flex items-center gap-1.5">
                <i class="fa-solid fa-user-pen text-brand-cyan"></i>
                <span>By <strong class="text-brand-navy font-semibold">{{ $post->author_name ?? 'Housing 365 Team' }}</strong></span>
            </span>
            <span class="text-slate-300">&bull;</span>
            <span class="flex items-center gap-1.5">
                <i class="fa-regular fa-calendar text-brand-cyan"></i>
                <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : now()->format('M d, Y') }}</span>
            </span>
            <span class="text-slate-300">&bull;</span>
            <span class="flex items-center gap-1.5">
                <i class="fa-regular fa-clock text-brand-cyan"></i>
                <span>{{ $post->read_time ?? '5 min read' }}</span>
            </span>
        </div>
    </div>
</section>

<!-- Article Main Body -->
<section class="py-14 sm:py-16 bg-slate-50/50">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 xl:gap-14">
            
            <!-- Left / Center Article Content -->
            <article class="lg:col-span-8 space-y-8">
                
                <div class="bg-white rounded-3xl p-8 sm:p-12 border border-slate-200/80 shadow-sm leading-relaxed text-slate-700 space-y-6">
                    @if($post->excerpt)
                        <div class="rounded-2xl bg-gradient-to-r from-brand-cyan/10 via-brand-cyan/5 to-transparent p-6 border-l-4 border-brand-cyan text-base sm:text-lg font-medium text-brand-navy leading-relaxed italic">
                            {{ $post->excerpt }}
                        </div>
                    @endif

                    <div class="text-base sm:text-[17px] text-slate-700 leading-relaxed space-y-5">
                        {!! nl2br(e($post->content)) !!}
                    </div>
                </div>

                <!-- Post Footer Action Bar -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-6 border-t border-slate-200">
                    <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-brand-navy hover:text-brand-cyan transition">
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                        <span>Back to All Guides</span>
                    </a>
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Share:</span>
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ urlencode(url()->current()) }}" target="_blank" class="w-9 h-9 rounded-full bg-white border border-slate-200 text-slate-600 hover:text-brand-cyan hover:border-brand-cyan flex items-center justify-center text-xs transition">
                            <i class="fa-brands fa-x-twitter"></i>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" target="_blank" class="w-9 h-9 rounded-full bg-white border border-slate-200 text-slate-600 hover:text-brand-cyan hover:border-brand-cyan flex items-center justify-center text-xs transition">
                            <i class="fa-brands fa-linkedin-in"></i>
                        </a>
                        <a href="mailto:?subject={{ urlencode($post->title) }}&body={{ urlencode(url()->current()) }}" class="w-9 h-9 rounded-full bg-white border border-slate-200 text-slate-600 hover:text-brand-cyan hover:border-brand-cyan flex items-center justify-center text-xs transition">
                            <i class="fa-regular fa-envelope"></i>
                        </a>
                    </div>
                </div>

            </article>

            <!-- Right Sidebar -->
            <aside class="lg:col-span-4 space-y-8">
                
                <!-- Advisory CTA Card (Light Modern) -->
                <div class="rounded-3xl bg-gradient-to-br from-sky-50 via-white to-cyan-50 border border-brand-cyan/30 p-8 relative overflow-hidden shadow-sm">
                    <div class="pointer-events-none absolute -bottom-10 -right-10 h-40 w-40 rounded-full bg-[radial-gradient(circle,rgba(0,174,239,0.15)_0%,transparent_70%)] blur-xl"></div>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-brand-cyan/15 border border-brand-cyan/25 px-3 py-1 text-xs font-bold text-brand-cyan uppercase tracking-wider mb-4">
                        <span class="h-1.5 w-1.5 rounded-full bg-brand-cyan"></span>
                        Complimentary Advisory
                    </span>
                    <h3 class="font-display text-2xl font-bold text-brand-navy mb-3">Maximize Your Rental Cash Flow</h3>
                    <p class="text-slate-600 text-sm leading-relaxed mb-6">
                        Get verified market comps, tenant screening analysis, and yield optimization strategies tailored to your property.
                    </p>
                    <a href="{{ route('contact') }}" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-brand-cyan py-3.5 px-5 text-xs font-bold uppercase tracking-wider text-white shadow-md shadow-brand-cyan/25 hover:bg-brand-cyan-hover transition">
                        <span>Talk to an Expert</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

                <!-- Recent Articles -->
                @if(isset($recentPosts) && $recentPosts->count() > 0)
                    <div class="rounded-3xl bg-white border border-slate-200/80 p-8 shadow-sm">
                        <h4 class="font-heading text-lg font-bold text-brand-navy mb-5 flex items-center gap-2">
                            <i class="fa-solid fa-newspaper text-brand-cyan text-sm"></i>
                            <span>Related Guides</span>
                        </h4>
                        <div class="space-y-5">
                            @foreach($recentPosts as $recent)
                                <div class="group border-b border-slate-100 last:border-0 pb-4 last:pb-0">
                                    <span class="text-[11px] font-semibold text-brand-cyan uppercase tracking-wider block mb-1">
                                        {{ $recent->category ?? 'Insights' }}
                                    </span>
                                    <h5 class="font-heading text-sm font-bold text-brand-navy group-hover:text-brand-cyan transition leading-snug">
                                        <a href="{{ route('blog.show', $recent->slug) }}">
                                            {{ $recent->title }}
                                        </a>
                                    </h5>
                                    <span class="text-[11px] text-slate-400 mt-1 block">
                                        {{ $recent->read_time ?? '5 min read' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Direct Phone Box -->
                <div class="rounded-3xl bg-white border border-slate-200/80 p-6 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-brand-cyan/10 text-brand-cyan flex items-center justify-center text-lg shrink-0">
                        <i class="fa-solid fa-phone"></i>
                    </div>
                    <div>
                        <span class="text-xs text-slate-400 font-medium block">Direct Inquiries</span>
                        <a href="tel:{{ $settings['contact_phone'] ?? '+916376163263' }}" class="font-heading font-bold text-brand-navy hover:text-brand-cyan text-sm transition">
                            {{ $settings['contact_phone'] ?? '+91 63761 63263' }}
                        </a>
                    </div>
                </div>

            </aside>

        </div>

        <!-- Light Modern Bottom CTA Banner -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-sky-50 via-white to-cyan-50 border border-slate-200/90 py-14 sm:py-16 px-6 sm:px-12 text-center mt-20 shadow-sm">
            <div class="pointer-events-none absolute -bottom-20 -right-20 h-72 w-72 rounded-full bg-[radial-gradient(circle,rgba(0,174,239,0.15)_0%,transparent_70%)] blur-2xl"></div>
            <div class="pointer-events-none absolute -top-20 -left-20 h-72 w-72 rounded-full bg-[radial-gradient(circle,rgba(0,174,239,0.1)_0%,transparent_70%)] blur-2xl"></div>
            
            <div class="relative z-10 max-w-2xl mx-auto">
                <span class="inline-flex items-center gap-2 rounded-full border border-brand-cyan/25 bg-brand-cyan/10 px-3.5 py-1 text-xs font-bold uppercase tracking-wider text-brand-cyan mb-4">
                    Housing 365 Residential Management
                </span>
                <h3 class="font-display text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-brand-navy mb-4">
                    Turn Your Rental Property Into a <span class="text-brand-cyan">Hands-Off Asset</span>
                </h3>
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed mb-8">
                    Discover why property owners and NRIs across Mumbai, Bengaluru, Delhi NCR, Hyderabad, Pune, and all 28 states &amp; UTs trust Housing365 for tenant screening, legal leasing, and property care.
                </p>
                <div class="flex flex-wrap items-center justify-center gap-4">
                    <a href="{{ route('contact') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand-cyan px-7 py-3.5 text-xs font-bold uppercase tracking-wider text-white shadow-lg shadow-brand-cyan/25 hover:bg-brand-cyan-hover transition">
                        <span>Talk to an Expert</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                    <a href="{{ route('pricing') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white shadow-sm px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-brand-navy hover:bg-slate-50 transition">
                        <span>View Transparent Pricing</span>
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection
