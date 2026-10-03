<?php

namespace App\Filament\Resources\Properties\Tables;

use App\Models\Property;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PropertiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Photo')
                    ->circular()
                    ->defaultImageUrl('https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=100&auto=format&fit=crop&q=80'),

                TextColumn::make('title')
                    ->label('Property Listing')
                    ->description(fn (Property $record): string => ($record->locality ? $record->locality.', ' : '').$record->city)
                    ->searchable()
                    ->weight('bold')
                    ->limit(35),

                TextColumn::make('purpose')
                    ->label('Purpose')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'rent' => 'info',
                        'sale' => 'success',
                        'lease' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => strtoupper($state)),

                TextColumn::make('category')
                    ->label('Category')
                    ->badge()
                    ->color('primary')
                    ->formatStateUsing(fn ($state) => ucwords(str_replace('_', ' ', $state))),

                TextColumn::make('price_display')
                    ->label('Price')
                    ->state(function (Property $record): string {
                        if (! empty($record->price_display)) {
                            return $record->price_display;
                        }

                        return '₹'.number_format($record->price).($record->purpose === 'sale' ? '' : '/mo');
                    })
                    ->weight('semibold')
                    ->color('success')
                    ->sortable(query: function ($query, string $direction) {
                        return $query->orderBy('price', $direction);
                    }),

                TextColumn::make('bedrooms')
                    ->label('BHK')
                    ->formatStateUsing(fn ($state) => $state ? $state.' BHK' : 'Plot/Studio')
                    ->sortable(),

                TextColumn::make('seller_phone')
                    ->label('Owner Contact')
                    ->description(fn (Property $record): string => $record->seller_name ?? 'Owner')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-phone'),

                IconColumn::make('is_verified')
                    ->label('Verified')
                    ->boolean()
                    ->trueIcon('heroicon-s-check-badge')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),

                IconColumn::make('featured')
                    ->label('Featured')
                    ->boolean()
                    ->trueIcon('heroicon-s-star')
                    ->falseIcon('heroicon-o-minus')
                    ->trueColor('warning')
                    ->falseColor('gray'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match (strtolower($state)) {
                        'available' => 'success',
                        'under offer' => 'warning',
                        'rented' => 'gray',
                        'sold' => 'danger',
                        default => 'primary',
                    }),

                TextColumn::make('created_at')
                    ->label('Listed')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('purpose')
                    ->label('Listing Purpose')
                    ->options([
                        'rent' => 'Residential Rent',
                        'sale' => 'Residential Sale',
                        'commercial_rent' => 'Commercial Rent',
                        'commercial_sale' => 'Commercial Sale',
                        'land_sale' => 'Land / Plots Sale',
                        'pg' => 'PG (Paying Guest)',
                        'coliving' => 'Co-Living',
                        'coworking' => 'Coworking Space',
                        'project' => 'Builder Project',
                    ]),

                SelectFilter::make('category')
                    ->label('Category')
                    ->options([
                        'residential' => 'Residential',
                        'commercial' => 'Commercial',
                        'plot_land' => 'Plots & Land',
                        'pg_coliving' => 'PG & Co-Living',
                    ]),

                TernaryFilter::make('is_verified')
                    ->label('Verification Status')
                    ->trueLabel('Verified Listings Only')
                    ->falseLabel('Unverified / Pending Listings'),

                TernaryFilter::make('featured')
                    ->label('Homepage Featured'),

                SelectFilter::make('status')
                    ->options([
                        'Available' => 'Available',
                        'Under Offer' => 'Under Offer',
                        'Rented' => 'Rented',
                        'Sold' => 'Sold',
                    ]),
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
