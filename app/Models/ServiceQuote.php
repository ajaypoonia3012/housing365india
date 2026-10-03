<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ServiceQuote extends Model
{
    public const STATUS_PENDING = 'PENDING';

    public const STATUS_APPROVED = 'APPROVED';

    public const STATUS_DECLINED = 'DECLINED';

    public const STATUS_SUPERSEDED = 'SUPERSEDED';

    protected $fillable = [
        'service_request_id',
        'vendor_id',
        'quote_number',
        'labour_amount',
        'materials_amount',
        'tax_amount',
        'other_charges',
        'total_amount',
        'estimated_duration',
        'notes',
        'line_items',
        'valid_until',
        'status',
        'rejection_reason',
        'approved_by',
        'approved_at',
        'approved_amount',
    ];

    protected $casts = [
        'labour_amount' => 'decimal:2',
        'materials_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'other_charges' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'approved_amount' => 'decimal:2',
        'line_items' => 'array',
        'valid_until' => 'date',
        'approved_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->quote_number)) {
                $model->quote_number = 'QUO-'.date('Ymd').'-'.strtoupper(bin2hex(random_bytes(3)));
            }
            if (empty($model->total_amount)) {
                $model->total_amount = (float) $model->labour_amount +
                                       (float) $model->materials_amount +
                                       (float) $model->tax_amount +
                                       (float) $model->other_charges;
            }
        });
    }

    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function workOrder(): HasOne
    {
        return $this->hasOne(WorkOrder::class);
    }
}
