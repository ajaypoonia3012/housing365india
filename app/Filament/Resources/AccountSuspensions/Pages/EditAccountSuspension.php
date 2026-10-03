<?php

namespace App\Filament\Resources\AccountSuspensions\Pages;

use App\Filament\Resources\AccountSuspensions\AccountSuspensionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAccountSuspension extends EditRecord
{
    protected static string $resource = AccountSuspensionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
