<!-- Fonts: Plus Jakarta Sans, Inter -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<!-- Font Awesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
    /* ── HOUSING365 WEBSITE DESIGN SYSTEM FOR FILAMENT ADMIN ── */
    :root {
        --brand-navy: #03234B;
        --brand-navy-light: #0D3261;
        --brand-navy-dark: #021630;
        --brand-cyan: #00AEEF;
        --brand-cyan-hover: #0096C7;
        --brand-accent: #00AEEF;
        --font-sans: 'Inter', sans-serif;
        --font-heading: 'Plus Jakarta Sans', sans-serif;
    }

    body, .fi-body {
        font-family: var(--font-sans) !important;
        background-color: #F8FAFC !important;
        color: #1E293B;
    }

    /* Headings */
    h1, h2, h3, h4, .fi-header-heading, .fi-ta-header-heading {
        font-family: var(--font-heading) !important;
        letter-spacing: -0.02em;
    }

    /* ── SIDEBAR STYLING (NAVY LUXURY THEME) ── */
    aside.fi-sidebar,
    .fi-sidebar {
        background: linear-gradient(180deg, #03234B 0%, #021630 100%) !important;
        border-right: 1px solid rgba(255, 255, 255, 0.08) !important;
        box-shadow: 4px 0 24px rgba(3, 35, 75, 0.12) !important;
    }

    /* Sidebar Header & Brand */
    .fi-sidebar-header {
        border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
        background-color: rgba(2, 22, 48, 0.6) !important;
        padding-top: 1.25rem !important;
        padding-bottom: 1.25rem !important;
    }

    .fi-logo,
    .fi-sidebar-header a,
    .fi-sidebar-header span {
        color: #FFFFFF !important;
        font-family: var(--font-heading) !important;
        font-weight: 800 !important;
        font-size: 1.2rem !important;
        letter-spacing: -0.03em !important;
    }

    /* ── SIDEBAR NAVIGATION ITEMS (PURE WHITE TEXT & CRISP CONTRAST) ── */
    .fi-sidebar,
    aside.fi-sidebar {
        color: #FFFFFF !important;
    }

    .fi-sidebar-nav,
    .fi-sidebar-nav * {
        -webkit-font-smoothing: antialiased;
    }

    .fi-sidebar-item-btn,
    .fi-sidebar-item a,
    .fi-sidebar-item button,
    .fi-sidebar-item-label,
    .fi-sidebar-item span,
    .fi-sidebar-item-grouped-btn {
        color: #FFFFFF !important;
        font-size: 0.85rem !important;
        font-weight: 500 !important;
        border-radius: 0.625rem !important;
        transition: all 0.15s ease-in-out !important;
    }

    /* Sidebar Item Icons */
    .fi-sidebar-item svg,
    .fi-sidebar-item .fi-icon,
    .fi-sidebar-item-icon {
        color: #FFFFFF !important;
    }

    /* Hover effect */
    .fi-sidebar-item-btn:hover,
    .fi-sidebar-item a:hover,
    .fi-sidebar-item button:hover {
        background-color: rgba(255, 255, 255, 0.12) !important;
        color: #FFFFFF !important;
    }

    .fi-sidebar-item-btn:hover svg,
    .fi-sidebar-item a:hover svg,
    .fi-sidebar-item button:hover svg {
        color: #00AEEF !important;
    }

    /* Active Navigation Item (Glowing Cyan Accent Pill with White Text) */
    .fi-sidebar-item-active,
    .fi-sidebar-item-active .fi-sidebar-item-btn,
    .fi-sidebar-item-active a,
    .fi-sidebar-item-active button,
    .fi-sidebar [aria-current="page"],
    .fi-sidebar [aria-current="page"] > * {
        background: linear-gradient(90deg, #00AEEF 0%, #0096C7 100%) !important;
        color: #FFFFFF !important;
        font-weight: 700 !important;
        box-shadow: 0 4px 14px rgba(0, 174, 239, 0.4) !important;
        border-radius: 0.5rem !important;
    }

    .fi-sidebar-item-active *,
    .fi-sidebar [aria-current="page"] * {
        color: #FFFFFF !important;
    }

    .fi-sidebar-item-active svg,
    .fi-sidebar-item-active .fi-icon,
    .fi-sidebar [aria-current="page"] svg {
        color: #FFFFFF !important;
        stroke: #FFFFFF !important;
    }

    /* Sidebar Group Labels (CONTENT & CMS, CORE OPERATIONS, SETTINGS & SYSTEM) */
    .fi-sidebar-group-label,
    .fi-sidebar-group-label span,
    .fi-sidebar-group-btn,
    .fi-sidebar-group-btn span,
    .fi-sidebar-group-title,
    .fi-sidebar-group-header span {
        color: #FFFFFF !important;
        font-size: 0.72rem !important;
        font-weight: 800 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.08em !important;
        opacity: 0.95 !important;
    }

    /* Group collapse / expand chevron arrows */
    .fi-sidebar-group-collapse-btn,
    .fi-sidebar-group-collapse-btn svg,
    .fi-sidebar-group-btn svg,
    .fi-sidebar-group-header svg {
        color: #FFFFFF !important;
    }

    /* Force all text inside sidebar to pure white regardless of Tailwind utility classes */
    .fi-sidebar [class*="text-gray-"],
    .fi-sidebar [class*="text-slate-"],
    .fi-sidebar [class*="text-zinc-"] {
        color: #FFFFFF !important;
    }

    /* ── TOPBAR STYLING ── */
    header.fi-topbar,
    .fi-topbar {
        background-color: #FFFFFF !important;
        border-bottom: 1px solid #E2E8F0 !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03) !important;
    }

    /* ── BUTTONS (BRAND CYAN) ── */
    .fi-btn-primary,
    button[type="submit"]:not([class*="fi-btn-secondary"]):not([class*="fi-btn-danger"]),
    .fi-btn.fi-color-primary {
        background: #00AEEF !important;
        border-color: #00AEEF !important;
        color: #FFFFFF !important;
        font-weight: 700 !important;
        border-radius: 0.625rem !important;
        box-shadow: 0 4px 12px rgba(0, 174, 239, 0.25) !important;
        transition: all 0.2s ease !important;
    }

    .fi-btn-primary:hover,
    button[type="submit"]:hover,
    .fi-btn.fi-color-primary:hover {
        background: #0096C7 !important;
        border-color: #0096C7 !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 6px 16px rgba(0, 174, 239, 0.35) !important;
    }

    /* ── CARDS & WIDGETS ── */
    .fi-section,
    .fi-wi-stats-overview-stat,
    .fi-ta-ctn,
    .fi-card {
        border: 1px solid #E2E8F0 !important;
        border-radius: 1rem !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04), 0 6px 16px rgba(0, 0, 0, 0.02) !important;
        background-color: #FFFFFF !important;
    }

    .fi-wi-stats-overview-stat-val {
        font-family: var(--font-heading) !important;
        font-weight: 800 !important;
        color: #03234B !important;
    }

    /* ── CRITICAL: PREVENT GIANT SVGS ── */
    svg {
        max-width: 100% !important;
        display: inline-block !important;
    }

    .fi-icon,
    .fi-sidebar-item svg,
    .fi-topbar svg,
    .fi-btn svg {
        max-width: 1.25rem !important;
        max-height: 1.25rem !important;
        width: 1.25rem !important;
        height: 1.25rem !important;
        flex-shrink: 0 !important;
    }

    /* Global link styling */
    a.text-primary-600,
    a.text-brand-cyan,
    .fi-link {
        color: #00AEEF !important;
    }

    a.text-primary-600:hover,
    a.text-brand-cyan:hover,
    .fi-link:hover {
        color: #0096C7 !important;
    }
</style>
