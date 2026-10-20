<?php

namespace App\Filament\Resources\Leads;

use App\Filament\Resources\Leads\Pages\CreateLead;
use App\Filament\Resources\Leads\Pages\EditLead;
use App\Filament\Resources\Leads\Pages\ListLeads;
use App\Models\Lead;
use App\Models\LeadDocument;
use App\Services\LeadManagementService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserGroup;

    protected static \UnitEnum|string|null $navigationGroup = 'Lead Operations';

    protected static ?string $navigationLabel = 'Unified CRM & Leads';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Contact Name')
                    ->required()
                    ->maxLength(150),

                TextInput::make('phone')
                    ->label('Phone Number / WhatsApp')
                    ->tel()
                    ->placeholder('+91 98765 43210')
                    ->maxLength(50),

                TextInput::make('email')
                    ->label('Email Address')
                    ->email()
                    ->placeholder('client@example.com')
                    ->maxLength(150),

                Select::make('lead_type')
                    ->label('Requirement Category')
                    ->options([
                        'residential_rental' => 'Residential Rental (Tenant/Owner)',
                        'property_sale' => 'Property Buy / Sale',
                        'commercial' => 'Commercial (Office/Retail)',
                        'land' => 'Land / Plot',
                        'pg' => 'PG / Paying Guest',
                        'coliving' => 'Co-Living Accommodation',
                        'coworking' => 'Coworking Desk / Space',
                        'builder_project' => 'Builder Project Lease-Up',
                        'care_service' => 'Housing365 Care & Maintenance',
                        'property_management' => 'NRI / Full Property Management',
                        'general' => 'General Inquiry',
                    ])
                    ->default('residential_rental')
                    ->required(),

                Select::make('property_id')
                    ->label('Referenced Property Listing')
                    ->relationship('property', 'title')
                    ->searchable()
                    ->preload()
                    ->nullable(),

                Select::make('status')
                    ->label('CRM Pipeline Stage')
                    ->options(Lead::getStatusOptions())
                    ->default(Lead::STATUS_NEW)
                    ->required(),

                Select::make('priority')
                    ->label('Priority')
                    ->options([
                        'low' => 'Low',
                        'normal' => 'Normal',
                        'high' => 'High',
                        'urgent' => 'Urgent 🔥',
                    ])
                    ->default('normal'),

                Select::make('assigned_user_id')
                    ->label('Assigned Property Specialist')
                    ->relationship('assignedUser', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),

                TextInput::make('city')
                    ->label('Target City')
                    ->placeholder('e.g. Bengaluru, Delhi NCR, Mumbai'),

                TextInput::make('locality')
                    ->label('Target Locality')
                    ->placeholder('e.g. Indiranagar, Whitefield, Bandra West'),

                TextInput::make('budget_min')
                    ->label('Budget Min (₹)')
                    ->numeric()
                    ->prefix('₹'),

                TextInput::make('budget_max')
                    ->label('Budget Max (₹)')
                    ->numeric()
                    ->prefix('₹'),

                Textarea::make('notes')
                    ->label('Customer Requirements / Ingestion Notes')
                    ->rows(3)
                    ->columnSpanFull(),

                // Conversion Section
                TextInput::make('converted_deal_value')
                    ->label('Converted Deal Value / Rent (₹)')
                    ->numeric()
                    ->prefix('₹')
                    ->visible(fn (?Lead $record) => $record?->isConverted() || request()->get('status') === Lead::STATUS_CONVERTED),

                Select::make('converted_deal_type')
                    ->label('Closed Deal Classification')
                    ->options([
                        'residential_rental_executed' => 'Residential Rental Tenancy Executed',
                        'commercial_lease_executed' => 'Commercial Lease Executed',
                        'property_sale_closed' => 'Property Sale / Buy Closed',
                        'care_maintenance_contract' => 'Property Care Annual Contract',
                        'nri_property_management' => 'NRI Full Property Management Onboarded',
                    ])
                    ->visible(fn (?Lead $record) => $record?->isConverted() || request()->get('status') === Lead::STATUS_CONVERTED),

                Textarea::make('conversion_notes')
                    ->label('Deal Conversion Details & Terms')
                    ->rows(2)
                    ->columnSpanFull()
                    ->visible(fn (?Lead $record) => $record?->isConverted() || request()->get('status') === Lead::STATUS_CONVERTED),

                // Documents & Agreements Repeater
                Repeater::make('documents')
                    ->relationship('documents')
                    ->label('Uploaded Agreements & Verification Documents')
                    ->schema([
                        TextInput::make('title')
                            ->label('Document Title')
                            ->required()
                            ->placeholder('e.g. 11-Month Rental Agreement Draft')
                            ->columnSpan(2),

                        Select::make('document_type')
                            ->label('Document Type')
                            ->options(LeadDocument::getDocumentTypeOptions())
                            ->required()
                            ->default(LeadDocument::TYPE_AGREEMENT_DRAFT)
                            ->columnSpan(1),

                        FileUpload::make('file_path')
                            ->label('File Attachment')
                            ->disk('public')
                            ->directory('lead-documents')
                            ->acceptedFileTypes(['application/pdf', 'image/*', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                            ->maxSize(10240)
                            ->required()
                            ->columnSpan(2),

                        Textarea::make('notes')
                            ->label('Document Notes')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])
                    ->columns(5)
                    ->columnSpanFull()
                    ->defaultItems(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Lead #')
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('contact_name')
                    ->label('Client Contact')
                    ->description(fn (Lead $record): string => ($record->contact_phone ? $record->contact_phone.' • ' : '').$record->contact_email)
                    ->searchable(['contact_name', 'name', 'contact_phone', 'phone', 'contact_email', 'email'])
                    ->weight('bold')
                    ->sortable(),

                TextColumn::make('lead_type')
                    ->label('Category')
                    ->badge()
                    ->formatStateUsing(fn ($state) => ucfirst(str_replace('_', ' ', (string) $state)))
                    ->color('info')
                    ->sortable(),

                TextColumn::make('property.title')
                    ->label('Property')
                    ->limit(25)
                    ->description(fn (Lead $record): ?string => $record->property ? ($record->property->locality ? $record->property->locality.', ' : '').$record->property->city : null)
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Pipeline Stage')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'NEW' => 'danger',
                        'ASSIGNED' => 'warning',
                        'CONTACTED' => 'info',
                        'QUALIFIED' => 'primary',
                        'VIEWING_SCHEDULED', 'VIEWING_COMPLETED' => 'purple',
                        'APPLICATION_SUBMITTED', 'AGREEMENT_DRAFTED' => 'warning',
                        'CONVERTED' => 'success',
                        'LOST', 'CLOSED' => 'gray',
                        'SPAM' => 'gray',
                        default => 'secondary',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'NEW' => '🔴 New',
                        'ASSIGNED' => '🟠 Assigned',
                        'CONTACTED' => '🟡 Contacted',
                        'QUALIFIED' => '🔵 Qualified',
                        'VIEWING_SCHEDULED' => '📅 Visit Scheduled',
                        'VIEWING_COMPLETED' => '🏡 Visit Done',
                        'APPLICATION_SUBMITTED' => '📋 KYC Submitted',
                        'AGREEMENT_DRAFTED' => '📄 Agreement Drafted',
                        'CONVERTED' => '🟢 Won / Converted',
                        'LOST' => '⚫ Lost',
                        'CLOSED' => '⚪ Closed',
                        'SPAM' => '🚫 Spam',
                        default => $state,
                    })
                    ->sortable(),

                TextColumn::make('converted_deal_value')
                    ->label('Won Deal (₹)')
                    ->money('INR')
                    ->badge()
                    ->color('success')
                    ->visible(fn (): bool => true),

                TextColumn::make('documents_count')
                    ->counts('documents')
                    ->label('Docs')
                    ->badge()
                    ->color(fn (int $state): string => $state > 0 ? 'success' : 'gray'),

                TextColumn::make('assignedUser.name')
                    ->label('Assigned Specialist')
                    ->placeholder('Unassigned')
                    ->searchable()
                    ->badge()
                    ->color('secondary'),

                TextColumn::make('sla_status')
                    ->label('SLA')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'achieved' => 'success',
                        'breached' => 'danger',
                        default => 'warning',
                    }),

                TextColumn::make('created_at')
                    ->label('Received')
                    ->dateTime('d M, h:i A')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Pipeline Stage')
                    ->options(Lead::getStatusOptions()),

                SelectFilter::make('lead_type')
                    ->label('Category')
                    ->options([
                        'residential_rental' => 'Residential Rental',
                        'property_sale' => 'Property Sale',
                        'commercial' => 'Commercial',
                        'land' => 'Land / Plot',
                        'care_service' => 'Housing365 Care',
                        'property_management' => 'Property Management',
                    ]),

                SelectFilter::make('priority')
                    ->options([
                        'urgent' => 'Urgent 🔥',
                        'high' => 'High',
                        'normal' => 'Normal',
                        'low' => 'Low',
                    ]),

                SelectFilter::make('assigned_user_id')
                    ->relationship('assignedUser', 'name')
                    ->label('Assigned Specialist'),
            ])
            ->recordActions([
                // 1. Reply through Email Action
                Action::make('email_reply')
                    ->label('Reply Email')
                    ->icon('heroicon-m-envelope')
                    ->color('primary')
                    ->modalHeading('Send Email Reply to Lead')
                    ->modalDescription('Compose and dispatch an official email response directly from Housing365 India CRM.')
                    ->modalSubmitActionLabel('Send Email Reply')
                    ->schema([
                        TextInput::make('recipient')
                            ->label('Recipient Email')
                            ->default(fn (Lead $record) => $record->email ?: $record->contact_email)
                            ->disabled()
                            ->dehydrated(false),

                        Select::make('template')
                            ->label('Choose Email Template')
                            ->options([
                                'custom' => 'Custom Message',
                                'visit_schedule' => '📅 Site Visit / Viewing Confirmation',
                                'agreement_ready' => '📄 Rental Agreement Draft Ready for Review',
                                'kyc_request' => '📋 KYC, Aadhaar & Biometric Police Verification Request',
                                'converted_welcome' => '🎉 Tenancy Executed / Welcome & Move-In Handover',
                                'follow_up' => '👋 General Requirement Follow-Up',
                            ])
                            ->default('follow_up')
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, Lead $record) {
                                $clientName = $record->contact_name ?: ($record->name ?: 'Customer');
                                $propertyTitle = $record->property ? $record->property->title : 'your requested property';

                                switch ($state) {
                                    case 'visit_schedule':
                                        $set('subject', "Site Visit Scheduled: {$propertyTitle} — Housing365 India");
                                        $set('message', "Dear {$clientName},\n\nWe have scheduled your physical site visit for {$propertyTitle}.\n\nOur field representative will meet you at the property location. Please keep your valid ID proof handy for society visitor registration.\n\nShould you need to reschedule or require directions, feel free to reply to this email or reach us on +91 8000 365 365.");
                                        break;

                                    case 'agreement_ready':
                                        $set('subject', "Rental Agreement Draft Ready for Review — Housing365 India");
                                        $set('message', "Dear {$clientName},\n\nWe have drafted the legal tenancy agreement for {$propertyTitle}.\n\nThe draft has been vetted as per the Model Tenancy Act with state-specific stamp duty calculations. Please review the attached document and let us know if any clauses require modification before digital e-stamping and Aadhaar e-sign.");
                                        break;

                                    case 'kyc_request':
                                        $set('subject', "Tenant KYC & Biometric Police Verification Submission — Housing365 India");
                                        $set('message', "Dear {$clientName},\n\nTo finalize your tenancy and execute the e-stamped agreement for {$propertyTitle}, please provide the following verification documents:\n\n1. Clear copy of Aadhaar Card (Front & Back)\n2. PAN Card\n3. Proof of Employment (Offer letter or company ID)\n4. Two passport-size photographs for police verification\n\nYou can reply directly to this email with the attachments or upload them securely to our portal.");
                                        break;

                                    case 'converted_welcome':
                                        $set('subject', "Congratulations! Tenancy Executed for {$propertyTitle} — Housing365 India");
                                        $set('message', "Dear {$clientName},\n\nCongratulations! The lease agreement for {$propertyTitle} has been legally executed and registered.\n\nYour move-in inspection report and emergency contact details have been linked to your Housing365 portal account. Welcome to your new home!");
                                        break;

                                    case 'follow_up':
                                    default:
                                        $set('subject', "Regarding Your Property Inquiry — Housing365 India");
                                        $set('message', "Dear {$clientName},\n\nThank you for reaching out to Housing365 India regarding {$propertyTitle}.\n\nOur property specialists have curated top verified options matching your requirements. Are you available for a brief discussion or site visit this week?");
                                        break;
                                }
                            }),

                        TextInput::make('subject')
                            ->label('Email Subject')
                            ->required()
                            ->default(fn (Lead $record) => "Regarding Your Property Inquiry — Housing365 India"),

                        Textarea::make('message')
                            ->label('Email Message Body')
                            ->required()
                            ->rows(6)
                            ->default(fn (Lead $record) => "Dear " . ($record->contact_name ?: 'Customer') . ",\n\nThank you for reaching out to Housing365 India. Our property specialist is reviewing your inquiry and will coordinate with you shortly."),

                        Select::make('attachment_file_path')
                            ->label('Attach Document / Agreement (Optional)')
                            ->options(fn (Lead $record) => $record->documents()->pluck('title', 'file_path')->toArray())
                            ->placeholder('No document attached')
                            ->helperText('Select any uploaded agreement, KYC, or inspection report to attach to this email.'),
                    ])
                    ->action(function (Lead $record, array $data): void {
                        try {
                            app(LeadManagementService::class)->sendEmailReply(
                                lead: $record,
                                subject: $data['subject'],
                                body: $data['message'],
                                attachmentPath: $data['attachment_file_path'] ?? null,
                                sender: auth()->user()
                            );

                            Notification::make()
                                ->title('Email Sent Successfully')
                                ->body('Reply dispatched to ' . ($record->email ?: $record->contact_email))
                                ->success()
                                ->send();
                        } catch (\Throwable $e) {
                            Notification::make()
                                ->title('Email Dispatch Failed')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),

                // 2. Advance to Next Stage Action
                Action::make('advance_stage')
                    ->label('Next Stage')
                    ->icon('heroicon-m-arrow-right-circle')
                    ->color('warning')
                    ->visible(fn (Lead $record): bool => ! in_array($record->status, [Lead::STATUS_CONVERTED, Lead::STATUS_LOST, Lead::STATUS_CLOSED]))
                    ->modalHeading('Advance Lead to Next Pipeline Stage')
                    ->modalSubmitActionLabel('Update Stage')
                    ->schema([
                        Select::make('new_status')
                            ->label('Target Stage')
                            ->options(Lead::getStatusOptions())
                            ->default(fn (Lead $record) => Lead::getNextPipelineStage($record->status) ?? $record->status)
                            ->required(),

                        Textarea::make('reason')
                            ->label('Outcome / Transition Reason')
                            ->placeholder('e.g. Completed initial phone discovery; customer requested site visit on Saturday.')
                            ->rows(3),
                    ])
                    ->action(function (Lead $record, array $data): void {
                        app(LeadManagementService::class)->updateStatus(
                            lead: $record,
                            newStatus: $data['new_status'],
                            actor: auth()->user(),
                            reason: $data['reason'] ?? null
                        );

                        Notification::make()
                            ->title('Pipeline Stage Updated')
                            ->body("Lead #{$record->id} moved to {$data['new_status']}")
                            ->success()
                            ->send();
                    }),

                // 3. Convert Lead Action
                Action::make('convert_lead')
                    ->label('Convert Deal')
                    ->icon('heroicon-m-check-badge')
                    ->color('success')
                    ->visible(fn (Lead $record): bool => $record->status !== Lead::STATUS_CONVERTED)
                    ->modalHeading('Convert Lead to Closed Deal / Executed Tenancy')
                    ->modalDescription('Record closed deal revenue, executed agreement type, and close the sales loop.')
                    ->modalSubmitActionLabel('Convert & Close Deal')
                    ->schema([
                        Select::make('deal_type')
                            ->label('Closed Deal Type')
                            ->options([
                                'residential_rental_executed' => 'Residential Rental Tenancy Executed',
                                'commercial_lease_executed' => 'Commercial Lease Executed',
                                'property_sale_closed' => 'Property Sale / Buy Closed',
                                'care_maintenance_contract' => 'Property Care Annual Contract',
                                'nri_property_management' => 'NRI Full Property Management Onboarded',
                            ])
                            ->default('residential_rental_executed')
                            ->required(),

                        TextInput::make('deal_value')
                            ->label('Closed Deal Value / Rent Amount (₹)')
                            ->numeric()
                            ->prefix('₹')
                            ->placeholder('e.g. 45000')
                            ->required(),

                        Textarea::make('notes')
                            ->label('Conversion Summary & Commercial Terms')
                            ->rows(3)
                            ->placeholder('e.g. 11-month e-stamped agreement signed. ₹45,000 rent, 2 months deposit. Police verification submitted.')
                            ->required(),
                    ])
                    ->action(function (Lead $record, array $data): void {
                        app(LeadManagementService::class)->convertLead(
                            lead: $record,
                            data: $data,
                            actor: auth()->user()
                        );

                        Notification::make()
                            ->title('🎉 Deal Converted Successfully!')
                            ->body("Lead #{$record->id} converted with value ₹" . number_format((float) $data['deal_value']))
                            ->success()
                            ->send();
                    }),

                // 4. Upload Document Action
                Action::make('upload_document')
                    ->label('Upload Doc')
                    ->icon('heroicon-m-document-arrow-up')
                    ->color('info')
                    ->modalHeading('Upload Agreement or Verification Document')
                    ->modalSubmitActionLabel('Upload Document')
                    ->schema([
                        TextInput::make('title')
                            ->label('Document Title')
                            ->required()
                            ->placeholder('e.g. 11-Month Registered Lease Agreement Draft'),

                        Select::make('document_type')
                            ->label('Document Classification')
                            ->options(LeadDocument::getDocumentTypeOptions())
                            ->default(LeadDocument::TYPE_AGREEMENT_DRAFT)
                            ->required(),

                        FileUpload::make('file_path')
                            ->label('File Attachment (PDF, DOCX, Images)')
                            ->disk('public')
                            ->directory('lead-documents')
                            ->acceptedFileTypes(['application/pdf', 'image/*', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                            ->maxSize(10240)
                            ->required(),

                        Textarea::make('notes')
                            ->label('Document Notes')
                            ->placeholder('e.g. Signed draft awaiting biometric police verification receipt.')
                            ->rows(2),
                    ])
                    ->action(function (Lead $record, array $data): void {
                        app(LeadManagementService::class)->uploadDocument(
                            lead: $record,
                            data: $data,
                            actor: auth()->user()
                        );

                        Notification::make()
                            ->title('Document Uploaded Successfully')
                            ->body("Attached {$data['title']} to Lead #{$record->id}")
                            ->success()
                            ->send();
                    }),

                // 5. WhatsApp Direct Action
                Action::make('whatsapp')
                    ->label('WhatsApp')
                    ->icon('heroicon-m-chat-bubble-left-ellipsis')
                    ->color('success')
                    ->url(function (Lead $record): string {
                        $rawPhone = preg_replace('/[^0-9]/', '', (string) ($record->phone ?: $record->contact_phone));
                        if (strlen($rawPhone) === 10) {
                            $rawPhone = '91' . $rawPhone;
                        }
                        $name = $record->contact_name ?: ($record->name ?: 'Customer');
                        $msg = urlencode("Hi {$name}, thank you for contacting Housing365 India. We are reviewing your property inquiry. Are you available for a quick discussion?");
                        return "https://wa.me/{$rawPhone}?text={$msg}";
                    }, shouldOpenInNewTab: true),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLeads::route('/'),
            'create' => CreateLead::route('/create'),
            'edit' => EditLead::route('/{record}/edit'),
        ];
    }
}
