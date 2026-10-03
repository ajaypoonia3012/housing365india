<?php

namespace App\Filament\Resources\ManagementPlans;

use App\Filament\Resources\ManagementPlans\Pages\CreateManagementPlan;
use App\Filament\Resources\ManagementPlans\Pages\EditManagementPlan;
use App\Filament\Resources\ManagementPlans\Pages\ListManagementPlans;
use App\Filament\Resources\ManagementPlans\Schemas\ManagementPlanForm;
use App\Filament\Resources\ManagementPlans\Tables\ManagementPlansTable;
use App\Models\ManagementPlan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ManagementPlanResource extends Resource
{
    protected static ?string $model = ManagementPlan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentText;

    protected static \UnitEnum|string|null $navigationGroup = 'Housing365 Care & Management';

    protected static ?string $navigationLabel = 'Management Plans';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return ManagementPlanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ManagementPlansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListManagementPlans::route('/'),
            'create' => CreateManagementPlan::route('/create'),
            'edit' => EditManagementPlan::route('/{record}/edit'),
        ];
    }
}
