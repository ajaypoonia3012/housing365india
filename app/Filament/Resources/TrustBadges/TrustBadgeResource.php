<?php

namespace App\Filament\Resources\TrustBadges;

use App\Filament\Resources\TrustBadges\Pages\CreateTrustBadge;
use App\Filament\Resources\TrustBadges\Pages\EditTrustBadge;
use App\Filament\Resources\TrustBadges\Pages\ListTrustBadges;
use App\Models\TrustBadge;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TrustBadgeResource extends Resource
{
    protected static ?string $model = TrustBadge::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CheckBadge;

    protected static \UnitEnum|string|null $navigationGroup = 'Trust & Verification';

    protected static ?string $navigationLabel = 'Trust Badges';

    protected static ?int $navigationSort = 2;

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
            TextInput::make('name')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(function ($state, callable $set, string $operation) {
                    if ($operation === 'create' || empty($state)) {
                        $set('slug', \Illuminate\Support\Str::slug($state));
                    }
                }),
            TextInput::make('slug')
                ->required()
                ->unique(ignoreRecord: true)
                ->helperText('Auto-generated from badge name.'),
            Textarea::make('description')->rows(2),
            Textarea::make('criteria')->rows(2),
            Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('slug')->badge()->sortable(),
                IconColumn::make('is_active')->boolean(),
                TextColumn::make('created_at')->dateTime()->sortable(),
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
            'index' => ListTrustBadges::route('/'),
            'create' => CreateTrustBadge::route('/create'),
            'edit' => EditTrustBadge::route('/{record}/edit'),
        ];
    }
}
