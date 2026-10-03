<?php

namespace App\Filament\Resources\ManagementPlans\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ManagementPlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Plan Name')
                    ->required()
                    ->placeholder('e.g. Standard Property Management'),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->placeholder('e.g. standard-management'),
                Select::make('fee_type')
                    ->label('Fee Structure')
                    ->options([
                        'percentage' => 'Percentage of Monthly Rent',
                        'fixed' => 'Fixed Monthly Fee (INR)',
                    ])
                    ->default('percentage')
                    ->required(),
                TextInput::make('fee_percentage')
                    ->label('Fee Percentage (%)')
                    ->numeric()
                    ->suffix('%')
                    ->nullable(),
                TextInput::make('fixed_fee')
                    ->label('Fixed Monthly Fee (INR)')
                    ->numeric()
                    ->prefix('₹')
                    ->nullable(),
                TextInput::make('sort_order')
                    ->label('Display Order')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->label('Active / Available for Enrollment')
                    ->default(true),
                Textarea::make('description')
                    ->label('Plan Summary')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }
}
