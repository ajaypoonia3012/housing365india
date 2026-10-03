<?php

namespace App\Filament\Resources\CareServiceCategories;

use App\Filament\Resources\CareServiceCategories\Pages\CreateCareServiceCategory;
use App\Filament\Resources\CareServiceCategories\Pages\EditCareServiceCategory;
use App\Filament\Resources\CareServiceCategories\Pages\ListCareServiceCategories;
use App\Filament\Resources\CareServiceCategories\Schemas\CareServiceCategoryForm;
use App\Filament\Resources\CareServiceCategories\Tables\CareServiceCategoriesTable;
use App\Models\CareServiceCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CareServiceCategoryResource extends Resource
{
    protected static ?string $model = CareServiceCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::WrenchScrewdriver;

    protected static \UnitEnum|string|null $navigationGroup = 'Housing365 Care & Management';

    protected static ?string $navigationLabel = 'Care Categories';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return CareServiceCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CareServiceCategoriesTable::configure($table);
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
            'index' => ListCareServiceCategories::route('/'),
            'create' => CreateCareServiceCategory::route('/create'),
            'edit' => EditCareServiceCategory::route('/{record}/edit'),
        ];
    }
}
