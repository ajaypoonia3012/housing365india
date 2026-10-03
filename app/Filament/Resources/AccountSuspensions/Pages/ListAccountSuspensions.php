<?php

namespace App\Filament\Resources\AccountSuspensions\Pages;

use App\Filament\Resources\AccountSuspensions\AccountSuspensionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAccountSuspensions extends ListRecords
{
    protected static string $resource = AccountSuspensionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
