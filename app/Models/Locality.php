<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Locality extends Model
{
    protected $guarded = [];

    protected $casts = [
        'society_clusters' => 'array',
        'is_active' => 'boolean',
        'is_indexable' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'display_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (Locality $locality) {
            if (empty($locality->slug)) {
                $locality->slug = Str::slug($locality->name);
            }
        });
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(CityLocation::class, 'city_location_id');
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeIndexable(Builder $query): Builder
    {
        return $query->where('is_active', true)->where('is_indexable', true);
    }

    /**
     * Count live properties in this locality.
     */
    public function getActivePropertiesCountAttribute(): int
    {
        return Property::where('status', 'Available')
            ->where('city', $this->city?->name)
            ->where(function ($q) {
                $q->where('locality', $this->name)
                    ->orWhere('locality', 'LIKE', '%'.$this->name.'%');
            })
            ->count();
    }
}
