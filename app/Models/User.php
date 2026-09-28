<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        'city',
        'is_prime',
        'prime_plan',
        'prime_contacts_limit',
        'unlocked_contacts_count',
        'prime_expires_at',
        'preferred_localities',
        'preferred_bhk',
        'preferred_budget',
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
}
