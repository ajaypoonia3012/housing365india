<?php

namespace App\Filament\Widgets;

use App\Models\CityLocation;
use App\Models\Inquiry;
use App\Models\Property;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalProperties = Property::count();
        $rentCount = Property::where('purpose', 'rent')->count();
        $saleCount = Property::where('purpose', 'sale')->count();
        $verifiedCount = Property::where('is_verified', true)->count();

        $totalInquiries = Inquiry::count();
        $newInquiries = Inquiry::where('status', 'new')->count();

        $totalUsers = User::count();

        $totalCities = CityLocation::count();

        return [
            Stat::make('Marketplace Properties', (string) $totalProperties)
                ->description("{$rentCount} Rent • {$saleCount} Sale • {$verifiedCount} Verified")
                ->descriptionIcon('heroicon-m-building-office-2')
                ->chart([8, 12, 11, 15, 14, 18, max($totalProperties, 20)])
                ->color('success'),

            Stat::make('Inquiries & Leads', (string) $totalInquiries)
                ->description($newInquiries > 0 ? "{$newInquiries} new awaiting response" : 'All customer leads addressed')
                ->descriptionIcon($newInquiries > 0 ? 'heroicon-m-bell-alert' : 'heroicon-m-check-circle')
                ->chart([4, 7, 5, 10, 8, 13, max($totalInquiries, 16)])
                ->color($newInquiries > 0 ? 'warning' : 'primary'),

            Stat::make('Registered Users', (string) $totalUsers)
                ->description('Platform Owners, Tenants & Partners')
                ->descriptionIcon('heroicon-m-user-group')
                ->chart([2, 5, 4, 8, 7, 11, max($totalUsers, 12)])
                ->color('warning'),

            Stat::make('Pan-India Coverage', (string) $totalCities.' Metros')
                ->description('All 28 States & UTs Catalogued')
                ->descriptionIcon('heroicon-m-map-pin')
                ->color('info'),
        ];
    }
}
