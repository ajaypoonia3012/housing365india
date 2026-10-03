<?php

namespace App\Filament\Resources\Reviews;

use App\Filament\Resources\Reviews\Pages\CreateReview;
use App\Filament\Resources\Reviews\Pages\EditReview;
use App\Filament\Resources\Reviews\Pages\ListReviews;
use App\Models\Review;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Star;

    protected static \UnitEnum|string|null $navigationGroup = 'Trust & Verification';

    protected static ?string $navigationLabel = 'Reviews & Ratings';

    protected static ?int $navigationSort = 3;

    public static function canViewAny(): bool
    {
        return Auth::check() && Auth::user()->isAdmin();
    }

    public static function canCreate(): bool
    {
        return Auth::check() && Auth::user()->isAdmin();
    }

    public static function canEdit(Model $record): bool
    {
        return Auth::check() && Auth::user()->isAdmin();
    }

    public static function canDelete(Model $record): bool
    {
        return Auth::check() && Auth::user()->isAdmin();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('status')
                ->label('Moderation Status')
                ->options([
                    Review::STATUS_PENDING => 'Pending Moderation',
                    Review::STATUS_PUBLISHED => 'Published',
                    Review::STATUS_REJECTED => 'Rejected',
                    Review::STATUS_HIDDEN => 'Hidden',
                    Review::STATUS_FLAGGED => 'Flagged for Abuse',
                ])
                ->required(),

            TextInput::make('rating')
                ->numeric()
                ->minValue(1)
                ->maxValue(5)
                ->required(),

            TextInput::make('title')->required(),

            Textarea::make('body')->rows(3)->required(),

            Textarea::make('moderation_reason')
                ->label('Moderation Notes')
                ->rows(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('subject_type')
                    ->label('Subject')
                    ->formatStateUsing(fn ($state, $record) => class_basename($state).' #'.$record->subject_id),
                TextColumn::make('user.name')
                    ->label('Reviewer')
                    ->searchable(),
                TextColumn::make('rating')
                    ->badge()
                    ->color(fn (int $state): string => $state >= 4 ? 'success' : ($state <= 2 ? 'danger' : 'warning')),
                TextColumn::make('title')->searchable()->limit(30),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Review::STATUS_PUBLISHED => 'success',
                        Review::STATUS_PENDING => 'warning',
                        Review::STATUS_FLAGGED => 'danger',
                        Review::STATUS_REJECTED => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('submitted_at')->dateTime()->sortable(),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReviews::route('/'),
            'create' => CreateReview::route('/create'),
            'edit' => EditReview::route('/{record}/edit'),
        ];
    }
}
