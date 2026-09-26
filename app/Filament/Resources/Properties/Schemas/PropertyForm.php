<?php

namespace App\Filament\Resources\Properties\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PropertyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('address')
                    ->default(null),
                TextInput::make('city')
                    ->required(),
                TextInput::make('state')
                    ->default(null),
                TextInput::make('country')
                    ->required()
                    ->default('CA'),
                TextInput::make('postal_code')
                    ->default(null),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('price_frequency')
                    ->required()
                    ->default('month'),
                TextInput::make('bedrooms')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('bathrooms')
                    ->required()
                    ->numeric()
                    ->default(1.0),
                TextInput::make('sqft')
                    ->numeric()
                    ->default(null),
                TextInput::make('property_type')
                    ->required()
                    ->default('Apartment'),
                TextInput::make('status')
                    ->required()
                    ->default('Available'),
                Toggle::make('featured')
                    ->required(),
                FileUpload::make('image')
                    ->image(),
                Textarea::make('gallery')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('amenities')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
