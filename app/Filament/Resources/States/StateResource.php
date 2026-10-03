<?php

namespace App\Filament\Resources\States;

use App\Models\State;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StateResource extends Resource
{
    protected static ?string $model = State::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';

    protected static \UnitEnum|string|null $navigationGroup = 'Locations & SEO';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('State / UT Name')
                    ->required(),
                TextInput::make('slug')
                    ->label('URL Slug')
                    ->required(),
                TextInput::make('code')
                    ->label('State Code (e.g. RJ, MH, DL)'),
                TextInput::make('region')
                    ->label('Region (e.g. Northern, Western)'),
                TextInput::make('capital')
                    ->label('State Capital'),
                TextInput::make('tenancy_act_title')
                    ->label('State Tenancy Act'),
                TextInput::make('police_verification_portal')
                    ->label('Police Verification Portal'),
                Textarea::make('stamp_duty_summary')
                    ->label('Stamp Duty & Legal Summary')
                    ->columnSpanFull(),
                TextInput::make('meta_title')
                    ->label('SEO Meta Title'),
                Textarea::make('meta_description')
                    ->label('SEO Meta Description'),
                Toggle::make('is_active')
                    ->label('Active on Platform')
                    ->default(true),
                Toggle::make('is_indexable')
                    ->label('Indexable by Search Engines')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('code')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('region')
                    ->sortable(),
                TextColumn::make('cities_count')
                    ->counts('cities')
                    ->label('Cities')
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
            'index' => Pages\ListStates::route('/'),
        ];
    }
}
