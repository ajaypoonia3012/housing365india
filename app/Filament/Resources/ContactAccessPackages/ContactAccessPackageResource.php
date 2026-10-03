<?php

namespace App\Filament\Resources\ContactAccessPackages;

use App\Filament\Resources\ContactAccessPackages\Pages\CreateContactAccessPackage;
use App\Filament\Resources\ContactAccessPackages\Pages\EditContactAccessPackage;
use App\Filament\Resources\ContactAccessPackages\Pages\ListContactAccessPackages;
use App\Filament\Resources\ContactAccessPackages\Schemas\ContactAccessPackageForm;
use App\Filament\Resources\ContactAccessPackages\Tables\ContactAccessPackagesTable;
use App\Models\ContactAccessPackage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ContactAccessPackageResource extends Resource
{
    protected static ?string $model = ContactAccessPackage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Key;

    protected static \UnitEnum|string|null $navigationGroup = 'Marketplace & Plans';

    protected static ?string $navigationLabel = 'Contact Access Packages';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return ContactAccessPackageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ContactAccessPackagesTable::configure($table);
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
            'index' => ListContactAccessPackages::route('/'),
            'create' => CreateContactAccessPackage::route('/create'),
            'edit' => EditContactAccessPackage::route('/{record}/edit'),
        ];
    }
}
