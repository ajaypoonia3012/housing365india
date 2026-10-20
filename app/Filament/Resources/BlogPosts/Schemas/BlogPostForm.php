<?php

namespace App\Filament\Resources\BlogPosts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BlogPostForm
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
                    ->helperText('Auto-generated from title, or customize if needed.'),
                TextInput::make('category')
                    ->required()
                    ->default('Rental Guide'),
                Textarea::make('excerpt')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('content')
                    ->default(null)
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->image(),
                TextInput::make('author_name')
                    ->required()
                    ->default('Housing365 Editorial'),
                TextInput::make('read_time')
                    ->required()
                    ->default('4 min read'),
                Toggle::make('is_published')
                    ->required(),
                DateTimePicker::make('published_at'),
            ]);
    }
}
