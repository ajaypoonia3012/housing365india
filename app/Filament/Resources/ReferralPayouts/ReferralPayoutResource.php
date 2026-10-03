<?php

namespace App\Filament\Resources\ReferralPayouts;

use App\Filament\Resources\ReferralPayouts\Pages\ListReferralPayouts;
use App\Models\ReferralPayout;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ReferralPayoutResource extends Resource
{
    protected static ?string $model = ReferralPayout::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Banknotes;

    protected static \UnitEnum|string|null $navigationGroup = 'Refer & Earn Program';

    protected static ?string $navigationLabel = 'Referral Payouts';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('payout_number')->disabled(),
            TextInput::make('amount')->numeric()->prefix('₹')->disabled(),
            TextInput::make('payout_destination_masked')->label('UPI Destination')->disabled(),
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
            TextInput::make('transaction_reference')->label('Bank / UPI UTR Reference'),
            Textarea::make('admin_notes'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('payout_number')->label('Payout #')->searchable()->sortable(),
                TextColumn::make('user.name')->label('Recipient User')->searchable(),
                TextColumn::make('amount')->label('Amount')->money('INR')->sortable(),
                TextColumn::make('payout_destination_masked')->label('UPI Destination'),
                TextColumn::make('status')->badge()->sortable(),
                TextColumn::make('transaction_reference')->label('UTR Ref')->searchable(),
                TextColumn::make('processed_at')->dateTime('d M Y, h:i A')->sortable(),
                TextColumn::make('created_at')->dateTime('d M Y')->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReferralPayouts::route('/'),
        ];
    }
}
