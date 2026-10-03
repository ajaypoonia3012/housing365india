<?php

namespace App\Filament\Resources\SeoMetaOverrides;

use App\Models\SeoMetaOverride;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SeoMetaOverrideResource extends Resource
{
    protected static ?string $model = SeoMetaOverride::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-globe-alt';

    protected static \UnitEnum|string|null $navigationGroup = 'Locations & SEO';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('path')
                    ->label('Target URL Path')
                    ->placeholder('e.g. /rent/rajasthan/jaipur/')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('meta_title')
                    ->label('Custom SEO Title')
                    ->columnSpan(1),
                TextInput::make('h1')
                    ->label('Custom Page H1')
                    ->columnSpan(1),
                Textarea::make('meta_description')
                    ->label('Custom Meta Description')
                    ->rows(2)
                    ->columnSpanFull(),
                TextInput::make('canonical_url')
                    ->label('Explicit Canonical URL')
                    ->placeholder('Leave blank to use default')
                    ->columnSpanFull(),
                Textarea::make('intro_text')
                    ->label('Introductory Text')
                    ->columnSpanFull(),
                Toggle::make('is_indexable')
                    ->label('Indexable (Robots: index, follow)')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('path')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('meta_title')
                    ->searchable()
                    ->limit(40),
                IconColumn::make('is_indexable')
                    ->label('Indexable')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSeoMetaOverrides::route('/'),
        ];
    }
}
