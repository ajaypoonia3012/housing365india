<?php

namespace App\Contracts;

use App\Models\TenantScreening;

interface IdentityVerificationProviderInterface
{
    /**
     * Request an identity/background verification check for a screening.
     *
     * @param  array<string, mixed>  $options
     * @return array{provider_reference: string, status: string, tracking_url?: string}
     */
    public function requestVerification(TenantScreening $screening, array $options = []): array;

    /**
     * Check the status of a pending verification from the provider.
     *
     * @return array{status: string, verified: bool, confidence_score?: float, checks?: array, error?: string}
     */
    public function checkVerificationStatus(string $providerReference): array;
}
