<?php

namespace App\Filament\Resources\ReferralRewards;

use App\Filament\Resources\ReferralRewards\Pages\ListReferralRewards;
use App\Models\ReferralReward;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ReferralRewardResource extends Resource
{
    protected static ?string $model = ReferralReward::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CurrencyRupee;

    protected static \UnitEnum|string|null $navigationGroup = 'Refer & Earn Program';

    protected static ?string $navigationLabel = 'Referral Rewards';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('amount')->numeric()->prefix('₹')->required(),
            Select::make('status')
                ->options([
                    'PENDING' => 'Pending',
                    'APPROVED' => 'Approved',
                    'ON_HOLD' => 'On Hold',
                    'PAID' => 'Paid',
                    'CANCELLED' => 'Cancelled',
                ])
                ->required(),
            TextInput::make('reason'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('Reward ID')->prefix('#RWD-')->sortable(),
                TextColumn::make('user.name')->label('Referrer User')->searchable(),
                TextColumn::make('referral.id')->label('Referral #')->prefix('#REF-')->sortable(),
                TextColumn::make('amount')->label('Amount')->money('INR')->sortable(),
                TextColumn::make('status')->badge()->sortable(),
                TextColumn::make('approved_at')->dateTime('d M Y, h:i A')->sortable(),
                TextColumn::make('created_at')->dateTime('d M Y')->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReferralRewards::route('/'),
        ];
    }
}
