<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $guarded = [];

    protected $casts = [
        'sections_data' => 'array',
        'is_published' => 'boolean',
    ];
}
