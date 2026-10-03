<?php

namespace App\Filament\Resources\Appeals\Pages;

use App\Filament\Resources\Appeals\AppealResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAppeal extends EditRecord
{
    protected static string $resource = AppealResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
