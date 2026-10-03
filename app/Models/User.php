<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'user_type',
        'current_role',
        'city',
        'is_prime',
        'prime_plan',
        'prime_contacts_limit',
        'unlocked_contacts_count',
        'prime_expires_at',
        'preferred_localities',
        'preferred_bhk',
        'preferred_budget',
        'referral_code',
        'upi_id',
        'upi_id_encrypted',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_prime' => 'boolean',
            'prime_expires_at' => 'datetime',
        ];
    }

    /**
     * Check if user has an active Housing365 Prime membership.
     */
    public function isPrimeActive(): bool
    {
        return $this->is_prime && ($this->prime_expires_at === null || $this->prime_expires_at->isFuture());
    }

    /**
     * Remaining direct owner contacts for this Prime member.
     */
    public function remainingContacts(): int
    {
        if ($this->prime_contacts_limit >= 999) {
            return 999; // Unlimited
        }

        return max(0, $this->prime_contacts_limit - $this->unlocked_contacts_count);
    }

    /**
     * Activate or upgrade Prime membership.
     */
    public function activatePrime(string $plan, int $contacts, int $days): void
    {
        $this->update([
            'is_prime' => true,
            'prime_plan' => $plan,
            'prime_contacts_limit' => $contacts,
            'prime_expires_at' => now()->addDays($days),
        ]);
    }

    /**
     * Determine whether the user can access the Filament admin panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    /**
     * User's Contact Access purchases.
     */
    public function contactAccessPurchases(): HasMany
    {
        return $this->hasMany(ContactAccessPurchase::class);
    }

    /**
     * User's unlocked property contacts.
     */
    public function contactUnlocks(): HasMany
    {
        return $this->hasMany(ContactUnlock::class);
    }

    /**
     * Check if user has already unlocked direct contact details for a specific property.
     */
    public function hasUnlockedProperty(Property|int|string $property): bool
    {
        $propertyId = $property instanceof Property ? $property->id : $property;

        return $this->contactUnlocks()
            ->where('property_id', $propertyId)
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Total available unexpired contact credits.
     */
    public function availableContactCredits(): int
    {
        return (int) $this->contactAccessPurchases()
            ->where('payment_status', 'paid')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->where('credits_remaining', '>', 0)
            ->sum('credits_remaining');
    }

    /**
     * Normalized roles associated with this user.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles')
            ->withPivot('is_active')
            ->withTimestamps();
    }

    /**
     * Get active roles for this user.
     *
     * @return Collection<int, Role>
     */
    public function activeRoles(): Collection
    {
        return $this->roles()
            ->wherePivot('is_active', true)
            ->where('roles.is_active', true)
            ->get();
    }

    /**
     * Determine if user has a specific role (by name or model).
     */
    public function hasRole(string|Role $role): bool
    {
        $roleName = $role instanceof Role ? $role->name : $role;

        return $this->roles()
            ->wherePivot('is_active', true)
            ->where('roles.is_active', true)
            ->where('roles.name', $roleName)
            ->exists();
    }

    /**
     * Determine if user is an Administrator.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Determine if user has any of the given roles.
     *
     * @param  array<string>  $roles
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()
            ->wherePivot('is_active', true)
            ->where('roles.is_active', true)
            ->whereIn('roles.name', $roles)
            ->exists();
    }

    /**
     * Determine if user has all of the given roles.
     *
     * @param  array<string>  $roles
     */
    public function hasAllRoles(array $roles): bool
    {
        if (empty($roles)) {
            return true;
        }

        $count = $this->roles()
            ->wherePivot('is_active', true)
            ->where('roles.is_active', true)
            ->whereIn('roles.name', $roles)
            ->count();

        return $count === count($roles);
    }

    /**
     * Assign a role to the user.
     */
    public function assignRole(string|Role $role, bool $isActive = true): self
    {
        $roleModel = $role instanceof Role ? $role : Role::firstOrCreate(['name' => $role], [
            'display_name' => ucfirst(str_replace('_', ' ', $role)),
            'is_active' => true,
        ]);

        $this->roles()->syncWithoutDetaching([
            $roleModel->id => ['is_active' => $isActive],
        ]);

        if (empty($this->current_role)) {
            $this->update(['current_role' => $roleModel->name]);
        }

        return $this;
    }

    /**
     * Remove a role from the user (or mark inactive).
     */
    public function removeRole(string|Role $role): self
    {
        $roleId = $role instanceof Role ? $role->id : Role::where('name', $role)->value('id');

        if ($roleId) {
            $this->roles()->detach($roleId);
        }

        return $this;
    }

    /**
     * Synchronize multiple roles.
     *
     * @param  array<string|int>  $roles
     */
    public function syncRoles(array $roles): self
    {
        $roleIds = [];
        foreach ($roles as $role) {
            if ($role instanceof Role) {
                $roleIds[$role->id] = ['is_active' => true];
            } elseif (is_numeric($role)) {
                $roleIds[$role] = ['is_active' => true];
            } else {
                $roleModel = Role::firstOrCreate(['name' => $role], [
                    'display_name' => ucfirst(str_replace('_', ' ', $role)),
                    'is_active' => true,
                ]);
                $roleIds[$roleModel->id] = ['is_active' => true];
            }
        }

        $this->roles()->sync($roleIds);

        return $this;
    }

    /**
     * Get the active workspace role name.
     */
    public function currentRole(): string
    {
        $sessionRole = session('active_role');
        if ($sessionRole && $this->hasRole($sessionRole)) {
            return $sessionRole;
        }

        if ($this->current_role && $this->hasRole($this->current_role)) {
            return $this->current_role;
        }

        $firstActive = $this->activeRoles()->first();
        if ($firstActive) {
            return $firstActive->name;
        }

        return 'tenant';
    }

    /**
     * Switch user's active workspace role.
     */
    public function switchRole(string $role): bool
    {
        if (! $this->hasRole($role)) {
            return false;
        }

        $this->update(['current_role' => $role]);
        session(['active_role' => $role]);

        return true;
    }

    /**
     * Organizations the user belongs to.
     */
    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class, 'organization_members')
            ->withPivot('role', 'is_active')
            ->withTimestamps();
    }

    /**
     * Organization membership records.
     */
    public function organizationMembers(): HasMany
    {
        return $this->hasMany(OrganizationMember::class);
    }

    /**
     * Saved/favorited properties for this user.
     */
    public function savedProperties(): BelongsToMany
    {
        return $this->belongsToMany(Property::class, 'saved_properties')
            ->withTimestamps();
    }

    /**
     * Vendor Profile if user is a vendor.
     */
    public function vendorProfile(): HasOne
    {
        return $this->hasOne(VendorProfile::class);
    }

    /**
     * Service requests created by this user.
     */
    public function serviceRequests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class);
    }

    /**
     * Quotes submitted by this vendor.
     */
    public function vendorQuotes(): HasMany
    {
        return $this->hasMany(ServiceQuote::class, 'vendor_id');
    }

    /**
     * Work orders assigned to this vendor.
     */
    public function vendorWorkOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class, 'vendor_id');
    }

    /**
     * Management enrollments requested by this owner.
     */
    public function managementEnrollments(): HasMany
    {
        return $this->hasMany(ManagementEnrollment::class);
    }

    /**
     * Referrals submitted by this user.
     */
    public function referrals(): HasMany
    {
        return $this->hasMany(Referral::class, 'referrer_user_id');
    }

    /**
     * Referral rewards earned by this user.
     */
    public function referralRewards(): HasMany
    {
        return $this->hasMany(ReferralReward::class);
    }

    /**
     * Referral payouts processed for this user.
     */
    public function referralPayouts(): HasMany
    {
        return $this->hasMany(ReferralPayout::class);
    }

    /**
     * Get or generate unique, non-guessable referral code for this user.
     */
    public function getOrCreateReferralCode(): string
    {
        if (! empty($this->referral_code)) {
            return $this->referral_code;
        }

        do {
            $code = 'H365-'.strtoupper(bin2hex(random_bytes(3)));
        } while (static::where('referral_code', $code)->exists());

        $this->update(['referral_code' => $code]);

        return $code;
    }

    /**
     * Masked UPI ID attribute (e.g. a***@upi).
     */
    public function getMaskedUpiAttribute(): ?string
    {
        if (empty($this->upi_id)) {
            return null;
        }

        $parts = explode('@', $this->upi_id);
        if (count($parts) !== 2) {
            return '***@upi';
        }

        $handle = $parts[0];
        $bank = $parts[1];

        if (strlen($handle) <= 2) {
            $maskedHandle = substr($handle, 0, 1).'***';
        } else {
            $maskedHandle = substr($handle, 0, 2).'***'.substr($handle, -1);
        }

        return $maskedHandle.'@'.$bank;
    }

    /**
     * Verifications submitted by or belonging to this user.
     */
    public function verifications(): HasMany
    {
        return $this->hasMany(Verification::class);
    }

    /**
     * Verification badges awarded to this user subject.
     */
    public function subjectBadges(): MorphMany
    {
        return $this->morphMany(SubjectBadge::class, 'subject');
    }

    /**
     * Active badges awarded to this user.
     */
    public function activeBadges()
    {
        return $this->subjectBadges()->active()->with('trustBadge');
    }

    /**
     * Reviews authored by this user.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Reviews received by this user (e.g. as an owner, broker, or vendor).
     */
    public function receivedReviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'subject');
    }

    /**
     * Suspensions history for this user.
     */
    public function suspensions(): HasMany
    {
        return $this->hasMany(AccountSuspension::class);
    }

    /**
     * Currently active suspension for this user, if any.
     */
    public function activeSuspension(): HasOne
    {
        return $this->hasOne(AccountSuspension::class)
            ->where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>', now());
            });
    }

    /**
     * Check if user account is currently suspended.
     */
    public function isSuspended(): bool
    {
        return $this->activeSuspension()->exists();
    }

    /**
     * Risk flags on this user.
     */
    public function riskFlags(): MorphMany
    {
        return $this->morphMany(RiskFlag::class, 'subject');
    }

    /**
     * Appeals submitted by this user.
     */
    public function appeals(): HasMany
    {
        return $this->hasMany(Appeal::class);
    }
}
