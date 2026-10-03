<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ServiceRequest extends Model
{
    // Lifecycle Constants
    public const STATUS_DRAFT = 'DRAFT';

    public const STATUS_SUBMITTED = 'SUBMITTED';

    public const STATUS_TRIAGED = 'TRIAGED';

    public const STATUS_VENDOR_ASSIGNMENT_PENDING = 'VENDOR_ASSIGNMENT_PENDING';

    public const STATUS_VENDOR_ASSIGNED = 'VENDOR_ASSIGNED';

    public const STATUS_QUOTE_REQUESTED = 'QUOTE_REQUESTED';

    public const STATUS_QUOTE_RECEIVED = 'QUOTE_RECEIVED';

    public const STATUS_AWAITING_APPROVAL = 'AWAITING_APPROVAL';

    public const STATUS_APPROVED = 'APPROVED';

    public const STATUS_SCHEDULED = 'SCHEDULED';

    public const STATUS_IN_PROGRESS = 'IN_PROGRESS';

    public const STATUS_COMPLETED = 'COMPLETED';

    public const STATUS_QC_PENDING = 'QC_PENDING';

    public const STATUS_QC_COMPLETED = 'QC_COMPLETED';

    public const STATUS_CLOSED = 'CLOSED';

    public const STATUS_CANCELLED = 'CANCELLED';

    public const STATUS_REJECTED = 'REJECTED';

    public const STATUS_DISPUTED = 'DISPUTED';

    public const STATUS_VENDOR_ACCEPTED = 'VENDOR_ACCEPTED';

    public const STATUS_VENDOR_REJECTED = 'VENDOR_REJECTED';

    public const STATUS_REWORK_REQUIRED = 'REWORK_REQUIRED';

    public const STATUS_INVOICE_GENERATED = 'INVOICE_GENERATED';

    public const STATUS_PAID = 'PAID';

    protected $fillable = [
        'request_number',
        'user_id',
        'property_id',
        'pg_room_id',
        'pg_bed_id',
        'coworking_inventory_id',
        'service_category_id',
        'assigned_vendor_id',
        'assignment_status',
        'assignment_rejected_reason',
        'title',
        'description',
        'priority',
        'status',
        'preferred_date',
        'preferred_time_window',
        'contact_preference',
        'service_location',
        'access_instructions',
        'photos',
        'video_url',
        'admin_notes',
        'qc_notes',
        'rework_notes',
        'rework_count',
        'rating',
        'review',
        'submitted_at',
        'triaged_at',
        'scheduled_at',
        'completed_at',
        'closed_at',
    ];

    protected $casts = [
        'photos' => 'array',
        'preferred_date' => 'date',
        'submitted_at' => 'datetime',
        'triaged_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'completed_at' => 'datetime',
        'closed_at' => 'datetime',
        'rating' => 'integer',
        'rework_count' => 'integer',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->request_number)) {
                $model->request_number = 'CARE-'.date('Ymd').'-'.strtoupper(bin2hex(random_bytes(3)));
            }
            if (empty($model->submitted_at) && $model->status !== self::STATUS_DRAFT) {
                $model->submitted_at = now();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function pgRoom(): BelongsTo
    {
        return $this->belongsTo(PgRoom::class);
    }

    public function pgBed(): BelongsTo
    {
        return $this->belongsTo(PgBed::class);
    }

    public function coworkingInventory(): BelongsTo
    {
        return $this->belongsTo(CoworkingInventory::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(CareServiceCategory::class, 'service_category_id');
    }

    public function assignedVendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_vendor_id');
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(ServiceQuote::class);
    }

    public function latestQuote(): HasOne
    {
        return $this->hasOne(ServiceQuote::class)->latestOfMany();
    }

    public function approvedQuote(): HasOne
    {
        return $this->hasOne(ServiceQuote::class)->where('status', ServiceQuote::STATUS_APPROVED);
    }

    public function workOrder(): HasOne
    {
        return $this->hasOne(WorkOrder::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(CareInvoice::class);
    }

    public function latestInvoice(): HasOne
    {
        return $this->hasOne(CareInvoice::class)->latestOfMany();
    }

    public function isAuthorizedUser(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        // Requester owns the request
        if ($this->user_id === $user->id) {
            return true;
        }

        // Property owner has oversight
        if ($this->property && $this->property->isOwnedBy($user)) {
            return true;
        }

        // Assigned vendor has job access
        if ($this->assigned_vendor_id === $user->id) {
            return true;
        }

        return false;
    }
}
