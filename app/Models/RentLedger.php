<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RentLedger extends Model
{
    protected $guarded = [];

    protected $casts = [
        'due_date' => 'date',
        'amount_due' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'outstanding_balance' => 'decimal:2',
        'late_fee' => 'decimal:2',
        'is_late' => 'boolean',
        'paid_at' => 'datetime',
    ];

    public const STATUS_DUE = 'DUE';

    public const STATUS_PARTIALLY_PAID = 'PARTIALLY_PAID';

    public const STATUS_PAID = 'PAID';

    public const STATUS_OVERDUE = 'OVERDUE';

    public function account(): BelongsTo
    {
        return $this->belongsTo(RentAccount::class, 'rent_account_id');
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(RentPayment::class);
    }
}
