<?php

namespace App\Filament\Resources\PgRooms;

use App\Filament\Resources\PgRooms\Pages\EditPgRoom;
use App\Filament\Resources\PgRooms\Pages\ListPgRooms;
use App\Models\PgRoom;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PgRoomResource extends Resource
{
    protected static ?string $model = PgRoom::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::HomeModern;

    protected static \UnitEnum|string|null $navigationGroup = 'Tenant Lifecycle & Operations';

    protected static ?string $navigationLabel = 'PG Inventory (Rooms)';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('room_number')->required(),
            Select::make('room_type')
                ->options([
                    'single' => 'Single Occupancy',
                    'double' => 'Double Sharing',
                    'triple' => 'Triple Sharing',
                    'four_sharing' => 'Four Sharing',
                ])
                ->required(),
            Select::make('gender_preference')
                ->options([
                    'male' => 'Male',
                    'female' => 'Female',
                    'unisex' => 'Unisex / Any',
                ])
                ->required(),
            TextInput::make('monthly_rent_per_bed')->numeric()->required(),
            TextInput::make('deposit_per_bed')->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('room_number')->searchable(),
                TextColumn::make('property.title')->label('PG Property')->limit(25),
                TextColumn::make('room_type')->badge(),
                TextColumn::make('gender_preference')->badge(),
                TextColumn::make('monthly_rent_per_bed')->money('INR')->sortable(),
                TextColumn::make('status')->badge(),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPgRooms::route('/'),
            'edit' => EditPgRoom::route('/{record}/edit'),
        ];
    }
}
