<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class AccountSuspension extends Model
{
    use HasFactory;

    public const TYPE_TEMPORARY = 'TEMPORARY';

    public const TYPE_PERMANENT = 'PERMANENT';

    public const APPEAL_NONE = 'NONE';

    public const APPEAL_APPEALED = 'APPEALED';

    public const APPEAL_REJECTED = 'REJECTED';

    public const APPEAL_OVERTURNED = 'OVERTURNED';

    protected $guarded = [];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function appeals(): MorphMany
    {
        return $this->morphMany(Appeal::class, 'appealable');
    }

    public function isEffective(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->starts_at->isFuture()) {
            return false;
        }

        if ($this->ends_at !== null && $this->ends_at->isPast()) {
            return false;
        }

        return true;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>', now());
            });
    }
}
