<?php

namespace App\Filament\Pages;

use BackedEnum;
use App\Models\Setting;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class AnalyticsSettings extends Page
{
    protected string $view = 'filament.pages.analytics-settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?string $navigationLabel = 'Analytics & Search Console';

    protected static ?string $title = 'Google Analytics & Search Console Settings';

    protected static ?int $navigationSort = 99;

    public ?string $google_analytics_id = '';
    public ?string $google_search_console_code = '';
    public ?string $google_analytics_script = '';

    public function mount(): void
    {
        $this->google_analytics_id = Setting::get('google_analytics_id', '');
        $this->google_search_console_code = Setting::get('google_search_console_code', '');
        $this->google_analytics_script = Setting::get('google_analytics_script', '');
    }

    public function save(): void
    {
        Setting::set('google_analytics_id', trim((string)$this->google_analytics_id), 'analytics', 'text', 'Google Analytics 4 Measurement ID');
        Setting::set('google_search_console_code', trim((string)$this->google_search_console_code), 'seo', 'text', 'Google Search Console Verification Code');
        Setting::set('google_analytics_script', trim((string)$this->google_analytics_script), 'analytics', 'textarea', 'Custom Analytics / Header Tracking Script');

        Notification::make()
            ->title('Settings Saved Successfully')
            ->body('Google Analytics and Google Search Console tags are now active across all public Housing365 pages.')
            ->success()
            ->send();
    }
}
