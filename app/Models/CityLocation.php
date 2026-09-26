<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CityLocation extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_featured' => 'boolean',
    ];
}
