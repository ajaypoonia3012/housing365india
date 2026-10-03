<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CoworkingInventory extends Model
{
    protected $guarded = [];

    protected $casts = [
        'capacity' => 'integer',
        'hourly_rate' => 'decimal:2',
        'daily_rate' => 'decimal:2',
        'monthly_rate' => 'decimal:2',
        'amenities' => 'array',
    ];

    public const TYPE_HOT_DESK = 'hot_desk';

    public const TYPE_DEDICATED_DESK = 'dedicated_desk';

    public const TYPE_PRIVATE_CABIN = 'private_cabin';

    public const TYPE_MEETING_ROOM = 'meeting_room';

    public const STATUS_AVAILABLE = 'AVAILABLE';

    public const STATUS_MAINTENANCE = 'MAINTENANCE';

    public const STATUS_BLOCKED = 'BLOCKED';

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(CoworkingBooking::class);
    }
}
