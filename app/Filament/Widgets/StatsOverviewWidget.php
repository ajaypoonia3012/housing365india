<?php

namespace App\Filament\Widgets;

use App\Models\CityLocation;
use App\Models\Inquiry;
use App\Models\Property;
use App\Models\Service;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalInquiries = Inquiry::count();
        $newInquiries = Inquiry::where('status', 'new')->count();

        $totalProperties = Property::count();
        $featuredProperties = Property::where('featured', true)->count();

        $activeServices = Service::where('is_active', true)->count();
        $totalCities = CityLocation::count();

        return [
            Stat::make('Total Inquiries & Leads', (string)$totalInquiries)
                ->description($newInquiries > 0 ? "{$newInquiries} new awaiting response" : 'All inquiries reviewed')
                ->descriptionIcon($newInquiries > 0 ? 'heroicon-m-bell-alert' : 'heroicon-m-check-circle')
                ->chart([3, 6, 4, 9, 7, 12, max($totalInquiries, 14)])
                ->color($newInquiries > 0 ? 'warning' : 'primary'),

            Stat::make('Rental Properties', (string)$totalProperties)
                ->description("{$featuredProperties} featured active units")
                ->descriptionIcon('heroicon-m-home')
                ->chart([5, 8, 7, 11, 10, 14, max($totalProperties, 16)])
                ->color('success'),

            Stat::make('Leasing Services', (string)$activeServices)
                ->description('14 Landing Pages & 8 Primary')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('info'),

            Stat::make('Covered Cities & Metros', (string)$totalCities)
                ->description('Canada & 57 Programmatic US Cities')
                ->descriptionIcon('heroicon-m-map-pin')
                ->color('primary'),
        ];
    }
}
