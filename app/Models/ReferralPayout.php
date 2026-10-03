<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReferralPayout extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'PENDING';

    public const STATUS_APPROVED = 'APPROVED';

    public const STATUS_PROCESSING = 'PROCESSING';

    public const STATUS_PAID = 'PAID';

    public const STATUS_FAILED = 'FAILED';

    public const STATUS_ON_HOLD = 'ON_HOLD';

    protected $fillable = [
        'payout_number',
        'user_id',
        'amount',
        'currency',
        'payout_method',
        'payout_destination_masked',
        'payout_destination_encrypted',
        'status',
        'provider',
        'provider_reference',
        'transaction_reference',
        'failure_reason',
        'admin_notes',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($payout) {
            if (empty($payout->payout_number)) {
                $payout->payout_number = 'PAY-REF-'.strtoupper(bin2hex(random_bytes(4)));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function rewards(): HasMany
    {
        return $this->hasMany(ReferralReward::class, 'payout_id');
    }
}
