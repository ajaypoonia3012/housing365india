<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VerificationType extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'required_documents' => 'array',
        'is_active' => 'boolean',
        'validity_days' => 'integer',
    ];

    public function verifications(): HasMany
    {
        return $this->hasMany(Verification::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
