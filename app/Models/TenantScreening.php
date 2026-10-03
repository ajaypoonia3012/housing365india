<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TenantScreening extends Model
{
    protected $guarded = [];

    protected $casts = [
        'consent_granted_at' => 'datetime',
        'monthly_income_verified' => 'decimal:2',
        'completed_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public const STATUS_DRAFT = 'DRAFT';

    public const STATUS_SUBMITTED = 'SUBMITTED';

    public const STATUS_CONSENT_REQUIRED = 'CONSENT_REQUIRED';

    public const STATUS_CONSENTED = 'CONSENTED';

    public const STATUS_DOCUMENTS_REQUIRED = 'DOCUMENTS_REQUIRED';

    public const STATUS_DOCUMENTS_SUBMITTED = 'DOCUMENTS_SUBMITTED';

    public const STATUS_UNDER_REVIEW = 'UNDER_REVIEW';

    public const STATUS_VERIFICATION_IN_PROGRESS = 'VERIFICATION_IN_PROGRESS';

    public const STATUS_VERIFIED = 'VERIFIED';

    public const STATUS_PARTIALLY_VERIFIED = 'PARTIALLY_VERIFIED';

    public const STATUS_FAILED = 'FAILED';

    public const STATUS_EXPIRED = 'EXPIRED';

    public const STATUS_CANCELLED = 'CANCELLED';

    public function application(): BelongsTo
    {
        return $this->belongsTo(TenantApplication::class, 'tenant_application_id');
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(VerificationDocument::class);
    }
}
