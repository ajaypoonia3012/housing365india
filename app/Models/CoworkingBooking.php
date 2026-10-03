<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoworkingBooking extends Model
{
    protected $guarded = [];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'duration_units' => 'integer',
        'total_amount' => 'decimal:2',
    ];

    public const STATUS_HELD = 'HELD';

    public const STATUS_RESERVED = 'RESERVED';

    public const STATUS_CONFIRMED = 'CONFIRMED';

    public const STATUS_ACTIVE = 'ACTIVE';

    public const STATUS_COMPLETED = 'COMPLETED';

    public const STATUS_CANCELLED = 'CANCELLED';

    public const STATUS_BLOCKED = 'BLOCKED';

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(CoworkingInventory::class, 'coworking_inventory_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
