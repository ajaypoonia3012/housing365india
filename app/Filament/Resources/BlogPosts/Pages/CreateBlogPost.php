<?php

namespace App\Filament\Resources\BlogPosts\Pages;

use App\Filament\Resources\BlogPosts\BlogPostResource;
use App\Services\LinkedInPublishingService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateBlogPost extends CreateRecord
{
    protected static string $resource = BlogPostResource::class;

    protected function afterCreate(): void
    {
        $record = $this->getRecord();
        if ($record->auto_post_to_linkedin && $record->is_published) {
            if (LinkedInPublishingService::isApiConfigured()) {
                $result = LinkedInPublishingService::publishViaApi($record);
                if ($result['success']) {
                    Notification::make()
                        ->title('Auto-posted to LinkedIn!')
                        ->body($result['message'])
                        ->success()
                        ->send();
                } else {
                    Notification::make()
                        ->title('LinkedIn Auto-post Notice')
                        ->body($result['message'])
                        ->warning()
                        ->send();
                }
            }
        }
    }
}
