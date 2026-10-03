<?php

namespace App\Filament\Widgets;

use App\Models\Inquiry;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentInquiriesWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Inquiry::query()->latest()->limit(8)
            )
            ->heading('Recent Customer Inquiries & Leads')
            ->description('Live inbound requests received from website landing pages and pricing forms.')
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->weight('bold')
                    ->searchable(),
                TextColumn::make('service_type')
                    ->label('Service')
                    ->badge()
                    ->color('info'),
                TextColumn::make('email')
                    ->label('Email'),
                TextColumn::make('phone')
                    ->label('Phone'),
                TextColumn::make('subject')
                    ->label('Subject')
                    ->limit(25),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match (strtolower($state)) {
                        'new' => 'warning',
                        'contacted' => 'info',
                        'closed' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Received')
                    ->since()
                    ->sortable(),
            ])
            ->paginated(false);
    }
}
