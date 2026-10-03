# Housing365 Phase 6 Implementation Plan
**Housing365 Care Vendor Dispatch, Quotation, Work Orders, QC & Service Operations**

---

## 1. Executive Summary & Objective

The objective of Phase 6 is to build the complete operational service marketplace and dispatch engine for **Housing365 Care**. Housing365 operates as an institutional service experience owning the customer journey, triage, vendor assignment, quote approvals, work order execution, quality control, invoicing, and vendor payouts, while partner vendors perform physical service execution across 15 standard maintenance categories.

---

## 2. Inviolable Architectural Locks & Invariants

1. **Property Management Is Strictly Optional:**
   - **Path A (Leasing Only):** Self-managed owners retain ₹0 free listings, free inquiries, free viewing requests, and independent on-demand access to Housing365 Care (plumbing, electrical, cleaning, maintenance, inspections) without ever being enrolled into Property Management, without creating a `ManagementEnrollment`, and without incurring management fees.
   - **Path B (Leasing + Property Management):** Property management enrollment remains strictly opt-in, requiring explicit plan selection, clear fee disclosure, and digital acceptance of a separate Property Management Agreement.
   - Care service requests, quote approvals, work order completions, and invoice payments **MUST NEVER** activate Property Management or change `properties.is_managed`.
2. **Contact Access Paywall is Immutable:**
   - Zero exposure of `seller_phone` or `seller_email` in Care customer views, vendor views, quotes, work orders, invoices, or payouts.
   - Vendors receive only service-location details required for the job; unmasked contact details are never leaked.
   - Prime remains deprecated and disconnected.
3. **Multi-Role & Cross-User Security:**
   - Customers can only view/approve/modify their own requests and quotes.
   - Vendors can only view/accept/quote/execute jobs explicitly assigned to them.
   - Organization membership does not bypass individual role authorization.
   - Direct URL manipulation returns `403 Forbidden`.
4. **Data Integrity & Non-Destructive Migrations:**
   - Purely additive migrations. Zero dropped tables, zero dropped columns, zero deleted records.

---

## 3. Database Architecture & Additive Migrations

### Additive Migration: `2026_10_02_180000_create_phase6_housing365_care_operations_tables.php`

1. **`care_invoices`:**
   - `id`: Big increments
   - `invoice_number`: Unique string (`INV-CARE-YYYYMMDD-XXXXXX`)
   - `service_request_id`: FK to `service_requests`
   - `work_order_id`: Nullable FK to `work_orders`
   - `user_id`: FK to `users` (customer/requester)
   - `vendor_id`: Nullable FK to `users` (assigned vendor)
   - `subtotal`: Decimal(10,2)
   - `tax_amount`: Decimal(10,2)
   - `discount_amount`: Decimal(10,2)
   - `total_amount`: Decimal(10,2)
   - `payment_status`: String (`PENDING`, `PAID`, `FAILED`, `CANCELLED`)
   - `idempotency_key`: Nullable string
   - `payment_reference`: Nullable string
   - `payment_method`: Nullable string
   - `paid_at`: Nullable datetime
   - `notes`: Nullable text
   - `timestamps`

2. **`vendor_payouts`:**
   - `id`: Big increments
   - `payout_number`: Unique string (`PAYOUT-YYYYMMDD-XXXXXX`)
   - `work_order_id`: FK to `work_orders`
   - `vendor_id`: FK to `users`
   - `care_invoice_id`: Nullable FK to `care_invoices`
   - `gross_amount`: Decimal(10,2)
   - `platform_fee`: Decimal(10,2) (Housing365 commission)
   - `tax_deduction`: Decimal(10,2)
   - `net_amount`: Decimal(10,2)
   - `status`: String (`PENDING`, `APPROVED`, `PROCESSING`, `PAID`, `FAILED`, `ON_HOLD`)
   - `payout_reference`: Nullable string
   - `paid_at`: Nullable datetime
   - `failure_reason`: Nullable string
   - `notes`: Nullable text
   - `timestamps`

3. **Additive Columns on Existing Models:**
   - `service_requests`:
     - `assignment_status`: Nullable string (`OFFERED`, `ACCEPTED`, `REJECTED`)
     - `assignment_rejected_reason`: Nullable text
     - `rework_notes`: Nullable text
     - `rework_count`: Integer default 0
     - `service_location`: Nullable text
     - `access_instructions`: Nullable text
     - `pg_room_id`: Nullable FK to `pg_rooms`
     - `pg_bed_id`: Nullable FK to `pg_beds`
     - `coworking_inventory_id`: Nullable FK to `coworking_inventories`
   - `service_quotes`:
     - `line_items`: Nullable JSON
     - `rejection_reason`: Nullable text
   - `work_orders`:
     - `qc_status`: Nullable string (`PENDING`, `APPROVED`, `REWORK_REQUIRED`, `REJECTED`)
     - `qc_notes`: Nullable text
     - `rework_count`: Integer default 0
     - `materials_used`: Nullable text
     - `arrival_notes`: Nullable text

---

## 4. Provider & Gateway Abstractions

1. **`App\Contracts\CarePaymentGatewayInterface`:**
   - `initiatePayment(CareInvoice $invoice, array $options = []): array`
   - `verifyPayment(string $reference, array $payload = []): array`
   - `refundPayment(string $reference, float $amount): array`

2. **`App\Services\Payment\SandboxCarePaymentGateway`:**
   - Production guard pattern (`if (app()->environment('production')) { throw new RuntimeException('Sandbox gateway cannot run in production'); }`)
   - Server-side signature verification, idempotency checking, and deterministic test reference generation.

---

## 5. Domain Service Layer & State Transitions

### Lifecycle State Machine:
`SUBMITTED` → `TRIAGED` → `VENDOR_ASSIGNMENT_PENDING` → `VENDOR_ASSIGNED` (Offer) → `VENDOR_ACCEPTED` (or `VENDOR_REJECTED` → re-triage) → `QUOTE_REQUESTED` → `AWAITING_APPROVAL` (Quote Created) → `APPROVED` (Customer Quote Approval) → `SCHEDULED` (Work Order Created) → `VENDOR_EN_ROUTE` → `IN_PROGRESS` → `COMPLETED` → `QC_PENDING` → `QC_APPROVED` (or `REWORK_REQUIRED` → back to `IN_PROGRESS`) → `INVOICE_GENERATED` → `PAID` → `PAYOUT_PENDING` → `CLOSED`.

### Business Logic Guardrails:
- Server-side state transition validations; invalid jumps trigger `InvalidArgumentException`.
- Customer quote approval logs IP address, timestamp, and user agent.
- Vendors cannot approve their own quotes.
- Work orders cannot be completed without proof of completion.
- Quality control review by staff or customer determines approval or rework.
- Invoicing is created upon QC approval with configurable platform fee (default 10%) and tax.
- Invoices transition to `PAID` strictly through server-side payment confirmation with idempotency key.
- Payouts are generated upon invoice payment and transition to `PAID` only when processed.

---

## 6. Filament Admin Resources

1. `ServiceRequestResource`: Operations triage, vendor assignment, re-assignment, QC escalation.
2. `WorkOrderResource`: Real-time technician dispatch tracking, photo evidence review, status oversight.
3. `CareInvoiceResource`: Billing line items, payment status, idempotency references.
4. `VendorPayoutResource`: Vendor settlement ledger, platform commission audits, payout tracking.

---

## 7. Testing Strategy (`tests/Feature/Phase6Housing365CareTest.php`)

All 33 required test cases:
1. Customer can create Care request.
2. Unauthorized user cannot access another customer's request.
3. Vendor cannot access unassigned request.
4. Vendor can accept assigned request.
5. Vendor cannot accept another vendor's assignment.
6. Vendor can submit quote.
7. Customer can approve own quote.
8. Customer cannot approve another customer's quote.
9. Vendor cannot approve own quote as customer.
10. Approved quote creates valid work order.
11. Invalid work-order state transition is blocked.
12. Unauthorized user cannot modify work order.
13. QC workflow works.
14. Customer can request rework.
15. Invoice is generated correctly.
16. Payment confirmation is server-side.
17. Duplicate payment callback is idempotent.
18. Vendor payout is created only under valid conditions.
19. Care request does NOT create ManagementEnrollment.
20. Quote approval does NOT create ManagementEnrollment.
21. Work completion does NOT create ManagementEnrollment.
22. Care payment does NOT create ManagementEnrollment.
23. Self-managed owner remains self-managed.
24. Explicit management enrollment remains the only way to activate Property Management.
25. Contact Access paywall remains intact.
26. `seller_phone` remains protected.
27. `seller_email` remains protected.
28. Prime does not bypass Contact Access.
29. Multi-role authorization remains intact.
30. Organization membership cannot bypass authorization.
31. Cross-property access is blocked.
32. Direct URL manipulation is blocked.
33. Existing Phase 1–5 tests continue to pass.
