<?php

namespace App\Filament\Resources\ContactAccessPackages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContactAccessPackagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Package')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('credits')
                    ->label('Credits')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('price')
                    ->label('Price')
                    ->money('INR')
                    ->sortable(),
                TextColumn::make('validity_days')
                    ->label('Validity')
                    ->suffix(' days')
                    ->sortable(),
                TextColumn::make('badge_text')
                    ->label('Badge')
                    ->badge(),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('sort_order')
                    ->label('Order')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
