<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContactAccessPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'contact_access_package_id',
        'order_reference',
        'credits_granted',
        'credits_remaining',
        'amount_paid',
        'currency',
        'payment_gateway',
        'payment_status',
        'gateway_order_id',
        'gateway_payment_id',
        'paid_at',
        'expires_at',
    ];

    protected $casts = [
        'credits_granted' => 'integer',
        'credits_remaining' => 'integer',
        'amount_paid' => 'decimal:2',
        'paid_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(ContactAccessPackage::class, 'contact_access_package_id');
    }

    public function unlocks(): HasMany
    {
        return $this->hasMany(ContactUnlock::class);
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isValid(): bool
    {
        if (! $this->isPaid()) {
            return false;
        }

        if ($this->credits_remaining <= 0) {
            return false;
        }

        if ($this->expires_at !== null && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }
}
