<?php

namespace App\Filament\Resources\CareInvoices;

use App\Filament\Resources\CareInvoices\Pages\EditCareInvoice;
use App\Filament\Resources\CareInvoices\Pages\ListCareInvoices;
use App\Models\CareInvoice;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CareInvoiceResource extends Resource
{
    protected static ?string $model = CareInvoice::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentCurrencyDollar;

    protected static \UnitEnum|string|null $navigationGroup = 'Housing365 Care & Management';

    protected static ?string $navigationLabel = 'Care Invoices';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('invoice_number')->disabled(),
            TextInput::make('subtotal')->numeric()->disabled(),
            TextInput::make('tax_amount')->numeric()->disabled(),
            TextInput::make('total_amount')->numeric()->disabled(),
            Select::make('payment_status')
                ->options([
                    'PENDING' => 'Pending',
                    'PAID' => 'Paid',
                    'FAILED' => 'Failed',
                    'CANCELLED' => 'Cancelled',
                ])
                ->required(),
            TextInput::make('payment_reference'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')->label('Invoice #')->searchable()->sortable(),
                TextColumn::make('serviceRequest.request_number')->label('Request #')->searchable(),
                TextColumn::make('user.name')->label('Customer')->searchable(),
                TextColumn::make('vendor.name')->label('Vendor')->searchable(),
                TextColumn::make('total_amount')->label('Total')->money('INR')->sortable(),
                TextColumn::make('payment_status')->badge()->sortable(),
                TextColumn::make('payment_reference')->label('Reference')->toggleable(),
                TextColumn::make('paid_at')->dateTime('d M Y, h:i A')->sortable(),
                TextColumn::make('created_at')->dateTime('d M Y')->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCareInvoices::route('/'),
            'edit' => EditCareInvoice::route('/{record}/edit'),
        ];
    }
}
