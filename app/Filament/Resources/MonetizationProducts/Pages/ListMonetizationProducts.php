<?php

namespace App\Filament\Resources\MonetizationProducts\Pages;

use App\Filament\Resources\MonetizationProducts\MonetizationProductResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMonetizationProducts extends ListRecords
{
    protected static string $resource = MonetizationProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
