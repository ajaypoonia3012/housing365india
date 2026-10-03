<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CareServiceCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'is_emergency_available',
        'is_active',
        'sort_order',
        'base_price',
        'pricing_type',
    ];

    protected $casts = [
        'is_emergency_available' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'base_price' => 'decimal:2',
    ];

    public function serviceRequests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class, 'service_category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('name');
    }

    public function scopeEmergency($query)
    {
        return $query->where('is_active', true)->where('is_emergency_available', true);
    }
}
