<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadRoutingRule extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
        'round_robin_user_ids' => 'array',
        'round_robin_last_index' => 'integer',
        'priority' => 'integer',
    ];

    public function assignToUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assign_to_user_id');
    }

    public function assignToOrganization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'assign_to_organization_id');
    }

    public function fallbackUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'fallback_user_id');
    }
}
