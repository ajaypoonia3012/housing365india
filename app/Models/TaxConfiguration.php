<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxConfiguration extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'tax_enabled' => 'boolean',
        'tax_rate' => 'decimal:2',
        'cgst_enabled' => 'boolean',
        'sgst_enabled' => 'boolean',
        'igst_enabled' => 'boolean',
        'cgst_rate' => 'decimal:2',
        'sgst_rate' => 'decimal:2',
        'igst_rate' => 'decimal:2',
        'effective_from' => 'datetime',
        'effective_until' => 'datetime',
        'applicable_to' => 'array',
        'active' => 'boolean',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_user_id');
    }

    /**
     * Scope query to active configurations.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    /**
     * Scope query to configurations effective at a given date.
     */
    public function scopeEffectiveAt(Builder $query, ?Carbon $date = null): Builder
    {
        $date = $date ?? Carbon::now();

        return $query->where(function ($q) use ($date) {
            $q->whereNull('effective_from')
                ->orWhere('effective_from', '<=', $date);
        })->where(function ($q) use ($date) {
            $q->whereNull('effective_until')
                ->orWhere('effective_until', '>=', $date);
        });
    }

    /**
     * Fetch the active effective tax configuration for a specific date and optional product code.
     */
    public static function getActiveConfiguration(?Carbon $date = null, ?string $productCode = null): ?self
    {
        $date = $date ?? Carbon::now();

        $query = static::query()
            ->active()
            ->effectiveAt($date);

        if ($productCode) {
            // First check if a product-specific rule exists
            $specific = (clone $query)
                ->whereNotNull('applicable_to')
                ->whereJsonContains('applicable_to', $productCode)
                ->orderByDesc('effective_from')
                ->orderByDesc('id')
                ->first();

            if ($specific) {
                return $specific;
            }
        }

        // Return global configuration (where applicable_to is null or empty)
        return $query
            ->where(function ($q) {
                $q->whereNull('applicable_to')
                    ->orWhere('applicable_to', '[]')
                    ->orWhere('applicable_to', '');
            })
            ->orderByDesc('effective_from')
            ->orderByDesc('id')
            ->first();
    }
}
