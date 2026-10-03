<?php

namespace App\Filament\Resources\RiskFlags\Pages;

use App\Filament\Resources\RiskFlags\RiskFlagResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRiskFlag extends EditRecord
{
    protected static string $resource = RiskFlagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
