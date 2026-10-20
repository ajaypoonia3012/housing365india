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
        'linkedin_shared_at' => 'datetime',
        'auto_post_to_linkedin' => 'boolean',
    ];

    public function getPublicUrlAttribute(): string
    {
        return url('/blog/' . $this->slug);
    }

    public function isSharedOnLinkedIn(): bool
    {
        return ! empty($this->linkedin_shared_at);
    }
}
