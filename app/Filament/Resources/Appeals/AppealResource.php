<?php

namespace App\Filament\Resources\Appeals;

use App\Filament\Resources\Appeals\Pages\CreateAppeal;
use App\Filament\Resources\Appeals\Pages\EditAppeal;
use App\Filament\Resources\Appeals\Pages\ListAppeals;
use App\Models\Appeal;
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

class AppealResource extends Resource
{
    protected static ?string $model = Appeal::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Scale;

    protected static \UnitEnum|string|null $navigationGroup = 'Trust & Verification';

    protected static ?string $navigationLabel = 'Appeals';

    protected static ?int $navigationSort = 6;

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
                ->options([
                    Appeal::STATUS_SUBMITTED => 'Submitted',
                    Appeal::STATUS_UNDER_REVIEW => 'Under Review',
                    Appeal::STATUS_APPROVED => 'Approved (Overturned)',
                    Appeal::STATUS_REJECTED => 'Rejected (Upheld)',
                    Appeal::STATUS_CLOSED => 'Closed',
                ])
                ->required(),

            TextInput::make('appealable_type')->disabled(),
            TextInput::make('appealable_id')->disabled(),

            Textarea::make('reason')->disabled()->rows(3),

            Textarea::make('admin_response')
                ->label('Compliance Resolution Notes')
                ->rows(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('user.name')->searchable(),
                TextColumn::make('appealable_type')
                    ->label('Target')
                    ->formatStateUsing(fn ($state, $record) => class_basename($state).' #'.$record->appealable_id),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Appeal::STATUS_APPROVED => 'success',
                        Appeal::STATUS_SUBMITTED => 'warning',
                        Appeal::STATUS_UNDER_REVIEW => 'info',
                        Appeal::STATUS_REJECTED => 'danger',
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
            'index' => ListAppeals::route('/'),
            'create' => CreateAppeal::route('/create'),
            'edit' => EditAppeal::route('/{record}/edit'),
        ];
    }
}
