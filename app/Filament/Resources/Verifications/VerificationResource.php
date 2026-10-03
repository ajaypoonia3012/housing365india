<?php

namespace App\Filament\Resources\Verifications;

use App\Filament\Resources\Verifications\Pages\CreateVerification;
use App\Filament\Resources\Verifications\Pages\EditVerification;
use App\Filament\Resources\Verifications\Pages\ListVerifications;
use App\Models\Verification;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
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

class VerificationResource extends Resource
{
    protected static ?string $model = Verification::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ShieldCheck;

    protected static \UnitEnum|string|null $navigationGroup = 'Trust & Verification';

    protected static ?string $navigationLabel = 'Verifications';

    protected static ?int $navigationSort = 1;

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
            Select::make('verification_type_code')
                ->label('Verification Type')
                ->options([
                    'OWNER_IDENTITY' => 'Owner Identity',
                    'OWNER_CONTACT' => 'Owner Contact',
                    'PROPERTY_OWNERSHIP' => 'Property Ownership',
                    'PROPERTY_DOCUMENT' => 'Property Document',
                    'BROKER_IDENTITY' => 'Broker Identity',
                    'BROKER_LICENSE' => 'Broker License (RERA)',
                    'BUILDER_IDENTITY' => 'Builder Identity',
                    'BUILDER_RERA' => 'Builder Project RERA',
                    'PG_OPERATOR_IDENTITY' => 'PG Operator Identity',
                    'PG_PROPERTY' => 'PG Property Space',
                    'COWORKING_OPERATOR' => 'Coworking Operator',
                    'VENDOR_IDENTITY' => 'Care Vendor Identity',
                    'VENDOR_BUSINESS' => 'Care Vendor Business',
                ])
                ->required(),

            Select::make('status')
                ->label('Status')
                ->options([
                    Verification::STATUS_PENDING => 'Pending Review',
                    Verification::STATUS_UNDER_REVIEW => 'Under Review',
                    Verification::STATUS_VERIFIED => 'Verified / Approved',
                    Verification::STATUS_REJECTED => 'Rejected',
                    Verification::STATUS_SUSPENDED => 'Suspended',
                    Verification::STATUS_EXPIRED => 'Expired',
                ])
                ->required(),

            TextInput::make('subject_type')
                ->label('Subject Class')
                ->required(),

            TextInput::make('subject_id')
                ->label('Subject ID')
                ->numeric()
                ->required(),

            Select::make('user_id')
                ->label('Submitted By User')
                ->relationship('user', 'name')
                ->searchable(),

            DateTimePicker::make('submitted_at')
                ->label('Submitted At'),

            DateTimePicker::make('expires_at')
                ->label('Expires At'),

            Textarea::make('rejection_reason')
                ->label('Rejection Reason (if rejected)')
                ->rows(2),

            Textarea::make('internal_notes')
                ->label('Internal Compliance Notes')
                ->rows(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('verification_type_code')
                    ->label('Type')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('subject_type')
                    ->label('Subject')
                    ->formatStateUsing(fn ($state, $record) => class_basename($state).' #'.$record->subject_id),
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Verification::STATUS_VERIFIED => 'success',
                        Verification::STATUS_PENDING => 'warning',
                        Verification::STATUS_UNDER_REVIEW => 'info',
                        Verification::STATUS_REJECTED => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('submitted_at')->dateTime()->sortable(),
                TextColumn::make('expires_at')->dateTime()->sortable(),
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
            'index' => ListVerifications::route('/'),
            'create' => CreateVerification::route('/create'),
            'edit' => EditVerification::route('/{record}/edit'),
        ];
    }
}
