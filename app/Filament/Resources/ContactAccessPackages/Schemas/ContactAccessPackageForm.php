<?php

namespace App\Filament\Resources\ContactAccessPackages\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ContactAccessPackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Package Name')
                    ->required()
                    ->placeholder('e.g. Starter Contact Pass')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set, string $operation) {
                        if ($operation === 'create' || empty($state)) {
                            $set('slug', \Illuminate\Support\Str::slug($state));
                        }
                    }),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->placeholder('e.g. starter-pass')
                    ->helperText('Auto-generated from name.'),
                TextInput::make('credits')
                    ->label('Contact Credits (Number of Unlocks)')
                    ->required()
                    ->numeric()
                    ->default(5),
                TextInput::make('price')
                    ->label('Price (INR)')
                    ->required()
                    ->numeric()
                    ->prefix('₹')
                    ->default(499.00),
                TextInput::make('currency')
                    ->label('Currency')
                    ->default('INR')
                    ->required(),
                TextInput::make('validity_days')
                    ->label('Validity (Days)')
                    ->numeric()
                    ->default(30)
                    ->required(),
                TextInput::make('badge_text')
                    ->label('Badge / Ribbon Text')
                    ->placeholder('e.g. Most Popular, Best Value')
                    ->nullable(),
                TextInput::make('sort_order')
                    ->label('Display Order')
                    ->numeric()
                    ->default(1),
                Toggle::make('is_active')
                    ->label('Active / Available for Purchase')
                    ->default(true),
                Textarea::make('description')
                    ->label('Description')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }
}
