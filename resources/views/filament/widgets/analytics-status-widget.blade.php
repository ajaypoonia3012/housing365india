<x-filament-widgets::widget>
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-gray-100 pb-5 dark:border-gray-800">
            <div>
                <div class="flex items-center gap-2">
                    <span class="flex h-2.5 w-2.5 rounded-full {{ ($gaActive && $gscActive) ? 'bg-emerald-500 animate-pulse' : 'bg-amber-500' }}"></span>
                    <h3 class="text-base font-bold text-gray-950 dark:text-white">
                        Google Analytics & Search Console Integration
                    </h3>
                </div>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Live verification and measurement tags injected into Housing365 public layouts.
                </p>
            </div>

            <div>
                <a href="{{ url('/admin/analytics-settings') }}" 
                   style="background: #00AEEF; color: #FFFFFF; font-size: 0.75rem; font-weight: 700; padding: 0.5rem 1rem; border-radius: 0.5rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 2px 8px rgba(0, 174, 239, 0.25);">
                    <i class="fa-solid fa-sliders" style="font-size: 13px;"></i>
                    <span>Configure Analytics &amp; SEO Keys</span>
                </a>
            </div>
        </div>

        <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Google Analytics 4 -->
            <div class="flex items-start justify-between rounded-xl border {{ $gaActive ? 'border-emerald-200 bg-emerald-50/30' : 'border-amber-200 bg-amber-50/30' }} p-4">
                <div class="flex items-start gap-3.5">
                    <div style="width: 40px; height: 40px; min-width: 40px; min-height: 40px; max-width: 40px; max-height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; background: {{ $gaActive ? 'rgba(16, 185, 129, 0.15)' : 'rgba(245, 158, 11, 0.15)' }}; color: {{ $gaActive ? '#059669' : '#D97706' }};">
                        <i class="fa-solid fa-chart-simple" style="font-size: 18px;"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h4 class="text-sm font-bold text-gray-900" style="color: #03234B;">Google Analytics 4 (GA4)</h4>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $gaActive ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ $gaActive ? 'Connected' : 'Pending Setup' }}
                            </span>
                        </div>
                        <p class="mt-1 text-xs text-gray-600">
                            @if($gaActive)
                                Tracking ID: <code style="font-family: monospace; font-size: 11px; font-weight: 700; color: #059669;">{{ $gaId }}</code>
                            @else
                                Add your Google Analytics Measurement ID (e.g. <code style="font-family: monospace; font-size: 10px;">G-XXXXXXXXXX</code>) to activate visitor analytics.
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Google Search Console -->
            <div class="flex items-start justify-between rounded-xl border {{ $gscActive ? 'border-emerald-200 bg-emerald-50/30' : 'border-amber-200 bg-amber-50/30' }} p-4">
                <div class="flex items-start gap-3.5">
                    <div style="width: 40px; height: 40px; min-width: 40px; min-height: 40px; max-width: 40px; max-height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; background: {{ $gscActive ? 'rgba(16, 185, 129, 0.15)' : 'rgba(245, 158, 11, 0.15)' }}; color: {{ $gscActive ? '#059669' : '#D97706' }};">
                        <i class="fa-solid fa-magnifying-glass-location" style="font-size: 18px;"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h4 class="text-sm font-bold text-gray-900" style="color: #03234B;">Google Search Console</h4>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $gscActive ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ $gscActive ? 'Tag Installed' : 'Pending Verification' }}
                            </span>
                        </div>
                        <p class="mt-1 text-xs text-gray-600 dark:text-gray-400">
                            @if($gscActive)
                                Site verification meta tag is active in public layout head.
                            @else
                                Paste your Google site verification token or meta tag to index and rank Indian states, metro cities, and verified property landing pages across Google Search.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
