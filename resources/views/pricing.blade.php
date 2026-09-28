@extends('layouts.app')

@section('title', $page->meta_title ?? 'Leasing Platform Pricing India | Transparent Success-Fee Leasing | Housing365 India')
@section('meta_description', $page->meta_description ?? 'Transparent leasing platform pricing across India. Performance-based success fee with no monthly retainers. Pay our success fee only when a qualified tenant signs the lease. Custom pricing for institutional lease-up.')

@section('content')

<!-- ── SECTION 1: HERO (LIGHT ELEGANT DESIGN) ── -->
<section class="relative isolate overflow-hidden bg-gradient-to-b from-[#F7FAFD] via-white to-[#F0F6FC]">
    <!-- Ambient cyan & gold glows -->
    <div class="pointer-events-none absolute -left-32 top-1/2 h-[520px] w-[520px] -translate-y-1/2 rounded-full bg-brand-cyan/10 blur-3xl"></div>
    <div class="pointer-events-none absolute -right-24 bottom-0 h-[420px] w-[420px] rounded-full bg-[#00AEEF]/10 blur-3xl"></div>

    <!-- Gold hairline top -->
    <div aria-hidden="true" class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-[#00AEEF]/50 to-transparent"></div>

    <!-- Breadcrumb sits inline at the top -->
    <div class="relative mx-auto max-w-7xl px-4 pt-8 text-slate-500">
        <nav aria-label="breadcrumb" class="text-xs sm:text-sm">
            <ol class="flex items-center gap-2">
                <li><a href="{{ url('/') }}" class="hover:text-brand-cyan transition-colors">Home</a></li>
                <li class="text-[#00AEEF]">/</li>
                <li class="text-brand-navy font-bold">Pricing</li>
            </ol>
        </nav>
    </div>

    <div class="relative mx-auto grid w-full max-w-7xl grid-cols-1 items-start gap-10 px-4 py-14 sm:px-6 sm:py-16 lg:grid-cols-12 lg:gap-12 lg:px-8 lg:py-20">
        <!-- LEFT: kicker, eyebrow, headline, lede, CTAs -->
        <div class="lg:col-span-7">
            <!-- Kicker -->
            <div class="mb-5 flex items-center gap-3">
                <span aria-hidden="true" class="h-px w-8 bg-[#00AEEF]"></span>
                <span class="text-xs font-bold uppercase tracking-[0.22em] text-[#03234B]">
                    Pricing Schedule
                </span>
            </div>

            <!-- Eyebrow chip -->
            <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-brand-cyan/20 bg-brand-cyan/5 px-3.5 py-1">
                <span class="h-1.5 w-1.5 rounded-full bg-brand-cyan" aria-hidden="true"></span>
                <span class="text-[11px] font-bold uppercase tracking-widest text-brand-cyan">
                    {{ $page->hero_badge ?? 'Transparent Pricing. Success-Fee Leasing.' }}
                </span>
            </div>

            <!-- Headline -->
            <h1 class="font-display text-4xl font-extrabold leading-[1.08] tracking-tight text-brand-navy sm:text-5xl lg:text-[3.5rem]">
                {!! $page->hero_title ?? 'Pay Nothing Until We Place a <span class="text-brand-cyan">Tenant</span><span aria-hidden="true" class="text-[#00AEEF]">.</span>' !!}
            </h1>

            <!-- Lede -->
            <p class="mt-6 max-w-2xl text-base leading-relaxed text-slate-600 sm:text-lg md:text-xl">
                {{ $page->hero_subtitle ?? 'Housing365 is a full-service leasing and property management company. There are no recurring monthly retainers or ongoing management percentages. You pay our leasing success fee — typically equivalent to one month of rent — only when a qualified tenant signs the lease.' }}
            </p>

            <!-- CTAs -->
            <div class="mt-8 flex flex-row flex-wrap items-center gap-4">
                <a
                    href="{{ url('/contact?type=owner') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand-cyan px-8 py-4 text-base font-bold text-white shadow-lg shadow-brand-cyan/25 transition-all duration-200 hover:-translate-y-0.5 hover:bg-brand-cyan-hover"
                >
                    <span>Talk to an Expert</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
                <a
                    href="{{ url('/contact?type=owner') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-7 py-4 text-base font-bold text-brand-navy shadow-sm transition-all duration-200 hover:bg-slate-50 hover:border-slate-400"
                >
                    List My Property
                </a>
            </div>
        </div>

        <!-- RIGHT: ₹0 / One-time spec sheet on glass -->
        <aside class="relative lg:col-span-5 lg:pl-8">
            <!-- Vertical gold rule on desktop -->
            <span
                aria-hidden="true"
                class="hidden lg:absolute lg:left-0 lg:top-1 lg:block lg:h-[calc(100%-0.5rem)] lg:w-px lg:bg-gradient-to-b lg:from-[#00AEEF]/70 lg:via-[#00AEEF]/30 lg:to-transparent"
            ></span>

            <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-[#03234B]">
                At a glance &middot; 2026 schedule
            </p>

            <!-- ₹0 callout -->
            <div class="relative mt-4 inline-block font-display text-[3.5rem] font-extrabold leading-[0.95] text-brand-cyan sm:text-[4.5rem]">
                <span class="relative inline-block">
                    ₹0
                </span>
                <span class="text-[#00AEEF]">.</span>
            </div>
            <p class="mt-2 text-sm uppercase tracking-[0.18em] text-slate-500 font-semibold">
                Monthly retainer. No ongoing percentage fees.
            </p>

            <div class="mt-6 font-display text-[3.5rem] font-extrabold leading-[0.95] text-brand-navy sm:text-[4.5rem]">
                One-time<span class="text-[#00AEEF]">.</span>
            </div>
            <p class="mt-2 text-sm uppercase tracking-[0.18em] text-slate-500 font-semibold">
                Leasing success fee &mdash; only when a qualified tenant signs.
            </p>

            <!-- Inline fact chips -->
            <div class="mt-6 flex flex-wrap gap-x-4 gap-y-2 text-xs font-bold uppercase tracking-[0.16em] text-slate-600">
                <span>₹0 retainer</span>
                <span aria-hidden="true" class="text-[#00AEEF]">/</span>
                <span>No monthly fee</span>
                <span aria-hidden="true" class="text-[#00AEEF]">/</span>
                <span>0 hidden costs</span>
            </div>

            <a
                href="#fee-schedule"
                class="mt-7 inline-flex items-center gap-2 border-b border-brand-cyan/40 pb-1 text-sm font-semibold text-brand-cyan transition-colors hover:border-brand-cyan hover:text-brand-navy"
            >
                Read the full fee schedule <i class="fa-solid fa-arrow-down text-xs ml-1"></i>
            </a>
        </aside>
    </div>

    <!-- Gold hairline bottom -->
    <div aria-hidden="true" class="absolute inset-x-0 bottom-0 h-px bg-gradient-to-r from-transparent via-[#00AEEF]/40 to-transparent"></div>
</section>

<!-- ── SECTION 2: § 01 · HOW WE CHARGE (3 Editorial Magazine Cards) ── -->
<section class="relative overflow-hidden bg-[#FBFAF6] py-20 lg:py-28">
    <!-- Decorative dot grid -->
    <div
        aria-hidden="true"
        class="pointer-events-none absolute inset-0 opacity-[0.04]"
        style="background-image: radial-gradient(#03234B 1px, transparent 1px); background-size: 28px 28px;"
    ></div>

    <div class="relative mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div>
            <div class="flex items-center gap-3">
                <span aria-hidden="true" class="h-px w-8 bg-[#00AEEF]/60"></span>
                <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-brand-navy/60">
                    &sect; 01 &middot; How we charge
                </p>
            </div>
            <h2 class="mt-3 max-w-3xl font-display text-3xl font-normal tracking-tight text-brand-navy sm:text-4xl lg:text-[2.6rem]">
                One fee. Earned on <span class="font-display font-medium text-brand-cyan">results</span><span class="text-[#00AEEF]">.</span>
            </h2>
            <p class="mt-5 max-w-2xl text-base leading-relaxed text-slate-600 sm:text-lg">
                Three principles shape every engagement letter we sign. Each one is visible long before you commit a single rupee.
            </p>
        </div>

        <div class="mt-14 grid grid-cols-1 gap-7 lg:grid-cols-3">
            <!-- Card 01: Transparent Onboarding -->
            <article class="group relative flex h-full flex-col overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition-all duration-500 hover:-translate-y-1 hover:border-brand-cyan/40 hover:shadow-2xl hover:shadow-brand-navy/10">
                <div class="relative aspect-[4/3] overflow-hidden bg-slate-100">
                    <img
                        src="https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=1600&q=80&auto=format&fit=crop"
                        alt="Owner handing over keys to a new tenant on move-in day"
                        class="h-full w-full object-cover transition-transform duration-[1200ms] ease-out group-hover:scale-105"
                    />
                    <div aria-hidden="true" class="absolute inset-0 bg-gradient-to-t from-brand-navy/85 via-brand-navy/20 to-transparent"></div>
                    <div class="absolute left-5 top-5 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-[10px] font-bold uppercase tracking-[0.22em] text-white backdrop-blur-md">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#00AEEF]" aria-hidden="true"></span>
                        <span>01</span>
                    </div>
                    <div class="absolute inset-x-5 bottom-5">
                        <p class="text-[10px] font-bold uppercase tracking-[0.22em] text-[#00AEEF]">
                            Transparent Setup
                        </p>
                        <p class="mt-1 font-display text-4xl font-normal leading-none text-white sm:text-5xl">
                            ₹199<span class="text-[#00AEEF]">.</span>
                        </p>
                    </div>
                </div>
                <div class="flex flex-1 flex-col gap-3 p-6 sm:p-7">
                    <h3 class="text-xl font-bold text-brand-navy">
                        All-Inclusive Onboarding.
                    </h3>
                    <p class="text-sm sm:text-base leading-relaxed text-slate-600">
                        Professional HDR photography, syndication across 40+ portals, screening, and documentation setup. No recurring monthly retainers or markups — our primary leasing placement fee is earned only when a qualified tenant is placed. (Government stamp duty, e-stamping, and statutory registration fees at actual extra cost).
                    </p>
                </div>
            </article>

            <!-- Card 02: One Success Fee -->
            <article class="group relative flex h-full flex-col overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition-all duration-500 hover:-translate-y-1 hover:border-brand-cyan/40 hover:shadow-2xl hover:shadow-brand-navy/10">
                <div class="relative aspect-[4/3] overflow-hidden bg-slate-100">
                    <img
                        src="https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=1600&q=80"
                        alt="Owner and tenant signing a residential lease at a table"
                        class="h-full w-full object-cover transition-transform duration-[1200ms] ease-out group-hover:scale-105"
                    />
                    <div aria-hidden="true" class="absolute inset-0 bg-gradient-to-t from-brand-navy/85 via-brand-navy/20 to-transparent"></div>
                    <div class="absolute left-5 top-5 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-[10px] font-bold uppercase tracking-[0.22em] text-white backdrop-blur-md">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#00AEEF]" aria-hidden="true"></span>
                        <span>02</span>
                    </div>
                    <div class="absolute inset-x-5 bottom-5">
                        <p class="text-[10px] font-bold uppercase tracking-[0.22em] text-[#00AEEF]">
                            One success fee
                        </p>
                        <p class="mt-1 font-display text-4xl font-normal leading-none text-white sm:text-5xl">
                            Once<span class="text-[#00AEEF]">.</span>
                        </p>
                    </div>
                </div>
                <div class="flex flex-1 flex-col gap-3 p-6 sm:p-7">
                    <h3 class="text-xl font-bold text-brand-navy">
                        Paid only when a lease is signed.
                    </h3>
                    <p class="text-sm sm:text-base leading-relaxed text-slate-600">
                        When a qualified tenant signs a lease we negotiated, you pay a single one-time success fee — typically equivalent to one month of contracted rent. Invoiced once, on placement. We are a leasing platform, not an ongoing manager: there is no monthly percentage, ever.
                    </p>
                </div>
            </article>

            <!-- Card 03: No Surprises -->
            <article class="group relative flex h-full flex-col overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition-all duration-500 hover:-translate-y-1 hover:border-brand-cyan/40 hover:shadow-2xl hover:shadow-brand-navy/10">
                <div class="relative aspect-[4/3] overflow-hidden bg-slate-100">
                    <img
                        src="https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=1600&q=80&auto=format&fit=crop"
                        alt="Bright modern living room in a recently leased condo"
                        class="h-full w-full object-cover transition-transform duration-[1200ms] ease-out group-hover:scale-105"
                    />
                    <div aria-hidden="true" class="absolute inset-0 bg-gradient-to-t from-brand-navy/85 via-brand-navy/20 to-transparent"></div>
                    <div class="absolute left-5 top-5 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-[10px] font-bold uppercase tracking-[0.22em] text-white backdrop-blur-md">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#00AEEF]" aria-hidden="true"></span>
                        <span>03</span>
                    </div>
                    <div class="absolute inset-x-5 bottom-5">
                        <p class="text-[10px] font-bold uppercase tracking-[0.22em] text-[#00AEEF]">
                            No surprises
                        </p>
                        <p class="mt-1 font-display text-4xl font-normal leading-none text-white sm:text-5xl">
                            Nothing<span class="text-[#00AEEF]">.</span>
                        </p>
                    </div>
                </div>
                <div class="flex flex-1 flex-col gap-3 p-6 sm:p-7">
                    <h3 class="text-xl font-bold text-brand-navy">
                        And nothing in between.
                    </h3>
                    <p class="text-sm sm:text-base leading-relaxed text-slate-600">
                        No vendor markups. No marketing surcharges. No listing-prep surcharges. No clawbacks. Optional add-ons (E-stamping &amp; registration, paid advertising beyond standard syndication) are clearly named and owner-approved. Institutional lease-up engagements are quoted per RFP. Everything else is on this page.
                    </p>
                </div>
            </article>
        </div>
    </div>
</section>

<!-- ── SECTION 3: EDITORIAL BRIDGE (What Zero-Upfront Actually Covers) ── -->
<section class="bg-white py-16 sm:py-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 items-center gap-10 lg:grid-cols-2 lg:gap-14">
            <!-- Left: Modern Living Room Image -->
            <div class="relative aspect-[4/3] overflow-hidden rounded-2xl shadow-lg ring-1 ring-brand-navy/10">
                <img
                    src="https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=1600&q=80&auto=format&fit=crop"
                    alt="Bright modern living room in a recently leased rental condo"
                    class="h-full w-full object-cover"
                />
                <div aria-hidden="true" class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-[#00AEEF]/70 to-transparent"></div>
                <div aria-hidden="true" class="absolute inset-x-0 bottom-0 h-px bg-gradient-to-r from-transparent via-[#00AEEF]/70 to-transparent"></div>
            </div>

            <!-- Right: Content & List -->
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-brand-cyan">
                    What zero-upfront actually covers
                </p>
                <h2 class="mt-3 font-display text-3xl font-normal leading-[1.15] text-brand-navy sm:text-4xl">
                    We carry the cost of getting your unit <span class="font-display font-medium text-brand-cyan">market-ready</span><span class="text-[#00AEEF]">.</span>
                </h2>
                <p class="mt-4 text-base leading-relaxed text-slate-600 sm:text-lg">
                    Before a single rupee changes hands, we have already invested in your listing. Everything below is on our balance sheet until a qualified tenant signs.
                </p>

                <ul class="mt-6 divide-y divide-slate-100 border-y border-slate-100">
                    <li class="flex items-baseline justify-between gap-4 py-3.5">
                        <span class="font-semibold text-brand-navy text-sm sm:text-base">Professional photography &amp; video</span>
                        <span class="hidden text-right text-xs text-slate-500 sm:inline">Camera, wide-angle lens, edited gallery.</span>
                    </li>
                    <li class="flex items-baseline justify-between gap-4 py-3.5">
                        <span class="font-semibold text-brand-navy text-sm sm:text-base">MLS + 40-portal syndication</span>
                        <span class="hidden text-right text-xs text-slate-500 sm:inline">Realtor.ca, Zumper, Zolo, Kijiji, Marketplace.</span>
                    </li>
                    <li class="flex items-baseline justify-between gap-4 py-3.5">
                        <span class="font-semibold text-brand-navy text-sm sm:text-base">Applicant screening &amp; references</span>
                        <span class="hidden text-right text-xs text-slate-500 sm:inline">Credit, employment, income, prior-tenancy.</span>
                    </li>
                    <li class="flex items-baseline justify-between gap-4 py-3.5">
                        <span class="font-semibold text-brand-navy text-sm sm:text-base">Lease drafting &amp; e-signing</span>
                        <span class="hidden text-right text-xs text-slate-500 sm:inline">11-Month e-stamped registered Leave & License agreement, State Stamp Duty compliance and addenda.</span>
                    </li>
                </ul>

                <p class="mt-5 text-[11px] font-bold uppercase tracking-[0.18em] text-brand-navy/60">
                    Invoiced once &middot; only on signed lease
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 4: § 02 · THE FEE SCHEDULE (Hero Price Banner + Magazine Table) ── -->
<section id="fee-schedule" class="relative overflow-hidden bg-gradient-to-br from-cyan-50/40 via-white to-sky-50/30 py-20 lg:py-28">
    <!-- Soft radial gradient mesh -->
    <div
        aria-hidden="true"
        class="pointer-events-none absolute inset-0 opacity-60"
        style="background: radial-gradient(ellipse at 12% 15%, rgba(0,174,239,0.08) 0%, transparent 55%), radial-gradient(ellipse at 88% 85%, rgba(0,174,239,0.06) 0%, transparent 55%);"
    ></div>

    <div class="relative mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div>
            <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-brand-navy/60">
                &sect; 02 &middot; The fee schedule
            </p>
        </div>

        <!-- Headline price moment — image-backed navy band -->
        <div class="mt-6 relative overflow-hidden rounded-3xl shadow-2xl shadow-brand-navy/20 ring-1 ring-brand-navy/10">
            <!-- Background image -->
            <div class="absolute inset-0">
                <img
                    src="https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=2400&q=80&auto=format&fit=crop"
                    alt=""
                    class="h-full w-full object-cover"
                />
                <!-- Heavy navy gradient so text/numbers stay legible -->
                <div aria-hidden="true" class="absolute inset-0 bg-gradient-to-br from-brand-navy/95 via-brand-navy/88 to-brand-navy/75"></div>
                <!-- Subtle cyan accent glow -->
                <div aria-hidden="true" class="absolute -left-32 top-1/2 h-[420px] w-[420px] -translate-y-1/2 rounded-full bg-brand-cyan/20 blur-3xl"></div>
            </div>

            <!-- Content grid sits above image -->
            <div class="relative grid grid-cols-12 items-center gap-6 px-6 py-14 sm:px-10 sm:py-16 lg:px-14 lg:py-20">
                <div class="col-span-12 lg:col-span-7">
                    <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-[#00AEEF]">
                        Due upfront to engage Housing365
                    </p>
                    <div class="relative mt-3 inline-block font-display font-normal leading-[0.85] text-white text-[5rem] sm:text-[8rem] lg:text-[11rem] xl:text-[13rem]">
                        <!-- Pulsing glow behind ₹0 -->
                        <span aria-hidden="true" class="pointer-events-none absolute -inset-8 -z-10 rounded-full bg-brand-cyan/25 blur-3xl animate-pulse"></span>
                        ₹0<span class="text-[#00AEEF]">.</span>
                    </div>
                </div>
                <div class="col-span-12 lg:col-span-5">
                    <p class="text-xs font-bold uppercase tracking-[0.28em] text-brand-cyan">
                        Always
                    </p>
                    <p class="mt-3 text-base leading-relaxed text-white/90 sm:text-lg">
                        You pay nothing to engage Housing365. The only fee on a standard leasing engagement is a one-time success fee, charged when a qualified tenant signs the lease &mdash; typically one month rent equivalent.
                    </p>
                    <a
                        href="{{ url('/contact?type=owner') }}"
                        class="mt-6 inline-flex items-center gap-2 rounded-xl bg-brand-cyan px-7 py-3.5 text-sm font-bold text-white shadow-lg shadow-brand-cyan/25 transition-all duration-200 hover:-translate-y-0.5 hover:bg-brand-cyan-hover hover:shadow-brand-cyan/40"
                    >
                        <span>Get a Free Rental Analysis</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Magazine fee schedule — restaurant menu with dotted leaders -->
        <div class="mt-20">
            <div class="flex items-baseline justify-between border-b border-brand-navy/15 pb-3">
                <p class="font-display text-2xl font-normal text-brand-navy">
                    Full schedule of fees
                </p>
                <p class="text-[10px] font-bold uppercase tracking-[0.22em] text-brand-navy/55">
                    Effective 2026 &middot; INR (₹)
                </p>
            </div>

            <!-- Desktop/tablet: table with double rules -->
            <div class="hidden border-t-2 border-double border-brand-navy/30 sm:block">
                <!-- Row 1: Standard leasing success fee -->
                <div class="grid grid-cols-12 items-baseline gap-4 border-b border-brand-navy/10 py-6">
                    <div class="col-span-7">
                        <p class="text-xl font-bold text-brand-navy">
                            Standard leasing success fee
                        </p>
                        <p class="mt-1 text-sm leading-relaxed text-slate-500">
                            Single condo, townhouse, or single-family home. Charged once, on signed lease, to a qualified tenant we placed.
                        </p>
                    </div>
                    <div aria-hidden="true" class="col-span-2 self-end overflow-hidden whitespace-nowrap pb-2 text-brand-navy/30 select-none" style="letter-spacing: 0.25em;">
                        ............................................
                    </div>
                    <div class="col-span-3 text-right">
                        <p class="text-2xl font-bold text-brand-navy">
                            One month rent equivalent
                        </p>
                    </div>
                </div>

                <!-- Row 2: Setup, onboarding, syndication -->
                <div class="grid grid-cols-12 items-baseline gap-4 border-b border-brand-navy/10 py-6">
                    <div class="col-span-7">
                        <p class="text-xl font-bold text-brand-navy">
                            Setup, onboarding, photography, syndication
                        </p>
                        <p class="mt-1 text-sm leading-relaxed text-slate-500">
                            Account creation, document collection, professional photos, 40+ portal listing.
                        </p>
                    </div>
                    <div aria-hidden="true" class="col-span-2 self-end overflow-hidden whitespace-nowrap pb-2 text-brand-navy/30 select-none" style="letter-spacing: 0.25em;">
                        ............................................
                    </div>
                    <div class="col-span-3 text-right">
                        <p class="text-2xl font-bold text-brand-navy">
                            ₹199
                        </p>
                    </div>
                </div>

                <!-- Row 3: Monthly retainer or management % -->
                <div class="grid grid-cols-12 items-baseline gap-4 border-b border-brand-navy/10 py-6">
                    <div class="col-span-7">
                        <p class="text-lg font-bold text-brand-navy">
                            Monthly retainer or management %
                        </p>
                        <p class="mt-1 text-sm leading-relaxed text-slate-500">
                            We are a leasing platform. There is no recurring fee.
                        </p>
                    </div>
                    <div aria-hidden="true" class="col-span-2 self-end overflow-hidden whitespace-nowrap pb-2 text-brand-navy/30 select-none" style="letter-spacing: 0.25em;">
                        ............................................
                    </div>
                    <div class="col-span-3 text-right">
                        <p class="text-xl font-bold text-brand-navy">
                            Not charged
                        </p>
                    </div>
                </div>

                <!-- Row 4: Tenant Replacement Guarantee -->
                <div class="grid grid-cols-12 items-baseline gap-4 border-b border-brand-navy/10 py-6">
                    <div class="col-span-7">
                        <p class="text-lg font-bold text-brand-navy">
                            Tenant Replacement Guarantee
                        </p>
                        <p class="mt-1 text-sm leading-relaxed text-slate-500">
                            If a tenant we placed breaks the lease in the first six months, we re-lease at no additional success fee.
                        </p>
                    </div>
                    <div aria-hidden="true" class="col-span-2 self-end overflow-hidden whitespace-nowrap pb-2 text-brand-navy/30 select-none" style="letter-spacing: 0.25em;">
                        ............................................
                    </div>
                    <div class="col-span-3 text-right">
                        <p class="text-xl font-bold text-brand-navy">
                            Included
                        </p>
                    </div>
                </div>

                <!-- Row 5: Lease Agreement, Stamp Duty & Registration -->
                <div class="grid grid-cols-12 items-baseline gap-4 border-b border-brand-navy/10 py-6">
                    <div class="col-span-7">
                        <p class="text-lg font-bold text-brand-navy">
                            Lease Agreement, Stamp Duty &amp; Registration
                        </p>
                        <p class="mt-1 text-sm leading-relaxed text-slate-500">
                            11-Month registered Leave &amp; License agreement drafting, digital e-signing, and police intimation. Government stamp duty, e-stamping charges, and statutory registration fees are at actuals (extra cost as per state norms).
                        </p>
                    </div>
                    <div aria-hidden="true" class="col-span-2 self-end overflow-hidden whitespace-nowrap pb-2 text-brand-navy/30 select-none" style="letter-spacing: 0.25em;">
                        ............................................
                    </div>
                    <div class="col-span-3 text-right">
                        <p class="text-xl font-bold text-brand-navy">
                            At actual cost (Extra)
                        </p>
                    </div>
                </div>

                <!-- Row 6: Paid advertising beyond standard syndication -->
                <div class="grid grid-cols-12 items-baseline gap-4 border-b border-brand-navy/10 py-6">
                    <div class="col-span-7">
                        <p class="text-lg font-bold text-brand-navy">
                            Paid advertising beyond standard syndication
                        </p>
                        <p class="mt-1 text-sm leading-relaxed text-slate-500">
                            Optional boosted listings, premium portal placements, or targeted social campaigns. Owner pre-approves.
                        </p>
                    </div>
                    <div aria-hidden="true" class="col-span-2 self-end overflow-hidden whitespace-nowrap pb-2 text-brand-navy/30 select-none" style="letter-spacing: 0.25em;">
                        ............................................
                    </div>
                    <div class="col-span-3 text-right">
                        <p class="text-xl font-bold text-brand-navy">
                            Pass-through at cost
                        </p>
                    </div>
                </div>

                <!-- Row 7: Institutional lease-up (PMC, builder, developer) -->
                <div class="grid grid-cols-12 items-baseline gap-4 border-b border-brand-navy/10 py-6">
                    <div class="col-span-7">
                        <p class="text-lg font-bold text-brand-navy">
                            Institutional lease-up (PMC, builder, developer)
                        </p>
                        <p class="mt-1 text-sm leading-relaxed text-slate-500">
                            Multi-unit lease-up programs, project-based engagements, custom service scope.
                        </p>
                    </div>
                    <div aria-hidden="true" class="col-span-2 self-end overflow-hidden whitespace-nowrap pb-2 text-brand-navy/30 select-none" style="letter-spacing: 0.25em;">
                        ............................................
                    </div>
                    <div class="col-span-3 text-right">
                        <p class="text-xl font-bold text-brand-navy">
                            Custom RFP
                        </p>
                    </div>
                </div>

                <!-- Row 8: Engagement exit -->
                <div class="grid grid-cols-12 items-baseline gap-4 border-b border-brand-navy/10 py-6">
                    <div class="col-span-7">
                        <p class="text-lg font-bold text-brand-navy">
                            Engagement exit
                        </p>
                        <p class="mt-1 text-sm leading-relaxed text-slate-500">
                            Either party. No exit fee, no clawback on a placement already earned.
                        </p>
                    </div>
                    <div aria-hidden="true" class="col-span-2 self-end overflow-hidden whitespace-nowrap pb-2 text-brand-navy/30 select-none" style="letter-spacing: 0.25em;">
                        ............................................
                    </div>
                    <div class="col-span-3 text-right">
                        <p class="text-xl font-bold text-brand-navy">
                            30-day notice
                        </p>
                    </div>
                </div>
            </div>

            <!-- Mobile: stacked cards -->
            <div class="border-t-2 border-double border-brand-navy/30 sm:hidden">
                <div class="border-b border-brand-navy/10 py-5">
                    <p class="text-lg font-bold text-brand-navy">Standard leasing success fee</p>
                    <p class="mt-1 text-sm leading-relaxed text-slate-500">Single condo, townhouse, or single-family home. Charged once, on signed lease, to a qualified tenant we placed.</p>
                    <p class="mt-2 text-2xl font-bold text-brand-navy">One month rent equivalent</p>
                </div>
                <div class="border-b border-brand-navy/10 py-5">
                    <p class="text-lg font-bold text-brand-navy">Setup, onboarding, photography, syndication</p>
                    <p class="mt-1 text-sm leading-relaxed text-slate-500">Account creation, document collection, professional photos, 40+ portal listing.</p>
                    <p class="mt-2 text-2xl font-bold text-brand-navy">₹199</p>
                </div>
                <div class="border-b border-brand-navy/10 py-5">
                    <p class="text-lg font-bold text-brand-navy">Monthly retainer or management %</p>
                    <p class="mt-1 text-sm leading-relaxed text-slate-500">We are a leasing platform. There is no recurring fee.</p>
                    <p class="mt-2 text-xl font-bold text-brand-navy">Not charged</p>
                </div>
                <div class="border-b border-brand-navy/10 py-5">
                    <p class="text-lg font-bold text-brand-navy">Tenant Replacement Guarantee</p>
                    <p class="mt-1 text-sm leading-relaxed text-slate-500">If a tenant we placed breaks the lease in the first six months, we re-lease at no additional success fee.</p>
                    <p class="mt-2 text-xl font-bold text-brand-navy">Included</p>
                </div>
                <div class="border-b border-brand-navy/10 py-5">
                    <p class="text-lg font-bold text-brand-navy">Lease Agreement, Stamp Duty &amp; Registration</p>
                    <p class="mt-1 text-sm leading-relaxed text-slate-500">11-Month registered agreement drafting, digital e-signing, and police intimation. Government stamp duty and registration fees are at actuals (extra cost as per state norms).</p>
                    <p class="mt-2 text-xl font-bold text-brand-navy">At actual cost (Extra)</p>
                </div>
                <div class="border-b border-brand-navy/10 py-5">
                    <p class="text-lg font-bold text-brand-navy">Paid advertising beyond standard syndication</p>
                    <p class="mt-1 text-sm leading-relaxed text-slate-500">Optional boosted listings, premium portal placements, or targeted social campaigns. Owner pre-approves.</p>
                    <p class="mt-2 text-xl font-bold text-brand-navy">Pass-through at cost</p>
                </div>
                <div class="border-b border-brand-navy/10 py-5">
                    <p class="text-lg font-bold text-brand-navy">Institutional lease-up (PMC, builder, developer)</p>
                    <p class="mt-1 text-sm leading-relaxed text-slate-500">Multi-unit lease-up programs, project-based engagements, custom service scope.</p>
                    <p class="mt-2 text-xl font-bold text-brand-navy">Custom RFP</p>
                </div>
                <div class="border-b border-brand-navy/10 py-5">
                    <p class="text-lg font-bold text-brand-navy">Engagement exit</p>
                    <p class="mt-1 text-sm leading-relaxed text-slate-500">Either party. No exit fee, no clawback on a placement already earned.</p>
                    <p class="mt-2 text-xl font-bold text-brand-navy">30-day notice</p>
                </div>
                <div class="border-t-2 border-double border-brand-navy/30"></div>
            </div>

            <p class="mt-8 max-w-3xl text-sm leading-relaxed text-slate-500">
                Pricing is tailored to property type, location, and engagement scope. Your advisor will confirm the exact success fee in writing before any work begins. All fees exclude applicable GST (18%). Government stamp duty, official e-stamping, and statutory registration fees are at actuals / extra cost as per state government regulations.
            </p>
        </div>
    </div>
</section>

<!-- ── SECTION 5: HOW PRICING COMPARES (Visual 2-Column with Architecture Photo) ── -->
<section class="bg-white py-20 lg:py-24">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 items-center gap-10 lg:grid-cols-12 lg:gap-14">
            <!-- Left: Text block -->
            <div class="lg:col-span-6">
                <p class="text-xs font-bold uppercase tracking-[0.28em] text-brand-cyan">
                    How our pricing compares
                </p>
                <h2 class="mt-3 font-display text-3xl font-normal tracking-tight text-brand-navy sm:text-4xl lg:text-[2.6rem]">
                    One success fee. <span class="font-display font-medium text-brand-cyan">No monthly drag</span><span class="text-[#00AEEF]">.</span>
                </h2>
                <p class="mt-5 text-base leading-relaxed text-slate-600 sm:text-lg">
                    Traditional property managers charge a monthly percentage for the life of the tenancy &mdash; that adds up to a multiple of our one-time success fee over a typical two-year hold. We're paid once, on placement, then we step back.
                </p>
                <ul class="mt-7 space-y-3.5">
                    <li class="flex items-start gap-3">
                        <i class="fa-solid fa-circle-check mt-1 text-base text-brand-cyan shrink-0"></i>
                        <span class="text-sm sm:text-base leading-relaxed text-slate-700 font-medium">Single fee, invoiced once on a signed lease</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fa-solid fa-circle-check mt-1 text-base text-brand-cyan shrink-0"></i>
                        <span class="text-sm sm:text-base leading-relaxed text-slate-700 font-medium">No monthly retainer for the life of the tenancy</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fa-solid fa-circle-check mt-1 text-base text-brand-cyan shrink-0"></i>
                        <span class="text-sm sm:text-base leading-relaxed text-slate-700 font-medium">No vendor markups or hidden maintenance charges</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fa-solid fa-circle-check mt-1 text-base text-brand-cyan shrink-0"></i>
                        <span class="text-sm sm:text-base leading-relaxed text-slate-700 font-medium">Tenant Replacement Guarantee included for six months</span>
                    </li>
                </ul>
            </div>

            <!-- Right: Modern condo image -->
            <div class="lg:col-span-6">
                <div class="relative aspect-[4/5] w-full overflow-hidden rounded-2xl shadow-lg ring-1 ring-brand-navy/10">
                    <img
                        src="https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=1400&q=80"
                        alt="Modern multi-unit residential building exterior"
                        class="h-full w-full object-cover"
                    />
                    <!-- Editorial overlay rules -->
                    <div aria-hidden="true" class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-[#00AEEF]/70 to-transparent"></div>
                    <div aria-hidden="true" class="absolute inset-x-0 bottom-0 h-px bg-gradient-to-r from-transparent via-[#00AEEF]/70 to-transparent"></div>
                    <!-- Caption pill -->
                    <div class="absolute bottom-4 left-4 right-4 flex items-center justify-between gap-3 rounded-xl bg-white/95 px-4 py-3 shadow-md backdrop-blur">
                        <p class="text-xs sm:text-sm font-semibold text-brand-navy leading-tight">
                            Built for owners of a single condo &mdash; or a 40-unit portfolio.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 6: OUTCOME GUARANTEE ILLUSTRATION BAND ── -->
<section class="bg-[#FBFAF6] py-20 lg:py-24">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 items-center gap-10 lg:grid-cols-12 lg:gap-14">
            <!-- Left: Supporting Image -->
            <div class="order-2 lg:order-1 lg:col-span-5">
                <div class="relative aspect-[4/3] w-full overflow-hidden rounded-2xl shadow-lg ring-1 ring-brand-navy/10">
                    <img
                        src="https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=1400&q=80"
                        alt="Owner and tenant signing a residential lease at a table"
                        class="h-full w-full object-cover"
                    />
                </div>
            </div>

            <!-- Right: Outcome Guarantee Block -->
            <div class="order-1 lg:order-2 lg:col-span-7">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-shield-halved text-2xl text-brand-cyan"></i>
                    <p class="text-xs font-bold uppercase tracking-[0.28em] text-brand-cyan">
                        Outcome guarantee
                    </p>
                </div>
                <h2 class="mt-4 font-display text-3xl font-normal tracking-tight text-brand-navy sm:text-4xl lg:text-[2.6rem]">
                    If we don't place a qualified tenant, you don't <span class="font-display font-medium text-brand-cyan">pay a single rupee</span><span class="text-[#00AEEF]">.</span>
                </h2>
                <p class="mt-5 text-base leading-relaxed text-slate-600 sm:text-lg">
                    Our model is contractually risk-shifted. Every cost we carry &mdash; photography, syndication, screening, lease prep &mdash; is on our balance sheet until your unit is leased. No clawbacks, no kill-fee, no time-and-materials hidden in a footer.
                </p>
                <p class="mt-4 text-base leading-relaxed text-slate-600 sm:text-lg">
                    We back it with the paperwork: a Tenant Replacement Guarantee for the first six months and a No-Placement, No-Fee Promise written into every engagement letter.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 7: § 03 · WHAT THE SUCCESS FEE INCLUDES (Transparency Table) ── -->
<section class="bg-white py-20 lg:py-28">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div>
            <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-brand-navy/60">
                &sect; 03 &middot; What the success fee includes
            </p>
            <h2 class="mt-3 max-w-4xl font-display text-3xl font-normal tracking-tight text-brand-navy sm:text-4xl lg:text-[2.6rem]">
                Every leasing service we offer &mdash; in <span class="font-display font-medium text-brand-cyan">one fee</span><span class="text-[#00AEEF]">.</span>
            </h2>
            <p class="mt-4 max-w-2xl text-base leading-relaxed text-slate-600 sm:text-lg">
                Most brokerages itemize photography, syndication, and screening as separate line items. We don't. The success fee covers all nine pillars of our leasing service. Add-ons are clearly labelled and never assumed.
            </p>
        </div>

        <!-- Desktop Wide Table with Double Rules -->
        <div class="mt-12 hidden border-t-2 border-double border-brand-navy/30 md:block">
            <!-- Header Row -->
            <div class="grid grid-cols-12 gap-4 border-b border-brand-navy/15 py-3">
                <div class="col-span-6 text-[10px] font-bold uppercase tracking-[0.22em] text-brand-navy/55">
                    Line item
                </div>
                <div class="col-span-6 text-right text-[10px] font-bold uppercase tracking-[0.22em] text-brand-navy/55">
                    Cost to you &middot; policy tag
                </div>
            </div>

            <!-- Row 1 -->
            <div class="grid grid-cols-12 items-baseline gap-4 border-b border-brand-navy/10 py-5">
                <div class="col-span-6">
                    <p class="text-base sm:text-lg font-bold text-brand-navy">Marketing photography &amp; video</p>
                    <p class="mt-1 text-sm leading-relaxed text-slate-500">Professional camera, wide-angle lens, edited gallery, optional 3D walkthrough.</p>
                </div>
                <div class="col-span-6 text-right">
                    <p class="text-sm sm:text-base font-bold text-brand-navy">Included in success fee</p>
                    <p class="mt-1 text-[10px] font-bold uppercase tracking-[0.18em] text-[#047857]">[ INCLUDED ]</p>
                </div>
            </div>

            <!-- Row 2 -->
            <div class="grid grid-cols-12 items-baseline gap-4 border-b border-brand-navy/10 py-5">
                <div class="col-span-6">
                    <p class="text-base sm:text-lg font-bold text-brand-navy">MLS + 40+ portal syndication</p>
                    <p class="mt-1 text-sm leading-relaxed text-slate-500">Housing 365 Direct Verified Network, MagicBricks, 99acres, LinkedIn Expat Network, and our agent network.</p>
                </div>
                <div class="col-span-6 text-right">
                    <p class="text-sm sm:text-base font-bold text-brand-navy">Included in success fee</p>
                    <p class="mt-1 text-[10px] font-bold uppercase tracking-[0.18em] text-[#047857]">[ INCLUDED ]</p>
                </div>
            </div>

            <!-- Row 3 -->
            <div class="grid grid-cols-12 items-baseline gap-4 border-b border-brand-navy/10 py-5">
                <div class="col-span-6">
                    <p class="text-base sm:text-lg font-bold text-brand-navy">Tenant screening &amp; background</p>
                    <p class="mt-1 text-sm leading-relaxed text-slate-500">Credit, employment, income, reference, and prior-tenancy verification.</p>
                </div>
                <div class="col-span-6 text-right">
                    <p class="text-sm sm:text-base font-bold text-brand-navy">Included in success fee</p>
                    <p class="mt-1 text-[10px] font-bold uppercase tracking-[0.18em] text-[#047857]">[ INCLUDED ]</p>
                </div>
            </div>

            <!-- Row 4 -->
            <div class="grid grid-cols-12 items-baseline gap-4 border-b border-brand-navy/10 py-5">
                <div class="col-span-6">
                    <p class="text-base sm:text-lg font-bold text-brand-navy">Showings &amp; applicant management</p>
                    <p class="mt-1 text-sm leading-relaxed text-slate-500">Coordinated viewings, qualification calls, applicant shortlist with our recommendation.</p>
                </div>
                <div class="col-span-6 text-right">
                    <p class="text-sm sm:text-base font-bold text-brand-navy">Included in success fee</p>
                    <p class="mt-1 text-[10px] font-bold uppercase tracking-[0.18em] text-[#047857]">[ INCLUDED ]</p>
                </div>
            </div>

            <!-- Row 5 -->
            <div class="grid grid-cols-12 items-baseline gap-4 border-b border-brand-navy/10 py-5">
                <div class="col-span-6">
                    <p class="text-base sm:text-lg font-bold text-brand-navy">Lease drafting &amp; e-signing</p>
                    <p class="mt-1 text-sm leading-relaxed text-slate-500">11-Month e-stamped registered Leave & License agreement, State Stamp Duty compliance, schedules, addenda, and digital execution.</p>
                </div>
                <div class="col-span-6 text-right">
                    <p class="text-sm sm:text-base font-bold text-brand-navy">Included in success fee</p>
                    <p class="mt-1 text-[10px] font-bold uppercase tracking-[0.18em] text-[#047857]">[ INCLUDED ]</p>
                </div>
            </div>

            <!-- Row 6 -->
            <div class="grid grid-cols-12 items-baseline gap-4 border-b border-brand-navy/10 py-5">
                <div class="col-span-6">
                    <p class="text-base sm:text-lg font-bold text-brand-navy">Move-in coordination &amp; key handover</p>
                    <p class="mt-1 text-sm leading-relaxed text-slate-500">Move-in inspection, condition report, key/fob delivery to tenant.</p>
                </div>
                <div class="col-span-6 text-right">
                    <p class="text-sm sm:text-base font-bold text-brand-navy">Included in success fee</p>
                    <p class="mt-1 text-[10px] font-bold uppercase tracking-[0.18em] text-[#047857]">[ INCLUDED ]</p>
                </div>
            </div>

            <!-- Row 7 -->
            <div class="grid grid-cols-12 items-baseline gap-4 border-b border-brand-navy/10 py-5">
                <div class="col-span-6">
                    <p class="text-base sm:text-lg font-bold text-brand-navy">Government Stamp Duty, E-Stamping &amp; Registration</p>
                    <p class="mt-1 text-sm leading-relaxed text-slate-500">Official state e-stamping (SHCIL/Govt), notary verification, and biometric registration handling.</p>
                </div>
                <div class="col-span-6 text-right">
                    <p class="text-sm sm:text-base font-bold text-brand-navy">At actuals as per state rates</p>
                    <p class="mt-1 text-[10px] font-bold uppercase tracking-[0.18em] text-[#92400E]">[ EXTRA AT ACTUALS ]</p>
                </div>
            </div>

            <!-- Row 8 -->
            <div class="grid grid-cols-12 items-baseline gap-4 border-b border-brand-navy/10 py-5">
                <div class="col-span-6">
                    <p class="text-base sm:text-lg font-bold text-brand-navy">Paid advertising beyond standard</p>
                    <p class="mt-1 text-sm leading-relaxed text-slate-500">Optional boosted listings, premium portal placements, or targeted social campaigns.</p>
                </div>
                <div class="col-span-6 text-right">
                    <p class="text-sm sm:text-base font-bold text-brand-navy">At cost - owner pre-approves</p>
                    <p class="mt-1 text-[10px] font-bold uppercase tracking-[0.18em] text-[#92400E]">[ PASS-THROUGH ]</p>
                </div>
            </div>

            <!-- Row 9 -->
            <div class="grid grid-cols-12 items-baseline gap-4 border-b border-brand-navy/10 py-5">
                <div class="col-span-6">
                    <p class="text-base sm:text-lg font-bold text-brand-navy">Institutional lease-up engagements</p>
                    <p class="mt-1 text-sm leading-relaxed text-slate-500">Multi-unit programs for property management companies, builders, and developers.</p>
                </div>
                <div class="col-span-6 text-right">
                    <p class="text-sm sm:text-base font-bold text-brand-navy">Custom RFP - contact for quote</p>
                    <p class="mt-1 text-[10px] font-bold uppercase tracking-[0.18em] text-[#3730A3]">[ OPTIONAL ADD-ON ]</p>
                </div>
            </div>

            <div class="border-t-2 border-double border-brand-navy/30"></div>
        </div>

        <!-- Mobile: Stacked Rows -->
        <div class="mt-12 border-t-2 border-double border-brand-navy/30 md:hidden">
            <div class="border-b border-brand-navy/10 py-5">
                <p class="text-base font-bold text-brand-navy">Marketing photography &amp; video</p>
                <p class="mt-1 text-sm leading-relaxed text-slate-500">Professional camera, wide-angle lens, edited gallery, optional 3D walkthrough.</p>
                <p class="mt-2 text-base font-bold text-brand-navy">Included in success fee</p>
                <p class="mt-1 text-[10px] font-bold uppercase tracking-[0.18em] text-[#047857]">[ INCLUDED ]</p>
            </div>
            <div class="border-b border-brand-navy/10 py-5">
                <p class="text-base font-bold text-brand-navy">MLS + 40+ portal syndication</p>
                <p class="mt-1 text-sm leading-relaxed text-slate-500">Housing 365 Direct Verified Network, MagicBricks, 99acres, LinkedIn Expat Network, and our agent network.</p>
                <p class="mt-2 text-base font-bold text-brand-navy">Included in success fee</p>
                <p class="mt-1 text-[10px] font-bold uppercase tracking-[0.18em] text-[#047857]">[ INCLUDED ]</p>
            </div>
            <div class="border-b border-brand-navy/10 py-5">
                <p class="text-base font-bold text-brand-navy">Tenant screening &amp; background</p>
                <p class="mt-1 text-sm leading-relaxed text-slate-500">Credit, employment, income, reference, and prior-tenancy verification.</p>
                <p class="mt-2 text-base font-bold text-brand-navy">Included in success fee</p>
                <p class="mt-1 text-[10px] font-bold uppercase tracking-[0.18em] text-[#047857]">[ INCLUDED ]</p>
            </div>
            <div class="border-b border-brand-navy/10 py-5">
                <p class="text-base font-bold text-brand-navy">Showings &amp; applicant management</p>
                <p class="mt-1 text-sm leading-relaxed text-slate-500">Coordinated viewings, qualification calls, applicant shortlist with our recommendation.</p>
                <p class="mt-2 text-base font-bold text-brand-navy">Included in success fee</p>
                <p class="mt-1 text-[10px] font-bold uppercase tracking-[0.18em] text-[#047857]">[ INCLUDED ]</p>
            </div>
            <div class="border-b border-brand-navy/10 py-5">
                <p class="text-base font-bold text-brand-navy">Lease drafting &amp; e-signing</p>
                <p class="mt-1 text-sm leading-relaxed text-slate-500">11-Month e-stamped registered Leave & License agreement, State Stamp Duty compliance, schedules, addenda, and digital execution.</p>
                <p class="mt-2 text-base font-bold text-brand-navy">Included in success fee</p>
                <p class="mt-1 text-[10px] font-bold uppercase tracking-[0.18em] text-[#047857]">[ INCLUDED ]</p>
            </div>
            <div class="border-b border-brand-navy/10 py-5">
                <p class="text-base font-bold text-brand-navy">Move-in coordination &amp; key handover</p>
                <p class="mt-1 text-sm leading-relaxed text-slate-500">Move-in inspection, condition report, key/fob delivery to tenant.</p>
                <p class="mt-2 text-base font-bold text-brand-navy">Included in success fee</p>
                <p class="mt-1 text-[10px] font-bold uppercase tracking-[0.18em] text-[#047857]">[ INCLUDED ]</p>
            </div>
            <div class="border-b border-brand-navy/10 py-5">
                <p class="text-base font-bold text-brand-navy">Government Stamp Duty, E-Stamping &amp; Registration</p>
                <p class="mt-1 text-sm leading-relaxed text-slate-500">Official state e-stamping, notary charges, and biometric registrar handling.</p>
                <p class="mt-2 text-base font-bold text-brand-navy">At actuals as per state rates</p>
                <p class="mt-1 text-[10px] font-bold uppercase tracking-[0.18em] text-[#92400E]">[ EXTRA AT ACTUALS ]</p>
            </div>
            <div class="border-b border-brand-navy/10 py-5">
                <p class="text-base font-bold text-brand-navy">Paid advertising beyond standard</p>
                <p class="mt-1 text-sm leading-relaxed text-slate-500">Optional boosted listings, premium portal placements, or targeted social campaigns.</p>
                <p class="mt-2 text-base font-bold text-brand-navy">At cost - owner pre-approves</p>
                <p class="mt-1 text-[10px] font-bold uppercase tracking-[0.18em] text-[#92400E]">[ PASS-THROUGH ]</p>
            </div>
            <div class="border-b border-brand-navy/10 py-5">
                <p class="text-base font-bold text-brand-navy">Institutional lease-up engagements</p>
                <p class="mt-1 text-sm leading-relaxed text-slate-500">Multi-unit programs for property management companies, builders, and developers.</p>
                <p class="mt-2 text-base font-bold text-brand-navy">Custom RFP - contact for quote</p>
                <p class="mt-1 text-[10px] font-bold uppercase tracking-[0.18em] text-[#3730A3]">[ OPTIONAL ADD-ON ]</p>
            </div>
            <div class="border-t-2 border-double border-brand-navy/30"></div>
        </div>

        <p class="mt-8 max-w-3xl text-sm leading-relaxed text-slate-500">
            <span class="font-bold text-brand-navy">Pass-through, never markup.</span>
            When an owner opts into paid advertising or partner services, the cost is passed through at the price the vendor charges us. We do not earn a margin on add-ons.
        </p>
    </div>
</section>

<!-- ── SECTION 8: INTERACTIVE INDIAN METRO RENT CALCULATOR (LIGHT MODERN DESIGN) ── -->
<section id="rent-calculator" class="py-16 lg:py-24 bg-[#F8FAFD] border-t border-slate-200/80 relative overflow-hidden">
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            <!-- Left Info Column -->
            <div>
                <p class="text-brand-cyan font-bold text-xs sm:text-sm uppercase tracking-wider mb-4">
                    Free Indian Estimation Tool
                </p>
                <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl text-brand-navy mb-6 font-extrabold tracking-tight">
                    How Much Could Your Property Earn?
                </h2>
                <p class="text-slate-600 text-base sm:text-lg mb-8 leading-relaxed">
                    Get an instant rental yield and monthly rent estimate for your apartment or villa across Mumbai, Bengaluru, Delhi NCR, Hyderabad, Pune, and other top Indian metros. No signup required.
                </p>
                <div class="flex flex-wrap items-center gap-x-6 gap-y-3 text-sm text-slate-500 font-medium">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-calculator text-brand-cyan"></i>
                        Based on Real-Time Indian Micro-Market Data
                    </span>
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-chart-line text-brand-cyan"></i>
                        Updated Monthly across 14 Metros
                    </span>
                </div>
            </div>

            <!-- Right Calculator Card -->
            <div class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xl">
                <div class="space-y-5">
                    <!-- City Dropdown -->
                    <div>
                        <label for="calc-city" class="block text-sm font-bold text-slate-700 mb-2">Select Your City</label>
                        <select
                            id="calc-city"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 text-slate-800 px-4 py-3.5 text-sm focus:ring-2 focus:ring-brand-cyan/50 focus:border-brand-cyan outline-none cursor-pointer"
                        >
                            <option value="">Select a city</option>
                            <optgroup label="Tier 1 Metros">
                                <option value="Mumbai">Mumbai (MMR)</option>
                                <option value="Bengaluru">Bengaluru (Bangalore)</option>
                                <option value="New Delhi">New Delhi (Delhi NCR)</option>
                                <option value="Gurugram">Gurugram (Gurgaon)</option>
                                <option value="Noida">Noida (Gautam Buddha Nagar)</option>
                                <option value="Hyderabad">Hyderabad (Telangana)</option>
                                <option value="Pune">Pune (Maharashtra)</option>
                                <option value="Chennai">Chennai (Tamil Nadu)</option>
                                <option value="Kolkata">Kolkata (West Bengal)</option>
                                <option value="Ahmedabad">Ahmedabad (Gujarat)</option>
                            </optgroup>
                            <optgroup label="Tier 2 &amp; Destination Metros">
                                <option value="Jaipur">Jaipur (Rajasthan)</option>
                                <option value="Goa">Goa (North &amp; South Goa)</option>
                                <option value="Kochi">Kochi (Kerala)</option>
                                <option value="Chandigarh">Chandigarh (Tricity / Mohali)</option>
                            </optgroup>
                        </select>
                    </div>

                    <!-- Bedrooms Selector -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Configuration (BHK)</label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                            <button
                                type="button"
                                data-bed="1"
                                class="calc-bed-btn py-3 px-2 rounded-xl text-xs sm:text-sm font-bold transition-all bg-slate-50 text-slate-700 border border-slate-200 hover:bg-slate-100 text-center"
                            >
                                1 BHK
                            </button>
                            <button
                                type="button"
                                data-bed="2"
                                class="calc-bed-btn py-3 px-2 rounded-xl text-xs sm:text-sm font-bold transition-all bg-slate-50 text-slate-700 border border-slate-200 hover:bg-slate-100 text-center"
                            >
                                2 BHK
                            </button>
                            <button
                                type="button"
                                data-bed="3"
                                class="calc-bed-btn py-3 px-2 rounded-xl text-xs sm:text-sm font-bold transition-all bg-slate-50 text-slate-700 border border-slate-200 hover:bg-slate-100 text-center"
                            >
                                3 BHK
                            </button>
                            <button
                                type="button"
                                data-bed="4"
                                class="calc-bed-btn py-3 px-2 rounded-xl text-xs sm:text-sm font-bold transition-all bg-slate-50 text-slate-700 border border-slate-200 hover:bg-slate-100 text-center"
                            >
                                4+ BHK
                            </button>
                        </div>
                    </div>

                    <!-- Calculate CTA -->
                    <button
                        type="button"
                        id="calc-submit-btn"
                        disabled
                        class="w-full py-4 rounded-xl bg-brand-cyan text-white font-bold text-sm hover:bg-brand-cyan-hover transition-colors disabled:opacity-50 disabled:cursor-not-allowed shadow-md shadow-brand-cyan/20"
                    >
                        Calculate Rent Estimate
                    </button>

                    <!-- Result Box -->
                    <div id="calc-result-box" class="hidden mt-6 pt-6 border-t border-slate-100 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-brand-navy font-bold text-base">Estimated Monthly Rent</span>
                            <span id="calc-result-price" class="font-extrabold text-3xl text-brand-cyan tabular-nums">
                                ₹0/mo
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            * Estimates derived from registered 11-month Leave &amp; License agreements and verified micro-market portals. Actual rent depends on society, furnishing, and floor level.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 9: FULL-WIDTH EDITORIAL BANNER ── -->
<section class="relative bg-white py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="relative aspect-[16/9] w-full overflow-hidden rounded-3xl shadow-xl ring-1 ring-brand-navy/10 sm:aspect-[2/1] lg:aspect-[21/8]">
            <img
                src="https://images.unsplash.com/photo-1570168007204-dfb528c6958f?auto=format&fit=crop&w=2400&q=80"
                alt="Indian metro skyline at dusk — Mumbai, Bengaluru, Delhi NCR, Hyderabad, Pune, Chennai"
                class="h-full w-full object-cover"
            />
            <div aria-hidden="true" class="absolute inset-0 bg-gradient-to-tr from-brand-navy/85 via-brand-navy/40 to-transparent"></div>
            <div class="absolute inset-0 flex items-end p-6 sm:p-10 lg:p-14">
                <div class="max-w-2xl">
                    <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-[#00AEEF]">
                        Built for Indian property owners &amp; NRIs
                    </p>
                    <p class="mt-3 font-display text-3xl font-normal leading-tight text-white sm:text-4xl lg:text-5xl">
                        One signed lease. One fee. <br class="hidden sm:block" /><span class="font-medium text-brand-cyan">Zero monthly drag.</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 10: § 06 · MONEY-BACK & GUARANTEES (Vertical Editorial Notes) ── -->
<section class="bg-[#FBFAF6] py-20 lg:py-28">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        <div>
            <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-brand-navy/60">
                &sect; 06 &middot; Money-back &amp; guarantees
            </p>
            <h2 class="mt-3 max-w-3xl font-display text-3xl font-normal tracking-tight text-brand-navy sm:text-4xl lg:text-[2.6rem]">
                We back the price with the <span class="font-display font-medium text-brand-cyan">paperwork</span><span class="text-[#00AEEF]">.</span>
            </h2>
            <p class="mt-4 max-w-2xl text-base leading-relaxed text-slate-600 sm:text-lg">
                Each guarantee is written into the leasing services agreement &mdash; not buried in a footer.
            </p>
        </div>

        <div class="mt-14 border-t-2 border-double border-brand-navy/25"></div>

        <!-- Note 01 -->
        <div class="grid grid-cols-12 gap-6 border-b border-brand-navy/10 py-12">
            <div class="col-span-12 sm:col-span-3">
                <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-brand-navy/55">
                    Note
                </p>
                <p class="mt-1 font-display text-6xl font-normal leading-none text-[#00AEEF] sm:text-7xl">
                    01
                </p>
            </div>
            <div class="col-span-12 sm:col-span-9">
                <h3 class="text-2xl font-bold text-brand-navy">
                    Tenant Replacement Guarantee.
                </h3>
                <p class="mt-3 max-w-2xl text-base leading-relaxed text-slate-600 sm:text-lg">
                    If a tenant we placed breaks the lease in the first six months, we re-lease the unit at no additional success fee. We carry advertising, screening, and lease prep again. Our economics absorb the cost, not yours.
                </p>
            </div>
        </div>

        <!-- Note 02 -->
        <div class="grid grid-cols-12 gap-6 border-b border-brand-navy/10 py-12">
            <div class="col-span-12 sm:col-span-3">
                <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-brand-navy/55">
                    Note
                </p>
                <p class="mt-1 font-display text-6xl font-normal leading-none text-[#00AEEF] sm:text-7xl">
                    02
                </p>
            </div>
            <div class="col-span-12 sm:col-span-9">
                <h3 class="text-2xl font-bold text-brand-navy">
                    No-Placement, No-Fee Promise.
                </h3>
                <p class="mt-3 max-w-2xl text-base leading-relaxed text-slate-600 sm:text-lg">
                    You owe nothing if we fail to place a qualified tenant. There is no kill-fee, no time-and-materials clawback, no minimum spend. Our risk is real and contractual - spelled out in writing before you sign anything.
                </p>
            </div>
        </div>

        <!-- Note 03 -->
        <div class="grid grid-cols-12 gap-6 py-12">
            <div class="col-span-12 sm:col-span-3">
                <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-brand-navy/55">
                    Note
                </p>
                <p class="mt-1 font-display text-6xl font-normal leading-none text-[#00AEEF] sm:text-7xl">
                    03
                </p>
            </div>
            <div class="col-span-12 sm:col-span-9">
                <h3 class="text-2xl font-bold text-brand-navy">
                    Transparent Exit.
                </h3>
                <p class="mt-3 max-w-2xl text-base leading-relaxed text-slate-600 sm:text-lg">
                    Engagements end on 30-day written notice from either party. There is no exit fee, no clawback on a placement already earned, and we hand off all applicant records and signed documents within seven days.
                </p>
            </div>
        </div>

        <div class="border-t-2 border-double border-brand-navy/25"></div>
    </div>
</section>

<!-- ── SECTION 11: § 07 · OWNERS ON OUR PRICING (Testimonials) ── -->
<section class="bg-white py-16 lg:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-brand-navy/60">
                &sect; 07 &middot; Owners on our pricing
            </p>
            <h2 class="mt-3 font-display text-3xl font-normal leading-tight text-brand-navy sm:text-4xl">
                No surprise invoices. <span class="font-display font-medium text-brand-cyan">Ever</span><span class="text-[#00AEEF]">.</span>
            </h2>
            <p class="mt-4 text-base leading-relaxed text-slate-600 sm:text-lg">
                What owners say about engaging a success-fee leasing platform instead of a percentage-based property manager.
            </p>
        </div>

        <!-- 3 Landlord Reviews -->
        <div class="mt-14 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="rounded-3xl border border-slate-200/80 bg-[#FBFAF6] p-8 flex flex-col justify-between shadow-sm">
                <div>
                    <div class="flex items-center gap-1 text-[#00AEEF] mb-4">
                        <i class="fa-solid fa-star text-sm"></i>
                        <i class="fa-solid fa-star text-sm"></i>
                        <i class="fa-solid fa-star text-sm"></i>
                        <i class="fa-solid fa-star text-sm"></i>
                        <i class="fa-solid fa-star text-sm"></i>
                    </div>
                    <blockquote class="text-sm sm:text-base leading-relaxed text-slate-600">
                        &ldquo;Every other property manager wanted 8% to 10% monthly in perpetuity just to collect automated rent. Housing365 charged one fee on lease-signing, placed an incredible corporate tenant, and saved me over ₹85,000 in brokerage and vacancy losses in the first year alone.&rdquo;
                    </blockquote>
                </div>
                <div class="mt-6 pt-5 border-t border-slate-200/70">
                    <p class="font-bold text-brand-navy text-sm">Vikram S.</p>
                    <p class="text-xs text-slate-500 font-medium">Bandra West, Mumbai Landlord</p>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200/80 bg-[#FBFAF6] p-8 flex flex-col justify-between shadow-sm">
                <div>
                    <div class="flex items-center gap-1 text-[#00AEEF] mb-4">
                        <i class="fa-solid fa-star text-sm"></i>
                        <i class="fa-solid fa-star text-sm"></i>
                        <i class="fa-solid fa-star text-sm"></i>
                        <i class="fa-solid fa-star text-sm"></i>
                        <i class="fa-solid fa-star text-sm"></i>
                    </div>
                    <blockquote class="text-sm sm:text-base leading-relaxed text-slate-600">
                        &ldquo;The ₹199 setup fee with professional photography and syndication gave us complete peace of mind. They placed an expat tech tenant seamlessly without the typical broker spam or recurring monthly retainers.&rdquo;
                    </blockquote>
                </div>
                <div class="mt-6 pt-5 border-t border-slate-200/70">
                    <p class="font-bold text-brand-navy text-sm">Pooja V.</p>
                    <p class="text-xs text-slate-500 font-medium">HSR Layout, Bengaluru Property Owner</p>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200/80 bg-[#FBFAF6] p-8 flex flex-col justify-between shadow-sm">
                <div>
                    <div class="flex items-center gap-1 text-[#00AEEF] mb-4">
                        <i class="fa-solid fa-star text-sm"></i>
                        <i class="fa-solid fa-star text-sm"></i>
                        <i class="fa-solid fa-star text-sm"></i>
                        <i class="fa-solid fa-star text-sm"></i>
                        <i class="fa-solid fa-star text-sm"></i>
                    </div>
                    <blockquote class="text-sm sm:text-base leading-relaxed text-slate-600">
                        &ldquo;The Tenant Replacement Guarantee proved they have skin in the game. Truly transparent pricing, 11-month e-stamped agreement, and zero junk broker fees. Best leasing experience in India.&rdquo;
                    </blockquote>
                </div>
                <div class="mt-6 pt-5 border-t border-slate-200/70">
                    <p class="font-bold text-brand-navy text-sm">Amitabh S.</p>
                    <p class="text-xs text-slate-500 font-medium">DLF Phase 5, Gurugram Owner</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── SECTION 12: PRICING & FEES FAQ ACCORDION (FULL WIDTH 2-COLUMN) ── -->
<section class="relative bg-[#F8FAFD] py-16 sm:py-20 lg:py-24 border-t border-slate-200/80">
    <div aria-hidden="true" class="pointer-events-none absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(#03234B 1px, transparent 1px); background-size: 28px 28px;"></div>
    
    <div class="relative mx-auto w-full max-w-[1720px] px-4 sm:px-8 lg:px-12 xl:px-16">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12 pb-6 border-b border-slate-200">
            <div>
                <p class="mb-3 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-[0.22em] text-brand-cyan">
                    <span class="h-2 w-2 rounded-full bg-brand-cyan"></span>
                    PRICING TRANSPARENCY &middot; FAQ
                </p>
                <h2 class="font-display text-3xl font-extrabold tracking-tight text-brand-navy sm:text-4xl md:text-[2.6rem]">
                    Pricing &amp; Fees Frequently Asked Questions
                </h2>
                <p class="mt-3 text-base text-slate-600 max-w-3xl">
                    Straightforward answers regarding our success-fee model, transparent onboarding, tenant replacement guarantee, and institutional lease-ups.
                </p>
            </div>
            <div class="shrink-0">
                <a href="{{ url('/contact?type=owner') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-6 py-3.5 text-sm font-bold text-brand-navy shadow-sm transition-all hover:bg-slate-50 hover:border-slate-400">
                    <span>Ask Our Finance Team</span>
                    <i class="fa-solid fa-arrow-right text-xs text-brand-cyan"></i>
                </a>
            </div>
        </div>

        @if(isset($faqs) && count($faqs) > 0)
            @php
                $half = ceil(count($faqs) / 2);
                $leftFaqs = $faqs->slice(0, $half);
                $rightFaqs = $faqs->slice($half);
            @endphp
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start w-full">
                <!-- Left Column Stack -->
                <div class="space-y-6">
                    @foreach($leftFaqs as $index => $faq)
                        @php
                            $numStr = str_pad($loop->iteration, 2, '0', STR_PAD_LEFT);
                        @endphp
                        <div class="faq-item rounded-2xl border border-slate-200/90 bg-white p-6 shadow-sm transition-all duration-200 hover:border-brand-cyan/40">
                            <button type="button" class="faq-toggle flex w-full cursor-pointer items-start justify-between gap-4 text-left">
                                <div class="flex items-start gap-3.5">
                                    <span class="faq-num mt-0.5 inline-flex items-center justify-center rounded-lg bg-sky-50 px-2.5 py-1 text-xs font-bold tracking-wide text-brand-cyan border border-sky-100">
                                        {{ $numStr }}
                                    </span>
                                    <h3 class="faq-title text-base sm:text-lg font-bold leading-snug tracking-tight text-brand-navy">
                                        {{ $faq->question }}
                                    </h3>
                                </div>
                                <span class="faq-icon mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full border border-slate-200 bg-slate-50 text-slate-500 transition-transform duration-300">
                                    <i class="fa-solid fa-plus text-xs"></i>
                                </span>
                            </button>
                            <div class="faq-body hidden pt-4 mt-4 border-t border-slate-100 text-sm sm:text-base leading-relaxed text-slate-600">
                                {!! nl2br(e($faq->answer)) !!}
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Right Column Stack -->
                <div class="space-y-6">
                    @foreach($rightFaqs as $index => $faq)
                        @php
                            $numStr = str_pad($half + $loop->iteration, 2, '0', STR_PAD_LEFT);
                        @endphp
                        <div class="faq-item rounded-2xl border border-slate-200/90 bg-white p-6 shadow-sm transition-all duration-200 hover:border-brand-cyan/40">
                            <button type="button" class="faq-toggle flex w-full cursor-pointer items-start justify-between gap-4 text-left">
                                <div class="flex items-start gap-3.5">
                                    <span class="faq-num mt-0.5 inline-flex items-center justify-center rounded-lg bg-sky-50 px-2.5 py-1 text-xs font-bold tracking-wide text-brand-cyan border border-sky-100">
                                        {{ $numStr }}
                                    </span>
                                    <h3 class="faq-title text-base sm:text-lg font-bold leading-snug tracking-tight text-brand-navy">
                                        {{ $faq->question }}
                                    </h3>
                                </div>
                                <span class="faq-icon mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full border border-slate-200 bg-slate-50 text-slate-500 transition-transform duration-300">
                                    <i class="fa-solid fa-plus text-xs"></i>
                                </span>
                            </button>
                            <div class="faq-body hidden pt-4 mt-4 border-t border-slate-100 text-sm sm:text-base leading-relaxed text-slate-600">
                                {!! nl2br(e($faq->answer)) !!}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>

<!-- ── SCRIPT: RENT CALCULATOR & FAQ ACCORDION ── -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ── 1. RENT CALCULATOR LOGIC ──
    const CITY_DATA = {
        Mumbai: { '1': 32000, '2': 58000, '3': 110000, '4': 185000 },
        Bengaluru: { '1': 22000, '2': 38000, '3': 65000, '4': 110000 },
        'New Delhi': { '1': 25000, '2': 45000, '3': 80000, '4': 140000 },
        Gurugram: { '1': 26000, '2': 46000, '3': 85000, '4': 150000 },
        Noida: { '1': 16000, '2': 26000, '3': 45000, '4': 75000 },
        Hyderabad: { '1': 18000, '2': 32000, '3': 55000, '4': 90000 },
        Pune: { '1': 16000, '2': 30000, '3': 50000, '4': 85000 },
        Chennai: { '1': 16000, '2': 28000, '3': 48000, '4': 80000 },
        Kolkata: { '1': 14000, '2': 22000, '3': 38000, '4': 65000 },
        Ahmedabad: { '1': 14000, '2': 24000, '3': 42000, '4': 70000 },
        Jaipur: { '1': 12000, '2': 21000, '3': 35000, '4': 55000 },
        Goa: { '1': 22000, '2': 35000, '3': 65000, '4': 120000 },
        Kochi: { '1': 14000, '2': 24000, '3': 40000, '4': 68000 },
        Chandigarh: { '1': 15000, '2': 25000, '3': 44000, '4': 75000 },
    };

    const citySelect = document.getElementById('calc-city');
    const bedButtons = document.querySelectorAll('.calc-bed-btn');
    const submitBtn = document.getElementById('calc-submit-btn');
    const resultBox = document.getElementById('calc-result-box');
    const resultPrice = document.getElementById('calc-result-price');

    let selectedCity = '';
    let selectedBed = '';

    function checkReady() {
        if (selectedCity && selectedBed) {
            submitBtn.removeAttribute('disabled');
        } else {
            submitBtn.setAttribute('disabled', 'disabled');
        }
    }

    if (citySelect) {
        citySelect.addEventListener('change', function () {
            selectedCity = this.value;
            resultBox.classList.add('hidden');
            checkReady();
        });
    }

    bedButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            selectedBed = this.getAttribute('data-bed');
            bedButtons.forEach(b => {
                b.classList.remove('bg-brand-cyan', 'text-white', 'border-brand-cyan');
                b.classList.add('bg-slate-50', 'text-slate-700', 'border-slate-200');
            });
            this.classList.remove('bg-slate-50', 'text-slate-700', 'border-slate-200');
            this.classList.add('bg-brand-cyan', 'text-white', 'border-brand-cyan');
            resultBox.classList.add('hidden');
            checkReady();
        });
    });

    if (submitBtn) {
        submitBtn.addEventListener('click', function () {
            if (!selectedCity || !selectedBed) return;
            const price = CITY_DATA[selectedCity]?.[selectedBed] || 0;
            if (price > 0) {
                resultPrice.textContent = '₹' + price.toLocaleString('en-IN') + '/mo';
                resultBox.classList.remove('hidden');
            }
        });
    }

    // ── 2. FAQ ACCORDION LOGIC ──
    const faqItems = document.querySelectorAll('.faq-item');
    faqItems.forEach(item => {
        const toggle = item.querySelector('.faq-toggle');
        const body = item.querySelector('.faq-body');
        const icon = item.querySelector('.faq-icon i');

        if (toggle && body) {
            toggle.addEventListener('click', function () {
                const isOpen = !body.classList.contains('hidden');

                if (isOpen) {
                    body.classList.add('hidden');
                    if (icon) {
                        icon.classList.remove('fa-minus');
                        icon.classList.add('fa-plus');
                    }
                    item.classList.remove('border-brand-cyan/40', 'shadow-md');
                } else {
                    body.classList.remove('hidden');
                    if (icon) {
                        icon.classList.remove('fa-plus');
                        icon.classList.add('fa-minus');
                    }
                    item.classList.add('border-brand-cyan/40', 'shadow-md');
                }
            });
        }
    });
});
</script>

@endsection
