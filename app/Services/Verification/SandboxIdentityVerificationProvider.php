<?php

namespace App\Services\Verification;

use App\Contracts\IdentityVerificationProviderInterface;
use App\Models\TenantScreening;

class SandboxIdentityVerificationProvider implements IdentityVerificationProviderInterface
{
    /**
     * Request an identity verification check.
     */
    public function requestVerification(TenantScreening $screening, array $options = []): array
    {
        $reference = 'IDV-SANDBOX-'.strtoupper(uniqid());

        return [
            'provider_reference' => $reference,
            'status' => 'IN_PROGRESS',
            'tracking_url' => 'https://sandbox.housing365.test/kyc/'.$reference,
        ];
    }

    /**
     * Check verification status.
     */
    public function checkVerificationStatus(string $providerReference): array
    {
        // Fail-safe simulation: if reference ends with 'FAIL', simulate failure; else success
        if (str_ends_with($providerReference, 'FAIL')) {
            return [
                'status' => 'FAILED',
                'verified' => false,
                'confidence_score' => 0.15,
                'error' => 'Document forgery or identity mismatch detected.',
            ];
        }

        return [
            'status' => 'VERIFIED',
            'verified' => true,
            'confidence_score' => 0.98,
            'checks' => [
                'id_match' => true,
                'liveness' => true,
                'criminal_background_clear' => true,
                'credit_score_band' => '750-800',
            ],
        ];
    }
}
