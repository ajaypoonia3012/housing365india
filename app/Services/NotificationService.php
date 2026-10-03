<?php

namespace App\Services;

use App\Models\CrmNotification;
use App\Models\NotificationPreference;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send notification across channels respecting user preferences.
     */
    public function send(User $user, string $type, string $title, string $message, array $metadata = []): CrmNotification
    {
        // 1. Get or create notification preference
        $preference = NotificationPreference::firstOrCreate(
            ['user_id' => $user->id, 'notification_type' => $type],
            [
                'in_app_enabled' => true,
                'email_enabled' => true,
                'sms_enabled' => true,
                'whatsapp_enabled' => true,
            ]
        );

        $channelsDelivered = [];

        // 2. In-App notification
        if ($preference->in_app_enabled) {
            $channelsDelivered[] = 'in_app';
        }

        // 3. Email Channel Abstraction
        if ($preference->email_enabled && ! empty($user->email)) {
            $this->dispatchEmail($user->email, $title, $message);
            $channelsDelivered[] = 'email';
        }

        // 4. SMS Channel Abstraction
        $phone = $user->mobile ?? $user->phone;
        if ($preference->sms_enabled && ! empty($phone)) {
            $this->dispatchSms($phone, $message);
            $channelsDelivered[] = 'sms';
        }

        // 5. WhatsApp Channel Abstraction
        if ($preference->whatsapp_enabled && ! empty($phone)) {
            $this->dispatchWhatsApp($phone, $message);
            $channelsDelivered[] = 'whatsapp';
        }

        return CrmNotification::create([
            'user_id' => $user->id,
            'type' => $type,
            'channel' => implode(',', $channelsDelivered) ?: 'in_app',
            'title' => $title,
            'message' => $message,
            'data' => array_merge($metadata, ['channels' => $channelsDelivered]),
            'status' => 'delivered',
            'read_at' => null,
        ]);
    }

    protected function dispatchEmail(string $email, string $title, string $message): void
    {
        Log::channel('single')->info("[CRM Notification - Email] To: {$email} | Subject: {$title}");
    }

    protected function dispatchSms(string $phone, string $message): void
    {
        Log::channel('single')->info("[CRM Notification - SMS] To: {$phone} | Msg: {$message}");
    }

    protected function dispatchWhatsApp(string $phone, string $message): void
    {
        Log::channel('single')->info("[CRM Notification - WhatsApp] To: {$phone} | Msg: {$message}");
    }
}
