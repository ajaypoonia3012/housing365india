<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ManagementPlan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'pricing_model',
        'fee_percentage',
        'fixed_fee',
        'billing_frequency',
        'included_services',
        'sla_details',
        'terms_content',
        'is_active',
    ];

    protected $casts = [
        'fee_percentage' => 'decimal:2',
        'fixed_fee' => 'decimal:2',
        'included_services' => 'array',
        'sla_details' => 'array',
        'is_active' => 'boolean',
    ];

    public function enrollments(): HasMany
    {
        return $this->hasMany(ManagementEnrollment::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getFeeDisplayAttribute(): string
    {
        if ($this->pricing_model === 'percentage' && $this->fee_percentage) {
            return rtrim(rtrim(number_format((float) $this->fee_percentage, 2), '0'), '.').'% '.ucfirst($this->billing_frequency ?: 'monthly');
        }

        if ($this->fixed_fee) {
            return '₹'.number_format((float) $this->fixed_fee, 2).' / '.($this->billing_frequency ?: 'month');
        }

        return 'Plan Pricing Configured in Admin';
    }

    public function getFeaturesAttribute(): array
    {
        return is_array($this->included_services) ? $this->included_services : [];
    }
}
