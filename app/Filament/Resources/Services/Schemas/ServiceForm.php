<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set, string $operation) {
                        if ($operation === 'create' || empty($state)) {
                            $set('slug', \Illuminate\Support\Str::slug($state));
                        }
                    }),
                TextInput::make('slug')
                    ->required()
                    ->helperText('Auto-generated from title, or enter custom URL slug.'),
                TextInput::make('category')
                    ->required()
                    ->default('all'),
                TextInput::make('icon')
                    ->default(null),
                TextInput::make('hero_eyebrow')
                    ->label('Hero Eyebrow Badge')
                    ->default(null),
                TextInput::make('hero_headline')
                    ->label('Hero Headline')
                    ->default(null),
                Textarea::make('hero_lede')
                    ->label('Hero Lede / Intro')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('hero_backdrop')
                    ->label('Hero Photographic Backdrop URL')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('short_description')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('full_description')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('meta_description')
                    ->label('SEO Meta Description')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('pricing_note')
                    ->label('Pricing & Transparency Callout Note')
                    ->default(null)
                    ->columnSpanFull(),
                Repeater::make('scope')
                    ->label('Service Scope & Deliverables')
                    ->schema([
                        TextInput::make('title')->required(),
                        Textarea::make('body')->required(),
                    ])
                    ->columnSpanFull(),
                Repeater::make('how_it_works')
                    ->label('Step-by-Step Workflow')
                    ->schema([
                        TextInput::make('step')->required(),
                        TextInput::make('title')->required(),
                        Textarea::make('body')->required(),
                    ])
                    ->columnSpanFull(),
                Repeater::make('who_its_for')
                    ->label('Target Audiences')
                    ->schema([
                        TextInput::make('audience')->required(),
                        Textarea::make('fitNote')->label('Why It Fits')->required(),
                    ])
                    ->columnSpanFull(),
                Repeater::make('faq_items')
                    ->label('Service-Specific FAQs')
                    ->schema([
                        TextInput::make('question')->required(),
                        Textarea::make('answer')->required(),
                    ])
                    ->columnSpanFull(),
                TextInput::make('pricing_info')
                    ->default(null),
                TextInput::make('order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
