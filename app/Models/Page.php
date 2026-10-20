<?php

namespace App\Models;

use App\Traits\HasAutoSlug;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasAutoSlug;

    protected $guarded = [];

    protected $casts = [
        'sections_data' => 'array',
        'is_published' => 'boolean',
    ];
}
