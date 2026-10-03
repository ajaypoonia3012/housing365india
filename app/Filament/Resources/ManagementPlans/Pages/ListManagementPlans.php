<?php

namespace App\Filament\Resources\ManagementPlans\Pages;

use App\Filament\Resources\ManagementPlans\ManagementPlanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListManagementPlans extends ListRecords
{
    protected static string $resource = ManagementPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
