<?php

namespace App\Models;

use App\Traits\HasAutoSlug;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasAutoSlug;

    protected $guarded = [];

    protected $casts = [
        'features' => 'array',
        'scope' => 'array',
        'how_it_works' => 'array',
        'who_its_for' => 'array',
        'faq_items' => 'array',
        'is_active' => 'boolean',
    ];
}
