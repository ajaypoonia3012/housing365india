<?php

namespace App\Filament\Resources\Localities;

use App\Models\Locality;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LocalityResource extends Resource
{
    protected static ?string $model = Locality::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home-modern';

    protected static \UnitEnum|string|null $navigationGroup = 'Locations & SEO';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('city_location_id')
                    ->label('City')
                    ->relationship('city', 'name')
                    ->searchable()
                    ->required(),
                TextInput::make('name')
                    ->label('Locality Name')
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
                    ->helperText('Auto-generated from locality name.'),
                TextInput::make('pincode')
                    ->label('Postal Pincode'),
                TextInput::make('meta_title')
                    ->label('SEO Meta Title'),
                Textarea::make('meta_description')
                    ->label('SEO Meta Description'),
                Textarea::make('seo_intro')
                    ->label('SEO Introduction')
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->label('Active on Platform')
                    ->default(true),
                Toggle::make('is_indexable')
                    ->label('Indexable by Search Engines')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('city.name')
                    ->label('City')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('pincode')
                    ->searchable(),
                TextColumn::make('active_properties_count')
                    ->label('Live Properties')
                    ->badge()
                    ->color('info'),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                IconColumn::make('is_indexable')
                    ->label('Indexable')
                    ->boolean(),
            ])
            ->filters([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLocalities::route('/'),
        ];
    }
}
