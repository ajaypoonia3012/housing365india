<?php

namespace App\Models;

use App\Traits\HasAutoSlug;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    use HasAutoSlug;

    protected $guarded = [];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];
}
