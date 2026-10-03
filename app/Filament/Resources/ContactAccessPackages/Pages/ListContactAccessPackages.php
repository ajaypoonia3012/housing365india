<?php

namespace App\Filament\Resources\ContactAccessPackages\Pages;

use App\Filament\Resources\ContactAccessPackages\ContactAccessPackageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListContactAccessPackages extends ListRecords
{
    protected static string $resource = ContactAccessPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
