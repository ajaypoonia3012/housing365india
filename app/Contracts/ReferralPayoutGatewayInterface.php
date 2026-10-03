<?php

namespace App\Contracts;

use App\Models\ReferralPayout;

interface ReferralPayoutGatewayInterface
{
    /**
     * Process or schedule a referral payout.
     */
    public function processPayout(ReferralPayout $payout, array $metadata = []): bool;

    /**
     * Check status of an existing payout.
     */
    public function checkPayoutStatus(ReferralPayout $payout): string;
}
