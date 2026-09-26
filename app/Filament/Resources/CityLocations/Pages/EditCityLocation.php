<?php

namespace App\Filament\Resources\CityLocations\Pages;

use App\Filament\Resources\CityLocations\CityLocationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCityLocation extends EditRecord
{
    protected static string $resource = CityLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
