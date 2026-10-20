<?php

namespace App\Filament\Widgets;

use App\Models\Property;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentPropertiesWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Property::query()->latest()->limit(6)
            )
            ->heading('Newly Posted Real Estate Listings')
            ->description('Recent residential, commercial, and plot listings submitted by owners, builders, and administrators.')
            ->columns([
                ImageColumn::make('image')
                    ->label('Thumbnail')
                    ->circular()
                    ->checkFileExistence(false)
                    ->state(function (Property $record): ?string {
                        $img = $record->image;
                        if (empty($img)) {
                            return 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=100&auto=format&fit=crop&q=80';
                        }
                        if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
                            return $img;
                        }
                        if (str_starts_with($img, '/storage/')) {
                            return substr($img, 9);
                        }
                        return $img;
                    })
                    ->defaultImageUrl('https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=100&auto=format&fit=crop&q=80'),

                TextColumn::make('title')
                    ->label('Listing')
                    ->description(fn (Property $record): string => ($record->locality ? $record->locality.', ' : '').$record->city)
                    ->weight('bold')
                    ->limit(32)
                    ->searchable(),

                TextColumn::make('purpose')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'rent' => 'info',
                        'sale' => 'success',
                        default => 'warning',
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
                    ->weight('bold')
                    ->color('success'),

                TextColumn::make('seller_phone')
                    ->label('Seller / Owner')
                    ->description(fn (Property $record): string => ($record->seller_name ?: $record->posted_by))
                    ->copyable()
                    ->icon('heroicon-m-phone'),

                IconColumn::make('is_verified')
                    ->label('Verified')
                    ->boolean()
                    ->trueIcon('heroicon-s-check-badge')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
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
                    ->label('Posted')
                    ->since()
                    ->sortable(),
            ])
            ->paginated(false);
    }
}
