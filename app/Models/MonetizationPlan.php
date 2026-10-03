<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MonetizationPlan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'price' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'validity_days' => 'integer',
        'features' => 'array',
        'is_active' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(MonetizationProduct::class, 'monetization_product_id');
    }

    public function promotions(): HasMany
    {
        return $this->hasMany(Promotion::class, 'plan_id');
    }
}
