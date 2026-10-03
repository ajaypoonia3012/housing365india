<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Verification extends Model
{
    use HasFactory;

    public const STATUS_NOT_SUBMITTED = 'NOT_SUBMITTED';

    public const STATUS_PENDING = 'PENDING';

    public const STATUS_UNDER_REVIEW = 'UNDER_REVIEW';

    public const STATUS_VERIFIED = 'VERIFIED';

    public const STATUS_REJECTED = 'REJECTED';

    public const STATUS_EXPIRED = 'EXPIRED';

    public const STATUS_SUSPENDED = 'SUSPENDED';

    protected $guarded = [];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'expires_at' => 'datetime',
        'evidence_references' => 'array',
        'metadata' => 'array',
    ];

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function verificationType(): BelongsTo
    {
        return $this->belongsTo(VerificationType::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(VerificationDocument::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(VerificationEvent::class);
    }

    public function badges(): HasMany
    {
        return $this->hasMany(SubjectBadge::class);
    }

    public function appeals(): MorphMany
    {
        return $this->morphMany(Appeal::class, 'appealable');
    }

    public function isVerified(): bool
    {
        return $this->status === self::STATUS_VERIFIED && ($this->expires_at === null || $this->expires_at->isFuture());
    }

    public function isPending(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_UNDER_REVIEW], true);
    }

    public function scopeVerified($query)
    {
        return $query->where('status', self::STATUS_VERIFIED)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            });
    }
}
