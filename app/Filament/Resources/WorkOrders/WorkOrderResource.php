<?php

namespace App\Filament\Resources\WorkOrders;

use App\Filament\Resources\WorkOrders\Pages\EditWorkOrder;
use App\Filament\Resources\WorkOrders\Pages\ListWorkOrders;
use App\Models\WorkOrder;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WorkOrderResource extends Resource
{
    protected static ?string $model = WorkOrder::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::WrenchScrewdriver;

    protected static \UnitEnum|string|null $navigationGroup = 'Housing365 Care & Management';

    protected static ?string $navigationLabel = 'Work Orders';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('work_order_number')->disabled(),
            Select::make('status')
                ->options([
                    'CREATED' => 'Created',
                    'SCHEDULED' => 'Scheduled',
                    'VENDOR_EN_ROUTE' => 'Vendor En Route',
                    'IN_PROGRESS' => 'In Progress',
                    'WAITING_FOR_PARTS' => 'Waiting For Parts',
                    'COMPLETED' => 'Completed',
                    'QC_PENDING' => 'QC Pending',
                    'QC_APPROVED' => 'QC Approved',
                    'REWORK_REQUIRED' => 'Rework Required',
                    'CANCELLED' => 'Cancelled',
                    'CLOSED' => 'Closed',
                ])
                ->required(),
            Select::make('qc_status')
                ->options([
                    'PENDING' => 'Pending Review',
                    'APPROVED' => 'Approved',
                    'REWORK_REQUIRED' => 'Rework Required',
                    'REJECTED' => 'Rejected',
                ]),
            Textarea::make('qc_notes'),
            Textarea::make('work_notes'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('work_order_number')->label('Work Order #')->searchable()->sortable(),
                TextColumn::make('serviceRequest.request_number')->label('Request #')->searchable(),
                TextColumn::make('vendor.name')->label('Vendor')->searchable(),
                TextColumn::make('property.title')->label('Property')->limit(25),
                TextColumn::make('status')->badge()->sortable(),
                TextColumn::make('qc_status')->label('QC')->badge()->sortable(),
                TextColumn::make('scheduled_at')->dateTime('d M Y, h:i A')->sortable(),
                TextColumn::make('created_at')->dateTime('d M Y')->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWorkOrders::route('/'),
            'edit' => EditWorkOrder::route('/{record}/edit'),
        ];
    }
}
