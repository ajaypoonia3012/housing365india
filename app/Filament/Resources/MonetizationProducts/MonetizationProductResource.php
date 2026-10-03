<?php

namespace App\Filament\Resources\MonetizationProducts;

use App\Filament\Resources\MonetizationProducts\Pages\CreateMonetizationProduct;
use App\Filament\Resources\MonetizationProducts\Pages\EditMonetizationProduct;
use App\Filament\Resources\MonetizationProducts\Pages\ListMonetizationProducts;
use App\Models\MonetizationProduct;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MonetizationProductResource extends Resource
{
    protected static ?string $model = MonetizationProduct::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CurrencyRupee;

    protected static \UnitEnum|string|null $navigationGroup = 'Monetization & Growth';

    protected static ?string $navigationLabel = 'Monetization Products';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Product Name')
                    ->required(),
                TextInput::make('code')
                    ->label('Catalog Code')
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('target_role')
                    ->label('Target Persona / Role')
                    ->placeholder('e.g. owner, broker, builder, tenant'),
                TextInput::make('target_category')
                    ->label('Target Category')
                    ->placeholder('e.g. LISTING_PROMOTION, SUBSCRIPTION'),
                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
                Toggle::make('is_taxable')
                    ->label('Subject to GST / Tax')
                    ->default(true),
                Textarea::make('description')
                    ->label('Description')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('code')->badge()->searchable(),
                TextColumn::make('target_role')->badge(),
                TextColumn::make('plans_count')->counts('plans')->label('Active Plans'),
                IconColumn::make('is_active')->boolean()->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMonetizationProducts::route('/'),
            'create' => CreateMonetizationProduct::route('/create'),
            'edit' => EditMonetizationProduct::route('/{record}/edit'),
        ];
    }
}
