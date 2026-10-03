<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReferralReward extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'PENDING';

    public const STATUS_APPROVED = 'APPROVED';

    public const STATUS_ON_HOLD = 'ON_HOLD';

    public const STATUS_PAID = 'PAID';

    public const STATUS_CANCELLED = 'CANCELLED';

    public const TYPE_PROPERTY_PUBLISHED = 'PROPERTY_PUBLISHED';

    protected $fillable = [
        'referral_id',
        'user_id',
        'amount',
        'currency',
        'status',
        'reward_type',
        'reason',
        'approved_by',
        'approved_at',
        'payout_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    public function referral(): BelongsTo
    {
        return $this->belongsTo(Referral::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function payout(): BelongsTo
    {
        return $this->belongsTo(ReferralPayout::class, 'payout_id');
    }
}
