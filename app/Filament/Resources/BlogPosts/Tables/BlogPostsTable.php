<?php

namespace App\Filament\Resources\BlogPosts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BlogPostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('category')
                    ->searchable(),
                ImageColumn::make('image')
                    ->circular()
                    ->checkFileExistence(false),
                TextColumn::make('author_name')
                    ->searchable(),
                TextColumn::make('read_time')
                    ->searchable(),
                IconColumn::make('is_published')
                    ->boolean(),
                TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('linkedin_shared_at')
                    ->label('LinkedIn')
                    ->badge()
                    ->state(fn ($record): string => $record->linkedin_shared_at ? 'Posted' : 'Not Shared')
                    ->color(fn ($record): string => $record->linkedin_shared_at ? 'success' : 'gray')
                    ->icon(fn ($record): string => $record->linkedin_shared_at ? 'heroicon-m-check-badge' : 'heroicon-m-share')
                    ->tooltip(fn ($record): string => $record->linkedin_shared_at ? 'Shared on ' . $record->linkedin_shared_at->format('d M Y, H:i') : 'Not shared on LinkedIn yet')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                \App\Services\LinkedInPublishingService::createFilamentAction(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
