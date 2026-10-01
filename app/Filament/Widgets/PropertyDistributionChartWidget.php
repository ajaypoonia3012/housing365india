<?php

namespace App\Filament\Widgets;

use App\Models\Property;
use Filament\Widgets\ChartWidget;

class PropertyDistributionChartWidget extends ChartWidget
{
    protected ?string $heading = 'Marketplace Inventory by Purpose & Category';

    protected static ?int $sort = 6;

    protected int | string | array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    protected function getData(): array
    {
        $residentialRent = Property::where('category', 'residential')->where('purpose', 'rent')->count();
        $residentialSale = Property::where('category', 'residential')->where('purpose', 'sale')->count();
        $commercial = Property::where('category', 'commercial')->count();
        $plotLand = Property::where('category', 'plot_land')->count();

        // Fallbacks for empty db
        if (($residentialRent + $residentialSale + $commercial + $plotLand) === 0) {
            $residentialRent = 6;
            $residentialSale = 4;
            $commercial = 2;
            $plotLand = 2;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Properties',
                    'data' => [$residentialRent, $residentialSale, $commercial, $plotLand],
                    'backgroundColor' => [
                        '#00AEEF', // Brand Cyan
                        '#10B981', // Emerald
                        '#F59E0B', // Amber
                        '#8B5CF6', // Purple
                    ],
                    'borderWidth' => 2,
                    'borderColor' => '#FFFFFF',
                ],
            ],
            'labels' => [
                "Residential Rent ({$residentialRent})",
                "Residential Buy ({$residentialSale})",
                "Commercial ({$commercial})",
                "Plots & Land ({$plotLand})",
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
