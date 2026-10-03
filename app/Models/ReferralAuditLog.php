<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReferralAuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'referral_id',
        'user_id',
        'event',
        'from_status',
        'to_status',
        'metadata',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function referral(): BelongsTo
    {
        return $this->belongsTo(Referral::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Record an audit log entry.
     */
    public static function record(Referral $referral, string $event, ?User $actor = null, ?string $fromStatus = null, ?string $toStatus = null, array $metadata = []): self
    {
        return static::create([
            'referral_id' => $referral->id,
            'user_id' => $actor?->id,
            'event' => $event,
            'from_status' => $fromStatus ?? $referral->status,
            'to_status' => $toStatus ?? $referral->status,
            'metadata' => $metadata,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
