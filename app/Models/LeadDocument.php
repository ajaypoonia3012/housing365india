<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class LeadDocument extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public const TYPE_AGREEMENT_DRAFT = 'agreement_draft';
    public const TYPE_SIGNED_AGREEMENT = 'signed_agreement';
    public const TYPE_TENANT_KYC = 'tenant_kyc';
    public const TYPE_OWNER_TITLE_DEED = 'owner_title_deed';
    public const TYPE_POLICE_VERIFICATION = 'police_verification';
    public const TYPE_INSPECTION_REPORT = 'inspection_report';
    public const TYPE_OTHER = 'other';

    public static function getDocumentTypeOptions(): array
    {
        return [
            self::TYPE_AGREEMENT_DRAFT => 'Draft Rental/Sale Agreement (PDF/DOC)',
            self::TYPE_SIGNED_AGREEMENT => 'Signed & E-Stamped Agreement',
            self::TYPE_TENANT_KYC => 'Tenant KYC (Aadhaar / PAN / Passport)',
            self::TYPE_OWNER_TITLE_DEED => 'Owner Title Deed / Electricity Bill',
            self::TYPE_POLICE_VERIFICATION => 'Police Verification Form / Receipt',
            self::TYPE_INSPECTION_REPORT => 'Property Inspection / Condition Report',
            self::TYPE_OTHER => 'Other Supporting Document',
        ];
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by_user_id');
    }

    public function getDownloadUrlAttribute(): ?string
    {
        if (empty($this->file_path)) {
            return null;
        }

        if (str_starts_with($this->file_path, 'http://') || str_starts_with($this->file_path, 'https://')) {
            return $this->file_path;
        }

        return Storage::disk('public')->url($this->file_path);
    }

    public function getFormattedFileSizeAttribute(): string
    {
        $bytes = $this->file_size;
        if (! $bytes) {
            return '—';
        }

        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        }

        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 1) . ' KB';
        }

        return $bytes . ' bytes';
    }
}
