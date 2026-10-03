<?php

namespace App\Services;

use App\Models\SubjectBadge;
use App\Models\TrustBadge;
use App\Models\User;
use App\Models\Verification;
use App\Models\VerificationDocument;
use App\Models\VerificationEvent;
use App\Models\VerificationType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class VerificationService
{
    public function __construct(
        protected NotificationService $notifications,
        protected AnalyticsService $analytics
    ) {}

    /**
     * Submit a verification request for a subject.
     */
    public function submitVerification(
        Model $subject,
        User $user,
        string $verificationTypeCode,
        array $evidence = [],
        array $files = []
    ): Verification {
        $type = VerificationType::where('code', $verificationTypeCode)->first();

        $verification = Verification::create([
            'subject_type' => get_class($subject),
            'subject_id' => $subject->id,
            'user_id' => $user->id,
            'verification_type_id' => $type?->id ?? 1,
            'verification_type_code' => $verificationTypeCode,
            'status' => Verification::STATUS_PENDING,
            'submitted_at' => now(),
            'evidence_references' => $evidence,
        ]);

        // Upload attached documents if any
        foreach ($files as $docKey => $file) {
            if ($file instanceof UploadedFile) {
                $this->uploadPrivateDocument($user, $file, is_string($docKey) ? $docKey : 'document', $verification);
            }
        }

        VerificationEvent::create([
            'verification_id' => $verification->id,
            'actor_id' => $user->id,
            'from_status' => null,
            'to_status' => Verification::STATUS_PENDING,
            'action' => 'submitted',
            'reason' => 'User submitted verification documentation',
        ]);

        $this->notifications->send(
            $user,
            'verification_submitted',
            'Verification Submitted',
            "Your verification for {$verificationTypeCode} has been submitted and is under review.",
            ['verification_id' => $verification->id]
        );

        $this->analytics->logEvent(
            'verification_submitted',
            method_exists($subject, 'getTable') && $subject->getTable() === 'properties' ? $subject : null,
            $user,
            ['verification_type' => $verificationTypeCode]
        );

        return $verification;
    }

    /**
     * Upload and securely store a private verification document.
     */
    public function uploadPrivateDocument(
        User $user,
        UploadedFile $file,
        string $docType,
        ?Verification $verification = null,
        ?string $documentNumber = null
    ): VerificationDocument {
        // Private local disk (storage/app/private)
        $path = $file->store('verification_documents/'.date('Y/m'), 'local');
        $hash = hash_file('sha256', $file->getRealPath());

        $masked = $documentNumber ? VerificationDocument::maskIdentifier($documentNumber, $docType) : null;

        return VerificationDocument::create([
            'verification_id' => $verification?->id,
            'user_id' => $user->id,
            'document_type' => strtolower($docType),
            'document_number_masked' => $masked,
            'file_path' => $path,
            'file_hash' => $hash,
            'original_filename' => $file->getClientOriginalName(),
            'ocr_status' => VerificationDocument::OCR_PENDING,
            'verification_status' => VerificationDocument::STATUS_PENDING,
            'retention_until' => now()->addDays(365 * 3), // 3 years retention policy
        ]);
    }

    /**
     * Admin review verification request.
     */
    public function reviewVerification(
        Verification $verification,
        User $reviewer,
        string $status,
        ?string $reason = null,
        ?string $notes = null
    ): Verification {
        $previousStatus = $verification->status;
        $validityDays = $verification->verificationType?->validity_days ?? 365;

        $verification->update([
            'status' => $status,
            'reviewed_at' => now(),
            'reviewed_by' => $reviewer->id,
            'expires_at' => $status === Verification::STATUS_VERIFIED ? now()->addDays($validityDays) : null,
            'rejection_reason' => $status === Verification::STATUS_REJECTED ? $reason : null,
            'internal_notes' => $notes,
        ]);

        VerificationEvent::create([
            'verification_id' => $verification->id,
            'actor_id' => $reviewer->id,
            'from_status' => $previousStatus,
            'to_status' => $status,
            'action' => strtolower($status),
            'reason' => $reason,
            'metadata' => ['notes' => $notes],
        ]);

        // Auto-award or revoke corresponding trust badge
        if ($status === Verification::STATUS_VERIFIED) {
            $badgeSlug = $this->resolveBadgeSlug($verification->verification_type_code);
            if ($badgeSlug) {
                $this->awardBadge($verification->subject, $badgeSlug, $verification, $validityDays);
            }

            // If property verification, sync is_verified on property
            if ($verification->subject_type === 'App\\Models\\Property' && $verification->subject) {
                $verification->subject->update([
                    'is_verified' => true,
                    'verification_level' => 'housing365_verified',
                ]);
            }
        } elseif (in_array($status, [Verification::STATUS_REJECTED, Verification::STATUS_SUSPENDED, Verification::STATUS_EXPIRED], true)) {
            // Revoke active badge associated with this verification
            SubjectBadge::where('verification_id', $verification->id)->update([
                'is_active' => false,
                'revoked_at' => now(),
                'revocation_reason' => "Verification {$status}: {$reason}",
            ]);

            if ($verification->subject_type === 'App\\Models\\Property' && $verification->subject) {
                $verification->subject->update(['is_verified' => false]);
            }
        }

        if ($verification->user) {
            $title = $status === Verification::STATUS_VERIFIED ? 'Verification Approved' : 'Verification Update';
            $msg = $status === Verification::STATUS_VERIFIED
                ? "Your verification for {$verification->verification_type_code} has been approved."
                : "Your verification status has been updated to {$status}. ".($reason ? "Reason: {$reason}" : '');

            $this->notifications->send($verification->user, 'verification_reviewed', $title, $msg, [
                'verification_id' => $verification->id,
                'status' => $status,
            ]);
        }

        return $verification;
    }

    /**
     * Award a verified trust badge to a subject.
     */
    public function awardBadge(
        Model $subject,
        string $badgeSlug,
        ?Verification $verification = null,
        ?int $validityDays = 365
    ): SubjectBadge {
        $badge = TrustBadge::firstOrCreate(
            ['slug' => $badgeSlug],
            [
                'name' => ucwords(str_replace('_', ' ', strtolower($badgeSlug))),
                'description' => 'Verified through Housing365 verification protocol',
                'is_active' => true,
            ]
        );

        return SubjectBadge::updateOrCreate(
            [
                'subject_type' => get_class($subject),
                'subject_id' => $subject->id,
                'trust_badge_id' => $badge->id,
            ],
            [
                'verification_id' => $verification?->id,
                'is_active' => true,
                'assigned_at' => now(),
                'expires_at' => $validityDays ? now()->addDays($validityDays) : null,
                'revoked_at' => null,
                'revocation_reason' => null,
            ]
        );
    }

    /**
     * Revoke a trust badge.
     */
    public function revokeBadge(SubjectBadge $badge, string $reason): void
    {
        $badge->update([
            'is_active' => false,
            'revoked_at' => now(),
            'revocation_reason' => $reason,
        ]);
    }

    /**
     * Check and expire badges that have passed validity date.
     */
    public function checkExpiredBadges(): int
    {
        return SubjectBadge::where('is_active', true)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->update([
                'is_active' => false,
                'revoked_at' => now(),
                'revocation_reason' => 'Badge expired after validity period',
            ]);
    }

    /**
     * Check if subject has an active badge.
     */
    public function hasActiveBadge(Model $subject, string $badgeSlug): bool
    {
        return SubjectBadge::where('subject_type', get_class($subject))
            ->where('subject_id', $subject->id)
            ->whereHas('trustBadge', fn ($q) => $q->where('slug', $badgeSlug))
            ->active()
            ->exists();
    }

    /**
     * Get active badges for a subject.
     */
    public function getSubjectBadges(Model $subject): Collection
    {
        return SubjectBadge::with('trustBadge')
            ->where('subject_type', get_class($subject))
            ->where('subject_id', $subject->id)
            ->active()
            ->get();
    }

    /**
     * Map verification type code to trust badge slug.
     */
    protected function resolveBadgeSlug(string $typeCode): ?string
    {
        return match ($typeCode) {
            'OWNER_IDENTITY' => TrustBadge::BADGE_IDENTITY_VERIFIED,
            'PROPERTY_OWNERSHIP', 'PROPERTY_DOCUMENT' => TrustBadge::BADGE_PROPERTY_VERIFIED,
            'BROKER_IDENTITY', 'BROKER_LICENSE' => TrustBadge::BADGE_BROKER_VERIFIED,
            'BUILDER_IDENTITY' => TrustBadge::BADGE_BUILDER_VERIFIED,
            'BUILDER_RERA' => TrustBadge::BADGE_RERA_VERIFIED,
            'PG_OPERATOR_IDENTITY', 'PG_PROPERTY' => TrustBadge::BADGE_PG_VERIFIED,
            'VENDOR_IDENTITY', 'VENDOR_BUSINESS' => TrustBadge::BADGE_VENDOR_VERIFIED,
            'HOUSING365_CARE' => TrustBadge::BADGE_HOUSING365_CARE_PROVIDER,
            default => TrustBadge::BADGE_IDENTITY_VERIFIED,
        };
    }
}
