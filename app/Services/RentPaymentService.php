<?php

namespace App\Services;

use App\Contracts\RentPaymentGatewayInterface;
use App\Models\Property;
use App\Models\RentAccount;
use App\Models\RentLedger;
use App\Models\RentPayment;
use App\Models\ServiceAuditLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class RentPaymentService
{
    public function __construct(
        protected RentPaymentGatewayInterface $paymentGateway
    ) {}

    /**
     * Establish a recurring rent account for a tenancy.
     * Note: Does NOT enroll the property into Property Management.
     */
    public function createRentAccount(Property $property, User $owner, User $tenant, array $data): RentAccount
    {
        return RentAccount::create([
            'property_id' => $property->id,
            'owner_id' => $owner->id,
            'tenant_id' => $tenant->id,
            'lease_start_date' => $data['lease_start_date'] ?? now(),
            'lease_end_date' => $data['lease_end_date'] ?? now()->addMonths(11),
            'monthly_rent' => $data['monthly_rent'] ?? $property->price,
            'security_deposit' => $data['security_deposit'] ?? ($property->deposit_amount ?? 0),
            'due_day_of_month' => $data['due_day_of_month'] ?? 5,
            'status' => RentAccount::STATUS_ACTIVE,
            'notes' => $data['notes'] ?? null,
        ]);
    }

    /**
     * Generate or fetch monthly ledger entry for a billing period.
     */
    public function generateMonthlyLedger(RentAccount $account, string $billingPeriod, ?string $dueDate = null): RentLedger
    {
        $existing = RentLedger::where('rent_account_id', $account->id)
            ->where('billing_period', $billingPeriod)
            ->first();

        if ($existing) {
            return $existing;
        }

        $due = $dueDate ? Carbon::parse($dueDate) : Carbon::parse("{$billingPeriod}-{$account->due_day_of_month}");

        return RentLedger::create([
            'rent_account_id' => $account->id,
            'property_id' => $account->property_id,
            'tenant_id' => $account->tenant_id,
            'owner_id' => $account->owner_id,
            'billing_period' => $billingPeriod,
            'due_date' => $due,
            'amount_due' => $account->monthly_rent,
            'amount_paid' => 0.00,
            'outstanding_balance' => $account->monthly_rent,
            'status' => RentLedger::STATUS_DUE,
            'is_late' => false,
            'late_fee' => 0.00,
        ]);
    }

    /**
     * Initiate a rent payment transaction with idempotency protection.
     */
    public function initiatePayment(RentLedger $ledger, User $tenant, float $amount, string $paymentMethod = 'upi', ?string $idempotencyKey = null): array
    {
        if ($ledger->tenant_id !== $tenant->id && ! $tenant->hasRole('admin')) {
            throw new InvalidArgumentException('Unauthorized: Only the assigned tenant can pay this rent ledger.');
        }

        if ($amount <= 0) {
            throw new InvalidArgumentException('Payment amount must be greater than zero.');
        }

        $idempotency = $idempotencyKey ?: 'RENT-'.uniqid().'-'.time();

        // Idempotency check: if identical key exists, return existing order
        $existing = RentPayment::where('idempotency_key', $idempotency)->first();
        if ($existing && in_array($existing->status, [RentPayment::STATUS_PAID, RentPayment::STATUS_INITIATED])) {
            return [
                'payment' => $existing,
                'gateway_order' => ['order_id' => $existing->gateway_order_id],
                'is_duplicate' => true,
            ];
        }

        return DB::transaction(function () use ($ledger, $tenant, $amount, $paymentMethod, $idempotency) {
            $gatewayOrder = $this->paymentGateway->createPaymentOrder($ledger, $amount);

            $payment = RentPayment::create([
                'rent_ledger_id' => $ledger->id,
                'rent_account_id' => $ledger->rent_account_id,
                'tenant_id' => $tenant->id,
                'amount' => $amount,
                'payment_method' => $paymentMethod,
                'gateway_order_id' => $gatewayOrder['order_id'],
                'idempotency_key' => $idempotency,
                'status' => RentPayment::STATUS_INITIATED,
            ]);

            ServiceAuditLog::record($payment, 'rent_payment_initiated', $tenant, [
                'ledger_id' => $ledger->id,
                'amount' => $amount,
                'order_id' => $gatewayOrder['order_id'],
            ]);

            return [
                'payment' => $payment,
                'gateway_order' => $gatewayOrder,
                'is_duplicate' => false,
            ];
        });
    }

    /**
     * Server-side payment verification and ledger balance reconciliation.
     * Prevents duplicate execution.
     */
    public function confirmPayment(RentPayment $payment, array $paymentData): RentPayment
    {
        // Duplicate callback guard
        if ($payment->status === RentPayment::STATUS_PAID) {
            return $payment;
        }

        $verified = $this->paymentGateway->verifyPayment($payment, $paymentData);

        if (! $verified) {
            $payment->update([
                'status' => RentPayment::STATUS_FAILED,
                'failure_reason' => 'Payment gateway verification signature mismatch or declined.',
            ]);

            ServiceAuditLog::record($payment, 'rent_payment_failed', null, [
                'reason' => 'Verification failed',
            ]);

            return $payment->fresh();
        }

        return DB::transaction(function () use ($payment, $paymentData) {
            $payment->update([
                'status' => RentPayment::STATUS_PAID,
                'gateway_payment_id' => $paymentData['gateway_payment_id'] ?? null,
                'gateway_signature' => $paymentData['gateway_signature'] ?? null,
                'paid_at' => now(),
            ]);

            // Reconcile ledger
            $ledger = $payment->ledger;
            $newPaid = (float) $ledger->amount_paid + (float) $payment->amount;
            $newOutstanding = max(0.00, (float) $ledger->amount_due + (float) $ledger->late_fee - $newPaid);
            $newStatus = $newOutstanding <= 0.00 ? RentLedger::STATUS_PAID : RentLedger::STATUS_PARTIALLY_PAID;

            $ledger->update([
                'amount_paid' => $newPaid,
                'outstanding_balance' => $newOutstanding,
                'status' => $newStatus,
                'paid_at' => $newStatus === RentLedger::STATUS_PAID ? now() : $ledger->paid_at,
            ]);

            ServiceAuditLog::record($payment, 'rent_payment_confirmed', null, [
                'paid_amount' => $payment->amount,
                'remaining_outstanding' => $newOutstanding,
                'ledger_status' => $newStatus,
            ]);

            return $payment->fresh();
        });
    }

    /**
     * Webhook payment handler.
     */
    public function handleWebhook(array $payload, string $signature): bool
    {
        if (! $this->paymentGateway->verifyWebhookSignature($payload, $signature)) {
            return false;
        }

        $orderId = $payload['order_id'] ?? null;
        if (! $orderId) {
            return false;
        }

        $payment = RentPayment::where('gateway_order_id', $orderId)->first();
        if (! $payment) {
            return false;
        }

        $this->confirmPayment($payment, $payload);

        return true;
    }

    /**
     * Refund / payment reversal.
     */
    public function refundPayment(RentPayment $payment, User $actor, ?string $reason = null): RentPayment
    {
        if ($payment->status !== RentPayment::STATUS_PAID) {
            throw new InvalidArgumentException('Only successfully paid transactions can be refunded.');
        }

        return DB::transaction(function () use ($payment, $actor, $reason) {
            $payment->update([
                'status' => RentPayment::STATUS_REFUNDED,
                'failure_reason' => $reason ? ('Refunded: '.$reason) : 'Refunded by administrator',
            ]);

            // Revert ledger balance
            $ledger = $payment->ledger;
            $newPaid = max(0.00, (float) $ledger->amount_paid - (float) $payment->amount);
            $newOutstanding = max(0.00, (float) $ledger->amount_due + (float) $ledger->late_fee - $newPaid);

            $ledger->update([
                'amount_paid' => $newPaid,
                'outstanding_balance' => $newOutstanding,
                'status' => $newPaid == 0.00 ? RentLedger::STATUS_DUE : RentLedger::STATUS_PARTIALLY_PAID,
            ]);

            ServiceAuditLog::record($payment, 'rent_payment_refunded', $actor, [
                'refund_amount' => $payment->amount,
                'reason' => $reason,
            ]);

            return $payment->fresh();
        });
    }
}
