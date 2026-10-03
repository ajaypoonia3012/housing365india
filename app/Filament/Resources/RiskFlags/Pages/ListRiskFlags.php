<?php

namespace App\Filament\Resources\RiskFlags\Pages;

use App\Filament\Resources\RiskFlags\RiskFlagResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRiskFlags extends ListRecords
{
    protected static string $resource = RiskFlagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
