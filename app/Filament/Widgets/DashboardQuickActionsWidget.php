<?php

namespace App\Filament\Widgets;

use App\Models\Inquiry;
use App\Models\Property;
use App\Models\User;
use Filament\Widgets\Widget;

class DashboardQuickActionsWidget extends Widget
{
    protected string $view = 'filament.widgets.dashboard-quick-actions-widget';

    protected static ?int $sort = 0;

    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $newLeadsCount = Inquiry::where('status', 'new')->count();
        $unverifiedProperties = Property::where('is_verified', false)->count();
        $primeUsersCount = User::where('is_prime', true)->count();
        $totalProperties = Property::count();

        return [
            'newLeadsCount' => $newLeadsCount,
            'unverifiedProperties' => $unverifiedProperties,
            'primeUsersCount' => $primeUsersCount,
            'totalProperties' => $totalProperties,
            'adminUser' => auth()->user(),
        ];
    }
}
