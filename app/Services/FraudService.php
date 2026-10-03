<?php

namespace App\Services;

use App\Models\AccountSuspension;
use App\Models\Appeal;
use App\Models\FraudReview;
use App\Models\FraudRule;
use App\Models\Lead;
use App\Models\Property;
use App\Models\Referral;
use App\Models\RiskEvent;
use App\Models\RiskFlag;
use App\Models\User;
use App\Models\Verification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class FraudService
{
    public function __construct(
        protected NotificationService $notifications,
        protected AnalyticsService $analytics
    ) {}

    /**
     * Evaluate risk for an activity event.
     */
    public function evaluateRisk(
        string $eventType,
        ?User $user = null,
        ?Model $subject = null,
        array $context = []
    ): array {
        $riskScore = 0.0;
        $triggeredRules = [];
        $recommendedAction = FraudRule::ACTION_NONE;

        // 1. Check excessive lead creation
        if ($eventType === 'lead_creation' && $user) {
            $recentLeadsCount = Lead::where('user_id', $user->id)
                ->where('created_at', '>=', now()->subHour())
                ->count();

            if ($recentLeadsCount >= 5) {
                $riskScore += 40.0;
                $triggeredRules[] = 'excessive_lead_creation';
                $recommendedAction = FraudRule::ACTION_SEND_TO_REVIEW;
            }
        }

        // 2. Check self-referral
        if ($eventType === 'referral_submission' && $user) {
            $phone = $context['referred_phone'] ?? null;
            $email = $context['referred_email'] ?? null;

            if (($phone && $phone === $user->phone) || ($email && strtolower($email) === strtolower($user->email))) {
                $riskScore += 80.0;
                $triggeredRules[] = 'self_referral';
                $recommendedAction = FraudRule::ACTION_REQUIRE_VERIFICATION;
            }
        }

        // 3. Check repeated failed verification
        if ($eventType === 'verification_submission' && $user) {
            $rejectedCount = Verification::where('user_id', $user->id)
                ->where('status', Verification::STATUS_REJECTED)
                ->count();

            if ($rejectedCount >= 3) {
                $riskScore += 50.0;
                $triggeredRules[] = 'repeated_failed_verification';
                $recommendedAction = FraudRule::ACTION_SEND_TO_REVIEW;
            }
        }

        // 4. Record Risk Event Telemetry (IP is hashed for privacy)
        $ip = request()?->ip() ?? '127.0.0.1';
        $ipHash = hash('sha256', $ip);

        // Sanitize context for privacy (no phone/email in payloads)
        $safePayload = $context;
        unset($safePayload['phone'], $safePayload['email'], $safePayload['password'], $safePayload['referred_phone'], $safePayload['referred_email']);

        RiskEvent::create([
            'user_id' => $user?->id,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject?->id,
            'event_type' => $eventType,
            'risk_score' => $riskScore,
            'payload' => $safePayload,
            'ip_hash' => $ipHash,
            'created_at' => now(),
        ]);

        // 5. Create Risk Flag and Fraud Review if score is high
        if ($riskScore >= 40.0 && $subject) {
            $severity = $riskScore >= 75.0 ? RiskFlag::SEVERITY_HIGH : RiskFlag::SEVERITY_MEDIUM;

            $this->flagRisk(
                $subject,
                $triggeredRules[0] ?? 'unusual_behavior',
                $severity,
                ['risk_score' => $riskScore, 'rules' => $triggeredRules],
                $user
            );

            if ($recommendedAction === FraudRule::ACTION_SEND_TO_REVIEW) {
                FraudReview::firstOrCreate(
                    [
                        'subject_type' => get_class($subject),
                        'subject_id' => $subject->id,
                        'status' => FraudReview::STATUS_PENDING,
                    ],
                    [
                        'user_id' => $user?->id,
                        'trigger_reason' => implode(', ', $triggeredRules),
                    ]
                );
            }
        }

        return [
            'risk_score' => $riskScore,
            'rules' => $triggeredRules,
            'action' => $recommendedAction,
        ];
    }

    /**
     * Create an auditable risk flag on a subject.
     */
    public function flagRisk(
        Model $subject,
        string $flagType,
        string $severity = RiskFlag::SEVERITY_MEDIUM,
        array $details = [],
        ?User $user = null
    ): RiskFlag {
        return RiskFlag::create([
            'subject_type' => get_class($subject),
            'subject_id' => $subject->id,
            'user_id' => $user?->id,
            'flag_type' => $flagType,
            'severity' => $severity,
            'status' => RiskFlag::STATUS_ACTIVE,
            'details' => $details,
        ]);
    }

    /**
     * Detect suspected duplicate property listings. Never auto-deletes.
     */
    public function detectDuplicateProperty(Property $property): ?RiskFlag
    {
        $normalizedAddress = strtolower(trim(preg_replace('/[^a-zA-Z0-9\s]/', '', $property->address ?? '')));

        if (empty($normalizedAddress) && empty($property->title)) {
            return null;
        }

        $duplicateQuery = Property::where('id', '!=', $property->id)
            ->where('status', 'active');

        if ($property->city) {
            $duplicateQuery->where('city', $property->city);
        }

        if ($property->property_type) {
            $duplicateQuery->where('property_type', $property->property_type);
        }

        if ($property->bedrooms) {
            $duplicateQuery->where('bedrooms', $property->bedrooms);
        }

        $candidates = $duplicateQuery->get();

        foreach ($candidates as $candidate) {
            $candidateNorm = strtolower(trim(preg_replace('/[^a-zA-Z0-9\s]/', '', $candidate->address ?? '')));

            // Match address or identical title + locality
            $addressMatch = ! empty($normalizedAddress) && ! empty($candidateNorm) && ($normalizedAddress === $candidateNorm || str_contains($normalizedAddress, $candidateNorm) || str_contains($candidateNorm, $normalizedAddress));
            $titleMatch = strtolower(trim($property->title)) === strtolower(trim($candidate->title));

            if ($addressMatch || $titleMatch) {
                return $this->flagRisk(
                    $property,
                    'duplicate_property_suspected',
                    RiskFlag::SEVERITY_MEDIUM,
                    [
                        'matched_property_id' => $candidate->id,
                        'reason' => 'Similar address, BHK, and locality detected',
                    ],
                    $property->user_id ? User::find($property->user_id) : null
                );
            }
        }

        return null;
    }

    /**
     * Controlled account suspension. User data is never deleted.
     */
    public function suspendAccount(
        User $user,
        string $type,
        string $reason,
        User $admin,
        ?Carbon $endsAt = null
    ): AccountSuspension {
        $suspension = AccountSuspension::create([
            'user_id' => $user->id,
            'suspension_type' => $type,
            'reason' => $reason,
            'starts_at' => now(),
            'ends_at' => $endsAt,
            'is_active' => true,
            'created_by' => $admin->id,
            'appeal_status' => AccountSuspension::APPEAL_NONE,
        ]);

        $this->notifications->send(
            $user,
            'account_suspended',
            'Account Restricted',
            "Your Housing365 account has been {$type} suspended. Reason: {$reason}. You may submit an appeal from your dashboard.",
            ['suspension_id' => $suspension->id]
        );

        return $suspension;
    }

    /**
     * Reinstate a suspended account upon appeal or administrative review.
     */
    public function reinstateAccount(AccountSuspension $suspension, User $admin, string $reason): void
    {
        $suspension->update([
            'is_active' => false,
            'appeal_status' => AccountSuspension::APPEAL_OVERTURNED,
        ]);

        $user = $suspension->user;
        if ($user) {
            $this->notifications->send(
                $user,
                'account_reinstated',
                'Account Reinstated',
                "Your Housing365 account suspension has been lifted. {$reason}",
                ['suspension_id' => $suspension->id]
            );
        }
    }

    /**
     * Submit an appeal for a rejected verification, suspension, listing, or review.
     */
    public function submitAppeal(
        User $user,
        Model $appealable,
        string $reason,
        array $evidence = []
    ): Appeal {
        $appeal = Appeal::create([
            'user_id' => $user->id,
            'appealable_type' => get_class($appealable),
            'appealable_id' => $appealable->id,
            'reason' => trim($reason),
            'evidence_data' => $evidence,
            'status' => Appeal::STATUS_SUBMITTED,
        ]);

        if ($appealable instanceof AccountSuspension) {
            $appealable->update(['appeal_status' => AccountSuspension::APPEAL_APPEALED]);
        }

        $this->notifications->send(
            $user,
            'appeal_submitted',
            'Appeal Received',
            'Your appeal has been received and will be reviewed by our compliance team.',
            ['appeal_id' => $appeal->id]
        );

        return $appeal;
    }

    /**
     * Admin review appeal decision.
     */
    public function reviewAppeal(
        Appeal $appeal,
        User $admin,
        string $decision,
        ?string $adminResponse = null
    ): Appeal {
        $status = strtoupper($decision) === 'APPROVED' ? Appeal::STATUS_APPROVED : Appeal::STATUS_REJECTED;

        $appeal->update([
            'status' => $status,
            'admin_response' => $adminResponse,
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
        ]);

        $appealable = $appeal->appealable;

        if ($status === Appeal::STATUS_APPROVED) {
            if ($appealable instanceof AccountSuspension) {
                $this->reinstateAccount($appealable, $admin, $adminResponse ?? 'Appeal approved by compliance');
            } elseif ($appealable instanceof Verification) {
                $appealable->update(['status' => Verification::STATUS_PENDING]);
            }
        } elseif ($status === Appeal::STATUS_REJECTED) {
            if ($appealable instanceof AccountSuspension) {
                $appealable->update(['appeal_status' => AccountSuspension::APPEAL_REJECTED]);
            }
        }

        if ($appeal->user) {
            $this->notifications->send(
                $appeal->user,
                'appeal_reviewed',
                "Appeal {$status}",
                "Your appeal has been {$status} by compliance. ".($adminResponse ? "Notes: {$adminResponse}" : ''),
                ['appeal_id' => $appeal->id, 'status' => $status]
            );
        }

        return $appeal;
    }
}
