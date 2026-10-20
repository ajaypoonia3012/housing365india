<?php

namespace App\Filament\Resources\CityLocations\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set, string $operation) {
                        if ($operation === 'create' || empty($state)) {
                            $set('slug', \Illuminate\Support\Str::slug($state));
                        }
                    }),
                TextInput::make('slug')
                    ->label('URL Slug')
                    ->required()
                    ->helperText('Auto-generated from city name.'),
                TextInput::make('province_or_state')
                    ->label('Indian State / Union Territory')
                    ->placeholder('e.g. Rajasthan, Maharashtra, Karnataka')
                    ->required(),
                TextInput::make('state_slug')
                    ->label('State Slug')
                    ->placeholder('e.g. rajasthan, maharashtra'),
                TextInput::make('country')
                    ->label('Country Code')
                    ->required()
                    ->default('IN'),
                TextInput::make('tier')
                    ->label('City Tier (1, 2, or 3)')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('seo_h1')
                    ->label('SEO H1 Heading')
                    ->placeholder('e.g. Property in Jaipur'),
                TextInput::make('meta_title')
                    ->label('SEO Meta Title'),
                Textarea::make('meta_description')
                    ->label('SEO Meta Description')
                    ->rows(2),
                Textarea::make('seo_intro')
                    ->label('SEO Intro Paragraph')
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->image(),
                Toggle::make('is_featured')
                    ->label('Featured City'),
                Toggle::make('is_active')
                    ->label('Active on Platform')
                    ->default(true),
                Toggle::make('is_indexable')
                    ->label('Indexable by Search Engines')
                    ->default(true),
            ]);

    }
}
