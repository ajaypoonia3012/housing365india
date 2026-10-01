<?php

namespace App\Filament\Resources\CityLocations\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CityLocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('City Name')
                    ->required(),
                TextInput::make('slug')
                    ->label('URL Slug')
                    ->required(),
                TextInput::make('province_or_state')
                    ->label('Indian State / Union Territory')
                    ->placeholder('e.g. Rajasthan, Maharashtra, Karnataka')
                    ->required(),
                TextInput::make('country')
                    ->label('Country Code')
                    ->required()
                    ->default('IN'),
                TextInput::make('tier')
                    ->label('City Tier (1, 2, or 3)')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('avg_rent')
                    ->label('Average Monthly Rent')
                    ->placeholder('e.g. ₹38,000')
                    ->default(null),
                TextInput::make('available_units_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->image(),
                Toggle::make('is_featured')
                    ->required(),
            ]);
    }
}
