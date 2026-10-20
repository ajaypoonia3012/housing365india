<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class AnalyticsSettings extends Page
{
    protected string $view = 'filament.pages.analytics-settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?string $navigationLabel = 'Analytics & Search Console';

    protected static \UnitEnum|string|null $navigationGroup = null;

    protected static ?string $title = 'Google Analytics, Search Console & WhatsApp Settings';

    protected static ?int $navigationSort = 15;

    public ?string $google_analytics_id = '';

    public ?string $google_search_console_code = '';

    public ?string $google_analytics_script = '';

    public ?string $whatsapp_number = '';

    public ?string $whatsapp_default_message = '';

    public function mount(): void
    {
        $this->google_analytics_id = Setting::get('google_analytics_id', '');
        $this->google_search_console_code = Setting::get('google_search_console_code', '');
        $this->google_analytics_script = Setting::get('google_analytics_script', '');
        $this->whatsapp_number = Setting::get('whatsapp_number', Setting::get('contact_phone', '+91 63761 63263'));
        $this->whatsapp_default_message = Setting::get('whatsapp_default_message', 'Hi Housing365 India, I am interested in your property services.');
    }

    public function save(): void
    {
        Setting::set('google_analytics_id', trim((string) $this->google_analytics_id), 'analytics', 'text', 'Google Analytics 4 Measurement ID');
        Setting::set('google_search_console_code', trim((string) $this->google_search_console_code), 'seo', 'text', 'Google Search Console Verification Code');
        Setting::set('google_analytics_script', trim((string) $this->google_analytics_script), 'analytics', 'textarea', 'Custom Analytics / Header Tracking Script');
        Setting::set('whatsapp_number', trim((string) $this->whatsapp_number), 'contact', 'text', 'Official Support WhatsApp Number');
        Setting::set('whatsapp_default_message', trim((string) $this->whatsapp_default_message), 'contact', 'text', 'Default WhatsApp Concierge Message');

        Notification::make()
            ->title('Settings Saved Successfully')
            ->body('Google Analytics, Search Console, and WhatsApp Concierge configurations are now live across all Housing365 pages.')
            ->success()
            ->send();
    }
}
