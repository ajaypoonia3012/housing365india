<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContactAccessPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'credits',
        'price',
        'currency',
        'validity_days',
        'badge_text',
        'features',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'credits' => 'integer',
        'price' => 'decimal:2',
        'validity_days' => 'integer',
        'features' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function purchases(): HasMany
    {
        return $this->hasMany(ContactAccessPurchase::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('price');
    }
}
