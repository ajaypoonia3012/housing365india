<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FraudRule extends Model
{
    use HasFactory;

    public const ACTION_NONE = 'NONE';

    public const ACTION_MONITOR = 'MONITOR';

    public const ACTION_REQUIRE_VERIFICATION = 'REQUIRE_VERIFICATION';

    public const ACTION_LIMIT_ACTION = 'LIMIT_ACTION';

    public const ACTION_SEND_TO_REVIEW = 'SEND_TO_REVIEW';

    public const ACTION_SUSPEND_LISTING = 'SUSPEND_LISTING';

    public const ACTION_SUSPEND_ACCOUNT = 'SUSPEND_ACCOUNT';

    protected $guarded = [];

    protected $casts = [
        'threshold' => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
