<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Review extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'PENDING';

    public const STATUS_PUBLISHED = 'PUBLISHED';

    public const STATUS_REJECTED = 'REJECTED';

    public const STATUS_HIDDEN = 'HIDDEN';

    public const STATUS_FLAGGED = 'FLAGGED';

    protected $guarded = [];

    protected $casts = [
        'rating' => 'integer',
        'submitted_at' => 'datetime',
        'published_at' => 'datetime',
        'moderated_at' => 'datetime',
    ];

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(ReviewResponse::class);
    }

    public function publishedResponses(): HasMany
    {
        return $this->hasMany(ReviewResponse::class)->where('status', ReviewResponse::STATUS_PUBLISHED);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(ReviewReport::class);
    }

    public function moderationActions(): HasMany
    {
        return $this->hasMany(ReviewModerationAction::class);
    }

    public function appeals(): MorphMany
    {
        return $this->morphMany(Appeal::class, 'appealable');
    }

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }
}
