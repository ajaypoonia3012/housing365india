<?php

namespace App\Filament\Resources\SeoMetaOverrides;

use App\Models\SeoMetaOverride;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SeoMetaOverrideResource extends Resource
{
    protected static ?string $model = SeoMetaOverride::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-globe-alt';

    protected static \UnitEnum|string|null $navigationGroup = 'Locations & SEO';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('path')
                    ->label('Target URL Path')
                    ->placeholder('e.g. /rent/rajasthan/jaipur/')
                    ->required()
                    ->maxLength(255)
                    ->regex('/^\/[a-zA-Z0-9_\-\.\/]*$/')
                    ->unique(ignoreRecord: true)
                    ->helperText('Relative URL path starting with a forward slash.')
                    ->columnSpanFull(),

                TextInput::make('meta_title')
                    ->label('Custom SEO Title')
                    ->placeholder('e.g. Verified Rental Flats in Jaipur | Housing365')
                    ->maxLength(255)
                    ->helperText('Recommended 50–60 characters. Overrides HTML, OpenGraph, and Twitter titles.')
                    ->columnSpan(1),

                TextInput::make('h1')
                    ->label('Custom Page H1')
                    ->placeholder('e.g. Rental Properties & Verified Flats in Jaipur')
                    ->maxLength(255)
                    ->helperText('Main visible H1 heading where supported by the page template.')
                    ->columnSpan(1),

                Textarea::make('meta_description')
                    ->label('Custom Meta Description')
                    ->placeholder('e.g. Browse 100% verified flats and apartments for rent in Jaipur with ₹0 owner listing fee...')
                    ->rows(3)
                    ->maxLength(500)
                    ->helperText('Recommended 150–160 characters. Overrides meta and social descriptions.')
                    ->columnSpanFull(),

                TextInput::make('canonical_url')
                    ->label('Explicit Canonical URL')
                    ->placeholder('https://housing365.in/rent/rajasthan/jaipur')
                    ->maxLength(255)
                    ->url()
                    ->helperText('Optional explicit canonical URL. Leave blank to use self-referencing canonical URL.')
                    ->columnSpanFull(),

                Textarea::make('intro_text')
                    ->label('Introductory Text / Market Summary')
                    ->rows(3)
                    ->helperText('Editorial summary displayed at the top of location and category guides.')
                    ->columnSpanFull(),

                Toggle::make('is_indexable')
                    ->label('Indexable (Robots: index, follow)')
                    ->helperText('Toggle off to output "noindex, follow" for thin, duplicate, or internal landing pages.')
                    ->default(true),

                TextInput::make('schema_type')
                    ->label('Schema Type')
                    ->placeholder('e.g. RealEstateListing, FAQPage, LocalBusiness')
                    ->maxLength(50)
                    ->columnSpan(1),

                DateTimePicker::make('last_reviewed_at')
                    ->label('Last Content Review Date')
                    ->columnSpan(1),

                Textarea::make('schema_json')
                    ->label('Custom Structured Data (JSON-LD)')
                    ->placeholder('{"@context": "https://schema.org", "@type": "RealEstateListing", ...}')
                    ->rows(4)
                    ->helperText('Valid JSON object for schema.org. Emitted as a JSON-LD script block.')
                    ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : $state)
                    ->mutateDehydratedStateUsing(function ($state) {
                        if (blank($state)) {
                            return null;
                        }
                        if (is_string($state)) {
                            $decoded = json_decode($state, true);

                            return json_last_error() === JSON_ERROR_NONE ? $decoded : null;
                        }

                        return $state;
                    })
                    ->rules([
                        function () {
                            return function (string $attribute, $value, \Closure $fail) {
                                if (! empty($value) && is_string($value)) {
                                    $decoded = json_decode($value, true);
                                    if (json_last_error() !== JSON_ERROR_NONE || ! is_array($decoded)) {
                                        $fail('The custom structured data must be a valid JSON object.');
                                    }
                                }
                            };
                        },
                    ])
                    ->columnSpanFull(),

                Textarea::make('faqs')
                    ->label('FAQ Structured Data (JSON Array)')
                    ->placeholder('[{"question": "How do I rent in Jaipur?", "answer": "Browse verified owner listings..."}]')
                    ->rows(4)
                    ->helperText('Valid JSON array of FAQ items with non-empty question and answer keys.')
                    ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : $state)
                    ->mutateDehydratedStateUsing(function ($state) {
                        if (blank($state)) {
                            return null;
                        }
                        if (is_string($state)) {
                            $decoded = json_decode($state, true);

                            return json_last_error() === JSON_ERROR_NONE ? $decoded : null;
                        }

                        return $state;
                    })
                    ->rules([
                        function () {
                            return function (string $attribute, $value, \Closure $fail) {
                                if (! empty($value) && is_string($value)) {
                                    $decoded = json_decode($value, true);
                                    if (json_last_error() !== JSON_ERROR_NONE || ! is_array($decoded)) {
                                        $fail('The FAQs must be a valid JSON array.');
                                        return;
                                    }
                                    foreach ($decoded as $index => $item) {
                                        if (! is_array($item)) {
                                            $fail('FAQ item #'.($index + 1).' must be a valid object.');
                                            return;
                                        }
                                        if (empty(trim((string) ($item['question'] ?? '')))) {
                                            $fail('FAQ item #'.($index + 1)." must contain a non-empty 'question'.");
                                            return;
                                        }
                                        if (empty(trim((string) ($item['answer'] ?? '')))) {
                                            $fail('FAQ item #'.($index + 1)." must contain a non-empty 'answer'.");
                                            return;
                                        }
                                    }
                                }
                            };
                        },
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('path')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('meta_title')
                    ->searchable()
                    ->limit(40),
                IconColumn::make('is_indexable')
                    ->label('Indexable')
                    ->boolean(),
                TextColumn::make('schema_type')
                    ->label('Schema')
                    ->badge()
                    ->color('info')
                    ->placeholder('None'),
                TextColumn::make('last_reviewed_at')
                    ->label('Reviewed')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
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
            'index' => Pages\ListSeoMetaOverrides::route('/'),
        ];
    }
}
