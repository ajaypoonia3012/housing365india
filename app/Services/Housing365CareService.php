<?php

namespace App\Services;

use App\Contracts\CarePaymentGatewayInterface;
use App\Models\CareInvoice;
use App\Models\CareServiceCategory;
use App\Models\Property;
use App\Models\ServiceAuditLog;
use App\Models\ServiceQuote;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Models\VendorPayout;
use App\Models\VendorProfile;
use App\Models\WorkOrder;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class Housing365CareService
{
    public function __construct(
        protected ?CarePaymentGatewayInterface $paymentGateway = null
    ) {}

    /**
     * Create a new Service Request for Care.
     */
    public function createRequest(User $requester, array $data): ServiceRequest
    {
        $propertyId = $data['property_id'] ?? null;
        if ($propertyId) {
            $property = Property::findOrFail($propertyId);
            $isOwner = $property->isOwnedBy($requester);
            $hasTenantRole = $requester->hasRole('tenant');

            if (! $isOwner && ! $hasTenantRole && ! $requester->hasRole('admin')) {
                throw new InvalidArgumentException('You are not authorized to create service requests for this property.');
            }
        }

        $category = CareServiceCategory::findOrFail($data['service_category_id']);

        return DB::transaction(function () use ($requester, $data, $category) {
            $priority = $data['priority'] ?? ($category->is_emergency_available && ! empty($data['is_emergency']) ? 'emergency' : 'normal');

            $serviceRequest = ServiceRequest::create([
                'user_id' => $requester->id,
                'property_id' => $data['property_id'] ?? null,
                'pg_room_id' => $data['pg_room_id'] ?? null,
                'pg_bed_id' => $data['pg_bed_id'] ?? null,
                'coworking_inventory_id' => $data['coworking_inventory_id'] ?? null,
                'service_category_id' => $category->id,
                'title' => strip_tags(trim($data['title'] ?? ($category->name.' Service Request'))),
                'description' => strip_tags(trim($data['description'])),
                'priority' => $priority,
                'status' => ServiceRequest::STATUS_SUBMITTED,
                'preferred_date' => $data['preferred_date'] ?? null,
                'preferred_time_window' => $data['preferred_time_window'] ?? null,
                'contact_preference' => $data['contact_preference'] ?? 'phone',
                'service_location' => $data['service_location'] ?? null,
                'access_instructions' => $data['access_instructions'] ?? null,
                'photos' => $data['photos'] ?? null,
                'video_url' => $data['video_url'] ?? null,
            ]);

            ServiceAuditLog::record($serviceRequest, 'service_request_created', $requester, [
                'priority' => $priority,
                'category' => $category->slug,
            ]);

            return $serviceRequest;
        });
    }

    /**
     * Triage service request (Admin / Support).
     */
    public function triageRequest(ServiceRequest $request, array $data, ?User $admin = null): ServiceRequest
    {
        return DB::transaction(function () use ($request, $data, $admin) {
            $updates = [
                'status' => ServiceRequest::STATUS_TRIAGED,
                'triaged_at' => now(),
            ];

            if (isset($data['service_category_id'])) {
                $updates['service_category_id'] = $data['service_category_id'];
            }
            if (isset($data['priority'])) {
                $updates['priority'] = $data['priority'];
            }
            if (isset($data['admin_notes'])) {
                $updates['admin_notes'] = $data['admin_notes'];
            }

            $request->update($updates);

            ServiceAuditLog::record($request, 'service_request_triaged', $admin, [
                'priority' => $request->priority,
            ]);

            return $request->fresh();
        });
    }

    /**
     * Assign a verified vendor to the service request with OFFERED status.
     */
    public function assignVendor(ServiceRequest $request, User $vendor, ?User $admin = null): ServiceRequest
    {
        if (! $vendor->hasRole('vendor') && ! $vendor->hasRole('admin')) {
            throw new InvalidArgumentException('User must hold the vendor persona to be assigned jobs.');
        }

        return DB::transaction(function () use ($request, $vendor, $admin) {
            $request->update([
                'assigned_vendor_id' => $vendor->id,
                'assignment_status' => 'OFFERED',
                'status' => ServiceRequest::STATUS_VENDOR_ASSIGNED,
            ]);

            ServiceAuditLog::record($request, 'vendor_assigned_offer', $admin, [
                'vendor_id' => $vendor->id,
                'vendor_name' => $vendor->name,
            ]);

            return $request->fresh();
        });
    }

    /**
     * Vendor accepts the assigned service request offer.
     */
    public function vendorAcceptAssignment(ServiceRequest $request, User $vendor): ServiceRequest
    {
        if ($request->assigned_vendor_id !== $vendor->id && ! $vendor->hasRole('admin')) {
            throw new InvalidArgumentException('Only the assigned vendor can accept this assignment.');
        }

        return DB::transaction(function () use ($request, $vendor) {
            $request->update([
                'assignment_status' => 'ACCEPTED',
                'status' => ServiceRequest::STATUS_VENDOR_ACCEPTED,
            ]);

            ServiceAuditLog::record($request, 'vendor_accepted_assignment', $vendor);

            return $request->fresh();
        });
    }

    /**
     * Vendor rejects the assigned service request offer.
     */
    public function vendorRejectAssignment(ServiceRequest $request, User $vendor, string $reason): ServiceRequest
    {
        if ($request->assigned_vendor_id !== $vendor->id && ! $vendor->hasRole('admin')) {
            throw new InvalidArgumentException('Only the assigned vendor can reject this assignment.');
        }

        return DB::transaction(function () use ($request, $vendor, $reason) {
            $request->update([
                'assignment_status' => 'REJECTED',
                'assignment_rejected_reason' => $reason,
                'assigned_vendor_id' => null,
                'status' => ServiceRequest::STATUS_TRIAGED,
            ]);

            ServiceAuditLog::record($request, 'vendor_rejected_assignment', $vendor, [
                'reason' => $reason,
            ]);

            return $request->fresh();
        });
    }

    /**
     * Vendor submits a quote with itemized line items.
     */
    public function submitQuote(ServiceRequest $request, User $vendor, array $data): ServiceQuote
    {
        if ($request->assigned_vendor_id !== $vendor->id && ! $vendor->hasRole('admin')) {
            throw new InvalidArgumentException('Only the assigned vendor can submit a quote for this job.');
        }

        return DB::transaction(function () use ($request, $vendor, $data) {
            // Supersede any existing pending quotes
            $request->quotes()->where('status', ServiceQuote::STATUS_PENDING)->update([
                'status' => ServiceQuote::STATUS_SUPERSEDED,
            ]);

            $labour = (float) ($data['labour_amount'] ?? 0);
            $materials = (float) ($data['materials_amount'] ?? 0);
            $tax = (float) ($data['tax_amount'] ?? 0);
            $other = (float) ($data['other_charges'] ?? 0);
            $total = $labour + $materials + $tax + $other;

            $quote = ServiceQuote::create([
                'service_request_id' => $request->id,
                'vendor_id' => $vendor->id,
                'labour_amount' => $labour,
                'materials_amount' => $materials,
                'tax_amount' => $tax,
                'other_charges' => $other,
                'total_amount' => $total,
                'line_items' => $data['line_items'] ?? null,
                'estimated_duration' => $data['estimated_duration'] ?? '1 Day',
                'notes' => $data['notes'] ?? null,
                'valid_until' => $data['valid_until'] ?? now()->addDays(7)->toDateString(),
                'status' => ServiceQuote::STATUS_PENDING,
            ]);

            $request->update([
                'status' => ServiceRequest::STATUS_AWAITING_APPROVAL,
            ]);

            ServiceAuditLog::record($quote, 'quote_submitted', $vendor, [
                'total_amount' => $total,
                'service_request_id' => $request->id,
            ]);

            return $quote;
        });
    }

    /**
     * Customer rejects quote with an explicit reason.
     */
    public function customerRejectQuote(ServiceQuote $quote, User $customer, string $reason): ServiceQuote
    {
        $request = $quote->serviceRequest;

        if (! $request->isAuthorizedUser($customer) && ! $customer->hasRole('admin')) {
            throw new InvalidArgumentException('You are not authorized to decline this quote.');
        }

        if ($quote->status !== ServiceQuote::STATUS_PENDING) {
            throw new InvalidArgumentException('Only pending quotes can be declined.');
        }

        return DB::transaction(function () use ($quote, $request, $customer, $reason) {
            $quote->update([
                'status' => ServiceQuote::STATUS_DECLINED,
                'rejection_reason' => $reason,
            ]);

            $request->update([
                'status' => ServiceRequest::STATUS_TRIAGED,
            ]);

            ServiceAuditLog::record($quote, 'quote_declined', $customer, [
                'reason' => $reason,
            ]);

            return $quote->fresh();
        });
    }

    /**
     * Customer approves quote. Work order is created.
     */
    public function approveQuote(ServiceQuote $quote, User $approver, ?array $auditMeta = null): WorkOrder
    {
        $request = $quote->serviceRequest;

        if (! $request->isAuthorizedUser($approver) && ! $approver->hasRole('admin')) {
            throw new InvalidArgumentException('You are not authorized to approve this quote.');
        }

        // A vendor cannot approve their own quote on behalf of customer!
        if ($quote->vendor_id === $approver->id && ! $approver->hasRole('admin')) {
            throw new InvalidArgumentException('Vendors cannot approve their own quotes on behalf of the customer.');
        }

        if ($quote->status !== ServiceQuote::STATUS_PENDING) {
            throw new InvalidArgumentException('Only pending quotes can be approved.');
        }

        return DB::transaction(function () use ($quote, $request, $approver, $auditMeta) {
            $quote->update([
                'status' => ServiceQuote::STATUS_APPROVED,
                'approved_by' => $approver->id,
                'approved_at' => now(),
                'approved_amount' => $quote->total_amount,
            ]);

            $request->update([
                'status' => ServiceRequest::STATUS_SCHEDULED,
                'scheduled_at' => now()->addDay(),
            ]);

            $workOrder = WorkOrder::create([
                'service_request_id' => $request->id,
                'service_quote_id' => $quote->id,
                'vendor_id' => $quote->vendor_id,
                'property_id' => $request->property_id,
                'status' => WorkOrder::STATUS_SCHEDULED,
                'qc_status' => WorkOrder::QC_STATUS_PENDING,
                'scheduled_at' => now()->addDay(),
                'work_notes' => 'Work order generated from approved Quote #'.$quote->quote_number,
            ]);

            ServiceAuditLog::record($quote, 'quote_approved', $approver, array_merge([
                'approved_amount' => $quote->total_amount,
                'work_order_id' => $workOrder->id,
            ], $auditMeta ?? []));

            return $workOrder;
        });
    }

    /**
     * Update Work Order Status through server-side transition state machine.
     */
    public function updateWorkOrderStatus(WorkOrder $workOrder, User $user, string $newStatus, array $metadata = []): WorkOrder
    {
        // Enforce vendor or admin authorization
        if ($workOrder->vendor_id !== $user->id && ! $user->hasRole('admin')) {
            throw new InvalidArgumentException('You are not authorized to modify this work order.');
        }

        // Define valid transitions
        $validTransitions = [
            WorkOrder::STATUS_CREATED => [WorkOrder::STATUS_SCHEDULED, WorkOrder::STATUS_CANCELLED],
            WorkOrder::STATUS_SCHEDULED => [WorkOrder::STATUS_VENDOR_EN_ROUTE, WorkOrder::STATUS_IN_PROGRESS, WorkOrder::STATUS_CANCELLED],
            WorkOrder::STATUS_VENDOR_EN_ROUTE => [WorkOrder::STATUS_IN_PROGRESS, WorkOrder::STATUS_CANCELLED],
            WorkOrder::STATUS_IN_PROGRESS => [WorkOrder::STATUS_WAITING_FOR_PARTS, WorkOrder::STATUS_COMPLETED, WorkOrder::STATUS_CANCELLED],
            WorkOrder::STATUS_WAITING_FOR_PARTS => [WorkOrder::STATUS_IN_PROGRESS, WorkOrder::STATUS_CANCELLED],
            WorkOrder::STATUS_COMPLETED => [WorkOrder::STATUS_QC_PENDING],
            WorkOrder::STATUS_QC_PENDING => [WorkOrder::STATUS_QC_APPROVED, WorkOrder::STATUS_REWORK_REQUIRED],
            WorkOrder::STATUS_QC_APPROVED => [WorkOrder::STATUS_CLOSED],
            WorkOrder::STATUS_REWORK_REQUIRED => [WorkOrder::STATUS_IN_PROGRESS, WorkOrder::STATUS_CANCELLED],
        ];

        $currentStatus = $workOrder->status;
        $allowed = $validTransitions[$currentStatus] ?? [];

        if (! in_array($newStatus, $allowed, true) && ! $user->hasRole('admin')) {
            throw new InvalidArgumentException("Invalid work order state transition from {$currentStatus} to {$newStatus}.");
        }

        return DB::transaction(function () use ($workOrder, $user, $newStatus, $metadata, $currentStatus) {
            $updates = ['status' => $newStatus];

            if ($newStatus === WorkOrder::STATUS_IN_PROGRESS && empty($workOrder->started_at)) {
                $updates['started_at'] = now();
            }
            if ($newStatus === WorkOrder::STATUS_COMPLETED) {
                $updates['completed_at'] = now();
                $updates['status'] = WorkOrder::STATUS_QC_PENDING;
            }
            if (isset($metadata['before_photos'])) {
                $updates['before_photos'] = $metadata['before_photos'];
            }
            if (isset($metadata['after_photos'])) {
                $updates['after_photos'] = $metadata['after_photos'];
            }
            if (isset($metadata['materials_used'])) {
                $updates['materials_used'] = $metadata['materials_used'];
            }
            if (isset($metadata['arrival_notes'])) {
                $updates['arrival_notes'] = $metadata['arrival_notes'];
            }
            if (isset($metadata['work_notes'])) {
                $updates['work_notes'] = $metadata['work_notes'];
            }

            $workOrder->update($updates);

            // Sync request status
            if ($newStatus === WorkOrder::STATUS_IN_PROGRESS) {
                $workOrder->serviceRequest->update(['status' => ServiceRequest::STATUS_IN_PROGRESS]);
            } elseif ($newStatus === WorkOrder::STATUS_COMPLETED || $newStatus === WorkOrder::STATUS_QC_PENDING) {
                $workOrder->serviceRequest->update(['status' => ServiceRequest::STATUS_QC_PENDING, 'completed_at' => now()]);
            } elseif ($newStatus === WorkOrder::STATUS_REWORK_REQUIRED) {
                $workOrder->serviceRequest->update(['status' => ServiceRequest::STATUS_REWORK_REQUIRED]);
            }

            ServiceAuditLog::record($workOrder, 'work_order_status_updated', $user, [
                'from_status' => $currentStatus,
                'to_status' => $workOrder->status,
            ]);

            return $workOrder->fresh();
        });
    }

    /**
     * Start work order.
     */
    public function startWorkOrder(WorkOrder $workOrder, User $vendor, ?array $beforePhotos = null, ?string $notes = null): WorkOrder
    {
        return $this->updateWorkOrderStatus($workOrder, $vendor, WorkOrder::STATUS_IN_PROGRESS, [
            'before_photos' => $beforePhotos ?? $workOrder->before_photos,
            'work_notes' => $notes ?? $workOrder->work_notes,
        ]);
    }

    /**
     * Vendor completes job and submits for Quality Control review.
     */
    public function completeWorkOrder(WorkOrder $workOrder, User $vendor, array $afterPhotos = [], ?string $notes = null, ?string $materials = null): WorkOrder
    {
        return $this->updateWorkOrderStatus($workOrder, $vendor, WorkOrder::STATUS_COMPLETED, [
            'after_photos' => $afterPhotos,
            'work_notes' => $notes ?? $workOrder->work_notes,
            'materials_used' => $materials ?? $workOrder->materials_used,
        ]);
    }

    /**
     * Quality Control Review by Housing365 Admin or Property Customer.
     */
    public function reviewQualityControl(ServiceRequest $request, string $decision, ?string $qcNotes = null, ?User $reviewer = null): ServiceRequest
    {
        return DB::transaction(function () use ($request, $decision, $qcNotes, $reviewer) {
            if ($decision === 'approve') {
                $request->update([
                    'status' => ServiceRequest::STATUS_QC_COMPLETED,
                    'qc_notes' => $qcNotes,
                    'closed_at' => now(),
                ]);

                if ($request->workOrder) {
                    $request->workOrder->update([
                        'status' => WorkOrder::STATUS_QC_APPROVED,
                        'qc_status' => WorkOrder::QC_STATUS_APPROVED,
                        'qc_approved_at' => now(),
                        'qc_notes' => $qcNotes,
                    ]);

                    // Generate invoice for approved work order
                    $this->generateInvoice($request->workOrder);
                }

                // Increment vendor stats
                if ($request->assigned_vendor_id) {
                    VendorProfile::where('user_id', $request->assigned_vendor_id)
                        ->increment('jobs_completed');
                }

                ServiceAuditLog::record($request, 'qc_approved', $reviewer, ['notes' => $qcNotes]);
            } elseif ($decision === 'rework_required') {
                $request->increment('rework_count');
                $request->update([
                    'status' => ServiceRequest::STATUS_REWORK_REQUIRED,
                    'rework_notes' => $qcNotes,
                ]);

                if ($request->workOrder) {
                    $request->workOrder->increment('rework_count');
                    $request->workOrder->update([
                        'status' => WorkOrder::STATUS_REWORK_REQUIRED,
                        'qc_status' => WorkOrder::QC_STATUS_REWORK_REQUIRED,
                        'qc_notes' => $qcNotes,
                    ]);
                }

                ServiceAuditLog::record($request, 'qc_rework_requested', $reviewer, ['notes' => $qcNotes]);
            } else {
                $request->update([
                    'status' => ServiceRequest::STATUS_DISPUTED,
                    'qc_notes' => $qcNotes,
                ]);

                if ($request->workOrder) {
                    $request->workOrder->update([
                        'qc_status' => WorkOrder::QC_STATUS_REJECTED,
                        'qc_notes' => $qcNotes,
                    ]);
                }

                ServiceAuditLog::record($request, 'qc_rejected', $reviewer, ['notes' => $qcNotes]);
            }

            return $request->fresh();
        });
    }

    /**
     * Customer confirms work resolution or requests rework.
     */
    public function customerConfirmResolution(ServiceRequest $request, User $customer, bool $resolved, ?string $feedback = null): ServiceRequest
    {
        if (! $request->isAuthorizedUser($customer) && ! $customer->hasRole('admin')) {
            throw new InvalidArgumentException('You are not authorized to confirm resolution for this request.');
        }

        return DB::transaction(function () use ($request, $customer, $resolved, $feedback) {
            if ($resolved) {
                $request->update([
                    'status' => ServiceRequest::STATUS_CLOSED,
                    'closed_at' => now(),
                    'review' => $feedback,
                ]);

                if ($request->workOrder && $request->workOrder->status === WorkOrder::STATUS_QC_APPROVED) {
                    $request->workOrder->update(['status' => WorkOrder::STATUS_CLOSED]);
                }

                ServiceAuditLog::record($request, 'customer_confirmed_resolution', $customer, [
                    'feedback' => $feedback,
                ]);
            } else {
                $request->increment('rework_count');
                $request->update([
                    'status' => ServiceRequest::STATUS_REWORK_REQUIRED,
                    'rework_notes' => $feedback,
                ]);

                if ($request->workOrder) {
                    $request->workOrder->increment('rework_count');
                    $request->workOrder->update([
                        'status' => WorkOrder::STATUS_REWORK_REQUIRED,
                        'qc_status' => WorkOrder::QC_STATUS_REWORK_REQUIRED,
                        'qc_notes' => $feedback,
                    ]);
                }

                ServiceAuditLog::record($request, 'customer_requested_rework', $customer, [
                    'feedback' => $feedback,
                ]);
            }

            return $request->fresh();
        });
    }

    /**
     * Generate Care Invoice from approved Work Order and Quote.
     */
    public function generateInvoice(WorkOrder $workOrder): ?CareInvoice
    {
        $quote = $workOrder->quote;
        $request = $workOrder->serviceRequest;

        if (! $quote) {
            return null;
        }

        // Check if invoice already exists
        $existing = CareInvoice::where('work_order_id', $workOrder->id)->first();
        if ($existing) {
            return $existing;
        }

        return DB::transaction(function () use ($workOrder, $quote, $request) {
            $subtotal = (float) $quote->labour_amount + (float) $quote->materials_amount + (float) $quote->other_charges;
            $taxAmount = (float) $quote->tax_amount;
            $totalAmount = (float) $quote->total_amount;

            $invoice = CareInvoice::create([
                'service_request_id' => $request->id,
                'work_order_id' => $workOrder->id,
                'user_id' => $request->user_id,
                'vendor_id' => $workOrder->vendor_id,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => 0.00,
                'total_amount' => $totalAmount,
                'payment_status' => CareInvoice::STATUS_PENDING,
            ]);

            $request->update(['status' => ServiceRequest::STATUS_INVOICE_GENERATED]);

            ServiceAuditLog::record($invoice, 'care_invoice_generated', null, [
                'total_amount' => $totalAmount,
                'invoice_number' => $invoice->invoice_number,
            ]);

            return $invoice;
        });
    }

    /**
     * Process invoice payment with idempotency and server-side verification.
     */
    public function processInvoicePayment(CareInvoice $invoice, User $payer, array $paymentData): CareInvoice
    {
        $request = $invoice->serviceRequest;
        if (! $request->isAuthorizedUser($payer) && ! $payer->hasRole('admin')) {
            throw new InvalidArgumentException('You are not authorized to pay this invoice.');
        }

        // Idempotency check: Already paid
        if ($invoice->payment_status === CareInvoice::STATUS_PAID) {
            return $invoice;
        }

        $idempotencyKey = $paymentData['idempotency_key'] ?? null;
        if ($idempotencyKey) {
            $existing = CareInvoice::where('idempotency_key', $idempotencyKey)
                ->where('payment_status', CareInvoice::STATUS_PAID)
                ->first();
            if ($existing) {
                return $existing;
            }
        }

        $gateway = $this->paymentGateway ?? app(CarePaymentGatewayInterface::class);

        // Server-side payment verification
        $isVerified = $gateway->verifyPayment($invoice, $paymentData);
        if (! $isVerified) {
            throw new InvalidArgumentException('Payment verification failed. Payment was not confirmed by the gateway.');
        }

        return DB::transaction(function () use ($invoice, $paymentData, $idempotencyKey, $payer) {
            $paymentRef = $paymentData['gateway_payment_id'] ?? ('PAYREF-CARE-'.strtoupper(bin2hex(random_bytes(4))));

            $invoice->update([
                'payment_status' => CareInvoice::STATUS_PAID,
                'idempotency_key' => $idempotencyKey,
                'payment_reference' => $paymentRef,
                'payment_method' => $paymentData['payment_method'] ?? 'card',
                'paid_at' => now(),
            ]);

            $invoice->serviceRequest->update([
                'status' => ServiceRequest::STATUS_PAID,
            ]);

            // Create Vendor Payout Record (with 10% platform fee)
            $this->createVendorPayout($invoice);

            ServiceAuditLog::record($invoice, 'care_invoice_paid', $payer, [
                'payment_reference' => $paymentRef,
                'total_amount' => $invoice->total_amount,
            ]);

            return $invoice->fresh();
        });
    }

    /**
     * Create Vendor Payout record from a paid Care Invoice.
     */
    public function createVendorPayout(CareInvoice $invoice): VendorPayout
    {
        $existing = VendorPayout::where('care_invoice_id', $invoice->id)->first();
        if ($existing) {
            return $existing;
        }

        return DB::transaction(function () use ($invoice) {
            $grossAmount = (float) $invoice->subtotal; // Vendor gets subtotal (labour + materials)
            $platformFeeRate = 0.10; // Configurable 10% platform commission
            $platformFee = round($grossAmount * $platformFeeRate, 2);
            $taxDeduction = 0.00;
            $netAmount = max(0, $grossAmount - $platformFee - $taxDeduction);

            $payout = VendorPayout::create([
                'work_order_id' => $invoice->work_order_id,
                'vendor_id' => $invoice->vendor_id,
                'care_invoice_id' => $invoice->id,
                'gross_amount' => $grossAmount,
                'platform_fee' => $platformFee,
                'tax_deduction' => $taxDeduction,
                'net_amount' => $netAmount,
                'status' => VendorPayout::STATUS_PENDING,
            ]);

            ServiceAuditLog::record($payout, 'vendor_payout_created', null, [
                'net_amount' => $netAmount,
                'platform_fee' => $platformFee,
            ]);

            return $payout;
        });
    }

    /**
     * Process / Settle Vendor Payout (Admin / Operations).
     */
    public function processVendorPayout(VendorPayout $payout, ?User $admin = null, ?string $reference = null): VendorPayout
    {
        if ($payout->status === VendorPayout::STATUS_PAID) {
            return $payout;
        }

        return DB::transaction(function () use ($payout, $admin, $reference) {
            $ref = $reference ?? ('PAYOUT-SETTLE-'.strtoupper(bin2hex(random_bytes(4))));

            $payout->update([
                'status' => VendorPayout::STATUS_PAID,
                'payout_reference' => $ref,
                'paid_at' => now(),
            ]);

            ServiceAuditLog::record($payout, 'vendor_payout_settled', $admin, [
                'reference' => $ref,
                'net_amount' => $payout->net_amount,
            ]);

            return $payout->fresh();
        });
    }
}
