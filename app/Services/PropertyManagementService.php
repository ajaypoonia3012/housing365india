<?php

namespace App\Services;

use App\Models\ManagementEnrollment;
use App\Models\ManagementPlan;
use App\Models\Property;
use App\Models\ServiceAuditLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class PropertyManagementService
{
    /**
     * Owner requests optional Property Management enrollment.
     * Note: Does NOT automatically activate! Status starts as REQUESTED.
     */
    public function requestEnrollment(User $owner, Property $property, ManagementPlan $plan, ?string $notes = null): ManagementEnrollment
    {
        if (! $property->isOwnedBy($owner) && ! $owner->hasRole('admin')) {
            throw new InvalidArgumentException('You are not authorized to enroll this property in Property Management.');
        }

        return DB::transaction(function () use ($owner, $property, $plan, $notes) {
            $pricingSnapshot = [
                'plan_name' => $plan->name,
                'pricing_model' => $plan->pricing_model,
                'fee_percentage' => $plan->fee_percentage,
                'fixed_fee' => $plan->fixed_fee,
                'billing_frequency' => $plan->billing_frequency,
                'included_services' => $plan->included_services,
                'snapshot_at' => now()->toIso8601String(),
            ];

            $enrollment = ManagementEnrollment::updateOrCreate(
                [
                    'user_id' => $owner->id,
                    'property_id' => $property->id,
                ],
                [
                    'management_plan_id' => $plan->id,
                    'status' => ManagementEnrollment::STATUS_REQUESTED,
                    'pricing_snapshot' => $pricingSnapshot,
                    'notes' => $notes,
                    'requested_at' => now(),
                ]
            );

            ServiceAuditLog::record($enrollment, 'management_requested', $owner, [
                'plan' => $plan->slug,
                'property_id' => $property->id,
            ]);

            return $enrollment;
        });
    }

    /**
     * Admin issues Management Agreement for owner review and acceptance.
     */
    public function issueAgreement(ManagementEnrollment $enrollment, ?User $admin = null): ManagementEnrollment
    {
        return DB::transaction(function () use ($enrollment, $admin) {
            $enrollment->update([
                'status' => ManagementEnrollment::STATUS_AGREEMENT_PENDING,
                'approved_at' => now(),
            ]);

            ServiceAuditLog::record($enrollment, 'agreement_issued', $admin, [
                'agreement_reference' => $enrollment->agreement_reference,
            ]);

            return $enrollment->fresh();
        });
    }

    /**
     * Owner formally accepts the separate Property Management Agreement.
     * Only upon explicit acceptance does enrollment become ACTIVE.
     */
    public function acceptAgreement(ManagementEnrollment $enrollment, User $owner, string $ipAddress): ManagementEnrollment
    {
        if ($enrollment->user_id !== $owner->id && ! $owner->hasRole('admin')) {
            throw new InvalidArgumentException('Only the property owner can accept this management agreement.');
        }

        return DB::transaction(function () use ($enrollment, $owner, $ipAddress) {
            $enrollment->update([
                'status' => ManagementEnrollment::STATUS_ACTIVE,
                'agreement_accepted_at' => now(),
                'agreement_accepted_ip' => $ipAddress,
                'activated_at' => now(),
            ]);

            // Activate managed flag on property
            $enrollment->property->update([
                'is_managed' => true,
            ]);

            ServiceAuditLog::record($enrollment, 'agreement_accepted', $owner, [
                'ip' => $ipAddress,
                'agreement_reference' => $enrollment->agreement_reference,
            ]);

            return $enrollment->fresh();
        });
    }

    /**
     * Cancel or terminate management enrollment (returns property to Leasing Only).
     */
    public function cancelEnrollment(ManagementEnrollment $enrollment, User $user, ?string $reason = null): ManagementEnrollment
    {
        if ($enrollment->user_id !== $user->id && ! $user->hasRole('admin')) {
            throw new InvalidArgumentException('You are not authorized to cancel this management enrollment.');
        }

        return DB::transaction(function () use ($enrollment, $user, $reason) {
            $enrollment->update([
                'status' => ManagementEnrollment::STATUS_CANCELLED,
                'cancelled_at' => now(),
                'notes' => $reason ? ($enrollment->notes.' | Cancellation Reason: '.$reason) : $enrollment->notes,
            ]);

            // Revert property to unmanaged (Leasing Only)
            $enrollment->property->update([
                'is_managed' => false,
            ]);

            ServiceAuditLog::record($enrollment, 'management_cancelled', $user, [
                'reason' => $reason,
            ]);

            return $enrollment->fresh();
        });
    }
}
