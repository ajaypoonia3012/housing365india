<?php

namespace App\Filament\Resources\BlogPosts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
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
                Toggle::make('auto_post_to_linkedin')
                    ->label('Post to LinkedIn automatically upon publishing')
                    ->helperText('Automatically shares this article to your company LinkedIn feed when published.')
                    ->default(false),
                Placeholder::make('linkedin_status')
                    ->label('LinkedIn Publication Status')
                    ->content(function ($record) {
                        if (! $record || ! $record->linkedin_shared_at) {
                            return 'Not shared on LinkedIn yet.';
                        }
                        $url = $record->linkedin_post_url ? "<a href='{$record->linkedin_post_url}' target='_blank' style='color:#0a66c2; font-weight:600; text-decoration:underline; margin-left:8px;'>Open LinkedIn Post ↗</a>" : '';
                        $dateStr = $record->linkedin_shared_at->format('d M Y, H:i');
                        return new \Illuminate\Support\HtmlString("<span>✅ Shared to LinkedIn on <strong>{$dateStr}</strong></span>{$url}");
                    })
                    ->visible(fn ($record) => $record && $record->linkedin_shared_at),
                DateTimePicker::make('published_at'),
            ]);
    }
}
