@extends('layouts.app')

@section('title', ($service['title'] ?? 'Service') . ' | Housing 365')
@section('meta_description', $service['metaDescription'] ?? 'Housing 365 professional leasing and tenant placement services across Canada and the US.')

@section('content')

<!-- ── BREADCRUMB ── -->
<div class="bg-white border-b border-slate-100 py-3.5 text-xs text-slate-500">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-brand-cyan transition font-medium">Home</a>
        <span class="text-slate-300">/</span>
        <a href="{{ route('services') }}" class="hover:text-brand-cyan transition font-medium">Services</a>
        <span class="text-slate-300">/</span>
        <span class="text-brand-navy font-bold">{{ $service['title'] }}</span>
    </div>
</div>

<!-- ── SECTION 1: HERO SHOWCASE (LIGHT ELEGANT DESIGN) ── -->
<section class="relative overflow-hidden bg-gradient-to-b from-[#F7FAFD] via-white to-[#F0F6FC] py-16 sm:py-20 lg:py-28">
    <!-- Ambient Glow Effects -->
    <div class="pointer-events-none absolute -left-40 top-0 h-[500px] w-[500px] rounded-full opacity-40 blur-3xl" style="background: radial-gradient(circle, rgba(0,174,239,0.18) 0%, transparent 70%)" aria-hidden="true"></div>
    <div class="pointer-events-none absolute right-0 top-20 h-[600px] w-[600px] rounded-full opacity-30 blur-3xl" style="background: radial-gradient(circle, rgba(3,35,75,0.06) 0%, transparent 70%)" aria-hidden="true"></div>

    <div class="relative mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="max-w-3xl">
            <!-- Eyebrow Badge -->
            <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-brand-cyan/20 bg-brand-cyan/5 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                <span class="h-2 w-2 rounded-full bg-brand-cyan animate-pulse"></span>
                {{ $service['heroEyebrow'] ?? 'HOUSING365 SPECIALIZED LEASING' }}
            </div>

            <!-- Headline -->
            <h1 class="font-display text-4xl font-extrabold leading-[1.12] tracking-tight text-brand-navy sm:text-5xl lg:text-6xl">
                {{ $service['heroHeadline'] ?? $service['title'] }}
            </h1>

            <!-- Subheadline / Lede -->
            <p class="mt-6 text-base sm:text-lg leading-relaxed text-slate-600 max-w-2xl">
                {{ $service['heroLede'] ?? $service['metaDescription'] }}
            </p>

            <!-- CTAs -->
            <div class="mt-8 flex flex-wrap items-center gap-4">
                <a href="{{ route('contact', ['service' => $service['slug']]) }}" 
                   class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand-cyan px-8 py-4 text-base font-bold text-white shadow-lg shadow-brand-cyan/25 transition-all duration-200 hover:-translate-y-0.5 hover:bg-brand-cyan-hover">
                    <span>{{ $service['cta1Label'] ?? 'Talk to an Expert' }}</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>

                <a href="{{ route('pricing') }}" 
                   class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-7 py-4 text-base font-bold text-brand-navy shadow-sm transition hover:border-slate-400 hover:bg-slate-50">
                    <span>{{ $service['cta2Label'] ?? 'Pricing & Terms' }}</span>
                </a>
            </div>
        </div>

        <!-- 4-Stat Proof Strip (Light Modern) -->
        <div class="mt-16 sm:mt-20 grid grid-cols-2 sm:grid-cols-4 gap-4 pt-10 border-t border-slate-200">
            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
                <span class="font-display text-3xl sm:text-4xl font-extrabold text-brand-cyan">$0</span>
                <span class="text-sm font-bold text-brand-navy block mt-1">Upfront Retainers</span>
                <span class="text-xs text-slate-500">Success-fee only</span>
            </div>
            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
                <span class="font-display text-3xl sm:text-4xl font-extrabold text-brand-navy">14d</span>
                <span class="text-sm font-bold text-brand-navy block mt-1">Avg Time to Lease</span>
                <span class="text-xs text-slate-500">Market-leading velocity</span>
            </div>
            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
                <span class="font-display text-3xl sm:text-4xl font-extrabold text-emerald-600">48h</span>
                <span class="text-sm font-bold text-brand-navy block mt-1">Screening Decision</span>
                <span class="text-xs text-slate-500">75-point underwriting</span>
            </div>
            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
                <span class="font-display text-3xl sm:text-4xl font-extrabold text-brand-cyan">20+</span>
                <span class="text-sm font-bold text-brand-navy block mt-1">Active Rental Markets</span>
                <span class="text-xs text-slate-500">Canada &amp; US coverage</span>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 2: THE PROBLEM (WHY UNSTRUCTURED LEASING FAILS) ── -->
@if(!empty($service['problemPoints']))
<section class="bg-white py-16 sm:py-20 lg:py-24 border-b border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="max-w-3xl mb-12">
            <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-rose-500">
                <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                THE RISK &amp; COST OF INACTION
            </div>
            <h2 class="font-display text-3xl font-normal tracking-tight text-brand-navy sm:text-4xl">
                {{ $service['problemTitle'] ?? 'What goes wrong without a structured process' }}
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($service['problemPoints'] as $index => $point)
                <div class="rounded-2xl border border-rose-100 bg-rose-50/30 p-6 flex flex-col justify-between transition-all duration-200 hover:shadow-md hover:border-rose-200">
                    <div>
                        <div class="flex size-10 items-center justify-center rounded-xl bg-rose-100 text-rose-600 mb-4">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </div>
                        <h3 class="text-base font-bold text-brand-navy mb-2">
                            {{ $point['title'] }}
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            {{ $point['body'] }}
                        </p>
                    </div>
                    <span class="mt-6 text-[11px] font-bold text-rose-500 uppercase tracking-wider">
                        Risk Factor 0{{ $index + 1 }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ── SECTION 3: THE SOLUTION & SCOPE ── -->
<section class="bg-[#F8FAFD] py-16 sm:py-20 lg:py-24">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="max-w-3xl mb-12">
            <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                <span class="h-1.5 w-1.5 rounded-full bg-brand-cyan"></span>
                THE HOUSING365 ADVANTAGE
            </div>
            <h2 class="font-display text-3xl font-normal tracking-tight text-brand-navy sm:text-4xl">
                {{ $service['solutionTitle'] ?? ('How Housing365 Delivers ' . $service['title']) }}
            </h2>
            <p class="mt-4 text-slate-600 text-sm sm:text-base leading-relaxed">
                {{ $service['solutionLede'] ?? 'One accountable team handles every facet of the workflow on a disciplined timeline.' }}
            </p>
        </div>

        @if(!empty($service['scope']))
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($service['scope'] as $idx => $item)
                <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm transition hover:shadow-md hover:border-brand-cyan/30">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="flex size-7 shrink-0 items-center justify-center rounded-full bg-brand-cyan text-white text-xs shadow-sm">
                            <i class="fa-solid fa-check text-[10px]"></i>
                        </div>
                        <h3 class="text-base font-bold text-brand-navy">
                            {{ $item['title'] }}
                        </h3>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed pl-10">
                        {{ $item['body'] }}
                    </p>
                </div>
            @endforeach
        </div>
        @endif
    </div>
</section>

<!-- ── SECTION 4: HOW IT WORKS (STEP-BY-STEP WORKFLOW) ── -->
@if(!empty($service['howItWorks']))
<section class="bg-white py-16 sm:py-20 lg:py-24 border-y border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="max-w-3xl mb-14">
            <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                <span class="h-1.5 w-1.5 rounded-full bg-brand-cyan"></span>
                OUR PROVEN PROCESS
            </div>
            <h2 class="font-display text-3xl font-normal tracking-tight text-brand-navy sm:text-4xl">
                Step-by-Step Execution
            </h2>
            <p class="mt-3 text-slate-600 text-sm sm:text-base">
                From initial onboarding to final sign-off, every milestone is tracked and visible in real time.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-{{ min(count($service['howItWorks']), 5) }} gap-6 relative">
            @foreach($service['howItWorks'] as $step)
                <div class="relative flex flex-col justify-between rounded-2xl border border-slate-100 bg-[#F8FAFD] p-6">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="font-display text-3xl font-bold text-brand-cyan">
                                0{{ $step['step'] }}
                            </span>
                            <span class="size-8 rounded-full bg-sky-50 text-brand-cyan flex items-center justify-center text-xs">
                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </span>
                        </div>
                        <h3 class="text-base font-bold text-brand-navy mb-2">
                            {{ $step['title'] }}
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            {{ $step['body'] }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ── SECTION 5: WHO IT'S FOR ── -->
@if(!empty($service['whoItsFor']))
<section class="bg-[#F8FAFD] py-16 sm:py-20 lg:py-24">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="max-w-3xl mb-12">
            <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                <span class="h-1.5 w-1.5 rounded-full bg-brand-cyan"></span>
                IDEAL AUDIENCES
            </div>
            <h2 class="font-display text-3xl font-normal tracking-tight text-brand-navy sm:text-4xl">
                Who This Service Is Built For
            </h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($service['whoItsFor'] as $target)
                <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                    <div class="flex size-11 items-center justify-center rounded-xl bg-sky-50 text-brand-cyan mb-4">
                        <i class="fa-solid fa-user-tie text-lg"></i>
                    </div>
                    <h3 class="text-base font-bold text-brand-navy mb-2">
                        {{ $target['audience'] }}
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        {{ $target['fitNote'] }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ── SECTION 6: PRICING & TRANSPARENCY CALLOUT ── -->
@if(!empty($service['pricingNote']))
<section class="bg-white py-12 sm:py-16">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="rounded-3xl border border-brand-cyan/20 bg-gradient-to-r from-sky-50 via-white to-sky-50/50 p-8 sm:p-10 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="max-w-2xl">
                <div class="flex items-center gap-2 text-xs font-bold text-brand-cyan uppercase tracking-wider mb-2">
                    <i class="fa-solid fa-shield-halved"></i>
                    Transparent &amp; Aligned Incentives
                </div>
                <h3 class="font-display text-2xl font-bold text-brand-navy">
                    Zero Upfront Retainer Policy
                </h3>
                <p class="text-slate-600 text-sm mt-2 leading-relaxed">
                    {{ $service['pricingNote'] }}
                </p>
            </div>

            <div class="shrink-0">
                <a href="{{ route('pricing') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand-cyan px-6 py-3.5 text-sm font-bold text-white shadow-md shadow-brand-cyan/25 transition hover:bg-brand-cyan-hover">
                    <span>View Pricing Guide</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    </div>
</section>
@endif

<!-- ── SECTION 7: FAQ ACCORDION (FULL WIDTH 2-COLUMN) ── -->
@if(!empty($service['faqItems']))
<section class="bg-[#F8FAFD] py-16 sm:py-20 lg:py-24 border-t border-slate-200/80">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12 pb-6 border-b border-slate-200">
            <div>
                <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                    <span class="h-2 w-2 rounded-full bg-brand-cyan"></span>
                    SERVICE CLARITY &middot; COMMON QUESTIONS
                </div>
                <h2 class="font-display text-3xl font-extrabold tracking-tight text-brand-navy sm:text-4xl">
                    Frequently Asked Questions
                </h2>
                <p class="mt-2 text-base text-slate-600 max-w-2xl">
                    Everything you need to know about our {{ strtolower($service['title'] ?? 'leasing') }} process, guarantees, and deliverables.
                </p>
            </div>
            <div class="shrink-0">
                <a href="{{ url('/contact?type=owner') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-bold text-brand-navy shadow-sm transition-all hover:bg-slate-50 hover:border-slate-400">
                    <span>Ask Our Leasing Specialists</span>
                    <i class="fa-solid fa-arrow-right text-xs text-brand-cyan"></i>
                </a>
            </div>
        </div>

        @php
            $items = $service['faqItems'] ?? [];
            $half = ceil(count($items) / 2);
            $leftFaqs = array_slice($items, 0, $half);
            $rightFaqs = array_slice($items, $half);
        @endphp
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start w-full">
            <!-- Left Column Stack -->
            <div class="space-y-6">
                @foreach($leftFaqs as $idx => $faq)
                    <div class="faq-item rounded-2xl border border-slate-200/90 bg-white p-6 shadow-sm transition-all duration-200 hover:border-brand-cyan/40">
                        <button type="button" 
                                onclick="toggleFaq({{ $idx }})"
                                class="w-full flex items-start justify-between gap-4 text-left">
                            <div class="flex items-start gap-3.5">
                                <span class="mt-0.5 inline-flex items-center justify-center rounded-lg bg-sky-50 px-2.5 py-1 text-xs font-bold tracking-wide text-brand-cyan border border-sky-100">
                                    {{ str_pad($idx + 1, 2, '0', STR_PAD_LEFT) }}
                                </span>
                                <h3 class="text-base sm:text-lg font-bold leading-snug tracking-tight text-brand-navy">
                                    {{ $faq['question'] }}
                                </h3>
                            </div>
                            <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full border border-slate-200 bg-slate-50 text-slate-500 transition-transform duration-300">
                                <i id="faq-icon-{{ $idx }}" class="fa-solid fa-chevron-down text-xs transition-transform duration-200"></i>
                            </span>
                        </button>
                        <div id="faq-body-{{ $idx }}" class="hidden pt-4 mt-4 border-t border-slate-100 text-sm sm:text-base text-slate-600 leading-relaxed">
                            {{ $faq['answer'] }}
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Right Column Stack -->
            <div class="space-y-6">
                @foreach($rightFaqs as $rIdx => $faq)
                    @php $actualIdx = $half + $rIdx; @endphp
                    <div class="faq-item rounded-2xl border border-slate-200/90 bg-white p-6 shadow-sm transition-all duration-200 hover:border-brand-cyan/40">
                        <button type="button" 
                                onclick="toggleFaq({{ $actualIdx }})"
                                class="w-full flex items-start justify-between gap-4 text-left">
                            <div class="flex items-start gap-3.5">
                                <span class="mt-0.5 inline-flex items-center justify-center rounded-lg bg-sky-50 px-2.5 py-1 text-xs font-bold tracking-wide text-brand-cyan border border-sky-100">
                                    {{ str_pad($actualIdx + 1, 2, '0', STR_PAD_LEFT) }}
                                </span>
                                <h3 class="text-base sm:text-lg font-bold leading-snug tracking-tight text-brand-navy">
                                    {{ $faq['question'] }}
                                </h3>
                            </div>
                            <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full border border-slate-200 bg-slate-50 text-slate-500 transition-transform duration-300">
                                <i id="faq-icon-{{ $actualIdx }}" class="fa-solid fa-chevron-down text-xs transition-transform duration-200"></i>
                            </span>
                        </button>
                        <div id="faq-body-{{ $actualIdx }}" class="hidden pt-4 mt-4 border-t border-slate-100 text-sm sm:text-base text-slate-600 leading-relaxed">
                            {{ $faq['answer'] }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

<!-- ── SECTION 8: RELATED SERVICES ── -->
@if(!empty($relatedServices))
<section class="bg-white py-16 sm:py-20 lg:py-24 border-t border-slate-100">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
            <div>
                <div class="mb-2 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                    <span class="h-1.5 w-1.5 rounded-full bg-brand-cyan"></span>
                    COMPLEMENTARY CAPABILITIES
                </div>
                <h2 class="font-display text-2xl sm:text-3xl font-normal tracking-tight text-brand-navy">
                    Related Leasing Services
                </h2>
            </div>
            <a href="{{ route('services') }}" class="text-xs font-bold text-brand-cyan hover:underline flex items-center gap-1">
                <span>All Services</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($relatedServices as $rel)
                <a href="{{ route('services.show', $rel['slug']) }}" class="group rounded-2xl border border-slate-100 bg-[#F8FAFD] p-6 shadow-sm transition hover:shadow-lg hover:border-brand-cyan/30 hover:bg-white flex flex-col justify-between">
                    <div>
                        <div class="flex size-11 items-center justify-center rounded-xl bg-sky-50 text-brand-cyan group-hover:bg-brand-cyan group-hover:text-white transition-colors mb-4">
                            <i class="fa-solid fa-building-circle-check text-lg"></i>
                        </div>
                        <h3 class="text-lg font-bold text-brand-navy group-hover:text-brand-cyan transition">
                            {{ $rel['title'] }}
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 mt-2 line-clamp-3 leading-relaxed">
                            {{ $rel['metaDescription'] ?? $rel['heroLede'] }}
                        </p>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-200/50 flex items-center gap-1.5 text-xs font-bold text-brand-cyan">
                        <span>Explore Service</span>
                        <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ── SECTION 9: CALL TO ACTION BANNER (LIGHT MODERN) ── -->
<section class="py-16 sm:py-20 lg:py-24 bg-gradient-to-r from-sky-50 via-white to-cyan-50">
    <div class="mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="relative overflow-hidden rounded-3xl border border-brand-cyan/20 bg-white p-8 sm:p-14 lg:p-16 shadow-xl flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="max-w-2xl">
                <div class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-cyan">
                    <span class="size-2 rounded-full bg-brand-cyan animate-pulse"></span>
                    GET STARTED TODAY
                </div>
                <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold text-brand-navy">
                    Ready to Optimize Your Rental Strategy?
                </h2>
                <p class="mt-4 text-base sm:text-lg text-slate-600">
                    Speak with our leasing and property specialists for a complimentary market evaluation.
                </p>
            </div>

            <div class="shrink-0 flex flex-wrap gap-4">
                <a href="{{ route('contact', ['service' => $service['slug']]) }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand-cyan px-8 py-4 text-base font-bold text-white shadow-lg shadow-brand-cyan/25 transition-all duration-200 hover:-translate-y-0.5 hover:bg-brand-cyan-hover">
                    <span>Talk to an Expert</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    function toggleFaq(idx) {
        const body = document.getElementById(`faq-body-${idx}`);
        const icon = document.getElementById(`faq-icon-${idx}`);
        if (!body || !icon) return;

        if (body.classList.contains('hidden')) {
            body.classList.remove('hidden');
            icon.classList.add('rotate-180');
        } else {
            body.classList.add('hidden');
            icon.classList.remove('rotate-180');
        }
    }
</script>
@endpush

@endsection
