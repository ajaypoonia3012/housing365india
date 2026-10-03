<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ManagementEnrollment extends Model
{
    public const STATUS_REQUESTED = 'REQUESTED';

    public const STATUS_UNDER_REVIEW = 'UNDER_REVIEW';

    public const STATUS_AGREEMENT_PENDING = 'AGREEMENT_PENDING';

    public const STATUS_AWAITING_OWNER_ACCEPTANCE = 'AWAITING_OWNER_ACCEPTANCE';

    public const STATUS_ACTIVE = 'ACTIVE';

    public const STATUS_PAUSED = 'PAUSED';

    public const STATUS_CANCELLED = 'CANCELLED';

    public const STATUS_EXPIRED = 'EXPIRED';

    protected $fillable = [
        'user_id',
        'property_id',
        'management_plan_id',
        'status',
        'agreement_reference',
        'pricing_snapshot',
        'agreement_accepted_at',
        'agreement_accepted_ip',
        'requested_at',
        'approved_at',
        'activated_at',
        'cancelled_at',
        'notes',
    ];

    protected $casts = [
        'pricing_snapshot' => 'array',
        'agreement_accepted_at' => 'datetime',
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'activated_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->requested_at)) {
                $model->requested_at = now();
            }
            if (empty($model->agreement_reference)) {
                $model->agreement_reference = 'PMA-'.date('Ymd').'-'.strtoupper(bin2hex(random_bytes(3)));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(ManagementPlan::class, 'management_plan_id');
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }
}
