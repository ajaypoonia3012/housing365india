<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PgBed extends Model
{
    protected $guarded = [];

    protected $casts = [
        'held_until' => 'datetime',
    ];

    public const STATUS_AVAILABLE = 'AVAILABLE';

    public const STATUS_HELD = 'HELD';

    public const STATUS_RESERVED = 'RESERVED';

    public const STATUS_OCCUPIED = 'OCCUPIED';

    public const STATUS_NOTICE_PERIOD = 'NOTICE_PERIOD';

    public const STATUS_MAINTENANCE = 'MAINTENANCE';

    public const STATUS_BLOCKED = 'BLOCKED';

    public function room(): BelongsTo
    {
        return $this->belongsTo(PgRoom::class, 'pg_room_id');
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function heldBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'held_by_user_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(PgBooking::class);
    }

    public function isAvailable(): bool
    {
        if ($this->status === self::STATUS_AVAILABLE) {
            return true;
        }

        if ($this->status === self::STATUS_HELD && $this->held_until && $this->held_until->isPast()) {
            return true;
        }

        return false;
    }
}
