<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Property extends Model
{
    protected $guarded = [];

    protected $casts = [
        'gallery' => 'array',
        'amenities' => 'array',
        'featured' => 'boolean',
        'is_verified' => 'boolean',
        'is_managed' => 'boolean',
        'food_included' => 'boolean',
        'coworking_details' => 'array',
        'price' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'bathrooms' => 'float',
    ];

    protected $attributes = [
        'country' => 'IN',
    ];

    /**
     * Owner user account (normalized in Phase 3, nullable for legacy compatibility).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Contact unlock logs for this property.
     */
    public function contactUnlocks(): HasMany
    {
        return $this->hasMany(ContactUnlock::class);
    }

    /**
     * Users who have saved/favorited this property.
     */
    public function savedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'saved_properties')
            ->withTimestamps();
    }

    /**
     * Referral that brought this property into Housing365 (if any).
     */
    public function referral(): HasOne
    {
        return $this->hasOne(Referral::class, 'qualified_property_id');
    }

    /**
     * Featured flag accessor & mutator alias.
     */
    public function getIsFeaturedAttribute(): bool
    {
        return (bool) ($this->attributes['featured'] ?? false);
    }

    public function setIsFeaturedAttribute($value): void
    {
        $this->attributes['featured'] = (bool) $value;
    }

    /**
     * Check if a specific user owns this property (via user_id or legacy phone/email fallback).
     */
    public function isOwnedBy(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        if ($this->user_id && (int) $this->user_id === (int) $user->id) {
            return true;
        }

        if (! empty($this->seller_email) && strtolower($this->seller_email) === strtolower($user->email)) {
            return true;
        }

        if (! empty($this->seller_phone) && $this->seller_phone === $user->phone) {
            return true;
        }

        return false;
    }

    /**
     * Determine if a user has unlocked direct contact access to this property.
     */
    public function isUnlockedBy(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        // Owner/lister viewing their own property has access
        if ($this->isOwnedBy($user)) {
            return true;
        }

        return $this->contactUnlocks()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Check if a property is saved/favorited by a user.
     */
    public function isSavedBy(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $this->savedByUsers()
            ->where('users.id', $user->id)
            ->exists();
    }

    /* ─── SCOPES FOR MARKETPLACE PURPOSES ─── */

    public function scopeForRent(Builder $query): Builder
    {
        return $query->whereIn('purpose', ['rent', 'lease']);
    }

    public function scopeForSale(Builder $query): Builder
    {
        return $query->where('purpose', 'sale');
    }

    public function scopeCommercialRent(Builder $query): Builder
    {
        return $query->where('purpose', 'commercial_rent')
            ->orWhere(function ($q) {
                $q->whereIn('purpose', ['rent', 'lease'])->where('category', 'commercial');
            });
    }

    public function scopeCommercialSale(Builder $query): Builder
    {
        return $query->where('purpose', 'commercial_sale')
            ->orWhere(function ($q) {
                $q->where('purpose', 'sale')->where('category', 'commercial');
            });
    }

    public function scopeLandSale(Builder $query): Builder
    {
        return $query->where('purpose', 'land_sale')
            ->orWhere(function ($q) {
                $q->where('purpose', 'sale')->where('category', 'plot_land');
            });
    }

    public function scopePg(Builder $query): Builder
    {
        return $query->where('purpose', 'pg');
    }

    public function scopeColiving(Builder $query): Builder
    {
        return $query->where('purpose', 'coliving');
    }

    public function scopeCoworking(Builder $query): Builder
    {
        return $query->where('purpose', 'coworking');
    }

    public function scopeProject(Builder $query): Builder
    {
        return $query->where('purpose', 'project');
    }

    public function scopeVerified(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->where('is_verified', true)
                ->orWhereIn('verification_level', ['basic_verified', 'document_verified', 'housing365_verified']);
        });
    }

    public function serviceRequests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class);
    }

    public function workOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class);
    }

    public function managementEnrollments(): HasMany
    {
        return $this->hasMany(ManagementEnrollment::class);
    }

    public function activeManagementEnrollment(): HasOne
    {
        return $this->hasOne(ManagementEnrollment::class)->where('status', ManagementEnrollment::STATUS_ACTIVE);
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    public function tenantApplications(): HasMany
    {
        return $this->hasMany(TenantApplication::class);
    }

    public function tenantScreenings(): HasMany
    {
        return $this->hasMany(TenantScreening::class);
    }

    public function rentAccounts(): HasMany
    {
        return $this->hasMany(RentAccount::class);
    }

    public function rentLedgers(): HasMany
    {
        return $this->hasMany(RentLedger::class);
    }

    public function pgRooms(): HasMany
    {
        return $this->hasMany(PgRoom::class);
    }

    public function pgBeds(): HasMany
    {
        return $this->hasMany(PgBed::class);
    }

    public function pgBookings(): HasMany
    {
        return $this->hasMany(PgBooking::class);
    }

    public function coworkingInventories(): HasMany
    {
        return $this->hasMany(CoworkingInventory::class);
    }

    public function coworkingBookings(): HasMany
    {
        return $this->hasMany(CoworkingBooking::class);
    }

    /**
     * Verifications for this property.
     */
    public function verifications(): MorphMany
    {
        return $this->morphMany(Verification::class, 'subject');
    }

    /**
     * Trust badges assigned to this property.
     */
    public function subjectBadges(): MorphMany
    {
        return $this->morphMany(SubjectBadge::class, 'subject');
    }

    /**
     * Active badges awarded to this property.
     */
    public function activeBadges()
    {
        return $this->subjectBadges()->active()->with('trustBadge');
    }

    /**
     * Reviews on this property.
     */
    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'subject');
    }

    /**
     * Published, verified reviews for public display.
     */
    public function publishedReviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'subject')->where('status', Review::STATUS_PUBLISHED);
    }

    /**
     * Average rating of published reviews.
     */
    public function verifiedRating(): ?float
    {
        $avg = $this->publishedReviews()->avg('rating');

        return $avg ? round((float) $avg, 1) : null;
    }

    /**
     * Count of published reviews.
     */
    public function verifiedReviewCount(): int
    {
        return $this->publishedReviews()->count();
    }

    /**
     * Risk flags on this property.
     */
    public function riskFlags(): MorphMany
    {
        return $this->morphMany(RiskFlag::class, 'subject');
    }
}
