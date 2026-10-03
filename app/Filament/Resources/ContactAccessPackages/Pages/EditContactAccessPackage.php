<?php

namespace App\Filament\Resources\ContactAccessPackages\Pages;

use App\Filament\Resources\ContactAccessPackages\ContactAccessPackageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditContactAccessPackage extends EditRecord
{
    protected static string $resource = ContactAccessPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
