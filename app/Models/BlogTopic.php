<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogTopic extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_used' => 'boolean',
        'used_at' => 'datetime',
        'keywords' => 'array',
    ];

    public function blogPost(): BelongsTo
    {
        return $this->belongsTo(BlogPost::class);
    }

    /**
     * Mark this topic as generated/used by a specific blog post.
     */
    public function markAsUsed(int $blogPostId): void
    {
        $this->update([
            'is_used' => true,
            'used_at' => now(),
            'blog_post_id' => $blogPostId,
        ]);
    }
}
