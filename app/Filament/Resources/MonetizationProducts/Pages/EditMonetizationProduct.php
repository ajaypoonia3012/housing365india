<?php

namespace App\Filament\Resources\MonetizationProducts\Pages;

use App\Filament\Resources\MonetizationProducts\MonetizationProductResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMonetizationProduct extends EditRecord
{
    protected static string $resource = MonetizationProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
