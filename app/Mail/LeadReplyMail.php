<?php

namespace App\Mail;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class LeadReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Lead $lead,
        public string $emailSubject,
        public string $emailMessage,
        public ?string $attachmentPath = null,
        public ?string $senderName = 'Housing365 India Support'
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->emailSubject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.lead-reply',
            with: [
                'lead' => $this->lead,
                'emailSubject' => $this->emailSubject,
                'emailMessage' => $this->emailMessage,
                'senderName' => $this->senderName,
            ],
        );
    }

    public function attachments(): array
    {
        if (! $this->attachmentPath) {
            return [];
        }

        $filePath = $this->attachmentPath;
        if (! file_exists($filePath)) {
            $storagePath = Storage::disk('public')->path($this->attachmentPath);
            if (file_exists($storagePath)) {
                $filePath = $storagePath;
            }
        }

        if (file_exists($filePath)) {
            return [
                Attachment::fromPath($filePath),
            ];
        }

        return [];
    }
}
