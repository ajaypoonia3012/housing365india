<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerificationDocument extends Model
{
    protected $guarded = [];

    protected $casts = [
        'ocr_extracted_data' => 'array',
        'verified_at' => 'datetime',
        'retention_until' => 'datetime',
        'anonymized_at' => 'datetime',
    ];

    public const STATUS_PENDING = 'PENDING';

    public const STATUS_VERIFIED = 'VERIFIED';

    public const STATUS_REJECTED = 'REJECTED';

    public const OCR_PENDING = 'PENDING';

    public const OCR_PROCESSING = 'PROCESSING';

    public const OCR_SUCCESS = 'SUCCESS';

    public const OCR_FAILED = 'FAILED';

    public const OCR_SKIPPED = 'SKIPPED';

    public function verification(): BelongsTo
    {
        return $this->belongsTo(Verification::class);
    }

    public function screening(): BelongsTo
    {
        return $this->belongsTo(TenantScreening::class, 'tenant_screening_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mask sensitive document number for UI display.
     */
    public static function maskIdentifier(string $raw, string $type): string
    {
        $clean = preg_replace('/[^A-Za-z0-9]/', '', $raw);
        $len = strlen($clean);

        if ($len <= 4) {
            return str_repeat('X', $len);
        }

        $lastFour = substr($clean, -4);

        if (strtolower($type) === 'aadhaar' && $len === 12) {
            return 'XXXX XXXX '.$lastFour;
        }

        if (strtolower($type) === 'pan' && $len === 10) {
            return 'XXXXX'.$lastFour.substr($clean, -1);
        }

        return str_repeat('X', max(0, $len - 4)).$lastFour;
    }
}
