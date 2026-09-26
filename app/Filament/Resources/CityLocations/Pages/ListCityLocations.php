<?php

namespace App\Filament\Resources\CityLocations\Pages;

use App\Filament\Resources\CityLocations\CityLocationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCityLocations extends ListRecords
{
    protected static string $resource = CityLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
