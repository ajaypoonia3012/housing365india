<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('User Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-envelope'),

                TextColumn::make('phone')
                    ->label('Phone / WhatsApp')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-phone'),

                TextColumn::make('user_type')
                    ->label('Role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'owner' => 'success',
                        'buyer' => 'info',
                        'tenant' => 'primary',
                        'admin' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => ucfirst($state)),

                TextColumn::make('city')
                    ->label('City')
                    ->searchable()
                    ->sortable(),

                IconColumn::make('is_prime')
                    ->label('Prime')
                    ->boolean()
                    ->trueIcon('heroicon-s-star')
                    ->falseIcon('heroicon-o-minus-circle')
                    ->trueColor('warning')
                    ->falseColor('gray'),

                TextColumn::make('prime_plan')
                    ->label('Package')
                    ->badge()
                    ->color('warning')
                    ->placeholder('Free Account'),

                TextColumn::make('unlocked_contacts_count')
                    ->label('Contacts Used / Limit')
                    ->formatStateUsing(function (User $record): string {
                        if (!$record->is_prime) return '0 (Free)';
                        $limit = $record->prime_contacts_limit >= 999 ? '∞ Unlimited' : (string)$record->prime_contacts_limit;
                        return "{$record->unlocked_contacts_count} / {$limit}";
                    }),

                TextColumn::make('prime_expires_at')
                    ->label('Prime Valid Until')
                    ->dateTime('d M Y')
                    ->placeholder('N/A')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('user_type')
                    ->label('Filter Role')
                    ->options([
                        'tenant' => 'Tenants',
                        'buyer'  => 'Buyers',
                        'owner'  => 'Owners / Landlords',
                    ]),

                TernaryFilter::make('is_prime')
                    ->label('Prime Membership')
                    ->placeholder('All Accounts')
                    ->trueLabel('Active Prime Members')
                    ->falseLabel('Free Standard Accounts'),
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
}
