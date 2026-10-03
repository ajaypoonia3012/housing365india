<?php

namespace App\Filament\Resources\ManagementEnrollments;

use App\Filament\Resources\ManagementEnrollments\Pages\CreateManagementEnrollment;
use App\Filament\Resources\ManagementEnrollments\Pages\EditManagementEnrollment;
use App\Filament\Resources\ManagementEnrollments\Pages\ListManagementEnrollments;
use App\Filament\Resources\ManagementEnrollments\Schemas\ManagementEnrollmentForm;
use App\Filament\Resources\ManagementEnrollments\Tables\ManagementEnrollmentsTable;
use App\Models\ManagementEnrollment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ManagementEnrollmentResource extends Resource
{
    protected static ?string $model = ManagementEnrollment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::BuildingOffice2;

    protected static \UnitEnum|string|null $navigationGroup = 'Housing365 Care & Management';

    protected static ?string $navigationLabel = 'Owner Enrollments';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return ManagementEnrollmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ManagementEnrollmentsTable::configure($table);
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
            'index' => ListManagementEnrollments::route('/'),
            'create' => CreateManagementEnrollment::route('/create'),
            'edit' => EditManagementEnrollment::route('/{record}/edit'),
        ];
    }
}
