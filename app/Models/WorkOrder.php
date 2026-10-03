<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WorkOrder extends Model
{
    public const STATUS_CREATED = 'CREATED';

    public const STATUS_SCHEDULED = 'SCHEDULED';

    public const STATUS_VENDOR_EN_ROUTE = 'VENDOR_EN_ROUTE';

    public const STATUS_IN_PROGRESS = 'IN_PROGRESS';

    public const STATUS_WAITING_FOR_PARTS = 'WAITING_FOR_PARTS';

    public const STATUS_COMPLETED = 'COMPLETED';

    public const STATUS_QC_PENDING = 'QC_PENDING';

    public const STATUS_QC_APPROVED = 'QC_APPROVED';

    public const STATUS_QC_COMPLETED = 'QC_APPROVED';

    public const STATUS_REWORK_REQUIRED = 'REWORK_REQUIRED';

    public const STATUS_CANCELLED = 'CANCELLED';

    public const STATUS_CLOSED = 'CLOSED';

    // QC Statuses
    public const QC_STATUS_PENDING = 'PENDING';

    public const QC_STATUS_APPROVED = 'APPROVED';

    public const QC_STATUS_REWORK_REQUIRED = 'REWORK_REQUIRED';

    public const QC_STATUS_REJECTED = 'REJECTED';

    protected $fillable = [
        'work_order_number',
        'service_request_id',
        'service_quote_id',
        'vendor_id',
        'property_id',
        'status',
        'qc_status',
        'qc_notes',
        'rework_count',
        'scheduled_at',
        'arrival_notes',
        'work_notes',
        'materials_used',
        'before_photos',
        'after_photos',
        'started_at',
        'completed_at',
        'qc_approved_at',
    ];

    protected $casts = [
        'before_photos' => 'array',
        'after_photos' => 'array',
        'rework_count' => 'integer',
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'qc_approved_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->work_order_number)) {
                $model->work_order_number = 'WO-'.date('Ymd').'-'.strtoupper(bin2hex(random_bytes(3)));
            }
        });
    }

    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    public function quote(): BelongsTo
    {
        return $this->belongsTo(ServiceQuote::class, 'service_quote_id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function careInvoice(): HasOne
    {
        return $this->hasOne(CareInvoice::class);
    }

    public function vendorPayout(): HasOne
    {
        return $this->hasOne(VendorPayout::class);
    }
}
