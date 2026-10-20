<?php

namespace App\Services;

use App\Models\BlogPost;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LinkedInPublishingService
{
    /**
     * Build engaging, professional LinkedIn post copy for a blog article.
     */
    public static function formatPostText(BlogPost $post, ?string $customNote = null): string
    {
        if (! empty($customNote)) {
            return trim($customNote);
        }

        $title = $post->title;
        $excerpt = $post->excerpt ?: 'Essential real estate insights, regulatory compliance, and tenancy guidance across India.';
        $url = $post->public_url;
        $category = $post->category ?? 'Real Estate Guide';
        $author = $post->author_name ?? 'Housing365 Team';

        $hashtags = self::generateHashtags($post);

        return "📢 NEW GUIDE: {$title}\n\n" .
            "💡 Overview:\n{$excerpt}\n\n" .
            "📌 Category: {$category}\n" .
            "✍️ By: {$author}\n\n" .
            "👉 Read the full comprehensive article here:\n{$url}\n\n" .
            "{$hashtags}";
    }

    /**
     * Generate relevant LinkedIn hashtags based on category and title.
     */
    public static function generateHashtags(BlogPost $post): string
    {
        $category = strtolower($post->category ?? '');
        $title = strtolower($post->title ?? '');

        $tags = ['#Housing365', '#IndianRealEstate', '#PropertyIndia'];

        if (str_contains($category, 'rera') || str_contains($title, 'rera')) {
            $tags[] = '#RERA';
            $tags[] = '#HomebuyersProtection';
            $tags[] = '#RealEstateLaw';
        }

        if (str_contains($category, 'rental') || str_contains($title, 'rent') || str_contains($title, 'tenant')) {
            $tags[] = '#ModelTenancyAct';
            $tags[] = '#TenantRights';
            $tags[] = '#LandlordTips';
        }

        if (str_contains($category, 'buying') || str_contains($title, 'buy') || str_contains($title, 'property')) {
            $tags[] = '#HomeBuyingGuide';
            $tags[] = '#PropertyInvestment';
            $tags[] = '#DueDiligence';
        }

        if (str_contains($category, 'tax') || str_contains($title, 'tax') || str_contains($title, 'stamp')) {
            $tags[] = '#StampDuty';
            $tags[] = '#IncomeTaxIndia';
            $tags[] = '#HomeLoan';
        }

        if (str_contains($category, 'nri') || str_contains($title, 'nri')) {
            $tags[] = '#NRIProperty';
            $tags[] = '#FEMA';
            $tags[] = '#NRIInvestment';
        }

        return implode(' ', array_unique($tags));
    }

    /**
     * Return direct 1-click LinkedIn Share URL (web dialog).
     */
    public static function getShareOffsiteUrl(BlogPost $post): string
    {
        return 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode($post->public_url);
    }

    /**
     * Return direct LinkedIn Share Intent URL with prefilled commentary text.
     */
    public static function getShareFeedIntentUrl(BlogPost $post, ?string $customText = null): string
    {
        $text = self::formatPostText($post, $customText);

        return 'https://www.linkedin.com/feed/?shareActive=true&text=' . urlencode($text);
    }

    /**
     * Check if LinkedIn API integration credentials exist.
     */
    /**
     * Check if LinkedIn API integration credentials exist.
     */
    public static function isApiConfigured(): bool
    {
        $token = config('services.linkedin.access_token') ?: Setting::get('linkedin_access_token');

        return ! empty($token);
    }

    /**
     * Publish article directly to LinkedIn feed via official LinkedIn REST Posts API.
     * Uses POST https://api.linkedin.com/rest/posts with modern Linkedin-Version and X-Restli-Protocol-Version headers.
     */
    public static function publishViaApi(BlogPost $post, ?string $customCommentary = null): array
    {
        $token = config('services.linkedin.access_token') ?: Setting::get('linkedin_access_token');
        if (empty($token)) {
            return [
                'success' => false,
                'message' => 'LinkedIn Access Token is not configured. Set LINKEDIN_ACCESS_TOKEN in .env or configure in Admin Settings.',
            ];
        }

        $authorUrn = config('services.linkedin.author_urn') ?: Setting::get('linkedin_author_urn');
        $apiVersion = config('services.linkedin.version', '202401');

        // If author URN is not set, try to resolve authenticated user's URN from userinfo endpoint
        if (empty($authorUrn)) {
            try {
                $userinfo = Http::withToken($token)
                    ->timeout(10)
                    ->get('https://api.linkedin.com/v2/userinfo');

                if ($userinfo->successful() && ! empty($userinfo->json('sub'))) {
                    $authorUrn = 'urn:li:person:' . $userinfo->json('sub');
                    Setting::set('linkedin_author_urn', $authorUrn, 'social', 'text', 'LinkedIn Author URN');
                }
            } catch (\Throwable $e) {
                Log::warning('LinkedIn userinfo fetch failed: ' . $e->getMessage());
            }
        }

        if (empty($authorUrn)) {
            return [
                'success' => false,
                'message' => 'Could not determine LinkedIn Author URN. Please configure LINKEDIN_AUTHOR_URN in .env (e.g. urn:li:organization:1234567 or urn:li:person:abcdef) or Settings.',
            ];
        }

        $commentary = self::formatPostText($post, $customCommentary);

        $payload = [
            'author' => $authorUrn,
            'commentary' => $commentary,
            'visibility' => 'PUBLIC',
            'distribution' => [
                'feedDistribution' => 'MAIN_FEED',
                'targetEntities' => [],
                'thirdPartyDistributionChannels' => [],
            ],
            'content' => [
                'article' => [
                    'source' => $post->public_url,
                    'title' => $post->title,
                    'description' => substr($post->excerpt ?: $post->title, 0, 200),
                ],
            ],
            'lifecycleState' => 'PUBLISHED',
            'isReshareDisabledByAuthor' => false,
        ];

        try {
            $response = Http::withToken($token)
                ->withHeaders([
                    'Linkedin-Version' => $apiVersion,
                    'X-Restli-Protocol-Version' => '2.0.0',
                    'Content-Type' => 'application/json',
                ])
                ->timeout(15)
                ->post('https://api.linkedin.com/rest/posts', $payload);

            if ($response->successful() || $response->status() === 201) {
                $postId = $response->header('x-restli-id')
                    ?: $response->json('id')
                    ?: ($response->header('Location') ? basename($response->header('Location')) : 'urn:li:share:' . time());

                $postUrl = str_starts_with($postId, 'urn:')
                    ? 'https://www.linkedin.com/feed/update/' . $postId
                    : 'https://www.linkedin.com/feed/update/urn:li:share:' . $postId;

                $post->update([
                    'linkedin_post_id' => $postId,
                    'linkedin_post_url' => $postUrl,
                    'linkedin_shared_at' => now(),
                ]);

                return [
                    'success' => true,
                    'post_id' => $postId,
                    'post_url' => $postUrl,
                    'message' => 'Successfully published article to LinkedIn via Posts API!',
                ];
            }

            $errorBody = $response->body();
            Log::error('LinkedIn Posts API Error: ' . $errorBody);

            return [
                'success' => false,
                'message' => 'LinkedIn Posts API returned error: ' . ($response->json('message') ?: 'HTTP ' . $response->status()),
                'details' => $errorBody,
            ];
        } catch (\Throwable $e) {
            Log::error('LinkedIn publish exception: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Network error connecting to LinkedIn: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Create a Filament Action configured for LinkedIn posting.
     */
    public static function createFilamentAction(string $name = 'post_to_linkedin'): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make($name)
            ->label('Post to LinkedIn')
            ->icon('heroicon-m-share')
            ->color('info')
            ->modalHeading(fn ($record) => $record ? "Post to LinkedIn: {$record->title}" : 'Post to LinkedIn')
            ->modalDescription('Share this guide to your LinkedIn network or company page. Use instant 1-click web composer or automated REST API.')
            ->modalSubmitActionLabel('Publish / Open LinkedIn')
            ->schema([
                \Filament\Forms\Components\Select::make('method')
                    ->label('Publishing Method')
                    ->options([
                        'feed_intent' => '⚡ 1-Click LinkedIn Composer (Pre-fills text, link & hashtags)',
                        'share_dialog' => '🔗 LinkedIn Share Dialog (Standard link preview dialog)',
                        'api' => '🤖 Direct API Post (Automated publishing via LinkedIn API)',
                    ])
                    ->default('feed_intent')
                    ->required(),

                \Filament\Forms\Components\TextInput::make('public_url')
                    ->label('Article Canonical URL')
                    ->default(fn ($record) => $record?->public_url)
                    ->disabled(),

                \Filament\Forms\Components\Textarea::make('commentary')
                    ->label('LinkedIn Post Text & Hashtags')
                    ->helperText('Edit the post text and hashtags before sharing.')
                    ->rows(8)
                    ->default(fn ($record) => $record ? self::formatPostText($record) : '')
                    ->required(),

                \Filament\Forms\Components\Placeholder::make('api_status')
                    ->label('API Status')
                    ->content(fn () => self::isApiConfigured()
                        ? '✅ LinkedIn API is configured in Settings. Direct posting is ready!'
                        : 'ℹ️ LinkedIn API token not configured in Settings. (1-Click Composer works instantly with zero setup!)'
                    ),
            ])
            ->action(function ($record, array $data, $livewire): void {
                $blogPost = $record ?? ($livewire && method_exists($livewire, 'getRecord') ? $livewire->getRecord() : null);

                if (! $blogPost) {
                    \Filament\Notifications\Notification::make()
                        ->title('Error')
                        ->body('Blog post record not found.')
                        ->danger()
                        ->send();

                    return;
                }

                if ($data['method'] === 'api') {
                    $res = self::publishViaApi($blogPost, $data['commentary']);
                    if ($res['success']) {
                        \Filament\Notifications\Notification::make()
                            ->title('Successfully Posted to LinkedIn!')
                            ->body($res['message'])
                            ->success()
                            ->send();
                    } else {
                        \Filament\Notifications\Notification::make()
                            ->title('LinkedIn API Error')
                            ->body($res['message'])
                            ->danger()
                            ->send();
                    }
                } elseif ($data['method'] === 'share_dialog') {
                    $url = self::getShareOffsiteUrl($blogPost);
                    $blogPost->update(['linkedin_shared_at' => now()]);
                    $livewire->js("window.open(" . json_encode($url) . ", '_blank');");
                    \Filament\Notifications\Notification::make()
                        ->title('Opened LinkedIn Share Dialog')
                        ->body('Article marked as shared.')
                        ->success()
                        ->send();
                } else {
                    $url = self::getShareFeedIntentUrl($blogPost, $data['commentary']);
                    $blogPost->update(['linkedin_shared_at' => now()]);
                    $livewire->js("window.open(" . json_encode($url) . ", '_blank');");
                    \Filament\Notifications\Notification::make()
                        ->title('Opened LinkedIn Composer')
                        ->body('Post drafted with engaging copy and hashtags!')
                        ->success()
                        ->send();
                }
            });
    }
}
