<?php

namespace App\Filament\Resources\VendorPayouts;

use App\Filament\Resources\VendorPayouts\Pages\EditVendorPayout;
use App\Filament\Resources\VendorPayouts\Pages\ListVendorPayouts;
use App\Models\VendorPayout;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VendorPayoutResource extends Resource
{
    protected static ?string $model = VendorPayout::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Banknotes;

    protected static \UnitEnum|string|null $navigationGroup = 'Housing365 Care & Management';

    protected static ?string $navigationLabel = 'Vendor Payouts';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('payout_number')->disabled(),
            TextInput::make('gross_amount')->numeric()->disabled(),
            TextInput::make('platform_fee')->numeric()->disabled(),
            TextInput::make('net_amount')->numeric()->disabled(),
            Select::make('status')
                ->options([
                    'PENDING' => 'Pending',
                    'APPROVED' => 'Approved',
                    'PROCESSING' => 'Processing',
                    'PAID' => 'Paid',
                    'FAILED' => 'Failed',
                    'ON_HOLD' => 'On Hold',
                ])
                ->required(),
            TextInput::make('payout_reference'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('payout_number')->label('Payout #')->searchable()->sortable(),
                TextColumn::make('workOrder.work_order_number')->label('Work Order #')->searchable(),
                TextColumn::make('vendor.name')->label('Vendor')->searchable(),
                TextColumn::make('gross_amount')->label('Gross')->money('INR')->sortable(),
                TextColumn::make('platform_fee')->label('Commission (10%)')->money('INR'),
                TextColumn::make('net_amount')->label('Net Payout')->money('INR')->sortable(),
                TextColumn::make('status')->badge()->sortable(),
                TextColumn::make('paid_at')->dateTime('d M Y, h:i A')->sortable(),
                TextColumn::make('created_at')->dateTime('d M Y')->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVendorPayouts::route('/'),
            'edit' => EditVendorPayout::route('/{record}/edit'),
        ];
    }
}
