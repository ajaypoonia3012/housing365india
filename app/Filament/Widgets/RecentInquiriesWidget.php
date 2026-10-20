<?php

namespace App\Filament\Widgets;

use App\Models\Inquiry;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentInquiriesWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Inquiry::query()->latest()->limit(10)
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
                    ->label('Email')
                    ->copyable()
                    ->copyMessage('Email copied')
                    ->icon('heroicon-m-envelope'),
                TextColumn::make('phone')
                    ->label('Phone')
                    ->copyable()
                    ->copyMessage('Phone copied')
                    ->icon('heroicon-m-phone'),
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
            ->recordActions([
                Action::make('whatsapp')
                    ->label('WhatsApp')
                    ->icon('heroicon-m-chat-bubble-left-ellipsis')
                    ->color('success')
                    ->url(function (Inquiry $record): string {
                        $rawPhone = preg_replace('/[^0-9]/', '', (string) $record->phone);
                        if (strlen($rawPhone) === 10) {
                            $rawPhone = '91' . $rawPhone;
                        }
                        $msg = urlencode("Hi {$record->name}, thank you for contacting Housing365 India regarding your inquiry. How can we assist you today?");
                        return "https://wa.me/{$rawPhone}?text={$msg}";
                    }, shouldOpenInNewTab: true),
                Action::make('mark_contacted')
                    ->label('Mark Contacted')
                    ->icon('heroicon-m-check')
                    ->color('info')
                    ->visible(fn (Inquiry $record): bool => strtolower((string) $record->status) === 'new')
                    ->action(function (Inquiry $record): void {
                        $record->update(['status' => 'contacted']);
                        Notification::make()
                            ->title('Inquiry updated to Contacted')
                            ->success()
                            ->send();
                    }),
                ViewAction::make(),
                DeleteAction::make(),
            ])
            ->paginated(false);
    }
}
