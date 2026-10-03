<?php

namespace App\Services\Payment;

use App\Contracts\ReferralPayoutGatewayInterface;
use App\Models\ReferralPayout;

class AdminManualReferralPayoutGateway implements ReferralPayoutGatewayInterface
{
    /**
     * In admin-manual mode, payouts are recorded and approved by Housing365 operations/finance.
     * This avoids claiming automated bank/UPI transfers without a licensed banking partner.
     */
    public function processPayout(ReferralPayout $payout, array $metadata = []): bool
    {
        if ($payout->status === ReferralPayout::STATUS_FAILED || ($metadata['status'] ?? '') === ReferralPayout::STATUS_FAILED) {
            $payout->update([
                'status' => ReferralPayout::STATUS_FAILED,
                'failure_reason' => $metadata['failure_reason'] ?? $payout->failure_reason ?? 'Disbursement failed',
            ]);

            return false;
        }

        // Require transaction or payout reference to mark processed
        $reference = $metadata['transaction_reference'] ?? $metadata['provider_reference'] ?? null;

        if (empty($reference)) {
            $payout->update([
                'status' => ReferralPayout::STATUS_PROCESSING,
                'admin_notes' => $metadata['admin_notes'] ?? 'Payout queued for admin UPI disbursement.',
            ]);

            return true;
        }

        $payout->update([
            'status' => ReferralPayout::STATUS_PAID,
            'transaction_reference' => $reference,
            'provider_reference' => $metadata['provider_reference'] ?? $reference,
            'processed_at' => now(),
            'admin_notes' => $metadata['admin_notes'] ?? $payout->admin_notes,
        ]);

        return true;
    }

    public function checkPayoutStatus(ReferralPayout $payout): string
    {
        return $payout->status;
    }
}
