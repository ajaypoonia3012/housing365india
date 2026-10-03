<?php

namespace App\Filament\Resources\SeoMetaOverrides\Pages;

use App\Filament\Resources\SeoMetaOverrides\SeoMetaOverrideResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSeoMetaOverrides extends ListRecords
{
    protected static string $resource = SeoMetaOverrideResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
