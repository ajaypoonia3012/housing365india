<?php

namespace App\Filament\Resources\TenantScreenings;

use App\Filament\Resources\TenantScreenings\Pages\EditTenantScreening;
use App\Filament\Resources\TenantScreenings\Pages\ListTenantScreenings;
use App\Models\TenantScreening;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TenantScreeningResource extends Resource
{
    protected static ?string $model = TenantScreening::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ShieldCheck;

    protected static \UnitEnum|string|null $navigationGroup = 'Tenant Lifecycle & Operations';

    protected static ?string $navigationLabel = 'Tenant Screenings';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('screening_number')
                ->disabled(),
            Select::make('status')
                ->options([
                    'CONSENT_REQUIRED' => 'Consent Required',
                    'CONSENTED' => 'Consented',
                    'DOCUMENTS_SUBMITTED' => 'Documents Submitted',
                    'UNDER_REVIEW' => 'Under Review',
                    'VERIFICATION_IN_PROGRESS' => 'Verification In Progress',
                    'VERIFIED' => 'Verified',
                    'PARTIALLY_VERIFIED' => 'Partially Verified',
                    'FAILED' => 'Failed',
                    'CANCELLED' => 'Cancelled',
                ])
                ->required(),
            Select::make('id_verification_status')
                ->options([
                    'PENDING' => 'Pending',
                    'IN_PROGRESS' => 'In Progress',
                    'VERIFIED' => 'Verified',
                    'FAILED' => 'Failed',
                ])
                ->required(),
            Textarea::make('reviewer_notes')
                ->rows(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('screening_number')->searchable()->sortable(),
                TextColumn::make('tenant.name')->label('Tenant')->searchable(),
                TextColumn::make('property.title')->label('Property')->limit(30),
                TextColumn::make('status')->badge()->sortable(),
                TextColumn::make('id_verification_status')->badge(),
                TextColumn::make('consent_granted_at')->dateTime()->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTenantScreenings::route('/'),
            'edit' => EditTenantScreening::route('/{record}/edit'),
        ];
    }
}
