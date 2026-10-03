<?php

namespace App\Filament\Resources\RiskFlags;

use App\Filament\Resources\RiskFlags\Pages\CreateRiskFlag;
use App\Filament\Resources\RiskFlags\Pages\EditRiskFlag;
use App\Filament\Resources\RiskFlags\Pages\ListRiskFlags;
use App\Models\RiskFlag;
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

class RiskFlagResource extends Resource
{
    protected static ?string $model = RiskFlag::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ExclamationTriangle;

    protected static \UnitEnum|string|null $navigationGroup = 'Trust & Verification';

    protected static ?string $navigationLabel = 'Risk & Fraud Flags';

    protected static ?int $navigationSort = 4;

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
            TextInput::make('flag_type')->required(),

            Select::make('severity')
                ->options([
                    RiskFlag::SEVERITY_LOW => 'Low',
                    RiskFlag::SEVERITY_MEDIUM => 'Medium',
                    RiskFlag::SEVERITY_HIGH => 'High',
                    RiskFlag::SEVERITY_CRITICAL => 'Critical',
                ])
                ->required(),

            Select::make('status')
                ->options([
                    RiskFlag::STATUS_ACTIVE => 'Active',
                    RiskFlag::STATUS_RESOLVED => 'Resolved',
                    RiskFlag::STATUS_DISMISSED => 'Dismissed',
                ])
                ->required(),

            Textarea::make('resolution_notes')->rows(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('flag_type')->badge()->sortable()->searchable(),
                TextColumn::make('subject_type')
                    ->label('Subject')
                    ->formatStateUsing(fn ($state, $record) => class_basename($state).' #'.$record->subject_id),
                TextColumn::make('severity')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        RiskFlag::SEVERITY_CRITICAL, RiskFlag::SEVERITY_HIGH => 'danger',
                        RiskFlag::SEVERITY_MEDIUM => 'warning',
                        default => 'info',
                    }),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        RiskFlag::STATUS_ACTIVE => 'danger',
                        RiskFlag::STATUS_RESOLVED => 'success',
                        default => 'gray',
                    }),
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
            'index' => ListRiskFlags::route('/'),
            'create' => CreateRiskFlag::route('/create'),
            'edit' => EditRiskFlag::route('/{record}/edit'),
        ];
    }
}
