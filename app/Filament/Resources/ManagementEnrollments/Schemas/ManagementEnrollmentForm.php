<?php

namespace App\Filament\Resources\ManagementEnrollments\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ManagementEnrollmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('agreement_reference')
                    ->label('Agreement Reference')
                    ->disabled()
                    ->dehydrated(false)
                    ->placeholder('Auto-generated (e.g. PMA-202610-ABCD)'),
                Select::make('user_id')
                    ->label('Property Owner')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),
                Select::make('property_id')
                    ->label('Property')
                    ->relationship('property', 'title')
                    ->searchable()
                    ->required(),
                Select::make('management_plan_id')
                    ->label('Management Plan')
                    ->relationship('plan', 'name')
                    ->required(),
                Select::make('status')
                    ->label('Enrollment Status')
                    ->options([
                        'REQUESTED' => 'REQUESTED',
                        'AGREEMENT_PENDING' => 'AGREEMENT_PENDING',
                        'ACTIVE' => 'ACTIVE',
                        'PAUSED' => 'PAUSED',
                        'CANCELLED' => 'CANCELLED',
                    ])
                    ->default('REQUESTED')
                    ->required(),
                DatePicker::make('start_date')
                    ->label('Coverage Start Date')
                    ->nullable(),
                DatePicker::make('end_date')
                    ->label('End Date / Expiry')
                    ->nullable(),
                Textarea::make('agreement_terms')
                    ->label('Agreed Scope & Special Notes')
                    ->nullable()
                    ->columnSpanFull(),
                Textarea::make('cancellation_reason')
                    ->label('Cancellation Reason (If Applicable)')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }
}
