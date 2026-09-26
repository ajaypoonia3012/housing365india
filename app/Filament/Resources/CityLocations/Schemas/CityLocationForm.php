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
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('province_or_state')
                    ->required(),
                TextInput::make('country')
                    ->required()
                    ->default('CA'),
                TextInput::make('tier')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('avg_rent')
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
