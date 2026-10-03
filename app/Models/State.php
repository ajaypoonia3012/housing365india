<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class State extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'is_indexable' => 'boolean',
        'display_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (State $state) {
            if (empty($state->slug)) {
                $state->slug = Str::slug($state->name);
            }
        });
    }

    public function cities(): HasMany
    {
        return $this->hasMany(CityLocation::class, 'state_id');
    }

    public function localities(): HasMany
    {
        return $this->hasMany(Locality::class, 'state_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeIndexable(Builder $query): Builder
    {
        return $query->where('is_active', true)->where('is_indexable', true);
    }
}
