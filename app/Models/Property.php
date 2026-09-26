<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $guarded = [];

    protected $casts = [
        'gallery' => 'array',
        'amenities' => 'array',
        'featured' => 'boolean',
        'price' => 'decimal:2',
        'bathrooms' => 'float',
    ];
}
