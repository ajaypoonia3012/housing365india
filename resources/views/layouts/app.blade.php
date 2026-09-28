<!DOCTYPE html>
<html lang="en-IN" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Housing 365 India | Verified Owners, Zero Brokerage & Legal Leasing Across All Indian Cities')</title>
    <meta name="description" content="@yield('meta_description', 'India\'s institutional leasing & rental platform outsmarting MagicBricks, 99acres & NoBroker. 100% verified owners, zero broker spam calls, 11-month e-stamped legal agreements, and biometric police verification across Mumbai, Bengaluru, Delhi NCR, Hyderabad, Pune, and every city in India.')">

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

    <!-- Font: DM Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">

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
                        sans: ['"DM Sans"', 'system-ui', '-apple-system', 'sans-serif'],
                        heading: ['"DM Sans"', 'system-ui', '-apple-system', 'sans-serif'],
                        display: ['"DM Sans"', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                    fontSize: {
                        xs: ['0.8125rem', { lineHeight: '1.25rem' }],
                        sm: ['0.9375rem', { lineHeight: '1.4rem' }],
                        base: ['1rem', { lineHeight: '1.65rem' }],
                        lg: ['1.125rem', { lineHeight: '1.75rem' }],
                        xl: ['1.25rem', { lineHeight: '1.75rem' }],
                        '2xl': ['1.5rem', { lineHeight: '2rem' }],
                        '3xl': ['1.875rem', { lineHeight: '2.25rem' }],
                        '4xl': ['2.25rem', { lineHeight: '2.5rem' }],
                        '5xl': ['3rem', { lineHeight: '1.15' }],
                        '6xl': ['3.75rem', { lineHeight: '1.1' }],
                    }
                }
            }
        }
    </script>
    <style>
        html {
            font-size: 16px;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        body {
            font-family: 'DM Sans', system-ui, -apple-system, sans-serif;
            font-size: 16px;
            line-height: 1.6;
            background-color: #FFFFFF;
            color: #1E293B;
        }
        h1, h2, h3, h4, h5, h6, .font-heading, .font-display {
            font-family: 'DM Sans', system-ui, -apple-system, sans-serif !important;
            letter-spacing: -0.02em;
        }
        p {
            font-size: 1rem;
            line-height: 1.65;
        }
        a, button, input, select, textarea {
            font-family: 'DM Sans', system-ui, -apple-system, sans-serif;
        }
    </style>
    @stack('styles')
</head>
<body class="flex flex-col min-h-screen antialiased text-slate-800">

    <!-- Header (Exact Housing365 Navbar) -->
    <header class="sticky top-0 z-50 w-full bg-white/95 backdrop-blur-md border-b border-slate-100 transition-[border-color,box-shadow] duration-300">
        <div class="mx-auto flex h-16 w-full max-w-[1720px] items-center justify-between px-4 sm:px-8 lg:px-12 xl:h-[72px] xl:px-16">
            
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

            <!-- Desktop Nav Groups (xl+ only) -->
            <nav class="hidden xl:flex items-center space-x-1 text-sm font-semibold">
                <a href="{{ route('properties.index') }}" class="px-3.5 py-2 rounded-xl text-brand-navy/80 hover:text-brand-navy hover:bg-slate-100 transition">
                    Properties
                </a>
                
                <!-- Owners Dropdown -->
                <div class="relative group">
                    <button class="flex items-center gap-1 px-3.5 py-2 rounded-xl text-brand-navy/80 hover:text-brand-navy hover:bg-slate-100 transition">
                        <span>Owners</span>
                        <i class="fa-solid fa-chevron-down text-[9px] text-slate-400 group-hover:rotate-180 transition-transform"></i>
                    </button>
                    <div class="absolute left-0 top-full pt-1.5 hidden group-hover:block z-50">
                        <div class="min-w-[270px] bg-white rounded-2xl shadow-xl border border-slate-100 p-2 space-y-1">
                            <a href="{{ route('owners') }}" class="block px-3 py-2 rounded-xl hover:bg-brand-cyan/5 transition">
                                <span class="font-bold text-xs text-brand-navy block">Landlord &amp; Owner Hub</span>
                                <span class="text-[11px] text-slate-500 block">Command centre for property owners</span>
                            </a>
                            <a href="{{ route('properties.post') }}" class="block px-3 py-2 rounded-xl hover:bg-amber-50 transition">
                                <span class="font-bold text-xs text-amber-800 flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-plus text-amber-500"></i>
                                    <span>Post Property (Free Listing)</span>
                                </span>
                                <span class="text-[11px] text-slate-500 block">List rental or resale home in 2 mins</span>
                            </a>
                            <a href="{{ route('pricing') }}" class="block px-3 py-2 rounded-xl hover:bg-brand-cyan/5 transition">
                                <span class="font-bold text-xs text-brand-navy block">Transparent Pricing</span>
                                <span class="text-[11px] text-slate-500 block">199 setup fee, no monthly retainer</span>
                            </a>
                            <a href="{{ route('property-managers') }}" class="block px-3 py-2 rounded-xl hover:bg-brand-cyan/5 transition">
                                <span class="font-bold text-xs text-brand-navy block">Property Managers</span>
                                <span class="text-[11px] text-slate-500 block">Institutional leasing-as-a-service</span>
                            </a>
                            <a href="{{ route('portal-and-technology') }}" class="block px-3 py-2 rounded-xl hover:bg-brand-cyan/5 transition">
                                <span class="font-bold text-xs text-brand-navy block">Owner Portal &amp; Tech</span>
                                <span class="text-[11px] text-slate-500 block">Real-time tenant ledger &amp; inspection logs</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Tenants Dropdown -->
                <div class="relative group">
                    <button class="flex items-center gap-1 px-3.5 py-2 rounded-xl text-brand-navy/80 hover:text-brand-navy hover:bg-slate-100 transition">
                        <span>Tenants</span>
                        <i class="fa-solid fa-chevron-down text-[9px] text-slate-400 group-hover:rotate-180 transition-transform"></i>
                    </button>
                    <div class="absolute left-0 top-full pt-1.5 hidden group-hover:block z-50">
                        <div class="min-w-[270px] bg-white rounded-2xl shadow-xl border border-slate-100 p-2 space-y-1">
                            <a href="{{ route('tenants') }}" class="block px-3 py-2 rounded-xl hover:bg-brand-cyan/5 transition">
                                <span class="font-bold text-xs text-brand-navy block">Tenant Hub</span>
                                <span class="text-[11px] text-slate-500 block">Everything renters need in one place</span>
                            </a>
                            <a href="{{ route('properties.index') }}" class="block px-3 py-2 rounded-xl hover:bg-brand-cyan/5 transition">
                                <span class="font-bold text-xs text-brand-navy block">Browse Verified Rentals</span>
                                <span class="text-[11px] text-slate-500 block">Flats, villas, and PGs across top cities</span>
                            </a>
                            <a href="{{ url('/services/rental-agreement-registration') }}" class="block px-3 py-2 rounded-xl hover:bg-brand-cyan/5 transition">
                                <span class="font-bold text-xs text-brand-navy block">Rent Agreement &amp; e-Stamping</span>
                                <span class="text-[11px] text-slate-500 block">11-month draft delivered digitally</span>
                            </a>
                            <a href="{{ url('/services/tenant-insurance') }}" class="block px-3 py-2 rounded-xl hover:bg-brand-cyan/5 transition">
                                <span class="font-bold text-xs text-brand-navy block">Tenant Insurance</span>
                                <span class="text-[11px] text-slate-500 block">Mandatory and contents coverage</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Services Dropdown -->
                <div class="relative group">
                    <button class="flex items-center gap-1 px-3.5 py-2 rounded-xl text-brand-navy/80 hover:text-brand-navy hover:bg-slate-100 transition">
                        <span>Services</span>
                        <i class="fa-solid fa-chevron-down text-[9px] text-slate-400 group-hover:rotate-180 transition-transform"></i>
                    </button>
                    <div class="absolute left-0 top-full pt-1.5 hidden group-hover:block z-50">
                        <div class="min-w-[300px] bg-white rounded-2xl shadow-xl border border-slate-100 p-2 space-y-1">
                            <a href="{{ url('/services/tenant-placement') }}" class="block px-3 py-2 rounded-xl hover:bg-brand-cyan/5 transition">
                                <span class="font-bold text-xs text-brand-navy block">Tenant Placement</span>
                                <span class="text-[11px] text-slate-500 block">Marketing, showings, and lease signing</span>
                            </a>
                            <a href="{{ url('/services/leasing-services') }}" class="block px-3 py-2 rounded-xl hover:bg-brand-cyan/5 transition">
                                <span class="font-bold text-xs text-brand-navy block">Full Leasing Management</span>
                                <span class="text-[11px] text-slate-500 block">End-to-end leasing for owners &amp; NRIs</span>
                            </a>
                            <a href="{{ url('/services/tenant-screening') }}" class="block px-3 py-2 rounded-xl hover:bg-brand-cyan/5 transition">
                                <span class="font-bold text-xs text-brand-navy block">Tenant Screening</span>
                                <span class="text-[11px] text-slate-500 block">75-point applicant verification</span>
                            </a>
                            <a href="{{ url('/services/rental-agreement-registration') }}" class="block px-3 py-2 rounded-xl hover:bg-brand-cyan/5 transition">
                                <span class="font-bold text-xs text-brand-navy block">Rent Agreement Registration</span>
                                <span class="text-[11px] text-slate-500 block">Legal e-stamping &amp; police verification</span>
                            </a>
                            <a href="{{ route('services') }}" class="block px-3 py-2 rounded-xl bg-slate-50 hover:bg-brand-cyan/10 transition mt-1 text-center">
                                <span class="font-bold text-xs uppercase tracking-wider text-brand-cyan block">View All 14 Services &rarr;</span>
                            </a>
                        </div>
                    </div>
                </div>

                <a href="{{ route('pricing') }}" class="px-3.5 py-2 rounded-xl text-brand-navy/80 hover:text-brand-navy hover:bg-slate-100 transition">
                    Pricing
                </a>

                <a href="{{ url('/india') }}" class="px-3.5 py-2 rounded-xl text-brand-navy/80 hover:text-brand-navy hover:bg-slate-100 transition">
                    Cities
                </a>
            </nav>

            <!-- Right Action Hub -->
            <div class="hidden xl:flex items-center space-x-3">
                <!-- Prime Badge Button (Single, Clean CTA) -->
                <a href="{{ route('prime') }}" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-gradient-to-r from-amber-400 via-amber-400 to-yellow-400 hover:from-amber-500 hover:to-yellow-500 text-slate-950 font-black text-xs uppercase tracking-wider shadow-sm transition hover:-translate-y-0.5 border border-amber-300">
                    <i class="fa-solid fa-crown text-amber-900 text-xs"></i>
                    <span>Prime</span>
                    <span class="bg-amber-950/15 text-slate-950 text-[9px] px-1.5 py-0.5 rounded-full font-bold">Zero Brokerage</span>
                </a>

                <!-- Post Property Free -->
                <a href="{{ route('properties.post') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-brand-navy text-white text-xs font-bold uppercase tracking-wider shadow-sm transition hover:-translate-y-0.5">
                    <i class="fa-solid fa-circle-plus text-amber-400 text-xs"></i>
                    <span>Post Property</span>
                    <span class="bg-amber-400 text-slate-950 text-[9px] px-1.5 py-0.5 rounded font-black">FREE</span>
                </a>

                <div class="h-5 w-px bg-slate-200"></div>

                @auth
                    <!-- Authenticated User Menu -->
                    <div class="relative group">
                        <a href="{{ route('my.prime') }}" class="inline-flex items-center gap-2 rounded-xl border {{ auth()->user()->isPrimeActive() ? 'border-amber-400 bg-amber-50/70 text-amber-950' : 'border-slate-200 bg-slate-50 text-slate-800' }} px-3 py-1.5 text-xs font-bold transition hover:bg-white shadow-sm">
                            <div class="size-6 rounded-full {{ auth()->user()->isPrimeActive() ? 'bg-amber-500 text-slate-950' : 'bg-brand-navy text-white' }} flex items-center justify-center text-[10px] font-bold">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="max-w-[85px] truncate">{{ auth()->user()->name }}</span>
                            @if(auth()->user()->isPrimeActive())
                                <i class="fa-solid fa-crown text-amber-600 text-xs"></i>
                            @endif
                            <i class="fa-solid fa-chevron-down text-[9px] text-slate-400 group-hover:rotate-180 transition-transform"></i>
                        </a>
                        <div class="absolute right-0 top-full pt-1.5 hidden group-hover:block z-50">
                            <div class="min-w-[210px] bg-white rounded-2xl shadow-xl border border-slate-100 p-2 space-y-1">
                                <div class="px-3 py-2 border-b border-slate-100">
                                    <span class="text-xs font-bold text-brand-navy block truncate">{{ auth()->user()->name }}</span>
                                    <span class="text-[11px] text-slate-500 block truncate">{{ auth()->user()->email }}</span>
                                    @if(auth()->user()->isPrimeActive())
                                        <span class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-amber-400 text-slate-950">
                                            <i class="fa-solid fa-crown text-[8px]"></i> {{ auth()->user()->prime_plan ?? 'Prime' }}
                                        </span>
                                    @endif
                                </div>
                                <a href="{{ route('my.prime') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-brand-navy transition">
                                    <i class="fa-solid fa-gauge-high text-brand-cyan text-xs"></i>
                                    <span>My Dashboard</span>
                                </a>
                                @if(!auth()->user()->isPrimeActive())
                                    <a href="{{ route('prime') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-bold text-amber-800 hover:bg-amber-50 transition">
                                        <i class="fa-solid fa-crown text-amber-500 text-xs"></i>
                                        <span>Upgrade to Prime</span>
                                    </a>
                                @endif
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
                <a href="{{ route('prime') }}" class="inline-flex items-center justify-center px-2.5 py-1.5 rounded-lg bg-gradient-to-r from-amber-500 to-yellow-500 text-slate-950 text-[11px] font-black uppercase tracking-wider shadow-sm gap-1">
                    <i class="fa-solid fa-crown text-[10px]"></i> Prime
                </a>
                <a href="{{ route('properties.post') }}" class="inline-flex items-center justify-center px-2.5 py-1.5 rounded-lg bg-slate-900 text-white text-[11px] font-bold uppercase tracking-wider shadow-sm">
                    + Post Free
                </a>
                <button id="mobile-toggle-btn" class="p-2 text-slate-700 hover:text-brand-cyan focus:outline-none">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Drawer -->
        <div id="mobile-drawer" class="hidden xl:hidden border-t border-slate-100 bg-white px-4 py-4 space-y-2 text-sm font-medium">
            @auth
                <div class="p-3 mb-2 rounded-2xl bg-amber-50/80 border border-amber-300 flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-1.5">
                            @if(auth()->user()->isPrimeActive())
                                <i class="fa-solid fa-crown text-amber-600 text-xs"></i>
                            @endif
                            <span class="text-xs font-bold text-brand-navy">{{ auth()->user()->name }}</span>
                        </div>
                        <span class="text-[11px] text-slate-500">{{ auth()->user()->isPrimeActive() ? (auth()->user()->prime_plan ?? 'Prime Member') : 'Standard Account' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('my.prime') }}" class="px-2.5 py-1 rounded-lg bg-brand-navy text-white text-[11px] font-bold">
                            Dashboard
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="p-1 text-rose-600 text-xs">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                            </button>
                        </form>
                    </div>
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

            <div class="p-1 mb-2 space-y-2">
                <a href="{{ route('prime') }}" class="flex items-center justify-center gap-2 w-full py-3 px-4 rounded-xl bg-gradient-to-r from-amber-500 via-amber-400 to-yellow-500 text-slate-950 font-black text-xs uppercase tracking-wider shadow-md transition">
                    <i class="fa-solid fa-crown"></i>
                    <span>Housing365 Prime (Zero Brokerage Membership)</span>
                </a>
                <a href="{{ route('properties.post') }}" class="flex items-center justify-center gap-2 w-full py-2.5 px-4 rounded-xl bg-slate-900 text-white font-bold text-xs uppercase tracking-wider shadow-sm transition">
                    <i class="fa-solid fa-circle-plus text-amber-400"></i>
                    <span>+ Post Free Property</span>
                </a>
            </div>
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-50">Home</a>
            <a href="{{ route('prime') }}" class="block px-3 py-2 rounded-lg text-amber-800 font-bold bg-amber-50/70">👑 Housing365 Prime Packages</a>
            <a href="{{ route('properties.index') }}" class="block px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-50 font-bold text-brand-navy">Properties, Plots &amp; Commercial</a>
            <a href="{{ route('owners') }}" class="block px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-50 font-bold text-brand-navy">Landlords &amp; Owners</a>
            <a href="{{ route('tenants') }}" class="block px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-50 font-bold text-brand-navy">Tenants &amp; Renters</a>
            <a href="{{ route('property-managers') }}" class="block px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-50 font-bold text-brand-navy">Property Managers</a>
            <a href="{{ route('portal-and-technology') }}" class="block px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-50">Portal &amp; Technology</a>
            <a href="{{ route('services') }}" class="block px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-50">Services (14 Sub-Services)</a>
            <a href="{{ route('locations') }}" class="block px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-50">Locations</a>
            <a href="{{ url('/india') }}" class="block px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-50 font-semibold text-brand-cyan">All India Cities &amp; States Directory</a>
            <a href="{{ route('pricing') }}" class="block px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-50">Pricing</a>
            <a href="{{ route('about') }}" class="block px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-50">About</a>
            <a href="{{ route('reviews') }}" class="block px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-50">Reviews</a>
            <a href="{{ route('blog.index') }}" class="block px-3 py-2 rounded-lg text-slate-700 hover:bg-slate-50">Blog</a>
            <a href="{{ route('contact') }}" class="block px-3 py-2.5 rounded-xl bg-brand-cyan text-white text-center font-bold mt-2">Talk to an Expert</a>
        </div>
    </header>

    <!-- Notification Toast -->
    @if(session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 text-emerald-800 text-sm max-w-7xl mx-auto my-4 rounded-r-lg shadow-sm flex items-center justify-between w-full">
            <div class="flex items-center">
                <i class="fa-solid fa-circle-check text-emerald-500 text-lg mr-3"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 font-bold">&times;</button>
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
        <div class="mx-auto w-full max-w-[1720px] px-4 pb-28 pt-12 sm:px-8 lg:px-12 xl:px-16 lg:pb-8 lg:pt-16">
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
                        <a href="{{ $settings['nextdoor_url'] ?? 'https://nextdoor.com/agency-detail/ca/ontario/city-of-ontario/' }}" target="_blank" rel="noopener noreferrer" aria-label="Nextdoor" title="Nextdoor" class="hover:text-brand-cyan transition inline-flex items-center">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 34 30" aria-hidden="true" role="img">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M4 10.2C4 8 4.2 6.5 4.8 5.7C5.4 4.9 6.6 4.6 8.5 4.6C10.2 4.6 11 5.3 11.4 7.2C12.8 5.4 15.6 4 19.8 4C26.5 4 30 7.8 30 14V26H24.5V14.6C24.5 10.8 22.8 8.6 19.2 8.6C15.2 8.6 13.5 11.2 13.5 15.4V26H8V13.8C8 12.2 7.4 10.8 5.8 11.2C4.7 11.5 4 11 4 10.2Z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- 6 Link Columns -->
                <div class="grid grid-cols-2 gap-8 sm:grid-cols-3 lg:col-span-8 lg:grid-cols-6">
                    <div>
                        <h3 class="mb-4 text-xs font-semibold uppercase tracking-widest text-brand-cyan">Core Services</h3>
                        <ul class="space-y-2.5 text-sm text-slate-400">
                            <li><a href="{{ url('/services/tenant-placement') }}" class="hover:text-white transition">Tenant Placement</a></li>
                            <li><a href="{{ url('/services/leasing-services') }}" class="hover:text-white transition">Leasing Services</a></li>
                            <li><a href="{{ url('/services/tenant-screening') }}" class="hover:text-white transition">Tenant Screening</a></li>
                            <li><a href="{{ url('/services/rental-agreement-registration') }}" class="hover:text-white transition">Rental Agreement &amp; Registration</a></li>
                            <li><a href="{{ url('/services/tenant-insurance') }}" class="hover:text-white transition">Tenant Insurance</a></li>
                            <li><a href="{{ url('/services/tenant-guarantor') }}" class="hover:text-white transition">Tenant Guarantor</a></li>
                            <li><a href="{{ url('/services/rental-preparation') }}" class="hover:text-white transition">Rental Preparation</a></li>
                            <li><a href="{{ url('/services/institutional-lease-up') }}" class="hover:text-white transition">Institutional Lease-Up</a></li>
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
                        <h3 class="mb-4 text-xs font-semibold uppercase tracking-widest text-brand-cyan">Owner Resources</h3>
                        <ul class="space-y-2.5 text-sm text-slate-400">
                            <li><a href="{{ route('properties.post') }}" class="text-amber-400 hover:text-amber-300 font-bold flex items-center gap-1.5"><i class="fa-solid fa-circle-plus text-xs"></i> Post Free Property</a></li>
                            <li><a href="{{ route('owners') }}" class="hover:text-white transition">Owner Hub</a></li>
                            <li><a href="{{ route('property-managers') }}" class="hover:text-white transition">Property Managers</a></li>
                            <li><a href="{{ route('portal-and-technology') }}" class="hover:text-white transition">Portal &amp; Technology</a></li>
                            <li><a href="{{ route('pricing') }}" class="hover:text-white transition">Pricing Guide</a></li>
                            <li><a href="{{ route('contact') }}" class="hover:text-white transition">Contact Us</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="mb-4 text-xs font-semibold uppercase tracking-widest text-brand-cyan">Tenant Resources</h3>
                        <ul class="space-y-2.5 text-sm text-slate-400">
                            <li><a href="{{ route('tenants') }}" class="hover:text-white transition">Tenant Hub</a></li>
                            <li><a href="{{ route('properties.index') }}" class="hover:text-white transition">Browse Rentals &amp; Sales</a></li>
                            <li><a href="{{ url('/services/tenant-insurance') }}" class="hover:text-white transition">Tenant Insurance</a></li>
                            <li><a href="{{ url('/services/tenant-guarantor') }}" class="hover:text-white transition">Tenant Guarantor</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="mb-4 text-xs font-semibold uppercase tracking-widest text-brand-cyan">Company</h3>
                        <ul class="space-y-2.5 text-sm text-slate-400">
                            <li><a href="{{ route('about') }}" class="hover:text-white transition">About Us</a></li>
                            <li><a href="{{ route('blog.index') }}" class="hover:text-white transition">Blog & News</a></li>
                            <li><a href="{{ route('reviews') }}" class="hover:text-white transition">Reviews</a></li>
                            <li><a href="{{ route('contact') }}" class="hover:text-white transition">Contact</a></li>
                        </ul>
                    </div>
                </div>

            </div>

            <!-- Bottom Bar -->
            <div class="mt-12 flex flex-col items-center justify-between gap-4 border-t border-white/10 pt-8 sm:flex-row text-sm text-slate-500">
                <p>{{ $settings['footer_copyright'] ?? '© 2026 Housing365 Inc. All rights reserved.' }}</p>
                <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 sm:justify-end">
                    <a href="{{ route('contact') }}" class="hover:text-slate-300">Privacy Policy</a>
                    <a href="{{ route('contact') }}" class="hover:text-slate-300">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Sticky Bottom CTA Bar -->
    <div class="fixed bottom-0 left-0 right-0 z-50 lg:hidden p-2.5 bg-white/95 backdrop-blur-lg border-t border-slate-200 shadow-xl">
        <div class="flex items-center gap-2">
            <a href="tel:{{ $settings['contact_phone'] ?? '+916376163263' }}" class="flex items-center justify-center w-11 h-11 rounded-xl bg-slate-50 text-brand-cyan border border-slate-200 shrink-0" aria-label="Call Housing365">
                <i class="fa-solid fa-phone text-sm"></i>
            </a>
            <a href="{{ route('properties.post') }}" class="flex-1 flex items-center justify-center gap-1.5 h-11 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs uppercase tracking-wider shadow-sm transition">
                <i class="fa-solid fa-plus-circle text-xs"></i>
                <span>Post Free</span>
            </a>
            <a href="{{ route('properties.index') }}" class="flex-1 flex items-center justify-center h-11 rounded-xl bg-brand-navy hover:bg-slate-800 text-white font-bold text-xs uppercase tracking-wider transition">
                Browse
            </a>
        </div>
    </div>

    <!-- Mobile Drawer JS -->
    <script>
        document.getElementById('mobile-toggle-btn').addEventListener('click', function() {
            var d = document.getElementById('mobile-drawer');
            d.classList.toggle('hidden');
        });
    </script>
    @stack('scripts')
</body>
</html>
