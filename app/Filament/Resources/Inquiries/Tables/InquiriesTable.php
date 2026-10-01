<?php

namespace App\Filament\Resources\Inquiries\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class InquiriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Lead Name')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('service_type')
                    ->label('Type / Category')
                    ->badge()
                    ->color('info')
                    ->searchable(),

                TextColumn::make('phone')
                    ->label('Phone / WhatsApp')
                    ->copyable()
                    ->icon('heroicon-m-phone')
                    ->searchable(),

                TextColumn::make('email')
                    ->label('Email Address')
                    ->copyable()
                    ->icon('heroicon-m-envelope')
                    ->searchable(),

                TextColumn::make('subject')
                    ->label('Subject / Summary')
                    ->limit(35)
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match (strtolower($state)) {
                        'new' => 'warning',
                        'contacted' => 'info',
                        'closed' => 'success',
                        'spam' => 'gray',
                        default => 'primary',
                    })
                    ->formatStateUsing(fn ($state) => ucfirst($state)),

                TextColumn::make('created_at')
                    ->label('Received')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'new' => 'New / Uncontacted',
                        'contacted' => 'Contacted / Follow Up',
                        'closed' => 'Closed / Converted',
                        'spam' => 'Spam / Invalid',
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
