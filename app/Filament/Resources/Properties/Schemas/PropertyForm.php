<?php

namespace App\Filament\Resources\Properties\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PropertyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Listing Title')
                    ->placeholder('e.g. Luxury 3 BHK High-Rise Apartment in Malviya Nagar')
                    ->required()
                    ->maxLength(180)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set, string $operation) {
                        if ($operation === 'create' || empty($state)) {
                            $set('slug', \Illuminate\Support\Str::slug($state));
                        }
                    }),

                TextInput::make('slug')
                    ->label('URL Slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->helperText('Auto-generated from listing title. Used in public URLs.'),

                Select::make('purpose')
                    ->label('Listing Purpose')
                    ->options([
                        'rent' => 'Residential Rent',
                        'sale' => 'Residential Sale',
                        'commercial_rent' => 'Commercial Rent',
                        'commercial_sale' => 'Commercial Sale',
                        'land_sale' => 'Land / Plots Sale',
                        'pg' => 'PG (Paying Guest)',
                        'coliving' => 'Co-Living',
                        'coworking' => 'Coworking Space',
                        'project' => 'Builder Project',
                    ])
                    ->default('rent')
                    ->required(),

                Select::make('category')
                    ->label('Property Category')
                    ->options([
                        'residential' => 'Residential (Apartment, Villa, House, Studio)',
                        'commercial' => 'Commercial (Office, Shop, Showroom, Warehouse)',
                        'plot_land' => 'Plots & Land (Residential, Farm, Industrial)',
                        'pg_coliving' => 'PG & Co-Living',
                    ])
                    ->default('residential')
                    ->required(),

                Select::make('verification_level')
                    ->label('Verification Tier')
                    ->options([
                        'unverified' => 'Unverified (Level 0)',
                        'submitted' => 'Submitted (Level 1)',
                        'basic_verified' => 'Basic Verified (Level 2)',
                        'document_verified' => 'Document Verified (Level 3)',
                        'housing365_verified' => 'Housing365 Verified (Level 4)',
                    ])
                    ->default('unverified')
                    ->required(),

                TextInput::make('property_type')
                    ->label('Property Type')
                    ->placeholder('e.g. Flat / Apartment, Builder Floor, Penthouse, Commercial Office')
                    ->required()
                    ->default('Flat / Apartment'),

                TextInput::make('city')
                    ->label('City')
                    ->placeholder('e.g. Jaipur, Bengaluru, Mumbai, Gurugram')
                    ->required()
                    ->default('Jaipur'),

                TextInput::make('locality')
                    ->label('Locality / Neighborhood')
                    ->placeholder('e.g. Malviya Nagar, HSR Layout, Bandra West')
                    ->required(),

                TextInput::make('state')
                    ->label('State / Province')
                    ->default('Rajasthan'),

                TextInput::make('country')
                    ->label('Country Code')
                    ->required()
                    ->default('IN'),

                TextInput::make('address')
                    ->label('Full Street Address / Landmark')
                    ->default(null),

                TextInput::make('postal_code')
                    ->label('PIN / Postal Code')
                    ->default(null),

                TextInput::make('price')
                    ->label('Numeric Price (in INR)')
                    ->required()
                    ->numeric()
                    ->prefix('₹'),

                TextInput::make('price_display')
                    ->label('Formatted Display Price')
                    ->placeholder('e.g. ₹35,000/mo or ₹1.85 Cr or ₹75 Lakhs')
                    ->helperText('Shown prominently on marketplace cards'),

                Select::make('price_frequency')
                    ->label('Price Frequency')
                    ->options([
                        'month' => 'Per Month (/mo)',
                        'total' => 'Total Price (Sale)',
                        'year' => 'Per Year (/yr)',
                    ])
                    ->default('month')
                    ->required(),

                TextInput::make('sqft')
                    ->label('Super Built-Up / Carpet Area')
                    ->numeric()
                    ->required()
                    ->default(1200),

                Select::make('area_unit')
                    ->label('Area Measurement Unit')
                    ->options([
                        'sqft' => 'Sq. Ft. (Square Feet)',
                        'sq.yards' => 'Sq. Yards (Gaj)',
                        'acres' => 'Acres',
                        'bigha' => 'Bigha',
                    ])
                    ->default('sqft'),

                TextInput::make('bedrooms')
                    ->label('Bedrooms (BHK)')
                    ->required()
                    ->numeric()
                    ->default(2),

                TextInput::make('bathrooms')
                    ->label('Bathrooms')
                    ->required()
                    ->numeric()
                    ->default(2.0),

                TextInput::make('balconies')
                    ->label('Balconies')
                    ->numeric()
                    ->default(1),

                TextInput::make('floor_no')
                    ->label('Property Floor Number')
                    ->placeholder('e.g. Ground, 4th Floor')
                    ->default('Ground'),

                TextInput::make('total_floors')
                    ->label('Total Floors in Building')
                    ->placeholder('e.g. 4, 12, 24')
                    ->default('4'),

                Select::make('furnishing')
                    ->label('Furnishing Status')
                    ->options([
                        'Fully Furnished' => 'Fully Furnished',
                        'Semi-Furnished' => 'Semi-Furnished',
                        'Unfurnished' => 'Unfurnished',
                        'Bare Shell' => 'Bare Shell (Commercial)',
                    ])
                    ->default('Semi-Furnished'),

                Select::make('posting_as')
                    ->label('Posting Type')
                    ->options([
                        'Full House' => 'Full House / Independent Unit',
                        'On sharing basis' => 'On sharing basis / Co-living',
                        'Full Floor' => 'Full Floor',
                    ])
                    ->default('Full House'),

                TextInput::make('maintenance')
                    ->label('Maintenance Charges')
                    ->placeholder('e.g. ₹2,000/mo or Included'),

                Select::make('posted_by')
                    ->label('Listing Authority')
                    ->options([
                        'Owner' => 'Direct Property Owner',
                        'Builder' => 'Builder / Developer',
                        'Agent' => 'Certified Agent / Consultant',
                    ])
                    ->default('Owner'),

                TextInput::make('seller_name')
                    ->label('Contact Person / Owner Name')
                    ->placeholder('e.g. Rajesh Sharma'),

                TextInput::make('seller_phone')
                    ->label('Owner Contact Phone (WhatsApp)')
                    ->placeholder('e.g. +91 98765 43210'),

                TextInput::make('seller_email')
                    ->label('Owner Contact Email')
                    ->email()
                    ->placeholder('e.g. owner@housing365.in'),

                Select::make('status')
                    ->label('Marketplace Status')
                    ->options([
                        'Available' => 'Available (Active Listing)',
                        'Under Offer' => 'Under Offer / In Negotiation',
                        'Rented' => 'Rented Out',
                        'Sold' => 'Sold Out',
                        'Archived' => 'Archived',
                    ])
                    ->required()
                    ->default('Available'),

                Toggle::make('featured')
                    ->label('Mark as Featured on Homepage')
                    ->default(false),

                Toggle::make('is_verified')
                    ->label('Verified by Housing365 Inspection Team')
                    ->default(true),

                TextInput::make('image')
                    ->label('Cover Photo URL (High-Res)')
                    ->placeholder('e.g. https://images.unsplash.com/... or /storage/...'),

                TextInput::make('video_url')
                    ->label('Video Walkthrough URL (YouTube / MP4)')
                    ->placeholder('e.g. https://www.youtube.com/watch?v=...'),

                Textarea::make('description')
                    ->label('Property Overview & Highlights')
                    ->placeholder('Detailed description of the property, society amenities, proximity to tech parks and metro...')
                    ->rows(5)
                    ->columnSpanFull(),
            ]);
    }
}
