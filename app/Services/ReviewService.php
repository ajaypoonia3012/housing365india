<?php

namespace App\Services;

use App\Models\CoworkingBooking;
use App\Models\Inquiry;
use App\Models\PgBooking;
use App\Models\Property;
use App\Models\RentAccount;
use App\Models\Review;
use App\Models\ReviewModerationAction;
use App\Models\ReviewReport;
use App\Models\ReviewResponse;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Models\WorkOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class ReviewService
{
    public function __construct(
        protected NotificationService $notifications,
        protected AnalyticsService $analytics
    ) {}

    /**
     * Check if a user is eligible to review a given subject.
     */
    public function checkEligibility(
        User $user,
        Model $subject,
        ?string $interactionType = null,
        ?int $interactionId = null
    ): array {
        // 1. Prevent Self-Review
        if ($subject instanceof Property && $subject->user_id === $user->id) {
            return ['eligible' => false, 'reason' => 'Self-review is prohibited. You cannot review your own listing.'];
        }

        if ($subject instanceof User && $subject->id === $user->id) {
            return ['eligible' => false, 'reason' => 'Self-review is prohibited. You cannot review your own profile.'];
        }

        // 2. Prevent Duplicate Reviews for Same Interaction
        $duplicateQuery = Review::where('user_id', $user->id)
            ->where('subject_type', get_class($subject))
            ->where('subject_id', $subject->id);

        if ($interactionType && $interactionId) {
            $duplicateQuery->where('interaction_type', $interactionType)
                ->where('interaction_id', $interactionId);
        }

        if ($duplicateQuery->exists()) {
            return ['eligible' => false, 'reason' => 'You have already submitted a review for this interaction.'];
        }

        // 3. Verify Qualifying Interaction
        $hasInteraction = false;
        $resolvedType = $interactionType;
        $resolvedId = $interactionId;

        if ($subject instanceof Property) {
            if ($interactionType && $interactionId) {
                $hasInteraction = $this->verifyPropertyInteraction($user, $subject, $interactionType, $interactionId);
            } else {
                // Discover any valid interaction
                $discovery = $this->discoverPropertyInteraction($user, $subject);
                $hasInteraction = $discovery['found'];
                $resolvedType = $discovery['type'] ?? null;
                $resolvedId = $discovery['id'] ?? null;
            }
        } elseif ($subject instanceof User) {
            // User subject (e.g. Vendor, Broker, Owner)
            if ($interactionType && $interactionId) {
                $hasInteraction = $this->verifyUserInteraction($user, $subject, $interactionType, $interactionId);
            } else {
                $discovery = $this->discoverUserInteraction($user, $subject);
                $hasInteraction = $discovery['found'];
                $resolvedType = $discovery['type'] ?? null;
                $resolvedId = $discovery['id'] ?? null;
            }
        } else {
            // General subject - if interaction passed, check it exists
            $hasInteraction = ! empty($interactionType);
        }

        if (! $hasInteraction) {
            return [
                'eligible' => false,
                'reason' => 'A verified qualifying interaction (e.g. completed viewing, rental tenancy, booking, or completed service) is required before submitting a review.',
            ];
        }

        return [
            'eligible' => true,
            'interaction_type' => $resolvedType,
            'interaction_id' => $resolvedId,
        ];
    }

    /**
     * Submit a legitimate review after server-side eligibility check.
     */
    public function submitReview(User $user, Model $subject, array $data): Review
    {
        $eligibility = $this->checkEligibility(
            $user,
            $subject,
            $data['interaction_type'] ?? null,
            $data['interaction_id'] ?? null
        );

        if (! $eligibility['eligible']) {
            throw ValidationException::withMessages([
                'eligibility' => [$eligibility['reason']],
            ]);
        }

        $rating = max(1, min(5, (int) ($data['rating'] ?? 5)));

        $review = Review::create([
            'subject_type' => get_class($subject),
            'subject_id' => $subject->id,
            'user_id' => $user->id,
            'interaction_type' => $eligibility['interaction_type'] ?? ($data['interaction_type'] ?? null),
            'interaction_id' => $eligibility['interaction_id'] ?? ($data['interaction_id'] ?? null),
            'rating' => $rating,
            'title' => trim($data['title']),
            'body' => trim($data['body']),
            'status' => Review::STATUS_PUBLISHED, // Published upon verified qualifying interaction
            'submitted_at' => now(),
            'published_at' => now(),
        ]);

        $this->analytics->logEvent(
            'review_submitted',
            $subject instanceof Property ? $subject : null,
            $user,
            ['rating' => $rating, 'review_id' => $review->id]
        );

        // Notify subject owner/operator if applicable
        $owner = $this->resolveSubjectOwner($subject);
        if ($owner && $owner->id !== $user->id) {
            $this->notifications->send(
                $owner,
                'review_received',
                'New Customer Review Received',
                "A verified customer left a {$rating}-star review on your listing.",
                ['review_id' => $review->id, 'rating' => $rating]
            );
        }

        return $review;
    }

    /**
     * Admin review moderation.
     */
    public function moderateReview(
        Review $review,
        User $admin,
        string $action,
        ?string $reason = null,
        ?string $notes = null
    ): Review {
        $status = match (strtolower($action)) {
            'approve', 'publish' => Review::STATUS_PUBLISHED,
            'reject' => Review::STATUS_REJECTED,
            'hide' => Review::STATUS_HIDDEN,
            'flag' => Review::STATUS_FLAGGED,
            default => Review::STATUS_HIDDEN,
        };

        $review->update([
            'status' => $status,
            'moderated_at' => now(),
            'moderated_by' => $admin->id,
            'moderation_reason' => $reason,
            'published_at' => $status === Review::STATUS_PUBLISHED ? ($review->published_at ?? now()) : null,
        ]);

        ReviewModerationAction::create([
            'review_id' => $review->id,
            'admin_id' => $admin->id,
            'action' => $action,
            'reason' => $reason,
            'notes' => $notes,
        ]);

        return $review;
    }

    /**
     * Official response from verified subject owner or operator.
     */
    public function submitResponse(User $user, Review $review, string $body): ReviewResponse
    {
        $subject = $review->subject;
        $authorized = false;

        if ($subject instanceof Property && $subject->user_id === $user->id) {
            $authorized = true;
        } elseif ($subject instanceof User && $subject->id === $user->id) {
            $authorized = true;
        } elseif ($user->isAdmin()) {
            $authorized = true;
        }

        if (! $authorized) {
            throw ValidationException::withMessages([
                'authorization' => ['Only the verified owner or operator of this subject can respond to reviews.'],
            ]);
        }

        return ReviewResponse::create([
            'review_id' => $review->id,
            'responder_id' => $user->id,
            'body' => trim($body),
            'status' => ReviewResponse::STATUS_PUBLISHED,
        ]);
    }

    /**
     * Report an abusive or fake review.
     */
    public function reportReview(User $user, Review $review, string $reason, ?string $details = null): ReviewReport
    {
        $report = ReviewReport::create([
            'review_id' => $review->id,
            'reported_by_user_id' => $user->id,
            'reason' => $reason,
            'details' => $details,
            'status' => ReviewReport::STATUS_PENDING,
        ]);

        // Auto-flag review if report count exceeds 3
        if ($review->reports()->count() >= 3) {
            $review->update(['status' => Review::STATUS_FLAGGED]);
        }

        return $report;
    }

    /**
     * Retrieve published reviews for public display.
     */
    public function getPublicReviews(Model $subject, int $perPage = 10): LengthAwarePaginator
    {
        return Review::with(['user', 'publishedResponses.responder'])
            ->where('subject_type', get_class($subject))
            ->where('subject_id', $subject->id)
            ->where('status', Review::STATUS_PUBLISHED)
            ->latest('published_at')
            ->paginate($perPage);
    }

    /**
     * Verify specific property interaction.
     */
    protected function verifyPropertyInteraction(User $user, Property $property, string $type, int $id): bool
    {
        return match (strtolower($type)) {
            'viewing', 'inquiry' => Inquiry::where('id', $id)
                ->where('property_id', $property->id)
                ->where(function ($q) use ($user) {
                    $q->where('email', $user->email);
                    if (! empty($user->phone)) {
                        $q->orWhere('phone', $user->phone);
                    }
                })->exists(),
            'rental', 'tenancy' => RentAccount::where('id', $id)
                ->where('property_id', $property->id)
                ->where('tenant_id', $user->id)
                ->exists(),
            'pg_booking' => PgBooking::where('id', $id)
                ->where('property_id', $property->id)
                ->where('user_id', $user->id)
                ->exists(),
            'coworking_booking' => CoworkingBooking::where('id', $id)
                ->where('property_id', $property->id)
                ->where('user_id', $user->id)
                ->exists(),
            default => false,
        };
    }

    /**
     * Discover any interaction for a property.
     */
    protected function discoverPropertyInteraction(User $user, Property $property): array
    {
        // 1. Check Tenancy
        $rent = RentAccount::where('property_id', $property->id)->where('tenant_id', $user->id)->first();
        if ($rent) {
            return ['found' => true, 'type' => 'rental', 'id' => $rent->id];
        }

        // 2. Check PG Booking
        $pg = PgBooking::where('property_id', $property->id)->where('user_id', $user->id)->first();
        if ($pg) {
            return ['found' => true, 'type' => 'pg_booking', 'id' => $pg->id];
        }

        // 3. Check Coworking Booking
        $cw = CoworkingBooking::where('property_id', $property->id)->where('user_id', $user->id)->first();
        if ($cw) {
            return ['found' => true, 'type' => 'coworking_booking', 'id' => $cw->id];
        }

        // 4. Check Inquiry/Viewing
        $inquiry = Inquiry::where('property_id', $property->id)
            ->where(function ($q) use ($user) {
                $q->where('email', $user->email);
                if (! empty($user->phone)) {
                    $q->orWhere('phone', $user->phone);
                }
            })->first();

        if ($inquiry) {
            return ['found' => true, 'type' => 'viewing', 'id' => $inquiry->id];
        }

        return ['found' => false];
    }

    /**
     * Verify interaction with a specific user subject (Vendor/Care/Broker).
     */
    protected function verifyUserInteraction(User $user, User $subjectUser, string $type, int $id): bool
    {
        return match (strtolower($type)) {
            'care_work_order', 'work_order' => WorkOrder::where('id', $id)
                ->where('vendor_id', $subjectUser->id)
                ->whereHas('serviceRequest', fn ($q) => $q->where('user_id', $user->id))
                ->exists(),
            'service_request' => ServiceRequest::where('id', $id)
                ->where('user_id', $user->id)
                ->exists(),
            default => false,
        };
    }

    /**
     * Discover interaction with a specific user subject.
     */
    protected function discoverUserInteraction(User $user, User $subjectUser): array
    {
        $wo = WorkOrder::where('vendor_id', $subjectUser->id)
            ->whereHas('serviceRequest', fn ($q) => $q->where('user_id', $user->id))
            ->first();

        if ($wo) {
            return ['found' => true, 'type' => 'care_work_order', 'id' => $wo->id];
        }

        return ['found' => false];
    }

    /**
     * Resolve owner/operator of a subject.
     */
    protected function resolveSubjectOwner(Model $subject): ?User
    {
        if ($subject instanceof Property) {
            return $subject->owner ?? ($subject->user_id ? User::find($subject->user_id) : null);
        }

        if ($subject instanceof User) {
            return $subject;
        }

        return null;
    }
}
