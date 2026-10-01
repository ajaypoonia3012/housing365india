<?php

namespace App\Filament\Widgets;

use App\Models\Setting;
use Filament\Widgets\Widget;

class AnalyticsStatusWidget extends Widget
{
    protected string $view = 'filament.widgets.analytics-status-widget';

    protected static ?int $sort = 6;

    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $gaId = Setting::get('google_analytics_id');
        $gscCode = Setting::get('google_search_console_code');

        return [
            'gaId' => $gaId,
            'gaActive' => !empty($gaId),
            'gscCode' => $gscCode,
            'gscActive' => !empty($gscCode),
        ];
    }
}
