<?php

namespace App\Filament\Resources\Inquiries\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class InquiryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Contact Name')
                    ->required()
                    ->maxLength(150),

                TextInput::make('email')
                    ->label('Email Address')
                    ->email()
                    ->required()
                    ->maxLength(150),

                TextInput::make('phone')
                    ->label('Phone Number / WhatsApp')
                    ->tel()
                    ->placeholder('+91 98765 43210')
                    ->maxLength(50),

                TextInput::make('service_type')
                    ->label('Lead / Service Category')
                    ->placeholder('e.g. General, Post Property Assistance, Prime Activation, Tenant Placement')
                    ->required()
                    ->default('General'),

                TextInput::make('subject')
                    ->label('Inquiry Subject')
                    ->placeholder('e.g. 3 BHK Rental in Jaipur')
                    ->maxLength(255),

                Select::make('status')
                    ->label('Follow-up Status')
                    ->options([
                        'new' => '🔴 New (Awaiting Call/Response)',
                        'contacted' => '🟡 Contacted (Follow-Up Scheduled)',
                        'closed' => '🟢 Closed / Successfully Converted',
                        'spam' => '⚪ Spam / Invalid Number',
                    ])
                    ->required()
                    ->default('new'),

                Textarea::make('message')
                    ->label('Customer Message / Requirement Details')
                    ->required()
                    ->rows(6)
                    ->columnSpanFull(),
            ]);
    }
}
