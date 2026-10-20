<?php

namespace App\Filament\Resources\CareServiceCategories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CareServiceCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Category Name')
                    ->required()
                    ->placeholder('e.g. Plumbing, Electrical, AC / HVAC')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set, string $operation) {
                        if ($operation === 'create' || empty($state)) {
                            $set('slug', \Illuminate\Support\Str::slug($state));
                        }
                    }),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->placeholder('e.g. plumbing, electrical')
                    ->helperText('Auto-generated from name.'),
                TextInput::make('icon')
                    ->label('FontAwesome Icon Class')
                    ->default('fa-solid fa-wrench')
                    ->placeholder('fa-solid fa-wrench'),
                Select::make('pricing_type')
                    ->label('Pricing Model')
                    ->options([
                        'fixed' => 'Fixed Base Price',
                        'quote_based' => 'Custom Quote Based',
                    ])
                    ->default('fixed')
                    ->required(),
                TextInput::make('base_price')
                    ->label('Base Starting Price (INR)')
                    ->numeric()
                    ->prefix('₹')
                    ->nullable(),
                TextInput::make('sort_order')
                    ->label('Display Order')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_emergency_available')
                    ->label('Available for 24/7 Emergency Dispatch')
                    ->default(false),
                Toggle::make('is_active')
                    ->label('Active / Visible to Customers')
                    ->default(true),
                Textarea::make('description')
                    ->label('Service Description')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }
}
