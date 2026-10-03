<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RentPayment extends Model
{
    protected $guarded = [];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public const STATUS_PENDING = 'PENDING';

    public const STATUS_INITIATED = 'INITIATED';

    public const STATUS_AUTHORIZED = 'AUTHORIZED';

    public const STATUS_PAID = 'PAID';

    public const STATUS_FAILED = 'FAILED';

    public const STATUS_CANCELLED = 'CANCELLED';

    public const STATUS_REFUNDED = 'REFUNDED';

    public const STATUS_PARTIALLY_REFUNDED = 'PARTIALLY_REFUNDED';

    public const STATUS_RECONCILIATION_REQUIRED = 'RECONCILIATION_REQUIRED';

    public function ledger(): BelongsTo
    {
        return $this->belongsTo(RentLedger::class, 'rent_ledger_id');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(RentAccount::class, 'rent_account_id');
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }
}
