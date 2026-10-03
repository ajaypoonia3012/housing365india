<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Internal Role Identifier')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50)
                    ->helperText('Unique lowercase identifier, e.g. broker, builder, pg_operator'),

                TextInput::make('display_name')
                    ->label('Display Name')
                    ->required()
                    ->maxLength(100)
                    ->placeholder('e.g. Real Estate Broker'),

                Textarea::make('description')
                    ->label('Description & Scope')
                    ->rows(3)
                    ->columnSpanFull(),

                Toggle::make('is_active')
                    ->label('Role Active in System')
                    ->default(true)
                    ->helperText('When disabled, users cannot switch to or register for this persona.'),
            ]);
    }
}
