<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorPayout extends Model
{
    public const STATUS_PENDING = 'PENDING';

    public const STATUS_APPROVED = 'APPROVED';

    public const STATUS_PROCESSING = 'PROCESSING';

    public const STATUS_PAID = 'PAID';

    public const STATUS_FAILED = 'FAILED';

    public const STATUS_ON_HOLD = 'ON_HOLD';

    protected $fillable = [
        'payout_number',
        'work_order_id',
        'vendor_id',
        'care_invoice_id',
        'gross_amount',
        'platform_fee',
        'tax_deduction',
        'net_amount',
        'status',
        'payout_reference',
        'paid_at',
        'failure_reason',
        'notes',
    ];

    protected $casts = [
        'gross_amount' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'tax_deduction' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->payout_number)) {
                $model->payout_number = 'PAYOUT-'.date('Ymd').'-'.strtoupper(bin2hex(random_bytes(3)));
            }
        });
    }

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function careInvoice(): BelongsTo
    {
        return $this->belongsTo(CareInvoice::class);
    }
}
