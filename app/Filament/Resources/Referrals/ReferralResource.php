<?php

namespace App\Filament\Resources\Referrals;

use App\Filament\Resources\Referrals\Pages\ListReferrals;
use App\Models\Referral;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ReferralResource extends Resource
{
    protected static ?string $model = Referral::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Gift;

    protected static \UnitEnum|string|null $navigationGroup = 'Refer & Earn Program';

    protected static ?string $navigationLabel = 'Referrals';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('referral_code')->disabled(),
            TextInput::make('owner_name')->required(),
            TextInput::make('owner_phone')->required(),
            TextInput::make('city')->required(),
            TextInput::make('locality')->required(),
            TextInput::make('property_type')->required(),
            Select::make('listing_intent')
                ->options([
                    'rent' => 'Rent',
                    'sale' => 'Sale',
                    'pg' => 'PG',
                    'coliving' => 'Co-living',
                    'commercial' => 'Commercial',
                    'land' => 'Land',
                ])
                ->required(),
            Select::make('status')
                ->options([
                    'SUBMITTED' => 'Submitted',
                    'UNDER_REVIEW' => 'Under Review',
                    'CONTACTED' => 'Contacted',
                    'QUALIFIED' => 'Qualified',
                    'PROPERTY_LISTED' => 'Property Listed',
                    'REWARD_PENDING' => 'Reward Pending',
                    'REWARD_APPROVED' => 'Reward Approved',
                    'PAYOUT_PENDING' => 'Payout Pending',
                    'PAID' => 'Paid',
                    'REJECTED' => 'Rejected',
                    'CANCELLED' => 'Cancelled',
                ])
                ->required(),
            Textarea::make('rejection_reason'),
            Textarea::make('admin_notes'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('Ref ID')->prefix('#REF-')->sortable(),
                TextColumn::make('referrer.name')->label('Referrer')->searchable(),
                TextColumn::make('owner_name')->label('Owner')->searchable(),
                TextColumn::make('normalized_owner_phone')->label('Phone')->searchable(),
                TextColumn::make('city')->label('City')->sortable()->searchable(),
                TextColumn::make('property_type')->label('Type'),
                TextColumn::make('status')->badge()->sortable(),
                IconColumn::make('duplicate_flag')->boolean()->label('Dup?'),
                IconColumn::make('fraud_flag')->boolean()->label('Fraud?'),
                TextColumn::make('submitted_at')->dateTime('d M Y')->sortable(),
            ])
            ->defaultSort('submitted_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReferrals::route('/'),
        ];
    }
}
