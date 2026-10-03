<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrustBadge extends Model
{
    use HasFactory;

    public const BADGE_IDENTITY_VERIFIED = 'IDENTITY_VERIFIED';

    public const BADGE_PROPERTY_VERIFIED = 'PROPERTY_VERIFIED';

    public const BADGE_BUSINESS_VERIFIED = 'BUSINESS_VERIFIED';

    public const BADGE_BROKER_VERIFIED = 'BROKER_VERIFIED';

    public const BADGE_BUILDER_VERIFIED = 'BUILDER_VERIFIED';

    public const BADGE_RERA_SUBMITTED = 'RERA_DETAILS_SUBMITTED';

    public const BADGE_RERA_VERIFIED = 'RERA_VERIFIED';

    public const BADGE_PG_VERIFIED = 'PG_VERIFIED';

    public const BADGE_VENDOR_VERIFIED = 'VENDOR_VERIFIED';

    public const BADGE_HOUSING365_CARE_PROVIDER = 'HOUSING365_CARE_PROVIDER';

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function subjectBadges(): HasMany
    {
        return $this->hasMany(SubjectBadge::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
