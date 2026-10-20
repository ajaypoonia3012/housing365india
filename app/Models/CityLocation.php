<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CityLocation extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'is_indexable' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'display_order' => 'integer',
    ];

    protected $attributes = [
        'country' => 'IN',
    ];

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function localities(): HasMany
    {
        return $this->hasMany(Locality::class, 'city_location_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeIndexable(Builder $query): Builder
    {
        return $query->where('is_active', true)->where('is_indexable', true);
    }

    public function getActivePropertiesCountAttribute(): int
    {
        return Property::where('status', 'Available')
            ->where('city', $this->name)
            ->count();
    }
}
