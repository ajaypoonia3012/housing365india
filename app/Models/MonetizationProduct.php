<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MonetizationProduct extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];

    public function plans(): HasMany
    {
        return $this->hasMany(MonetizationPlan::class, 'monetization_product_id');
    }
}
