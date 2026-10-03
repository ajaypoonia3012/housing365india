<?php

namespace App\Filament\Resources\TaxConfigurations;

use App\Filament\Resources\TaxConfigurations\Pages\CreateTaxConfiguration;
use App\Filament\Resources\TaxConfigurations\Pages\EditTaxConfiguration;
use App\Filament\Resources\TaxConfigurations\Pages\ListTaxConfigurations;
use App\Models\TaxConfiguration;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TaxConfigurationResource extends Resource
{
    protected static ?string $model = TaxConfiguration::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Banknotes;

    protected static \UnitEnum|string|null $navigationGroup = 'Monetization & Growth';

    protected static ?string $navigationLabel = 'GST / Tax Settings';

    protected static ?int $navigationSort = 4;

    public static function canViewAny(): bool
    {
        return Auth::check() && Auth::user()->isAdmin();
    }

    public static function canCreate(): bool
    {
        return Auth::check() && Auth::user()->isAdmin();
    }

    public static function canEdit(Model $record): bool
    {
        return Auth::check() && Auth::user()->isAdmin();
    }

    public static function canDelete(Model $record): bool
    {
        return Auth::check() && Auth::user()->isAdmin();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Toggle::make('tax_enabled')
                    ->label('GST / Tax Enabled (Global Toggle)')
                    ->helperText('When OFF, tax is calculated as ₹0 on checkout and invoices.')
                    ->default(true),

                TextInput::make('tax_name')
                    ->label('Tax Name')
                    ->default('GST')
                    ->required(),

                Select::make('tax_type')
                    ->label('Tax Type')
                    ->options([
                        'GST' => 'Goods and Services Tax (GST)',
                        'OTHER' => 'Other / State Tax',
                        'NONE' => 'Exempt / None',
                    ])
                    ->default('GST')
                    ->required(),

                TextInput::make('tax_rate')
                    ->label('Total Tax Rate (%)')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->default(18.00)
                    ->required()
                    ->helperText('Total applicable tax percentage (e.g. 18.00, 12.00, 5.00).'),

                Toggle::make('cgst_enabled')
                    ->label('CGST Enabled')
                    ->default(true),

                TextInput::make('cgst_rate')
                    ->label('CGST Rate (%)')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(50)
                    ->default(9.00),

                Toggle::make('sgst_enabled')
                    ->label('SGST Enabled')
                    ->default(true),

                TextInput::make('sgst_rate')
                    ->label('SGST Rate (%)')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(50)
                    ->default(9.00),

                Toggle::make('igst_enabled')
                    ->label('IGST Enabled (Interstate)')
                    ->default(false),

                TextInput::make('igst_rate')
                    ->label('IGST Rate (%)')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->default(18.00),

                DateTimePicker::make('effective_from')
                    ->label('Effective From')
                    ->default(now()),

                DateTimePicker::make('effective_until')
                    ->label('Effective Until (Optional)'),

                Toggle::make('active')
                    ->label('Status: Active')
                    ->default(true),

                Textarea::make('notes')
                    ->label('Audit Notes / Rationale')
                    ->placeholder('e.g. GST rate adjustment per Council notification')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tax_name')
                    ->label('Tax')
                    ->searchable()
                    ->weight('bold'),

                IconColumn::make('tax_enabled')
                    ->label('Tax ON/OFF')
                    ->boolean(),

                TextColumn::make('tax_rate')
                    ->label('Rate (%)')
                    ->formatStateUsing(fn ($state) => number_format($state, 2).'%')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('cgst_rate')
                    ->label('CGST')
                    ->formatStateUsing(fn ($state) => number_format($state, 2).'%'),

                TextColumn::make('sgst_rate')
                    ->label('SGST')
                    ->formatStateUsing(fn ($state) => number_format($state, 2).'%'),

                TextColumn::make('effective_from')
                    ->label('Effective From')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                IconColumn::make('active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTaxConfigurations::route('/'),
            'create' => CreateTaxConfiguration::route('/create'),
            'edit' => EditTaxConfiguration::route('/{record}/edit'),
        ];
    }
}
