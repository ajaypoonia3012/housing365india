<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class RiskFlag extends Model
{
    use HasFactory;

    public const SEVERITY_LOW = 'LOW';

    public const SEVERITY_MEDIUM = 'MEDIUM';

    public const SEVERITY_HIGH = 'HIGH';

    public const SEVERITY_CRITICAL = 'CRITICAL';

    public const STATUS_ACTIVE = 'ACTIVE';

    public const STATUS_RESOLVED = 'RESOLVED';

    public const STATUS_DISMISSED = 'DISMISSED';

    protected $guarded = [];

    protected $casts = [
        'details' => 'array',
        'resolved_at' => 'datetime',
    ];

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }
}
