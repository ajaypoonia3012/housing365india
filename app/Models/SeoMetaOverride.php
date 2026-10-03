<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoMetaOverride extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_indexable' => 'boolean',
        'schema_json' => 'array',
        'faqs' => 'array',
        'last_reviewed_at' => 'datetime',
    ];

    public static function findByPath(string $path): ?self
    {
        $normalized = '/'.ltrim(rtrim($path, '/'), '/');

        return static::where('path', $normalized)
            ->orWhere('path', $normalized.'/')
            ->first();
    }
}
