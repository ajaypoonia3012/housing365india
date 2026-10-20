<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Page Title')
                    ->placeholder('e.g. Home, About Us, Owner Solutions')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set, string $operation) {
                        if ($operation === 'create' || empty($state)) {
                            $set('slug', \Illuminate\Support\Str::slug($state));
                        }
                    }),

                TextInput::make('slug')
                    ->label('Page Slug')
                    ->placeholder('e.g. home, about, owners')
                    ->required()
                    ->maxLength(100)
                    ->helperText('Auto-generated from title, or enter custom URL slug.'),

                Toggle::make('is_published')
                    ->label('Published')
                    ->default(true),

                TextInput::make('hero_badge')
                    ->label('Hero Eyebrow Badge')
                    ->placeholder('e.g. EVERYTHING PROPERTY. ONE PLATFORM.')
                    ->maxLength(255)
                    ->columnSpanFull(),

                TextInput::make('hero_title')
                    ->label('Hero Main Headline (H1)')
                    ->placeholder('e.g. Everything Property. One Platform.')
                    ->maxLength(255)
                    ->helperText('Supports safe inline styling such as <span class="text-brand-cyan">.')
                    ->columnSpanFull(),

                Textarea::make('hero_subtitle')
                    ->label('Hero Subheadline')
                    ->placeholder('e.g. Find, list, lease, verify, move, maintain, manage, buy and sell property across India...')
                    ->rows(3)
                    ->columnSpanFull(),

                TextInput::make('hero_cta_text')
                    ->label('Primary CTA Button Text')
                    ->placeholder('e.g. List my Property — Free')
                    ->maxLength(100),

                TextInput::make('hero_cta_link')
                    ->label('Primary CTA Button URL')
                    ->placeholder('e.g. /post-property or https://housing365.in/post-property')
                    ->maxLength(255)
                    ->rules(['nullable', static::ctaUrlRule()])
                    ->helperText('Relative path (e.g. /post-property) or approved https:// URL. Protocol-relative or javascript: URLs are rejected.'),

                TextInput::make('sections_data.secondary_cta_text')
                    ->label('Secondary CTA Button Text')
                    ->placeholder('e.g. Find a Property')
                    ->maxLength(100),

                TextInput::make('sections_data.secondary_cta_link')
                    ->label('Secondary CTA Button URL')
                    ->placeholder('e.g. /properties')
                    ->maxLength(255)
                    ->rules(['nullable', static::ctaUrlRule()])
                    ->helperText('Relative path (e.g. /properties) or approved https:// URL. Protocol-relative or javascript: URLs are rejected.'),

                TextInput::make('meta_title')
                    ->label('SEO Meta Title')
                    ->placeholder('e.g. Housing365 India | Everything Property. One Platform.')
                    ->maxLength(255)
                    ->columnSpanFull(),

                Textarea::make('meta_description')
                    ->label('SEO Meta Description')
                    ->placeholder('e.g. Find, list, lease, verify, move, maintain, manage, buy and sell property across India...')
                    ->rows(2)
                    ->maxLength(500)
                    ->columnSpanFull(),

                Textarea::make('content')
                    ->label('Page Content / Body Copy')
                    ->rows(5)
                    ->columnSpanFull(),
            ]);
    }

    /**
     * Unified validation rule for primary and secondary CTA URLs.
     */
    public static function ctaUrlRule(): \Closure
    {
        return function (string $attribute, $value, \Closure $fail) {
            if (blank($value)) {
                return;
            }
            $trimmed = trim((string) $value);

            // Explicitly reject protocol-relative URLs (e.g. //example.com)
            if (str_starts_with($trimmed, '//')) {
                $fail('Protocol-relative URLs starting with // are not permitted.');
                return;
            }

            // Reject dangerous or unsupported schemes
            if (preg_match('/^(javascript|data|vbscript|file):/i', $trimmed)) {
                $fail('The '.$attribute.' contains an unsafe or unsupported URL scheme.');
                return;
            }

            // Permit valid relative paths starting with a single forward slash
            if (preg_match('/^\/(?!\/)[a-zA-Z0-9_\-\.\/?=&#%+~]*$/', $trimmed)) {
                return;
            }

            // Permit approved absolute HTTPS URLs
            if (preg_match('/^https:\/\/[a-zA-Z0-9_\-\.]+(\:[0-9]+)?(\/[a-zA-Z0-9_\-\.\/?=&#%+~]*)?$/i', $trimmed)) {
                return;
            }

            // Permit tel: and mailto:
            if (preg_match('/^tel:\+?[0-9\-]+$/i', $trimmed)) {
                return;
            }
            if (preg_match('/^mailto:[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/i', $trimmed)) {
                return;
            }

            $fail('The '.$attribute.' must be a valid relative path (e.g. /post-property) or an approved https://, tel:, or mailto: URL.');
        };
    }
}
