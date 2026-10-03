<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PgBooking extends Model
{
    protected $guarded = [];

    protected $casts = [
        'check_in_date' => 'date',
        'monthly_rent' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
    ];

    public const STATUS_HELD = 'HELD';

    public const STATUS_CONFIRMED = 'CONFIRMED';

    public const STATUS_CANCELLED = 'CANCELLED';

    public const STATUS_COMPLETED = 'COMPLETED';

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(PgRoom::class, 'pg_room_id');
    }

    public function bed(): BelongsTo
    {
        return $this->belongsTo(PgBed::class, 'pg_bed_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
