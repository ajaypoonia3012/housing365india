<?php

namespace App\Filament\Resources\Inquiries\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class InquiriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Lead Name')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('service_type')
                    ->label('Type / Category')
                    ->badge()
                    ->color('info')
                    ->searchable(),

                TextColumn::make('phone')
                    ->label('Phone / WhatsApp')
                    ->copyable()
                    ->icon('heroicon-m-phone')
                    ->searchable(),

                TextColumn::make('email')
                    ->label('Email Address')
                    ->copyable()
                    ->icon('heroicon-m-envelope')
                    ->searchable(),

                TextColumn::make('subject')
                    ->label('Subject / Summary')
                    ->limit(35)
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match (strtolower($state)) {
                        'new' => 'warning',
                        'contacted' => 'info',
                        'closed' => 'success',
                        'spam' => 'gray',
                        default => 'primary',
                    })
                    ->formatStateUsing(fn ($state) => ucfirst($state)),

                TextColumn::make('created_at')
                    ->label('Received')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'new' => 'New / Uncontacted',
                        'contacted' => 'Contacted / Follow Up',
                        'closed' => 'Closed / Converted',
                        'spam' => 'Spam / Invalid',
                    ]),
            ])
            ->recordActions([
                \Filament\Actions\Action::make('reply_email')
                    ->label('Reply Email')
                    ->icon('heroicon-m-envelope')
                    ->color('primary')
                    ->modalHeading('Reply to Inquiry via Email')
                    ->modalSubmitActionLabel('Send Email')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('email')
                            ->label('Recipient')
                            ->default(fn (\App\Models\Inquiry $record) => $record->email)
                            ->disabled(),

                        \Filament\Forms\Components\TextInput::make('subject')
                            ->label('Email Subject')
                            ->required()
                            ->default(fn (\App\Models\Inquiry $record) => "Regarding your inquiry: {$record->subject} — Housing365 India"),

                        \Filament\Forms\Components\Textarea::make('message')
                            ->label('Message Body')
                            ->required()
                            ->rows(5)
                            ->default(fn (\App\Models\Inquiry $record) => "Dear {$record->name},\n\nThank you for reaching out to Housing365 India. We have received your inquiry and our property specialist will be happy to assist you."),
                    ])
                    ->action(function (\App\Models\Inquiry $record, array $data): void {
                        try {
                            \Illuminate\Support\Facades\Mail::raw($data['message'], function ($mail) use ($record, $data) {
                                $mail->to($record->email)
                                    ->subject($data['subject']);
                            });

                            $record->update(['status' => 'contacted']);

                            \Filament\Notifications\Notification::make()
                                ->title('Email Reply Sent')
                                ->body("Dispatched to {$record->email}")
                                ->success()
                                ->send();
                        } catch (\Throwable $e) {
                            \Filament\Notifications\Notification::make()
                                ->title('Email Failed')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),

                \Filament\Actions\Action::make('whatsapp')
                    ->label('WhatsApp')
                    ->icon('heroicon-m-chat-bubble-left-ellipsis')
                    ->color('success')
                    ->url(function (\App\Models\Inquiry $record): string {
                        $rawPhone = preg_replace('/[^0-9]/', '', (string) $record->phone);
                        if (strlen($rawPhone) === 10) {
                            $rawPhone = '91'.$rawPhone;
                        }
                        $msg = urlencode("Hi {$record->name}, thank you for contacting Housing365 India regarding {$record->subject}. How can we assist you today?");
                        return "https://wa.me/{$rawPhone}?text={$msg}";
                    }, shouldOpenInNewTab: true),

                \Filament\Actions\Action::make('view_crm_lead')
                    ->label('View in CRM')
                    ->icon('heroicon-m-user-group')
                    ->color('info')
                    ->url(function (\App\Models\Inquiry $record): string {
                        $lead = $record->lead ?: \App\Models\Lead::where('inquiry_id', $record->id)->first();
                        if (! $lead) {
                            $lead = app(\App\Services\LeadManagementService::class)->ingestFromInquiry($record);
                        }
                        return url("/admin/leads/{$lead->id}/edit");
                    }),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
