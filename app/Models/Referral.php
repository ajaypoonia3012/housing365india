<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Referral extends Model
{
    use HasFactory;

    // Referral Status Lifecycle
    public const STATUS_SUBMITTED = 'SUBMITTED';

    public const STATUS_UNDER_REVIEW = 'UNDER_REVIEW';

    public const STATUS_CONTACTED = 'CONTACTED';

    public const STATUS_QUALIFIED = 'QUALIFIED';

    public const STATUS_PROPERTY_LISTED = 'PROPERTY_LISTED';

    public const STATUS_REWARD_PENDING = 'REWARD_PENDING';

    public const STATUS_REWARD_APPROVED = 'REWARD_APPROVED';

    public const STATUS_PAYOUT_PENDING = 'PAYOUT_PENDING';

    public const STATUS_PAID = 'PAID';

    public const STATUS_REJECTED = 'REJECTED';

    public const STATUS_CANCELLED = 'CANCELLED';

    // Submission Methods
    public const METHOD_REFER_OWNER = 'refer_owner';

    public const METHOD_PROPERTY_BOARD_PHOTO = 'property_board_photo';

    protected $fillable = [
        'referrer_user_id',
        'referral_code',
        'submission_method',
        'owner_name',
        'owner_phone',
        'normalized_owner_phone',
        'owner_email',
        'normalized_owner_email',
        'state',
        'city',
        'locality',
        'property_type',
        'listing_intent',
        'property_address',
        'notes',
        'referrer_relationship',
        'photo_path',
        'photo_mime',
        'photo_size',
        'status',
        'qualification_status',
        'duplicate_flag',
        'fraud_flag',
        'fraud_reasons',
        'existing_property_id',
        'qualified_property_id',
        'rejection_reason',
        'admin_notes',
        'submitted_at',
        'qualified_at',
        'property_listed_at',
        'reward_eligible_at',
    ];

    protected $casts = [
        'duplicate_flag' => 'boolean',
        'fraud_flag' => 'boolean',
        'fraud_reasons' => 'array',
        'submitted_at' => 'datetime',
        'qualified_at' => 'datetime',
        'property_listed_at' => 'datetime',
        'reward_eligible_at' => 'datetime',
    ];

    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referrer_user_id');
    }

    public function existingProperty(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'existing_property_id');
    }

    public function qualifiedProperty(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'qualified_property_id');
    }

    public function rewards(): HasMany
    {
        return $this->hasMany(ReferralReward::class);
    }

    public function latestReward(): HasOne
    {
        return $this->hasOne(ReferralReward::class)->latestOfMany();
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(ReferralAuditLog::class);
    }

    /**
     * Check if a given user is the authorized referrer or admin.
     */
    public function isAuthorizedReferrer(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return (int) $this->referrer_user_id === (int) $user->id || $user->hasRole('admin');
    }
}
