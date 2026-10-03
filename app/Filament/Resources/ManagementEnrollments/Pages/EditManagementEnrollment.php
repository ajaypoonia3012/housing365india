<?php

namespace App\Filament\Resources\ManagementEnrollments\Pages;

use App\Filament\Resources\ManagementEnrollments\ManagementEnrollmentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditManagementEnrollment extends EditRecord
{
    protected static string $resource = ManagementEnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
