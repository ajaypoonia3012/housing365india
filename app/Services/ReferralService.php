<?php

namespace App\Services;

use App\Contracts\ReferralPayoutGatewayInterface;
use App\Models\Property;
use App\Models\Referral;
use App\Models\ReferralAuditLog;
use App\Models\ReferralPayout;
use App\Models\ReferralReward;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use InvalidArgumentException;

class ReferralService
{
    public function __construct(
        protected ?ReferralPayoutGatewayInterface $payoutGateway = null
    ) {
        $this->payoutGateway = $payoutGateway ?? app(ReferralPayoutGatewayInterface::class);
    }

    /**
     * Normalize Indian mobile phone number to clean 10-digit format.
     */
    public function normalizePhone(?string $phone): string
    {
        if (empty($phone)) {
            return '';
        }

        // Strip non-digits
        $cleaned = preg_replace('/\D/', '', $phone);

        // Remove leading international code or zero
        if (str_starts_with($cleaned, '91') && strlen($cleaned) === 12) {
            $cleaned = substr($cleaned, 2);
        } elseif (str_starts_with($cleaned, '0') && strlen($cleaned) === 11) {
            $cleaned = substr($cleaned, 1);
        }

        // Keep last 10 digits if longer
        if (strlen($cleaned) > 10) {
            $cleaned = substr($cleaned, -10);
        }

        return $cleaned;
    }

    /**
     * Normalize email address.
     */
    public function normalizeEmail(?string $email): ?string
    {
        if (empty($email)) {
            return null;
        }

        return strtolower(trim($email));
    }

    /**
     * Submit a new property referral (Method A: Refer Owner, or Method B: Property Board Photo).
     */
    public function createReferral(User $referrer, array $data, ?UploadedFile $photo = null): Referral
    {
        // 1. Anti-abuse rate limiting (Max 25 referrals per day per user)
        $rateLimitKey = 'referral_submission:'.$referrer->id;
        if (RateLimiter::tooManyAttempts($rateLimitKey, 25)) {
            throw new InvalidArgumentException('You have reached the daily referral limit. Please try again tomorrow.');
        }
        RateLimiter::hit($rateLimitKey, 86400);

        // 2. Normalize contact details
        $rawPhone = $data['owner_phone'] ?? '';
        $normalizedPhone = $this->normalizePhone($rawPhone);
        if (strlen($normalizedPhone) !== 10) {
            throw new InvalidArgumentException('Please provide a valid 10-digit Indian mobile number for the property owner.');
        }

        $rawEmail = $data['owner_email'] ?? null;
        $normalizedEmail = $this->normalizeEmail($rawEmail);

        // 3. Self-referral prevention
        $referrerPhone = $this->normalizePhone($referrer->phone);
        $referrerEmail = $this->normalizeEmail($referrer->email);

        if ($normalizedPhone === $referrerPhone || ($normalizedEmail && $normalizedEmail === $referrerEmail)) {
            throw new InvalidArgumentException('Self-referral is not permitted. You cannot refer your own contact details.');
        }

        // 4. Duplicate check against existing active referrals (First-valid referral gets attribution)
        $existingReferral = Referral::where('normalized_owner_phone', $normalizedPhone)
            ->whereNotIn('status', [Referral::STATUS_REJECTED, Referral::STATUS_CANCELLED])
            ->first();

        if ($existingReferral) {
            throw new InvalidArgumentException('This owner contact has already been referred to Housing365 by another user.');
        }

        // 5. Existing property check
        $existingProperty = Property::where(function ($query) use ($normalizedPhone, $normalizedEmail) {
            $query->where('seller_phone', $normalizedPhone)
                ->orWhere('seller_phone', 'LIKE', '%'.$normalizedPhone);

            if ($normalizedEmail) {
                $query->orWhere('seller_email', $normalizedEmail);
            }
        })->first();

        if ($existingProperty) {
            throw new InvalidArgumentException('Property already exists on Housing365. Existing active listings are not eligible for a referral reward.');
        }

        // 6. Broker / Professional detection flag
        $fraudFlag = false;
        $fraudReasons = [];

        $isKnownBroker = User::where(function ($query) use ($normalizedPhone, $normalizedEmail) {
            $query->where('phone', $normalizedPhone);
            if ($normalizedEmail) {
                $query->orWhere('email', $normalizedEmail);
            }
        })->whereHas('roles', function ($q) {
            $q->whereIn('name', ['broker', 'builder']);
        })->exists();

        if ($isKnownBroker) {
            $fraudFlag = true;
            $fraudReasons[] = 'BROKER_CONTACT_DETECTED';
        }

        // 7. Handle secure photo upload for Method B
        $photoPath = null;
        $photoMime = null;
        $photoSize = null;

        if ($photo) {
            // Validate MIME type strictly
            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
            if (! in_array($photo->getMimeType(), $allowedMimes, true)) {
                throw new InvalidArgumentException('Invalid photo format. Only JPG, PNG, and WebP images are allowed.');
            }

            if ($photo->getSize() > 5 * 1024 * 1024) {
                throw new InvalidArgumentException('Photo file size must not exceed 5 MB.');
            }

            // Store in private disk (not publicly accessible directly)
            $photoPath = $photo->store('referral-boards', 'local');
            $photoMime = $photo->getMimeType();
            $photoSize = $photo->getSize();
        }

        $referralCode = $referrer->getOrCreateReferralCode();

        return DB::transaction(function () use (
            $referrer,
            $referralCode,
            $data,
            $normalizedPhone,
            $normalizedEmail,
            $photoPath,
            $photoMime,
            $photoSize,
            $fraudFlag,
            $fraudReasons
        ) {
            $referral = Referral::create([
                'referrer_user_id' => $referrer->id,
                'referral_code' => $referralCode,
                'submission_method' => $data['submission_method'] ?? Referral::METHOD_REFER_OWNER,
                'owner_name' => strip_tags(trim($data['owner_name'])),
                'owner_phone' => strip_tags(trim($data['owner_phone'])),
                'normalized_owner_phone' => $normalizedPhone,
                'owner_email' => $normalizedEmail,
                'normalized_owner_email' => $normalizedEmail,
                'state' => strip_tags(trim($data['state'] ?? 'India')),
                'city' => strip_tags(trim($data['city'])),
                'locality' => strip_tags(trim($data['locality'])),
                'property_type' => strip_tags(trim($data['property_type'] ?? 'Apartment')),
                'listing_intent' => strip_tags(trim($data['listing_intent'] ?? 'rent')),
                'property_address' => isset($data['property_address']) ? strip_tags(trim($data['property_address'])) : null,
                'notes' => isset($data['notes']) ? strip_tags(trim($data['notes'])) : null,
                'referrer_relationship' => isset($data['referrer_relationship']) ? strip_tags(trim($data['referrer_relationship'])) : null,
                'photo_path' => $photoPath,
                'photo_mime' => $photoMime,
                'photo_size' => $photoSize,
                'status' => Referral::STATUS_SUBMITTED,
                'qualification_status' => 'PENDING',
                'duplicate_flag' => false,
                'fraud_flag' => $fraudFlag,
                'fraud_reasons' => ! empty($fraudReasons) ? $fraudReasons : null,
                'submitted_at' => now(),
            ]);

            ReferralAuditLog::record($referral, 'referral_submitted', $referrer, null, Referral::STATUS_SUBMITTED, [
                'submission_method' => $referral->submission_method,
                'city' => $referral->city,
                'locality' => $referral->locality,
            ]);

            return $referral;
        });
    }

    /**
     * Server-side finite state transition validation for referrals.
     */
    public function updateReferralStatus(Referral $referral, string $newStatus, ?User $actor = null, ?string $reason = null, ?string $notes = null): Referral
    {
        $validTransitions = [
            Referral::STATUS_SUBMITTED => [
                Referral::STATUS_UNDER_REVIEW,
                Referral::STATUS_REJECTED,
                Referral::STATUS_CANCELLED,
            ],
            Referral::STATUS_UNDER_REVIEW => [
                Referral::STATUS_CONTACTED,
                Referral::STATUS_QUALIFIED,
                Referral::STATUS_REJECTED,
                Referral::STATUS_CANCELLED,
            ],
            Referral::STATUS_CONTACTED => [
                Referral::STATUS_QUALIFIED,
                Referral::STATUS_REJECTED,
                Referral::STATUS_CANCELLED,
            ],
            Referral::STATUS_QUALIFIED => [
                Referral::STATUS_PROPERTY_LISTED,
                Referral::STATUS_REJECTED,
                Referral::STATUS_CANCELLED,
            ],
            Referral::STATUS_PROPERTY_LISTED => [
                Referral::STATUS_REWARD_PENDING,
                Referral::STATUS_REWARD_APPROVED,
                Referral::STATUS_CANCELLED,
            ],
            Referral::STATUS_REWARD_PENDING => [
                Referral::STATUS_REWARD_APPROVED,
                Referral::STATUS_REJECTED,
                Referral::STATUS_CANCELLED,
            ],
            Referral::STATUS_REWARD_APPROVED => [
                Referral::STATUS_PAYOUT_PENDING,
                Referral::STATUS_PAID,
                Referral::STATUS_CANCELLED,
            ],
            Referral::STATUS_PAYOUT_PENDING => [
                Referral::STATUS_PAID,
                Referral::STATUS_REJECTED,
                Referral::STATUS_CANCELLED,
            ],
        ];

        $currentStatus = $referral->status;
        $allowed = $validTransitions[$currentStatus] ?? [];

        if (! in_array($newStatus, $allowed, true) && ! ($actor && $actor->hasRole('admin'))) {
            throw new InvalidArgumentException("Invalid referral status transition from {$currentStatus} to {$newStatus}.");
        }

        return DB::transaction(function () use ($referral, $newStatus, $actor, $reason, $notes, $currentStatus) {
            $updates = ['status' => $newStatus];

            if ($newStatus === Referral::STATUS_QUALIFIED && empty($referral->qualified_at)) {
                $updates['qualified_at'] = now();
                $updates['qualification_status'] = 'QUALIFIED';
            }

            if ($newStatus === Referral::STATUS_PROPERTY_LISTED && empty($referral->property_listed_at)) {
                $updates['property_listed_at'] = now();
            }

            if ($newStatus === Referral::STATUS_REWARD_PENDING && empty($referral->reward_eligible_at)) {
                $updates['reward_eligible_at'] = now();
            }

            if ($newStatus === Referral::STATUS_REJECTED) {
                $updates['rejection_reason'] = $reason ?? 'Referral did not qualify under Housing365 program criteria.';
            }

            if ($notes) {
                $updates['admin_notes'] = $notes;
            }

            $referral->update($updates);

            ReferralAuditLog::record($referral, 'status_updated', $actor, $currentStatus, $newStatus, [
                'reason' => $reason,
                'notes' => $notes,
            ]);

            return $referral->fresh();
        });
    }

    /**
     * Hook called when a property is published or listed to match against open referrals.
     * Generates the immutable ₹100 reward upon match.
     */
    public function matchAndQualifyListing(Property $property): ?Referral
    {
        $sellerPhone = $this->normalizePhone($property->seller_phone);
        $sellerEmail = $this->normalizeEmail($property->seller_email);

        if (empty($sellerPhone) && empty($sellerEmail)) {
            return null;
        }

        // Look for eligible referral waiting for property publication
        $referral = Referral::where(function ($query) use ($sellerPhone, $sellerEmail) {
            if (! empty($sellerPhone)) {
                $query->where('normalized_owner_phone', $sellerPhone);
            }
            if (! empty($sellerEmail)) {
                $query->orWhere('normalized_owner_email', $sellerEmail);
            }
        })->whereIn('status', [
            Referral::STATUS_SUBMITTED,
            Referral::STATUS_UNDER_REVIEW,
            Referral::STATUS_CONTACTED,
            Referral::STATUS_QUALIFIED,
        ])->first();

        if (! $referral) {
            return null;
        }

        return DB::transaction(function () use ($referral, $property) {
            $referral->update([
                'qualified_property_id' => $property->id,
                'property_listed_at' => now(),
                'reward_eligible_at' => now(),
                'qualification_status' => 'QUALIFIED',
                'status' => Referral::STATUS_PROPERTY_LISTED,
            ]);

            // Create ₹100 reward
            $this->createReward($referral);

            // Transition to REWARD_PENDING
            $referral->update(['status' => Referral::STATUS_REWARD_PENDING]);

            ReferralAuditLog::record($referral, 'property_published_match', null, null, Referral::STATUS_REWARD_PENDING, [
                'property_id' => $property->id,
                'property_title' => $property->title,
            ]);

            return $referral->fresh();
        });
    }

    /**
     * Create immutable ₹100 reward for an eligible referral.
     */
    public function createReward(Referral $referral, ?float $amount = null): ReferralReward
    {
        // Enforce uniqueness: Only 1 reward per referral and reward type
        $existing = ReferralReward::where('referral_id', $referral->id)
            ->where('reward_type', ReferralReward::TYPE_PROPERTY_PUBLISHED)
            ->first();

        if ($existing) {
            return $existing;
        }

        $rewardAmount = $amount ?? (float) Setting::get('referral_reward_amount', 100.00);

        return DB::transaction(function () use ($referral, $rewardAmount) {
            $reward = ReferralReward::create([
                'referral_id' => $referral->id,
                'user_id' => $referral->referrer_user_id,
                'amount' => $rewardAmount,
                'currency' => 'INR',
                'status' => ReferralReward::STATUS_PENDING,
                'reward_type' => ReferralReward::TYPE_PROPERTY_PUBLISHED,
                'reason' => 'Referral reward for successfully listed property #'.($referral->qualified_property_id ?? 'N/A'),
            ]);

            ReferralAuditLog::record($referral, 'reward_created', null, null, null, [
                'reward_id' => $reward->id,
                'amount' => $rewardAmount,
            ]);

            return $reward;
        });
    }

    /**
     * Admin approves reward.
     */
    public function approveReward(ReferralReward $reward, User $admin): ReferralReward
    {
        if (! $admin->hasRole('admin')) {
            throw new InvalidArgumentException('Only administrators can approve referral rewards.');
        }

        if ($reward->status === ReferralReward::STATUS_APPROVED) {
            return $reward;
        }

        return DB::transaction(function () use ($reward, $admin) {
            $reward->update([
                'status' => ReferralReward::STATUS_APPROVED,
                'approved_by' => $admin->id,
                'approved_at' => now(),
            ]);

            $reward->referral->update([
                'status' => Referral::STATUS_REWARD_APPROVED,
            ]);

            ReferralAuditLog::record($reward->referral, 'reward_approved', $admin, null, Referral::STATUS_REWARD_APPROVED, [
                'reward_id' => $reward->id,
            ]);

            return $reward->fresh();
        });
    }

    /**
     * User requests payout for their approved rewards.
     */
    public function requestPayout(User $user, ?string $upiId = null): ReferralPayout
    {
        $approvedRewards = ReferralReward::where('user_id', $user->id)
            ->where('status', ReferralReward::STATUS_APPROVED)
            ->whereNull('payout_id')
            ->get();

        if ($approvedRewards->isEmpty()) {
            throw new InvalidArgumentException('No approved rewards are currently available for payout.');
        }

        $destinationUpi = $upiId ?? $user->upi_id;
        if (empty($destinationUpi)) {
            throw new InvalidArgumentException('A valid UPI ID is required to process your reward payout.');
        }

        // Save UPI ID if not already saved
        if (empty($user->upi_id)) {
            $user->update([
                'upi_id' => $destinationUpi,
                'upi_id_encrypted' => encrypt($destinationUpi),
            ]);
        }

        $totalAmount = $approvedRewards->sum('amount');

        return DB::transaction(function () use ($user, $approvedRewards, $totalAmount, $destinationUpi) {
            $maskedUpi = $user->masked_upi ?? '***@upi';

            $payout = ReferralPayout::create([
                'user_id' => $user->id,
                'amount' => $totalAmount,
                'currency' => 'INR',
                'payout_method' => 'UPI',
                'payout_destination_masked' => $maskedUpi,
                'payout_destination_encrypted' => encrypt($destinationUpi),
                'status' => ReferralPayout::STATUS_PENDING,
                'provider' => 'ADMIN_MANUAL',
            ]);

            // Link rewards to payout
            ReferralReward::whereIn('id', $approvedRewards->pluck('id'))
                ->update(['payout_id' => $payout->id]);

            // Update referrals to PAYOUT_PENDING
            foreach ($approvedRewards as $rwd) {
                $rwd->referral->update(['status' => Referral::STATUS_PAYOUT_PENDING]);
                ReferralAuditLog::record($rwd->referral, 'payout_requested', $user, null, Referral::STATUS_PAYOUT_PENDING, [
                    'payout_id' => $payout->id,
                    'amount' => $totalAmount,
                ]);
            }

            return $payout;
        });
    }

    /**
     * Admin processes and settles a referral payout.
     */
    public function processPayout(ReferralPayout $payout, User $admin, array $metadata = []): ReferralPayout
    {
        if (! $admin->hasRole('admin')) {
            throw new InvalidArgumentException('Only administrators can settle referral payouts.');
        }

        return DB::transaction(function () use ($payout, $admin, $metadata) {
            $gateway = $this->payoutGateway ?? app(ReferralPayoutGatewayInterface::class);
            $gateway->processPayout($payout, $metadata);

            $payout->update([
                'processed_by' => $admin->id,
                'processed_at' => now(),
            ]);

            if ($payout->status === ReferralPayout::STATUS_PAID) {
                // Update rewards to PAID
                $payout->rewards()->update(['status' => ReferralReward::STATUS_PAID]);

                // Update referrals to PAID
                foreach ($payout->rewards as $rwd) {
                    $rwd->referral->update(['status' => Referral::STATUS_PAID]);
                    ReferralAuditLog::record($rwd->referral, 'payout_settled_paid', $admin, null, Referral::STATUS_PAID, [
                        'payout_number' => $payout->payout_number,
                        'transaction_reference' => $payout->transaction_reference,
                    ]);
                }
            } elseif ($payout->status === ReferralPayout::STATUS_FAILED) {
                // Return rewards to APPROVED so they can be re-attempted
                $payout->rewards()->update(['payout_id' => null]);
            }

            return $payout->fresh();
        });
    }

    /**
     * Get aggregate statistics for a user's referral portfolio.
     */
    public function getReferralStats(User $user): array
    {
        $referrals = Referral::where('referrer_user_id', $user->id)->get();

        return [
            'total' => $referrals->count(),
            'successful' => $referrals->whereIn('status', [
                Referral::STATUS_PROPERTY_LISTED,
                Referral::STATUS_REWARD_PENDING,
                Referral::STATUS_REWARD_APPROVED,
                Referral::STATUS_PAYOUT_PENDING,
                Referral::STATUS_PAID,
            ])->count(),
            'pending' => $referrals->whereIn('status', [
                Referral::STATUS_SUBMITTED,
                Referral::STATUS_UNDER_REVIEW,
                Referral::STATUS_CONTACTED,
                Referral::STATUS_QUALIFIED,
            ])->count(),
            'rejected' => $referrals->where('status', Referral::STATUS_REJECTED)->count(),
        ];
    }

    /**
     * Get aggregate reward and payout statistics for a user.
     */
    public function getRewardStats(User $user): array
    {
        $rewards = ReferralReward::where('user_id', $user->id)->get();

        $totalEarned = $rewards->whereIn('status', [ReferralReward::STATUS_APPROVED, ReferralReward::STATUS_PAID])->sum('amount');
        $approvedAmount = $rewards->where('status', ReferralReward::STATUS_APPROVED)->whereNull('payout_id')->sum('amount');
        $paidAmount = $rewards->where('status', ReferralReward::STATUS_PAID)->sum('amount');
        $pendingAmount = $rewards->where('status', ReferralReward::STATUS_PENDING)->sum('amount');

        return [
            'total_earned' => (float) $totalEarned,
            'available_for_payout' => (float) $approvedAmount,
            'paid_amount' => (float) $paidAmount,
            'pending_amount' => (float) $pendingAmount,
            'rewards_count' => $rewards->count(),
        ];
    }
}
