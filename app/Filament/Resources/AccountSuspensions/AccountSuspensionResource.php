<?php

namespace App\Filament\Resources\AccountSuspensions;

use App\Filament\Resources\AccountSuspensions\Pages\CreateAccountSuspension;
use App\Filament\Resources\AccountSuspensions\Pages\EditAccountSuspension;
use App\Filament\Resources\AccountSuspensions\Pages\ListAccountSuspensions;
use App\Models\AccountSuspension;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AccountSuspensionResource extends Resource
{
    protected static ?string $model = AccountSuspension::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::NoSymbol;

    protected static \UnitEnum|string|null $navigationGroup = 'Trust & Verification';

    protected static ?string $navigationLabel = 'Account Suspensions';

    protected static ?int $navigationSort = 5;

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
            Select::make('user_id')
                ->relationship('user', 'name')
                ->searchable()
                ->required(),

            Select::make('suspension_type')
                ->options([
                    AccountSuspension::TYPE_TEMPORARY => 'Temporary Suspension',
                    AccountSuspension::TYPE_PERMANENT => 'Permanent Restriction',
                ])
                ->required(),

            Textarea::make('reason')->required()->rows(3),

            DateTimePicker::make('starts_at')->required()->default(now()),
            DateTimePicker::make('ends_at'),

            Toggle::make('is_active')->default(true),

            Select::make('appeal_status')
                ->options([
                    AccountSuspension::APPEAL_NONE => 'No Appeal',
                    AccountSuspension::APPEAL_APPEALED => 'Appeal Submitted',
                    AccountSuspension::APPEAL_REJECTED => 'Appeal Rejected',
                    AccountSuspension::APPEAL_OVERTURNED => 'Appeal Overturned (Reinstated)',
                ])
                ->default(AccountSuspension::APPEAL_NONE),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('user.name')->searchable()->sortable(),
                TextColumn::make('suspension_type')->badge(),
                IconColumn::make('is_active')->boolean(),
                TextColumn::make('appeal_status')->badge(),
                TextColumn::make('starts_at')->dateTime(),
                TextColumn::make('ends_at')->dateTime(),
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
            'index' => ListAccountSuspensions::route('/'),
            'create' => CreateAccountSuspension::route('/create'),
            'edit' => EditAccountSuspension::route('/{record}/edit'),
        ];
    }
}
