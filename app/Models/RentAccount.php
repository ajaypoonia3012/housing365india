<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RentAccount extends Model
{
    protected $guarded = [];

    protected $casts = [
        'monthly_rent' => 'decimal:2',
        'security_deposit' => 'decimal:2',
        'lease_start_date' => 'date',
        'lease_end_date' => 'date',
        'due_day_of_month' => 'integer',
    ];

    public const STATUS_ACTIVE = 'ACTIVE';

    public const STATUS_PAUSED = 'PAUSED';

    public const STATUS_TERMINATED = 'TERMINATED';

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function ledgers(): HasMany
    {
        return $this->hasMany(RentLedger::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(RentPayment::class);
    }
}
