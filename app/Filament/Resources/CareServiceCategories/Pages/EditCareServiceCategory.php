<?php

namespace App\Filament\Resources\CareServiceCategories\Pages;

use App\Filament\Resources\CareServiceCategories\CareServiceCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCareServiceCategory extends EditRecord
{
    protected static string $resource = CareServiceCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
