<!DOCTYPE html>
<html lang="en-IN" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $rawPath = request()->getPathInfo() ?: '/';
        $normalizedPath = '/' . ltrim(rtrim($rawPath, '/'), '/');
        if ($rawPath === '/' || $normalizedPath === '') {
            $normalizedPath = '/';
        }

        $seoOverride = \App\Models\SeoMetaOverride::findByPath($normalizedPath);
        $seoIndexService = app(\App\Services\SeoIndexabilityService::class);

        $yieldedTitle = trim($__env->yieldContent('title'));
        $pageTitle = $seoOverride?->meta_title
            ?: ($yieldedTitle ?: 'Housing365 India | Everything Property. One Platform. Verified Owners, Zero Brokerage & Legal Leasing');

        $yieldedDesc = trim($__env->yieldContent('meta_description'));
        $pageDescription = $seoOverride?->meta_description
            ?: ($yieldedDesc ?: 'India\'s unified property platform for leasing, verification, property care, management, buy & sell. 100% verified owners, ₹0 standard owner listing fee, legal e-stamped agreements, and end-to-end property solutions across all Indian cities.');

        $yieldedCanonical = trim($__env->yieldContent('canonical'));
        $pageCanonical = $seoOverride?->canonical_url
            ?: ($yieldedCanonical ?: $seoIndexService->buildCanonicalUrl(url()->current()));

        $yieldedRobots = trim($__env->yieldContent('meta_robots'));
        if ($seoOverride && $seoOverride->is_indexable !== null) {
            $pageRobots = $seoOverride->is_indexable ? 'index, follow' : 'noindex, follow';
        } elseif (!empty($yieldedRobots)) {
            $pageRobots = $yieldedRobots;
        } else {
            $shouldIndex = $seoIndexService->shouldIndex($normalizedPath);
            $pageRobots = $shouldIndex ? 'index, follow' : 'noindex, follow';
        }

        $seoH1 = $seoOverride?->h1;
        view()->share('seoH1', $seoH1);
        view()->share('seoOverride', $seoOverride);
    @endphp
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <meta name="robots" content="{{ $pageRobots }}">
    <link rel="canonical" href="{{ $pageCanonical }}">

    <!-- OpenGraph & Twitter/X Cards -->
    <meta property="og:site_name" content="Housing365 India">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $pageCanonical }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">

    @if(!empty($seoOverride?->schema_json))
        @php
            $customSchema = is_string($seoOverride->schema_json)
                ? json_decode($seoOverride->schema_json, true)
                : $seoOverride->schema_json;
        @endphp
        @if(!empty($customSchema) && (is_array($customSchema) || is_object($customSchema)))
            <script type="application/ld+json">
            {!! json_encode($customSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}
            </script>
        @endif
    @endif

    @if(!empty($seoOverride?->faqs))
        @php
            $rawFaqs = is_string($seoOverride->faqs) ? json_decode($seoOverride->faqs, true) : $seoOverride->faqs;
            $overrideFaqItems = is_array($rawFaqs)
                ? array_values(array_filter($rawFaqs, fn($f) => is_array($f) && !empty(trim((string)($f['question'] ?? ''))) && !empty(trim((string)($f['answer'] ?? '')))))
                : [];
        @endphp
        @if(count($overrideFaqItems) > 0)
            <script type="application/ld+json">
            {!! json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => array_map(fn($f) => [
                    '@type' => 'Question',
                    'name' => trim((string)$f['question']),
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => trim((string)$f['answer']),
                    ],
                ], $overrideFaqItems),
            ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}
            </script>
        @endif
    @endif

    @stack('schema')

    {{-- Google Search Console Site Verification --}}
    @php
        $gscCode = \App\Models\Setting::get('google_search_console_code');
        $gaId = \App\Models\Setting::get('google_analytics_id');
        $customScript = \App\Models\Setting::get('google_analytics_script');
    @endphp
    @if(!empty($gscCode))
        @if(str_contains($gscCode, '<meta'))
            {!! $gscCode !!}
        @else
            <meta name="google-site-verification" content="{{ trim($gscCode) }}">
        @endif
    @endif

    {{-- Google Analytics (GA4) --}}
    @if(!empty($gaId))
        <!-- Google tag (gtag.js) -->

        <script async src="https://www.googletagmanager.com/gtag/js?id={{ trim($gaId) }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ trim($gaId) }}');
        </script>
    @endif

    {{-- Custom Header / Analytics Scripts --}}
    @if(!empty($customScript))
        {!! $customScript !!}
    @endif

    <!-- Favicon and App Icons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <meta name="theme-color" content="#03234B">

    <!-- Google Fonts: Plus Jakarta Sans & DM Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS (CDN with Housing365 design system tokens) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            navy: '#03234B',
                            'navy-light': '#0D3261',
                            'navy-dark': '#021630',
                            'navy-muted': '#1E3A5F',
                            cyan: '#00AEEF',
                            'cyan-hover': '#0096C7',
                            'cyan-dark': '#0284C7',
                            'cyan-light': '#38BDF8',
                            'cyan-muted': '#E0F2FE',
                            emerald: '#00AEEF',
                            'emerald-hover': '#0096C7',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', '"DM Sans"', 'system-ui', '-apple-system', 'sans-serif'],
                        heading: ['"Plus Jakarta Sans"', '"DM Sans"', 'system-ui', '-apple-system', 'sans-serif'],
                        display: ['"Plus Jakarta Sans"', '"DM Sans"', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                    fontSize: {
                        '2xs': ['0.75rem', { lineHeight: '1.15rem' }],
                        xs: ['0.875rem', { lineHeight: '1.35rem' }],
                        sm: ['0.975rem', { lineHeight: '1.5rem' }],
                        base: ['1.0625rem', { lineHeight: '1.7rem' }],
                        lg: ['1.1875rem', { lineHeight: '1.8rem' }],
                        xl: ['1.3125rem', { lineHeight: '1.85rem' }],
                        '2xl': ['1.625rem', { lineHeight: '2.1rem' }],
                        '3xl': ['2rem', { lineHeight: '2.35rem' }],
                        '4xl': ['2.5rem', { lineHeight: '2.75rem' }],
                        '5xl': ['3.25rem', { lineHeight: '1.15' }],
                        '6xl': ['4rem', { lineHeight: '1.1' }],
                    }
                }
            }
        }
    </script>
    <style>
        html {
            font-size: 16.5px;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
        }
        @media (min-width: 1280px) {
            html {
                font-size: 17px;
            }
        }
        body {
            font-family: 'Plus Jakarta Sans', 'DM Sans', system-ui, -apple-system, sans-serif;
            font-size: 1rem;
            line-height: 1.65;
            background-color: #FFFFFF;
            color: #0F172A;
            letter-spacing: -0.012em;
        }
        h1, h2, h3, h4, h5, h6, .font-heading, .font-display {
            font-family: 'Plus Jakarta Sans', 'DM Sans', system-ui, -apple-system, sans-serif !important;
            letter-spacing: -0.028em;
            font-weight: 700;
        }
        p {
            font-size: 1.05rem;
            line-height: 1.7;
        }
        a, button, input, select, textarea {
            font-family: 'Plus Jakarta Sans', 'DM Sans', system-ui, -apple-system, sans-serif;
        }
        /* Global Full Width Layout Override: expand all pages edge-to-edge */
        .max-w-\[1720px\] {
            max-width: 100% !important;
        }
    </style>
    @stack('styles')
    @stack('head')
</head>
<body class="flex flex-col min-h-screen antialiased text-slate-800">

    <!-- Header (Exact Housing365 Navbar) -->
    <header class="sticky top-0 z-50 w-full bg-white/95 backdrop-blur-md border-b border-slate-100 transition-[border-color,box-shadow] duration-300">
        <div class="mx-auto flex h-16 w-full items-center justify-between px-4 sm:px-8 lg:px-12 xl:h-[72px] xl:px-16 2xl:px-20">
            
            <!-- Housing365 Brand Logo SVG -->
            <a href="{{ route('home') }}" class="inline-flex shrink-0 items-center select-none" aria-label="Housing365 - Home">
                <svg viewBox="0 0 335 64" width="220" height="42" fill="none" role="img" aria-label="Housing365" class="block h-auto w-auto object-contain">
                    <title>Housing365</title>
                    <image href="/logo-icon.png" x="2" y="4" width="56" height="56" preserveAspectRatio="xMidYMid meet" onerror="this.style.display='none'" />
                    <circle cx="30" cy="32" r="24" fill="#00AEEF" fill-opacity="0.1" stroke="#00AEEF" stroke-width="2" />
                    <path d="M20 34 L30 24 L40 34 V42 H20 Z" fill="#03234B" />
                    <text x="68" y="34" font-family="-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Plus Jakarta Sans', sans-serif" font-size="28" font-weight="900" letter-spacing="0.3px" fill="#03234B">
                        HOUSING<tspan dx="6" fill="#00AEEF" font-weight="900">365</tspan>
                    </text>
                    <text x="69" y="52" font-family="-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif" font-size="13.5" font-weight="800" letter-spacing="0.3px" fill="#03234B">
                        LEASING &amp; PROPERTY MANAGEMENT
                    </text>
                </svg>
            </a>

            <!-- Desktop Nav Items -->
            <nav class="hidden xl:flex items-center space-x-0.5 text-sm font-semibold">
                
                <!-- Owners Dropdown -->
                <div class="relative group">
                    <button class="flex items-center gap-1 px-3 py-2 rounded-xl text-brand-navy/80 hover:text-brand-navy hover:bg-slate-100 transition">
                        <span>Owners</span>
                        <i class="fa-solid fa-chevron-down text-[9px] text-slate-400 group-hover:rotate-180 transition-transform"></i>
                    </button>
                    <div class="absolute left-0 top-full pt-1.5 hidden group-hover:block z-50">
                        <div class="min-w-[280px] bg-white rounded-2xl shadow-xl border border-slate-100 p-2 space-y-1">
                            <a href="{{ route('owners') }}" class="block px-3 py-2 rounded-xl hover:bg-brand-cyan/5 transition">
                                <span class="font-bold text-xs text-brand-navy block">Owner &amp; Landlord Hub</span>
                                <span class="text-[11px] text-slate-500 block">Residential leasing &amp; management command center</span>
                            </a>
                            <a href="{{ route('properties.post') }}" class="block px-3 py-2 rounded-xl hover:bg-cyan-50/70 transition">
                                <span class="font-bold text-xs text-brand-cyan-dark flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-check text-brand-cyan"></i>
                                    <span>List my Property — Free</span>
                                </span>
                                <span class="text-[11px] text-slate-500 block">₹0 listing fee for standard residential leasing</span>
                            </a>
                            <a href="{{ route('pricing') }}" class="block px-3 py-2 rounded-xl hover:bg-brand-cyan/5 transition">
                                <span class="font-bold text-xs text-brand-navy block">Transparent Pricing</span>
                                <span class="text-[11px] text-slate-500 block">Clear distinction: Free Leasing vs Optional Care</span>
                            </a>
                            <a href="{{ route('property-managers') }}" class="block px-3 py-2 rounded-xl hover:bg-brand-cyan/5 transition">
                                <span class="font-bold text-xs text-brand-navy block">Property Managers</span>
                                <span class="text-[11px] text-slate-500 block">Professional portfolio &amp; tenant oversight</span>
                            </a>
                            <a href="{{ route('portal-and-technology') }}" class="block px-3 py-2 rounded-xl hover:bg-brand-cyan/5 transition">
                                <span class="font-bold text-xs text-brand-navy block">Owner Portal &amp; Tech</span>
                                <span class="text-[11px] text-slate-500 block">Digital agreements, tenant ledgers &amp; inspection logs</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Tenants Dropdown -->
                <div class="relative group">
                    <button class="flex items-center gap-1 px-3 py-2 rounded-xl text-brand-navy/80 hover:text-brand-navy hover:bg-slate-100 transition">
                        <span>Tenants</span>
                        <i class="fa-solid fa-chevron-down text-[9px] text-slate-400 group-hover:rotate-180 transition-transform"></i>
                    </button>
                    <div class="absolute left-0 top-full pt-1.5 hidden group-hover:block z-50">
                        <div class="min-w-[280px] bg-white rounded-2xl shadow-xl border border-slate-100 p-2 space-y-1">
                            <a href="{{ route('tenants') }}" class="block px-3 py-2 rounded-xl hover:bg-brand-cyan/5 transition">
                                <span class="font-bold text-xs text-brand-navy block">Tenant &amp; Renter Hub</span>
                                <span class="text-[11px] text-slate-500 block">Search, verify, move-in and rent easily</span>
                            </a>
                            <a href="{{ route('properties.index') }}" class="block px-3 py-2 rounded-xl hover:bg-brand-cyan/5 transition">
                                <span class="font-bold text-xs text-brand-navy block">Browse Verified Rentals</span>
                                <span class="text-[11px] text-slate-500 block">Flats, villas, and apartments across India</span>
                            </a>
                            <a href="{{ url('/services/rental-agreement-registration') }}" class="block px-3 py-2 rounded-xl hover:bg-brand-cyan/5 transition">
                                <span class="font-bold text-xs text-brand-navy block">Rent Agreement &amp; e-Stamping</span>
                                <span class="text-[11px] text-slate-500 block">Legally enforceable digital lease agreements</span>
                            </a>
                            <a href="{{ url('/properties?property_type=pg') }}" class="block px-3 py-2 rounded-xl hover:bg-brand-cyan/5 transition">
                                <span class="font-bold text-xs text-brand-navy block">PG &amp; Co-Living Accommodations</span>
                                <span class="text-[11px] text-slate-500 block">Flexible living options for young professionals</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Services Mega Menu (6 Groups) -->
                <div class="relative group">
                    <button class="flex items-center gap-1 px-3 py-2 rounded-xl text-brand-navy/80 hover:text-brand-navy hover:bg-slate-100 transition">
                        <span>Services</span>
                        <i class="fa-solid fa-chevron-down text-[9px] text-slate-400 group-hover:rotate-180 transition-transform"></i>
                    </button>
                    <div class="absolute left-1/2 -translate-x-1/2 top-full pt-1.5 hidden group-hover:block z-50">
                        <div class="w-[960px] max-w-[95vw] bg-white rounded-3xl shadow-2xl border border-slate-100 p-6">
                            <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-brand-cyan/10 text-brand-cyan text-xs">
                                        <i class="fa-solid fa-grid-2"></i>
                                    </span>
                                    <div>
                                        <h4 class="text-xs font-black uppercase tracking-wider text-brand-navy">Housing365 Unified Services Directory</h4>
                                        <p class="text-[11px] text-slate-500">Comprehensive real estate, leasing, care &amp; commercial solutions across India</p>
                                    </div>
                                </div>
                                <a href="{{ route('services') }}" class="text-xs font-bold text-brand-cyan hover:underline flex items-center gap-1">
                                    <span>Explore All Services</span>
                                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </a>
                            </div>

                            <div class="grid grid-cols-3 gap-6">
                                <!-- Group 1: Rent & Lease -->
                                <div class="p-3 rounded-2xl bg-slate-50/70 border border-slate-100/80 hover:bg-slate-50 transition">
                                    <div class="flex items-center gap-2 mb-2 pb-1.5 border-b border-slate-200/60">
                                        <i class="fa-solid fa-key text-xs text-brand-cyan"></i>
                                        <h5 class="text-xs font-bold uppercase tracking-wider text-brand-navy">1. Rent &amp; Lease</h5>
                                    </div>
                                    <ul class="space-y-1 text-xs text-slate-600">
                                        <li><a href="{{ route('properties.index', ['listing_type' => 'rent']) }}" class="hover:text-brand-cyan transition block py-0.5">• Residential Rentals</a></li>
                                        <li><a href="{{ url('/services/tenant-placement') }}" class="hover:text-brand-cyan transition block py-0.5">• Tenant Placement</a></li>
                                        <li><a href="{{ url('/services/tenant-screening') }}" class="hover:text-brand-cyan transition block py-0.5">• Tenant Verification</a></li>
                                        <li><a href="{{ url('/services/rental-agreement-registration#police-verification') }}" class="hover:text-brand-cyan transition block py-0.5">• Police Verification Assistance</a></li>
                                        <li><a href="{{ url('/services/rental-agreement-registration') }}" class="hover:text-brand-cyan transition block py-0.5">• Rent Agreement</a></li>
                                        <li><a href="{{ url('/services/rental-agreement-registration#e-stamp') }}" class="hover:text-brand-cyan transition block py-0.5">• E-Stamp</a></li>
                                        <li><a href="{{ url('/services/rental-agreement-registration#notary') }}" class="hover:text-brand-cyan transition block py-0.5">• Notary Coordination</a></li>
                                    </ul>
                                </div>

                                <!-- Group 2: Live & Move -->
                                <div class="p-3 rounded-2xl bg-slate-50/70 border border-slate-100/80 hover:bg-slate-50 transition">
                                    <div class="flex items-center gap-2 mb-2 pb-1.5 border-b border-slate-200/60">
                                        <i class="fa-solid fa-truck-moving text-xs text-brand-cyan"></i>
                                        <h5 class="text-xs font-bold uppercase tracking-wider text-brand-navy">2. Live &amp; Move</h5>
                                    </div>
                                    <ul class="space-y-1 text-xs text-slate-600">
                                        <li><a href="{{ route('properties.index', ['property_type' => 'pg']) }}" class="hover:text-brand-cyan transition block py-0.5">• PG</a></li>
                                        <li><a href="{{ route('properties.index', ['property_type' => 'co-living']) }}" class="hover:text-brand-cyan transition block py-0.5">• Co-living</a></li>
                                        <li><a href="{{ route('services.care') }}#furniture" class="hover:text-brand-cyan transition block py-0.5">• Furniture Rental</a></li>
                                        <li><a href="{{ route('services.care') }}#appliance" class="hover:text-brand-cyan transition block py-0.5">• Appliance Rental</a></li>
                                        <li><a href="{{ url('/services/rental-preparation') }}#cleaning" class="hover:text-brand-cyan transition block py-0.5">• Cleaning</a></li>
                                        <li><a href="{{ route('services.care') }}#packers-movers" class="hover:text-brand-cyan transition block py-0.5">• Packers &amp; Movers</a></li>
                                    </ul>
                                </div>

                                <!-- Group 3: Property Care -->
                                <div class="p-3 rounded-2xl bg-cyan-50/40 border border-cyan-100/80 hover:bg-cyan-50/60 transition">
                                    <div class="flex items-center gap-2 mb-2 pb-1.5 border-b border-cyan-200/60">
                                        <i class="fa-solid fa-shield-heart text-xs text-brand-cyan"></i>
                                        <h5 class="text-xs font-bold uppercase tracking-wider text-brand-navy">3. Property Care</h5>
                                    </div>
                                    <ul class="space-y-1 text-xs text-slate-600">
                                        <li><a href="{{ route('services.care') }}" class="font-bold text-brand-navy hover:text-brand-cyan transition block py-0.5">• Housing365 Care</a></li>
                                        <li><a href="{{ route('services.care') }}#maintenance" class="hover:text-brand-cyan transition block py-0.5">• Maintenance</a></li>
                                        <li><a href="{{ route('services.care') }}#inspection" class="hover:text-brand-cyan transition block py-0.5">• Property Inspection</a></li>
                                        <li><a href="{{ url('/services/rental-preparation') }}" class="hover:text-brand-cyan transition block py-0.5">• Rental Preparation</a></li>
                                        <li>
                                            <a href="{{ url('/services/leasing-services') }}" class="hover:text-brand-cyan transition block py-0.5">
                                                • Property Management
                                                <span class="text-[9px] uppercase px-1.5 py-0.2 rounded bg-amber-100 text-amber-800 font-bold ml-1">Optional</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Group 4: Commercial -->
                                <div class="p-3 rounded-2xl bg-slate-50/70 border border-slate-100/80 hover:bg-slate-50 transition">
                                    <div class="flex items-center gap-2 mb-2 pb-1.5 border-b border-slate-200/60">
                                        <i class="fa-solid fa-building text-xs text-brand-cyan"></i>
                                        <h5 class="text-xs font-bold uppercase tracking-wider text-brand-navy">4. Commercial</h5>
                                    </div>
                                    <ul class="space-y-1 text-xs text-slate-600">
                                        <li><a href="{{ route('properties.index', ['property_type' => 'commercial', 'listing_type' => 'rent']) }}" class="hover:text-brand-cyan transition block py-0.5">• Commercial Rent</a></li>
                                        <li><a href="{{ route('properties.index', ['property_type' => 'office']) }}" class="hover:text-brand-cyan transition block py-0.5">• Office Space</a></li>
                                        <li><a href="{{ route('properties.index', ['property_type' => 'coworking']) }}" class="hover:text-brand-cyan transition block py-0.5">• Coworking</a></li>
                                        <li><a href="{{ route('properties.index', ['property_type' => 'retail']) }}" class="hover:text-brand-cyan transition block py-0.5">• Retail Space</a></li>
                                        <li><a href="{{ route('properties.index', ['property_type' => 'warehouse']) }}" class="hover:text-brand-cyan transition block py-0.5">• Warehouse</a></li>
                                    </ul>
                                </div>

                                <!-- Group 5: Buy & Sell -->
                                <div class="p-3 rounded-2xl bg-slate-50/70 border border-slate-100/80 hover:bg-slate-50 transition">
                                    <div class="flex items-center gap-2 mb-2 pb-1.5 border-b border-slate-200/60">
                                        <i class="fa-solid fa-handshake text-xs text-brand-cyan"></i>
                                        <h5 class="text-xs font-bold uppercase tracking-wider text-brand-navy">5. Buy &amp; Sell</h5>
                                    </div>
                                    <ul class="space-y-1 text-xs text-slate-600">
                                        <li><a href="{{ route('properties.index', ['listing_type' => 'sale']) }}" class="hover:text-brand-cyan transition block py-0.5">• Buy Property</a></li>
                                        <li><a href="{{ route('properties.post', ['listing_type' => 'sale']) }}" class="hover:text-brand-cyan transition block py-0.5">• Sell Property</a></li>
                                        <li><a href="{{ route('properties.index', ['property_type' => 'plot']) }}" class="hover:text-brand-cyan transition block py-0.5">• Land &amp; Plots</a></li>
                                        <li><a href="{{ route('properties.index', ['property_type' => 'commercial']) }}" class="hover:text-brand-cyan transition block py-0.5">• Commercial Property</a></li>
                                        <li><a href="{{ route('properties.index', ['property_type' => 'builder-project']) }}" class="hover:text-brand-cyan transition block py-0.5">• Builder Projects</a></li>
                                    </ul>
                                </div>

                                <!-- Group 6: Professional Network -->
                                <div class="p-3 rounded-2xl bg-slate-50/70 border border-slate-100/80 hover:bg-slate-50 transition">
                                    <div class="flex items-center gap-2 mb-2 pb-1.5 border-b border-slate-200/60">
                                        <i class="fa-solid fa-users text-xs text-brand-cyan"></i>
                                        <h5 class="text-xs font-bold uppercase tracking-wider text-brand-navy">6. Professional Network</h5>
                                    </div>
                                    <ul class="space-y-1 text-xs text-slate-600">
                                        <li><a href="{{ route('franchising') }}#brokers" class="hover:text-brand-cyan transition block py-0.5">• Brokers</a></li>
                                        <li><a href="{{ route('franchising') }}#builders" class="hover:text-brand-cyan transition block py-0.5">• Builders</a></li>
                                        <li><a href="{{ route('services.care') }}#vendors" class="hover:text-brand-cyan transition block py-0.5">• Vendors</a></li>
                                        <li><a href="{{ route('franchising') }}" class="font-bold text-brand-navy hover:text-brand-cyan transition block py-0.5">• Franchising</a></li>
                                        <li><a href="{{ route('refer-and-earn') }}" class="font-bold text-amber-600 hover:text-amber-700 transition block py-0.5">• Refer &amp; Earn ₹100</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="{{ route('pricing') }}" class="px-3 py-2 rounded-xl text-brand-navy/80 hover:text-brand-navy hover:bg-slate-100 transition">
                    Pricing
                </a>

                <a href="{{ route('resources') }}" class="px-3 py-2 rounded-xl text-brand-navy/80 hover:text-brand-navy hover:bg-slate-100 transition">
                    Resources
                </a>

                <a href="{{ route('franchising') }}" class="px-3 py-2 rounded-xl text-brand-navy/80 hover:text-brand-navy hover:bg-slate-100 transition">
                    Franchising
                </a>

                <a href="{{ route('about') }}" class="px-3 py-2 rounded-xl text-brand-navy/80 hover:text-brand-navy hover:bg-slate-100 transition">
                    About
                </a>

                <a href="{{ route('contact') }}" class="px-3 py-2 rounded-xl text-brand-navy/80 hover:text-brand-navy hover:bg-slate-100 transition">
                    Contact
                </a>
            </nav>

            <!-- Right Action Hub -->
            <div class="hidden xl:flex items-center space-x-3">
                <!-- List my Property — Free CTA -->
                <a href="{{ route('properties.post') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-brand-navy hover:bg-brand-navy-light text-white text-xs font-bold uppercase tracking-wider shadow-sm transition hover:-translate-y-0.5">
                    <i class="fa-solid fa-circle-plus text-brand-cyan text-xs"></i>
                    <span>List my Property</span>
                    <span class="bg-brand-cyan text-brand-navy text-[9px] px-1.5 py-0.5 rounded font-black">FREE</span>
                </a>

                <div class="h-5 w-px bg-slate-200"></div>

                @auth
                    <!-- Authenticated User Menu -->
                    <div class="relative group">
                        <button class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 text-slate-800 px-3 py-1.5 text-xs font-bold transition hover:bg-white shadow-sm">
                            <div class="size-6 rounded-full bg-brand-navy text-white flex items-center justify-center text-[10px] font-bold">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="max-w-[85px] truncate">{{ auth()->user()->name }}</span>
                            <i class="fa-solid fa-chevron-down text-[9px] text-slate-400 group-hover:rotate-180 transition-transform"></i>
                        </button>
                        <div class="absolute right-0 top-full pt-1.5 hidden group-hover:block z-50">
                            <div class="min-w-[210px] bg-white rounded-2xl shadow-xl border border-slate-100 p-2 space-y-1">
                                <div class="px-3 py-2 border-b border-slate-100">
                                    <span class="text-xs font-bold text-brand-navy block truncate">{{ auth()->user()->name }}</span>
                                    <span class="text-[11px] text-slate-500 block truncate">{{ auth()->user()->email }}</span>
                                </div>
                                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-bold text-brand-navy bg-brand-cyan/10 hover:bg-brand-cyan/20 transition">
                                    <i class="fa-solid fa-shapes text-brand-cyan text-xs"></i>
                                    <span>My Workspaces</span>
                                </a>
                                <a href="{{ route('properties.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-brand-navy transition">
                                    <i class="fa-solid fa-building text-brand-cyan text-xs"></i>
                                    <span>Browse Properties</span>
                                </a>
                                <a href="{{ route('properties.post') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-brand-navy transition">
                                    <i class="fa-solid fa-circle-plus text-brand-cyan text-xs"></i>
                                    <span>Post a Property</span>
                                </a>
                                <a href="{{ route('dashboard.referrals') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-brand-navy transition">
                                    <i class="fa-solid fa-gift text-amber-500 text-xs"></i>
                                    <span>Refer &amp; Earn ₹100</span>
                                </a>
                                <a href="{{ route('dashboard.rewards') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-brand-navy transition">
                                    <i class="fa-solid fa-wallet text-emerald-600 text-xs"></i>
                                    <span>My Rewards</span>
                                </a>
                                <form action="{{ route('logout') }}" method="POST" class="pt-1 border-t border-slate-100">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-bold text-rose-600 hover:bg-rose-50 transition text-left">
                                        <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                                        <span>Sign Out</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Clean Sign In / Register Links -->
                    <div class="flex items-center gap-1">
                        <a href="{{ route('login') }}" class="px-2.5 py-1.5 text-xs font-bold text-slate-600 hover:text-brand-navy transition rounded-lg hover:bg-slate-50">
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" class="px-3 py-1.5 rounded-xl bg-brand-cyan hover:bg-brand-cyan-hover text-white text-xs font-bold uppercase tracking-wider shadow-sm transition">
                            Register
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Mobile Hamburger Button -->
            <div class="xl:hidden flex items-center space-x-2">
                <a href="{{ route('properties.post') }}" class="inline-flex items-center justify-center px-2.5 py-1.5 rounded-lg bg-brand-navy text-white text-[11px] font-bold uppercase tracking-wider shadow-sm">
                    + List Free
                </a>
                <button id="mobile-toggle-btn" class="p-2 text-slate-700 hover:text-brand-cyan focus:outline-none">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Drawer -->
        <div id="mobile-drawer" class="hidden xl:hidden border-t border-slate-100 bg-white px-4 py-4 space-y-2 text-sm font-medium max-h-[85vh] overflow-y-auto">
            @auth
                <div class="p-3 mb-2 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-brand-navy block">{{ auth()->user()->name }}</span>
                        <a href="{{ route('dashboard') }}" class="text-[11px] font-bold text-brand-cyan hover:underline flex items-center gap-1 mt-0.5">
                            <i class="fa-solid fa-shapes text-[10px]"></i>
                            <span>My Workspaces &rarr;</span>
                        </a>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="p-1 text-rose-600 text-xs font-bold">
                            Sign Out
                        </button>
                    </form>
                </div>
            @else
                <div class="grid grid-cols-2 gap-2 mb-2">
                    <a href="{{ route('login') }}" class="py-2 px-3 text-center rounded-xl border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-50">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="py-2 px-3 text-center rounded-xl bg-brand-cyan text-white text-xs font-bold hover:bg-brand-cyan-hover">
                        Register Free
                    </a>
                </div>
            @endauth

            <div class="p-1 mb-2">
                <a href="{{ route('properties.post') }}" class="flex items-center justify-center gap-2 w-full py-2.5 px-4 rounded-xl bg-brand-navy text-white font-bold text-xs uppercase tracking-wider shadow-sm transition">
                    <i class="fa-solid fa-circle-plus text-brand-cyan"></i>
                    <span>List my Property — Free</span>
                </a>
            </div>
            
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-50">Home</a>
            <a href="{{ route('owners') }}" class="block px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-50 font-bold text-brand-navy">Owners</a>
            <a href="{{ route('tenants') }}" class="block px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-50 font-bold text-brand-navy">Tenants</a>
            <a href="{{ route('services') }}" class="block px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-50 font-bold text-brand-navy">Services &amp; Mega Menu</a>
            <a href="{{ route('pricing') }}" class="block px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-50">Pricing</a>
            <a href="{{ route('resources') }}" class="block px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-50 font-bold text-brand-navy">Resources &amp; Guides</a>
            <a href="{{ route('franchising') }}" class="block px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-50 font-bold text-brand-navy">Franchising Network</a>
            <a href="{{ route('services.care') }}" class="block px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-50 font-bold text-brand-cyan">Housing365 Care</a>
            <a href="{{ route('properties.index') }}" class="block px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-50">Browse Properties</a>
            <a href="{{ url('/india') }}" class="block px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-50 font-semibold text-brand-cyan">All India Cities Directory</a>
            <a href="{{ route('about') }}" class="block px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-50">About</a>
            <a href="{{ route('contact') }}" class="block px-3 py-2.5 rounded-xl bg-brand-cyan text-white text-center font-bold mt-2">Contact Us</a>
        </div>
    </header>

    <!-- Notification Toast -->
    @if(session('success'))
        <div class="w-full px-4 sm:px-8 lg:px-12 xl:px-16 2xl:px-20 my-4">
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 text-emerald-800 text-sm rounded-r-xl shadow-sm flex items-center justify-between w-full">
                <div class="flex items-center">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-lg mr-3"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 font-bold">&times;</button>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Wave Separator SVG -->
    <div class="relative -mb-px overflow-hidden leading-[0]">
        <svg viewBox="0 0 1200 80" preserveAspectRatio="none" class="block h-12 w-full sm:h-16 md:h-20" aria-hidden="true">
            <path d="M0,40 C200,80 400,0 600,40 C800,80 1000,0 1200,40 L1200,80 L0,80 Z" fill="#0B1D3A" />
        </svg>
    </div>

    <!-- Footer (Exact Housing365 Footer) -->
    <footer class="bg-[#0B1D3A] text-white" role="contentinfo">
        <div class="mx-auto w-full px-4 pb-28 pt-12 sm:px-8 lg:px-12 xl:px-16 2xl:px-20 lg:pb-8 lg:pt-16">
            <div class="grid grid-cols-1 gap-10 lg:grid-cols-12 lg:gap-8">
                
                <!-- Brand Column -->
                <div class="lg:col-span-4">
                    <!-- Logo in crisp White -->
                    <a href="{{ route('home') }}" class="inline-flex shrink-0 items-center select-none" aria-label="Housing365">
                        <svg viewBox="0 0 335 64" width="220" height="42" fill="none" role="img" class="block h-auto w-auto object-contain">
                            <title>Housing365</title>
                            <image href="/logo-icon-white.png" x="2" y="4" width="56" height="56" preserveAspectRatio="xMidYMid meet" onerror="this.style.display='none'" />
                            <circle cx="30" cy="32" r="24" fill="#FFFFFF" fill-opacity="0.1" stroke="#00AEEF" stroke-width="2" />
                            <path d="M20 34 L30 24 L40 34 V42 H20 Z" fill="#FFFFFF" />
                            <text x="68" y="34" font-family="-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Plus Jakarta Sans', sans-serif" font-size="28" font-weight="900" letter-spacing="0.3px" fill="#FFFFFF">
                                HOUSING<tspan dx="6" fill="#00AEEF" font-weight="900">365</tspan>
                            </text>
                            <text x="69" y="52" font-family="-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif" font-size="13.5" font-weight="800" letter-spacing="0.3px" fill="#38BDF8">
                                LEASING &amp; PROPERTY MANAGEMENT
                            </text>
                        </svg>
                    </a>

                    <p class="mt-4 max-w-xs text-sm leading-relaxed text-slate-400">
                        {{ $settings['site_tagline'] ?? 'Full-service leasing and property management delivering end-to-end leasing execution for landlords, NRIs, and property owners across all major metropolitan cities in India.' }}
                    </p>

                    <!-- Contact Info -->
                    <div class="mt-5 space-y-1.5">
                        <a href="tel:{{ $settings['contact_phone'] ?? '+916376163263' }}" class="flex items-center gap-2 text-sm text-slate-400 transition-colors hover:text-brand-cyan">
                            <i class="fa-solid fa-phone text-xs text-brand-cyan"></i>
                            <span>{{ $settings['contact_phone'] ?? '+91 63761 63263' }}</span>
                        </a>
                        <a href="tel:{{ $settings['contact_phone_alt'] ?? '+916376163263' }}" class="flex items-center gap-2 text-sm text-slate-400 transition-colors hover:text-brand-cyan">
                            <i class="fa-solid fa-phone text-xs text-brand-cyan"></i>
                            <span>{{ $settings['contact_phone_alt'] ?? '+91 63761 63263' }}</span>
                        </a>
                        <a href="mailto:{{ $settings['contact_email'] ?? 'contact@housing365.in' }}" class="flex items-center gap-2 text-sm text-slate-400 transition-colors hover:text-brand-cyan">
                            <i class="fa-solid fa-envelope text-xs text-brand-cyan"></i>
                            <span>{{ $settings['contact_email'] ?? 'contact@housing365.in' }}</span>
                        </a>
                        <address class="flex items-start gap-2 text-sm not-italic text-slate-400">
                            <i class="fa-solid fa-location-dot text-xs text-brand-cyan mt-1"></i>
                            <span>{{ $settings['office_address'] ?? 'Indiqube Emerald, Sector 2, HSR Layout, Bengaluru, Karnataka 560102, India' }}</span>
                        </address>
                    </div>

                    <!-- Social Icons -->
                    <div class="mt-6 flex items-center gap-4 text-slate-400">
                        <a href="{{ $settings['facebook_url'] ?? 'https://facebook.com/housing365' }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook" title="Facebook" class="hover:text-brand-cyan transition"><i class="fa-brands fa-facebook-f text-base"></i></a>
                        <a href="{{ $settings['instagram_url'] ?? 'https://instagram.com/housing365' }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram" title="Instagram" class="hover:text-brand-cyan transition"><i class="fa-brands fa-instagram text-base"></i></a>
                        <a href="{{ $settings['linkedin_url'] ?? 'https://www.linkedin.com/company/housing365/' }}" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn" title="LinkedIn" class="hover:text-brand-cyan transition"><i class="fa-brands fa-linkedin-in text-base"></i></a>
                        <a href="{{ $settings['tiktok_url'] ?? 'https://www.tiktok.com/@housing365' }}" target="_blank" rel="noopener noreferrer" aria-label="TikTok" title="TikTok" class="hover:text-brand-cyan transition"><i class="fa-brands fa-tiktok text-base"></i></a>
                        <a href="{{ $settings['x_url'] ?? 'https://x.com/housing365' }}" target="_blank" rel="noopener noreferrer" aria-label="X (Twitter)" title="X (Twitter)" class="hover:text-brand-cyan transition"><i class="fa-brands fa-x-twitter text-base"></i></a>
                        <a href="https://youtube.com/@housing365" target="_blank" rel="noopener noreferrer" aria-label="YouTube" title="YouTube" class="hover:text-brand-cyan transition"><i class="fa-brands fa-youtube text-base"></i></a>
                    </div>
                </div>

                <!-- 6 Link Columns -->
                <div class="grid grid-cols-2 gap-8 sm:grid-cols-3 lg:col-span-8 lg:grid-cols-6">
                    <div>
                        <h3 class="mb-4 text-xs font-semibold uppercase tracking-widest text-brand-cyan">Core Services</h3>
                        <ul class="space-y-2.5 text-sm text-slate-400">
                            <li><a href="{{ url('/services/tenant-placement') }}" class="hover:text-white transition">Tenant Placement</a></li>
                            <li><a href="{{ route('services.care') }}" class="hover:text-white transition">Housing365 Care</a></li>
                            <li><a href="{{ url('/services/rental-agreement-registration') }}" class="hover:text-white transition">Rent Agreement &amp; e-Stamp</a></li>
                            <li><a href="{{ url('/services/tenant-screening') }}" class="hover:text-white transition">Tenant Verification</a></li>
                            <li><a href="{{ url('/services/rental-preparation') }}" class="hover:text-white transition">Rental Preparation</a></li>
                            <li><a href="{{ url('/services/leasing-services') }}" class="hover:text-white transition">Leasing Services</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="mb-4 text-xs font-semibold uppercase tracking-widest text-brand-cyan">Metro Hubs</h3>
                        <ul class="space-y-2.5 text-sm text-slate-400">
                            <li><a href="{{ url('/india/maharashtra/mumbai') }}" class="hover:text-white transition">Mumbai MMR</a></li>
                            <li><a href="{{ url('/india/karnataka/bengaluru') }}" class="hover:text-white transition">Bengaluru Tech Hub</a></li>
                            <li><a href="{{ url('/india/delhi/central-delhi') }}" class="hover:text-white transition">Delhi NCR</a></li>
                            <li><a href="{{ url('/india/haryana/gurugram') }}" class="hover:text-white transition">Gurugram Cyber City</a></li>
                            <li><a href="{{ url('/india/telangana/hyderabad') }}" class="hover:text-white transition">Hyderabad HITEC City</a></li>
                            <li><a href="{{ url('/india/maharashtra/pune') }}" class="hover:text-white transition">Pune IT Hub</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="mb-4 text-xs font-semibold uppercase tracking-widest text-brand-cyan">States &amp; UTs</h3>
                        <ul class="space-y-2.5 text-sm text-slate-400">
                            <li><a href="{{ url('/india/maharashtra') }}" class="hover:text-white transition">Maharashtra</a></li>
                            <li><a href="{{ url('/india/karnataka') }}" class="hover:text-white transition">Karnataka</a></li>
                            <li><a href="{{ url('/india/delhi') }}" class="hover:text-white transition">Delhi NCT</a></li>
                            <li><a href="{{ url('/india/haryana') }}" class="hover:text-white transition">Haryana</a></li>
                            <li><a href="{{ url('/india/tamil-nadu') }}" class="hover:text-white transition">Tamil Nadu</a></li>
                            <li><a href="{{ url('/india') }}" class="hover:text-brand-cyan transition font-semibold">View All 28 States &rarr;</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="mb-4 text-xs font-semibold uppercase tracking-widest text-brand-cyan">Owners &amp; Landlords</h3>
                        <ul class="space-y-2.5 text-sm text-slate-400">
                            <li><a href="{{ route('properties.post') }}" class="text-brand-cyan hover:text-cyan-300 font-bold flex items-center gap-1.5"><i class="fa-solid fa-circle-plus text-xs"></i> List Property Free</a></li>
                            <li><a href="{{ route('owners') }}" class="hover:text-white transition">Owner Hub</a></li>
                            <li><a href="{{ route('property-managers') }}" class="hover:text-white transition">Property Managers</a></li>
                            <li><a href="{{ route('portal-and-technology') }}" class="hover:text-white transition">Portal &amp; Technology</a></li>
                            <li><a href="{{ route('pricing') }}" class="hover:text-white transition">Transparent Pricing</a></li>
                            <li><a href="{{ route('services.care') }}" class="hover:text-white transition">Housing365 Care</a></li>
                            <li><a href="{{ route('refer-and-earn') }}" class="text-amber-400 hover:text-amber-300 font-bold transition">Refer &amp; Earn ₹100</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="mb-4 text-xs font-semibold uppercase tracking-widest text-brand-cyan">Tenants &amp; Living</h3>
                        <ul class="space-y-2.5 text-sm text-slate-400">
                            <li><a href="{{ route('tenants') }}" class="hover:text-white transition">Tenant Hub</a></li>
                            <li><a href="{{ route('properties.index') }}" class="hover:text-white transition">Browse Rentals &amp; Resale</a></li>
                            <li><a href="{{ url('/services/rental-agreement-registration') }}" class="hover:text-white transition">Rent Agreement</a></li>
                            <li><a href="{{ url('/properties?property_type=pg') }}" class="hover:text-white transition">PG &amp; Co-Living</a></li>
                            <li><a href="{{ url('/services/tenant-insurance') }}" class="hover:text-white transition">Tenant Insurance</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="mb-4 text-xs font-semibold uppercase tracking-widest text-brand-cyan">Company &amp; Network</h3>
                        <ul class="space-y-2.5 text-sm text-slate-400">
                            <li><a href="{{ route('franchising') }}" class="hover:text-white transition">Franchising Network</a></li>
                            <li><a href="{{ route('resources') }}" class="hover:text-white transition">Resources &amp; Guides</a></li>
                            <li><a href="{{ route('about') }}" class="hover:text-white transition">About Us</a></li>
                            <li><a href="{{ route('blog.index') }}" class="hover:text-white transition">Blog &amp; Insights</a></li>
                            <li><a href="{{ route('reviews') }}" class="hover:text-white transition">Client Reviews</a></li>
                            <li><a href="{{ route('contact') }}" class="hover:text-white transition">Contact Us</a></li>
                            <li><a href="{{ route('refer-and-earn.terms') }}" class="hover:text-white transition">Referral Terms</a></li>
                        </ul>
                    </div>
                </div>

            </div>

            <!-- Bottom Bar -->
            <div class="mt-12 flex flex-col items-center justify-between gap-4 border-t border-white/10 pt-8 sm:flex-row text-sm text-slate-500">
                <p>{{ $settings['footer_copyright'] ?? '© 2026 Housing365 India. All rights reserved.' }}</p>
                <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 sm:justify-end">
                    <a href="{{ route('contact') }}" class="hover:text-slate-300">Privacy Policy</a>
                    <a href="{{ route('contact') }}" class="hover:text-slate-300">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- ── HOUSING365 FLOATING WHATSAPP CHAT BOX ── -->
    @php
        $rawWaPhone = \App\Models\Setting::get('whatsapp_number') ?: (\App\Models\Setting::get('contact_phone') ?: '+916376163263');
        $cleanWaDigits = preg_replace('/[^0-9]/', '', (string) $rawWaPhone);
        if (strlen($cleanWaDigits) === 10) {
            $cleanWaDigits = '91' . $cleanWaDigits;
        }
        $defaultWaMsg = \App\Models\Setting::get('whatsapp_default_message') ?: 'Hi Housing365 India, I am interested in property services.';
    @endphp
    <div id="h365-wa-widget" class="fixed z-50 bottom-20 lg:bottom-6 right-4 sm:right-6 flex flex-col items-end pointer-events-auto">
        <!-- Floating Popup Chat Window (Toggled via JS) -->
        <div id="h365-wa-popup" class="hidden mb-3 w-[320px] sm:w-[360px] bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden transform transition-all duration-300 origin-bottom-right">
            <!-- Header -->
            <div class="bg-gradient-to-r from-brand-navy via-brand-navy-light to-brand-navy-dark p-4 text-white relative">
                <button type="button" onclick="toggleWhatsAppChat()" class="absolute top-3.5 right-3.5 w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition" aria-label="Close Chat">
                    <i class="fa-solid fa-xmark text-xs"></i>
                </button>
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <div class="w-11 h-11 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xl shadow-inner font-bold">
                            <i class="fa-brands fa-whatsapp"></i>
                        </div>
                        <span class="absolute bottom-0 right-0 w-3 h-3 rounded-full bg-emerald-400 border-2 border-brand-navy"></span>
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <h4 class="font-bold text-sm tracking-tight text-white leading-tight">Housing365 India</h4>
                            <i class="fa-solid fa-circle-check text-emerald-400 text-xs" title="Verified Concierge"></i>
                        </div>
                        <p class="text-[11px] text-emerald-300 font-medium">Online &bull; Quick WhatsApp Support</p>
                    </div>
                </div>
            </div>

            <!-- Body with Chat Bubbles -->
            <div class="p-4 bg-slate-50 space-y-3 max-h-[340px] overflow-y-auto">
                <!-- Welcome Agent Bubble -->
                <div class="flex items-start gap-2.5">
                    <div class="w-7 h-7 rounded-full bg-brand-navy text-white text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <div class="bg-white p-3 rounded-2xl rounded-tl-none border border-slate-200/80 shadow-sm text-xs text-slate-700 leading-relaxed max-w-[85%]">
                        <p class="font-bold text-brand-navy mb-1">Namaste! 🙏</p>
                        Welcome to Housing365 India. Looking to rent, list, buy, or manage a property?
                        <span class="block text-[10px] text-slate-400 text-right mt-1">Just now</span>
                    </div>
                </div>

                <!-- Quick Prompt Option Pills -->
                <div class="pt-1">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">How can we help you?</p>
                    <div class="grid grid-cols-1 gap-1.5">
                        <button type="button" onclick="sendWhatsAppQuick('Hi Housing365, I am looking to rent a verified property in India.')" class="text-left w-full px-3 py-2 rounded-xl bg-white hover:bg-emerald-50 border border-slate-200 hover:border-emerald-300 text-xs font-medium text-slate-700 transition flex items-center justify-between group">
                            <span>🔑 Find Rental Property</span>
                            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400 group-hover:text-emerald-600 transition"></i>
                        </button>
                        <button type="button" onclick="sendWhatsAppQuick('Hi Housing365, I am an owner and want to list my property with ₹0 brokerage.')" class="text-left w-full px-3 py-2 rounded-xl bg-white hover:bg-emerald-50 border border-slate-200 hover:border-emerald-300 text-xs font-medium text-slate-700 transition flex items-center justify-between group">
                            <span>🏠 List Property Free (₹0 Brokerage)</span>
                            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400 group-hover:text-emerald-600 transition"></i>
                        </button>
                        <button type="button" onclick="sendWhatsAppQuick('Hi Housing365, I need an online 11-month legal rent agreement with e-stamp.')" class="text-left w-full px-3 py-2 rounded-xl bg-white hover:bg-emerald-50 border border-slate-200 hover:border-emerald-300 text-xs font-medium text-slate-700 transition flex items-center justify-between group">
                            <span>📜 Rent Agreement &amp; e-Stamp</span>
                            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400 group-hover:text-emerald-600 transition"></i>
                        </button>
                        <button type="button" onclick="sendWhatsAppQuick('Hi Housing365, I want to know more about Housing365 Care property management.')" class="text-left w-full px-3 py-2 rounded-xl bg-white hover:bg-emerald-50 border border-slate-200 hover:border-emerald-300 text-xs font-medium text-slate-700 transition flex items-center justify-between group">
                            <span>🛡️ Housing365 Care &amp; Management</span>
                            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400 group-hover:text-emerald-600 transition"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Bottom Direct Action -->
            <div class="p-3 bg-white border-t border-slate-100">
                <a id="h365-wa-direct-link" href="https://wa.me/{{ $cleanWaDigits }}?text={{ urlencode($defaultWaMsg) }}" target="_blank" rel="noopener noreferrer" class="w-full py-2.5 px-4 rounded-xl bg-[#25D366] hover:bg-[#20BD5A] text-white font-bold text-xs uppercase tracking-wider shadow-md hover:shadow-lg flex items-center justify-center gap-2 transition">
                    <i class="fa-brands fa-whatsapp text-lg"></i>
                    <span>Start WhatsApp Chat</span>
                </a>
            </div>
        </div>

        <!-- Floating Button Trigger -->
        <button id="h365-wa-trigger" type="button" onclick="toggleWhatsAppChat()" class="group relative flex items-center justify-center w-14 h-14 rounded-full bg-[#25D366] hover:bg-[#20BD5A] text-white shadow-xl hover:shadow-2xl hover:scale-105 transition-all duration-300 focus:outline-none" aria-label="Chat on WhatsApp">
            <!-- Ping animation ring -->
            <span class="absolute -inset-1 rounded-full bg-[#25D366] opacity-30 group-hover:opacity-50 animate-ping pointer-events-none"></span>
            <i class="fa-brands fa-whatsapp text-3xl relative z-10"></i>
            <span class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-red-500 border-2 border-white text-white text-[10px] font-black flex items-center justify-center shadow">1</span>
        </button>
    </div>

    <!-- Mobile Sticky Bottom CTA Bar -->
    <div class="fixed bottom-0 left-0 right-0 z-50 lg:hidden p-2.5 bg-white/95 backdrop-blur-lg border-t border-slate-200 shadow-xl">
        <div class="flex items-center gap-2">
            <a href="tel:{{ $settings['contact_phone'] ?? '+916376163263' }}" class="flex items-center justify-center w-11 h-11 rounded-xl bg-slate-50 text-brand-cyan border border-slate-200 shrink-0" aria-label="Call Housing365">
                <i class="fa-solid fa-phone text-sm"></i>
            </a>
            <a href="{{ route('properties.post') }}" class="flex-1 flex items-center justify-center gap-1.5 h-11 rounded-xl bg-brand-navy hover:bg-brand-navy-light text-white font-bold text-xs uppercase tracking-wider shadow-sm transition">
                <i class="fa-solid fa-plus-circle text-brand-cyan text-xs"></i>
                <span>List Free</span>
            </a>
            <a href="{{ route('properties.index') }}" class="flex-1 flex items-center justify-center h-11 rounded-xl bg-slate-100 hover:bg-slate-200 text-brand-navy font-bold text-xs uppercase tracking-wider transition">
                Browse
            </a>
        </div>
    </div>

    <!-- Mobile Drawer JS & WhatsApp Chat Toggle JS -->
    <script>
        document.getElementById('mobile-toggle-btn').addEventListener('click', function() {
            var d = document.getElementById('mobile-drawer');
            d.classList.toggle('hidden');
        });

        function toggleWhatsAppChat() {
            var popup = document.getElementById('h365-wa-popup');
            if (popup) {
                popup.classList.toggle('hidden');
            }
        }

        function sendWhatsAppQuick(message) {
            var phone = '{{ $cleanWaDigits }}';
            var url = 'https://wa.me/' + phone + '?text=' + encodeURIComponent(message);
            window.open(url, '_blank');
        }
    </script>
    @stack('scripts')
</body>
</html>
