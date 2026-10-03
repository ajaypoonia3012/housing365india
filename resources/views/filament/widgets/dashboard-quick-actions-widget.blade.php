<x-filament-widgets::widget>
    <style>
        .h365-cmd-card {
            background: linear-gradient(135deg, #03234B 0%, #073B75 55%, #021630 100%);
            border: 1px solid rgba(0, 174, 239, 0.35);
            border-radius: 1rem;
            padding: 1.5rem 1.75rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(2, 22, 48, 0.25);
            color: #FFFFFF !important;
        }
        .h365-cmd-card * {
            box-sizing: border-box;
        }
        .h365-cmd-top {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding-bottom: 1.25rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        }
        .h365-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
            gap: 0.75rem;
            margin-top: 1rem;
        }
        .h365-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 0.85rem 0.65rem;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 0.75rem;
            text-decoration: none;
            transition: all 0.2s ease-in-out;
            cursor: pointer;
        }
        .h365-btn:hover {
            background: rgba(0, 174, 239, 0.18);
            border-color: #00AEEF;
            transform: translateY(-2px);
        }
        .h365-btn-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }
    </style>

    <div class="h365-cmd-card">
        <!-- Top Row: Greeting & Notification Alerts -->
        <div class="h365-cmd-top">
            <div>
                <div style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; background: rgba(0, 174, 239, 0.2); color: #38BDF8; border: 1px solid rgba(0, 174, 239, 0.4); margin-bottom: 0.5rem;">
                    <i class="fa-solid fa-shield-halved" style="color: #38BDF8;"></i>
                    <span>Housing 365 India &bull; Executive Command Center</span>
                </div>
                <h2 style="font-size: 1.5rem; font-weight: 800; color: #FFFFFF; margin: 0; font-family: 'Plus Jakarta Sans', sans-serif;">
                    Welcome back, {{ $adminUser?->name ?? 'Administrator' }}
                </h2>
                <p style="font-size: 0.85rem; color: #CBD5E1; margin: 0.25rem 0 0 0; max-width: 650px; line-height: 1.4;">
                    Full dynamic control over marketplace listings, verified inventory, inbound leads, user management, CMS landing pages, and pan-India SEO coverage.
                </p>
            </div>

            <!-- Quick Notification Alerts -->
            <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                @if($newLeadsCount > 0)
                    <a href="{{ url('/admin/inquiries') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border-radius: 0.75rem; font-size: 0.75rem; font-weight: 700; color: #FFFFFF; background: #E11D48; text-decoration: none; box-shadow: 0 4px 12px rgba(225, 29, 72, 0.4);">
                        <i class="fa-solid fa-bell"></i>
                        <span>{{ $newLeadsCount }} New Inquiries Awaiting Callback</span>
                        <i class="fa-solid fa-arrow-right" style="font-size: 10px;"></i>
                    </a>
                @else
                    <div style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border-radius: 0.75rem; font-size: 0.75rem; font-weight: 600; color: #6EE7B7; background: rgba(6, 78, 59, 0.7); border: 1px solid rgba(16, 185, 129, 0.3);">
                        <i class="fa-solid fa-circle-check text-emerald-400"></i>
                        <span>All customer leads reviewed</span>
                    </div>
                @endif

                @if($unverifiedProperties > 0)
                    <a href="{{ url('/admin/properties') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 0.85rem; border-radius: 0.75rem; font-size: 0.75rem; font-weight: 700; color: #FDE68A; background: rgba(120, 53, 15, 0.7); border: 1px solid rgba(245, 158, 11, 0.3); text-decoration: none;">
                        <i class="fa-solid fa-triangle-exclamation" style="color: #FBBF24;"></i>
                        <span>{{ $unverifiedProperties }} Pending Verification</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- Rapid Actions Grid -->
        <div style="margin-top: 1.25rem;">
            <div style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #94A3B8; margin-bottom: 0.5rem;">
                Instant Admin Operations
            </div>

            <div class="h365-grid">
                <!-- 1. Add Property -->
                <a href="{{ url('/admin/properties/create') }}" class="h365-btn">
                    <div class="h365-btn-icon" style="background: rgba(0, 174, 239, 0.2); color: #38BDF8;">
                        <i class="fa-solid fa-plus-circle"></i>
                    </div>
                    <span style="font-size: 0.78rem; font-weight: 700; color: #FFFFFF;">Add Property</span>
                    <span style="font-size: 0.65rem; color: #94A3B8; margin-top: 0.15rem;">Rent / Sale / Land</span>
                </a>

                <!-- 2. Users Directory -->
                <a href="{{ url('/admin/users') }}" class="h365-btn">
                    <div class="h365-btn-icon" style="background: rgba(245, 158, 11, 0.2); color: #FBBF24;">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <span style="font-size: 0.78rem; font-weight: 700; color: #FFFFFF;">Users &amp; Clients</span>
                    <span style="font-size: 0.65rem; color: #94A3B8; margin-top: 0.15rem;">{{ $totalUsersCount }} Accounts</span>
                </a>

                <!-- 3. Inquiries -->
                <a href="{{ url('/admin/inquiries') }}" class="h365-btn">
                    <div class="h365-btn-icon" style="background: rgba(16, 185, 129, 0.2); color: #34D399;">
                        <i class="fa-solid fa-comments"></i>
                    </div>
                    <span style="font-size: 0.78rem; font-weight: 700; color: #FFFFFF;">Inbound Leads</span>
                    <span style="font-size: 0.65rem; color: #94A3B8; margin-top: 0.15rem;">Contact &amp; Assistance</span>
                </a>

                <!-- 4. Add City -->
                <a href="{{ url('/admin/city-locations/create') }}" class="h365-btn">
                    <div class="h365-btn-icon" style="background: rgba(56, 189, 248, 0.2); color: #38BDF8;">
                        <i class="fa-solid fa-city"></i>
                    </div>
                    <span style="font-size: 0.78rem; font-weight: 700; color: #FFFFFF;">Add City</span>
                    <span style="font-size: 0.65rem; color: #94A3B8; margin-top: 0.15rem;">Top Metro Hubs</span>
                </a>

                <!-- 5. Analytics & SEO -->
                <a href="{{ url('/admin/analytics-settings') }}" class="h365-btn">
                    <div class="h365-btn-icon" style="background: rgba(168, 85, 247, 0.2); color: #C084FC;">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <span style="font-size: 0.78rem; font-weight: 700; color: #FFFFFF;">SEO &amp; GA4</span>
                    <span style="font-size: 0.65rem; color: #94A3B8; margin-top: 0.15rem;">Google Tags &amp; GSC</span>
                </a>

                <!-- 6. View Live Site -->
                <a href="{{ url('/') }}" target="_blank" class="h365-btn">
                    <div class="h365-btn-icon" style="background: rgba(99, 102, 241, 0.2); color: #818CF8;">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    </div>
                    <span style="font-size: 0.78rem; font-weight: 700; color: #FFFFFF;">Live Website</span>
                    <span style="font-size: 0.65rem; color: #94A3B8; margin-top: 0.15rem;">housing365.in</span>
                </a>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
