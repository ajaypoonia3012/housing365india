<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorProfile extends Model
{
    protected $fillable = [
        'user_id',
        'business_name',
        'service_categories',
        'service_areas',
        'verification_status',
        'is_available',
        'rating',
        'jobs_completed',
    ];

    protected $casts = [
        'service_categories' => 'array',
        'service_areas' => 'array',
        'is_available' => 'boolean',
        'rating' => 'decimal:2',
        'jobs_completed' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
