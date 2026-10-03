<?php

namespace App\Filament\Resources\ManagementEnrollments\Pages;

use App\Filament\Resources\ManagementEnrollments\ManagementEnrollmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListManagementEnrollments extends ListRecords
{
    protected static string $resource = ManagementEnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
