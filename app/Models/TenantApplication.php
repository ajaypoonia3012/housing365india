<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TenantApplication extends Model
{
    protected $guarded = [];

    protected $casts = [
        'monthly_income' => 'decimal:2',
        'proposed_move_in' => 'date',
        'tenancy_months' => 'integer',
    ];

    public const STATUS_DRAFT = 'DRAFT';

    public const STATUS_SUBMITTED = 'SUBMITTED';

    public const STATUS_UNDER_REVIEW = 'UNDER_REVIEW';

    public const STATUS_APPROVED = 'APPROVED';

    public const STATUS_REJECTED = 'REJECTED';

    public const STATUS_WITHDRAWN = 'WITHDRAWN';

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function screening(): HasOne
    {
        return $this->hasOne(TenantScreening::class);
    }
}
