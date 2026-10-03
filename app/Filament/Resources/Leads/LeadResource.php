<?php

namespace App\Filament\Resources\Leads;

use App\Filament\Resources\Leads\Pages\CreateLead;
use App\Filament\Resources\Leads\Pages\EditLead;
use App\Filament\Resources\Leads\Pages\ListLeads;
use App\Models\Lead;
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

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserGroup;

    protected static \UnitEnum|string|null $navigationGroup = 'Lead Operations';

    protected static ?string $navigationLabel = 'Unified Leads';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Contact Name')
                    ->required(),
                TextInput::make('phone')
                    ->label('Phone Number')
                    ->tel(),
                TextInput::make('email')
                    ->label('Email Address')
                    ->email(),
                Select::make('lead_type')
                    ->label('Lead Type')
                    ->options([
                        'residential_rental' => 'Residential Rental',
                        'property_sale' => 'Property Sale',
                        'land' => 'Land / Plot',
                        'commercial' => 'Commercial',
                        'pg' => 'PG',
                        'coliving' => 'Co-living',
                        'coworking' => 'Coworking',
                        'builder_project' => 'Builder Project',
                        'care_service' => 'Housing365 Care',
                        'property_management' => 'Property Management',
                    ])
                    ->required(),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'NEW' => 'NEW',
                        'ASSIGNED' => 'ASSIGNED',
                        'CONTACTED' => 'CONTACTED',
                        'QUALIFIED' => 'QUALIFIED',
                        'VIEWING_REQUESTED' => 'VIEWING_REQUESTED',
                        'VIEWING_SCHEDULED' => 'VIEWING_SCHEDULED',
                        'VIEWING_COMPLETED' => 'VIEWING_COMPLETED',
                        'APPLICATION_STARTED' => 'APPLICATION_STARTED',
                        'APPLICATION_SUBMITTED' => 'APPLICATION_SUBMITTED',
                        'CONVERTED' => 'CONVERTED',
                        'LOST' => 'LOST',
                        'CLOSED' => 'CLOSED',
                    ])
                    ->required(),
                Select::make('priority')
                    ->label('Priority')
                    ->options([
                        'low' => 'Low',
                        'normal' => 'Normal',
                        'high' => 'High',
                        'urgent' => 'Urgent',
                    ])
                    ->default('normal'),
                Select::make('assigned_user_id')
                    ->label('Assigned User')
                    ->relationship('assignedUser', 'name')
                    ->searchable()
                    ->nullable(),
                TextInput::make('preferred_city')
                    ->label('Target City'),
                TextInput::make('preferred_locality')
                    ->label('Target Locality'),
                Textarea::make('notes')
                    ->label('Lead Ingestion Notes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('lead_type')->badge()->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'NEW' => 'info',
                        'ASSIGNED', 'CONTACTED', 'QUALIFIED' => 'warning',
                        'VIEWING_SCHEDULED', 'VIEWING_COMPLETED' => 'primary',
                        'CONVERTED' => 'success',
                        'LOST', 'CLOSED' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('sla_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'achieved' => 'success',
                        'breached' => 'danger',
                        default => 'warning',
                    }),
                TextColumn::make('assignedUser.name')->label('Assigned To')->searchable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                //
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

    public static function getPages(): array
    {
        return [
            'index' => ListLeads::route('/'),
            'create' => CreateLead::route('/create'),
            'edit' => EditLead::route('/{record}/edit'),
        ];
    }
}
