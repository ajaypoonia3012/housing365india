<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'custom_fields' => 'array',
        'utm_data' => 'array',
        'first_response_at' => 'datetime',
        'sla_deadline_at' => 'datetime',
        'breached_at' => 'datetime',
        'converted_at' => 'datetime',
        'last_contacted_at' => 'datetime',
        'converted_deal_value' => 'decimal:2',
    ];

    public const STATUS_NEW = 'NEW';
    public const STATUS_ASSIGNED = 'ASSIGNED';
    public const STATUS_CONTACTED = 'CONTACTED';
    public const STATUS_QUALIFIED = 'QUALIFIED';
    public const STATUS_VIEWING_REQUESTED = 'VIEWING_REQUESTED';
    public const STATUS_VIEWING_SCHEDULED = 'VIEWING_SCHEDULED';
    public const STATUS_VIEWING_COMPLETED = 'VIEWING_COMPLETED';
    public const STATUS_APPLICATION_STARTED = 'APPLICATION_STARTED';
    public const STATUS_APPLICATION_SUBMITTED = 'APPLICATION_SUBMITTED';
    public const STATUS_AGREEMENT_DRAFTED = 'AGREEMENT_DRAFTED';
    public const STATUS_CONVERTED = 'CONVERTED';
    public const STATUS_LOST = 'LOST';
    public const STATUS_CLOSED = 'CLOSED';
    public const STATUS_SPAM = 'SPAM';

    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_NEW => '🔴 New (Uncontacted)',
            self::STATUS_ASSIGNED => '🟠 Assigned to Agent',
            self::STATUS_CONTACTED => '🟡 Contacted (Follow-Up In Progress)',
            self::STATUS_QUALIFIED => '🔵 Qualified (Requirement Matched)',
            self::STATUS_VIEWING_SCHEDULED => '📅 Site Visit / Viewing Scheduled',
            self::STATUS_VIEWING_COMPLETED => '🏡 Site Visit Completed',
            self::STATUS_APPLICATION_SUBMITTED => '📋 Tenant KYC / Application Submitted',
            self::STATUS_AGREEMENT_DRAFTED => '📄 Agreement Drafted & In Review',
            self::STATUS_CONVERTED => '🟢 Converted / Deal Closed',
            self::STATUS_LOST => '⚫ Lost / Requirement Cancelled',
            self::STATUS_CLOSED => '⚪ Closed',
            self::STATUS_SPAM => '🚫 Spam / Invalid Contact',
        ];
    }

    public static function getNextPipelineStage(string $currentStatus): ?string
    {
        return match ($currentStatus) {
            self::STATUS_NEW => self::STATUS_CONTACTED,
            self::STATUS_ASSIGNED => self::STATUS_CONTACTED,
            self::STATUS_CONTACTED => self::STATUS_QUALIFIED,
            self::STATUS_QUALIFIED => self::STATUS_VIEWING_SCHEDULED,
            self::STATUS_VIEWING_REQUESTED => self::STATUS_VIEWING_SCHEDULED,
            self::STATUS_VIEWING_SCHEDULED => self::STATUS_VIEWING_COMPLETED,
            self::STATUS_VIEWING_COMPLETED => self::STATUS_APPLICATION_SUBMITTED,
            self::STATUS_APPLICATION_STARTED => self::STATUS_APPLICATION_SUBMITTED,
            self::STATUS_APPLICATION_SUBMITTED => self::STATUS_AGREEMENT_DRAFTED,
            self::STATUS_AGREEMENT_DRAFTED => self::STATUS_CONVERTED,
            default => null,
        };
    }

    public function isConverted(): bool
    {
        return $this->status === self::STATUS_CONVERTED;
    }

    public function documents(): HasMany
    {
        return $this->hasMany(LeadDocument::class);
    }

    public function getNameAttribute(): ?string
    {
        return $this->attributes['contact_name'] ?? null;
    }

    public function setNameAttribute(?string $value): void
    {
        $this->attributes['contact_name'] = $value;
    }

    public function getPhoneAttribute(): ?string
    {
        return $this->attributes['contact_phone'] ?? null;
    }

    public function setPhoneAttribute(?string $value): void
    {
        $this->attributes['contact_phone'] = $value;
    }

    public function getEmailAttribute(): ?string
    {
        return $this->attributes['contact_email'] ?? null;
    }

    public function setEmailAttribute(?string $value): void
    {
        $this->attributes['contact_email'] = $value;
    }

    public function inquiry(): BelongsTo
    {
        return $this->belongsTo(Inquiry::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function assignedOrganization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'assigned_organization_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(LeadAssignment::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(LeadStatusHistory::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(LeadActivity::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(LeadNote::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(LeadTask::class);
    }
}
