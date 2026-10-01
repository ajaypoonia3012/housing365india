<?php

namespace App\Filament\Resources\CityLocations;

use App\Filament\Resources\CityLocations\Pages\CreateCityLocation;
use App\Filament\Resources\CityLocations\Pages\EditCityLocation;
use App\Filament\Resources\CityLocations\Pages\ListCityLocations;
use App\Filament\Resources\CityLocations\Schemas\CityLocationForm;
use App\Filament\Resources\CityLocations\Tables\CityLocationsTable;
use App\Models\CityLocation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CityLocationResource extends Resource
{
    protected static ?string $model = CityLocation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::MapPin;

    protected static \UnitEnum|string|null $navigationGroup = 'Content & CMS';

    protected static ?string $navigationLabel = 'Cities & Locations';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return CityLocationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CityLocationsTable::configure($table);
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
            'index' => ListCityLocations::route('/'),
            'create' => CreateCityLocation::route('/create'),
            'edit' => EditCityLocation::route('/{record}/edit'),
        ];
    }
}
