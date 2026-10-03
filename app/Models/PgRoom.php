<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PgRoom extends Model
{
    protected $guarded = [];

    protected $casts = [
        'monthly_rent_per_bed' => 'decimal:2',
        'deposit_per_bed' => 'decimal:2',
        'amenities' => 'array',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function beds(): HasMany
    {
        return $this->hasMany(PgBed::class);
    }
}
