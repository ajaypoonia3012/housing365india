<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Full Name')
                    ->required()
                    ->maxLength(120),

                TextInput::make('email')
                    ->label('Email Address')
                    ->email()
                    ->required()
                    ->maxLength(150),

                TextInput::make('phone')
                    ->label('Phone Number (WhatsApp)')
                    ->tel()
                    ->maxLength(20),

                Select::make('user_type')
                    ->label('Account Persona / Role')
                    ->options([
                        'tenant' => 'Tenant (Looking to Rent)',
                        'buyer'  => 'Buyer (Looking to Purchase)',
                        'owner'  => 'Property Owner / Landlord',
                        'admin'  => 'Platform Administrator',
                    ])
                    ->default('tenant')
                    ->required(),

                TextInput::make('city')
                    ->label('Primary City')
                    ->placeholder('e.g. Jaipur, Bengaluru, Mumbai')
                    ->default('Jaipur'),

                TextInput::make('password')
                    ->label('Account Password')
                    ->password()
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->helperText('Leave empty on edit to keep existing password'),

                Toggle::make('is_prime')
                    ->label('Housing365 Prime Activated')
                    ->default(false),

                Select::make('prime_plan')
                    ->label('Active Prime Package')
                    ->options([
                        'Prime Basic (Rent)' => 'Prime Basic (Rent) - 10 Contacts / 90 Days',
                        'Prime Pro (Rent)'   => 'Prime Pro (Rent) - 30 Contacts / 90 Days',
                        'Prime VIP'          => 'Prime VIP (Rent) - Unlimited / 180 Days',
                        'Prime Basic (Buy)'  => 'Prime Basic (Buy) - 10 Contacts / 180 Days',
                        'Prime Pro (Buy)'    => 'Prime Pro (Buy) - 30 Contacts / 180 Days',
                        'Prime VIP (Buy)'    => 'Prime VIP (Buy) - Unlimited / 365 Days',
                        'Free'               => 'Standard Free Account',
                    ])
                    ->nullable(),

                TextInput::make('prime_contacts_limit')
                    ->label('Total Owner Contacts Allotted')
                    ->numeric()
                    ->default(0),

                TextInput::make('unlocked_contacts_count')
                    ->label('Owner Contacts Used')
                    ->numeric()
                    ->default(0),

                DateTimePicker::make('prime_expires_at')
                    ->label('Prime Expiration Date')
                    ->nullable(),

                TextInput::make('preferred_localities')
                    ->label('Preferred Localities / Areas')
                    ->placeholder('e.g. Vaishali Nagar, Malviya Nagar')
                    ->columnSpanFull(),

                TextInput::make('preferred_bhk')
                    ->label('Preferred BHK / Configuration')
                    ->placeholder('e.g. 2 BHK, 3 BHK'),

                TextInput::make('preferred_budget')
                    ->label('Budget Range')
                    ->placeholder('e.g. ₹25,000 - ₹35,000/mo or ₹75 Lakhs'),
            ]);
    }
}
