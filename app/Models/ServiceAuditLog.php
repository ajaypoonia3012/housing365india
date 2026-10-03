<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ServiceAuditLog extends Model
{
    protected $fillable = [
        'auditable_type',
        'auditable_id',
        'user_id',
        'event',
        'details',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function record(Model $model, string $event, ?User $user = null, array $details = []): self
    {
        return self::create([
            'auditable_type' => get_class($model),
            'auditable_id' => $model->getKey(),
            'user_id' => $user?->id ?? auth()->id(),
            'event' => $event,
            'details' => $details,
        ]);
    }
}
