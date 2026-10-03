<?php

namespace App\Filament\Resources\ServiceRequests\Schemas;

use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ServiceRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('request_number')
                    ->label('Request Number')
                    ->disabled()
                    ->dehydrated(false)
                    ->placeholder('Auto-generated (e.g. SR-202610-ABCD)'),
                Select::make('user_id')
                    ->label('Requesting Customer / Owner / Tenant')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),
                Select::make('service_category_id')
                    ->label('Care Service Category')
                    ->relationship('category', 'name')
                    ->required(),
                Select::make('property_id')
                    ->label('Property (Optional)')
                    ->relationship('property', 'title')
                    ->searchable()
                    ->nullable(),
                Select::make('assigned_vendor_id')
                    ->label('Assigned Partner Vendor')
                    ->options(fn () => User::whereHas('roles', fn ($q) => $q->where('name', 'vendor'))->pluck('name', 'id'))
                    ->searchable()
                    ->nullable(),
                TextInput::make('title')
                    ->label('Request Title')
                    ->nullable(),
                Select::make('priority')
                    ->label('Priority')
                    ->options([
                        'low' => 'Low',
                        'normal' => 'Normal',
                        'high' => 'High',
                        'emergency' => 'Emergency 24/7',
                    ])
                    ->default('normal')
                    ->required(),
                Select::make('status')
                    ->label('Lifecycle Status')
                    ->options([
                        'SUBMITTED' => 'SUBMITTED',
                        'TRIAGED' => 'TRIAGED',
                        'VENDOR_ASSIGNED' => 'VENDOR_ASSIGNED',
                        'QUOTE_PENDING' => 'QUOTE_PENDING',
                        'AWAITING_APPROVAL' => 'AWAITING_APPROVAL',
                        'SCHEDULED' => 'SCHEDULED',
                        'IN_PROGRESS' => 'IN_PROGRESS',
                        'QC_PENDING' => 'QC_PENDING',
                        'QC_COMPLETED' => 'QC_COMPLETED',
                        'CLOSED' => 'CLOSED',
                        'CANCELLED' => 'CANCELLED',
                    ])
                    ->default('SUBMITTED')
                    ->required(),
                DatePicker::make('preferred_date')
                    ->label('Preferred Date')
                    ->nullable(),
                TextInput::make('preferred_time_window')
                    ->label('Preferred Time Window')
                    ->placeholder('e.g. morning, afternoon, evening')
                    ->nullable(),
                Textarea::make('description')
                    ->label('Customer Problem Description')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('admin_notes')
                    ->label('Internal Operations / Triage Notes (Staff Only)')
                    ->nullable()
                    ->columnSpanFull(),
                Textarea::make('qc_notes')
                    ->label('Quality Control Verification Notes')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }
}
