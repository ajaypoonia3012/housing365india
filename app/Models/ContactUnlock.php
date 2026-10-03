<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactUnlock extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'property_id',
        'contact_access_purchase_id',
        'unlocked_at',
        'status',
    ];

    protected $casts = [
        'unlocked_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(ContactAccessPurchase::class, 'contact_access_purchase_id');
    }
}
