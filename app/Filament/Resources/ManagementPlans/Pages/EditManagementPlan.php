<?php

namespace App\Filament\Resources\ManagementPlans\Pages;

use App\Filament\Resources\ManagementPlans\ManagementPlanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditManagementPlan extends EditRecord
{
    protected static string $resource = ManagementPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
