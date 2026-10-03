<?php

namespace App\Filament\Resources\RentLedgers;

use App\Filament\Resources\RentLedgers\Pages\EditRentLedger;
use App\Filament\Resources\RentLedgers\Pages\ListRentLedgers;
use App\Models\RentLedger;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RentLedgerResource extends Resource
{
    protected static ?string $model = RentLedger::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Banknotes;

    protected static \UnitEnum|string|null $navigationGroup = 'Tenant Lifecycle & Operations';

    protected static ?string $navigationLabel = 'Rent Ledgers';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('billing_period')->disabled(),
            TextInput::make('amount_due')->numeric()->disabled(),
            TextInput::make('amount_paid')->numeric(),
            TextInput::make('outstanding_balance')->numeric(),
            Select::make('status')
                ->options([
                    'DUE' => 'Due',
                    'PARTIALLY_PAID' => 'Partially Paid',
                    'PAID' => 'Paid',
                    'OVERDUE' => 'Overdue',
                ])
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('billing_period')->sortable(),
                TextColumn::make('property.title')->label('Property')->limit(25),
                TextColumn::make('tenant.name')->label('Tenant'),
                TextColumn::make('amount_due')->money('INR')->sortable(),
                TextColumn::make('amount_paid')->money('INR'),
                TextColumn::make('outstanding_balance')->money('INR'),
                TextColumn::make('status')->badge()->sortable(),
                TextColumn::make('due_date')->date()->sortable(),
            ])
            ->defaultSort('due_date', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRentLedgers::route('/'),
            'edit' => EditRentLedger::route('/{record}/edit'),
        ];
    }
}
