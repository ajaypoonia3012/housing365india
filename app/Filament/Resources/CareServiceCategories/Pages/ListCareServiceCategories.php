<?php

namespace App\Filament\Resources\CareServiceCategories\Pages;

use App\Filament\Resources\CareServiceCategories\CareServiceCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCareServiceCategories extends ListRecords
{
    protected static string $resource = CareServiceCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
